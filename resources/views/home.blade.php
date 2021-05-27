@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-right">
                <div class="card-header">@lang("Dashboard")</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @lang('Successfully Logged into The Website!')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
