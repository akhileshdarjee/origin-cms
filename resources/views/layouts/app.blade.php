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
    <body class="hold-transition layout-top-nav" data-url="{{ route('home') }}" data-base-url="{{ route('show.website') }}">
        @include('templates.preloader')
        <div class="wrapper">
            @include('templates.navbar')
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
                google: { families: ['Roboto:wght@100,300,400,500,700&display=swap'] },
                timeout: 4000
            };

            WebFont.load(font_conf);
        </script>
    </body>
</html>
