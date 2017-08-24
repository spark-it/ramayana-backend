<?php

namespace BrPayments;

use BrPayments\Requests\RequestInterface;
use GuzzleHttp\Client;
use BrPayments\Requests\RequestInterface as Request;


use BrPayments\OrderInterface as Order;

//include __DIR__ . 'OrderInterface.php';


class MakeRequest
{

    private $client;
    private $request;

    public function __construct(RequestInterface $request)
    {
        $this->client = new Client;
        $this->request = $request;
    }

    public function make(Order $order, $sandbox = null)
    {
        $response = $this->client->request(
            $this->request->getMethod(),
            $this->request->getUrl($order, $sandbox),
            $this->request->config()
        );

        return $response->getBody();
    }
}