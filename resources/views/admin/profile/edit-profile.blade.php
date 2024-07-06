@extends('layouts.modern-layout.master')

@section('title')
Edit Profile
@endsection

@push('css')
@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Edit Profile</h3>
        @endslot
        <li class="breadcrumb-item">Profile</li>
        <li class="breadcrumb-item active">Edit Profile</li>
    @endcomponent
    
    <div class="container-fluid">
        <div class="edit-profile">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h4 class="card-title mb-0">Edit Profile</h4>
                            <div class="card-options">
                                <a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                <a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input class="form-control" type="text" name="name" placeholder="First Name" value="{{ old('name', $user->name) }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Username</label>
                                            <input class="form-control" type="text" name="username" placeholder="Username" value="{{ old('username', $user->username) }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email address</label>
                                            <input class="form-control" type="email" name="email" placeholder="Email" value="{{ old('email', $user->email) }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Phone</label>
                                            <input class="form-control" type="text" name="phone" placeholder="Phone" value="{{ old('phone', $user->phone) }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Gender</label>
                                            <input class="form-control" type="text" name="gender" placeholder="Gender" value="{{ old('gender', $user->gender) }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Avatar</label>
                                            <input class="form-control" type="file" name="avatar">
                                            @if ($user->avatar)
                                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Current Avatar" style="max-width: 100px; max-height: 100px;">
                                            @else
                                                <p>No avatar uploaded</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer text-end">
                                    <button class="btn btn-primary" type="submit">Update Profile</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    @endpush

@endsection
