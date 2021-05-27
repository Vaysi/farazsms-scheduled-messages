@extends('layouts.dashboard')
@section('title','رویداد ها')
@section('content')
    <div class="col-12 mb-4" id="events">
        <div class="row">
            <div class="col-md-6 col-12">
                <div class="card shadow mb-4 text-right h-100">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fa fa-calendar align-middle"></i>
                            <span class="mr-1">مدیریت رویداد ها</span>
                        </h6>
                    </div>
                    <div class="card-body scroller">
                        <div class="list-group">
                            <div v-for="item in events" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" :ref='"item_"+item.id' :data-id="item.id">
                                <div>
                                    <span class="text" :ref='"text_"+item.id'>برای دفترچه
                                        <b> @{{ item.contact.name }}</b>
                                        <span class="text-info d-block">
                                            هر
                                            @{{ (item.year ? item.year + ' سال و' : ' ') + (item.month ? item.month + ' ماه و' : ' ') + (item.day ? item.day + ' روز و' : ' ') + (item.hour ? item.hour + ' ساعت و' : ' ') + (item.minute ? item.minute + ' دقیقه و' : ' ') }}
                                        </span>
                                    </span>
                                </div>
                                <div>
                                    <button data-toggle="tooltip" data-placement="top" :data-title="status(item)" class="btn-warning btn btn-circle mr-1 btn-sm" type="button">
                                        <i class="fa fa-clock"></i>
                                    </button>
                                    <button class="btn btn-danger btn-circle mr-1 btn-sm" type="button" @click="destroy">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <span class="small text-gray">فقط امکان حذف رویداد های اجرا نشده رو دارید !</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="card shadow mb-4 text-right h-100">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fa fa-calendar-plus align-middle"></i>
                            <span class="mr-1">افزودن رویداد به دفترچه</span>
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>تاریخ</label>
                            <div class="input-group ltr">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">دیگر</span>
                                </div>
                                <input type="number" v-model="year"  min="0" placeholder="سال" class="form-control rtl">
                                <input type="number" v-model="month"  min="0" placeholder="ماه" class="form-control rtl">
                                <input type="number" v-model="day"  min="0" placeholder="روز" class="form-control rtl">
                                <input type="number" v-model="hour"  min="0" placeholder="ساعت" class="form-control rtl">
                                <input type="number" v-model="minute" min="0" placeholder="دقیقه" class="form-control rtl">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="contact_id">دفترچه</label>
                            <select name="contact_id" id="contact_id" class="form-control" v-model="contact_id">
                                <option v-for="item in contacts" :value="item.id">@{{ item.name }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="from">خط ارسال کننده</label>
                            <select name="from" id="from" class="form-control" v-model="from">
                                @if(user()->api_notify_sender)
                                    <option value="{{ user()->api_sim_sender }}">خط اطلاع رسانی ({{user()->api_notify_sender}})</option>
                                @endif
                                @if(user()->api_ads_sender)
                                    <option value="{{ user()->api_sim_sender }}">خط تبلیغاتی ({{user()->api_ads_sender}})</option>
                                @endif
                                @if(user()->api_sim_sender)
                                    <option value="{{ user()->api_sim_sender }}">خط سیم کارت ({{user()->api_sim_sender}})</option>
                                @endif
                                <option value="+98100020400">خط پیشفرض سیستم (98100020400)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="msg">متن پیام</label>
                            <textarea name="msg" id="msg" class="form-control" v-model="msg"></textarea>
                        </div>
                    </div>
                    <div class="card-footer text-left">
                        <button type="button" class="btn btn-primary" :disabled="working" @click="addEvent">افزودن</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    @php
        $mycontacts = user()->contacts()->pluck('id')->toArray();
    @endphp
    <script>
        let eventsList = JSON.parse('{!! App\Event::whereIn('contact_id',$mycontacts)->with('contact')->latest()->get()->toJson() !!}');
        let contactsList = JSON.parse('{!! user()->contacts()->select(['name','id'])->get()->toJson() !!}');
    </script>
@stop

