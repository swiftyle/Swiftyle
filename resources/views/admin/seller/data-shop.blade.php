@extends('layouts.modern-layout.master')

@section('title')
    Data Shop Table
    {{ $title }}
@endsection

@push('css')

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Data Shop</h3>
        @endslot
        <li class="breadcrumb-item">Shop</li>
        <li class="breadcrumb-item active">Data</li>
    @endcomponent

    <div class="container-fluid">

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordernone">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Seller</th>
                                <th>Rating</th>
                                <th>Email</th>
                                <th>Addres</th>
                                <th>Phone</th>
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
                            @foreach ($shops as $shop)
                                <tr>
                                    <td>
                                        <div class="media">
                                            <img class="img-fluid rounded-circle" src="{{ $shop->logo }}"
                                                alt="" width="30px" height="30px">
                                            <div class="media-body">
                                                <span>{{ $shop->name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p>{{ $shop->user->name }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $shop->rating }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $shop->email }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $shop->address }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $shop->phone }}</p>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    {{ $shops->links() }}
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
