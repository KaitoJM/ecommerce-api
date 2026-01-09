<?php

namespace App\Providers;

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
            ProductSpecificationExistsRule::class,
            ProductOwnsSpecificationRule::class,
        ], AddToCartRule::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
