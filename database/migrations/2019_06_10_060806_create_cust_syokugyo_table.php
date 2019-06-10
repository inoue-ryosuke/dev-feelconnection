<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustSyokugyoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cust_syokugyo', function(Blueprint $table)
		{
			$table->integer('csy_id', true);
			$table->string('csy_name', 64)->nullable();
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
		Schema::drop('cust_syokugyo');
	}

}
