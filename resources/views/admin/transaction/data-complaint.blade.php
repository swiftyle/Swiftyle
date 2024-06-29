@extends('layouts.modern-layout.master')

@section('title')
    Complain User Table
    {{ $title }}
@endsection

@push('css')

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Complain User</h3>
        @endslot
        <li class="breadcrumb-item">User</li>
        <li class="breadcrumb-item active">Complain</li>
    @endcomponent

    <div class="container-fluid">

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordernone">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Order ID</th>
                                <th>Description</th>
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
                            @foreach ($complaints as $complaint)
                                <tr>
                                    <td>
                                        <div class="media">
                                            <img class="img-fluid rounded-circle"
                                                src="{{ optional($complaint->user)->avatar }}" alt="" width="30px"
                                                height="30px">
                                            <div class="media-body">
                                                <span>{{ optional($complaint->user)->name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p>{{ $complaint->order_id }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $complaint->description }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $complaint->status }}</p>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
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
