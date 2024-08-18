<?php

namespace Wednesdev\Mail\Services;

use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Mail\Mailer;
use Wednesdev\Mail\Events\EmailFailed;
use Wednesdev\Mail\Events\EmailSent;
use Wednesdev\Mail\Jobs\SendEmailJob;
use Wednesdev\Mail\Models\Email;
use Wednesdev\Mail\Models\EmailTemplate;

class EmailService
{

    public function __construct(protected Mailer $mailer)
    {

    }

    /**
     * @param $mailable
     * @param string $templateName
     * @param string $to
     * @param array $data
     * @param string|null $locale
     * @return void
     */
    public function queue($mailable, string $templateName, string $to, array $data = [], string $locale = null): void
    {
        SendEmailJob::dispatch($mailable, $templateName, $to, $data, $locale);
    }

    /**
     * @param $mailable
     * @param string $templateName
     * @param string $to
     * @param array $data
     * @param string|null $locale
     * @return Email
     * @throws Exception
     */
    public function send($mailable, string $templateName, string $to, array $data = [], string $locale = null): Email
    {
        $locale = $locale ?? app()->getLocale();
        $template = EmailTemplate::where('name', $templateName)->firstOrFail();

        $email = new Email([
            'recipient_email' => $to,
            'email_template_id' => $template->id,
            'locale' => $locale,
            'status' => 0 // 0 is queued, TODO: it must be an enum
        ]);

        $mailable->emails()->save($email);

        $translation = $template->translations()
            ->where('locale', $locale)
            ->firstOrFail();

        $subject = $this->replacePlaceholders($translation->subject, $data);
        $body = $this->replacePlaceholders($translation->body, $data);

        try {
            $this->mailer->send([], [], function ($message) use ($to, $subject, $body) {
                $message->to($to)
                    ->subject($subject)
                    ->html($body);
            });

            $email->update(['status' => 1, 'sent_at' => now()]);// 1 is Sent //TODO:To Enum
            event(new EmailSent($email));
        } catch (\Exception $e) {
            $email->update(['status' => 2]); //2 is Failed//TODO:To Enum
            event(new EmailFailed($email, $e));
            throw $e;
        }

        return $email;
    }

    /**
     * @param $content
     * @param $data
     * @return array|string|null
     */
    protected function replacePlaceholders($content, $data): array|string|null
    {
        return preg_replace_callback('/\{(\w+)}/', function ($matches) use ($data) {
            return $data[$matches[1]] ?? $matches[0];
        }, $content);
    }
}