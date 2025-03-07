<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Reports controller
 */
class Reports extends Admin_Controller
{
    protected $permissionCreate = 'Bezzeting.Reports.Create';
    protected $permissionDelete = 'Bezzeting.Reports.Delete';
    protected $permissionEdit   = 'Bezzeting.Reports.Edit';
    protected $permissionView   = 'Bezzeting.Reports.View';
    protected $permissionRequest   = 'Petajabatan.Reports.Request';
    
    protected $permissionFiltersatker   = 'Bezzeting.Pilihsatker.View';
    public $UNOR_ID = null;
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('petajabatan/request_formasi_model');
        $this->auth->restrict($this->permissionView);
        $this->lang->load('bezzeting');
        
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'reports/_sub_nav');

        Assets::add_module_js('bezzeting', 'bezzeting.js');
        // filter untuk role yang filtersatkernya aktif
        if(!$this->auth->has_permission($this->permissionFiltersatker)){
            $this->UNOR_ID = $this->pegawai_model->getunor_induk($this->auth->username());
        }
    }

    /**
     * Display a list of bezzeting data.
     *
     * @return void
     */
    public function index()
    {
        $this->load->model('pegawai/unitkerja_model');
        $unit_kerja = $this->unitkerja_model->find($this->UNOR_ID);
        // print_r($unit_kerja);
        // die();
        Template::set('unit_kerja', $unit_kerja);
        Template::set('toolbar_title', lang('bezzeting_manage'));
        Template::render();
    }
    public function ajukan($jabatan)
    {
        $unit_id = $this->uri->segment(6);
        $this->load->model('pegawai/unitkerja_model');
        $this->load->model('ref_jabatan/jabatan_model');
        $jabatans = $this->jabatan_model->find_by("KODE_JABATAN",$jabatan);
        $unit_kerja = $this->unitkerja_model->find($unit_id);
        $this->request_formasi_model->where("id_jabatan",$jabatan);
        $request_formasi = $this->request_formasi_model->find_by("unit_id",$unit_id);
        Template::set('request_formasi', $request_formasi);
        Template::set('jabatans', $jabatans);
        Template::set('jabatan', $jabatan);
        Template::set('unit_kerja', $unit_kerja);
        Template::set('toolbar_title', "Ajukan Kebutuhan Formasi");
        Template::render();
    }
    public function viewdata()
    {
        $unitkerja = $this->input->get('unitkerja');
        if(!$this->auth->has_permission($this->permissionFiltersatker)){
            $unitkerja = $this->UNOR_ID;
        }
        $this->load->model('petajabatan/kuotajabatan_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/unitkerja_model');
        $datadetil = $this->unitkerja_model->find_view_unit_list($unitkerja);
        $aunors = Modules::run('pegawai/manage_unitkerja/getcache_unor',$unitkerja);
        $json_unor = $this->cache->get('json_unor');
        array_unshift($json_unor[$unitkerja], $datadetil[0]);
        $satker = $json_unor[$unitkerja];
        // echo "<pre>";
        // print_r($json_unor[$unitkerja]);
        // echo "</pre>";
        $akuota[] = array(); 
        // Mulai kuota jabatan 
        $peraturan = trim($this->settings_lib->item('peta.permen'));
        $this->kuotajabatan_model->like('kuota_jabatan."PERATURAN"',trim($peraturan),"BOTH");
        $kuotajabatan = $this->kuotajabatan_model->find_all($unitkerja);
        if (isset($kuotajabatan) && is_array($kuotajabatan) && count($kuotajabatan)):
            foreach($kuotajabatan as $record):
                $akuota[trim($record->KODE_UNIT_KERJA)."-ID_JABATAN"][]     = trim($record->KODE_JABATAN);
                $akuota[trim($record->KODE_UNIT_KERJA)."-NAMA_Jabatan"][]   = trim($record->NAMA_JABATAN);
                $akuota[trim($record->KODE_UNIT_KERJA)."-KELAS"][]  = trim($record->KELAS);
                $akuota[trim($record->KODE_UNIT_KERJA)."-JML"][] = trim($record->JUMLAH_PEMANGKU_JABATAN);
            endforeach;
        endif;

        $pegawaijabatan = $this->pegawai_model->find_grupjabatan_unor_induk($unitkerja);
        $apegawai = array(); 
        if (isset($pegawaijabatan) && is_array($pegawaijabatan) && count($pegawaijabatan)):
            foreach($pegawaijabatan as $record):
                $apegawai[trim($record->UNOR_ID)."-jml-".trim($record->JABATAN_INSTANSI_ID)] = $record->jumlah;
            endforeach;
        endif;
        // get request
        $this->request_formasi_model->where("tahun",trim($this->settings_lib->item('peta_tahun')));
        $data_request = $this->request_formasi_model->find_all($unitkerja);
        $arequest_data = array();
        if (isset($data_request) && is_array($data_request) && count($data_request)):
            foreach($data_request as $record):
                $arequest_data[trim($record->unit_id)."_".trim($record->id_jabatan)] = $record->jumlah_ajuan;
            endforeach;
        endif;
         // GET DETIL PEGAWAI
        $pegawaijabatandetil = $this->pegawai_model->find_pegawai_jabatan_unor_induk($unitkerja);
        $apegawaidet = array(); 
        if (isset($pegawaijabatandetil) && is_array($pegawaijabatandetil) && count($pegawaijabatandetil)):
            foreach($pegawaijabatandetil as $record):
                $apegawaidet[trim($record->UNOR_ID)."-ID-".trim($record->JABATAN_INSTANSI_ID)][] = trim($record->ID);
                $apegawaidet[trim($record->UNOR_ID)."-NAMA-".trim($record->JABATAN_INSTANSI_ID)][] = trim($record->NAMA);
                $apegawaidet[trim($record->UNOR_ID)."-NIP-".trim($record->JABATAN_INSTANSI_ID)][] = trim($record->NIP_BARU);
            endforeach;
        endif;
        $output = $this->load->view('reports/content',array('satker'=>$satker,'datadetil'=>$datadetil,"akuota"=>$akuota,"apegawai"=>$apegawai,"apegawaidet"=>$apegawaidet,"arequest_data"=>$arequest_data,"unitkerja"=>$unitkerja),true);   
       
        echo $output;
        die();
    }
}