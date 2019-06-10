<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProdMasterTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('prod_master', function(Blueprint $table)
		{
			$table->integer('prod_id', true);
			$table->integer('prodclass_id')->nullable();
			$table->integer('seq')->nullable();
			$table->string('prodname', 128);
			$table->integer('tanka_sale')->nullable();
			$table->integer('tanka_buy')->nullable();
			$table->integer('ticket_count')->nullable();
			$table->integer('res_count')->nullable()->default(0);
			$table->integer('outside_flg')->default(1);
			$table->string('ticket_flg', 8)->nullable()->default('Y');
			$table->string('club_flg', 2)->nullable()->default('N');
			$table->string('shozoku_flg', 2)->default('N');
			$table->string('charge_flg', 4)->nullable()->default('N');
			$table->integer('another_flg');
			$table->integer('ticket_class')->default(0);
			$table->integer('expire')->nullable()->default(0);
			$table->integer('free_month')->default(0);
			$table->date('coupon');
			$table->string('stockflg', 8)->nullable()->default('Y');
			$table->integer('over_short')->default(0);
			$table->integer('proper_quantity')->default(0);
			$table->text('shelf_number');
			$table->string('avail_flg', 2)->nullable()->default('Y');
			$table->integer('point')->nullable()->default(0);
			$table->integer('autocal')->default(0);
			$table->integer('taxclass')->default(2);
			$table->integer('erase_flg')->default(0);
			$table->integer('net_flg');
			$table->integer('enrollment_flg');
			$table->integer('trial');
			$table->text('iname')->nullable();
			$table->text('itxtcol')->nullable();
			$table->text('ibgcol')->nullable();
			$table->integer('tid')->nullable()->default(0);
			$table->string('code', 20);
			$table->integer('add_up')->default(0);
			$table->integer('no_can_treat')->default(0);
			$table->integer('no_can_useup')->default(0);
			$table->integer('cancel_prohibit')->default(0);
			$table->integer('attend_count_flg')->default(0);
			$table->integer('memtype_change')->default(0);
			$table->text('prod_kind')->nullable();
			$table->text('prod_code')->nullable();
			$table->text('memo1')->nullable();
			$table->text('memo2')->nullable();
			$table->text('memo3')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('prod_master');
	}

}
