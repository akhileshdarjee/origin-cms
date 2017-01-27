<!DOCTYPE html>
<html lang="en">
	<head>
		<title>{{ ucwords($title) }} - Origin CMS</title>
		@include('templates.headers')
		<link type="text/css" rel="stylesheet" href="{{ elixir('css/web_app/app-report.css') }}">
	</head>
	<body class="nav-md">
		<div class="container body">
			<div class="main_container">
				@include('templates.vertical_nav')
				@include('templates.navbar')
				<!-- page content -->
				<div class="right_col" role="main">
					<div class="animated fadeInRight">
						<div class="row">
							<div class="col-sm-12">
								<div class="x_panel">
									<div class="x_title floatbox-title">
										<div class="form-name">
											<i class="fa fa-list"></i> {{ ucwords($title) }}
										</div>
										<div class="panel_toolbox">
											<a class="btn btn-default btn-sm" id="download_report" name="download_report" 
												data-toggle="tooltip" data-placement="bottom" data-container="body" title="Download Report in Excel format">
												<i class="fa fa-download"></i> Download
											</a>
											@if (view()->exists('layouts/reports/' . strtolower(str_replace(" ", "_", $title))))
												<a class="btn btn-primary btn-sm" id="refresh_report" name="refresh_report"
													data-toggle="tooltip" data-placement="bottom" data-container="body" title="Filter Report">
													<i class="fa fa-filter"></i> Filter
												</a>
											@endif
										</div>
									</div>
									@if (view()->exists('layouts/reports/' . strtolower(str_replace(" ", "_", $title))))
										<div class="x_content">
											@include($file)
										</div>
									@endif
									<div class="x_content">
										<div class="table-responsive">
											<table class="table table-bordered" id="report-table" data-report-name="{{ $title }}">
												<thead class="panel-heading text-small">
													<tr>
														<th>#</th>
														@if (isset($columns) && $columns)
															@foreach ($columns as $column)
																@var $col_head = str_replace("Id", "ID", awesome_case($column))
																<th name="{{ $column }}">{{ $col_head }}</th>
															@endforeach
														@endif
													</tr>
												</thead>
												<tbody>
													@if (isset($rows) && $rows && (count($rows) > 0))
														@var $counter = 0
														@foreach ($rows as $row)
															<tr>
																<td>{{ $counter += 1 }}</td>
																@foreach ($columns as $column)
																	<td data-field-name="{{ $column }}" data-toggle="tooltip" data-placement="bottom" data-container="body"
																		title="{{ (isset($row->$column) && $row->$column) ? $row->$column : "" }}">
																		@if (isset($module) && $module
																			&& isset($link_field) && $link_field
																			&& isset($record_identifier) && $record_identifier
																			&& $column == $record_identifier)
																				<a href="/form/{{ $module }}/{{ $row->$link_field }}">{{ (isset($row->$column) && $row->$column) ? $row->$column : "" }}</a>
																		@elseif (filter_var($row->$column, FILTER_VALIDATE_URL))
																			<a href="{{ $row->$column }}" target="_blank">{{ $row->$column }}</a>
																		@else
																			{{ (isset($row->$column) && $row->$column) ? $row->$column : "" }}
																		@endif
																	</td>
																@endforeach
															</tr>
														@endforeach
													@endif
												</tbody>
											</table>
										</div>
									</div>
									<div class="x_content">
										<strong>
											Total : <span class="badge bg-primary" id="item-count">{{ $count }}</span>
											{{ (isset($module) && $module) ? awesome_case($module) : 'item' }}(s)
										</strong>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /page content -->
				@include('templates.footer')
				<script type="text/javascript" src="{{ elixir('js/web_app/app-report.js') }}"></script>
			</div>
		</div>
	</body>
</html>