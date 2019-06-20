<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTenpoMaster20190620155500 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenpo_master', function (Blueprint $table) {
            $table->text('address')->nullable(false)->default('')->comment('店舗住所');
            $table->text('station')->nullable(false)->default('')->comment('最寄り駅情報');
            $table->string('lat',30)->nullable()->comment('店舗位置情報：緯度');
            $table->string('lng',30)->nullable()->comment('店舗位置情報：経度');
            $table->text('timetable')->nullable()->comment('営業時間情報');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_master', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('station');
            $table->dropColumn('lat',30);
            $table->dropColumn('lng',30);
            $table->dropColumn('timetable');
        });
    }
}
