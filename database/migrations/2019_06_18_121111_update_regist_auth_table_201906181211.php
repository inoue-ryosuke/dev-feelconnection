<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRegistAuthTable201906181211 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('regist_auth', function (Blueprint $table) {
            //
            $table->integer('processing_category')->comment('処理分類');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('regist_auth', function (Blueprint $table) {
            //
            $table->dropColumn('processing_category');
        });
    }
}
