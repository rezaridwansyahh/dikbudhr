<?php 
require APPPATH . '/libraries/LIPIAPI_REST_Controller.php';
class Riwayat_ppk extends  LIPIAPI_REST_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
       
    }
     protected $methods = [
			'index_get' => ['level' => 10, 'limit' => 10],
            'xlist_get' => ['level' => 0, 'limit' => 10],
			'sub_get' => ['level' => 10, 'limit' => 10],
            'add_riwayat_ppk_post' => ['level' => 0, 'limit' => 10],
            'add_riwayat_ppk_post' => ['level' => 0, 'limit' => 10],
            'del_riwayat_ppk_post' => ['level' => 0, 'limit' => 10],
    ];
    public function list_get(){
        $this->load->model('pegawai/riwayat_prestasi_kerja_model');
        $pegawai_nip = $this->get('NIP');
        if ($NIP === NULL)
        {
            $output['msg'] = 'Parameter NIP di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        $this->riwayat_prestasi_kerja_model->where("PNS_NIP",$NIP); 
        $this->riwayat_prestasi_kerja_model->ORDER_BY("ID","Desc");
        $records = $this->riwayat_prestasi_kerja_model->find_all();
        $output = array(
            'success' => true,
            'data'=>$records
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function add_riwayat_ppk_post(){
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/riwayat_prestasi_kerja_model');
        if(sizeof($_POST)==0){
            $this->form_validation->set_data(array('____check_____'=>1));
        }
        $this->form_validation->set_rules($this->riwayat_prestasi_kerja_model->get_validation_rules());
        
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = $this->form_validation->error_array();//validation_errors();
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST); // OK (200) being the HTTP response code
            die();
        }
       
        $data = $this->riwayat_prestasi_kerja_model->prep_data($this->input->post());
       
        $this->pegawai_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $pegawai_data = $this->pegawai_model->find_first_row();  
        $data["PNS_ID"] = $pegawai_data->PNS_ID;
        $data["PNS_NAMA"] = $pegawai_data->NAMA;
        $data["PNS_NIP"] = $pegawai_data->NIP_BARU;
        
        
        $jenis_jabatan_data = $this->jenis_jabatan_model->find($this->input->post("JABATAN_TIPE"));
        $data["JABATAN_TIPE_TEXT"] = $jenis_jabatan_data->NAMA;

       
        if(empty($data["SK_TANGGAL"])){
            unset($data["SK_TANGGAL"]);
        }

       
        $data['NILAI_PROSENTASE_SKP'] = 60;
        $data['NILAI_SKP_AKHIR'] = $data['NILAI_PROSENTASE_SKP']/100*$data['NILAI_SKP'];

        $data['NILAI_PROSENTASE_PERILAKU'] = 40;
        $data['NILAI_PERILAKU'] = $data['PERILAKU_KOMITMEN'] 
                                  +$data['PERILAKU_INTEGRITAS'] 
                                  +$data['PERILAKU_DISIPLIN'] 
                                  +$data['PERILAKU_KERJASAMA'] 
                                  +$data['PERILAKU_ORIENTASI_PELAYANAN'] ;
        if($data['JABATAN_TIPE']==1){
             $data['NILAI_PERILAKU'] += $data['PERILAKU_KEPEMIMPINAN'] ;
              $data['NILAI_PERILAKU'] = $data['NILAI_PERILAKU']/6;
        }
        else {
            $data['NILAI_PERILAKU'] = $data['NILAI_PERILAKU']/5;
        }
        $data['NILAI_PERILAKU_AKHIR'] = $data['NILAI_PROSENTASE_PERILAKU']/100*$data['NILAI_PERILAKU'];

         $data['NILAI_PPK'] = $data['NILAI_SKP_AKHIR']  + $data['NILAI_PERILAKU_AKHIR'];

        $id_data = $this->input->post("ID");
        $inserted_id = $this->riwayat_prestasi_kerja_model->insert($data);

        $response = array(
            'success'=>true,
            'id'=>$inserted_id
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function edit_riwayat_ppk_post(){
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/riwayat_prestasi_kerja_model');
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

        $data = $this->riwayat_prestasi_kerja_model->prep_data($this->input->post());
       
        $this->pegawai_model->where("NIP_BARU",$this->input->post("NIP"));
        $pegawai_data = $this->pegawai_model->find_first_row();  
        $data["PNS_ID"] = $pegawai_data->PNS_ID;
        $data["PNS_NAMA"] = $pegawai_data->NAMA;
        $data["PNS_NIP"] = $pegawai_data->NIP_BARU;
        
        
        $jenis_jabatan_data = $this->jenis_jabatan_model->find($this->input->post("JABATAN_TIPE"));
        $data["JABATAN_TIPE_TEXT"] = $jenis_jabatan_data->NAMA;

       
        if(empty($data["SK_TANGGAL"])){
            unset($data["SK_TANGGAL"]);
        }

       
        $data['NILAI_PROSENTASE_SKP'] = 60;
        $data['NILAI_SKP_AKHIR'] = $data['NILAI_PROSENTASE_SKP']/100*$data['NILAI_SKP'];

        $data['NILAI_PROSENTASE_PERILAKU'] = 40;
        $data['NILAI_PERILAKU'] = $data['PERILAKU_KOMITMEN'] 
                                  +$data['PERILAKU_INTEGRITAS'] 
                                  +$data['PERILAKU_DISIPLIN'] 
                                  +$data['PERILAKU_KERJASAMA'] 
                                  +$data['PERILAKU_ORIENTASI_PELAYANAN'] ;
        if($data['JABATAN_TIPE']==1){
             $data['NILAI_PERILAKU'] += $data['PERILAKU_KEPEMIMPINAN'] ;
              $data['NILAI_PERILAKU'] = $data['NILAI_PERILAKU']/6;
        }
        else {
            $data['NILAI_PERILAKU'] = $data['NILAI_PERILAKU']/5;
        }
        $data['NILAI_PERILAKU_AKHIR'] = $data['NILAI_PROSENTASE_PERILAKU']/100*$data['NILAI_PERILAKU'];

         $data['NILAI_PPK'] = $data['NILAI_SKP_AKHIR']  + $data['NILAI_PERILAKU_AKHIR'];

        
        $hasil = false;
        if(isset($id_data) && !empty($id_data)){
            $hasil = $this->riwayat_prestasi_kerja_model->update($id_data,$data);
        }
         

        $response = array(
            'success'=>true,
            'result'=>$hasil
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function del_riwayat_ppk_post(){
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/riwayat_prestasi_kerja_model');
        $this->load->helper('application');
        $hasil  = false;
        $record_id    = $this->input->post("ID");
        if ($record_id === NULL)
        {
            $output['msg'] = 'Parameter ID di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        if ($this->riwayat_prestasi_kerja_model->delete($record_id)) {
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