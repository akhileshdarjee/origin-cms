<!DOCTYPE html>
<html lang="en">
    <head>
        <title>@yield('title')</title>
        @stack('meta')
        @include('templates.headers')
        @stack('styles')

        @section('data')
            <script type="text/javascript">
                window.origin = {
                    data: <?php echo isset($data) ? json_encode($data) : "false" ?>,
                };
            </script>
        @show
    </head>
    <body data-url="{{ route('home') }}" data-base-url="{{ route('show.website') }}" class="hold-transition {{ session('app_settings')['theme'] ?? 'skin-blue' }} fixed sidebar-mini{{ (session('app_settings')['display_type'] == "cozy") ? ' sidebar-collapse' : '' }}">
        <div class="wrapper">
            @include('templates.navbar')
            @include('templates.vertical_nav')
            <div class="content-wrapper">
                @yield('title_section')
                <section class="content">
                    @yield('body')
                </section>
            </div>
            @include('templates.footer')
            @include('templates.msgbox')
        </div>
        <script type="text/javascript" src="{{ asset(mix('js/all.js')) }}"></script>
        @if (session()->exists('msg'))
            <script type="text/javascript">
                @if (session()->has('msg'))
                    @if (session('success'))
                        notify('{!! nl2br(session("msg")) !!}');
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
                google: { families: ['Source+Sans+Pro:200,300,400,600,700'] },
                timeout: 3000
            };

            WebFont.load(font_conf);
        </script>
    </body>
</html>
