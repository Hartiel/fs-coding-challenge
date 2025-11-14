<?php

namespace App\Jobs;

use App\Models\Message;
use App\Services\Messaging\ChannelInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Message $message)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $channelName = $this->message->channel->name;

        try {
            $service = app("messaging.channel.{$channelName}");
            $service->send($this->message);

        } catch (\Exception $e) {
            Log::error("Falha ao processar envio: {$e->getMessage()}", [
                'message_id' => $this->message->id,
                'channel' => $channelName
            ]);

            $this->message->update(['status' => 'error']);
        }
    }
}
