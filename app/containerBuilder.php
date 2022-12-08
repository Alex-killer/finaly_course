<?php

use Delight\Auth\Auth;
use League\Plates\Engine;

$containerBuilder = new DI\ContainerBuilder();
$containerBuilder->addDefinitions([
    Engine::class => function() {
        return new Engine('../app/views');
    },

    PDO::class => function () {
        $driver = "mysql";
        $host = "mysql";
        $database_name = "test";
        $username = "root";
        $password = "root";

        return new PDO("$driver:host=$host;dbname=$database_name", $username, $password);
    },

    Auth::class => function ($container) {
        return new Auth($container->get('PDO'));
    },
]);
$container = $containerBuilder->build();