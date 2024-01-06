<?php

namespace Database\Factories;

use App\Models\OrderList;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderListFactory extends Factory
{
    protected $model = OrderList::class;

    public function definition()
    {
        return [
            // Definiuj dane dla modelu OrderList
            'product_id' => $this->faker->randomDigit,
            'order_id' => $this->faker->randomDigit,
            'amount' => $this->faker->randomNumber(2),
            'netto' => $this->faker->randomFloat(2, 10, 100),
            'vat' => $this->faker->randomFloat(2, 1, 20),
            'brutto' => $this->faker->randomFloat(2, 11, 120),
        ];
    }
}
