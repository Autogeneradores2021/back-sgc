<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Services\Security;
use App\Models\Employee;
use App\Models\SecurityUser;
use App\Models\Selectable;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login',]]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {

        $credentials = request(['email', 'password']);

        $credentials['email'] = strtolower($credentials['email']);

        $payload = Security::login($credentials);

        $user = User::query()->where(['email' => strtolower($credentials['email'])])->first();

        if ($payload) {
            $person = $payload->user_data->person;
            $actions = $payload->actions;
            $user = User::query()->where(['identification_number' => $person->document_number])->first();
            Log::info('CONEXIÓN CON SEGURIDAD TRANSVERSAL CONFIRMADA');
            if ($user) {
                Log::info('EXISTE REGISTRO DE USUARIO');
                $area_code = $user->area_code;
                $position_code = $user->position_code;
                // $employee = null;
                $employee = Employee::query()->where('estado', 'ACTIVO')->where('codigo', $person->document_number)->orderBy('codigo', 'desc')->first();
                if ($employee) {
                    Selectable::createIfNotExist('areas', $employee->division, $employee->division);
                    Selectable::createIfNotExist('positions', $employee->cargo, $employee->cargo);
                    $area_code= $employee->division;
                    $position_code = $employee->cargo;
                }
                $user->phone_number = $person->cellphone ? $person->cellphone : $person->telephone;
                $user->state_code = $person->state == 1 ? 'ACTIVE' : 'DISABLE';
                $user->password = Hash::make($credentials['password']);
                $user->area_code = $area_code;
                $user->position_code = $position_code;
                $user->email = strtolower($person->email);
                $user->save();
                Log::info('USUARIO ACTUALIZADO');
                Log::info($user);
            } else {
                Log::info('NO EXISTE REGISTRO DE USUARIO');
                // $employee = null;
                $employee = Employee::query()->where('estado', 'ACTIVO')->where('codigo', $person->document_number)->orderBy('codigo', 'desc')->first();
                $area_code = 'EXTERNO';
                $position_code = 'EN_MISION';
                $role = 'BOLSA';
                if ($employee) {
                    Selectable::createIfNotExist('areas', $employee->division, $employee->division);
                    Selectable::createIfNotExist('positions', $employee->cargo, $employee->cargo);
                    $area_code = $employee->division;
                    $position_code = $employee->cargo;
                    $role = 'NOMINA';
                }
                $user = new User([
                    'name' => strtoupper($person->contractor_company ? $person->contractor_company : $person->first_name.' '.$person->second_name.' '.$person->first_lastname.' '.$person->second_lastname),
                    'email' => strtolower($person->email),
                    'phone_number' => $person->cellphone ? $person->cellphone : $person->telephone,
                    'identification_type' => $person->document_type->code,
                    'identification_number' => $person->document_number,
                    'role_code' => $role,
                    'area_code' => $area_code,
                    'position_code' => $position_code,
                ]);
                $user->state_code = $person->state == 1 ? 'ACTIVE' : 'DISABLE';
                $user->password = Hash::make($credentials['password']);
                $user->save();
                Log::info('USUARIO REGISTRADO');
                Log::info($user);
            }
            $user->actions = json_encode($actions);
            $user->save();
        }

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($user && $user->state_code == 'DISABLE') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }


        return $this->respondWithToken($token, $payload ? 'Online': 'Offline');
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh(), 'Offline');
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $state)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'eh_state' =>  $state
        ]);
    }
}