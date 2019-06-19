<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInviteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// php artisan migrate 時に動作する処理
		Schema::create('invite', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->integer('cid')->comment('紹介会員マスタID。cust_master.id');
            $table->integer('lid')->comment('体験レッスンID。lesson_master.lid');
            $table->string('invite_code', 64)->unique();
            $table->timestamps();
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
		Schema::drop('invite');
	}

}
