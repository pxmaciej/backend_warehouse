<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Service\OrderRepositoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderRepositoryServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $orderService;

    public function setUp(): void
    {
        parent::setUp();
        $this->orderService = new OrderRepositoryService();
    }

    /** @test */
    public function it_returns_all_orders()
    {
        // Przygotuj testowe dane
        Order::factory()->count(3)->create();

        // Wywołaj metodę serwisu
        $result = $this->orderService->getAll();

        // Sprawdź, czy zwrócono kolekcję zamówień
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
        $this->assertCount(3, $result);

        // Sprawdź, czy każdy element kolekcji jest instancją modelu Order
        foreach ($result as $order) {
            $this->assertInstanceOf(Order::class, $order);
        }
    }

    /** @test */
    public function it_creates_an_order()
    {
        // Przygotuj testowe dane
        $requestData = [
            'nameBuyer' => 'John Doe',
            'address' => '123 Main St',
            'status' => 0,
            'dateOrder' => now(),
            'dateDeliver' => now()->addDays(7),
        ];

        $requestObject = (object) $requestData;

        // Wywołaj metodę serwisu
        $result = $this->orderService->setData($requestObject);

        // Sprawdź, czy zwrócono instancję modelu Order
        $this->assertInstanceOf(Order::class, $result);

        // Sprawdź, czy dane zostały poprawnie zapisane w bazie danych
        $this->assertDatabaseHas('orders', [
            'nameBuyer' => $requestData['nameBuyer'],
            'address' => $requestData['address'],
            'status' => $requestData['status']
        ]);
    }

    /** @test */
    public function it_deletes_an_order()
    {
        // Przygotuj testowe dane
        $order = Order::factory()->create();

        // Wywołaj metodę serwisu do usunięcia zamówienia
        $result = $this->orderService->destroy($order->id);

        // Sprawdź, czy metoda zwraca true
        $this->assertTrue($result);

        // Sprawdź, czy zamówienie zostało usunięte z bazy danych
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }
}