<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTblHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tbl_history', function(Blueprint $table)
		{
			$table->integer('hid', true);
			$table->string('user')->nullable();
			$table->integer('setting_id')->default(0);
			$table->dateTime('career_date')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tbl_history');
	}

}
