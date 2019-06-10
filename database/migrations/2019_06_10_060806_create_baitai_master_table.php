<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBaitaiMasterTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('baitai_master', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('baitai_id', 64);
			$table->string('baitai2_id', 64);
			$table->string('baitai_name', 64);
			$table->integer('flg')->default(0);
			$table->integer('seq')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('baitai_master');
	}

}
