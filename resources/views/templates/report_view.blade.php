@extends('layouts.app')

@section('title', __(ucwords($title)) . ' - ' . config('app.brand.name'))
@section('search', __(ucwords($title)))

@push('styles')
    <link type="text/css" rel="stylesheet" href="{{ asset(mix('css/origin/report_view.css')) }}">
@endpush

@section('breadcrumb')
    <ol class="breadcrumb app-breadcrumb">
        <li>
            <a href="{{ route('home') }}"><strong>{{ __('Home') }}</strong></a>
        </li>
        <li>
            <a href="{{ route('show.app.reports') }}"><strong>{{ __('Report') }}</strong></a>
        </li>
        <li class="active">
            {{ __(ucwords($title)) }}
        </li>
    </ol>
@endsection

@section('title_section')
    <div id="sticky-anchor"></div>
    <section class="content-header title-section" id="sticky">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-8">
                <div class="form-name">
                    <i class="fa fa-sitemap"></i> {{ __(ucwords($title)) }}
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-4 text-right">
                <div class="btn-group">
                    <button data-toggle="dropdown" class="btn btn-success btn-sm dropdown-toggle">
                        <span class="hidden-xs" data-toggle="tooltip" data-placement="bottom" title="{{ __('Download report in multiple formats') }}">
                            {{ __('Download') }} <span class="caret"></span>
                        </span>
                        <span class="visible-xs">
                            <i class="fa fa-download"></i>
                            <span class="caret"></span>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-left">
                        <li>
                            <a href="#" class="download-report" data-format="xls">
                                {{ __('XLS') }}
                            </a>
                        </li>
                        <li>
                            <a href="#" class="download-report" data-format="xlsx">
                                {{ __('XLSX') }}
                            </a>
                        </li>
                        <li>
                            <a href="#" class="download-report" data-format="csv">
                                {{ __('CSV') }}
                            </a>
                        </li>
                    </ul>
                </div>
                @if (view()->exists('layouts/reports/' . strtolower(str_replace(" ", "_", $title))))
                    <a class="btn btn-primary btn-sm" id="filter_report" name="refresh_report" data-toggle="tooltip" data-placement="bottom" title="{{ __('Apply filters') }}">
                        <span class="hidden-xs">{{ __('Filter') }}</span>
                        <span class="visible-xs"><i class="fa fa-filter"></i></span>
                    </a>
                @endif
            </div>
        </div>
    </section>
@endsection

@section('body')
    <div class="box n-m-b">
        @if (view()->exists('layouts/reports/' . strtolower(str_replace(" ", "_", $title))))
            <div class="box-header with-border report-filter-sec">
                @include($file)
            </div>
        @endif
        <div class="box-body table-responsive report-content">
            <table class="table table-bordered" id="report-table" data-report-name="{{ $title }}">
                <thead class="panel-heading text-small">
                    <tr>
                        <th>#</th>
                        @if (isset($columns) && $columns)
                            @foreach ($columns as $column)
                                @var $col_head = str_replace("Id", "ID", awesome_case($column))
                                <th name="{{ $column }}">{{ __($col_head) }}</th>
                            @endforeach
                        @endif
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <div class="data-loader" style="display: none;">{{ __('Loading') }}...</div>
        </div>
        <div class="box-footer clearfix">
            <div class="row report-pagination">
                <div class="col-sm-5 col-xs-6">
                    <div class="dataTables_info" id="report-table_info" role="status" aria-live="polite"></div>
                </div>
                <div class="col-sm-7 col-xs-6 text-right">
                    <div class="dataTables_paginate" id="report-table_paginate"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset(mix('js/origin/report_view.js')) }}"></script>
@endpush
