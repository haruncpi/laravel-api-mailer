<?php

namespace Haruncpi\LaravelApiMailer\Services;

use Haruncpi\LaravelApiMailer\Contract\Sendable;

class SendGrid extends Sendable
{
    protected $key;
    protected $serviceUrl = 'https://api.sendgrid.com/v3/mail/send';

    public function __construct()
    {
        $this->key = config('api-mailer.drivers.sendgrid.api_key');
    }

    public function makePayload($payload)
    {
        $toArray = [];

        if (array_key_exists("from", $payload)) {
            $from = makeEmailString($payload["from"]);
        } else {
            $from = makeEmailString(config('api-mailer.from'));
        }

        if (array_key_exists("to", $payload)) {
            $to = $payload["to"];
            if (is_string($to)) {
                array_push($toArray, ["email" => $to]);
            }
            if (is_array($to)) {
                for ($i = 0; $i < count($to); $i++) {
                    array_push($toArray, ["email" => $to[$i]]);
                }
            }
        }

        if (array_key_exists("subject", $payload)) {
            $subject = $payload["subject"];
        }
        if (array_key_exists("body", $payload)) {
            $body = $payload["body"];
        }
        $data = [
            "personalizations" => [["to" => $toArray, "subject" => $subject]],
            "from"             => ["email" => $from],
            "content"          => [
                [
                    "type"  => "text/html",
                    "value" => $body
                ]
            ]
        ];

        return json_encode($data);
    }


    public function send($payload)
    {

        $data = $this->makePayload($payload);

        $headers = array();
        $headers[] = 'Content-type: application/json';
        $headers[] = 'Authorization: Bearer ' . $this->key;

        $ch = curl_init($this->serviceUrl);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        //status 202 accepted
        if ($statusCode == 202) {
            return ['success' => true, 'message' => 'successfully send'];
        } else {
            $message = json_decode($result)->errors[0]->message;
            return ['success' => false, 'message' => $message];
        }
    }
}