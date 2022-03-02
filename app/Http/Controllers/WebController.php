<?php

namespace App\Http\Controllers;

class WebController extends Controller
{
    public function index() {
        $data = [];
        return view('index', $data);
    }
}
