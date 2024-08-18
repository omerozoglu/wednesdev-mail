<?php

namespace Wednesdev\Mail\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run(): void
    {
        // Create the welcome email template
        $templateId = Str::uuid();

        DB::table('email_templates')->insert([
            'id' => $templateId,
            'name' => 'welcome_email',
            'description' => 'Email sent to new users upon registration',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create translations for the welcome email
        $translations = [
            'en' => [
                'subject' => 'Welcome to Our Platform, {name}!',
                'body' => "
                    <h1>Welcome, {name}!</h1>
                    <p>We're excited to have you on board. Here are a few things you can do to get started:</p>
                    <ul>
                        <li>Complete your profile</li>
                        <li>Explore our features</li>
                        <li>Connect with other users</li>
                    </ul>
                    <p>If you have any questions, feel free to reach out to our support team.</p>
                    <p>Best regards,<br>The {app_name} Team</p>
                "
            ],
            'tr' => [
                'subject' => 'Platformumuza Hoş Geldiniz, {name}!',
                'body' => "
                    <h1>Hoş geldiniz, {name}!</h1>
                    <p>Aramıza katıldığınız için çok heyecanlıyız. Başlamak için yapabileceğiniz birkaç şey:</p>
                    <ul>
                        <li>Profilinizi tamamlayın</li>
                        <li>Özelliklerimizi keşfedin</li>
                        <li>Diğer kullanıcılarla bağlantı kurun</li>
                    </ul>
                    <p>Herhangi bir sorunuz varsa, destek ekibimize ulaşmaktan çekinmeyin.</p>
                    <p>Saygılarımızla,<br>{app_name} Ekibi</p>
                "
            ],
            'es' => [
                'subject' => '¡Bienvenido a Nuestra Plataforma, {name}!',
                'body' => "
                    <h1>¡Bienvenido, {name}!</h1>
                    <p>Estamos emocionados de tenerte a bordo. Aquí hay algunas cosas que puedes hacer para comenzar:</p>
                    <ul>
                        <li>Completa tu perfil</li>
                        <li>Explora nuestras características</li>
                        <li>Conéctate con otros usuarios</li>
                    </ul>
                    <p>Si tienes alguna pregunta, no dudes en contactar a nuestro equipo de soporte.</p>
                    <p>Saludos cordiales,<br>El Equipo de {app_name}</p>
                "
            ],
        ];

        foreach ($translations as $locale => $content) {
            DB::table('email_template_translations')->insert([
                'id' => Str::uuid(),
                'email_template_id' => $templateId,
                'locale' => $locale,
                'subject' => $content['subject'],
                'body' => $content['body'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
