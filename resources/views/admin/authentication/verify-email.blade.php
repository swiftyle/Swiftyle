@extends('admin.authentication.master')

@section('title')
    Verify Email OTP
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
                        <form class="theme-form login-form" action="{{ route('verify-otp') }}" method="POST" style="border-radius:20px">
                            @csrf
                            <h4>Verify Email OTP</h4>
                            <h6>Enter the OTP sent to your email</h6>
                            <div class="form-group">
                                <label>OTP</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="icon-lock"></i></span>
                                    <input class="form-control"  type="text" name="otp" required=""
                                        placeholder="Enter OTP" />
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-full-width" type="submit">Verify OTP</button>
                            </div>
                            <!-- <p>Didn't receive the OTP?<a class="ms-2" href="{{ route('resend.email.otp') }}">Resend OTP</a></p> -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
