<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReasonRestTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reason_rest', function(Blueprint $table)
		{
			$table->integer('rr_id', true);
			$table->text('rr_name');
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
		Schema::drop('reason_rest');
	}

}
