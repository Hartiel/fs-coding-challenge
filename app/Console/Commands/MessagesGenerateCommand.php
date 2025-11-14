<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contact;
use App\Models\Message;
use App\Models\Channel;

class MessagesGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'messages:generate {--count=50 : Quantidade de mensagens a serem geradas}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera mensagens fake';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = (int) $this->option('count');

        // Verifica se tem canais e contatos
        if (Contact::count() === 0 || Channel::count() === 0) {
            $this->error('Precisa rodar os seeders primeiro. migrate:fresh --seed');
            return 1;
        }

        $this->info("Gerando $count mensagens...");

        Message::factory($count)->create();

        $this->info('Mensagens criadas.');
        return 0;
    }
}
