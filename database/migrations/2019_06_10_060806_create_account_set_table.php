<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccountSetTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('account_set', function(Blueprint $table)
		{
			$table->integer('asid', true);
			$table->integer('at_condition')->default(1);
			$table->date('transfer_date');
			$table->dateTime('set_date');
			$table->integer('at_count');
			$table->integer('set_uid');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('account_set');
	}

}
