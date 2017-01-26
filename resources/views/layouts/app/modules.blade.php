@extends('app')

@section('title', 'Modules - Origin CMS')

@push('data')
	<script type="text/javascript">
		window.doc = {
			data: <?php echo isset($data) ? json_encode($data) : "false" ?>,
		};
	</script>
@endpush

@section('body')
	<div class="box-body">
		@foreach ($data as $module)
			<a href="{{ $module['href'] }}" class="btn btn-app module-btn" style="background-color: {{ $module['bg_color'] }}; box-shadow: inset 0px 0px 0px {{ $module['bg_color'] }}, 0px 5px 0px 0px {{ $module['bg_color'] }}, 0px 10px 5px #999999; border-color: {{ $module['bg_color'] }};" title="{{ $module['module_label'] }}">
				<i class="fa {{ $module['icon'] }} fa-2x" style="color: {{ $module['icon_color'] }};"></i>
				{{ $module['module_label'] }}
			</a>
		@endforeach
	</div>
@endsection