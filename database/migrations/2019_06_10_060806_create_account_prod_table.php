<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccountProdTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('account_prod', function(Blueprint $table)
		{
			$table->integer('atpid', true);
			$table->integer('at_flg')->default(1);
			$table->integer('at_cid');
			$table->integer('at_soid');
			$table->integer('at_sgid');
			$table->integer('at_pid');
			$table->integer('at_bclass');
			$table->integer('at_class');
			$table->date('start_date');
			$table->date('end_date');
			$table->date('end_date_recover');
			$table->integer('at_lot')->default(1);
			$table->integer('at_price');
			$table->integer('at_ticket_type');
			$table->integer('tkid');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('account_prod');
	}

}
