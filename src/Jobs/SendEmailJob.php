<?php

namespace Wednesdev\Mail\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Wednesdev\Mail\Services\EmailService;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected $mailable,
        protected $templateName,
        protected $to,
        protected $data = [],
        protected $locale = null)
    {
        //
    }

    /**
     * @param EmailService $emailService
     * @return void
     */
    public function handle(EmailService $emailService): void
    {
        $emailService->send(
            $this->mailable,
            $this->templateName,
            $this->to,
            $this->data,
            $this->locale
        );
    }
}
