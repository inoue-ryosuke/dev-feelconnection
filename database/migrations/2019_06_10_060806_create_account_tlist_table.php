<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccountTlistTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('account_tlist', function(Blueprint $table)
		{
			$table->integer('atid', true);
			$table->integer('cid');
			$table->integer('tenpo_id');
			$table->integer('flg')->default(1);
			$table->string('order_code', 30);
			$table->string('authori_code', 30);
			$table->integer('at_condition');
			$table->integer('at_araigae');
			$table->integer('at_memtype')->nullable();
			$table->integer('at_store_id')->nullable();
			$table->date('transfer_date');
			$table->integer('price');
			$table->integer('reg_uid');
			$table->dateTime('reg_date');
			$table->string('transfer_flg', 1);
			$table->integer('inport_uid');
			$table->dateTime('inport_date');
			$table->text('t_menu');
			$table->text('bank_info');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('account_tlist');
	}

}
