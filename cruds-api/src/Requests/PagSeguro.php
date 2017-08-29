<?php

namespace BrPayments\Requests;

use BrPayments\OrderInterface as Order;


include_once 'RequestInterface.php';
include_once 'RequestAbstract.php';

class PagSeguro extends RequestAbstract
{
    const URL = 'https://ws.pagseguro.uol.com.br/v2/checkout';
    const URL_SANDBOX = 'https://ws.sandbox.pagseguro.uol.com.br/v2/checkout';
    const METHOD = 'POST';

    const URL_FINAL = 'https://pagseguro.uol.com.br/v2/checkout/payment.html';
    const URL_FINAL_SANDBOX = 'https://sandbox.pagseguro.uol.com.br/v2/checkout/payment.html';


    public function getUrlFinal($code, $sandbox = null)
    {
        if ($sandbox) {
            return PagSeguro::URL_FINAL_SANDBOX . '?' . (string)$code;
        }
        return PagSeguro::URL_FINAL . '?' . (string)$code;
    }

    public function config(Order $order = null)
    {
        return  [
            'form_params' => []
        ];
    }
}