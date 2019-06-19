<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterChangeUserMasterHistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_master_hist__c', function(Blueprint $table)
        {
            $table->dateTime("end__c")->comment('店舗所属終了日')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_master_hist__c', function(Blueprint $table)
        {
            $table->dateTime("end__c")->comment('店舗所属終了日')->change();
        });
    }
}
