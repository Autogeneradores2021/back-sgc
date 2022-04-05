<?php

namespace App\Http\Controllers;

use App\Models\UpgradePlan;
use App\Models\FinishRequest;
use App\Models\Issue;
use App\Models\QuestionaryAnswers;
use App\Models\Request as ModelsRequest;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        "sci1" => 'team_members',
        "sci2" => 'questionary_answers',
        "sci3" => 'upgrade_plans',
        "sci4" => 'finish_requests',
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
        $this->request = $request;
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
        if (in_array($module.$step, ['sgc5', 'sci5'])) { $data = FinishRequest::query()->where($params)->get(); }
        if (in_array($module.$step, ['sgc4', 'sci3'])) { $data = UpgradePlan::query()->where($params)->get(); }
        if (in_array($module.$step, ['sgc2',])) { $data = UpgradePlan::query()->where($params)->get(); }
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
        $keys = ['team_memebers'];
        $request = request($keys);
        if (!$request) {
            $this->body["message"] = "Seguimiento vacio";
            $this->body["status"] = 400;
            return;
        }
        $data = $request[$keys[0]];
        $first = true;
        $request_id = null;
        if (count($data) != 0) {  $request_id = $data[0]['request_id']; }
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
            Issue::createWorkTeam(
                $this->request->user(),
                ModelsRequest::query()->where('id', $request_id)->first(),
                $data
            );
            $this->body["message"] = "El equipo se creo correctamente";
        } else {
            DB::rollback();
        }
    }

    private function sciS2() {
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
        $request_id = null;
        if (count($collection) != 0) { $request_id = $collection[0]['request_id']; }
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
            Issue::createAnalysis(
                $this->request->user(),
                ModelsRequest::query()->where('id', $request_id)->first(),
                $collection,
            );
            $this->body["message"] = "El paso 3 se completo correctamente";
        } else {
            DB::rollback();
        }
    }

    private function sciS3() {
        $this->body["data"] = [];
        $this->body["message"] = [];
        $this->body["status"] = 201;
        $data = $this->request->all();
        $request_id = $data['request_id'];
        $index = $data['index'];
        DB::beginTransaction();
        if ($index == '0') {
            UpgradePlan::query()->where('request_id', '=', $data['request_id'])->where('upgrade_plan_type_code', '=', 'DEF    ')->delete();
        }
        $data["upgrade_plan_type_code"] = "DEF";
        $validator = Validator::make($data, UpgradePlan::$rules);
        if ($validator->fails()) {
            $this->body["message"] = "Error validando el analisis";
            array_push( $this->body["data"], $validator->errors() );
            $this->body["status"] = 400;
        } else {
            $count = 0;
            $data['evidence_file'] = '';
            while ($this->request->hasfile('evidence_file_'.$count)) {
                $file = $this->request->file('evidence_file_'.$count);
                $extention = $file->getClientOriginalExtension();
                $filename = time().$this->generateRandomString(15).'.'.$extention;
                $file->move('upland/'.$request_id.'/', $filename);
                $dir = 'upland/'.$request_id.'/'.$filename.';';
                $data['evidence_file'] .= $dir;
                Log::info('SE RECIBIO UN ARCHIVO Y SE GUARDO');
                Log::info($dir);
                $count++;
            }
            $data = UpgradePlan::create($data);
        }
        if ($this->body["status"] == 201) {
            DB::commit();
            Log::info('ULTIMO');
            Log::info($data);
            if ($index == 'last') {
                ModelsRequest::updateStatus($data['request_id'], 'OPEN');
                Issue::createUPlan(
                    $this->request->user(),
                    ModelsRequest::query()->where('id', $request_id)->first(),
                    UpgradePlan::query()->where('request_id', '=', $data['request_id'])->where('upgrade_plan_type_code', '=', 'DEF    ')->get(),
                    'acciones de correccion inmediatas'
                );
            }
            $this->body["message"] = "El plan de mejora se creo correctamente";
        } else {
            DB::rollback();
        }
    }

    private function sciS4() {
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
            Issue::createFinishRequest(
                $this->request->user(),
                ModelsRequest::query()->where('id', $data['request_id'])->first(),
                $record,
            );
            $this->body["status"] = 201;
            $this->body["data"] = $record;
            $this->body["message"] = "La solicitud se cerro correctamente";
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
        $request_id = null;
        if (count($data) != 0) {  $request_id = $data[0]['request_id']; }
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
            Issue::createWorkTeam(
                $this->request->user(),
                ModelsRequest::query()->where('id', $request_id)->first(),
                $data
            );
            $this->body["message"] = "El equipo se creo correctamente";
        } else {
            DB::rollback();
        }
    }

    private function sgcS2() {
        $this->body["data"] = [];
        $this->body["message"] = [];
        $this->body["status"] = 201;
        $data = $this->request->all();
        $request_id = $data['request_id'];
        $index = $data['index'];
        DB::beginTransaction();
        if ($index == '0') {
            UpgradePlan::query()->where('request_id', '=', $data['request_id'])->where('upgrade_plan_type_code', '=', 'INM    ')->delete();
        }
        $data["upgrade_plan_type_code"] = "INM";
        $validator = Validator::make($data, UpgradePlan::$rules);
        if ($validator->fails()) {
            $this->body["message"] = "Error validando el analisis";
            array_push( $this->body["data"], $validator->errors() );
            $this->body["status"] = 400;
        } else {
            $count = 0;
            $data['evidence_file'] = '';
            while ($this->request->hasfile('evidence_file_'.$count)) {
                $file = $this->request->file('evidence_file_'.$count);
                $extention = $file->getClientOriginalExtension();
                $filename = time().$this->generateRandomString(15).'.'.$extention;
                $file->move('uplani/'.$request_id.'/', $filename);
                $dir = 'uplani/'.$request_id.'/'.$filename.';';
                $data['evidence_file'] .= $dir;
                Log::info('SE RECIBIO UN ARCHIVO Y SE GUARDO');
                Log::info($dir);
                $count++;
            }
            $data = UpgradePlan::create($data);
        }
        if ($this->body["status"] == 201) {
            DB::commit();
            if ($index == 'last') {
                Issue::createUPlan(
                    $this->request->user(),
                    ModelsRequest::query()->where('id', $request_id)->first(),
                    UpgradePlan::query()->where('request_id', '=', $data['request_id'])->where('upgrade_plan_type_code', '=', 'INM    ')->get(),
                    'acciones de correccion inmediatas'
                );
            }
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
        $request_id = null;
        if (count($collection) != 0) { $request_id = $collection[0]['request_id']; }
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
            Issue::createAnalysis(
                $this->request->user(),
                ModelsRequest::query()->where('id', $request_id)->first(),
                $collection,
            );
            $this->body["message"] = "El paso 3 se completo correctamente";
        } else {
            DB::rollback();
        }
    }

    private function sgcS4() {
        $this->body["data"] = [];
        $this->body["message"] = [];
        $this->body["status"] = 201;
        $data = $this->request->all();
        $request_id = $data['request_id'];
        $index = $data['index'];
        DB::beginTransaction();
        if ($index == '0') {
            UpgradePlan::query()->where('request_id', '=', $data['request_id'])->where('upgrade_plan_type_code', '=', 'DEF    ')->delete();
        }
        $data["upgrade_plan_type_code"] = "DEF";
        $validator = Validator::make($data, UpgradePlan::$rules);
        if ($validator->fails()) {
            $this->body["message"] = "Error validando el analisis";
            array_push( $this->body["data"], $validator->errors() );
            $this->body["status"] = 400;
        } else {
            $count = 0;
            $data['evidence_file'] = '';
            while ($this->request->hasfile('evidence_file_'.$count)) {
                $file = $this->request->file('evidence_file_'.$count);
                $extention = $file->getClientOriginalExtension();
                $filename = time().$this->generateRandomString(15).'.'.$extention;
                $file->move('upland/'.$request_id.'/', $filename);
                $dir = 'upland/'.$request_id.'/'.$filename.';';
                $data['evidence_file'] .= $dir;
                Log::info('SE RECIBIO UN ARCHIVO Y SE GUARDO');
                Log::info($dir);
                $count++;
            }
            $data = UpgradePlan::create($data);
        }
        if ($this->body["status"] == 201) {
            DB::commit();
            Log::info('ULTIMO');
            Log::info($data);
            if ($index == 'last') {
                ModelsRequest::updateStatus($data['request_id'], 'OPEN');
                Issue::createUPlan(
                    $this->request->user(),
                    ModelsRequest::query()->where('id', $request_id)->first(),
                    UpgradePlan::query()->where('request_id', '=', $data['request_id'])->where('upgrade_plan_type_code', '=', 'DEF    ')->get(),
                    'acciones de correccion inmediatas'
                );
            }
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
            Issue::createFinishRequest(
                $this->request->user(),
                ModelsRequest::query()->where('id', $data['request_id'])->first(),
                $record,
            );
            $this->body["status"] = 201;
            $this->body["data"] = $record;
            $this->body["message"] = "La solicitud se cerro correctamente";
        }
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
