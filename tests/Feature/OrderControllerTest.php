<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        // Przygotuj dane testowe
        Order::factory(5)->create();

        // Wywołaj endpoint /orders
        $response = $this->get('api/order/index');

        // Sprawdź, czy status odpowiedzi to 200 (OK)
        $response->assertStatus(200);

        // Sprawdź, czy odpowiedź zawiera oczekiwane dane
        $response->assertJsonCount(5);
    }

    public function testStore()
    {
        // Przygotuj dane testowe
        $orderData = [
            'client' => 'Test Order',
            'zipCode' => '59-220',
            'city' => 'LongLive',
            'address' => 'John Doe',
            'type' => 'dostawa',
            'status' => true,
            'dateDeliver' => now()->addDays(5)->format('Y-m-d'),
            'dateOrder' => now()->addDays(5)->format('Y-m-d'),
        ];

        // Wywołaj endpoint /orders
        $response = $this->post('api/order/store', $orderData);

        // Sprawdź, czy status odpowiedzi to 200 (OK)
        $response->assertStatus(200);
    }

    public function testShow()
    {
        // Przygotuj dane testowe
        $order = Order::factory()->create();

        // Wywołaj endpoint /orders/{id}
        $response = $this->get("api/order/show/{$order->id}");

        // Sprawdź, czy status odpowiedzi to 200 (OK)
        $response->assertStatus(200);

    }

    public function testProduct()
    {
        // Przygotuj dane testowe
        $order = Order::factory()->create();

        // Wywołaj endpoint /orders/product/{id}
        $response = $this->get("api/order/product/{$order->id}");

        // Sprawdź, czy status odpowiedzi to 200 (OK)
        $response->assertStatus(200);
    }


    public function testDestroy()
    {
        // Przygotuj dane testowe
        $order = Order::factory()->create();

        // Wywołaj endpoint /orders/{id}
        $response = $this->delete("api/order/destroy/{$order->id}");

        // Sprawdź, czy status odpowiedzi to 200 (OK)
        $response->assertStatus(200);
    }
}
