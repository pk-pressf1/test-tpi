<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TicketGatewayInterface;
use App\Services\LeadbookTicketGateway;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            TicketGatewayInterface::class,
            LeadbookTicketGateway::class //Выбор провайдера поставщика
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
