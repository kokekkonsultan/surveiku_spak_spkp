<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SertifikatController extends Client_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}
		$this->load->library('form_validation');
	}

	public function index($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = "E-Sertifikat";

		$this->data['profiles'] = Client_Controller::_get_data_profile($id1, $id2);
		$this->data['user'] = $this->ion_auth->user()->row();

		$this->db->select("*, manage_survey.id AS id_manage_survey, manage_survey.table_identity AS table_identity, manage_survey.id_jenis_pelayanan AS id_jenis_pelayanan, DATE_FORMAT(survey_start, '%M') AS survey_mulai, DATE_FORMAT(survey_end, '%M %Y') AS survey_selesai");
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();
		$table_identity = $manage_survey->table_identity;
		$this->data['manage_survey'] = $manage_survey;

		//LOAD PROFIL RESPONDEN
		$this->data['profil_responden'] = $this->db->get_where("profil_responden_$manage_survey->table_identity", array('jenis_isian'  => 1));

		if (date("Y-m-d") < $manage_survey->survey_end) {
			$this->data['pesan'] = 'Halaman ini hanya bisa dikelola jika periode survei sudah diselesai atau survei sudah ditutup.';
			return view('not_questions/index', $this->data);
		}

		if ($this->db->get_where("survey_$manage_survey->table_identity", array('is_submit' => 1))->num_rows() == 0) {
			$this->data['pesan'] = 'survei belum dimulai atau belum ada responden !';
			return view('not_questions/index', $this->data);
		}

		//PENDEFINISIAN SKALA LIKERT
		$skala_likert = 100 / ($manage_survey->skala_likert == 5 ? 5 : 4);
		$this->data['definisi_skala'] = $this->db->query("SELECT * FROM definisi_skala_$manage_survey->table_identity ORDER BY id DESC");


		//JUMLAH KUISIONER
		$this->data['jumlah_kuisioner'] = $this->db->get_where("survey_$manage_survey->table_identity", ['is_submit' => 1])->num_rows();


		$this->form_validation->set_rules('nama', 'Nama', 'trim|required');
		$this->form_validation->set_rules('jabatan', 'Jabatan', 'trim|required');
		$this->form_validation->set_rules('model_sertifikat', 'Model sertifikat', 'trim|required');
		$this->form_validation->set_rules('periode', 'Periode Survei', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			return view('sertifikat/index', $this->data);
		} else {
			// if ($manage_survey->nomor_sertifikat == NULL) {
				
			// 	$array_bulan = array(1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");
			// 	$bulan = $array_bulan[date('n')];

			// 	$object = [
			// 		'nomor_sertifikat' 	=> '000' . $manage_survey->id .  '/SKM/' .  $manage_survey->id_user . '/' . $bulan . '/' . date('Y'),
			// 		// 'qr_code' 	=> $manage_survey->table_identity . '.png'
			// 	];
			// 	$this->db->where('slug', $this->uri->segment(2));
			// 	$this->db->update('manage_survey', $object);
			// };


			$input 	= $this->input->post(NULL, TRUE);
			if($input['is_tanda_tangan'] == 1) {
				$nama_file             			= strtolower("ttd_");
				$config['upload_path']          = 'assets/klien/ttd_sertifikat/';
				$config['allowed_types']        = 'jpg|jpeg|png';
				$config['file_name']            = $nama_file . $manage_survey->table_identity;
				$config['remove_space'] 		= TRUE;
				$config['overwrite']            = TRUE;
				$config['detect_mime']        	= TRUE;



				$this->load->library('upload', $config);
				if ($this->upload->do_upload('ttd')) {
					$uploaded_data = $this->upload->data();
					$new_data = [
						'img_ttd_sertifikat' => $uploaded_data['file_name'],
					];
					$this->db->where("slug", $this->uri->segment(2));
					$this->db->update('manage_survey', $new_data);
				}
			}


			$array_bulan = array(1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");
			$bulan = $array_bulan[date('n')];


			$this->data['produk'] = $input['produk'];
			$this->data['nama'] = $input['nama'];
			$this->data['jabatan'] = $input['jabatan'];
			$this->data['model_sertifikat'] = $input['model_sertifikat'];
			$this->data['periode'] = $input['periode'];
			$this->data['table_identity'] = $manage_survey->table_identity;
			$this->data['is_nomor_sertifikat'] = $input['is_nomor_sertifikat'];
			$this->data['nomor_sertifikat'] = '000' . $manage_survey->id .  '/SPAK/' .  $manage_survey->id_user . '/' . $bulan . '/' . date('Y');
			$this->data['is_tanda_tangan'] = $input['is_tanda_tangan'];
			$profil_responden = $input['profil_responden'];
			$data_profil = implode(",", $profil_responden);

			//TAMPILKAN PROFIL YANG DIPILIH
			$this->data['profil'] = $this->db->query("SELECT * FROM profil_responden_$manage_survey->table_identity WHERE id IN ($data_profil)");

			$this->data['qr_code'] = 'https://image-charts.com/chart?chl=' . base_url() . 'validasi-sertifikat/' . $manage_survey->uuid . '&choe=UTF-8&chs=300x300&cht=qr';

			
			$is_produk = $this->data['produk'];
			$this->data['nilai'] = $this->db->query("SELECT SUM(rata_rata_bobot) AS nilai
			FROM (
					SELECT nama_unsur_pelayanan, is_produk,
					IF(id_parent = 0, unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub,
					(((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden)))/(SELECT COUNT(id) FROM unsur_pelayanan_$table_identity WHERE id_parent = 0 && is_produk = $is_produk)) AS rata_rata_bobot
					
					FROM jawaban_pertanyaan_unsur_$table_identity
					JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
					JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
					JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
					WHERE survey_$table_identity.is_submit = 1 && jawaban_pertanyaan_unsur_$table_identity.skor_jawaban != '0.0' && is_produk = $is_produk
					GROUP BY id_sub
				) spkp")->row()->nilai;

		

			//------------------------------CETAK-------------------------//
			$this->load->library('pdfgenerator');
			$this->data['title_pdf'] = 'SERTIFIKAT';
			$file_pdf = 'SERTIFIKAT';
			$paper = 'A4';
			$orientation = "potrait";

			$html = $this->load->view('sertifikat/cetak', $this->data, true);

			$this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
		}
	}


	



	// public function proses()
	// {

	// 	$this->db->select("*, manage_survey.id AS id_manage_survey, manage_survey.table_identity AS table_identity, manage_survey.id_jenis_pelayanan AS id_jenis_pelayanan, DATE_FORMAT(survey_start, '%M') AS survey_mulai, DATE_FORMAT(survey_end, '%M %Y') AS survey_selesai");
	// 	$this->db->from('manage_survey');
	// 	$this->db->where('manage_survey.slug', $this->uri->segment(2));
	// 	$manage_survey = $this->db->get()->row();
	// 	$this->data['manage_survey'] = $manage_survey;

	// 	//PENDEFINISIAN SKALA LIKERT
	// 	$skala_likert = 100 / ($manage_survey->skala_likert == 5 ? 5 : 4);
	// 	$this->data['definisi_skala'] = $this->db->query("SELECT * FROM definisi_skala_$manage_survey->table_identity ORDER BY id DESC");


	// 	$this->data['user'] = $this->ion_auth->user()->row();

	// 	//JUMLAH KUISIONER
	// 	$this->db->select('COUNT(id) AS id');
	// 	$this->db->from('survey_' . $manage_survey->table_identity);
	// 	$this->db->where("is_submit = 1");
	// 	$this->data['jumlah_kuisioner'] = $this->db->get()->row()->id;


	// 	$this->data['nilai_per_unsur'] = $this->db->query("SELECT IF(id_parent = 0,unsur_pelayanan_$manage_survey->table_identity.id, unsur_pelayanan_$manage_survey->table_identity.id_parent) AS id_sub,
	// 	(SELECT nomor_unsur FROM unsur_pelayanan_$manage_survey->table_identity unsur_sub WHERE unsur_sub.id = id_sub) AS nomor_unsur,
	// 	(SELECT nama_unsur_pelayanan FROM unsur_pelayanan_$manage_survey->table_identity unsur_sub WHERE unsur_sub.id = id_sub) AS nama_unsur_pelayanan,
	// 	(SUM(skor_jawaban)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden)) AS rata_rata, 
	// 	(COUNT(id_parent)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden)) AS colspan,
	// 	((SUM(skor_jawaban)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden))) AS nilai_per_unsur, (((SUM(skor_jawaban)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden)))/(SELECT COUNT(id) FROM unsur_pelayanan_$manage_survey->table_identity WHERE id_parent = 0)) AS rata_rata_bobot

	// 	FROM jawaban_pertanyaan_unsur_$manage_survey->table_identity
	// 	JOIN pertanyaan_unsur_pelayanan_$manage_survey->table_identity ON jawaban_pertanyaan_unsur_$manage_survey->table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id
	// 	JOIN unsur_pelayanan_$manage_survey->table_identity ON pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id_unsur_pelayanan = unsur_pelayanan_$manage_survey->table_identity.id
	// 	JOIN survey_$manage_survey->table_identity ON jawaban_pertanyaan_unsur_$manage_survey->table_identity.id_responden = survey_$manage_survey->table_identity.id_responden
	// 	WHERE survey_$manage_survey->table_identity.is_submit = 1
	// 	GROUP BY id_sub");


	// 	foreach ($this->data['nilai_per_unsur']->result() as $value) {
	// 		$nilai_bobot[] = $value->rata_rata_bobot;
	// 		$nilai_tertimbang = array_sum($nilai_bobot);
	// 		$this->data['ikm'] = ROUND($nilai_tertimbang * $skala_likert, 10);
	// 	}


	// 	if ($manage_survey->nomor_sertifikat == NULL) {

	// 		$array_bulan = array(1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");
	// 		$bulan = $array_bulan[date('n')];

	// 		$object = [
	// 			'nomor_sertifikat' 	=> '000' . $manage_survey->id .  '/SKM/' .  $manage_survey->id_user . '/' . $bulan . '/' . date('Y'),
	// 			// 'qr_code' 	=> $manage_survey->table_identity . '.png'
	// 		];
	// 		$this->db->where('slug', $this->uri->segment(2));
	// 		$this->db->update('manage_survey', $object);
	// 	};


	// 	$input 	= $this->input->post(NULL, TRUE);
	// 	$this->data['nama'] = $input['nama'];
	// 	$this->data['jabatan'] = $input['jabatan'];
	// 	$this->data['model_sertifikat'] = $input['model_sertifikat'];
	// 	$this->data['periode'] = $input['periode'];
	// 	$this->data['table_identity'] = $manage_survey->table_identity;
	// 	$profil_responden = $input['profil_responden'];
	// 	$data_profil = implode(",", $profil_responden);

	// 	//TAMPILKAN PROFIL YANG DIPILIH
	// 	$this->data['profil'] = $this->db->query("SELECT * FROM profil_responden_$manage_survey->table_identity WHERE id IN ($data_profil)");

	// 	$this->data['qr_code'] = 'https://image-charts.com/chart?chl=' . base_url() . 'validasi-sertifikat/' . $manage_survey->uuid . '&choe=UTF-8&chs=300x300&cht=qr';


	// 	//------------------------------CETAK-------------------------//
	// 	$this->load->library('pdfgenerator');
	// 	$this->data['title_pdf'] = 'SERTIFIKAT E-SKM';
	// 	$file_pdf = 'SERTIFIKAT E-SKM';
	// 	$paper = 'A4';
	// 	$orientation = "potrait";

	// 	$html = $this->load->view('sertifikat/cetak', $this->data, true);

	// 	$this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);

	// 	// $this->load->view('sertifikat/cetak', $this->data);
	// }



	public function update_publikasi($id1, $id2)
	{
		$mode = $_POST['mode'];
		$id = $_POST['nilai_id'];

		if ($mode == 'true') {
			$object = [
				'is_publikasi' => 1
			];
			$this->db->where('slug', $id2);
			$this->db->update('manage_survey', $object);


			$message = 'Survei Berhasil dipublikasi';
			$success = 'Enabled';
			echo json_encode(array('message' => $message, '$success' => $success));
		} else if ($mode == 'false') {
			$object = [
				'is_publikasi' => null
			];
			$this->db->where('slug', $id2);
			$this->db->update('manage_survey', $object);


			$message = 'Survei Berhasil diprivate';
			$success = 'Disabled';
			echo json_encode(array('message' => $message, 'success' => $success));
		}
	}

}

/* End of file SertifikatController.php */
/* Location: ./application/controllers/SertifikatController.php */