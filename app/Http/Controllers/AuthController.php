<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthAgentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(private readonly AuthAgentService $authAgent)
    {
    }

    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function register(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'identifier' => ['required', 'string', 'max:32'],
            'pin' => ['required', 'string', 'min:4', 'max:12'],
        ]);

        $result = $this->authAgent->authenticate(
            $data['identifier'],
            $data['pin'],
            $request->boolean('remember'),
            $request
        );

        if ($request->expectsJson()) {
            return response()->json($result, $result['success'] ? 200 : 422);
        }

        if (! $result['success']) {
            return back()
                ->withErrors(['identifier' => $result['message']])
                ->withInput($request->only('identifier'));
        }

        $user = User::where('phone', $data['identifier'])->orWhere('nik', $data['identifier'])->firstOrFail();
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended($result['redirect_to']);
    }

    public function storeRegister(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30', 'unique:users,phone'],
            'nik' => ['nullable', 'string', 'max:32', 'unique:users,nik'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $data['pin_hash'] = Hash::make($data['password']);
        $user = User::create($data);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function createManagedAccount(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'role' => ['required', 'string', 'in:'.implode(',', AuthAgentService::ROLES)],
            'village_name' => ['required', 'string', 'max:255'],
        ]);

        $result = $this->authAgent->createManagedAccount($request->user(), $data, $request);

        return response()->json($result, $result['success'] ? 201 : 422);
    }

    public function requestPinReset(Request $request): JsonResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
        ]);

        $result = $this->authAgent->requestPinReset($data['phone'], $request);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function verifyPinReset(Request $request): JsonResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
            'code' => ['required', 'string', 'size:6'],
        ]);

        $result = $this->authAgent->verifyPinResetCode($data['phone'], $data['code'], $request);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function completePinReset(Request $request): JsonResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
            'code' => ['required', 'string', 'size:6'],
            'pin' => ['required', 'string', 'min:4', 'max:12', 'confirmed'],
        ]);

        $result = $this->authAgent->completePinReset($data['phone'], $data['code'], $data['pin'], $request);

        return response()->json($result, $result['success'] ? 200 : 422);
    }
}
