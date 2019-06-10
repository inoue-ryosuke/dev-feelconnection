<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserMasterTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_master', function(Blueprint $table)
		{
			$table->integer('uid', true);
			$table->string('code', 20)->nullable();
			$table->bigInteger('seq')->nullable();
			$table->string('login_id', 13)->nullable();
			$table->text('login_pass')->nullable();
			$table->integer('prev_id')->default(1);
			$table->integer('tenpo_id')->default(0);
			$table->string('alive_flg', 2)->default('Y');
			$table->string('user_name', 64)->nullable();
			$table->integer('teacher')->default(1);
			$table->string('retire_date', 16)->nullable();
			$table->integer('mflg')->nullable();
			$table->integer('free_flg')->default(0);
			$table->text('effective_store');
			$table->text('salt');
			$table->dateTime('password_change_datetime');
			$table->integer('login_trial_count')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_master');
	}

}
