@extends('include_backend/template_auth')

@php
$ci = get_instance();
@endphp

@section('style')
<link rel="stylesheet" href="{{ VENDOR_PATH }}sweetalert2/sweetalert2.min.css">
@endsection

@section('content')

<div class="login-signin">
    <div class="mb-20">
        <h3>Log In</h3>
        <div class="text-muted font-weight-bold">Masukkan detail Login akun anda:</div>
    </div>

    @if (isset($message))
    <div class="alert alert-custom alert-light-warning fade show mb-5" role="alert">
        <div class="alert-icon"><i class="flaticon-warning"></i></div>
        <div class="alert-text"><?php echo $message; ?></div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="ki ki-close"></i></span>
            </button>
        </div>
    </div>
    @endif
    @php
    echo form_open("auth/login");
    @endphp
    <div class="form-group mb-5">
        @php
        echo form_input($identity);
        @endphp
    </div>
    <div class="form-group mb-5">
        @php
        echo form_input($password);
        @endphp
    </div>
    <div class="form-group d-flex flex-wrap justify-content-between align-items-center">
        <div class="checkbox-inline">
            <label class="checkbox m-0 text-muted">
                @php
                echo form_checkbox('remember', '1', FALSE, 'id="remember"');
                @endphp
                <span></span>Ingat saya</label>
        </div>
        @php
        echo anchor(base_url().'auth/forgot_password', 'Lupa password anda?', ['class' => 'text-muted
        text-hover-primary'])
        @endphp
    </div>
    @php
    echo form_submit('submit', lang('login_submit_btn'), ['class' => 'btn btn-primary font-weight-bold px-9 py-4 my-3
    mx-4']);
    @endphp


    @php
    echo form_close();
    @endphp
</div>

@endsection

@section('javascript')
<script src="{{ VENDOR_PATH }}sweetalert2/sweetalert2.min.js"></script>
@if (isset($message))
<script>
$(document).ready(function() {
    Swal.fire(
        'Terjadi Kesalahan',
        'Cek kembali data anda',
        'warning'
    );
});
</script>
@endif



@php
$data_message = $ci->session->flashdata('data_message');
@endphp
@if (!empty($data_message))
<script>
$(document).ready(function() {
    Swal.fire(
        'Informasi',
        'Paket anda sudah habis! Silahkan lakukan perpanjangan paket untuk Login ke menu SKM.',
        'warning'
    );
});
</script>
@endif
@endsection