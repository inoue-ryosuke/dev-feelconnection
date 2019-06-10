<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTenpoPerfTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tenpo_perf', function(Blueprint $table)
		{
			$table->bigInteger('pid', true);
			$table->integer('tenpo_id')->default(0);
			$table->date('perf_date')->nullable();
			$table->integer('tenki_day')->nullable();
			$table->integer('tenki_night')->nullable();
			$table->integer('sales_total')->nullable();
			$table->integer('sales_buppan')->nullable();
			$table->integer('sales_sejutsu')->nullable();
			$table->bigInteger('sales_other')->nullable();
			$table->bigInteger('kessai_cash')->nullable();
			$table->bigInteger('kessai_card')->nullable();
			$table->bigInteger('kessai_vir')->nullable();
			$table->bigInteger('other_kessai')->nullable();
			$table->integer('cash_cnt');
			$table->integer('card_cnt');
			$table->integer('charge_cnt');
			$table->integer('other_cnt');
			$table->integer('charge')->nullable()->default(0);
			$table->integer('lcount')->nullable();
			$table->integer('newcomer')->nullable();
			$table->integer('repeater')->nullable();
			$table->bigInteger('real_cash')->nullable();
			$table->bigInteger('cchange')->nullable()->default(0);
			$table->integer('unpay');
			$table->bigInteger('cash_overshort')->nullable();
			$table->text('bikou')->nullable();
			$table->integer('cash_type_10000')->nullable()->default(0);
			$table->integer('cash_type_5000')->nullable()->default(0);
			$table->integer('cash_type_2000')->nullable()->default(0);
			$table->integer('cash_type_1000')->nullable()->default(0);
			$table->integer('cash_type_500')->nullable()->default(0);
			$table->integer('cash_type_100')->nullable()->default(0);
			$table->integer('cash_type_50')->nullable()->default(0);
			$table->integer('cash_type_10')->nullable()->default(0);
			$table->integer('cash_type_5')->nullable()->default(0);
			$table->integer('cash_type_1')->nullable()->default(0);
			$table->bigInteger('carry')->nullable();
			$table->integer('flg')->default(0);
			$table->integer('del_times')->nullable()->default(0);
			$table->integer('uid')->nullable();
			$table->date('nreg_date')->nullable();
			$table->time('reg_time')->nullable();
			$table->integer('print_cnt');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tenpo_perf');
	}

}
