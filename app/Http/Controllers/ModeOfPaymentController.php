<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ModeOfPaymentController extends Controller
{
	// define common variables
	public $form_config;

	public function __construct() {
		$this->form_config = [
			'module' => 'ModeOfPayment',
			'module_label' => 'Mode Of Payment',
			'module_icon' => 'fa fa-money',
			'table_name' => 'tabModeOfPayment',
			'view' => 'layouts.mode_of_payment',
			'link_field' => 'id',
			'link_field_label' => 'ID',
			'record_identifier' => 'name'
		];
	}
}