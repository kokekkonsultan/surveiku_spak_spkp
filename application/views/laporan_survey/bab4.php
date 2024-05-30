 <!--============================================== BAB IV =================================================== -->
 <div class="page-session">
     <table style="width: 100%;">
         <tr>
             <td style="text-align: center; font-size:16px; font-weight: bold;">
                 BAB IV
                 <br>
                 DATA SURVEI
                 <br>
                 <br>
             </td>
         </tr>
     </table>


     <table style="width: 100%;">
         <tr>
             <td width="3%"><b><?= $no_Bab4++ ?>.</b></td>
             <td><b>Data Responden</b></td>
         </tr>
     </table>

     <table style="width: 100%; margin-left: auto; margin-right: auto; padding-left:2em;" class="table-list">
         <tr style="background-color:#E4E6EF;">
             <th class="td-th-list"></th>
             <?php
                $profil = $this->db->query("SELECT * FROM profil_responden_$manage_survey->table_identity ORDER BY IF(urutan != '',urutan,id) ASC")->result();

                $data_profil = [];
                foreach ($profil as $get) {
                    if ($get->jenis_isian == 1) {

                        $data_profil[] = "(SELECT nama_kategori_profil_responden FROM kategori_profil_responden_$table_identity WHERE responden_$table_identity.$get->nama_alias = kategori_profil_responden_$table_identity.id) AS $get->nama_alias";
                    } else {
                        $data_profil[] = $get->nama_alias;
                    }
                }
                $query_profil = implode(", ", $data_profil);

                $data_responden = $this->db->query("SELECT *, responden_$table_identity.uuid AS uuid_responden, $query_profil
                    FROM responden_$table_identity
                    JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
                    WHERE is_submit = 1");

                $array_profil = array('email', 'nomor_telepon', 'no_telepon', 'telepon', 'nomor', 'handphone', 'no_hp', 'whatsapp', 'nomor_whatsapp', 'no_wa', 'nama_lengkap');

                foreach ($profil as $row) {
                    if (!in_array($row->nama_alias, $array_profil)) { ?>
                     <th class="td-th-list"><?php echo $row->nama_profil_responden ?></th>
             <?php }
                } ?>
         </tr>

         <?php
            $e = 1;
            foreach ($data_responden->result() as $value) { ?>
             <tr>
                 <td class="td-th-list" style="text-align: left;">Responden <?= $e++ ?></td>

                 <?php
                        foreach ($profil as $get) {
                            $nama_profil = $get->nama_alias;
                            if (!in_array($get->nama_alias, $array_profil)) {
                                ?>

                         <td class="td-th-list"><?php echo $value->$nama_profil ?></td>
                 <?php }
                        } ?>
             </tr>
         <?php } ?>

     </table>


     <table style="width: 100%;">
         <tr>
             <td width="3%"></td>
             <td class="content-text"><i style="font-size: 12px;"><span style="color:red;">**</span> Data <b>Nama
                         Lengkap</b>, <b>Email</b> dan <b>Nomor Telepon</b> tidak ditampilkan untuk menjaga
                     kerahasiaan data responden.</i>
                 <br />
                 <br />
             </td>
         </tr>
     </table>





     <table style="width: 100%;">
         <tr>
             <td width="3%"><b><?= $no_Bab4++ ?>.</b></td>
             <td><b>Capture Aplikasi Survei</b></td>
         </tr>
         <tr>
             <td></td>
             <td align="center">
                 <?php if ($manage_survey->img_form_opening != '') { ?>
                     <div style="outline: dashed 1px black;">
                        <img src="https://image-charts.com/chart?chs=150x150&cht=qr&chl=<?= base_url() . 'validasi-sertifikat/' . $manage_survey->uuid ?>&choe=UTF-8" alt="" width="30%">
                     </div>
                 <?php } else { ?>

                     <i>Gambar form survei belum diambil.</i>

                 <?php } ?>
                 <br />
                 <br />
             </td>
         </tr>
     </table>




     <table style="width: 100%;">
         <tr>
             <td width="3%"><b><?= $no_Bab4++ ?>.</b></td>
             <td><b>Sertifikat Survei</b></td>
         </tr>
         <tr>
             <td></td>
             <td>Link dan barcode untuk validasi hasil Survei:</td>
         </tr>
         <tr>
             <td></td>
             <td align="center">
                 <img src="https://image-charts.com/chart?chs=150x150&cht=qr&chl=https://spak.surveiku.com/validasi-sertifikat/<?= $manage_survey->uuid ?>&choe=UTF-8" alt="" width="30%">
             </td>
         </tr>
         <tr>
             <td></td>
             <td align="center">
                 <div style="outline: dashed 1px black; text-align:center; padding:1em;">
                     <span style="color: blue; "><?php echo base_url() . 'validasi-sertifikat/' . $manage_survey->uuid ?></span>
                     <br>
                 </div>
             </td>
         </tr>
     </table>
 </div>