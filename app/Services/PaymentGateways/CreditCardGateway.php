<?php

namespace App\Services\PaymentGateways;

use App\Models\Payment;

class CreditCardGateway implements PaymentGatewayInterface
{
    public function processPayment(Payment $payment)
    {

        // Simulate credit card payment processing
        $payment->status = 'successful'; // Simulate success
        $payment->save();

        return $payment;
    }
}
