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
    echo "Code start";
});
$router->group(['as' => 'register', 'prefix' => 'register'], function () use ($router) {
	$router->post('user', ['uses' => 'users\registerController@index']);
	$router->post('doctor', ['uses' => 'doctors\registerController@index']);
});

