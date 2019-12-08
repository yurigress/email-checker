<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmailReceiver extends Model
{
	protected $fillable = ['personal', 'mail', 'host', 'id_read_email'];
	
	/**
	 * @param int $idEmail
	 * @param array $receivers
	 * @return array|null
	 * @throws \Exception
	 */
	public static function saveReceiversFromArray(int $idEmail, array $receivers):? array
	{
		if (count($receivers) === 0) {
			throw new \Exception();
		}
		
		$output = [];
		DB::transaction(function () use ($receivers, $idEmail, &$output) {
			foreach ($receivers as $receiver) {
				$receiver->id_read_email = $idEmail;
				array_push($output, self::create((array)$receiver));
			}
		});
		return $output;
	}
	
	public function readEmail()
	{
		$this->belongsTo(ReadEmail::class, 'id', 'id_read_email');
	}
}
