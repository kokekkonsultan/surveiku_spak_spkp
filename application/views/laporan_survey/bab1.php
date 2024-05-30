<!--============================================== BAB I =================================================== -->
<div class="page-session">
    <table style="width: 100%;">
        <tr>
            <td style="text-align: center; font-size:16px; font-weight: bold;">
                BAB I
                <br>
                KUESIONER SURVEI
                <br>
                <br>
            </td>
        </tr>
    </table>

    <table style="width: 100%;">
        <tr>
            <td width="3%"><b><?= $no_Bab1++ ?>.</b></td>
            <td><b>Variable Survei</b></td>
        </tr>
        <tr>
            <td></td>
            <td class="content-text">
                Variabel Survei Persepsi Kualitas Pelayanan (SPKP) meliputi  :
                <ol>
                    <li>Informasi pelayanan<br>
                        Informasi pelayanan melalui media elektronik maupun non elektronik selalu tersedia dan dapat
                        menjawab kebutuhan pengguna layanan, mudah digunakan, serta memiliki fasilitas interaktif dan
                        FAQ.
                    </li>
                    <li>Persyaratan pelayanan<br>
                        Informasi persyaratan layanan dapat dipahami dengan jelas untuk mendapatkan produk/jenis
                        pelayanan serta penerapan persyaratan pelayanan sesuai dengan yang diinformasikan.
                    </li>
                    <li>Sistem, mekanisme, dan prosedur<br>
                        Informasi prosedur/alur layanan dapat dipahami dengan jelas dan sesuai untuk mendapatkan
                        produk/jenis pelayanan serta penerapan prosedur/alur layanan sesuai yang diinformasikan.
                    </li>
                    <li>Waktu penyelesaian pelayanan<br>
                        Jangka waktu penyelesaian pelayanan dapat dipahami dengan jelas, jangka waktu penyelesaian
                        pelayanan tersebut wajar dan penyelesaian pelayanan sesuai dengan yang diinformasikan.
                    </li>
                    <li>Tarif/biaya pelayanan<br>
                        Biaya pelayanan dapat dipahami dengan jelas dan biaya pelayanan dibayarkan sesuai dengan yang
                        diinformasikan, termasuk bila biaya pelayanan gratis.
                    </li>

                    <li>Sarana dan prasarana<br>
                        Sarana prasarana pendukung pelayanan/sistem pelayanan online sudah mempermudah proses pelayanan,
                        meringkas waktu dan hemat biaya.
                    </li>
                    <li>Kecepatan respon pelayanan<br>
                        Kemampuan petugas dalam memberikan respon pelayanan dengan cepat kepada pengguna layanan, baik
                        melalui tatap muka maupun melalui aplikasi layanan daring.
                    </li>
                    <li>Konsultasi dan pengaduan<br>
                        Sarana layanan konsultasi dan pengaduan beragam (tempat konsultasi dan pengaduan/hotline/call
                        center/media online), prosedur untuk melakukan pelayanan mudah dan pengaduan mudah, respon
                        konsultasi dan pengaduan cepat serta tindak lanjut jelas.
                    </li>
                </ol>
            </td>
        </tr>
        <tr>
            <td></td>
            <td class="content-text">
                <br>
                Variabel Survei Persepsi Anti Korupsi (SPAK) meliputi :
                <ol>
                    <li>Diskriminasi pelayanan<br>
                        Petugas memberikan pelayanan secara khusus atau membeda-bedakan pelayanan karena
                        faktor suku, agama, kekerabatan, almamater, dan sejenisnya.
                    </li>
                    <li>Kecurangan pelayanan <br>
                        Petugas memberikan pelayanan yang tidak sesuai dengan ketentuan sehingga
                        mengindikasikan kecurangan, seperti penyerobotan antrian, mempersingkat waktu tunggu
                        layanan diluar prosedur, pengurangan syarat/prosedur, pengurangan denda, dll.
                    </li>
                    <li>Menerima imbalan dan/atau gratifikasi<br>
                        Petugas menerima/bahkan meminta imbalan uang untuk alasan administrasi,
                        transportasi, rokok, kopi, dll diluar ketentuan, pemberian imbalan barang berupa
                        makanan jadi, rokok, parsel, perhiasan, elektronik, pakaian, bahan pangan, dll
                        diluar ketentuan, pemberian imbalan fasilitas berupa akomodasi (hotel, resort
                        perjalanan/jasa transportasi, komunikasi, hiburan, voucher belanja, dll) diluar
                        ketentuan.
                    </li>
                    <li>Pungutan liar<br>
                        Petugas melakukan pungli, yaitu permintaan pembayaran atas pelayanan yang diterima
                        pengguna layanan diluar tarif resmi (Pungli bisa dikamuflasekan melalui berbagai
                        istilah seperti “uang administrasi”, “uang rokok”, “uang terima kasih”, dsb).
                    </li>
                    <li>Percaloan<br>
                        Praktik percaloan (pihak yang melakukan percaloan dapat berasal dari oknum pegawai
                        pada unit layanan ini, maupun pihak luar yang memiliki hubungan atau tidak memiliki
                        hubungan dengan oknum pegawai).
                    </li>
                </ol>
            </td>
        </tr>
    </table>

    <table style="width: 100%;">
        <tr>
            <td width="3%"><b><?= $no_Bab1++ ?>.</b></td>
            <td><b>Kuesioner Survei</b></td>
        </tr>
        <tr>
            <td width="3%"></td>
            <td class="content-text">Kuesioner yang digunakan dalam pelaksanaan survei adalah sebagai berikut:</td>
        </tr>
    </table>

    <!-- LOAD DRAF KUESIONER -->
    <div style="padding-left:1.5em;">
        <?php $this->view('laporan_survey/view_draf_kuesioner'); ?>
    </div>
</div>