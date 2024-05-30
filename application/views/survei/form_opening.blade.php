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
            <li id="account"><strong>Data Responden</strong></li>
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
        <div class="col-md-8 offset-md-2">
            <div class="card shadow" data-aos="fade-up" id="root">
                @if($judul->img_benner == '')
                <img class="card-img-top" src="{{ base_url() }}assets/img/site/page/banner-survey.jpg"
                    alt="new image" />
                @else
                <img class="card-img-top shadow"
                    src="{{ base_url() }}assets/klien/benner_survei/{{$manage_survey->img_benner}}" alt="new image">
                @endif

                <div class="card-body">
                    <div>
                        @php
                        $slug = $ci->uri->segment(2);

                        $data_user = $ci->db->query("SELECT *
                        FROM manage_survey
                        JOIN users u ON manage_survey.id_user = u.id
                        WHERE slug = '$slug'")->row();
                        @endphp

                        {!! $data_user->deskripsi_opening_survey !!}
                    </div>
                    <br><br>
                    @if ($ci->uri->segment(3) == NULL)
                    {!! anchor(base_url() . 'survei/' . $ci->uri->segment(2) . '/data-responden', 'IKUT SURVEI',
                    ['class' => 'btn btn-warning btn-block font-weight-bold shadow']) !!}
                    @else
                    {!! anchor(base_url() . 'survei/' . $ci->uri->segment(2) . '/data-responden/' .
                    $ci->uri->segment(3), 'IKUT SURVEI', ['class' => 'btn btn-warning btn-block']) !!}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('javascript')

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
</script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/react/16.8.6/umd/react.production.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/react-dom/16.8.6/umd/react-dom.production.min.js'></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>


@if($manage_survey->img_form_opening == '')
<script>
function get_canvas() {
    // $(document).ready(function() {

    // document.getElementById("btn_convert").addEventListener("click", function() {

    html2canvas(document.getElementById("root"), {
        allowTaint: true,
        useCORS: true
    }).then(function(canvas) {
        var anchorTag = document.createElement("a");
        document.body.appendChild(anchorTag);

        var dataURL = canvas.toDataURL();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . 'survei/' . $ci->uri->segment(2) . '/form-opening/convert' ?>",
            data: {
                imgBase64: dataURL
            },
            beforeSend: function() {},
            complete: function() {}
        }).done(function(o) {

        });
    });
    // });
    // });
};
setTimeout(get_canvas, 2500);
</script>
@endif

@endsection