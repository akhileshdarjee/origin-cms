@extends('admin')

@section('title', __('Reports') . ' - ' . config('app.brand.name'))

@section('body')
    <div class="container pt-4">
        <div class="row report-list"> 
            @foreach ($data as $report_name => $report)
                <div class="col-md-3 col-12 report" data-href="{{ route('show.report', Str::snake($report_name)) }}">
                    <div class="card elevation-2">
                        <a href="{{ route('show.report', Str::snake($report_name)) }}">
                            <div class="card-body">
                                <div class="report-body">
                                    @if (session('app_settings') && isset(session('app_settings')['theme']) && session('app_settings')['theme'] == 'dark')
                                        <div class="btn btn-app report-btn" data-default-color="{{ $report['bg_color'] }}" style="background-color: {{ luminance($report['bg_color'], -0.4) }}; border-color: {{ luminance($report['bg_color'], -0.4) }}; color: {{ $report['icon_color'] }};">
                                    @else
                                        <div class="btn btn-app report-btn" data-default-color="{{ $report['bg_color'] }}" style="background-color: {{ $report['bg_color'] }}; border-color: {{ $report['bg_color'] }}; color: {{ $report['icon_color'] }};">
                                    @endif
                                        <i class="{{ $report['icon'] }}"></i>
                                    </div>
                                </div>
                                <h5 class="text-center mb-0 report-title">{{ __($report['label']) }}</h5>
                            </div>
                            <div class="card-footer text-center">
                                <small class="text-muted">{{ __($report['description']) }}</small>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
