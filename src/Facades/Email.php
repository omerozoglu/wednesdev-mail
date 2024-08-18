<?php

namespace Wednesdev\Mail\Facades;

use Illuminate\Support\Facades\Facade;

class Email extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'email';
    }
}