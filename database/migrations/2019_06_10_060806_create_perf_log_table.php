<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePerfLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('perf_log', function(Blueprint $table)
		{
			$table->integer('plid', true);
			$table->integer('pid');
			$table->integer('pl_user');
			$table->dateTime('pl_datetime');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('perf_log');
	}

}
