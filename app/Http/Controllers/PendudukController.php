<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PendudukController extends Controller
{
    public function index(Request $request)
    {
        return view('penduduk.index');
    }
}
