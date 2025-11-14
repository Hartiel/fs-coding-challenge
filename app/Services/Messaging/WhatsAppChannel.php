<?php

namespace App\Services\Messaging;

use App\Models\Message;
use Illuminate\Support\Facades\Log;

class WhatsAppChannel implements ChannelInterface
{
    public function send(Message $message): void
    {
        // Simula o delay
        sleep(rand(1, 3));

        // Tenta falhar a mensagem com 5% de chance de acontecer
        if (rand(1, 20) === 1) {
            Log::error("Falha ao enviar WhatsApp: {$message->id}");
            throw new \Exception('Falha: WhatsApp API indisponível');
        }

        $message->status = 'sent';
        $message->sent_at = now();
        $message->save();

        Log::info("WhatsApp enviado: {$message->id}");
    }
}