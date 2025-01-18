<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper method to generate JWT token for a user.
     */
    protected function getJwtToken(User $user): string
    {
        //  return auth()->claims(['sub' => $user->id])->fromUser($user);
        return \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
    }

    public function test_create_order()
    {
        $user = User::factory()->create();
        $token = $this->getJwtToken($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->postJson('/api/orders', [
            'items' => [
                [
                    'product_name' => 'Laptop',
                    'quantity' => 1,
                    'price' => 1000,
                ],
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'user_id',
                    'items',
                    'total',
                    'status',
                    'created_at',
                    'updated_at',
                ],
                'message',
            ])
            ->assertJson([
                'success' => true,
                'message' => 'order Created ',
            ]);
    }

    public function test_update_order()
    {
        $user = User::factory()->create();
        $token = $this->getJwtToken($user);
        $order = Order::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->putJson("/api/orders/{$order->id}", [
            'items' => [
                [
                    'product_name' => 'Updated Laptop',
                    'quantity' => 2,
                    'price' => 1200,
                ],
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'user_id',
                    'items',
                    'total',
                    'status',
                    'created_at',
                    'updated_at',
                ],
                'message',
            ])
            ->assertJson([
                'success' => true,
                'message' => 'order Updated',
                'data' => [
                    'items' => [
                        [
                            'product_name' => 'Updated Laptop',
                            'quantity' => 2,
                            'price' => 1200,
                        ],
                    ],
                ],
            ]);
    }

    public function test_delete_order()
    {
        $user = User::factory()->create();
        $token = $this->getJwtToken($user);
        $order = Order::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->deleteJson("/api/orders/{$order->id}");

        $response->assertStatus(204)
            ->assertNoContent();
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }

    public function test_cannot_delete_order_with_payments()
    {
        $user = User::factory()->create();
        $token = $this->getJwtToken($user);
        $order = Order::factory()->create(['user_id' => $user->id]);

        // Simulate associated payments
        $order->payments()->create([
            'payment_id' => uniqid('pay_'),
            'payment_method' => 'credit_card',
            'status' => 'successful',
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
