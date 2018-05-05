<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Presensi extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
    }
    public function index()
    {
        $this->load->view('upload_presensi');
    }

    public function upload()
    {
      $fileName = $this->input->post('file', TRUE);

      $config['upload_path'] = './upload/excel/';
      $config['file_name'] = $fileName;
      $config['allowed_types'] = 'xls|xlsx|csv';
      $config['max_size'] = 10000;

      $this->load->library('upload', $config);
      $this->upload->initialize($config);

      if (! $this->upload->do_upload('file')) {
        $this->upload->display_errors();

        $media = $this->upload->data('file');
        $inputFileName = './upload/excel/'.$media['file_name'];
        try {
          $inputFileName = IOFactory::identify($inputFileName);
          $objReader = IOFactory::createReader($inputFileType);
          $objPHPExcel = $objReader->load($inputFileName);
        } catch (\Exception $e) {
          die('Error loading file "'.pathinfo($inputFileName, PATHINFO_BASENAME). '": '.$e->getMessage());
        }

        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        for ($row=2; $row <=$highestRow ; $row++) {
          $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

          $data = array(
            "npk" => $rowData[0][0],
            "tanggal" => $rowData[0][1],
            "jam_masuk" => $rowData[0][2],
            "jam_keluar" => $rowData[0][3]
          );

          $this->db->insert("presensi", $data);
          delete_files($media['file_path']);
        }
        $this->session->set_flashdata('msg', 'Berhasil upload');
        redirect('tampil');
      }
    }

    public function tampil()
    {
      $this->load->view('tampil_presensi');
    }
}
