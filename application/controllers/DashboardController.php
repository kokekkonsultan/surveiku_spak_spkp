<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DashboardController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('ion_auth');

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be logged in to access this page');
			redirect('auth', 'refresh');
		}
		$this->load->model('ManageSurvey_model', 'models');
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = 'Dashboard';

		return view('dashboard/index', $this->data);
	}

	public function jumlah_survei()
	{
		$user_id = $this->session->userdata('user_id');

		$this->db->select('COUNT(id) AS jumlah_survei');
		$query = $this->db->get_where('manage_survey', ['id_user' => $user_id])->row();

		$this->data = [];
		$this->data['jumlah_survei'] = $query->jumlah_survei;


		return view('dashboard/jumlah_survei', $this->data);
		// echo json_encode($data);
	}

	
	public function prosedur_aplikasi()
	{
		$this->data = [];
		$this->data['title'] = 'Prosedur Penggunaan Aplikasi';


		return view('dashboard/prosedur_aplikasi', $this->data);
	}


	public function get_chart_survei()
	{
		$this->data = [];
		$this->data['title'] = 'Dashboard Chart';

		$manage_survey = $this->db->get_where("manage_survey", array('id_user' => $this->session->userdata('user_id')));
		$users_groups = $this->db->get_where("users_groups ug", array('user_id' => $this->session->userdata('user_id')))->row();

		if ($users_groups->group_id == 2) {
			$this->db->select('*, manage_survey.slug AS slug_manage_survey');
			$this->db->from('manage_survey');
			$this->db->where('id_user', $this->session->userdata('user_id'));
		} else {
			$data_user = $this->db->get_where("users u", array('id' => $this->session->userdata('user_id')))->row();

			$this->db->select('*, manage_survey.slug AS slug_manage_survey');
			$this->db->from('manage_survey');
			$this->db->join("supervisor_drs$data_user->is_parent", "manage_survey.id_berlangganan = supervisor_drs$data_user->is_parent.id_berlangganan");
			$this->db->where("supervisor_drs$data_user->is_parent.id_user", $this->session->userdata('user_id'));
		}
		$this->db->order_by('manage_survey.id', 'DESC');
		$this->db->limit(10);
		$manage_survey = $this->db->get();
		$this->data['total_survey'] = $manage_survey->num_rows();

		if ($manage_survey->num_rows() > 0) {

			$nama_survei = [];
			$skor_akhir = [];
			$no = 1;
			foreach ($manage_survey->result() as $value) {

				$skala_likert = (100 / ($value->skala_likert == 5 ? 5 : 4));
				$this->data['tahun_awal'] = $value->survey_year;


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


                    $chart_spkp[] = '{ label: "' . $value->survey_name . '", value: "' . ROUND($nilai_spkp, 3) . '" }';
                    $chart_spak[] = '{ label: "' . $value->survey_name . '", value: "' . ROUND($nilai_spak, 3) . '" }';

                } else {

                    $chart_spkp[] = '{ label: "' . $value->survey_name . '", value: "0" }';
                    $chart_spak[] = '{ label: "' . $value->survey_name . '", value: "0" }';
                };
				$no++;
			}
            $this->data['chart_spkp'] = implode(", ", $chart_spkp);
            $this->data['chart_spak'] = implode(", ", $chart_spak);

		} else {
			$this->data['chart_spkp'] = '{ label: "", value: "0" }';
            $this->data['chart_spak'] = '{ label: "", value: "0" }';
		}
		return view("dashboard/chart_survei", $this->data);
	}

	public function get_tabel_survei()
	{
		$this->data = [];
		$this->data['title'] = 'Dashboard Tabel';

		return view("dashboard/tabel_survei", $this->data);
	}

	public function ajax_list_tabel_survei()
	{
		$this->db->select('*');
		$this->db->from('users u');
		$this->db->join('users_groups ug', 'u.id = ug.user_id');
		$this->db->where('username', $this->session->userdata('username'));
		$data_user = $this->db->get()->row();

		$list = $this->models->get_datatables($data_user);
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
                $nilai_spkp[$no] = 0;
                $nilai_spak[$no] = 0;
            };



			$row[] = $no;
			$row[] = $value->survey_name;
			$row[] = $value->organisasi;
			$row[] = ROUND($nilai_spkp[$no], 3);
            $row[] = ROUND($nilai_spak[$no], 3);

			$data[] = $row;
		}

		$output = array(
			"draw" 				=> $_POST['draw'],
			"recordsTotal" 		=> $this->models->count_all($data_user),
			"recordsFiltered" 	=> $this->models->count_filtered($data_user),
			"data" 				=> $data,
		);

		echo json_encode($output);
	}

	public function get_detail_hasil_analisa()
	{

		$this->data = [];
		$id_manage_survey = $this->uri->segment(4);
		$this->data['id_manage_survey'] = $id_manage_survey;

		$this->data['manage_survey'] = $this->db->get_where('manage_survey', array('id' => $id_manage_survey))->row();


		return view('dashboard/detail_hasil_analisa', $this->data);
	}
}

/* End of file DashboardController.php */
/* Location: ./application/controllers/DashboardController.php */