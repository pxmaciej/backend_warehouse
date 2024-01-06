<?php

namespace Tests\Unit\Service;

use App\Exceptions\NotFoundException;
use App\Models\Category;
use App\Models\Product;
use App\Service\CategoryRepositoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class CategoryRepositoryServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $categoryService;

    public function setUp(): void
    {
        parent::setUp();

        $this->categoryService = new CategoryRepositoryService();
    }

    /** @test */
    public function it_returns_all_categories()
    {
        // Przygotuj testowe dane
        Category::factory()->count(3)->create();

        // Wywołaj metodę serwisu
        $result = $this->categoryService->getAll();

        // Sprawdź, czy zwrócono kolekcję kategorii
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
        $this->assertCount(3, $result);
    }

    /** @test */
    public function it_creates_category_when_data_is_valid()
    {
        // Przygotuj testowe dane
        $requestData = [
            'name' => 'Example Category',
            'description' => 'Example Description',
        ];

        // Wywołaj metodę serwisu
        $result = $this->categoryService->setData((object) $requestData);

        // Sprawdź, czy utworzono kategorię poprawnie
        $this->assertInstanceOf(Category::class, $result);
        $this->assertEquals($requestData['name'], $result->name);
        $this->assertEquals($requestData['description'], $result->description);
    }

    /** @test */
    public function it_throws_not_found_exception_if_category_id_does_not_exist()
    {
        $this->expectException(NotFoundException::class);

        // Wywołaj metodę serwisu z nieistniejącym ID kategorii
        $this->categoryService->show(999);
    }

    /** @test */
    public function it_deletes_a_category_with_associated_products()
    {
        // Przygotuj testowe dane
        $category = Category::factory()->create();
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        // Dodaj produkty do kategorii
        $category->products()->attach([$product1->id, $product2->id]);

        // Wywołaj metodę serwisu do usunięcia kategorii
        $result = $this->categoryService->destroy($category->id);

        // Sprawdź, czy metoda zwraca true
        $this->assertTrue($result);

        // Sprawdź, czy kategoria została usunięta z bazy danych
        $this->assertDeleted('categories', ['id' => $category->id]);

        // Sprawdź, czy produkty zostały odłączone od kategorii
        $this->assertDatabaseMissing('categories_products', [
            'category_id' => $category->id,
            'product_id' => $product1->id,
        ]);
        $this->assertDatabaseMissing('categories_products', [
            'category_id' => $category->id,
            'product_id' => $product2->id,
        ]);
    }

    /** @test */
    public function it_throws_exception_when_deleting_nonexistent_category()
    {
        // Próba usunięcia nieistniejącej kategorii powinna spowodować wyjątek NotFoundException
        $this->expectException(NotFoundException::class);

        // Wywołaj metodę serwisu do usunięcia nieistniejącej kategorii
        $this->categoryService->destroy(999);
    }

    // Dodaj więcej testów dla pozostałych metod serwisu

    protected function tearDown(): void
    {
        parent::tearDown();

        // Zwolnij fałszowane obiekty Mockery
        Mockery::close();
    }
}