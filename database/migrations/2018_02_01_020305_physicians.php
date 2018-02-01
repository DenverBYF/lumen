<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Physicians extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('physicians', function (Blueprint $table) {
			$table->increments('id');	//唯一id
			$table->integer('did');		//对应医生表中唯一id
			$table->integer('status')->default(0);	//审核状态,默认为0,通过为1,未通过为2
			$table->string('apply_reason');		//申请理由
			$table->string('refuse_reason')->nullable();	//审核未通过理由
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
		Schema::drop('physicians');
    }
}
