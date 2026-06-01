<?php

namespace App\Http\Controllers;

use App\Services\KopdesAgentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KopdesController extends Controller
{
    public function __construct(private readonly KopdesAgentService $kopdesAgent)
    {
    }

    public function index(string $feature = 'members'): View
    {
        abort_unless(in_array($feature, ['members', 'savings', 'loans', 'reports'], true), 404);

        return view('kopdes.index', ['feature' => $feature]);
    }

    public function storeMember(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'member_number' => ['nullable', 'string', 'max:50', 'unique:koperasi_members,member_number'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'nik' => ['nullable', 'string', 'max:32'],
            'village_name' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'status' => ['nullable', Rule::in(KopdesAgentService::MEMBER_STATUSES)],
            'joined_at' => ['nullable', 'date'],
        ], $this->messages());

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        return response()->json($this->kopdesAgent->createMember($validator->validated(), $request->user()), 201);
    }

    public function storeSaving(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'member_id' => ['required', 'exists:koperasi_members,id'],
            'type' => ['required', Rule::in(KopdesAgentService::SAVING_TYPES)],
            'amount' => ['required', 'numeric', 'gt:0'],
            'paid_at' => ['nullable', 'date'],
            'period' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ], $this->messages());

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $result = $this->kopdesAgent->recordSaving($validator->validated(), $request->user());

        return response()->json($result, $result['success'] ? 201 : 422);
    }

    public function evaluateLoan(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'member_id' => ['required', 'exists:koperasi_members,id'],
            'principal_amount' => ['required', 'numeric', 'gt:0'],
        ], $this->messages());

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        return response()->json($this->kopdesAgent->evaluateLoan(
            (int) $validator->validated()['member_id'],
            (float) $validator->validated()['principal_amount']
        ));
    }

    public function storeLoan(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'member_id' => ['required', 'exists:koperasi_members,id'],
            'principal_amount' => ['required', 'numeric', 'gt:0'],
            'tenor_months' => ['required', 'integer', 'min:1', 'max:60'],
            'interest_rate' => ['nullable', 'numeric', 'min:0'],
            'due_start_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ], $this->messages());

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $result = $this->kopdesAgent->createLoan($validator->validated(), $request->user());

        return response()->json($result, $result['success'] ? 201 : 422);
    }

    public function payInstallment(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'installment_id' => ['required', 'exists:koperasi_installments,id'],
            'amount' => ['required', 'numeric', 'gt:0'],
        ], $this->messages());

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        return response()->json($this->kopdesAgent->payInstallment($validator->validated()));
    }

    public function report(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'village_name' => ['nullable', 'string', 'max:255'],
        ], $this->messages());

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        return response()->json($this->kopdesAgent->financialReport($validator->validated()['village_name'] ?? null));
    }

    private function validationResponse($validator): JsonResponse
    {
        $fields = collect($validator->errors()->messages())->keys()->values()->all();

        return response()->json([
            'success' => false,
            'message' => 'Data belum lengkap atau belum sesuai. Mohon periksa: '.implode(', ', $fields).'.',
            'errors' => $validator->errors(),
        ], 422);
    }

    private function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'unique' => ':attribute sudah digunakan.',
            'exists' => ':attribute tidak ditemukan.',
            'in' => ':attribute tidak sesuai pilihan yang tersedia.',
            'numeric' => ':attribute harus berupa angka.',
            'integer' => ':attribute harus berupa angka bulat.',
            'gt' => ':attribute harus lebih dari Rp 0.',
            'min' => ':attribute nilainya terlalu kecil.',
            'max' => ':attribute nilainya terlalu besar.',
            'date' => ':attribute harus berupa tanggal yang benar.',
        ];
    }
}
