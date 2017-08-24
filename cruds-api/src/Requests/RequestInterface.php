<?php

namespace BrPayments\Requests;
use BrPayments\OrderInterface  as Order;

interface RequestInterface
{
    public function getUrl(Order $order, $sandbox = null);
    public function getMethod();
    public function config(Order $order = null);

}