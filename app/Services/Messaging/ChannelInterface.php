<?php

namespace App\Services\Messaging;

use App\Models\Message;

interface ChannelInterface
{
    /**
     * Simula o envio de uma mensagem.
     * Deve atualizar o status da mensagem ou erro.
     *
     * @param Message $message O registro da mensagem a ser enviada.
     * @throws \Exception Em caso de falha de envio simulada.
     */
    public function send(Message $message): void;
}