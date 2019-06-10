<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderListTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_list', function(Blueprint $table)
		{
			$table->integer('oid', true);
			$table->integer('tenpo_id')->default(0);
			$table->date('order_date')->nullable();
			$table->integer('customer_id')->default(0);
			$table->integer('memberid_reg');
			$table->integer('memtype');
			$table->integer('tenpo_posi');
			$table->integer('tenpo_reg');
			$table->string('kessai_shubetu', 16)->nullable();
			$table->string('flg', 8)->nullable()->default('Y');
			$table->integer('lflg')->default(0);
			$table->integer('soid');
			$table->integer('sflg')->default(0);
			$table->integer('net_soid')->default(0);
			$table->integer('at_flg');
			$table->text('onepoint')->nullable();
			$table->integer('sales_lesson')->nullable();
			$table->integer('sales_buppan')->nullable();
			$table->integer('sales_other')->nullable();
			$table->integer('sales')->nullable();
			$table->integer('sreg_person');
			$table->integer('nebiki')->nullable();
			$table->integer('charge')->default(0);
			$table->integer('sales_total')->nullable();
			$table->integer('pay1')->nullable();
			$table->integer('kessai1')->nullable();
			$table->integer('card1')->default(0);
			$table->integer('pay2')->nullable();
			$table->integer('kessai2')->nullable();
			$table->integer('card2')->default(0);
			$table->integer('deposit')->default(0);
			$table->integer('c_change')->default(0);
			$table->string('baitai1', 64)->nullable();
			$table->string('baitai2', 64)->nullable();
			$table->integer('reg_uid')->nullable();
			$table->dateTime('reg_time')->nullable();
			$table->text('del_uid')->nullable();
			$table->date('del_date')->nullable();
			$table->time('del_time')->nullable();
			$table->integer('unpay')->nullable();
			$table->text('memo')->nullable();
			$table->integer('process_flg')->default(0);
			$table->dateTime('created_at')->nullable();
			$table->integer('created_by')->nullable();
			$table->dateTime('modified_at')->nullable();
			$table->integer('modified_by')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('order_list');
	}

}
