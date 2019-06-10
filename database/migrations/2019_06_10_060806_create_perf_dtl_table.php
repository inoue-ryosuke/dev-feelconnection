<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePerfDtlTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('perf_dtl', function(Blueprint $table)
		{
			$table->integer('pdid', true);
			$table->integer('pid');
			$table->text('card');
			$table->text('card_oth');
			$table->text('sejutsu');
			$table->text('buppan');
			$table->text('other');
			$table->text('charge');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('perf_dtl');
	}

}
