<?php
$user = $this->ion_auth->user()->row();
$title_header = unserialize($manage_survey->title_header_survey);
$title_1 = $title_header[0];
$title_2 = $title_header[1];
?>


<table style="padding:0; width: 95%; margin-left: auto; margin-right: auto;" class="table-list">
    <tr>
        <td class="th-td-draf" style="text-align:left; font-size: 11px;">
            <table>
                <tr>
                    <td width="10%" style="padding-left: 8px;">
                        <?php if ($user->foto_profile == NULL) : ?>
                            <img src="<?php echo base_url() ?>assets/klien/foto_profile/200px.jpg" height="60" alt="">
                        <?php else : ?>
                            <img src="<?php echo base_url() ?>assets/klien/foto_profile/<?php echo $user->foto_profile ?>" height="60" alt="">
                        <?php endif; ?>
                    </td>
                    <td class="text-right">
                        <div style="font-size:13px; font-weight:bold; padding-left: 8px;">
                            <?php echo $title_1 . '<br>' . strtoupper($title_2) ?>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table style="padding:0; width: 95%; margin-left: auto; margin-right: auto;" class="table-list">
    <tr>
        <td class="th-td-draf" style="text-align:center; height:35px;">Dalam rangka mengukur indeks persepsi anti korupsi dan indeks persepsi kualitas pelayanan, Saudara dipercaya menjadi responden pada kegiatan survei ini. Atas kesediaan Saudara kami sampaikan terima kasih dan penghargaan sedalam-dalamnya.</td>
    </tr>
</table>

<table style="padding:0; width: 95%; margin-left: auto; margin-right: auto;" class="table-list">
    <tr>
        <td colspan="2" class="th-td-draf" style="text-align:left; background-color: black; color:white;">
            <b>DATA RESPONDEN</b> (Berilah tanda silang (x) sesuai jawaban Saudara pada kolom yang tersedia)
        </td>
    </tr>

    <tr>
        <td width=" 30%" class="th-td-draf" style="text-align:left;">Jenis Pelayanan yang diterima</td>
        <td width="70%" class="th-td-draf" style="text-align:left;">

            <?php foreach ($this->db->get_where("layanan_survei_$table_identity", ['is_active' => 1])->result() as $value) { ?>
                <input type="checkbox"> <?php echo $value->nama_layanan ?>
                <br>
            <?php } ?>

        </td>
    </tr>


    <?php foreach ($this->db->order_by('urutan', 'asc')->get("profil_responden_$table_identity")->result() as $row) { ?>
        <tr>
            <td width=" 30%" class="th-td-draf" style="text-align:left;"><?= $row->nama_profil_responden ?></td>
            <td width="70%" class="th-td-draf" style="text-align:left;">

                <?php if ($row->jenis_isian == 1) { ?>
                    <?php foreach ($this->db->get_where("kategori_profil_responden_$table_identity", ['id_profil_responden' => $row->id])->result() as $value) { ?>

                        <input type="checkbox"> <?php echo $value->nama_kategori_profil_responden ?>
                        <br>

                    <?php } ?>

                <?php } ?>

            </td>
        </tr>

    <?php } ?>
</table>


<table style="padding:0; width: 95%; margin-left: auto; margin-right: auto;" class="table-list">
    <tr>
        <td class="th-td-draf" style="text-align:left; background-color: black; color:white;">
            <b>PENILAIAN TERHADAP UNSUR-UNSUR PERSEPSI ANTI KORUPSI DAN KUALITAS PELAYANAN</b>
        </td>
    </tr>

    <tr>
        <td class="th-td-draf" style="text-align:left; background-color: black; color:white;">
            Berikan tanda silang (x) sesuai jawaban Saudara.
        </td>
    </tr>
</table>


<table style="padding:0; width: 95%; margin-left: auto; margin-right: auto; text-align:center;" class="table-list">
    <tr style="background-color:#C7C6C1">
        <td rowspan="2" width="5%" class="th-td-draf">No</td>
        <td rowspan="2" width="32%" class="th-td-draf">PERTANYAAN</td>
        <td colspan="4" width="40%" class="th-td-draf">PILIHAN JAWABAN</td>
        <td rowspan="2" width="23%" class="th-td-draf">Berikan alasan jika pilihan jawaban: 1 atau 2
        </td>
    </tr>
    <tr style="background-color:#C7C6C1">
        <td class="th-td-draf">1</td>
        <td class="th-td-draf">2</td>
        <td class="th-td-draf">3</td>
        <td class="th-td-draf">4</td>
    </tr>
</table>


<?php
//PERTANYAAN TERBUKA ATAS
if (in_array(2, unserialize($manage_survey->atribut_pertanyaan_survey))) {

    foreach ($this->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian_pertanyaan_terbuka, (SELECT DISTINCT(dengan_isian_lainnya) FROM isi_pertanyaan_ganda_$table_identity WHERE isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id) AS dengan_isian_lainnya
                    FROM pertanyaan_terbuka_$table_identity
                    JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
                    WHERE pertanyaan_terbuka_$table_identity.is_letak_pertanyaan = 1")->result() as $value) { ?>

        <?php if ($value->id_jenis_pilihan_jawaban == 2) { ?>

            <table style="padding:0; width: 95%; margin-left: auto; margin-right: auto;" class="table-list">
                <tr>
                    <td width="5%" style="text-align:center;" class="th-td-draf"><?= $value->nomor_pertanyaan_terbuka ?></td>
                    <td width="32%" style="text-align:left;" class="th-td-draf"><?= $value->isi_pertanyaan_terbuka ?></td>
                    <td width="63%" class="th-td-draf"></td>
                </tr>
            </table>

        <?php } else {

                    $pilihan_terbuka = $this->db->get_where("isi_pertanyaan_ganda_$table_identity", array('id_perincian_pertanyaan_terbuka' => $value->id_perincian_pertanyaan_terbuka));

                    if ($value->dengan_isian_lainnya == 1) {
                        $isi = $pilihan_terbuka->num_rows() + 2;
                    } else {
                        $isi = $pilihan_terbuka->num_rows() + 1;
                    }
                    ?>

            <table style="padding:0; width: 95%; margin-left: auto; margin-right: auto;" class="table-list">
                <tr>
                    <td rowspan="<?= $isi ?>" width="5%" style="text-align:center;" class="th-td-draf"><?= $value->nomor_pertanyaan_terbuka ?></td>
                    <td rowspan="<?= $isi ?>" width="32%" style="text-align:left;" class="th-td-draf"><?= $value->isi_pertanyaan_terbuka ?></td>
                    <td class="th-td-draf" colspan="2"></td>
                </tr>


                <?php foreach ($pilihan_terbuka->result() as $get) { ?>
                    <tr>
                        <td class="th-td-draf" width="5%"></td>
                        <td class="th-td-draf" style="text-align: left; background-color:#C7C6C1;"><?= $get->pertanyaan_ganda ?></td>
                    </tr>
                <?php } ?>


                <?php if ($value->dengan_isian_lainnya == 1) { ?>
                    <tr>
                        <td class="th-td-draf" width="5%"></td>
                        <td class="th-td-draf" style="text-align: left; background-color:#C7C6C1;">Lainnya</td>
                    </tr>
                <?php } ?>



            </table>

<?php }
    }
} ?>



<?php foreach($this->db->get('produk')->result() as $pdk) { ?>

    <table style="padding:0; width: 95%; margin-left: auto; margin-right: auto;" class="table-list">
        <tr>
            <th align="left" class="th-td-draf"><?= strtoupper($pdk->nama) ?></th>
        </tr>
    </table>

<?php
//PERTANYAAN UNSUR
$pertanyaan_unsur = $this->db->query("SELECT *, (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_kategori_unsur_pelayanan = 1 ) AS pilihan_1,
(SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_kategori_unsur_pelayanan = 2 ) AS pilihan_2,
(SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_kategori_unsur_pelayanan = 3 ) AS pilihan_3,
(SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_kategori_unsur_pelayanan = 4 ) AS pilihan_4
FROM pertanyaan_unsur_pelayanan_$table_identity
JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
WHERE is_produk = $pdk->id
ORDER BY SUBSTR(nomor_unsur,2) + 0 ASC");

foreach ($pertanyaan_unsur->result() as $row) {
    ?>

    <table style="padding:0; width: 95%; margin-left: auto; margin-right: auto;" class="table-list">
        <tr>
            <td rowspan="2" width="5%" style="text-align:center;" class="th-td-draf">
                <?= $row->nomor_unsur ?>
            </td>

            <td width="32%" rowspan="2" style="text-align:left;" class="th-td-draf">
                <?= $row->isi_pertanyaan_unsur ?>
            </td>

            <td width="10%" style="background-color:#C7C6C1; text-align:center;" class="th-td-draf">
                <?= $row->pilihan_1 ?>
            </td>

            <td width="10%" style="background-color:#C7C6C1; text-align:center;" class="th-td-draf">
                <?= $row->pilihan_2 ?>
            </td>

            <td width="10%" style="background-color:#C7C6C1; text-align:center;" class="th-td-draf">
                <?= $row->pilihan_3 ?>
            </td>

            <td width="10%" style="background-color:#C7C6C1; text-align:center;" class="th-td-draf">
                <?= $row->pilihan_4 ?>
            </td>

            <td width="23%" rowspan="2" style="text-align:left;" class="th-td-draf"></td>
        </tr>

        <tr>
            <th class="th-td-draf"></th>
            <th class="th-td-draf"></th>
            <th class="th-td-draf"></th>
            <th class="th-td-draf"></th>
        </tr>
    </table>


    <?php
        //PERTANYAAN TERBUKA
        if (in_array(2, unserialize($manage_survey->atribut_pertanyaan_survey))) {

            foreach ($this->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian_pertanyaan_terbuka, (SELECT DISTINCT(dengan_isian_lainnya) FROM isi_pertanyaan_ganda_$table_identity WHERE isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id) AS dengan_isian_lainnya
                    FROM pertanyaan_terbuka_$table_identity
                    JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
                    WHERE  id_unsur_pelayanan = $row->id_unsur_pelayanan")->result() as $value) { ?>

            <?php if ($value->id_jenis_pilihan_jawaban == 2) { ?>

                <table style="padding:0; width: 95%; margin-left: auto; margin-right: auto;" class="table-list">
                    <tr>
                        <td width="5%" style="text-align:center;" class="th-td-draf"><?= $value->nomor_pertanyaan_terbuka ?></td>
                        <td width="32%" style="text-align:left;" class="th-td-draf"><?= $value->isi_pertanyaan_terbuka ?></td>
                        <td width="63%" class="th-td-draf"></td>
                    </tr>
                </table>

            <?php } else {

                            $pilihan_terbuka = $this->db->get_where("isi_pertanyaan_ganda_$table_identity", array('id_perincian_pertanyaan_terbuka' => $value->id_perincian_pertanyaan_terbuka));

                            if ($value->dengan_isian_lainnya == 1) {
                                $isi = $pilihan_terbuka->num_rows() + 2;
                            } else {
                                $isi = $pilihan_terbuka->num_rows() + 1;
                            }
                            ?>

                <table style="padding:0; width: 95%; margin-left: auto; margin-right: auto;" class="table-list">
                    <tr>
                        <td rowspan="<?= $isi ?>" width="5%" style="text-align:center;" class="th-td-draf"><?= $value->nomor_pertanyaan_terbuka ?></td>
                        <td rowspan="<?= $isi ?>" width="32%" style="text-align:left;" class="th-td-draf"><?= $value->isi_pertanyaan_terbuka ?></td>
                        <td class="th-td-draf" colspan="2"></td>
                    </tr>


                    <?php foreach ($pilihan_terbuka->result() as $get) { ?>
                        <tr>
                            <td class="th-td-draf" width="5%"></td>
                            <td class="th-td-draf" style="text-align: left; background-color:#C7C6C1;"><?= $get->pertanyaan_ganda ?></td>
                        </tr>
                    <?php } ?>


                    <?php if ($value->dengan_isian_lainnya == 1) { ?>
                        <tr>
                            <td class="th-td-draf" width="5%"></td>
                            <td class="th-td-draf" style="text-align: left; background-color:#C7C6C1;">Lainnya</td>
                        </tr>
                    <?php } ?>



                </table>

    <?php } } } ?>

<?php } ?>

<?php } ?>



<?php
//PERTANYAAN TERBUKA BAWAH
if (in_array(2, unserialize($manage_survey->atribut_pertanyaan_survey))) {

    foreach ($this->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian_pertanyaan_terbuka, (SELECT DISTINCT(dengan_isian_lainnya) FROM isi_pertanyaan_ganda_$table_identity WHERE isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id) AS dengan_isian_lainnya
                    FROM pertanyaan_terbuka_$table_identity
                    JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
                    WHERE pertanyaan_terbuka_$table_identity.is_letak_pertanyaan = 2")->result() as $value) { ?>

        <?php if ($value->id_jenis_pilihan_jawaban == 2) { ?>

            <table style="padding:0; width: 95%; margin-left: auto; margin-right: auto;" class="table-list">
                <tr>
                    <td width="5%" style="text-align:center;" class="th-td-draf"><?= $value->nomor_pertanyaan_terbuka ?></td>
                    <td width="32%" style="text-align:left;" class="th-td-draf"><?= $value->isi_pertanyaan_terbuka ?></td>
                    <td width="63%" class="th-td-draf"></td>
                </tr>
            </table>

        <?php } else {

                    $pilihan_terbuka = $this->db->get_where("isi_pertanyaan_ganda_$table_identity", array('id_perincian_pertanyaan_terbuka' => $value->id_perincian_pertanyaan_terbuka));

                    if ($value->dengan_isian_lainnya == 1) {
                        $isi = $pilihan_terbuka->num_rows() + 2;
                    } else {
                        $isi = $pilihan_terbuka->num_rows() + 1;
                    }
                    ?>

            <table style="padding:0; width: 95%; margin-left: auto; margin-right: auto;" class="table-list">
                <tr>
                    <td rowspan="<?= $isi ?>" width="5%" style="text-align:center;" class="th-td-draf"><?= $value->nomor_pertanyaan_terbuka ?></td>
                    <td rowspan="<?= $isi ?>" width="32%" style="text-align:left;" class="th-td-draf"><?= $value->isi_pertanyaan_terbuka ?></td>
                    <td class="th-td-draf" colspan="2"></td>
                </tr>


                <?php foreach ($pilihan_terbuka->result() as $get) { ?>
                    <tr>
                        <td class="th-td-draf" width="5%"></td>
                        <td class="th-td-draf" style="text-align: left; background-color:#C7C6C1;"><?= $get->pertanyaan_ganda ?></td>
                    </tr>
                <?php } ?>


                <?php if ($value->dengan_isian_lainnya == 1) { ?>
                    <tr>
                        <td class="th-td-draf" width="5%"></td>
                        <td class="th-td-draf" style="text-align: left; background-color:#C7C6C1;">Lainnya</td>
                    </tr>
                <?php } ?>



            </table>

<?php }
    }
} ?>




<?php
//PERTANYAAN KUALITATIF
if (in_array(3, unserialize($manage_survey->atribut_pertanyaan_survey))) { ?>

    <table style="padding:0; width: 95%; margin-left: auto; margin-right: auto;" class="table-list">
        <tr>
            <td colspan="3" class="th-td-draf" style="text-align:left; background-color: black; color:white;"><b>PENILAIAN KUALITATIF PERSEPSI ANTI KORUPSI</b></td>
        </tr>

        <tr>
            <td colspan="3" class="th-td-draf" style="text-align:left; background-color: black; color:white;">Berikan jawaban sesuai dengan pendapat dan pengetahuan Saudara.</td>
        </tr>

        <tr>
            <td width="5%" class="th-td-draf" style="background-color:#C7C6C1;">NO</td>
            <td width="32%" class="th-td-draf" style="background-color:#C7C6C1;">PERTANYAAN</td>
            <td width="63%" class="th-td-draf" style="background-color:#C7C6C1;">JAWABAN</td>
        </tr>

    </table>

    <?php
        $no_kualitatif = 1;
        foreach ($this->db->get_where("pertanyaan_kualitatif_$table_identity", array('is_active' => 1))->result() as $row) { ?>
        <table style="padding:0; width: 95%; margin-left: auto; margin-right: auto;" class="table-list">
            <tr>
                <td width="5%" class="th-td-draf"><?= $no_kualitatif++ ?></td>
                <td width="32%" class="th-td-draf" style="text-align: left;"><?= $row->isi_pertanyaan ?></td>
                <td width="63%" class="th-td-draf"></td>
            </tr>
        </table>

<?php }
} ?>




<?php
//SARAN
if ($manage_survey->is_saran == 1) { ?>
    <table style="padding:0; width: 95%; margin-left: auto; margin-right: auto;" class="table-list">
        <tr>
            <td class="th-td-draf" style="text-align:left;"><b><?= strtoupper($manage_survey->judul_form_saran) ?> :</b>
                <br>
                <br>
                <br>
                <br>
            </td>
        </tr>
    </table>
<?php } ?>


<table style="padding:0; width: 95%; margin-left: auto; margin-right: auto;" class="table-list">
    <tr>
        <td class="th-td-draf" style="text-align:center;">Terima kasih atas kesediaan Saudara mengisi kuesioner tersebut di atas.<br>Saran dan penilaian Saudara memberikan konstribusi yang sangat berarti bagi instansi ini.
        </td>
    </tr>
</table>