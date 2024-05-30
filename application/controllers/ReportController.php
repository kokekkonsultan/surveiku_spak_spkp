<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ReportController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
            redirect('auth', 'refresh');
        }
    }



    public function download_docx($username, $slug)
    {
        $this->data = [];
        $this->data['title'] = "Laporan Survei";

        $manage_survey = $this->db->get_where("manage_survey", ['slug' => $slug])->row();
        $users = $this->db->get_where("users", ['username' => $username])->row();
        $table_identity = $manage_survey->table_identity;
        $atribut_pertanyaan = unserialize($manage_survey->atribut_pertanyaan_survey);
        $img_profile = $users->foto_profile != '' ? $users->foto_profile : '200px.jpg';
        $skala_likert = 100 / ($manage_survey->skala_likert == '' ? $manage_survey->skala_likert : 4);
        $definisi_skala = $this->db->get("definisi_skala_$table_identity");


        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        PhpOffice\PhpWord\Settings::setDefaultFontSize(11);
        $paragraphStyleName = 'pStyle';
        $phpWord->addParagraphStyle($paragraphStyleName, array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 100));
        $section = $phpWord->addSection();



        # Add first page header
        $header = $section->addHeader();
        $header->firstPage();

        # Add header for all other pages =======================
        $subHeader = $section->addHeader();
        $htmlHeader = '<table style="width: 100%;">
                            <tr>
                                <td width="15%"><img src="' . base_url() . 'assets/klien/foto_profile/' . $img_profile . '" height="50" alt="" /></td>
                                <td width="70%" align="center"><b style="font-size:13.5px; color:#DE2226;">L A P O R A N   A K H I R</b>
                                <br/><span style="font-size:11px;">SURVEI PERSEPSI KUALITAS PELAYANAN<br/> DAN SURVEI PERSEPSI ANTI KORUPSI<br/>' . strtoupper($manage_survey->organisasi) . '</span></td>
                                <td width="15%"></td>
                            </tr>
                        </table>';
        \PhpOffice\PhpWord\Shared\Html::addHtml($subHeader, $htmlHeader, false, false);
        $subHeader->addLine(['weight' => 1, 'width' => 450, 'height' => 0]);
        #END ===================================================


        #Add footer ==================================================
        $footer = $section->addFooter();
        $footer->addLine(['weight' => 1, 'width' => 450, 'height' => 0]);
        $tableFooter = $footer->addTable('tableFooter');
        $tableFooter->addRow();
        $tableFooter->addCell(8000)->addText('SPKP dan SPAK ' . date('Y'), ['name' => 'Arial', 'size' => 9.5], ['spaceAfter' => 0]);
        $tableFooter->addCell(1000)->addPreserveText('{PAGE}', ['name' => 'Arial', 'size' => 9], ['spaceAfter' => 0, 'align' => 'right']);
        $tableFooter->addRow();
        $tableFooter->addCell(8000)->addText('Generate by SurveiKu.com', ['name' => 'Arial', 'size' => 9.5, 'bold' => true,], ['spaceAfter' => 0]);
        #END ========================================================


        #HALAMAN COVER LAPORAN ===============================
        $bulan = array(1 =>  'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        $split1 = explode('-', $manage_survey->survey_start);
        $split2 = explode('-', $manage_survey->survey_end);
        if ((int)$split1[0] != (int)$split2[0]) {
            $periode =  strtoupper($bulan[(int)$split1[1]] . ' ' . $split1[0] . ' - ' . $bulan[(int)$split2[1]] . ' ' . $split2[0]);
        } else {
            if ($bulan[(int)$split1[1]] == $bulan[(int)$split2[1]]) {
                $periode =  strtoupper($bulan[(int)$split2[1]] . ' ' . $split1[0]);
            } else {
                $periode =  strtoupper($bulan[(int)$split1[1]] . ' - ' . $bulan[(int)$split2[1]] . ' ' . $split1[0]);
            }
        }



        $section->addTextBreak(3);
        $htmlCover = '<table style="width: 100%;">
                        <tr>
                            <td align="center">
                                <img src="' . base_url() . 'assets/klien/foto_profile/' . $img_profile . '" height="150" alt="" />
                                <br/>
                                <br/>
                                <br/>
                                <br/>
                                <br/>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" style="font-size:25px;"><b>LAPORAN</b></td>
                        </tr>
                        <tr>
                            <td align="center" style="font-size:25px;"><b>SURVEI PERSEPSI KUALITAS PELAYANAN<br/> DAN SURVEI PERSEPSI ANTI KORUPSI</b><br/></td>
                        </tr>
                        <tr>
                            <td align="center" style="font-size:20px;"><b>' . strtoupper($manage_survey->organisasi) . '</b><br/></td>
                        </tr>
                        <tr>
                            <td align="center" style="font-size:17px;"><b>PERIODE ' . $periode . '</b></td>
                        </tr>
                    </table>';
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlCover, false, false);
        // echo $htmlCover;

        $section->addPageBreak();
        #END ==================================================================================================================


        #CSS HTML ====================================================
        $content_paragraph = 'text-align: justify; text-indent: 30pt;';
        $no_table = 1;
        $no_gambar = 1;



         #START BAB I ==============================
        #==========================================
        $no_Bab1 = 1;
        $htmlbab1 = '
        <table style="width: 100%; line-height: 1.3;">
            <tr>
                <td style="text-align:center; font-weight: bold; font-size:16px;">BAB I</td>
            </tr>
            <tr>
                <td style="text-align:center; font-weight: bold; font-size:16px;">KUESIONER SURVEI<br/></td>
            </tr>
        </table>

        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
            <tr>
                <td width="3%"><b>' . $no_Bab1++ . '.</b></td>
                <td width="97%"><b>Variable Survei</b></td>
            </tr>
            <tr>
                <td width="3%"><b></b></td>
                <td width="97%"><p style="text-align: justify;">Variabel Survei Persepsi Kualitas Pelayanan (SPKP) meliputi :</p></td>
            </tr>
        </table>
        
        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
            <tr>
                <td width="3%"></td>
                <td width="3%"></td>
                <td width="3%">1.</td>
                <td>Informasi pelayanan <p style="text-align: justify;">Informasi pelayanan melalui media elektronik maupun non elektronik selalu tersedia dan dapat menjawab kebutuhan pengguna layanan, mudah digunakan, serta memiliki fasilitas interaktif dan FAQ.</p></td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td width="3%"></td>
                <td width="3%">2.</td>
                <td>Persyaratan pelayanan <p style="text-align: justify;">Informasi persyaratan layanan dapat dipahami dengan jelas untuk mendapatkan produk/jenis pelayanan serta penerapan persyaratan pelayanan sesuai dengan yang diinformasikan.</p></td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td width="3%"></td>
                <td width="3%">3.</td>
                <td>Sistem, mekanisme, dan prosedur <p style="text-align: justify;">Informasi prosedur/alur layanan dapat dipahami dengan jelas dan sesuai untuk mendapatkan produk/jenis pelayanan serta penerapan prosedur/alur layanan sesuai yang diinformasikan.</p></td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td width="3%"></td>
                <td width="3%">4.</td>
                <td>Waktu penyelesaian pelayanan <p style="text-align: justify;">Jangka waktu penyelesaian pelayanan dapat dipahami dengan jelas, jangka waktu penyelesaian pelayanan tersebut wajar dan penyelesaian pelayanan sesuai dengan yang diinformasikan.</p></td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td width="3%"></td>
                <td width="3%">5.</td>
                <td>Tarif/biaya pelayanan <p style="text-align: justify;">Biaya pelayanan dapat dipahami dengan jelas dan biaya pelayanan dibayarkan sesuai dengan yang diinformasikan, termasuk bila biaya pelayanan gratis.</p></td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td width="3%"></td>
                <td width="3%">6.</td>
                <td>Sarana dan prasarana <p style="text-align: justify;">Sarana prasarana pendukung pelayanan/sistem pelayanan online sudah mempermudah proses pelayanan, meringkas waktu dan hemat biaya.</p></td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td width="3%"></td>
                <td width="3%">7.</td>
                <td>Kecepatan respon pelayanan <p style="text-align: justify;">Kemampuan petugas dalam memberikan respon pelayanan dengan cepat kepada pengguna layanan, baik melalui tatap muka maupun melalui aplikasi layanan daring.</p></td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td width="3%"></td>
                <td width="3%">8.</td>
                <td>Konsultasi dan pengaduan <p style="text-align: justify;">Sarana layanan konsultasi dan pengaduan beragam (tempat konsultasi dan pengaduan/hotline/call center/media online), prosedur untuk melakukan pelayanan mudah dan pengaduan mudah, respon konsultasi dan pengaduan cepat serta tindak lanjut jelas.</p></td>
            </tr>
            
        </table>

        <br/>
        <br/>
        
        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
            <tr>
                <td width="3%"><b></b></td>
                <td width="97%"><p style="text-align: justify;">Variabel Survei Persepsi Anti Korupsi (SPAK) meliputi :</p></td>
            </tr>
        </table>

        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
            <tr>
                <td width="3%"></td>
                <td width="3%"></td>
                <td width="3%">1.</td>
                <td>Diskriminasi pelayanan <p style="text-align: justify;">Petugas memberikan pelayanan secara khusus atau membeda-bedakan pelayanan karena faktor suku, agama, kekerabatan, almamater, dan sejenisnya.</p></td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td width="3%"></td>
                <td width="3%">2.</td>
                <td>Kecurangan pelayanan <p style="text-align: justify;">Petugas memberikan pelayanan yang tidak sesuai dengan ketentuan sehingga mengindikasikan kecurangan, seperti penyerobotan antrian, mempersingkat waktu tunggu layanan diluar prosedur, pengurangan syarat/prosedur, pengurangan denda, dll.</p></td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td width="3%"></td>
                <td width="3%">3.</td>
                <td>Menerima imbalan dan/atau gratifikasi <p style="text-align: justify;">Petugas menerima/bahkan meminta imbalan uang untuk alasan administrasi, transportasi, rokok, kopi, dll diluar ketentuan, pemberian imbalan barang berupa makanan jadi, rokok, parsel, perhiasan, elektronik, pakaian, bahan pangan, dll diluar ketentuan, pemberian imbalan fasilitas berupa akomodasi (hotel, resort perjalanan/jasa transportasi, komunikasi, hiburan, voucher belanja, dll) diluar ketentuan.</p></td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td width="3%"></td>
                <td width="3%">4.</td>
                <td>Pungutan liar <p style="text-align: justify;">Petugas melakukan pungli, yaitu permintaan pembayaran atas pelayanan yang diterima pengguna layanan diluar tarif resmi (Pungli bisa dikamuflasekan melalui berbagai istilah seperti “uang administrasi”, “uang rokok”, “uang terima kasih”, dsb).</p></td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td width="3%"></td>
                <td width="3%">5.</td>
                <td>Percaloan<p style="text-align: justify;">Praktik percaloan (pihak yang melakukan percaloan dapat berasal dari oknum pegawai pada unit layanan ini, maupun pihak luar yang memiliki hubungan atau tidak memiliki hubungan dengan oknum pegawai).</p></td>
            </tr>
        </table>

        <br/>

        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
            <tr>
                <td width="3%"><b>' . $no_Bab1++ . '.</b></td>
                <td width="97%"><b>Kuesioner Survei</b></td>
            </tr>
            <tr>
                <td width="3%"><b></b></td>
                <td width="97%"><p style="text-align: justify;">Kuesioner yang digunakan dalam pelaksanaan survei adalah sebagai berikut:</p></td>
            </tr>
        </table>
        ';
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab1, false, false);


        $draf_kuesioner = $this->_draf_kuesioner( $img_profile, $manage_survey);
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $draf_kuesioner, false, false);
        $section->addPageBreak();
        #END BAB I ================================
        #==========================================




        #START BAB II ==============================
        #==========================================
        $no_Bab2 = 1;
        $htmlbab2 = '
        <table style="width: 100%; line-height: 1.3;">
            <tr>
                <td style="text-align:center; font-weight: bold; font-size:16px;">BAB II</td>
            </tr>
            <tr>
                <td style="text-align:center; font-weight: bold; font-size:16px;">METODOLOGI SURVEI<br/></td>
            </tr>
        </table>

        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
            <tr>
                <td width="3%"><b>' . $no_Bab2++ . '.</b></td>
                <td width="97%"><b>Kriteria Responden</b></td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td width="97%">
                    <p style="' . $content_paragraph . '">Responden adalah seluruh pihak yang pernah mendapatkan pelayanan di unit ini. Jumlah responden yang digunakan dalam Survei Persepsi Kualitas Pelayanan (SPKP) dan Survei Persepsi Anti Korupsi (SPAK) ini dihitung menggunakan rumus Krejcie sebagai berikut:</p>

                    <div><b>Rumus Krejcie dan Morgan:</b>
                        <table width="43%" align="center" style="border: 1px #000 solid;">
                            <tr>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;S = {λ². N. P. Q}/ {d² (N-1) + λ². P. Q</td>
                            </tr>
                        </table>
                    </div>
                    <br/>
                    <table>
                        <tr>
                            <td width="25%"><b>Keterangan :</b></td>
                            <td width="75%">
                                <p>S = Jumlah sampel</p>
                                <p>λ² = Lamda (faktor pengali) dengan dk = 1, (taraf kesalahan yang digunakan 5%, sehingga nilai lamba 3,841)</p>
                                <p>N = Populasi sebanyak ' . $manage_survey->jumlah_populasi . '</p>
                                <p>P = Q = 0,5 (populasi menyebar normal)</p>
                                <p>d = 0,05</p>
                            </td>
                        </tr>
                    </table>

                    <p style="text-align: justify;">Sehingga dari perhitungan di atas, jumlah responden minimal yang harus diperoleh adalah '.$manage_survey->jumlah_sampling.' responden.<br/></p>
                </td>
            </tr>

            <tr>
                <td width="3%"><b>' . $no_Bab2++ . '.</b></td>
                <td width="97%"><b>Metode Pencacahan</b></td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td width="97%"><p style="' . $content_paragraph . '">Pengumpulan data dilakukan dengan menggunakan metode survei elektronik melalui sistem broadcast data. Broadcast data dilakukan melalui WhatsApp, SMS, Email, dan scan barcode.<br/></p></td>
            </tr>

            <tr>
                <td width="3%"><b>' . $no_Bab2++ . '.</b></td>
                <td width="97%"><b>Metode Pengolahan Data dan Analisis</b></td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td width="97%"><p style="' . $content_paragraph . '">Metode yang digunakan dalam pengolahan data dan analisis Survei Persepsi Kualitas Pelayanan (SPKP) dan Survei Persepsi Anti Korupsi (SPAK) ini menggunakan aplikasi survei yang akan menghasilkan analisis deskriptif kuantitatif.</p></td>
            </tr>
        </table>
        ';
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab2, false, false);
        $section->addPageBreak();
        #END BAB II ==============================
        #==========================================



        
        #START BAB III ==============================
        #==========================================
        $no_Bab3 = 1;

        #JENIS PELAYANAN
        $responden = $this->db->query("SELECT * FROM responden_$table_identity
        JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
        WHERE is_submit = 1");

        $data = [];
        foreach ($responden->result() as $key => $value) {
            $id_layanan_survei = implode(", ", unserialize($value->id_layanan_survei));
            $data[$key] = "UNION ALL SELECT * FROM layanan_survei_$table_identity WHERE id IN ($id_layanan_survei)";
        }
        $tabel_layanan = implode(" ", $data);

        $layanan = $this->db->query("SELECT id, nama_layanan, COUNT(id) - 1 AS perolehan,
        SUM(Count(id)) OVER () - (SELECT COUNT(id) FROM layanan_survei_$table_identity WHERE is_active = 1) as total_survei
        FROM (
            SELECT * FROM layanan_survei_$table_identity
            $tabel_layanan
            ) ls
            WHERE is_active = 1 GROUP BY id");

        $no = 1;
        foreach ($layanan->result() as $row) {
            $perolehan[] = $row->perolehan;
            $total_perolehan = array_sum($perolehan);
            $persentase[] = ($row->perolehan/$row->total_survei) * 100;
            $total_persentase  = array_sum($persentase);

            $arr_layanan[] = '
            <tr>
                <td width="5%" align="center" style="font-weight: bold;">' . $no++ . '</td>
                <td width="55%">' . $row->nama_layanan . '</td>
                <td width="20%" align="center">' . $row->perolehan . '</td>
                <td width="20%" align="center">' . ROUND(($row->perolehan/$row->total_survei) * 100,2) . '%</td>
            </tr>';
        }


        $htmlBab3 = '
        <table style="width: 100%; line-height: 1.3;">
            <tr>
                <td style="text-align:center; font-weight: bold; font-size:16px;">BAB III</td>
            </tr>
            <tr>
                <td style="text-align:center; font-weight: bold; font-size:16px;">PENGOLAHAN SURVEI<br/></td>
            </tr>
        </table>

        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
            <tr>
                <td width="3%"><b>' . $no_Bab3++ . '.</b></td>
                <td width="97%"><b>Jenis Layanan</b></td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td width="97%"><p style="text-align: justify;">Berikut merupakan jenis layanan yang diperoleh dari Survei Persepsi Kualitas Pelayanan (SPKP) dan Survei Persepsi Anti Korupsi (SPAK):</p></td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td width="97%" style="text-align:center;">Tabel ' . $no_table++ . '. Jenis Layanan
                    <table width="100%" align="center" style="font-size:13.5px; border: 1px #000 solid;">
                        <tr style="background-color: #F3F6F9;">
                            <th width="5%" align="center" style="font-weight: bold;">No</th>
                            <th width="55%" align="center" style="font-weight: bold;">Jenis Pelayanan</th>
                            <th width="20%" align="center" style="font-weight: bold;">Jumlah</th>
                            <th width="20%" align="center" style="font-weight: bold;">Persentase</th>
                        </tr>
                        ' . implode("", $arr_layanan) . '
                        <tr>
                            <th width="60%" align="center" colspan="2" style="font-weight: bold;">TOTAL</th>
                            <th width="20%" align="center" style="font-weight: bold;">' . $total_perolehan . '</th>
                            <th width="20%" align="center" style="font-weight: bold;">100%</th>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
            <tr>
                <td width="3%"><b>' . $no_Bab3++ . '.</b></td>
                <td width="97%"><b>Analisis Hasil Survei</b></td>
            </tr>
        </table>';
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlBab3, false, false);




        #Analisis Hasil Survei
        foreach ($this->db->query("SELECT * FROM produk")->result() as $z => $pdk) {

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
                                                <td width="5%" align="center">' . $b++ . '</td>
                                                <td width="55%">' . $n_ikm->nomor_unsur . '. ' . $n_ikm->nama_unsur_pelayanan . '</td>
                                                <td width="20%" align="center">' . Round($n_ikm->rata_rata, 2) . '</td>
                                                <td width="20%" align="center">' . $ktgUnsur[$z] . '</td>
                                            </tr>';
            }

            foreach ($definisi_skala->result() as $obj) {
                if (($nilaiIKM[$z] * $skala_likert) <= $obj->range_bawah && ($nilaiIKM[$z] * $skala_likert) >= $obj->range_atas) {
                    $ktgNilaiIKM[$z] = $obj->kategori;
                }
            }
            if (($nilaiIKM[$z] * $skala_likert) <= 0) {
                $ktgNilaiIKM[$z] = 'NULL';
            }


            $AB = $pdk->id == 1 ? 'A' : 'B';
            $htmlBab32 =
                '<table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                <tr>
                    <td width="3%"></td>
                    <td width="3%"><b>' . $AB . '.</b></td>
                    <td width="94%"><b>' . $pdk->nama . ' (' . $pdk->nama_alias . ')</b></td>
                </tr>
                <tr>
                    <td width="3%"></td>
                    <td width="3%"></td>
                    <td width="94%"><p style="text-align: justify;">Hasil ' . $pdk->nama . ' (' . $pdk->nama_alias . ') ' . $manage_survey->organisasi . ' mendapatkan nilai ' . $pdk->nama_indeks . ' sebesar <b>' . ROUND($nilaiIKM[$z] * $skala_likert, 2) . '</b>, dengan predikat <b>' . $ktgNilaiIKM[$z] . '</b>. Nilai ' . $pdk->nama_indeks . ' tersebut didapat dari nilai rata-rata seluruh unsur pada tabel berikut.</p></td>
                </tr>
            </table>';
            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlBab32, false, false);



            $htmlBab32A =
                '<table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                <tr>
                    <td width="3%"></td>
                    <td width="3%"></td>
                    <td width="94%" style="text-align:center;">Tabel ' . $no_table++ . '. Nilai Unsur ' . $pdk->nama . '
                    <table width="100%" align="center" style="font-size:13.5px; border: 1px #000 solid;">
                        <tr style="background-color: #F3F6F9;">
                            <th width="5%" align="center" style="font-weight: bold;">No</th>
                            <th width="55%" align="center" style="font-weight: bold;">Unsur</th>
                            <th width="20%" align="center" style="font-weight: bold;">Indeks</th>
                            <th width="20%" align="center" style="font-weight: bold;">Kategori</th>
                        </tr>
                        ' . implode("", $arrayNilaiIKM[$z]) . '
                        <tr>
                            <th width="60%" align="center" colspan="2" style="font-weight: bold;">Nilai ' . $pdk->nama_indeks . '</th>
                            <th width="20%" align="center" style="font-weight: bold;">' . ROUND($nilaiIKM[$z], 3) . '</th>
                            <th width="20%" align="center" style="font-weight: bold;">' . $ktgNilaiIKM[$z] . '</th>
                        </tr>
                        <tr>
                            <th width="60%" align="center" colspan="2" style="font-weight: bold;">Nilai Konversi</th>
                            <th width="20%" align="center" style="font-weight: bold;">' . ROUND($nilaiIKM[$z] * $skala_likert, 2) . '</th>
                            <th width="20%" align="center" style="font-weight: bold;">' . $ktgNilaiIKM[$z] . '</th>
                        </tr>
                    </table>
                    </td>
                </tr>

                <tr>
                    <td width="3%"></td>
                    <td width="3%"></td>
                    <td width="94%"><p style="text-align: justify;">Nilai unsur ' . $pdk->nama . ' (' . $pdk->nama_alias . ') pada ' . $manage_survey->organisasi . ' dapat dilihat pada gambar di bawah ini.</p></td>
                </tr>
            </table>';
            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlBab32A, false, false);


            $section->addImage("https://quickchart.io/chart?bg=white&h=" . (50 + (count($arrayNomorUnsur[$z]) * 40)) . "&c={type:'horizontalBar',data:{labels:[" . implode(",", $arrayNomorUnsur[$z]) . "],datasets:[{label:'Dataset1',backgroundColor:'rgb(79,129,189)',stack:'Stack0',data:[" . implode(",", $arrayRataRataNilaiIKM_1[$z]) . "],},],},options:{title:{display:true,text:'Nilai+Unsur'},legend:{display:false},plugins:{roundedBars:true,datalabels:{anchor:'center',align:'center',color:'white',font:{weight:'normal',},},},responsive:true,},}", array('width' => 300, 'ratio' => true, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
            $section->addText('Gambar ' . $no_gambar++ . '. Grafik Unsur ' . $pdk->nama, array('size' => 9.5), $paragraphStyleName);
            $section->addTextBreak();


            #START PEMBAHASAN UNSUR 
            $htmlBab321 =
                '<table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                <tr>
                    <td width="3%"></td>
                    <td width="3%"></td>
                    <td width="3%"><b>1.</b></td>
                    <td width="94%"><b>Pembahasan Unsur</b></td>
                </tr>
                <tr>
                    <td width="3%"></td>
                    <td width="3%"></td>
                    <td width="3%"></td>
                    <td width="94%"><p style="text-align: justify;">Unsur yang dipakai dalam ' . $pdk->nama . ' (' . $pdk->nama_alias . ') dapat dijadikan sebagai acuan untuk mengetahui predikat kualitas pelayanan pada ' . $manage_survey->organisasi . '. Berikut adalah pembahasan mengenai jumlah persentase persepsi responden di setiap unsur:</p></td>
                </tr>
            </table>';
            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlBab321, false, false);



            
            $kategori_unsur[$z] = $this->db->query("SELECT *,
            (SELECT COUNT(IF(skor_jawaban != 0, 1, NULL)) FROM jawaban_pertanyaan_unsur_$table_identity JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden WHERE jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = kategori_unsur_pelayanan_$table_identity.id_pertanyaan_unsur && kategori_unsur_pelayanan_$table_identity.nomor_kategori_unsur_pelayanan = jawaban_pertanyaan_unsur_$table_identity.skor_jawaban && is_submit = 1) AS perolehan,

            (SELECT COUNT(IF(skor_jawaban != 0, 1, NULL)) FROM jawaban_pertanyaan_unsur_$table_identity JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden WHERE jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = kategori_unsur_pelayanan_$table_identity.id_pertanyaan_unsur && is_submit = 1) AS jumlah_pengisi

            FROM kategori_unsur_pelayanan_$table_identity");

            $alasan_unsur[$z] = $this->db->query("SELECT *
            FROM jawaban_pertanyaan_unsur_$table_identity
            JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
            JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
            WHERE is_submit = 1 && alasan_pilih_jawaban != '' && jawaban_pertanyaan_unsur_$table_identity.is_active = 1");


            foreach ($this->db->query("SELECT * FROM unsur_pelayanan_$table_identity WHERE id_parent = 0 && is_produk = $pdk->id ORDER BY SUBSTR(nomor_unsur,2) + 0")->result() as $up => $unsur) {

                $htmlbab3PembahasanUnsur[$z] = '
                    <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                        <tr>
                            <td width="3%"></td>
                            <td width="3%"></td>
                            <td width="3%"></td>
                            <td width="5%"><b>' . $unsur->nomor_unsur . '. </b></td>
                            <td><b>' . $unsur->nama_unsur_pelayanan . '</b></td>
                        </tr>
                    </table>';
                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab3PembahasanUnsur[$z], false, false);

                $cek_sub = $this->db->query("SELECT * FROM unsur_pelayanan_$table_identity WHERE id_parent = $unsur->id ORDER BY SUBSTR(nomor_unsur,4) + 0");

                if ($cek_sub->num_rows() == 0) {
                    
                    $c = 1;
                    foreach ($kategori_unsur[$z]->result() as $kup) {
                        if ($kup->id_unsur_pelayanan == $unsur->id) {

                            $arrayTotalPerolehanKup[$z][$up][] = $kup->perolehan;
                            $totalPerolehanKup[$z][$up] = array_sum($arrayTotalPerolehanKup[$z][$up]);
                            $arrayNamaKup[$z][$up][] = '%27' . str_replace(' ', '+', $kup->nama_kategori_unsur_pelayanan) . '%27';
                            $arrayPersentaseKup[$z][$up][] = ROUND(($kup->perolehan / $kup->jumlah_pengisi) * 100, 2);
                            $arrayPerolehanKup[$z][$up][] = '<tr>
                                                                <td width="5%" align="center">' . $c++ . '</td>
                                                                <td width="55%">' . $kup->nama_kategori_unsur_pelayanan . '</td>
                                                                <td width="20%" align="center">' . $kup->perolehan . '</td>
                                                                <td width="20%" align="center">' . ROUND(($kup->perolehan / $kup->jumlah_pengisi) * 100, 2) . '%</td>
                                                            </tr>';
                        }
                    }

                    //ALASAN UNSUR =================================================
                    $arrayAlasanUnsur[$z][$up] = [];
                    foreach ($alasan_unsur[$z]->result() as $a_unsur) {
                        if ($a_unsur->id_unsur_pelayanan == $unsur->id) {
                            $arrayAlasanUnsur[$z][$up][] = '<li>' . $a_unsur->alasan_pilih_jawaban . '</li>';
                        } else {
                            $arrayAlasanUnsur[$z][$up][] = '';
                        }
                    }
                    if (implode("", $arrayAlasanUnsur[$z][$up]) != '') {
                        $alasanUnsur[$z][$up] = '<p style="text-align:left;">Alasan yang diberikan responden pada unsur ' . $unsur->nama_unsur_pelayanan . '</p><ul>' . implode("", $arrayAlasanUnsur[$z][$up]) . '</ul>';
                    } else {
                        $alasanUnsur[$z][$up] = '';
                    }
                    //END ALASAN UNSUR =================================================


                    $section->addImage("https://quickchart.io/chart?h=250&c={type:'outlabeledPie',data:{labels:[" . implode(",", $arrayNamaKup[$z][$up]) . "],datasets:[{data:[" . implode(",", $arrayPersentaseKup[$z][$up]) . "]}]},options:{plugins:{legend:false,outlabels:{text:%27%l%20%p%27,color:'white',stretch:30,font:{resizable:true,minSize:10}}}}}", array('width' => 350, 'ratio' => true, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));


                    // $section->addImage("https://quickchart.io/chart?bg=white&h=" . (50 + (count($arrayNamaKup[$z][$up]) * 50)) . "&c={type:'horizontalBar',data:{labels:[" . implode(",", $arrayNamaKup[$z][$up]) . "],datasets:[{backgroundColor:'rgb(79,129,189)',stack:'Stack0',data:[" . implode(",", $arrayPersentaseKup[$z][$up]) . "],},],},options:{layout:{padding:{right:50}},scales:{xAxes:[{ticks:{min:0,max:100},},]},title:{display:true,text:'" . str_replace(' ', '+', $unsur->nama_unsur_pelayanan) . "'},legend:{display:false},responsive:true,plugins:{roundedBars:true,datalabels:{anchor:'end',align:'center',backgroundColor:'rgb(255,255,255)',borderColor:'rgb(79,129,189)',borderWidth:1,borderRadius:5,formatter:(value)%3D%3E%7Breturn%20value%2B'%25';},},},},}", array('width' => 300, 'ratio' => true, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
                    $section->addText('Gambar ' . $no_gambar++ . '. Grafik Unsur ' . $unsur->nama_unsur_pelayanan, array('size' => 9.5), $paragraphStyleName);
                    $section->addTextBreak();

                    $htmlbab3PembahasanUnsur[$z] = '
                    <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                        <tr>
                            <td width="3%"></td>
                            <td width="3%"></td>
                            <td width="5%"></td>
                            <td style="text-align:center;">Tabel ' . $no_table++ . '. Persentase Responden pada Unsur ' . $unsur->nama_unsur_pelayanan . '
                            <table width="100%" align="center" style="font-size:13.5px; border: 1px #000 solid;">
                                <tr style="background-color: #F3F6F9;">
                                    <th width="5%" align="center" style="font-weight: bold;">No</th>
                                    <th width="55%" align="center" style="font-weight: bold;">Kategori</th>
                                    <th width="20%" align="center" style="font-weight: bold;">Jumlah</th>
                                    <th width="20%" align="center" style="font-weight: bold;">Persentase</th>
                                </tr>
                                    ' . implode("", $arrayPerolehanKup[$z][$up]) . '
                                <tr>
                                    <th width="60%" align="center" colspan="2" style="font-weight: bold;">TOTAL</th>
                                    <th width="20%" align="center" style="font-weight: bold;">' . $totalPerolehanKup[$z][$up] . '</th>
                                    <th width="20%" align="center" style="font-weight: bold;">100%</th>
                                </tr>
                            </table>
                                ' . $alasanUnsur[$z][$up] . '
                            </td>
                        </tr>
                    </table>';
                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab3PembahasanUnsur[$z], false, false);

                } else {
                    foreach ($cek_sub->result() as $sup => $subunsur) {

                        $htmlbab3PembahasanUnsurSub = '
                        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                            <tr>
                                <td width="3%"></td>
                                <td width="3%"></td>
                                <td width="3%"></td>
                                <td width="5%"></td>
                                <td><b>' . $subunsur->nomor_unsur . '. ' . $subunsur->nama_unsur_pelayanan . '</b></td>
                            </tr>
                        </table>';
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab3PembahasanUnsurSub, false, false);

                        $d = 1;
                        foreach ($kategori_unsur[$z]->result() as $subkup) {
                            if ($subkup->id_unsur_pelayanan == $subunsur->id) {

                                $arrayTotalPerolehanSubKup[$z][$up][$sup][] = $subkup->perolehan;
                                $totalPerolehanSubKup[$z][$up][$sup] = array_sum($arrayTotalPerolehanSubKup[$z][$up][$sup]);
                                $arrayNamaSubKup[$z][$up][$sup][] = '%27' . str_replace(' ', '+', $subkup->nama_kategori_unsur_pelayanan) . '%27';
                                $arrayPersentaseSubKup[$z][$up][$sup][] = ROUND(($subkup->perolehan / $subkup->jumlah_pengisi) * 100, 2);
                                $arrayPerolehanSubKup[$z][$up][$sup][] = '<tr>
                                                        <td width="5%" align="center">' . $d++ . '</td>
                                                        <td width="55%">' . $subkup->nama_kategori_unsur_pelayanan . '</td>
                                                        <td width="20%" align="center">' . $subkup->perolehan . '</td>
                                                        <td width="20%" align="center">' . ROUND(($subkup->perolehan / $subkup->jumlah_pengisi) * 100, 2) . '%</td>
                                                    </tr>';
                            }
                        }


                        //ALASAN UNSUR =================================================
                        $arrayAlasanSubUnsur[$z][$up][$sup] = [];
                        foreach ($alasan_unsur[$z]->result() as $a_subunsur) {
                            if ($a_subunsur->id_unsur_pelayanan == $subunsur->id) {
                                $arrayAlasanSubUnsur[$z][$up][$sup][] = '<li>' . $a_subunsur->alasan_pilih_jawaban . '</li>';
                            } else {
                                $arrayAlasanSubUnsur[$z][$up][$sup][] = '';
                            }
                        }
                        if (implode("", $arrayAlasanSubUnsur[$z][$up][$sup]) != '') {
                            $alasanSubUnsur[$z][$up][$sup] = '<p style="text-align:left;">Alasan yang diberikan responden pada unsur ' . $subunsur->nama_unsur_pelayanan . '</p><ul>' . implode("", $arrayAlasanSubUnsur[$z][$up][$sup]) . '</ul>';
                        } else {
                            $alasanSubUnsur[$z][$up][$sup] = '';
                        }
                        //END ALASAN UNSUR =================================================


                        $section->addImage("https://quickchart.io/chart?h=250&c={type:'outlabeledPie',data:{labels:[" . implode(",", $arrayNamaSubKup[$z][$up][$sup]) . "],datasets:[{data:[" . implode(",", $arrayPersentaseSubKup[$z][$up][$sup]) . "]}]},options:{plugins:{legend:false,outlabels:{text:%27%l%20%p%27,color:'white',stretch:30,font:{resizable:true,minSize:10}}}}}", array('width' => 350, 'ratio' => true, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

                        // $section->addImage("https://quickchart.io/chart?bg=white&h=" . (50 + (count($arrayNamaSubKup[$z][$up][$sup]) * 50)) . "&c={type:'horizontalBar',data:{labels:[" . implode(",", $arrayNamaSubKup[$z][$up][$sup]) . "],datasets:[{backgroundColor:'rgb(79,129,189)',stack:'Stack0',data:[" . implode(",", $arrayPersentaseSubKup[$z][$up][$sup]) . "],},],},options:{layout:{padding:{right:50}},scales:{xAxes:[{ticks:{min:0,max:100},},]},title:{display:true,text:'" . str_replace(' ', '+', $subunsur->nama_unsur_pelayanan) . "'},legend:{display:false},responsive:true,plugins:{roundedBars:true,datalabels:{anchor:'end',align:'center',backgroundColor:'rgb(255,255,255)',borderColor:'rgb(79,129,189)',borderWidth:1,borderRadius:5,formatter:(value)%3D%3E%7Breturn%20value%2B'%25';},},},},}", array('width' => 300, 'ratio' => true, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
                        $section->addText('Gambar ' . $no_gambar++ . '. Grafik Unsur ' . $subunsur->nama_unsur_pelayanan, array('size' => 9.5), $paragraphStyleName);
                        $section->addTextBreak();


                        $htmlbab3PembahasanUnsurSubA[$z] = '
                        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                            <tr>
                                <td width="3%"></td>
                                <td width="3%"></td>
                                <td width="3%"></td>
                                <td width="5%"></td>
                                <td style="text-align:center;">Tabel ' . $no_table++ . '. Persentase Responden pada Unsur ' . $subunsur->nama_unsur_pelayanan . '
                                <table width="100%" align="center" style="font-size:13.5px; border: 1px #000 solid;">
                                    <tr style="background-color: #F3F6F9;">
                                        <th width="5%" align="center" style="font-weight: bold;">No</th>
                                        <th width="55%" align="center" style="font-weight: bold;">Kategori</th>
                                        <th width="20%" align="center" style="font-weight: bold;">Jumlah</th>
                                        <th width="20%" align="center" style="font-weight: bold;">Persentase</th>
                                    </tr>
                                    ' . implode("", $arrayPerolehanSubKup[$z][$up][$sup]) . '
                                    <tr>
                                        <th width="60%" align="center" colspan="2" style="font-weight: bold;">TOTAL</th>
                                        <th width="20%" align="center" style="font-weight: bold;">' . $totalPerolehanSubKup[$z][$up][$sup] . '</th>
                                        <th width="20%" align="center" style="font-weight: bold;">100%</th>
                                    </tr>
                                </table>
                                ' . $alasanSubUnsur[$z][$up][$sup] . '
                            </td>
                            </tr>
                        </table>';
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab3PembahasanUnsurSubA[$z], false, false);

                    }
                }
            }
        }




        #START SARAN
        $h = 1;
        $saran = $this->db->query("SELECT * FROM survey_$table_identity WHERE is_submit = 1 && saran != ''");
        if ($saran->num_rows() > 0) {
            foreach ($saran->result() as $sy) {
                $arraySaran[] = '<tr>
                                    <td width="7%" align="center">' . $h++ . '</td>
                                    <td width="93%">' . $sy->saran . '</td>
                                </tr>';
            }
        } else {
            $arraySaran[] = '<tr>
                                <td align="center" colspan="2"><i>Belum ada saran yang diberikan.</i></td>
                                </tr>
                            ';
        }


        $htmlbab3Saran  = '
        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                <tr>
                <td width="3%"><b>' . $no_Bab3++ . '.</b></td>
                <td width="97%"><b>Saran Responden</b></td>
            </tr>
            <tr>
                <td width="5%"></td>
                <td><p style="' . $content_paragraph . '">Saran responden mengenai Survei Persepsi Kualitas Pelayanan (SPKP) dan Survei Persepsi Anti Korupsi (SPAK) pada ' . $manage_survey->organisasi . ' sebagai berikut:</p></td>
            </tr>
            <tr>
                <td width="5%"></td>
                <td align="center">Tabel ' . $no_table++ . '. Saran Responden
                    <table width="100%" align="center" style="font-size:13.5px; border: 1px #000 solid;">
                        <tr style="background-color: #F3F6F9;">
                            <th width="7%" align="center" style="font-weight: bold;">No</th>
                            <th width="93%" align="center" style="font-weight: bold;">Saran</th>
                        </tr>
                        ' . implode("", $arraySaran) . '
                    </table>
                </td>
            </tr>
        </table>';
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab3Saran, false, false);



        $htmlbab3TindakLanjut  = '
        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
            <tr>
                <td width="3%"><b>' . $no_Bab3++ . '.</b></td>
                <td width="97%"><b>Tindak Lanjut Hasil Survei</b></td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td width="97%"><p style="text-align:justify;">Berdasarkan hasil dari Survei Persepsi Kualitas Pelayanan (SPKP) dan Survei Persepsi Anti Korupsi (SPAK), maka rekomendasi yang dapat dilakukan sebagai berikut:</p></td>
            </tr>
        </table>';
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab3TindakLanjut, false, false);


        foreach ($this->db->query("SELECT * FROM produk")->result() as $y => $pdk) {

            $htmlbab3TindakLanjutA  = '
            <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                <tr>
                    <td width="3%"></td>
                    <td width="5%"><b>4.' . ($y + 1) . '.</b></td>
                    <td width="92%"><b>' . $pdk->nama . ' (' . $pdk->nama_alias . ')</b></td>
                </tr>
            </table>';
            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab3TindakLanjutA, false, false);

            $analisa[$y] = $this->db->query("SELECT *
            FROM unsur_pelayanan_$table_identity
            JOIN analisa_$table_identity ON unsur_pelayanan_$table_identity.id = analisa_$table_identity.id_unsur_pelayanan
            WHERE is_produk = $pdk->id
            GROUP BY id_unsur_pelayanan
            ORDER BY SUBSTR(nomor_unsur,2) + 0 ASC");

            if($analisa[$y]->num_rows() > 0){
                foreach($analisa[$y]->result() as $ans => $analis){

                    foreach($this->db->query("SELECT * FROM analisa_$table_identity WHERE id_unsur_pelayanan = $analis->id_unsur_pelayanan")->result() as $auns){
                        $arrFaktorPenyebab[$y][$ans][] = '<li>' . $auns->faktor_penyebab . '</li>';
                        $arrRencanaPerbaikan[$y][$ans][] = '<li>' . $auns->rencana_perbaikan . '</li>';
                    }


                    $arrAnalisa[$y][] = '
                    <div>
                        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                            <tr>
                                <td width="3%"></td>
                                <td width="5%"></td>
                                <td width="92%"><b>' . $analis->nomor_unsur . '. ' . $analis->nama_unsur_pelayanan . '</b></td>
                            </tr>
                            <tr>
                                <td width="3%"></td>
                                <td width="5%"></td>
                                <td width="92%">Persepsi responden yang mempengaruhi:</td>
                            </tr>
                        </table>
                        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                            <tr>
                                <td width="4%"></td>
                                <td width="96%"><ul>' . implode("", $arrFaktorPenyebab[$y][$ans]) . '</ul></td>
                            </tr>
                        </table>
                        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                            <tr>
                                <td width="3%"></td>
                                <td width="5%"></td>
                                <td width="92%">Rencana tindak lanjut:</td>
                            </tr>
                        </table>
                        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                            <tr>
                                <td width="4%"></td>
                                <td width="96%"><ul>' . implode("", $arrRencanaPerbaikan[$y][$ans]) . '</ul></td>
                            </tr>
                        </table>
                    </div>
                    <br/>';
                }
                $htmlAnalisa[$y] = implode("", $arrAnalisa[$y]);

            } else {
                $htmlAnalisa[$y] = '<p style="font-size:13.5px; text-align: center;"><i>Belum ada hasil tindak lanjut yang dibuat.</i></p>';
            }

            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlAnalisa[$y], false, false);

        }
        $section->addPageBreak();
        #END BAB III ==============================
        #==========================================




        // #START BAB IV ==============================
        // #==========================================
        $no_Bab4 = 1;
        $htmlbab4 = '
        <table style="width: 100%; line-height: 1.3;">
            <tr>
                <td style="text-align:center; font-weight: bold; font-size:16px;">BAB IV</td>
            </tr>
            <tr>
                <td style="text-align:center; font-weight: bold; font-size:16px;">DATA SURVEI<br/></td>
            </tr>
        </table>';
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab4, false, false);


        $array_profil = "'email', 'nomor_telepon', 'no_telepon', 'telepon', 'nomor', 'handphone', 'no_hp', 'whatsapp', 'nomor_whatsapp', 'no_wa', 'nama_lengkap'";
        $profil_responden_bab4 = $this->db->query("SELECT * FROM profil_responden_$table_identity
        WHERE nama_alias NOT IN ($array_profil)
        ORDER BY IF(urutan != '',urutan,id) ASC")->result();

        $data_profil = [];
        foreach ($profil_responden_bab4 as $get) {
            $arrLabelProfil[] = $get->nama_profil_responden;
            $arrLabelAlisaProfil[] = '<th width="15%" align="center" style="font-weight: bold;">' . $get->nama_profil_responden . '</th>';

            if ($get->jenis_isian == 1) {
                $data_profil[] = "(SELECT nama_kategori_profil_responden FROM kategori_profil_responden_$table_identity WHERE responden_$table_identity.$get->nama_alias = kategori_profil_responden_$table_identity.id) AS $get->nama_alias";
            } else {
                $data_profil[] = $get->nama_alias;
            }
        }
        $query_profil = implode(",", $data_profil);
        

        foreach($this->db->query("SELECT *, responden_$table_identity.uuid AS uuid_responden, $query_profil
        FROM responden_$table_identity
        JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
        WHERE is_submit = 1")->result() as $x => $rspnd){

            foreach ($profil_responden_bab4 as $val) {
                $profil = $val->nama_alias;
                $arrIsiProfil[$x][] = '<td width="15%">' . $rspnd->$profil . '</td>';
            }


            $arrResponden[] = '
            <tr>
                <td width="15%">Responden ' . ($x + 1 ) . '</td>
                ' . implode("", $arrIsiProfil[$x]) . '
            </tr>';

        }


        $htmlbab4DataResponden = '
        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
            <tr>
                <td width="3%"><b>' . $no_Bab4++ . '.</b></td>
                <td width="97%"><b>Data Responden</b></td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td width="97%">
                    <table width="100%" align="center" style="font-size:13.5px; border: 1px #000 solid;">
                        <tr style="background-color: #F3F6F9;">
                            <th width="15%" align="center" style="font-weight: bold;"></th>
                            ' . implode("", $arrLabelAlisaProfil) . '
                        </tr>
                        ' . implode("", $arrResponden) . '
                    </table>
                </td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td width="97%"><p style="text-align: justify;"><i>** Data Nama Lengkap, Nomor Handphone, Email tidak ditampilkan untuk menjaga kerahasiaan data responden. Sesuai Undang-Undang Nomor 27 Tahun 2022 tentang Perlindungan Data Pribadi.</i></p></td>
            </tr>
        </table>
        <br/>';
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab4DataResponden, false, false);



        if($manage_survey->img_form_opening != '') {
            $capture = '<img src="' . base_url() . 'assets/klien/form_opening/' . $manage_survey->img_form_opening . '" alt="" width="500"/>
            <p style="text-align: center;">Gambar ' . $no_gambar . '. Capture Aplikasi Survei</p>';
        } else {
            $capture = '<i>Gambar form survei belum diambil.</i>';
        }

        $htmlbab4Capture = '
        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
            <tr>
                <td width="3%"><b>' . $no_Bab4++ . '.</b></td>
                <td width="97%"><b>Capture Aplikasi Survei</b></td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td width="97%" align="center">' . $capture . '</td>
            </tr>
        </table>
        <br/>';
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab4Capture, false, false);



        $htmlbab4Link = '
        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
            <tr>
                <td width="3%"><b>' . $no_Bab4++ . '.</b></td>
                <td width="97%"><b>Link Akses Hasil Survei</b></td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td width="97%"><p style="text-align: justify;">Link dan barcode untuk validasi hasil Survei:</p>
                <i>' . base_url() . 'validasi-sertifikat/' . $manage_survey->uuid . '</i>
                </td>
            </tr>
        </table>';
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab4Link, false, false);

        $section->addImage('https://image-charts.com/chart?chl='. base_url() . 'validasi-sertifikat/' . $manage_survey->uuid . '&choe=UTF-8&chs=300x300&cht=qr', array('width' => 130, 'ratio' => true, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
        $section->addText('Gambar ' . $no_gambar++ . '. Link Akses Hasil Survei', array('size' => 9.5), $paragraphStyleName);


        // #END BAB IV ==============================
        // #==========================================

        $filename = 'Laporan ' .  $manage_survey->survey_name;
        header('Content-Type: application/msword');
        header('Content-Disposition: attachment;filename="' . $filename . '.docx"');
        header('Cache-Control: max-age=0');
        $phpWord->save('php://output');
    }






    public function _draf_kuesioner($img_profile, $manage_survey)
    {
        $table_identity = $manage_survey->table_identity;
        $title_header = unserialize($manage_survey->title_header_survey);
        $title_1 = $title_header[0];
        $title_2 = $title_header[1];
        $skala_likert = 100 / ($manage_survey->skala_likert == '' ? $manage_survey->skala_likert : 4);

        #LAYANAN SURVEI =================
        $nama_layanan = [];
        foreach ($this->db->get_where("layanan_survei_$table_identity", array('is_active' => 1))->result() as $row) {
            $nama_layanan[] = '<img src="' . base_url() . 'assets/img/site/vector/check.png" height="10" alt="" /> ' . $row->nama_layanan . '<br/>';
        }



        # PROFIL RESPONDEN =============================================
        $profil_responden = $this->db->query("SELECT * FROM profil_responden_$table_identity ORDER BY IF(urutan != '',urutan,id) ASC")->result();
        $nama_profil = [];
        foreach ($profil_responden as $get_profil) {
            if ($get_profil->jenis_isian == 1) {
                $kategori = [];
                foreach ($this->db->get_where("kategori_profil_responden_$table_identity", array('id_profil_responden' => $get_profil->id))->result() as $value) {
                    $kategori[] = '<img src="' . base_url() . 'assets/img/site/vector/check.png" height="10" alt="" /> ' . $value->nama_kategori_profil_responden . '<br/>';
                }
                $get_kategori = implode("", $kategori);
            } else {
                $get_kategori = '';
            };
            $nama_profil[] = '<tr style="font-size: 11px;"><td style="width: 30%; height:15px;" valign="top">' . $get_profil->nama_profil_responden . ' </td><td style="width: 70%;">' . $get_kategori . '</td></tr>';
        }


        //=================================== PERTANYAAN TERBUKA ATAS ==========================================
        if (in_array(2, unserialize($manage_survey->atribut_pertanyaan_survey))) {

            $pertanyaan_terbuka_atas = $this->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian_pertanyaan_terbuka, (SELECT DISTINCT(dengan_isian_lainnya) FROM isi_pertanyaan_ganda_$table_identity WHERE isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id) AS dengan_isian_lainnya
            FROM pertanyaan_terbuka_$table_identity
            JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
            WHERE pertanyaan_terbuka_$table_identity.is_letak_pertanyaan = 1");

            if ($pertanyaan_terbuka_atas->num_rows() > 0) {

                $per_terbuka_atas = [];
                foreach ($pertanyaan_terbuka_atas->result() as $value) {

                    if ($value->id_jenis_pilihan_jawaban == 2) {

                        $per_terbuka_atas[] = '
                        <table style="width: 100%; font-size: 11px; border: 1px #000 solid;" cellpadding="3">
                            <tr>
                                <td style="width: 5%; text-align:center; font-size: 11px;">' . $value->nomor_pertanyaan_terbuka . '</td>
                                <td style="width: 32%; text-align:left; font-size: 11px;">' . strip_tags($value->isi_pertanyaan_terbuka) . '</td>
                                <td colspan="2" style="width: 63%; "></td>
                            </tr>
                        </table>';
                    } else {

                        $pilihan_terbuka_atas = [];
                        foreach ($this->db->get_where("isi_pertanyaan_ganda_$table_identity", array('id_perincian_pertanyaan_terbuka' => $value->id_perincian_pertanyaan_terbuka))->result() as $get) {

                            $pilihan_terbuka_atas[] = '<tr>
                                <td style="width: 4%; border-bottom: 2px #000 solid; "></td>
                                <td style="width: 36%; background-color:#C7C6C1; border-left: 2px #000 solid; border-bottom: 2px #000 solid;">' . $get->pertanyaan_ganda . '</td>
                            </tr>';
                        }



                        if ($value->dengan_isian_lainnya == 1) {
                            $get_pilihan_terbuka_atas = implode("", $pilihan_terbuka_atas) . '<tr>
                            <td style="width: 4%; "></td>
                            <td style="width: 36%; background-color:#C7C6C1; border-left: 2px #000 solid; ">Lainnya</td>
                            </tr>';

                            $isi_terbuka_atas[$value->nomor_pertanyaan_terbuka] = $this->db->get_where("isi_pertanyaan_ganda_$table_identity", array('id_perincian_pertanyaan_terbuka' => $value->id_perincian_pertanyaan_terbuka))->num_rows() + 2;
                        } else {
                            $get_pilihan_terbuka_atas = implode("", $pilihan_terbuka_atas);

                            $isi_terbuka_atas[$value->nomor_pertanyaan_terbuka] = $this->db->get_where("isi_pertanyaan_ganda_$table_identity", array('id_perincian_pertanyaan_terbuka' => $value->id_perincian_pertanyaan_terbuka))->num_rows() + 1;
                        }

                        $per_terbuka_atas[] = '
                        <table style="width: 100%; font-size: 11px; border: 1px #000 solid;" cellpadding="3">
                            <tr>
                                <td rowspan="' . $isi_terbuka_atas[$value->nomor_pertanyaan_terbuka] . '" style="width: 5%; text-align:center; font-size: 11px;">' . $value->nomor_pertanyaan_terbuka . '</td>

                                <td rowspan="' . $isi_terbuka_atas[$value->nomor_pertanyaan_terbuka] . '" style="width: 32%; text-align:left; font-size: 11px;">' . $value->isi_pertanyaan_terbuka . '</td>

                                <td colspan="2" style="width: 63%; "><table style="width: 100%; font-size: 11px; border: 0px #000 solid;" cellpadding="0"><tr><td colspan="2" style="width: 40%; border-bottom: 2px #000 solid; ">&nbsp;</td></tr>' . $get_pilihan_terbuka_atas . '</table></td>
                            </tr>
                    </table>';
                    }
                }
                $get_pertanyaan_terbuka_atas = implode("", $per_terbuka_atas);
            } else {
                $get_pertanyaan_terbuka_atas = '';
            }
        } else {
            $get_pertanyaan_terbuka_atas = '';
        };



        //============================================= PERTANYAAN UNSUR =============================================
        $arr_produk = [];
        foreach ($this->db->get('produk')->result() as $pdk) {
            if (in_array($pdk->id, unserialize($manage_survey->atribut_produk_survei))) {

                $pertanyaan_unsur = $this->db->query("SELECT *, (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_kategori_unsur_pelayanan = 1 ) AS pilihan_1,
            (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_kategori_unsur_pelayanan = 2 ) AS pilihan_2,
            (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_kategori_unsur_pelayanan = 3 ) AS pilihan_3,
            (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_kategori_unsur_pelayanan = 4 ) AS pilihan_4,
            (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_kategori_unsur_pelayanan = 5 ) AS pilihan_5
            FROM pertanyaan_unsur_pelayanan_$table_identity
            JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
            WHERE is_produk = $pdk->id
            ORDER BY SUBSTR(nomor_unsur,2) + 0 ASC");

                $per_unsur = [];
                foreach ($pertanyaan_unsur->result() as $row) {


                    if (in_array(2, unserialize($manage_survey->atribut_pertanyaan_survey))) {

                        $pertanyaan_terbuka = $this->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian_pertanyaan_terbuka, (SELECT DISTINCT(dengan_isian_lainnya) FROM isi_pertanyaan_ganda_$table_identity WHERE isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id) AS dengan_isian_lainnya
                    FROM pertanyaan_terbuka_$table_identity
                    JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
                    WHERE  id_unsur_pelayanan = $row->id_unsur_pelayanan");

                        $per_terbuka = [];
                        foreach ($pertanyaan_terbuka->result() as $value) {


                            if ($value->id_jenis_pilihan_jawaban == 2) {

                                $per_terbuka[] = '
                            <table style="width: 100%; font-size: 11px; border: 1px #000 solid;" cellpadding="3">
                                <tr>
                                    <td style="width: 5%; text-align:center; font-size: 11px;">' . $value->nomor_pertanyaan_terbuka . '</td>
                                    <td style="width: 32%; text-align:left; font-size: 11px;">' . $value->isi_pertanyaan_terbuka . '</td>
                                    <td colspan="2" style="width: 63%; "></td>
                                </tr>
                            </table>
                        ';
                            } else {

                                $pilihan_terbuka = [];
                                foreach ($this->db->get_where("isi_pertanyaan_ganda_$table_identity", array('id_perincian_pertanyaan_terbuka' => $value->id_perincian_pertanyaan_terbuka))->result() as $get) {

                                    $pilihan_terbuka[] = '<tr>
                                <td style="width: 4%; border-bottom: 2px #000 solid; "></td>
                                <td style="width: 36%; background-color:#C7C6C1; border-left: 2px #000 solid; border-bottom: 2px #000 solid;">' . $get->pertanyaan_ganda . '</td>
                                </tr>';
                                }

                                if ($value->dengan_isian_lainnya == 1) {
                                    $get_pilihan_terbuka = implode("", $pilihan_terbuka) . '<tr>
                                <td style="width: 4%; "></td>
                                <td style="width: 36%; background-color:#C7C6C1; border-left: 2px #000 solid; ">Lainnya</td>
                                </tr>';

                                    $isi[$value->nomor_pertanyaan_terbuka] = $this->db->get_where("isi_pertanyaan_ganda_$table_identity", array('id_perincian_pertanyaan_terbuka' => $value->id_perincian_pertanyaan_terbuka))->num_rows() + 2;
                                } else {
                                    $get_pilihan_terbuka = implode("", $pilihan_terbuka);

                                    $isi[$value->nomor_pertanyaan_terbuka] = $this->db->get_where("isi_pertanyaan_ganda_$table_identity", array('id_perincian_pertanyaan_terbuka' => $value->id_perincian_pertanyaan_terbuka))->num_rows() + 1;
                                }


                                $per_terbuka[] = '
                            <table style="width: 100%; font-size: 11px; border: 1px #000 solid;" cellpadding="3">
                                <tr>
                                    <td rowspan="' . $isi[$value->nomor_pertanyaan_terbuka] . '" style="width: 5%; text-align:center; font-size: 11px;">' . $value->nomor_pertanyaan_terbuka . '</td>

                                    <td rowspan="' . $isi[$value->nomor_pertanyaan_terbuka] . '" style="width: 32%; text-align:left; font-size: 11px;">' . $value->isi_pertanyaan_terbuka . '</td>

                                    <td colspan="2" style="width: 63%; "><table style="width: 100%; font-size: 11px; border: 0px #000 solid;" cellpadding="0"><tr><td colspan="2" style="width: 40%; border-bottom: 2px #000 solid; ">&nbsp;</td></tr>' . $get_pilihan_terbuka . '</table></td>
                                </tr>
                            </table>
                        ';
                            }
                        }
                        $get_pertanyaan_terbuka = implode("", $per_terbuka);
                    } else {
                        $get_pertanyaan_terbuka = '';
                    }



                    $pilihan_ke_2 = $row->pilihan_4;
                    $width = 10;
                    if ($row->jenis_pilihan_jawaban == 1) {

                        $per_unsur[] = '
                    <table style="width: 100%; border: 1px #000 solid; " cellpadding="4">
                        <tr>
                            <td rowspan="2" style="width: 5%; text-align:center; font-size: 11px;">' . $row->nomor_unsur . '</td>
                            <td rowspan="2" style="width: 32%; text-align:left; font-size: 11px;">' . $row->isi_pertanyaan_unsur . '</td>
                            <td style="width: 20%; background-color:#C7C6C1; text-align:center; font-size: 11px;">' . $row->pilihan_1 . '</td>
                            <td style="width: 20%; background-color:#C7C6C1; text-align:center; font-size: 11px;">' . $pilihan_ke_2 . '</td>
                            <td rowspan="2" style="width: 23%; text-align:left; font-size: 11px;"></td>
                        </tr>
                    </table>
                ' . $get_pertanyaan_terbuka;
                    } else {


                        $per_unsur[] = '
                <table style="width: 100%; border: 1px #000 solid; " cellpadding="4">
                    <tr>
                        <td rowspan="2" style="width: 5%; text-align:center; font-size: 11px;">' . $row->nomor_unsur . '</td>
                        <td rowspan="2" style="width: 32%; text-align:left; font-size: 11px;">' . strip_tags($row->isi_pertanyaan_unsur) . '</td>
                        <td style="width: ' . $width . '%; background-color:#C7C6C1; text-align:center; font-size: 11px;">' . $row->pilihan_1 . '</td>
                        <td style="width: ' . $width . '%; background-color:#C7C6C1; text-align:center; font-size: 11px;">' . $row->pilihan_2 . '</td>
                        <td style="width: ' . $width . '%; background-color:#C7C6C1; text-align:center; font-size: 11px;">' . $row->pilihan_3 . '</td>
                        <td style="width: ' . $width . '%; background-color:#C7C6C1; text-align:center; font-size: 11px;">' . $row->pilihan_4 . '</td>
                        <td rowspan="2" style="width: 23%; text-align:left; font-size: 11px;"></td>
                    </tr>
                </table>
                ' . $get_pertanyaan_terbuka;
                    }
                }
                $get_pertanyaan_unsur = implode("", $per_unsur);

                $arr_produk[] = '<table style="width: 100%; border: 1px #000 solid; " cellpadding="4">
                            <tr>
                                <td style="text-align:left; font-size: 11px;"><b>' . strtoupper($pdk->nama) . '</b></td>
                            </tr>
                        </table>' . implode("", $per_unsur);
            }
        }
        $get_pertanyaan_unsur = implode("", $arr_produk);



        //============================================= PERTANYAAN TERBUKA BAWAH =========================================
        if (in_array(2, unserialize($manage_survey->atribut_pertanyaan_survey))) {

            $pertanyaan_terbuka_bawah = $this->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian_pertanyaan_terbuka, (SELECT DISTINCT(dengan_isian_lainnya) FROM isi_pertanyaan_ganda_$table_identity WHERE isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id) AS dengan_isian_lainnya
            FROM pertanyaan_terbuka_$table_identity
            JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
            WHERE pertanyaan_terbuka_$table_identity.is_letak_pertanyaan = 2");

            if ($pertanyaan_terbuka_bawah->num_rows() > 0) {

                $per_terbuka_bawah = [];
                foreach ($pertanyaan_terbuka_bawah->result() as $value) {

                    if ($value->id_jenis_pilihan_jawaban == 2) {

                        $per_terbuka_bawah[] = '
                        <table style="width: 100%; font-size: 11px; border: 1px #000 solid; " cellpadding="3">
                            <tr>
                                <td style="width: 5%; text-align:center; font-size: 11px;">' . $value->nomor_pertanyaan_terbuka . '</td>
                                <td style="width: 32%; text-align:left; font-size: 11px;">' . strip_tags($value->isi_pertanyaan_terbuka) . '</td>
                                <td colspan="2" style="width: 63%; "></td>
                                <!--<td style="width: 23%; text-align:left; font-size: 11px;"></td>-->
                            </tr>
                        </table>';
                    } else {

                        $pilihan_terbuka_bawah = [];
                        foreach ($this->db->get_where("isi_pertanyaan_ganda_$table_identity", array('id_perincian_pertanyaan_terbuka' => $value->id_perincian_pertanyaan_terbuka))->result() as $get) {

                            $pilihan_terbuka_bawah[] = '<tr>
                                <td style="width: 4%; border-bottom: 2px #000 solid; "></td>
                                <td style="width: 36%; background-color:#C7C6C1; border-left: 2px #000 solid; border-bottom: 2px #000 solid;">' . $get->pertanyaan_ganda . '</td>
                            </tr>';
                        }



                        if ($value->dengan_isian_lainnya == 1) {
                            $get_pilihan_terbuka_bawah = implode("", $pilihan_terbuka_bawah) . '<tr>
                    <td style="width: 4%; "></td>
                    <td style="width: 36%; background-color:#C7C6C1; border-left: 2px #000 solid; ">Lainnya</td>
                    </tr>';

                            $isi_terbuka_bawah[$value->nomor_pertanyaan_terbuka] = $this->db->get_where("isi_pertanyaan_ganda_$table_identity", array('id_perincian_pertanyaan_terbuka' => $value->id_perincian_pertanyaan_terbuka))->num_rows() + 2;
                        } else {
                            $get_pilihan_terbuka_bawah = implode("", $pilihan_terbuka_bawah);

                            $isi_terbuka_bawah[$value->nomor_pertanyaan_terbuka] = $this->db->get_where("isi_pertanyaan_ganda_$table_identity", array('id_perincian_pertanyaan_terbuka' => $value->id_perincian_pertanyaan_terbuka))->num_rows() + 1;
                        }

                        $per_terbuka_bawah[] = '
                    <table style="width: 100%; font-size: 11px; border: 1px #000 solid; " cellpadding="3">
                        <tr>
                            <td rowspan="' . $isi_terbuka_bawah[$value->nomor_pertanyaan_terbuka] . '" style="width: 5%; text-align:center; font-size: 11px;">' . $value->nomor_pertanyaan_terbuka . '</td>

                            <td rowspan="' . $isi_terbuka_bawah[$value->nomor_pertanyaan_terbuka] . '" style="width: 32%; text-align:left; font-size: 11px;">' . $value->isi_pertanyaan_terbuka . '</td>

                            <td colspan="2" style="width: 63%; "><table style="width: 100%; font-size: 11px; border: 0px #000 solid;" cellpadding="0"><tr><td colspan="2" style="width: 40%; border-bottom: 2px #000 solid; ">&nbsp;</td></tr>' . $get_pilihan_terbuka_bawah . '</table></td>
                                    
                            <!--<td rowspan="' . $isi_terbuka_bawah[$value->nomor_pertanyaan_terbuka] . '" style="width: 23%; text-align:left; font-size: 11px;"></td>-->
                        </tr>
                </table>';
                    }
                }
                $get_pertanyaan_terbuka_bawah = implode("", $per_terbuka_bawah);
            } else {
                $get_pertanyaan_terbuka_bawah = '';
            }
        } else {
            $get_pertanyaan_terbuka_bawah = '';
        };



        // ======================================== PERTANYAAN KUALITATIF ======================================
        if (in_array(3, unserialize($manage_survey->atribut_pertanyaan_survey))) {

            $pertanyaan_kualitatif = $this->db->get_where("pertanyaan_kualitatif_$table_identity", array('is_active' => 1));
            $per_kualitatif = [];
            $no = 1;
            foreach ($pertanyaan_kualitatif->result() as $row) {
                $per_kualitatif[] = '
                <tr>
                    <td style="width: 5%; ">' . $no++ . '</td>
                    <td style="width: 32%; ">' . $row->isi_pertanyaan . '</td>
                    <td style="width: 63%; "></td>
                </tr>
            ';
            }
            $get_pertanyaan_kualitatif = '
            <table style="width: 100%; border: 1px #000 solid; " cellpadding="3">
                <tr>
                    <td style="width: 100%; text-align:left; font-size: 11px; background-color: black; color:white;"><b>PENILAIAN KUALITATIF PERSEPSI ANTI KORUPSI</b></td>
                </tr>
                <tr>
                    <td style="width: 100%; text-align:left; font-size: 11px; background-color: black; color:white;">Berikan jawaban sesuai dengan pendapat dan pengetahuan Saudara.</td>
                </tr>
            </table>

            <table style="width: 100%; font-size: 11px; background-color:#C7C6C1; border: 1px #000 solid; " cellpadding="3">
                <tr>
                    <td style="width: 5%; text-align:center; ">No</td>
                    <td style="width: 32%; text-align:center; ">PERTANYAAN</td>
                    <td style="width: 63%; text-align:center; ">JAWABAN</td>
                </tr>
            </table>

            <table style="width: 100%; font-size: 11px; border: 1px #000 solid; " cellpadding="10">
                ' . implode("", $per_kualitatif) . '
            </table>';
        } else {
            $get_pertanyaan_kualitatif = '';
        }



        // =============================================== STATUS SARAN ================================================
        if ($manage_survey->is_saran == 1) {
            $is_saran = '<tr>
            <td style="width: 100%; text-align:left; font-size: 11px;"><b>SARAN :</b>
                <br/>
                <br/>
                <br/>
            </td>
        </tr>';
        } else {
            $is_saran = '';
        }





        #START HTML ======================================================
        $html = '
         <table style="width: 100%; border: 1px #000 solid;">
             <tr>
                 <td style="width: 100%; ">
                     <table cellspacing="2" cellpadding="1" style="width: 100%;">
                         <tr>
                             <td style="width: 10%;"><img src="' . base_url() . 'assets/klien/foto_profile/' . $img_profile . '" height="50" alt="" /></td>
                             <td style="width: 90%; font-size:12px; font-weight:bold;">' . strtoupper($title_1) . '<br/>' . strtoupper($title_2) . '</td>
                         </tr>
                     </table>
                 </td>
             </tr>
         </table>

         <table style="width: 100%; border: 1px #000 solid;" cellpadding="7">
             <tr>
                 <td style="width: 100%; text-align:center; font-size: 11px; font-family:Arial, Helvetica, sans-serif; height:35px; ">Dalam rangka mengukur indeks persepsi anti korupsi, Saudara dipercaya menjadi responden pada kegiatan survei ini.<br/>Atas kesediaan Saudara kami sampaikan terima kasih dan penghargaan sedalam-dalamnya.</td>
             </tr>
         </table>
 
 
         <table style="width: 100%; border: 1px #000 solid;" cellpadding="3">
             <tr>
                 <td style="width: 100%; font-size: 11px; background-color: black; color:white; height:15px;"><b>DATA RESPONDEN</b> (Berilah tanda silang (x) sesuai jawaban Saudara pada kolom yang tersedia)</td>
             </tr>
         </table>

         <table style="width: 100%; border: 1px #000 solid;" cellpadding="4">
             <tr style="font-size: 11px;">
                 <td style="width: 30%; height:15px;">Jenis Pelayanan yang diterima</td>
                 <td style="width: 70%; ">' . implode("", $nama_layanan) . '</td>
             </tr>' . implode("", $nama_profil) . '
        </table>


        <table style="width: 100%; border: 1px #000 solid;" cellpadding="3">
            <tr>
                <td style="width: 100%; text-align:left; font-size: 11px; background-color: black; color:white;"><b>PENILAIAN TERHADAP UNSUR-UNSUR PERSEPSI ANTI KORUPSI</b><br/>Berilah tanda silang (x) sesuai jawaban Saudara</td>
            </tr>
        </table>


        <table style="width: 100%; border: 1px #000 solid; background-color:#C7C6C1;" cellpadding="4">
            <tr style="font-size: 11px;">
                <td style="width: 5%; text-align:center; ">No</td>
                <td style="width: 32%; text-align:center; ">PERTANYAAN</td>
                <td style="width: 40%; ">
                    <table style="width: 100%; border: 0px #000 solid;" cellpadding="4">
                        <tr style="font-size: 11px;">
                            <td colspan="4" style="text-align:center;">PILIHAN JAWABAN</td>
                        </tr>
                        <tr>
                            <td style="width: 25%; text-align:center; border-right: 2px #000 solid; ">1</td>
                            <td style="width: 25%; text-align:center; border-right: 2px #000 solid; ">2</td>
                            <td style="width: 25%; text-align:center; border-right: 2px #000 solid; ">3</td>
                            <td style="width: 25%; text-align:center; ">4</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 23%; text-align:center; ">Berikan alasan jika pilihan jawaban: 1 atau 2</td>
            </tr>
        </table>
        ' . $get_pertanyaan_terbuka_atas . $get_pertanyaan_unsur . $get_pertanyaan_kualitatif . '
        <table style="width: 100%; border: 1px #000 solid;" cellpadding="5">' . $is_saran . '
            <tr>
                <td style="width: 100%; text-align:center; font-size: 11px;">Terima kasih atas kesediaan Saudara mengisi kuesioner tersebut di atas.<br/>Saran dan penilaian Saudara memberikan konstribusi yang sangat berarti bagi instansi ini.</td>
            </tr>
        </table>
        ';
        #END HTML ======================================================


        return $html;
    }
}

/* End of file ReportController.php */
