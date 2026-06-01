<?php

namespace App\Http\Controllers;

use App\Services\AuthAgentService;
use App\Services\DashboardAgentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardAgentService $dashboardAgent)
    {
    }

    public function index(Request $request, ?string $role = null): View
    {
        abort_unless($role === null || in_array($role, AuthAgentService::ROLES, true), 404);

        $dashboard = $this->dashboardAgent->load(
            $request->user(),
            $request->query('village_name'),
            $request->query('date') ? Carbon::parse($request->query('date')) : null
        );

        return view('dashboard', [
            'dashboardRole' => $role,
            'dashboard' => $dashboard,
        ]);
    }

    public function data(Request $request): JsonResponse
    {
        return response()->json($this->dashboardAgent->load(
            $request->user(),
            $request->query('village_name'),
            $request->query('date') ? Carbon::parse($request->query('date')) : null
        ));
    }

    public function villageReport(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'village_name' => ['required', 'string', 'max:255'],
            'month' => ['required', 'integer', 'between:1,12'],
            'year' => ['required', 'integer', 'min:2020'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data laporan belum lengkap.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        return response()->json($this->dashboardAgent->villageReport($data['village_name'], (int) $data['month'], (int) $data['year']));
    }
}
