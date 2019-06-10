<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContractChangeHistTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contract_change_hist', function(Blueprint $table)
		{
			$table->integer('ccid', true);
			$table->integer('cc_customer_id');
			$table->dateTime('cc_updatetime');
			$table->integer('cc_tenpo');
			$table->integer('cc_uid');
			$table->integer('cc_memberid');
			$table->string('cc_name', 64);
			$table->integer('cc_entry');
			$table->integer('cc_memtype');
			$table->integer('cc_store_id');
			$table->date('cc_sc_date');
			$table->integer('cc_sc_memtype');
			$table->integer('cc_sc_tenpo');
			$table->integer('cc_at_flg');
			$table->integer('cc_entry_before');
			$table->integer('cc_memtype_before');
			$table->integer('cc_store_id_before');
			$table->date('cc_sc_date_before');
			$table->integer('cc_sc_memtype_before');
			$table->integer('cc_sc_tenpo_before');
			$table->integer('cc_at_flg_before');
			$table->text('memo');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('contract_change_hist');
	}

}
