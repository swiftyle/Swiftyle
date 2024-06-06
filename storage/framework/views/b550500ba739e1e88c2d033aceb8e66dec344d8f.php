<?php $__env->startSection('title'); ?>
    User Data Table
    <?php echo e($title); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/animate.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/chartist.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/date-picker.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/prism.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vector-map.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('breadcrumb_title'); ?>
            <h3>User Data</h3>
        <?php $__env->endSlot(); ?>
        <li class="breadcrumb-item">Data</li>
        <li class="breadcrumb-item active">User</li>
    <?php echo $__env->renderComponent(); ?>

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
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div class="media">
                                            <img class="img-fluid rounded-circle" src="<?php echo e($user->avatar); ?>"
                                                alt="" width="30px" height="30px">
                                            <div class="media-body">
                                                <span><?php echo e($user->name); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p><?php echo e($user->username); ?></p>
                                    </td>
                                    <td>
                                        <p><?php echo e($user->email); ?></p>
                                    </td>
                                    <td>
                                        <p><?php echo e($user->role); ?></p>
                                    </td>
                                    <td>
                                        <p><?php echo e($user->gender); ?></p>
                                    </td>
                                    <td>
                                        <p><?php echo e($user->address); ?></p>
                                    </td>
                                    <td>
                                        <p><?php echo e($user->created_at->format('Y-m-d')); ?></p>
                                    </td>
                                    <td>
                                        <p><?php echo e($user->status); ?></p>
                                    </td>
                                    
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    <?php echo e($users->links()); ?>

                </div>
            </div>
        </div>
    </div>


        <?php $__env->startPush('scripts'); ?>
        <script>
            if (window.history && window.history.pushState) {
                window.history.pushState(null, null, window.location.href);
                window.onpopstate = function() {
                    window.history.pushState(null, null, window.location.href);
                };
            }
        </script>

        <script src="<?php echo e(asset('assets/js/chart/knob/knob.min.js')); ?>"></script>
        <script src="<?php echo e(asset('assets/js/chart/knob/knob-chart.js')); ?>"></script>
        <script src="<?php echo e(asset('assets/js/prism/prism.min.js')); ?>"></script>
        <script src="<?php echo e(asset('assets/js/clipboard/clipboard.min.js')); ?>"></script>
        <script src="<?php echo e(asset('assets/js/counter/jquery.waypoints.min.js')); ?>"></script>
        <script src="<?php echo e(asset('assets/js/counter/jquery.counterup.min.js')); ?>"></script>
        <script src="<?php echo e(asset('assets/js/counter/counter-custom.js')); ?>"></script>
        <script src="<?php echo e(asset('assets/js/custom-card/custom-card.js')); ?>"></script>
        <script src="<?php echo e(asset('assets/js/notify/bootstrap-notify.min.js')); ?>"></script>
        <script src="<?php echo e(asset('assets/js/dashboard/default.js')); ?>"></script>
        <?php $__env->stopPush(); ?>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\swiftyle-1.1\resources\views/admin/users/data-user.blade.php ENDPATH**/ ?>