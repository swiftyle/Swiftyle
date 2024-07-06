@extends('layouts.modern-layout.master')

@section('title')
    Deleted Users
    {{ $title }}
@endsection

@push('css')

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Deleted Users</h3>
        @endslot
        <li class="breadcrumb-item">Users</li>
        <li class="breadcrumb-item active">Deleted Data</li>
    @endcomponent

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordernone">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Gender</th>
                            <th>Address</th>
                            <th>Deleted At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deletedUsers as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role }}</td>
                                <td>{{ $user->gender }}</td>
                                <td>{{ $user->address }}</td>
                                <td>{{ $user->deleted_at->format('Y-m-d') }}</td>
                                <td>
                                    <form action="{{ route('users.restore', $user->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-success">Restore</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $deletedUsers->links() }}
            </div>
        </div>
    </div>
@endsection
