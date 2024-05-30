

<?php
$ci = get_instance();
?>

<?php $__env->startSection('style'); ?>
<link href="<?php echo e(TEMPLATE_BACKEND_PATH); ?>plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"
    type="text/css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-secondary font-weight-bold">
            <?php echo e($title); ?>

        </div>
        <div class="card-body">
            <?php echo $ci->session->set_flashdata('message_success') ?>
            <div class="table-responsive">
                <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%"
                    style="font-size: 12px;">
                    <thead class="bg-secondary">
                        <tr>
                            <th width="5%">No.</th>
                            <th>Status</th>
                            <th>Form</th>
                            <th>Surveyor</th>

                            <?php $__currentLoopData = $profil; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <th><?php echo $row->nama_profil_responden ?></th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <th>Saran</th>
                            <th>Waktu Isi Survei</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
<script src="<?php echo e(TEMPLATE_BACKEND_PATH); ?>plugins/custom/datatables/datatables.bundle.js"></script>
<script>
$(document).ready(function() {
    table = $('#table').DataTable({

        "processing": true,
        "serverSide": true,
        "order": [],
        "language": {
            "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
        },
        "ajax": {
            "url": "<?php echo e(base_url()); ?>data-perolehan-surveyor/ajax-list",
            "type": "POST",
            "data": function(data) {}
        },

        "columnDefs": [{
            "targets": [-1],
            "orderable": false,
        }, ],

    });
});

$('#btn-filter').click(function() {
    table.ajax.reload();
});
$('#btn-reset').click(function() {
    $('#form-filter')[0].reset();
    table.ajax.reload();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('include_backend/template_backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp-7.3.33\htdocs\surveiku_spak_spkp\application\views/data_perolehan_surveyor/index.blade.php ENDPATH**/ ?>