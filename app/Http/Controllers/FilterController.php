<?php

namespace App\Http\Controllers;

use App\Models\Filter;
use App\Models\FilterValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class FilterController extends Controller
{

    public function index(Request $request) {
        $filters = DB::table('filter_types')->orderBy('created_at', 'desc')->get();
        $result = [];
        foreach ($filters as $filter) {
            $where = [];
            foreach (Filter::query()->where('type', $filter->code)->get('value') as $value) {
                array_push($where, $value->value);
            }
            array_push($result, [
                'code' => $filter->code,
                'name' => $filter->name,
                'description' => $filter->description,
                'filters' => Filter::query()->where('type', $filter->code)->orderBy('created_at', 'desc')->get(),
                'options' => FilterValue::query()->where('code', 'like', $filter->code.'%')->whereNotIn('code', $where)->get()
            ]);
        }
        return response()->json([
            'message' => 'ok',
            'data' => $result
        ]);
    }

    public function create(Request $request) {
        $data = request('filter');
        $filter = new Filter($data);
        $validator = FacadesValidator::make($data, $filter->rules());
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'data' => $validator->errors()
            ], 400);
        } else {
            $model = $filter;
            $model->save();
        }
        return response()->json([
            'message' => 'ok',
            'data' => $data
        ]);
    }

    public function delete(Request $request, $id) {
        $count = Filter::query()->where('id', $id)->count();
        if ($count == 0) {
            return response()->json([
                'message' => 'No existe este registro'
            ], 404);
        } else {
            Filter::destroy($id);
        }
        return response()->json([
            'message' => 'ok',
        ], 204);
    }

}