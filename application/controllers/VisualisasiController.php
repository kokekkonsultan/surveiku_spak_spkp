<?php
defined('BASEPATH') or exit('No direct script access allowed');

class VisualisasiController extends Client_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}
	}


	public function index($id1, $id2, $id3)
	{
		$this->data = [];
		$this->data['title'] = $id3 == 1 ? 'Grafik Per Unsur<br>Survei Persepsi Kualitas Pelayanan' : 'Grafik Per Unsur<br>Survei Persepsi Anti Korupsi';
		$this->data['is_produk'] = $id3;
		$this->data['profiles'] = Client_Controller::_get_data_profile($id1, $id2);


		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('slug', $this->uri->segment(2));
		$this->data['manage_survey'] = $this->db->get()->row();
		$this->data['table_identity'] = $this->data['manage_survey']->table_identity;
		$table_identity = $this->data['table_identity'];

		//PENDEFINISIAN SKALA LIKERT
		$this->data['skala_likert'] = 100 / ($this->data['manage_survey']->skala_likert == 5 ? 5 : 4);
		$this->data['definisi_skala'] = $this->db->query("SELECT * FROM definisi_skala_$table_identity ORDER BY id DESC");



		$this->data['unsur_pelayanan'] = $this->db->query("SELECT *, unsur_pelayanan_$table_identity.id AS id_unsur_pelayanan, (SELECT isi_pertanyaan_unsur FROM pertanyaan_unsur_pelayanan_$table_identity WHERE id_unsur_pelayanan = unsur_pelayanan_$table_identity.id) as isi_pertanyaan_unsur
		FROM unsur_pelayanan_$table_identity
		WHERE id_parent = 0 && is_produk = $id3
		ORDER BY SUBSTR(nomor_unsur,2) + 0 ASC");

		$this->data['get_pilihan_jawaban'] = $this->db->query("SELECT *, (SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity
        JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden WHERE jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = kategori_unsur_pelayanan_$table_identity.id_pertanyaan_unsur && kategori_unsur_pelayanan_$table_identity.nomor_kategori_unsur_pelayanan = jawaban_pertanyaan_unsur_$table_identity.skor_jawaban && is_submit = 1) AS perolehan, (SELECT COUNT(id) FROM survey_$table_identity WHERE is_submit = 1) AS jumlah_pengisi
        FROM kategori_unsur_pelayanan_$table_identity");


		$this->data['rekap_turunan_unsur'] = $this->db->query("SELECT *, pertanyaan_unsur_pelayanan_$table_identity.id AS id_pertanyaan_unsur_pelayanan,
		(SELECT COUNT(skor_jawaban)
		FROM jawaban_pertanyaan_unsur_$table_identity
		JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
		WHERE is_submit = 1 && id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 1) AS perolehan_1,
		(SELECT COUNT(skor_jawaban)
		FROM jawaban_pertanyaan_unsur_$table_identity
		JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
		WHERE is_submit = 1 && id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 2) AS perolehan_2,
		(SELECT COUNT(skor_jawaban)
		FROM jawaban_pertanyaan_unsur_$table_identity
		JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
		WHERE is_submit = 1 && id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 3) AS perolehan_3,
		(SELECT COUNT(skor_jawaban)
		FROM jawaban_pertanyaan_unsur_$table_identity
		JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
		WHERE is_submit = 1 && id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 4) AS perolehan_4,
		(SELECT COUNT(skor_jawaban)
		FROM jawaban_pertanyaan_unsur_$table_identity
		JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
		WHERE is_submit = 1 && id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 5) AS perolehan_5,
		(SELECT COUNT(id) FROM survey_$table_identity WHERE is_submit = 1) AS jumlah_pengisi,
		(SELECT AVG(skor_jawaban)
		FROM jawaban_pertanyaan_unsur_$table_identity
		JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
		WHERE is_submit = 1 && id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id) AS rata_rata
		FROM unsur_pelayanan_$table_identity
		JOIN pertanyaan_unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");


		//NILAI PER UNSUR
		$this->data['nilai_per_unsur'] = $this->db->query("SELECT IF(id_parent = 0, unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub, 
		(SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden)) AS rata_rata, 
		(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden)) AS colspan, 
		((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden))) AS nilai_per_unsur, 
		(SELECT nomor_unsur FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) as nomor_unsur, 
		(SELECT nama_unsur_pelayanan FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) as nama_unsur_pelayanan, unsur_pelayanan_$table_identity.id AS id_unsur
		
		FROM jawaban_pertanyaan_unsur_$table_identity 
		JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id 
		JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
		JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
		WHERE survey_$table_identity.is_submit = 1 && is_produk = $id3
		GROUP BY id_sub
		ORDER BY SUBSTR(nomor_unsur,2) + 0 ASC");
		// var_dump($this->data['nilai_per_unsur']->result());

		if ($this->db->get_where('survey_' . $this->data['table_identity'], array('is_submit' => 1))->num_rows() == 0) {
			$this->data['pesan'] = 'survei belum dimulai atau belum ada responden !';
			return view('not_questions/index', $this->data);
		}


		return view('visualisasi/index', $this->data);
	}


	public function convert_chart()
	{
		$slug = $this->uri->segment(2);
		$manage_survey = $this->db->get_where('manage_survey', array('slug' => $this->uri->segment(2)))->row();

		$id = $this->uri->segment(5);
		$img = $_POST['imgBase64'];
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$fileData = base64_decode($img);
		// $fileName = 'assets/klien/img_rekap_responden/chart_' . $id . '.png';

		$fileName = 'assets/klien/survei/' . $manage_survey->table_identity . '/chart_unsur/' . $id . '.png';
		file_put_contents($fileName, $fileData);

		// $data = [
		// 	'atribut_kuadran' => serialize(array('kuadran-' . $manage_survey->table_identity . '.png', date("d/m/Y")))
		// ];
		// $this->db->where('slug', $slug);
		// $this->db->update('manage_survey', $data);

		$msg = ['sukses' => 'Data berhasil disimpan'];
		echo json_encode($msg);
	}


}

/* End of file VisualisasiController.php */
/* Location: ./application/controllers/VisualisasiController.php */