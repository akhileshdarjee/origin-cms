<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{ __('Login') }} - {{ config('app.brand.name') }}</title>
        @include('admin.templates.headers')
    </head>
    <body class="hold-transition login-page">
        @include('admin.templates.preloader')
        <div class="login-box">
            <div class="card card-outline card-primary elevation-2">
                <div class="card-header text-center">
                    <a href="{{ route('show.website') }}" title="{{ config('app.brand.name') }}" class="h1">
                        @if (file_exists('img/logo.svg'))
                            <img src="{{ asset('img/logo.svg') }}" alt="{{ config('app.brand.name') }}" width="100" height="100">
                        @else
                            <b>{{ config('app.brand.abbr') }}</b>
                        @endif
                    </a>
                </div>
                <div class="card-body">
                    <p class="login-box-msg">{{ __('Login to start your session') }}</p>
                    <form action="{{ route('submit.login') }}" method="POST" name="login-form" id="login-form">
                        @if (session('message'))
                            <div class="alert alert-danger">
                                <i class="icon fas fa-ban"></i>{{ __(session('message')) }}
                            </div>
                        @endif
                        @if (count($errors) > 0)
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger">
                                    <i class="icon fas fa-ban"></i>{{ __($error) }}
                                </div>
                            @endforeach
                        @endif
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <input type="text" name="username" id="username" class="form-control" placeholder="{{ __('Username') }}">
                            <span class="invalid-feedback">
                                {{ __('Please Enter Username') }}
                            </span>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" id="password" class="form-control" placeholder="{{ __('Password') }}">
                            <span class="invalid-feedback">
                                {{ __('Please Enter Password') }}
                            </span>
                        </div>
                        <div class="row vertical-center mb-3">
                            <div class="col-7">
                                <div class="custom-control custom-checkbox d-flex vertical-center">
                                    <input type="checkbox" name="remember" id="remember-me" class="custom-control-input">
                                    <label class="custom-control-label remember-me" for="remember-me"> {{ __('Remember Me') }}</label>
                                </div>
                            </div>
                            <div class="col-5 text-right">
                                <a href="{{ route('password.request') }}" class="text-sm">
                                    {{ __('Forgot password') }}?
                                </a>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm btn-block" id="submit-login">
                            {{ __('Login') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="{{ asset(mix('js/all.js')) }}"></script>
        <script type="text/javascript" src="{{ asset('js/origin/login.js') }}"></script>
        <script type="text/javascript">
            var font_conf = {
                google: { families: ['Roboto:wght@100,300,400,500,700&display=swap'] },
                timeout: 4000
            };

            WebFont.load(font_conf);
        </script>
    </body>
</html>
