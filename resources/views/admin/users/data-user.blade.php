@extends('layouts.modern-layout.master')

@section('title')
    User Data Table
    {{ $title }}
@endsection

@push('css')

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>User Data</h3>
        @endslot
        <li class="breadcrumb-item">Data</li>
        <li class="breadcrumb-item active">User</li>
    @endcomponent

    <div class="container-fluid">

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordernone">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Gender</th>
                                <th>Address</th>
                                <th>Account Created</th>
                                <th>Status</th>
                                <th>
                                    <div class="setting-list">
                                        <ul class="list-unstyled setting-option">
                                            <li>
                                                <div class="setting-primary"><i class="icon-settings"> </i></div>
                                            </li>
                                            <li><i class="view-html fa fa-code font-primary"></i></li>
                                            <li><i class="icofont icofont-maximize full-card font-primary"></i></li>
                                            <li><i class="icofont icofont-minus minimize-card font-primary"></i></li>
                                            <li><i class="icofont icofont-refresh reload-card font-primary"></i></li>
                                            <li><i class="icofont icofont-error close-card font-primary"></i></li>
                                        </ul>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <div class="media">
                                            <img class="img-fluid rounded-circle" src="{{ $user->avatar }}"
                                                alt="" width="30px" height="30px">
                                            <div class="media-body">
                                                <span>{{ $user->name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p>{{ $user->username }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $user->email }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $user->role }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $user->gender }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $user->address }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $user->created_at->format('Y-m-d') }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $user->status }}</p>
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- <div class="pagination">
                    {{ $users->links() }}
                </div> --}}
            </div>
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
