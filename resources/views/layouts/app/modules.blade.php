<div class="">
	<div class="page-title">
		<div class="">
			@foreach ($data as $module)
				<div class="col-lg-2 col-md-3 col-xs-6 text-center m-b app-module">
					<a href="{{ $module['href'] }}" class="btn btn-app" style="background-color: {{ $module['bg_color'] }}; box-shadow: inset 0px 0px 0px {{ $module['bg_color'] }}, 0px 5px 0px 0px {{ $module['bg_color'] }}, 0px 10px 5px #999999; border-color: {{ $module['bg_color'] }};" title="{{ $module['module_label'] }}">
						<i class="fa {{ $module['icon'] }} fa-2x" style="color: {{ $module['icon_color'] }};"></i>
					</a>
					<h3 class="module-label">{{ $module['module_label'] }}</h3>
				</div>
			@endforeach
		</div>
	</div>
</div>