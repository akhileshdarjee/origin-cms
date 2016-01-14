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
			'Booking' => [
				'from' => 'booking@bookbasecamp.com',
				'template' => 'emails.booking'
			],
			'Feedback' => [
				'from' => 'hello@bookbasecamp.com',
				'template' => 'emails.feedback'
			],
			'Client' => [
				'from' => 'info@bookbasecamp.com',
				'template' => 'emails.sign_up'
			],
			'Cook' => [
				'from' => 'info@bookbasecamp.com',
				'template' => 'emails.sign_up'
			],
			'EnquiryFromContactPage' => [
				'from' => 'info@bookbasecamp.com',
				'template' => 'emails.enquiry'
			]
		];

		$cc = '';

		if (!$from && $module) {
			$from = $email_template_map[$module]['from'];
		}

		if (!$subject && $module) {
			if ($module == "Booking") {
				$subject = "Basecamp Booking";
			}
			elseif ($module == "Feedback") {
				$subject = "Basecamp Feedback";
			}
			else {
				$subject = "Basecamp";
			}
		}

		if ($module) {
			$template = $email_template_map[$module]['template'];
			if ($module == "Booking") {
				$client_id = DB::table('tabGuest')
					->leftJoin('tabClient', 'tabGuest.company', '=', 'tabClient.full_name')
					->where('tabGuest.email_id', $to)
					->pluck('tabClient.email_id');

				$data['building_name'] = DB::table('tabBooking')
					->leftJoin('tabBasecamp', 'tabBooking.basecamp_name', '=', 'tabBasecamp.basecamp_name')
					->where('tabBasecamp.basecamp_name', $data['basecamp_name'])
					->pluck('tabBasecamp.building_name');

				$data['basecamp_address'] = DB::table('tabBuilding')
					->where('building_name', $data['building_name'])
					->pluck('address');

				$data['sharing_type'] = DB::table('tabBed')
					->where('bed_no', $data['bed_no'])
					->pluck('sharing_type');

				$basecamp = DB::table('tabBasecamp')
					->select('room_no', 'map_link')
					->where('basecamp_name', $data['basecamp_name'])
					->first();

				$data['room_no'] = $basecamp->room_no;
				$data['map_link'] = $basecamp->map_link;

				$to = [$to, $client_id];
			}

			if ($module == "Feedback") {
				$to = [$to, 'pankajpatil@bookbasecamp.com'];
			}
		}

		$mail_config = (object) array(
			'from' => $from ? $from : "hello@bookbasecamp.com",
			'to' => $to,
			'cc' => 'aakashsathe@bookbasecamp.com',
			'subject' => $subject
		);

		Mail::later(5, $template, array('data' => $data), function ($message) use ($mail_config) {
			$message->from($mail_config->from, "Basecamp");
			$message->to($mail_config->to);
			$message->cc($mail_config->cc);
			$message->subject($mail_config->subject);
		});
	}
}