<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AssignationAction extends Mailable
{
    use Queueable, SerializesModels;

    public $uplan;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($uplan)
    {
        $this->uplan = $uplan;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.add-action')->with([
            'title' => 'Nueva acción',
            'msg' => 'Se ha asignado un nuevo responsable de una acción correctiva',
            'linkUrl' => `/acciones-mejoramiento/`,
            'uplan' => $this->uplan
        ])->subject('Nuevo responsable de una acción correctiva')->priority(1);
    }
}
