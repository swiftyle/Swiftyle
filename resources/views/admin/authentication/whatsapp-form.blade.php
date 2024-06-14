@extends('admin.authentication.master')

@section('title')
    WhatsApp Number
    {{ $title }}
@endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweetalert2.css') }}">
    <style>
        .btn-full-width {
            width: 100%;
        }
    </style>
@endpush

@section('content')
    <section>
        <div class="container-fluid p-0">
            <img class="bg-img-cover bg-center" src="{{ asset('assets/images/login/sign-up.png') }}" />
            <div class="row m-0">
                <div class="col-12 p-0">
                    <div class="login-card">
                        <form class="theme-form login-form" action="{{ route('whatsapp.number') }}" method="POST" style="border-radius:20px">
                            @csrf
                            <h4>Enter WhatsApp Number</h4>
                            <h6>Enter your WhatsApp number to receive OTP</h6>
                            <div class="form-group">
                                <label>WhatsApp Number</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="icon-phone"></i></span>
                                    <input class="form-control" type="text" name="whatsapp_number" required=""
                                        placeholder="+1234567890" />
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-full-width" type="submit">Send OTP</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
