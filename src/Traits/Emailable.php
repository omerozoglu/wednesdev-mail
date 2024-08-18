<?php

namespace Wednesdev\Mail\Traits;

use Illuminate\Support\Facades\Mail;
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
        $email = Mail::send($templateName, $recipientEmail, $data, $locale);
        $this->emails()->save($email);
        return $email;
    }
}
