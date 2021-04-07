<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{ __('Password Reset') }} - {{ config('app.brand.name') }}</title>
        @include('templates.headers')
    </head>
    <body class="hold-transition login-page">
        @include('templates.preloader')
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
                    <p class="login-box-msg">{{ __('Reset your password') }}</p>
                    <form action="{{ route('password.update') }}" method="POST" name="password_reset" id="password_reset">
                        @if (session()->has('first_login_msg'))
                            <div class="alert alert-info">
                                <i class="icon fas fa-info-circle"></i>
                                {{ __(session('first_login_msg')) }}
                            </div>
                        @endif
                        @if (count($errors) > 0)
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger">
                                    <i class="icon fas fa-ban"></i>
                                    {{ __($error) }}
                                </div>
                            @endforeach
                        @endif
                        {!! csrf_field() !!}
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="form-group mb-3">
                            @if ($email)
                                <input type="text" name="email" class="form-control" placeholder="{{ __('Email Address') }}" value="{{ $email }}" required autofocus readonly>
                            @else
                                <input type="text" name="email" class="form-control" placeholder="{{ __('Email Address') }}" value="" required autofocus>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <input type="password" name="password" class="form-control" placeholder="{{ __('Password') }}">
                        </div>
                        <div class="form-group mb-3">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="{{ __('Confirm Password') }}">
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-sm btn-block">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="{{ asset(mix('js/all.js')) }}"></script>
        <script type="text/javascript">
            var font_conf = {
                google: { families: ['Roboto:wght@100,300,400,500,700&display=swap'] },
                timeout: 4000
            };

            WebFont.load(font_conf);
        </script>
    </body>
</html>
