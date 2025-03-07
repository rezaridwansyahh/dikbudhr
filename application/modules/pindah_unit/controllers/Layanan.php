<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Layanan controller
 */
class Layanan extends Admin_Controller
{
    protected $permissionCreate = 'Pindah_unit.Layanan.Create';
    protected $permissionDelete = 'Pindah_unit.Layanan.Delete';
    protected $permissionEdit   = 'Pindah_unit.Layanan.Edit';
    protected $permissionView   = 'Pindah_unit.Layanan.View';
    protected $permissionViewUnitAsal   = 'Pindah_unit.Layanan.UnitAsal';
    protected $permissionViewUnitTujuan   = 'Pindah_unit.Layanan.UnitTujuan';
    protected $permissionViewBiro   = 'Pindah_unit.Layanan.Biro';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        //$this->auth->restrict($this->permissionView);
        $this->load->model('pindah_unit/pindah_unit_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/vw_unit_list_model');
        $this->lang->load('pindah_unit');
        
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'layanan/_sub_nav');

        Assets::add_module_js('pindah_unit', 'pindah_unit.js');
        $this->load->model('pegawai/unitkerja_model');
        Template::set('recsatker', $this->unitkerja_model->find_satker());
    }

    /**
     * Display a list of pindah unit data.
     *
     * @return void
     */
    public function index()
    {
           
        $records = $this->pindah_unit_model->find_all();

        Template::set('records', $records);
        
        Template::set('toolbar_title', "Pegawai Pindah Unit");

        Template::render();
    }
    public function biro()
    {
        
        Template::set('collapse', TRUE);
        Template::set('toolbar_title', "Pegawai Pindah Unit");

        Template::render();
    }
    public function asal()
    {
        $this->auth->restrict($this->permissionViewUnitAsal);   
        Template::set('collapse', TRUE);
        Template::set('toolbar_title', "Pegawai Pindah Unit");

        Template::render();
    }
    public function tujuan()
    {
        $this->auth->restrict($this->permissionViewUnitTujuan);   
        Template::set('collapse', TRUE);
        Template::set('toolbar_title', "Pegawai Pindah Unit");

        Template::render();
    }
    public function sestama()
    {
        
        Template::set('collapse', TRUE);
        Template::set('toolbar_title', "Pegawai Pindah Unit [Sestama]");

        Template::render();
    }
    public function satuunit()
    {
        
        Template::set('collapse', TRUE);
        Template::set('toolbar_title', "Pegawai Pindah Satu Unit");

        Template::render();
    }
    /**
     * Create a pindah unit object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        
        if(!$this->auth->has_permission($this->permissionViewProfileOther)){
            $pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($this->auth->username()));

            $PNS_ID = isset($pegawai->PNS_ID) ? $pegawai->PNS_ID : "";
            $selectedpegawai = $this->pegawai_model->find_by("PNS_ID",trim($PNS_ID));
            Template::set('selectedpegawai',$selectedpegawai);
            //die($id." masuk");
        }


        Template::set('toolbar_title', "Ajukan Pindah Unit");

        Template::render();
    }
    public function createsatu()
    {
        $this->auth->restrict($this->permissionCreate);
        
        if(!$this->auth->has_permission($this->permissionViewProfileOther)){
            $pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($this->auth->username()));

            $PNS_ID = isset($pegawai->PNS_ID) ? $pegawai->PNS_ID : "";
            $selectedpegawai = $this->pegawai_model->find_by("PNS_ID",trim($PNS_ID));
            Template::set('selectedpegawai',$selectedpegawai);
            //die($id." masuk");
        }


        Template::set('toolbar_title', "Ajukan Pindah dalam satu Unit");

        Template::render();
    }
    /**
     * Allows editing of pindah unit data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('pindah_unit_invalid_id'), 'error');

            redirect(SITE_AREA . '/layanan/pindah_unit');
        }
        
        $this->load->model('pegawai/pegawai_model');
        
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('pindah_unit_invalid_id'), 'error');

            redirect(SITE_AREA . '/layanan/pindah_unit');
        }
        $datadetil = $this->pindah_unit_model->find($id);

        $selectedpegawai = $this->pegawai_model->find_by("PNS_ID",trim($datadetil->NIP));
        $jabatan_instansi = isset($selectedpegawai->JABATAN_INSTANSI_ID) ? trim($selectedpegawai->JABATAN_INSTANSI_ID) : "";
        $UNIT_TUJUAN = isset($datadetil->UNIT_TUJUAN) ? trim($datadetil->UNIT_TUJUAN) : "";
        $UNIT_ASAL = isset($datadetil->UNIT_ASAL) ? trim($datadetil->UNIT_ASAL) : "";

        $selectedUnitTujuan = $this->unitkerja_model->find_by_id(TRIM($UNIT_TUJUAN));
        $selectedUnitAsal = $this->unitkerja_model->find_by_id(TRIM($UNIT_ASAL));
        //data SKP
        $this->load->model('pegawai/riwayat_prestasi_kerja_model');
        $this->riwayat_prestasi_kerja_model->where("PNS_ID",$datadetil->NIP);
        $recordppk = $this->riwayat_prestasi_kerja_model->find_all();;
        Template::set('recordppk', $recordppk);

        //die($jabatan_instansi." ad");
        $this->load->model('ref_jabatan/Ref_jabatan_model');
        $detiljabatan = $this->Ref_jabatan_model->find_by("ID_JABATAN",$jabatan_instansi);
        Template::set('detiljabatan', $detiljabatan);
        //print_r($detiljabatan);
        // cek kuota jabatan
        $this->load->model('petajabatan/kuotajabatan_model');
        $kuotajabatan = $this->kuotajabatan_model->find_by_jabatan($UNIT_TUJUAN,$jabatan_instansi);
        Template::set('kuotajabatan', $kuotajabatan);
        // get jumlah pegawai jabatan
        $countpegawai_jabatan = $this->pegawai_model->get_count_jabatan_instansi($UNIT_TUJUAN,$jabatan_instansi);
        //die($countpegawai_jabatan." ads");
        //print_r($kuotajabatan);
        //die();
        Template::set('countpegawai_jabatan', $countpegawai_jabatan);

        Template::set('pindah_unit', $datadetil);
        Template::set('selectedpegawai',$selectedpegawai);
        Template::set('selectedUnitTujuan',$selectedUnitTujuan);
        Template::set('selectedUnitAsal',$selectedUnitAsal);
        Template::set('toolbar_title', "Lihat detil");
        Template::render();
    }
    public function verifikasisestama()
    {
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/unitkerja_model');
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('pindah_unit_invalid_id'), 'error');

            redirect(SITE_AREA . '/layanan/pindah_unit');
        }
        $datadetil = $this->pindah_unit_model->find($id);
        $jabatan_instansi = isset($datadetil->JABATAN_ID) ? $datadetil->JABATAN_ID : "";
        $selectedpegawai = $this->pegawai_model->find_by("PNS_ID",trim($datadetil->NIP));
        //$jabatan_instansi = isset($selectedpegawai->JABATAN_INSTANSI_ID) ? trim($selectedpegawai->JABATAN_INSTANSI_ID) : "";
        $UNIT_TUJUAN = isset($datadetil->UNIT_TUJUAN) ? trim($datadetil->UNIT_TUJUAN) : "";
        $UNIT_ASAL = isset($datadetil->UNIT_ASAL) ? trim($datadetil->UNIT_ASAL) : "";
        //die($UNIT_TUJUAN);
        $selectedUnitTujuan = $this->unitkerja_model->find_by_id(TRIM($UNIT_TUJUAN));
        $selectedUnitAsal = $this->unitkerja_model->find_by_id(TRIM($UNIT_ASAL));
        //data SKP
        $this->load->model('pegawai/riwayat_prestasi_kerja_model');
        $this->riwayat_prestasi_kerja_model->where("PNS_ID",$datadetil->NIP);
        $recordppk = $this->riwayat_prestasi_kerja_model->find_all();;
        Template::set('recordppk', $recordppk);

        //die($jabatan_instansi." ad");
        $this->load->model('ref_jabatan/Ref_jabatan_model');
        $detiljabatan = $this->Ref_jabatan_model->find_by("ID_JABATAN",$jabatan_instansi);
        Template::set('detiljabatan', $detiljabatan);
        //print_r($detiljabatan);
        // cek kuota jabatan
        $this->load->model('petajabatan/kuotajabatan_model');
        $kuotajabatan = $this->kuotajabatan_model->find_by_jabatan($UNIT_TUJUAN,$jabatan_instansi);
        Template::set('kuotajabatan', $kuotajabatan);
        // get jumlah pegawai jabatan
        $countpegawai_jabatan = $this->pegawai_model->get_count_jabatan_instansi($UNIT_TUJUAN,$jabatan_instansi);
        //die($countpegawai_jabatan." ads");
        //print_r($kuotajabatan);
        //die();
        Template::set('countpegawai_jabatan', $countpegawai_jabatan);

        Template::set('pindah_unit', $datadetil);
        Template::set('selectedpegawai',$selectedpegawai);
        Template::set('selectedUnitTujuan',$selectedUnitTujuan);
        Template::set('selectedUnitAsal',$selectedUnitAsal);
        
        Template::set('toolbar_title', "Verifikasi Data");
        Template::render();
    }
    public function detil()
    {
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/unitkerja_model');
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('pindah_unit_invalid_id'), 'error');

            redirect(SITE_AREA . '/layanan/pindah_unit');
        }
        $datadetil = $this->pindah_unit_model->find($id);
        $jabatan_instansi = isset($datadetil->JABATAN_ID) ? $datadetil->JABATAN_ID : "";
        $selectedpegawai = $this->pegawai_model->find_by("PNS_ID",trim($datadetil->NIP));
        //$jabatan_instansi = isset($selectedpegawai->JABATAN_INSTANSI_ID) ? trim($selectedpegawai->JABATAN_INSTANSI_ID) : "";
        $UNIT_TUJUAN = isset($datadetil->UNIT_TUJUAN) ? trim($datadetil->UNIT_TUJUAN) : "";
        $UNIT_ASAL = isset($datadetil->UNIT_ASAL) ? trim($datadetil->UNIT_ASAL) : "";
        //die($UNIT_TUJUAN);
        $selectedUnitTujuan = $this->unitkerja_model->find_by_id(TRIM($UNIT_TUJUAN));
        $selectedUnitAsal = $this->unitkerja_model->find_by_id(TRIM($UNIT_ASAL));
        //data SKP
        $this->load->model('pegawai/riwayat_prestasi_kerja_model');
        $this->riwayat_prestasi_kerja_model->where("PNS_ID",$datadetil->NIP);
        $recordppk = $this->riwayat_prestasi_kerja_model->find_all();;
        Template::set('recordppk', $recordppk);

        //die($jabatan_instansi." ad");
        $this->load->model('ref_jabatan/Ref_jabatan_model');
        $detiljabatan = $this->Ref_jabatan_model->find_by("ID_JABATAN",$jabatan_instansi);
        Template::set('detiljabatan', $detiljabatan);
        //print_r($detiljabatan);
        // cek kuota jabatan
        $this->load->model('petajabatan/kuotajabatan_model');
        $kuotajabatan = $this->kuotajabatan_model->find_by_jabatan($UNIT_TUJUAN,$jabatan_instansi);
        Template::set('kuotajabatan', $kuotajabatan);
        // get jumlah pegawai jabatan
        $countpegawai_jabatan = $this->pegawai_model->get_count_jabatan_instansi($UNIT_TUJUAN,$jabatan_instansi);
        //die($countpegawai_jabatan." ads");
        //print_r($kuotajabatan);
        //die();
        Template::set('countpegawai_jabatan', $countpegawai_jabatan);

        Template::set('pindah_unit', $datadetil);
        Template::set('selectedpegawai',$selectedpegawai);
        Template::set('selectedUnitTujuan',$selectedUnitTujuan);
        Template::set('selectedUnitAsal',$selectedUnitAsal);
        
        Template::set('toolbar_title', "Verifikasi Data");
        Template::render();
    }
    public function viewdetil()
    {
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/unitkerja_model');
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('pindah_unit_invalid_id'), 'error');

            redirect(SITE_AREA . '/layanan/pindah_unit');
        }
        $datadetil = $this->pindah_unit_model->find($id);
        $jabatan_instansi = isset($datadetil->JABATAN_ID) ? $datadetil->JABATAN_ID : "";
        $selectedpegawai = $this->pegawai_model->find_by("PNS_ID",trim($datadetil->NIP));
        $UNIT_TUJUAN = isset($datadetil->UNIT_TUJUAN) ? trim($datadetil->UNIT_TUJUAN) : "";
        $UNIT_ASAL = isset($datadetil->UNIT_ASAL) ? trim($datadetil->UNIT_ASAL) : "";

        $selectedUnitTujuan = $this->unitkerja_model->find_by_id(TRIM($UNIT_TUJUAN));
        $selectedUnitAsal = $this->unitkerja_model->find_by_id(TRIM($UNIT_ASAL));
        //data SKP
        $this->load->model('pegawai/riwayat_prestasi_kerja_model');
        $this->riwayat_prestasi_kerja_model->where("PNS_ID",$datadetil->NIP);
        $recordppk = $this->riwayat_prestasi_kerja_model->find_all();;
        Template::set('recordppk', $recordppk);

        //die($jabatan_instansi." ad");
        $this->load->model('ref_jabatan/Ref_jabatan_model');
        $detiljabatan = $this->Ref_jabatan_model->find_by("ID_JABATAN",$jabatan_instansi);
        Template::set('detiljabatan', $detiljabatan);
        //print_r($detiljabatan);
        // cek kuota jabatan
        $this->load->model('petajabatan/kuotajabatan_model');
        $kuotajabatan = $this->kuotajabatan_model->find_by_jabatan($UNIT_TUJUAN,$jabatan_instansi);
        Template::set('kuotajabatan', $kuotajabatan);
        // get jumlah pegawai jabatan
        $countpegawai_jabatan = $this->pegawai_model->get_count_jabatan_instansi($UNIT_TUJUAN,$jabatan_instansi);
        //die($countpegawai_jabatan." ads");
        //print_r($kuotajabatan);
        //die();
        Template::set('countpegawai_jabatan', $countpegawai_jabatan);

        Template::set('pindah_unit', $datadetil);
        Template::set('selectedpegawai',$selectedpegawai);
        Template::set('selectedUnitTujuan',$selectedUnitTujuan);
        Template::set('selectedUnitAsal',$selectedUnitAsal);
        Template::set('toolbar_title', "Lihat Detil");
        Template::render();
    }

    //--------------------------------------------------------------------------
    // !PRIVATE METHODS
    //--------------------------------------------------------------------------

    /**
     * Save the data.
     *
     * @param string $type Either 'insert' or 'update'.
     * @param int    $id   The ID of the record to update, ignored on inserts.
     *
     * @return boolean|integer An ID for successful inserts, true for successful
     * updates, else false.
     */
    private function save_pindah_unit($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['ID'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->pindah_unit_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->pindah_unit_model->prep_data($this->input->post());

        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->pindah_unit_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->pindah_unit_model->update($id, $data);
        }

        return $return;
    }
    function uploadberkas(){
        $kode = $this->input->post('kode');
        $kolom = $this->input->post('kolom');
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error',
            'namafile'=>''
        );

        $this->load->helper('handle_upload');
        $uploadData = array();
        $upload = true;
        $id = "";
        $namafile = "";
        if (isset($_FILES['userfile']) && is_array($_FILES['userfile']) && $_FILES['userfile']['error'] != 4)
        {
            $tmp_name = pathinfo($_FILES['userfile']['name'], PATHINFO_FILENAME);
            $uploadData = handle_upload('userfile',trim($this->settings_lib->item('site.pathuploaded'))."layanan/");
            //print_r($uploadData);
            if (isset($uploadData['error']) && !empty($uploadData['error']))
            {
                $tipefile=$_FILES['userfile']['type'];
                $upload = false;
                log_activity($this->auth->user_id(), 'Gagal : '.$uploadData['error'].$tipefile.$this->input->ip_address(), 'pindah_unit');
            }else{
                $response['success']= true;
                $response['msg']= "Transaksi berhasil";
                $namafile = $uploadData['data']['file_name'];
            }
        }else{
            die("File tidak ditemukan");
            log_activity($this->auth->user_id(), 'File tidak ditemukan : ' . $this->input->ip_address(), 'pindah_unit');
        }  
        $response['namafile']= $namafile; 
        echo json_encode($response);    
       exit();
    }
    public function saveajuan(){
         // Validate the data
        $this->auth->restrict($this->permissionCreate);
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
        $id = $this->uri->segment(5);
        $this->form_validation->set_rules($this->pindah_unit_model->get_validation_rules());
        $this->form_validation->set_rules('NIP','Silahkan Pilih Pegawai','required|max_length[32]');
        $this->form_validation->set_rules('ID_SATUAN_KERJA','Silahkan Pilih Satuan Kerja','required|max_length[32]');
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = "
            <div class='alert alert-block alert-error fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                    Error
                </h4>
                ".validation_errors()."
            </div>
            ";
            echo json_encode($response);
            exit();
        }
        $this->pegawai_model->where("PNS_ID",$this->input->post("NIP"));
        $pegawai_data = $this->pegawai_model->find_first_row();  
        
        //die($pegawai_data->UNOR_ID." unor id");
        //DIE($this->input->post('ID_SATUAN_KERJA')." TUJUAN");
        $UNIT_TUJUAN = $this->input->post('UNIT_TUJUAN') != "" ? $this->input->post('UNIT_TUJUAN') : $this->input->post('ID_SATUAN_KERJA');
        $data = $this->pindah_unit_model->prep_data($this->input->post()); 
        $data["UNIT_ASAL"] = $pegawai_data->UNOR_ID != "" ? $pegawai_data->UNOR_ID : "";
        $data['UNIT_TUJUAN']    = $UNIT_TUJUAN;
        $data['TANGGAL_SK_PINDAH']    = $this->input->post('TANGGAL_SK_PINDAH') ? $this->input->post('TANGGAL_SK_PINDAH') : null;
        if($id == ""){
            $result = $this->pindah_unit_model->insert($data);
        }else{
            $result = $this->pindah_unit_model->update($id,$data);
        }
        if($result){
            $response ['success']= true;
            $response ['msg']= "Simpan berhasil";
        }
        echo json_encode($response);    

    }
    public function verifikasiajuan(){
         // Validate the data
        $this->auth->restrict($this->permissionCreate);
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
        $id = $this->uri->segment(5);
        $this->form_validation->set_rules($this->pindah_unit_model->get_validation_rules());
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = "
            <div class='alert alert-block alert-error fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                    Error bro
                </h4>
                ".validation_errors()."
            </div>
            ";
            echo json_encode($response);
            exit();
        }
        $NO_SK_PINDAH        = $this->input->post("NO_SK_PINDAH") != "" ? $this->input->post("NO_SK_PINDAH") : "";
        //$data = $this->pindah_unit_model->prep_data($this->input->post()); 
        $data["SURAT_PERMOHONAN_PINDAH"]        = $this->input->post("SURAT_PERMOHONAN_PINDAH") != "" ? $this->input->post("SURAT_PERMOHONAN_PINDAH") : 0;
        $data["SURAT_PERNYATAAN_MELEPAS"]        = $this->input->post("SURAT_PERNYATAAN_MELEPAS") != "" ? $this->input->post("SURAT_PERNYATAAN_MELEPAS") : 0;
        $data["SK_KP_TERAKHIR"]        = $this->input->post("SK_KP_TERAKHIR") != "" ? $this->input->post("SK_KP_TERAKHIR") : 0;
        $data["SK_JABATAN"]        = $this->input->post("SK_JABATAN") != "" ? $this->input->post("SK_JABATAN") : 0;
        $data["SK_TUNKIN"]        = $this->input->post("SK_TUNKIN") != "" ? $this->input->post("SK_TUNKIN") : 0;
        $data["SURAT_PERNYATAAN_MENERIMA"]        = $this->input->post("SURAT_PERNYATAAN_MENERIMA") != "" ? $this->input->post("SURAT_PERNYATAAN_MENERIMA") : 0;
        $data["SKP"]        = $this->input->post("SKP") != "" ? $this->input->post("SKP") : 0;

        $data["KETERANGAN"]        = $this->input->post("KETERANGAN") != "" ? TRIM($this->input->post("KETERANGAN")) : "";

        $data["NO_SK_PINDAH"]        = $this->input->post("NO_SK_PINDAH") != "" ? $this->input->post("NO_SK_PINDAH") : 0;
        $data["TANGGAL_SK_PINDAH"]        = $this->input->post("TANGGAL_SK_PINDAH") != "" ? $this->input->post("TANGGAL_SK_PINDAH") : NULL;
        $data["TANGGAL_TMT_PINDAH"]        = $this->input->post("TANGGAL_TMT_PINDAH") != "" ? $this->input->post("TANGGAL_TMT_PINDAH") : NULL;
        
        $data["FILE_SK"]        = $this->input->post("FILE_SK") != "" ? $this->input->post("FILE_SK") : "";

        $data["STATUS_SATKER"] =    $this->input->post("STATUS_SATKER") != "" ? $this->input->post("STATUS_SATKER") : 0;
        $data["STATUS_BIRO"]        = $this->input->post("STATUS_BIRO") != "" ? $this->input->post("STATUS_BIRO") : 0;
        $result = $this->pindah_unit_model->update($id,$data);
        if($result){
            $response ['success']= true;
            $response ['msg']= "Simpan berhasil";

            if($this->input->post("STATUS_BIRO") == "1" && $NO_SK_PINDAH != ""){
                $this->load->model('pegawai/riwayat_pindah_unit_kerja_model');
                $datarwt = array();
                $datadetil = $this->pindah_unit_model->find($id);
                //print_r($datadetil);
                $PNS_ID = isset($datadetil->NIP) ? trim($datadetil->NIP) : "";
                $UNIT_ASAL = isset($datadetil->UNIT_ASAL) ? TRIM($datadetil->UNIT_ASAL) : "";
                $UNIT_TUJUAN = isset($datadetil->UNIT_TUJUAN) ? TRIM($datadetil->UNIT_TUJUAN) : "";
                $ESELON_2 = isset($datadetil->ESELON_2) ? TRIM($datadetil->ESELON_2) : "";

                $this->pegawai_model->where("PNS_ID",$PNS_ID);
                $pegawai_data = $this->pegawai_model->find_first_row();  
                $pegawai_id = ISSET($pegawai_data->ID) ? $pegawai_data->ID : "";
                $datarwt["PNS_ID"] = $pegawai_data->PNS_ID;
                $datarwt["PNS_NAMA"] = $pegawai_data->NAMA;
                $datarwt["PNS_NIP"] = $pegawai_data->NIP_BARU;
                
                // ASAL
                $unor_asal_data = $this->unitkerja_model->find_by("ID",$UNIT_ASAL);
                $NAMA_UNOR_ASAL = isset($unor_asal_data->NAMA_UNOR) ? $unor_asal_data->NAMA_UNOR : "";
                $datarwt["ASAL_ID"] = $UNIT_ASAL;
                $datarwt["ASAL_NAMA"] = $NAMA_UNOR_ASAL;
                
                // TUJUAN
                $unor_baru_data = $this->vw_unit_list_model->find_by("ID",$UNIT_TUJUAN);
                $datarwt["ID_UNOR_BARU"] = $UNIT_TUJUAN;
                $datarwt["NAMA_UNOR_BARU"] = $unor_baru_data->NAMA_UNOR_FULL;
                $datarwt["ID_SATUAN_KERJA"] = $unor_baru_data->ESELON_2;
                $datarwt["NAMA_SATUAN_KERJA"] = $unor_baru_data->NAMA_UNOR_ESELON_2;
                $datarwt["NAMA_INSTANSI"] = "KEMENTERIAN PENDIDIKAN dan KEBUDAYAAN";
                
                $datarwt['SK_TANGGAL']     = $this->input->post('TANGGAL_SK_PINDAH') ? $this->input->post('TANGGAL_SK_PINDAH') : null;
                $SK_NOMOR = $this->input->post('NO_SK_PINDAH') ? $this->input->post('NO_SK_PINDAH') : null;
                $datarwt['SK_NOMOR']       = $this->input->post('NO_SK_PINDAH') ? $this->input->post('NO_SK_PINDAH') : null;
                $this->riwayat_pindah_unit_kerja_model->where("PNS_ID",$PNS_ID);
                $recordexist = $this->riwayat_pindah_unit_kerja_model->find_by("SK_NOMOR",$SK_NOMOR);
                if(isset($recordexist->ID)){
                    $kode = $recordexist->ID;
                    $this->riwayat_pindah_unit_kerja_model->update($kode,$datarwt);
                }else{
                    $this->riwayat_pindah_unit_kerja_model->insert($datarwt);    
                }
                $UPDATE_PROFILE = $this->input->post('UPDATE_PROFILE') ? $this->input->post('UPDATE_PROFILE') : null;
                if($UPDATE_PROFILE == "1"){
                    $dataprofile = array();
                    $dataprofile['UNOR_ID'] = trim($UNIT_TUJUAN);
                    $this->pegawai_model->update($pegawai_id,$dataprofile);
                }
            }
        }
        echo json_encode($response);    

    }
    public function verifikasiajuansestama(){
         // Validate the data
        $this->auth->restrict($this->permissionCreate);
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
        $id = $this->uri->segment(5);
        $this->form_validation->set_rules($this->pindah_unit_model->get_validation_rules());
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = "
            <div class='alert alert-block alert-error fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                    Error bro
                </h4>
                ".validation_errors()."
            </div>
            ";
            echo json_encode($response);
            exit();
        }
        
        //$data = $this->pindah_unit_model->prep_data($this->input->post()); 
        
        $data["STATUS_SATKER"] =    $this->input->post("STATUS_SATKER") != "" ? $this->input->post("STATUS_SATKER") : 0;
        $result = $this->pindah_unit_model->update($id,$data);
        if($result){
            $response ['success']= true;
            $response ['msg']= "Simpan berhasil";
        }
        echo json_encode($response);    

    }
    public function getdata(){
        $this->auth->restrict($this->permissionView);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/layanan/pindah_unit');
        }
        $this->load->model('pegawai/pegawai_model');

        $this->load->library('convert');
        $convert = new Convert();
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        $pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($this->auth->username()));
        $PNS_ID = isset($pegawai->PNS_ID) ? $pegawai->PNS_ID : "";
       
        $length= $this->input->post('length');
        $start= $this->input->post('start');

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $this->pindah_unit_model->where("NIP",$PNS_ID);
        $total= $this->pindah_unit_model->count_all();;
        $output=array();
        $output['draw']=$draw;

        
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        
        
        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        if($search!=""){
            $this->pindah_unit_model->where('upper("PANGKAT") LIKE \''.strtoupper($search).'%\'');
        }
        $this->pindah_unit_model->limit($length,$start);
        /*Urutkan dari alphabet paling terkahir*/
        
        $kolom = $iSortCol != "" ? $iSortCol : "NIP";
        $sSortCol == "asc" ? "asc" : "desc";
        $this->pindah_unit_model->order_by($iSortCol,$sSortCol);
        $this->pindah_unit_model->order_by("ID","DESC");
        $this->pindah_unit_model->where("NIP",$PNS_ID);
        $records=$this->pindah_unit_model->find_all();
            
        /*Ketika dalam mode pencarian, berarti kita harus
        'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
        yang mengandung keyword tertentu
        */
        if($search != "")
        {
            $this->pindah_unit_model->where('upper("PANGKAT") LIKE \''.strtoupper($search).'%\'');
            $jum    = $this->pindah_unit_model->count_all();
            $output['recordsTotal']=$output['recordsFiltered']=$jum;
        }
        
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $status = array("","Diterima","Proses","Tidak Diterima");
                $STATUS_SATKER  = $record->STATUS_SATKER;
                $STATUS_BIRO    = $record->STATUS_BIRO;
                $msgverifikasi = "";
                $msgverifikasibiro = "";
                if($STATUS_BIRO == "0" OR $STATUS_BIRO == "")
                    $msgverifikasibiro = "<br><b class='text-red'>(Perlu Verifikasi Biro)</b>";
                elseif($STATUS_BIRO == "1")
                    $msgverifikasi = "<br><b class='text-blue'>(Sudah diverifikasi Biro)</b>";

                $status_berkas = "<b>Belum Lengkap</b><br>".trim($record->KETERANGAN);
                if(trim($record->SURAT_PERNYATAAN_MELEPAS) == "1" and trim($record->SK_KP_TERAKHIR) == "1" and trim($record->SK_JABATAN) == "1" and trim($record->SK_TUNKIN) == "1" and trim($record->SURAT_PERNYATAAN_MENERIMA) == "1"){
                    $status_berkas = "<b>Lengkap</b>";
                }
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->NAMA;
                $row []  = $record->NAMA_UNOR;
                $row []  = $record->NAMA_UNOR_TUJUAN;
                $row []  = $status_berkas;

                //$row []  = $msgverifikasi.$msgverifikasibiro;
                $row []  = isset($status[$record->STATUS_BIRO]) ? "<b>".$status[$record->STATUS_BIRO]."</b>"."<br>".trim($record->KETERANGAN) : $msgverifikasibiro;
                $btn_actions = array();
                $btn_actions  [] = "
                    <a class='show-modal-custom' href='".base_url()."admin/layanan/pindah_unit/viewdetil/".$record->ID."'  data-toggle='modal' title='Lihat detil'><span class='fa-stack'>
                        <i class='fa fa-square fa-stack-2x'></i>
                        <i class='fa fa-eye fa-stack-1x fa-inverse'></i>
                        </span>
                        </a>
                ";
                
                if($this->auth->has_permission($this->permissionDelete)){
                $btn_actions  [] = "
                        <a href='#' kode='$record->ID' class='btn-hapus' data-toggle='tooltip' title='Hapus data' >
                        <span class='fa-stack'>
                        <i class='fa fa-square fa-stack-2x'></i>
                        <i class='fa fa-trash-o fa-stack-1x fa-inverse'></i>
                        </span>
                        </a>
                ";
                }
                $row[] = implode(" ",$btn_actions);
                

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        die();
    }
    public function getdatabiro(){
        $this->auth->restrict($this->permissionViewBiro);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/layanan/pindah_unit');
        }

        $this->load->library('convert');
        $convert = new convert();
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
       
        $length= $this->input->post('length');
        $start= $this->input->post('start');

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $filter_unit_kerja = $this->input->post('filter_unit_kerja');
        $filter_status = $this->input->post('filter_status');

        $total= $this->pindah_unit_model->count_all();;
        $output=array();
        $output['draw']=$draw;

        
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        
        
        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        if($filter_status!=""){
            $this->pindah_unit_model->where("STATUS_BIRO",$filter_status);
        }
        if($filter_unit_kerja != "")
        {
             $this->pindah_unit_model->db->group_start();
                $this->pindah_unit_model->db->where("UT.ID",$filter_unit_kerja);
                $this->pindah_unit_model->db->or_where("UT.ESELON_1",$filter_unit_kerja);
                $this->pindah_unit_model->db->or_where("UT.ESELON_2",$filter_unit_kerja);
                $this->pindah_unit_model->db->or_where("UT.ESELON_3",$filter_unit_kerja);
                $this->pindah_unit_model->db->or_where("UT.ESELON_4",$filter_unit_kerja);

                $this->pindah_unit_model->db->or_where("UA.ESELON_1",$filter_unit_kerja);
                $this->pindah_unit_model->db->or_where("UA.ESELON_2",$filter_unit_kerja);
                $this->pindah_unit_model->db->or_where("UA.ESELON_3",$filter_unit_kerja);
                $this->pindah_unit_model->db->or_where("UA.ESELON_4",$filter_unit_kerja);
            $this->pindah_unit_model->db->group_end();
        }
        $this->pindah_unit_model->limit($length,$start);
        /*Urutkan dari alphabet paling terkahir*/
        
        $kolom = $iSortCol != "" ? $iSortCol : "NIP";
        $sSortCol == "asc" ? "asc" : "desc";
        $this->pindah_unit_model->order_by($iSortCol,$sSortCol);
        $this->pindah_unit_model->order_by("ID","DESC");
        
        $records=$this->pindah_unit_model->find_all();
            
        /*Ketika dalam mode pencarian, berarti kita harus
        'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
        yang mengandung keyword tertentu
        */
        if($filter_status != "")
        {
            $this->pindah_unit_model->where("STATUS_BIRO",$filter_status);
        }
        if($filter_unit_kerja != "")
        {
             $this->pindah_unit_model->db->group_start();
                $this->pindah_unit_model->db->where("UT.ID",$filter_unit_kerja);
                $this->pindah_unit_model->db->or_where("UT.ESELON_1",$filter_unit_kerja);
                $this->pindah_unit_model->db->or_where("UT.ESELON_2",$filter_unit_kerja);
                $this->pindah_unit_model->db->or_where("UT.ESELON_3",$filter_unit_kerja);
                $this->pindah_unit_model->db->or_where("UT.ESELON_4",$filter_unit_kerja);

                $this->pindah_unit_model->db->or_where("UA.ESELON_1",$filter_unit_kerja);
                $this->pindah_unit_model->db->or_where("UA.ESELON_2",$filter_unit_kerja);
                $this->pindah_unit_model->db->or_where("UA.ESELON_3",$filter_unit_kerja);
                $this->pindah_unit_model->db->or_where("UA.ESELON_4",$filter_unit_kerja);
            $this->pindah_unit_model->db->group_end();
        }
        $jum    = $this->pindah_unit_model->count_all();
            $output['recordsTotal']=$output['recordsFiltered']=$jum;
        
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $status = array("","Diterima","Proses","Tidak Diterima");
                $STATUS_SATKER  = $record->STATUS_SATKER;
                $STATUS_BIRO    = $record->STATUS_BIRO;
                $msgverifikasi = "";
                $msgverifikasibiro = "";
                if($STATUS_SATKER == "0" OR $STATUS_SATKER == "")
                    $msgverifikasi = "<b class='text-red'>(Perlu Verifikasi Satker)</b>";
                if($STATUS_BIRO == "0" OR $STATUS_BIRO == "")
                    $msgverifikasibiro = "<br><b class='text-red'>(Perlu Verifikasi Biro)</b>";
                elseif($STATUS_BIRO == "1")
                    $msgverifikasi = "<br><b class='text-blue'>(Sudah diverifikasi Biro)</b>";

                $status_berkas = "<b>Belum Lengkap</b><br>".trim($record->KETERANGAN);
                if(trim($record->SURAT_PERNYATAAN_MELEPAS) == "1" and trim($record->SK_KP_TERAKHIR) == "1" and trim($record->SK_JABATAN) == "1" and trim($record->SK_TUNKIN) == "1" and trim($record->SURAT_PERNYATAAN_MENERIMA) == "1"){
                    $status_berkas = "<b>Lengkap</b>";
                }
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->NAMA;
                $row []  = $record->NAMA_UNOR;
                $row []  = $record->NAMA_UNOR_TUJUAN;
                $row []  = $status_berkas;

                //$row []  = $msgverifikasi.$msgverifikasibiro;
                $row []  = isset($status[$record->STATUS_BIRO]) ? "<b>".$status[$record->STATUS_BIRO]."</b>"."<br>".trim($record->KETERANGAN) : $msgverifikasibiro;
                $btn_actions = array();
                $btn_actions  [] = "
                    <a class='show-modal-custom' href='".base_url()."admin/layanan/pindah_unit/detil/".$record->ID."'  data-toggle='modal' title='Lihat detil'><span class='fa-stack'>
                        <i class='fa fa-square fa-stack-2x'></i>
                        <i class='fa fa-eye fa-stack-1x fa-inverse'></i>
                        </span>
                        </a>
                ";
                if($this->auth->has_permission($this->permissionEdit)){
                $btn_actions  [] = "
                        <a href='".base_url()."admin/layanan/pindah_unit/edit/".$record->ID."'  data-toggle='modal' title='Edit data'><span class='fa-stack'>
                        <i class='fa fa-square fa-stack-2x'></i>
                        <i class='fa fa-edit fa-stack-1x fa-inverse'></i>
                        </span>
                        </a>
                ";
                }
                if($this->auth->has_permission($this->permissionDelete)){
                $btn_actions  [] = "
                        <a href='#' kode='$record->ID' class='btn-hapus' data-toggle='tooltip' title='Hapus data' >
                        <span class='fa-stack'>
                        <i class='fa fa-square fa-stack-2x'></i>
                        <i class='fa fa-trash-o fa-stack-1x fa-inverse'></i>
                        </span>
                        </a>
                ";
                }
                $row[] = implode(" ",$btn_actions);
                

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        die();
    }
    public function getdataasal(){
        $this->auth->restrict($this->permissionViewUnitAsal);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/layanan/pindah_unit');
        }
        $UNIT_ASAL = $this->pegawai_model->getunor_induk($this->auth->username());

        $this->load->library('convert');
        $convert = new convert();
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
       
        $length= $this->input->post('length');
        $start= $this->input->post('start');

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $keyword = $this->input->post('keyword');
        $filter_status = $this->input->post('filter_status');

        $this->pindah_unit_model->where('(UA."ESELON_1" = \''.$UNIT_ASAL.'\' OR UA."ESELON_2" = \''.$UNIT_ASAL.'\' OR UA."ESELON_3" = \''.$UNIT_ASAL.'\' OR UA."ESELON_4" = \''.$UNIT_ASAL.'\')');
        $total= $this->pindah_unit_model->count_all();;
        $output=array();
        $output['draw']=$draw;

        
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        
        
        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        $this->pindah_unit_model->where('(UA."ESELON_1" = \''.$UNIT_ASAL.'\' OR UA."ESELON_2" = \''.$UNIT_ASAL.'\' OR UA."ESELON_3" = \''.$UNIT_ASAL.'\' OR UA."ESELON_4" = \''.$UNIT_ASAL.'\')');
        if($search!=""){
            $this->pindah_unit_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
        }

        if($filter_status!=""){
            $this->pindah_unit_model->where("STATUS_BIRO",$filter_status);
        }
        if($keyword!=""){
            $this->pindah_unit_model->where('upper("NAMA") LIKE \''.strtoupper($keyword).'%\'');
        }
        $this->pindah_unit_model->limit($length,$start);
        /*Urutkan dari alphabet paling terkahir*/
        
        $kolom = $iSortCol != "" ? $iSortCol : "NIP";
        $sSortCol == "asc" ? "asc" : "desc";
        $this->pindah_unit_model->order_by($iSortCol,$sSortCol);
        $this->pindah_unit_model->order_by("ID","DESC");
        
        $records=$this->pindah_unit_model->find_all();
            
        /*Ketika dalam mode pencarian, berarti kita harus
        'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
        yang mengandung keyword tertentu
        */

        if($search != "")
        {
            $this->pindah_unit_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
            $this->pindah_unit_model->where('(UA."ESELON_1" = \''.$UNIT_ASAL.'\' OR UA."ESELON_2" = \''.$UNIT_ASAL.'\' OR UA."ESELON_3" = \''.$UNIT_ASAL.'\' OR UA."ESELON_4" = \''.$UNIT_ASAL.'\')');
            
        }
       
        if($filter_status!=""){
            $this->pindah_unit_model->where("STATUS_BIRO",$filter_status);
        }
        if($keyword!=""){
            $this->pindah_unit_model->where('upper("NAMA") LIKE \''.strtoupper($keyword).'%\'');
        }
        $jum    = $this->pindah_unit_model->count_all();
            $output['recordsTotal']=$output['recordsFiltered']=$jum;
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $status = array("","Diterima","Proses","Tidak Diterima");
                $STATUS_SATKER  = $record->STATUS_SATKER;
                $STATUS_BIRO    = $record->STATUS_BIRO;
                $msgverifikasi = "";
                $msgverifikasibiro = "";
                if($STATUS_BIRO == "0" OR $STATUS_BIRO == "")
                    $msgverifikasibiro = "<br><b class='text-red'>(Perlu Verifikasi Biro)</b>";
                elseif($STATUS_BIRO == "1")
                    $msgverifikasi = "<br><b class='text-blue'>(Sudah diverifikasi Biro)</b>";

                $status_berkas = "<b>Belum Lengkap</b>";
                if(trim($record->SURAT_PERNYATAAN_MELEPAS) == "1" and trim($record->SK_KP_TERAKHIR) == "1" and trim($record->SK_JABATAN) == "1" and trim($record->SK_TUNKIN) == "1" and trim($record->SURAT_PERNYATAAN_MENERIMA) == "1"){
                    $status_berkas = "<b>Lengkap</b>";
                }
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->NAMA;
                $row []  = $record->NAMA_UNOR;
                $row []  = $record->NAMA_UNOR_TUJUAN;
                $row []  = $status_berkas;

                //$row []  = $msgverifikasi.$msgverifikasibiro;
                $row []  = isset($status[$record->STATUS_BIRO]) ? "<b>".$status[$record->STATUS_BIRO]."</b>"."<br>".trim($record->KETERANGAN) : $msgverifikasibiro;
                $btn_actions = array();
                $btn_actions  [] = "
                    <a class='show-modal-custom' href='".base_url()."admin/layanan/pindah_unit/viewdetil/".$record->ID."'  data-toggle='modal' title='Lihat detil'><span class='fa-stack'>
                        <i class='fa fa-square fa-stack-2x'></i>
                        <i class='fa fa-eye fa-stack-1x fa-inverse'></i>
                        </span>
                        </a>
                ";
                 
                 
                $row[] = implode(" ",$btn_actions);
                

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        die();
    }
    public function getdatatujuan(){
        $this->auth->restrict($this->permissionViewUnitTujuan);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/layanan/pindah_unit');
        }
        $UNOR_ID = $this->pegawai_model->getunor_induk($this->auth->username());

        $this->load->library('convert');
        $convert = new convert();
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
       
        $length= $this->input->post('length');
        $start= $this->input->post('start');

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $keyword = $this->input->post('keyword');
        $filter_status = $this->input->post('filter_status');

        $this->pindah_unit_model->where('(UT."ESELON_1" = \''.$UNOR_ID.'\' OR UT."ESELON_2" = \''.$UNOR_ID.'\' OR UT."ESELON_3" = \''.$UNOR_ID.'\' OR UT."ESELON_4" = \''.$UNOR_ID.'\')');
        $total= $this->pindah_unit_model->count_all();;
        $output=array();
        $output['draw']=$draw;

        
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        
        
        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        $this->pindah_unit_model->where('(UT."ESELON_1" = \''.$UNOR_ID.'\' OR UT."ESELON_2" = \''.$UNOR_ID.'\' OR UT."ESELON_3" = \''.$UNOR_ID.'\' OR UT."ESELON_4" = \''.$UNOR_ID.'\')');
        if($search!=""){
            $this->pindah_unit_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
        }
        if($filter_status!=""){
            $this->pindah_unit_model->where("STATUS_BIRO",$filter_status);
        }
        if($keyword!=""){
            $this->pindah_unit_model->where('upper("NAMA") LIKE \''.strtoupper($keyword).'%\'');
        }
        $this->pindah_unit_model->limit($length,$start);
        /*Urutkan dari alphabet paling terkahir*/
        
        $kolom = $iSortCol != "" ? $iSortCol : "NIP";
        $sSortCol == "asc" ? "asc" : "desc";
        $this->pindah_unit_model->order_by($iSortCol,$sSortCol);
        $this->pindah_unit_model->order_by("ID","DESC");
        
        $records=$this->pindah_unit_model->find_all();
            
        /*Ketika dalam mode pencarian, berarti kita harus
        'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
        yang mengandung keyword tertentu
        */

        if($search != "")
        {
            $this->pindah_unit_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
            $this->pindah_unit_model->where('(UT."ESELON_1" = \''.$UNOR_ID.'\' OR UT."ESELON_2" = \''.$UNOR_ID.'\' OR UT."ESELON_3" = \''.$UNOR_ID.'\' OR UT."ESELON_4" = \''.$UNOR_ID.'\')');
            
        }
        if($filter_status!=""){
            $this->pindah_unit_model->where("STATUS_BIRO",$filter_status);
        }
        if($keyword!=""){
            $this->pindah_unit_model->where('upper("NAMA") LIKE \''.strtoupper($keyword).'%\'');
        }
        $jum    = $this->pindah_unit_model->count_all();
        $output['recordsTotal']=$output['recordsFiltered']=$jum;

        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $status = array("","Diterima","Proses","Tidak Diterima");
                $STATUS_SATKER  = $record->STATUS_SATKER;
                $STATUS_BIRO    = $record->STATUS_BIRO;
                $msgverifikasi = "";
                $msgverifikasibiro = "";
                if($STATUS_BIRO == "0" OR $STATUS_BIRO == "")
                    $msgverifikasibiro = "<br><b class='text-red'>(Perlu Verifikasi Biro)</b>";
                elseif($STATUS_BIRO == "1")
                    $msgverifikasi = "<br><b class='text-blue'>(Sudah diverifikasi Biro)</b>";

                $status_berkas = "<b>Belum Lengkap</b><br>".trim($record->KETERANGAN);
                if(trim($record->SURAT_PERNYATAAN_MELEPAS) == "1" and trim($record->SK_KP_TERAKHIR) == "1" and trim($record->SK_JABATAN) == "1" and trim($record->SK_TUNKIN) == "1" and trim($record->SURAT_PERNYATAAN_MENERIMA) == "1"){
                    $status_berkas = "<b>Lengkap</b>";
                }
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->NAMA;
                $row []  = $record->NAMA_UNOR;
                $row []  = $record->NAMA_UNOR_TUJUAN;
                $row []  = $status_berkas;

                //$row []  = $msgverifikasi.$msgverifikasibiro;
                $row []  = isset($status[$record->STATUS_BIRO]) ? $status[$record->STATUS_BIRO] : $msgverifikasi.$msgverifikasibiro;
                $btn_actions = array();
                $btn_actions  [] = "
                    <a class='show-modal-custom' href='".base_url()."admin/layanan/pindah_unit/viewdetil/".$record->ID."'  data-toggle='modal' title='Lihat detil'><span class='fa-stack'>
                        <i class='fa fa-square fa-stack-2x'></i>
                        <i class='fa fa-eye fa-stack-1x fa-inverse'></i>
                        </span>
                        </a>
                ";
                
                if($this->auth->has_permission($this->permissionDelete)){
                $btn_actions  [] = "
                        <a href='#' kode='$record->ID' class='btn-hapus' data-toggle='tooltip' title='Hapus data' >
                        <span class='fa-stack'>
                        <i class='fa fa-square fa-stack-2x'></i>
                        <i class='fa fa-trash-o fa-stack-1x fa-inverse'></i>
                        </span>
                        </a>
                ";
                }
                $row[] = implode(" ",$btn_actions);
                

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        die();
    }
    public function getdatasestama(){
        $this->auth->restrict($this->permissionViewUnitAsal);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/layanan/pindah_unit');
        }
        $UNIT_ASAL = $this->pegawai_model->getunor_eselon1($this->auth->username());

        $this->load->library('convert');
        $convert = new convert();
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
       
        $length= $this->input->post('length');
        $start= $this->input->post('start');

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $this->pindah_unit_model->where('(UA."ESELON_1" = \''.$UNIT_ASAL.'\')');
        $total= $this->pindah_unit_model->count_all();;
        $output=array();
        $output['draw']=$draw;

        
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        
        
        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        $this->pindah_unit_model->where('(UA."ESELON_1" = \''.$UNIT_ASAL.'\')');
        if($search!=""){
            $this->pindah_unit_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
        }
        $this->pindah_unit_model->limit($length,$start);
        /*Urutkan dari alphabet paling terkahir*/
        
        $kolom = $iSortCol != "" ? $iSortCol : "NIP";
        $sSortCol == "asc" ? "asc" : "desc";
        $this->pindah_unit_model->order_by($iSortCol,$sSortCol);
        $this->pindah_unit_model->order_by("ID","DESC");
        
        $records=$this->pindah_unit_model->find_all();
            
        /*Ketika dalam mode pencarian, berarti kita harus
        'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
        yang mengandung keyword tertentu
        */

        if($search != "")
        {
            $this->pindah_unit_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
            $this->pindah_unit_model->where('(UA."ESELON_1" = \''.$UNIT_ASAL.'\')');
            $jum    = $this->pindah_unit_model->count_all();
            $output['recordsTotal']=$output['recordsFiltered']=$jum;
        }
        
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $status = array("","Diterima","Proses","Tidak Diterima");
                $STATUS_SATKER  = $record->STATUS_SATKER;
                $STATUS_BIRO    = $record->STATUS_BIRO;
                $msgverifikasi = "";
                $msgverifikasibiro = "";
                if($STATUS_SATKER == "0" OR $STATUS_SATKER == "")
                    $msgverifikasi = "<b class='text-red'>(Perlu Verifikasi Satker)</b>";
                if($STATUS_BIRO == "0" OR $STATUS_BIRO == "")
                    $msgverifikasibiro = "<br><b class='text-red'>(Perlu Verifikasi Biro)</b>";
                elseif($STATUS_BIRO == "1")
                    $msgverifikasi = "<br><b class='text-blue'>(Sudah diverifikasi Biro)</b>";

                $status_berkas = "<b>Belum Lengkap</b><br>".trim($record->KETERANGAN);
                if(trim($record->SURAT_PERNYATAAN_MELEPAS) == "1" and trim($record->SK_KP_TERAKHIR) == "1" and trim($record->SK_JABATAN) == "1" and trim($record->SK_TUNKIN) == "1" and trim($record->SURAT_PERNYATAAN_MENERIMA) == "1"){
                    $status_berkas = "<b>Lengkap</b>";
                }
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->NAMA;
                $row []  = $record->NAMA_UNOR;
                $row []  = $record->NAMA_UNOR_TUJUAN;
                $row []  = $status_berkas;

                //$row []  = $msgverifikasi.$msgverifikasibiro;
                $row []  = isset($status[$record->STATUS_BIRO]) ? $status[$record->STATUS_BIRO] : $msgverifikasi.$msgverifikasibiro;
                $btn_actions = array();
                $btn_actions  [] = "
                    <a class='show-modal-custom' href='".base_url()."admin/layanan/pindah_unit/verifikasisestama/".$record->ID."'  data-toggle='modal' title='Lihat detil'><span class='fa-stack'>
                        <i class='fa fa-square fa-stack-2x'></i>
                        <i class='fa fa-eye fa-stack-1x fa-inverse'></i>
                        </span>
                        </a>
                ";
                 
                 
                $row[] = implode(" ",$btn_actions);
                

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        die();
    }
    public function deletedata()
    {
        $this->auth->restrict($this->permissionDelete);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/layanan/pindah_unit/biro');
        }
        $id     = $this->input->post('kode');
        if ($this->pindah_unit_model->delete($id)) {
             log_activity($this->auth->user_id(),"Delete data" . ': ' . $id . ' : ' . $this->input->ip_address(), 'pindah_unit');
             Template::set_message("Delete data pindah unitkerja sukses", 'success');
             echo "Sukses";
        }else{
            echo "Gagal";
        }

        exit();
    }
	private function gen_qrcode($ref){
		$this->load->library('phpqrcode_lib');
        //$ref = uniqid("sk_kgb");
        $barcode_file = $ref.".png";
        $barcode_file_path = APPPATH."..".DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."qrcodes".DIRECTORY_SEPARATOR."".$barcode_file; 
        $qr_data = base_url("pindah_unit/services/detail/".$ref);
        QRcode::png($qr_data, $barcode_file_path, 'L', 4, 2);
        return $barcode_file_path;
	}
    public function cetak_sk(){
        $this->load->helper('dikbud');
        $this->load->model('pegawai/Pns_aktif_model');
        $this->load->model('ref_jabatan/Ref_jabatan_model');
        $this->load->model('pegawai/unitkerja_model');
        $this->load->model('pegawai/lokasi_model');
        $this->load->model('pegawai/pendidikan_model');
        $this->load->model('pegawai/golongan_model');
        $convert = new Convert();
        $id = $this->uri->segment(5);
        $datadetil = $this->pindah_unit_model->find($id);
        //die($datadetil->ref." ref");
        $jabatan_instansi   = isset($datadetil->JABATAN_ID) ? trim($datadetil->JABATAN_ID) : ""; 
        $PNS_ID             = isset($datadetil->NIP) ? trim($datadetil->NIP) : "";
        $UNIT_TUJUAN             = isset($datadetil->UNIT_TUJUAN) ? trim($datadetil->UNIT_TUJUAN) : "";
        $UNIT_ASAL             = isset($datadetil->UNIT_TUJUAN) ? trim($datadetil->UNIT_ASAL) : "";

        $selectedpegawai = $this->pegawai_model->find_by("PNS_ID",trim($PNS_ID));
        $ID = isset($selectedpegawai->ID) ? trim($selectedpegawai->ID) : "";

        $recpns_aktif = $this->Pns_aktif_model->find($ID);
        $masa_kerja_th = isset($recpns_aktif->masa_kerja_th) ? trim($recpns_aktif->masa_kerja_th) : "";
        $masa_kerja_bl = isset($recpns_aktif->masa_kerja_bl) ? trim($recpns_aktif->masa_kerja_bl) : "";

        $NIP_BARU       = isset($selectedpegawai->NIP_BARU) ? trim($selectedpegawai->NIP_BARU) : "";
        $NAMA           = isset($selectedpegawai->NAMA) ? trim($selectedpegawai->NAMA) : "";
        $GELAR_DEPAN    = isset($selectedpegawai->GELAR_DEPAN) ? trim($selectedpegawai->GELAR_DEPAN) : "";
        $GELAR_BELAKANG = isset($selectedpegawai->GELAR_BELAKANG) ? trim($selectedpegawai->GELAR_BELAKANG) : "";

        $TEMPAT_LAHIR_ID   = isset($selectedpegawai->TEMPAT_LAHIR_ID) ? trim($selectedpegawai->TEMPAT_LAHIR_ID) : "";
        $TGL_LAHIR      = isset($selectedpegawai->TGL_LAHIR) ? $convert->fmtDate(trim($selectedpegawai->TGL_LAHIR),"dd month yyyy") : "";
        $PENDIDIKAN_ID  = isset($selectedpegawai->PENDIDIKAN_ID) ? trim($selectedpegawai->PENDIDIKAN_ID) : "";
        $TAHUN_LULUS    = isset($selectedpegawai->TAHUN_LULUS) ? trim($selectedpegawai->TAHUN_LULUS) : "";
        $JABATAN_INSTANSI_ID       = isset($selectedpegawai->JABATAN_INSTANSI_ID) ? trim($selectedpegawai->JABATAN_INSTANSI_ID) : "";
        $GOL_ID = isset($selectedpegawai->GOL_ID) ? trim($selectedpegawai->GOL_ID) : "";
        $TMT_GOLONGAN = isset($selectedpegawai->TMT_GOLONGAN) ? $convert->fmtDate(trim($selectedpegawai->TMT_GOLONGAN),"dd month yyyy") : "";


        $NO_SK_PINDAH = isset($datadetil->NO_SK_PINDAH) ? trim($datadetil->NO_SK_PINDAH) : "";
        $TANGGAL_SK_PINDAH = isset($datadetil->TANGGAL_SK_PINDAH) ? $convert->fmtDate(trim($datadetil->TANGGAL_SK_PINDAH),"dd month yyyy") : "";
        $TANGGAL_TMT_PINDAH = isset($datadetil->TANGGAL_TMT_PINDAH) ? $convert->fmtDate($datadetil->TANGGAL_TMT_PINDAH,"dd month yyyy") : "";

        
        
        // UNIT TUJUAN
        $selectedUnitTujuan = $this->unitkerja_model->find_by_id(TRIM($UNIT_TUJUAN));
        $ESELON_4_BARU = isset($selectedUnitTujuan->NAMA_UNOR_ESELON_4) ? trim($selectedUnitTujuan->NAMA_UNOR_ESELON_4) : "";
        $ESELON_3_BARU = isset($selectedUnitTujuan->NAMA_UNOR_ESELON_3) ? trim($selectedUnitTujuan->NAMA_UNOR_ESELON_3) : "";
        $ESELON_2_BARU = isset($selectedUnitTujuan->NAMA_UNOR_ESELON_2) ? trim($selectedUnitTujuan->NAMA_UNOR_ESELON_2) : "";
        $ESELON_1_BARU = isset($selectedUnitTujuan->NAMA_UNOR_ESELON_1) ? trim($selectedUnitTujuan->NAMA_UNOR_ESELON_1) : "";
        // UNIT ASAL
        $selectedUnitAsal = $this->unitkerja_model->find_by_id(TRIM($UNIT_ASAL));
        $ESELON_4_LAMA = isset($selectedUnitAsal->NAMA_UNOR_ESELON_4) ? trim($selectedUnitAsal->NAMA_UNOR_ESELON_4) : "";
        $ESELON_3_LAMA = isset($selectedUnitAsal->NAMA_UNOR_ESELON_3) ? trim($selectedUnitAsal->NAMA_UNOR_ESELON_3) : "";
        $ESELON_2_LAMA = isset($selectedUnitAsal->NAMA_UNOR_ESELON_2) ? trim($selectedUnitAsal->NAMA_UNOR_ESELON_2) : "";
        $ESELON_1_LAMA = isset($selectedUnitAsal->NAMA_UNOR_ESELON_1) ? trim($selectedUnitAsal->NAMA_UNOR_ESELON_1) : "";

        // jabatan BARU
        $detiljabatan               = $this->Ref_jabatan_model->find_by("ID_JABATAN",$jabatan_instansi);
        $KELAS_JABATAN              = isset($detiljabatan->KELAS) ? $detiljabatan->KELAS : "";
        $KELAS_JABATAN_TERBILANG    = $convert->Terbilang($KELAS_JABATAN);

        $NAMA_JABATAN_BARU          = isset($detiljabatan->NAMA_JABATAN) ? $detiljabatan->NAMA_JABATAN : "";
        // TUNJANGAN jabatan
        $TUNJANGAN                  = isset($detiljabatan->TUNJANGAN) ? $detiljabatan->TUNJANGAN : 0;
        $TUNJANGAN_TERBILANG        = $convert->Terbilang($TUNJANGAN);

        // JABATAN LAMA
        $detiljabatanlama            = $this->Ref_jabatan_model->find_by("ID_JABATAN",$JABATAN_INSTANSI_ID);
        $NAMA_JABATAN_LAMA           = isset($detiljabatanlama->NAMA_JABATAN) ? $detiljabatanlama->NAMA_JABATAN : "";

        // lokasi
        $detil_lokasi_lahir = $this->lokasi_model->find($TEMPAT_LAHIR_ID);
        $TEMPAT_LAHIR = ISSET($detil_lokasi_lahir->NAMA) ? $detil_lokasi_lahir->NAMA : "";

        // pendidikan
        $recpendidikan = $this->pendidikan_model->find($PENDIDIKAN_ID);
        $PENDIDIKAN_NAMA = isset($recpendidikan->NAMA) ? $recpendidikan->NAMA : "";

        // golongan
        $recgolongan = $this->golongan_model->find(trim($GOL_ID));
        $GOLONGAN = ISSET($recgolongan->NAMA) ? $recgolongan->NAMA_PANGKAT." ".$recgolongan->NAMA : "";


        $this->load->library('Template_doc_lib');
        //$TBS = $this->template_doc_lib->gen_xls(BASEPATH."../../assets/templates/".$template_data->template_file);
        $TBS = $this->template_doc_lib->gen_xls(BASEPATH."../../assets/templates/template_mutasi.docx");
        $tembusan_data = array();
        $tembusan_data [] = 'Kepala BKN, u.p. Deputi Bidang Sistem Informasi Kepegawaian';
        $tembusan_data [] = 'Kepala Kantor Tata Usaha Anggaran, Kementerian Keuangan';
        $tembusan_data [] = 'Pegawai yang bersangkutan';
        $TBS->MergeBlock('tembusan', $tembusan_data);
        $sk_data = array(
            'p_nip'=>$NIP_BARU,
            'p_nama'=>$GELAR_DEPAN." ".$NAMA." ".$GELAR_BELAKANG,
            'p_tampat_lahir'=>$TEMPAT_LAHIR,
            'p_tgl_lahir'=>$TGL_LAHIR,
            
            'p_pendidikan_terakhir'=>$PENDIDIKAN_NAMA,
            'p_p_tahun'=>$TAHUN_LULUS,

            'p_pangkat'=>$GOLONGAN,
            'p_tmt_gol'=>$TMT_GOLONGAN,

            'p_mk_tahun'=>$masa_kerja_th,
            'p_mk_bulan'=>$masa_kerja_bl,


            'kelas_jabatan'=>$KELAS_JABATAN,
            'kelas_jabatan_kata'=>$KELAS_JABATAN_TERBILANG,
            'kelas_jabatan_rp'=>$convert->ToRpnosimbol($TUNJANGAN),
            'kelas_jabatan_rp_kata'=>$TUNJANGAN_TERBILANG,

            'no_sk'=>$NO_SK_PINDAH,
            'tgl_sk'=>$TANGGAL_SK_PINDAH,
            'lokasi_sk'=>'',
            'no_surat_usulan'=>'',
            'tgl_surat_usulan'=>'',
            
            'p_jabatan_lama'=>$NAMA_JABATAN_LAMA,
            'p_eselon_4_lama'=>$ESELON_4_LAMA,
            'p_eselon_3_lama'=>$ESELON_3_LAMA,
            'p_satker_lama'=>$ESELON_2_LAMA,
            'p_eselon_1_lama'=>$ESELON_1_LAMA,

            'p_eselon_1_baru'=>$ESELON_1_BARU,
            'p_satker_baru'=>$ESELON_2_BARU,
            'p_eselon_3_baru'=>$ESELON_3_BARU,
            'p_eselon_4_baru'=>$ESELON_4_BARU,
            'p_jabatan_baru'=>$NAMA_JABATAN_BARU,
			'foto'=>$this->gen_qrcode($datadetil->ID),	
        );
        $TBS->MergeField('r', $sk_data);
        $output_file_name = "SK";
        if($tipe=="pdf"){
            $dir = APPPATH.'../assets/kgb'.DIRECTORY_SEPARATOR;
            $fullpath_docx =  APPPATH.'../assets/kgb'.DIRECTORY_SEPARATOR.$output_file_name.".docx";
            $fullpath_pdf =  APPPATH.'../assets/kgb'.DIRECTORY_SEPARATOR.$output_file_name.".pdf";

            $TBS->Show(OPENTBS_FILE,$fullpath_docx);
            //echo shell_exec("export HOME=/tmp && libreoffice --invisible --nologo --headless --convert-to pdf $fullpath_docx --outdir ./tmp $dir");
            shell_exec($dir."/script.sh");
            echo $dir."/script.sh";
            exec($dir."/script.sh");
            echo shell_exec("echo 123");
            echo "libreoffice --headless --convert-to pdf $fullpath_docx ";
            echo "oke";
            die("masuk");
            //$output_file_name = $output_file_name.".docx";
            //$TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields
            
        }
        else {
            $output_file_name = $output_file_name.".docx";
            $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.  
        }
        
    }
}