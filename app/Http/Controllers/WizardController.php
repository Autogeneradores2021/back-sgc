<?php

namespace App\Http\Controllers;

use App\Models\Analysis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WizardController extends Controller
{

    public $body = [
        "data" => [],
        "status" => 200,
        "message" => ""
    ];

    /**
     * Get data by step and module
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show() {
        return response()->json(["message" => "ok"]);
    }


    /**
     * Complete a step
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function complete(Request $request, $module, $step) {
        try {
            eval('$this->'.$module."S".$step.'();');
        } catch (\BadMethodCallException $e) {
            $this->body["status"] = 404;
            $this->body["message"] = "Este paso todavia no esta disponible";
            $this->body["data"] = $e;
        }
        return response()->json(["message" => $this->body["message"], "data" => $this->body["data"]], $this->body["status"]);
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

    private function sciS1()
    {
        $key = 'analysis';
        $data = request([$key])[$key];
        $this->body["message"] = [];
        DB::beginTransaction();
        $this->body["status"] = 201;
        foreach ($data as $a) {
            $record = new Analysis($a);
            $validator = Validator::make($a, $record->rules);
            if ($validator->fails()) {
                $this->body["message"] = "Error validando el analisis";
                array_push( $this->body["data"], $validator->errors() );
                $this->body["status"] = 404;
                DB::rollback();
            } else {
                $record->save();
            }
        }
        if ($this->body["status"] == 201) {
            DB::commit();
            $this->body["message"] = "El analisis se creo correctamente";
        }
    }
}
