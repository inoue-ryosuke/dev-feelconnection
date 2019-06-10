<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderLessonTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_lesson', function(Blueprint $table)
		{
			$table->integer('oid', true);
			$table->integer('tenpo_id')->default(0);
			$table->date('order_date')->nullable();
			$table->dateTime('checkin_date');
			$table->integer('customer_id')->default(0);
			$table->integer('memtype');
			$table->integer('flg')->nullable()->default(1);
			$table->integer('sb_flg')->nullable()->default(0);
			$table->integer('nflg')->nullable()->default(0);
			$table->integer('add_up')->default(0);
			$table->integer('another')->default(0);
			$table->integer('no_can_useup')->default(0);
			$table->integer('no_can_treat')->default(0);
			$table->integer('cancel_prohibit')->default(0);
			$table->integer('ngmail');
			$table->integer('sid')->nullable();
			$table->integer('lid')->nullable();
			$table->integer('tkid')->nullable();
			$table->integer('ctkid')->nullable();
			$table->integer('sheet');
			$table->integer('reg_uid')->nullable();
			$table->dateTime('reg_date')->nullable();
			$table->integer('nocan_flg')->nullable()->default(0);
			$table->integer('cancel_tik')->default(0);
			$table->integer('experience')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('order_lesson');
	}

}
