<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        // Przygotuj dane testowe
        Product::factory(5)->create();

        // Wywołaj endpoint /products
        $response = $this->get('api/product/index');

        // Sprawdź, czy status odpowiedzi to 200 (OK)
        $response->assertStatus(200);

        // Sprawdź, czy odpowiedź zawiera oczekiwane dane
        $response->assertJsonCount(5);
    }

    public function testStore()
    {
        // Przygotuj dane testowe
        $productData = [
            'name' => 'Test Product',
            'company' => 'test',
            'model' => 'model',
            'code' => 123512,
            'amount' => 10,
            'netto' => 50.00,
            'vat' => 'zw',
            'brutto' => 50.00,
        ];

        // Wywołaj endpoint /products
        $response = $this->post('api/product/store', $productData);

        // Sprawdź, czy status odpowiedzi to 200 (OK)
        $response->assertStatus(200);

        // Sprawdź, czy odpowiedź zawiera oczekiwane dane
        $response->assertJsonFragment($productData);
    }

    public function testShow()
    {
        // Przygotuj dane testowe
        $product = Product::factory()->create();

        // Wywołaj endpoint /products/{id}
        $response = $this->get("api/product/show/{$product->id}");

        // Sprawdź, czy status odpowiedzi to 200 (OK)
        $response->assertStatus(200);

        // Sprawdź, czy odpowiedź zawiera oczekiwane dane
        $response->assertJson($product->toArray());
    }

    public function testDestroy()
    {
        // Przygotuj dane testowe
        $product = Product::factory()->create();

        // Wywołaj endpoint /products/{id}
        $response = $this->delete("api/product/destroy/{$product->id}");

        // Sprawdź, czy status odpowiedzi to 200 (OK)
        $response->assertStatus(200);

        // Sprawdź, czy produkt został usunięty
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
