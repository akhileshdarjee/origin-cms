<div class="row m-t-large m-b report-list">
	<div class="col-md-3 report" data-href="/app/report/user_report">
		<section class="panel text-center">
			<div class="panel-body">
				<a class="btn btn-circle btn-lg">
					<i class="fa fa-user" style="background-color: #d35400; color: #ffffff;"></i>
				</a>
				<div class="h4">USER REPORT</div>
				<div class="line m-l m-r"></div>
				<small>List of all User(s)</small>
			</div>
		</section>
	</div>
</div>
<script type="text/javascript">
	$( document ).ready(function() {
		// on click of report widget div navigate to report
		$(".report-list > .report").on("click", function() {
			window.location = $(this).data("href");
		});
	});
</script>