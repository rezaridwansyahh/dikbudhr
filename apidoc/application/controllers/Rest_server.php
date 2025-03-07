<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Rest_server extends CI_Controller {

	public function __construct(){
        parent::__construct();
        
        $this->load->model('Kategori_model');
	}

    public function index(){

        $data['isi_kategori'] = $this->Kategori_model->get_kategori();

        $this->load->view('layout/v_header');
        $this->load->view('layout/v_body', $data);
    }

    
    
}
