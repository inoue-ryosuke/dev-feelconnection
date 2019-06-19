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
		Schema::create('cust_memtype__c', function(Blueprint $table)
		{
			$table->integer('mid__c', true);
			$table->text('type_name__c');
			$table->text('status__c');
			$table->char('flg__c', 1)->default('Y');
			$table->text('iname__c')->nullable();
			$table->text('itxtcol__c')->nullable();
			$table->text('ibgcol__c')->nullable();
			$table->integer('rescnt_mem__c');
			$table->integer('resspan__c');
			$table->smallInteger('attend_count__c')->default(0);
			$table->integer('seq__c')->default(0);
			$table->integer('mem_prod__c')->default(0);
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
