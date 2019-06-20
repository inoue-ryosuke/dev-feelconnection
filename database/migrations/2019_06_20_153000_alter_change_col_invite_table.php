<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterChangeColInviteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// php artisan migrate 時に動作する処理
		Schema::table('invite', function(Blueprint $table)
		{
			$table->integer('lid')->nullable()->change();

		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// php artisan migrate:rollback 等の戻り処理時に動作する処理
        Schema::table('invite', function(Blueprint $table)
        {
            $table->integer('lid')->change();

        });
	}

}
