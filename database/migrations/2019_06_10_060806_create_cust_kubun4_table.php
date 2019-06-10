<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustKubun4Table extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cust_kubun4', function(Blueprint $table)
		{
			$table->integer('ck4_id', true);
			$table->string('ck4_name', 64)->nullable();
			$table->text('iname');
			$table->text('itxtcol');
			$table->text('ibgcol');
			$table->integer('seq')->default(0);
			$table->char('flg', 1)->default('Y');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cust_kubun4');
	}

}
