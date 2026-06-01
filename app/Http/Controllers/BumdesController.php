<?php

namespace App\Http\Controllers;

use App\Services\BumdesAgentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BumdesController extends Controller
{
    public function __construct(private readonly BumdesAgentService $bumdesAgent)
    {
    }

    public function index(string $feature = 'units'): View
    {
        abort_unless(in_array($feature, ['units', 'transactions', 'reversal', 'consolidation', 'annual'], true), 404);

        return view('bumdes.index', ['feature' => $feature]);
    }

    public function storeUnit(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(BumdesAgentService::UNIT_CATEGORIES)],
            'village_name' => ['nullable', 'string', 'max:255'],
            'pades_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
            'notes' => ['nullable', 'string'],
        ], $this->messages());

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        return response()->json($this->bumdesAgent->createUnit($validator->validated(), $request->user()), 201);
    }

    public function storeTransaction(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'unit_id' => ['required', 'exists:bumdes_units,id'],
            'transaction_number' => ['nullable', 'string', 'max:100', 'unique:bumdes_unit_transactions,transaction_number'],
            'type' => ['required', Rule::in(BumdesAgentService::TRANSACTION_TYPES)],
            'category' => ['nullable', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'transaction_date' => ['nullable', 'date'],
            'description' => ['required', 'string', 'max:255'],
        ], $this->messages());

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        return response()->json($this->bumdesAgent->recordTransaction($validator->validated(), $request->user()), 201);
    }

    public function reverseTransaction(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => ['required', 'exists:bumdes_unit_transactions,id'],
            'reason' => ['required', 'string', 'max:255'],
        ], $this->messages());

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $validator->validated();
        $result = $this->bumdesAgent->reverseTransaction((int) $data['transaction_id'], $data['reason'], $request->user());

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function consolidatedReport(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'village_name' => ['nullable', 'string', 'max:255'],
            'period' => ['nullable', 'date_format:Y-m'],
        ], $this->messages());

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $validator->validated();

        return response()->json($this->bumdesAgent->consolidatedReport($data['village_name'] ?? null, $data['period'] ?? null));
    }

    public function annualReport(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'village_name' => ['required', 'string', 'max:255'],
            'year' => ['required', 'integer', 'min:2020'],
        ], $this->messages());

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $validator->validated();

        return response()->json($this->bumdesAgent->annualReport($data['village_name'], (int) $data['year']));
    }

    public function unitFinancialReport(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'bumdes_name' => ['required', 'string', 'max:255'],
            'unit_name' => ['required', 'string', 'max:255'],
            'month' => ['required', 'integer', 'between:1,12'],
            'year' => ['required', 'integer', 'min:2020'],
            'pades_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ], $this->messages());

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $data = $validator->validated();

        return response()->json($this->bumdesAgent->unitFinancialReport(
            $data['bumdes_name'],
            $data['unit_name'],
            (int) $data['month'],
            (int) $data['year'],
            isset($data['pades_percentage']) ? (float) $data['pades_percentage'] : null
        ));
    }

    private function validationResponse($validator): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Data BUMDes belum lengkap atau belum sesuai.',
            'errors' => $validator->errors(),
        ], 422);
    }

    private function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'exists' => ':attribute tidak ditemukan.',
            'unique' => ':attribute sudah dipakai.',
            'in' => ':attribute tidak sesuai pilihan.',
            'numeric' => ':attribute harus berupa angka.',
            'integer' => ':attribute harus berupa angka bulat.',
            'gt' => ':attribute harus lebih dari 0.',
            'min' => ':attribute nilainya terlalu kecil.',
            'max' => ':attribute nilainya terlalu besar.',
            'date' => ':attribute harus berupa tanggal yang benar.',
            'date_format' => ':attribute harus memakai format YYYY-MM.',
        ];
    }
}
