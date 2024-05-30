<?php

class RekapitulasiIndeksPerPelayananController extends Client_Controller {

	public function index($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = 'Rekapitulasi Indeks Per Pelayanan';
		$this->data['profiles'] = Client_Controller::_get_data_profile($id1, $id2);

		$slug = $this->uri->segment(2);
		$manage_survey = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
		$table_identity = $manage_survey->table_identity;
		$this->data['manage_survey'] = $manage_survey;
		$this->data['table_identity'] = $table_identity;

		$this->db->select("*");
		$this->db->from("layanan_survei_$table_identity");
		// $this->db->order_by("layanan_survei_$table_identity.urutan asc");

		// $this->db->select("*");
		// $this->db->from("responden_$table_identity");
		// $this->db->join("survey_$table_identity", "responden_$table_identity.id = survey_$table_identity.id_responden");
		// $this->db->where("is_submit", "1");
		// $this->db->group_by('id_layanan_survei'); 

		$this->data['layanan'] = $this->db->get();

        $this->db->select('COUNT(id) AS id');
        $this->db->from('survey_' . $manage_survey->table_identity);
        $this->db->where("is_submit = 1");
        $this->data['jumlah_kuisioner'] = $this->db->get()->row()->id;
		
		return view('rekapitulasi_indeks_per_pelayanan/index', $this->data);
	}
}
