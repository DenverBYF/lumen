<?php

/**
 * Created by PhpStorm.
 * User: denverb
 * Date: 18/1/31
 * Time: 上午11:25
 */
namespace App\Http\Controllers\doctors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class registerController extends Controller
{
	public function index(Request $request)
	{
		$ret = $this->request_filter($request);	//数据检验
		if ($ret !== true) {
			return response()->json(['code' => -1, 'msg' => $ret]);	//数据不符合规定
		}
		$insertId = DB::table('doctors')->insertGetId([	//新建user
			'name' => $request->input('name'),
			'username' => $request->input('username'),
			'tel' => $request->input('tel'),
			'email' => $request->input('email'),
			'password' => sha1($request->input('password')),
			'card' => $request->input('card'),
			'hospital' => $request->input('hospital'),
			'desc' => $request->input('desc'),
			'created_at' => date('Y-m-d h:i:sa', time()),
		]);
		if ($insertId) {
			return response()->json(['code' => 0, 'msg' => 'success']);	//success
		} else {
			return response()->json(['code' => -2, 'msg' => 'fail']);	//fail
		}
	}

	/*
	 * 数据检验
	 * */
	protected function request_filter($data)
	{
		try {
			$this->validate($data, [
				'name' => 'required',
				'username' => 'required|max:20|unique:doctors',
				'password' => 'required|max:16',
				'tel' => 'required|unique:doctors',
				'email' => 'required|email',
				'hospital' => 'required',
				'area' => 'required',
				'card' => 'required',
			]);
		} catch (ValidationException $e) {
			return $e->response->original;
		}
		return true;
	}
}