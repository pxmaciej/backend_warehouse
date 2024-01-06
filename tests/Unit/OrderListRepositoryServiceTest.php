<?php

namespace Tests\Unit\Service;

use App\Exceptions\NotFoundException;
use App\Models\Order;
use App\Models\OrderList;
use App\Models\Product;
use App\Service\OrderListRepositoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class OrderListRepositoryServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $orderListService;

    public function setUp(): void
    {
        parent::setUp();

        $this->orderListService = new OrderListRepositoryService();
    }

    /** @test */
    public function it_returns_all_order_lists()
    {  
        // Przygotuj testowe dane
        $product = Product::factory()->create();
        $order = Order::factory()->create();

        $orderLists = OrderList::factory()->count(3)->create([
            'product_id' => $product->id,
            'order_id' => $order->id,
        ]);

        // Wywołaj metodę serwisu
        $result = $this->orderListService->getAll();

        // Sprawdź, czy zwrócono kolekcję list zamówień
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);

        // Sprawdź, czy liczba zwróconych elementów jest zgodna z liczbą utworzonych w testowych danych
        $this->assertCount(3, $result);

        // Sprawdź, czy każdy element kolekcji jest instancją modelu OrderList
        foreach ($result as $orderList) {
            $this->assertInstanceOf(OrderList::class, $orderList);
        }

        // Sprawdź, czy każdy element z kolekcji znajduje się w testowych danych
        foreach ($orderLists as $orderList) {
            $this->assertTrue($result->contains('id', $orderList->id));
        }
    }

    /** @test */
    public function it_creates_order_list_when_data_is_valid()
    {
        // Przygotuj testowe dane
        $product = Product::factory()->create();
        $order = Order::factory()->create();
        $requestData = [
            'product_id' => $product->id,
            'order_id' => $order->id,
            'amount' => 2,
            'netto' => 100,
            'vat' => 23,
            'brutto' => 123,
        ];

        // Fałszuj obiekty Product i Order, aby uniknąć rzeczywistego połączenia z bazą danych
        $productMock = Mockery::mock(Product::class);
        $productMock->shouldReceive('find')->andReturn($product);

        $orderMock = Mockery::mock(Order::class);
        $orderMock->shouldReceive('find')->andReturn($order);

        // Fałszuj obiekt OrderList, aby uniknąć rzeczywistego zapisu do bazy danych
        $orderListMock = Mockery::mock(OrderList::class);
        $orderListMock->shouldReceive('create')->andReturn(new OrderList($requestData));

        // Podmień oryginalne modele fałszowanymi obiektami w serwisie
        $this->app->instance(Product::class, $productMock);
        $this->app->instance(Order::class, $orderMock);
        $this->app->instance(OrderList::class, $orderListMock);

        // Wywołaj metodę serwisu
        $result = $this->orderListService->setData((object) $requestData);

        // Sprawdź, czy utworzono listę zamówień poprawnie
        $this->assertInstanceOf(OrderList::class, $result);
        $this->assertEquals($requestData['product_id'], $result->product_id);
        $this->assertEquals($requestData['order_id'], $result->order_id);
        $this->assertEquals($requestData['amount'], $result->amount);
        $this->assertEquals($requestData['netto'], $result->netto);
        $this->assertEquals($requestData['vat'], $result->vat);
        $this->assertEquals($requestData['brutto'], $result->brutto);
    }

    /** @test */
    public function it_throws_not_found_exception_if_order_list_id_does_not_exist()
    {
        $this->expectException(NotFoundException::class);

        // Wywołaj metodę serwisu z nieistniejącym ID listy zamówień
        $this->orderListService->show(999);
    }

    /** @test */
    public function it_deletes_an_order_list()
    {
        // Przygotuj testowe dane
        $product = Product::factory()->create();
        $order = Order::factory()->create();

        $orderLists = OrderList::factory()->create([
            'product_id' => $product->id,
            'order_id' => $order->id,
        ]);

        // Wywołaj metodę serwisu do usunięcia pozycji zamówienia
        $result = $this->orderListService->destroy($orderLists->id);

        // Sprawdź, czy metoda zwraca true
        $this->assertTrue($result);

        // Sprawdź, czy pozycja zamówienia została usunięta z bazy danych
        $this->assertDatabaseMissing('order_lists', ['id' => $orderLists->id]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Zwolnij fałszowane obiekty Mockery
        Mockery::close();
    }
}
