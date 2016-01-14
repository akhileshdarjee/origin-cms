<!DOCTYPE html>
<html lang="en">
	<head>
		<title>{{ $title }} - Report</title>
		@include('templates.headers')
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
											<a class="btn btn-white btn-sm" id="download_report" name="download_report">
												<i class="fa fa-download"></i> Download
											</a>
											<a class="btn btn-primary btn-sm" id="refresh_report" name="refresh_report">
												Refresh
											</a>
										</div>
									</div>
								</div>
							</header>
							@include($file)
							<div style="height: 375px; margin-bottom: 0px; padding: 0px;" class="panel-body scrollbar scroll-x scroll-y table-responsive b-t">
								<table class="table table-striped table-bordered" id="report-table" data-report-name="{{ $title }}">
									<thead class="panel-heading text-small">
										<tr>
											<th>#</th>
											@if (isset($columns) && $columns)
												@foreach ($columns as $column)
													<th name="{{ $column }}">{{ $column }}</th>
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
														<td data-field-name="{{ $column }}" 
															title="{{ (isset($row->$column) && $row->$column) ? $row->$column : "" }}">
															{{ (isset($row->$column) && $row->$column) ? $row->$column : "" }}
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
	</body>
</html>