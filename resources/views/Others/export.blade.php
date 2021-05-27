@extends('layouts.dashboard')
@section('title','خروجی از دفترچه تلفن ها')
@section('content')
    <div class="col-12">
        <div class="card shadow mb-4 text-right h-100">
            <div class="card-header">
                <h4>تنظیمات سایت</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('export.post') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="id">دفترچه تلفن</label>
                        @php
                            $contacts = App\Contact::with('numbers')->get()->sortBy(function($contact)
                            {
                                return $contact->numbers->count();
                            });
                        @endphp
                        <select name="id" id="id" class="form-control">
                            @foreach($contacts as $contact)
                                <option value="{{ $contact->id }}">
                                    {{ $contact->name }}
                                    ({{ $contact->numbers->count() }} شماره)

                                    ({{ $contact->user->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary w-100">دانلود در قالب اکسل</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
