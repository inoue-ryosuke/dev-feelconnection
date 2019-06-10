<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReceptClassTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('recept_class', function(Blueprint $table)
		{
			$table->integer('rcid', true);
			$table->integer('tid')->nullable();
			$table->text('classname');
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
		Schema::drop('recept_class');
	}

}
