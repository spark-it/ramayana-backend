<?php

namespace BrPayments;

use Illuminate\Support\Facades\Notification;

include_once __DIR__ . '/../../src/Notifications/PagSeguro.php';
include_once __DIR__ . '/../../src/Requests/PagSeguro.php';
include_once __DIR__ . '/../../src/Requests/PagSeguroNotification.php';
include_once __DIR__ . '/../../src/MakeRequest.php';

class PagSeguroNotificationTest extends \PHPUnit_Framework_TestCase
{
    public function testPagSeguroRequest()
    {
        $access = [
            'email' => 'professorramayana@gmail.com',
            'token' => '6C461B67BD034EE8A9B1BA64E40F744D',
            'notificationCode' => 'ED09A032F31FF31FC56774749FA063FF5CF3'

        ];

        $pag_seguro = new Notifications\PagSeguro($access);

        $pag_seguro_request = new Requests\PagSeguroNotification;

        $response = (new MakeRequest($pag_seguro_request))->make($pag_seguro, true);

        $result= (string)$response;

        $this->assertTrue(is_string($result));


    }
}