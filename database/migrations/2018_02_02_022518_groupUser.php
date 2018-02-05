<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GroupUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('group_user', function (Blueprint $table) {
			$table->increments('id');	//主键
			$table->integer('gid')->index();	//群id
			$table->integer('uid')->index();	//用户id
			$table->integer('status')->default(0);	//状态 1 通过  2 未通过
			$table->integer('type');
			$table->index(['uid', 'gid', 'type']);	//联合索引
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
		Schema::drop('group_user');
    }
}
