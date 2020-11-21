<?php

namespace Haruncpi\LaravelApiMailer;

use Haruncpi\LaravelApiMailer\Services\Mailgun;
use Haruncpi\LaravelApiMailer\Services\SendGrid;
use Haruncpi\LaravelApiMailer\Services\SendInBlue;

class ApiMailer
{
    protected $driver;
    protected $mailService;
    protected $mailServiceProviders = ['sendgrid', 'mailgun','sendinblue'];

    public function __construct()
    {
        $this->driver = config('api-mailer.default');
        if (!in_array($this->driver, $this->mailServiceProviders)) {
            throw new \Exception('Invalid mail-service provider. You can choose .'.implode(",",$this->mailServiceProviders));
        }

        switch ($this->driver) {
            case 'sendgrid':
                $this->mailService = new SendGrid();
                break;
            case 'mailgun':
                $this->mailService = new Mailgun();
                break;
            case 'sendinblue':
                $this->mailService = new SendInBlue();
                break;
        }
    }

    private function validatePayload($payload)
    {
        if (!array_key_exists("to", $payload)) {
            throw  new \Exception('to address required');
        }
        if (!array_key_exists("subject", $payload)) {
            throw  new \Exception('email subject required');
        }
        if (!array_key_exists("body", $payload)) {
            throw  new \Exception('email body required');
        }
    }

    /**
     * @param $payload
     * @return array|void
     * @throws \Exception
     */
    public function send($payload)
    {
        $this->validatePayload($payload);
        return $this->mailService->send($payload);
    }
}