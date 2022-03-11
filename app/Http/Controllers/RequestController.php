<?php

namespace App\Http\Controllers;

use App\Http\Services\Mailer;
use App\Models\Issue;
use Illuminate\Http\Request;
use App\Models\Request as ModelsRequest;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use function GuzzleHttp\Promise\all;

class RequestController extends Controller
{

    /**
     * create new request
     *  
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request) {
        $requestData = $request->all();
        $requestData['status_code'] = 'PENDING';
        $requestRecord = new ModelsRequest($requestData);
        $validator = Validator::make($requestData, ModelsRequest::$rules);
        if (!$requestRecord->request_code) {
            $lastCode = ModelsRequest::query()->where('request_type_code', '=', $requestRecord->request_type_code)
                        ->whereNotNull('request_code')
                        ->orderByRaw('TO_NUMBER(request_code) desc')
                        ->limit(1)
                        ->get('request_code');
            if (count($lastCode) != 0 ) { $lastCode=$lastCode->first()['request_code']; } else { $lastCode = 0; }
            $requestRecord->request_code = $lastCode + 1;
            Log::info('SE CALCULO UN NUEVO CODIGO');
            Log::info($lastCode);
            Log::info($requestRecord);
        }
        if ($validator->fails()) {
            return response()->json(
                [
                    "message" => "Error de validacion",
                    "data" => $validator->errors(),
                ],
                406
            );
        }
        $count = 0;
        $requestRecord->evidence_file = '';
        while ($request->hasfile('evidence_file_'.$count)) {
            $file = $request->file('evidence_file_'.$count);
            $extention = $file->getClientOriginalExtension();
            $filename = time().$this->generateRandomString(15).'.'.$extention;
            $file->move('request/'.$requestRecord->request_code.'/', $filename);
            $dir = 'request/'.$requestRecord->request_code.'/'.$filename.';';
            $requestRecord->evidence_file .= $dir;
            Log::info('SE RECIBIO UN ARCHIVO Y SE GUARDO');
            Log::info($dir);
            $count++;
        }
        $requestRecord->area_code = $request->user()->area_code;
        $requestRecord->position_code = $request->user()->position_code;
        $requestRecord->save();
        Issue::createRequest(
            $request->user(),
            ModelsRequest::query()->where('id', $requestRecord->id)->first()
        );
        Mailer::sendNewRequestNotification($requestRecord, User::getEmailById($requestRecord->process_lead_id));
        return response()->json(
            [
                "message" => "Solicitud creada con exito",
                "data" => $requestRecord
            ]
        );
    }

    /**
     * list paginate request
     *  
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {
        $params = [
            "per_page" => 10,
            "columns" => ['*'],
            "pageName" => 'page',
            "page" => 1,
            "request_type" => 'SGC',
            "status_code" => ['OPEN', 'CLOSE', 'R_TO_CLOSE', 'PENDING'],
            "search" => "",
            "id" => null
        ];
        $queryParams = $request->query();
        foreach ($queryParams as $key => $value) {
            if ($key == 'status_code' && $value == 'ALL') { }
            else if ($key == 'status_code') { $params[$key] = [$value]; }
            else { $params[$key] = $value; }
        }
        if ($params['id']) {
            $query = [];
            $query['data'] = ModelsRequest::query()->where('id', '=', $params['id'])->get();
        } else {
            $query = ModelsRequest::query()->where('request_type_code', '=', $params['request_type'])
            ->where('request_code', 'like', '%' . $params['search'] . '%')
                ->whereIn('status_code', $params['status_code'])
            ->orderByRaw('TO_NUMBER(request_code) desc')
            ->paginate(
                $params["per_page"],
                $params["columns"],
                $params["pageName"],
                $params["page"],
            );
        }
        return response()->json($query);
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
