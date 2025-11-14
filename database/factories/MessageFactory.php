<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Channel;
use App\Models\Contact;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    public function definition(): array
    {
        $direction = fake()->randomElement(['in', 'out']);
        $status = $direction === 'in' ? 'sent' : fake()->randomElement(['sending', 'sent', 'error']);

        return [
            'contact_id' => Contact::inRandomOrder()->first()->id,
            'channel_id' => Channel::inRandomOrder()->first()->id,
            'content' => fake()->sentence(),
            'direction' => $direction,
            'status' => $status,
            'sent_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
