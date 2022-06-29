<?php

namespace App\Http\Controllers;

use App\Http\Services\Excel;
use App\Models\FinishRequest;
use App\Models\QuestionaryAnswers;
use App\Models\Request as ModelsRequest;
use App\Models\TeamMember;
use App\Models\Tracking;
use App\Models\UpgradePlan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function dashboard(Request $request)
    {
        $user_id = $request->user()->id;
        $data = [];
        $data['user'] = [
            'sig' => [
                'open' => ModelsRequest::countByUserAndStatus('SGC',$user_id, 'OPEN'),
                'close' => ModelsRequest::countByUserAndStatus('SGC',$user_id, 'CLOSE'),
                'r_to_close' => ModelsRequest::countByUserAndStatus('SGC',$user_id, 'R_TO_CLOSE'),
                'expired' => ModelsRequest::countByUserAndStatus('SGC',$user_id, 'EXPIRED'),
                'pending' => ModelsRequest::countByUserAndStatus('SGC',$user_id, 'PENDING'),
            ],
            'sci' => [
                'open' => ModelsRequest::countByUserAndStatus('SCI',$user_id, 'OPEN'),
                'close' => ModelsRequest::countByUserAndStatus('SCI',$user_id, 'CLOSE'),
                'r_to_close' => ModelsRequest::countByUserAndStatus('SCI',$user_id, 'R_TO_CLOSE'),
                'expired' => ModelsRequest::countByUserAndStatus('SCI',$user_id, 'EXPIRED'),
                'pending' => ModelsRequest::countByUserAndStatus('SCI',$user_id, 'PENDING'),
            ]
        ];
        $data['general'] = [
            'sig' => [
                'this_week' => ModelsRequest::countByPeriod('SGC',6),
                'this_month' => ModelsRequest::countByPeriod('SGC',27),
                'total' => ModelsRequest::countByTypeAndStatus('SGC', ['OPEN', 'PENDING', 'R_TO_CLOSE', 'PENDING', 'CLOSE', 'EXPIRED']),
                'open' => ModelsRequest::countByTypeAndStatus('SGC', ['OPEN', 'PENDING', 'R_TO_CLOSE', 'PENDING', 'EXPIRED']),
                'close' => ModelsRequest::countByTypeAndStatus('SGC', ['CLOSE']),
            ],
            'sci' => [
                'this_week' => ModelsRequest::countByPeriod('SCI',6),
                'this_month' => ModelsRequest::countByPeriod('SCI',27),
                'total' => ModelsRequest::countByTypeAndStatus('SCI', ['OPEN', 'PENDING', 'R_TO_CLOSE', 'PENDING', 'CLOSE', 'EXPIRED']),
                'open' => ModelsRequest::countByTypeAndStatus('SCI', ['OPEN', 'PENDING', 'R_TO_CLOSE', 'PENDING', 'EXPIRED']),
                'close' => ModelsRequest::countByTypeAndStatus('SCI', ['CLOSE']),],
        ];
        return response()->json([
            'message' => 'ok',
            'data' => $data,
        ]);
    }

    public function overview($id) {

        $collection = ModelsRequest::query()->where(['request_code' => $id])->get();

        if (!$collection) { return response()->json('Not Found', 404); }

        $data = [];

        foreach ($collection as $request) {
            $work_team_collection = TeamMember::query()->where(['request_id' => $request->id])->get();
            $immediately_upgrade_plan_collection = UpgradePlan::query()->where(['request_id' => $request->id, 'upgrade_plan_type_code' => 'INM'])->get();
            $definitive_upgrade_plan_collection = UpgradePlan::query()->where(['request_id' => $request->id, 'upgrade_plan_type_code' => 'DEF'])->get();
            $tracking_collection = Tracking::query()->whereIn('upgrade_plan_id', Arr::pluck($definitive_upgrade_plan_collection, 'id'))->get();
            $questionary_answers_collection = QuestionaryAnswers::query()->where(['request_id' => $request->id])->get();
            $finish_request_collection = FinishRequest::query()->where(['request_id' => $request->id])->first();
            $result = $request->toArray();
            $result['work_team_lead'] = $work_team_collection ? $work_team_collection->first() : null;
            $result['work_team'] = $work_team_collection ? array_slice($work_team_collection->toArray(), 1) : null;
            $result['immediately_upgrade_plan'] = $immediately_upgrade_plan_collection;
            $result['definitive_upgrade_plan'] = $definitive_upgrade_plan_collection;
            $result['questionary_answers'] = $questionary_answers_collection;
            $result['tracking'] = $tracking_collection;
            $result['finish_request'] = $finish_request_collection;
            Log::info($result['work_team']);
            array_push($data, $result);
        }

        return view('report.overview', ['collection' => $data, ]);
    }

    public function byRange()
    {
        $high = date('Y-m-d');
        $low = date('Y-m-d', strtotime("00:00am January 01 1990"));
        $data = request(['init_date', 'end_date', 'request_type_code']);
        if ($data['init_date']) { $low = date('Y-m-d', strtotime($data['init_date'])); }
        if ($data['end_date']) { $high = date('Y-m-d', strtotime($data['end_date'])); }
        $query = DB::select(
        <<<SQL
            SELECT 
            r.REQUEST_CODE  AS "Código de solicitud", 
            r.INIT_DATE AS "Fecha de inicio",
            r.DETECTED_DATE AS "Fecha de detección",
            (SELECT DESCRIPTION FROM DETECTED_PLACES dp WHERE code = r.DETECTED_IN_CODE) AS "Lugar de detección",
            (SELECT DESCRIPTION FROM UNFULFILLED_REQUIREMENTS ur WHERE code = r.UNFULFILLED_REQUIREMENT_CODE) AS "Requerimiento incumplido",
            (SELECT DESCRIPTION FROM DETECTION_TYPES dt WHERE code = r.HOW_DETECTED_CODE ) AS "Tipo de detección",
            (SELECT DESCRIPTION FROM AFFECTED_PROCESSES ap WHERE code = r.AFFECTED_PROCESS_CODE  ) AS "Proceso afectado",
            (SELECT NAME FROM USERS u WHERE id = r.PROCESS_LEAD_ID  ) AS "Líder del proceso",
            (SELECT NAME FROM USERS u WHERE id = r.DETECTED_FOR_ID  ) AS "Detectado por",
            (SELECT DESCRIPTION FROM STATUS s WHERE code = r.STATUS_CODE  ) AS "Estado"
            FROM REQUESTS r
            WHERE r.REQUESt_TYPE_CODE = :type_code and r.DETECTED_DATE BETWEEN :low and :high
        SQL,
        [
            'low' => $low,
            'high' => $high,
            'type_code' => $data['request_type_code']
        ]);

        $path = Excel::generate($query);

        return response()->download($path)->deleteFileAfterSend();
    }

    public function byProcess()
    {
        $high = date('Y-m-d');
        $low = date('Y-m-d', strtotime("00:00am January 01 1990"));
        $data = request(['process']);
        $query = DB::select(
        <<<SQL
            SELECT 
            r.REQUEST_CODE  AS "Código de solicitud", 
            r.INIT_DATE AS "Fecha de inicio",
            r.DETECTED_DATE AS "Fecha de detección",
            (SELECT DESCRIPTION FROM DETECTED_PLACES dp WHERE code = r.DETECTED_IN_CODE) AS "Lugar de detección",
            (SELECT DESCRIPTION FROM UNFULFILLED_REQUIREMENTS ur WHERE code = r.UNFULFILLED_REQUIREMENT_CODE) AS "Requerimiento incumplido",
            (SELECT DESCRIPTION FROM DETECTION_TYPES dt WHERE code = r.HOW_DETECTED_CODE ) AS "Tipo de detección",
            (SELECT DESCRIPTION FROM AFFECTED_PROCESSES ap WHERE code = r.AFFECTED_PROCESS_CODE  ) AS "Proceso afectado",
            (SELECT NAME FROM USERS u WHERE id = r.PROCESS_LEAD_ID  ) AS "Líder del proceso",
            (SELECT NAME FROM USERS u WHERE id = r.DETECTED_FOR_ID  ) AS "Detectado por",
            (SELECT DESCRIPTION FROM STATUS s WHERE code = r.STATUS_CODE  ) AS "Estado"
            FROM REQUESTS r
            WHERE r.AFFECTED_PROCESS_CODE = :process
        SQL,
        [
            'process' => $data['process'],
        ]);

        $path = Excel::generate($query);

        return response()->download($path)->deleteFileAfterSend();
    }

    public function byUser()
    {
        $high = date('Y-m-d');
        $low = date('Y-m-d', strtotime("00:00am January 01 1990"));
        $data = request(['lead', 'auditor']);
        if (!$data['auditor']) { $data['auditor'] = 0; }
        if (!$data['lead']) { $data['lead'] = 0; }
        $query = DB::select(
        <<<SQL
            SELECT 
            r.REQUEST_CODE  AS "Código de solicitud", 
            r.INIT_DATE AS "Fecha de inicio",
            r.DETECTED_DATE AS "Fecha de detección",
            (SELECT DESCRIPTION FROM DETECTED_PLACES dp WHERE code = r.DETECTED_IN_CODE) AS "Lugar de detección",
            (SELECT DESCRIPTION FROM UNFULFILLED_REQUIREMENTS ur WHERE code = r.UNFULFILLED_REQUIREMENT_CODE) AS "Requerimiento incumplido",
            (SELECT DESCRIPTION FROM DETECTION_TYPES dt WHERE code = r.HOW_DETECTED_CODE ) AS "Tipo de detección",
            (SELECT DESCRIPTION FROM AFFECTED_PROCESSES ap WHERE code = r.AFFECTED_PROCESS_CODE  ) AS "Proceso afectado",
            (SELECT NAME FROM USERS u WHERE id = r.PROCESS_LEAD_ID  ) AS "Líder del proceso",
            (SELECT NAME FROM USERS u WHERE id = r.DETECTED_FOR_ID  ) AS "Detectado por",
            (SELECT DESCRIPTION FROM STATUS s WHERE code = r.STATUS_CODE  ) AS "Estado"
            FROM REQUESTS r
            WHERE r.PROCESS_LEAD_ID = :lead or r.DETECTED_FOR_ID = :auditor
        SQL,
        [
            'lead' => $data['lead'],
            'auditor' => $data['auditor'],
        ]);

        $path = Excel::generate($query);

        return response()->download($path)->deleteFileAfterSend();
    }
}
