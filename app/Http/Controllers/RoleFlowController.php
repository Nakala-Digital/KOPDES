<?php

namespace App\Http\Controllers;

class RoleFlowController extends Controller
{
    public function index()
    {
        $flowSteps = config('role_flow.flow_steps');
        $roles = config('role_flow.roles');

        return view('role-flow.index', compact('flowSteps', 'roles'));
    }
}
