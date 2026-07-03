<?php

namespace App\Providers;

use App\Listeners\ActivateJobOfferBoostListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use PayGate\LaravelPayGateGlobal\Events\PaymentReceived;

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
        Event::listen(PaymentReceived::class, ActivateJobOfferBoostListener::class);
    }
}