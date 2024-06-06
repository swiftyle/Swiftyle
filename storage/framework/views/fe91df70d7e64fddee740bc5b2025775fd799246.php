<?php $__env->startSection('title'); ?>Draggable Card
 <?php echo e($title); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
	<?php $__env->startComponent('components.breadcrumb'); ?>
		<?php $__env->slot('breadcrumb_title'); ?>
			<h3>Draggable Card</h3>
		<?php $__env->endSlot(); ?>
		<li class="breadcrumb-item">Cards</li>
		<li class="breadcrumb-item active">Draggable Card</li>
	<?php echo $__env->renderComponent(); ?>

    <div class="container-fluid">
        <div class="row ui-sortable" id="draggableMultiple">
            <div class="col-sm-12 col-xl-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Basic Card</h5>
                    </div>
                    <div class="card-body">
                        <p>
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled.
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6">
                <div class="card b-r-0">
                    <div class="card-header pb-0">
                        <h5>Flat Card</h5>
                    </div>
                    <div class="card-body">
                        <p>
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled.
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6">
                <div class="card shadow-0 border">
                    <div class="card-header pb-0">
                        <h5>Without shadow Card</h5>
                    </div>
                    <div class="card-body">
                        <p>
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled.
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5><i class="icon-move me-2"></i> Icon in Heading</h5>
                    </div>
                    <div class="card-body">
                        <p>
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled.
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Card sub Title</h5>
                        <span>Using the <a href="javascript:void(0)">card</a> component, you can extend the default collapse behavior to create an accordion.</span>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled.
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Card sub Title</h5>
                        <span>Using the <a href="javascript:void(0)">card</a> component, you can extend the default collapse behavior to create an accordion.</span>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled.
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>    

    <?php $__env->startPush('scripts'); ?>
       <script src="<?php echo e(asset('assets/js/jquery.ui.min.js')); ?>"></script>
       <script src="<?php echo e(asset('assets/js/dragable/sortable.js')); ?>"></script>
       <script src="<?php echo e(asset('assets/js/dragable/sortable-custom.js')); ?>"></script>
	<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Viho-Laravel-8\viho laravel 9\resources\views/admin/bonus-ui/dragable-card.blade.php ENDPATH**/ ?>