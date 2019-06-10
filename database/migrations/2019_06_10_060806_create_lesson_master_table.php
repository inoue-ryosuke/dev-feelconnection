<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLessonMasterTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lesson_master', function(Blueprint $table)
		{
			$table->integer('lid', true);
			$table->bigInteger('seq')->nullable();
			$table->string('lessonname', 128);
			$table->integer('lesson_class1')->default(0);
			$table->integer('lesson_class2')->default(0);
			$table->integer('lesson_class3')->default(0);
			$table->integer('lessontime')->default(0);
			$table->integer('ticket_class')->default(0);
			$table->integer('res_count')->default(1);
			$table->integer('reserve_limit')->default(0);
			$table->integer('cancel_limit')->default(0);
			$table->integer('flg')->default(1);
			$table->integer('mess_flg');
			$table->text('iname')->nullable();
			$table->text('itxtcol')->nullable();
			$table->text('ibgcol')->nullable();
			$table->text('memtype');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('lesson_master');
	}

}
