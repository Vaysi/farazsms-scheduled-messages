@extends('layouts.dashboard')
@section('title','دفترچه تلفن')
@section('content')
    <div class="col-12" id="contacts">
        <div class="row">
            <div class="col-md-6 col-12">
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
                                    <button class="btn btn-warning btn-circle mr-1 btn-sm" type="button" @click="show" data-toggle="tooltip" data-placement="top" title="نمایش شماره ها">
                                        <i class="fa fa-eye"></i>
                                    </button>
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
            <div class="col-md-6 col-12">
                <div class="card shadow mb-4 text-right h-100">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fa fa-phone align-middle"></i>
                            <span class="mr-1">افزودن رویداد</span>
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="phone">تلفن</label>
                            <input type="text" v-model="phone" max="11" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="form-group">
                            <label for="phone">دفترچه</label>
                            <select name="contacts" id="contacts" class="form-control" v-model="targetId">
                                <option v-for="item in contacts" :value="item.id">@{{ item.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer text-left">
                        <button type="button" class="btn btn-primary" @click="addPhone">افزودن</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title float-right" id="exampleModalLongTitle">نمایش شماره ها</h5>
                        <button type="button" class="close float-left mr-auto ml-0" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-right">
                        <ul>
                            <li v-for="number in numbers">@{{ number }}</li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script>
        let contactsList = JSON.parse('{!! user()->contacts()->select(['name','id'])->get()->toJson() !!}');
    </script>
@stop

