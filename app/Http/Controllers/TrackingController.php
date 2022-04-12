<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Request as ModelsRequest;
use App\Models\Tracking;
use App\Models\UpgradePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $collection = Tracking::query()->where($query)->orderBy('percentage', 'asc')->get();
        return response()->json(["message" => "ok", "data" => $collection]);
    }

    /**
     * Display all tracking by request_id
     * 
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) 
    {
        $data = $request->all();
        $validator = Validator::make($data, Tracking::$rules);
        if ($validator->fails()) {
            return response()->json([
                "message" => "Error de validacion",
                "data" => $validator->errors()
            ],
            400);
        } else {
            $count = 0;
            $data['evidence_file'] = '';
            while ($request->hasfile('evidence_file_'.$count)) {
                $file = $request->file('evidence_file_'.$count);
                $extention = $file->getClientOriginalExtension();
                $filename = time().$this->generateRandomString(15).'.'.$extention;
                $file->move('request/', $filename);
                $dir = 'request/'.$filename.';';
                $data['evidence_file'] .= $dir;
                $count++;
            }
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


    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


}
