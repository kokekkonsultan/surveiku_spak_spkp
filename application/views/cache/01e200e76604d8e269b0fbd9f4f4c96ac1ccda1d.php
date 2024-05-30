

<?php
$ci = get_instance();
?>

<?php $__env->startSection('style'); ?>
<link rel="stylesheet" href="<?php echo e(VENDOR_PATH); ?>sweetalert2/sweetalert2.min.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="login-signin">
    <div class="mb-20">
        <h3>Log In</h3>
        <div class="text-muted font-weight-bold">Masukkan detail Login akun anda:</div>
    </div>

    <?php if(isset($message)): ?>
    <div class="alert alert-custom alert-light-warning fade show mb-5" role="alert">
        <div class="alert-icon"><i class="flaticon-warning"></i></div>
        <div class="alert-text"><?php echo $message; ?></div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="ki ki-close"></i></span>
            </button>
        </div>
    </div>
    <?php endif; ?>
    <?php
    echo form_open("auth/login");
    ?>
    <div class="form-group mb-5">
        <?php
        echo form_input($identity);
        ?>
    </div>
    <div class="form-group mb-5">
        <?php
        echo form_input($password);
        ?>
    </div>
    <div class="form-group d-flex flex-wrap justify-content-between align-items-center">
        <div class="checkbox-inline">
            <label class="checkbox m-0 text-muted">
                <?php
                echo form_checkbox('remember', '1', FALSE, 'id="remember"');
                ?>
                <span></span>Ingat saya</label>
        </div>
        <?php
        echo anchor(base_url().'auth/forgot_password', 'Lupa password anda?', ['class' => 'text-muted
        text-hover-primary'])
        ?>
    </div>
    <?php
    echo form_submit('submit', lang('login_submit_btn'), ['class' => 'btn btn-primary font-weight-bold px-9 py-4 my-3
    mx-4']);
    ?>


    <?php
    echo form_close();
    ?>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
<script src="<?php echo e(VENDOR_PATH); ?>sweetalert2/sweetalert2.min.js"></script>
<?php if(isset($message)): ?>
<script>
$(document).ready(function() {
    Swal.fire(
        'Terjadi Kesalahan',
        'Cek kembali data anda',
        'warning'
    );
});
</script>
<?php endif; ?>



<?php
$data_message = $ci->session->flashdata('data_message');
?>
<?php if(!empty($data_message)): ?>
<script>
$(document).ready(function() {
    Swal.fire(
        'Informasi',
        'Paket anda sudah habis! Silahkan lakukan perpanjangan paket untuk Login ke menu SKM.',
        'warning'
    );
});
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('include_backend/template_auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp-7.3.33\htdocs\surveiku_spak_spkp\application\views/auth/login.blade.php ENDPATH**/ ?>