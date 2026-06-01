<?php

namespace App\Http\Controllers;

use App\Services\MbgSupplyChainAgentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MbgController extends Controller
{
    public function __construct(private readonly MbgSupplyChainAgentService $mbgAgent)
    {
    }

    public function index(string $feature = 'orders'): View
    {
        abort_unless(in_array($feature, ['orders', 'suppliers', 'distributions', 'reports'], true), 404);

        return view('mbg.index', ['feature' => $feature]);
    }

    public function storeOrder(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'order_number' => ['nullable', 'string', 'max:100', 'unique:mbg_orders,order_number'],
            'village_name' => ['required', 'string', 'max:255'],
            'beneficiary_name' => ['required', 'string', 'max:255'],
            'target_portions' => ['required', 'integer', 'gt:0'],
            'distribution_date' => ['nullable', 'date'],
        ], $this->messages());

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        return response()->json($this->mbgAgent->createOrder($validator->validated()), 201);
    }

    public function notifySuppliers(Request $request, int $order): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'suppliers' => ['required', 'array', 'min:1'],
            'suppliers.*.supplier_name' => ['required', 'string', 'max:255'],
            'suppliers.*.supplier_type' => ['nullable', Rule::in(MbgSupplyChainAgentService::SUPPLIER_TYPES)],
            'suppliers.*.product_name' => ['required', 'string', 'max:255'],
            'suppliers.*.quantity' => ['required', 'numeric', 'gt:0'],
            'suppliers.*.unit' => ['required', 'string', 'max:50'],
            'suppliers.*.delivery_date' => ['required', 'date'],
            'suppliers.*.estimated_unit_price' => ['nullable', 'numeric', 'gte:0'],
        ], $this->messages());

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        return response()->json($this->mbgAgent->notifySuppliers($order, $validator->validated()['suppliers']));
    }

    public function confirmSupplier(Request $request, int $confirmation): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'can_fulfill' => ['required', 'boolean'],
        ], $this->messages());

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        return response()->json($this->mbgAgent->confirmSupplier($confirmation, (bool) $validator->validated()['can_fulfill']));
    }

    public function failSupplier(Request $request, int $confirmation): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'reason' => ['required', 'string'],
        ], $this->messages());

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        return response()->json($this->mbgAgent->markSupplierFailed($confirmation, $validator->validated()['reason']));
    }

    public function processDeadlines(): JsonResponse
    {
        return response()->json($this->mbgAgent->processSupplierDeadlines());
    }

    public function recordDistribution(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'mbg_order_id' => ['required', 'exists:mbg_orders,id'],
            'school_name' => ['required', 'string', 'max:255'],
            'target_portions' => ['required', 'integer', 'gte:0'],
            'realized_portions' => ['required', 'integer', 'gte:0'],
            'distributed_at' => ['required', 'date'],
            'status' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ], $this->messages());

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        return response()->json($this->mbgAgent->recordDistribution($validator->validated()), 201);
    }

    public function report(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'village_name' => ['required', 'string', 'max:255'],
            'month' => ['required', 'integer', 'between:1,12'],
            'year' => ['required', 'integer', 'min:2020'],
        ], $this->messages());

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $validator->validated();

        return response()->json($this->mbgAgent->monthlyReport($data['village_name'], (int) $data['month'], (int) $data['year']));
    }

    private function validationResponse($validator): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Data MBG belum lengkap atau belum sesuai. Mohon cek kembali.',
            'errors' => $validator->errors(),
        ], 422);
    }

    private function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'array' => ':attribute harus berupa daftar.',
            'min' => ':attribute nilainya terlalu kecil.',
            'max' => ':attribute nilainya terlalu besar.',
            'gt' => ':attribute harus lebih dari 0.',
            'gte' => ':attribute tidak boleh kurang dari 0.',
            'integer' => ':attribute harus berupa angka bulat.',
            'numeric' => ':attribute harus berupa angka.',
            'date' => ':attribute harus berupa tanggal yang benar.',
            'exists' => ':attribute tidak ditemukan.',
            'unique' => ':attribute sudah dipakai.',
            'in' => ':attribute tidak sesuai pilihan.',
            'boolean' => ':attribute harus ya atau tidak.',
            'between' => ':attribute tidak sesuai rentang.',
        ];
    }
}
