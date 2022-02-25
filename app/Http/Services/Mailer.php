<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Mail;

class Mailer {

    public static function sendRequestNotification($data, $to) {
        Mail::send('Html.view', $data, function ($message) use ($data, $to) {
            $message->to($to->email);
            $message->subject($data['subject']);
            $message->priority(1);
            $message->attach('pathToFile');
        });
    }

}