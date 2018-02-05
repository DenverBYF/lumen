<?php
/**
 * Created by PhpStorm.
 * User: denverb
 * Date: 18/2/2
 * Time: 上午9:46
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class GroupController extends Controller
{
	//todo 消息通知

	//群列表
	public function index($page)
	{
		$pageNum = 10;
		$offset = ($page - 1) * $pageNum;
		$groups = DB::table('group')
			->select('id', 'name', 'desc', 'area', 'member', 'max', 'status')
			->where('status', 3)
			->orderby('id', 'desc')
			->offset($offset)
			->limit($pageNum)
			->get();
		return response()->json(['code' => 0, 'msg' => $groups]);
	}

	//具体群信息
	public function show($id)
	{
		$group = DB::table('group')
			->select('id', 'name', 'area', 'desc', 'member', 'max', 'status')
			->where('id', $id)
			->first();
		if (empty($group) or $group->status != 3) {
			return response()->json(['code' => -2, 'msg' => '该群不存在或未通过审核']);
		} else {
			return response()->json(['code' => 0, 'msg' => $group]);
		}
	}

	//创建群
	public function create(Request $request)
	{
		try {
			$this->validate($request, [
				'name' => 'required|max:20|unique:group',
				'area' => 'required',
			]);
		} catch (ValidationException $e) {
			return response()->json(['code' => -2, 'msg' => $e->response->original]);
		}
		$row = DB::table('group')->insert([
			'name' => $request->input('name'),
			'desc' => $request->input('desc')?? null,
			'area' => $request->input('area'),
			'max' => $request->input('max')?? 500,
			'uid' => $_SESSION['id'],
			'status' => 1,
		]);
		if ($row) {
			return response()->json(['code' => 0, 'msg' => 'success']);
		} else {
			return response()->json(['code' => -3, 'msg' => '创建失败']);
		}
	}

	//申请加入
	public function apply($id)
	{
		$userId = $_SESSION['id'];
		$group = $this->findGroup($id);
		if (empty($group)) {
			return response()->json(['code' => -2, 'msg' => '该群不存在或未通过审核']);
		}
		$row = DB::table('group_user')->insert([
			'gid' => $group->id,
			'uid' => $userId,
			'type' => $_SESSION['type'],
		]);
		if ($row) {
			return response()->json(['code' => 0, 'msg' => 'success']);
		} else {
			return response()->json(['code' => -3, 'msg' => '申请失败']);
		}
	}

	//删除群
	public function delete($id)
	{
		$userId = $_SESSION['id'];
		$group = $this->findGroup($id);
		if (empty($group)) {
			return response()->json(['code' => -2, 'msg' => '该群不存在或未通过审核']);
		}
		if ($group->uid != $userId) {
			return response()->json(['code' => -3, 'msg' => '仅群主可删除群']);
		}
		DB::table('group')->where('id', $id)->delete();
		DB::table('group_user')->where('gid', $id)->delete();
		return response()->json(['code' => 0, 'msg' => 'success']);
	}

	//更新群信息
	public function update(Request $request, $id)
	{
		$userId = $_SESSION['id'];
		$group = $this->findGroup($id);
		if (empty($group)) {
			return response()->json(['code' => -2, 'msg' => '该群不存在或未通过审核']);
		}
		if ($group->uid != $userId) {
			return response()->json(['code' => -3, 'msg' => '仅群主可更新群信息']);
		}
		$row = DB::table('group')->where('id', $id)->update($request->all());
		if ($row) {
			return response()->json(['code' => 0, 'msg' => 'success']);
		} else {
			return response()->json(['code' => -3, 'msg' => '更新失败']);
		}
	}

	//批准群成员加入
	public function accept($id, $uid)
	{
		$userId = $_SESSION['id'];
		$group = $this->findGroup($id);
		if (empty($group)) {
			return response()->json(['code' => -2, 'msg' => '该群不存在或未通过审核']);
		}
		if ($group->uid != $userId) {
			return response()->json(['code' => -3, 'msg' => '仅群主可审批人员加入']);
		}
		$row = DB::table('group_user')->where('id', $uid)->update([
			'status' => 1,
		]);
		if ($row) {
			return response()->json(['code' => 0, 'msg' => 'success']);
		} else {
			return response()->json(['code' => -3, 'msg' => '审批失败']);
		}
	}

	//踢出群成员
	public function kick($id, $uid)
	{
		$userId = $_SESSION['id'];
		$group = $this->findGroup($id);
		if (empty($group)) {
			return response()->json(['code' => -2, 'msg' => '该群不存在或未通过审核']);
		}
		if ($group->uid != $userId) {
			return response()->json(['code' => -3, 'msg' => '仅群主可踢出群成员']);
		}
		$row = DB::table('group_user')->where('id', $uid)->delete();
		if ($row) {
			return response()->json(['code' => 0, 'msg' => 'success']);
		} else {
			return response()->json(['code' => -3, 'msg' => '失败']);
		}
	}

	protected function findGroup($id)
	{
		$group = DB::table('group')->select('id', 'uid', 'status')->where('id', $id)->first();
		if ($group->status != 3) {
			return null;
		}
		return $group;
	}
}