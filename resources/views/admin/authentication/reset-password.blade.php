@extends('admin.authentication.master')

@section('title')Forget Password
 {{ $title }}
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweetalert2.css') }}">
<style>
    .btn-full-width {
        width: 100%;
		height: 100%;
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
	                    <div class="login-main">
	                        <form class="theme-form login-form" style="border-radius:20px">
	                            <h4 class="mb-3">Reset Your Password</h4>
								
	                            <div class="form-group" id="phoneForm" style="display: none;">
	                                <label>Enter Your Mobile Number</label>
	                                <div class="row">
	                                    <div class="col-4 col-sm-3">
	                                        <input class="form-control" type="text" value="+ 62" />
	                                    </div>
	                                    <div class="col-8 col-sm-9">
	                                        <input class="form-control" type="tel" value="000-000-0000" />
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="form-group">
	                                <button class="btn btn-primary btn-full-width" type="submit">Send</button>
	                            </div>
	                            <div class="form-group">
	                                <span class="reset-password-link">If don't receive OTP? <a class="btn-link text-danger" href="javascript:void(0)">Resend</a></span>
	                            </div>
	                            <div class="form-group">
	                                <label>Enter OTP</label>
	                                <div class="row">
	                                    <div class="col">
	                                        <input class="form-control text-center opt-text" type="text" value="00" maxlength="2" />
	                                    </div>
	                                    <div class="col">
	                                        <input class="form-control text-center opt-text" type="text" value="00" maxlength="2" />
	                                    </div>
	                                    <div class="col">
	                                        <input class="form-control text-center opt-text" type="text" value="00" maxlength="2" />
	                                    </div>
	                                </div>
	                            </div>
	                            <h6>Create Your Password</h6>
	                            <div class="form-group">
	                                <label>New Password</label>
	                                <div class="input-group">
										<span class="input-group-text"><i class="icon-lock"></i></span>
										<input class="form-control" id="password" type="password" name="password"
											required="" placeholder="*********" />
										<div class="show-hide"><span onclick="togglePassword()"></span></div>
									</div>
	                            </div>
	                            <div class="form-group">
	                                <label>Retype Password</label>
									<div class="input-group">
										<span class="input-group-text"><i class="icon-lock"></i></span>
										<input class="form-control" id="password" type="password" name="password"
											required="" placeholder="*********" />
										<div class="show-hide"><span onclick="togglePassword()"></span></div>
									</div>
	                            </div>
	                            <div class="form-group">
	                                <div class="checkbox">
	                                    <input id="checkbox1" type="checkbox" />
	                                    <label class="text-muted" for="checkbox1">Remember password</label>
	                                </div>
	                            </div>
	                            <div class="form-group">
	                                <button class="btn btn-primary btn-full-width" type="submit">Done</button>
	                            </div>
	                            <p>Already have an password?<a class="ms-2" href="{{ route('login') }}">Sign in</a></p>
	                        </form>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</section>

    @push('scripts')
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			// Mendapatkan referensi ke elemen form dan tombol toggle
			const emailForm = document.getElementById('emailForm');
			const phoneForm = document.getElementById('phoneForm');
			const resetWithEmailButton = document.getElementById('resetWithEmail');
			const resetWithPhoneButton = document.getElementById('resetWithPhone');

			// Menambahkan event listener untuk tombol toggle email
			resetWithEmailButton.addEventListener('click', function() {
				emailForm.style.display = 'block';
				phoneForm.style.display = 'none';
				// Menambahkan kelas 'active' ke tombol email dan menghapusnya dari tombol telepon
				resetWithEmailButton.classList.add('active');
				resetWithPhoneButton.classList.remove('active');

				// Menyesuaikan kelas tombol
				resetWithEmailButton.classList.add('btn-primary');
				resetWithPhoneButton.classList.remove('btn-primary');
			});

			// Menambahkan event listener untuk tombol toggle nomor telepon
			resetWithPhoneButton.addEventListener('click', function() {
				emailForm.style.display = 'none';
				phoneForm.style.display = 'block';
				// Menambahkan kelas 'active' ke tombol telepon dan menghapusnya dari tombol email
				resetWithPhoneButton.classList.add('active');
				resetWithEmailButton.classList.remove('active');

				// Menyesuaikan kelas tombol
				resetWithPhoneButton.classList.add('btn-primary');
				resetWithEmailButton.classList.remove('btn-primary');
			});

		});

		function togglePassword() {
			console.log('Toggle Password function called'); // Debugging line
			var passwordInput = document.getElementById('password');
			var showHideSpan = document.querySelector('.show-hide span');

			if (passwordInput && showHideSpan) {
				if (passwordInput.type === 'password') {
					passwordInput.type = 'text';
					showHideSpan.textContent = 'Hide';
				} else {
					passwordInput.type = 'password';
					showHideSpan.textContent = '';
				}
			} else {
				console.error('Element not found');
			}
		}
	</script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    @endpush

@endsection