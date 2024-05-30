@php
$ci = get_instance();


@endphp




<form action="{{base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/analisa-survei/edit'}}"
    class="form_default" method="POST">


    <input name="id_unsur_pelayanan" value="{{$id_unsur_pelayanan}}" hidden>
    <input name="id_layanan_survei" value="{{$id_layanan_survei}}" hidden>

    <div class="form-group row">
        <label class="col-sm-3 col-form-label font-weight-bold">Persepsi Responden yang Mempengaruhi <b
                class="text-danger">*</b></label>
        <div class="col-sm-9">
            <textarea class="form-control" name="faktor_penyebab" id="faktor_penyebab" required>{!! $faktor_penyebab !!}</textarea>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-3 col-form-label font-weight-bold">Tindak Lanjut <b class="text-danger">*</b></label>
        <div class="col-sm-9">
            <textarea class="form-control" name="rencana_perbaikan" id="rencana_perbaikan" required>{!! $rencana_perbaikan !!}</textarea>
        </div>
    </div>


    <div class="text-right">
        <button type="button" class="btn btn-secondary tombolCancel" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary tombolSimpan">Simpan</button>
    </div>

</form>

<!-- <script>
ClassicEditor
    .create(document.querySelector('#faktor_penyebab'))
    .then(editor => {
        console.log(editor);
    })
    .catch(error => {
        console.error(error);
    });

ClassicEditor
    .create(document.querySelector('#rencana_perbaikan'))
    .then(editor => {
        console.log(editor);
    })
    .catch(error => {
        console.error(error);
    });
</script> -->



<script>
$('.form_default').submit(function(e) {

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolSimpan').attr('disabled', 'disabled');
            $('.tombolSimpan').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');
            $('.tombolCancel').attr('disabled', 'disabled');
        },
        complete: function() {
            $('.tombolSimpan').removeAttr('disabled');
            $('.tombolSimpan').html('Simpan');
            $('.tombolCancel').removeAttr('disabled');
        },
        error: function(e) {
            Swal.fire(
                'Error !',
                e,
                'error'
            )
        },
        success: function(data) {
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
</script>