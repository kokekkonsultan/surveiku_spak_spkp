

<?php
$ci = get_instance();
?>

<?php $__env->startSection('style'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid">
    <?php echo $__env->make("include_backend/partials_no_aside/_inc_menu_repository", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row mt-5">
        <div class="col-md-3">
            <?php echo $__env->make('manage_survey/menu_data_survey', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-md-9">

            <div class="card">
                <div class="card-header bg-secondary">
                    <h3><?php echo e($title); ?></h3>
                    <div> Please enter the user's information below.</div>
                </div>
                <div class="card-body">
                    <br>

                    <div id="infoMessage"><?php echo $message; ?></div>

                    <?php
                    echo form_open($form_action);
                    ?>
                    <?php
                    echo validation_errors();
                    ?>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Nama Depan <span style="color: red;">*</span></label>
                            <?php echo form_input($first_name); ?>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Nama Belakang <span style="color: red;">*</span></label>
                            <?php echo form_input($last_name); ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Kode Surveyor <span style="color: red;">*</span></label>
                            <?php echo form_input($kode_surveyor); ?>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Email <span style="color: red;">*</span></label>
                            <?php echo form_input($email); ?>
                        </div>
                    </div>

                    <div>
                        <label class="font-weight-bold">Keterangan</label>
                        <?php echo form_input($keterangan); ?>
                    </div>

                    <br>

                    <div>
                        <label class="font-weight-bold">Phone <span style="color: red;">*</span></label>
                        <?php echo form_input($phone); ?>
                    </div>

                    <br>


                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Password</label>
                            <?php echo form_input($password); ?>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Confirm Password</label>
                            <?php echo form_input($password_confirm); ?>
                        </div>
                    </div>


                    <div class="text-right">
                        <?php
                        echo
                        anchor(base_url().$ci->session->userdata('username').'/'.$ci->uri->segment(2).'/data-surveyor',
                        'Cancel', ['class' => 'btn btn-light-primary font-weight-bold'])
                        ?>
                        <?php echo form_submit('submit', 'Edit Surveyor', ['class' => 'btn btn-primary font-weight-bold']); ?>
                    </div>

                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('include_backend/template_backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp-7.3.33\htdocs\surveiku_spak_spkp\application\views/data_surveyor/form_edit.blade.php ENDPATH**/ ?>