<?php 
require APPPATH . '/libraries/LIPIAPI_REST_Controller.php';
class Riwayat_unitkerja extends  LIPIAPI_REST_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
       
    }
     protected $methods = [
			'index_get' => ['level' => 10, 'limit' => 10],
            'xlist_get' => ['level' => 0, 'limit' => 10],
			'sub_get' => ['level' => 10, 'limit' => 10],
            'add_riwayat_unitkerja_post' => ['level' => 0, 'limit' => 10],
            'add_riwayat_unitkerja_post' => ['level' => 0, 'limit' => 10],
            'del_riwayat_unitkerja_post' => ['level' => 0, 'limit' => 10],
    ];
    public function list_get(){
        $this->load->model('pegawai/riwayat_pindah_unit_kerja_model');
        $pegawai_nip = $this->get('NIP');
        if ($NIP === NULL)
        {
            $output['msg'] = 'Parameter NIP di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        $this->riwayat_pindah_unit_kerja_model->where("PNS_NIP",$NIP); 
        $this->riwayat_pindah_unit_kerja_model->ORDER_BY("ID","Desc");
        $records = $this->riwayat_pindah_unit_kerja_model->find_all();
        $output = array(
            'success' => true,
            'data'=>$records
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function add_riwayat_unitkerja_post(){
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/riwayat_pindah_unit_kerja_model');
        if(sizeof($_POST)==0){
            $this->form_validation->set_data(array('____check_____'=>1));
        }
        $this->form_validation->set_rules($this->riwayat_pindah_unit_kerja_model->get_validation_rules());
        
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = $this->form_validation->error_array();//validation_errors();
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST); // OK (200) being the HTTP response code
            die();
        }
       
        $data = $this->riwayat_pindah_unit_kerja_model->prep_data($this->input->post());
       
        $this->pegawai_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $pegawai_data = $this->pegawai_model->find_first_row();  
        $data["PNS_ID"] = $pegawai_data->PNS_ID;
        $data["PNS_NAMA"] = $pegawai_data->NAMA;
        $data["PNS_NIP"] = $pegawai_data->NIP_BARU;
       
        $unor_baru_data = $this->unitkerja_model->find($data['ID_UNOR_BARU']);
        //$data["NAMA_UNOR_BARU"] = $unor_baru_data->NAMA_ESELON_I."-".$unor_baru_data->NAMA_ESELON_II."-".$unor_baru_data->NAMA_ESELON_III."-".$unor_baru_data->NAMA_ESELON_IV;
        $data["NAMA_UNOR_BARU"] = $unor_baru_data->NAMA_UNOR;
        $instansi_baru_data = $this->instansi_model->find($data['ID_INSTANSI']);
        $data["NAMA_INSTANSI"] = $instansi_baru_data->NAMA;
        
        $recordunors = $this->unitkerja_model->find($this->input->post("ID_SATUAN_KERJA"));
        $NAMA_SATKER = ISSET($recordunors->NAMA_UNOR) ? $recordunors->NAMA_UNOR : "";
 //       die($NAMA_SATKER."ini");
        $data["NAMA_SATUAN_KERJA"] = $NAMA_SATKER;


        if(empty($data["SK_TANGGAL"])){
            unset($data["SK_TANGGAL"]);
        }
        $inserted_id =  $this->riwayat_pindah_unit_kerja_model->insert($data);

        $response = array(
            'success'=>true,
            'id'=>$inserted_id
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function edit_riwayat_unitkerja_post(){
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/riwayat_pindah_unit_kerja_model');
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

        $data = $this->riwayat_pindah_unit_kerja_model->prep_data($this->input->post());
       
        $this->pegawai_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $pegawai_data = $this->pegawai_model->find_first_row();  
        $data["PNS_ID"] = $pegawai_data->PNS_ID;
        $data["PNS_NAMA"] = $pegawai_data->NAMA;
        $data["PNS_NIP"] = $pegawai_data->NIP_BARU;
       
        $unor_baru_data = $this->unitkerja_model->find($data['ID_UNOR_BARU']);
        //$data["NAMA_UNOR_BARU"] = $unor_baru_data->NAMA_ESELON_I."-".$unor_baru_data->NAMA_ESELON_II."-".$unor_baru_data->NAMA_ESELON_III."-".$unor_baru_data->NAMA_ESELON_IV;
        $data["NAMA_UNOR_BARU"] = $unor_baru_data->NAMA_UNOR;
        $instansi_baru_data = $this->instansi_model->find($data['ID_INSTANSI']);
        $data["NAMA_INSTANSI"] = $instansi_baru_data->NAMA;
        
        $recordunors = $this->unitkerja_model->find($this->input->post("ID_SATUAN_KERJA"));
        $NAMA_SATKER = ISSET($recordunors->NAMA_UNOR) ? $recordunors->NAMA_UNOR : "";
 //       die($NAMA_SATKER."ini");
        $data["NAMA_SATUAN_KERJA"] = $NAMA_SATKER;


        if(empty($data["SK_TANGGAL"])){
            unset($data["SK_TANGGAL"]);
        }
        $hasil = false;
        if(isset($id_data) && !empty($id_data)){
            $hasil = $this->riwayat_pindah_unit_kerja_model->update($id_data,$data);
        }
        $response = array(
            'success'=>true,
            'result'=>$hasil
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function del_riwayat_unitkerja_post(){
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/riwayat_pindah_unit_kerja_model');
        $this->load->helper('application');
        $hasil  = false;
        $record_id    = $this->input->post("ID");
        if ($record_id === NULL)
        {
            $output['msg'] = 'Parameter ID di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        if ($this->riwayat_pindah_unit_kerja_model->delete($record_id)) {
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