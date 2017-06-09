@extends('app')

@section('title', 'Activities - ' . env('BRAND_NAME', 'Origin CMS'))
@section('search', 'Activities')

@section('body')
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="box">
				<div class="box-header with-border">
					<h3 class="box-title">
						<i class="fa fa-bell"></i> Activities
					</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<ul class="timeline">
						@foreach($data as $activity)
							@if ($activity->action == "Create")
								@var $icon_bg = "bg-blue"
							@elseif ($activity->action == "Update")
								@var $icon_bg = "bg-yellow"
							@elseif ($activity->action == "Delete")
								@var $icon_bg = "bg-red"
							@else
								@var $icon_bg = "gray-bg"
							@endif
							<li>
								<i class="fa {{ $activity->icon }} {{ $icon_bg }}"></i>
								<div class="timeline-item bg-gray">
									<span class="time">
										<i class="fa fa-clock-o"></i> 
										{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $activity->created_at)->diffForHumans() }}
									</span>
									<div class="timeline-body no-border">{!! nl2br(make_act_desc($activity)) !!}<br />
										<small class="text-muted">
											@var $activity_dt = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $activity->created_at)
											{{ $activity_dt->toFormattedDateString() }} at {{ $activity_dt->format('h:i A') }}
										</small>
									</div>
								</div>
							</li>
						@endforeach
					</ul>
				</div>
				<!-- /.box-body -->
				<div class="box-footer clearfix">
					<div class="row">
						<div class="col-sm-12 text-right pull-right">
							{{ $data->links() }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection