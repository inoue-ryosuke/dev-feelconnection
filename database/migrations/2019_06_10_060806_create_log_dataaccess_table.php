<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLogDataaccessTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('log_dataaccess', function(Blueprint $table)
		{
			$table->bigInteger('aid', true);
			$table->bigInteger('uid')->nullable();
			$table->integer('tenpo_id')->nullable();
			$table->date('access_date')->nullable();
			$table->text('access_time')->nullable();
			$table->text('logout_time')->nullable();
			$table->text('lreg_time')->nullable();
			$table->text('ldel_time')->nullable();
			$table->text('zenrin_time')->nullable();
			$table->text('csv_time')->nullable();
			$table->text('label_time')->nullable();
			$table->text('accept_time')->nullable();
			$table->text('orderedit')->nullable();
			$table->text('lessonedit')->nullable();
			$table->text('club_time')->nullable();
			$table->text('custreg2')->nullable();
			$table->text('custdel2')->nullable();
			$table->text('lessonedit2')->nullable();
			$table->text('salereg2')->nullable();
			$table->text('saledel2')->nullable();
			$table->text('orderedit2')->nullable();
			$table->text('ship2')->nullable();
			$table->text('storage2')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('log_dataaccess');
	}

}
