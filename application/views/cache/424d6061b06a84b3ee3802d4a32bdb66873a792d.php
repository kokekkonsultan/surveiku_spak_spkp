

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

            <div class="row justify-content-md-center">
                <div class="col col-lg-12">

                    <?php echo $__env->make('setting_survei/menu_settings', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><br>
                    <!-- <div class="card border-primary mb-5" data-aos="fade-down">
                        <div class="card-body">

                            <div class="alert alert-custom alert-notice alert-light-primary fade show" role="alert">
                                <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                <div class="alert-text"> <span>Atribut Pertanyaan Survei digunakan untuk mengatur jenis
                                        pertanyaan apa saja yang digunakan dalam survei. Mengubah Jenis Pertanyaan juga
                                        akan menghapus semua data perolehan survei yang sudah masuk!</span>
                                </div>
                                <div class="alert-close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="ki ki-close"></i></span>
                                    </button>
                                </div>
                            </div>

                            <br>

                            <form
                                action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/setting-pertanyaan' ?>"
                                class="form_atribut_pertanyaan">

                                <div class="form-group row">
                                    <label for="recipient-name" class="col-sm-4 col-form-label font-weight-bold">Atribut
                                        Pertanyaan Survei<span style="color:red;">*</span></label>

                                    <div class="col-sm-8">

                                        <input type="hidden" name="atribut_pertanyaan[]" value="0">
                                        <label class="font-weight-bold"><input type="checkbox" checked disabled>
                                            Pertanyaan Unsur</label><br>

                                        <label><input type="checkbox" name="atribut_pertanyaan[]" value="1" <?php if (in_array(1, $atribut_pertanyaan_survey)) {
                                                                                                                echo 'checked';
                                                                                                            } ?>>
                                            Pertanyaan Harapan</label><br>

                                        <label><input type="checkbox" name="atribut_pertanyaan[]" value="2" <?php if (in_array(2, $atribut_pertanyaan_survey)) {
                                                                                                                echo 'checked';
                                                                                                            } ?>>
                                            Pertanyaan Tambahan</label><br>

                                        <label><input type="checkbox" name="atribut_pertanyaan[]" value="3" <?php if (in_array(3, $atribut_pertanyaan_survey)) {
                                                                                                                echo 'checked';
                                                                                                            } ?>>
                                            Pertanyaan Kualitatif</label>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button type="submit"
                                        class="btn btn-primary font-weight-bold tombolSimpanJenisPertanyaan">Update
                                        Jenis Pertanyaan</button>
                                </div>
                            </form>
                        </div>
                    </div> -->

                    <div class="card border-danger" data-aos="fade-down">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5 class="card-title">Delete Survei</h5>
                                    <p class="card-text">Setelah Anda menghapus survei, data tidak akan bisa
                                        dikembalikan. Harap yakin.</p>
                                </div>
                                <div class="col-md-4 text-right mt-3">
                                    <button type="button" class="btn btn-danger font-weight-bold"
                                        onclick="delete_data(<?php echo $id_manage_survey ?>)">Hapus Survei Ini</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
<script>
function delete_data(id) {
    Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Anda akan menghapus survei ini beserta semua datanya!",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Batal',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, saya menghapusnya!'
    }).then((result) => {
        if (result.value) {

            $.ajax({
                url: "<?php echo e(base_url()); ?>manage-survey/delete/" + id,
                type: "POST",
                dataType: "JSON",
                beforeSend: function() {
                    Swal.fire({
                        title: 'Memproses data',
                        html: 'Mohon tunggu sebentar. Sistem sedang melakukan request anda.',
                        allowOutsideClick: false,
                        onOpen: () => {
                            swal.showLoading()
                        }
                    });
                },
                success: function(data) {
                    if (data.status) {

                        window.location.href =
                            "<?php echo e(base_url().$ci->session->userdata('username')); ?>/manage-survey";

                    } else {

                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });
        }
    });
}
</script>

<script>
$('.form_atribut_pertanyaan').submit(function(e) {

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolSimpanJenisPertanyaan').attr('disabled', 'disabled');
            $('.tombolSimpanJenisPertanyaan').html(
                '<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

            KTApp.block('#content_1', {
                overlayColor: '#000000',
                state: 'primary',
                message: 'Processing...'
            });

            setTimeout(function() {
                KTApp.unblock('#content_1');
            }, 1000);

        },
        complete: function() {
            $('.tombolSimpanJenisPertanyaan').removeAttr('disabled');
            $('.tombolSimpanJenisPertanyaan').html('Update Jenis Pertanyaan');
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
                toastr["success"]('Data berhasil disimpan');
                window.setTimeout(function() {
                    location.reload()
                }, 1000);
            }
        }
    })
    return false;

});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('include_backend/template_backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp-7.3.33\htdocs\surveiku_spak_spkp\application\views/setting_survei/form_settings.blade.php ENDPATH**/ ?>