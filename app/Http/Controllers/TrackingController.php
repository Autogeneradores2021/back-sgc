<?php

namespace App\Http\Controllers;

use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    /**
     * Display all tracking by request_id
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) 
    {
        $query = $request->query();
        if (!$this->query_validate($query)) {
            return response()->json([
                "message" => "Consulta invalida",
                "data" => [
                    "request_id" => "The request id field is required."
                ]
            ], 406);
        }
        $collection = Tracking::query()->where($query)->get();
        return response()->json(["message" => "ok", "data" => $collection]);
    }

    /**
     * Display all tracking by request_id
     * 
     * @return \Illuminate\Http\Response
     */
    public function create() 
    {
        $keys = ['tracking'];
        $request = request($keys);
        if (!$request) {
            return response()->json([
                "message" => "No hay informacion disponible",
                "data" => []
            ],
            400);
        }
        $data = $request[$keys[0]];
        $validator = Validator::make($data, Tracking::$rules);
        if ($validator->fails()) {
            return response()->json([
                "message" => "Error de validacion",
                "data" => $validator->errors()
            ],
            406);
        } else {
            return response()->json([
                "message" => "ok",
                "data" => Tracking::create($data)
            ],
            201);
        }
    }

    /**
     * validate param request_id
     * 
     * @return \Illuminate\Http\Response
     */
    public function query_validate($query) 
    {
        return array_key_exists("request_id", $query);
    }


}
