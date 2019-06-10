<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGtmDatalayerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gtm_datalayer', function(Blueprint $table)
		{
			$table->integer('cid')->primary('gtm_datalayer_pkc');
			$table->integer('memtype')->nullable();
			$table->integer('tenpoid')->nullable();
			$table->char('birth_ymd', 8)->nullable();
			$table->char('sex', 1)->nullable();
			$table->char('admit_ymd', 8)->nullable();
			$table->char('last_order_lesson_reg_ymd', 8)->nullable();
			$table->char('last_took_lesson_ymd', 8)->nullable();
			$table->integer('in30_order_lesson_reg_times')->nullable();
			$table->integer('in30_took_lesson_times')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('gtm_datalayer');
	}

}
