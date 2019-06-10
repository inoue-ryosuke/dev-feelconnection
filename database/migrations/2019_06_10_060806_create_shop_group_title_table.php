<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShopGroupTitleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_group_title', function(Blueprint $table)
		{
			$table->integer('sgtid', true);
			$table->string('group_title', 64);
			$table->text('target_tenpo');
			$table->integer('flg');
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
		Schema::drop('shop_group_title');
	}

}
