<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdKassaiMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_kassai_master', function (Blueprint $table) {
            $table->integer('prod_kessai_id', true);
            $table->integer('prod_id');
            $table->integer('kessai_id');
            $table->integer('type');
            $table->integer('flg')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prod_kassai_master');
    }
}
