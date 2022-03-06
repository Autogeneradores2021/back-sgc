<?php

namespace App\Http\Controllers;

use App\Models\Analysis;
use App\Models\AnalysisDefinition;
use App\Models\UpgradePlan;
use App\Models\FinishRequest;
use App\Models\QuestionaryAnswers;
use App\Models\Request as ModelsRequest;
use App\Models\Tracking;
use App\Models\TeamMember;
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

    public $dictionary = [
        "sgc1" => 'team_members',
        "sgc2" => 'upgrade_plans',
        "sgc3" => 'questionary_answers',
        "sgc4" => 'upgrade_plans',
        "sgc5" => 'finish_requests',
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
     * Complete a step
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function retrive(Request $request, $module, $step) {
        $params = request()->query();
        $data = DB::table($this->dictionary[$module.$step])->where($params)->orderBy('id')->get();
        if ($module.$step == 'sgc5') { $data = FinishRequest::query()->where($params)->get(); }
        if ($module.$step == 'sgc2') { $data = UpgradePlan::query()->where($params)->get(); }
        if ($module.$step == 'sgc4') { $data = UpgradePlan::query()->where($params)->get(); }
        $this->body["status"] = 200;
        $this->body["data"] = $data;
        $this->body["message"] = "ok";
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
                $this->body["status"] = 400;
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
                $this->body["status"] = 400;
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
            $this->body["status"] = 400;
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
        $keys = ['team_memebers'];
        $request = request($keys);
        if (!$request) {
            $this->body["message"] = "Seguimiento vacio";
            $this->body["status"] = 400;
            return;
        }
        $data = $request[$keys[0]];
        $first = true;
        DB::beginTransaction();
        foreach ($data as $a) {
            if ($first) {
                $a['is_lead'] = true;
                $first = false;
                TeamMember::query()->where('request_id', '=', $a['request_id'])->delete();
            }
            $validator = Validator::make($a, TeamMember::$rules);
            if ($validator->fails()) {
                $this->body["message"] = "Error validando equipo de trabajo";
                array_push( $this->body["data"], $validator->errors() );
                $this->body["status"] = 400;
            } else {
                $record = TeamMember::create($a);
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
        $keys = ['upgrade_plan'];
        $request = request($keys);
        if (!$request) {
            $this->body["message"] = "Seguimiento vacio";
            $this->body["status"] = 400;
            return;
        }
        $data = $request[$keys[0]];
        $first = true;
        DB::beginTransaction();
        foreach ($data as $a) {
            if ($first) {
                $first = false;
                UpgradePlan::query()->where('request_id', '=', $a['request_id'])->where('upgrade_plan_type_code', '=', 'INM    ')->delete();
            }
            $a["upgrade_plan_type_code"] = "INM";
            $validator = Validator::make($a, UpgradePlan::$rules);
            if ($validator->fails()) {
                $this->body["message"] = "Error validando el analisis";
                array_push( $this->body["data"], $validator->errors() );
                $this->body["status"] = 400;
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
        $this->body["data"] = [];
        $this->body["message"] = [];
        $this->body["status"] = 201;
        $keys = ['answers'];
        $request = request($keys);
        if (!$request) {
            $this->body["message"] = "Seguimiento vacio";
            $this->body["status"] = 400;
            return;
        }
        DB::beginTransaction();
        $collection = $request[$keys[0]];
        $first = true;
        foreach ($collection as $a) {
            if ($first) {
                $first = false;
                QuestionaryAnswers::query()->where('request_id', '=', $a['request_id'])->delete();
            }
            $validator = Validator::make($a, QuestionaryAnswers::$rules);
            if ($validator->fails()) {
                $this->body["message"] = "Error validando el analisis";
                array_push( $this->body["data"]["answers"], $validator->errors() );
                $this->body["status"] = 400;
            } else {
                $a = QuestionaryAnswers::create($a);
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
        $keys = ['upgrade_plan'];
        $request = request($keys);
        if (!$request) {
            $this->body["message"] = "Seguimiento vacio";
            $this->body["status"] = 400;
            return;
        }
        $data = $request[$keys[0]];
        $first = true;
        DB::beginTransaction();
        foreach ($data as $a) {
            if ($first) {
                $first = false;
                UpgradePlan::query()->where('request_id', '=', $a['request_id'])->where('upgrade_plan_type_code', '=', 'DEF')->delete();
            }
            $a["upgrade_plan_type_code"] = "DEF";
            $validator = Validator::make($a, UpgradePlan::$rules);
            if ($validator->fails()) {
                $this->body["message"] = "Error validando el analisis";
                array_push( $this->body["data"], $validator->errors() );
                $this->body["status"] = 400;
            } else {
                $a = UpgradePlan::create($a);
            }
        }
        if ($this->body["status"] == 201) {
            ModelsRequest::updateStatus($a['request_id'], 'OPEN');
            DB::commit();
            $this->body["message"] = "El plan de mejora se creo correctamente";
        } else {
            DB::rollback();
        }
    }

    private function sgcS5() {
        $keys = ['finish_request'];
        $request = request($keys);
        if (!$request) {
            $this->body["message"] = "Seguimiento vacio";
            $this->body["status"] = 400;
            return;
        }
        $data = $request[$keys[0]];
        $validator = Validator::make($data, FinishRequest::$rules);
        FinishRequest::query()->where('request_id', '=', $data['request_id'])->delete();
        if ($validator->fails()) {
            $this->body["message"] = "Error de validacion";
            $this->body["data"] = $validator->errors();
            $this->body["status"] = 400;
        } else {
            $record = FinishRequest::create($data);
            ModelsRequest::updateStatus($data['request_id'], $record->result_code);
            $this->body["status"] = 201;
            $this->body["data"] = $record;
            $this->body["message"] = "El plan de mejora se creo correctamente";
        }
    }

}
