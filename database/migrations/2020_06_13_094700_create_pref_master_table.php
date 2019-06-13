<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePrefMasterTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// php artisan migrate 時に動作する処理
		Schema::create('pref_master', function(Blueprint $table)
		{
			$table->smallIncrements('pid')->comment('都道府県コード'); // unsigned smallint auto increment
			$table->string('name', 16)->nullable(false)->comment('都道府県名');
			$table->char('country', 3)->nullable(false)->default('JPN')->comment('国コード（英字3文字）');
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
		Schema::drop('pref_master');
	}

}
