<?php

namespace Wednesdev\Mail\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Wednesdev\Mail\Events\EmailFailed;

class NotifyAdminOnEmailFailure
{
    /**
     * @param EmailFailed $event
     * @return void
     */
    public function handle(EmailFailed $event): void
    {
        Log::info('Email sent', [
            'email_id' => $event->email->id,
            'recipient' => $event->email->recipient_email,
            'email_template_id' => $event->email->email_template_id,
            'exception' => $event->exception->getMessage(),
        ]);
//        Notification::route('mail', config('mail.admin_email'))
//            ->notify(new EmailFailureNotification($event->email, $event->exception));
    }
}
