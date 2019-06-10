<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLessonCommentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lesson_comment', function(Blueprint $table)
		{
			$table->integer('loid');
			$table->integer('checkin')->default(0);
			$table->text('res_comment');
			$table->dateTime('com_date');
			$table->integer('com_uid')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('lesson_comment');
	}

}
