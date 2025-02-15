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
                            {{strtoupper($title)}}
                        </h3>

                        @if ($is_question == 1)
                        <button type="button" class="btn btn-primary font-weight-bold shadow-lg btn-sm"
                            data-toggle="modal" data-target="#exampleModal">
                            <i class="fas fa-plus"></i> Tambah Pertanyaan
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card card-custom card-sticky" data-aos="fade-down">

                <div class="card-body">
                    <!-- <p>
                        Setelah anda menginputkan Unsur SKM, selanjutnya anda membuat pertanyaan beserta pilihan jawaban
                        dari Unsur SKM yang anda inputkan sebelumnya.
                    </p> -->

                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%"
                            style="font-size: 12px;">
                            <thead class="bg-secondary">
                                <tr>
                                    <th width="5%">No.</th>
                                    <th>Unsur Pelayanan</th>
                                    <th>Isi Pertanyaan</th>
                                    <th>Pilihan Jawaban</th>
                                    <th></th>
                                    <?php if ($is_question == 1) {
                                        echo '<th></th>';
                                    } ?>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <br>

                    <!-- <div class="mt-5">
                        <div class="row">
                            <div class="col-md-6">
                                @php
                                echo anchor(base_url() .
                                $ci->session->userdata('username').'/'.$ci->uri->segment(2).'/unsur-pelayanan-survey',
                                '<i class="fas fa-arrow-left text-dark"></i> Unsur Pelayanan', ['class' => 'btn
                                btn-light-secondary text-dark font-weight-bold shadow']);
                                @endphp
                            </div>
                            <div class="col-md-6 text-right">
                                @php
                                echo anchor(base_url() .
                                $ci->session->userdata('username').'/'.$ci->uri->segment(2).'/pertanyaan-harapan',
                                'Pertanyaan Harapan <i class="fas fa-arrow-right text-dark"></i>', ['class' => 'btn
                                btn-light-secondary text-dark font-weight-bold shadow']);
                                @endphp
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card-deck">
                    <a href="{{base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) .
                '/pertanyaan-unsur/add'}}" class="card card-body btn btn-outline-primary shadow">
                        <div class="text-center font-weight-bold">
                            <i class="fas fa-plus"></i><br>Tambah Pertanyaan Unsur
                        </div>
                    </a>

                    <a href="{{base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) .
                '/pertanyaan-unsur/add-sub'}}" class="card card-body btn btn-outline-primary shadow">
                        <div class="text-center font-weight-bold">
                            <i class="fas fa-plus"></i><br>Tambah Pertanyaan Sub Unsur
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


@php
$no = 1;
@endphp
@foreach($unsur_pelayanan->result() as $value)										  
<!-- Modal -->
<div class="modal fade" id="exampleModal{{ $no++ }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit nomor unsur dan nama unsur</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>

        <form action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-unsur/edit-unsur' ?>" method="POST" class="form_default">
        <div class="modal-body">
        
            <input name="id" value="{{$value->id_unsur}}" hidden>
            <div class="form-group">
                <label>Nomor unsur dan nama unsur saat ini</label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">{{ $value->nomor_unsur }}</span></div>
                    <input type="text" class="form-control" placeholder="" value="{{ $value->nama_unsur_pelayanan }}" disabled />
                </div>
            </div>

            <div class="">
                <label>Inputkan nomor unsur dan nama unsur yang akan anda ubah pada bidang dibawah ini</label>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <input type="text" name="nomor_unsur" class="form-control" value="{{ $value->nomor_unsur }}" required />
                </div>
                <div class="col-md-10">
                    <input type="text" name="nama_unsur_pelayanan" class="form-control" value="{{ $value->nama_unsur_pelayanan }}" required />
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary tombolSimpanDefault">Simpan</button>
        </div>
        </form>

        </div>
    </div>
</div>
@endforeach


@endsection

@section('javascript')
<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>
<script>
$('.form_default').submit(function(e) {
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolSimpanDefault').attr('disabled', 'disabled');
            $('.tombolSimpanDefault').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');
            KTApp.block('#content_1', {
                overlayColor: '#000000',
                state: 'primary',
                message: 'Processing...'
            });
            setTimeout(function() {
                KTApp.unblock('#content_1');
            }, 1000);
        },
        complete: function() {
            $('.tombolSimpanDefault').removeAttr('disabled');
            $('.tombolSimpanDefault').html('Simpan');
        },
        error: function(e) {
            Swal.fire(
                'Error !',
                e,
                'error'
            )
        },
        success: function(data) {
            if (data.validasi) {
                $('.pesan').fadeIn();
                $('.pesan').html(data.validasi);
            }
            if (data.sukses) {
                toastr["success"]('Data berhasil disimpan');
                window.setTimeout(function() {
                    location.reload()
                }, 2000);
            }
        }
    })
    return false;
});

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
            "url": "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-unsur/ajax-list' ?>",
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


function delete_data(id_pertanyaan_unsur) {
    if (confirm('Are you sure delete this data?')) {
        $.ajax({
            url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-unsur/delete/' ?>" +
                id_pertanyaan_unsur,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.status) {

                    $('#table').DataTable().ajax.reload()

                    Swal.fire(
                        'Informasi',
                        'Berhasil menghapus data',
                        'success'
                    );
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

<script>
function cek() {
    Swal.fire({
        icon: 'warning',
        title: 'Informasi',
        text: 'Unsur tidak dapat dihapus karna masih terdapat sub unsur turunan di bawahnya. Silahkan hapus sub unsur turunan terlebih dahulu!',
        allowOutsideClick: false,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Ya, Saya mengerti !',
    });
}
</script>

@endsection