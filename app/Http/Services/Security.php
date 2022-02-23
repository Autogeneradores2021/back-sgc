<?php

use Illuminate\Support\Facades\Http;

class Security {

    public static string $URL = '';

    public static function login($data) {
        $response = Http::post(Security::$URL, $data);
        if ($response->staus == 200) {
            return 'ok';
        } else {
            return 'Error';
        }
    }

}