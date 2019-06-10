<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSiteHConfTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('site_h_conf', function(Blueprint $table)
		{
			$table->integer('h_0')->nullable()->default(1);
			$table->integer('h_1')->nullable()->default(1);
			$table->integer('h_2')->nullable()->default(1);
			$table->integer('h_3')->nullable()->default(1);
			$table->integer('h_4')->nullable()->default(1);
			$table->integer('h_5')->nullable()->default(1);
			$table->integer('h_6')->nullable()->default(1);
			$table->integer('h_7')->nullable()->default(1);
			$table->integer('h_8')->nullable()->default(1);
			$table->integer('h_9')->nullable()->default(1);
			$table->integer('h_10')->nullable()->default(1);
			$table->integer('h_11')->nullable()->default(1);
			$table->integer('h_12')->nullable()->default(1);
			$table->integer('h_13')->nullable()->default(1);
			$table->integer('h_14')->nullable()->default(1);
			$table->integer('h_15')->nullable()->default(1);
			$table->integer('h_16')->nullable()->default(1);
			$table->integer('h_17')->nullable()->default(1);
			$table->integer('h_18')->nullable()->default(1);
			$table->integer('h_19')->nullable()->default(1);
			$table->integer('h_20')->default(1);
			$table->integer('h_21')->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('site_h_conf');
	}

}
