

<?php
$ci = get_instance();
?>

<?php $__env->startSection('style'); ?>
<link href="<?php echo e(TEMPLATE_BACKEND_PATH); ?>plugins/custom/datatables/datatables.bundle.css"
    rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-secondary font-weight-bold">
            <?php echo e($title); ?>

        </div>
        <div class="card-body">
            <div class="text-center font-weight-bold mt-5">
                Bagikan link dibawah ini kepada responden untuk dilakukan pengisian.
            </div>
            <br>
            <div class='input-group'>
                <input class='form-control' id='kt_clipboard_1'
                    value="<?php echo e(base_url()); ?>survei/<?php echo e($manage_survey->slug); ?>/<?php echo e($data_surveyor->uuid); ?>" readonly />
                <div class='input-group-append'>
                    <a href='javascript:void(0)' class='btn btn-light-primary' data-clipboard='true'
                        data-clipboard-target='#kt_clipboard_1'><i class='la la-copy'></i> <strong>Copy
                            Link</strong></a>
                </div>
            </div>

            <br>
            <div class="text-center font-weight-bold mt-5">
                Atau gunakan tombol dibawah ini.
            </div>

            <br>

            <div class="text-center">
                <a class="btn btn-primary font-weight-bold shadow btn-block"
                    href="<?php echo e(base_url()); ?>survei/<?php echo e($manage_survey->slug); ?>/<?php echo e($data_surveyor->uuid); ?>"
                    target="_blank"><i class="fas fa-link"></i>Link Survei</a>
            </div>
            <br>
            <br>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
<script>
"use strict";
// Class definition

var KTClipboardDemo = function() {

    // Private functions
    var demos = function() {
        // basic example
        new ClipboardJS('[data-clipboard=true]').on('success', function(e) {
            e.clearSelection();
            // alert('Copied!');
            toastr["success"]('Link berhasil dicopy, Silahkan paste di browser anda sekarang.');
        });
    }

    return {
        // public functions
        init: function() {
            demos();
        }
    };
}();

jQuery(document).ready(function() {
    KTClipboardDemo.init();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('include_backend/template_backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp-7.3.33\htdocs\surveiku_spak_spkp\application\views/link_per_surveyor/index.blade.php ENDPATH**/ ?>