<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login - {{ config('app.brand.name') }}</title>
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
                <p class="login-box-msg">{{ config('app.brand.name') }}</p>
                <form action="{{ route('submit.login') }}" method="POST" name="login-form" id="login-form">
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
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </span>
                            <input type="text" name="login_id" class="form-control" placeholder="Login ID">
                        </div>
                        <div class="text-danger alert" style="text-align: left; display: none;">
                            Please Enter Login ID
                        </div>
                    </div>
                    <div class="form-group has-feedback">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-lock"></i>
                            </span>
                            <input type="password" name="password" class="form-control" placeholder="Password">
                        </div>
                        <div class="text-danger alert" style="text-align: left; display: none;">
                            Please Enter Password
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember"> Remember Me
                                </label>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat" id="submit-login" data-loading-text="Logging In...">
                                Login
                            </button>
                        </div>
                    </div>
                    <a href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                </form>
            </div>
        </div>
        <script type="text/javascript" src="{{ asset(mix('js/all.js')) }}"></script>
        <script type="text/javascript" src="{{ asset('/js/origin/login.js') }}"></script>
        <script type="text/javascript">
            var font_conf = {
                google: { families: ['Source+Sans+Pro:200,300,400,600,700'] },
                timeout: 3000
            };

            WebFont.load(font_conf);
        </script>
    </body>
</html>
