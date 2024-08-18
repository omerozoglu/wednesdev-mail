<?php

namespace Wednesdev\Mail\Events;

use Exception;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Wednesdev\Mail\Models\Email;

class EmailFailed
{
    use Dispatchable, SerializesModels;

    /**
     * @param Email $email
     * @param Exception $exception
     */
    public function __construct(public Email $email, public Exception $exception)
    {

    }
}
