<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShiftMasterTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shift_master', function(Blueprint $table)
		{
			$table->integer('shiftid', true);
			$table->integer('shift_tenpoid')->nullable();
			$table->text('flg')->nullable();
			$table->string('wflg', 4)->nullable();
			$table->integer('tlimit')->nullable()->default(0);
			$table->integer('tlimit_cancel')->default(0);
			$table->integer('teacher')->default(0);
			$table->integer('room')->default(0);
			$table->integer('gender');
			$table->text('cstid')->nullable();
			$table->text('regid')->nullable();
			$table->text('canid');
			$table->integer('useup_cnt')->default(0);
			$table->date('shift_date')->nullable();
			$table->time('updatetime')->nullable();
			$table->integer('shift_type')->nullable();
			$table->integer('ls_menu')->nullable();
			$table->integer('shift_capa')->nullable()->default(0);
			$table->integer('taiken_capa');
			$table->integer('taiken_mess');
			$table->integer('taiken_les_flg');
			$table->time('ls_st')->nullable();
			$table->time('ls_et')->nullable();
			$table->integer('cancel')->nullable()->default(0);
			$table->integer('patern');
			$table->integer('wmid');
			$table->integer('cancel_mail');
			$table->dateTime('open_datetime');
			$table->char('reserve_lock', 1)->default('N');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('shift_master');
	}

}
