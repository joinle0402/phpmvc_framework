<?php
namespace application\configurations;

use application\middlewares\AuthMiddleware;
use application\middlewares\AdminMiddleware;

$configurations['application'] = [
    'routeMiddlewares' => [
        '/san-pham/{id}' => AuthMiddleware::class
    ],
    'globalMiddlewares' => [
        AdminMiddleware::class
    ],
];
