<?php 
require APPPATH . '/libraries/LIPIAPI_REST_Controller.php';
class Riwayat_keluarga extends  LIPIAPI_REST_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');

        $this->load->model('pegawai/orang_tua_model');
        $this->load->model('pegawai/istri_model');
        $this->load->model('pegawai/anak_model');
        $this->load->model('pegawai/pegawai_model');
    }
     protected $methods = [
			'index_get' => ['level' => 10, 'limit' => 10],
            'xlist_get' => ['level' => 0, 'limit' => 10],
			'sub_get' => ['level' => 10, 'limit' => 10],
            'add_riwayat_ortu_post' => ['level' => 0, 'limit' => 10],
            'add_riwayat_ppk_post' => ['level' => 0, 'limit' => 10],
            'del_riwayat_ppk_post' => ['level' => 0, 'limit' => 10],
    ];
    public function list_ortu_get(){
        $this->load->model('pegawai/orang_tua_model');
        $NIP = $this->get('NIP');
        if ($NIP === NULL)
        {
            $output['msg'] = 'Parameter NIP di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        $this->orang_tua_model->where("NIP",$NIP); 
        $this->orang_tua_model->ORDER_BY("ID","Desc");
        $records = $this->orang_tua_model->find_all();
        $output = array(
            'success' => true,
            'data'=>$records
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function list_anak_get(){
        $NIP = $this->get('NIP');
        if ($NIP === NULL)
        {
            $output['msg'] = 'Parameter NIP di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        $this->anak_model->where("NIP",$NIP); 
        $this->anak_model->ORDER_BY("ID","Desc");
        $records = $this->anak_model->find_all();
        $output = array(
            'success' => true,
            'data'=>$records
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function list_istri_get(){
        $NIP = $this->get('NIP');
        if ($NIP === NULL)
        {
            $output['msg'] = 'Parameter NIP di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        $this->anak_model->where("NIP",$NIP); 
        $this->anak_model->ORDER_BY("ID","Desc");
        $records = $this->anak_model->find_all();
        $output = array(
            'success' => true,
            'data'=>$records
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function add_ortu_post(){
        
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
         
        if ($NIP === NULL)
        {
            $output['msg'] = 'Parameter NIP di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        if ($this->form_validation->run() === false) {
            $response['msg'] = $this->form_validation->error_array();//validation_errors();
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST); // OK (200) being the HTTP response code
            die();
        }
       
        $data = $this->orang_tua_model->prep_data($this->input->post());
        $this->pegawai_model->where("NIP_BARU",$this->input->post("NIP"));
        $pegawai_data = $this->pegawai_model->find_first_row(); 
        
        $data["NIP"] = $pegawai_data->NIP_BARU;
        
        $data['TANGGAL_LAHIR']  = $this->input->post('TANGGAL_LAHIR') ? $this->input->post('TANGGAL_LAHIR') : null;
        $data['TGL_MENINGGAL']  = $this->input->post('TGL_MENINGGAL') ? $this->input->post('TGL_MENINGGAL') : null;

        
        $inserted_id = $this->orang_tua_model->insert($data);

        $response = array(
            'success'=>true,
            'id'=>$inserted_id
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function edit_ortu_post(){
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

        $data = $this->orang_tua_model->prep_data($this->input->post());
        $this->pegawai_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $pegawai_data = $this->pegawai_model->find_first_row(); 
        
        $data["NIP"] = $pegawai_data->NIP_BARU;
        
        $data['TANGGAL_LAHIR']  = $this->input->post('TANGGAL_LAHIR') ? $this->input->post('TANGGAL_LAHIR') : null;
        $data['TGL_MENINGGAL']  = $this->input->post('TGL_MENINGGAL') ? $this->input->post('TGL_MENINGGAL') : null;
        $hasil = false;
        if(isset($id_data) && !empty($id_data)){
            $hasil = $this->orang_tua_model->update($id_data,$data);
        }
        $response = array(
            'success'=>true,
            'result'=>$hasil
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function del_ortu_post(){
        $this->load->helper('application');
        $hasil  = false;
        $record_id    = $this->input->post("ID");
        if ($record_id === NULL)
        {
            $output['msg'] = 'Parameter ID di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        if ($this->orang_tua_model->delete($record_id)) {
             //log_activity($this->auth->user_id(), 'delete data Riwayat hukuman disiplin via API : ' . $record_id . ' : ' . $this->input->ip_address(), 'hukuman_disiplin');    
             $hasil = true;
        } 

        $response = array(
            'success'=>true,
            'result'=>$hasil
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    // anak 
    public function add_anak_post(){
        
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
         
        if ($NIP === NULL)
        {
            $output['msg'] = 'Parameter NIP di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        if ($this->form_validation->run() === false) {
            $response['msg'] = $this->form_validation->error_array();//validation_errors();
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST); // OK (200) being the HTTP response code
            die();
        }
       
        $data = $this->anak_model->prep_data($this->input->post());
        $this->pegawai_model->where("NIP_BARU",$this->input->post("NIP"));
        $pegawai_data = $this->pegawai_model->find_first_row(); 
        
        $data["NIP"] = $pegawai_data->NIP_BARU;
        $data['TANGGAL_LAHIR']  = $this->input->post('TANGGAL_LAHIR') ? $this->input->post('TANGGAL_LAHIR') : null;
         
        $inserted_id = $this->anak_model->insert($data);

        $response = array(
            'success'=>true,
            'id'=>$inserted_id
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function edit_anak_post(){
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

        $data = $this->anak_model->prep_data($this->input->post());
        $this->pegawai_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $pegawai_data = $this->pegawai_model->find_first_row(); 
        
        $data["NIP"] = $pegawai_data->NIP_BARU;
        $data['TANGGAL_LAHIR']  = $this->input->post('TANGGAL_LAHIR') ? $this->input->post('TANGGAL_LAHIR') : null;
         
        $hasil = false;
        if(isset($id_data) && !empty($id_data)){
            $hasil = $this->anak_model->update($id_data,$data);
        }
        $response = array(
            'success'=>true,
            'result'=>$hasil
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function del_anak_post(){
        $this->load->helper('application');
        $hasil  = false;
        $record_id    = $this->input->post("ID");
        if ($record_id === NULL)
        {
            $output['msg'] = 'Parameter ID di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        if ($this->anak_model->delete($record_id)) {
             //log_activity($this->auth->user_id(), 'delete data Riwayat hukuman disiplin via API : ' . $record_id . ' : ' . $this->input->ip_address(), 'hukuman_disiplin');    
             $hasil = true;
        } 

        $response = array(
            'success'=>true,
            'result'=>$hasil
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    // istri 
    public function add_istri_post(){
        
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
         
        if ($NIP === NULL)
        {
            $output['msg'] = 'Parameter NIP di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        if ($this->form_validation->run() === false) {
            $response['msg'] = $this->form_validation->error_array();//validation_errors();
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST); // OK (200) being the HTTP response code
            die();
        }
       
        $data = $this->istri_model->prep_data($this->input->post());
        $this->pegawai_model->where("NIP_BARU",$this->input->post("NIP"));
        $pegawai_data = $this->pegawai_model->find_first_row(); 
        $jk = trim($pegawai_data->JENIS_KELAMIN);
        if($jk == "M")
            $data["HUBUNGAN"] = "1";    
        else
            $data["HUBUNGAN"] = "2";    

        $data["NIP"] = $pegawai_data->NIP_BARU;
        
        $data['TANGGAL_MENINGGAL']  = $this->input->post('TANGGAL_MENINGGAL') ? $this->input->post('TANGGAL_MENINGGAL') : null;
        $data['TANGGAL_MENIKAH']  = $this->input->post('TANGGAL_MENIKAH') ? $this->input->post('TANGGAL_MENIKAH') : null;
        $data['TANGGAL_CERAI']  = $this->input->post('TANGGAL_CERAI') ? $this->input->post('TANGGAL_CERAI') : null;
        
        $inserted_id = $this->istri_model->insert($data);

        $response = array(
            'success'=>true,
            'id'=>$inserted_id
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function edit_istri_post(){
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

        $data = $this->istri_model->prep_data($this->input->post());
        $this->pegawai_model->where("NIP_BARU",$this->input->post("NIP"));
        $pegawai_data = $this->pegawai_model->find_first_row(); 
        $jk = trim($pegawai_data->JENIS_KELAMIN);
        if($jk == "M")
            $data["HUBUNGAN"] = "1";    
        else
            $data["HUBUNGAN"] = "2";    

        $data["NIP"] = $pegawai_data->NIP_BARU;
        
        $data['TANGGAL_MENINGGAL']  = $this->input->post('TANGGAL_MENINGGAL') ? $this->input->post('TANGGAL_MENINGGAL') : null;
        $data['TANGGAL_MENIKAH']  = $this->input->post('TANGGAL_MENIKAH') ? $this->input->post('TANGGAL_MENIKAH') : null;
        $data['TANGGAL_CERAI']  = $this->input->post('TANGGAL_CERAI') ? $this->input->post('TANGGAL_CERAI') : null;
        
        $hasil = false;
        if(isset($id_data) && !empty($id_data)){
            $hasil = $this->istri_model->update($id_data,$data);
        }
        $response = array(
            'success'=>true,
            'result'=>$hasil
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function del_istri_post(){
        $this->load->helper('application');
        $hasil  = false;
        $record_id    = $this->input->post("ID");
        if ($record_id === NULL)
        {
            $output['msg'] = 'Parameter ID di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        if ($this->istri_model->delete($record_id)) {
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