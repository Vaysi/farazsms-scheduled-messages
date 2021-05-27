@extends('layouts.dashboard')
@section('title','کاربران سایت')
@section('content')
    <div class="col-12">
        <div class="card shadow mb-4 text-right h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fa fa-users align-middle"></i>
                    <span class="mr-1">کاربران وبسایت</span>
                </h6>
            </div>
            <div class="card-body">
                @php
                    $users = App\User::paginate(100);
                @endphp
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>نام</th>
                            <th>شماره همراه</th>
                            <th>ایمیل</th>
                            <th>شماره تایید شده ؟</th>
                            <th>حق اشتراک پرداخت کرده ؟</th>
                            <th>وضعیت</th>
                            <th>تاریخ ثبت نام</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>نام</th>
                            <th>شماره همراه</th>
                            <th>ایمیل</th>
                            <th>شماره تایید شده ؟</th>
                            <th>حق اشتراک پرداخت کرده ؟</th>
                            <th>وضعیت</th>
                            <th>تاریخ ثبت نام</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        <tr>
                            @foreach($users as $user)
                                <th>{{ $user->name }}</th>
                                <th>{{ $user->phone }}</th>
                                <th>{{ $user->email }}</th>
                                <th>{!! $user->phone_verified_at ? '<span class="badge badge-success">بله</span>' : '<span class="badge badge-danger">خیر</span>'  !!}</th>
                                <th>{!! $user->paid ? '<span class="badge badge-success">بله</span>' : '<span class="badge badge-danger">خیر</span>'  !!}</th>
                                <th>{{ userStatus($user) }}</th>
                                <th>{{ jdate($user->created_at)->format('Y/m/d') }}</th>
                            @endforeach
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-left">
                <nav>
                    {!! $users->render() !!}
                </nav>
            </div>
        </div>
    </div>
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}">
@stop
@section('js')
    <!-- Page level plugins -->
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                language:{
                    "sEmptyTable":     "هیچ داده‌ای در جدول وجود ندارد",
                    "sInfo":           "نمایش _START_ تا _END_ از _TOTAL_ ردیف",
                    "sInfoEmpty":      "نمایش 0 تا 0 از 0 ردیف",
                    "sInfoFiltered":   "(فیلتر شده از _MAX_ ردیف)",
                    "sInfoPostFix":    "",
                    "sInfoThousands":  ",",
                    "sLengthMenu":     "نمایش _MENU_ ردیف",
                    "sLoadingRecords": "در حال بارگزاری...",
                    "sProcessing":     "در حال پردازش...",
                    "sSearch":         "جستجو:",
                    "sZeroRecords":    "رکوردی با این مشخصات پیدا نشد",
                    "oPaginate": {
                        "sFirst":    "برگه‌ی نخست",
                        "sLast":     "برگه‌ی آخر",
                        "sNext":     "بعدی",
                        "sPrevious": "قبلی"
                    },
                    "oAria": {
                        "sSortAscending":  ": فعال سازی نمایش به صورت صعودی",
                        "sSortDescending": ": فعال سازی نمایش به صورت نزولی"
                    }
                }
            });
        });
    </script>
@stop

