@extends('app')

@section('title', 'Reports - Origin CMS')

@section('body')
	<div class="rpw"> 
		@foreach ($data as $report_name => $report)
			@if ($report->type == "Query")
				@var $report_link = "/app/query_report/" . snake_case($report->name)
			@else
				@var $report_link = "/app/standard_report/" . snake_case($report->name)
			@endif

			<div class="col-md-3 col-xs-12 report" data-href="{{ $report_link }}">
				<div class="box">
					<div class="box-body">
						<div style="text-align: center; margin-bottom: 17px">
							<a href="{{ $report_link }}" class="btn btn-app" style="background-color: {{ $report->bg_color }}; border-color: {{ $report->bg_color }}; color: {{ $report->icon_color }};">
								<i class="{{ $report->icon }}"></i>
							</a>
						</div>
						<h3 class="text-center">{{ $report->name }}</h3>
					</div>
					<!-- /.box-body -->
					<div class="box-footer clearfix text-center">
						{{ $report->description }}
					</div>
				</div>
			</div>
		@endforeach

		@if (Session::get('role') == "Administrator")
			<div class="col-md-3 col-xs-12 report" data-href="/list/reports">
				<div class="box">
					<div class="box-body">
						<div style="text-align: center; margin-bottom: 17px">
							<a href="/list/reports" class="btn btn-app"  style="background-color: #676a6c; border-color: #676a6c; color: #fff;">
								<i class="fa fa-plus"></i>
							</a>
						</div>
						<h3 class="text-center">New Report</h3>
					</div>
					<!-- /.box-body -->
					<div class="box-footer clearfix text-center">Create New Report</div>
				</div>
			</div>
		@endif
	</div>
@endsection