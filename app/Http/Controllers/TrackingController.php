<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Request as ModelsRequest;
use App\Models\Tracking;
use App\Models\UpgradePlan;
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
            ], 400);
        }
        $collection = Tracking::query()->where($query)->get();
        return response()->json(["message" => "ok", "data" => $collection]);
    }

    /**
     * Display all tracking by request_id
     * 
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) 
    {
        $keys = ['tracking'];
        $requestKey = request($keys);
        if (!$requestKey) {
            return response()->json([
                "message" => "No hay informacion disponible",
                "data" => []
            ],
            400);
        }
        $data = $requestKey[$keys[0]];
        $data['status_code'] = "OPEN";
        $validator = Validator::make($data, Tracking::$rules);
        if ($validator->fails()) {
            return response()->json([
                "message" => "Error de validacion",
                "data" => $validator->errors()
            ],
            406);
        } else {
            $record = Tracking::create($data);
            Tracking::verify($record->upgrade_plan_id);
            Issue::createTracking(
                $request->user(),
                ModelsRequest::query()->where('id', UpgradePlan::query()->where('id', $record->upgrade_plan_id)->first()->request_id)->first(),
                $data
            );
            return response()->json([
                "message" => "ok",
                "data" => $record
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
        return array_key_exists("upgrade_plan_id", $query);
    }


}
