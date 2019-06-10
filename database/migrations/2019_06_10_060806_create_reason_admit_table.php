<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReasonAdmitTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reason_admit', function(Blueprint $table)
		{
			$table->integer('ra_id', true);
			$table->text('ra_name');
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
		Schema::drop('reason_admit');
	}

}
