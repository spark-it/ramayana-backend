<?php

namespace BrPayments\Requests;

use BrPayments\OrderInterface as Order;

include_once 'RequestInterface.php';

abstract class RequestAbstract implements RequestInterface
{

    private $child_const;


    public function getUrl(Order $order, $sandbox = null)
    {
        if ($sandbox) {
            return $this->getChildConstants('url_sandbox') . (string)$order;
//            return PagSeguro::URL_SANDBOX . '?' . (string)$order;
        }

        if (!strpos($this->getChildConstants('url'), 'notification')) {
            return $this->getChildConstants('url') . '?' . (string)$order;
        } else {
            return $this->getChildConstants('url') . (string)$order;
        }



//        return PagSeguro::URL . '?' . (string)$order;
    }


    public function getMethod()
    {
        return $this->getChildConstants('method');
//        return PagSeguro::METHOD;
    }

    private function getChildConstants($const)
    {
        if (!$this->child_const) {
            $child = get_class($this);
            $this->child_const = [
                'url' => constant($child . '::URL'),
                'url_sandbox' => constant($child . '::URL_SANDBOX'),
                'method' => constant($child . '::METHOD'),
            ];
        }

        return $this->child_const[$const];
    }
}