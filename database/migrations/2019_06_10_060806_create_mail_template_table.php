<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMailTemplateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mail_template', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('mail_type')->default(0);
			$table->text('mail_theme');
			$table->text('mail_title');
			$table->text('mail_content');
			$table->date('reg_date')->nullable();
			$table->string('flg', 32)->default('Y');
			$table->integer('tid')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('mail_template');
	}

}
