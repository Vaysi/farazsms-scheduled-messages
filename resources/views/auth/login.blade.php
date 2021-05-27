@extends('layouts.auth')
@section('title','ورود')
@section('content')
<div class="card o-hidden border-0 shadow-lg my-5">
    <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
            <div class="col-lg-6">
                <div class="p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">خوش آمدید !</h1>
                    </div>
                    <form method="POST" action="{{ route('login') }}" class="user">
                        @csrf

                        <div class="form-group">
                            <input id="phone" type="text" placeholder="شماره همراه" class="form-control form-control-user @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus>

                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input id="password" type="password" placeholder="رمز عبور " class="form-control form-control-user @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox small text-right">
                                <input type="checkbox" class="custom-control-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="remember">مرا به خاطر داشته باش !</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-user btn-block">
                            ورود
                        </button>
                        <hr>
                        <a href="{{ route('register') }}" class="btn btn-google btn-user btn-block">
                            <i class="fa fa-plus fa-fw align-middle"></i>
                             ثبت نام
                        </a>
                    </form>
                    <hr>
                    <div class="text-center">
                        <a class="small" href="{{ route('password.request') }}">فراموشی رمز عبور</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
