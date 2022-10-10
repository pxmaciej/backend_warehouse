<?php

namespace App\Providers;

use App\Http\Controllers\AlertInterface;
use App\Http\Controllers\OrderInterface;
use App\Http\Controllers\OrderListInterface;
use App\Http\Controllers\ProductInterface;
use App\Http\Controllers\StatisticInterface;
use App\Service\OrderListRepositoryService;
use App\Service\OrderRepositoryService;
use App\Service\ProductRepositoryService;
use App\Service\StatisticRepositoryService;
use Illuminate\Support\ServiceProvider;
use App\Service\AlertRepositoryService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AlertInterface::class, function () {
            return new AlertRepositoryService;
        });
        $this->app->bind(OrderInterface::class, function () {
            return new OrderRepositoryService();
        });
        $this->app->bind(OrderListInterface::class, function () {
            return new OrderListRepositoryService();
        });
        $this->app->bind(ProductInterface::class, function () {
            return new ProductRepositoryService();
        });
        $this->app->bind(StatisticInterface::class, function () {
            return new StatisticRepositoryService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
