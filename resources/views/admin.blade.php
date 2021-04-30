<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <title>@yield('title')</title>
        @stack('meta')
        @include('admin.templates.headers')
        @stack('styles')

        @section('data')
            <script type="text/javascript">
                window.origin = {
                    data: <?php echo isset($data) ? json_encode($data) : 'false' ?>,
                    locale: <?php echo json_encode(app()->getLocale()) ?>,
                    time_zone: <?php echo json_encode(auth()->user()->time_zone) ?>,
                    translations: <?php echo json_encode(session()->get('translations')) ?>,
                };
            </script>
        @show
    </head>
    @if (session('app_settings') && isset(session('app_settings')['theme']) && session('app_settings')['theme'] == 'dark')
        <body class="hold-transition layout-top-nav layout-navbar-fixed dark-mode" data-url="{{ route('home') }}" data-base-url="{{ route('show.website') }}">
    @else
        <body class="hold-transition layout-top-nav layout-navbar-fixed" data-url="{{ route('home') }}" data-base-url="{{ route('show.website') }}">
    @endif
        <div class="wrapper">
            @include('admin.templates.navbar')
            <div class="content-wrapper">
                @yield('title_section')
                <section class="content">
                    @yield('body')
                </section>
            </div>
            @include('admin.templates.footer')
            @include('admin.templates.msgbox')
        </div>
        <script type="text/javascript" src="{{ asset(mix('js/all.js')) }}"></script>
        @if (session()->exists('msg'))
            <script type="text/javascript">
                @if (session()->has('msg'))
                    @if (session('success'))
                        notify('{!! nl2br(session("msg")) !!}', 'success');
                    @elseif (!session('success'))
                        notify('{!! nl2br(session("msg")) !!}', 'error');
                    @else
                        notify('{!! nl2br(session("msg")) !!}', 'info');
                    @endif
                @endif
            </script>
        @endif
        @stack('scripts')
        <script type="text/javascript">
            var font_conf = {
                google: { families: ['Roboto:wght@100,300,400,500,700&display=swap'] },
                timeout: 4000
            };

            WebFont.load(font_conf);
        </script>
    </body>
</html>
