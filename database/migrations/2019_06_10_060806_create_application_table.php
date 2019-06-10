<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApplicationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('application', function(Blueprint $table)
		{
			$table->integer('apid', true);
			$table->integer('type');
			$table->integer('ap_memtype');
			$table->integer('flg')->default(1);
			$table->dateTime('ap_datetime');
			$table->integer('ap_tenpo');
			$table->integer('ap_shozoku');
			$table->integer('ap_uid');
			$table->date('ap_date');
			$table->integer('ap_cid');
			$table->string('ap_name', 32);
			$table->integer('ap_reason');
			$table->date('ra_date');
			$table->text('bikou');
			$table->text('stop_ticket');
			$table->text('stop_club');
			$table->text('stop_ap');
			$table->text('start_ticket');
			$table->text('substitute_ticket');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('application');
	}

}
