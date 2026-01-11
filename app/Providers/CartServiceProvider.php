<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Tag all rules that implement AddCartRule
        $this->app->tag([
            \App\Services\Site\Cart\Validation\NoOtherActiveCartRule::class,
        ], \App\Services\Site\Cart\Validation\AddCartRule::class);

        // Bind the pipeline so it receives tagged rules
        $this->app->bind(\App\Services\Site\Cart\Pipelines\AddCartPipeline::class, function ($app) {
            return new \App\Services\Site\Cart\Pipelines\AddCartPipeline(
                $app->tagged(\App\Services\Site\Cart\Validation\AddCartRule::class)
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
