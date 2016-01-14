<?php

namespace App\Http\Controllers;

use DB;
use Session;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
	public function showReport(Request $request, $report_name) {
		$user_role = Session::get('role');
		$user_name = Session::get('user');

		if ($request->has('download') && $request->get('download') == 'Yes') {
			$columns = $this->report_view_columns($report_name);
			$rows = $this->get_records($request, $report_name, $user_role, $user_name);
			return $this->downloadReport($report_name, $columns, $rows);
		}
		else {
			if ($request->ajax()) {
				return $this->get_records($request, $report_name, $user_role, $user_name);
			}
			else {
				$columns = $this->report_view_columns($report_name);
				$rows = $this->get_records($request, $report_name, $user_role, $user_name);
				$report_view_data = $this->prepare_report_view_data($rows, $columns, $report_name);

				return view('templates.report_view', $report_view_data);
			}
		}
	}


	public function report_view_columns($report_name)
	{
		$report_view_columns = [
			'Booking Report' => [
				'booking_no', 'bed_no', 'other_bed_no', 'basecamp_name', 'check_in_date', 'check_out_date', 
				'guest_id', 'guest_name', 'guest_company', 
				'total_no_of_days', 'cancelled'
			],
			'Guest Report' => [
				'full_name', 'gender', 'email_id', 'contact_no', 'pan_no', 'company', 'description'
			],
			'Vacant Beds' => [
				'basecamp_name', 'basecamp_client', 'bed_no', 'sharing_type', 
				'guest_name', 'guest_id', 'status', 'check_in_date', 'check_out_date', 'description'
			],
			'Income Report' => [
				'payment_from', 'client_name', 'guest_id', 'guest_name', 'date_of_payment', 
				'is_paid', 'food_total_amount', 'laundry_total_amount', 'grand_total', 'mode_of_payment'
			],
			'Expense Report' => [
				'expense_item', 'expense_category', 'expense_date', 'expense_by', 'total_amount', 'has_bill', 'mode_of_payment'
			],
			'Balance Sheet' => [
				'account_name', 'type', 'date', 'debit', 'credit'
			],
		];

		return $report_view_columns[$report_name];
	}


	public function get_records($request, $report_name, $user_role, $user_name) {
		if ($report_name == "Booking Report") {
			return $this->get_bookings($request, $user_role, $user_name);
		}
		elseif ($report_name == "Guest Report") {
			return $this->get_guest($request, $user_role, $user_name);
		}
		elseif ($report_name == "Vacant Beds") {
			return $this->get_vacant_beds($request, $user_role, $user_name);
		}
		elseif ($report_name == "Income Report") {
			return $this->get_incomes($request, $user_role, $user_name);
		}
		elseif ($report_name == "Expense Report") {
			return $this->get_expenses($request, $user_role, $user_name);
		}
		elseif ($report_name == "Balance Sheet") {
			return $this->get_balance_sheet($request, $user_role, $user_name);
		}
	}


	public function get_bookings($request, $user_role, $user_name) {
		$query = DB::table('tabBooking')
			->select(
				'booking_no', 'bed_no', 'other_bed_no', 'basecamp_name', 'check_in_date', 'check_out_date', 
				'guest_id', 'guest_name', 'guest_company', 
				'total_no_of_days', 'cancelled'
			);

		if ($request->has('filters') && $request->get('filters')) {
			$filters = $request->get('filters');
			if (isset($filters['client']) && $filters['client'] && $user_role != "Client") {
				$query = $query->where('guest_company', $filters['client']);
			}
			if (isset($filters['basecamp_name']) && $filters['basecamp_name']) {
				$query = $query->where('basecamp_name', $filters['basecamp_name']);
			}
			if (isset($filters['bed_no']) && $filters['bed_no']) {
				$query = $query->where('bed_no', $filters['bed_no']);
			}
			if (isset($filters['from_date']) && isset($filters['to_date']) && $filters['from_date'] && $filters['to_date']) {
				$booking_nos = [];
				$booking_records = DB::table('tabBooking')->get();

				if ($booking_records) {
					$check_date_ranges = BookingController::get_date_ranges($filters['from_date'], $filters['to_date']);

					foreach ($check_date_ranges as $check_date) {
						foreach ($booking_records as $booking) {
							if ((strtotime($check_date) >= strtotime($booking->check_in_date) && 
								strtotime($check_date) <= strtotime($booking->check_out_date))) {
									if (!in_array($booking->booking_no, $booking_nos)){
										array_push($booking_nos, $booking->booking_no);
									}
							}
						}
					}
				}

				$query = $query->whereIn('booking_no', $booking_nos);
			}
		}

		if ($user_role == "Client") {
			$query = $query->where('guest_company', $user_name);
		}

		$result = $query->orderBy('id', 'desc')->get();
		return $result;
	}


	public function get_guest($request, $user_role, $user_name) {
		$query = DB::table('tabGuest')
			->select(
				'full_name', 'gender', 'email_id', 'contact_no', 'pan_no', 'company', 'description'
			);

		if ($request->has('filters') && $request->get('filters')) {
			$filters = $request->get('filters');
			if (isset($filters['company']) && $filters['company'] && $user_role != "Client") {
				$query = $query->where('company', $filters['company']);
			}
			if (isset($filters['from_date']) && isset($filters['to_date']) && $filters['from_date'] && $filters['to_date']) {
				$guest_list = [];
				$guest_records = DB::table('tabGuest')->get();

				if ($guest_records) {
					foreach ($guest_records as $guest) {
						if ((strtotime($guest->created_at) >= strtotime($filters['from_date']) && 
							strtotime($guest->created_at) <= strtotime($filters['to_date']))) {
								if (!in_array($guest->email_id, $guest_list)){
									array_push($guest_list, $guest->email_id);
								}
						}
					}
				}

				$query = $query->whereIn('email_id', $guest_list);
			}
		}

		if ($user_role == "Client") {
			$query = $query->where('company', $user_name);
		}

		$result = $query->orderBy('id', 'desc')->get();
		return $result;
	}


	public function get_vacant_beds($request, $user_role, $user_name) {
		$query = DB::table('tabBed')
			->leftJoin('tabBasecamp', 'tabBed.basecamp_name', '=', 'tabBasecamp.basecamp_name')
			->select(
				'tabBed.basecamp_name',
				'tabBasecamp.basecamp_client',
				'tabBed.bed_no', 
				'tabBed.sharing_type', 
				'tabBed.guest_name',
				'tabBed.guest_id',
				'tabBed.status',
				'tabBed.description'
			);

		if ($request->has('filters') && $request->get('filters')) {
			$filters = $request->get('filters');
			if (isset($filters['client']) && $filters['client'] && $user_role != "Client") {
				$query = $query->where('tabBasecamp.basecamp_client', $filters['client']);
			}
			if (isset($filters['basecamp_name']) && $filters['basecamp_name']) {
				$query = $query->where('tabBed.basecamp_name', $filters['basecamp_name']);
			}
			if (isset($filters['sharing_type']) && $filters['sharing_type']) {
				$query = $query->where('tabBed.sharing_type', $filters['sharing_type']);
			}
			if (isset($filters['from_date']) && isset($filters['to_date']) && $filters['from_date'] && $filters['to_date']) {
				$booked_beds = [];
				$booking_records = DB::table('tabBooking')
					->where('booked', 1)
					->where('cancelled', 0)
					->get();

				if ($booking_records) {
					$check_date_ranges = BookingController::get_date_ranges($filters['from_date'], $filters['to_date']);

					foreach ($check_date_ranges as $check_date) {
						foreach ($booking_records as $booking) {
							if ((strtotime($check_date) >= strtotime($booking->check_in_date) && 
								strtotime($check_date) <= strtotime($booking->check_out_date))) {
									if (!in_array($booking->bed_no, $booked_beds)){
										array_push($booked_beds, $booking->bed_no);
									}

									if ($booking->other_bed_no && (!in_array($booking->other_bed_no, $booked_beds))) {
										array_push($booked_beds, $booking->other_bed_no);
									}
							}
						}
					}
				}

				$query = $query->whereNotIn('tabBed.bed_no', $booked_beds);
			}
		}

		if ($user_role == "Client") {
			$query = $query->where('tabBasecamp.basecamp_client', $user_name);
		}

		$result = $query->orderBy('tabBed.id', 'desc')->groupBy('tabBed.bed_no')->get();

		// remove check in and check out date from vacant beds
		foreach (array_values($result) as $index => $bed) {
			if ($bed->status == "Vacant") {
				$bed->check_in_date = null;
				$bed->check_out_date = null;
			}
			elseif ($bed->status == "Occupied") {
				$booking_dates = DB::table('tabBooking')
					->select('check_in_date', 'check_out_date')
					->where('check_in', 1);

				$booking_dates = $booking_dates->where(function($query) use ($bed)  {
					$query->where('bed_no', $bed->bed_no)
						->orWhere('other_bed_no', $bed->bed_no);
				});

				$booking_dates = $booking_dates->first();

				$bed->check_in_date = $booking_dates->check_in_date;
				$bed->check_out_date = $booking_dates->check_out_date;
			}
		}

		return $result;
	}


	public function get_incomes($request, $user_role, $user_name) {
		$query = DB::table('tabIncome')
			->select(
				'payment_from', 'client_name', 'guest_id', 'guest_name', 'date_of_payment', 
				'is_paid', 'food_total_amount', 'laundry_total_amount', 'grand_total', 'mode_of_payment'
			);

		if ($request->has('filters') && $request->get('filters')) {
			$filters = $request->get('filters');
			if (isset($filters['payment_from']) && $filters['payment_from']) {
				if ($filters['payment_from'] != 'Payment From') {
					$query = $query->where('payment_from', $filters['payment_from']);
				}
			}
			if (isset($filters['is_paid']) && $filters['is_paid']) {
				if ($filters['is_paid'] != 'Is Paid') {
					$query = $query->where('is_paid', $filters['is_paid']);
				}
			}
			if (isset($filters['client_name']) && $filters['client_name'] && $user_role != "Client") {
				$query = $query->where('client_name', $filters['client_name']);
			}
			if (isset($filters['from_date']) && isset($filters['to_date']) && $filters['from_date'] && $filters['to_date']) {
				$income_list = [];
				$income_records = DB::table('tabIncome')->get();

				if ($income_records) {
					foreach ($income_records as $income) {
						if ((strtotime($income->created_at) >= strtotime($filters['from_date']) && 
							strtotime($income->created_at) <= strtotime($filters['to_date']))) {
								if (!in_array($income->email_id, $guest_list)){
									array_push($income_list, $income->id);
								}
						}
					}
				}

				$query = $query->whereIn('id', $income_list);
			}
		}

		if ($user_role == "Client") {
			$query = $query->where('client_name', $user_name);
		}

		$result = $query->orderBy('id', 'desc')->get();
		return $result;
	}


	public function get_expenses($request, $user_role, $user_name) {
		$query = DB::table('tabExpense')
			->select(
				'expense_item', 'expense_category', 'expense_date', 'expense_by', 'total_amount', 'has_bill', 'mode_of_payment'
			);

		if ($request->has('filters') && $request->get('filters')) {
			$filters = $request->get('filters');
			if (isset($filters['has_bill']) && $filters['has_bill']) {
				if ($filters['has_bill'] != 'Has Bill') {
					$query = $query->where('has_bill', $filters['has_bill']);
				}
			}
			if (isset($filters['expense_category']) && $filters['expense_category']) {
				$query = $query->where('expense_category', $filters['expense_category']);
			}
			if (isset($filters['from_date']) && isset($filters['to_date']) && $filters['from_date'] && $filters['to_date']) {
				$expense_list = [];
				$expense_records = DB::table('tabExpense')->get();

				if ($expense_records) {
					foreach ($expense_records as $expense) {
						if ((strtotime($expense->created_at) >= strtotime($filters['from_date']) && 
							strtotime($expense->created_at) <= strtotime($filters['to_date']))) {
								if (!in_array($expense->id, $expense_list)){
									array_push($expense_list, $expense->id);
								}
						}
					}
				}

				$query = $query->whereIn('id', $expense_list);
			}
		}

		$result = $query->orderBy('id', 'desc')->get();
		return $result;
	}


	public function get_balance_sheet($request) {
		$income_query = DB::table('tabIncome')
			->select(
				DB::raw("CONCAT_WS(' : ', payment_from, IFNULL(client_name, guest_name)) as account_name"), 
				DB::raw("'Income' as type"), 
				'date_of_payment as date', 
				DB::raw("'' as debit"), 
				'grand_total as credit'
			)
			->where('tabIncome.is_paid', 'Yes');

		$expense_query = DB::table('tabExpense')
			->select(
				'expense_item as account_name', 
				DB::raw("'Expense' as type"), 
				DB::raw("DATE(expense_date) as date"), 
				'total_amount as debit', 
				DB::raw("'' as credit") 
			);

		if ($request->has('filters') && $request->get('filters')) {
			$filters = $request->get('filters');
			if (isset($filters['from_date']) && isset($filters['to_date']) && $filters['from_date'] && $filters['to_date']) {
				$income_list = [];
				$income_records = DB::table('tabIncome')->get();

				if ($income_records) {
					foreach ($income_records as $income) {
						if ((strtotime($income->created_at) >= strtotime($filters['from_date']) && 
							strtotime($income->created_at) <= strtotime($filters['to_date']))) {
								if (!in_array($income->id, $income_list)){
									array_push($income_list, $income->id);
								}
						}
					}
				}

				$expense_list = [];
				$expense_records = DB::table('tabExpense')->get();

				if ($expense_records) {
					foreach ($expense_records as $expense) {
						if ((strtotime($expense->created_at) >= strtotime($filters['from_date']) && 
							strtotime($expense->created_at) <= strtotime($filters['to_date']))) {
								if (!in_array($expense->id, $expense_list)){
									array_push($expense_list, $expense->id);
								}
						}
					}
				}

				$income_query = $income_query->whereIn('id', $income_list);
				$expense_query = $expense_query->whereIn('id', $expense_list);
			}
		}

		$result = array_merge($income_query->orderBy('id', 'desc')->get(), $expense_query->orderBy('id', 'desc')->get());
		return $result;
	}


	// Returns an array of all data to be passed to report view
	public function prepare_report_view_data($rows, $columns, $report_name) {
		$report_view_data = [
			'rows' => $rows,
			'columns' => $columns,
			'title' => $report_name,
			'file' => 'layouts.reports.' . strtolower(str_replace(" ", "_", $report_name)),
			'count' => count($rows)
		];

		return $report_view_data;
	}


	// make downloadable xls file for report
	public function downloadReport($report_name, $columns, $rows, $suffix = null, $action = null, $custom_rows = null) {
		// file name for download
		if ($suffix) {
			$filename = $report_name . "-" . date('Y-m-d H:i:s') . "-" . $suffix;
		}
		else {
			$filename = $report_name . "-" . date('Y-m-d H:i:s');
		}

		$data_to_export['sheets'][] = [
			'header' => $columns,
			'sheet_title' => $report_name,
			'details' => $rows
		];

		$report = Excel::create($filename, function($excel) use($data_to_export, $custom_rows) {
			foreach($data_to_export['sheets'] as $data_sheet) {
				$excel->sheet($data_sheet['sheet_title'], function($sheet) use($data_sheet, $custom_rows) {
					$column_header = $data_sheet['header'];

					foreach ($column_header as $key => $value) {
						$column_header[$key] = ucwords(str_replace("_", " ", $column_header[$key]));
						if (strpos($column_header[$key], 'Id') !== false) {
							$column_header[$key] = str_replace("Id", "ID", $column_header[$key]);
						}
					}
					$data = [];
					array_push($data, $column_header);

					foreach($data_sheet['details'] as $excel_row){
						array_push($data, (array) $excel_row);
					}

					// Add custom rows to file
					if ($custom_rows) {
						if (isset($custom_rows['after_line']) && $custom_rows['after_line']) {
							for ($i = 0; $i < $custom_rows['after_line']; $i++) { 
								array_push($data, []);
							}
						}

						if (isset($custom_rows['rows']) && $custom_rows['rows']) {
							foreach ($custom_rows['rows'] as $key => $value) {
								array_push($data, array($key, $value));
							}
						}
					}

					$sheet->fromArray($data, null, 'A1', false, false);
				});
			}
		});

		if ($action) {
			if ($action == "store") {
				return $report->store('xls', false, true);
			}
			else {
				$report->download('xls');
			}
		}
		else {
			$report->download('xls');
		}
	}
}