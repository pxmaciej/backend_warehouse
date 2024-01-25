<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            // Definiuj dane dla modelu Order
            'client' => $this->faker->name,
            'zipCode' => $this->faker->randomDigit(),
            'city' => $this->faker->city,
            'address' => $this->faker->address,
            'type' => $this->faker->randomElement(['dostawa','wysyłka']),
            'status' => $this->faker->randomElement([0,1]),
            'dateOrder' => $this->faker->date,
            'dateDeliver' => $this->faker->date,
        ];
    }
}
