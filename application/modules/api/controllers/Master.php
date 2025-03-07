<?php 
require APPPATH . '/libraries/LIPIAPI_REST_Controller.php';
class Master extends  LIPIAPI_REST_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');	  
    }

   

    function list_agama_get() {
        $this->load->model('pegawai/agama_model');
        
        $data = $this->agama_model->find_all();
        $output = array(
            'success' => true,
            'data'=>$data
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    function list_golongan_get() {
        $this->load->model('pegawai/golongan_model');
        
        $data = $this->golongan_model->find_all();
        $output = array(
            'success' => true,
            'data'=>$data
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    function list_jabatan_get() {
        $this->load->model('jabatan/jabatan_model');
        
        $data = $this->jabatan_model->find_all();
        $output = array(
            'success' => true,
            'data'=>$data
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    function list_diklat_fungsional_get() {
        $this->load->model('pegawai/jenis_diklat_fungsional_model');
        
        $data = $this->jenis_diklat_fungsional_model->find_all();
        $output = array(
            'success' => true,
            'data'=>$data
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    function list_diklat_struktural_get() {
        $this->load->model('pegawai/jenis_diklat_struktural_model');
        
        $data = $this->jenis_diklat_struktural_model->find_all();
        $output = array(
            'success' => true,
            'data'=>$data
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    function list_jenis_hukuman_get() {
        $this->load->model('pegawai/jenis_hukuman_model');
        
        $data = $this->jenis_hukuman_model->find_all();
        $output = array(
            'success' => true,
            'data'=>$data
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    function list_jenis_jabatan_get() {
        $this->load->model('pegawai/jenis_jabatan_model');
        
        $data = $this->jenis_jabatan_model->find_all();
        $output = array(
            'success' => true,
            'data'=>$data
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    
    function list_status_kawin_get() {
        $this->load->model('pegawai/jenis_kawin_model');
        
        $data = $this->jenis_kawin_model->find_all();
        $output = array(
            'success' => true,
            'data'=>$data
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    function list_jenis_pegawai_get() {
        $this->load->model('pegawai/jenis_pegawai_model');
        
        $data = $this->jenis_pegawai_model->find_all();
        $output = array(
            'success' => true,
            'data'=>$data
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    function list_kedudukan_hukum_get() {
        $this->load->model('pegawai/kedudukan_hukum_model');
        
        $data = $this->kedudukan_hukum_model->find_all();
        $output = array(
            'success' => true,
            'data'=>$data
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    
    function list_lokasi_get() {
        $this->load->model('pegawai/lokasi_model');
        
        $data = $this->lokasi_model->find_all();
        $output = array(
            'success' => true,
            'data'=>$data
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    function list_pendidikan_get() {
        $this->load->model('pegawai/pendidikan_model');
        
        $data = $this->pendidikan_model->find_all();
        $output = array(
            'success' => true,
            'data'=>$data
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    function list_tingkat_pendidikan_get() {
        $this->load->model('pegawai/tingkatpendidikan_model');
        
        $data = $this->tingkatpendidikan_model->find_all();
        $output = array(
            'success' => true,
            'data'=>$data
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    function list_unitkerja_get($id = "") {
        $this->load->model('pegawai/unitkerja_model');

        $id = $this->get('id');

        $data = $this->unitkerja_model->find_all_by_id($id);
        $output = array(
            'success' => true,
            'data'=>$data
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    
    }

}
