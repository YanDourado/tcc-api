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

$router->group(['prefix' => 'api'], function () use ($router) {
    
    $router->post('register', 'AuthController@register');

    $router->post('login', 'AuthController@login');

    $router->post('alert', 'AlertController@create');

    $router->post('camera', 'CameraController@create');

    $router->group(['middleware' => 'auth'], function() use ($router) {

        $router->get('/profile', 'ProfileController@show');
        
        $router->group(['prefix' => 'camera'], function () use ($router) {
            $router->get('', 'CameraController@index');

            $router->put('', 'CameraController@update');
        });

        $router->group(['prefix' => 'user'], function () use ($router) {

            $router->put('', 'UserController@update');
        });

        $router->group(['prefix' => 'alert'], function () use ($router) {

            $router->get('', 'AlertController@index');

            $router->put('', 'AlertController@update');
        });

    });
 
 });
