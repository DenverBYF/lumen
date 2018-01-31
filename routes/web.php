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

/*
 * 注册路由组
 * */
$router->group(['as' => 'register', 'prefix' => 'register'], function () use ($router) {
	$router->post('user', ['uses' => 'users\registerController@index']);
	$router->post('doctor', ['uses' => 'doctors\registerController@index']);
});

/*
 * 登录路由组
 * */
$router->group(['as' => 'login', 'prefix' => 'login'], function () use ($router) {
	$router->get('user', ['uses' => 'users\loginController@index']);
	$router->post('doctor', ['uses' => 'doctors\loginController@index']);
	$router->post('manager', ['uses' => 'manager\loginController@index']);
});

/*
 * Api路由组
 * 使用auth中间件进行身份认证
 * */
$router->group(['as' => 'api', 'prefix' => 'api'], function () use ($router) {
	$router->group(['prefix' => 'user', 'middleware' => 'auth:users'], function () use ($router) {

	});
	$router->group(['prefix' => 'manager', 'middleware' => 'auth:manager'], function () use ($router){

	});
	$router->group(['prefix' => 'doctor', 'middleware' => 'auth:doctors'], function () use ($router){

	});
});

