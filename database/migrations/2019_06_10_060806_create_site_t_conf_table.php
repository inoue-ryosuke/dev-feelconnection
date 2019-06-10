<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSiteTConfTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('site_t_conf', function(Blueprint $table)
		{
			$table->integer('stid', true);
			$table->integer('tid')->nullable();
			$table->integer('t_0')->nullable()->default(1);
			$table->integer('t_1')->nullable()->default(1);
			$table->integer('t_2')->nullable()->default(1);
			$table->integer('t_3')->nullable()->default(1);
			$table->integer('t_4')->nullable()->default(1);
			$table->integer('t_5')->nullable()->default(1);
			$table->integer('t_6')->nullable()->default(1);
			$table->integer('t_7')->nullable()->default(1);
			$table->integer('t_8')->nullable()->default(1);
			$table->integer('t_9')->nullable()->default(1);
			$table->integer('t_10')->nullable()->default(1);
			$table->integer('t_11')->nullable()->default(1);
			$table->integer('t_12')->nullable()->default(1);
			$table->integer('t_13')->nullable()->default(1);
			$table->integer('t_14')->nullable()->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('site_t_conf');
	}

}
