<?php

namespace BrPayments;

use GuzzleHttp\Client;
use BrPayments\Requests\PagSeguro as Request;
use BrPayments\Payments\PagSeguro as Order;


class MakeRequest
{

    private $client;
    private $request;

    public function __construct()
    {
        $this->client = new Client;
        $this->request = new Request();
    }

    public function post(Order $order, $sandbox = null)
    {
        $response = $this->client->request(
            $this->request->getMethod(),
            $this->request->getUrlCheckout($order, $sandbox),
            [
                'form_params' => []
            ]
        );

        return $response->getBody();
    }
}