<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReasonQuitTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reason_quit', function(Blueprint $table)
		{
			$table->integer('rq_id', true);
			$table->text('rq_name');
			$table->integer('flg')->default(1);
			$table->integer('seq');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('reason_quit');
	}

}
