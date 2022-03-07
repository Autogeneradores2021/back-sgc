<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Request as ModelsRequest;
use Illuminate\Http\Request;

class IssuesController extends Controller
{
    public function byRequestId(Request $request, $request_id)
    {
        $query = Issue::query()->where('request_id', $request_id)->orderBy('created_at', 'desc')->get();
        if (count($query) == 0) {
            return response()->json([
                'message' => 'Esta solicitud no ha sido creada todavia',
            ], 404);
        }
        return response()->json([
            'message' => 'ok',
            'data' => $query,
            'code' => ModelsRequest::query()->where('id', $request_id)->limit(1)->get('request_code')[0]->request_code,
        ]);
    }
}
