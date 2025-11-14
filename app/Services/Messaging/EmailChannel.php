<?php

namespace App\Services\Messaging;

use App\Models\Message;
use Illuminate\Support\Facades\Log;

class EmailChannel implements ChannelInterface
{
    public function send(Message $message): void
    {
        sleep(rand(1, 3));

        // Tenta falhar a mensagem com 5% de chance de acontecer
        if (rand(1, 20) === 1) {
            Log::error("Falha ao enviar Email: {$message->id}");
            throw new \Exception('Falha: Conexão SMTP recusada');
        }

        // 3. Sucesso
        $message->status = 'sent';
        $message->sent_at = now();
        $message->save();

        Log::info("Email enviado: {$message->id}");
    }
}