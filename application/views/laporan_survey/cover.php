<div class="page-session">
    <div style="text-align:center;">
        <br>

        <?php if ($profiles->foto_profile != '' || $profiles->foto_profile != null) { ?>
            <img src="<?= base_url() . 'assets/klien/foto_profile/' . $profiles->foto_profile ?>" alt="Logo" width="250" class="center">
        <?php } else { ?>
            <img src="<?= base_url() ?>assets/klien/foto_profile/200px.jpg" alt="Logo" width="250" class="center">
        <?php } ?>



        <br>
        <br>
        <br>
        <br>


        <div style="font-size:25px; font-weight:bold;">
            LAPORAN<br>SURVEI PERSEPSI KUALITAS PELAYANAN<br>DAN SURVEI PERSEPSI ANTI KORUPSI
        </div>
        <br>
        <br>
        <br>
        <div style="font-size:20px; font-weight:bold;">
            <?= strtoupper($manage_survey->organisasi) ?>
        </div>
        <br>
        <br>


        <?php
        $bulan = array(
            1 =>   'JANUARI',
            'FEBRUARI',
            'MARET',
            'APRIL',
            'MEI',
            'JUNI',
            'JULI',
            'AGUSTUS',
            'SEPTEMBER',
            'OKTOBER',
            'NOVENBER',
            'DESEMBER'
        );
        $month_start = $bulan[(int) date("m", strtotime($manage_survey->survey_start))];
        $month_end = $bulan[(int) date("m", strtotime($manage_survey->survey_end))];
        $year_start = date("Y", strtotime($manage_survey->survey_end));
        $year_end = date("Y", strtotime($manage_survey->survey_end));

        if ($month_start == $month_end) {
            $periode =  $month_end . ' ' . $year_end;
        } else {
            $periode =  $month_start . ' - ' . $month_end . ' ' . $year_end;
        }
        ?>



        <div style="font-size:17px; font-weight:bold;">
            PERIODE <?= $periode ?>
        </div>

    </div>
</div>