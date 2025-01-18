<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;

class PaymentService
{
    protected $gateway;

    public function __construct(PaymentGatewayFactory $gatewayFactory, ?string $gateway = null)
    {
        $gateway = $gateway ?? config('payment.default_gateway');
        $this->gateway = $gatewayFactory->create($gateway);
    }

    public function processPayment(Order $order, string $method)
    {
        if ($order->status !== 'confirmed') {
            throw new \Exception('Payments can only be processed for confirmed orders.');
        }

        $payment = new Payment([
            'order_id' => $order->id,
            'status' => 'pending',
            'payment_method' => $method,
            'payment_id' => uniqid(),
            'amount' => $order->total, // Assuming you have a total field in the Order
        ]);
        $payment->save();

        return $this->gateway->processPayment($payment);
    }
}
