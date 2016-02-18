<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class EmailController extends Controller
{
	// send email
	public static function send($from, $to, $subject, $data, $module = null) {
		$email_template_map = [
			'User' => [
				'from' => 'akhileshdarjee@gmail.com',
				'template' => 'emails.sign_up'
			],
		];

		$cc = '';

		if (!$from && $module) {
			$from = $email_template_map[$module]['from'];
		}

		if (!$subject && $module) {
			if ($module == "User") {
				$subject = "Sign Up successful";
			}
		}

		if ($module) {
			$template = $email_template_map[$module]['template'];

			if ($module == "User") {
				$to = [$to, 'akhi_192@yahoo.com'];
			}
		}

		$mail_config = (object) array(
			'from' => $from ? $from : "akhileshdarjee@gmail.com",
			'to' => $to,
			'cc' => 'akhi_192@yahoo.com',
			'subject' => $subject
		);

		Mail::send($template, array('data' => $data), function ($message) use ($mail_config) {
			$message->from($mail_config->from, "Basecamp");
			$message->to($mail_config->to);
			$message->cc($mail_config->cc);
			$message->subject($mail_config->subject);
		});
	}
}