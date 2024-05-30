@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
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
                            {!! strtoupper($title) !!}
                        </h3>
                    </div>
                </div>
            </div>



            <div class="card card-body" data-aos="fade-down">
                <h4 class="text-center text-primary"><b>Nilai {{$ci->session->userdata('is_produk') == 1 ? 'IPKP' : 'IPAK'}} : {{ROUND($nilai_tertimbang, 3)}}
                        
                        @php
                        foreach ($definisi_skala->result() as $obj) {
                            if ($ikm <= $obj->range_bawah && $ikm >= $obj->range_atas) {
                                echo '(' . $obj->kategori . ')';
                            }
                        }
                        if ($ikm <= 0) {
                            echo  'NULL';
                        }
                        @endphp
                    </b>
                </h4>
                <hr>
                <br>



                <h4 class="text-primary"><b>Unsur</b></h4>
                <div class="table-responsive">
                    <table class="table table-hover example" style="width:100%">
                        <thead class="">
                            <tr>
                                <th>No</th>
                                <th>Unsur</th>
                                <th>Indeks</th>
                                <th>Kategori</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                            $no = 1;
                            @endphp
                            @foreach ($nilai_per_unsur->result() as $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->nomor_unsur }}. {{ $value->nama_unsur_pelayanan }}</td>
                                    <td>{{ ROUND($value->nilai_per_unsur,2) }}</td>
                                    <td>
                                        @php
                                        $nilai_konversi = $value->nilai_per_unsur * $skala_likert;
                                        foreach ($definisi_skala->result() as $obj) {
                                            if ($nilai_konversi <= $obj->range_bawah && $nilai_konversi >= $obj->range_atas) {
                                                echo $obj->kategori;
                                            }
                                        }
                                        if ($nilai_konversi <= 0) {
                                            echo  'NULL';
                                        }
                                        @endphp
                                    </td>
                                    <td>
                                        <a href="{{ base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/analisa-survei/detail/' . $value->id_sub}}" class="btn btn-light-primary btn-sm font-weight-bold"><i class="fa fa-book"></i>
                                            Lakukan Analisa</a>
                                    </td>
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
<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>
<script src="{{ base_url() }}assets/themes/metronic/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script>
    $(document).ready(function() {
        $('.example').DataTable();
    });
</script>
@endsection