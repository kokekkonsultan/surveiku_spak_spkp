<!--============================================== BAB II =================================================== -->
<div class="page-session">
    <table style="width: 100%;">
        <tr>
            <td style="text-align: center; font-size:16px; font-weight: bold;">
                BAB II
                <br>
                METODOLOGI SURVEI
                <br>
                <br>
            </td>
        </tr>
    </table>


    <table style="width: 100%;">
        <tr>
            <td width="3%"><b><?= $no_Bab2++ ?>.</b></td>
            <td><b>Kriteria Responden</b></td>
        </tr>

        <?php if ($manage_survey->id_sampling == 1) { ?>
            <tr>
                <td></td>
                <td class="content-paragraph">Responden adalah seluruh pihak yang pernah mendapatkan pelayanan di unit ini. Jumlah responden yang digunakan dalam Survei Persepsi Kualitas Pelayanan (SPKP) dan Survei Persepsi Anti Korupsi (SPAK) ini dihitung menggunakan rumus Krejcie sebagai berikut:
                    <br />
                    <br />
                    <b>Rumus Krejcie</b>
                    <div style="text-align:center;">
                        <img src="<?= base_url() . 'assets/img/site/rumus_krejcie.png' ?>" alt="rumus krejcie" width="50%">
                    </div>
                </td>
            </tr>

            <tr>
                <td></td>
                <td class="content-text">Keterangan :
                    <div style="padding-left:4em;">
                        <table style="width: 100%;">
                            <tr>
                                <td width="7%">&nbsp;S</td>
                                <td width="5%">:</td>
                                <td>Jumlah sampel</td>
                            </tr>
                            <tr>
                                <td width="7%"><img src="<?= base_url() . 'assets/img/site/lamda.png' ?>" alt="rumus krejcie" width="60%"></td>
                                <td width="5%">:</td>
                                <td>Lamda (faktor pengali) dengan dk = 1,<br>(taraf kesalahan yang digunakan 5%, sehingga
                                    nilai lamba 3,841)
                                </td>
                            </tr>
                            <tr>
                                <td width="7%">&nbsp;N</td>
                                <td width="5%">:</td>
                                <td>Populasi sebanyak <?= $manage_survey->jumlah_populasi ?></td>
                            </tr>
                            <tr>
                                <td width="7%">&nbsp;P</td>
                                <td width="5%">:</td>
                                <td>Q = 0,5 (populasi menyebar normal)</td>
                            </tr>
                            <tr>
                                <td width="7%">&nbsp;d</td>
                                <td width="5%">:</td>
                                <td>0,05</td>
                            </tr>
                        </table>
                    </div>
                    <div>Sehingga dari perhitungan di atas, jumlah responden minimal yang harus diperoleh adalah
                        <?= $manage_survey->jumlah_sampling ?> responden.</div><br />
                </td>
            </tr>
        <?php } else { ?>

            <tr>
                <td></td>
                <td>Responden adalah seluruh pihak yang pernah mendapatkan pelayanan di unit ini.</td>
            </tr>


        <?php } ?>
    </table>


    <table style="width: 100%;">
        <tr>
            <td width="3%"><b><?= $no_Bab2++ ?>.</b></td>
            <td><b>Metode Pencacahan</b></td>
        </tr>

        <tr>
            <td></td>
            <td class="content-paragraph">Pengumpulan data dilakukan dengan menggunakan metode survei elektronik melalui
                sistem broadcast data. Broadcast data dilakukan melalui WhatsApp, SMS, Email, dan scan barcode.
                <br />
                <br />
            </td>
        </tr>
    </table>

    <table style="width: 100%;">
        <tr>
            <td width="3%"><b><?= $no_Bab2++ ?>.</b></td>
            <td><b>Metode Pengolahan Data dan Analisis</b></td>
        </tr>

        <tr>
            <td></td>
            <td class="content-paragraph">Metode yang digunakan dalam pengolahan data dan analisis Survei Persepsi Kualitas Pelayanan (SPKP) dan Survei Persepsi Anti Korupsi (SPAK) ini menggunakan aplikasi survei yang akan menghasilkan analisis deskriptif kuantitatif.
            </td>
        </tr>
    </table>
</div>