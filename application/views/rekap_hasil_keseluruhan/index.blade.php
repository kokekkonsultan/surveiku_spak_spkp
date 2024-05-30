@extends('include_backend/template_backend')



@php

$ci = get_instance();

@endphp



@section('style')

<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"

    type="text/css" />

@endsection



@section('content')



<div class="container-fluid">

    <div class="card card-custom card-sticky" data-aos="fade-down" data-aos-delay="300">

        <div class="card-body">





            <div class="card card-custom bgi-no-repeat gutter-b"

                style="height: 150px; background-color: #1c2840; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(/assets/img/banner/rhone-2.svg)"

                data-aos="fade-down">

                <div class="card-body d-flex align-items-center">

                    <div>

                        <h3 class="text-white font-weight-bolder line-height-lg mb-5">

                            {{strtoupper($title)}}

                        </h3>



                        <div class="btn-group" role="group">

                            <button id="btnGroupDrop1" type="button" class="btn

                        btn-secondary btn-sm font-weight-bold dropdown-toggle" data-toggle="dropdown"

                                aria-haspopup="true" aria-expanded="false">

                                <i class="fa fa-print"></i> Export PDF

                            </button>



                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                                <!-- <a class="dropdown-item" href="{{base_url() . 'rekap-hasil-keseluruhan/cetak-alasan'}}"

                                    target="_blank">Alasan Jawaban</a> -->



                                <a class="dropdown-item" href="{{base_url() . 'rekap-hasil-keseluruhan/cetak-saran'}}"

                                    target="_blank">Saran</a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="table-responsive">

<table id="table-saran" class="table table-bordered table-hover" cellspacing="0"

    width="100%">

    <thead class="bg-secondary">

        <tr>

            <th width="5%">No.</th>

            <th>Saran</th>

        </tr>

    </thead>

    <tbody>

    </tbody>

</table>

</div>





        </div>

    </div>

</div>



@endsection



@section('javascript')

<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>

<script>

$(document).ready(function() {

    table = $('#table').DataTable({



        "processing": true,

        "serverSide": true,

        "order": [],

        "language": {

            "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',

        },

        "lengthMenu": [

            [5, 10, 25, 50, 100],

            [5, 10, 25, 50, 100]

        ],

        "pageLength": 5,

        "ajax": {

            "url": "<?php echo base_url() . 'rekap-hasil-keseluruhan/ajax-list/' . $slug ?>",

            "type": "POST",

            "data": function(data) {}

        },



        "columnDefs": [{

            "targets": [-1],

            "orderable": false,

        }, ],



    });

});



$('#btn-filter').click(function() {

    table.ajax.reload();

});

$('#btn-reset').click(function() {

    $('#form-filter')[0].reset();

    table.ajax.reload();

});

</script>





<script>

$(document).ready(function() {

    table = $('#table-saran').DataTable({



        "processing": true,

        "serverSide": true,

        "order": [],

        "language": {

            "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',

        },

        "ajax": {

            "url": "<?php echo base_url() . 'rekap-hasil-keseluruhan/ajax-list-rekap-saran' ?>",

            "type": "POST",

            "data": function(data) {}

        },



        "columnDefs": [{

            "targets": [-1],

            "orderable": false,

        }, ],



    });

});



$('#btn-filter').click(function() {

    table.ajax.reload();

});

$('#btn-reset').click(function() {

    $('#form-filter')[0].reset();

    table.ajax.reload();

});

</script>

@endsection