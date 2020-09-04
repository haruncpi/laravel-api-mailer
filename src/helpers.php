<?php

if (!function_exists('apiMailer')) {
    function apiMailer()
    {
        return new Haruncpi\LaravelApiMailer\ApiMailer();
    }
}

if (!function_exists('makeEmailString')) {
    //RFC 2822
    /**
     * @param $email
     * @return string
     */
    function makeEmailString($email)
    {
        if (is_string($email)) {
            return $email;
        }
        if (is_array($email)) {
            if (array_key_exists('name', $email) && array_key_exists('address', $email)) {
                return $email['name'] . " <" . $email['address'] . ">";
            }
        }
    }
}