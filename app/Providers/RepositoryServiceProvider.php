<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Main\MealType\MealTypeRepositoryInterface;
use App\Main\MealType\MealTypeRepository;
use App\Main\TableNumber\TableNumberRepositoryInterface;
use App\Main\TableNumber\TableNumberRepository;
use App\Main\Cart\CartRepositoryInterface;
use App\Main\Cart\CartRepository;
use App\Main\MenuItem\MenuItemRepositoryInterface;
use App\Main\MenuItem\MenuItemRepository;
use App\Main\TechnicalFiche\TechnicalFicheRepositoryInterface;
use App\Main\TechnicalFiche\TechnicalFicheRepository;
use App\Main\Order\OrderRepositoryInterface;
use App\Main\Order\OrderRepository;
use App\Main\OrderStatus\OrderStatusRepositoryInterface;
use App\Main\OrderStatus\OrderStatusRepository;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(MealTypeRepositoryInterface::class, MealTypeRepository::class);
        $this->app->bind(TableNumberRepositoryInterface::class, TableNumberRepository::class);
        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);
        $this->app->bind(MenuItemRepositoryInterface::class, MenuItemRepository::class);
        $this->app->bind(TechnicalFicheRepositoryInterface::class, TechnicalFicheRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(OrderStatusRepositoryInterface::class, OrderStatusRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
