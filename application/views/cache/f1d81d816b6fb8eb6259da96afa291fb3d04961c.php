

<?php
$ci = get_instance();
?>

<?php $__env->startSection('style'); ?>
<link href="<?php echo e(TEMPLATE_BACKEND_PATH); ?>plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"
    type="text/css" />
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
                style="height: 150px; background-color: #1c2840; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(/assets/img/banner/taieri.svg)"
                data-aos="fade-down">
                <div class="card-body d-flex align-items-center">
                    <div>
                        <h3 class="text-white font-weight-bolder line-height-lg mb-5">
                            TABULASI DAN <?php echo e(strtoupper($title)); ?>

                        </h3>

                        <span class="btn btn-light btn-sm font-weight-bold">
                            <i class="fa fa-bookmark"></i> <strong><?php echo $jumlah_kuisioner; ?></strong> Kuesioner
                            Lengkap
                        </span>
                    </div>
                </div>
            </div>

            <div class="card card-custom card-sticky" data-aos="fade-down">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%"
                            style="font-size: 12px;">
                            <thead class="bg-light">
                                <tr>
                                    <th rowspan="2" class="text-center">No.</th>
                                    <th rowspan="2" class="text-center">Nama Lengkap</th>

                                    <?php
                                    $produk = $ci->db->query("SELECT *, (SELECT COUNT(id_unsur_pelayanan) FROM
                                    unsur_pelayanan_$profiles->table_identity JOIN
                                    pertanyaan_unsur_pelayanan_$profiles->table_identity ON
                                    unsur_pelayanan_$profiles->table_identity.id =
                                    pertanyaan_unsur_pelayanan_$profiles->table_identity.id_unsur_pelayanan WHERE
                                    is_produk = produk.id) AS jumlah_unsur
                                    FROM produk");
                                    ?>

                                    <?php $__currentLoopData = $produk->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th colspan="<?php echo e($row->jumlah_unsur); ?>" class="text-center bg-white text-primary">
                                        <?php echo e($row->nama . ' (' . $row->nama_alias . ')'); ?></th>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tr>
                                <tr>

                                    <?php $__currentLoopData = $unsur->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th><?php echo e($row->nomor_unsur); ?></th>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <div class="card card-body mt-5">
                <h6 class="text-primary">Indeks Persepsi Kualitas Pelayanan</h6>
                <hr>
                <br>
                <div class="table-responsive">
                    <table width="100%" class="table table-bordered" style="font-size: 12px;">
                        <tr align="center">
                            <th></th>
                            <?php $__currentLoopData = $unsur->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($val->is_produk == 1): ?>
                            <th class="bg-primary text-white"><?php echo e($val->nomor_unsur); ?></th>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>

                        <tr>
                            <th class="bg-light">TOTAL</th>
                            <?php $__currentLoopData = $total->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($val->is_produk == 1): ?>
                            <th class="text-center"><?php echo e(ROUND($val->sum_skor_jawaban, 3)); ?></th>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>

                        <tr>
                            <th class="bg-light">Rata-Rata</th>
                            <?php $__currentLoopData = $total->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($val->is_produk == 1): ?>
                            <td class="text-center"><?php echo e(ROUND($val->rata_rata, 3)); ?></td>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>

                        <tr>
                            <th class="bg-light">Nilai per Unsur</th>
                            <?php $__currentLoopData = $nilai_spkp->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <th colspan="<?php echo e($val->colspan); ?>" class="text-center">
                                <?php echo e(ROUND($val->nilai_per_unsur, 3)); ?>

                            </th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>


                        <tr>
                            <th class="bg-light">Rata-Rata * Bobot</th>
                            <?php $__currentLoopData = $nilai_spkp->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            $nilai_bobot_spkp[] = $val->rata_rata_bobot;
                            $nilai_tertimbang_spkp = array_sum($nilai_bobot_spkp);
                            $index_spkp = ROUND($nilai_tertimbang_spkp * $skala_likert, 10);

                            $colspan_spkp[] = $val->colspan;
                            $jumlah_spkp = array_sum($colspan_spkp);
                            ?>
                            <th colspan="<?php echo e($val->colspan); ?>" class="text-center">
                                <?php echo e(ROUND($val->rata_rata_bobot, 3)); ?>

                            </th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>

                        <tr>
                            <th class="bg-light">Indeks</th>
                            <th colspan="<?php echo e($jumlah_spkp); ?>"><?php echo e(ROUND($nilai_tertimbang_spkp, 3)); ?></th>
                        </tr>


                        <tr>
                            <th class="bg-light">Nilai Konversi
                                <!--Rata2 Tertimbang-->
                            </th>
                            <th colspan="<?php echo e($jumlah_spkp); ?>"><?php echo e(ROUND($index_spkp, 2)); ?></th>
                        </tr>


                        <?php
                        foreach ($definisi_skala->result() as $val) {
                            if ($index_spkp <= $val->range_bawah && $index_spkp >= $val->range_atas) {
                                $kategori_spkp = $val->kategori;
                                $mutu_spkp = $val->mutu;
                            }
                        }
                        if ($index_spkp <= 0) {
                            $kategori_spkp = 'NULL';
                            $mutu_spkp = 'NULL' ;
                        }
                        ?>
                        <tr>
                            <th class="bg-light">PREDIKAT</th>
                            <th colspan="<?php echo e($jumlah_spkp); ?>"><?php echo e($mutu_spkp); ?></th>
                        </tr>

                        <tr>
                            <th class="bg-light">KATEGORI</th>
                            <th colspan="<?php echo e($jumlah_spkp); ?>"><?php echo e($kategori_spkp); ?></th>
                        </tr>
                    </table>
                </div>
            </div>



            <div class="card card-body mt-5">
                <h6 class="text-primary">Indeks Persepsi Anti Korupsi</h6>
                <hr>
                <br>
                <div class="table-responsive">
                    <table width="100%" class="table table-bordered" style="font-size: 12px;">
                        <tr align="center">
                            <th></th>
                            <?php $__currentLoopData = $unsur->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($row->is_produk == 2): ?>
                            <th class="bg-primary text-white"><?php echo e($row->nomor_unsur); ?></th>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>

                        <tr>
                            <th class="bg-light">TOTAL</th>
                            <?php $__currentLoopData = $total->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($row->is_produk == 2): ?>
                            <th class="text-center"><?php echo e(ROUND($row->sum_skor_jawaban, 3)); ?></th>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>

                        <tr>
                            <th class="bg-light">Rata-Rata</th>
                            <?php $__currentLoopData = $total->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($row->is_produk == 2): ?>
                            <td class="text-center"><?php echo e(ROUND($row->rata_rata, 3)); ?></td>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>

                        <tr>
                            <th class="bg-light">Nilai per Unsur</th>
                            <?php $__currentLoopData = $nilai_spak->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <th colspan="<?php echo e($row->colspan); ?>" class="text-center">
                                <?php echo e(ROUND($row->nilai_per_unsur, 3)); ?>

                            </th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>


                        <tr>
                            <th class="bg-light">Rata-Rata * Bobot</th>
                            <?php $__currentLoopData = $nilai_spak->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            $nilai_bobot_spak[] = $row->rata_rata_bobot;
                            $nilai_tertimbang_spak = array_sum($nilai_bobot_spak);
                            $index_spak = ROUND($nilai_tertimbang_spak * $skala_likert, 10);

                            $colspan_spak[] = $row->colspan;
                            $jumlah_spak = array_sum($colspan_spak);
                            ?>
                            <th colspan="<?php echo e($row->colspan); ?>" class="text-center">
                                <?php echo e(ROUND($row->rata_rata_bobot, 3)); ?>

                            </th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>

                        <tr>
                            <th class="bg-light">Indeks</th>
                            <th colspan="<?php echo e($jumlah_spak); ?>"><?php echo e(ROUND($nilai_tertimbang_spak, 3)); ?></th>
                        </tr>


                        <tr>
                            <th class="bg-light">Nilai Konversi
                                <!--Rata2 Tertimbang-->
                            </th>
                            <th colspan="<?php echo e($jumlah_spak); ?>"><?php echo e(ROUND($index_spak, 2)); ?></th>
                        </tr>


                        <?php
                        foreach ($definisi_skala->result() as $row) {
                            if ($index_spak <= $row->range_bawah && $index_spak >= $row->range_atas) {
                                $kategori_spak = $row->kategori;
                                $mutu_spak = $row->mutu;
                            }
                        }
                        if ($index_spak <= 0) {
                            $kategori_spak = 'NULL';
                            $mutu_spak = 'NULL';
                        }
                        ?>
                        <tr>
                            <th class="bg-light">PREDIKAT</th>
                            <th colspan="<?php echo e($jumlah_spak); ?>"><?php echo e($mutu_spak); ?></th>
                        </tr>

                        <tr>
                            <th class="bg-light">KATEGORI</th>
                            <th colspan="<?php echo e($jumlah_spak); ?>"><?php echo e($kategori_spak); ?></th>
                        </tr>
                    </table>
                </div>
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
        // paging: true,
        //     dom: 'Blfrtip',
        //     "buttons": [
        //         {
        //             extend: 'collection',
        //             text: 'Export',
        //             buttons: [
        //                 'excel'
        //             ]
        //         }
        //     ],

        "lengthMenu": [
            [5, 10, 25, 50, 100, -1],
            [5, 10, 25, 50, 100, "Semua data"]
        ],
        "pageLength": 5,
        "order": [],
        "language": {
            "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
        },
        "ajax": {
            "url": "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/olah-data/ajax-list' ?>",
            "type": "POST",
            "data": function(data) {}
        },

        "columnDefs": [{
            "targets": [-1],
            "orderable": false,
        }, ],

    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('include_backend/template_backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp-7.3.33\htdocs\surveiku_spak_spkp\application\views/olah_data/index.blade.php ENDPATH**/ ?>