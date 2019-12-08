<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailAccount extends Model
{
	public function getValidateCertAttribute($value) {
		return (bool)$value;
	}
	
	public static function emailChecker() {
		$accounts = self::all();
		$accounts->each(function (\App\EmailAccount $account) {
			$checker = new \App\EmailChecker();
			$checker->verify($account);
		});
	}
}
