<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTrialFlgToOrderLesson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_lesson', function (Blueprint $table) {
            $table->integer('trial_flg')->default(0)->comment('体験レッスン(初回レッスン)フラグ: 1(体験レッスン) 0(通常レッスン)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_lesson', function (Blueprint $table) {
            $table->dropColumn('trial_flg');
        });
    }
}
