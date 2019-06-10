<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCancelHistTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cancel_hist', function(Blueprint $table)
		{
			$table->integer('canid', true);
			$table->integer('sid');
			$table->integer('tenpo_id')->default(0);
			$table->date('order_date')->nullable();
			$table->integer('customer_id')->default(0);
			$table->integer('memtype')->default(0);
			$table->integer('nflg')->nullable()->default(0);
			$table->integer('lid')->nullable()->default(0);
			$table->integer('reg_uid')->nullable()->default(0);
			$table->dateTime('reg_date')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cancel_hist');
	}

}
