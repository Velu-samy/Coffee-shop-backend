<?php

protected $routeMiddleware = [
    // other middleware...
    'auth.custom' => \App\Http\Middleware\EnsureUserIsAuthenticated::class,
];