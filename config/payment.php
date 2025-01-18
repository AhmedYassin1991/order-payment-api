<?php

// config/payment.php
return [
    'default_gateway' => env('DEFAULT_PAYMENT_GATEWAY', 'credit_card'),

    'gateways' => [
        'credit_card' => \App\Services\PaymentGateways\CreditCardGateway::class,
        'paypal' => \App\Services\PaymentGateways\PayPalGateway::class,
    ],
];
