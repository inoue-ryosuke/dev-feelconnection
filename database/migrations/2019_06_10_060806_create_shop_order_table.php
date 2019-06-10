<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShopOrderTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_order', function(Blueprint $table)
		{
			$table->integer('soid', true);
			$table->char('credit_orderid', 27)->default(0);
			$table->integer('flg')->default(1);
			$table->integer('tid');
			$table->dateTime('hacchubi');
			$table->text('order_list');
			$table->string('menu', 64);
			$table->string('price', 64);
			$table->string('lot', 64);
			$table->string('coupon_code', 64);
			$table->integer('sum');
			$table->integer('sogokei');
			$table->integer('shiharai');
			$table->integer('campaign_flg')->default(0);
			$table->integer('cid');
			$table->string('name', 64);
			$table->string('kana', 64);
			$table->string('pcode', 20);
			$table->text('address');
			$table->string('tel', 64);
			$table->text('email');
			$table->dateTime('setdate');
			$table->dateTime('hassoubi');
			$table->text('ship_no');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('shop_order');
	}

}
