@extends('layouts.app')

@section('title', __('Reports') . ' - ' . config('app.brand.name'))
@section('search', __('Reports'))

@section('body')
    <div class="container pt-5">
        <div class="row report-list"> 
            @foreach ($data as $report_name => $report)
                @if (isset($report['allowed_roles']) && $report['allowed_roles'] && !in_array(auth()->user()->role, $report['allowed_roles']))
                    @continue
                @endif
                <div class="col-md-3 col-12 report" data-href="{{ route('show.report', Str::snake($report_name)) }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="report-body">
                                <a href="{{ route('show.report', Str::snake($report_name)) }}" class="btn btn-app report-btn" style="background-color: {{ $report['bg_color'] }}; border-color: {{ $report['bg_color'] }}; color: {{ $report['icon_color'] }};">
                                    <i class="{{ $report['icon'] }}"></i>
                                </a>
                            </div>
                            <h4 class="text-center mb-0">{{ __($report['label']) }}</h4>
                        </div>
                        <div class="card-footer text-center">
                            <small class="text-muted">{{ __($report['description']) }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            // on click of report widget div navigate to report
            $(".report-list > .report").on("click", function() {
                window.location = $(this).data("href");
            });
        });
    </script>
@endpush
