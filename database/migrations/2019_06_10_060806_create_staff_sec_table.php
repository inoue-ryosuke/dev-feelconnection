<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStaffSecTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('staff_sec', function(Blueprint $table)
		{
			$table->integer('prev_id', true);
			$table->string('prev_name', 16)->nullable();
			$table->integer('login')->default(0);
			$table->integer('h_private')->default(0);
			$table->integer('h_accept')->default(0);
			$table->integer('h_sales')->default(0);
			$table->integer('h_prog_reg')->default(0);
			$table->integer('h_prog')->default(0);
			$table->integer('h_close')->default(0);
			$table->integer('h_search')->default(0);
			$table->integer('h_mail')->default(0);
			$table->integer('h_stock')->default(0);
			$table->integer('h_unpay')->default(0);
			$table->integer('h_club')->default(0);
			$table->integer('h_ana')->default(0);
			$table->integer('h_correct')->default(0);
			$table->integer('h_account_transfer')->default(0);
			$table->integer('h_contract')->default(0);
			$table->integer('h_set')->default(0);
			$table->integer('h_sec')->default(0);
			$table->integer('h_shop_order')->default(0);
			$table->integer('t_accept')->default(0);
			$table->integer('t_accept_xls')->default(0);
			$table->integer('t_sales')->default(0);
			$table->integer('t_prog_reg')->default(0);
			$table->integer('t_prog')->default(0);
			$table->integer('t_close')->default(0);
			$table->integer('t_stock')->default(0);
			$table->integer('t_unpay')->default(0);
			$table->integer('t_club')->default(0);
			$table->integer('t_ana')->default(0);
			$table->integer('t_search')->default(0);
			$table->integer('t_mail')->default(0);
			$table->integer('t_correct')->default(0);
			$table->integer('t_set')->default(0);
			$table->integer('t_private')->default(0);
			$table->integer('t_account_transfer')->default(0);
			$table->integer('t_shop_order')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('staff_sec');
	}

}
