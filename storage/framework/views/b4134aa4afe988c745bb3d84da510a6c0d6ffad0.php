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
                                <label>Name</label>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="icon-user"></i></span>
                                        <input class="form-control" id="name" type="text" name="name" required=""
                                            placeholder="Your Name" autocomplete="name"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="icon-email"></i></span>
                                    <input class="form-control" id="email" type="email" name="email" required=""
                                        placeholder="Test@gmail.com" autocomplete="email"/>
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
                                    <label class="text-muted" for="checkbox1">Agree with <span>Privacy Policy</span></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-full-width" type="submit">Create Account</button>
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
            function togglePassword() {
                console.log('Toggle Password function called');
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

<?php echo $__env->make('admin.authentication.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Swiftyle\resources\views/admin/authentication/sign-up.blade.php ENDPATH**/ ?>