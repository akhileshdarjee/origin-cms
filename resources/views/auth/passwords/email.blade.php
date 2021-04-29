<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{ __('Forgot Password') }} - {{ config('app.brand.name') }}</title>
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
                    <p class="login-box-msg">{{ __('Forgot Password') }}</p>
                    <form action="{{ route('password.email') }}" method="POST" name="password_email" id="password_email">
                        @if (session()->has('status'))
                            <div class="alert alert-success">
                                <i class="icon fas fa-check"></i>
                                {{ __(session('status')) }}
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
                        <div class="form-group mb-3">
                            <input type="email" class="form-control" name="email" placeholder="{{ __('Email Address') }}" />
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-sm btn-block">
                                    {{ __('Send Password Reset Link') }}
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
