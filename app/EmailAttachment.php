<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Webklex\IMAP\Attachment;
use Webklex\IMAP\Support\AttachmentCollection;

class EmailAttachment extends Model
{
	public $fillable = ['id_read_email', 'extension', 'content', 'content_type', 'mime_type', 'name', 'code'];
	
	public static function saveAttachmentsFromArray(int $idEmail, AttachmentCollection $attachments): array
	{
		if ($attachments->count() === 0) {
			throw new \Exception('Não possui anexos');
		}
		
		$output = [];
		$attachments->each(function (Attachment $attachment) use ($idEmail, &$output) {
			DB::transaction(function () use ($attachment, $idEmail, &$output) {
				array_push($output, self::create([
					'id_read_email' => $idEmail,
					'extension' => $attachment->getExtension(),
					'content_type' => $attachment->getContentType(),
					'name' => $attachment->getName(),
					'code' => $attachment->getId(),
				]));
				
				self::createFile($attachment);
			});
		});
		return $output;
	}
	
	public static function createFile(Attachment $attachment)
	{
		Storage::append("{$attachment->getId()}.{$attachment->getExtension()}", $attachment->getContent());
	}
	
	public function readEmail()
	{
		$this->belongsTo(ReadEmail::class, 'id', 'id_read_email');
	}
}
