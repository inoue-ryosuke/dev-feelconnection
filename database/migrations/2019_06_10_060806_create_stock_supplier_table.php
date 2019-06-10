<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStockSupplierTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stock_supplier', function(Blueprint $table)
		{
			$table->bigInteger('suid', true);
			$table->integer('tid');
			$table->text('maker')->nullable();
			$table->string('flg', 1)->default('0');
			$table->integer('seq');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('stock_supplier');
	}

}
