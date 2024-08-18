<?php

namespace Wednesdev\Mail\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static queue($mailable, string $templateName, string $to, array $data = [], string $locale = null)
 * @method static send($mailable, string $templateName, string $to, array $data = [], string $locale = null)
 */
class Email extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'email';
    }
}