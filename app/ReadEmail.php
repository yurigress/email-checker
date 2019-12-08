<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Webklex\IMAP\Message;

class ReadEmail extends Model
{
	protected $fillable = ['body', 'header', 'message_id'];
	
	/**
	 * Salva as informações do email
	 * @param Message $message
	 * @return ReadEmail|null
	 */
	public static function saveFromMessage(Message $message): ?ReadEmail
	{
		$readEmail = null;
		DB::transaction(function () use ($message, &$readEmail) {
			/** @var ReadEmail $readEmail */
			$readEmail = ReadEmail::create([
				'body' => $message->getTextBody(),
				'header' => $message->getHeader(),
				'message_id' => $message->getMessageId(),
			]);
		});
		
		return $readEmail;
	}
	
	public function senders()
	{
		return $this->hasMany(EmailSender::class, 'id_read_email', 'id');
	}
	
	public function receivers()
	{
		return $this->hasMany(EmailReceiver::class, 'id_read_email', 'id');
	}
	
	public function attachments()
	{
		return $this->hasMany(EmailAttachment::class, 'id_read_email', 'id');
	}
}
