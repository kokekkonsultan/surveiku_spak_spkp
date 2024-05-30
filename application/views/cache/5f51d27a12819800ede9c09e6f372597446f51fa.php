

<?php 
  $ci = get_instance();
?>

<?php $__env->startSection('style'); ?>
<link rel="stylesheet" href="<?php echo e(VENDOR_PATH); ?>sweetalert2/sweetalert2.min.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="login-signin">
    <div class="mb-20">
        <h3>Forgotten Password ?</h3>
        <div class="text-muted font-weight-bold">Enter your email to reset your password</div>
    </div>
    <?php if(isset($message)): ?>
    <div class="alert alert-custom alert-light-warning fade show mb-5" role="alert">
      <div class="alert-icon"><i class="flaticon-warning"></i></div>
      <div class="alert-text"><?php echo $message;?></div>
      <div class="alert-close">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true"><i class="ki ki-close"></i></span>
          </button>
      </div>
    </div>
    <?php endif; ?>
    <?php
        echo form_open("auth/forgot_password");
    ?>
        <div class="form-group mb-10">
            <?php
                echo form_input($identity);
            ?>
        </div>
        <div class="form-group d-flex flex-wrap flex-center mt-10">
            <?php
                echo form_submit('submit', 'Request', ['class' => 'btn btn-primary font-weight-bold px-9 py-4 my-3 mx-2']);
            ?>
            <?php
                echo anchor(base_url().'auth/login', 'Cancel', ['class' => 'btn btn-light-primary font-weight-bold px-9 py-4 my-3 mx-2']);
            ?>
        </div>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('include_backend/template_auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp-7.3.33\htdocs\surveiku_spak_spkp\application\views/auth/forgot_password.blade.php ENDPATH**/ ?>