@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"
    type="text/css" />
@endsection

@section('content')

<div class="container-fluid mt-5">

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
                        <a href="{{ base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/analisa-survei/' . $ci->session->userdata('is_produk') }}"
                            class="btn btn-secondary font-weight-bold"><i class="fa fa-arrow-left"></i> Kembali</a>
                    </div>
                </div>
            </div>

            <div class="card card-custom card-sticky">
                <div class="card-header">
                    <div class="card-title">Nilai Indeks Dan Pertanyaan</div>
                    <div class="card-toolbar"></div>
                </div>
                <div class="card-body">
                    <div>
                        <table class="table">
                            <tr>
                                <td><span class="font-weight-bold"><b>Unsur</b></span></td>
                                <td>:</td>
                                <td>{{ $pertanyaan->nomor_unsur }} {!! $pertanyaan->nama_unsur_pelayanan !!}
                                </td>
                            </tr>

                            <tr>
                                <td><span class="font-weight-bold"><b>Indeks</b></span></td>
                                <td>:</td>
                                <td>{!! ROUND($pertanyaan->nilai_per_unsur, 2) !!}</td>
                            </tr>

                            <tr>
                                <td><span class="font-weight-bold"><b>Ketegori</b></span></td>
                                <td>:</td>
                                <td>
                                    @php
                                    $nilai_konversi = $pertanyaan->nilai_per_unsur * $skala_likert;
                                    foreach ($definisi_skala->result() as $obj) {
                                    if ($nilai_konversi <= $obj->range_bawah && $nilai_konversi >= $obj->range_atas) {
                                            echo $obj->kategori;
                                        }
                                    }
                                    if ($nilai_konversi <= 0) {
                                        echo 'NULL' ;
                                    }
                                    @endphp </td>
                            </tr>
                            @if($cek_turunan_unsur->num_rows() == 0)
                            <tr>
                                <td><span class="font-weight-bold"><b>Pertanyaan</b></span></td>
                                <td>:</td>
                                <td>{!! $pertanyaan->isi_pertanyaan_unsur !!}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>


            <div class="card card-body mt-5">
                <div class="table-responsive">
                    <table class="table table-hover table-striped example">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Jenis Pelayanan</th>
                                <th>Alasan</th>
                                <th>Persepsi Responden yang Mempengaruhi</th>
                                <th>Tindak Lanjut Hasil Survei</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $no = 1;
                            @endphp
                            @foreach($ci->db->get_where("layanan_survei_$table_identity", ['is_active' => 1])->result() as $i => $row)
                            <tr>
                                <td>{{$no++}}</td>
                                <td>{{$row->nama_layanan}}</td>
                                <td>
                                    @php
                                    $id[$i] = serialize(array($row->id));
                                    $arr[$i] = [];
                                    foreach($alasan->result() as $val) {
                                        if($val->id_layanan_survei == "$id[$i]"){
                                            $arr[$i][] = '<li>' . $val->alasan_pilih_jawaban . '</li>';
                                        }
                                    }
                                    echo implode("", $arr[$i]);
                                    @endphp
                                </td>

                                @php
                                $analisa[$i] = $ci->db->query("SELECT * FROM analisa_$table_identity WHERE id_unsur_pelayanan = $pertanyaan->id_unsur_pelayanan && id_layanan_survei = $row->id");
                                @endphp
                                @if($analisa[$i]->num_rows() > 0)
                                <td>{!! $analisa[$i]->row()->faktor_penyebab !!}</td>
                                <td>{!! $analisa[$i]->row()->rencana_perbaikan !!}</td>
                                @else
                                <td></td>
                                <td></td>
                                @endif

                                <td>
                                    <a class="btn btn-light-primary btn-sm font-weight-bold" data-toggle="modal" onclick="showeditunsur('{{$row->id}}')" href="#modal_edit"><i class="fa fa-edit"></i> Isi Analisa</a>
                                    
                                    <!-- <a class="btn btn-light-danger btn-sm btn-icon" href="javascript:void(0)" onclick="delete_data('{{$row->id}}')"><i class="fa fa-trash"></i></a> -->
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


<!-- ======================================= EDIT ========================================== -->
<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h6 class="text-primary">Isi Analisa</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" id="bodyModalEdit">
                <div align="center" id="loading_registration">
                    <img src="{{ base_url() }}assets/site/img/ajax-loader.gif" alt="">
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('javascript')
<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>
<script src="{{ base_url() }}assets/themes/metronic/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
<script>
$(document).ready(function() {
    $('.example').DataTable();
});
</script>


<script>
function showeditunsur(id) {
    $('#bodyModalEdit').html(
        "<div class='text-center'><img src='{{ base_url() }}assets/img/ajax/ajax-loader-big.gif'></div>");

    $.ajax({
        type: "post",
        url: "{{base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/analisa-survei/modal-edit/' . $pertanyaan->id_unsur_pelayanan}}/" + id,
        // data: "id=" + id,
        dataType: "text",
        success: function(response) {
            $('#bodyModalEdit').empty();
            $('#bodyModalEdit').append(response);
        }
    });
}
</script>


<script>
    function delete_data(id) {
    if (confirm('Are you sure delete this data?')) {
        $.ajax({
            url: "{{base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/analisa-survei/delete/'}}" + id,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    Swal.fire(
                        'Informasi',
                        'Berhasil menghapus data',
                        'success'
                    );
                    window.setTimeout(function() {
                        location.reload()
                    }, 2000);
                } else {
                    Swal.fire(
                        'Informasi',
                        'Hak akses terbatasi. Bukan akun administrator.',
                        'warning'
                    );
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error deleting data');
            }
        });

    }
}
</script>
@endsection