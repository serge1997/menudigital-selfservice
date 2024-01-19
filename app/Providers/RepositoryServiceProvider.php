<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Main\MealType\MealTypeRepositoryInterface;
use App\Main\MealType\MealTypeRepository;
use App\Main\TableNumber\TableNumberRepositoryInterface;
use App\Main\TableNumber\TableNumberRepository;
use App\Main\Cart\CartRepositoryInterface;
use App\Main\Cart\CartRepository;


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
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
