<?php 
require APPPATH . '/libraries/LIPIAPI_REST_Controller.php';
class Riwayat_kgb extends  LIPIAPI_REST_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
       
    }
     protected $methods = [
			'index_get' => ['level' => 10, 'limit' => 10],
            'xlist_get' => ['level' => 0, 'limit' => 10],
			'sub_get' => ['level' => 10, 'limit' => 10],
            'add_riwayat_kgb_post' => ['level' => 0, 'limit' => 10],
            'add_riwayat_kgb_post' => ['level' => 0, 'limit' => 10],
            'del_riwayat_kgb_post' => ['level' => 0, 'limit' => 10],
    ];
    public function list_get(){
        $this->load->model('pegawai/riwayat_kgb_model');
        $pegawai_nip = $this->get('NIP');
        if ($NIP === NULL)
        {
            $output['msg'] = 'Parameter NIP di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        $this->riwayat_kgb_model->where("pegawai_nip",$NIP); 
        $this->riwayat_kgb_model->ORDER_BY("ID","Desc");
        $records = $this->riwayat_kgb_model->find_all();
        $output = array(
            'success' => true,
            'data'=>$records
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function add_riwayat_kgb_post(){
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/riwayat_kgb_model');
        if(sizeof($_POST)==0){
            $this->form_validation->set_data(array('____check_____'=>1));
        }
        $this->form_validation->set_rules('ID', 'ID', 'required');
        $this->form_validation->set_rules('NIP', 'NIP', 'required');
        
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = $this->form_validation->error_array();//validation_errors();
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST); // OK (200) being the HTTP response code
            die();
        }
       
        $data = $this->riwayat_kgb_model->prep_data($this->input->post());
        $this->pegawai_model->where("NIP_BARU",$this->input->post("NIP"));
        $pegawai_data = $this->pegawai_model->find_first_row();  
        $golongan_data = $this->golongan_model->find($this->input->post('n_golongan_id'));
        $unit_kerja_induk_data = $this->unitkerja_model->find($this->input->post('unit_kerja_induk_id'));
        $lokasi_lahir_data = $this->lokasi_model->find($pegawai_data->TEMPAT_LAHIR_ID);
        
        $data["pegawai_nip"] = $pegawai_data->NIP_BARU;
        $data["pegawai_nama"] = $pegawai_data->NAMA;
        $data["birth_place"] = $lokasi_lahir_data->NAMA;
        $data["birth_date"] = $pegawai_data->TGL_LAHIR;
        $data["pegawai_id"] = $pegawai_data->ID;
        $data["n_gol_ruang"] = $golongan_data->NAMA_PANGKAT." - ".$golongan_data->NAMA;
        $data["unit_kerja_induk_text"] = $unit_kerja_induk_data->NAMA_UNOR;
        
        if(empty($data["tgl_sk"])){
            unset($data["tgl_sk"]);
        }
        if(empty($data["tmt_sk"])){
            unset($data["tmt_sk"]);
        }
        $inserted_id = $this->riwayat_kgb_model->insert($data);

        $response = array(
            'success'=>true,
            'id'=>$inserted_id
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function edit_riwayat_kgb_post(){
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/riwayat_kgb_model');
        $NIP = $this->input->post("NIP");
        $id_data = $this->input->post("ID");
        if(sizeof($_POST)==0){
            $this->form_validation->set_data(array('____check_____'=>1));
        }
        //$this->form_validation->set_rules($this->riwayat_huk_dis_model->get_validation_rules());
        $this->form_validation->set_rules('ID', 'ID', 'required');
        $this->form_validation->set_rules('NIP', 'NIP', 'required');
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = $this->form_validation->error_array();//validation_errors();
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST); // OK (200) being the HTTP response code
            die();
        }
        if ($NIP === NULL)
        {
            $output['msg'] = 'Parameter NIP di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        if ($id_data === NULL)
        {
            $output['msg'] = 'Parameter ID di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        $data = $this->riwayat_kgb_model->prep_data($this->input->post());
        $this->pegawai_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $pegawai_data = $this->pegawai_model->find_first_row();  
        $golongan_data = $this->golongan_model->find($this->input->post('n_golongan_id'));
        $unit_kerja_induk_data = $this->unitkerja_model->find($this->input->post('unit_kerja_induk_id'));
        $lokasi_lahir_data = $this->lokasi_model->find($pegawai_data->TEMPAT_LAHIR_ID);
        
        $data["pegawai_nip"] = $pegawai_data->NIP_BARU;
        $data["pegawai_nama"] = $pegawai_data->NAMA;
        $data["birth_place"] = $lokasi_lahir_data->NAMA;
        $data["birth_date"] = $pegawai_data->TGL_LAHIR;
        $data["pegawai_id"] = $pegawai_data->ID;
        $data["n_gol_ruang"] = $golongan_data->NAMA_PANGKAT." - ".$golongan_data->NAMA;
        $data["unit_kerja_induk_text"] = $unit_kerja_induk_data->NAMA_UNOR;
        
        if(empty($data["tgl_sk"])){
            unset($data["tgl_sk"]);
        }
        if(empty($data["tmt_sk"])){
            unset($data["tmt_sk"]);
        }
        $hasil = FALSE;
        if(isset($id_data) && !empty($id_data)){
            $hasil = $this->riwayat_kgb_model->update($id_data,$data);
        }
        $response = array(
            'success'=>true,
            'result'=>$hasil
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function del_riwayat_kgb_post(){
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/riwayat_kgb_model');
        $this->load->helper('application');
        $hasil  = false;
        $record_id    = $this->input->post("ID");
        if ($record_id === NULL)
        {
            $output['msg'] = 'Parameter ID di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        if ($this->riwayat_kgb_model->delete($record_id)) {
             //log_activity($this->auth->user_id(), 'delete data Riwayat hukuman disiplin via API : ' . $record_id . ' : ' . $this->input->ip_address(), 'hukuman_disiplin');    
             $hasil = true;
        } 

        $response = array(
            'success'=>true,
            'result'=>$hasil
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
}