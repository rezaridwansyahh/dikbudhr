<?php 
require APPPATH . '/libraries/LIPIAPI_REST_Controller.php';
class Riwayat_hukdis extends  LIPIAPI_REST_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
       
    }
     protected $methods = [
			'index_get' => ['level' => 10, 'limit' => 10],
            'xlist_get' => ['level' => 0, 'limit' => 10],
			'sub_get' => ['level' => 10, 'limit' => 10],
            'add_riwayat_hukdis_post' => ['level' => 0, 'limit' => 10],
            'add_riwayat_hukdis_post' => ['level' => 0, 'limit' => 10],
            'del_riwayat_hukdis_post' => ['level' => 0, 'limit' => 10],
    ];
    public function list_get(){
        $this->load->model('pegawai/riwayat_huk_dis_model');
        $pegawai_nip = $this->get('pegawai_nip');
        if ($pegawai_nip === NULL)
        {
            $output['msg'] = 'Parameter pegawainip di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        $this->riwayat_huk_dis_model->where("PNS_NIP",$pegawai_nip); 
        $this->riwayat_huk_dis_model->ORDER_BY("ID","Desc");
        $records = $this->riwayat_huk_dis_model->find_all();
        $output = array(
            'success' => true,
            'data'=>$records
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function add_riwayat_hukdis_post(){
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/riwayat_huk_dis_model');
        if(sizeof($_POST)==0){
            $this->form_validation->set_data(array('____check_____'=>1));
        }
        $this->form_validation->set_rules($this->riwayat_huk_dis_model->get_validation_rules());
        
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = $this->form_validation->error_array();//validation_errors();
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST); // OK (200) being the HTTP response code
            die();
        }
       
        $data = $this->riwayat_huk_dis_model->prep_data($this->input->post());
        $this->pegawai_model->where("NIP_BARU",$this->input->post("pegawai_nip"));
        $pegawai_data = $this->pegawai_model->find_first_row();  
        $data["PNS_NIP"]    = $pegawai_data->NIP_BARU;
        $data["PNS_ID"]     = $pegawai_data->PNS_ID;
        $data["NAMA"]       = $pegawai_data->NAMA;
        
        if(empty($data["SK_TANGGAL"])){
            unset($data["SK_TANGGAL"]);
        }
        if(empty($data["TANGGAL_SK_PEMBATALAN"])){
            unset($data["TANGGAL_SK_PEMBATALAN"]);
        }
        if(empty($data["TANGGAL_MULAI_HUKUMAN"])){
            unset($data["TANGGAL_MULAI_HUKUMAN"]);
        }
        if(empty($data["TANGGAL_AKHIR_HUKUMAN"])){
            unset($data["TANGGAL_AKHIR_HUKUMAN"]);
        }
        $data["MASA_TAHUN"]     = isset($pegawai_data->MASA_TAHUN) ? $pegawai_data->MASA_TAHUN : 0;
        $data["MASA_BULAN"]     = isset($pegawai_data->MASA_BULAN) ? $pegawai_data->MASA_BULAN : 0;

        $inserted_id = $this->riwayat_huk_dis_model->insert($data);

        $response = array(
            'success'=>true,
            'id'=>$inserted_id
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function edit_riwayat_hukdis_post(){
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/riwayat_huk_dis_model');
        $NIP = $this->input->post("NIP");
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

        $data = $this->riwayat_huk_dis_model->prep_data($this->input->post());
        $this->pegawai_model->where("NIP_BARU",$this->input->post("NIP"));
        $pegawai_data = $this->pegawai_model->find_first_row();  
        $data["PNS_NIP"]    = trim($pegawai_data->NIP_BARU);
        $data["PNS_ID"]     = trim($pegawai_data->PNS_ID);
        $data["NAMA"]       = trim($pegawai_data->NAMA);
        
        if(empty($data["SK_TANGGAL"])){
            unset($data["SK_TANGGAL"]);
        }
        if(empty($data["TANGGAL_SK_PEMBATALAN"])){
            unset($data["TANGGAL_SK_PEMBATALAN"]);
        }
        if(empty($data["TANGGAL_MULAI_HUKUMAN"])){
            unset($data["TANGGAL_MULAI_HUKUMAN"]);
        }
        if(empty($data["TANGGAL_AKHIR_HUKUMAN"])){
            unset($data["TANGGAL_AKHIR_HUKUMAN"]);
        }
        $data["MASA_TAHUN"]     = isset($pegawai_data->MASA_TAHUN) ? $pegawai_data->MASA_TAHUN : 0;
        $data["MASA_BULAN"]     = isset($pegawai_data->MASA_BULAN) ? $pegawai_data->MASA_BULAN : 0;

        $id_data    = $this->input->post("ID");
        $hasil      = false;
        if(isset($id_data) && !empty($id_data)){
           $hasil =  $this->riwayat_huk_dis_model->update($id_data,$data);
        }

        $response = array(
            'success'=>true,
            'result'=>$hasil
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function del_riwayat_hukdis_post(){
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/riwayat_huk_dis_model');
        $this->load->helper('application');
        $hasil  = false;
        $record_id    = $this->input->post("ID");
        if ($record_id === NULL)
        {
            $output['msg'] = 'Parameter ID di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        if ($this->riwayat_huk_dis_model->delete($record_id)) {
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