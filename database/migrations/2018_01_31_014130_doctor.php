<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Doctor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('doctors', function (Blueprint $table) {
			$table->increments('id');	//唯一id
			$table->string('name');	//真实姓名
			$table->string('username');	//用户名
			$table->string('tel');	//手机(登陆凭证)
			$table->char('password', 40);	//密码 (sha1加密)
			$table->char('card', 18);	//身份证号
			$table->string('hospital');	//所在医院
			$table->string('area');	//所在科室
			$table->string('title')->nullable();	//个人职称,可空
			$table->string('desc')->nullable();	//个人简介,可空
			$table->string('email');	//邮箱
			$table->integer('user_type')->nullable();	//医师(1) or 医生(2)
			$table->string('img_url')->nullable();	//头像地址
			$table->integer('status')->default(0);	//审核状态,未审核为0,提交审核后设为1,未通过设为2
			$table->string('reason')->nullable();	//审核未通过原因
			$table->string('card_url')->nullable();	//身份证照片地址
			$table->string('doctor_card_url')->nullable();	//医生资格证照片地址
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
		Schema::drop('doctors');
    }
}
