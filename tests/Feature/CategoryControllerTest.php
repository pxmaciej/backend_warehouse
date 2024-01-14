<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        // Przygotuj dane testowe
        Category::factory(5)->create();

        // Wywołaj endpoint /categories
        $response = $this->get('api/category/index');

        // Sprawdź, czy status odpowiedzi to 200 (OK)
        $response->assertStatus(200);

        // Sprawdź, czy odpowiedź zawiera oczekiwane dane
        $response->assertJsonCount(5);
    }

    public function testStore()
    {
        // Przygotuj dane testowe
        $categoryData = [
            'name' => 'Test Category',
            'description' => 'Test Description',
        ];

        // Wywołaj endpoint /categories
        $response = $this->post('api/category/store', $categoryData);

        // Sprawdź, czy status odpowiedzi to 200 (OK)
        $response->assertStatus(200);

        // Sprawdź, czy odpowiedź zawiera oczekiwane dane
        $response->assertJsonFragment($categoryData);
    }

    public function testShow()
    {
        // Przygotuj dane testowe
        $category = Category::factory()->create();

        // Wywołaj endpoint /categories/{id}
        $response = $this->get("api/category/show/{$category->id}");

        // Sprawdź, czy status odpowiedzi to 200 (OK)
        $response->assertStatus(200);
    }

    public function testDestroy()
    {
        // Przygotuj dane testowe
        $category = Category::factory()->create();

        // Wywołaj endpoint /categories/{id}
        $response = $this->delete("api/category/destroy/{$category->id}");

        // Sprawdź, czy status odpowiedzi to 200 (OK)
        $response->assertStatus(200);

        // Sprawdź, czy kategoria została usunięta
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
