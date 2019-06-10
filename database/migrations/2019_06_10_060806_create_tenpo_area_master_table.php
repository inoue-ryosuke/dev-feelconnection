<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTenpoAreaMasterTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tenpo_area_master', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->char('flg', 1)->default('Y');
			$table->string('name', 10);
			$table->integer('seq');
			$table->text('iname')->nullable();
			$table->text('itxtcol')->nullable();
			$table->text('ibgcol')->nullable();
			$table->string('description', 20)->nullable();
			$table->dateTime('created_at');
			$table->dateTime('modified_at')->nullable();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tenpo_area_master');
	}

}
