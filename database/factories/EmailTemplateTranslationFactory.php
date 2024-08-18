<?php

namespace Wednesdev\Mail\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Wednesdev\Mail\Models\EmailTemplate;
use Wednesdev\Mail\Models\EmailTemplateTranslation;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class EmailTemplateTranslationFactory extends Factory
{
    protected $model = EmailTemplateTranslation::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        return [
            'email_template_id' => EmailTemplate::factory(),
            'locale' => $this->faker->languageCode(),
            'subject' => $this->faker->sentence(),
            'body' => $this->faker->paragraph(),
        ];
    }
}
