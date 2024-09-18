<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Log;

class EmailSentListener
{
    public function handle(MessageSent $event)
    {
        // Obtenez l'email du destinataire
        $toEmail = $event->message->getTo();

        // Loguer l'envoi
        Log::info('Email envoyé à : ' . json_encode($toEmail));
    }
}
