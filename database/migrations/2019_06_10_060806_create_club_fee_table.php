<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClubFeeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('club_fee', function(Blueprint $table)
		{
			$table->bigInteger('cfid', true);
			$table->integer('tenpo_id')->nullable()->default(0);
			$table->integer('iid')->nullable()->default(0);
			$table->date('order_date')->nullable();
			$table->date('depo_date')->nullable();
			$table->integer('customer_id')->nullable()->default(0);
			$table->integer('flg')->nullable()->default(1);
			$table->integer('reg_flg');
			$table->integer('at_flg');
			$table->integer('ticket')->nullable()->default(0);
			$table->integer('tanka')->nullable()->default(0);
			$table->date('start_date');
			$table->date('expire')->nullable();
			$table->date('expire_cl_recover');
			$table->integer('pay')->nullable()->default(0);
			$table->integer('kessai')->nullable()->default(0);
			$table->integer('card')->nullable()->default(0);
			$table->dateTime('reg_date')->nullable();
			$table->integer('reg_uid')->nullable()->default(0);
			$table->text('memo')->nullable();
			$table->integer('tcount')->nullable()->default(0);
			$table->integer('tcount_max');
			$table->string('upid', 20)->nullable();
			$table->integer('withdraw_fail')->nullable()->default(0);
			$table->date('withdraw_date')->nullable();
			$table->string('ch_exp', 1)->nullable()->default('0');
			$table->integer('kiyaku_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('club_fee');
	}

}
