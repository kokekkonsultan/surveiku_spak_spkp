

<?php
$ci = get_instance();
?>


<?php $__env->startSection('style'); ?>
<link href="<?php echo e(TEMPLATE_BACKEND_PATH); ?>plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <?php echo $__env->make('include_backend/partials_no_aside/_message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make("include_backend/partials_no_aside/_inc_menu_repository", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row mt-5">
        <div class="col-md-3">
            <?php echo $__env->make('manage_survey/menu_data_survey', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-md-9">


            <div class="card card-custom bgi-no-repeat gutter-b" style="height: 150px; background-color: #1c2840; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(/assets/img/banner/taieri.svg)" data-aos="fade-down">
                <div class="card-body d-flex align-items-center">
                    <div>
                        <h3 class="text-white font-weight-bolder line-height-lg mb-5"><?php echo e(strtoupper($title)); ?></h3>
                        <span class="text-white">Setelah aktivitas survei selesai dan data sudah terkumpul maka Anda
                            dapat mendownload laporan survei. Gunakan tombol dibawah ini untuk mendownload laporan
                            survei Anda.</span>
                    </div>
                </div>
            </div>


            <div class="card card-body">
                <div class="card-deck">
                    <a href="<?php echo e(base_url()); ?><?php echo e($ci->session->userdata('username')); ?>/<?php echo e($ci->uri->segment(2)); ?>/laporan-survey/download-docx" target="_blank" class="card card-body border border-primary text-primary shadow wave wave-animate-slow wave-primary">
                        <div class="text-center font-weight-bold">
                            <i class="fa fa-file-word text-primary" style="font-size: 30px;"></i><br>
                            <h6 class="mt-3">Download<br>Laporan format .docx</h6>
                        </div>
                    </a>

                    <a href="<?php echo e(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/laporan-survey/cetak'); ?>" class="card card-body text-danger border border-danger shadow wave wave-animate-slow wave-danger" target="_blank">
                        <div class="text-center font-weight-bold">
                            <i class="fa fa-file-pdf text-danger" style="font-size: 30px;"></i><br>
                            <h6 class="mt-3">Download<br>Laporan format .pdf</h5>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="example-modal">
    <div id="add" class="modal fade" role="dialog">
        <div class="modal-dialog border border-primary">
            <div class="modal-content">
                <!-- <div class="modal-header bg-secondary">
                    <h5 class="font-weight-bold">Buat Laporan</h5>
                </div> -->
                <div class="modal-body">

                    <form class="form_submit" method="POST" action="<?php echo e(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/laporan-survey/generate'); ?>">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">Keterangan :</label>
                            <textarea class="form-control" name="keterangan" rows="5"></textarea>
                        </div>

                        <div class="text-right mt-5">
                            <button class="btn btn-primary font-weight-bold tombolSubmit" type="submit"><i class="fa fa-download"></i> Generate Laporan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
<script src="<?php echo e(TEMPLATE_BACKEND_PATH); ?>plugins/custom/datatables/datatables.bundle.js"></script>
<script>
    $(document).ready(function(e) {
        $('.form_submit').submit(function(e) {

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                dataType: 'json',
                data: $(this).serialize(),
                cache: false,
                beforeSend: function() {
                    $('.tombolCancel').attr('disabled', 'disabled');
                    $('.tombolSubmit').attr('disabled', 'disabled');
                    $('.tombolSubmit').html(
                        '<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

                    Swal.fire({
                        title: 'Memproses data',
                        html: 'Mohon tunggu sebentar. Sistem sedang menyiapkan request anda.',
                        allowOutsideClick: false,
                        onOpen: () => {
                            swal.showLoading()
                        }
                    });

                },
                complete: function() {
                    $('.tombolCancel').removeAttr('disabled');
                    $('.tombolSubmit').removeAttr('disabled');
                    $('.tombolSubmit').html('Simpan');
                },
                error: function(e) {
                    Swal.fire(
                        'Error !',
                        e,
                        'error'
                    )
                },
                success: function(data) {

                    if (data.validasi) {
                        $('.pesan').fadeIn();
                        $('.pesan').html(data.validasi);
                    }
                    if (data.sukses) {

                        Swal.fire(
                            'Informasi',
                            'Berhasil Membuat Laporan!',
                            'success'
                        );
                        window.setTimeout(function() {
                            location.reload()
                        }, 1500);

                    }
                }
            })
            return false;
        });
    });
</script>

<script>
    $(document).ready(function() {
        table = $('.example').DataTable();
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('include_backend/template_backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp-7.3.33\htdocs\surveiku_spak_spkp\application\views/laporan_survey/index.blade.php ENDPATH**/ ?>