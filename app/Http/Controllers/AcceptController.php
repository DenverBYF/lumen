<?php
/**
 * Created by PhpStorm.
 * User: denverb
 * Date: 18/2/2
 * Time: 上午8:55
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AcceptController extends Controller
{
	protected $doctor;

	//接收成为医生
	public function acceptDoctor($id)
	{
		$this->doctor = DB::table('doctors')->select('id', 'status_type')->where('id', $id)->first();
		if ($this->judgeType(1)) {
			if (DB::table('doctors')->where('id', $id)->update(['status' => 3])) {
				return response()->json(['code' => 0, 'msg' => 'success']);
			}
		} else {
			return response()->json(['code' => -2, 'msg' => '申请类型与审批类型不一致']);
		}

	}

	//接受成为医师
	public function acceptPhysicians($id)
	{
		$this->doctor = DB::table('doctors')->select('id', 'status_type')->where('id', $id)->first();
		if ($this->judgeType(2)){
			if (DB::table('doctors')->where('id', $id)->update(['status' => 3])) {
				return response()->json(['code' => 0, 'msg' => 'success']);
			}
		} else {
			return response()->json(['code' => -2, 'msg' => '申请类型与审批类型不一致']);
		}

	}

	//审核通过群组创建
	public function acceptGroup($id)
	{
		$group = DB::table('group')->select('id', 'status')->where('id', $id)->first();
		if (empty($group)) {
			return response()->json(['code' => -2, 'msg' => '不存在此群']);
		}
		if ($group->status != 1) {
			return response()->json(['code' => -3, 'msg' => '此群已通过审核']);
		}
		$row = DB::table('group')->where('id', $id)->update([
			'status' => 3
		]);
		if ($row) {
			return response()->json(['code' => 0, 'msg' => 'success']);
		} else {
			return response()->json(['code' => -4, 'msg' => '审批失败']);
		}
	}

	//判断申请类型
	protected function judgeType($type)
	{
		return $this->doctor->status_type == $type;
	}

}