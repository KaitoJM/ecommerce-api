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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
