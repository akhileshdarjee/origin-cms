<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Forgot Password - {{ config('app.brand.name') }}</title>
        @include('templates.headers')
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="{{ route('show.website') }}" title="{{ config('app.brand.name') }}">
                    @if (file_exists('img/logo.svg'))
                        <img src="{{ asset('img/logo.svg') }}" alt="{{ config('app.brand.name') }}" width="100" height="100">
                    @else
                        <b>{{ config('app.brand.abbr') }}</b>
                    @endif
                </a>
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg">Forgot Password</p>
                <form action="{{ route('password.email') }}" method="POST" name="password_email" id="password_email">
                    @if (session()->has('status'))
                        <div class="block">
                            <div class="alert alert-success">
                                <strong>
                                    <i class="fa fa-check fa-lg"></i>
                                    {{ session('status') }}
                                </strong>
                            </div>
                        </div>
                    @endif
                    @if (count($errors) > 0)
                        @foreach ($errors->all() as $error)
                            <div class="block">
                                <div class="alert alert-danger">
                                    <strong>
                                        <i class="fa fa-exclamation-triangle fa-lg"></i>
                                        {{ $error }}
                                    </strong>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    {!! csrf_field() !!}
                    <div class="form-group has-feedback">
                        <input type="text" class="form-control" name="email" placeholder="Email Address" />
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">
                                Send Password Reset Link
                            </button>
                        </div>
                    </div>
                </form>
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
