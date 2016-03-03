<div class="row m-t-large m-b report-list">
	@foreach (config('reports') as $report_name => $report)
		<div class="col-md-3 report" data-href="/app/report/{{ snake_case($report_name) }}">
			<section class="panel text-center">
				<div class="panel-body">
					<a class="btn btn-circle btn-lg">
						<i class="{{ $report['icon'] }}" style="background-color: {{ $report['bg_color'] }}; color: {{ $report['icon_color'] }};"></i>
					</a>
					<div class="h4">{{ $report['label'] }}</div>
					<div class="line m-l m-r"></div>
					<small>{{ $report['description'] }}</small>
				</div>
			</section>
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