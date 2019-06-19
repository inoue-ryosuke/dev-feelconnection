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
        Schema::table('user_master_hist', function(Blueprint $table)
        {
            $table->dateTime("end")->comment('店舗所属終了日')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_master_hist', function(Blueprint $table)
        {
            $table->dateTime("end")->comment('店舗所属終了日')->change();
        });
    }
}
