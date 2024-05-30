<?php

defined('BASEPATH') or exit('No direct script access allowed');



class OlahDataPerBagianController extends Client_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
            redirect('auth', 'refresh');
        }
        $this->load->library('form_validation');
        $this->load->model('DataPerolehanPerBagian_model', 'models');
        $this->load->model('OlahData_model', 'models');
        $this->load->model('OlahData_model');
    }

    public function index()
    {
        $this->data = [];
        $this->data['title'] = 'Olah Data';
        
        return view('olah_data_per_bagian/index', $this->data);

    }


    public function ajax_list()
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
            $table_identity = $value->table_identity;
            $klien_user = $this->db->get_where("users", array('id' => $value->id_user))->row();
            $skala_likert = (100 / ($value->skala_likert == 5 ? 5 : 4));

            if ($this->db->get_where("survey_$table_identity", array('is_submit' => 1))->num_rows() > 0) {

                $nilai_spkp = $this->db->query("SELECT SUM(rata_rata_bobot) AS nilai_spkp
                FROM (
                SELECT nama_unsur_pelayanan, is_produk,
                IF(id_parent = 0, unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub,

                (((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden)))/(SELECT COUNT(id) FROM unsur_pelayanan_$table_identity WHERE id_parent = 0 && is_produk = 1)) AS rata_rata_bobot

                FROM jawaban_pertanyaan_unsur_$table_identity
                JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
                JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
                JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
                WHERE survey_$table_identity.is_submit = 1 && jawaban_pertanyaan_unsur_$table_identity.skor_jawaban != '0.0' && is_produk = 1
                GROUP BY id_sub
                ) spak")->row()->nilai_spkp;

                
                $nilai_spak = $this->db->query("SELECT SUM(rata_rata_bobot) AS nilai_spak
                FROM (
                SELECT nama_unsur_pelayanan, is_produk,
                IF(id_parent = 0, unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub,
                
                (((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden)))/(SELECT COUNT(id) FROM unsur_pelayanan_$table_identity WHERE id_parent = 0 && is_produk = 2)) AS rata_rata_bobot
                
                FROM jawaban_pertanyaan_unsur_$table_identity
                JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
                JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
                JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
                WHERE survey_$table_identity.is_submit = 1 && jawaban_pertanyaan_unsur_$table_identity.skor_jawaban != '0.0' && is_produk = 2
                GROUP BY id_sub
                ) spak")->row()->nilai_spak;

            } else {
                $nilai_spkp = 0;
                $nilai_spak = 0;
            };



            $no++;
            $row = array();
            $row[] = '
			<a href="' . base_url() . 'olah-data-per-bagian/' . $klien_user->username . '/' . $value->slug . '" title="">
			<div class="card mb-5 shadow" style="background-color: SeaShell;">
				<div class="card-body">
					<div class="row">
						<div class="col sm-10">
							<strong style="font-size: 17px;">' . $value->survey_name . '</strong><br>
							<span class="text-dark">Nama Akun : <b>' . $value->first_name . ' ' . $value->last_name . '</b></span><br>
                            <span class="text-dark">IPKP : <b>' . ROUND($nilai_spkp,3) . '</b></span><br>
                            <span class="text-dark">IPAK : <b>' . ROUND($nilai_spak,3) . '</b></span><br>
						</div>
						<div class="col sm-2 text-right"><span class="badge badge-info" width="40%">Detail</span>
							<div class="mt-3 text-dark font-weight-bold" style="font-size: 11px;">
                            Periode Survei : ' . date('d-m-Y', strtotime($value->survey_start)) . ' s/d ' . date('d-m-Y', strtotime($value->survey_end)) . '
							</div>
						</div>
					</div>
				</div>
			</div>
		</a>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->models->count_all($parent),
            "recordsFiltered" => $this->models->count_filtered($parent),
            "data" => $data,
        );
        echo json_encode($output);

    }

    public function detail($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = 'Olah Data';
        $this->data['profiles'] = Client_Controller::_get_data_profile($id1, $id2);
        $slug = $this->uri->segment(3);

        $manage_survey = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();
        $table_identity = $manage_survey->table_identity;
        $this->data['nama_survey'] = $manage_survey->survey_name;

        $this->data['skala_likert'] = 100 / ($manage_survey->skala_likert == 5 ? 5 : 4);
		$this->data['definisi_skala'] = $this->db->query("SELECT * FROM definisi_skala_$table_identity ORDER BY id DESC");

        //JUMLAH KUISIONER
        $this->data['jumlah_kuisioner'] = $this->db->get_where("survey_$table_identity", ['is_submit' => 1])->num_rows();


        $this->data['unsur'] = $this->db->query("SELECT *, SUBSTR(nomor_unsur,2) AS nomor_harapan
		FROM unsur_pelayanan_$table_identity
		JOIN pertanyaan_unsur_pelayanan_$table_identity ON unsur_pelayanan_$table_identity.id = pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan
		ORDER BY is_produk ASC, SUBSTR(nomor_unsur,2) + 0 ASC");


		//TOTAL
		$this->data['total'] = $this->db->query("SELECT SUM(skor_jawaban) AS sum_skor_jawaban,
		AVG(skor_jawaban) AS rata_rata,
		(SELECT nomor_unsur FROM unsur_pelayanan_$table_identity JOIN pertanyaan_unsur_pelayanan_$table_identity ON unsur_pelayanan_$table_identity.id = pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan WHERE pertanyaan_unsur_pelayanan_$table_identity.id = jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur) AS nomor_unsur,
		(SELECT is_produk FROM unsur_pelayanan_$table_identity JOIN pertanyaan_unsur_pelayanan_$table_identity ON unsur_pelayanan_$table_identity.id = pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan WHERE pertanyaan_unsur_pelayanan_$table_identity.id = jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur) AS is_produk

		FROM jawaban_pertanyaan_unsur_$table_identity
		JOIN responden_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
		JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
		WHERE is_submit = 1 && jawaban_pertanyaan_unsur_$table_identity.skor_jawaban != '0.0'
		GROUP BY id_pertanyaan_unsur
		ORDER BY is_produk ASC, SUBSTR(nomor_unsur,2) + 0 ASC");



        $this->data['nilai_spkp'] = $this->db->query("SELECT nama_unsur_pelayanan, is_produk,
		IF(id_parent = 0, unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub,
		(SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden)) AS rata_rata,
		(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden)) AS colspan,
		((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden))) AS nilai_per_unsur,
		(((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden)))/(SELECT COUNT(id) FROM unsur_pelayanan_$table_identity WHERE id_parent = 0 && is_produk = 1)) AS rata_rata_bobot
		
		FROM jawaban_pertanyaan_unsur_$table_identity
		JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
		JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
		JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
		WHERE survey_$table_identity.is_submit = 1 && jawaban_pertanyaan_unsur_$table_identity.skor_jawaban != '0.0' && is_produk = 1
		GROUP BY id_sub
		ORDER BY is_produk ASC, SUBSTR(nomor_unsur,2) + 0 ASC");



		$this->data['nilai_spak'] = $this->db->query("SELECT nama_unsur_pelayanan, is_produk,
		IF(id_parent = 0, unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub,
		(SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden)) AS rata_rata,
		(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden)) AS colspan,
		((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden))) AS nilai_per_unsur,
		(((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden)))/(SELECT COUNT(id) FROM unsur_pelayanan_$table_identity WHERE id_parent = 0 && is_produk = 2)) AS rata_rata_bobot
		
		FROM jawaban_pertanyaan_unsur_$table_identity
		JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
		JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
		JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
		WHERE survey_$table_identity.is_submit = 1 && jawaban_pertanyaan_unsur_$table_identity.skor_jawaban != '0.0' && is_produk = 2
		GROUP BY id_sub
		ORDER BY is_produk ASC, SUBSTR(nomor_unsur,2) + 0 ASC");

        return view('olah_data_per_bagian/detail', $this->data);
    }

}



/* End of file OlahDataPerBagianController.php */

/* Location: ./application/controllers/OlahDataPerBagianController.php */

