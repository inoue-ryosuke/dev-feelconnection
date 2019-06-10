<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShopOrderTmpTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_order_tmp', function(Blueprint $table)
		{
			$table->integer('tmpid', true);
			$table->integer('tmp_flg');
			$table->integer('tmp_cid');
			$table->integer('tmp_tenpo');
			$table->integer('tmp_uri_tenpo');
			$table->date('tmp_start_date');
			$table->char('tmp_card_status', 32);
			$table->char('tmp_errcode', 3);
			$table->text('tmpval');
			$table->string('tmporderid', 64);
			$table->dateTime('tmpdate');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('shop_order_tmp');
	}

}
