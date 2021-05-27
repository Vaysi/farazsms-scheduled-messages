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
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                یک لینک تازه به شماره همراه شما ارسال شد
                            </div>
                        @endif

                        قبل از ادامه تلفن همراه خود را برای لینک بررسی کنید !
                        اگر هیچ پیامکی دریافت نکردید از طریق دکمه زیر مجددا درخواست کنید !
                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline">درخواست مجدد</button>.
                        </form>
                        <hr>
                        <div class="text-center">
                            <a class="small" href="{{ route('login') }}">ورود</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
