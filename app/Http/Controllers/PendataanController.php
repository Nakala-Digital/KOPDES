<?php

namespace App\Http\Controllers;

use App\Services\PendataanAgentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PendataanController extends Controller
{
    public function __construct(private readonly PendataanAgentService $pendataanAgent)
    {
    }

    public function index(string $feature = 'aset'): View
    {
        abort_unless(in_array($feature, ['aset', 'umkm', 'export'], true), 404);

        return view('pendataan.index', ['feature' => $feature]);
    }

    public function storeAsset(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(PendataanAgentService::ASSET_CATEGORIES)],
            'location_description' => ['required', 'string', 'max:255'],
            'condition' => ['required', Rule::in(PendataanAgentService::ASSET_CONDITIONS)],
            'estimated_value' => ['required', 'numeric', 'min:0'],
            'photo_url' => ['nullable', 'url', 'max:2048'],
            'notes' => ['nullable', 'string'],
            'village_name' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ], $this->messages());

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        return response()->json($this->pendataanAgent->storeAsset($validator->validated(), $request->user()), 201);
    }

    public function storeUmkm(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'business_name' => ['required', 'string', 'max:255'],
            'owner_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'business_type' => ['required', 'string', 'max:255'],
            'main_products' => ['required', 'string', 'max:1000'],
            'production_capacity' => ['required', 'numeric', 'gt:0'],
            'production_unit' => ['required', 'string', 'max:50'],
            'production_period' => ['required', 'string', 'max:50'],
            'needs_capital' => ['required', Rule::in(['ya', 'tidak'])],
            'address' => ['required', 'string'],
            'village_name' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ], $this->messages());

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        return response()->json($this->pendataanAgent->storeUmkm($validator->validated(), $request->user()), 201);
    }

    public function export(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'village_name' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(PendataanAgentService::EXPORT_CATEGORIES)],
            'format' => ['required', Rule::in(PendataanAgentService::EXPORT_FORMATS)],
            'period' => ['required', 'string', 'max:100'],
        ], $this->messages());

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        return response()->json($this->pendataanAgent->exportPotentialData($validator->validated()));
    }

    private function validationResponse($validator): JsonResponse
    {
        $missingFields = collect($validator->errors()->messages())
            ->keys()
            ->values()
            ->all();

        return response()->json([
            'success' => false,
            'message' => 'Data belum lengkap atau belum sesuai. Mohon lengkapi: '.implode(', ', $missingFields).'.',
            'missing_fields' => $missingFields,
            'errors' => $validator->errors(),
        ], 422);
    }

    private function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'in' => ':attribute tidak sesuai pilihan yang tersedia.',
            'numeric' => ':attribute harus berupa angka.',
            'gt' => ':attribute harus lebih dari 0.',
            'min' => ':attribute tidak boleh kurang dari 0.',
            'between' => ':attribute tidak sesuai format koordinat yang benar.',
            'url' => ':attribute harus berupa alamat URL yang benar.',
        ];
    }
}
