<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTblMonthlyKiyakuTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tbl_monthly_kiyaku', function(Blueprint $table)
		{
			$table->integer('mkid', true);
			$table->integer('flg')->default(1);
			$table->string('title', 64);
			$table->text('html_path');
			$table->text('kiyaku_path');
			$table->dateTime('reg_date');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tbl_monthly_kiyaku');
	}

}
