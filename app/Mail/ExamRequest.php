<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExamRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $finish_request;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($finish_request)
    {
        $this->finish_request = $finish_request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.add-quiz')->with([
            'title' => 'Ya tenemos el resultado de solicitud',
            'msg' => 'Todos los pasos para tu solicitud fueron completados y ya tenemos disponible un resultado gracias a una evaluación',
            'linkUrl' => `/acciones-mejoramiento/`,
            'finish_request' => $this->finish_request
        ])->subject('Resultado de solicitud')->priority(1);
    }
}
