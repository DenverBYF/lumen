<?php
/**
 * Created by PhpStorm.
 * User: denverb
 * Date: 18/1/31
 * Time: 上午11:39
 */

namespace App\Providers;


use Illuminate\Support\Facades\DB;

class LoginService
{
	protected $table;	//表名

	public function __construct($table)
	{
		$this->table = $table;
	}

	/*
	 * 登录
	 * @param:request
	 * @return:json_data
	 * */
	public function login($request)
	{
		$user = DB::table($this->table)
			->select('id')
			->where('tel', $request['tel'])
			->where('password', sha1($request['password']))
			->first();
		if ($user) {
			return $user->id;
		} else {
			return false;
		}
	}

}