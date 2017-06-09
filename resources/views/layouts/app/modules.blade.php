@extends('app')

@section('title', 'Modules - ' . env('BRAND_NAME', 'Origin CMS'))
@section('search', 'Modules')

@section('body')
	<div class="row">
		@foreach ($data as $module)
			<div class="col-lg-2 col-md-3 col-xs-6 text-center m-b app-module">
				<a class="module-btn" data-href="{{ url($module['href']) }}" style="background-color: {{ $module['bg_color'] }}; box-shadow: inset 0px 0px 0px {{ $module['bg_color'] }}, 0px 5px 0px 0px {{ $module['bg_color'] }}, 0px 10px 5px #999999; border-color: {{ $module['bg_color'] }};" title="{{ $module['module_label'] }}">
					<i class="{{ $module['icon'] }}" style="color: {{ $module['icon_color'] }};"></i>
				</a>
				<h3 class="module-label">
					<a href="{{ url($module['href']) }}">{{ $module['module_label'] }}</a>
				</h3>
			</div>
		@endforeach
	</div>
@endsection