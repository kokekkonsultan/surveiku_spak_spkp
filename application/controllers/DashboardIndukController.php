<?php

defined('BASEPATH') or exit('No direct script access allowed');



class DashboardIndukController extends CI_Controller

{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message_warning', 'You must be logged in to access this page');
            redirect('auth', 'refresh');
        }
        $this->load->model('DashboardInduk_model', 'models');
    }


    public function get_chart_survei()
    {
        $this->data = [];
        $this->data['title'] = 'Dashboard Chart';
        
        $users_parent = $this->db->get_where("users", array('id_parent_induk' => $this->session->userdata('user_id')));
        if($users_parent->num_rows() > 0){
            $parent = [];
            foreach($users_parent->result() as $get){
                $parent[] = $get->id;
            }
        } else {
            $parent = [0];
        }


        $this->db->select('*, manage_survey.slug AS slug_manage_survey');
        $this->db->from('manage_survey');
        $this->db->where_in('id_user', $parent);
        $this->db->order_by('manage_survey.id', 'DESC');
        $this->data['manage_survey'] = $this->db->get();
        $this->data['total_survey'] = $this->data['manage_survey']->num_rows();


        if ($this->data['manage_survey']->num_rows() > 0) {
            
            $pilih_survei = [];
            $no = 1;
            foreach ($this->data['manage_survey']->result() as $value) {
                if ($this->db->get_where("survey_$value->table_identity", array('is_submit' => 1))->num_rows() > 0) {


                    $nilai_spkp = $this->db->query("SELECT SUM(rata_rata_bobot) AS nilai_spkp
                    FROM (
                    SELECT nama_unsur_pelayanan, is_produk,
                    IF(id_parent = 0, unsur_pelayanan_$value->table_identity.id, unsur_pelayanan_$value->table_identity.id_parent) AS id_sub,
                    (((SUM(skor_jawaban)/COUNT(DISTINCT survey_$value->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$value->table_identity.id_responden)))/(SELECT COUNT(id) FROM unsur_pelayanan_$value->table_identity WHERE id_parent = 0 && is_produk = 1)) AS rata_rata_bobot

                    FROM jawaban_pertanyaan_unsur_$value->table_identity
                    JOIN pertanyaan_unsur_pelayanan_$value->table_identity ON jawaban_pertanyaan_unsur_$value->table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$value->table_identity.id
                    JOIN unsur_pelayanan_$value->table_identity ON pertanyaan_unsur_pelayanan_$value->table_identity.id_unsur_pelayanan = unsur_pelayanan_$value->table_identity.id
                    JOIN survey_$value->table_identity ON jawaban_pertanyaan_unsur_$value->table_identity.id_responden = survey_$value->table_identity.id_responden
                    WHERE survey_$value->table_identity.is_submit = 1 && jawaban_pertanyaan_unsur_$value->table_identity.skor_jawaban != '0.0' && is_produk = 1
                    GROUP BY id_sub
                    ) spak")->row()->nilai_spkp;


                    $nilai_spak = $this->db->query("SELECT SUM(rata_rata_bobot) AS nilai_spak
                    FROM (
                    SELECT nama_unsur_pelayanan, is_produk,
                    IF(id_parent = 0, unsur_pelayanan_$value->table_identity.id, unsur_pelayanan_$value->table_identity.id_parent) AS id_sub,
                    (((SUM(skor_jawaban)/COUNT(DISTINCT survey_$value->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$value->table_identity.id_responden)))/(SELECT COUNT(id) FROM unsur_pelayanan_$value->table_identity WHERE id_parent = 0 && is_produk = 2)) AS rata_rata_bobot
                    
                    FROM jawaban_pertanyaan_unsur_$value->table_identity
                    JOIN pertanyaan_unsur_pelayanan_$value->table_identity ON jawaban_pertanyaan_unsur_$value->table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$value->table_identity.id
                    JOIN unsur_pelayanan_$value->table_identity ON pertanyaan_unsur_pelayanan_$value->table_identity.id_unsur_pelayanan = unsur_pelayanan_$value->table_identity.id
                    JOIN survey_$value->table_identity ON jawaban_pertanyaan_unsur_$value->table_identity.id_responden = survey_$value->table_identity.id_responden
                    WHERE survey_$value->table_identity.is_submit = 1 && jawaban_pertanyaan_unsur_$value->table_identity.skor_jawaban != '0.0' && is_produk = 2
                    GROUP BY id_sub
                    ) spak")->row()->nilai_spak;

                    $chart_spkp[] = '{ label: "' . $value->survey_name . ' - ' . $value->organisasi . '", value: "' . ROUND($nilai_spkp, 3) . '" }';
                    $chart_spak[] = '{ label: "' . $value->survey_name . ' - ' . $value->organisasi . '", value: "' . ROUND($nilai_spak, 3) . '" }';

                } else {

                    $chart_spkp[] = '{ label: "' . $value->survey_name . ' - ' . $value->organisasi . '", value: "0" }';
                    $chart_spak[] = '{ label: "' . $value->survey_name . ' - ' . $value->organisasi . '", value: "0" }';
                };

                $no++;

                $pilih_survei[] = '<div class="checkbox-list mb-3"><label class="checkbox"><input type="checkbox" name="id[]" value="' . $value->id . '" class="child"><span></span>' . $value->survey_name . ' <b> (' . $value->organisasi . ')</b>' . '</label></div>';
            }

            $this->data['checkbox'] = implode("", $pilih_survei);
            $this->data['chart_spkp'] = implode(", ", $chart_spkp);
            $this->data['chart_spak'] = implode(", ", $chart_spak);
        } else {

            $this->data['checkbox'] = '<div class="text-center"><i>Belum ada survei yang di kaitkan dengan akun ini!</i></div>';
            $this->data['chart_spkp'] = '{ label: "", value: "0" }';
            $this->data['chart_spak'] = '{ label: "", value: "0" }';
        }

        return view("dashboard/chart_survei_induk", $this->data);
    }



    public function get_tabel_survei()
    {
        $this->data = [];
        $this->data['title'] = 'Dashboard Tabel';

        return view("dashboard/tabel_survei_induk", $this->data);
    }



    public function ajax_list_tabel_survei_induk()
    {
        $users_parent = $this->db->get_where("users", array('id_parent_induk' => $this->session->userdata('user_id')));
        if($users_parent->num_rows() > 0){
            $parent = [];
            foreach($users_parent->result() as $get){
                $parent[] = $get->id;
            }
        } else {
            $parent = [0];
        }

        $list = $this->models->get_datatables($parent);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $value) {

            $no++;
            $row = array();
            $skala_likert = (100 / ($value->skala_likert == 5 ? 5 : 4));

            if ($this->db->get_where("survey_$value->table_identity", array('is_submit' => 1))->num_rows() > 0) {

                $nilai_spkp[$no] = $this->db->query("SELECT SUM(rata_rata_bobot) AS nilai_spkp
                FROM (
                    SELECT nama_unsur_pelayanan, is_produk,
                    IF(id_parent = 0, unsur_pelayanan_$value->table_identity.id, unsur_pelayanan_$value->table_identity.id_parent) AS id_sub,
                    (((SUM(skor_jawaban)/COUNT(DISTINCT survey_$value->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$value->table_identity.id_responden)))/(SELECT COUNT(id) FROM unsur_pelayanan_$value->table_identity WHERE id_parent = 0 && is_produk = 1)) AS rata_rata_bobot

                    FROM jawaban_pertanyaan_unsur_$value->table_identity
                    JOIN pertanyaan_unsur_pelayanan_$value->table_identity ON jawaban_pertanyaan_unsur_$value->table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$value->table_identity.id
                    JOIN unsur_pelayanan_$value->table_identity ON pertanyaan_unsur_pelayanan_$value->table_identity.id_unsur_pelayanan = unsur_pelayanan_$value->table_identity.id
                    JOIN survey_$value->table_identity ON jawaban_pertanyaan_unsur_$value->table_identity.id_responden = survey_$value->table_identity.id_responden
                    WHERE survey_$value->table_identity.is_submit = 1 && jawaban_pertanyaan_unsur_$value->table_identity.skor_jawaban != '0.0' && is_produk = 1
                    GROUP BY id_sub
                ) spak")->row()->nilai_spkp;

                $nilai_spak[$no] = $this->db->query("SELECT SUM(rata_rata_bobot) AS nilai_spak
                FROM (
                    SELECT nama_unsur_pelayanan, is_produk,
                    IF(id_parent = 0, unsur_pelayanan_$value->table_identity.id, unsur_pelayanan_$value->table_identity.id_parent) AS id_sub,
                    (((SUM(skor_jawaban)/COUNT(DISTINCT survey_$value->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$value->table_identity.id_responden)))/(SELECT COUNT(id) FROM unsur_pelayanan_$value->table_identity WHERE id_parent = 0 && is_produk = 2)) AS rata_rata_bobot
                    
                    FROM jawaban_pertanyaan_unsur_$value->table_identity
                    JOIN pertanyaan_unsur_pelayanan_$value->table_identity ON jawaban_pertanyaan_unsur_$value->table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$value->table_identity.id
                    JOIN unsur_pelayanan_$value->table_identity ON pertanyaan_unsur_pelayanan_$value->table_identity.id_unsur_pelayanan = unsur_pelayanan_$value->table_identity.id
                    JOIN survey_$value->table_identity ON jawaban_pertanyaan_unsur_$value->table_identity.id_responden = survey_$value->table_identity.id_responden
                    WHERE survey_$value->table_identity.is_submit = 1 && jawaban_pertanyaan_unsur_$value->table_identity.skor_jawaban != '0.0' && is_produk = 2
                    GROUP BY id_sub
                ) spak")->row()->nilai_spak;


            } else {
                $nilai_spak[$no] = 0;
                $nilai_spkp[$no] = 0;
            };


            // foreach ($this->db->query("SELECT * FROM definisi_skala_$value->table_identity ORDER BY id DESC")->result() as $obj) {
            //     if ($skor_akhir[$no] <= $obj->range_bawah && $skor_akhir[$no] >= $obj->range_atas) {
            //         $kualitas_pelayanan[$no] = $obj->kategori;
            //         $mutu_pelayanan[$no] = $obj->mutu;
            //     }
            // }

            // if ($skor_akhir[$no] <= 0) {
            //     $kualitas_pelayanan[$no] = '-';//NULL
            //     $mutu_pelayanan[$no] = '-';//NULL
            // }


            $row[] = $no;
            $row[] = $value->survey_name;
            $row[] = $value->organisasi;
            $row[] = ROUND($nilai_spkp[$no], 3);
            $row[] = ROUND($nilai_spak[$no], 3);

            $data[] = $row;
        }

        $output = array(
            "draw"                 => $_POST['draw'],
            "recordsTotal"         => $this->models->count_all($parent),
            "recordsFiltered"     => $this->models->count_filtered($parent),
            "data"                 => $data,
        );

        echo json_encode($output);
    }
}



/* End of file DashboardIndukController.php */
/* Location: ./application/controllers/DashboardIndukController.php */
