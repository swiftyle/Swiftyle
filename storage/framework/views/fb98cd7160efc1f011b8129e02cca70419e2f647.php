<?php $__env->startSection('title'); ?>Tilt Animation
 <?php echo e($title); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
	<?php $__env->startComponent('components.breadcrumb'); ?>
		<?php $__env->slot('breadcrumb_title'); ?>
			<h3>Tilt Animation</h3>
		<?php $__env->endSlot(); ?>
		<li class="breadcrumb-item">Animation</li>
		<li class="breadcrumb-item active">Tilt Animation</li>
	<?php echo $__env->renderComponent(); ?>

    <div class="container-fluid">
        <div class="row tilt-showcase">
            <div class="col-sm-12">
                <div class="card text-center">
                    <div class="card-header pb-0">
                        <h5>Parallax hover tilt effect</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 offset-md-3">
                                <div><img class="img-fluid img-thumbnail js-tilt" src="<?php echo e(asset('assets/images/lightgallry/02.jpg')); ?>" data-tilt-perspective="300" data-tilt-speed="400" data-tilt-max="5" alt="" /></div>
                                <div>
                                    <h6 class="sub-title mt-4 mb-0">The most basic usage:</h6>
                                    <ul>
                                        <li>
                                            <div class="line">
                                                <code><span> &lt;script src="tilt.jquery.js')}}"&gt;&lt;/script&gt;</span></code>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="line">
                                                Mark your elements with<code><span> &lt;span data-tilt"&gt;&lt;/span&gt;</span></code>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 box-col-6 col-sm-6">
                <div class="card height-equal">
                    <div class="card-header pb-0">
                        <h5>Parallax hover tilt effect</h5>
                    </div>
                    <div class="card-body">
                        <div class="tilt-image"><img class="img-fluid img-thumbnail js-tilt" src="<?php echo e(asset('assets/images/lightgallry/03.jpg')); ?>" data-tilt-perspective="300" data-tilt-speed="400" data-tilt-max="5" alt="" /></div>
                        <div>
                            <h6 class="sub-title mt-4">How to Use:</h6>
                            <ol class="mb-0">
                                <li class="m-b-20">
                                    A tiny requestAnimationFrame powered 60+fps lightweight parallax hover tilt effect for jQuery.
                                </li>
                                <li class="m-b-20">
                                    <div class="line">Add <code>transform-style: preserve-3d </code>to your tilt element.</div>
                                </li>
                                <li>
                                    <div class="line">Add <code>transform: translateZ(20px)</code> to your inner elements that have to pop out.</div>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 box-col-6 col-sm-6">
                <div class="card height-equal">
                    <div class="card-header pb-0">
                        <h5>Glare effect:</h5>
                    </div>
                    <div class="card-body">
                        <div class="tilt-image">
                            <img class="img-fluid img-thumbnail js-tilt" src="<?php echo e(asset('assets/images/lightgallry/04.jpg')); ?>" data-tilt-glare="true" data-tilt-maxglare=".5" data-tilt-perspective="200" data-tilt-speed="300" data-tilt-max="10" alt="" />
                        </div>
                        <div>
                            <h6 class="sub-title mt-4">How to Use:</h6>
                            <div class="line">
                                <span>Setting this option will enable a glare effect. You can tweak the glare value with <code>{'maxGlare': .5}</code></span>
                                <pre class="mb-0 pre-mt"><code class="language-javascript">$('.js-tilt').tilt({
        &nbsp; glare: true,maxGlare: .5
    })</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 box-col-6 col-sm-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Keep floating:</h5>
                    </div>
                    <div class="card-body">
                        <div class="tilt-image"><img class="img-fluid img-thumbnail js-tilt" src="<?php echo e(asset('assets/images/lightgallry/05.jpg')); ?>" data-tilt-reset="false" data-tilt-speed="200" data-tilt-max="5" data-tilt-perspective="150" alt="" /></div>
                        <div>
                            <h6 class="sub-title mt-4">How to Use:</h6>
                            <div class="line">
                                <span>Setting this option will not reset the tilt element when the user mouse leaves the element.</span>
                                <pre class="mb-0"><code class="language-javascript">$('.js-tilt').tilt({
        &nbsp;  reset: false
    })</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 box-col-6 col-sm-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Scale on hover</h5>
                    </div>
                    <div class="card-body o-hidden">
                        <div class="tilt-image"><img class="img-fluid img-thumbnail js-tilt" src="<?php echo e(asset('assets/images/lightgallry/06.jpg')); ?>" data-tilt-speed="1000" data-tilt-max="20" data-tilt-scale="1.01" data-tilt-perspective="250" alt="" /></div>
                        <div>
                            <h6 class="sub-title mt-4">How to Use:</h6>
                            <span>Setting this option will scale tilt element on hover.</span>
                            <pre class="mb-0"><code class="language-javascript">$('.js-tilt').tilt({
        &nbsp;  scale: 1.2
    })</code></pre>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 box-col-6 col-sm-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Disable Y axis</h5>
                    </div>
                    <div class="card-body">
                        <div class="tilt-image"><img class="img-fluid img-thumbnail js-tilt" src="<?php echo e(asset('assets/images/lightgallry/07.jpg')); ?>" data-tilt-speed="100" data-tilt-max="10" data-tilt-axis="x" data-tilt-perspective="250" alt="" /></div>
                        <div>
                            <h6 class="sub-title mt-4">The most basic usage:</h6>
                            <span>Setting this option will disable the Y-Axis on the tilt element.</span>
                            <pre class="mb-0"><code class="language-javascript">$('.js-tilt').tilt({
        &nbsp;  axis: x
    })</code></pre>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 box-col-6 col-sm-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Disable X axis</h5>
                    </div>
                    <div class="card-body">
                        <div class="tilt-image"><img class="img-fluid img-thumbnail js-tilt" src="<?php echo e(asset('assets/images/lightgallry/08.jpg')); ?>" data-tilt-speed="250" data-tilt-max="10" data-tilt-axis="y" data-tilt-perspective="250" alt="" /></div>
                        <div>
                            <h6 class="sub-title mt-4">The most basic usage:</h6>
                            <span>Setting this option will disable the X-Axis on the tilt element.</span>
                            <pre class="mb-0"><code class="language-javascript">$('.js-tilt').tilt({
        &nbsp;   axis: Y
    })</code></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('assets/js/animation/tilt/tilt.jquery.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/animation/tilt/tilt-custom.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/height-equal.js')); ?>"></script>
	<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Viho-Laravel-8\theme\resources\views/admin/animation/tilt.blade.php ENDPATH**/ ?>