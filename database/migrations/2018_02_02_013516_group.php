<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Group extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('group', function (Blueprint $table) {
			$table->increments('id');	//唯一id
			$table->string('name');		//群名称
			$table->string('desc')->nullable();	//简介
			$table->string('area');		//科室
			$table->integer('member')->default(1);	//成员数
			$table->integer('max')->default(500);	//最大成员数
			$table->integer('uid')->index();		//群主对应用户id
			$table->integer('status')->default(1);	//审核状态,1为提交审核,2为未通过,3为通过
			$table->string('refuse_reason')->nullable();  //审核未通过理由
			$table->timestamps();
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
		Schema::drop('group');
    }
}
