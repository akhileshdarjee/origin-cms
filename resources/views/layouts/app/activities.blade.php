<div class="row animated fadeInRight">
	<div class="col-md-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5><i class="fa fa-bell"></i> Activities</h5>
			</div>
			<div class="ibox-content">
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<div id="vertical-timeline" class="vertical-container light-timeline no-margins">
							@foreach($data as $activity)
								<div class="vertical-timeline-block">
									@if ($activity->action == "Create")
										@var $icon_bg = "blue-bg"
									@elseif ($activity->action == "Update")
										@var $icon_bg = "yellow-bg"
									@elseif ($activity->action == "Delete")
										@var $icon_bg = "red-bg"
									@else
										@var $icon_bg = "navy-bg"
									@endif
									<div class="vertical-timeline-icon {{ $icon_bg }}">
										<i class="{{ $activity->icon }}"></i>
									</div>
									<div class="vertical-timeline-content gray-bg">
										<small class="pull-right">
											{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $activity->created_at)->diffForHumans() }}
										</small>
										<p class="no-mar">{!! nl2br($activity->description) !!}</p>
										<small class="text-muted">
											@var $activity_dt = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $activity->created_at)
											{{ $activity_dt->toFormattedDateString() }} at {{ $activity_dt->format('h:i A') }}
										</small>
									</div>
								</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
			@if (count($data) > 20)
				<div class="ibox-content">
					<div class="row">
						<div class="col-sm-5 text-right pull-right">
							{!! $data->render() !!}
						</div>
					</div>
				</div>
			@endif
		</div>
	</div>
</div>
<script type="text/javascript">
	$( document ).ready(function() {
		// set pagination attributes
		$(".pagination").attr("class", "pagination pagination-small m-t-none m-b-none");
	});
</script>