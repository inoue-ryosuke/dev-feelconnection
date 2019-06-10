<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketMasterTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ticket_master', function(Blueprint $table)
		{
			$table->bigInteger('tkid', true);
			$table->integer('iid')->default(0);
			$table->integer('cid')->default(0);
			$table->integer('menuid')->default(0);
			$table->string('dflg', 4)->default('Y');
			$table->integer('lot')->default(0);
			$table->date('expire')->nullable();
			$table->date('expire_tk_recover');
			$table->text('shiftid')->nullable();
			$table->string('flg', 4)->default('Y');
			$table->integer('another');
			$table->integer('reg_flg')->default(0);
			$table->integer('at_flg');
			$table->integer('tcount')->default(0);
			$table->integer('tcount_max');
			$table->integer('tik_status')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ticket_master');
	}

}
