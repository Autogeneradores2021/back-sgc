<?php

namespace App\Http\Controllers;

use App\Models\Action;
use Illuminate\Http\Request;
use App\Models\RequestModel;
use Illuminate\Support\Facades\Validator;

use function GuzzleHttp\Promise\all;

class RequestController extends Controller
{

    /**
     * create new request
     *  
     * @return \Illuminate\Http\JsonResponse
     */
    public function create() {
        $requestData = request(['request'])['request'];
        $requestData['status_code'] = 'OPEN';
        $requestRecord = new RequestModel($requestData);
        $validator = Validator::make($requestData, RequestModel::$rules);
        if ($validator->fails()) {
            return response()->json(
                [
                    "message" => "Error de validacion",
                    "data" => $validator->errors(),
                ],
                406
            );
        }
        $requestRecord->save();
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
            "request_status" => ['OPEN', 'CLOSE', 'R_TO_CLOSE'],
            "search" => ""
        ];
        $queryParams = $request->query();
        foreach ($queryParams as $key => $value) {
           $params[$key] = $value;
        }
        $query = RequestModel::query()->where('request_type_code', '=', $params['request_type'])
        ->whereIn('status_code', $params['request_status'])
        ->orderBy('id')
        ->paginate(
            $params["per_page"],
            $params["columns"],
            $params["pageName"],
            $params["page"],
        );
        return response()->json($query);
    }

}
