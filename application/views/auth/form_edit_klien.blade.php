@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container">
    <div class="row justify-content-md-center">
        <div class="col col-lg-8 mt-5">

            {!! form_open(uri_string()); !!}


            <div class="card">
                <div class="card-header font-weight-bold">
                    Data PIC Klien Anak
                </div>
                <div class="card-body">

                    <div id="infoMessage">{!! $message; !!}</div>


                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Akun Induk <span
                                class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_dropdown($id_parent_induk); !!}
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama Depan <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_input($first_name); !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama Belakang <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_input($last_name); !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Organisasi <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_input($company); !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">HP <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_input($phone); !!}
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Password <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_input($password); !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Ulangi Password <span
                                class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_input($password_confirm); !!}
                        </div>
                    </div>

                </div>
            </div>


            <div class="text-right mt-5 mb-5">
                {!! anchor(base_url().'pengguna-klien', 'Cancel', ['class' => 'btn btn-light-primary font-weight-bold
                shadow-lg']) !!}
                {!! form_submit('submit', lang('edit_user_submit_btn'), ['class' => 'btn btn-primary font-weight-bold
                shadow-lg']); !!}
            </div>


            <div class="mt-5">
                <a class="btn btn-danger font-weight-bold" href="javascript:void(0)" title="Hapus"
                    onclick="delete_data(<?php echo $ci->uri->segment(3) ?>)">Hapus Pengguna Ini</a>
            </div>
            {!! form_close(); !!}

        </div>
    </div>


</div>

@endsection

@section('javascript')
<script>
function delete_data(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "Apakah anda akan menghapus pengguna ? Data tidak dapat dikembalikan",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oke, Saya mengerti!'
    }).then((result) => {

        if (result.value) {

            $.ajax({
                url: "{{ base_url() }}auth/delete-user/" + id,
                type: "POST",
                dataType: "JSON",
                beforeSend: function() {

                    Swal.fire({
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        title: "Mohon tunggu",
                        text: "Sistem sedang melakukan request anda.",
                        onOpen: function() {
                            Swal.showLoading()
                        }
                    })

                },

                success: function(data) {
                    if (data.status) {

                        Swal.fire(
                            'Deleted!',
                            'Data anda berhasil dihapus.',
                            'success'
                        );

                        setTimeout(function() {
                            window.location.href = ('{{ base_url() }}pengguna-klien');
                        }, 2000);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }

            });

        }
    });

}
</script>
@endsection