<!--============================================== BAB III =================================================== -->
<div class="page-session">


    <table style="width: 100%;">
        <tr>
            <td style="text-align: center; font-size:16px; font-weight: bold;">
                BAB III
                <br>
                PENGOLAHAN SURVEI
                <br>
                <br>
            </td>
        </tr>
    </table>




    <!-- JENIS PELAYANAN -->
<table style="width: 100%;">
        <tr>
            <td width="3%"><b><?= $no_Bab3++ ?>.</b></td>
            <td><b>Jenis Layanan</b></td>
        </tr>

        <tr>
            <td></td>
            <td class="content-text">Berikut merupakan jenis layanan yang diperoleh dari Survei Persepsi Kualitas Pelayanan (SPKP) dan Survei Persepsi Anti Korupsi (SPAK):</td>
        </tr>

        <tr>
            <td></td>
            <td align="center">
                <p><?= 'Tabel ' .  $no_table++ . '. Jenis Layanan ' ?></p>
                <table style="width: 100%; margin-left: auto; margin-right: auto;" class="table-list">
                    <tr style="background-color:#E4E6EF;">
                        <th class="td-th-list">No</th>
                        <th class="td-th-list">Jenis Pelayanan</th>
                        <th class="td-th-list">Jumlah</th>
                        <th class="td-th-list">Persentase</th>
                    </tr>


                    <?php
                               $responden = $this->db->query("SELECT * FROM responden_$table_identity
                               JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
                               WHERE is_submit = 1");
   
                               $data = [];
                               foreach ($responden->result() as $key => $value) {
                                   $id_layanan_survei = implode(", ", unserialize($value->id_layanan_survei));
                                   $data[$key] = "UNION ALL SELECT *
                                               FROM layanan_survei_$table_identity
                                               WHERE id IN ($id_layanan_survei)";
                               }
                               $tabel_layanan = implode(" ", $data);
   
                               $layanan = $this->db->query("
                               SELECT id, nama_layanan, COUNT(id) - 1 AS perolehan,
                               SUM(Count(id)) OVER () - (SELECT COUNT(id) FROM layanan_survei_$table_identity WHERE is_active = 1) as total_survei
                               FROM (
                                   SELECT * FROM layanan_survei_$table_identity
                                   $tabel_layanan
                                   ) ls
                               WHERE is_active = 1
                               GROUP BY id
                               ");
   
                               $no = 1;
                               foreach ($layanan->result() as $row) {
                                   $perolehan[] = $row->perolehan;
                                   $total_perolehan = array_sum($perolehan);
   
                                   $persentase[] = ($row->perolehan/$row->total_survei) * 100;
                                   $total_persentase  = array_sum($persentase);
                                   ?>
                    <tr>

                        <td class="td-th-list"><?= $no++ ?></td>
                        <td class="td-th-list"><?= $row->nama_layanan ?></td>
                        <td class="td-th-list"><?= $row->perolehan ?></td>
                        <td class="td-th-list"><?= ROUND(($row->perolehan/$row->total_survei) * 100,2) ?> %</td>
                    </tr>
                    <?php } ?>


                    <tr>
                        <th class="td-th-list" colspan="2">TOTAL</th>
                        <th class="td-th-list"><?= $total_perolehan ?></th>
                        <th class="td-th-list"><?= ROUND($total_persentase) ?> %</th>
                    </tr>
                </table>
            </td>
        </tr>
    </table>



    <!-- Analisis Hasil Survei -->
    <table style="width: 100%;">
        <tr>
            <td width="3%"><b><?= $no_Bab3++ ?>.</b></td>
            <td><b>Analisis Hasil Survei</b></td>
        </tr>
    </table>

    <?php foreach($this->db->get('produk')->result() as $z => $pdk) { ?>


        <?php
        #NILAI INDEKS UNSUR
        $bab3nilaiIKM[$z] = $this->db->query("SELECT 
        IF(id_parent = 0, unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub,
        (SELECT nomor_unsur FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) AS nomor_unsur,
        (SELECT nama_unsur_pelayanan FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) AS nama_unsur_pelayanan,
        AVG(IF(jawaban_pertanyaan_unsur_$table_identity.skor_jawaban != 0, skor_jawaban, NULL)) AS rata_rata

        FROM jawaban_pertanyaan_unsur_$table_identity
        JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
        JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
        JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
        WHERE survey_$table_identity.is_submit = 1 && is_produk = $pdk->id
        GROUP BY id_sub
        ORDER BY SUBSTR(nomor_unsur,2) + 0");


        $b = 1;
        foreach ($bab3nilaiIKM[$z]->result() as $n_ikm) {
            foreach ($definisi_skala->result() as $obj) {
                if (($n_ikm->rata_rata * $skala_likert) <= $obj->range_bawah && ($n_ikm->rata_rata * $skala_likert) >= $obj->range_atas) {
                    $ktgUnsur[$z] = $obj->kategori;
                }
            }
            if (($n_ikm->rata_rata * $skala_likert) <= 0) {
                $ktgUnsur[$z] = 'NULL';
            }

            $arrayNomorUnsur[$z][] = '%27' . str_replace(' ', '+', $n_ikm->nomor_unsur) . '%27';
            $arrayRataRataNilaiIKM_1[$z][] = ROUND($n_ikm->rata_rata, 3);
            $arrayRataRataNilaiIKM_2[$z][] = $n_ikm->rata_rata;
            $nilaiIKM[$z] = array_sum($arrayRataRataNilaiIKM_2[$z]) / count($arrayRataRataNilaiIKM_2[$z]);
            $arrayNilaiIKM[$z][] = '<tr>
                                            <td width="5%" class="td-th-list">' . $b++ . '</td>
                                            <td class="td-th-list" align="left">' . $n_ikm->nomor_unsur . '. ' . $n_ikm->nama_unsur_pelayanan . '</td>
                                            <td class="td-th-list">' . Round($n_ikm->rata_rata, 2) . '</td>
                                            <td class="td-th-list">' . $ktgUnsur[$z] . '</td>
                                        </tr>';
        }

        foreach ($definisi_skala->result() as $obj) {
            if (($nilaiIKM[$z] * $skala_likert) <= $obj->range_bawah && ($nilaiIKM[$z] * $skala_likert) >= $obj->range_atas) {
                $ktgNilaiIKM[$z] = $obj->kategori;
            }
        }
        if (($nilaiIKM[$z] * $skala_likert) <= 0) {
            $ktgNilaiIKM[$z] = 'NULL';
        } ?>



    <table style="width: 100%;">
        <tr>
            <td width="3%"></td>
            <td width="3%"><b><?= $pdk->id == 1 ? 'A' : 'B' ?>.</b></td>
            <td><b><?= $pdk->nama ?></b></td>
        </tr>
        <tr>
            <td width="3%"></td>
            <td width="3%"></td>
            <td class="content-text"><?= 'Hasil ' . $pdk->nama . ' (' . $pdk->nama_alias . ') ' . $manage_survey->organisasi . ' mendapatkan nilai ' . $pdk->nama_indeks . ' sebesar <b>' . ROUND($nilaiIKM[$z] * $skala_likert, 2) . '</b>, dengan predikat <b>' . $ktgNilaiIKM[$z] . '</b>. Nilai ' . $pdk->nama_indeks . ' tersebut didapat dari nilai rata-rata seluruh unsur pada tabel berikut.' ?></td>
        </tr>
        <tr>
            <td width="3%"></td>
            <td width="3%"></td>
            <td align="center">
                <p><?= 'Tabel ' .  $no_table++ . '. Nilai Unsur ' . $manage_survey->organisasi ?></p>
                <table style="width: 100%; margin-left: auto; margin-right: auto;" class="table-list">
                    <tr style="background-color:#E4E6EF;">
                        <th class="td-th-list" width="3%">No</th>
                        <th class="td-th-list" width="60%">Unsur</th>
                        <th class="td-th-list" width="17%">Nilai Indeks</th>
                        <th class="td-th-list" width="25%">Predikat</th>
                    </tr>
                    <?= implode("", $arrayNilaiIKM[$z]) ?>
                    <tr>
                        <td class="td-th-list" colspan="2"><b>Nilai <?= $pdk->nama_indeks ?></b></td>
                        <td class="td-th-list"><b><?= ROUND($nilaiIKM[$z], 3) ?></b></td>
                        <td class="td-th-list"><?= $ktgNilaiIKM[$z] ?></td>
                    </tr>
                    <tr>
                        <td class="td-th-list" colspan="2"><b>Nilai Konversi</b></td>
                        <td class="td-th-list"><b><?= ROUND($nilaiIKM[$z] * $skala_likert, 2) ?></b></td>
                        <td class="td-th-list"><b><?= $ktgNilaiIKM[$z] ?></b></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td class="content-text">Nilai unsur <?= $pdk->nama . ' (' . $pdk->nama_alias . ')' ?> pada
                <?= $manage_survey->organisasi ?> dapat dilihat pada gambar di bawah ini.</td>
        </tr>


        <tr>
            <td></td>
            <td></td>
            <td align="center">
                <img src='data:image/png;base64,<?= base64_encode(file_get_contents(@"https://quickchart.io/chart?bg=white&h=" . (50 + (count($arrayNomorUnsur[$z]) * 40)) . "&c={type:'horizontalBar',data:{labels:[" . implode(",", $arrayNomorUnsur[$z]) . "],datasets:[{label:'Dataset1',backgroundColor:'rgb(79,129,189)',stack:'Stack0',data:[" . implode(",", $arrayRataRataNilaiIKM_1[$z]) . "],},],},options:{title:{display:true,text:'Nilai+Unsur'},legend:{display:false},plugins:{roundedBars:true,datalabels:{anchor:'center',align:'center',color:'white',font:{weight:'normal',},},},responsive:true,},}"))  ?>' width="75%">
                <p><?= 'Gambar ' .  $no_gambar++ . '. Grafik Unsur ' . $manage_survey->organisasi ?></p>
            </td>
        </tr>
    </table>
    

    <!--Pembahasan Unsur =================================================== -->
    <table style="width: 100%;">
        <tr>
            <td width="3%"></td>
            <td width="3%"></td>
            <td width="3%"><b>1.</b></td>
            <td><b>Pembahasan Unsur</b></td>
        </tr>
        <tr>
            <td width="3%"></td>
            <td width="3%"></td>
            <td width="3%"></td>
            <td class="content-text">Unsur yang dipakai dalam <?= $pdk->nama . ' (' . $pdk->nama_alias . ')' ?> dapat dijadikan sebagai acuan untuk mengetahui predikat kualitas pelayanan pada <?= $manage_survey->organisasi ?>. Berikut adalah pembahasan mengenai jumlah persentase persepsi responden di setiap unsur:</td>
        </tr>
    </table>

    <?php
    foreach ($this->db->query("SELECT * FROM unsur_pelayanan_$table_identity
    WHERE id_parent = 0 && is_produk = $pdk->id ORDER BY SUBSTR(nomor_unsur,2) + 0")->result() as $up => $unsur) {
   
    $cek_sub = $this->db->query("SELECT * FROM unsur_pelayanan_$table_identity WHERE id_parent = $unsur->id ORDER BY SUBSTR(nomor_unsur,4) + 0"); ?>

    <table style="width: 100%;">
        <tr>
            <td width="3%"></td>
            <td width="3%"></td>
            <td width="3%"></td>
            <td width="4%"><b><?= $unsur->nomor_unsur ?>.</b></td>
            <td><b><?= $unsur->nama_unsur_pelayanan ?></b></td>
        </tr>
    </table>

     <!-- // UNSUR TIDAK MEMILIKI TURUNAN ======================================== -->
     <?php if ($cek_sub->num_rows() == 0) {
        $c = 1;
        foreach ($kategori_unsur->result() as $kup) {
            if ($kup->id_unsur_pelayanan == $unsur->id) {

                $arrayTotalPerolehanKup[$z][$up][] = $kup->perolehan;
                $totalPerolehanKup[$z][$up] = array_sum($arrayTotalPerolehanKup[$z][$up]);
                $arrayNamaKup[$z][$up][] = '%27' . str_replace(' ', '+', $kup->nama_kategori_unsur_pelayanan) . '%27';
                $arrayPersentaseKup[$z][$up][] = ROUND(($kup->perolehan / $kup->jumlah_pengisi) * 100, 2);
                $arrayPerolehanKup[$z][$up][] = '
                <tr>
                    <td class="td-th-list">' . $c++ . '</td>
                    <td class="td-th-list" align="left">' . $kup->nama_kategori_unsur_pelayanan . '</td>
                    <td class="td-th-list">' . $kup->perolehan . '</td>
                    <td class="td-th-list">' . ROUND(($kup->perolehan / $kup->jumlah_pengisi) * 100, 2) . '%</td>
                </tr>';
            }
        }

        //ALASAN UNSUR =================================================
        $arrayAlasanUnsur[$z][$up] = [];
        foreach ($alasan_unsur->result() as $a_unsur) {
            if ($a_unsur->id_unsur_pelayanan == $unsur->id) {
                $arrayAlasanUnsur[$z][$up][] = '<li>' . $a_unsur->alasan_pilih_jawaban . '</li>';
            } else {
                $arrayAlasanUnsur[$z][$up][] = '';
            }
        }
        if (implode("", $arrayAlasanUnsur[$z][$up]) != '') {
            $alasanUnsur[$z][$up] = '
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Alasan yang diberikan responden pada unsur ' . $unsur->nama_unsur_pelayanan . '
                    <ul>' . implode("", $arrayAlasanUnsur[$z][$up]) . '</ul>
                </td>
            </tr>';
        } else {
            $alasanUnsur[$z][$up] = '';
        }
        //END ALASAN UNSUR ================================================= 
        ?>

    <table style="width: 100%;">
        <tr>
            <td width="3%"></td>
            <td width="3%"></td>
            <td width="3%"></td>
            <td width="4%"></td>
            <td align="center">
                <img src='data:image/png;base64,<?= base64_encode(file_get_contents(@"https://quickchart.io/chart?h=250&c={type:'outlabeledPie',data:{labels:[" . implode(",", $arrayNamaKup[$z][$up]) . "],datasets:[{data:[" . implode(",", $arrayPersentaseKup[$z][$up]) . "]}]},options:{plugins:{legend:false,outlabels:{text:%27%l%20%p%27,color:'white',stretch:30,font:{resizable:true,minSize:10}}}}}"))  ?>' width="75%">
                <p><?= 'Gambar ' . $no_gambar++ . '. Grafik Unsur ' . $unsur->nama_unsur_pelayanan ?></p>
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td align="center">
                <p><?= 'Tabel ' . $no_table++ . '. Persentase Responden pada Unsur ' . $unsur->nama_unsur_pelayanan ?>
                </p>
                <table style="width: 100%; margin-left: auto; margin-right: auto;" class="table-list">
                    <tr style="background-color:#E4E6EF;">
                        <th class="td-th-list" width="7%">No</th>
                        <th class="td-th-list">Kategori</th>
                        <th class="td-th-list" width="15%">Jumlah</th>
                        <th class="td-th-list" width="25%">Persentase</th>
                    </tr>

                    <?= implode("", $arrayPerolehanKup[$z][$up]) ?>

                    <tr>
                        <th class="td-th-list" colspan="2">TOTAL</th>
                        <th class="td-th-list"><?= $totalPerolehanKup[$z][$up] ?></th>
                        <th class="td-th-list">100%</th>
                    </tr>
                </table>
            </td>
        </tr>

        <?= $alasanUnsur[$z][$up] ?>
    </table>

    <!-- // JIKA UNSUR MEMILIKI TURUNAN ======================================== -->
    <?php } else { ?>

    <?php foreach ($cek_sub->result() as $sup => $subunsur) {
        $d = 1;
        foreach ($kategori_unsur->result() as $subkup) {
            if ($subkup->id_unsur_pelayanan == $subunsur->id) {

                $arrayTotalPerolehanSubKup[$z][$up][$sup][] = $subkup->perolehan;
                $totalPerolehanSubKup[$z][$up][$sup] = array_sum($arrayTotalPerolehanSubKup[$z][$up][$sup]);
                $arrayNamaSubKup[$z][$up][$sup][] = '%27' . str_replace(' ', '+', $subkup->nama_kategori_unsur_pelayanan) . '%27';
                $arrayPersentaseSubKup[$z][$up][$sup][] = ROUND(($subkup->perolehan / $subkup->jumlah_pengisi) * 100, 2);
                $arrayPerolehanSubKup[$z][$up][$sup][] = '<tr>
                    <td class="td-th-list">' . $d++ . '</td>
                    <td class="td-th-list" align="left">' . $subkup->nama_kategori_unsur_pelayanan . '</td>
                    <td class="td-th-list">' . $subkup->perolehan . '</td>
                    <td class="td-th-list">' . ROUND(($subkup->perolehan / $subkup->jumlah_pengisi) * 100, 2) . '%</td>
                </tr>';
            }
        }

        //ALASAN UNSUR =================================================
        $arrayAlasanSubUnsur[$z][$up][$sup] = [];
        foreach ($alasan_unsur->result() as $a_subunsur) {
            if ($a_subunsur->id_unsur_pelayanan == $subunsur->id) {
                $arrayAlasanSubUnsur[$z][$up][$sup][] = '<li>' . $a_subunsur->alasan_pilih_jawaban . '</li>';
            } else {
                $arrayAlasanSubUnsur[$z][$up][$sup][] = '';
            }
        }
        if (implode("", $arrayAlasanSubUnsur[$z][$up][$sup]) != '') {
            $alasanSubUnsur[$z][$up][$sup] = '
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    Alasan yang diberikan responden pada unsur ' . $subunsur->nama_unsur_pelayanan . '
                    <ul>' . implode("", $arrayAlasanSubUnsur[$z][$up][$sup]) . '</ul>
                </td>
            </tr>';
        } else {
            $alasanSubUnsur[$z][$up][$sup] = '';
        }
        //END ALASAN UNSUR =================================================
        ?>

        <table style="width: 100%;">
            <tr>
                <td width="3%"></td>
                <td width="3%"></td>
                <td width="3%"></td>
                <td width="4%"></td>
                <td width="5%"><b><?= $subunsur->nomor_unsur ?>.</b></td>
                <td><b><?= $subunsur->nama_unsur_pelayanan ?></b></td>
            </tr>

            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td align="center">
                    <img src='data:image/png;base64,<?= base64_encode(file_get_contents(@"https://quickchart.io/chart?h=250&c={type:'outlabeledPie',data:{labels:[" . implode(",", $arrayNamaSubKup[$z][$up][$sup]) . "],datasets:[{data:[" . implode(",", $arrayPersentaseSubKup[$z][$up][$sup]) . "]}]},options:{plugins:{legend:false,outlabels:{text:%27%l%20%p%27,color:'white',stretch:30,font:{resizable:true,minSize:10}}}}}"))  ?>' width="75%">
                    <p><?= 'Gambar ' . $no_gambar++ . '. Grafik Unsur ' . $subunsur->nama_unsur_pelayanan ?></p>
                </td>
            </tr>

            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td align="center">
                    <p><?= 'Tabel ' . $no_table++ . '. Persentase Responden pada Unsur ' . $subunsur->nama_unsur_pelayanan ?>
                    </p>
                    <table style="width: 100%; margin-left: auto; margin-right: auto;" class="table-list">
                        <tr style="background-color:#E4E6EF;">
                            <th class="td-th-list" width="7%">No</th>
                            <th class="td-th-list">Kategori</th>
                            <th class="td-th-list" width="15%">Jumlah</th>
                            <th class="td-th-list" width="25%">Persentase</th>
                        </tr>

                        <?= implode("", $arrayPerolehanSubKup[$z][$up][$sup]) ?>

                        <tr>
                            <th class="td-th-list" colspan="2">TOTAL</th>
                            <th class="td-th-list"><?= $totalPerolehanSubKup[$z][$up][$sup] ?></th>
                            <th class="td-th-list">100%</th>
                        </tr>
                    </table>
                </td>
            </tr>

            <?= $alasanSubUnsur[$z][$up][$sup] ?>
        </table>

    <?php } ?>
    <?php } ?>
    <?php } ?>
    <?php } ?>




    <!-- SARAN RESPONDEN -->
    <?php 
    $h = 1;
    $saran = $this->db->query("SELECT * FROM survey_$table_identity WHERE is_submit = 1 && saran != ''");
    if ($saran->num_rows() > 0) {
        foreach ($saran->result() as $sy) {
            $arraySaran[] = '<tr>
                                <td></td>
                                <td class="td-th-list">' . $h++ . '</td>
                                <td class="td-th-list" align="left">' . $sy->saran . '</td>
                            </tr>';
        }
    } else {
        $arraySaran[] = '<tr>
                           <td></td>
                           <td class="td-th-list" colspan="2"><i>Belum ada saran yang diberikan.</i></td>
                       </tr>';
    } ?>

    <table style="width: 100%;">
        <tr>
            <td width="3%"><b><?= $no_Bab3++ ?>.</b></td>
            <td><b>Saran Responden</b></td>
        </tr>
        <tr>
            <td></td>
            <td class="content-text">Saran responden mengenai Survei Persepsi Kualitas Pelayanan (SPKP) dan Survei Persepsi Anti Korupsi (SPAK) pada <?php echo $manage_survey->organisasi ?> sebagai berikut:</td>
        </tr>
    </table>


    <div align="center" style="font-size: 13px; margin-left: 2em; margin-top: 0.5em;">
            <?= 'Tabel ' . $no_table++ . '. Saran Responden' ?></div>
    <table style="width: 100%; margin-left: auto; margin-right: auto; margin-top: 1em;" class="table-list">
        <tr>
            <td width="3%"></td>
            <th width="7%" class="td-th-list" style="background-color:#E4E6EF;">No</th>
            <th class="td-th-list" style="background-color:#E4E6EF;">Saran</th>
        </tr>

        <?= implode("", $arraySaran) ?>
    </table>
    <br/>



    <!-- TINDAK LANJUT -->
    <table style="width: 100%;">
        <tr>
            <td width="3%"><b><?= $no_Bab3++ ?>.</b></td>
            <td><b>Tindak Lanjut Hasil Survei</b></td>
        </tr>
        <tr>
            <td></td>
            <td class="content-text">Berdasarkan hasil dari Survei Persepsi Kualitas Pelayanan (SPKP) dan Survei Persepsi Anti Korupsi (SPAK), maka rekomendasi yang dapat dilakukan sebagai berikut:</td>
        </tr>
    </table>

    <?php foreach ($this->db->query("SELECT * FROM produk")->result() as $y => $pdk) {
        $analisa[$y] = $this->db->query("SELECT *
        FROM unsur_pelayanan_$table_identity
        JOIN analisa_$table_identity ON unsur_pelayanan_$table_identity.id = analisa_$table_identity.id_unsur_pelayanan
        WHERE is_produk = $pdk->id
        GROUP BY id_unsur_pelayanan
        ORDER BY SUBSTR(nomor_unsur,2) + 0 ASC");
    ?>
    

    <table style="width: 100%;">
        <tr>
            <td width="3%"></td>
            <td width="5%"><b>4.<?= ($y + 1) ?>.</b></td>
            <td width="92%"><b><?= $pdk->nama . ' (' . $pdk->nama_alias . ')' ?></b></td>
        </tr>
    </table>


    <?php if($analisa[$y]->num_rows() > 0){
    foreach($analisa[$y]->result() as $ans => $analis){
        foreach($this->db->query("SELECT * FROM analisa_$table_identity WHERE id_unsur_pelayanan = $analis->id_unsur_pelayanan")->result() as $auns){
            $arrFaktorPenyebab[$y][$ans][] = '<li>' . $auns->faktor_penyebab . '</li>';
            $arrRencanaPerbaikan[$y][$ans][] = '<li>' . $auns->rencana_perbaikan . '</li>';
        } ?>

        <table style="width: 100%;">
            <tr>
                <td width="3%"></td>
                <td width="5%"></td>
                <td><b><?= $analis->nomor_unsur . '. ' . $analis->nama_unsur_pelayanan ?></b></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>Persepsi responden yang mempengaruhi:<br/><div style="padding-left: 1em;"><?= implode("", $arrFaktorPenyebab[$y][$ans]) ?></div></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>Rencana tindak lanjut:<br/><div style="padding-left: 1em;"><?= implode("", $arrRencanaPerbaikan[$y][$ans]) ?></div></td>
            </tr>
        </table>
        <br/>

    <?php } ?>
    <?php } else { ?>
        <p style="font-size: 13px;" align="center"><i>Belum ada hasil tindak lanjut yang dibuat.</i></p>
    <?php } ?>
    <?php } ?>


    


</div>