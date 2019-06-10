<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStockItemTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stock_item', function(Blueprint $table)
		{
			$table->bigInteger('stiid', true);
			$table->bigInteger('stid')->nullable();
			$table->dateTime('storage_datetime');
			$table->dateTime('over_datetime');
			$table->integer('pid')->default(0);
			$table->integer('part_flg')->default(0);
			$table->integer('part_amount');
			$table->integer('storage_amount');
			$table->integer('ship_amount');
			$table->integer('reg_uid');
			$table->integer('flg')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('stock_item');
	}

}
