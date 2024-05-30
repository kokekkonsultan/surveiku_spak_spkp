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
    @include("include_backend/partials_no_aside/_inc_menu_repository")

    <div class="row mt-5">
        <div class="col-md-3">
            @include('manage_survey/menu_data_survey')
        </div>
        <div class="col-md-9">

            <div class="card card-custom bgi-no-repeat gutter-b"
                style="height: 150px; background-color: #1c2840; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(/assets/img/banner/taieri.svg)"
                data-aos="fade-down">
                <div class="card-body d-flex align-items-center">
                    <div>
                        <h3 class="text-white font-weight-bolder line-height-lg mb-5">
                            TABULASI DAN {{strtoupper($title)}}
                        </h3>

                        <span class="btn btn-light btn-sm font-weight-bold">
                            <i class="fa fa-bookmark"></i> <strong><?php echo $jumlah_kuisioner; ?></strong> Kuesioner
                            Lengkap
                        </span>
                    </div>
                </div>
            </div>

            <div class="card card-custom card-sticky" data-aos="fade-down">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%"
                            style="font-size: 12px;">
                            <thead class="bg-light">
                                <tr>
                                    <th rowspan="2" class="text-center">No.</th>
                                    <th rowspan="2" class="text-center">Nama Lengkap</th>

                                    @php
                                    $produk = $ci->db->query("SELECT *, (SELECT COUNT(id_unsur_pelayanan) FROM
                                    unsur_pelayanan_$profiles->table_identity JOIN
                                    pertanyaan_unsur_pelayanan_$profiles->table_identity ON
                                    unsur_pelayanan_$profiles->table_identity.id =
                                    pertanyaan_unsur_pelayanan_$profiles->table_identity.id_unsur_pelayanan WHERE
                                    is_produk = produk.id) AS jumlah_unsur
                                    FROM produk");
                                    @endphp

                                    @foreach($produk->result() as $row)
                                    <th colspan="{{$row->jumlah_unsur}}" class="text-center bg-white text-primary">
                                        {{$row->nama . ' (' . $row->nama_alias . ')'}}</th>
                                    @endforeach
                                </tr>
                                <tr>

                                    @foreach ($unsur->result() as $row)
                                    <th>{{$row->nomor_unsur}}</th>
                                    @endforeach

                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <div class="card card-body mt-5">
                <h6 class="text-primary">Indeks Persepsi Kualitas Pelayanan</h6>
                <hr>
                <br>
                <div class="table-responsive">
                    <table width="100%" class="table table-bordered" style="font-size: 12px;">
                        <tr align="center">
                            <th></th>
                            @foreach ($unsur->result() as $val)
                            @if($val->is_produk == 1)
                            <th class="bg-primary text-white">{{ $val->nomor_unsur }}</th>
                            @endif
                            @endforeach
                        </tr>

                        <tr>
                            <th class="bg-light">TOTAL</th>
                            @foreach ($total->result() as $val)
                            @if($val->is_produk == 1)
                            <th class="text-center">{{ ROUND($val->sum_skor_jawaban, 3) }}</th>
                            @endif
                            @endforeach
                        </tr>

                        <tr>
                            <th class="bg-light">Rata-Rata</th>
                            @foreach ($total->result() as $val)
                            @if($val->is_produk == 1)
                            <td class="text-center">{{ ROUND($val->rata_rata, 3) }}</td>
                            @endif
                            @endforeach
                        </tr>

                        <tr>
                            <th class="bg-light">Nilai per Unsur</th>
                            @foreach ($nilai_spkp->result() as $val)
                            <th colspan="{{ $val->colspan }}" class="text-center">
                                {{ ROUND($val->nilai_per_unsur, 3) }}
                            </th>
                            @endforeach
                        </tr>


                        <tr>
                            <th class="bg-light">Rata-Rata * Bobot</th>
                            @foreach ($nilai_spkp->result() as $val)
                            @php
                            $nilai_bobot_spkp[] = $val->rata_rata_bobot;
                            $nilai_tertimbang_spkp = array_sum($nilai_bobot_spkp);
                            $index_spkp = ROUND($nilai_tertimbang_spkp * $skala_likert, 10);

                            $colspan_spkp[] = $val->colspan;
                            $jumlah_spkp = array_sum($colspan_spkp);
                            @endphp
                            <th colspan="{{ $val->colspan }}" class="text-center">
                                {{ ROUND($val->rata_rata_bobot, 3) }}
                            </th>
                            @endforeach
                        </tr>

                        <tr>
                            <th class="bg-light">Indeks</th>
                            <th colspan="{{ $jumlah_spkp }}">{{ROUND($nilai_tertimbang_spkp, 3)}}</th>
                        </tr>


                        <tr>
                            <th class="bg-light">Nilai Konversi
                                <!--Rata2 Tertimbang-->
                            </th>
                            <th colspan="{{ $jumlah_spkp }}">{{ ROUND($index_spkp, 2) }}</th>
                        </tr>


                        @php
                        foreach ($definisi_skala->result() as $val) {
                            if ($index_spkp <= $val->range_bawah && $index_spkp >= $val->range_atas) {
                                $kategori_spkp = $val->kategori;
                                $mutu_spkp = $val->mutu;
                            }
                        }
                        if ($index_spkp <= 0) {
                            $kategori_spkp = 'NULL';
                            $mutu_spkp = 'NULL' ;
                        }
                        @endphp
                        <tr>
                            <th class="bg-light">PREDIKAT</th>
                            <th colspan="{{ $jumlah_spkp }}">{{$mutu_spkp}}</th>
                        </tr>

                        <tr>
                            <th class="bg-light">KATEGORI</th>
                            <th colspan="{{ $jumlah_spkp }}">{{$kategori_spkp}}</th>
                        </tr>
                    </table>
                </div>
            </div>



            <div class="card card-body mt-5">
                <h6 class="text-primary">Indeks Persepsi Anti Korupsi</h6>
                <hr>
                <br>
                <div class="table-responsive">
                    <table width="100%" class="table table-bordered" style="font-size: 12px;">
                        <tr align="center">
                            <th></th>
                            @foreach ($unsur->result() as $row)
                            @if($row->is_produk == 2)
                            <th class="bg-primary text-white">{{ $row->nomor_unsur }}</th>
                            @endif
                            @endforeach
                        </tr>

                        <tr>
                            <th class="bg-light">TOTAL</th>
                            @foreach ($total->result() as $row)
                            @if($row->is_produk == 2)
                            <th class="text-center">{{ ROUND($row->sum_skor_jawaban, 3) }}</th>
                            @endif
                            @endforeach
                        </tr>

                        <tr>
                            <th class="bg-light">Rata-Rata</th>
                            @foreach ($total->result() as $row)
                            @if($row->is_produk == 2)
                            <td class="text-center">{{ ROUND($row->rata_rata, 3) }}</td>
                            @endif
                            @endforeach
                        </tr>

                        <tr>
                            <th class="bg-light">Nilai per Unsur</th>
                            @foreach ($nilai_spak->result() as $row)
                            <th colspan="{{ $row->colspan }}" class="text-center">
                                {{ ROUND($row->nilai_per_unsur, 3) }}
                            </th>
                            @endforeach
                        </tr>


                        <tr>
                            <th class="bg-light">Rata-Rata * Bobot</th>
                            @foreach ($nilai_spak->result() as $row)
                            @php
                            $nilai_bobot_spak[] = $row->rata_rata_bobot;
                            $nilai_tertimbang_spak = array_sum($nilai_bobot_spak);
                            $index_spak = ROUND($nilai_tertimbang_spak * $skala_likert, 10);

                            $colspan_spak[] = $row->colspan;
                            $jumlah_spak = array_sum($colspan_spak);
                            @endphp
                            <th colspan="{{ $row->colspan }}" class="text-center">
                                {{ ROUND($row->rata_rata_bobot, 3) }}
                            </th>
                            @endforeach
                        </tr>

                        <tr>
                            <th class="bg-light">Indeks</th>
                            <th colspan="{{ $jumlah_spak }}">{{ROUND($nilai_tertimbang_spak, 3)}}</th>
                        </tr>


                        <tr>
                            <th class="bg-light">Nilai Konversi
                                <!--Rata2 Tertimbang-->
                            </th>
                            <th colspan="{{ $jumlah_spak }}">{{ ROUND($index_spak, 2) }}</th>
                        </tr>


                        @php
                        foreach ($definisi_skala->result() as $row) {
                            if ($index_spak <= $row->range_bawah && $index_spak >= $row->range_atas) {
                                $kategori_spak = $row->kategori;
                                $mutu_spak = $row->mutu;
                            }
                        }
                        if ($index_spak <= 0) {
                            $kategori_spak = 'NULL';
                            $mutu_spak = 'NULL';
                        }
                        @endphp
                        <tr>
                            <th class="bg-light">PREDIKAT</th>
                            <th colspan="{{ $jumlah_spak }}">{{$mutu_spak}}</th>
                        </tr>

                        <tr>
                            <th class="bg-light">KATEGORI</th>
                            <th colspan="{{ $jumlah_spak }}">{{$kategori_spak}}</th>
                        </tr>
                    </table>
                </div>
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
        // paging: true,
        //     dom: 'Blfrtip',
        //     "buttons": [
        //         {
        //             extend: 'collection',
        //             text: 'Export',
        //             buttons: [
        //                 'excel'
        //             ]
        //         }
        //     ],

        "lengthMenu": [
            [5, 10, 25, 50, 100, -1],
            [5, 10, 25, 50, 100, "Semua data"]
        ],
        "pageLength": 5,
        "order": [],
        "language": {
            "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
        },
        "ajax": {
            "url": "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/olah-data/ajax-list' ?>",
            "type": "POST",
            "data": function(data) {}
        },

        "columnDefs": [{
            "targets": [-1],
            "orderable": false,
        }, ],

    });
});
</script>
@endsection