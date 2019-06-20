<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCancelTkidToOrderLesson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_lesson', function (Blueprint $table) {
            $table->integer('cancel_tkid')->nullable()->comment('予約キャンセルして使用回数を戻したチケットID');
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
            $table->dropColumn('cancel_tkid');
        });
    }
}
