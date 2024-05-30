@extends('include_frontend/template_frontend')

@php
$ci = get_instance();
@endphp

@section('style')

<style>
.header_card {
    color: #ffffff;
    background: linear-gradient(105deg, rgba(0 158 247) 0%, rgba(0, 247, 218) 100%);
    font-family: montserrat;
    font-size: 20px;
}
</style>

@endsection

@section('content')
<div class="main-content wow fadeIn" id="top" data-wow-duration="1s" data-wow-delay="0.5s">

    <div class="card shadow aos-init aos-animate" style="border-radius: 25px;">
        <div class="card-header fw-bold shadow text-center header_card" style="border-radius: 25px;">
            DETAIL SERTIFIKAT

        </div>

        <div class="card-body mt-3 mb-3" style="padding-left: 50px;">

            <div class="text-left mb-3" style="width: 175px;">
                <?php if ($user->foto_profile == NULL) : ?>
                <img class="card-img-top" src="{{ base_url() }}assets/klien/foto_profile/200px.jpg" alt="Card image">
                <?php else : ?>
                <img class="card-img-top"
                    src="<?php echo base_url(); ?>assets/klien/foto_profile/<?php echo $user->foto_profile ?>"
                    alt="Card image">
                <?php endif; ?>
            </div>
            <br>

            <div class="form-group row mb-3">
                <div class="col-sm-3" style="font-weight:bold;">Organisasi</div>
                <div class="col-sm-1">:</div>
                <div class="col-sm-8">{{strtoupper($user->company . ' (' . $manage_survey->organisasi . ')')}}</div>
            </div>
            <div class="form-group row mb-3">
                <div class="col-sm-3" style="font-weight:bold;">Nama Survei</div>
                <div class="col-sm-1">:</div>
                <div class="col-sm-8">{{$manage_survey->survey_name}}</div>
            </div>
            <div class="form-group row mb-3">
                <div class="col-sm-3" style="font-weight:bold;">Tanggal Survei</div>
                <div class="col-sm-1">:</div>
                <div class="col-sm-8">{{$manage_survey->survey_mulai . ' s/d ' . $manage_survey->survey_selesai}}</div>
            </div>


            @if($manage_survey->id_sampling == 1)
            <div class="form-group row mb-3">
                <div class="col-sm-3" style="font-weight:bold;">Metode Sampling</div>
                <div class="col-sm-1">:</div>
                <div class=" col-sm-8">{{$manage_survey->nama_sampling}}</div>
            </div>
            <div class="form-group row mb-3">
                <div class="col-sm-3" style="font-weight:bold;">Sample Minimal</div>
                <div class="col-sm-1">:</div>
                <div class=" col-sm-8">{{$manage_survey->jumlah_sampling}}</div>
            </div>
            @endif

        </div>
    </div>


    <div class="row mt-5 mb-5">
        <div class="col-md-5">

            <div class="card shadow aos-init aos-animate mb-4" style="border-radius: 25px;">
                <div class="card-header fw-bold shadow text-center header_card" style="border-radius: 25px;">
                    JENIS PELAYANAN
                </div>
                <div class="card-body mt-3 mb-3" style="padding-left: 50px;">

                    @foreach ($layanan->result() as $row)
                    <div class="mb-3">{{strtoupper($row->nama_layanan)}} :</b> {{$row->perolehan}} Orang</div>
                    @endforeach

                </div>
            </div>


            <div class="row">
                <div class="col-6">
                    <div class="card shadow aos-init aos-animate mb-4" style="border-radius: 25px;">
                        <div class="card-header fw-bold shadow text-center header_card" style="border-radius: 25px;">IPKP</div>
                        <div class="card-body mt-3 mb-3">
                            <div class="text-center" style="font-weight: bold; font-size:40px;">{{ROUND($nilai_spkp, 3)}}</div>
                            <div class="text-center" style="font-size:16px;">Predikat : <br>
                                <b>@php
                                    foreach ($definisi_skala->result() as $obj) {
                                        if (($nilai_spkp * 25) <= $obj->range_bawah && ($nilai_spkp * 25) >= $obj->range_atas) {
                                            echo $obj->kategori;
                                        }
                                    }
                                    if (($nilai_spkp * 25) <= 0 || ($nilai_spkp * 25)==NULL) {
                                        echo 'NULL' ;
                                    }
                                    @endphp
                                </b>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card shadow aos-init aos-animate mb-4" style="border-radius: 25px;">
                        <div class="card-header fw-bold shadow text-center header_card" style="border-radius: 25px;">IPAK</div>
                        <div class="card-body mt-3 mb-3">
                            <div class="text-center" style="font-weight: bold; font-size:40px;">{{ROUND($nilai_spak, 3)}}</div>
                            <div class="text-center" style="font-size:16px;">Predikat : <br>
                                <b>
                                    @php
                                    foreach ($definisi_skala->result() as $obj) {
                                        if (($nilai_spak * 25) <= $obj->range_bawah && ($nilai_spak * 25) >= $obj->range_atas) {
                                            echo $obj->kategori;
                                        }
                                    }
                                    if (($nilai_spak * 25) <= 0 || ($nilai_spak * 25)==NULL) {
                                        echo 'NULL' ;
                                    }
                                    @endphp
                                </b>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-7">
            <div class="card shadow aos-init aos-animate" style="border-radius: 25px;">
                <div class="card-header fw-bold shadow text-center header_card" style="border-radius: 25px;">
                    RESPONDEN
                </div>
                <div class="card-body mt-3 mb-3" style="padding-left: 50px;">

                    <div class="mb-3"><b>JUMLAH RESPONDEN :</b> <?php echo $jumlah_kuisioner ?> Orang</div>

                    @foreach ($profil->result() as $row)
                    <div><b>{{$row->nama_profil}}</b>

                        <ul style="padding-left: 30px;">
                            @php
                            $kategori_profil_responden = $ci->db->query("SELECT *, (SELECT COUNT(*) FROM
                            responden_$manage_survey->table_identity JOIN survey_$manage_survey->table_identity ON
                            responden_$manage_survey->table_identity.id =
                            survey_$manage_survey->table_identity.id_responden WHERE
                            kategori_profil_responden_$manage_survey->table_identity.id =
                            responden_$manage_survey->table_identity.$row->nama_alias && is_submit = 1) AS perolehan
                            FROM kategori_profil_responden_$manage_survey->table_identity");
                            @endphp

                            @foreach ($kategori_profil_responden->result() as $value)
                            @if ($value->id_profil_responden == $row->id)

                            <li>{{$value->nama_kategori_profil_responden}} : {{$value->perolehan}} Orang</li>

                            @endif
                            @endforeach

                        </ul>
                    </div>

                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('javascript')

@endsection