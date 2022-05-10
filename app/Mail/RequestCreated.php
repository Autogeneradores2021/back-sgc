<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RequestCreated extends Mailable
{
    use Queueable, SerializesModels;

    protected $record;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($record)
    {
        $this->record = $record;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        Log::info('------------------');
        Log::info($this->record);
        Log::info('------------------');
        return $this->view('mail.new-request', [
            'title' => 'Te han asignado una nueva solicitud',
            'msg' => 'Parece que tienes una nueva solicitud recuerda atenderla antes que se venza',
            'linkUrl' => `/acciones-mejoramiento/`,
            'record' => $this->record
        ])->subject('Nueva solicitud asignada')->priority(1);
    }
}
