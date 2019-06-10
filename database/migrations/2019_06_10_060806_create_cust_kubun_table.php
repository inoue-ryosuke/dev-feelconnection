<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustKubunTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cust_kubun', function(Blueprint $table)
		{
			$table->integer('ckid', true);
			$table->text('kubun_name1')->nullable();
			$table->text('kubun_name2')->nullable();
			$table->text('kubun_name3')->nullable();
			$table->text('kubun_name4')->nullable();
			$table->text('kubun_name5');
			$table->text('kubun_name6');
			$table->text('kubun_name7');
			$table->text('kubun_name8');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cust_kubun');
	}

}
