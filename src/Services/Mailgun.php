<?php

namespace Haruncpi\LaravelApiMailer\Services;

use Haruncpi\LaravelApiMailer\Contract\Sendable;

class Mailgun extends Sendable
{
    protected $key;
    protected $serviceUrl, $domain;

    public function __construct()
    {
        $this->domain = config('api-mailer.drivers.mailgun.domain');
        $this->serviceUrl = "https://api.mailgun.net/v3/" . $this->domain . "/messages";
        $this->key = config('api-mailer.drivers.mailgun.api_key');
    }

    public function makePayload($payload)
    {
        $toString = null;

        if (array_key_exists("from", $payload)) {
            $from = makeEmailString($payload["from"]);
        } else {
            $from = makeEmailString(config('api-mailer.from'));
        }

        if (array_key_exists("to", $payload)) {
            $to = $payload["to"];
            if (is_string($to)) {
                $toString = $payload["to"];
            }
            if (is_array($to)) {
                $toString = implode(",", $payload["to"]);
            }
        }

        if (array_key_exists("subject", $payload)) {
            $subject = $payload["subject"];
        }
        if (array_key_exists("body", $payload)) {
            $body = $payload["body"];
        }
        $data = [
            "from"    => $from,
            "to"      => $toString,
            "subject" => $subject,
            "html"    => $body
        ];

        if (array_key_exists("cc", $payload)) {
            $cc = $payload["cc"];
            if (is_string($cc)) {
                $data['cc'] = $payload["cc"];
            }
            if (is_array($cc)) {
                $data['cc'] = implode(",", $payload["cc"]);
            }
        }

        return $data;
    }


    public function send($payload)
    {

        $data = $this->makePayload($payload);

        $ch = curl_init($this->serviceUrl);
        curl_setopt($ch, CURLOPT_USERPWD, "api:" . $this->key);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $result = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);


        curl_close($ch);


        //status 200 success
        if ($statusCode == 200) {
            return ['success' => true, 'message' => 'successfully send'];
        } else {
            $message = json_decode($result)->message;
            return ['success' => false, 'message' => $message];
        }
    }
}