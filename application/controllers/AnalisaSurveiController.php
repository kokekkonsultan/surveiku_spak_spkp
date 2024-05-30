<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AnalisaSurveiController extends Client_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}

		$this->load->library('form_validation');
		$this->load->model('AnalisaSurvei_model', 'Models');
	}

	public function index($id1, $id2, $id3)
	{
		$this->data = [];
		$this->data['title'] = $id3 == 1 ? 'Analisa<br>Survei Persepsi Kualitas Pelayanan' : 'Analisa<br>Survei Persepsi Anti Korupsi';
		$this->data['profiles'] = Client_Controller::_get_data_profile($id1, $id2);

		#======== Set Session Produk SPAK / SPKP
		$this->session->unset_userdata('is_produk');
		$this->session->set_userdata('is_produk', $id3);
		#======== End Session Produk SPAK / SPKP


		$manage_survey = $this->db->get_where('manage_survey', array('slug' => $this->uri->segment(2)))->row();
		$table_identity = $manage_survey->table_identity;
		// $this->data['executive_summary'] = $manage_survey->executive_summary;

		//PENDEFINISIAN SKALA LIKERT
		$this->data['skala_likert'] = 100 / ($manage_survey->skala_likert == 5 ? 5 : 4);
		$this->data['definisi_skala'] = $this->db->query("SELECT * FROM definisi_skala_$table_identity ORDER BY id desc");


		if (date("Y-m-d") < $manage_survey->survey_end) {
			$this->data['pesan'] = 'Halaman ini hanya bisa dikelola jika periode survei sudah diselesai atau survei sudah ditutup.';
			return view('not_questions/index', $this->data);
		}


		$this->data['jumlah_kuisioner'] = $this->db->get_where("survey_$table_identity", array('is_submit' => 1));
		if ($this->data['jumlah_kuisioner']->num_rows() == 0) {
			$this->data['pesan'] = 'survei belum dimulai atau belum ada responden !';
			return view('not_questions/index', $this->data);
		}

		// $this->data['nilai_per_sub_unsur'] = $this->db->query("SELECT unsur_pelayanan_$table_identity.id AS id_unsur_pelayanan, nomor_unsur, nama_unsur_pelayanan, (SELECT AVG(jawaban_pertanyaan_unsur_$table_identity.skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden WHERE survey_$table_identity.is_submit = 1 && id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id) AS rata_skor
		// FROM unsur_pelayanan_$table_identity
		// JOIN pertanyaan_unsur_pelayanan_$table_identity ON unsur_pelayanan_$table_identity.id = pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan
		// WHERE id_parent != 0 && is_produk = $id3");


		$this->data['nilai_per_unsur'] = $this->db->query("SELECT is_produk, IF(id_parent = 0,unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub,
		(SELECT nomor_unsur FROM unsur_pelayanan_$table_identity unsur_sub WHERE unsur_sub.id = id_sub) AS nomor_unsur,
		(SELECT nama_unsur_pelayanan FROM unsur_pelayanan_$table_identity unsur_sub WHERE unsur_sub.id = id_sub) AS nama_unsur_pelayanan,
		(SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden)) AS rata_rata, 
		(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden)) AS colspan,
		((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden))) AS nilai_per_unsur, (((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden)))/(SELECT COUNT(id) FROM unsur_pelayanan_$table_identity WHERE id_parent = 0 && is_produk = $id3)) AS rata_rata_bobot
		
		FROM jawaban_pertanyaan_unsur_$table_identity
		JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
		JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
		JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
		WHERE survey_$table_identity.is_submit = 1 && is_produk = $id3
		GROUP BY id_sub
		ORDER BY rata_rata_bobot ASC");


		foreach ($this->data['nilai_per_unsur']->result() as $value) {
			$nilai_bobot[] = $value->rata_rata_bobot;
			$nilai_tertimbang = array_sum($nilai_bobot);
			$this->data['ikm'] = ROUND($nilai_tertimbang * $this->data['skala_likert'], 10);
			$this->data['nilai_tertimbang'] = $nilai_tertimbang;
		}

		return view('analisa_survei/index', $this->data);
	}


	public function detail($id1, $id2, $id3)
	{
		$this->data = [];
		$this->data['title'] = 'Detail Analisa Unsur';
		$this->data['profiles'] = Client_Controller::_get_data_profile($id1, $id2);

		$manage_survey = $this->db->get_where('manage_survey', array('slug' => $id2))->row();
		$table_identity = $manage_survey->table_identity;

		
		$this->data['table_identity'] = $table_identity;
		$this->data['skala_likert'] = 100 / ($manage_survey->skala_likert == 5 ? 5 : 4);
		$this->data['definisi_skala'] = $this->db->query("SELECT * FROM definisi_skala_$table_identity ORDER BY id desc");


		$this->data['cek_turunan_unsur'] = $this->db->get_where("unsur_pelayanan_$table_identity", ["id_parent" => $id3]);
		if ($this->data['cek_turunan_unsur']->num_rows() > 0) {
			$nomor_unsur = "(SELECT nomor_unsur FROM unsur_pelayanan_$table_identity WHERE unsur_pelayanan_$table_identity.id = unsur_baru.id_sub) AS nomor_unsur";
			$nama_unsur_pelayanan = "(SELECT nama_unsur_pelayanan FROM unsur_pelayanan_$table_identity WHERE unsur_pelayanan_$table_identity.id = unsur_baru.id_sub) AS nama_unsur_pelayanan";
			$kondisi = "unsur_baru.id_sub";
		} else {
			$nomor_unsur = "nomor_unsur";
			$nama_unsur_pelayanan = 'nama_unsur_pelayanan';
			$kondisi = "unsur_baru.id";
		}


		$this->data['pertanyaan'] = $this->db->query("SELECT unsur_baru.id AS id_unsur_pelayanan,
		((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden))) AS nilai_per_unsur,
		$nama_unsur_pelayanan,
		$nomor_unsur,
		isi_pertanyaan_unsur
		
		FROM jawaban_pertanyaan_unsur_$table_identity
		JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
		JOIN (SELECT *, IF(id_parent = 0,unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub FROM unsur_pelayanan_$table_identity) AS unsur_baru ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_baru.id
		JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
		WHERE is_submit = 1 && $kondisi = $id3")->row();


		$this->data['alasan'] = $this->db->query("SELECT *
		FROM jawaban_pertanyaan_unsur_$table_identity
		JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
		JOIN (SELECT *, IF(id_parent = 0,unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub FROM unsur_pelayanan_$table_identity) AS unsur_baru ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_baru.id
		JOIN responden_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
		JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
		WHERE is_submit = 1 && alasan_pilih_jawaban != '' && $kondisi = $id3");


		return view('analisa_survei/detail', $this->data);
	}
	

	public function modal_edit($id1, $id2, $id3, $id4)
	{
		$this->data = [];
		$this->data['title'] = 'Detail Analisa Unsur';
		$this->data['profiles'] = Client_Controller::_get_data_profile($id1, $id2);
		$table_identity = $this->data['profiles']->table_identity;

		$this->data['id_unsur_pelayanan'] = $id3;
		$this->data['id_layanan_survei'] = $id4;
		$analisa = $this->db->query("SELECT * FROM analisa_$table_identity WHERE id_unsur_pelayanan = $id3 && id_layanan_survei = $id4");
		if($analisa->num_rows() > 0){
			$this->data['faktor_penyebab'] = $analisa->row()->faktor_penyebab;
			$this->data['rencana_perbaikan'] = $analisa->row()->rencana_perbaikan;
		} else {
			$this->data['faktor_penyebab'] = '';
			$this->data['rencana_perbaikan'] = '';
		}
		

		return view('analisa_survei/modal_edit', $this->data);
	}

	public function edit($id1, $id2)
	{
		$this->data['profiles'] = Client_Controller::_get_data_profile($id1, $id2);
		$table_identity = $this->data['profiles']->table_identity;
		$input 	= $this->input->post(NULL, TRUE);

		$id_unsur_pelayanan = $input['id_unsur_pelayanan'];
		$id_layanan_survei = $input['id_layanan_survei'];

		$object = [
			'id_unsur_pelayanan' 	=> $id_unsur_pelayanan,
			'id_layanan_survei' 	=> $id_layanan_survei,
			'faktor_penyebab' 		=> $input['faktor_penyebab'],
			'rencana_perbaikan' 	=> $input['rencana_perbaikan']
		];
		if($this->db->query("SELECT * FROM analisa_$table_identity WHERE id_unsur_pelayanan = $id_unsur_pelayanan && id_layanan_survei = $id_layanan_survei")->num_rows() > 0){
			$this->db->where(['id_unsur_pelayanan' => $id_unsur_pelayanan, 'id_layanan_survei' => $id_layanan_survei]);
			$this->db->update("analisa_$table_identity", $object);
		} else {
			$this->db->insert("analisa_$table_identity", $object);
		}
		// var_dump($object);

		$pesan = 'Data berhasil disimpan';
        $msg = ['sukses' => $pesan];
        echo json_encode($msg);
	}


	public function delete($id1, $id2, $id3)
	{
		$manage_survey = $this->db->get_where('manage_survey', array('slug' => $id2))->row();
		$this->db->delete("analisa_survei_$manage_survey->table_identity", array('id' => $id3));

		echo json_encode(array("status" => TRUE));
	}
	
}

/* End of file PertanyaanKualitatifController.php */
/* Location: ./application/controllers/PertanyaanKualitatifController.php */