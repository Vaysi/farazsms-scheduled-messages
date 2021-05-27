@extends('layouts.dashboard')
@section('title','تنظیمات حساب')
@section('content')
    <div class="col-md-6 col-12">
        <div class="card shadow mb-4 text-right h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fa fa-edit align-middle"></i>
                    <span class="mr-1">ویرایش حساب کاربری</span>
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.update') }}" method="post" id="settingsForm">
                    @csrf
                    {{ method_field('PATCH') }}
                    <div class="form-group">
                        <label for="name">نام</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') ?? user()->name }}">
                    </div>
                    <div class="form-group">
                        <label for="password">رمز عبور فعلی</label>
                        <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}">
                    </div>
                    <div class="form-group">
                        <label for="new_password">رمز عبور جدید</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" value="{{ old('new_password') }}">
                    </div>
                </form>
            </div>
            <div class="card-footer text-left">
                <button type="submit" class="btn btn-primary" form="settingsForm">ذخیره تنظیمات</button>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="card shadow mb-4 text-right h-100" id="phoneVerification">
            <div class="card-header py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fa fa-phone align-middle"></i>
                    <span class="mr-1">ویرایش/تایید شماره همراه</span>
                </h6>
                <span v-show="sent">@{{ countDown }}</span>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="phone">تلفن</label>
                    <input type="text" v-model="phone" max="11" class="form-control" id="phone" name="phone">
                </div>
                <div class="form-group" v-show="sent">
                    <label for="phone">کد فعالسازی</label>
                    <input type="text" v-model="code" max="11" class="form-control" id="code" name="code">
                </div>
            </div>
            <div class="card-footer text-left">
                <button type="button" class="btn btn-primary" @click="sendCode">ارسال کد</button>
                <button type="button" v-show="sent" class="btn btn-success mr-1" @click="verifyCode">بررسی کد</button>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script>
        let phone = '{{ user()->phone }}';
    </script>
@stop

