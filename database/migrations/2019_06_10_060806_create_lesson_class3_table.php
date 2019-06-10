<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLessonClass3Table extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lesson_class3', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->char('flg', 1)->default('Y');
			$table->string('name', 10);
			$table->integer('seq');
			$table->dateTime('created_at');
			$table->dateTime('modified_at')->nullable();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('lesson_class3');
	}

}
