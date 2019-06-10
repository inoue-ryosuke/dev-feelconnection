<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccountAraiTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('account_arai', function(Blueprint $table)
		{
			$table->integer('arid', true);
			$table->dateTime('set_date');
			$table->dateTime('araigae_date');
			$table->integer('set_flg');
			$table->integer('uid');
			$table->integer('arai_cnt');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('account_arai');
	}

}
