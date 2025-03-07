<?php
require APPPATH . '/libraries/LIPIAPI_REST_Controller.php';
class Riwayat_pendidikan extends  LIPIAPI_REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }
    protected $methods = [
        'index_get' => ['level' => 10, 'limit' => 10],
        'xlist_get' => ['level' => 0, 'limit' => 10],
        'sub_get' => ['level' => 10, 'limit' => 10],
        'add_riwayat_pendidikan_post' => ['level' => 0, 'limit' => 10],
        'add_riwayat_pendidikan_post' => ['level' => 0, 'limit' => 10],
        'del_riwayat_pendidikan_post' => ['level' => 0, 'limit' => 10],
    ];
    public function list_get()
    {
        $this->load->model('pegawai/riwayat_pendidikan_model');
        // $pegawai_nip = $this->get('pegawai_nip');
        // if ($pegawai_nip === NULL)
        // {
        //     $output['msg'] = 'Parameter NIP di butuhkan';
        //     $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        // }
        // $this->riwayat_pendidikan_model->where("NIP",$pegawai_nip); 
        // $this->riwayat_pendidikan_model->ORDER_BY("ID","Desc");
        // $records = $this->riwayat_pendidikan_model->find_all();
        // $output = array(
        //     'success' => true,
        //     'data'=>$records
        // );
        // $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        $appKeyData = $this->getApplicationKey();
        //print_r($appKeyData);
        // $satkers = array();
        if ($appKeyData->satker_auth) {
            $satker = $appKeyData->satker_auth;
        }
        $pegawai_nip = $this->get('nip');
        if ($pegawai_nip === NULL) {
            $output['msg'] = 'Parameter NIP di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        // $this->riwayat_kepangkatan_model->where("PNS_NIP", $NIP);
        // $this->riwayat_kepangkatan_model->ORDER_BY("ID", "Desc");
        $records = $this->riwayat_pendidikan_model->find_all_by_nip_unorid($pegawai_nip, $satker);
        $output = array(
            'success' => true,
            'data' => $records
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function add_riwayat_pendidikan_post()
    {
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/riwayat_pendidikan_model');
        if (sizeof($_POST) == 0) {
            $this->form_validation->set_data(array('____check_____' => 1));
        }
        $this->form_validation->set_rules('ID', 'ID', 'required');
        $this->form_validation->set_rules('NIP', 'NIP', 'required');

        $response = array(
            'success' => false,
            'msg' => 'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = $this->form_validation->error_array(); //validation_errors();
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST); // OK (200) being the HTTP response code
            die();
        }
        if ($NIP === NULL) {
            $output['msg'] = 'Parameter NIP di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        if ($id_data === NULL) {
            $output['msg'] = 'Parameter ID di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }


        $this->pegawai_model->where("NIP_BARU", $this->input->post("NIP"));
        $pegawai_data = $this->pegawai_model->find_first_row();
        $PNS_ID = $pegawai_data->PNS_ID;


        $data = $this->riwayat_pendidikan_model->prep_data($this->input->post());
        if (empty($data['TANGGAL_LULUS'])) {
            unset($data['TANGGAL_LULUS']);
        }
        if ($this->input->post("STATUS_SATKER") == "1") {
            $data["STATUS_SATKER"]    = 1;
        } else {
            $data["STATUS_SATKER"]    = 0;
        }
        if ($this->input->post("STATUS_BIRO") == "1") {
            $data["STATUS_BIRO"]    = 1;
        } else {
            $data["STATUS_BIRO"]    = 0;
        }
        if ($this->input->post("PENDIDIKAN_TERAKHIR") == "1") {
            $dataupdate = array();
            $dataupdate["PENDIDIKAN_TERAKHIR"] = 0;
            $this->riwayat_pendidikan_model->update_where("PNS_ID", $PNS_ID, $dataupdate);
            $data["PENDIDIKAN_TERAKHIR"]    = 1;

            $recpendidikan = $this->pendidikan_model->find($this->input->post('PENDIDIKAN_ID'));
            $dataupdate = array();
            $dataupdate['PENDIDIKAN_ID']    = $this->input->post('PENDIDIKAN_ID');
            $dataupdate['PENDIDIKAN']       = isset($recpendidikan->NAMA) ? $recpendidikan->NAMA : "";
            $dataupdate['TK_PENDIDIKAN']    = $this->input->post('TINGKAT_PENDIDIKAN_ID');
            $this->pegawai_model->update_where("PNS_ID", $PNS_ID, $dataupdate);
        }
        $inserted_id = $this->riwayat_pendidikan_model->insert($data);

        $response = array(
            'success' => true,
            'id' => $inserted_id
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function edit_riwayat_pendidikan_post()
    {
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/riwayat_pendidikan_model');
        $NIP = $this->input->post("NIP");
        $id_data = $this->input->post("ID");
        if (sizeof($_POST) == 0) {
            $this->form_validation->set_data(array('____check_____' => 1));
        }
        //$this->form_validation->set_rules($this->riwayat_huk_dis_model->get_validation_rules());
        $this->form_validation->set_rules('ID', 'ID', 'required');
        $this->form_validation->set_rules('NIP', 'NIP', 'required');
        $response = array(
            'success' => false,
            'msg' => 'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = $this->form_validation->error_array(); //validation_errors();
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST); // OK (200) being the HTTP response code
            die();
        }
        if ($NIP === NULL) {
            $output['msg'] = 'Parameter NIP di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        if ($id_data === NULL) {
            $output['msg'] = 'Parameter ID di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        $this->pegawai_model->where("NIP_BARU", $this->input->post("NIP"));
        $pegawai_data = $this->pegawai_model->find_first_row();
        $PNS_ID = $pegawai_data->PNS_ID;


        $data = $this->riwayat_pendidikan_model->prep_data($this->input->post());
        if (empty($data['TANGGAL_LULUS'])) {
            unset($data['TANGGAL_LULUS']);
        }
        if ($this->input->post("STATUS_SATKER") == "1") {
            $data["STATUS_SATKER"]    = 1;
        } else {
            $data["STATUS_SATKER"]    = 0;
        }
        if ($this->input->post("STATUS_BIRO") == "1") {
            $data["STATUS_BIRO"]    = 1;
        } else {
            $data["STATUS_BIRO"]    = 0;
        }
        if ($this->input->post("PENDIDIKAN_TERAKHIR") == "1") {
            $dataupdate = array();
            $dataupdate["PENDIDIKAN_TERAKHIR"] = 0;
            $this->riwayat_pendidikan_model->update_where("PNS_ID", $PNS_ID, $dataupdate);
            $data["PENDIDIKAN_TERAKHIR"]    = 1;

            $recpendidikan = $this->pendidikan_model->find($this->input->post('PENDIDIKAN_ID'));
            $dataupdate = array();
            $dataupdate['PENDIDIKAN_ID']    = $this->input->post('PENDIDIKAN_ID');
            $dataupdate['PENDIDIKAN']       = isset($recpendidikan->NAMA) ? $recpendidikan->NAMA : "";
            $dataupdate['TK_PENDIDIKAN']    = $this->input->post('TINGKAT_PENDIDIKAN_ID');
            $this->pegawai_model->update_where("PNS_ID", $PNS_ID, $dataupdate);
        }

        $hasil = false;
        if (isset($id_data) && !empty($id_data)) {
            $hasil = $this->riwayat_pendidikan_model->update($id_data, $data);
        }

        $response = array(
            'success' => true,
            'result' => $hasil
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function del_riwayat_pendidikan_post()
    {
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/riwayat_pendidikan_model');
        $this->load->helper('application');
        $hasil  = false;
        $record_id    = $this->input->post("ID");
        if ($record_id === NULL) {
            $output['msg'] = 'Parameter ID di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        if ($this->riwayat_pendidikan_model->delete($record_id)) {
            //log_activity($this->auth->user_id(), 'delete data Riwayat hukuman disiplin via API : ' . $record_id . ' : ' . $this->input->ip_address(), 'hukuman_disiplin');    
            $hasil = true;
        }

        $response = array(
            'success' => true,
            'result' => $hasil
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
}
