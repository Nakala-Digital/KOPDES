<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\PinResetCode;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthAgentService
{
    public const ROLES = [
        'super_admin',
        'admin_desa',
        'pengurus_kopdes',
        'pengurus_bumdes',
        'umkm_petani',
        'operator_mbg',
        'warga',
        'pemda_viewer',
    ];

    private const ROLE_LEVELS = [
        'super_admin' => 100,
        'admin_desa' => 80,
        'pengurus_kopdes' => 60,
        'pengurus_bumdes' => 60,
        'operator_mbg' => 40,
        'umkm_petani' => 20,
        'warga' => 10,
        'pemda_viewer' => 10,
    ];

    public function authenticate(string $identifier, string $pin, bool $remember, Request $request): array
    {
        $user = User::with('role')
            ->where('phone', $identifier)
            ->orWhere('nik', $identifier)
            ->first();

        if (! $user) {
            $this->audit(null, 'login_identifier_not_found', $identifier, $request);

            return $this->response(false, null, null, 'Identifier tidak ditemukan. Periksa kembali No. HP atau NIK Anda.');
        }

        if ($user->status === 'inactive') {
            $this->audit($user, 'login_inactive_account', $identifier, $request);

            return $this->response(false, $user->role?->slug, null, 'Akun Anda nonaktif. Silakan hubungi admin desa.');
        }

        if ($this->isLocked($user)) {
            $this->audit($user, 'login_blocked_account', $identifier, $request);

            return $this->response(false, $user->role?->slug, null, 'Akun sedang diblokir sementara karena terlalu banyak percobaan gagal. Coba lagi setelah 15 menit.');
        }

        if (! $user->pin_hash || ! Hash::check($pin, $user->pin_hash)) {
            $this->recordFailedAttempt($user);
            $this->audit($user, 'login_wrong_pin', $identifier, $request, [
                'failed_login_attempts' => $user->failed_login_attempts,
                'locked_until' => $user->locked_until?->toISOString(),
            ]);

            if ($this->isLocked($user)) {
                return $this->response(false, $user->role?->slug, null, 'PIN salah 3 kali. Akun diblokir sementara selama 15 menit.');
            }

            return $this->response(false, $user->role?->slug, null, 'PIN salah. Silakan coba lagi dengan PIN yang benar.');
        }

        $token = $this->generateJwt($user);
        $refreshToken = $remember ? Str::random(80) : null;

        $user->forceFill([
            'failed_login_attempts' => 0,
            'locked_until' => null,
            'last_login_at' => now(),
            'remember_refresh_token' => $refreshToken ? Crypt::encryptString($refreshToken) : null,
            'remember_refresh_token_expires_at' => $refreshToken ? now()->addDays(30) : null,
        ])->save();

        $this->audit($user, 'login_success', $identifier, $request, [
            'role' => $user->role?->slug,
            'remember' => $remember,
        ]);

        return $this->response(true, $user->role?->slug, $token, 'Login berhasil. Anda akan diarahkan ke dashboard.');
    }

    public function createManagedAccount(User $admin, array $data, Request $request): array
    {
        if (! $this->canCreateRole($admin->role?->slug, $data['role'])) {
            $this->audit($admin, 'create_user_forbidden_role', $data['phone'], $request, ['target_role' => $data['role']]);

            return ['success' => false, 'message' => 'Admin tidak memiliki izin untuk membuat akun dengan role tersebut.'];
        }

        if (User::where('phone', $data['phone'])->exists()) {
            return ['success' => false, 'message' => 'No. HP sudah terdaftar. Gunakan nomor lain atau lakukan reset PIN.'];
        }

        $temporaryPin = (string) random_int(100000, 999999);
        $role = Role::firstOrCreate(
            ['slug' => $data['role']],
            ['name' => Str::headline($data['role']), 'dashboard_type' => $data['role'], 'dashboard_label' => Str::headline($data['role'])]
        );

        $user = DB::transaction(function () use ($data, $role, $temporaryPin) {
            return User::create([
                'name' => $data['name'],
                'email' => $data['phone'].'@kopdes.local',
                'phone' => $data['phone'],
                'role_id' => $role->id,
                'village_name' => $data['village_name'],
                'password' => Hash::make(Str::random(32)),
                'pin_hash' => Hash::make($temporaryPin),
                'status' => 'active',
                'must_change_pin' => true,
            ]);
        });

        $this->audit($admin, 'create_user_success', $data['phone'], $request, [
            'created_user_id' => $user->id,
            'target_role' => $data['role'],
            'sms_status' => 'queued',
        ]);

        return [
            'success' => true,
            'message' => 'Akun berhasil dibuat. PIN sementara telah disiapkan untuk dikirim melalui SMS. Pengguna wajib mengganti PIN saat login pertama.',
            'user_id' => $user->id,
            'role' => $data['role'],
        ];
    }

    public function requestPinReset(string $phone, Request $request): array
    {
        $user = User::where('phone', $phone)->first();

        if (! $user) {
            $this->audit(null, 'pin_reset_phone_not_found', $phone, $request);

            return ['success' => false, 'message' => 'No. HP tidak terdaftar di sistem.'];
        }

        $code = (string) random_int(100000, 999999);

        PinResetCode::create([
            'user_id' => $user->id,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(10),
        ]);

        $this->audit($user, 'pin_reset_code_requested', $phone, $request, ['sms_status' => 'queued']);

        return ['success' => true, 'message' => 'Kode verifikasi telah disiapkan untuk dikirim melalui SMS dan berlaku selama 10 menit.'];
    }

    public function verifyPinResetCode(string $phone, string $code, Request $request): array
    {
        $user = User::where('phone', $phone)->first();

        if (! $user) {
            return ['success' => false, 'message' => 'No. HP tidak terdaftar di sistem.'];
        }

        $resetCode = PinResetCode::where('user_id', $user->id)
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (! $resetCode || ! Hash::check($code, $resetCode->code_hash)) {
            $this->audit($user, 'pin_reset_code_invalid', $phone, $request);

            return ['success' => false, 'message' => 'Kode verifikasi salah atau sudah kedaluwarsa.'];
        }

        $resetCode->forceFill(['verified_at' => now()])->save();
        $this->audit($user, 'pin_reset_code_verified', $phone, $request);

        return ['success' => true, 'message' => 'Kode berhasil diverifikasi. Silakan buat PIN baru.'];
    }

    public function completePinReset(string $phone, string $code, string $newPin, Request $request): array
    {
        $user = User::where('phone', $phone)->first();

        if (! $user) {
            return ['success' => false, 'message' => 'No. HP tidak terdaftar di sistem.'];
        }

        $resetCode = PinResetCode::where('user_id', $user->id)
            ->whereNotNull('verified_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (! $resetCode || ! Hash::check($code, $resetCode->code_hash)) {
            return ['success' => false, 'message' => 'Kode verifikasi belum valid atau sudah kedaluwarsa.'];
        }

        DB::transaction(function () use ($user, $newPin) {
            $user->forceFill([
                'pin_hash' => Hash::make($newPin),
                'failed_login_attempts' => 0,
                'locked_until' => null,
                'remember_refresh_token' => null,
                'remember_refresh_token_expires_at' => null,
                'must_change_pin' => false,
            ])->save();

            DB::table('sessions')->where('user_id', $user->id)->delete();
        });

        $this->audit($user, 'pin_reset_completed', $phone, $request, ['sessions_invalidated' => true]);

        return ['success' => true, 'message' => 'PIN berhasil diperbarui. Semua sesi aktif telah dinonaktifkan.'];
    }

    private function isLocked(User $user): bool
    {
        return $user->status === 'blocked' || ($user->locked_until && $user->locked_until->isFuture());
    }

    private function recordFailedAttempt(User $user): void
    {
        $attempts = min(3, $user->failed_login_attempts + 1);

        $user->forceFill([
            'failed_login_attempts' => $attempts,
            'locked_until' => $attempts >= 3 ? now()->addMinutes(15) : null,
        ])->save();

        $user->refresh();
    }

    private function generateJwt(User $user): string
    {
        $now = now();
        $payload = [
            'iss' => config('app.url'),
            'sub' => (string) $user->id,
            'role' => $user->role?->slug,
            'iat' => $now->timestamp,
            'exp' => $now->copy()->addHours(8)->timestamp,
            'jti' => (string) Str::uuid(),
        ];

        $header = ['typ' => 'JWT', 'alg' => 'HS256'];
        $segments = [
            $this->base64UrlEncode(json_encode($header, JSON_THROW_ON_ERROR)),
            $this->base64UrlEncode(json_encode($payload, JSON_THROW_ON_ERROR)),
        ];

        $signature = hash_hmac('sha256', implode('.', $segments), config('app.key'), true);
        $segments[] = $this->base64UrlEncode($signature);

        return implode('.', $segments);
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function canCreateRole(?string $adminRole, string $targetRole): bool
    {
        if (! isset(self::ROLE_LEVELS[$adminRole], self::ROLE_LEVELS[$targetRole])) {
            return false;
        }

        return self::ROLE_LEVELS[$adminRole] > self::ROLE_LEVELS[$targetRole];
    }

    private function response(bool $success, ?string $role, ?string $token, string $message): array
    {
        return [
            'success' => $success,
            'role' => $role,
            'redirect_to' => $role ? '/dashboard/'.$role : '/dashboard',
            'token' => $token,
            'message' => $message,
        ];
    }

    private function audit(?User $user, string $event, ?string $identifier, Request $request, array $metadata = []): void
    {
        AuditLog::create([
            'user_id' => $user?->id,
            'event' => $event,
            'identifier' => $identifier,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => $metadata,
        ]);
    }
}
