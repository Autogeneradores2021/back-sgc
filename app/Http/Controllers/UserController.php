<?php

namespace App\Http\Controllers;

use App\Models\Filter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function create()
    {
        $data = request(['email', 'name', 'identification_number', 'identification_type', 'phone_number']);

        if (!$data) {
            return response()->json(['error' => 'Incomplete data'], 400);
        }
        $data['email'] = strtolower($data['email']);
        $validator = Validator::make($data, User::$rules);
        Log::info($validator->errors());
        if ($validator->fails()) {
            Log::info($validator->errors());
            return response()->json([
                "message" => "Email ya esta en uso",
                "data" => $validator->errors()
            ],
            400);
        } else {
            $user = new User($data);
            $user->password = Hash::make($user->identification_number);
            $user->role_code = 'EXTERNO';
            $user->position_code = 'EXTERNO';
            $user->area_code = 'EXTERNO';
            $user->state_code = 'ACTIVE';
            $user->actions = json_encode([
                "sistema-gestion-de-calidad-parametros-configuracion-consultar",
                "sistema-gestion-de-calidad-parametros-configuracion-acceder",
                "sistema-gestion-de-calidad-solicitudes-solicitudes-sgc-consultar",
                "sistema-gestion-de-calidad-parametros-configuracion-seleccionable-acceder",
                "sistema-gestion-de-calidad-parametros-configuracion-entidad-externa-acceder",
                "sistema-gestion-de-calidad-solicitudes-solicitud-acceder",
                "sistema-gestion-de-calidad-solicitudes-solicitud-listar",
                "sistema-gestion-de-calidad-modulo-principal-detalle-parcial",
                "sistema-gestion-de-calidad-modulo-principal-detalle-completo",
                "sistema-gestion-de-calidad-modulo-principal-atencion-acceder",
                "sistema-gestion-de-calidad-modulo-principal-seguimiento-acceder",
                "sistema-gestion-de-calidad-modulo-principal-evaluacion-acceder"
            ]) ;
            $user->save();
            return response()->json([
                'message' => 'successfuly',
                'user' => $user
            ], 200);
        }
    }

    public function updateState(Request $_, $id) {
        $user = User::query()->where('id', $id)->first();
        $user->state_code = request(['state'])['state'] ? 'ACTIVE' : 'DISABLE';
        $user->save();
        return response()->json([
            'message' => 'successfuly',
            'user' => $user
        ], 200);
    }

    public function updateRole(Request $request, $id) {
        $input = $request->all();
        $currentUser = $request->user();
        if (!$currentUser->role_code == 'ADMIN') {
            return response()->json(['message' => 'Este usuario no es administrador'], 400);
        }
        $user = User::where('id',$id)->first();
        if ($input['role_code']) {
            $user->role_code = $input['role_code'];
            $user->save();
        }
        return response()->json([
            'message' => 'ok',
            'data' => $user
        ]);
    }

    public function getMembers() {
        return response()->json([
            'message' => 'ok',
            'data' => User::query()->whereIn('role_code', ['ADMIN', 'AUDITOR'])->get(),
        ]);
    }

    public function getExternal() {
        return response()->json([
            'message' => 'ok',
            'data' => User::query()->whereIn('role_code', ['EXTERNO'])->get(),
        ]);
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $exclude = $request->query('exclude');
        $filter = $request->query('filter');
        if (!$search) { $search = ''; }
        if (!$exclude) { $exclude = []; }
        $search = strtoupper($search);
        $query = User::query()->where('name', 'like', '%'.$search.'%')->whereNotIn('id', $exclude)->orderBy('name')->limit(10);
        if ($filter) {
            $query = $query->where(function($q) use ($filter) {
                $filters = Filter::query()->where('type', $filter)->get();
                foreach ($filters as $value) {
                    $q->orWhereRaw($value->query);
                }
            });
        }
        return response()->json([
            'message' => 'ok',
            'data' => $query->get()
        ]);
    }

    public function retrive(Request $request) {
        return response()->json([
            'message' => 'ok',
            'data' => $request->user()
        ]);
    }

    public function permission(Request $request, $name) {
        $user = $request->user();
        if ($user) {
            return response()->json([
                'permissionGaranted' =>  in_array($name, $user->permissions)  
            ]);
        }
        return response()->json([
            'permissionGaranted' => false  
        ]);
    }


    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
