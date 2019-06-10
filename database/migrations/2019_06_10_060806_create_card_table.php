<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCardTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('card', function(Blueprint $table)
		{
			$table->integer('crid', true);
			$table->integer('seq')->default(0);
			$table->integer('tid')->nullable();
			$table->text('cardname');
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
		Schema::drop('card');
	}

}
