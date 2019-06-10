<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShopGoodsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_goods', function(Blueprint $table)
		{
			$table->integer('sgid', true);
			$table->integer('flg')->default(1);
			$table->integer('prd_group')->default(0);
			$table->integer('kind');
			$table->integer('g_name');
			$table->integer('weight');
			$table->string('fname1', 64);
			$table->integer('monthly_caution')->default(0);
			$table->string('fname2', 64);
			$table->integer('price');
			$table->text('expl');
			$table->integer('laundering');
			$table->integer('campaign');
			$table->integer('campaign_30day')->default(0);
			$table->integer('shozoku');
			$table->integer('forcash');
			$table->integer('trial')->default(0);
			$table->integer('entry')->default(0);
			$table->text('memtype');
			$table->text('tenpotype');
			$table->integer('monthly_kiyaku');
			$table->text('prod_type');
			$table->integer('buy_confirm')->default(0);
			$table->integer('adweb');
			$table->dateTime('view_date_start');
			$table->dateTime('view_date_end');
			$table->string('adweb_code', 64);
			$table->integer('coupon_kind')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('shop_goods');
	}

}
