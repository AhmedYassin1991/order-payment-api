<?php

namespace App\Services\PaymentGateways;

use App\Models\Payment;

interface PaymentGatewayInterface
{
    public function processPayment(Payment $payment);
}
