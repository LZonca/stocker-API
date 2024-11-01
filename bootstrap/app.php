<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web([
            \App\Http\Middleware\web\SetLocale::class,
        ]);
        $middleware->api([
            $middleware->appendToGroup('user-mgroup', \App\Http\Middleware\api\CheckUserSelf::class),
            $middleware->appendToGroup('group-mgroup', \App\Http\Middleware\api\CheckGroupAccess::class),
            $middleware->appendToGroup('owner-mgroup', \App\Http\Middleware\api\CheckGroupOwnership::class),
            $middleware->appendToGroup('stock-mgroup', \App\Http\Middleware\api\CheckStockAccess::class),
            $middleware->appendToGroup('set-locale', \App\Http\Middleware\api\SetLocale::class)
        ]);


    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
