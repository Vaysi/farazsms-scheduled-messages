@extends('layouts.auth')
@section('title','فراموشی رمز عبور')
@section('content')
    <div class="card o-hidden border-0 shadow-lg my-5" id="resetPass">
        <div class="card-body p-0">
            <form class="user" @submit="false">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
                    <div class="col-lg-6">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">خوش آمدید !</h1>
                            </div>
                            <span v-show="sent" class="my-2 text-center">
                                <span class="badge badge-info">@{{ countDown }}</span>
                                <span class="small">ثانیه صبر کنید و در صورت عدم دریافت پیامک دوباره تلاش کنید</span>
                            </span>
                            <div class="form-group">
                                <input v-model="phone" @key.enter="sendCode" id="phone" type="text" placeholder="تلفن همراه" class="form-control form-control-user" name="phone" autocomplete="phone" autofocus>
                            </div>
                            <div class="form-group">
                                <input v-model="code" @key.enter="verifyCode" id="code" v-show="sent" type="text" placeholder="کد فعالسازی" class="form-control form-control-user" name="code">
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-primary btn-user btn-block" @click="sendCode">
                                    ارسال کد فعالسازی
                                </button>
                                <button type="button" class="btn btn-info btn-user btn-block mt-0 mr-1" v-show="sent" @click="verifyCode">
                                    بررسی کد
                                </button>
                            </div>
                            <hr>
                            <a href="{{ route('register') }}" class="btn btn-danger btn-user btn-block">
                                <i class="fa fa-plus fa-fw align-middle"></i>
                                ثبت نام
                            </a>

                        <hr>
                        <div class="text-center">
                            <a class="small" href="{{ route('login') }}">ورود</a>
                        </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop
