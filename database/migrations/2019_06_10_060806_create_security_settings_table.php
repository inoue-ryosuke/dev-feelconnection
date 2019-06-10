<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSecuritySettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('security_settings', function(Blueprint $table)
		{
			$table->integer('mypage_login_trial_count')->default(3);
			$table->integer('mypage_password_valid_days')->default(90);
			$table->integer('management_login_trial_count')->default(3);
			$table->integer('management_password_valid_days')->default(90);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('security_settings');
	}

}
