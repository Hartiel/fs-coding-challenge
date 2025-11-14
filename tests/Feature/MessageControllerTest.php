<?php

use App\Models\Channel;
use App\Models\Contact;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertSessionHasErrors;
use function Pest\Laravel\post;

// Limpa o banco de dados a cada teste
uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

// Cria os canais e um contato antes de cada teste
beforeEach(function () {
    $this->channel = Channel::factory()->create(['name' => 'whatsapp']);
    $this->contact = Contact::factory()->create();
});


test('fail to send message with no content', function () {
    $data = [
        'contact_id' => $this->contact->id,
        'channel_id' => $this->channel->id,
        'content' => '',
    ];

    $response = post(route('messages.store'), $data);

    $response->assertStatus(302);
    $response->assertSessionHasErrors('content');
});


test('send message and update status to sent', function () {
    $data = [
        'contact_id' => $this->contact->id,
        'channel_id' => $this->channel->id,
        'content' => 'Test message',
    ];

    $this->mock(ChannelInterface::class, function ($mock) {
        $mock->shouldReceive('send')
            ->once()
            ->withArgs(function ($message) {
                $message->status = 'sent';
                $message->save();
                return true;
            });
    });

    // Linka o service para usar o mock
    app()->bind('messaging.channel.whatsapp', fn() => app(ChannelInterface::class));

    $response = post(route('messages.store'), $data);

    $response->assertStatus(302);
    
    assertDatabaseHas('messages', [
        'content' => 'Test message',
        'status' => 'sent',
    ]);
});


test('update message status to error', function () {
    $data = [
        'contact_id' => $this->contact->id,
        'channel_id' => $this->channel->id,
        'content' => 'Fail test',
    ];

    $this->mock(ChannelInterface::class, function ($mock) {
        $mock->shouldReceive('send')
            ->once()
            ->andThrow(new \Exception('Fail test'));
    });

    // Linka o service para usar o mock
    app()->bind('messaging.channel.whatsapp', fn() => app(ChannelInterface::class));

    $response = post(route('messages.store'), $data);

    $response->assertStatus(302);

    assertDatabaseHas('messages', [
        'content' => 'Fail test',
        'status' => 'error',
    ]);
});
