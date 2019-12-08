<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailAttachmentsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('email_attachments', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('id_read_email');
			$table->string('extension');
			$table->string('content_type');
			$table->string('name');
			$table->string('code');
			$table->timestamps();
			
			$table->foreign('id_read_email')->references('id')->on('read_emails');
		});
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('email_attachments', function (Blueprint $table) {
			$table->dropForeign('email_attachments_id_read_email_foreign');
		});
		
		Schema::dropIfExists('email_attachments');
	}
}
