<?php


namespace BrPayments\Requests;

use BrPayments\OrderInterface as Order;

include_once 'RequestInterface.php';
include_once 'RequestAbstract.php';

class PagSeguroNotification extends RequestAbstract
{
    const URL = 'https://ws.pagseguro.uol.com.br/v3/transactions/notifications/';
    const URL_SANDBOX = 'https://ws.sandbox.pagseguro.uol.com.br/v3/transactions/notifications/';
    const METHOD = 'GET';

    public function getUrlFinal($code, $sandbox = null)
    {
        if ($sandbox) {
            return PagSeguro::URL_FINAL_SANDBOX  . (string)$code;
        }
        return PagSeguro::URL_FINAL . (string)$code;
    }


    public function config(Order $order = null)
    {
        return [];
    }
}