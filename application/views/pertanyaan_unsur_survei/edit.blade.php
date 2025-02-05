@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container-fluid">
    @include("include_backend/partials_no_aside/_inc_menu_repository")

    <div class="row justify-content-md-center mt-5">
        <div class="col-md-3">
            @include('manage_survey/menu_data_survey')
        </div>
        <div class="col-md-9">
            <div class="card" data-aos="fade-down">
                <div class="card-header bg-secondary">
                    <h5>{{ $title }}</h5>
                </div>
                <div class="card-body">
                    <span class="text-danger"><?php echo validation_errors(); ?></span>
                    <br>
                    <?php echo form_open(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-unsur/edit/' . $ci->uri->segment(5)); ?>
                    @php
                    echo validation_errors();
                    @endphp


                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Unsur Pelayanan <span
                                style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-prepend"><span
                                        class="input-group-text"><?php echo $nomor_unsur ?></span></div>
                                @php
                                echo form_input($nama_unsur_pelayanan);
                                @endphp
                                <small>
                                    Menurut Permenpan dan RB, unsur SPAK terbagi 5 unsur antara lain: 1) Diskriminasi pelayanan, 2) Kecurangan pelayanan, 3) Menerima imbalan dan/atau gratifikasi, 4) Pungutan liar, 5) Percaloan
                                </small>
                            </div>
                        </div>
                    </div>

                    @if($unsur_turunan == 1)
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Isi Pertanyaan <span
                                style="color: red;">*</span>
                        </label>
                        <div class="col-sm-9">
                            @php
                            echo form_textarea($isi_pertanyaan_unsur);
                            @endphp
                        </div>
                    </div>

                    <br>

                    @foreach ($nama_kategori_unsur as $row)
                    <input type="text" class="form-control" id="id_kategori" name="id_kategori[]"
                        value="<?php echo $row->id; ?>" hidden>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Pilihan Jawaban
                            <span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="nama_kategori_unsur_pelayanan"
                                name="nama_kategori_unsur_pelayanan[]"
                                value="<?php echo $row->nama_kategori_unsur_pelayanan; ?>" required>
                        </div>
                    </div>
                    @endforeach

                    <hr class="mb-5">

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label
                        font-weight-bold">Wajib Diisi <span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-control" id="is_required" name="is_required"
                                value="<?php echo set_value('is_required'); ?>">
                                <option>Please Select</option>
                                <option value='1' <?php if ($pertanyaan_unsur->is_required == "1") {
                                                        echo "selected";
                                                    } ?>>Aktif</option>
                                <option value='2' <?php if ($pertanyaan_unsur->is_required == "2") {
                                                        echo "selected";
                                                    } ?>>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label
                        font-weight-bold">Alasan <span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-control" id="is_alasan" name="is_alasan"
                                value="<?php echo set_value('is_alasan'); ?>">
                                <option>Please Select</option>
                                <option value='1' <?php if ($pertanyaan_unsur->is_alasan == "1") {
                                                        echo "selected";
                                                    } ?>>Aktif</option>
                                <option value='2' <?php if ($pertanyaan_unsur->is_alasan == "2") {
                                                        echo "selected";
                                                    } ?>>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>

                    @else
                        <input type="hidden" id="is_required" name="is_required" value="<?php echo $pertanyaan_unsur->is_required; ?>">
                        <input type="hidden" id="is_alasan" name="is_alasan" value="<?php echo $pertanyaan_unsur->is_alasan; ?>">

                    @endif

                    <div class="text-right">
                        @php
                        echo
                        anchor(base_url().$ci->session->userdata('username').'/'.$ci->uri->segment(2).'/pertanyaan-unsur/' . $ci->session->userdata('is_produk'),
                        'Cancel', ['class' => 'btn btn-light-primary font-weight-bold'])
                        @endphp
                        <?php echo form_submit('submit', 'Update', ['class' => 'btn btn-primary font-weight-bold']); ?>
                    </div>

                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section ('javascript')


<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script type="text/javascript">
$(function() {
    $(":radio.custom").click(function() {
        $("#id_parent").hide()
        if ($(this).val() == "1") {
            $("#id_parent").show();
        } else {
            $("#id_parent").hidden();
        }
    });
});
</script>

<script type="text/javascript">
$(function() {
    $(":radio.jawaban").click(function() {
        $("#4_jawaban").hide()
        if ($(this).val() == "Custom") {
            $("#4_jawaban").show();
        } else {
            $("#4_jawaban").hide();
        }

        $("#2_jawaban").hide()
        if ($(this).val() == "Default") {
            $("#2_jawaban").show();
        } else {
            $("#2_jawaban").hide();
        }
    });
});
</script>

<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
<script>
ClassicEditor
    .create(document.querySelector('#isi_pertanyaan_unsur'))
    .then(editor => {
        console.log(editor);
    })
    .catch(error => {
        console.error(error);
    });
</script>
@endsection