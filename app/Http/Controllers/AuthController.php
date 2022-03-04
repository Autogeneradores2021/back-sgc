<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Services\Security;
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
        $this->middleware('auth:api', ['except' => ['login']]);
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

        if ($payload) {
            $person = $payload->person;
            $colection = User::query()->where(['email' => strtolower($person->email)])->get();
            Log::info('CONECCION CON SEGURIDAD TRANSVERSAL CONFIRMADA');
            if (count($colection) != 0) {
                Log::info('EXISTE REGISTRO DE USUARIO');
                $user = $colection[0];
                $user->phone_number = $person->cellphone ? $person->cellphone : $person->telephone;
                $user->save();
                Log::info('USUARIO ACTUALIZADO');
                Log::info($user);
            } else {
                Log::info('NO EXISTE REGISTRO DE USUARIO');
                $user = new User([
                    'name' => $person->contractor_company ? $person->contractor_company : $person->first_name.' '.$person->second_name.' '.$person->first_lastname.' '.$person->second_lastname,
                    'email' => strtolower($person->email),
                    'phone_number' => $person->cellphone ? $person->cellphone : $person->telephone,
                    'identification_type' => $person->document_type->code,
                    'identification_number' => $person->document_number,
                    'role_code' => 'USER',
                ]);
                $user->password = Hash::make($credentials['password']);
                $user->save();
                Log::info('USUARIO REGISTRADO');
                Log::info($user);
            }
        }

        if (! $token = auth()->attempt($credentials)) {
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