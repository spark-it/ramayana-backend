<?php

namespace BrPayments\Notifications;

use BrPayments\OrderInterface;
include_once __DIR__ . '/../OrderInterface.php';

class PagSeguro implements OrderInterface
{

    protected $config;

    public function __construct($config)

    {
        $this->config = $config;
    }

    public function __toString()
    {
        $access = [
            'email' => $this->config['email'],
            'token' => $this->config['token']
        ];
        $http_query = http_build_query($access);
        return $this->config['notificationCode'] . '?' . $http_query;
    }
}