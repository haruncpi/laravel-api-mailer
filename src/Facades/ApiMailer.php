<?php

namespace Haruncpi\LaravelApiMailer\Facades;

use Illuminate\Support\Facades\Facade;


class ApiMailer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Haruncpi\LaravelApiMailer\ApiMailer::class;
    }
}