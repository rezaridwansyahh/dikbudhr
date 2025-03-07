<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Riwayat_ujikom extends Admin_Controller
{
    
    

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/riwayat_ujikom_model');
    }


    public function ajax_list(){
        $NIP_BARU = $this->input->post('NIP_BARU');
        $listDiklat = $this->riwayat_ujikom_model->find_all($NIP_BARU);
        echo json_encode(array("data"=>$listDiklat));
		die();
    }

    


    
}
