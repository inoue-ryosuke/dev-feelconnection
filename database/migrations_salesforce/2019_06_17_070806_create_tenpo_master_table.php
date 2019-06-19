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
		Schema::create('tenpo_master__c', function(Blueprint $table)
		{
			$table->integer('tid__c', true);
			$table->string('tenpo_name__c', 64);
			$table->text('iname__c')->nullable();
			$table->text('itxtcol__c')->nullable();
			$table->text('ibgcol__c')->nullable();
			$table->string('tenpo_code__c', 10)->nullable();
			$table->integer('tenpo_area_id__c')->default(0);
			$table->integer('honbu_prev__c')->nullable()->default(0);
			$table->integer('tenpo_kubun__c')->nullable()->default(1);
			$table->string('zip__c', 10)->nullable();
			$table->text('tenpo_addr__c')->nullable();
			$table->text('tenpo_tel__c')->nullable();
			$table->text('res_tel__c')->nullable();
			$table->string('tenpo_mail__c', 128)->nullable();
			$table->text('tenpo_url__c')->nullable();
			$table->integer('point__c')->nullable()->default(0);
			$table->text('header__c')->nullable();
			$table->integer('max_del_times__c')->default(5);
			$table->integer('del_times__c')->default(0);
			$table->date('del_count_date__c')->nullable();
			$table->integer('rescount__c')->nullable()->default(0);
			$table->integer('rescount_day__c')->nullable()->default(0);
			$table->integer('nolimit__c')->default(0);
			$table->integer('flg__c')->default(0);
			$table->integer('seq__c')->default(0);
			$table->integer('m_price__c')->nullable()->default(0);
			$table->integer('monthly_avail_all__c');
			$table->text('monthly_avail_tenpo__c');
			$table->date('monthly_free_exp__c');
			$table->text('monthly_fname__c');
			$table->text('mtenpo_explain__c');
			$table->text('tenpo_memtype__c');
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
