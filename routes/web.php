<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/abc', function () {
    return 'Hello from the other side';
});

$router->post('auth/login', 'AuthController@authenticate');
$router->post('auth/register', 'AuthController@register');

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('protected', 'AuthController@protected');
    $router->get('ingredients/{id}', 'IngredientsController@show');    
    $router->get('ingredients', 'IngredientsController@index');
    $router->post('ingredients', 'IngredientsController@store');
});