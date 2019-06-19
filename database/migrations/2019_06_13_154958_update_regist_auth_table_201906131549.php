<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRegistAuthTable201906131549 extends Migration
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
            $table->dateTime('expire')->comment('有効期限（日時）');
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
            $table->dropColumn('expire');
        });
    }
}
