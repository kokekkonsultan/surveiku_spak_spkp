@extends('include_backend/template_backend')



@php

$ci = get_instance();

@endphp



@section('style')

<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />

@endsection



@section('content')



<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-custom bgi-no-repeat gutter-b" style="height: 150px; background-color: #1c2840; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(/assets/img/banner/rhone-2.svg)" data-aos="fade-down">
                <div class="card-body d-flex align-items-center">
                    <div>
                        <h3 class="text-white font-weight-bolder line-height-lg mb-5">
                            TABULASI & {{strtoupper($title)}} <?php echo strtoupper($nama_survey); ?>
                        </h3>
                        <a class="btn btn-light-primary btn-sm" href="{{base_url() . 'olah-data-per-bagian'}}"><i class="fa fa-arrow-left"></i> Kembali</a>
                        <span class="btn btn-secondary btn-sm disable">
                            <i class="fa fa-bookmark"></i> <b><?php echo $jumlah_kuisioner ?> Kuesioner Terisi</b></span>
                    </div>
                </div>
            </div>





            @if($jumlah_kuisioner > 0)
            <div class="card card-custom card-sticky" data-aos="fade-down">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%" style="font-size: 12px;">
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
                            @foreach ($unsur->result() as $row)
                            @if($row->is_produk == 1)
                            <th class="bg-primary text-white">{{ $row->nomor_unsur }}</th>
                            @endif
                            @endforeach
                        </tr>

                        <tr>
                            <th class="bg-light">TOTAL</th>
                            @foreach ($total->result() as $row)
                            @if($row->is_produk == 1)
                            <th class="text-center">{{ ROUND($row->sum_skor_jawaban, 3) }}</th>
                            @endif
                            @endforeach
                        </tr>

                        <tr>
                            <th class="bg-light">Rata-Rata</th>
                            @foreach ($total->result() as $row)
                            @if($row->is_produk == 1)
                            <td class="text-center">{{ ROUND($row->rata_rata, 3) }}</td>
                            @endif
                            @endforeach
                        </tr>

                        <tr>
                            <th class="bg-light">Nilai per Unsur</th>
                            @foreach ($nilai_spkp->result() as $row)
                            <th colspan="{{ $row->colspan }}" class="text-center">
                                {{ ROUND($row->nilai_per_unsur, 3) }}
                            </th>
                            @endforeach
                        </tr>


                        <tr>
                            <th class="bg-light">Rata-Rata * Bobot</th>
                            @foreach ($nilai_spkp->result() as $row)
                            @php
                            $nilai_bobot_spkp[] = $row->rata_rata_bobot;
                            $nilai_tertimbang_spkp = array_sum($nilai_bobot_spkp);
                            $index_spkp = ROUND($nilai_tertimbang_spkp * $skala_likert, 10);

                            $colspan_spkp[] = $row->colspan;
                            $jumlah_spkp = array_sum($colspan_spkp);
                            @endphp
                            <th colspan="{{ $row->colspan }}" class="text-center">
                                {{ ROUND($row->rata_rata_bobot, 3) }}
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
                        foreach ($definisi_skala->result() as $row) {
                            if ($index_spkp <= $row->range_bawah && $index_spkp >= $row->range_atas) {
                                $kategori_spkp = $row->kategori;
                                $mutu_spkp = $row->mutu;
                            }
                        }
                        if ($index_spkp <= 0) {
                            $kategori_spkp = 'NULL';
                            $mutu_spkp = 'NULL';
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
                            @foreach ($unsur->result() as $val)
                            @if($val->is_produk == 2)
                            <th class="bg-primary text-white">{{ $val->nomor_unsur }}</th>
                            @endif
                            @endforeach
                        </tr>

                        <tr>
                            <th class="bg-light">TOTAL</th>
                            @foreach ($total->result() as $val)
                            @if($val->is_produk == 2)
                            <th class="text-center">{{ ROUND($val->sum_skor_jawaban, 3) }}</th>
                            @endif
                            @endforeach
                        </tr>

                        <tr>
                            <th class="bg-light">Rata-Rata</th>
                            @foreach ($total->result() as $val)
                            @if($val->is_produk == 2)
                            <td class="text-center">{{ ROUND($val->rata_rata, 3) }}</td>
                            @endif
                            @endforeach
                        </tr>

                        <tr>
                            <th class="bg-light">Nilai per Unsur</th>
                            @foreach ($nilai_spak->result() as $val)
                            <th colspan="{{ $val->colspan }}" class="text-center">
                                {{ ROUND($val->nilai_per_unsur, 3) }}
                            </th>
                            @endforeach
                        </tr>


                        <tr>
                            <th class="bg-light">Rata-Rata * Bobot</th>
                            @foreach ($nilai_spak->result() as $val)
                            @php
                            $nilai_bobot_spak[] = $val->rata_rata_bobot;
                            $nilai_tertimbang_spak = array_sum($nilai_bobot_spak);
                            $index_spak = ROUND($nilai_tertimbang_spak * $skala_likert, 10);

                            $colspan_spak[] = $val->colspan;
                            $jumlah_spak = array_sum($colspan_spak);
                            @endphp
                            <th colspan="{{ $val->colspan }}" class="text-center">
                                {{ ROUND($val->rata_rata_bobot, 3) }}
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
                        foreach ($definisi_skala->result() as $val) {
                            if ($index_spak <= $val->range_bawah && $index_spak >= $val->range_atas) {
                                $kategori_spak = $val->kategori;
                                $mutu_spak = $val->mutu;
                            }
                        }
                        if ($index_spak <= 0) {
                            $kategori_spak = 'NULL';
                            $mutu_spak = 'NULL' ;
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

            @else
            <div class="card card-body">
                <div class="text-danger text-center"><i>Belum ada data responden yang sesuai.</i></div>
            </div>
            @endif

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
                "url": "{{base_url() . $ci->uri->segment(2) . '/' . $ci->uri->segment(3) . '/olah-data/ajax-list'}}",
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