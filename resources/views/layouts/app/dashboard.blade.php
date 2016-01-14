<script type="text/javascript">
	var chart_data = <?php echo isset($data) ? json_encode($data) : "false" ?>;
</script>
<div class="row m-t-large m-b" id="city_analytics_chart">
	<div class="col-md-6">
		<section class="panel city-analytics">
			<header class="panel-heading font-bold">
				<strong>Percentage Pie</strong>
			</header>
			<div class="panel-body">
				<div id="analytics-details" style="height: 240px;"></div>
				<div class="line pull-in"></div>
				<div class="row" id="analytics-percent"></div>
			</div>
		</section>
	</div>
	<div class="col-md-6">
		<section class="panel">
			<header class="panel-heading">
				<span class="label bg-danger pull-right" style="font-size: 12px;">{{ count($data) }}</span>
				<strong>City Clicks</strong>
			</header>
			<div>
				<table class="table table-striped m-b-none text-small" id="no_of_clicks">
					<thead>
						<tr>
							<th>Click Percentage</th>
							<th>City</th>
							<th width="70">Clicks</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</section>
	</div>
</div>
<script type="text/javascript" src="/js/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="/js/flot/jquery.flot.tooltip.min.js"></script>
<script type="text/javascript" src="/js/flot/jquery.flot.resize.js"></script>
<script type="text/javascript" src="/js/flot/jquery.flot.orderBars.js"></script>
<script type="text/javascript" src="/js/flot/jquery.flot.pie.min.js"></script>
<script type="text/javascript" src="/js/stage_42/stage_42_chart.js"></script>