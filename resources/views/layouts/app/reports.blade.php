<div class="row report-list">
	@foreach (config('reports') as $report_name => $report)
		@if (isset($report['allowed_roles']) && $report['allowed_roles'] && !in_array(Session::get('role'), $report['allowed_roles']))
			{{-- */continue;/* --}}
		@endif

		<div class="col-md-3 col-xs-12 widget widget_tally_box report" data-href="/app/report/{{ snake_case($report_name) }}">
			<div class="x_panel ui-ribbon-container">
				<div class="x_content">
					<div style="text-align: center; margin-bottom: 17px">
						<a href="/app/report/{{ snake_case($report_name) }}" class="btn btn-app"  style="background-color: {{ $report['bg_color'] }}; border-color: {{ $report['bg_color'] }}; color: {{ $report['icon_color'] }};">
							<i class="{{ $report['icon'] }}"></i>
						</a>
					</div>
					<h3 class="name_title">{{ $report['label'] }}</h3>
					<div class="divider"></div>
					<p>{{ $report['description'] }}</p>
				</div>
			</div>
		</div>
	@endforeach
</div>