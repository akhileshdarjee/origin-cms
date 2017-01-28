@extends('app')

@section('title', 'Reports - Origin CMS')

@section('body')
	<div class="rpw"> 
		@foreach ($data as $report_name => $report)
			@if (isset($report['allowed_roles']) && $report['allowed_roles'] && !in_array(Session::get('role'), $report['allowed_roles']))
				@continue
			@endif

			<div class="col-md-3 col-xs-12 report" data-href="/app/report/{{ snake_case($report_name) }}">
				<div class="box">
					<div class="box-body">
						<div style="text-align: center; margin-bottom: 17px">
							<a href="/app/report/{{ snake_case($report_name) }}" class="btn btn-app"  style="background-color: {{ $report['bg_color'] }}; border-color: {{ $report['bg_color'] }}; color: {{ $report['icon_color'] }};">
								<i class="{{ $report['icon'] }}"></i>
							</a>
						</div>
						<h3 class="text-center">{{ $report['label'] }}</h3>
					</div>
					<!-- /.box-body -->
					<div class="box-footer clearfix text-center">
						{{ $report['description'] }}
					</div>
				</div>
			</div>
		@endforeach
	</div>
@endsection