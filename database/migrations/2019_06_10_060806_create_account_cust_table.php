<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccountCustTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('account_cust', function(Blueprint $table)
		{
			$table->integer('cid');
			$table->integer('tenpo_id');
			$table->integer('flg')->default(1);
			$table->string('gmo_id', 64);
			$table->integer('araigae')->default(0);
			$table->integer('araigae_ng_cnt');
			$table->integer('at_condition');
			$table->integer('unpay');
			$table->integer('monthly');
			$table->integer('m_01');
			$table->integer('m_02');
			$table->integer('m_03');
			$table->integer('m_04');
			$table->integer('m_05');
			$table->integer('m_06');
			$table->integer('m_07');
			$table->integer('m_08');
			$table->integer('m_09');
			$table->integer('m_10');
			$table->integer('m_11');
			$table->integer('m_12');
			$table->date('start');
			$table->date('end');
			$table->integer('end_flg');
			$table->text('menu');
			$table->integer('first_amount');
			$table->integer('monthly_amount');
			$table->string('credit_aname', 20);
			$table->string('credit_num', 20);
			$table->string('credit_exp', 20);
			$table->string('credit_class', 20);
			$table->string('b_code', 20);
			$table->string('b_name', 20);
			$table->string('br_code', 20);
			$table->string('br_name', 20);
			$table->string('c_code', 20)->default('1');
			$table->string('c_name', 20);
			$table->string('a_name', 20);
			$table->string('a_number', 20);
			$table->string('y_code', 20);
			$table->string('y_number', 20);
			$table->string('ya_name', 20);
			$table->integer('bank_type')->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('account_cust');
	}

}
