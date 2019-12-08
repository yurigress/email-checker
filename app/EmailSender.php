<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmailSender extends Model
{
	protected $fillable = ['personal', 'mail', 'host', 'id_read_email'];
	
	/**
	 * Salva as informações dos remetentes do e-mail
	 * @param int $idEmail
	 * @param array $senders
	 * @return array|null
	 * @throws \Exception
	 */
	public static function saveSendersFromArray(int $idEmail, array $senders):? array
	{
		if (count($senders) === 0) {
			throw new \Exception('Não possui remetentes');
		}
		
		$output = [];
		DB::transaction(function () use ($senders, $idEmail, &$output) {
			foreach ($senders as $sender) {
				$sender->id_read_email = $idEmail;
				array_push($output, self::create((array)$sender));
			}
		});
		
		return $output;
	}
	
	public function readEmail()
	{
		$this->belongsTo(ReadEmail::class, 'id', 'id_read_email');
	}
}
