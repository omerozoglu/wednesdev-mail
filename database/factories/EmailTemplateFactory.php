<?php

namespace Wednesdev\Mail\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Wednesdev\Mail\Models\EmailTemplate;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class EmailTemplateFactory extends Factory
{
    protected $model = EmailTemplate::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'description' => $this->faker->sentence(),
        ];
    }
}

