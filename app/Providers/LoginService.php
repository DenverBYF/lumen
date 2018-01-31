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
		$password = DB::table($this->table)->select('password')->where('tel', $request['tel'])->get();
		if (sha1($request['password']) === $password) {
			return true;
		} else {
			return false;
		}
	}

}