<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateColumnCustTenpoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// php artisan migrate 時に動作する処理
		Schema::create('cust_tenpo__c', function(Blueprint $table)
		{
			$table->bigIncrements('ctenpo_id__c')->comment('ID'); // unsigned bigint auto increment
			$table->integer('cid')->length(11)->nullable(false)->comment('会員ID');
			$table->integer('tenpo_id')->length(11)->nullable(false)->comment('店舗ID');
			$table->integer('flg')->length(11)->nullable(false)->default(1)->comment('有効無効フラグ');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// php artisan migrate:rollback 等の戻り処理時に動作する処理
		Schema::drop('cust_tenpo');
	}

}
