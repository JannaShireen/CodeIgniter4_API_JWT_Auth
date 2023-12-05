<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->group("api",["namespace" => "App\Controllers\Api"], function ($routes){
    $routes->post("register", "ApiController::userRegister");
    $routes->post("login", "ApiController::userLogin");
    $routes->get(
        "profile", "ApiController::userProfile"
    );
    $routes->post(
        "create-book","ApiController::createBook"


    );
    $routes->get(
        "list-book","ApiController::listBook"

    );
    $routes->delete(
        "delete-book/(:num)", "ApiController::deleteBook/$1"
    );

} );
