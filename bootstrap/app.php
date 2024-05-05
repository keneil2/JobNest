<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AuthMiddleware;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
           "authadmins"=> App\Http\Middleware\AuthMiddleware::class,
           "AuthUsers"=>App\Http\Middleware\usersMiddlesware::class

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
