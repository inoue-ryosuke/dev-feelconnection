<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketClassTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ticket_class', function(Blueprint $table)
		{
			$table->integer('tid', true);
			$table->text('name')->nullable();
			$table->string('class', 10)->nullable();
			$table->string('flg', 10)->default('Y');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ticket_class');
	}

}
