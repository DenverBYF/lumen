<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class User extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('users', function (Blueprint $table) {
			$table->increments('id');	//唯一id
			$table->string('name');		//真实姓名
			$table->string('username');	//用户名
			$table->char('password', 32);	//密码(sha1加密)
			$table->string('email');	//邮箱
			$table->string('tel');	//手机号
			$table->string('province')->nullable();	//所处省
			$table->string('city')->nullable();	//所处市
			$table->longText('desc')->nullable();	//个人病史
			$table->string('img_url')->nullable();	//个人头像
			$table->timestamps();	//created_at && update_at
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
		Schema::drop('users');
    }
}
