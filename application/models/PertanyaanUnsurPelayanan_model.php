<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PertanyaanUnsurPelayanan_model extends CI_Model
{

    var $table             = 'pertanyaan_unsur_pelayanan';
    var $column_order     = array(null, null, null, null, null);
    var $column_search     = array('pertanyaan_unsur_pelayanan.isi_pertanyaan_unsur');
    var $order             = array('pertanyaan_unsur_pelayanan.id' => 'asc');

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query()
    {
        $this->db->select('*, unsur_pelayanan.id AS id_unsur_pelayanan, pertanyaan_unsur_pelayanan.id AS id_pertanyaan_unsur_pelayanan');
        $this->db->from('pertanyaan_unsur_pelayanan');
        $this->db->join('unsur_pelayanan', 'unsur_pelayanan.id = pertanyaan_unsur_pelayanan.id_unsur_pelayanan');
        // $this->db->join('kategori_unsur_pelayanan', 'unsur_pelayanan.id = kategori_unsur_pelayanan.id_unsur_pelayanan');
        $this->db->join('jenis_pelayanan', 'unsur_pelayanan.id_jenis_pelayanan = jenis_pelayanan.id');
        // $this->db->join('unsur_skm', 'unsur_pelayanan.id_unsur_skm = unsur_skm.id');
        $this->db->join('klasifikasi_survei ks', 'ks.id = jenis_pelayanan.id_klasifikasi_survei');

        if ($this->input->post('id_jenis_pelayanan')) {
            $this->db->where('unsur_pelayanan.id_jenis_pelayanan', $this->input->post('id_jenis_pelayanan'));
        }

        $i = 0;

        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {

                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->select('*');
        $this->db->from('pertanyaan_unsur_pelayanan');
        $this->db->join('unsur_pelayanan', 'unsur_pelayanan.id = pertanyaan_unsur_pelayanan.id_unsur_pelayanan');
        $this->db->join('jenis_pelayanan', 'unsur_pelayanan.id_jenis_pelayanan = jenis_pelayanan.id');
        // $this->db->join('unsur_skm', 'unsur_pelayanan.id_unsur_skm = unsur_skm.id');
        $this->db->join('klasifikasi_survei ks', 'ks.id = jenis_pelayanan.id_klasifikasi_survei');
        return $this->db->count_all_results();
    }

    public function dropdown_unsur_pelayanan()
    {
        $this->db->select('*, unsur_pelayanan.id AS id_unsur_pelayanan');
        $this->db->from('unsur_pelayanan');
        $this->db->join('jenis_pelayanan', 'jenis_pelayanan.id = unsur_pelayanan.id_jenis_pelayanan');
        $this->db->join('klasifikasi_survei ks', 'ks.id = jenis_pelayanan.id_klasifikasi_survei');
        $this->db->where('jenis_pelayanan.id', $this->uri->segment(4));
        $this->db->where("NOT EXISTS (SELECT * FROM pertanyaan_unsur_pelayanan WHERE unsur_pelayanan.id = pertanyaan_unsur_pelayanan.id_unsur_pelayanan)", null, false);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $dd[''] = 'Please Select';
            foreach ($query->result_array() as $row) {
                $dd[$row['id_unsur_pelayanan']] = $row['nama_klasifikasi_survei'] . ' -- ' . $row['nama_jenis_pelayanan_responden'] . ' -- ' . $row['nomor_unsur'] . '. ' . $row['nama_unsur_pelayanan'];
            }

            return $dd;
        }
    }

    public function get_kategori_unsur_pelayanan($id)
    {
        $query = $this->db->query("SELECT * , kategori_unsur_pelayanan.id AS id_kategori_unsur_pelayanan
                                    FROM `kategori_unsur_pelayanan`
                                    WHERE kategori_unsur_pelayanan.id_pertanyaan_unsur = '$id'");
        return $query->result();
    }

    function getById($id_kategori)
    {
        $query = $this->db->get_where('kategori_unsur_pelayanan', array('id' =>  $id_kategori));
        return $query;
    }

    function tampil_data()
    {
        $this->db->select('*');
        $this->db->from('pilihan_jawaban_pertanyaan_harapan');
        return $this->db->get();
        // return $this->db->get('unsur_pelayanan');
    }

    function cari($id)
    {
        $this->db->select('*');
        $this->db->from('pilihan_jawaban_pertanyaan_harapan');
        $this->db->where('id =' . $id);
        return $this->db->get();

        // $query = $this->db->get_where('unsur_pelayanan', array('id' => $id));
        // return $query;
    }
}

/* End of file UnsurPelayanan_model.php */
/* Location: ./application/models/UnsurPelayanan_model.php */