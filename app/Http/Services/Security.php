<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class Security {

    public static function login($data) {
        $data['user'] = $data['email'];
        try {
            $response = Http::timeout(10)->withHeaders([
                'key' => env('SECURITY_PRIVATE_KEY', '---any-key-jet---'),
            ])->post(env('SECURITY_URL', '---any-url-yet---').'auth/login-user', $data);
        } catch (\Throwable $th) {
            return null;
        }
        Log::info("RESPUESTA DE SEGURIDAD TRANSVERSAL");
        Log::info($response->status());
        Log::info($response);
        if ($response->status() != 200) { return null; }
        $token = json_decode($response->body())->token;
        $jwtPayload = json_decode(base64_decode($token));
        return $jwtPayload;
    }

}