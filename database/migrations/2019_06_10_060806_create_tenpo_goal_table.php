<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTenpoGoalTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tenpo_goal', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('tenpo_id')->default(0);
			$table->integer('year')->default(0);
			$table->integer('month')->default(0);
			$table->integer('day')->default(0);
			$table->integer('g_sales')->default(0);
			$table->integer('g_profit')->default(0);
			$table->text('s_time');
			$table->text('s_minutes');
			$table->text('e_time');
			$table->text('e_minutes');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tenpo_goal');
	}

}
