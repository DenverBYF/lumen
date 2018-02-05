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
 * 分为患者,医生,管理员三个组
 * */
$router->group(['as' => 'api', 'prefix' => 'api'], function () use ($router) {
	$router->group(['prefix' => 'user', 'middleware' => 'auth:users'], function () use ($router) {

	});
	$router->group(['prefix' => 'manager', 'middleware' => 'auth:manager'], function () use ($router){
		$router->get('accept/doctor/{id}', ['uses' =>'AcceptController@acceptDoctor']);
		$router->get('accept/physicians/{id}', ['uses' => 'AcceptController@acceptPhysicians']);
		$router->get('accept/group/{id}', ['uses' => 'AcceptController@acceptGroup']);	//todo
		$router->post('deny/doctor/{id}', ['uses' => 'DenyController@denyDoctor']);
		$router->post('deny/physicians/{id}', ['uses' => 'DenyController@denyPhysicians']);
		$router->post('dent/group/{id}', ['uses' => 'DenyController@denyGroup']);
	});
	$router->group(['prefix' => 'doctor', 'middleware' => 'auth:doctors'], function () use ($router) {
		$router->post('apply/doctor', ['uses' => 'ApplyController@applyDoctor']);
		$router->post('apply/physicians', ['uses' => 'ApplyController@applyPhysicians']);
	});
	$router->group(['prefix' => 'group'], function () use ($router) {		//todo 群组相关路由 RESTful
		$router->get('/{page}', ['uses' => 'GroupController@index', 'middleware' => 'auth']);	//所有群列表,分页(初试为1)
		$router->get('show/{id}', ['uses' => 'GroupController@show','middleware' => 'auth']);	//查看群信息 传入id
		$router->post('create', ['uses' => 'GroupController@create', 'middleware' => 'auth:doctors']);	//创建群,需医生权限
		$router->get('apply/{id}', ['uses' => 'GroupController@apply', 'middleware' => 'auth']);	//申请加入群 传入id
		$router->put('update/{id}', ['uses' => 'GroupController@update', 'middleware' => 'auth:doctors']);	//更新群信息
		$router->delete('delete/{id}', ['uses' => 'GroupController@delete', 'middleware' => 'auth:doctors']);	//删除群
		$router->get('accept/{id}/{uid}', ['uses' => 'GroupController@accept', 'middleware' => 'auth:doctors']);	//批准成员加入
		$router->get('kick/{id}/{uid}', ['uses' => 'GroupController@kick', 'middleware' => 'auth:doctors']);	//踢出成员
	});
});


