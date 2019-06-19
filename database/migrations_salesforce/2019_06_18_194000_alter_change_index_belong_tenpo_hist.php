<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterChangeIndexBelongTenpoHist extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('belong_tenpo_hist__c', function(Blueprint $table)
		{
            $table->renameIndex('user_master_hist__c_pkey', 'belong_tenpo_hist_pkey');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('belong_tenpo_hist__c', function(Blueprint $table)
        {
            $table->renameIndex('belong_tenpo_hist_pkey', 'user_master_hist__c_pkey');
        });
	}

}
