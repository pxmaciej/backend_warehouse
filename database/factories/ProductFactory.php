<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'company' => $this->faker->company(),
            'model' => $this->faker->name(),
            'code' => $this->faker->imei(),
            'amount' => $this->faker->randomDigit(),
            'netto' => $this->faker->randomFloat('2', 0, 2),
            'brutto' => $this->faker->randomFloat('2', 0, 2),
            'vat' => $this->faker->randomElement(['zw', '5', '8', '23'])
        ];
    }
}
