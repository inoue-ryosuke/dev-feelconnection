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
        Schema::drop('user_master_hist');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('user_master_hist', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('uid')->comment('スタッフID。user_master.uid');
            $table->integer('tid')->comment('店舗ID。tenpo_master.tid');
            $table->smallInteger('flg')->comment('0: 無効 / 1:有効');
            $table->dateTime('start')->comment('店舗所属開始日');
            $table->dateTime('end')->comment('店舗所属終了日');

        });
    }
}
