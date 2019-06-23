@extends('layouts.app')

@section('title', 'Backups - ' . config('app.brand.name'))
@section('search', 'Backups')

@section('title_section')
    <div id="sticky-anchor"></div>
    <section class="content-header title-section" id="sticky">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-8">
                <div class="form-name">
                    <i class="fa fa-hdd-o"></i> Backups
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-4 text-right">
                <button type="button" class="btn btn-success btn-sm refresh-backups">
                    <span class="hidden-xs">Refresh</span>
                    <span class="visible-xs"><i class="fa fa-refresh"></i></span>
                </button>
                <div class="btn-group">
                    <button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle">
                        <span class="hidden-xs">Create Backup <span class="caret"></span></span>
                        <span class="visible-xs"><i class="fa fa-plus"></i></span>
                    </button>
                    <ul class="dropdown-menu dropdown-left">
                        <li>
                            <a class="create-backup" data-href="{{ route('create.app.backups') }}">
                                Database + Files
                            </a>
                        </li>
                        <li>
                            <a class="create-backup" data-href="{{ route('create.app.backups') }}?type=db">
                                Database
                            </a>
                        </li>
                        <li>
                            <a class="create-backup" data-href="{{ route('create.app.backups') }}?type=files">
                                Files
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('body')
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-body list-content">
                    <div class="table-responsive list-responsive">
                        <table class="table table-hover list-view">
                            <thead>
                                <tr class="list-header">
                                    <th class="text-center" valign="middle">#</th>
                                    <th name="name" valign="middle">Name</th>
                                    <th name="date" valign="middle">Date</th>
                                    <th name="size" valign="middle">Size</th>
                                    <th name="size" valign="middle">Type</th>
                                    <th name="download" valign="middle">Download</th>
                                    <th name="delete" valign="middle">Delete</th>
                                </tr>
                            </thead>
                            <tbody class="list-view-items"></tbody>
                        </table>
                    </div>
                </div>
                <div class="box-footer clearfix">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-5">
                            <span id="item-from"></span> -
                            <span id="item-to"></span> of 
                            <strong>
                                <span class="badge" id="item-count"></span>
                            </strong>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-7">
                            <div class="origin-pagination-content"></div>
                        </div>
                    </div>
                </div>
                <div class="data-loader" style="display: none;">Loading...</div>
            </div>
            <div class="data-loader-full" style="display: none;">Creating...</div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset(mix('js/origin_backups.js')) }}"></script>
@endpush
