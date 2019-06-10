<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOneTimeCouponTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('one_time_coupon', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->char('available_flag', 1)->default('Y');
			$table->char('coupon_code', 16);
			$table->integer('prod_id');
			$table->char('used_flag', 1)->default('N');
			$table->integer('used_by_cid')->nullable();
			$table->integer('used_by_memberid')->nullable();
			$table->dateTime('used_at')->nullable();
			$table->dateTime('created_at')->nullable();
			$table->dateTime('modified_at')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('one_time_coupon');
	}

}
