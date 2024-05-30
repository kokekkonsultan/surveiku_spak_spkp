<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>

    <style>
        @page {
            margin: 100px 20px;
        }

        .content-paragraph {
            text-indent: 5%;
            text-align: justify;
            text-justify: inter-word;
            line-height: 1.5;
            margin-left: 76px;
            margin-right: 76px;

        }

        .content-list {
            text-indent: 10%;
            text-align: justify;
            text-justify: inter-word;
            line-height: 1.5;

        }

        .content-text {
            text-align: justify;
            text-justify: inter-word;
            line-height: 1.5;
            margin-left: 76px;
            margin-right: 76px;

        }

        .page-session {
            page-break-after: always;
            font-family: Calibri, sans-serif;
            margin: 0.2in 0.5in 0.2in 0.5in;
        }

        .page-session:last-child {
            page-break-after: never;
        }

        .table-list {
            border-collapse: collapse;
            font-family: sans-serif;

            text-align: center;
        }

        table,
        th,
        td {
            font-size: 13px;
            padding: 3px;
            font-family: Arial, Helvetica, sans-serif;
        }

        li {
            padding: 4px;
            text-align: justify;
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.5;
        }

        .td-th-list {
            border: 1px solid black;
            height: 20px;
            font-family: Arial, Helvetica, sans-serif;
            vertical-align: middle;
        }

        header {
            position: fixed;
            top: -90px;
            left: 0px;
            right: 0px;
            height: 50px;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            /* background-color: lightblue; */
            height: 50px;
        }

        footer .page:after {
            content: counter(page, decimal);
        }

        input[type=checkbox] {
            display: inline;
        }

        .th-td-draf {
            border: 1px solid black;
            font-size: 11px;
            height: 15px;
        }
    </style>
</head>

<body>

    <!--============================================== COVER =================================================== -->
    <?php //$this->load->view('laporan_survey/cover'); ?>


    <header>
        <table style="width: 90%; margin-left: auto; margin-right: auto;" class="table-list">
            <tr>
                <td style="width: 15%;">
                    <?php if ($profiles->foto_profile != '' || $profiles->foto_profile != null) { ?>
                        <img src="<?= base_url() . 'assets/klien/foto_profile/' . $profiles->foto_profile ?>" alt="Logo" width="70">
                    <?php } else { ?>
                        <img src="<?= base_url() . 'assets/klien/foto_profile/200px.jpg' ?>" alt="Logo" width="70">
                    <?php } ?>
                </td>
                <td>
                    <div style="color:#DE2226; font-size:15px; vertical-align:middle;">
                        <b>L A P O R A N</b>
                    </div>
                    SURVEI PERSEPSI KUALITAS PELAYANAN<br>DAN SURVEI PERSEPSI ANTI KORUPSI
                    <br>
                    <?= strtoupper($manage_survey->organisasi) ?>
                </td>

                <td style="width: 15%;">
                </td>
            </tr>
        </table>
        <hr>
    </header>

    <footer>
        <hr>
        <table style="width: 90%; margin-left: auto; margin-right: auto;">
            <tr>
                <td align="left">SPKP dan SPAK <?= date('Y') ?>
                    <br>
                    <b>Generate by <a target="_blank" href="https://surveiku.com/" style="color:black;">SurveiKu.com</a></b>
                </td>
                <td align="right">
                    <p class="page"></p>
                </td>
            </tr>
        </table>
    </footer>




    <main>

        <!--============================================== BAB I =================================================== -->
        <?php $this->load->view('laporan_survey/bab1'); ?>

        <!--============================================== BAB II =================================================== -->
        <?php $this->load->view('laporan_survey/bab2'); ?>

        <!--============================================== BAB III =================================================== -->
        <?php $this->load->view('laporan_survey/bab3'); ?>

        <!--============================================== BAB IV =================================================== -->
        <?php $this->load->view('laporan_survey/bab4'); ?>


    </main>
</body>

</html>