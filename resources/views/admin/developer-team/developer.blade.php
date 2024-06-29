@extends('layouts.modern-layout.master')

@section('title')
    Developer Table
    {{ $title }}
@endsection

@push('css')

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Developer</h3>
        @endslot
        <li class="breadcrumb-item">Data</li>
        <li class="breadcrumb-item active">User</li>
    @endcomponent

    <div class="container-fluid user-card">
        <div class="row">
            @foreach($users as $user)
                <div class="col-md-6 col-lg-6 col-xl-4 box-col-6">
                    <div class="card custom-card">
                        <div class="card-profile">
                            <img class="rounded-circle" src="{{ $user->avatar }}">
                        </div>
                        <ul class="card-social">
                            <li><a href="javascript:void(0)"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="javascript:void(0)"><i class="fa fa-google-plus"></i></a></li>
                            <li><a href="javascript:void(0)"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="javascript:void(0)"><i class="fa fa-instagram"></i></a></li>
                            <li><a href="javascript:void(0)"><i class="fa fa-rss"></i></a></li>
                        </ul>
                        <div class="text-center profile-details">
                            <a href="#">
                                <h4>{{ $user->name }}</h4>
                            </a>
                            <h6>{{ $user->username }}</h6>
                        </div>
                        <!-- <div class="card-footer row">
                            <div class="col-4 col-sm-4">
                                <h6>Follower</h6>
                                <h3 class="counter">{{ $user->followers }}</h3>
                            </div>
                            <div class="col-4 col-sm-4">
                                <h6>Following</h6>
                                <h3><span class="counter">{{ $user->following }}</span>K</h3>
                            </div>
                            <div class="col-4 col-sm-4">
                                <h6>Total Post</h6>
                                <h3><span class="counter">{{ $user->total_posts }}</span>M</h3>
                            </div>
                        </div> -->
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @push('scripts')
    <script>
        if (window.history && window.history.pushState) {
            window.history.pushState(null, null, window.location.href);
            window.onpopstate = function() {
                window.history.pushState(null, null, window.location.href);
            };
        }
    </script>

    @endpush
@endsection
