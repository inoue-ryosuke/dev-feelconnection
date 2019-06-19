<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropUserMasterHistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('user_master_hist__c');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('user_master_hist__c', function (Blueprint $table) {
            $table->bigIncrements('id__c');
            $table->integer('uid__c')->comment('スタッフID。user_master.uid');
            $table->integer('tid__c')->comment('店舗ID。tenpo_master.tid');
            $table->smallInteger('flg__c')->comment('0: 無効 / 1:有効');
            $table->dateTime('start__c')->comment('店舗所属開始日');
            $table->dateTime('end__c')->comment('店舗所属終了日');

        });
    }
}
