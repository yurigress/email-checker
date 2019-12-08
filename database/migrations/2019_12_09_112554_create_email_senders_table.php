<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailSendersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('email_senders', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('personal', 200);
			$table->string('mail', 200);
			$table->string('host', 200);
			$table->unsignedBigInteger('id_read_email');
			$table->timestamps();
			
			$table->foreign('id_read_email')->on('read_emails')->references('id');
		});
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('email_senders', function (Blueprint $table) {
			$table->dropForeign('email_senders_id_read_email_foreign');
		});
		
		Schema::dropIfExists('email_senders');
	}
}
