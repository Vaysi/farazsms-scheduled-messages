@extends('layouts.dashboard')
@section('title','پرداخت اشتراک')
@section('content')
    <div class="col-12">
        <div class="card shadow mb-4 text-right h-100">
            <div class="card-header">
                <h4>پرداخت اشتراک استفاده از اپلیکیشن</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-danger">
                    برای استفاده از امکانات این اپلیکیشن باید مبلغ
                    {{ money(option('amount')) }}
                    را پرداخت کنید !
                </div>
                <div class="text-center">
                    <a href="{{ route('pay') }}" class="btn btn-primary btn-icon-split">
                    <span class="icon">
                      <i class="fas fa-shopping-cart"></i>
                    </span>
                        <span class="text">پرداخت حق اشتراک</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
