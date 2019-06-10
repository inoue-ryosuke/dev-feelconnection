<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePreparationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('preparation', function(Blueprint $table)
		{
			$table->bigInteger('preid', true);
			$table->integer('tid')->nullable();
			$table->date('set_date')->nullable();
			$table->integer('reserve')->nullable();
			$table->integer('reg_uid')->nullable();
			$table->dateTime('regdate')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('preparation');
	}

}
