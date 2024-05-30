<?php
$ci = get_instance();
?>

<div class="row mb-5">
    <div class="col-6">
        <div class="card card-body" id="chart-spkp"></div>
    </div>
    <div class="col-6">
        <div class="card card-body" id="chart-spak"></div>
    </div>

</div>



<script>
FusionCharts.ready(function() {
    var myChart = new FusionCharts({
        type: "<?= $total_survey > 10 ? 'bar3d' : 'column3d' ?>",
        renderAt: "chart-spkp",
        width: "100%",
        height: "<?= $total_survey > 10 ? '700' : '350' ?>",
        dataFormat: "json",
        dataSource: {
            chart: {
                caption: "IPKP",
                subcaption: "Survei Persepsi Kualitas Pelayanan",
                decimals: "3",
                showvalues: "1",
                theme: "umber",
                "bgColor": "#ffffff"
            },
            data: [<?php echo $chart_spkp ?>]
        }
    });
    myChart.render();
});
</script>

<script>
FusionCharts.ready(function() {
    var myChart = new FusionCharts({
        type: "<?= $total_survey > 10 ? 'bar3d' : 'column3d' ?>",
        renderAt: "chart-spak",
        width: "100%",
        height: "<?= $total_survey > 10 ? '700' : '350' ?>",
        dataFormat: "json",
        dataSource: {
            chart: {
                caption: "IPAK",
                subcaption: "Survei Persepsi Anti Korupsi",
                decimals: "3",
                showvalues: "1",
                theme: "umber",
                "bgColor": "#ffffff"
            },
            data: [<?php echo $chart_spak ?>]
        }
    });
    myChart.render();
});
</script>


<?php /**PATH C:\xampp-7.3.33\htdocs\surveiku_spak_spkp\application\views/dashboard/chart_survei.blade.php ENDPATH**/ ?>