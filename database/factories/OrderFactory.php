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
            'nameBuyer' => $this->faker->name,
            'address' => $this->faker->address,
            'status' => $this->faker->randomElement([0,1]),
            'dateOrder' => $this->faker->date,
            'dateDeliver' => $this->faker->date,
        ];
    }
}
