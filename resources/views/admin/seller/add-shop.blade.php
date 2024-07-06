@extends('layouts.modern-layout.master')

@section('title')
    Add Shop
    {{ $title }}
@endsection

@push('css')

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Add Shop</h3>
        @endslot
        <li class="breadcrumb-item">Shop</li>
        <li class="breadcrumb-item active">Data</li>
    @endcomponent

    <div class="container-fluid">

        <div class="card">
            <div class="card-body">
                <div class="d-flex mb-3">
                
					<div class="card-body">
						<form>
							<div class="row g-3">
								<div class="col-md-4">
									<label class="form-label" for="validationDefault01">ID User</label>
									<input class="form-control" id="validationDefault01" type="text" placeholder="User Name" required="">
								</div>
								<div class="col-md-4">
									<label class="form-label" for="validationDefault02">Shop Name</label>
									<input class="form-control" id="validationDefault02" type="text" placeholder="Enter Your Shop Name" required="">
								</div>
                                <div class="col-md-4">
									<label class="form-label" for="validationDefault03">Shop Profile</label>
									<input class="form-control" id="validationDefault03" type="file">
								</div>
							</div>
							<div class="row g-3">
                                <div class="col-md-6 mb-3">
									<label class="form-label" for="validationDefaultEmail">Email</label>
									<div class="input-group">
										<span class="input-group-text" id="inputGroupPrepend2">@</span>
										<input class="form-control" id="validationDefaultEmail" type="text" placeholder="Enter Your Email" aria-describedby="inputGroupPrepend2" required="">
									</div>
								</div>
                                <div class="col-md-6 mb-3">
									<label class="form-label" for="validationDefaultPhone">Phone</label>
									<div class="input-group">
										<span class="input-group-text" id="inputGroupPrepend2">+62</span>
										<input class="form-control" id="validationDefaultPhone" type="text" placeholder="Enter Your Phone" aria-describedby="inputGroupPrepend2" required="">
									</div>
								</div>
								<div class="col-md-12">
									<label class="form-label" for="validationDefaultAddress">Address</label>
									<textarea class="form-control" id="validationDefaultAddress" rows="3"></textarea>
								</div>
							</div>
							<div class="mb-3">
								<div class="form-check">
									<div class="checkbox p-0">
										<input class="form-check-input" id="invalidCheck2" type="checkbox" required="">
										<label class="form-check-label" for="invalidCheck2">Agree to terms and conditions</label>
									</div>
								</div>
							</div>
							<button class="btn btn-primary" type="submit">Submit form</button>
						</form>
					</div>
				</div>
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
