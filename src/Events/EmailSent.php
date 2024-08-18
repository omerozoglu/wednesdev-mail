<?php

namespace Wednesdev\Mail\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Wednesdev\Mail\Models\Email;

class EmailSent
{
    use Dispatchable, SerializesModels;

    /**
     * @param Email $email
     */
    public function __construct(public Email $email)
    {

    }
}
