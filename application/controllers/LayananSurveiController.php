<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LayananSurveiController extends Client_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}

		$this->load->library('form_validation');
		$this->load->model('DefinisiSkalaSurvei_model', 'Models');
	}

	public function index($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = 'Jenis Pelayanan';
		$this->data['profiles'] = Client_Controller::_get_data_profile($id1, $id2);

		$slug = $this->uri->segment(2);
		$manage_survey = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
		$table_identity = $manage_survey->table_identity;

		$this->data['layanan'] = $this->db->get("layanan_survei_$table_identity");

		return view('layanan_survei/index', $this->data);
	}


	public function add()
	{
		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();

		$input 	= $this->input->post(NULL, TRUE);
		$object = [
			'nama_layanan' 	=> $input['nama_layanan'],
			'is_active' 	=> $input['is_active']
		];
		$this->db->insert('layanan_survei_' . $manage_survey->table_identity, $object);

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}


	public function edit()
	{
		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();

		$input 	= $this->input->post(NULL, TRUE);
		$object = [
			'nama_layanan' 	=> $input['nama_layanan'],
			'is_active' 	=> $input['is_active']
		];

		$this->db->where('id', $input['id']);
		$this->db->update('layanan_survei_' . $manage_survey->table_identity, $object);

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}


	public function delete()
	{
		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();

		$this->db->where('id', $this->uri->segment(5));
		$this->db->delete('layanan_survei_' . $manage_survey->table_identity);

		echo json_encode(array("status" => TRUE));
	}



	public function grafik($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = 'Grafik Jenis Pelayanan';
		$this->data['profiles'] = Client_Controller::_get_data_profile($id1, $id2);


		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$this->data['manage_survey'] = $this->db->get()->row();
		$table_identity = $this->data['manage_survey']->table_identity;


		$responden = $this->db->query("SELECT * FROM responden_$table_identity
			JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
			WHERE is_submit = 1");

			$data = [];
			foreach ($responden->result() as $key => $value) {
				$id_layanan_survei = implode(", ", unserialize($value->id_layanan_survei));
				$data[$key] = "UNION ALL SELECT *, $value->id_responden AS id_responden
							FROM layanan_survei_$table_identity
							WHERE id IN ($id_layanan_survei)";
			}
			$tabel_layanan = implode(" ", $data);

			$this->data['layanan'] = $this->db->query("
			SELECT id, nama_layanan, COUNT(id) - 1 AS perolehan,
			SUM(Count(id)) OVER () - (SELECT COUNT(id) FROM layanan_survei_$table_identity WHERE is_active = 1) as total_survei,
			GROUP_CONCAT(id_responden) AS id_responden
			FROM (
				SELECT *, '0' AS id_responden FROM layanan_survei_$table_identity
				$tabel_layanan
				) ls
			WHERE is_active = 1
			GROUP BY id
			");


		if ($this->db->get_where('survey_' . $table_identity, array('is_submit' => 1))->num_rows() == 0) {
			$this->data['pesan'] = 'Survei belum dimulai atau belum ada responden !';
			return view('not_questions/index', $this->data);
		}

		return view('layanan_survei/grafik', $this->data);
	}


	public function pilihan_jenis()
	{
		$input 	= $this->input->post(NULL, TRUE);
		$object = [
			'is_layanan_survei' 	=> $input['is_layanan_survei']
		];
		$this->db->where('slug', $this->uri->segment(2));
		$this->db->update('manage_survey', $object);

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}


}

/* End of file PertanyaanKualitatifController.php */
/* Location: ./application/controllers/PertanyaanKualitatifController.php */
