<?php
require APPPATH . '/libraries/LIPIAPI_REST_Controller.php';
class Pegawai extends  LIPIAPI_REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('pegawai/ropeg_model');
    }
    protected $methods = [
        'index_get' => ['level' => 10, 'limit' => 10],
        'xlist_get' => ['level' => 0, 'limit' => 10],
        'sub_get' => ['level' => 10, 'limit' => 10],
        'add_riwayat_pendidikan_post' => ['level' => 0, 'limit' => 10],
        'add_riwayat_jabatan_post' => ['level' => 0, 'limit' => 10],
        'add_riwayat_pindah_unitkerja_post' => ['level' => 0, 'limit' => 10],
        'add_riwayat_kepangkatan_post' => ['level' => 0, 'limit' => 10],
        'add_riwayat_skp_post' => ['level' => 0, 'limit' => 10],
    ];
    public function cek_get()
    {
        $nip = $this->get('nip');

        $pegawai_data = $this->db->query("
            select 
                p.\"NIP_BARU\",temp.* from hris.pegawai p 
                left join (
                    select * from (
                    select ROW_NUMBER() over(PARTITION by \"PNS_ID\" ORDER BY \"TMT_JABATAN\" desc) AS _order,rjab.* from rwt_jabatan rjab 
                        where  \"PNS_NIP\" = ? and rjab.\"TMT_JABATAN\" IS NOT NULL )
                    as temp where temp._order = 1 and ? = temp.\"PNS_NIP\"
                ) as temp on p.\"NIP_BARU\" = temp.\"PNS_NIP\"
                where p.\"NIP_BARU\" = ? 
            
            ", array($nip, $nip, $nip))->first_row();
        if (!$pegawai_data) {
            $response = array(
                'success' => false,
                'message' => 'NIP tidak ditemukan'
            );
            $this->response($response, 500); // OK (200) being the HTTP response code
        }
        $data = array(
            'nip' => $pegawai_data->NIP_BARU,
            'nomor_sk_jab' => trim($pegawai_data->NOMOR_SK),
            'tgl_sk' => $pegawai_data->TANGGAL_SK,
            'tmt_sk' => $pegawai_data->TMT_JABATAN,
            'id_satuan_kerja' => $pegawai_data->ID_SATUAN_KERJA,
            'id_jabatan' => trim($pegawai_data->ID_JABATAN),
            'id_riwayat_jabatan' => trim($pegawai_data->ID),
            'id_unor_bkn' => $pegawai_data->ID_UNOR_BKN,
            //'data'=>$pegawai_data,
        );

        $response = array(
            'success' => true,
            'data' => $data
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function add_riwayat_skp_post()
    {
        $this->load->model('pegawai/riwayat_prestasi_kerja_model');
        if (sizeof($_POST) == 0) {
            $this->form_validation->set_data(array('____check_____' => 1));
        }
        $this->form_validation->set_rules($this->riwayat_prestasi_kerja_model->get_validation_rules());

        $response = array(
            'success' => false,
            'msg' => 'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = $this->form_validation->error_array(); //validation_errors();
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST); // OK (200) being the HTTP response code
            die();
        }

        $data = $this->riwayat_prestasi_kerja_model->prep_data($this->input->post());

        $nilai_perilaku = $data['PERILAKU_DISIPLIN'] + $data['PERILAKU_INTEGRITAS'] + $data['PERILAKU_KERJASAMA'] + $data['PERILAKU_KOMITMEN'] + $data['PERILAKU_ORIENTASI_PELAYANAN'];
        if (isset($data['JABATAN_TIPE'])) {
            if ($data['JABATAN_TIPE'] == 1) {
                $nilai_perilaku += $data['PERILAKU_KEPEMIMPINAN'];
                $data['NILAI_PERILAKU'] = $nilai_perilaku / 6;
            } else $data['NILAI_PERILAKU'] = $nilai_perilaku / 5;
        } else $data['NILAI_PERILAKU'] = $nilai_perilaku / 5;
        $data['NILAI_PROSENTASE_PERILAKU'] = 40;
        $data['NILAI_PERILAKU_AKHIR'] = $data['NILAI_PERILAKU'] * $data['NILAI_PROSENTASE_PERILAKU'] / 100;

        $data['NILAI_PROSENTASE_SKP'] = 60;
        $data['NILAI_SKP_AKHIR'] = $data['NILAI_SKP'] * $data['NILAI_PROSENTASE_SKP'] / 100;

        $data['NILAI_PPK'] = $data['NILAI_SKP_AKHIR'] + $data['NILAI_PERILAKU_AKHIR'];


        $inserted_id = $this->riwayat_prestasi_kerja_model->insert($data);
        $response = array(
            'success' => true,
            'id' => $inserted_id
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function add_riwayat_kepangkatan_post()
    {
        $this->load->model('pegawai/riwayat_kepangkatan_model');
        if (sizeof($_POST) == 0) {
            $this->form_validation->set_data(array('____check_____' => 1));
        }
        $this->form_validation->set_rules($this->riwayat_kepangkatan_model->get_validation_rules());

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

        $inserted_id = $this->riwayat_kepangkatan_model->insert($data);
        $response = array(
            'success' => true,
            'id' => $inserted_id
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function add_riwayat_pindah_unitkerja_post()
    {
        $this->load->model('pegawai/riwayat_pindah_unit_kerja_model');
        if (sizeof($_POST) == 0) {
            $this->form_validation->set_data(array('____check_____' => 1));
        }
        $this->form_validation->set_rules($this->riwayat_pindah_unit_kerja_model->get_validation_rules());

        $response = array(
            'success' => false,
            'msg' => 'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = $this->form_validation->error_array(); //validation_errors();
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST); // OK (200) being the HTTP response code
            die();
        }

        $data = $this->riwayat_pindah_unit_kerja_model->prep_data($this->input->post());

        $inserted_id = $this->riwayat_pindah_unit_kerja_model->insert($data);
        $response = array(
            'success' => true,
            'id' => $inserted_id
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function add_riwayat_jabatan_post()
    {
        $this->load->model('pegawai/riwayat_jabatan_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/jenis_jabatan_model');
        $this->load->model('ref_jabatan/jabatan_model');
        $this->load->model('pegawai/unitkerja_model');
        if (sizeof($_POST) == 0) {
            $this->form_validation->set_data(array('____check_____' => 1));
        }
        $this->form_validation->set_rules($this->riwayat_jabatan_model->get_validation_rules());

        $response = array(
            'success' => false,
            'msg' => 'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = $this->form_validation->error_array(); //validation_errors();
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST); // OK (200) being the HTTP response code
            die();
        }

        $data = $this->riwayat_jabatan_model->prep_data($this->input->post());

        $this->pegawai_model->where("PNS_ID", $this->input->post("PNS_ID"));
        $pegawai_data = $this->pegawai_model->find_first_row();
        if (!$pegawai_data) {
            $response['msg'] = 'Pegawai tidak ditemukan';
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST); // OK (200) being the HTTP response code
        }
        $data["PNS_NIP"] = $pegawai_data->NIP_BARU;
        $data["PNS_ID"] = $pegawai_data->PNS_ID;
        $data["PNS_NAMA"] = $pegawai_data->NAMA;

        $rec_jenis = $this->jenis_jabatan_model->find($this->input->post("ID_JENIS_JABATAN"));
        if (!$rec_jenis) {
            $response['msg'] = 'Jenis Data tidak ditemukan';
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST); // OK (200) being the HTTP response code
        }
        $data["ID_JENIS_JABATAN"]   = $rec_jenis->ID;
        $data["JENIS_JABATAN"]      = $rec_jenis->NAMA;


        $rec_jabatan_struktural = $this->unitkerja_model->find_by("ID", $this->input->post("ID_UNOR")); // POST
        $data["ID_UNOR_BKN"]    = $this->input->post("ID_UNOR");
        //isset($rec_jabatan_struktural->ID) ? $rec_jabatan_struktural->ID : "";
        //$data["ID_UNOR_BKN"]    = isset($rec_jabatan_struktural->ID) ? $rec_jabatan_struktural->ID : "";
        $data["UNOR"]           = isset($rec_jabatan_struktural->NAMA_UNOR) ? $rec_jabatan_struktural->NAMA_UNOR : "";

        // jika jabatannya struktural
        if ($this->input->post("ID_JENIS_JABATAN") == "1") {
            if ($this->input->post("ID_UNOR") != "") {
                $rec_jabatan = $this->unitkerja_model->find_by("ID", $this->input->post("ID_UNOR"));
                $data["NAMA_JABATAN"] = $rec_jabatan->NAMA_JABATAN;
                //$data["UNOR"] = $rec_jabatan->NAMA_UNOR;
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
        $id_data = $this->input->post("ID");
        //cek eksisting id data 
        $this->riwayat_jabatan_model->where("PNS_NIP", $data["PNS_NIP"]);
        $this->riwayat_jabatan_model->where("ID", $id_data);
        //$this->riwayat_jabatan_model->where("TMT_JABATAN",$data["TMT_JABATAN"]);
        $exist_data = $this->riwayat_jabatan_model->find_first_row();
        if ($exist_data) {
            $id_data = $exist_data->ID;
        }

        if (isset($id_data) && !empty($id_data)) {
            $this->riwayat_jabatan_model->update($id_data, $data, $this->input->post());
        } else {
            $id_data = $this->riwayat_jabatan_model->insert($data, $this->input->post());
        }
        $response['success'] = true;
        $response['msg'] = "berhasil";
        $response['id'] = $id_data;

        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function add_riwayat_pendidikan_post()
    {
        $this->load->model('pegawai/riwayat_pendidikan_model');
        if (sizeof($_POST) == 0) {
            $this->form_validation->set_data(array('____check_____' => 1));
        }
        $this->form_validation->set_rules($this->riwayat_pendidikan_model->get_validation_rules());

        $response = array(
            'success' => false,
            'msg' => 'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = $this->form_validation->error_array(); //validation_errors();
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST); // OK (200) being the HTTP response code
            die();
        }

        $data = $this->riwayat_pendidikan_model->prep_data($this->input->post());

        $inserted_id = $this->riwayat_pendidikan_model->insert($data);
        $response = array(
            'success' => true,
            'id' => $inserted_id
        );
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function list_get()
    {
        $this->load->model("pegawai/unitkerja_model");
        $this->load->model("pegawai/Mv_pegawai_model");
        $appKeyData = $this->getApplicationKey();
        //print_r($appKeyData);
        $satkers = array();
        if ($appKeyData->satker_auth) {
            $satkers = explode(',', $this->unitkerja_model->unor_tree_to_array($appKeyData->satker_auth));
        }

        $output = array(
            'success' => false,
            'msg' => 'Unknown error'
        );
        $unor_id = $this->get('unor_id');
        $start = (int)$this->get('start');
        $limit = $this->get('limit');
        if ($unor_id === NULL) {
            $output['msg'] = 'Parameter unor_id di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        if ($start === NULL) {
            $start = 0;
        } else {
            if ($start < 0) {
                $output['msg'] = 'Parameter start harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
        }

        if ($limit === NULL) {
            $limit = 10;
        } else {
            if ($limit == -1) {
                // no problem
            } else if ($limit > 1000) {
                $output['msg'] = 'Parameter limit maksimal 1000 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            } else if ($limit < 0) {
                $output['msg'] = 'Parameter limit harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
        }

        $this->load->model('pegawai/pegawai_model');


        $filter_satkers = array();
        if (sizeof($satkers) == 0) { // has ALL PRIV
            $filter_satkers[] = $unor_id;
        } else {
            $found_priv = false;
            foreach ($satkers as $satker) {
                if ($satker == $unor_id) {
                    $found_priv = true;
                }
            }
            if (!$found_priv) {
                $output['msg'] = 'Tidak ada akses untuk pegawai satker ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
                return;
            } else {
                $filter_satkers[] = $unor_id;
            }
        }
        $total = $this->Mv_pegawai_model->count_all_api($filter_satkers, true);
        if ($limit == -1) {
        } else {
            $this->db->limit($limit, $start);
        }
        $pegawais = $this->Mv_pegawai_model->find_all_api($filter_satkers, true);
        $this->db->flush_cache();
        $output = array(
            'success' => true,
            'total' => $total,
            'data' => $pegawais
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function detail_get()
    {
        $this->load->model("pegawai/Ropeg_model");
        // $this->load->model("pegawai/Mv_pegawai_model");
        $appKeyData = $this->getApplicationKey();
        //print_r($appKeyData);
        // $satkers = array();
        if ($appKeyData->satker_auth) {
            $satker = $appKeyData->satker_auth;
        }

        //$filter_satkers = array();
        // if (sizeof($satkers) == 0) { // has ALL PRIV
        //     $output['msg'] = 'Tidak ada akses untuk pegawai satker ';
        //     $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
        //     return;
        // } else {
        //     $found_priv = false;
        //     foreach ($satkers as $satker) {
        //         if ($satker == $unor_id) {
        //             $found_priv = true;
        //         }
        //     }
        //     if (!$found_priv) {
        //         $output['msg'] = 'Tidak ada akses untuk pegawai satker ';
        //         $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
        //         return;
        //     } else {
        //         $filter_satkers[] = $unor_id;
        //     }


        $pegawai_nip = $this->input->post('nip');
        $output = array(
            'success' => false,
            'msg' => 'Unknown error'
        );
        $pegawai_nip = $this->get('pegawai_nip');
        if ($pegawai_nip === NULL) {
            $output['msg'] = 'Parameter pegawai_nip di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        $data_pegawai = $this->Ropeg_model->detail_by_customized_satker($satker, $pegawai_nip); //->first_row();
        if (sizeof($data_pegawai) == 0) {
            $output['msg'] = 'Pegawai tidak ditemukan';
            $this->response($output, REST_Controller::HTTP_NOT_FOUND); // BAD_REQUEST (400) being the HTTP response code
        }
        $output = array(
            'success' => true,
            'data' => $data_pegawai
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function update_post()
    {
        date_default_timezone_set("Asia/Bangkok");
        $id_data = $this->input->post('ID');
        $this->form_validation->set_rules($this->pegawai_model->get_validation_rules());
        $extra_unique_rule = '';
        if ($id_data != '') {
            $_POST['id'] = $id_data;
            $extra_unique_rule = ',pegawai.ID';
        } else {
            $this->form_validation->set_rules('PNS_ID', 'KODE', 'required|max_length[30]|unique[pegawai.PNS_ID' . $extra_unique_rule . ']');
        }
        $this->form_validation->set_rules('PNS_ID', 'PNS ID', 'required|max_length[32]|unique[pegawai.PNS_ID' . $extra_unique_rule . ']');
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
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            exit();
        }

        // Make sure we only pass in the fields we want
        $data = $this->pegawai_model->prep_data($this->input->post());

        $reclokasikerja = $this->lokasi_model->find($this->input->post('LOKASI_KERJA_ID'));
        $data['LOKASI_KERJA']   = $reclokasikerja->NAMA;
        $recpendidikan = $this->pendidikan_model->find($this->input->post('PENDIDIKAN_ID'));
        $data['PENDIDIKAN'] = $recpendidikan->NAMA;
        if ($this->input->post("JENIS_JABATAN_ID") != "") {
            $rec_jenis = $this->jenis_jabatan_model->find($this->input->post("JENIS_JABATAN_ID"));
            $data["JENIS_JABATAN_NAMA"] = $rec_jenis->NAMA;
        }

        $recjabatan = $this->jabatan_model->find_by("KODE_JABATAN", $this->input->post('JABATAN_INSTANSI_ID'));
        $data['JABATAN_INSTANSI_NAMA']  = $recjabatan->NAMA_JABATAN;

        $data['PNS_ID'] = $this->input->post('PNS_ID');
        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        $data['AGAMA_ID']   = $this->input->post('AGAMA_ID') ? $this->input->post('AGAMA_ID') : null;
        $data['GOL_ID'] = $this->input->post('GOL_ID') ? $this->input->post('GOL_ID') : null;
        $data['JENIS_JABATAN_ID']   = $this->input->post('JENIS_JABATAN_ID') ? $this->input->post('JENIS_JABATAN_ID') : null;

        $data['TGL_LAHIR']  = $this->input->post('TGL_LAHIR') ? $this->input->post('TGL_LAHIR') : null;
        $data['TGL_SK_CPNS']    = $this->input->post('TGL_SK_CPNS') ? $this->input->post('TGL_SK_CPNS') : null;
        $data['TMT_CPNS']   = $this->input->post('TMT_CPNS') ? $this->input->post('TMT_CPNS') : null;
        $data['TMT_PNS']    = $this->input->post('TMT_PNS') ? $this->input->post('TMT_PNS') : null;
        $data['TMT_GOLONGAN']   = $this->input->post('TMT_GOLONGAN') ? $this->input->post('TMT_GOLONGAN') : null;
        $data['TMT_JABATAN']    = $this->input->post('TMT_JABATAN') ? $this->input->post('TMT_JABATAN') : null;

        $data['TMT_PENSIUN']    = $this->input->post('TMT_PENSIUN') ? $this->input->post('TMT_PENSIUN') : null;
        $data['TGL_SURAT_DOKTER']   = $this->input->post('TGL_SURAT_DOKTER') ? $this->input->post('TGL_SURAT_DOKTER') : null;
        $data['TGL_BEBAS_NARKOBA']  = $this->input->post('TGL_BEBAS_NARKOBA') ? $this->input->post('TGL_BEBAS_NARKOBA') : null;
        $data['TGL_CATATAN_POLISI'] = $this->input->post('TGL_CATATAN_POLISI') ? $this->input->post('TGL_CATATAN_POLISI') : null;
        $data['TGL_MENINGGAL']  = $this->input->post('TGL_MENINGGAL') ? $this->input->post('TGL_MENINGGAL') : null;
        $data['TGL_NPWP']   = $this->input->post('TGL_NPWP') ? $this->input->post('TGL_NPWP') : null;
        $data['terminated_date']    = $this->input->post('terminated_date') ? $this->input->post('terminated_date') : null;
        $data['status_pegawai'] = $this->input->post('status_pegawai') ? $this->input->post('status_pegawai') : 1;

        $result = false;
        $msg = "";
        if ($id_data == "") {
            $data['CREATED_DATE']   = date("Y-m-d");
            $data['CREATED_BY']     = $this->auth->user_id();
            if ($this->pegawai_model->insert($data)) {
                $msg = $this->agama_model->error;
            } else {
                $result = true;
            }
        } elseif ($id_data != "") {
            $data['UPDATED_DATE']   = date("Y-m-d");
            $data['UPDATED_BY']     = $this->auth->user_id();
            $result = $this->pegawai_model->update($id_data, $data);
        }
        if ($result) {
            $response['success'] = true;
            $response['msg'] = "Simpan Berhasil";
        } else {
            $response['success'] = false;
            $response['msg'] = "Ada kesalahan : " . $msg;
        }
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    //----------------------------------------------------RIFKYZ-----------------------------
    public function unor_get()
    {
        //$unor_id = 'A8ACA7397AEB3912E040640A040269BB';//$this->input->post('unor_id'); 1940CF2301E5B17CE050640A150273EF

        $output = array(
            'success' => false,
            'msg' => 'Unknown error'
        );
        $unor_id = $this->get('unor_id');
        if ($unor_id === NULL) {
            $output['msg'] = 'Parameter unor_id di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        $data_unor = $this->db->from("hris.unitkerja")->get()->result_array(); //->where('ID',$unor_id)->get()->first_row();
        $output = array(
            'success' => true,
            'data' => $data_unor
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function unor_id_get()
    {
        //$unor_id = 'A8ACA7397AEB3912E040640A040269BB';//$this->input->post('unor_id'); 1940CF2301E5B17CE050640A150273EF
        $this->load->model("pegawai/unitkerja_model");
        $output = array(
            'success' => false,
            'msg' => 'Unknown error'
        );
        $unor_id = $this->get('unor_id');
        if ($unor_id === NULL) {
            $output['msg'] = 'Parameter unor_id di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        $data_unor = $this->unitkerja_model->find_all($unor_id); // POST
        $output = array(
            'success' => true,
            'data' => $data_unor
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    /*@@@@@@@@@@@@@@@@@@@@@@@@@BANA WAS HERE@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/
    public function list_asesmen_get()
    {
        $this->load->model("pegawai/unitkerja_model");
        $appKeyData = $this->getApplicationKey();
        $satkers = array();
        if ($appKeyData->satker_auth) {
            $satkers = explode(',', $this->unitkerja_model->unor_tree_to_array($appKeyData->satker_auth));
        }

        $output = array(
            'success' => false,
            'msg' => 'Unknown error'
        );
        $unor_id = $this->get('unor_id');
        $start = (int)$this->get('start');
        $limit = $this->get('limit');
        $nama_with_nip = $this->get('param1');
        if ($unor_id === NULL) {
            $output['msg'] = 'Parameter unor_id di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        if ($start === NULL) {
            $start = 0;
        } else {
            if ($start < 0) {
                $output['msg'] = 'Parameter start harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
        }

        if ($limit === NULL) {
            $limit = 10;
        } else {
            if ($limit == -1) {
                // no problem
            } else if ($limit > 1000) {
                $output['msg'] = 'Parameter limit maksimal 1000 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            } else if ($limit < 0) {
                $output['msg'] = 'Parameter limit harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
        }

        $this->load->model('pegawai/pegawai_model');
        if ($limit == -1) {
        } else {
            $this->db->limit($limit, $start);
        }

        $filter_satkers = array();
        if (sizeof($satkers) == 0) { // has ALL PRIV
            $filter_satkers[] = $unor_id;
        } else {
            $found_priv = false;
            foreach ($satkers as $satker) {
                if ($satker == $unor_id) {
                    $found_priv = true;
                }
            }
            if (!$found_priv) {
                $output['msg'] = 'Parameter limit harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
                return;
            } else {
                $filter_satkers[] = $unor_id;
            }
        }
        $total = $this->ropeg_model->count_list_asesmen($unor_id, $nama_with_nip);

        $pegawais = $this->ropeg_model->find_all_asesmen($unor_id, $nama_with_nip, $start, $limit);
        $this->db->flush_cache();
        $output = array(
            'success' => true,
            'total' => $total,
            'data' => $pegawais
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function list_asesmen_by_nip_get()
    {
        $this->load->model("pegawai/unitkerja_model");
        $appKeyData = $this->getApplicationKey();
        $satkers = array();
        if ($appKeyData->satker_auth) {
            $satkers = explode(',', $appKeyData->satker_auth);
        }

        $output = array(
            'success' => false,
            'msg' => 'Unknown error'
        );
        //$unor_id = $this->get('unor_id');
        $start = (int)$this->get('start');
        $limit = $this->get('limit');
        $nama_with_nip = $this->get('param1');
        if ($nama_with_nip === NULL) {
            $output['msg'] = 'Parameter param1 di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        if ($start === NULL) {
            $start = 0;
        } else {
            if ($start < 0) {
                $output['msg'] = 'Parameter start harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
        }

        if ($limit === NULL) {
            $limit = 10;
        } else {
            if ($limit == -1) {
                // no problem
            } else if ($limit > 1000) {
                $output['msg'] = 'Parameter limit maksimal 1000 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            } else if ($limit < 0) {
                $output['msg'] = 'Parameter limit harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
        }

        $this->load->model('pegawai/pegawai_model');
        if ($limit == -1) {
        } else {
            $this->db->limit($limit, $start);
        }

        /*
        $filter_satkers = array();
        if(sizeof($satkers)==0){ // has ALL PRIV
            $filter_satkers[] = $unor_id;
        }
        else {
            $found_priv = false;
            foreach($satkers as $satker){
                if($satker == $unor_id){
                    $found_priv = true;
                }
            }
            if(!$found_priv){
                $output['msg'] = 'Parameter limit harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
                return;
            }   
            else {
                $filter_satkers[] = $unor_id;
            }
        }*/
        $total = $this->ropeg_model->count_list_asesmen_by_nip($nama_with_nip);

        $pegawais = $this->ropeg_model->find_all_asesmen_by_nip($nama_with_nip, $start, $limit);
        $this->db->flush_cache();
        $output = array(
            'success' => true,
            'total' => $total,
            'data' => $pegawais
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@END@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/
    public function log_transaksi_get()
    {
        $this->load->model("pegawai/log_transaksi_model");
        $appKeyData = $this->getApplicationKey();
        $satkers = array();
        if ($appKeyData->satker_auth) {
            $satkers = explode(',', $appKeyData->satker_auth);
        }

        $output = array(
            'success' => false,
            'msg' => 'Unknown error'
        );
        $dari = $this->get('dari');
        $sampai = $this->get('sampai');
        $start = (int)$this->get('start');
        $limit = $this->get('limit');
        $nama_with_nip = $this->get('param1');
        if ($dari === NULL) {
            $output['msg'] = 'Parameter dari di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        if ($sampai === NULL) {
            $output['msg'] = 'Parameter sampai di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        if ($start === NULL) {
            $start = 0;
        } else {
            if ($start < 0) {
                $output['msg'] = 'Parameter start harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
        }
        //die($sampai);
        $total = $this->log_transaksi_model->count_dari_sampai($dari, $sampai);

        if ($limit === NULL) {
            $limit = 10;
        } else {
            if ($limit == -1) {
                // no problem
            } else if ($limit > 1000) {
                $output['msg'] = 'Parameter limit maksimal 1000 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            } else if ($limit < 0) {
                $output['msg'] = 'Parameter limit harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
        }

        if ($limit == -1) {
        } else {
            $this->db->limit($limit, $start);
        }




        $pegawais = $this->log_transaksi_model->find_dari_sampai($dari, $sampai);
        $this->db->flush_cache();
        $output = array(
            'success' => true,
            'total' => $total,
            'data' => $pegawais
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@END@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/

    /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@BANA@@@@@@@@@@@@@@@@@@@@@@@*/
    public function list_custom_satker_get()
    {
        $this->load->model("pegawai/unitkerja_model");
        $this->load->model("pegawai/Mv_pegawai_model");
        $appKeyData = $this->getApplicationKey();
        //print_r($appKeyData);
        $satkers = array();
        if ($appKeyData->satker_auth) {
            $satkers = explode(',', $appKeyData->satker_auth);
        }

        $output = array(
            'success' => false,
            'msg' => 'Unknown error'
        );
        // $unor_id = $this->get('unor_id');
        $start = (int)$this->get('start');
        $limit = $this->get('limit');
        // if ($unor_id === NULL)
        // {
        //     $output['msg'] = 'Parameter unor_id di butuhkan';
        //     $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        // }
        if ($start === NULL) {
            $start = 0;
        } else {
            if ($start < 0) {
                $output['msg'] = 'Parameter start harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
        }

        if ($limit === NULL) {
            $limit = 10;
        } else {
            if ($limit == -1) {
                // no problem
            } else if ($limit > 1000) {
                $output['msg'] = 'Parameter limit maksimal 1000 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            } else if ($limit < 0) {
                $output['msg'] = 'Parameter limit harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
        }

        $this->load->model('pegawai/pegawai_model');


        // $filter_satkers = array();
        // if(sizeof($satkers)==0){ // has ALL PRIV
        //     $filter_satkers[] = $unor_id;
        // }
        // else {
        //     $found_priv = false;
        //     foreach($satkers as $satker){
        //         if($satker == $unor_id){
        //             $found_priv = true;
        //         }
        //     }
        //     if(!$found_priv){
        //         $output['msg'] = 'Tidak ada akses untuk pegawai satker ';
        //         $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
        //         return;
        //     }   
        //     else {
        //         $filter_satkers[] = $unor_id;
        //     }
        // }
        $total = $this->Mv_pegawai_model->count_all_api($satkers, true);
        if ($limit == -1) {
        } else {
            $this->db->limit($limit, $start);
        }
        $pegawais = $this->Mv_pegawai_model->find_all_api($satkers, true);
        $this->db->flush_cache();
        $output = array(
            'success' => true,
            'total' => $total,
            'data' => $pegawais
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function detail_custom_satker_get()
    {
        $appKeyData = $this->getApplicationKey();
        //echo $appKeyData->satker_auth;
        $satkers = array();
        if ($appKeyData->satker_auth) {
            $satkers = explode(',', $appKeyData->satker_auth);
        }

        $satkers_param = implode("','", $satkers);
        $pegawai_nip = $this->input->post('nip');
        $output = array(
            'success' => false,
            'msg' => 'Unknown error'
        );
        $pegawai_nip = $this->get('nip');
        if ($pegawai_nip === NULL) {
            $output['msg'] = 'Parameter pegawai_nip di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        $data_pegawai =  $this->ropeg_model->detail_by_customized_satker($satkers_param, $pegawai_nip);
        $output = array(
            'success' => true,
            'data' => $data_pegawai
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function nominatif_get()
    {
        $this->load->model("pegawai/unitkerja_model");
        $this->load->model("pegawai/Mv_pegawai_model");
        $appKeyData = $this->getApplicationKey();
        //print_r($appKeyData);
        $satkers = array();
        if ($appKeyData->satker_auth) {
            $satkers = explode(',', $this->unitkerja_model->unor_tree_to_array($appKeyData->satker_auth));
        }

        $output = array(
            'success' => false,
            'msg' => 'Unknown error'
        );
        $unor_id = $this->get('unor_id');
        $start = (int)$this->get('start');
        $limit = $this->get('limit');
        if ($unor_id === NULL) {
            $output['msg'] = 'Parameter unor_id di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        if ($start === NULL) {
            $start = 0;
        } else {
            if ($start < 0) {
                $output['msg'] = 'Parameter start harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
        }

        if ($limit === NULL) {
            $limit = 10;
        } else {
            if ($limit == -1) {
                // no problem
            } else if ($limit > 1000) {
                $output['msg'] = 'Parameter limit maksimal 1000 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            } else if ($limit < 0) {
                $output['msg'] = 'Parameter limit harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
        }

        $this->load->model('pegawai/pegawai_model');


        $filter_satkers = array();
        if (sizeof($satkers) == 0) { // has ALL PRIV
            $filter_satkers[] = $unor_id;
        } else {
            $found_priv = false;
            foreach ($satkers as $satker) {
                if ($satker == $unor_id) {
                    $found_priv = true;
                }
            }
            if (!$found_priv) {
                $output['msg'] = 'Tidak ada akses untuk pegawai satker ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
                return;
            } else {
                $filter_satkers[] = $unor_id;
            }
        }
        $pegawais=$this->pegawai_model->find_download($filter_satkers);
        $this->db->flush_cache();
        $output = array(
            'success' => true,
            'total' => $total,
            'data' => $pegawais
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
}
