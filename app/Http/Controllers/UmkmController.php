<?php

namespace App\Http\Controllers;

use App\Services\UmkmAgentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UmkmController extends Controller
{
    public function __construct(private readonly UmkmAgentService $umkmAgent)
    {
    }

    public function index(string $feature = 'products'): View
    {
        abort_unless(in_array($feature, ['products', 'orders', 'reports'], true), 404);

        return view('umkm.index', ['feature' => $feature]);
    }

    public function storeProduct(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'umkm_profile_id' => ['required', 'exists:umkm_profiles,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'gt:0'],
            'unit' => ['required', 'string', 'max:50'],
            'stock' => ['required', 'numeric', 'gte:0'],
            'minimum_stock' => ['required', 'numeric', 'gte:0'],
            'category' => ['required', 'string', 'max:100'],
            'photo_url' => ['nullable', 'url', 'max:2048'],
            'confirmed' => ['nullable', 'boolean'],
        ], $this->messages());

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $result = $this->umkmAgent->addProduct($validator->validated());

        return response()->json($result, $result['success'] ? 201 : 202);
    }

    public function incomingOrder(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'product_id' => ['required', 'exists:products,id'],
            'order_number' => ['nullable', 'string', 'max:100', 'unique:marketplace_orders,order_number'],
            'buyer_name' => ['required', 'string', 'max:255'],
            'buyer_phone' => ['nullable', 'string', 'max:30'],
            'quantity' => ['required', 'numeric', 'gt:0'],
            'shipping_address' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ], $this->messages());

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $result = $this->umkmAgent->createIncomingOrder($validator->validated());

        return response()->json($result, $result['success'] ? 201 : 422);
    }

    public function confirmOrder(Request $request, int $order): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'confirmed' => ['required', Rule::in([true, 1, '1', 'true'])],
        ], $this->messages());

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $result = $this->umkmAgent->confirmOrder($order, true);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function shipOrder(int $order): JsonResponse
    {
        $result = $this->umkmAgent->markAsShipped($order);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function overdueAlerts(): JsonResponse
    {
        return response()->json($this->umkmAgent->alertOverdueOrders());
    }

    public function salesReport(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'umkm_profile_id' => ['required', 'exists:umkm_profiles,id'],
            'period' => ['nullable', 'string', 'max:20'],
        ], $this->messages());

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        return response()->json($this->umkmAgent->salesReport(
            (int) $validator->validated()['umkm_profile_id'],
            $validator->validated()['period'] ?? null
        ));
    }

    private function validationResponse($validator): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Data belum lengkap atau belum benar. Mohon cek bagian yang ditandai.',
            'errors' => $validator->errors(),
        ], 422);
    }

    private function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'exists' => ':attribute tidak ditemukan.',
            'unique' => ':attribute sudah dipakai.',
            'numeric' => ':attribute harus berupa angka.',
            'gt' => ':attribute harus lebih dari 0.',
            'gte' => ':attribute tidak boleh kurang dari 0.',
            'url' => ':attribute harus berupa link yang benar.',
            'in' => 'Mohon konfirmasi dulu sebelum menyimpan data penting.',
        ];
    }
}
