<?php
namespace Haruncpi\LaravelApiMailer\Contract;

abstract class Sendable{
    public function send($payload){}
    public function makePayload($payload){}
}