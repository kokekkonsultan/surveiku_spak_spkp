@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />


<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
</link>


<style type="text/css">
    [pointer-events="bounding-box"] {
        display: none
    }

    .dataTables_length {
        display: none
    }

    .dataTables_filter {
        display: none
    }
</style>

@endsection

@section('content')

<div class="container-fluid">
    @include("include_backend/partials_no_aside/_inc_menu_repository")

    <div class="row mt-5" data-aos="fade-down">
        <div class="col-md-3">
            @include('manage_survey/menu_data_survey')
        </div>
        <div class="col-md-9">

            <div class="card card-custom card-sticky mb-5">
                <div class="card-body">

                    <div class="d-flex justify-content-center" id="chart"></div>
                    <br>

                    <table class="table table-bordered table-striped example" style="width:100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kelompok</th>
                                <th>Jumlah</th>
                                <th>Persentase</th>
                                <th>Indeks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $no = 1;
                            $chart = [];
                            @endphp
                            @foreach ($layanan->result() as $value)

                            @php
                            $indeks = ROUND($ci->db->query("SELECT AVG(rata_rata) AS rata_rata
                            FROM ( SELECT nama_unsur_pelayanan, IF(id_parent = 0, unsur_pelayanan_$manage_survey->table_identity.id, unsur_pelayanan_$manage_survey->table_identity.id_parent) AS id_sub,
                            AVG(IF(jawaban_pertanyaan_unsur_$manage_survey->table_identity.skor_jawaban != 0, skor_jawaban, NULL)) AS rata_rata
                            
                            FROM jawaban_pertanyaan_unsur_$manage_survey->table_identity
                            JOIN pertanyaan_unsur_pelayanan_$manage_survey->table_identity ON jawaban_pertanyaan_unsur_$manage_survey->table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id
                            JOIN unsur_pelayanan_$manage_survey->table_identity ON pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id_unsur_pelayanan = unsur_pelayanan_$manage_survey->table_identity.id
                            JOIN survey_$manage_survey->table_identity ON jawaban_pertanyaan_unsur_$manage_survey->table_identity.id_responden = survey_$manage_survey->table_identity.id_responden
                            WHERE survey_$manage_survey->table_identity.id_responden IN ($value->id_responden)
                            GROUP BY id_sub
                            ORDER BY SUBSTR(nomor_unsur,2) + 0) ni")->row()->rata_rata ,3);

                            $chart[] = '{
                                            "label": "' . $value->nama_layanan . '",
                                            "value": "' . $indeks . '"
                                        }';
                            @endphp
                            

                            <tr>
                                <td>{{$no++}}</td>
                                <td>{{$value->nama_layanan}}</td>
                                <td>{{$value->perolehan}}</th>
                                <td>{{ ROUND(($value->perolehan / $value->total_survei) * 100, 2)}} %</td>
                                <td>{{$indeks}}</td>
                               
                            </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.js"></script>

<script src="{{ base_url() }}assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.accessibility.js">
</script>
<script src="{{ base_url() }}assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.candy.js"></script>
<script src="{{ base_url() }}assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.carbon.js"></script>
<script src="{{ base_url() }}assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.fint.js"></script>
<script src="{{ base_url() }}assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.fusion.js"></script>
<script src="{{ base_url() }}assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.gammel.js"></script>
<script src="{{ base_url() }}assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.ocean.js"></script>
<script src="{{ base_url() }}assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.umber.js"></script>
<script src="{{ base_url() }}assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.zune.js"></script>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


<script>
    $(document).ready(function() {
        $('.example').DataTable();
    });
</script>



<script>
    FusionCharts.ready(function() {
        var myChart = new FusionCharts({
            "type": "bar3d",
            "renderAt": "chart",
            "width": "100%",
            "height": "350",
            "dataFormat": "json",
            dataSource: {
                "chart": {
                    caption: "Layanan Survei",
                    subcaption: "Jenis Pelayanan yang di Survei",
                    "enableSmartLabels": "1",
                    "startingAngle": "0",
                    "showPercentValues": "1",
                    "decimals": "2",
                    "useDataPlotColorForLabels": "1",
                    // "theme": "umber",
                    // "bgColor": "#ffffff",

                    theme: "fusion"
                },
                "data": [<?= implode(", ", $chart) ?>]
            }

        });
        myChart.render();
    });
</script>


@endsection