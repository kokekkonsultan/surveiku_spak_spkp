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
        <h3>Forgotten Password ?</h3>
        <div class="text-muted font-weight-bold">Enter your email to reset your password</div>
    </div>
    @if (isset($message))
    <div class="alert alert-custom alert-light-warning fade show mb-5" role="alert">
      <div class="alert-icon"><i class="flaticon-warning"></i></div>
      <div class="alert-text"><?php echo $message;?></div>
      <div class="alert-close">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true"><i class="ki ki-close"></i></span>
          </button>
      </div>
    </div>
    @endif
    @php
        echo form_open("auth/forgot_password");
    @endphp
        <div class="form-group mb-10">
            @php
                echo form_input($identity);
            @endphp
        </div>
        <div class="form-group d-flex flex-wrap flex-center mt-10">
            @php
                echo form_submit('submit', 'Request', ['class' => 'btn btn-primary font-weight-bold px-9 py-4 my-3 mx-2']);
            @endphp
            @php
                echo anchor(base_url().'auth/login', 'Cancel', ['class' => 'btn btn-light-primary font-weight-bold px-9 py-4 my-3 mx-2']);
            @endphp
        </div>
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
@endsection