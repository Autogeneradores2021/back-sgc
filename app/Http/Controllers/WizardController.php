<?php

namespace App\Http\Controllers;

use App\Models\Analysis;
use App\Models\AnalysisDefinition;
use App\Models\UpgradePlan;
use App\Models\FinishRequest;
use App\Models\Standard;
use App\Models\StandardDefinition;
use App\Models\WorkTeam;
use App\Models\WorkTeamUser;
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

    private function sciS1() {
        $this->body["data"] = [];
        $this->body["message"] = [];
        $this->body["status"] = 201;
        $keys = ['analysis', 'tracking_id'];
        $request = request($keys);
        if (!$request) {
            $this->body["message"] = "Seguimiento vacio";
            $this->body["status"] = 400;
            return;
        }
        $data = $request[$keys[0]];
        $tracking_id = (int)$request[$keys[1]];
        DB::beginTransaction();
        foreach ($data as $a) {
            $validator = Validator::make($a, Analysis::$rules);
            if ($validator->fails()) {  
                $this->body["message"] = "Error validando el analisis";
                array_push( $this->body["data"], $validator->errors() );
                $this->body["status"] = 406;
            } else {
                $record = Analysis::create($a);
            }
        }
        if ($this->body["status"] == 201) {
            DB::commit();
            $this->body["message"] = "El analisis se creo correctamente";
        } else {
            DB::rollback();
        }
    }

    private function sciS2() {
        $this->body["data"] = [];
        $this->body["message"] = [];
        $this->body["status"] = 201;
        $keys = ['upgrade_plan', 'tracking_id'];
        $request = request($keys);
        if (!$request) {
            $this->body["message"] = "Seguimiento vacio";
            $this->body["status"] = 400;
            return;
        }
        $data = $request[$keys[0]];
        $tracking_id = (int)$request[$keys[1]];
        DB::beginTransaction();
        foreach ($data as $a) {
            $a["upgrade_plan_type"] = "D";
            $validator = Validator::make($a, UpgradePlan::$rules);
            if ($validator->fails()) {
                $this->body["message"] = "Error validando el analisis";
                array_push( $this->body["data"], $validator->errors() );
                $this->body["status"] = 406;
            } else {
                UpgradePlan::create($a);
            }
        }
        if ($this->body["status"] == 201) {
            DB::commit();
            $this->body["message"] = "El plan de mejora se creo correctamente";
        } else {
            DB::rollback();
        }
    }

    private function sciS3() {
        $keys = ['finish_request', 'tracking_id'];
        $request = request($keys);
        if (!$request) {
            $this->body["message"] = "Seguimiento vacio";
            $this->body["status"] = 400;
            return;
        }
        $tracking_id = (int)$request[$keys[1]];
        $data = $request[$keys[0]];
        $validator = Validator::make($data, FinishRequest::$rules);
        if ($validator->fails()) {
            $this->body["message"] = "Error de validacion";
            $this->body["data"] = $validator->errors();
            $this->body["status"] = 406;
        } else {
            $record = FinishRequest::create($data);
            $this->body["status"] = 201;
            $this->body["data"] = $record;
            $this->body["message"] = "El plan de mejora se creo correctamente";
        }
    }

    private function sgcS1() {
        $this->body["data"] = [];
        $this->body["message"] = [];
        $this->body["status"] = 201;
        $keys = ['work_team', 'tracking_id'];
        $request = request($keys);
        if (!$request) {
            $this->body["message"] = "Seguimiento vacio";
            $this->body["status"] = 400;
            return;
        }
        $tracking_id = (int)$request[$keys[1]];
        $data = $request[$keys[0]];
        $workTeam = null;
        DB::beginTransaction();
        foreach ($data as $a) {
            $validator = Validator::make($a, WorkTeamUser::$rules);
            if ($validator->fails()) {
                $this->body["message"] = "Error validando equipo de trabajo";
                array_push( $this->body["data"], $validator->errors() );
                $this->body["status"] = 406;
            } else {
                $record = WorkTeamUser::create($a);
                if (!$workTeam) {
                    $workTeam = [
                        "lead_id" => $record->id,
                        "tracking_id" => $tracking_id
                    ];
                    $validator = Validator::make($workTeam, WorkTeam::$rules);
                    if ($validator->fails()) {
                        $this->body["message"] = "Error validando el equipo de trabajo";
                        array_push( $this->body["data"], $validator->errors() );
                        $this->body["status"] = 406;
                    } else {
                        $workTeam = WorkTeam::create($workTeam);
                    }
                }
            }
        }
        if ($this->body["status"] == 201) {
            DB::commit();
            $this->body["message"] = "El equipo se creo correctamente";
        } else {
            DB::rollback();
        }

    }

    private function sgcS2() {
        $this->body["data"] = [];
        $this->body["message"] = [];
        $this->body["status"] = 201;
        $keys = ['upgrade_plan', 'tracking_id'];
        $request = request($keys);
        if (!$request) {
            $this->body["message"] = "Seguimiento vacio";
            $this->body["status"] = 400;
            return;
        }
        $tracking_id = (int)$request[$keys[1]];
        $data = $request[$keys[0]];
        DB::beginTransaction();
        foreach ($data as $a) {
            $a["upgrade_plan_type"] = "I";
            $validator = Validator::make($a, UpgradePlan::$rules);
            if ($validator->fails()) {
                $this->body["message"] = "Error validando el analisis";
                array_push( $this->body["data"], $validator->errors() );
                $this->body["status"] = 406;
            } else {
                $a = UpgradePlan::create($a);
            }
        }
        if ($this->body["status"] == 201) {
            DB::commit();
            $this->body["message"] = "El plan de mejora se creo correctamente";
        } else {
            DB::rollback();
        }
    }

    private function sgcS3() {
        $this->body["data"] = [
            "analysis_definitions" => [],
            "analysis" => []
        ];
        $this->body["message"] = [];
        $this->body["status"] = 201;
        $keys = ['analysis', 'analysis_definitions', 'tracking_id'];
        $request = request($keys);
        if (!$request) {
            $this->body["message"] = "Seguimiento vacio";
            $this->body["status"] = 400;
            return;
        }
        $collection = $request[$keys[0]];
        $definition = $request[$keys[1]];
        $tracking_id = (int)$request[$keys[2]];
        $validator = Validator::make($definition, AnalysisDefinition::$rules);
        if ($validator->fails()) {
            $this->body["message"] = "Error validando la definicion del analisis";
            $this->body["data"]["analysis_definitions"] = $validator->errors();
            $this->body["status"] = 406;
        } else {
            $definition = AnalysisDefinition::create($definition);
        }
        DB::beginTransaction();
        foreach ($collection as $a) {
            $a[$keys[2]] = $tracking_id;
            $validator = Validator::make($a, Analysis::$rules);
            if ($validator->fails()) {
                $this->body["message"] = "Error validando el analisis";
                array_push( $this->body["data"]["analysis"], $validator->errors() );
                $this->body["status"] = 406;
            } else {
                $a = Analysis::create($a);
            }
        }
        if ($this->body["status"] == 201) {
            DB::commit();
            $this->body["message"] = "El paso 3 se completo correctamente";
        } else {
            DB::rollback();
        }
    }

    private function sgcS4() {
        $this->body["data"] = [];
        $this->body["message"] = [];
        $this->body["status"] = 201;
        $keys = ['upgrade_plan', 'tracking_id'];
        $request = request($keys);
        if (!$request) {
            $this->body["message"] = "Seguimiento vacio";
            $this->body["status"] = 400;
            return;
        }
        $tracking_id = (int)$request[$keys[1]];
        $data = $request[$keys[0]];
        DB::beginTransaction();
        foreach ($data as $a) {
            $a["upgrade_plan_type"] = "D";
            $validator = Validator::make($a, UpgradePlan::$rules);
            if ($validator->fails()) {
                $this->body["message"] = "Error validando el analisis";
                array_push( $this->body["data"], $validator->errors() );
                $this->body["status"] = 406;
            } else {
                $a = UpgradePlan::create($a);
            }
        }
        if ($this->body["status"] == 201) {
            DB::commit();
            $this->body["message"] = "El plan de mejora se creo correctamente";
        } else {
            DB::rollback();
        }
    }

    private function sgcS5() {
        $this->body["data"] = [
            "standard_definitions" => [],
            "standard_activities" => [],
            "comunication_activities" => []
        ];
        $this->body["message"] = [];
        $this->body["status"] = 201;
        $keys = ['standard_activities', 'comunication_activities', 'standard_definitions', 'tracking_id'];
        $request = request($keys);
        if (!$request) {
            $this->body["message"] = "Seguimiento vacio";
            $this->body["status"] = 400;
            return;
        }
        $standardCollection = $request[$keys[0]];
        $actvitieCollection = $request[$keys[1]];
        $definition = $request[$keys[2]];
        $tracking_id = (int)$request[$keys[3]];
        $validator = Validator::make($definition, StandardDefinition::$rules);
        if ($validator->fails()) {
            $this->body["message"] = "Error validando la definicion del analisis";
            $this->body["data"]["standard_definitions"] = $validator->errors();
            $this->body["status"] = 406;
        } else {
            $definition = StandardDefinition::create($definition);
        }
        DB::beginTransaction();
        foreach ($standardCollection as $a) {
            $a[$keys[2]] = $tracking_id;
            $a["standard_type"] = "E";
            $validator = Validator::make($a, Standard::$rules);
            if ($validator->fails()) {
                $this->body["message"] = "Error validando el analisis";
                array_push( $this->body["data"]["standard_activities"], $validator->errors() );
                $this->body["status"] = 406;
            } else {
                $a = Analysis::create($a);
            }
        }
        foreach ($actvitieCollection as $a) {
            $a[$keys[2]] = $tracking_id;
            $a["standard_type"] = "C";
            $validator = Validator::make($a, Standard::$rules);
            if ($validator->fails()) {
                $this->body["message"] = "Error validando el analisis";
                array_push( $this->body["data"]["comunication_activities"], $validator->errors() );
                $this->body["status"] = 406;
            } else {
                $a = Analysis::create($a);
            }
        }
        if ($this->body["status"] == 201) {
            DB::commit();
            $this->body["message"] = "El paso 3 se completo correctamente";
        } else {
            DB::rollback();
        }
    }

    private function sgcS6() {
        $keys = ['finish_request', 'tracking_id'];
        $request = request($keys);
        if (!$request) {
            $this->body["message"] = "Seguimiento vacio";
            $this->body["status"] = 400;
            return;
        }
        $tracking_id = (int)$request[$keys[1]];
        $data = $request[$keys[0]];
        $validator = Validator::make($data, FinishRequest::$rules);
        if ($validator->fails()) {
            $this->body["message"] = "Error de validacion";
            $this->body["data"] = $validator->errors();
            $this->body["status"] = 406;
        } else {
            $record = FinishRequest::create($data);
            $this->body["status"] = 201;
            $this->body["data"] = $record;
            $this->body["message"] = "El plan de mejora se creo correctamente";
        }
    }
}
