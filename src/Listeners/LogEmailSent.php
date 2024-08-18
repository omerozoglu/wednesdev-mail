<?php

namespace Wednesdev\Mail\Listeners;

use Illuminate\Support\Facades\Log;
use Wednesdev\Mail\Events\EmailSent;

class LogEmailSent
{
    /**
     * @param EmailSent $event
     * @return void
     */
    public function handle(EmailSent $event): void
    {
        Log::info('Email sent', [
            'email_id' => $event->email->id,
            'recipient' => $event->email->recipient_email,
            'email_template_id' => $event->email->email_template_id,
        ]);
    }
}
