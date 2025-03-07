<?php
require APPPATH . '/libraries/LIPIAPI_REST_Controller.php';
class Riwayat_kepangkatan extends  LIPIAPI_REST_Controller
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
        'add_riwayat_kepangkatan_post' => ['level' => 0, 'limit' => 10],
        'add_riwayat_kepangkatan_post' => ['level' => 0, 'limit' => 10],
        'del_riwayat_kepangkatan_post' => ['level' => 0, 'limit' => 10],
    ];
    public function list_get()
    {
        $this->load->model('pegawai/riwayat_kepangkatan_model');
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
        $records = $this->riwayat_kepangkatan_model->find_all_by_nip_unorid($pegawai_nip, $satker);
        $output = array(
            'success' => true,
            'data' => $records
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function add_riwayat_kepangkatan_post()
    {
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/riwayat_kepangkatan_model');
        if (sizeof($_POST) == 0) {
            $this->form_validation->set_data(array('____check_____' => 1));
        }
        //$this->form_validation->set_rules($this->riwayat_kepangkatan_model->get_validation_rules());
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

        $data = $this->riwayat_kepangkatan_model->prep_data($this->input->post());

        $this->pegawai_model->where("NIP_BARU", $this->input->post("NIP"));
        $pegawai_data = $this->pegawai_model->find_first_row();
        $data["PNS_NIP"] = $pegawai_data->NIP_BARU;
        $data["PNS_ID"] = $pegawai_data->PNS_ID;
        $data["PNS_NAMA"] = $pegawai_data->NAMA;

        $jenis_kp_data = $this->jenis_kp_model->find($data['KODE_JENIS_KP']);
        $data["JENIS_KP"] = $jenis_kp_data->NAMA;

        $this->golongan_model->where("ID", $data['ID_GOLONGAN']);
        $golongan_data = $this->golongan_model->find_first_row();
        $data["GOLONGAN"] = $golongan_data->NAMA;
        $data["PANGKAT"] = $golongan_data->NAMA_PANGKAT;

        if (empty($data["TMT_GOLONGAN"])) {
            unset($data["TMT_GOLONGAN"]);
        }
        if (empty($data["SK_TANGGAL"])) {
            unset($data["SK_TANGGAL"]);
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
        if ($this->input->post("PANGKAT_TERAKHIR") == "1") {
            $dataupdate = array();
            $dataupdate["PANGKAT_TERAKHIR"] = 0;
            $this->riwayat_kepangkatan_model->update_where("PNS_ID", $this->input->post("PNS_ID"), $dataupdate);
            $dataupdate = array();
            $dataupdate['GOL_ID']           = $this->input->post('ID_GOLONGAN');
            $dataupdate['TMT_GOLONGAN']     = $this->input->post('TMT_GOLONGAN') ? $this->input->post('TMT_GOLONGAN') : null;
            $this->pegawai_model->update_where("PNS_ID", $this->input->post("PNS_ID"), $dataupdate);

            $data["PANGKAT_TERAKHIR"]    = 1;
        }

        $inserted_id = $this->riwayat_kepangkatan_model->insert($data);

        $response = array(
            'success' => true,
            'id' => $inserted_id
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function edit_riwayat_kepangkatan_post()
    {
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/riwayat_kepangkatan_model');
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

        $data = $this->riwayat_kepangkatan_model->prep_data($this->input->post());

        $this->pegawai_model->where("NIP_BARU", $this->input->post("NIP"));
        $pegawai_data = $this->pegawai_model->find_first_row();
        $data["PNS_NIP"] = $pegawai_data->NIP_BARU;
        $data["PNS_ID"] = $pegawai_data->PNS_ID;
        $data["PNS_NAMA"] = $pegawai_data->NAMA;

        $jenis_kp_data = $this->jenis_kp_model->find($data['KODE_JENIS_KP']);
        $data["JENIS_KP"] = $jenis_kp_data->NAMA;

        $this->golongan_model->where("ID", $data['ID_GOLONGAN']);
        $golongan_data = $this->golongan_model->find_first_row();
        $data["GOLONGAN"] = $golongan_data->NAMA;
        $data["PANGKAT"] = $golongan_data->NAMA_PANGKAT;

        if (empty($data["TMT_GOLONGAN"])) {
            unset($data["TMT_GOLONGAN"]);
        }
        if (empty($data["SK_TANGGAL"])) {
            unset($data["SK_TANGGAL"]);
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
        if ($this->input->post("PANGKAT_TERAKHIR") == "1") {
            $dataupdate = array();
            $dataupdate["PANGKAT_TERAKHIR"] = 0;
            $this->riwayat_kepangkatan_model->update_where("PNS_ID", $this->input->post("PNS_ID"), $dataupdate);
            $dataupdate = array();
            $dataupdate['GOL_ID']           = $this->input->post('ID_GOLONGAN');
            $dataupdate['TMT_GOLONGAN']     = $this->input->post('TMT_GOLONGAN') ? $this->input->post('TMT_GOLONGAN') : null;
            $this->pegawai_model->update_where("PNS_ID", $this->input->post("PNS_ID"), $dataupdate);

            $data["PANGKAT_TERAKHIR"]    = 1;
        }

        $id_data = $this->input->post("ID");
        $hasil = false;
        if (isset($id_data) && !empty($id_data)) {
            $hasil =  $this->riwayat_kepangkatan_model->update($id_data, $data);
        } else $this->riwayat_kepangkatan_model->insert($data);

        $response = array(
            'success' => true,
            'result' => $hasil
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function del_riwayat_kepangkatan_post()
    {
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/riwayat_kepangkatan_model');
        $this->load->helper('application');
        $hasil  = false;
        $record_id    = $this->input->post("ID");
        if ($record_id === NULL) {
            $output['msg'] = 'Parameter ID di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        if ($this->riwayat_kepangkatan_model->delete($record_id)) {
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
