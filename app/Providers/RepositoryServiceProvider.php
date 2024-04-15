<?php

namespace App\Providers;

use App\Main\QrcodeOrderNumber\QrcodeOrderNumberRepository;
use App\Main\QrcodeOrderNumber\QrCodeOrderNumberRepositoryInterface;
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
use App\Main\Product\ProductRepositoryInterface;
use App\Main\Product\ProductRepository;
use App\Main\Stock\StockRepositoryInterface;
use App\Main\Stock\StockRepository;
use App\Main\Reservation\ReservationRepositoryInterface;
use App\Main\Reservation\ReservationRepository;
use App\Main\User\UserRepositoryInterface;
use App\Main\User\UserRepository;
use App\Main\Restaurant\RestaurantRepositoryInterface;
use App\Main\Restaurant\RestaurantRepository;
use App\Main\Role\RoleRepositoryInterface;
use App\Main\Role\RoleRepository;
use App\Main\UserRole\UserRoleRepositoryInterface;
use App\Main\UserRole\UserRoleRepository;
use App\Main\Department\DepartmentRepositoryInterface;
use App\Main\Department\DepartmentRepository;
use App\Main\Position\PositionRepositoryInterface;
use App\Main\Position\PositionRepository;
use App\Main\Login\LoginRepositoryInterface;
use App\Main\Login\LoginRepository;
use App\Main\Planning\PlanningRepositoryInterface;
use App\Main\Planning\PlanningRepository;
use App\Main\PurchaseRequisition\PurchaseRequisitionRepositoryInterface;
use App\Main\PurchaseRequisition\PurchaseRequisitionRepository;
use App\Main\Expense\ExpenseRepository;
use App\Main\Expense\ExpenseRepositoryInterface;
use App\Main\Language\LanguageRepositoryInterface;
use App\Main\Language\LanguageRepository;


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
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(StockRepositoryInterface::class, StockRepository::class);
        $this->app->bind(ReservationRepositoryInterface::class, ReservationRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(RestaurantRepositoryInterface::class, RestaurantRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(UserRoleRepositoryInterface::class, UserRoleRepository::class);
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(PositionRepositoryInterface::class, PositionRepository::class);
        $this->app->bind(LoginRepositoryInterface::class, LoginRepository::class);
        $this->app->bind(PlanningRepositoryInterface::class, PlanningRepository::class);
        $this->app->bind(PurchaseRequisitionRepositoryInterface::class, PurchaseRequisitionRepository::class);
        $this->app->bind(ExpenseRepositoryInterface::class, ExpenseRepository::class);
        $this->app->bind(LanguageRepositoryInterface::class, LanguageRepository::class);
        $this->app->bind(QrCodeOrderNumberRepositoryInterface::class, QrcodeOrderNumberRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
