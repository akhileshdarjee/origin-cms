<!DOCTYPE html>
<html lang="en">
	<head>
		<title>{{ $title }} - Report</title>
		@include('templates.headers')
		<style type="text/css">
			::-webkit-resizer {
				background-color: transparent;
			}
			::-webkit-resizer:hover {
				cursor: row-resize;
			}
			::-webkit-scrollbar-corner:hover {
				cursor: row-resize;
			}
		</style>
	</head>
	<body class="navbar-fixed">
		@include('templates.navbar')
		@include('templates.vertical_nav')
		<section id="content" class="content-sidebar bg-white">
			<section class="main">
				<div class="row">
					<div class="col-lg-12">
						<section class="bg-white">
							<header class="panel-heading bg-white">
								<div class="row">
									<div class="col-md-6">
										<div class="h4">
											<span><i class="fa fa-list"></i> Report: {{ $title }}</span>
										</div>
									</div>
									<div class="col-md-6 col-md-push-4">
										<div style="line-height:39px;">
											<a class="btn btn-white btn-sm" id="download_report" name="download_report"
												data-toggle="tooltip" data-placement="bottom" data-container="body" title="Download Report in Excel format">
													<i class="fa fa-download"></i> Download
											</a>
											<a class="btn btn-primary btn-sm" id="refresh_report" name="refresh_report"
												data-toggle="tooltip" data-placement="bottom" data-container="body" title="Filter Report">
													Refresh
											</a>
										</div>
									</div>
								</div>
							</header>
							@if (view()->exists('layouts/reports/' . strtolower(str_replace(" ", "_", $title))))
								@include($file)
							@endif
							<div style="height: 375px; margin-bottom: 0px; padding: 0px;" class="panel-body scrollbar scroll-x scroll-y table-responsive b-t">
								<table class="table table-bordered" id="report-table" data-report-name="{{ $title }}">
									<thead class="panel-heading text-small remove-before">
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
							<footer class="panel-footer text-right report-footer">
								<strong>Total : <span class="badge bg-primary" id="item-count">{{ $count }}</span> item(s)</strong>
							</footer>
						</section>
					</div>
				</div>
			</section>
		</section>
		@include('templates.msgbox')
		@if (Session::has('msg'))
			<script type="text/javascript">
				msgbox("{{ Session::get('msg') }}");
			</script>
		@endif
		<script src="/js/datatables/jquery.dataTables.min.js"></script>
		<script src="/js/web_app/report_view.js"></script>
	</body>
</html>