<?php

namespace Tests\Unit\Controllers;

use App\Exceptions\NotFoundException;
use App\Models\Alert;
use App\Models\Product;
use App\Service\AlertRepositoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;

class AlertRepositoryServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $alertService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->alertService = new AlertRepositoryService();
    }
       /** @test */
       public function it_returns_all_alerts_with_product_name()
       {
           // Przygotuj testowe dane
           $product = Product::factory()->create();
           $alert = Alert::factory(['product_id' => $product->id])->create();
   
           // Fałszuj obiekt Product, aby uniknąć rzeczywistego połączenia z bazą danych
           $productMock = Mockery::mock(Product::class);
           $productMock->shouldReceive('find')->andReturn($product);
   
           // Fałszuj obiekt Alert, aby uniknąć rzeczywistego połączenia z bazą danych
           $alertMock = Mockery::mock(Alert::class);
           $alertMock->shouldReceive('join')->andReturnSelf();
           $alertMock->shouldReceive('select')->andReturnSelf();
           $alertMock->shouldReceive('get')->andReturn([$alert]);
   
           // Podmień oryginalne modele fałszowanymi obiektami w serwisie
           $this->app->instance(Product::class, $productMock);
           $this->app->instance(Alert::class, $alertMock);
   
           // Wywołaj metodę serwisu
           $result = $this->alertService->getAll();
   
           // Sprawdź, czy wynik zawiera oczekiwane dane
           $this->assertEquals(1, $result->count());
           $this->assertEquals($alert->name, $result[0]->name);
           $this->assertEquals($product->name, $result[0]->product_name);
           // Dodaj więcej asercji w zależności od rzeczywistych oczekiwań
       }
       /** @test */
    public function it_creates_alert_when_data_is_valid()
    {
        // Przygotuj testowe dane
        $product = Product::factory()->create();
        $requestData = [
            'product_id' => $product->id,
            'name' => 'Example Alert',
        ];

        // Fałszuj obiekt Product, aby uniknąć rzeczywistego połączenia z bazą danych
        $productMock = Mockery::mock(Product::class);
        $productMock->shouldReceive('find')->andReturn($product);

        // Fałszuj obiekt Alert, aby uniknąć rzeczywistego połączenia z bazą danych
        $alertMock = Mockery::mock(Alert::class);
        $alertMock->shouldReceive('create')->andReturn(new Alert($requestData));

        // Podmień oryginalne modele fałszowanymi obiektami w serwisie
        $this->app->instance(Product::class, $productMock);
        $this->app->instance(Alert::class, $alertMock);

        // Wywołaj metodę serwisu
        $result = $this->alertService->setData((object) $requestData);

        // Sprawdź, czy alert został utworzony poprawnie
        $this->assertInstanceOf(Alert::class, $result);
        $this->assertEquals($requestData['product_id'], $result->product_id);
        $this->assertEquals($requestData['name'], $result->name);
    }

    /** @test */
    public function it_throws_not_found_exception_if_product_id_does_not_exist()
    {
        $this->expectException(NotFoundException::class);

        // Przygotuj testowe dane
        $requestData = [
            'product_id' => 999,
            'name' => 'Example Alert',
        ];

        // Wywołaj metodę serwisu
        $this->alertService->setData((object) $requestData);
    }

    /** @test */
    public function it_deletes_an_alert()
    {
        // Przygotuj testowe dane
        $product = Product::factory()->create();
        $alert = Alert::factory(['product_id' => $product->id])->create();

        // Wywołaj metodę serwisu do usunięcia alertu
        $result = $this->alertService->destroy($alert->id);

        // Sprawdź, czy metoda zwraca true
        $this->assertTrue($result);

        // Sprawdź, czy alert został usunięty z bazy danych
        $this->assertDeleted('alerts', ['id' => $alert->id]);
    }

 /** @test */
 public function it_throws_exception_when_deleting_nonexistent_alert()
 {
     // Próba usunięcia nieistniejącego alertu powinna spowodować wyjątek NotFoundException
     $this->expectException(NotFoundException::class);

     // Wywołaj metodę serwisu do usunięcia nieistniejącego alertu
     $this->alertService->destroy(999);
 }
    
    protected function tearDown(): void
    {
        parent::tearDown();
   
        // Zwolnij fałszowane obiekty Mockery
        Mockery::close();
    }
}