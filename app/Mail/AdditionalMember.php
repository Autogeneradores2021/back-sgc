<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdditionalMember extends Mailable
{
    use Queueable, SerializesModels;

    public $member;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($member)
    {
        $this->member = $member;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.add-member')->with([
            'title' => 'Nuevo integrante de equipo de trabajo',
            'msg' => 'Se ha asignado un nuevo integrante al equipo de trabajo',
            'linkUrl' => `/acciones-mejoramiento/`,
            'request' => $this->member
        ])->subject('Nuevo integrante')->priority(1);
    }
}
