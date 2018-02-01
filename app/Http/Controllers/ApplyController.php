<?php
/**
 * Created by PhpStorm.
 * User: denverb
 * Date: 18/2/1
 * Time: 上午10:13
 */

namespace App\Http\Controllers;

use App\Providers\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/*
 * 资质申请,医生 or 医师
 * 0:success
 * -1:no auth
 * -2:request error
 * -3:not image
 * -4:server error
 * */
class ApplyController extends Controller
{
	protected $user, $request, $card_url, $doctor_card_url, $upload_error;
	public function __construct(Request $request)
	{
		$this->user = DB::table('doctors')->select('id', 'status')->where('id', 2)->first();
		$this->request = $request;
		$this->upload_error = 0;
	}

	//申请成为医生
	public function applyDoctor()
	{
		$this->judgeStatus();
		if ($error = $this->dataJudge()) {
			return response()->json(['code' => -2, 'msg' => $error]);
		} else {
			switch ($this->upload_error) {
				case 1:
					return response()->json(['code' => -3, 'msg' => '上传失败']);
					break;
				case 2:
					return response()->json(['code' => -4, 'msg' => '图片不合法']);
					break;
				case 3:
					return response()->json(['code' => -5, 'msg' => '已经处于申请状态,请等待审核结果']);
					break;
				case 0:
					$row = DB::table('doctors')->where('id', 2)
						->update(['card_url' => $this->card_url,
							'doctor_card_url' => $this->doctor_card_url,
							'status' => 1,
							'status_type' => 1
							]);
					if ($row) {
						return response()->json(['code' => 0, 'msg' => 'success']);
					} else {
						return response()->json(['code' => -6, 'msg' => '数据库更新失败']);
					}
					break;
				default :
					break;
			}
		}
	}

	//申请成为医师
	public function applyPhysicians()
	{
		if ($error = $this->dataJudge()) {
			return response()->json(['code' => -2, 'msg' => $error]);
		} else {
			switch ($this->upload_error) {
				case -1:
					return response()->json(['code' => -3, 'msg' => '上传失败']);
					break;
				case -2:
					return response()->json(['code' => -4, 'msg' => '图片不合法']);
					break;
				case -3:
					return response()->json(['code' => -5, 'msg' => '已经处于申请状态,请等待审核结果']);
					break;
				default:
					$row = DB::table('doctors')->where('id', 2)
						->update(['card_url' => $this->card_url,
							'doctor_card_url' => $this->doctor_card_url,
							'status' => 1,
							'status_type' => 2
							]);
					if ($row) {
						return response()->json(['code' => 0, 'msg' => 'success']);
					} else {
						return response()->json(['code' => -6, 'msg' => '数据库更新失败']);
					}
					break;
			}
		}
	}

	//查看审核状态,看是否处于申请中。
	protected function judgeStatus()
	{
		if ($this->user->status != 0) {
			$this->upload_error = 3;
		}
	}

	//请求数据检验
	protected function dataJudge()
	{
		try {
			$this->validate($this->request, [
				'card' => 'required|file',
				'doctor_card' => 'required|file',
				/*'desc' => 'required',
				'reason' =>'required'*/
			]);
		} catch (ValidationException $e) {
			return $e->response->original;
		}
		$card_img = $this->request->file('card');
		$doctor_card_img = $this->request->file('doctor_card');
		$this->card_url = $this->fileUpload($card_img);
		$this->doctor_card_url = $this->fileUpload($doctor_card_img);
		return false;
	}

	//文件处理并上传至七牛云
	protected function fileUpload($file)
	{
		$file_service = new FileService($file);
		$ret = $file_service->upload();
		if (in_array($ret, [1, 2])) {
			$this->upload_error = $ret;
		}
		return $ret;
	}

}