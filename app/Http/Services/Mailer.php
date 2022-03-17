<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Mailer {

    public static function sendNewRequestNotification($request, $to) {
        try {
            Mail::send('mail.simple-notification', [
                'title' => 'Te han asignado una nueva solicitud',
                'msg' => 'Parece que tienes una nueva solicitud recuerda atenderla antes que se venza',
                'linkUrl' => `/acciones-mejoramiento/`
            ], function ($message) use ($to) {
                $message->to($to);
                $message->subject('Nueva solicitud asignada');
                $message->priority(1);
            });
        } catch (\Throwable $th) {
            Log::info('Ocurrio un error enviando el correo electornico');
        }
    }

}