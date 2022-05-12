<?php

namespace App\Http\Controllers;

use App\Models\Selectable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SelectableController extends Controller
{
    public function index(Request $request, $table) {
        try {
            $search = $request->query('search');
            $all = $request->query('all');
            if (!$search) { $search = ''; }
            Log::info($all);
            if (!$all) {
                $query = DB::table($table)->where('enabled', '1')->where(function($query) use ($search){
                    $query->where('code', 'like', '%'.$search.'%')->orWhereRaw("UPPER(description) like '%".strtoupper($search)."%'");
                })->orderBy('description', 'asc')->limit(50)->get();
            } else {
                $query = DB::table($table)->orderBy('description', 'asc')->get();
            }
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
                'message' => 'Error de validación',
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
            'message' => 'ok',
            'data' => $data
        ]);
    }

    public function delete(Request $request, $table, $code) {
        $query = DB::table($table)->where('code', '=', $code);
        if (!$query->get()->isEmpty()) {
            try {
                $query->delete();
            } catch (\Illuminate\Database\QueryException $e) {
                if (strpos($e->getMessage(), '2292')) {
                    return response()->json([
                        'message' => 'Ya existen solicitudes que utilizan este seleccionable. Si desea eliminarlo porfavor elimine los registros necesarios y vuelva a intentarlo.',
                        'data' => $e->getMessage()
                    ], 400);
                }
                return response()->json([
                    'message' => 'Error en la consulta',
                    'data' => $e->getMessage()
                ], 500);
            }
        } else {
            return response()->json([
                'message' => 'No encontrado',
                'data' => [ 'code' => 'El codigo no existe']
            ], 400);
        }
        return response()->json([
            'message' => 'Registro eliminado'
        ], 204);
    }
}
