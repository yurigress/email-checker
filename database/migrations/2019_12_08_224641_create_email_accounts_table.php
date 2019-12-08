<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailAccountsTable extends Migration
{
	public function up()
	{
		Schema::create('email_accounts', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('host', 180);
			$table->smallInteger('port');
			$table->enum('protocol', ['imap', 'pop3'])->default('imap');
			$table->enum('encryption', ['false', 'ssl', 'tls', 'starttls', 'notls'])->default('false');
			$table->boolean('validate_cert')->default(false);
			$table->string('username', 150);
			$table->string('password',  50);
			$table->string('folder', 200)->default('INBOX')->comment('Pasta onde será realizada a leitura dos e-mails');
			$table->timestamps();
		});
	}
	
	public function down()
	{
		Schema::dropIfExists('email_accounts');
	}
}
