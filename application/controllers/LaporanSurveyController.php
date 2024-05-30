<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\Word2007;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Element\Chart;
use PhpOffice\PhpWord\Element\Field;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use Dompdf\Dompdf;
use Dompdf\Options;


class LaporanSurveyController extends Client_Controller
{

    function  __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
            redirect('auth', 'refresh');
        }
        $this->load->helper('url');
    }

    public function index($id1, $id2)
    {

        $this->data = [];
        $this->data['title'] = "Laporan Survei";
        $this->data['profiles'] = Client_Controller::_get_data_profile($id1, $id2);

        $this->data['query'] = $this->db->get_where('manage_survey', array('slug' => $id2))->row();
        $table_identity = $this->data['query']->table_identity;

        $cek_survey = $this->db->get_where("survey_$table_identity", array('is_submit' => 1))->num_rows();

        if (date("Y-m-d") < $this->data['query']->survey_end) {
            $this->data['pesan'] = 'Halaman ini hanya bisa dikelola jika periode survei sudah diselesai atau survei sudah ditutup.';
            return view('not_questions/index', $this->data);
        }

        if ($cek_survey == 0) {
            $this->data['pesan'] = 'survei belum dimulai atau belum ada responden !';
            return view('not_questions/index', $this->data);
        }


        return view('laporan_survey/index', $this->data);
    }

    public function cetak($id1, $id2)
    {
        $id3 = 1;
        $this->data = [];

        // $this->data['nama_produk'] = 'Survei Persepsi Kualitas Pelayanan';
        // $this->data['nama_alias'] = 'SPKP';
        // $this->data['nama_indeks'] = 'Indeks Persepsi Kualitas Pelayanan';
        // $this->data['nama_uraian'] = 'Persepsi Kualitas Pelayanan';

        $this->data['profiles'] = Client_Controller::_get_data_profile($id1, $id2);
        $this->data['title'] = "Laporan SPKP & SPAK - " . $this->data['profiles']->organisasi;
        $this->data['no_Bab1'] = 1;
        $this->data['no_Bab2'] = 1;
        $this->data['no_Bab3'] = 1;
        $this->data['no_Bab4'] = 1;
        $this->data['no_gambar'] = 1;
        $this->data['no_table'] = 1;



        $this->data['manage_survey'] = $this->db->get_where('manage_survey', array('slug' => $id2))->row();
        $table_identity = $this->data['manage_survey']->table_identity;
        $this->data['table_identity'] = $this->data['manage_survey']->table_identity;


        //PENDEFINISIAN SKALA LIKERT
        $this->data['skala_likert'] = 100 / ($this->data['manage_survey']->skala_likert == 5 ? 5 : 4);
        $this->data['definisi_skala'] = $this->db->query("SELECT * FROM definisi_skala_$table_identity ORDER BY id DESC");


        $this->data['kategori_unsur'] = $this->db->query("SELECT *,
        (SELECT COUNT(IF(skor_jawaban != 0, 1, NULL)) FROM jawaban_pertanyaan_unsur_$table_identity JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden WHERE jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = kategori_unsur_pelayanan_$table_identity.id_pertanyaan_unsur && kategori_unsur_pelayanan_$table_identity.nomor_kategori_unsur_pelayanan = jawaban_pertanyaan_unsur_$table_identity.skor_jawaban && is_submit = 1) AS perolehan,
        (SELECT COUNT(IF(skor_jawaban != 0, 1, NULL)) FROM jawaban_pertanyaan_unsur_$table_identity JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden WHERE jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = kategori_unsur_pelayanan_$table_identity.id_pertanyaan_unsur && is_submit = 1) AS jumlah_pengisi
        FROM kategori_unsur_pelayanan_$table_identity");


        $this->data['alasan_unsur'] = $this->db->query("SELECT *
        FROM jawaban_pertanyaan_unsur_$table_identity
        JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
        JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
        WHERE is_submit = 1 && alasan_pilih_jawaban != '' && jawaban_pertanyaan_unsur_$table_identity.is_active = 1");



        // $this->load->view('laporan_survey/cetak', $this->data);
        $this->load->library('pdfgenerator');
        $file_pdf = 'Laporan SPKP & SPAK - ' . $this->data['profiles']->organisasi;
        $paper = 'A4';
        $orientation = "potrait";
        $html = $this->load->view('laporan_survey/cetak', $this->data, true);
        $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
    }



    public function download_pdf($slug, $filename)
    {
        $this->load->helper('download');
        $path    =   file_get_contents(base_url() . "assets/klien/file/laporan/pdf/" . $filename . '.pdf');
        $name    =   "laporan-" . $slug . '.pdf';
        force_download($name, $path);
    }

    public function download_word($slug, $filename)
    {
        $this->load->helper('download');
        $path    =   file_get_contents(base_url() . "assets/klien/file/laporan/word/" . $filename . '.docx');
        $name    =   "laporan-" . $slug . '.docx';
        force_download($name, $path);
    }
}
