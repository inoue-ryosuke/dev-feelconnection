<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClassTenpoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('class_tenpo', function(Blueprint $table)
		{
			$table->integer('dpid')->default(0);
			$table->integer('dtid')->default(0);
			$table->char('dflg', 1)->nullable()->default('N');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('class_tenpo');
	}

}
