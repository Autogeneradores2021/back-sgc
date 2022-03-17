<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $exclude = $request->query('exclude');
        if (!$search) { $search = ''; }
        if (!$exclude) { $exclude = []; }
        $search = strtoupper($search);
        $query = Employee::query()->where('estado', 'ACTIVO')->where('nombre', 'like', '%'.$search.'%')->whereNotIn('nombre', $exclude)->orderBy('nombre')->limit(4)->get();
        return response()->json([
            'message' => 'ok',
            'data' => $query
        ]);
    }
}
