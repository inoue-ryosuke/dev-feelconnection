<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTenpoMasterTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tenpo_master', function(Blueprint $table)
		{
			$table->integer('tid', true);
			$table->string('tenpo_name', 64);
			$table->text('iname')->nullable();
			$table->text('itxtcol')->nullable();
			$table->text('ibgcol')->nullable();
			$table->string('tenpo_code', 10)->nullable();
			$table->integer('tenpo_area_id')->default(0);
			$table->integer('honbu_prev')->nullable()->default(0);
			$table->integer('tenpo_kubun')->nullable()->default(1);
			$table->string('zip', 10)->nullable();
			$table->text('tenpo_addr')->nullable();
			$table->text('tenpo_tel')->nullable();
			$table->text('res_tel')->nullable();
			$table->string('tenpo_mail', 128)->nullable();
			$table->text('tenpo_url')->nullable();
			$table->integer('point')->nullable()->default(0);
			$table->text('header')->nullable();
			$table->integer('max_del_times')->default(5);
			$table->integer('del_times')->default(0);
			$table->date('del_count_date')->nullable();
			$table->integer('rescount')->nullable()->default(0);
			$table->integer('rescount_day')->nullable()->default(0);
			$table->integer('nolimit')->default(0);
			$table->integer('flg')->default(0);
			$table->integer('seq')->default(0);
			$table->integer('m_price')->nullable()->default(0);
			$table->integer('monthly_avail_all');
			$table->text('monthly_avail_tenpo');
			$table->date('monthly_free_exp');
			$table->text('monthly_fname');
			$table->text('mtenpo_explain');
			$table->text('tenpo_memtype');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tenpo_master');
	}

}
