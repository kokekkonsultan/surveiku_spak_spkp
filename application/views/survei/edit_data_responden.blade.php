@extends('include_backend/_template')

@php
$ci = get_instance();
@endphp

@section('style')
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
@endsection

@section('content')

<div class="container mt-5 mb-5" style="font-family: nunito;">
    <div class="text-center" data-aos="fade-up">
        <div id="progressbar" class="mb-5">
            <li class="active" id="account"><strong>Data Responden</strong></li>
            <li id="personal"><strong>Pertanyaan Survei</strong></li>
            @if($status_saran == 1)
            <li id="payment"><strong>Saran</strong></li>
            @endif
            <li id="completed"><strong>Completed</strong></li>
        </div>
    </div>
    <br>
    <br>

    <div class="row">
        <div class="col-md-8 offset-md-2" style="font-size: 16px; font-family:arial, helvetica, sans-serif;">
            <div class="card shadow mb-4 mt-4" id="kt_blockui_content" data-aos="fade-up"
                style="border-left: 5px solid #FFA800;">


                @if($manage_survey->img_benner == '')
                <img class="card-img-top" src="{{ base_url() }}assets/img/site/page/banner-survey.jpg"
                    alt="new image" />
                @else
                <img class="card-img-top shadow"
                    src="{{ base_url() }}assets/klien/benner_survei/{{$manage_survey->img_benner}}" alt="new image">
                @endif


                <div class="card-header text-center">
                    <h4><b>DATA RESPONDEN</b> - @include('include_backend/partials_backend/_tanggal_survei')</h4>
                </div>

                <form
                    action="<?php echo base_url() . 'survei/' . $ci->uri->segment(2) . '/data-responden/' . $ci->uri->segment(4) . '/update' ?>"
                    class="form_responden" method="POST">

                    <div class="card-body">



                        <span style="color: red; font-style: italic;">{!! validation_errors() !!}</span>

                        <div class="form-group">
                            <label class="font-weight-bold">Jenis Pelayanan yang diterima <span class="text-danger">*</span></label>
                           

                            @php
                            $id_layanan_survei = unserialize($responden->id_layanan_survei);
                            $layanan = $ci->db->get_where("layanan_survei_$manage_survey->table_identity", array('is_active' => 1));
                            @endphp
                            @if($manage_survey->is_layanan_survei == 1)

                            <select class="form-control" name="id_layanan_survei[]">
                                @foreach($layanan->result() as $row)
                                <option value="{{$row->id}}" <?php echo in_array($row->id, $id_layanan_survei) ? 'selected' : '' ?>>{{$row->nama_layanan}}</option>
                                @endforeach
                            </select>

                            @else

                            <div class="row mt-3">
                                <div class="col-md-1"></div>
                                <div class="col-md-11">
                                    <div class="checkbox-list layanan-survei">
                                        @foreach($layanan->result() as $row)
                                        <label class="checkbox">
                                            <input type="checkbox" name="id_layanan_survei[]" value="{{$row->id}}" <?php echo in_array($row->id, $id_layanan_survei) ? 'checked' : '' ?>>
                                            <span></span> {{$row->nama_layanan}}
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            @endif


                        </div>

                        </br>


                        @foreach ($profil_responden->result() as $row)
                        @php
                        $nama_alias = $row->nama_alias;
                        $nama_alias_lainnya = $row->nama_alias. '_lainnya';
                        @endphp

                        <div class="form-group">
                            <label class="font-weight-bold">{{$row->nama_profil_responden}}<span
                                    class="text-danger">*</span></label>

                            @if ($row->jenis_isian == 2)
                            <input class="form-control" type="<?php echo $row->type_data ?>"
                                name="<?php echo $row->nama_alias ?>" placeholder="Masukkan data anda ..."
                                value="<?php echo $responden->$nama_alias ?>" required>

                            @else
                            <select class="form-control" name="{{$row->nama_alias}}" id="{{$row->nama_alias}}" required>
                                <option value="">Please Select</option>

                                @foreach ($kategori_profil_responden->result() as $value)
                                @if ($value->id_profil_responden == $row->id)

                                <option value="{{$value->id}}" id="{{$value->nama_kategori_profil_responden}}"
                                    <?php echo $responden->$nama_alias == $value->id ? 'selected' : '' ?>>
                                    {{$value->nama_kategori_profil_responden}}
                                </option>

                                @endif
                                @endforeach

                            </select>

                            @if ($row->is_lainnya == 1)
                            <input class="form-control mt-5" type="text" name="{{$row->nama_alias}}_lainnya"
                                id="{{$row->nama_alias}}_lainnya" placeholder="Sebutkan Lainnya ..."
                                value="<?php echo $responden->$nama_alias_lainnya ?>"
                                <?php echo $responden->$nama_alias_lainnya == '' ? 'style="display: none;"' : ' required' ?>>
                            @endif

                            @endif
                        </div>

                        </br>
                        @endforeach


                    </div>
                    <div class="card-footer">
                        <table class="table table-borderless">
                            <tr>
                                <!-- <td class="text-left">
                                    {!! anchor(base_url().'survei/'.$ci->uri->segment(2), '<i class="fa fa-arrow-left"></i>
                                    Kembali',
                                    ['class' => 'btn btn-secondary btn-lg font-weight-bold shadow tombolCancel']) !!}
                                </td> -->
                                <td class="text-right">
                                    <button type="submit"
                                        class="btn btn-warning btn-lg font-weight-bold shadow tombolSave"
                                        onclick="preventBack()">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>

            <br><br>
        </div>
    </div>
</div>


@endsection

@section('javascript')

<script>
$('.form_responden').submit(function(e) {

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolCancel').attr('disabled', 'disabled');
            $('.tombolSave').attr('disabled', 'disabled');
            $('.tombolSave').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

            KTApp.block('#kt_blockui_content', {
                overlayColor: '#FFA800',
                state: 'primary',
                message: 'Processing...'
            });

            setTimeout(function() {
                KTApp.unblock('#kt_blockui_content');
            }, 500);

        },
        complete: function() {
            $('.tombolCancel').removeAttr('disabled');
            $('.tombolSave').removeAttr('disabled');
            $('.tombolSave').html('Selanjutnya <i class="fa fa-arrow-right"></i>');
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
                // toastr["success"]('Data berhasil disimpan');

                setTimeout(function() {
                    window.location.href =
                        "<?php echo base_url() . 'survei/' . $ci->uri->segment(2) . '/pertanyaan/' . $ci->uri->segment(4) . '/edit' ?>";
                }, 500);
            }
        }
    })
    return false;
});
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
@php
$profil_responden_js = $ci->db->query("SELECT * FROM
profil_responden_$manage_survey->table_identity WHERE jenis_isian = 1 && is_lainnya = 1");
@endphp

@foreach($profil_responden_js->result() as $pr_js)
<script type='text/javascript'>
$(window).load(function() {
    $("#{{$pr_js->nama_alias}}").change(function() {
        console.log(document.getElementById("{{$pr_js->nama_alias}}").options['Lainnya'].selected);

        if (document.getElementById("{{$pr_js->nama_alias}}").options['Lainnya'].selected == true) {
            $('#{{$pr_js->nama_alias}}_lainnya').show().prop('required', true);
        } else {
            $('#{{$pr_js->nama_alias}}_lainnya').removeAttr('required').hide();
        }
    });
});
</script>
@endforeach


<script type="text/javascript">
function preventBack() {
    window.history.forward();
}
setTimeout("preventBack()", 0);
window.onunload = function() {
    null
};
</script>

@endsection