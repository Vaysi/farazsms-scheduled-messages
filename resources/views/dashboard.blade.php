@extends('layouts.dashboard')
@section('title','داشبورد')
@section('content')
    <div class="col-6 mb-4" id="events">
        <div class="row">
            <div class="col-12">
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
        </div>
    </div>
    <div class="col-6" id="contacts">
        <div class="card shadow mb-4 text-right h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fa fa-address-book align-middle"></i>
                    <span class="mr-1">مدیریت دفترچه های تلفن</span>
                </h6>
            </div>
            <div class="card-body scroller">
                <div class="list-group">
                    <div v-for="item in contacts" data-toggle="tooltip" data-trigger="focus" title="برای ثبت تغیرات دکمه Enter را بزنید" data-placement="top" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" :ref='"item_"+item.id' :data-id="item.id" @focusout="normal">
                        <div>
                            <span class="text" :ref='"text_"+item.id' @click="edit"> @{{ item.name }} </span>
                            <input type="text" style="display: none;" :ref='"input_"+item.id' class="form-control-plaintext text-white" name="name" @keyup.enter="update" :value="item.name">
                        </div>
                        <div>
                            <button class="btn btn-danger btn-circle mr-1 btn-sm" type="button" @click="destroy">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center">
                <span class="small text-gray">برای ویرایش روی نام دفترچه کلیک کنید</span>
                <button class="btn btn-primary" @click="add">افزودن</button>
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
