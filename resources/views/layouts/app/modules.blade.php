<div class="row m-t-large m-b">
	@foreach ($data as $module)
		<div class="col-lg-2 col-md-3 col-xs-6 text-center m-b module">
			<a href="{{ $module['href'] }}" class="btn btn-circle" data-toggle="tooltip" data-placement="bottom" data-container="body" title="{{ $module['module_label'] }}">
				<i class="{{ $module['icon'] }}" style="background-color: {{ $module['bg_color'] }}; color: {{ $module['icon_color'] }};"></i>
				<strong class="text-muted">{{ $module['module_label'] }}</strong>
			</a>
		</div>
	@endforeach
</div>