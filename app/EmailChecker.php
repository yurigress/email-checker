<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Webklex\IMAP\Attachment;
use Webklex\IMAP\Client;
use Webklex\IMAP\Folder;
use Webklex\IMAP\Message;
use Webklex\IMAP\Support\MessageCollection;

class EmailChecker extends Model
{
	/**
	 * Verifica as caixas de e-mails das contas cadastradas
	 * @param EmailAccount $emailAccount
	 * @throws \Webklex\IMAP\Exceptions\ConnectionFailedException
	 * @throws \Webklex\IMAP\Exceptions\GetMessagesFailedException
	 * @throws \Webklex\IMAP\Exceptions\InvalidWhereQueryCriteriaException
	 * @throws \Webklex\IMAP\Exceptions\MaskNotFoundException
	 */
	public function verify(EmailAccount $emailAccount)
	{
		$client = new Client($emailAccount->toArray());
		
		if (!$client->isConnected()) {
			$client->connect();
		}
		
		/** @var Folder $folder */
		$folder = $client->getFolder($emailAccount->folder); // Obtem a caixa de entrada
		
		/** @var MessageCollection $messages */
		$messages = $folder->getMessages();
		$messages->each(function (Message $message) {
			if ($message->hasAttachments()) { // Somente realiza a leitura de e-mails com anexo
				try {
					$readEmail = ReadEmail::saveFromMessage($message);
					if ($readEmail) {
						$senders = EmailSender::saveSendersFromArray($readEmail->id, $message->getFrom());
						$receivers = EmailReceiver::saveReceiversFromArray($readEmail->id, $message->getTo());
						$attachments = EmailAttachment::saveAttachmentsFromArray($readEmail->id, $message->getAttachments());
						
						self::sendInformations([
							'message' => $readEmail,
							'senders' => $senders,
							'receivers' => $receivers,
							'attachments' => $attachments,
						]);
						
						$message->moveToFolder('IMPORTED');
					}
				} catch (\Exception $exception) {
					throw new \Exception($exception->getMessage());
				}
			}
		});
	}
	
	/**
	 * Realiza o envio das informações do e-mail para API
	 * @param array $informations
	 */
	public function sendInformations(array $informations) {
		$guzz = new \GuzzleHttp\Client();
		$request = $guzz->post('https://my-json-server.typicode.com/typicode/demo/comments', ['form_params' => $informations]);
		$response = $request->getBody();
	}
	
	
}
