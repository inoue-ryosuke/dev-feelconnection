<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAnaTallyTlTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ana_tally_tl', function(Blueprint $table)
		{
			$table->bigInteger('inc', true);
			$table->integer('date_ym')->default(0);
			$table->integer('tenpo_id')->default(0);
			$table->integer('teacher')->default(0);
			$table->integer('lm_id')->default(0);
			$table->bigInteger('t_hold_cnt')->default(0);
			$table->bigInteger('t_capa_cnt')->default(0);
			$table->bigInteger('t_regid_cnt')->default(0);
			$table->bigInteger('t_canid_cnt')->default(0);
			$table->bigInteger('t_new_cnt')->default(0);
			$table->bigInteger('t_cust_nomember')->default(0);
			$table->bigInteger('t_n_cust_chn')->default(0);
			$table->text('t_n_cust_chn_ary')->nullable();
			$table->bigInteger('t_new_rep')->default(0);
			$table->text('t_new_rep_ary')->nullable();
			$table->bigInteger('t_next_res')->default(0);
			$table->text('t_next_res_ary')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ana_tally_tl');
	}

}
