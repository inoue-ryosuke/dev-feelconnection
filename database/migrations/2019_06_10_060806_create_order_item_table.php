<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderItemTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_item', function(Blueprint $table)
		{
			$table->integer('iid', true);
			$table->integer('oid')->default(0);
			$table->integer('menu_id')->nullable();
			$table->string('charge_flg', 1)->default('N');
			$table->integer('big_prodclass_id')->default(0);
			$table->integer('uriage_kubun')->nullable();
			$table->integer('tanka')->nullable();
			$table->integer('tanka2')->nullable();
			$table->integer('suuryo')->nullable();
			$table->integer('item_total')->nullable();
			$table->string('iflg', 8)->nullable()->default('Y');
			$table->integer('net_flg');
			$table->integer('at_flg');
			$table->integer('entry_flg')->default(0);
			$table->string('undeliver', 1)->nullable()->default('0');
			$table->integer('point_ex')->nullable()->default(0);
			$table->integer('disc_type');
			$table->date('deliver_date')->nullable();
			$table->integer('del_uid')->nullable();
			$table->dateTime('del_date')->nullable();
			$table->dateTime('regdate')->nullable();
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
		Schema::drop('order_item');
	}

}
