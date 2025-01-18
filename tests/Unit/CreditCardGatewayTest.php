<?php

// tests/Unit/CreditCardGatewayTest.php

namespace Tests\Unit;

use App\Models\Payment;
use App\Services\PaymentGateways\CreditCardGateway;
use Tests\TestCase;

class CreditCardGatewayTest extends TestCase
{
    public function test_process_payment()
    {
        // Create a Payment object using the factory
        $payment = Payment::factory()->create(['status' => 'pending']); // Initial status is 'pending'

        // Instantiate the CreditCardGateway
        $gateway = new CreditCardGateway;

        // Process the payment
        $result = $gateway->processPayment($payment);

        // Assert that the returned object is an instance of Payment
        $this->assertInstanceOf(Payment::class, $result);

        // Assert that the payment status is updated to 'successful'
        $this->assertEquals('successful', $result->status);

        // Assert that the payment object is saved in the database
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'successful',
        ]);
    }
}
