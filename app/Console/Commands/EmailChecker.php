<?php

namespace App\Console\Commands;

use App\EmailAccount;
use Illuminate\Console\Command;

class EmailChecker extends Command
{
	protected $signature = 'email-checker:verify';
	
	protected $description = 'Verifica as contas de e-mails';
	
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$this->output->title('Verificador de e-mails');
		try {
			$this->output->text('Iniciado o processo de verificação de novos e-mails');
			EmailAccount::emailChecker();
			$this->output->success('Finalizado com sucesso!');
		} catch (\Exception $exception) {
			$this->output->error($exception->getMessage());
		}
	}
}
