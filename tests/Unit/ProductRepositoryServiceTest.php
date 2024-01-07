<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Service\ProductRepositoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Exceptions\NotFoundException;
use Tests\TestCase;

class ProductRepositoryServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $productService;

    public function setUp(): void
    {
        parent::setUp();
        $this->productService = new ProductRepositoryService();
    }

    /** @test */
    public function it_returns_all_products()
    {
        // Przygotuj testowe dane
        Product::factory()->count(3)->create();

        // Wywołaj metodę serwisu
        $result = $this->productService->getAll();

        // Sprawdź, czy zwrócono kolekcję produktów
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
        $this->assertCount(3, $result);

        // Sprawdź, czy każdy element kolekcji jest instancją modelu Product
        foreach ($result as $product) {
            $this->assertInstanceOf(Product::class, $product);
        }
    }

    /** @test */
    public function it_creates_a_product()
    {
        // Przygotuj testowe dane
        $requestData = [
            'name' => 'Product Name',
            'company' => 'Company Inc.',
            'model' => 'ABC123',
            'code' => 102252,
            'amount' => 10,
            'netto' => 50.00,
            'vat' => 23,
            'brutto' => 61.50,
        ];

        // Przekształć tablicę danych w obiekt
        $requestObject = (object) $requestData;

        // Wywołaj metodę serwisu
        $result = $this->productService->setData($requestObject);

        // Sprawdź, czy zwrócono instancję modelu Product
        $this->assertInstanceOf(Product::class, $result);

        // Sprawdź, czy dane zostały poprawnie zapisane w bazie danych
        $this->assertDatabaseHas('products', $requestData);
    }

        /** @test */
        public function it_deletes_a_product_with_associated_orders_and_categories()
        {
            // Create a product
            $product = Product::factory()->create();
    
            // Attach the product to a category
            $category = Category::factory()->create();
            $product->categories()->attach($category->id);
    
            // Attach the product to an order
            $order = Order::factory()->create();
            $product->orders()->attach($order->id);
    
            // Call the destroy method
            $result = $this->productService->destroy($product->id);
    
            // Assert that the method returns true
            $this->assertTrue($result);
    
            // Assert that the product is deleted from the database
            $this->assertDeleted('products', ['id' => $product->id]);
    
            // Assert that the product is detached from the category
            $this->assertDatabaseMissing('categories_products', [
                'category_id' => $category->id,
                'product_id' => $product->id,
            ]);
    
            // Assert that the product is detached from the order
            $this->assertDatabaseMissing('order_lists', [
                'order_id' => $order->id,
                'product_id' => $product->id,
            ]);
        }
    
        /** @test */
        public function it_throws_an_exception_when_trying_to_delete_nonexistent_product()
        {
            $this->expectException(NotFoundException::class);
    
            // Call the destroy method with a nonexistent product ID
            $this->productService->destroy(999);
        }
}