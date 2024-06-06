<?php $__env->startSection('title'); ?>Sticky
 <?php echo e($title); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/sticky.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
	<?php $__env->startComponent('components.breadcrumb'); ?>
		<?php $__env->slot('breadcrumb_title'); ?>
			<h3>Sticky</h3>
		<?php $__env->endSlot(); ?>
		<li class="breadcrumb-item">Advance</li>
		<li class="breadcrumb-item active">Sticky</li>
	<?php echo $__env->renderComponent(); ?>

    <div class="container-fluid">
		<div class="row sticky-header-main">
		  <div class="col-sm-12">
			<div class="card">
			  <div class="card-header pb-0">
				<h5>Sticky Note <a class="btn btn-primary pull-right m-l-10" id="add_new" href="javascript:;">Add New Note</a></h5>
			  </div>
			  <div class="card-body">
				<div class="sticky-note" id="board"></div>
			  </div>
			</div>
		  </div>
		</div>
	</div>

    <?php $__env->startPush('scripts'); ?>
        <script src="<?php echo e(asset('assets/js/jquery.ui.min.js')); ?>"></script>
        <script src="<?php echo e(asset('assets/js/sticky/sticky.js')); ?>"></script>
    <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Viho-Laravel-8\theme\resources\views/admin/bonus-ui/sticky.blade.php ENDPATH**/ ?>