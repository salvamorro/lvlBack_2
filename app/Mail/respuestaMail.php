<?php

namespace App\Mail;

use App\Models\Inc;
use App\Models\Respuesta;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use MailerSend\Helpers\Builder\Personalization;
use MailerSend\Helpers\Builder\Variable;

class respuestaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mensaje;
    public $titulo;

    public $user;

    public $tipo;
    /**
     * Create a new message instance.
     */
    public function __construct( string $mensaje,  string $titulo,  User $user, $tipo)
    {
        $this->mensaje= $mensaje;
        $this->titulo = $titulo;
        $this->user = $user;
        $this->tipo = $tipo;
        }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Answer from: '.$this->user->nombre.' '.$this->user->apellidos

        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {

        return new Content(
            view: '/emails/RespuestaInc',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
