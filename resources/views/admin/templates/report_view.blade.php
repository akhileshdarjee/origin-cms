@extends('admin')

@section('title', __($title) . ' - ' . config('app.brand.name'))

@push('styles')
    <link type="text/css" rel="stylesheet" href="{{ asset(mix('css/origin/report_view.css')) }}">
@endpush

@section('breadcrumb')
    <ol class="breadcrumb app-breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('show.app.modules') }}">{{ __('Home') }}</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('show.app.reports') }}">{{ __('Reports') }}</a>
        </li>
        <li class="breadcrumb-item active">
            {{ __($title) }}
        </li>
    </ol>
@endsection

@section('title_section')
    <div id="sticky-anchor"></div>
    <div class="content-header" id="sticky">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-8 content-title">
                    <h1 class="m-0">
                        <small class="content-title-label">
                            <i class="fas fa-sitemap"></i>
                            {{ __($title) }}
                        </small>
                    </h1>
                </div>
                <div class="col-sm-4 col-4 text-right list-btns">
                    <div class="btn-group">
                        <button type="button" class="btn bg-gradient-primary btn-sm dropdown-toggle dropdown-icon elevation-2" data-toggle="dropdown">
                            <span class="d-none d-sm-none d-md-inline-block" data-toggle="tooltip" data-placement="bottom" title="{{ __('Download report in multiple formats') }}">
                                {{ __('Download') }}
                            </span>
                            <span class="d-md-none d-lg-none d-xl-none"><i class="fas fa-download"></i></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item download-report" href="#" data-format="xls">
                                {{ __('XLS') }}
                            </a>
                            <a class="dropdown-item download-report" href="#" data-format="xlsx">
                                {{ __('XLSX') }}
                            </a>
                            <a class="dropdown-item download-report" href="#" data-format="csv">
                                {{ __('CSV') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('body')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card elevation-2">
                    <div class="card-body table-responsive report-content">
                        <table class="table table-bordered" id="report-table" data-report-name="{{ $title }}">
                            <thead></thead>
                            <tbody class="report-data"></tbody>
                        </table>
                        <div class="not-found" style="display: none;"></div>
                        <div class="data-loader" style="display: none;">{{ __('Loading') }}...</div>
                    </div>
                    <div class="card-body list-actions with-border-top">
                        <div class="row page-info">
                            <div class="col-sm-7 col-7 p-0">
                                <div class="dataTables_info" id="report-table_info" role="status" aria-live="polite"></div>
                            </div>
                            <div class="col-sm-5 col-5 p-0 text-right">
                                <div class="dataTables_paginate" id="report-table_paginate"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset(mix('js/origin/report_view.js')) }}"></script>
@endpush
