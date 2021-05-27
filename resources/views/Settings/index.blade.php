@extends('layouts.dashboard')
@section('title','تنظیمات سایت')
@section('content')
    <div class="col-12">
        <div class="card shadow mb-4 text-right h-100">
            <div class="card-header">
                <h4>تنظیمات سایت</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active" id="v-pills-payment-tab" data-toggle="pill" href="#payment" role="tab" aria-controls="payment" aria-selected="true">درگاه پرداخت</a>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="payment" role="tabpanel" aria-labelledby="v-pills-payment-tab">
                                <form action="{{ route('site.update') }}" method="post">
                                    @csrf
                                    <h5 class="mb-2">سرویس درگاه پرداخت</h5>
                                    <hr class="mt-3">
                                    <div class="form-group">
                                        <label for="should_pay">پرداخت اجباری برای کاربران جدید</label>
                                        <select name="should_pay" id="should_pay" class="form-control">
                                            <option value="1">خیر</option>
                                            <option value="1" {{ option('should_pay') == '1' ? 'selected': '' }}>بله</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="payment_driver">درگاه پرداخت</label>
                                        <select name="payment_driver" id="payment_driver" class="form-control">
                                            <option value="zarinpal">زرین پال</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="payment_merchant">مرچنت آی دی</label>
                                        <input type="text" class="ltr form-control" name="payment_merchant" id="payment_merchant" value="{{ old('payment_merchant') ?? option('payment_merchant') ?? ''  }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="amount">مبلغ قابل پرداخت</label>
                                        <input type="number" min="1000" step="1000" class="ltr form-control" name="amount" id="amount" value="{{ old('amount') ?? option('amount') ?? ''  }}">
                                    </div>
                                    <hr class="mb-2">
                                    <div class="form-group">
                                        <button class="btn btn-primary w-100" type="submit">ذخیره</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-left">
                <nav class="d-inline-block">
                </nav>
            </div>
        </div>
    </div>
@endsection
