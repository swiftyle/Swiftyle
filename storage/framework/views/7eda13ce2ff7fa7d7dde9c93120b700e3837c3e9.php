<?php $__env->startSection('title'); ?>
    Sign Up
    <?php echo e($title); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/sweetalert2.css')); ?>">
    <style>
        .btn-full-width {
            width: 100%;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <section>
        <div class="container-fluid p-0">
            <img class="bg-img-cover bg-center" src="<?php echo e(asset('assets/images/login/sign-up.png')); ?>" />
            <div class="row m-0">
                <div class="col-12 p-0">
                    <div class="login-card">
                        <form class="theme-form login-form" action="<?php echo e(route('register')); ?>" method="POST" style="border-radius:20px">
							<?php echo csrf_field(); ?>
                            <h4>Create your account</h4>
                            <h6>Enter your personal details to create account</h6>
                            <div class="form-group">
                                <label>Username</label>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="icon-user"></i></span>
                                        <input class="form-control" type="text" name="username" required=""
                                            placeholder="Your Username" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Choose Registration Method:</label>
                                <div class="btn-group" role="group" aria-label="Registration Method">
                                    <button type="button" class="btn btn-primary" id="registerWithEmail">Email</button>
                                    <button type="button" class="btn" id="registerWithPhone">Phone</button>
                                </div>
                            </div>

                            <div class="form-group" id="emailForm">
                                <label>Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="icon-email"></i></span>
                                    <input class="form-control" type="email" name="email" required=""
                                        placeholder="Test@gmail.com" />
                                </div>
                            </div>
                            <div class="form-group" id="phoneForm" style="display: none;">
                                <label>Mobile Number</label>
                                <div class="row">
                                    <div class="col-4 col-sm-3">
                                        <input class="form-control" type="text" value="+62" />
                                    </div>
                                    <div class="col-8 col-sm-9">
                                        <input class="form-control" name="phone" type="tel" value="000-000-0000" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Password</label>
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
                                    <label class="text-muted" for="checkbox1">Agree with <span>Privacy Policy
                                        </span></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-full-width" type="submit">Create Account</button>
                            </div>
                            <div class="login-social-title">
                                <h5>Sign up with</h5>
                            </div>
                            <div class="form-group">
                                <ul class="login-social">
                                    <li>
                                        <a href="https://www.linkedin.com/login" target="_blank"><i
                                                data-feather="linkedin"></i></a>
                                    </li>
                                    <li>
                                        <a href="https://www.linkedin.com/login" target="_blank"><i
                                                data-feather="twitter"></i></a>
                                    </li>
                                    <li>
                                        <a href="https://www.linkedin.com/login" target="_blank"><i
                                                data-feather="facebook"></i></a>
                                    </li>
                                    <li>
                                        <a href="https://www.instagram.com/login" target="_blank"><i
                                                data-feather="instagram"> </i></a>
                                    </li>
                                </ul>
                            </div>
                            <p>Already have an account?<a class="ms-2" href="<?php echo e(route('login')); ?>">Sign in</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <?php $__env->startPush('scripts'); ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Mendapatkan referensi ke elemen form dan tombol toggle
                const emailForm = document.getElementById('emailForm');
                const phoneForm = document.getElementById('phoneForm');
                const registerWithEmailButton = document.getElementById('registerWithEmail');
                const registerWithPhoneButton = document.getElementById('registerWithPhone');

                // Menambahkan event listener untuk tombol toggle email
                registerWithEmailButton.addEventListener('click', function() {
                    emailForm.style.display = 'block';
                    phoneForm.style.display = 'none';
                    // Menambahkan kelas 'active' ke tombol email dan menghapusnya dari tombol telepon
                    registerWithEmailButton.classList.add('active');
                    registerWithPhoneButton.classList.remove('active');

                    // Menyesuaikan kelas tombol
                    registerWithEmailButton.classList.add('btn-primary');
                    registerWithPhoneButton.classList.remove('btn-primary');
                });

                // Menambahkan event listener untuk tombol toggle nomor telepon
                registerWithPhoneButton.addEventListener('click', function() {
                    emailForm.style.display = 'none';
                    phoneForm.style.display = 'block';
                    // Menambahkan kelas 'active' ke tombol telepon dan menghapusnya dari tombol email
                    registerWithPhoneButton.classList.add('active');
                    registerWithEmailButton.classList.remove('active');

                    // Menyesuaikan kelas tombol
                    registerWithPhoneButton.classList.add('btn-primary');
                    registerWithEmailButton.classList.remove('btn-primary');
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
        <script src="<?php echo e(asset('assets/js/sweet-alert/sweetalert.min.js')); ?>"></script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.authentication.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Swiftyle-main\resources\views/admin/authentication/sign-up.blade.php ENDPATH**/ ?>