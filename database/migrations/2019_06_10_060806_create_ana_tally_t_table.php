<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAnaTallyTTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ana_tally_t', function(Blueprint $table)
		{
			$table->bigInteger('inc', true);
			$table->integer('date_ym')->default(0);
			$table->integer('tenpo_id')->default(0);
			$table->integer('t_busi_day')->default(0);
			$table->bigInteger('t_lesson_cnt')->default(0);
			$table->bigInteger('t_capa_cnt')->default(0);
			$table->bigInteger('t_regid_cnt')->default(0);
			$table->text('t_study_ary')->nullable();
			$table->bigInteger('t_new_study')->default(0);
			$table->bigInteger('t_cust_all')->default(0);
			$table->bigInteger('t_cust_member')->default(0);
			$table->integer('t_cust_web')->default(0);
			$table->integer('t_cust_cmail')->default(0);
			$table->integer('t_cust_pcmail')->default(0);
			$table->text('t_cust2_ary')->nullable();
			$table->text('t_rai_ary')->nullable();
			$table->bigInteger('t_new_all')->default(0);
			$table->text('t_moti_ary')->nullable();
			$table->bigInteger('t_repeat_all')->default(0);
			$table->bigInteger('t_repeat_new')->default(0);
			$table->bigInteger('t_repeat_rr')->default(0);
			$table->text('t_repeat_ary')->nullable();
			$table->bigInteger('t_admission')->default(0);
			$table->bigInteger('t_today_admission')->default(0);
			$table->text('t_recess_ary')->nullable();
			$table->text('t_return_ary')->nullable();
			$table->text('t_cancellation_ary')->nullable();
			$table->text('t_change_store_in_ary')->nullable();
			$table->text('t_change_store_out_ary')->nullable();
			$table->text('t_1mau_ary')->nullable();
			$table->text('t_2mau_ary')->nullable();
			$table->bigInteger('t_sales')->default(0);
			$table->bigInteger('t_account')->default(0);
			$table->text('t_lesson_ary')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ana_tally_t');
	}

}
