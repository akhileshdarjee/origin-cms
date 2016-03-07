<div class="row m-b">	
	@foreach ($data as $module)
		<div class="col-lg-2 col-md-3 col-xs-6 text-center m-b app-module">
			<button class="btn btn-primary dim btn-large-dim module-btn" data-href="{{ $module['href'] }}" 
				style="background-color: {{ $module['bg_color'] }}; box-shadow: inset 0px 0px 0px {{ $module['bg_color'] }}, 0px 5px 0px 0px {{ $module['bg_color'] }}, 0px 10px 5px #999999; border-color: {{ $module['bg_color'] }};" title="{{ $module['module_label'] }}">
				<i class="fa {{ $module['icon'] }}" style="color: {{ $module['icon_color'] }};"></i>
			</button>
			<h3 class="module-label">{{ $module['module_label'] }}</h3>
		</div>
	@endforeach
</div>