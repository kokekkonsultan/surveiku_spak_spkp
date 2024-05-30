<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak</title>
    <style>
    table {
        border-collapse: collapse;
        font-family: sans-serif;
        font-size: .8rem;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }

    th,
    td {
        padding: 10px;
        font-size: 12px;
        font-family: 'Times New Roman', Times, serif;
    }
    </style>
</head>

<body>

    <div style="overflow-x:auto;">
        <table class="table table-bordered table-hover" cellspacing="0" width="100%">
            <thead>
                <tr style="background-color: yellow; text-align:center; ">
                    <td colspan="<?php echo $colspan ?>" style="font-size: 20px; font-weight:bold;">
                        DATA PEROLEHAN SURVEI
                    </td>
                </tr>
                <tr style="background-color: #f2f2f2;">
                    <th>N0.</th>
                    <th>STATUS PENGISIAN</th>
                    <th>Jenis Layanan yang diterima</th>

                    <?php foreach ($profil->result() as $row) { ?>
                    <th><?php echo $row->nama_profil_responden ?></th>
                    <?php } ?>

                    <?php foreach ($unsur->result() as $row) { ?>
                    <th><?php echo $row->nomor_unsur ?></th>
                    <?php } ?>

                    <?php if (in_array(1, $atribut_pertanyaan)) {
                        foreach ($unsur->result() as $value) { ?>
                    <th>H<?php echo $value->nomor_harapan ?></th>
                    <?php }
                    } ?>

                    <?php foreach ($unsur->result() as $value) { ?>
                    <th>A<?php echo $value->nomor_harapan ?></th>
                    <?php } ?>

                    <?php if ($is_saran == 1) { ?>
                    <th>SARAN</th>
                    <?php } ?>

                    <th>Waktu Isi</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $no = 1;
                foreach ($survey->result() as $row) {

                    $id_layanan_survei = implode(", ", unserialize($row->id_layanan_survei));
                    $data_nama = [];
                    foreach($this->db->query("SELECT * FROM layanan_survei_$manage_survey->table_identity WHERE id IN ($id_layanan_survei)")->result() as $val){
                        $data_nama[] = '<li>' . $val->nama_layanan . '</li>';
                    }
                    $nama_layanan = implode(" ", $data_nama);
                ?>

                <tr>
                    <td><?php echo $no++ ?></td>

                    <td style="color: blue;">VALID</td>

                    <td><?php echo $nama_layanan ?></td>


                    <?php foreach ($profil->result() as $get) {
                            $profil_get = $get->nama_alias; ?>
                    <th><?php echo $row->$profil_get ?></th>
                    <?php } ?>


                    <?php foreach ($jawaban_unsur->result() as $value) {
                            if ($value->id_responden == $row->id_responden) { ?>
                    <td><?php echo $value->skor_jawaban ?></td>
                    <?php }
                        } ?>

                    <?php if (in_array(1, $atribut_pertanyaan)) {
                            foreach ($jawaban_harapan->result() as $value) {
                                if ($value->id_responden == $row->id_responden) { ?>
                    <td><?php echo $value->skor_jawaban ?></td>
                    <?php }
                            }
                        } ?>

                    <?php foreach ($jawaban_unsur->result() as $value) {
                            if ($value->id_responden == $row->id_responden) { ?>
                    <td><?php echo $value->alasan_pilih_jawaban ?></td>
                    <?php }
                        } ?>

                    <?php if ($is_saran == 1) { ?>
                    <td><?php echo $row->saran ?></td>
                    <?php } ?>

                    <td><?php echo date("H:i:s d-m-Y", strtotime($row->waktu_isi)) ?></td>
                </tr>
                <?php
                } ?>
            </tbody>
        </table>
    </div>
</body>

</html>