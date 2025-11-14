<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Messaging\EmailChannel;
use App\Services\Messaging\WhatsAppChannel;

class MessagingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('messaging.channel.whatsapp', fn() => new WhatsAppChannel());
        $this->app->bind('messaging.channel.email', fn() => new EmailChannel());
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
