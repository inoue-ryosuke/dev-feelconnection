<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustMemtypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cust_memtype', function(Blueprint $table)
		{
			$table->integer('mid', true);
			$table->text('type_name');
			$table->text('status');
			$table->char('flg', 1)->default('Y');
			$table->text('iname')->nullable();
			$table->text('itxtcol')->nullable();
			$table->text('ibgcol')->nullable();
			$table->integer('rescnt_mem');
			$table->integer('resspan');
			$table->smallInteger('attend_count')->default(0);
			$table->integer('seq')->default(0);
			$table->integer('mem_prod')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cust_memtype');
	}

}
