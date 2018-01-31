<?php
/**
 * Created by PhpStorm.
 * User: denverb
 * Date: 18/1/31
 * Time: 上午11:47
 */

namespace App\Http\Controllers\doctors;

session_start();
use App\Http\Controllers\Controller;
use App\Providers\LoginService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class loginController extends Controller
{
	public function index(Request $request)
	{
		$ret = $this->request_filter($request);
		if ($ret !== true) {
			return response()->json(['code' => -1, 'msg' => $ret]);
		}
		$login = new LoginService('doctors');
		$data = ['tel' => $request->input('tel'), 'password' => $request->input('password')];
		$user = $login->login($data);
		if ($user) {
			$_SESSION['id'] = $user;
			$_SESSION['type'] = 'doctors';
			$sessionId = session_id();
			return response()->json(['code' => 0, 'msg' => $sessionId]);
		} else {
			return response()->json(['code' => -2, 'password wrong']);
		}
	}

	protected function request_filter($data)
	{
		try {
			$this->validate($data, [
				'tel' => 'required|exists:doctors',
				'password' => 'required',
			]);
		} catch (ValidationException $e) {
			return $e->response->original;
		}
		return true;
	}
}