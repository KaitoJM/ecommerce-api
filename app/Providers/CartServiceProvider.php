<?php

namespace App\Providers;

use App\Services\Site\Cart\Validation\AddCartRule;
use App\Services\Site\Cart\Validation\NoOtherActiveCartRule;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
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
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
