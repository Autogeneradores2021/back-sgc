<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WizardController extends Controller
{

    public string $module;
    public string $step;
    /**
     * Get data by step and module
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        return response()->json(["message" => "ok"]);
    }


    /**
     * Complete a step
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function complete($step) {
        return response()->json(["message" => "ok"]);
    }

    /**
     * Display all steps
     * 
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        return response()->json(["message" => "ok"]);
    }
}
