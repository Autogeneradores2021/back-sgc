<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestModel;
use Illuminate\Support\Facades\Validator;

class RequestController extends Controller
{

    /**
     * create new request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request, $module) {
        $requestData = request(['request'])['request'];
        $requestRecord = new RequestModel($requestData);
        $requestRecord->request_type = $module;
        $requestRecord->status = "open";
        $validator = Validator::make($requestData, RequestModel::$rules);
        if ($validator->fails()) {
            return response()->json(
                [
                    "message" => "Error de validacion",
                    "data" => $validator->errors()
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
}
