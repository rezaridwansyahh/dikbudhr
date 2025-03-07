<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends CI_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Kategori_model');
	}

    public function index(){

        $data['isi'] = $this->Kategori_model->get_kategori();

            $this->load->view('layout/v_header');
            $this->load->view('v_kategori', $data);
    
    }

    public function addKategori(){
        $data = '';   
        $this->form_validation->set_rules('nama_kategori', 'Nama Kategori harus diisi', 'required|trim');

		if($this->form_validation->run()==FALSE){
            $this->load->view('layout/v_header');
            $this->load->view('v_kategori_add', $data);
        }else{
            $nama_kategori = $this->input->post('nama_kategori');
			$desc = $this->input->post('desc');

			$upload_image = $_FILES['image']['name'];

			if($upload_image){
				$config['upload_path'] = './assets/img/thumbs/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size']     = '2048'; // 2 MEga
				$this->load->library('upload', $config);
				
				if($this->upload->do_upload('image')){
                    $image = $this->upload->data('file_name');
				}else{
					echo $this->upload->display_errors();
				}
			}
            // Insert Kategori
            $arr_insert = array(
                    'nama_kategori' => $nama_kategori,
                    'desc' => $desc,
                    'image' => $image
            );

            $insert_id = $this->Kategori_model->insert_kategori($arr_insert);
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Berhasil ! Kategori sudah Bertambah</div>');
            
            redirect('kategori');

        }
        
    }

    public function editKategori($id_kategori){

        $data['data_kategori'] = $this->Kategori_model->get_kategori_byId($id_kategori);

        $this->form_validation->set_rules('nama_kategori', 'Nama Kategori harus diisi', 'required|trim');

		if($this->form_validation->run()==FALSE){
            $this->load->view('layout/v_header');
            $this->load->view('v_kategori_edit', $data);
        }else{
            $nama_kategori = $this->input->post('nama_kategori');
			$desc = $this->input->post('desc');

			$upload_image = $_FILES['image']['name'];

			if($upload_image){
				$config['upload_path'] = './assets/img/thumbs/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size']     = '2048'; // 2 MEga
				$this->load->library('upload', $config);
				
				if($this->upload->do_upload('image')){
                    $image = $this->upload->data('file_name');
				}else{
					echo $this->upload->display_errors();
				}
			}
            // Insert Kategori
            $arr_insert = array(
                    'nama_kategori' => $nama_kategori,
                    'desc' => $desc,
                    'image' => $image
            );

            $insert_id = $this->Kategori_model->update_kategori($id_kategori, $arr_insert);
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Berhasil ! Kategori sudah Berganti</div>');
            
            redirect('kategori');

        }


    }

    public function deleteKategori($id_kategori){
        // Select dulu
        $select = $this->Kategori_model->get_api_controller_by_kategori_id($id_kategori);
        foreach ($select as $value) {
            # code...
            $arr_id_kategori[] = $value['id'];
        }
        // Update Controller with Kategori ID
        $update_api_controller = $this->Kategori_model->update_api_contoller($arr_id_kategori);
        
        // Delete Kategori
        $delete_kategori = $this->Kategori_model->delete_kategori($id_kategori);

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Berhasil ! Kategori sudah Terhapus</div>');
        redirect('kategori');
    }

    
    
}
