<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccountLaunderingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('account_laundering', function(Blueprint $table)
		{
			$table->integer('alid', true);
			$table->dateTime('set_date');
			$table->dateTime('laundering_date');
			$table->integer('set_flg');
			$table->integer('uid');
			$table->integer('laundering_cnt');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('account_laundering');
	}

}
