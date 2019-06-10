<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTblReceiptTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tbl_receipt', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('tid')->nullable();
			$table->integer('flg')->nullable()->default(0);
			$table->integer('sales_reg')->default(0);
			$table->integer('print_reg')->default(0);
			$table->string('image_path')->nullable();
			$table->text('tenpo_name')->nullable();
			$table->text('free1')->nullable();
			$table->text('free2')->nullable();
			$table->string('image_path2')->nullable();
			$table->integer('qr_flg')->nullable()->default(0);
			$table->dateTime('udate')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tbl_receipt');
	}

}
