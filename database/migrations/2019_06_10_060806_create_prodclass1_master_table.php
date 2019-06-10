<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProdclass1MasterTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('prodclass1_master', function(Blueprint $table)
		{
			$table->bigInteger('pclass1_id', true);
			$table->integer('class_id')->nullable();
			$table->string('pclass1_name', 64);
			$table->integer('seq')->default(0);
			$table->string('flg', 4)->default('Y');
			$table->integer('net');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('prodclass1_master');
	}

}
