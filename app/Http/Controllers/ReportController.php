<?php

namespace App\Http\Controllers;

use App\Models\FinishRequest;
use App\Models\QuestionaryAnswers;
use App\Models\Request as ModelsRequest;
use App\Models\TeamMember;
use App\Models\Tracking;
use App\Models\UpgradePlan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function dashboard(Request $request)
    {
        $user_id = $request->user()->id;
        $data = [];
        $data['user'] = [
            'sgc' => [
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
            'sgc' => [
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
            $tracking_collection = Tracking::query()->whereIn('upgrade_plan_id', UpgradePlan::query()->where(['request_id' => $request->id, 'upgrade_plan_type_code' => 'DEF'])->get('id'))->get();
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

        // $pdf = PDF::loadView('report.overview', ['collection' => $data, ]);

        // return $pdf->download('invoice.pdf');
    }
}
