<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{ __('Password Reset') }} - {{ config('app.brand.name') }}</title>
        @include('templates.headers')
    </head>
    <body class="hold-transition login-page">
        @if (session()->has('first_login_msg'))
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="alert alert-info text-center">
                        {{ __(session('first_login_msg')) }}
                    </div>
                </div>
            </div>
        @endif
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
            <div class="login-box-body">
                <p class="login-box-msg">{{ __('Password Reset') }}</p>
                <form action="{{ route('password.update') }}" method="POST" name="password_reset" id="password_reset">
                    @if (count($errors) > 0)
                        @foreach ($errors->all() as $error)
                            <div class="block">
                                <div class="alert red-bg">
                                    <button type="button" class="close" data-dismiss="alert">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <strong>
                                        <i class="fa fa-exclamation-triangle fa-lg"></i>
                                        {{ __($error) }}
                                    </strong>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    {!! csrf_field() !!}
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="form-group">
                        @if ($email)
                            <input type="text" name="email" class="form-control" placeholder="{{ __('Email Address') }}" value="{{ $email or old('email') }}" required autofocus readonly>
                        @else
                            <input type="text" name="email" class="form-control" placeholder="{{ __('Email Address') }}" value="{{ $email or old('email') }}" required autofocus>
                        @endif
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="{{ __('Password') }}">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password_confirmation" class="form-control" placeholder="{{ __('Confirm Password') }}">
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">
                                {{ __('Reset Password') }}
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
