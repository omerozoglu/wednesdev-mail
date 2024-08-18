<?php

namespace Wednesdev\Mail\Traits;

use Wednesdev\Mail\Models\Email;

/**
 * @method morphMany(string $class, string $string)
 */
trait Emailable
{
    /**
     * @return mixed
     */
    public function emails(): mixed
    {
        return $this->morphMany(Email::class, 'mailable');
    }

    /**
     * @return mixed
     */
    public function latestEmail(): mixed
    {
        return $this->emails()->latest()->first();
    }

    /**
     * @param $templateName
     * @param $recipientEmail
     * @param $data
     * @param $locale
     * @return mixed
     */
    public function sendEmail($templateName, $recipientEmail, $data = [], $locale = null): mixed
    {
        // Assuming you have an Email facade or service
        $email = \YourNamespace\Facades\Email::send($templateName, $recipientEmail, $data, $locale);
        $this->emails()->save($email);
        return $email;
    }
}
