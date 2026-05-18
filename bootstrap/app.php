<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->redirectGuestsTo('/admin/login');

        // Middleware Takma Adlarını (Alias) Tanımlama
        $middleware->alias([
            // Teklif Verme Kontrolü
            'can_make_offer' => \App\Http\Middleware\CanMakeOffer::class,

            // İlan Detaylarını Görüntüleme Kontrolü
            'can_view_listing' => \App\Http\Middleware\CanViewListingDetails::class,

            //Abonelik kontrolü
            'is.subscriber' => \App\Http\Middleware\IsSubscriber::class,

            // Admin kontrolü
            'is.admin' => \App\Http\Middleware\IsAdmin::class,

            'ensure.admin' => \App\Http\Middleware\EnsureIsAdmin::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
