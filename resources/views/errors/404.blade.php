<!DOCTYPE html>
<html lang="en">
    <head>
        <title>404 - Not Found - {{ config('app.brand.name') }}</title>
        @include('templates.headers')
    </head>
    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <div class="col-md-12">
                    <div class="col-middle">
                        <div class="text-center text-center">
                            <h1 class="error-number">404</h1>
                            <h2>Sorry but we couldn't find this page</h2>
                            <p>Try checking the URL for error, then hit the refresh button on your browser or try found something else in our app.</p>
                            <div class="mid_center">
                                <a href="{{ route('home') }}" class="btn btn-success">
                                    Back to Home
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="{{ asset(mix('js/all.js')) }}"></script>
        <script type="text/javascript">
            var font_conf = {
                google: { families: ['Source+Sans+Pro:200,300,400,600,700'] },
                timeout: 3000
            };

            WebFont.load(font_conf);
        </script>
    </body>
</html>
