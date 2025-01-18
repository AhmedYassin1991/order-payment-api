<?php

// database/factories/PaymentFactory.php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'order_id' => Order::factory(), // Associate with an Order
            'payment_id' => $this->faker->uuid, // Generate a unique payment ID
            'payment_method' => $this->faker->randomElement(['credit_card', 'paypal']), // Random payment method
            'status' => $this->faker->randomElement(['pending', 'successful', 'failed']), // Random status
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'), // Random creation date
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'), // Random update date
        ];
    }
}
