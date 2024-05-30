<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Survei_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }


    public function dropdown_layanan_survei($table_identity)
    {
        $query = $this->db->get_where("layanan_survei_$table_identity", array('is_active' => 1));

        if ($query->num_rows() > 0) {

            $dd[''] = 'Please Select';
            foreach ($query->result_array() as $row) {
                $dd[$row['id']] = $row['nama_layanan'];
            }

            return $dd;
        }
    }

    public function dropdown_umur()
    {
        $query = $this->db->get('umur');

        if ($query->num_rows() > 0) {

            $dd[''] = 'Pilih umur anda';
            foreach ($query->result_array() as $row) {
                $dd[$row['id']] = $row['umur_responden'];
            }

            return $dd;
        }
    }

    public function dropdown_jenis_kelamin()
    {
        $query = $this->db->get('jenis_kelamin');

        if ($query->num_rows() > 0) {

            $dd[''] = 'Please Select';
            foreach ($query->result_array() as $row) {
                $dd[$row['id']] = $row['jenis_kelamin_responden'];
            }

            return $dd;
        }
    }

    public function dropdown_jenis_pelayanan_bkpsdm()
    {
        $query = $this->db->get('jenis_pelayanan_bkpsdm');

        if ($query->num_rows() > 0) {

            $dd[''] = 'Please Select';
            foreach ($query->result_array() as $row) {
                $dd[$row['id']] = $row['nama_pelayanan'];
            }

            return $dd;
        }
    }

    public function dropdown_jumlah_kunjungan()
    {
        $query = $this->db->get('jumlah_kunjungan');

        if ($query->num_rows() > 0) {

            $dd[''] = 'Please Select';
            foreach ($query->result_array() as $row) {
                $dd[$row['id']] = $row['banyak_kunjungan'];
            }

            return $dd;
        }
    }

    public function dropdown_pendidikan_terakhir()
    {
        $query = $this->db->get('pendidikan_terakhir');

        if ($query->num_rows() > 0) {

            $dd[''] = 'Pilih pendidikan terakhir anda';
            foreach ($query->result_array() as $row) {
                $dd[$row['id']] = $row['nama_pendidikan_terakhir_responden'];
            }

            return $dd;
        }
    }

    public function dropdown_pekerjaan_utama()
    {
        $query = $this->db->get('pekerjaan_utama');

        if ($query->num_rows() > 0) {

            $dd[''] = 'Pilih pekerjaan utama anda';
            foreach ($query->result_array() as $row) {
                $dd[$row['id']] = $row['nama_pekerjaan_utama_responden'];
            }

            return $dd;
        }
    }

    public function dropdown_pembiayaan()
    {
        $query = $this->db->get('pembiayaan');

        if ($query->num_rows() > 0) {

            $dd[''] = 'Pilih pembiayaan';
            foreach ($query->result_array() as $row) {
                $dd[$row['id']] = $row['nama_pembiayaan_responden'];
            }

            return $dd;
        }
    }

    public function dropdown_status_responden()
    {
        $query = $this->db->get('status_responden');

        if ($query->num_rows() > 0) {

            $dd[''] = 'Pilih status responden';
            foreach ($query->result_array() as $row) {
                $dd[$row['id']] = $row['nama_status_responden'];
            }

            return $dd;
        }
    }

    public function dropdown_jenis_layanan_perbantuan()
    {
        $query = $this->db->get('jenis_layanan_perbantuan');

        if ($query->num_rows() > 0) {

            $dd[''] = 'Please Select';
            foreach ($query->result_array() as $row) {
                $dd[$row['id']] = $row['nama_jenis_perbantuan'];
            }

            return $dd;
        }
    }

    public function dropdown_kategori_institusi()
    {
        $query = $this->db->get('kategori_institusi');

        if ($query->num_rows() > 0) {

            $dd[''] = 'Please Select';
            foreach ($query->result_array() as $row) {
                $dd[$row['id']] = $row['nama_kategori_institusi'];
            }

            return $dd;
        }
    }

    public function get_all_pekerjaan_utama()
    {
        $query = $this->db->query("SELECT *
                                    FROM `pekerjaan_utama`");
        return $query->result();
    }

    public function get_manage_survei($data)
    {
        $query = $this->db->query("SELECT *
                                    FROM `manage_survey`
                                    WHERE slug = '$data'");
        return $query->result();
    }

    public function pertanyaan($id_jenis_pelayanan)
    {
        $query = $this->db->query("SELECT pertanyaan_unsur_pelayanan.id AS id_pertanyaan_unsur, unsur_pelayanan.id AS id_unsur_pelayanan,
                                    IF(unsur_pelayanan.is_sub_unsur_pelayanan = 2, SUBSTRING(nama_unsur_pelayanan, 1, 2), SUBSTRING(nama_unsur_pelayanan, 1, 4)) AS Nomor,

                                    pertanyaan_unsur_pelayanan.isi_pertanyaan_unsur, unsur_pelayanan.jumlah_pilihan_jawaban,

                                    ( SELECT kategori_unsur_pelayanan.nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan WHERE kategori_unsur_pelayanan.nomor_kategori_unsur_pelayanan = 1 AND kategori_unsur_pelayanan.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan.id ) AS pilihan_1,

                                    ( SELECT kategori_unsur_pelayanan.nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan WHERE kategori_unsur_pelayanan.nomor_kategori_unsur_pelayanan = 2 AND kategori_unsur_pelayanan.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan.id ) AS pilihan_2,

                                    ( SELECT kategori_unsur_pelayanan.nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan WHERE kategori_unsur_pelayanan.nomor_kategori_unsur_pelayanan = 3 AND kategori_unsur_pelayanan.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan.id ) AS pilihan_3,

                                    ( SELECT kategori_unsur_pelayanan.nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan WHERE kategori_unsur_pelayanan.nomor_kategori_unsur_pelayanan = 4 AND kategori_unsur_pelayanan.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan.id ) AS pilihan_4

                                FROM pertanyaan_unsur_pelayanan
                                JOIN unsur_pelayanan ON unsur_pelayanan.id = pertanyaan_unsur_pelayanan.id_unsur_pelayanan
                                JOIN jenis_pelayanan ON jenis_pelayanan.id = unsur_pelayanan.id_jenis_pelayanan
                                WHERE jenis_pelayanan.id = $id_jenis_pelayanan
                                ORDER BY pertanyaan_unsur_pelayanan.id ASC");
        return $query->result();
    }

    public function pertanyaan_terbuka($id_jenis_pelayanan)
    {
        $query = $this->db->query("SELECT IF(perincian_pertanyaan_terbuka.id_jenis_pilihan_jawaban = '2','ada',null) AS pilihan_jawaban, unsur_pelayanan.id AS id_unsur_pelayanan, pertanyaan_terbuka.id AS id_pertanyaan_terbuka, pertanyaan_terbuka.nama_pertanyaan_terbuka, perincian_pertanyaan_terbuka.isi_pertanyaan_terbuka, SUBSTRING(pertanyaan_terbuka.nama_pertanyaan_terbuka, 1, 4) AS nomor_pertanyaan_terbuka,

			( SELECT isi_pertanyaan_ganda.pertanyaan_ganda FROM isi_pertanyaan_ganda WHERE isi_pertanyaan_ganda.nilai_pertanyaan_ganda = 1 AND isi_pertanyaan_ganda.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka.id ) AS pilihan_1,
			( SELECT isi_pertanyaan_ganda.pertanyaan_ganda FROM isi_pertanyaan_ganda WHERE isi_pertanyaan_ganda.nilai_pertanyaan_ganda = 2 AND isi_pertanyaan_ganda.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka.id ) AS pilihan_2,
			( SELECT isi_pertanyaan_ganda.pertanyaan_ganda FROM isi_pertanyaan_ganda WHERE isi_pertanyaan_ganda.nilai_pertanyaan_ganda = 3 AND isi_pertanyaan_ganda.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka.id ) AS pilihan_3,
			( SELECT isi_pertanyaan_ganda.pertanyaan_ganda FROM isi_pertanyaan_ganda WHERE isi_pertanyaan_ganda.nilai_pertanyaan_ganda = 4 AND isi_pertanyaan_ganda.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka.id ) AS pilihan_4,
			( SELECT DISTINCT IF(isi_pertanyaan_ganda.dengan_isian_lainnya = 1,'Lainnya', null)
			FROM isi_pertanyaan_ganda WHERE isi_pertanyaan_ganda.dengan_isian_lainnya = 1 AND isi_pertanyaan_ganda.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka.id) AS lainnya

			FROM pertanyaan_terbuka
			JOIN unsur_pelayanan ON pertanyaan_terbuka.id_unsur_pelayanan = unsur_pelayanan.id
			JOIN jenis_pelayanan ON unsur_pelayanan.id_jenis_pelayanan = jenis_pelayanan.id
			JOIN perincian_pertanyaan_terbuka ON pertanyaan_terbuka.id = perincian_pertanyaan_terbuka.id_pertanyaan_terbuka
			WHERE jenis_pelayanan.id = $id_jenis_pelayanan
			ORDER BY pertanyaan_terbuka.id ASC");
        return $query->result();
    }
}

/* End of file JenisPelayanan_model.php */
/* Location: ./application/models/JenisPelayanan_model.php */