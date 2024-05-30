@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container">

    <div class="row justify-content-md-center">
        <div class="col col-lg-9 mt-5">
            {!! form_open("pengguna-klien/create-klien"); !!}

            <div class="card">
                <div class="card-header font-weight-bold">
                    Data PIC Klien Anak
                </div>
                <div class="card-body">
                    <div id="infoMessage text-danger">{!! $message; !!}</div>

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

                    @if ($identity_column !== 'email')
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Username <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_error('identity'); !!}
                            {!! form_input($identity); !!}
                        </div>
                    </div>
                    @endif

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Organisasi <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_input($company); !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Email <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_input($email); !!}
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

                            <div class="input-group">
                                {!! form_input($password); !!}
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-eye"></i></span>
                                </div>
                            </div>

                            <a class="text-primary font-weight-bold mt-3 mb-5" data-toggle="modal"
                                title="Generate Password" onclick="showuserdetail(1)" href="#exampleModal"><i
                                    class="fas fa-key text-primary"></i> Generate Password</a>
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
                shadow-lg']); !!}
                {!! form_submit('submit', 'Create Klien', ['class' => 'btn btn-primary font-weight-bold shadow-lg']);
                !!}
            </div>

            {!! form_close(); !!}
        </div>
    </div>


</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" id="bodyModalDetail">
                <div align="center" id="loading_registration">
                    <img src="{{ base_url() }}assets/img/ajax/ajax-loader-big.gif" alt="">
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')
<script>
var btn = document.getElementById("open_modal");

btn.onclick = function() {
    $('#exampleModal').modal('show');
}

function showuserdetail(id) {
    $('#bodyModalDetail').html(
        "<div class='text-center'><img src='{{ base_url() }}assets/img/ajax/ajax-loader-big.gif'></div>");

    $.ajax({
        type: "post",
        url: "{{ base_url() }}auth/generate-password",
        data: "id=" + id,
        dataType: "text",
        success: function(response) {

            $('.modal-title').text('Generate Password');
            $('#bodyModalDetail').empty();
            $('#bodyModalDetail').append(response);
        }
    });
}
</script>
<script>
! function($) {
    //eyeOpenClass: 'fa-eye',
    //eyeCloseClass: 'fa-eye-slash',
    'use strict';

    $(function() {
        $('[data-toggle="password"]').each(function() {
            var input = $(this);
            var eye_btn = $(this).parent().find('.input-group-text');
            eye_btn.css('cursor', 'pointer').addClass('input-password-hide');
            eye_btn.on('click', function() {
                if (eye_btn.hasClass('input-password-hide')) {
                    eye_btn.removeClass('input-password-hide').addClass('input-password-show');
                    eye_btn.find('.fa').removeClass('fa-eye').addClass('fa-eye-slash')
                    input.attr('type', 'text');
                } else {
                    eye_btn.removeClass('input-password-show').addClass('input-password-hide');
                    eye_btn.find('.fa').removeClass('fa-eye-slash').addClass('fa-eye')
                    input.attr('type', 'password');
                }
            });
        });
    });

}(window.jQuery);
</script>

@endsection