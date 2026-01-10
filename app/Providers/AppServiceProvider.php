<?php

namespace App\Providers;

use App\Services\Site\Cart\Validation\AddCartRule;
use App\Services\Site\Cart\Validation\NoOtherActiveCartRule;
use App\Services\Site\CartItem\Validation\AddToCartRule;
use App\Services\Site\CartItem\Validation\ProductOwnsSpecificationRule;
use App\Services\Site\CartItem\Validation\ProductSpecificationExistsRule;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->tag([
            ProductOwnsSpecificationRule::class,
        ], AddToCartRule::class);

        $this->app->bind(\App\Services\Site\CartItem\CartItemService::class, function ($app) {
            return new \App\Services\Site\CartItem\CartItemService(
                $app->make(\App\Repositories\CartItemRepository::class),
                $app->tagged(AddToCartRule::class)
            );
        });

        $this->app->tag([
            NoOtherActiveCartRule::class,
        ], AddCartRule::class);


        $this->app->bind(\App\Services\Site\Cart\CartService::class, function ($app) {
            return new \App\Services\Site\Cart\CartService(
                $app->make(\App\Repositories\CartRepository::class),
                $app->tagged(AddCartRule::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
