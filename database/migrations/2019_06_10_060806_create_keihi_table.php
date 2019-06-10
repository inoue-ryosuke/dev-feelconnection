<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKeihiTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('keihi', function(Blueprint $table)
		{
			$table->bigInteger('poid', true);
			$table->date('pdate')->nullable();
			$table->time('ptime');
			$table->integer('tenpo_id')->nullable();
			$table->string('flg', 2)->default('Y');
			$table->text('shop')->nullable();
			$table->text('item')->nullable();
			$table->text('purpose')->nullable();
			$table->bigInteger('keihi')->nullable();
			$table->bigInteger('depo')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('keihi');
	}

}
