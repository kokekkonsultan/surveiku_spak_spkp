

<?php
$ci = get_instance();
?>

<?php $__env->startSection('style'); ?>
<style>
.border-menu {
    border-color: #304EC0 !important;
    background-color: #f3f3f3;
}

.card-menu {
    cursor: pointer;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <?php echo $__env->make("include_backend/partials_no_aside/_inc_menu_repository", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row mt-5">
        <div class="col-md-3">
            <?php echo $__env->make('manage_survey/menu_data_survey', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-md-9">

            <div class="card card-custom bgi-no-repeat gutter-b"
                style="height: 175px; background-color: #1c2840; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(/assets/img/banner/taieri.svg)"
                data-aos="fade-down">
                <div class="card-body d-flex align-items-center">
                    <div>
                        <h3 class="text-white font-weight-bolder line-height-lg mb-5">
                            E-SERTIFIKAT
                        </h3>

                        <div class="alert alert-white font-weight-bold" role="alert">
                            Anda bisa menerbitkan sertifikat dengan melengkapi form dibawah ini. Sertifikat yang
                            diterbitkan dilengkapi dengan QrCode yang bisa divalidasi kebenaran datanya. Desain
                            sertifikat akan selalu diupdate oleh administrator.
                        </div>
                    </div>
                </div>
            </div>


            <div class="card" data-aos="fade-down">

                <div class="card-body">

                    <form method="post"
                        action="<?php echo e(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/e-sertifikat'); ?>"
                        enctype="multipart/form-data" target="_blank">

                        <h4 class="mb-5"><u>Desain Sertifikat</u></h4>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Pilih Produk Survei <span
                                    style="color: red;">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="produk" required>
                                    <option value="">Please Select</option>
                                    <option value="1">Survei Persepsi Kualitas Pelayanan (SPKP)</option>
                                    <option value="2">Survei Persepsi Anti Korupsi (SPAK)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Pilih Desain Sertifikat <span
                                    style="color: red;">*</span></label>
                            <!-- <input type="text" name="model_sertifikat"> -->
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>
                                            <input type="radio" name="model_sertifikat" value="desain-1.jpg"
                                                required="required">
                                            <div class="card card-menu">
                                                <div class="card-body">

                                                    <div class="text-center">
                                                        <img src="<?php echo e(base_url()); ?>assets/files/sertifikat/desain-1.jpg"
                                                            alt="" width="150px;">
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label>
                                            <input type="radio" name="model_sertifikat" value="desain-2.jpg"
                                                required="required">
                                            <div class="card card-menu">
                                                <div class="card-body">
                                                    <div class="text-center">
                                                        <img src="<?php echo e(base_url()); ?>assets/files/sertifikat/desain-2.jpg"
                                                            alt="" width="150px;">
                                                    </div>

                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label>
                                            <input type="radio" name="model_sertifikat" value="desain-3.jpg"
                                                required="required">
                                            <div class="card card-menu">
                                                <div class="card-body">
                                                    <div class="text-center">
                                                        <img src="<?php echo e(base_url()); ?>assets/files/sertifikat/desain-3.jpg"
                                                            alt="" width="150px;">
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>


                        <hr>
                        <h4 class="mb-5"><u>Penandatangan Sertifikat</u></h4>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Nama lengkap beserta title yang menandatangani
                                sertifikat <span style="color: red;">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="nama" placeholder="" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Jabatan <span style="color: red;">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="jabatan" placeholder="" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Pilih Periode Survei <span
                                    style="color: red;">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="periode" required>
                                    <option value="">Please Select</option>
                                    <option value="Bulan Januari">Bulan Januari</option>
                                    <option value="Bulan Februari">Bulan Februari</option>
                                    <option value="Bulan Maret">Bulan Maret</option>
                                    <option value="Bulan April">Bulan April</option>
                                    <option value="Bulan Mei">Bulan Mei</option>
                                    <option value="Bulan Juni">Bulan Juni</option>
                                    <option value="Bulan Juli">Bulan Juli</option>
                                    <option value="Bulan Agustus">Bulan Agustus</option>
                                    <option value="Bulan September">Bulan September</option>
                                    <option value="Bulan Oktober">Bulan Oktober</option>
                                    <option value="Bulan November">Bulan November</option>
                                    <option value="Bulan Desember">Bulan Desember</option>
                                    <option value="Triwulan I">Triwulan I</option>
                                    <option value="Triwulan II">Triwulan II</option>
                                    <option value="Triwulan III">Triwulan III</option>
                                    <option value="Triwulan IV">Triwulan IV</option>
                                    <option value="Semester I">Semester I</option>
                                    <option value="Semester II">Semester II</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Pilih Profil Responden Yang di Tampilkan <span
                                    style="color: red;">*</span></label>
                            <div class="col-sm-9">
                                <select id="profil_responden" name="profil_responden[]" class="form-control"
                                    multiple="multiple" required>

                                    <option value=""></option>

                                    <?php $__currentLoopData = $profil_responden->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($row->id); ?>"><?php echo e($row->nama_profil_responden); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Gunakan Nomor Sertifikat <span
                                    style="color: red;">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="is_nomor_sertifikat" required>
                                    <option value="">Please Select</option>
                                    <option value="1">Ya</option>
                                    <option value="2">Tidak</option>
                                </select>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tanda Tangan <span
                                    style="color: red;">*</span></label>
                            <div class="col-9 col-form-label">
                                <div class="radio-inline">
                                    <label class="radio radio">
                                        <input type="radio" name="is_tanda_tangan" value="2" class="tanda_tangan">
                                        <span></span> Tanpa Tanda Tangan
                                    </label>
                                </div>
                                <hr>
                                <div class="radio-inline">
                                    <label class="radio radio">
                                        <input type="radio" name="is_tanda_tangan" value="1" class="tanda_tangan"
                                            required>
                                        <span></span> Dengan Tanda Tangan
                                    </label>
                                </div>

                                <div class="mt-3" id="dengan_tanda_tangan" style="display: none;">

                                    <?php if($manage_survey->img_ttd_sertifikat == ''): ?>

                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="ttd" name="ttd">
                                        <label class="custom-file-label" for="validatedCustomFile">Choose
                                            file...</label>
                                        <small class="text-danger">
                                            ** Format file harus png. | Ukuran max file adalah 5MB. | Dimensi banner
                                            proporsional 1:1
                                        </small>
                                    </div>
                                    <?php else: ?>


                                    <div class="row">
                                        <div class="custom-file col-md-3">
                                            <img src="<?php echo e(base_url() . 'assets/klien/ttd_sertifikat/' . $manage_survey->img_ttd_sertifikat); ?>"
                                                alt="ttd" height="100" class="shadow">
                                        </div>
                                        <div class="col-md-9">
                                            <input type="file" class="custom-file-input" name="ttd">
                                            <label class="custom-file-label" for="validatedCustomFile">Choose
                                                file...</label>
                                            <small class="text-danger">
                                                ** Format file harus png. | Ukuran max file adalah 5MB. | Dimensi
                                                banner
                                                proporsional 1:1
                                            </small>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>




                        <div class="text-right">
                            <button type="submit" class="btn btn-primary font-weight-bold" target="_blank"><i
                                    class="fas fa-print"></i>Generate Sertifikat</button>
                        </div>

                    </form>

                </div>
            </div>


            <div class="row mt-5">
                <div class="col-md-4">
                    <div class="card" data-aos="fade-down">
                        <div class="card-header bg-secondary font-weight-bold">
                            QR Code
                        </div>
                        <div class="card-body text-center">
                            <img src="https://image-charts.com/chart?chl=<?php echo base_url() . "validasi-sertifikat/" . $manage_survey->uuid ?>&choe=UTF-8&chs=300x300&cht=qr"
                                height="130" alt="" class="shadow">
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card" data-aos="fade-down">
                        <div class="card-header bg-secondary font-weight-bold">
                            Link Validasi
                        </div>
                        <div class="card-body">
                            <div class='input-group'>
                                <input type='text' class='form-control' id='kt_clipboard_1'
                                    value="<?php echo e(base_url()); ?>validasi-sertifikat/<?php echo e($manage_survey->uuid); ?>"
                                    placeholder='Type some value to copy' />
                                <div class='input-group-append'>
                                    <a href='javascript:void(0)' class='btn btn-light-primary font-weight-bold shadow'
                                        data-clipboard='true' data-clipboard-target='#kt_clipboard_1'><i
                                            class='la la-copy'></i> Copy Link</a>
                                </div>
                            </div>

                            <div class="mt-5 mb-5 text-center">
                                Atau gunakan tombol dibawah ini.
                            </div>
                            <div class="text-center">
                                <a class="btn btn-primary"
                                    href="<?php echo e(base_url()); ?>validasi-sertifikat/<?php echo e($manage_survey->uuid); ?>"
                                    target="_blank"><i class="fas fa-globe"></i>
                                    Link Validasi</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- <div class="card mt-5" data-aos="fade-down">
                <div class="card-body">
                    <?php
                    $checked = ($profiles->is_publikasi == 1) ? "checked" : "";
                    ?>

                    <div class="row">
                        <div class="col-md-6">
                            <b>Publikasikan Survei ?</b>
                        </div>
                        <div class="col-md-6">

                            <span class="switch switch-sm">
                                <label>
                                    <input value="<?php echo e($profiles->is_publikasi); ?>" type="checkbox" name="setting_value"
                                        class="toggle_dash" <?php echo e($checked); ?> />
                                    <span></span>
                                </label>
                            </span>
                        </div>
                    </div>
                </div>
            </div> -->

        </div>
    </div>


</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


<script type="text/javascript">
$(function() {
    $(":radio.tanda_tangan").click(function() {
        if ($(this).val() == 1) {
            $('#ttd').prop('required', true);
            $("#dengan_tanda_tangan").show();
        } else {
            $('#ttd').removeAttr('required');
            $("#dengan_tanda_tangan").hide();
        }
    });
});
</script>



<script>
$('.toggle_dash').change(function() {

    var mode = $(this).prop('checked');
    var nilai_id = $(this).val();

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: "<?php echo e(base_url()); ?><?php echo e($ci->session->userdata('username')); ?>/<?php echo e($ci->uri->segment(2)); ?>/update-publikasi",
        data: {
            'mode': mode,
            'nilai_id': nilai_id
        },
        success: function(data) {
            var data = eval(data);
            message = data.message;
            success = data.success;

            toastr["success"](message);
            // window.setTimeout(function() {
            //     location.reload()
            // }, 1500);
        }
    });

});
</script>

<script>
"use strict";
var KTClipboardDemo = function() {
    var demos = function() {
        new ClipboardJS('[data-clipboard=true]').on('success', function(e) {
            e.clearSelection();
            toastr["success"]('Link berhasil dicopy, Silahkan paste di browser anda sekarang.');
        });
    }
    return {
        init: function() {
            demos();
        }
    };
}();

jQuery(document).ready(function() {
    KTClipboardDemo.init();
});
</script>

<script>
$('.card-menu').hover(
    function() {
        $(this).addClass('border-menu shadow')
    },
    function() {
        $(this).removeClass('border-menu shadow')
    }
)
</script>

<script>
$(document).ready(function() {

    $("#profil_responden").select2({
        placeholder: "   Please Select",
        allowClear: true
    });

});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('include_backend/template_backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp-7.3.33\htdocs\surveiku_spak_spkp\application\views/sertifikat/index.blade.php ENDPATH**/ ?>