<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRankTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rank', function(Blueprint $table)
		{
			$table->integer('term')->default(0);
			$table->integer('type')->default(0);
			$table->integer('type2')->default(0);
			$table->integer('start_m')->default(0);
			$table->integer('ra')->default(0);
			$table->integer('rb')->default(0);
			$table->integer('rc')->default(0);
			$table->integer('rd')->default(0);
			$table->integer('ba')->default(0);
			$table->integer('bb')->default(0);
			$table->integer('bc')->default(0);
			$table->integer('bd')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('rank');
	}

}
