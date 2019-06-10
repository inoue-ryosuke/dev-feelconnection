<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReceptListTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('recept_list', function(Blueprint $table)
		{
			$table->integer('rlid', true);
			$table->integer('tenpo_id');
			$table->integer('customer_id');
			$table->date('rec_date');
			$table->time('rec_time');
			$table->integer('recepter');
			$table->integer('class');
			$table->text('memo');
			$table->dateTime('reg_date');
			$table->integer('flg')->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('recept_list');
	}

}
