@extends('layouts.dashboard')
@section('title','تنظیمات وب سرویس')
@section('content')
    <div class="col-12">
        <div class="card shadow mb-4 text-right h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fa fa-sms align-middle"></i>
                    <span class="mr-1">ویرایش تنظیمات وب سرویس</span>
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('webservice.update') }}" method="post" id="webserviceForm">
                    @csrf
                    {{ method_field('PATCH') }}
                    <div class="form-row">
                        <div class="form-group col-6">
                            <label for="api_username">نام کاربری</label>
                            <input type="text" class="form-control" id="api_username" name="api_username" value="{{ old('api_username') ?? user()->api_username }}">
                        </div>
                        <div class="form-group col-6">
                            <label for="api_password">رمزعبور</label>
                            <input type="text" class="form-control" id="api_password" name="api_password" value="{{ old('api_password') ?? user()->api_password }}">
                        </div>
                        <div class="form-group col-4">
                            <label for="api_ads_sender">خط تبلیغاتی</label>
                            <input type="text" class="form-control ltr" id="api_ads_sender" name="api_ads_sender" value="{{ old('api_ads_sender') ?? user()->api_ads_sender }}">
                        </div>
                        <div class="form-group col-4">
                            <label for="api_notify_sender">خط اطلاع رسانی</label>
                            <input type="text" class="form-control ltr" id="api_notify_sender" name="api_notify_sender" value="{{ old('api_notify_sender') ?? user()->api_notify_sender }}">
                        </div>
                        <div class="form-group col-4">
                            <label for="api_sim_sender">خط سیم کارت</label>
                            <input type="text" class="form-control ltr" id="api_sim_sender" name="api_sim_sender" value="{{ old('api_sim_sender') ?? user()->api_sim_sender }}">
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer text-left">
                <button type="submit" class="btn btn-primary" form="webserviceForm">ذخیره تنظیمات</button>
            </div>
        </div>
    </div>
@stop

