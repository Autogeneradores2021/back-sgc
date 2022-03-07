<?php

namespace App\Http\Controllers;

use App\Models\Request as ModelsRequest;
use Illuminate\Http\Request;

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
                'last_7_days' => ModelsRequest::countByPeriod('SGC',7),
                'last_28_days' => ModelsRequest::countByPeriod('SGC',28),
                'total' => ModelsRequest::countByTypeAndStatus('SGC', ['OPEN', 'PENDING', 'R_TO_CLOSE', 'PENDING', 'CLOSE', 'EXPIRED']),
                'open' => ModelsRequest::countByTypeAndStatus('SGC', ['OPEN', 'PENDING', 'R_TO_CLOSE', 'PENDING', 'EXPIRED']),
                'close' => ModelsRequest::countByTypeAndStatus('SGC', ['CLOSE']),
            ],
            'sci' => [
                'last_7_days' => ModelsRequest::countByPeriod('SCI',7),
                'last_28_days' => ModelsRequest::countByPeriod('SCI',28),
                'total' => ModelsRequest::countByTypeAndStatus('SCI', ['OPEN', 'PENDING', 'R_TO_CLOSE', 'PENDING', 'CLOSE', 'EXPIRED']),
                'open' => ModelsRequest::countByTypeAndStatus('SCI', ['OPEN', 'PENDING', 'R_TO_CLOSE', 'PENDING', 'EXPIRED']),
                'close' => ModelsRequest::countByTypeAndStatus('SCI', ['CLOSE']),],
        ];
        return response()->json([
            'message' => 'ok',
            'data' => $data,
        ]);
    }
}
