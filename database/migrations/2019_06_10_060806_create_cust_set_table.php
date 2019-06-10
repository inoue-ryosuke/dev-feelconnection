<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustSetTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cust_set', function(Blueprint $table)
		{
			$table->integer('felica')->default(1);
			$table->integer('nid')->default(1);
			$table->integer('bank')->default(1);
			$table->integer('jid')->default(1);
			$table->integer('ck1')->default(1);
			$table->integer('ck2')->default(1);
			$table->integer('ck4')->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cust_set');
	}

}
