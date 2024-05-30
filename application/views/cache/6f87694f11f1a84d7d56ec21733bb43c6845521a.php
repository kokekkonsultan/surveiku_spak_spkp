

<?php
$ci = get_instance();
?>

<?php $__env->startSection('style'); ?>
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="container mt-5 mb-5" style="font-family: nunito;">
    <div class="text-center" data-aos="fade-up">
        <div id="progressbar" class="mb-5">
            <li class="active" id="account"><strong>Data Responden</strong></li>
            <li id="personal"><strong>Pertanyaan Survei</strong></li>
            <?php if($status_saran == 1): ?>
            <li id="payment"><strong>Saran</strong></li>
            <?php endif; ?>
            <li id="completed"><strong>Completed</strong></li>
        </div>
    </div>
    <br>
    <br>

    <div class="row">
        <div class="col-md-8 offset-md-2" style="font-size: 16px; font-family:arial, helvetica, sans-serif;">
            <div class="card shadow mb-4 mt-4" id="kt_blockui_content" data-aos="fade-up" style="border-left: 5px solid #FFA800;">


                <?php if($judul->img_benner == ''): ?>
                <img class="card-img-top" src="<?php echo e(base_url()); ?>assets/img/site/page/banner-survey.jpg" alt="new image" />
                <?php else: ?>
                <img class="card-img-top shadow" src="<?php echo e(base_url()); ?>assets/klien/benner_survei/<?php echo e($manage_survey->img_benner); ?>" alt="new image">
                <?php endif; ?>


                <div class="card-header text-center">
                    <h4><b>DATA RESPONDEN</b> - <?php echo $__env->make('include_backend/partials_backend/_tanggal_survei', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></h4>
                </div>
                <div class="card-body">

                    <form action="<?php echo $form_action ?>" class="form_responden" method="POST">

                        <span style="color: red; font-style: italic;"><?php echo validation_errors(); ?></span>

                        <input name="id_surveyor" value="<?php echo e($surveyor); ?>" hidden>

                        <div class="form-group">
                            <label class="font-weight-bold"><b>Jenis Pelayanan yang diterima <span class="text-danger">*</span></b></label>

                            <?php if($manage_survey->is_layanan_survei == 2): ?>

                            <div class="row mt-3">
                                <div class="col-md-1"></div>
                                <div class="col-md-11">
                                    <div class="checkbox-list layanan-survei">
                                        <?php $__currentLoopData = $ci->db->get_where("layanan_survei_$manage_survey->table_identity", array('is_active' => 1))->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <label class="checkbox">
                                            <input type="checkbox" name="id_layanan_survei[]" value="<?php echo e($row->id); ?>" required>
                                            <span></span> <?php echo e($row->nama_layanan); ?>

                                        </label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>

                            <?php else: ?>

                            <?php
                            echo form_dropdown($id_layanan_survei);
                            ?>

                            <?php endif; ?>


                        </div>

                        <br>



                        <?php $__currentLoopData = $profil_responden->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php 
                        if($row->is_required==1){
                            $wajib = 'required';
                            $wajib2 = '<span class="text-danger">*</span>';
                        }else{ 
                            $wajib = '';
                            $wajib2 = '';
                        }
                        ?>
                        <div class="form-group">
                            <label class="font-weight-bold"><b><?php echo e($row->nama_profil_responden); ?> <?php echo $wajib2; ?></b></label>

                            <?php if($row->jenis_isian == 2): ?>
                            <input class="form-control" type="<?php echo e($row->type_data); ?>" name="<?php echo e($row->nama_alias); ?>" placeholder="Masukkan data anda ..." <?php echo e($wajib); ?>>

                            <?php else: ?>
                            <select class="form-control" name="<?php echo e($row->nama_alias); ?>" id="<?php echo e($row->nama_alias); ?>" <?php echo e($wajib); ?>>
                                <option value="">Please Select</option>

                                <?php $__currentLoopData = $kategori_profil_responden->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($value->id_profil_responden == $row->id): ?>

                                <option value="<?php echo e($value->id); ?>" id="<?php echo e($value->nama_kategori_profil_responden); ?>">
                                    <?php echo e($value->nama_kategori_profil_responden); ?>

                                </option>

                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </select>

                            <?php if($row->is_lainnya == 1): ?>
                            <input class="form-control mt-5" type="text" name="<?php echo e($row->nama_alias); ?>_lainnya" id="<?php echo e($row->nama_alias); ?>_lainnya" placeholder="Sebutkan Lainnya ..." style="display: none;">
                            <?php endif; ?>

                            <?php endif; ?>
                        </div>

                        </br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                </div>
                <div class="card-footer">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-left">
                                <?php echo anchor(base_url().'survei/'.$ci->uri->segment(2), '<i class="fa fa-arrow-left"></i>
                                Kembali',
                                ['class' => 'btn btn-secondary btn-lg font-weight-bold shadow tombolCancel']); ?>

                            </td>
                            <td class="text-right">
                                <button type="submit" class="btn btn-warning btn-lg font-weight-bold shadow tombolSave" onclick="preventBack()">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                            </td>
                        </tr>
                    </table>
                </div>
                </form>
            </div>


            <br><br>
        </div>
    </div>
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>

<script>
    $(function() {
        var requiredCheckboxes = $('.layanan-survei :checkbox[required]');
        requiredCheckboxes.change(function() {
            if (requiredCheckboxes.is(':checked')) {
                requiredCheckboxes.removeAttr('required');
            } else {
                requiredCheckboxes.attr('required', 'required');
            }
        });
    });
</script>

<script>
    $('.form_responden').submit(function(e) {

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            cache: false,
            beforeSend: function() {
                $('.tombolCancel').attr('disabled', 'disabled');
                $('.tombolSave').attr('disabled', 'disabled');
                $('.tombolSave').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

                KTApp.block('#kt_blockui_content', {
                    overlayColor: '#FFA800',
                    state: 'primary',
                    message: 'Processing...'
                });

                setTimeout(function() {
                    KTApp.unblock('#kt_blockui_content');
                }, 500);

            },
            complete: function() {
                $('.tombolCancel').removeAttr('disabled');
                $('.tombolSave').removeAttr('disabled');
                $('.tombolSave').html('Selanjutnya <i class="fa fa-arrow-right"></i>');
            },

            error: function(e) {
                Swal.fire(
                    'Gagal Menyimpan Data Survei!',
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
                    // toastr["success"]('Data berhasil disimpan');

                    setTimeout(function() {
                        window.location.href =
                            "<?php echo base_url() . 'survei/' . $ci->uri->segment(2) . '/pertanyaan/' ?>" +
                            data.uuid;
                    }, 500);
                }
            }
        })
        return false;
    });
</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<?php
$profil_responden_js = $ci->db->query("SELECT * FROM
profil_responden_$manage_survey->table_identity WHERE jenis_isian = 1 && is_lainnya = 1");
?>

<?php $__currentLoopData = $profil_responden_js->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pr_js): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<script type='text/javascript'>
    $(window).load(function() {
        $("#<?php echo e($pr_js->nama_alias); ?>").change(function() {
            console.log(document.getElementById("<?php echo e($pr_js->nama_alias); ?>").options['Lainnya'].selected);

            if (document.getElementById("<?php echo e($pr_js->nama_alias); ?>").options['Lainnya'].selected == true) {
                $('#<?php echo e($pr_js->nama_alias); ?>_lainnya').show().prop('required', true);
            } else {
                $('#<?php echo e($pr_js->nama_alias); ?>_lainnya').removeAttr('required').hide();
            }
        });
    });
</script>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<script type="text/javascript">
    function preventBack() {
        window.history.forward();
    }
    setTimeout("preventBack()", 0);
    window.onunload = function() {
        null
    };
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('include_backend/_template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp-7.3.33\htdocs\surveiku_spak_spkp\application\views/survei/form_data_responden.blade.php ENDPATH**/ ?>