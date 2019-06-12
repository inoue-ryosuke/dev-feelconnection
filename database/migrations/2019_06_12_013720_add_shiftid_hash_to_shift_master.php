<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShiftidHashToShiftMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shift_master', function (Blueprint $table) {
            $table->char('shiftid_hash', 128)->nullable();
            $table->index('shiftid_hash', 'shiftid_hash');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shift_master', function (Blueprint $table) {
            $table->dropIndex('shiftid_hash');
            $table->dropColumn('shiftid_hash');
        });
    }
}
