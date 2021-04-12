@extends('admin')

@var $page_title = isset($form_data[$table_name]['id']) ? $form_data[$table_name][$form_title] : $title
@section('title', $page_title . ' - ' . config('app.brand.name'))

@section('data')
    <script type="text/javascript">
        window.origin = {
            data: <?php echo isset($form_data) ? json_encode($form_data) : json_encode(false) ?>,
            title: "{{ $title }}",
            slug: "{{ $slug }}",
            module: "{{ $module }}",
            changed: false,
            table_name: "{{ $table_name }}",
            permissions: <?php echo isset($permissions) ? json_encode($permissions) : json_encode(false) ?>
        };
    </script>
@endsection

{{-- Hide breadcrumbs for Single type Modules eg. Settings --}}

@if (!isset($module_type))
    @section('breadcrumb')
        <ol class="breadcrumb app-breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('show.app.modules') }}">{{ __('Home') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('show.list', $slug) }}">{{ __($title) }}</a>
            </li>
            <li class="breadcrumb-item active">
                @if (isset($form_data[$table_name]['id']))
                    {{ $form_data[$table_name][$form_title] }}
                @else
                    {{ __('New') }} {{ __($title) }}
                @endif
            </li>
        </ol>
    @endsection
@endif

@section('title_section')
    <div id="sticky-anchor"></div>
    <div class="content-header" id="sticky">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-8">
                    <h1 class="m-0">
                        <small>
                            @if (isset($module_type) && $module_type == "Single")
                                <i class="{{ $icon }}"></i> {{ __($title) }}
                            @else
                                <i class="{{ $icon }}"></i>
                                @if (isset($form_data[$table_name]['id']))
                                    {{ $form_data[$table_name][$form_title] }}
                                @else
                                    {{ __('New') }} {{ __($title) }}
                                @endif
                            @endif
                        </small>
                        @if (isset($form_data[$table_name]['id']) && $permissions['update'])
                            <div class="form-status ml-1">
                                <span class="text-center text-xs font-weight-normal" id="form-stats">
                                    <i class="fas fa-circle fa-sm text-green"></i>
                                    <span id="form-status">{{ __('Saved') }}</span>
                                </span>
                            </div>
                        @endif
                    </h1>
                </div>
                <div class="col-sm-4 col-4 text-right list-btns">
                    @if (isset($form_data[$table_name]['id']) && ($permissions['create'] || $permissions['delete']))
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-default btn-sm dropdown-toggle dropdown-icon elevation-2" data-toggle="dropdown">
                                <span class="d-none d-sm-none d-md-inline-block">
                                    {{ __('Menu') }}
                                </span>
                                <span class="d-md-none d-lg-none d-xl-none"><i class="fas fa-ellipsis-h"></i></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                @if ($permissions['create'])
                                    <a class="dropdown-item" href="{{ route('copy.doc', ['slug' => $slug, 'id' => $form_data[$table_name][$link_field]]) }}">
                                        {{ __('Duplicate') }}
                                    </a>
                                @endif
                                @if ($permissions['delete'])
                                    <a class="dropdown-item" href="#" id="delete" name="delete">
                                        {{ __('Delete') }}
                                    </a>
                                @endif
                                @if ($permissions['create'])
                                    <a class="dropdown-item" href="{{ route('new.doc', $slug) }}">
                                        {{ __('New') }} {{ __($title) }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                    @if ((isset($module_type) && $module_type == "Single") || $permissions['update'])
                        <button type="submit" class="btn bg-gradient-primary btn-sm elevation-2 disabled" id="save_form" disabled>
                            <span class="d-none d-sm-none d-md-inline-block">
                                <i class="fas fa-save fa-sm pr-1"></i>
                                {{ __('Save') }}
                            </span>
                            <span class="d-md-none d-lg-none d-xl-none"><i class="fas fa-save"></i></span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('body')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 form-container">
                <div class="form-body">
                    @if (isset($module_type) && $module_type == "Single")
                        @include($file)
                    @else
                        @if (isset($form_data[$table_name]['id']) && $form_data[$table_name]['id'])
                            @var $action = route('show.doc', ['slug' => $slug, 'id' => $form_data[$table_name]['id']])
                        @else
                            @var $action = route('new.doc', $slug)
                        @endif
                        <form method="POST" action="{{ $action }}" name="{{ $slug }}" id="{{ $slug }}" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            <input type="hidden" name="id" id="id" class="form-control" data-mandatory="no" autocomplete="off" readonly>
                            @if (view()->exists(str_replace('.', '/', $file)))
                                @include($file)
                            @else
                                {{ __('Please create/update') }} '{{ str_replace('.', '/', $file) }}.blade.php' {{ __('in views') }}
                            @endif
                        </form>
                    @endif
                </div>
                <div class="data-loader-full" style="display: none;">{{ __('Saving') }}...</div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset(mix('js/origin/form.js')) }}"></script>
    @if (file_exists(public_path('/js/origin/' . $slug . '.js')))
        <script type="text/javascript" src="{{ asset('js/origin') }}/{{ $slug }}.js"></script>
    @endif
@endpush
