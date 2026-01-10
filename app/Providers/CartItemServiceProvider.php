<?php

namespace App\Providers;

use App\Services\Site\CartItem\Validation\AddToCartRule;
use App\Services\Site\CartItem\Validation\ProductOwnsSpecificationRule;
use Illuminate\Support\ServiceProvider;

class CartItemServiceProvider extends ServiceProvider
{
    /**
     * Register services.
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
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
