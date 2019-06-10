<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProdTenpoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('prod_tenpo', function(Blueprint $table)
		{
			$table->integer('dpid')->default(0);
			$table->integer('dtid')->default(0);
			$table->char('dflg', 1)->nullable()->default('N');
			$table->integer('dseq')->nullable()->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('prod_tenpo');
	}

}
