<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Facades\JWTAuth;

class Security {

    public static function login($data) {
        $data['user'] = $data['email'];
        $response = Http::withHeaders([
            'key' => env('SECURITY_PRIVATE_KEY', '---any-key-jet---'),
        ])->post(env('SECURITY_URL', '---any-url-yet---').'auth/login-user', $data);
        if ($response->status() != 200) { return null; }
        $token = json_decode($response->body())->token;
        $jwtPayload = json_decode(base64_decode($token));
        return $jwtPayload->user_data;
    }

}