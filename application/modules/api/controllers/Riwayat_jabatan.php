<?php
require APPPATH . '/libraries/LIPIAPI_REST_Controller.php';
class Riwayat_jabatan extends  LIPIAPI_REST_Controller
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
    ];

    public function list_pegawai_get()
    {
        $this->load->model('pegawai/riwayat_jabatan_model');
        $post_nip = $this->get('pegawai_nip');
        if ($post_nip === NULL) {
            $output['msg'] = 'Parameter nip di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        $this->riwayat_jabatan_model->limit(300);
        $jumlah = $data_riwayat = $this->riwayat_jabatan_model->find_all_nip($post_nip);
        //
        //$data_riwayat = $this->db->select("rwj.*")->
        //      from("hris.rwt_jabatan rwj")->
        //      where("rwj.LAST_UPDATED like '".$post_tahun."-".$post_bulan."-%'");

        $output = array(
            'success' => true,
            'total' => $jumlah,
            'data' => $data_riwayat
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function list_get()
    {
        $this->load->model('pegawai/riwayat_jabatan_model');
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
        $records = $this->riwayat_jabatan_model->find_all_by_nip_unorid($pegawai_nip, $satker);
        $output = array(
            'success' => true,
            'data' => $records
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function list_tahun_bulan_get()
    {
        $this->load->model('pegawai/riwayat_jabatan_model');
        $post_tahun = $this->get('tahun');
        $post_bulan = $this->get('bulan');
        $post_limit = $this->get('limit');
        if ($post_limit == "")
            $post_limit = 300;
        $post_from_limit = $this->get('from_limit');
        if ($post_bulan === NULL) {
            $output['msg'] = 'Parameter bulan di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }


        $jumlah = $data_riwayat = $this->riwayat_jabatan_model->count_all_api($post_tahun, $post_bulan);
        if ($post_limit != "")
            $this->riwayat_jabatan_model->limit($post_limit);
        if ($post_limit != "" and $post_from_limit != "")
            $this->riwayat_jabatan_model->limit($post_limit, $post_from_limit);

        //$this->riwayat_jabatan_model->limit(2,0);
        $data_riwayat = $this->riwayat_jabatan_model->find_all_api($post_tahun, $post_bulan);
        //$data_riwayat = $this->db->select("rwj.*")->
        //		from("hris.rwt_jabatan rwj")->
        //		where("rwj.LAST_UPDATED like '".$post_tahun."-".$post_bulan."-%'");

        $output = array(
            'success' => true,
            'jumlah' => $jumlah,
            'data' => $data_riwayat
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function add_riwayat_jabatan_post()
    {
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/riwayat_jabatan_model');
        if (sizeof($_POST) == 0) {
            $this->form_validation->set_data(array('____check_____' => 1));
        }
        $this->form_validation->set_rules($this->riwayat_jabatan_model->get_validation_rules());
        $response = array(
            'success' => false,
            'msg' => 'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = "
            <div class='alert alert-block alert-error fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                    Error
                </h4>
                " . validation_errors() . "
            </div>
            ";
            echo json_encode($response);
            exit();
        }


        $data = $this->riwayat_jabatan_model->prep_data($this->input->post());

        $this->pegawai_model->where("NIP_BARU", $this->input->post("NIP"));
        $pegawai_data = $this->pegawai_model->find_first_row();
        $data["PNS_NIP"] = $pegawai_data->NIP_BARU;
        $data["PNS_ID"] = $pegawai_data->PNS_ID;
        $data["PNS_NAMA"] = $pegawai_data->NAMA;

        $rec_jenis = $this->jenis_jabatan_model->find($this->input->post("ID_JENIS_JABATAN"));
        $data["ID_JENIS_JABATAN"]   = $rec_jenis->ID;
        $data["JENIS_JABATAN"]      = $rec_jenis->NAMA;

        $rec_jabatan = $this->jabatan_model->find_by("KODE_JABATAN", $this->input->post("ID_JABATAN"));
        $data["ID_JABATAN_BKN"] = isset($rec_jabatan->KODE_BKN) ? $rec_jabatan->KODE_BKN : "";

        // struktur
        $rec_jabatan_struktural = $this->unitkerja_model->find_by("KODE_INTERNAL", $this->input->post("ID_UNOR")); // POST
        $data["ID_UNOR_BKN"]    = isset($rec_jabatan_struktural->ID) ? $rec_jabatan_struktural->ID : "";
        $data["ID_UNOR_BKN"]    = isset($rec_jabatan_struktural->ID) ? $rec_jabatan_struktural->ID : "";
        $data["UNOR"]           = isset($rec_jabatan_struktural->NAMA_UNOR) ? $rec_jabatan_struktural->NAMA_UNOR : "";
        // jika jabatannya struktural
        if ($this->input->post("ID_JENIS_JABATAN") == "1") {
            if ($this->input->post("ID_UNOR") != "") {
                $NAMA_JABATAN  = isset($rec_jabatan_struktural->NAMA_JABATAN) ? $rec_jabatan_struktural->NAMA_JABATAN : "";
                $data["NAMA_JABATAN"] = $NAMA_JABATAN;

                if ($this->input->post("IS_ACTIVE") == "1") {
                    $UNOR_ID           = isset($rec_jabatan_struktural->ID) ? $rec_jabatan_struktural->ID : "";
                    // jika jabatan aktif set ke update tabel pejabat pada tabel unitkerja
                    $dataupdate = array(
                        'PEMIMPIN_PNS_ID'     => $this->input->post("PNS_ID"),
                        'JABATAN_ID'     => $this->input->post("ID_JABATAN")
                    );
                    $this->unitkerja_model->update($UNOR_ID, $dataupdate);
                }
            }
        } else {
            if ($this->input->post("ID_JABATAN") != "") {
                $rec_jabatan = $this->jabatan_model->find_by("KODE_JABATAN", $this->input->post("ID_JABATAN"));
                $data["NAMA_JABATAN"] = $rec_jabatan->NAMA_JABATAN;
            }
        }
        $data["ID_SATUAN_KERJA"]    = trim($this->input->post("ID_SATUAN_KERJA"));
        if (empty($data["TMT_JABATAN"])) {
            unset($data["TMT_JABATAN"]);
        }
        if (empty($data["TANGGAL_SK"])) {
            unset($data["SK_TANGTANGGAL_SKGAL"]);
        }
        if (empty($data["TMT_PELANTIKAN"])) {
            unset($data["TMT_PELANTIKAN"]);
        }

        $data["LAST_UPDATED"]   = date("Y-m-d");
        $inserted_id = $this->riwayat_jabatan_model->insert($data, $this->input->post());


        $response = array(
            'success' => true,
            'id' => $inserted_id
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function edit_riwayat_jabatan_post()
    {
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/riwayat_jabatan_model');
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

        $data = $this->riwayat_jabatan_model->prep_data($this->input->post());

        $this->pegawai_model->where("PNS_ID", $this->input->post("PNS_ID"));
        $pegawai_data = $this->pegawai_model->find_first_row();
        $data["PNS_NIP"] = $pegawai_data->NIP_BARU;
        $data["PNS_ID"] = $pegawai_data->PNS_ID;
        $data["PNS_NAMA"] = $pegawai_data->NAMA;

        $rec_jenis = $this->jenis_jabatan_model->find($this->input->post("ID_JENIS_JABATAN"));
        $data["ID_JENIS_JABATAN"]   = $rec_jenis->ID;
        $data["JENIS_JABATAN"]      = $rec_jenis->NAMA;

        $rec_jabatan = $this->jabatan_model->find_by("KODE_JABATAN", $this->input->post("ID_JABATAN"));
        $data["ID_JABATAN_BKN"] = isset($rec_jabatan->KODE_BKN) ? $rec_jabatan->KODE_BKN : "";

        // struktur
        $rec_jabatan_struktural = $this->unitkerja_model->find_by("KODE_INTERNAL", $this->input->post("ID_UNOR")); // POST
        $data["ID_UNOR_BKN"]    = isset($rec_jabatan_struktural->ID) ? $rec_jabatan_struktural->ID : "";
        $data["ID_UNOR_BKN"]    = isset($rec_jabatan_struktural->ID) ? $rec_jabatan_struktural->ID : "";
        $data["UNOR"]           = isset($rec_jabatan_struktural->NAMA_UNOR) ? $rec_jabatan_struktural->NAMA_UNOR : "";
        // jika jabatannya struktural
        if ($this->input->post("ID_JENIS_JABATAN") == "1") {
            if ($this->input->post("ID_UNOR") != "") {
                $NAMA_JABATAN  = isset($rec_jabatan_struktural->NAMA_JABATAN) ? $rec_jabatan_struktural->NAMA_JABATAN : "";
                $data["NAMA_JABATAN"] = $NAMA_JABATAN;

                if ($this->input->post("IS_ACTIVE") == "1") {
                    $UNOR_ID           = isset($rec_jabatan_struktural->ID) ? $rec_jabatan_struktural->ID : "";
                    // jika jabatan aktif set ke update tabel pejabat pada tabel unitkerja
                    $dataupdate = array(
                        'PEMIMPIN_PNS_ID'     => $this->input->post("PNS_ID"),
                        'JABATAN_ID'     => $this->input->post("ID_JABATAN")
                    );
                    $this->unitkerja_model->update($UNOR_ID, $dataupdate);
                }
            }
        } else {
            if ($this->input->post("ID_JABATAN") != "") {
                $rec_jabatan = $this->jabatan_model->find_by("KODE_JABATAN", $this->input->post("ID_JABATAN"));
                $data["NAMA_JABATAN"] = $rec_jabatan->NAMA_JABATAN;
            }
        }
        $data["ID_SATUAN_KERJA"]    = trim($this->input->post("ID_SATUAN_KERJA"));
        if (empty($data["TMT_JABATAN"])) {
            unset($data["TMT_JABATAN"]);
        }
        if (empty($data["TANGGAL_SK"])) {
            unset($data["SK_TANGTANGGAL_SKGAL"]);
        }
        if (empty($data["TMT_PELANTIKAN"])) {
            unset($data["TMT_PELANTIKAN"]);
        }

        $data["LAST_UPDATED"]   = date("Y-m-d");

        $hasil = false;
        if (isset($id_data) && !empty($id_data)) {
            $hasil =  $this->riwayat_jabatan_model->update($id_data, $data, $this->input->post());
        }

        $response = array(
            'success' => true,
            'result' => $hasil
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function del_riwayat_jabatan_post()
    {
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/riwayat_jabatan_model');
        $this->load->helper('application');
        $hasil  = false;
        $record_id    = $this->input->post("ID");
        if ($record_id === NULL) {
            $output['msg'] = 'Parameter ID di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        if ($this->riwayat_jabatan_model->delete($record_id)) {
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
