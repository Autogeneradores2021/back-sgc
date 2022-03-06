<?php

namespace App\Http\Controllers;

use App\Models\Selectable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SelectableController extends Controller
{
    public function index(Request $request, $table) {
        try {
            $search = $request->query('search');
            if (!$search) { $search = ''; }
            $query = DB::table($table)->where('description', 'like', '%'.$search.'%')->orwhere('description', 'like', '%'.$search.'%')->get();
            return response()->json([
                'message' => 'ok',
                'data' => $query
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            if (strpos($e->getMessage(), '942')) {
                return response()->json([
                    'message' => 'Seleccionable no encontrado',
                    'data' => $e->getMessage()
                ], 400);
            }
            return response()->json([
                'message' => 'Error en la consulta',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function create(Request $request, $table) {
        $data = request('selectable');
        $validator = Validator::make($data, Selectable::$rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validacion',
                'data' => $validator->errors()
            ], 400);
        } else {
            $query = DB::table($table)->where('code', '=', $data['code']);
            $model = new Selectable($data, $table);
            $model->own_system = false;
            if (!$query->get()->isEmpty()) {
                $query->update($data);
            } else {
                $model->save();
            }
        }
        return response()->json([
            'data' => $model
        ]);
    }

    public function delete(Request $request, $table, $code) {
        $query = DB::table($table)->where('code', '=', $code);
        if (!$query->get()->isEmpty()) {
            $query->delete();
        } else {
            return response()->json([
                'message' => 'No encontrado',
                'data' => [ 'code' => 'El codigo no existe']
            ], 404);
        }
        return response()->json([
            'message' => 'Registro eliminado'
        ], 204);
    }
}
