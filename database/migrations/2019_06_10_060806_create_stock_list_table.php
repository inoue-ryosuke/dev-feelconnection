<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStockListTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stock_list', function(Blueprint $table)
		{
			$table->bigInteger('stid', true);
			$table->integer('tid')->default(0);
			$table->dateTime('ship_date')->nullable();
			$table->dateTime('storage_date')->nullable();
			$table->dateTime('updatetime')->nullable();
			$table->string('ship_purpose', 4)->nullable();
			$table->integer('destination')->default(0);
			$table->integer('dest_sup_flg')->default(0);
			$table->string('situation', 4)->nullable();
			$table->string('ship_no', 20)->nullable();
			$table->text('comment')->nullable();
			$table->text('remark')->nullable();
			$table->string('uid', 10)->nullable();
			$table->string('storage_uid', 10)->nullable();
			$table->integer('flg')->default(0);
			$table->string('del_id', 10)->nullable();
			$table->dateTime('del_date')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('stock_list');
	}

}
