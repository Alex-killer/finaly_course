<?php

require_once 'containerBuilder.php';

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/register', ['App\Controllers\Auth\RegisterController' , 'register']);
    $r->addRoute('POST', '/register', ['App\Controllers\Auth\RegisterController' , 'signUp']);
    $r->addRoute('GET', '/', ['App\Controllers\Auth\LoginController' , 'login']);
    $r->addRoute('POST', '/login', ['App\Controllers\Auth\LoginController' , 'signIn']);
    $r->addRoute('GET', '/logout', ['App\Controllers\Auth\LogoutController', 'logout']);

    if (isset($_SESSION['auth_logged_in'])) {
        $r->addRoute('GET', '/users', ['App\Controllers\UserController' , 'getAllUsers']);
        $r->addRoute('GET', '/add_user', ['App\Controllers\UserController' , 'addUser']);
        $r->addRoute('POST', '/add_user', ['App\Controllers\UserController' , 'createUser']);
        $r->addRoute('GET', '/edit_user/{id:\d+}', ['App\Controllers\UserController' , 'editUser']);
        $r->addRoute('POST', '/update_user', ['App\Controllers\UserController' , 'updateUser']);
        $r->addRoute('GET', '/update_status/{id:\d+}', ['App\Controllers\UserController' , 'editStatus']);
        $r->addRoute('POST', '/update_status', ['App\Controllers\UserController' , 'updateStatus']);
        $r->addRoute('GET', '/update_avatar/{id:\d+}', ['App\Controllers\UserController' , 'editAvatar']);
        $r->addRoute('POST', '/update_avatar', ['App\Controllers\UserController' , 'updateAvatar']);
        $r->addRoute('GET', '/edit_password/{id:\d+}', ['App\Controllers\UserController' , 'editPassword']);
        $r->addRoute('POST', '/edit_password', ['App\Controllers\UserController' , 'updatePassword']);
        $r->addRoute('POST', '/delete_user/{id:\d+}', ['App\Controllers\UserController' , 'deleteUser']);
        $r->addRoute('GET', '/profile/{id:\d+}', ['App\Controllers\UserController' , 'profile']);
    }
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo '404';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        echo 'Метод не разрешен';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $container->call($routeInfo[1], [$vars]);
        break;
}