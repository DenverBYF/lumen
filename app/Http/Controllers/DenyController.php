<?php
/**
 * Created by PhpStorm.
 * User: denverb
 * Date: 18/2/2
 * Time: 上午10:37
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DenyController extends Controller
{

	protected $denyReason;

	public function __construct(Request $request)
	{
		$this->denyReason = $request->input('reason');
	}


	public function denyDoctor($id)
	{
		return $this->update('doctors', $id);
	}

	public function denyPhysicians($id)
	{
		return $this->update('doctors', $id);
	}

	public function denyGroup($id)
	{
		return $this->update('group', $id);
	}

	protected function update($table, $id)
	{
		$row = DB::table($table)->where('id', $id)
			->update(['status' => 2, 'reason' => $this->denyReason]);
		if ($row) {
			return response()->json(['code' => 0, 'msg' => 'success']);
		} else {
			return response()->json(['code' => -2, 'msg' => 'error']);
		}
	}
}