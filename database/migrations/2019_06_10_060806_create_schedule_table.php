<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateScheduleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('schedule', function(Blueprint $table)
		{
			$table->integer('sc_id', true);
			$table->integer('sc_soid');
			$table->integer('sc_flg')->default(1);
			$table->integer('appli_flg');
			$table->integer('sc_cid');
			$table->date('sc_date');
			$table->integer('sc_memtype');
			$table->integer('sc_tenpo');
			$table->dateTime('sc_regdate');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('schedule');
	}

}
