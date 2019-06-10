<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMovMasterTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mov_master', function(Blueprint $table)
		{
			$table->integer('type')->nullable()->default(1);
			$table->integer('teacher')->nullable()->default(1);
			$table->integer('mail_regist')->nullable()->default(1);
			$table->integer('membership')->nullable()->default(1);
			$table->integer('term')->nullable()->default(1);
			$table->integer('regist')->nullable()->default(1);
			$table->text('admin_mail');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('mov_master');
	}

}
