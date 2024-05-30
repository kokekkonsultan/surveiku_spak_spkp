<br>
<hr>
<div class="table-responsive mt-10">
    <table width="100%" class="table table-bordered" id='myolahdata' style="font-size: 12px;">

    <tr>
        <th rowspan="2"></th>
            @foreach($produk->result() as $val)
            <th colspan="{{$val->jumlah_unsur}}" class="text-center text-warning bg-light">
                {{$val->nama . ' (' . $val->nama_alias . ')'}}
            </th>
            @endforeach
        </tr>

        <tr align="center">
            <?php foreach ($unsur->result() as $val) { ?>
            <th class="bg-warning text-white"><?php echo $val->nomor_unsur ?></th>
            <?php } ?>
        </tr>
        <tr>
            <th class="bg-light">TOTAL</th>
            @foreach ($koreksi_total->result() as $val)
            <th class="text-center">{{ ROUND($val->sum_skor_jawaban, 3) }}</th>
            @endforeach
        </tr>

        <tr>
            <th class="bg-light">Rata-Rata</th>
            @foreach ($koreksi_total->result() as $val)
            <td class="text-center">{{ ROUND($val->rata_rata, 3) }}</td>
            @endforeach
        </tr>

        <tr>
            <th class="bg-light">Nilai per Unsur</th>
            @foreach ($koreksi_nilai->result() as $val)
            <th colspan="{{ $val->colspan }}" class="text-center">
                {{ ROUND($val->nilai_per_unsur, 3) }}
            </th>
            @endforeach
        </tr>


        <tr>
            <th class="bg-light">Rata-Rata * Bobot</th>

            @php
            $koreksi_nilai_bobot_spak = [];
            $koreksi_colspan_spak = [];
            $koreksi_nilai_bobot_spkp = [];
            $koreksi_colspan_spkp = [];
            @endphp
            @foreach ($koreksi_nilai->result() as $val)
            @php
            
            if($val->is_produk == 1){
                $koreksi_nilai_bobot_spkp[] = $val->rata_rata_bobot;
                $koreksi_colspan_spkp[] = $val->colspan;
            }

            if($val->is_produk == 2){
                $koreksi_nilai_bobot_spak[] = $val->rata_rata_bobot;
                $koreksi_colspan_spak[] = $val->colspan;
            }

            $koreksi_nilai_tertimbang_spkp = array_sum($koreksi_nilai_bobot_spkp);
            $koreksi_jumlah_spkp = array_sum($koreksi_colspan_spkp);
            $koreksi_index_spkp = ROUND($koreksi_nilai_tertimbang_spkp * $skala_likert, 10);

            $koreksi_nilai_tertimbang_spak = array_sum($koreksi_nilai_bobot_spak);
            $koreksi_jumlah_spak = array_sum($koreksi_colspan_spak);
            $koreksi_index_spak = ROUND($koreksi_nilai_tertimbang_spak * $skala_likert, 10);
            @endphp
            <th colspan="{{ $val->colspan }}" class="text-center">
                {{ ROUND($val->rata_rata_bobot, 3) }}
            </th>
            @endforeach
        </tr>

        <tr>
            <th class="bg-light">Indeks</th>
            <th colspan="{{ $koreksi_jumlah_spkp }}">{{ROUND($koreksi_nilai_tertimbang_spkp, 3)}}</th>
            <th colspan="{{ $koreksi_jumlah_spak }}">{{ROUND($koreksi_nilai_tertimbang_spak, 3)}}</th>
        </tr>


        <tr>
            <th class="bg-light">Nilai Konversi
                <!--Rata2 Tertimbang-->
            </th>
            <th colspan="{{ $koreksi_jumlah_spkp }}">{{ ROUND($koreksi_index_spkp, 2) }}</th>
            <th colspan="{{ $koreksi_jumlah_spak }}">{{ ROUND($koreksi_index_spak, 2) }}</th>
        </tr>


        @php
        foreach ($definisi_skala->result() as $val) {
        if ($koreksi_index_spkp <= $val->range_bawah && $koreksi_index_spkp >= $val->range_atas) {
            $koreksi_kategori_spkp = $val->kategori;
            $koreksi_mutu_spkp = $val->mutu;
            }
        }
        if ($koreksi_index_spkp <= 0) {
            $koreksi_kategori_spkp='NULL';
            $koreksi_mutu_spkp='NULL';
        }

        foreach ($definisi_skala->result() as $val) {
        if ($koreksi_index_spak <= $val->range_bawah && $koreksi_index_spak >= $val->range_atas) {
            $koreksi_kategori_spak = $val->kategori;
            $koreksi_mutu_spak = $val->mutu;
            }
        }
        if ($koreksi_index_spak <= 0) {
            $koreksi_kategori_spak='NULL';
            $koreksi_mutu_spak='NULL';
        }
        @endphp
        <tr>
            <th class="bg-light">PREDIKAT</th>
            <th colspan="{{ $koreksi_jumlah_spkp }}">{{ $koreksi_mutu_spkp }}</th>
            <th colspan="{{ $koreksi_jumlah_spak }}">{{$koreksi_mutu_spak}}</th>
        </tr>
        <tr>
            <th class="bg-light">KATEGORI</th>
            <th colspan="{{ $koreksi_jumlah_spkp }}">{{ $koreksi_kategori_spkp }}</th>
            <th colspan="{{ $koreksi_jumlah_spak }}">{{$koreksi_kategori_spak}}</th>
        </tr>
    </table>
</div>