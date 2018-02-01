<?php

namespace App\Providers;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService
{
	protected $file, $file_name, $file_path;
	public function __construct(UploadedFile $file)
	{
		$this->file = $file;
	}

	public function upload()
	{
		if ($this->imgJudge()) {
			$upload_ret = $this->uploadWithQiNiu($this->file_path, $this->file_name);
			if ($upload_ret) {
				unlink($this->file_path);
				$url = 'http://'.env('QINIU_DOMAIN_NAME').$upload_ret;
				return $url;
			} else {
				return 1;
			}
		} else {
			return 2;
		}
	}

	//图片格式检验,合法时返回图片地址,不合法时返回false
	protected function imgJudge()
	{
		$type_array = ['jpg', 'png', 'jpeg'];
		$mime_array = ['image/jpeg', 'image/png'];
		$file_type = $this->file->guessExtension();
		$file_mime = $this->file->getMimeType();
		if (in_array($file_type, $type_array) and in_array($file_mime, $mime_array)) {
			$this->file_name = md5(uniqid()).'.'.$file_type;
			$this->file->move(base_path().'/public/', $this->file_name);
			$this->file_path = base_path().'/public/'.$this->file_name;
			return true;
		} else {
			return false;
		}
	}

	protected function uploadWithQiNiu($file_path, $file_name)
	{
		$access_key = env('QINIU_ACCESS_KEY');
		$secret_key = env('QINIU_SECRET_KEY');
		$bucket = env('QINIU_BUCKET_NAME');
		$auth = new Auth($access_key, $secret_key);
		$token = $auth->uploadToken($bucket);
		$upload_manager = new UploadManager();
		list($ret, $err) = $upload_manager->putFile($token, $file_name, $file_path);
		if (!empty($err)) {
			return false;
		} else {
			return $ret['key'];
		}
	}
}