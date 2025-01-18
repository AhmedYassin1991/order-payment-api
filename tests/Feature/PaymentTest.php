<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_process_payment()
    {
        $user = User::factory()->create();
        $token = $this->getJwtToken($user);
        $order = Order::factory()->create(['user_id' => $user->id, 'status' => 'confirmed']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/payments', [
            'order_id' => $order->id,
            'payment_method' => 'credit_card',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'order_id',
                    'payment_id',
                    'status',
                    'payment_method',
                    'created_at',
                    'updated_at',
                ],
                'gateway_response',
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Payment processed successfully.',
            ]);
    }

    public function test_view_payments()
    {
        $user = User::factory()->create();
        $token = $this->getJwtToken($user);
        $order = Order::factory()->create(['user_id' => $user->id]);
        $payment = $order->payments()->create([
            'payment_id' => 'pay_123456789',
            'status' => 'successful',
            'payment_method' => 'credit_card',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson("/api/orders/{$order->id}/payments");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'id' => $payment->id,
                        'order_id' => $order->id,
                        'payment_id' => 'pay_123456789',
                        'status' => 'successful',
                        'payment_method' => 'credit_card',
                    ],
                ],
            ]);
    }

    public function test_payment_workflow()
    {
        $user = User::factory()->create();
        $token = $this->getJwtToken($user); // Generate JWT token
        $order = Order::factory()->create(['user_id' => $user->id, 'status' => 'confirmed']);

        // Process payment
        $paymentResponse = $this->withHeaders([
            'Authorization' => 'Bearer '.$token, // Include JWT token in headers
        ])->postJson("/api/orders/{$order->id}/payments", [
            'payment_method' => 'credit_card',
        ]);

        $paymentResponse->assertStatus(201);

        // View payments
        $viewResponse = $this->withHeaders([
            'Authorization' => 'Bearer '.$token, // Include JWT token in headers
        ])->getJson("/api/orders/{$order->id}/payments");

        $viewResponse->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'order_id' => $order->id,
                        'status' => 'successful',
                        'payment_method' => 'credit_card',
                    ],
                ],
            ]);
    }

    public function test_cannot_process_payment_for_non_confirmed_order()
    {
        $user = User::factory()->create();
        $token = $this->getJwtToken($user);
        $order = Order::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/payments', [
            'order_id' => $order->id,
            'payment_method' => 'credit_card',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Payments can only be processed for confirmed orders.',
            ]);
    }
    public function test_cannot_delete_order_with_payments()
    {
        $user = User::factory()->create();
        $token = $this->getJwtToken($user);
        $order = Order::factory()->create(['user_id' => $user->id]);

        // Simulate associated payments
        $order->payments()->create([
            'payment_id' => 'pay_123456789',
            'status' => 'successful',
            'payment_method' => 'credit_card',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->deleteJson("/api/orders/{$order->id}");

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Cannot delete order with associated payments.',
            ]);
    }
}
