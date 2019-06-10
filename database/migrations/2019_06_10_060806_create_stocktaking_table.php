<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStocktakingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stocktaking', function(Blueprint $table)
		{
			$table->integer('sttid', true);
			$table->integer('pid')->default(0);
			$table->integer('stock_tid')->default(0);
			$table->string('real_stock', 20)->nullable();
			$table->text('remark')->nullable();
			$table->dateTime('st_date')->nullable();
			$table->dateTime('st_update')->nullable();
			$table->string('uid', 20)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('stocktaking');
	}

}
