<?php

namespace Tests\Feature;

use App\Models\Alert;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AlertControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        // Przygotuj dane testowe
        $product = Product::factory()->create();
        Alert::factory(['product_id' => $product->id])->count(3)->create();

        // Wywołaj endpoint /alerts
        $response = $this->get('api/alert/index');

        // Sprawdź, czy status odpowiedzi to 200 (OK)
        $response->assertStatus(200);

        // Sprawdź, czy odpowiedź zawiera oczekiwane dane
        $response->assertJsonCount(3);
    }

    public function testStore()
    {
        $product = Product::factory()->create();
        // Przygotuj dane testowe
        $alertData = [
            'product_id' => $product->id,
            'name' => 'Test Alert',
        ];

        // Wywołaj endpoint /alerts
        $response = $this->post('api/alert/store', $alertData);

        // Sprawdź, czy status odpowiedzi to 200 (OK)
        $response->assertStatus(200);

        // Sprawdź, czy odpowiedź zawiera oczekiwane dane
        $response->assertJsonFragment($alertData);
    }

    public function testShow()
    {
        // Przygotuj dane testowe
        $user = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create();
        $alert = Alert::factory(['product_id' => $product->id])->create();

        // Wywołaj endpoint /alerts/{id}
        $response = $this->actingAs($user)->getJson("api/alert/show/{$alert->id}");

        // Sprawdź, czy status odpowiedzi to 200 (OK)
        $response->assertStatus(200);
    }

}