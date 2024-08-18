<?php

namespace Wednesdev\Mail\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Wednesdev\Mail\Models\Email;
use Wednesdev\Mail\Models\EmailTemplate;

class EmailFactory extends Factory
{
    protected $model = Email::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        return [
            'email_template_id' => EmailTemplate::factory(),
            'mailable_id' => $this->faker->uuid,
            'mailable_type' => $this->faker->randomElement(['App\Models\User', 'App\Models\Order']),
            'recipient_email' => $this->faker->safeEmail,
            'locale' => $this->faker->languageCode,
            'status' => $this->faker->randomElement([0, 1, 2]), // Assuming 0: queued, 1: sent, 2: failed
            'sent_at' => $this->faker->optional()->dateTimeThisMonth,
        ];
    }

    /**
     * Indicate that the email has been sent.
     */
    public function sent(): EmailFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 1,
                'sent_at' => $this->faker->dateTimeThisMonth,
            ];
        });
    }

    /**
     * Indicate that the email has failed.
     */
    public function failed(): EmailFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 2,
                'sent_at' => null,
            ];
        });
    }

    /**
     * Indicate that the email is queued.
     */
    public function queued(): EmailFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 0,
                'sent_at' => null,
            ];
        });
    }
}