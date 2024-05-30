<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PertanyaanKualitatifController extends Client_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}

		$this->load->library('form_validation');
		$this->load->model('PertanyaanKualitatif_model');
	}

	public function index($id1, $id2)
	{

		$url = $this->uri->uri_string();
		$this->session->set_userdata('urlback', $url);

		$this->data = [];
		$this->data['title'] = 'Pertanyaan Kualitatif';
		$this->data['profiles'] = Client_Controller::_get_data_profile($id1, $id2);

		$this->db->select('manage_survey.id AS id_manage_survey, manage_survey.table_identity AS table_identity, is_question');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();
		// var_dump($id_manage_survey);

		$this->data['is_question'] = $manage_survey->is_question;


		return view('pertanyaan_kualitatif/index', $this->data);
	}

	public function ajax_list()
	{
		$slug = $this->uri->segment(2);

		$get_identity = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
		$table_identity = $get_identity->table_identity;

		$list = $this->PertanyaanKualitatif_model->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			if ($value->is_active == 1) {
				$status = '<span class="badge badge-primary">Aktif</span>';
			} else {
				$status = '<span class="badge badge-danger">Tidak Aktif</span>';
			}

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->isi_pertanyaan;
			$row[] = $status;
			$row[] = anchor($this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-kualitatif/edit/' . $value->id, '<i class="fa fa-edit"></i> Edit', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);

			if ($get_identity->is_question == 1) {
				$row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus ' . $value->isi_pertanyaan . '" onclick="delete_pertanyaan_kualitatif(' . "'" . $value->id . "'" . ')"><i class="fa fa-trash"></i> Delete</a>';
			}


			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->PertanyaanKualitatif_model->count_all($table_identity),
			"recordsFiltered" => $this->PertanyaanKualitatif_model->count_filtered($table_identity),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function add_pertanyaan_kualitatif($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = "Tambah Pertanyaan Kualitatif";

		$this->data['profiles'] = Client_Controller::_get_data_profile($id1, $id2);

		$this->db->select('manage_survey.id AS id_manage_survey, manage_survey.table_identity AS table_identity');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();

		$this->form_validation->set_rules('isi_pertanyaan', 'Isi Pertanyaan', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			return view('pertanyaan_kualitatif/add', $this->data);
		} else {

			$input 	= $this->input->post(NULL, TRUE);

			$object = [
				'isi_pertanyaan' 	=> $input['isi_pertanyaan'],
				'is_active' 	=> $input['is_active']
			];
			// var_dump($object);

			$this->db->insert('pertanyaan_kualitatif_' . $manage_survey->table_identity, $object);

			if ($this->db->affected_rows() > 0) {

				$this->session->set_flashdata('message_success', 'Berhasil menambah data');
				redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-kualitatif', 'refresh');
			} else {

				$this->data['message_data_danger'] = "Gagal menambah data";
				return view('pertanyaan_kualitatif/add', $this->data);
			}
		}
	}

	public function edit_pertanyaan_kualitatif($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = "Edit Pertanyaan Kualitatif";

		$this->data['profiles'] = Client_Controller::_get_data_profile($id1, $id2);


		$this->db->select('manage_survey.id AS id_manage_survey, manage_survey.table_identity AS table_identity');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();

		$this->data['kualitatif'] = $this->db->get_where('pertanyaan_kualitatif_' . $manage_survey->table_identity, ['id' => $this->uri->segment(5)])->row();

		$this->form_validation->set_rules('isi_pertanyaan', 'Isi Pertanyaan', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			return view('pertanyaan_kualitatif/edit', $this->data);
		} else {

			$input 	= $this->input->post(NULL, TRUE);

			$object = [
				'isi_pertanyaan' 	=> $input['isi_pertanyaan'],
				'is_active' 	=> $input['is_active']
			];

			$this->db->where('id', $this->uri->segment(5));
			$this->db->update('pertanyaan_kualitatif_' . $manage_survey->table_identity, $object);

			if ($this->db->affected_rows() > 0) {

				$this->session->set_flashdata('message_success', 'Berhasil mengubah data');
				redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-kualitatif', 'refresh');
			}
		}
	}

	public function delete_pertanyaan_kualitatif()
	{
		// $this->data['profiles'] = Client_Controller::_get_data_profile($id1, $id2);
		$this->db->select('manage_survey.id AS id_manage_survey, manage_survey.table_identity AS table_identity');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();

		$this->db->delete('pertanyaan_kualitatif_' . $manage_survey->table_identity, array('id' => $this->uri->segment(5)));

		echo json_encode(array("status" => TRUE));
	}

}

/* End of file PertanyaanKualitatifController.php */
/* Location: ./application/controllers/PertanyaanKualitatifController.php */