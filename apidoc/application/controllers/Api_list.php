<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Api_list extends CI_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Api_model');
        $this->load->model('Kategori_model');
	}

    public function index($kategori_id = ''){

        
        $data_kategori = $this->Kategori_model->get_kategori();

        $arr_kategori[''] = '- Semua Kategori -';
        foreach ($data_kategori as $value) {
            # code...
            $arr_kategori[$value['id']] = $value['nama_kategori'];
        }
        $data['arr_kategori'] = $arr_kategori;
        $data['kategori_id'] = $kategori_id;

        if($kategori_id){
            $data['data_api_all'] = $this->Api_model->get_api_by_kategori_id($kategori_id);
        }else{
            $data['data_api_all'] = $this->Api_model->get_api_all();
        }

        $this->load->view('layout/v_header');
        $this->load->view('v_api_list', $data);
    }

    public function addApi(){
        $isi_kategori = $this->Kategori_model->get_kategori();
        foreach ($isi_kategori as  $value) {
            # code...
            $arr_kategori[$value['id']] = $value['nama_kategori'];
        }

        $data['arr_kategori'] = $arr_kategori;
        
        $this->form_validation->set_rules('name', 'Nama Api harus diisi', 'required|trim');
        $this->form_validation->set_rules('url', 'Url Api harus diisi', 'required|trim');
        $this->form_validation->set_rules('parameter', 'Parameter Api harus diisi', 'required|trim');

		if($this->form_validation->run()==FALSE){
            $this->load->view('layout/v_header');
            $this->load->view('v_api_add', $data);
        }else{

            // Insert API
            $arr_insert = array(
                    'name' => $this->input->post('name'),
                    'url' => $this->input->post('url'),
                    'description' => $this->input->post('description'),
                    'scope' => $this->input->post('scope'),
                    'method' => $this->input->post('method'),
                    'parameter' => $this->input->post('parameter'),
                    'example_param' => $this->input->post('example_param'),
                    'result' => $this->input->post('result'),
                    'kategori_id' => $this->input->post('kategori_id'),
                    'active' => $this->input->post('active'),
            );

            $insert_id = $this->Api_model->insert_api($arr_insert);
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Berhasil ! API sudah Bertambah</div>');
            
            redirect('api_list');

        }
        
    }

    public function editApi($id){
        $isi_kategori = $this->Kategori_model->get_kategori();
        foreach ($isi_kategori as  $value) {
            # code...
            $arr_kategori[$value['id']] = $value['nama_kategori'];
        }

        $data['arr_kategori'] = $arr_kategori;

        $data['data_api'] = $this->Api_model->get_api_by_id($id);
        
        $this->form_validation->set_rules('name', 'Nama Api harus diisi', 'required|trim');
        $this->form_validation->set_rules('url', 'Url Api harus diisi', 'required|trim');
        $this->form_validation->set_rules('parameter', 'Parameter Api harus diisi', 'required|trim');

		if($this->form_validation->run()==FALSE){
            $this->load->view('layout/v_header');
            $this->load->view('v_api_edit', $data);
        }else{

            // Update API
            $arr_update = array(
                    'name' => $this->input->post('name'),
                    'url' => $this->input->post('url'),
                    'description' => $this->input->post('description'),
                    'scope' => $this->input->post('scope'),
                    'method' => $this->input->post('method'),
                    'parameter' => $this->input->post('parameter'),
                    'example_param' => $this->input->post('example_param'),
                    'result' => $this->input->post('result'),
                    'kategori_id' => $this->input->post('kategori_id'),
                    'active' => $this->input->post('active'),
            );

            $insert_id = $this->Api_model->update_api($id, $arr_update);
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Berhasil ! API sudah Berubah</div>');
            
            redirect('api_list');

        }
        
    }

    public function deleteApi($id){
        // Delete Kategori
        $delete_api= $this->Api_model->delete_api($id);

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Berhasil ! API sudah Terhapus</div>');
        redirect('api_list');
    }


    public function ajaxGetDetailApi(){
        $status = FALSE;
        $data = '';

        $data = $this->Api_model->get_api_by_id($this->input->post('id'));
        if($data){
            $status = TRUE;
        }
        
        $arr_data = array (
            'status' => $status,
            'data' => $data
        );

        echo json_encode($arr_data);
    }

    
    
}
