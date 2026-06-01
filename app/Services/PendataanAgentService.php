<?php

namespace App\Services;

use App\Models\AsetDesa;
use App\Models\UmkmProfile;
use App\Models\User;
use App\Models\VillageCommodity;
use App\Models\VillageHumanResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PendataanAgentService
{
    public const ASSET_CATEGORIES = ['tanah', 'bangunan', 'alat', 'infrastruktur'];
    public const ASSET_CONDITIONS = ['baik', 'sedang', 'rusak'];
    public const EXPORT_CATEGORIES = ['semua', 'aset', 'sdm', 'umkm', 'komoditas'];
    public const EXPORT_FORMATS = ['pdf', 'excel'];

    public function storeAsset(array $data, ?User $admin): array
    {
        $asset = AsetDesa::create([
            'created_by' => $admin?->id,
            'village_name' => $data['village_name'] ?? $admin?->village_name,
            'name' => $data['name'],
            'category' => $data['category'],
            'location_description' => $data['location_description'],
            'estimated_value' => $data['estimated_value'],
            'condition' => $data['condition'],
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'photo_url' => $data['photo_url'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        return [
            'success' => true,
            'message' => 'Aset desa berhasil disimpan.',
            'asset_id' => $asset->id,
            'summary' => [
                'nama_aset' => $asset->name,
                'kategori' => $asset->category,
                'lokasi' => $asset->location_description,
                'kondisi' => $asset->condition,
                'estimasi_nilai' => $this->formatRupiah((float) $asset->estimated_value),
                'foto' => $asset->photo_url,
                'keterangan' => $asset->notes,
            ],
        ];
    }

    public function storeUmkm(array $data, ?User $admin): array
    {
        $owner = User::where('phone', $data['phone'])->first();
        $products = collect(explode(',', $data['main_products']))
            ->map(fn (string $product) => trim($product))
            ->filter()
            ->values()
            ->all();

        $profile = UmkmProfile::create([
            'created_by' => $admin?->id,
            'owner_user_id' => $owner?->id,
            'village_name' => $data['village_name'] ?? $admin?->village_name,
            'business_name' => $data['business_name'],
            'owner_name' => $data['owner_name'],
            'phone' => $data['phone'],
            'business_type' => $data['business_type'],
            'main_products' => $products,
            'production_capacity' => $data['production_capacity'],
            'production_unit' => $data['production_unit'],
            'production_period' => $data['production_period'],
            'needs_capital' => $data['needs_capital'] === 'ya',
            'address' => $data['address'],
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
        ]);

        return [
            'success' => true,
            'message' => $owner
                ? 'Profil UMKM berhasil didaftarkan dan sudah terhubung dengan akun pemilik.'
                : 'Profil UMKM berhasil didaftarkan. Pemilik belum punya akun, Anda dapat membuat akun dengan role umkm_petani.',
            'umkm_id' => $profile->id,
            'owner_has_account' => (bool) $owner,
            'offer_create_account' => ! $owner,
            'recommended_role' => $owner ? null : 'umkm_petani',
            'summary' => [
                'nama_usaha' => $profile->business_name,
                'pemilik' => $profile->owner_name,
                'no_hp' => $profile->phone,
                'jenis_usaha' => $profile->business_type,
                'produk_utama' => $profile->main_products,
                'kapasitas_produksi' => $profile->production_capacity.' '.$profile->production_unit.' per '.$profile->production_period,
                'butuh_modal' => $profile->needs_capital ? 'ya' : 'tidak',
                'alamat' => $profile->address,
            ],
        ];
    }

    public function exportPotentialData(array $filters): array
    {
        $category = $filters['category'];
        $format = $filters['format'];
        $villageName = $filters['village_name'];
        $period = $filters['period'];
        $tables = $this->collectExportData($category, $villageName);
        $directory = public_path('exports');

        File::ensureDirectoryExists($directory);

        $baseName = Str::slug($villageName.'-'.$category.'-'.$period.'-'.now()->format('YmdHis'));
        $path = $format === 'excel'
            ? $directory.'/'.$baseName.'.xls'
            : $directory.'/'.$baseName.'.pdf';

        if ($format === 'excel') {
            File::put($path, $this->buildExcelHtml($villageName, $category, $period, $tables));
        } else {
            File::put($path, $this->buildSimplePdf($villageName, $category, $period, $tables));
        }

        return [
            'success' => true,
            'message' => 'Export data berhasil dibuat.',
            'download_url' => url('exports/'.basename($path)),
            'summary' => [
                'desa' => $villageName,
                'kategori' => $category,
                'format' => $format,
                'periode' => $period,
                'jumlah_data' => collect($tables)->sum(fn (array $table) => count($table['rows'])),
            ],
        ];
    }

    public function formatRupiah(float $value): string
    {
        return 'Rp'.number_format($value, 0, ',', '.');
    }

    private function collectExportData(string $category, string $villageName): array
    {
        $tables = [];

        if (in_array($category, ['semua', 'aset'], true)) {
            $tables[] = [
                'title' => 'Aset Desa',
                'headers' => ['ID', 'Nama', 'Kategori', 'Lokasi', 'Kondisi', 'Estimasi Nilai'],
                'rows' => AsetDesa::query()
                    ->when($villageName, fn ($query) => $query->where(function ($inner) use ($villageName) {
                        $inner->where('village_name', $villageName)->orWhereNull('village_name');
                    }))
                    ->latest()
                    ->get()
                    ->map(fn (AsetDesa $asset) => [
                        $asset->id,
                        $asset->name,
                        $asset->category,
                        $asset->location_description,
                        $asset->condition,
                        $this->formatRupiah((float) $asset->estimated_value),
                    ])->all(),
            ];
        }

        if (in_array($category, ['semua', 'umkm'], true)) {
            $tables[] = [
                'title' => 'UMKM',
                'headers' => ['ID', 'Nama Usaha', 'Pemilik', 'No. HP', 'Produk', 'Kapasitas'],
                'rows' => UmkmProfile::query()
                    ->when($villageName, fn ($query) => $query->where(function ($inner) use ($villageName) {
                        $inner->where('village_name', $villageName)->orWhereNull('village_name');
                    }))
                    ->latest()
                    ->get()
                    ->map(fn (UmkmProfile $profile) => [
                        $profile->id,
                        $profile->business_name,
                        $profile->owner_name,
                        $profile->phone,
                        implode(', ', $profile->main_products ?? []),
                        $profile->production_capacity.' '.$profile->production_unit.' per '.$profile->production_period,
                    ])->all(),
            ];
        }

        if (in_array($category, ['semua', 'sdm'], true)) {
            $tables[] = [
                'title' => 'SDM',
                'headers' => ['ID', 'Nama', 'Kategori', 'No. HP', 'Alamat'],
                'rows' => VillageHumanResource::query()
                    ->when($villageName, fn ($query) => $query->where(function ($inner) use ($villageName) {
                        $inner->where('village_name', $villageName)->orWhereNull('village_name');
                    }))
                    ->latest()
                    ->get()
                    ->map(fn (VillageHumanResource $resource) => [
                        $resource->id,
                        $resource->name,
                        $resource->category,
                        $resource->phone,
                        $resource->address,
                    ])->all(),
            ];
        }

        if (in_array($category, ['semua', 'komoditas'], true)) {
            $tables[] = [
                'title' => 'Komoditas',
                'headers' => ['ID', 'Jenis', 'Volume per Bulan', 'Harga', 'Lokasi'],
                'rows' => VillageCommodity::query()
                    ->when($villageName, fn ($query) => $query->where(function ($inner) use ($villageName) {
                        $inner->where('village_name', $villageName)->orWhereNull('village_name');
                    }))
                    ->latest()
                    ->get()
                    ->map(fn (VillageCommodity $commodity) => [
                        $commodity->id,
                        $commodity->name,
                        $commodity->monthly_production_volume.' '.$commodity->unit,
                        $this->formatRupiah((float) $commodity->price),
                        $commodity->location_description,
                    ])->all(),
            ];
        }

        return $tables;
    }

    private function buildExcelHtml(string $villageName, string $category, string $period, array $tables): string
    {
        $html = '<html><head><meta charset="UTF-8"></head><body>';
        $html .= '<h2>Data Potensi Desa</h2>';
        $html .= '<p>Desa: '.e($villageName).'</p>';
        $html .= '<p>Tanggal export: '.e(now()->format('d/m/Y H:i')).'</p>';
        $html .= '<p>Kategori: '.e($category).'</p>';
        $html .= '<p>Periode: '.e($period).'</p>';

        foreach ($tables as $table) {
            $html .= '<h3>'.e($table['title']).'</h3><table border="1" cellpadding="6" cellspacing="0"><thead><tr>';
            foreach ($table['headers'] as $header) {
                $html .= '<th>'.e($header).'</th>';
            }
            $html .= '</tr></thead><tbody>';
            foreach ($table['rows'] as $row) {
                $html .= '<tr>';
                foreach ($row as $cell) {
                    $html .= '<td>'.e((string) $cell).'</td>';
                }
                $html .= '</tr>';
            }
            if (! count($table['rows'])) {
                $html .= '<tr><td colspan="'.count($table['headers']).'">Belum ada data.</td></tr>';
            }
            $html .= '</tbody></table>';
        }

        return $html.'</body></html>';
    }

    private function buildSimplePdf(string $villageName, string $category, string $period, array $tables): string
    {
        $lines = [
            'Data Potensi Desa',
            'Desa: '.$villageName,
            'Tanggal export: '.now()->format('d/m/Y H:i'),
            'Kategori: '.$category,
            'Periode: '.$period,
            '',
        ];

        foreach ($tables as $table) {
            $lines[] = $table['title'];
            $lines[] = implode(' | ', $table['headers']);
            foreach ($table['rows'] as $row) {
                $lines[] = implode(' | ', array_map(fn ($cell) => (string) $cell, $row));
            }
            if (! count($table['rows'])) {
                $lines[] = 'Belum ada data.';
            }
            $lines[] = '';
        }

        return $this->minimalPdf($lines);
    }

    private function minimalPdf(array $lines): string
    {
        $content = "BT\n/F1 11 Tf\n50 790 Td\n";
        foreach ($lines as $index => $line) {
            if ($index > 0) {
                $content .= "0 -16 Td\n";
            }
            $content .= '('.$this->escapePdfText(Str::limit($line, 110, '')).") Tj\n";
        }
        $content .= 'ET';

        $objects = [
            "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n",
            "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n",
            "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>\nendobj\n",
            "4 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n",
            "5 0 obj\n<< /Length ".strlen($content)." >>\nstream\n".$content."\nendstream\nendobj\n",
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $object) {
            $offsets[] = strlen($pdf);
            $pdf .= $object;
        }
        $xref = strlen($pdf);
        $pdf .= "xref\n0 ".(count($objects) + 1)."\n0000000000 65535 f \n";
        foreach (array_slice($offsets, 1) as $offset) {
            $pdf .= sprintf("%010d 00000 n \n", $offset);
        }
        $pdf .= "trailer\n<< /Size ".(count($objects) + 1)." /Root 1 0 R >>\nstartxref\n".$xref."\n%%EOF";

        return $pdf;
    }

    private function escapePdfText(string $text): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
    }
}
