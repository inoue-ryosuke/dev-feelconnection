<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLessonMasterTenpoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lesson_master_tenpo', function(Blueprint $table)
		{
			$table->integer('lid_t');
			$table->integer('tenpo_id')->nullable()->default(0);
			$table->integer('capa')->default(0);
			$table->integer('capa_tk');
			$table->integer('taiken_mess');
			$table->integer('flg_t')->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('lesson_master_tenpo');
	}

}
