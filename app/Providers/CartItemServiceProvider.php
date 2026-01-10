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
        // Tag all rules that implement AddToCartRule
        $this->app->tag([
            \App\Services\Site\CartItem\Validation\ProductOwnsSpecificationRule::class,
        ], \App\Services\Site\CartItem\Validation\AddToCartRule::class);

        // Bind the pipeline so it receives tagged rules
        $this->app->bind(\App\Services\Site\CartItem\Pipelines\AddToCartPipeline::class, function ($app) {
            return new \App\Services\Site\CartItem\Pipelines\AddToCartPipeline(
                $app->tagged(\App\Services\Site\CartItem\Validation\AddToCartRule::class)
            );
        });

        // Bind CartItemService with the repository and the pipeline
        $this->app->bind(\App\Services\Site\CartItem\CartItemService::class, function ($app) {
            return new \App\Services\Site\CartItem\CartItemService(
                $app->make(\App\Repositories\CartItemRepository::class),
                $app->make(\App\Services\Site\CartItem\Pipelines\AddToCartPipeline::class)
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
