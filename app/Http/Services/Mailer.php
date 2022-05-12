<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Mailer {

    public static function sendNotification($mailer, $to) {
        try {
            Mail::to('alexander.cruzb@electrohuila.co')->later(5, $mailer);
            // Mail::to($to)->later(5, $mailer);
        } catch (\Throwable $th) {
            Log::info('Ocurrió un error enviando el correo electronica');
        }
    }

}