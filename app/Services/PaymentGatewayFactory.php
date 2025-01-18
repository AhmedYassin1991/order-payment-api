<?php

namespace App\Services;

use App\Services\PaymentGateways\PaymentGatewayInterface;
use InvalidArgumentException;

class PaymentGatewayFactory
{
    public static function create(string $gateway): PaymentGatewayInterface
    {
        $gatewayClass = config("payment.gateways.$gateway");

        if (! $gatewayClass || ! class_exists($gatewayClass)) {
            throw new InvalidArgumentException("Payment gateway '$gateway' not found.");
        }

        return app($gatewayClass);
    }
}
