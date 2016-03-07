<div class="row m-t-large m-b report-list">
	@foreach (config('reports') as $report_name => $report)
		<div class="col-md-3 report" data-href="/app/report/{{ snake_case($report_name) }}">
			<div class="ibox-content text-center p-md report-box">
				<div class="ibox float-e-margins">
					<button class="btn btn-white btn-circle btn-xl" style="background-color: {{ $report['bg_color'] }}; border-color: {{ $report['bg_color'] }}; color: {{ $report['icon_color'] }};">
						<i class="{{ $report['icon'] }}"></i>
					</button>
					<div class="h4">{{ $report['label'] }}</div>
					<div class="hr-line-dashed"></div>
					<small>{{ $report['description'] }}</small>
				</div>
			</div>
		</div>
	@endforeach
</div>
<script type="text/javascript">
	$( document ).ready(function() {
		// on click of report widget div navigate to report
		$(".report-list > .report").on("click", function() {
			window.location = $(this).data("href");
		});
	});
</script>