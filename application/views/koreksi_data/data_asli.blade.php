<br>
<hr>

@php
$produk = $ci->db->query("SELECT *, (SELECT COUNT(id_unsur_pelayanan) FROM unsur_pelayanan_$profiles->table_identity JOIN pertanyaan_unsur_pelayanan_$profiles->table_identity ON unsur_pelayanan_$profiles->table_identity.id = pertanyaan_unsur_pelayanan_$profiles->table_identity.id_unsur_pelayanan WHERE is_produk = produk.id) AS jumlah_unsur FROM produk");
@endphp

<div class="table-responsive mt-10">
    <table width="100%" class="table table-bordered" id='myolahdata' style="font-size: 12px;">

    <tr>
        <th rowspan="2"></th>
            @foreach($produk->result() as $row)
            <th colspan="{{$row->jumlah_unsur}}" class="text-center text-primary bg-light">
                {{$row->nama . ' (' . $row->nama_alias . ')'}}
            </th>
            @endforeach
        </tr>

        <tr align="center">
            <?php foreach ($unsur->result() as $row) { ?>
            <th class="bg-primary text-white"><?php echo $row->nomor_unsur ?></th>
            <?php } ?>
        </tr>
        <tr>
            <th class="bg-light">TOTAL</th>
            @foreach ($total->result() as $row)
            <th class="text-center">{{ ROUND($row->sum_skor_jawaban, 3) }}</th>
            @endforeach
        </tr>

        <tr>
            <th class="bg-light">Rata-Rata</th>
            @foreach ($total->result() as $row)
            <td class="text-center">{{ ROUND($row->rata_rata, 3) }}</td>
            @endforeach
        </tr>

        <tr>
            <th class="bg-light">Nilai per Unsur</th>
            @foreach ($nilai->result() as $row)
            <th colspan="{{ $row->colspan }}" class="text-center">
                {{ ROUND($row->nilai_per_unsur, 3) }}
            </th>
            @endforeach
        </tr>


        <tr>
            <th class="bg-light">Rata-Rata * Bobot</th>

            @php
            $nilai_bobot_spak = [];
            $colspan_spak = [];
            $nilai_bobot_spkp = [];
            $colspan_spkp = [];
            @endphp
            @foreach ($nilai->result() as $row)
            @php
            if($row->is_produk == 1){
                $nilai_bobot_spkp[] = $row->rata_rata_bobot;
                $colspan_spkp[] = $row->colspan;
            }

            if($row->is_produk == 2){
                $nilai_bobot_spak[] = $row->rata_rata_bobot;
                $colspan_spak[] = $row->colspan;
            }

            $nilai_tertimbang_spkp = array_sum($nilai_bobot_spkp);
            $jumlah_spkp = array_sum($colspan_spkp);
            $index_spkp = ROUND($nilai_tertimbang_spkp * $skala_likert, 10);

            $nilai_tertimbang_spak = array_sum($nilai_bobot_spak);
            $jumlah_spak = array_sum($colspan_spak);
            $index_spak = ROUND($nilai_tertimbang_spak * $skala_likert, 10);
            @endphp
            <th colspan="{{ $row->colspan }}" class="text-center">
                {{ ROUND($row->rata_rata_bobot, 3) }}
            </th>
            @endforeach
        </tr>

        <tr>
            <th class="bg-light">Indeks</th>
            <th colspan="{{ $jumlah_spkp }}">{{ROUND($nilai_tertimbang_spkp, 3)}}</th>
            <th colspan="{{ $jumlah_spak }}">{{ROUND($nilai_tertimbang_spak, 3)}}</th>
        </tr>


        <tr>
            <th class="bg-light">Nilai Konversi
                <!--Rata2 Tertimbang-->
            </th>
            <th colspan="{{ $jumlah_spkp }}">{{ ROUND($index_spkp, 2) }}</th>
            <th colspan="{{ $jumlah_spak }}">{{ ROUND($index_spak, 2) }}</th>
        </tr>


        @php
        foreach ($definisi_skala->result() as $row) {
        if ($index_spkp <= $row->range_bawah && $index_spkp >= $row->range_atas) {
            $kategori_spkp = $row->kategori;
            $mutu_spkp = $row->mutu;
            }
        }
        if ($index_spkp <= 0) {
            $kategori_spkp='NULL';
            $mutu_spkp='NULL';
        }

        foreach ($definisi_skala->result() as $row) {
        if ($index_spak <= $row->range_bawah && $index_spak >= $row->range_atas) {
            $kategori_spak = $row->kategori;
            $mutu_spak = $row->mutu;
            }
        }
        if ($index_spak <= 0) {
            $kategori_spak='NULL';
            $mutu_spak='NULL';
        }
        @endphp
        <tr>
            <th class="bg-light">PREDIKAT</th>
            <th colspan="{{ $jumlah_spkp }}">{{ $mutu_spkp }}</th>
            <th colspan="{{ $jumlah_spak }}">{{$mutu_spak}}</th>
        </tr>
        <tr>
            <th class="bg-light">KATEGORI</th>
            <th colspan="{{ $jumlah_spkp }}">{{ $kategori_spkp }}</th>
            <th colspan="{{ $jumlah_spak }}">{{$kategori_spak}}</th>
        </tr>
    </table>
</div>