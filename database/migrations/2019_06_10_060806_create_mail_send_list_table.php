<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMailSendListTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mail_send_list', function(Blueprint $table)
		{
			$table->integer('msid', true);
			$table->integer('mtid')->default(0);
			$table->text('tenpo_id');
			$table->text('m_type');
			$table->text('condition');
			$table->bigInteger('count')->default(0);
			$table->text('cid');
			$table->date('send_date')->nullable();
			$table->time('send_time')->default('00:00:00');
			$table->text('flg');
			$table->integer('send_flg')->default(0);
			$table->integer('return_flg')->default(0);
			$table->text('nonarrival')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('mail_send_list');
	}

}
