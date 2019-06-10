<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRegistAuthTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('regist_auth', function(Blueprint $table)
		{
			$table->bigInteger('aid', true);
			$table->string('maddress', 128)->nullable();
			$table->string('ahash', 64)->nullable();
			$table->date('reg_date')->nullable();
			$table->bigInteger('cid')->nullable();
			$table->integer('type');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('regist_auth');
	}

}
