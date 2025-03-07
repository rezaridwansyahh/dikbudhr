<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Izin controller
 */
class Izin extends Admin_Controller
{
    protected $permissionCreate = 'Izin_pegawai.Izin.Create';
    protected $permissionDelete = 'Izin_pegawai.Izin.Delete';
    protected $permissionEdit   = 'Izin_pegawai.Izin.Edit';
    protected $permissionView   = 'Izin_pegawai.Izin.View';
    protected $permissionVerifikasi   = 'Izin_pegawai.Izin.Persetujuan';
    protected $permissionPybmc   = 'Izin_pegawai.Izin.Persetujuan';
    protected $permissionViewSatker   = 'Izin_pegawai.Izin.ViewSatker';
    protected $permissionViewAll   = 'Izin_pegawai.Izin.ViewAll';
    

    protected $permissionSettingView   = 'Setting_atasan.Izin.View';
    protected $permissionSettingAdd   = 'Setting_atasan.Izin.Add';
    protected $permissionSettingEdit   = 'Setting_atasan.Izin.Edit';
    protected $permissionSettingDelete   = 'Setting_atasan.Izin.Delete';

    protected $permissionSettingKirimAbsen   = 'Izin_pegawai.KirimAbsen.View';
    protected $permissionLaporanPegawai   = 'Izin_pegawai.Izin.LaporanPegawai';
    protected $permissionUploaddata   = 'Izin_pegawai.Upload.View';
    protected $permissionFiltersatker   = 'Pegawai.Kepegawaian.Filtersatker';
    protected $UnitkerjaTerbatas   = 'Unitkerja.Kepegawaian.Terbatas';
    protected $permissionKirimKehadiran   = 'Izin_pegawai.KirimKeEkehadiran.View';
    
    public $SATKER_ID = null;
    public $is_pejabat_cuti = null;
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        date_default_timezone_set("Asia/Jakarta");
        $this->load->model('izin_pegawai/izin_pegawai_model');
        $this->load->model('izin_pegawai/mv_pegawai_cuti_model');
        $this->load->model('izin_pegawai/pegawai_atasan_model');
        $this->load->model('sisa_cuti/sisa_cuti_model');
        $this->load->model('jenis_izin/jenis_izin_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('izin_pegawai/absen_model');
        $this->load->model('izin_pegawai/line_approval_model');
        $this->load->model('izin_pegawai/izin_alasan_model');
        $this->load->model('izin_pegawai/izin_verifikasi_model');
        $this->load->model('izin_pegawai/vw_pejabat_cuti_model');
        
        $this->load->model('sk_ds/log_ds_model');
        $this->load->model('ds_riwayat/ds_riwayat_model');
        $this->load->model('izin_pegawai/pengajuan_bdr_model');
        $this->lang->load('izin_pegawai');
        $this->load->helper('dikbud');
            Assets::add_css('flick/jquery-ui-1.8.13.custom.css');
            Assets::add_js('jquery-ui-1.8.13.min.js');
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'izin/_sub_nav');

        Assets::add_module_js('izin_pegawai', 'izin_pegawai.js');

        $data_status_izin = Liststatus_izin();
        Template::set('data_status_izin', $data_status_izin);
        $this->is_pejabat_cuti = $this->vw_pejabat_cuti_model->count_me(trim($this->auth->username()));
        if($this->auth->has_permission($this->permissionFiltersatker)){
            $this->SATKER_ID = $this->pegawai_model->getunor_induk($this->auth->username());
        }
    }

    /**
     * Display a list of izin pegawai data.
     *
     * @return void
     */
    public function index()
    {
        $this->auth->restrict($this->permissionView);
        $jenis_izin = $this->jenis_izin_model->find_active();
        Template::set('jenis_izin', $jenis_izin);
        Template::set('toolbar_title', lang('izin_pegawai_manage'));
        Template::render();
    }
    public function viewall()
    {
        $this->auth->restrict($this->permissionViewAll);
        $jenis_izin = $this->jenis_izin_model->find_active();
        Template::set('jenis_izin', $jenis_izin);
        Template::set('toolbar_title', "Lihat semua Izin");
        Template::render();
    }
    public function viewallsatker()
    {
        $this->auth->restrict($this->permissionViewSatker);
        $jenis_izin = $this->jenis_izin_model->find_active();
        Template::set('jenis_izin', $jenis_izin);
        Template::set('toolbar_title', "Lihat semua Izin (Satker)");
        Template::render();
    }
    public function verifikasi()
    {
        if (!$this->auth->has_permission($this->permissionVerifikasi) and !$this->is_pejabat_cuti) {
            Template::set_message("Anda tidak punya akses persetujuan cuti/izin", 'error');
            redirect(SITE_AREA . '/izin/izin_pegawai/perizinan');
        }
        Template::set('toolbar_title', "Verifikasi Izin");
        Template::render();
    }
    public function pybmc()
    {
        $this->auth->restrict($this->permissionPybmc);
        Template::set('toolbar_title', "Persetujuan PYBMC");
        Template::render();
    }
    
    public function setting()
    {
        $this->auth->restrict($this->permissionSettingView);
        $jenis_izin = $this->jenis_izin_model->find_active();
        Template::set('jenis_izin', $jenis_izin);
        Template::set('toolbar_title', "Setting Atasan");
        Template::render();
    }

    public function pilihpejabat()
    {
        $this->auth->restrict($this->permissionSettingAdd);
        
        Template::set('toolbar_title', "Pilih Pejabat");
        Template::render();
    }
    public function pilihpejabatedit($nip = "")
    {
        $this->auth->restrict($this->permissionSettingEdit);
        $line_approval = $this->line_approval_model->find_all($nip);
        $ajabatan = get_list_pejabat_cuti();
        Template::set('ajabatan', $ajabatan);
        Template::set('line_approval', $line_approval);
        Template::set('nip', $nip);
        Template::set('toolbar_title', "Pilih Pejabat");
        Template::render();
    }
    public function pengajuanjadwalbdr(){
        $ses_nip    = trim($this->auth->username());
        $dataatasan     = $this->get_data_pejabat($ses_nip);
        $NIP_ATASAN     = isset($dataatasan[2]->NIP_ATASAN) ? $dataatasan[2]->NIP_ATASAN : "";
        Template::set('dataset',array("nip"=>$ses_nip,"atasan"=>$dataatasan));
        Template::set('atasan_langsung',$NIP_ATASAN);
        Template::set('toolbar_title', "Pengajuan BDR");
        Template::render();
    }

    public function savejadwalbdr(){
        $_POST['meta'] = json_encode($_POST['meta']);
        $_POST['status_pengajuan'] = "DIAJUKAN";
        //var_dump($_POST);
        echo $this->pengajuan_bdr_model->save_bdr($_POST);
    }

    public function daftarpengajuanbawahan(){
        echo json_encode(array("data"=>$this->pengajuan_bdr_model->get_pengajuan_bawahan($_GET['nip'])));
    }

    public function daftarpengajuanpribadi(){
        echo json_encode(array("data"=>$this->pengajuan_bdr_model->get_pengajuan_pribadi($_GET['nip'])));
    }

    public function ubahstatuspengajuan(){
        $data = array(
            "status_pengajuan"=>$_POST['status_pengajuan'],
            "pesan"=>$_POST['pesan'],
            "id"=>$_POST['id']
        );

        

        echo $this->pengajuan_bdr_model->update_pengajuan($data);
    }
    /**
     * Create a izin pegawai object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        $this->load->model('pegawai/Pns_aktif_model');
        $this->load->model('pegawai/unitkerja_model');
        $this->load->model('ref_jabatan/jabatan_model');
        $this->load->library('Convert');
        $convert = new Convert;
        $id = $this->uri->segment(5);
        $ses_nip    = trim($this->auth->username());
        $TAHUN      = trim(date("Y")." ");
        $jenis_izin = $this->jenis_izin_model->find($id);
        $nama_izin  = isset($jenis_izin->NAMA_IZIN) ? $jenis_izin->NAMA_IZIN : "";
        $keterangan_izin = isset($jenis_izin->KETERANGAN) ? $jenis_izin->KETERANGAN : "";
        $level      = isset($jenis_izin->PERSETUJUAN) ? $jenis_izin->PERSETUJUAN : "";
        $ahari_libur = $this->get_json_harilibur_tahunan($TAHUN);
        Template::set('ahari_libur', json_encode($ahari_libur));
        //print_r($ahari_libur);
        if(!$this->cek_kelengkapan_atasan($ses_nip,$id)){
            die('<div class="callout callout-danger">
               <h4>Perhatian</h4>
               <p>Data atasan anda belum lengkap</p>
             </div>');
        }
        // get atasan langsung
        $dataatasan     = $this->get_data_pejabat($ses_nip);
        $NIP_ATASAN     = isset($dataatasan[2]->NIP_ATASAN) ? $dataatasan[2]->NIP_ATASAN : "";
        
        $alasan_izin = $this->izin_alasan_model->find_all($id);
        Template::set('alasan_izin', $alasan_izin);
        if($id == "1"){
            $this->create_saldo($ses_nip,$TAHUN);

            $this->sisa_cuti_model->where("sisa_cuti.TAHUN = '".$TAHUN."'");
            $data_cuti = $this->sisa_cuti_model->find_by("PNS_NIP",$ses_nip);
            if((int)$data_cuti->SUDAH_DIAMBIL < (int)$data_cuti->V_SUDAH_DIAMBIL)
                $data_cuti->SUDAH_DIAMBIL = $data_cuti->V_SUDAH_DIAMBIL;
        }
        if($id == "5"){
            
        }
        if($id == "12"){
            // absen WFH
            $WAKTU = $this->get_waktu();
            $tanggal = date("Y-m-d");
            $data_absen = $this->absen_model->getdata_absen($ses_nip,$tanggal);
            Template::set("data_absen",$data_absen);
            Template::set("WAKTU",$WAKTU);
        }
        $file_view = 'application/modules/izin_pegawai/views/izin/'.$id.'.php'; 

        if (file_exists($file_view))  
        { 
            Template::set_view("izin/".$id);
        } 
        else 
        { 
            Template::set_view("izin/create");
        } 

        $pegawai = $this->pegawai_model->find_detil_with_nip($ses_nip);
        $recpns_aktif = $this->Pns_aktif_model->find($pegawai->ID);
        Template::set("parent_path_array_unor",$this->unitkerja_model->get_parent_path($pegawai->UNOR_ID,true,true));

        $JABATAN_ID = $pegawai->JABATAN_INSTANSI_ID;
        $JABATAN_INSTANSI_REAL_ID = $pegawai->JABATAN_INSTANSI_REAL_ID;
        if($JABATAN_INSTANSI_REAL_ID != ""){
            $recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_INSTANSI_REAL_ID));
            Template::set('NAMA_JABATAN_REAL', $recjabatan->NAMA_JABATAN);
        }
        if($JABATAN_ID != ""){
            $recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_ID));
            Template::set('NAMA_JABATAN', $recjabatan->NAMA_JABATAN);
            Template::set('NAMA_JABATAN_BKN', $recjabatan->NAMA_JABATAN_BKN);
        }
        $foto_pegawai = trim($this->settings_lib->item('site.urlphoto'))."noimage.jpg";
        if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO)){
            $foto_pegawai =  trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO;
        }
        Template::set('foto_pegawai', $foto_pegawai);
        // CEK DI TABEL UNITKERJA
        $unor = $this->unitkerja_model->find_by("ID",trim($pegawai->UNOR_ID));
        
        Template::set('unit_kerja',$unor->NAMA_UNOR);
        $unor_induk = $this->unitkerja_model->find_by("ID",$unor->UNOR_INDUK);
        Template::set('unor_induk_id',$unor_induk->ID);
        Template::set('unor_induk',$unor_induk->NAMA_UNOR);

         
        $tanggal = date("Y-m-d");
        $tanggal_indonesia = $convert->fmtDate($tanggal,"dd month yyyy");
        $hari = $convert->Gethari($tanggal);
        Template::set('tanggal_indonesia', $tanggal_indonesia);
        Template::set('hari', $hari);
        Template::set('NIP_ATASAN', $NIP_ATASAN);
        Template::set('data_cuti',$data_cuti);
        Template::set('pegawai', $pegawai);
        Template::set('recpns_aktif', $recpns_aktif);
        Template::set('keterangan_izin', $keterangan_izin);
        Template::set('kode_izin', $id);
        Template::set('NIP_PNS', $ses_nip);
        Template::set('TAHUN', $TAHUN);
        Template::set('nama_izin', $nama_izin);
        Template::set('toolbar_title', "Ajukan Izin ".$nama_izin);

        Template::render();
    }
    public function createselect($id_jenis_izin = "")
    {
        $this->auth->restrict($this->permissionCreate);
        $this->load->model('pegawai/Pns_aktif_model');
        $this->load->model('pegawai/unitkerja_model');
        $this->load->model('ref_jabatan/jabatan_model');

        $jenis_izin = $this->jenis_izin_model->find_active();
        Template::set('jenis_izin', $jenis_izin);
        Template::set('id_jenis_izin', $id_jenis_izin); 
        Template::set('toolbar_title', "Ajukan Izin ");

        Template::render();
    }
    public function viewalur()
    {
        $this->auth->restrict($this->permissionCreate);
        $this->load->model('pegawai/Pns_aktif_model');
        $this->load->model('pegawai/unitkerja_model');
        $this->load->model('ref_jabatan/jabatan_model');
        $id = $this->uri->segment(5);
        $kode_tabel = $this->uri->segment(6);
        $izin_pegawai = $this->izin_pegawai_model->find($kode_tabel);
        Template::set('izin_pegawai', $izin_pegawai);

        $ses_nip    = trim($this->auth->username());
        $data_pegawai = $this->pegawai_model->find_by("NIP_BARU",$ses_nip);
        $NAMA_PEGAWAI = isset($data_pegawai->NAMA) ? $data_pegawai->NAMA : "";

        $jenis_izin = $this->jenis_izin_model->find($id);
        $nama_izin  = isset($jenis_izin->NAMA_IZIN) ? $jenis_izin->NAMA_IZIN : "";
        $keterangan_izin = isset($jenis_izin->KETERANGAN) ? $jenis_izin->KETERANGAN : "";
        $PERSETUJUAN      = isset($jenis_izin->PERSETUJUAN) ? $jenis_izin->PERSETUJUAN : "";
        Template::set('NAMA_PEGAWAI', $NAMA_PEGAWAI);
        Template::set('aatasan', $this->get_data_pejabat($ses_nip));
        Template::set('line_approval', $line_approval);
        Template::set('PERSETUJUAN', $PERSETUJUAN);

        Template::set_view("izin/_lineapproval");

        Template::render();
    }
    public function view_libur()
    {
        $this->auth->restrict($this->permissionCreate);
        $tahun = $this->uri->segment(5);
        $this->load->model('izin_pegawai/hari_libur_model');
        $record_hari_libur_tahunan = $this->hari_libur_model->find_by_tahun($tahun);
        //print_r($record_hari_libur_tahunan);
        $this->generateharilibur($tahun);
        Template::set("tahun",$tahun);
        Template::set("record_hari_libur_tahunan",$record_hari_libur_tahunan);
        Template::set_view("presensi/harilibur_content");

        Template::render();
    }
    private function cek_kelengkapan_atasan($nip = "",$jenis_izin = ""){
        $dataatasan     = $this->get_data_pejabat($nip);
        $persetujuan    = $this->getpersetujuan($jenis_izin);
        $return = false;
        if(count($persetujuan) >0 and is_array($persetujuan)){
            foreach($persetujuan as $values)
            {
                if(isset($dataatasan[$values])){
                    return true;
                }
            }
        }else{
            return true;
        }
        return $return;
    }
    private function generateharilibur($tahun = ""){
        $this->load->model('izin_pegawai/hari_libur_model');
        $start_date = $tahun."-01-01";
        $end_date = $tahun."-12-31";
        $this->load->library('Api_kehadiran');
        $api_kehadiran = new Api_kehadiran;
        $generate_harilibur = $api_kehadiran->getHarilibur($start_date,$end_date);
        $json_harilibur = json_decode($generate_harilibur);
        $dataharilibur = $json_harilibur->data;
        $jml = 0;
        foreach($dataharilibur as $row)
        {
            $datadel = array('START_DATE '=>$row->start_date);
            $this->hari_libur_model->delete_where($datadel);

            $data = array();
            $data['START_DATE']       = $row->start_date;
            $data['END_DATE']         = $row->end_date;
            $data['INFO']          = $row->info;
            if($this->hari_libur_model->insert($data)){
                $jml = $jml + 1;
            } 

        }
    }
    /**
     * Allows editing of izin pegawai data.
     *
     * @return void
     */
    public function edit()
    {
        $this->auth->restrict($this->permissionCreate);
        $this->load->model('pegawai/Pns_aktif_model');
        $this->load->model('pegawai/unitkerja_model');
        $this->load->model('ref_jabatan/jabatan_model');
        $id = $this->uri->segment(5);
        $kode_tabel = $this->uri->segment(6);
        $izin_pegawai = $this->izin_pegawai_model->find($kode_tabel);
        Template::set('izin_pegawai', $izin_pegawai);

        $ses_nip    = trim($this->auth->username());
        $TAHUN      = trim(date("Y")." ");
        $jenis_izin = $this->jenis_izin_model->find($id);
        $nama_izin  = isset($jenis_izin->NAMA_IZIN) ? $jenis_izin->NAMA_IZIN : "";
        $keterangan_izin = isset($jenis_izin->KETERANGAN) ? $jenis_izin->KETERANGAN : "";
        $level      = isset($jenis_izin->PERSETUJUAN) ? $jenis_izin->PERSETUJUAN : "";

        if($level != ""){
            $this->line_approval_model->limit($level);   
        }
        $line_approval = $this->line_approval_model->find_all($ses_nip);
        Template::set('line_approval', $line_approval);
        
        $ahari_libur = $this->get_json_harilibur_tahunan($TAHUN);
        Template::set('ahari_libur', json_encode($ahari_libur));
        $alasan_izin = $this->izin_alasan_model->find_all($id);
        Template::set('alasan_izin', $alasan_izin);
        if($id == "1"){
            $this->create_saldo($ses_nip,$TAHUN);

            $this->sisa_cuti_model->where("sisa_cuti.TAHUN = '".$TAHUN."'");
            $data_cuti = $this->sisa_cuti_model->find_by("PNS_NIP",$ses_nip);
        }
        if($id == "12"){
            $tanggal = date("Y-m-d");
            $data_absen = $this->absen_model->getdata_absen($ses_nip,$tanggal);
            // print_r($data_absen);
            $jam_checkin = isset($data_absen[0]->JAM) ? $data_absen[0]->JAM : "";
            $jam_checkout = "";
            if(isset($data_absen[0]->JAM) and count($data_absen) > 1){
                $index_checkout =  count($data_absen) - 1;
                $jam_checkout = isset($data_absen[$index_checkout]->JAM) ? $data_absen[$index_checkout]->JAM : "";
            }
            Template::set("jam_checkin",$jam_checkin);
            Template::set("jam_checkout",$jam_checkout);
        }
        $file_view = 'application/modules/izin_pegawai/views/izin/'.$id.'.php'; 
        if (file_exists($file_view))  
        { 
            Template::set_view("izin/".$id);
        } 
        else 
        { 
            Template::set_view("izin/create");
        } 

        $pegawai = $this->pegawai_model->find_detil_with_nip($ses_nip);
        $recpns_aktif = $this->Pns_aktif_model->find($pegawai->ID);
        Template::set("parent_path_array_unor",$this->unitkerja_model->get_parent_path($pegawai->UNOR_ID,true,true));

        $JABATAN_ID = $pegawai->JABATAN_INSTANSI_ID;
        $JABATAN_INSTANSI_REAL_ID = $pegawai->JABATAN_INSTANSI_REAL_ID;
        if($JABATAN_INSTANSI_REAL_ID != ""){
            $recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_INSTANSI_REAL_ID));
            Template::set('NAMA_JABATAN_REAL', $recjabatan->NAMA_JABATAN);
        }
        if($JABATAN_ID != ""){
            $recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_ID));
            Template::set('NAMA_JABATAN', $recjabatan->NAMA_JABATAN);
            Template::set('NAMA_JABATAN_BKN', $recjabatan->NAMA_JABATAN_BKN);
        }
        $foto_pegawai = trim($this->settings_lib->item('site.urlphoto'))."noimage.jpg";
        if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO)){
            $foto_pegawai =  trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO;
        }
        Template::set('foto_pegawai', $foto_pegawai);
        // CEK DI TABEL UNITKERJA
        $unor = $this->unitkerja_model->find_by("ID",trim($pegawai->UNOR_ID));
        
        Template::set('unit_kerja',$unor->NAMA_UNOR);
        $unor_induk = $this->unitkerja_model->find_by("ID",$unor->UNOR_INDUK);
        Template::set('unor_induk_id',$unor_induk->ID);
        Template::set('unor_induk',$unor_induk->NAMA_UNOR);

        // CEK PEJABAT DI TABEL SETTING PEJABAT
        $recdata_pejabat = $this->pegawai_atasan_model->find_by("PNS_NIP",trim($ses_nip));
        $NIP_ATASAN = "";
        $NAMA_ATASAN = "";
        $NIP_PPK = "";
        $NAMA_PPK = "";
        if($recdata_pejabat->ID != ""){
            $NIP_ATASAN = $recdata_pejabat->NIP_ATASAN;
            $NAMA_ATASAN = $recdata_pejabat->NAMA_ATASAN;
            $NIP_PPK = $recdata_pejabat->PPK;
            $NAMA_PPK = $recdata_pejabat->NAMA_PPK;
        }else{
            // atasan dan PPK ambil dari data unitkerja
            $PNS_ID_UNOR = isset($unor->PEMIMPIN_PNS_ID) ? $unor->PEMIMPIN_PNS_ID : "";
            //$NAMA_ATASAN = isset($unor->NAMA_PEJABAT) ? trim($unor->NAMA_PEJABAT) : "";
            $ID_UNOR_ATASAN = isset($unor->DIATASAN_ID) ? trim($unor->DIATASAN_ID) : "";
            $this->pegawai_model->select("NIP_BARU,NAMA");
            $recdata_pejabat = $this->pegawai_model->find_by("PNS_ID",trim($PNS_ID_UNOR));
            $NIP_ATASAN = $recdata_pejabat->NIP_BARU;
            $NAMA_ATASAN = $recdata_pejabat->NAMA;

            $recunor_atasan = $this->unitkerja_model->find_by("ID",trim($ID_UNOR_ATASAN));
            $PNS_ID_LEVEL2 = isset($recunor_atasan->PEMIMPIN_PNS_ID) ? $recunor_atasan->PEMIMPIN_PNS_ID : "";
            $this->pegawai_model->select("NIP_BARU,NAMA");
            $recdata_pejabat = $this->pegawai_model->find_by("PNS_ID",trim($PNS_ID_LEVEL2));
            $NIP_ppk = $recdata_pejabat->NIP_BARU;
            $NAMA_ppk = $recdata_pejabat->NAMA;

        }
        Template::set('NIP_ATASAN',$NIP_ATASAN);
        Template::set('NAMA_ATASAN',$NAMA_ATASAN);
        Template::set('NIP_PPK',$NIP_PPK);
        Template::set('NAMA_PPK',$NAMA_PPK);

        Template::set('data_cuti',$data_cuti);
        Template::set('pegawai', $pegawai);
        Template::set('recpns_aktif', $recpns_aktif);
        Template::set('level', $level);
        Template::set('keterangan_izin', $keterangan_izin);
        Template::set('kode_izin', $id);
        Template::set('NIP_PNS', $ses_nip);
        Template::set('nama_izin', $nama_izin);
        Template::set('toolbar_title', "Ajukan Izin ".$nama_izin);

        Template::render();
    }
    public function lineaproval()
    {
        $this->auth->restrict($this->permissionCreate);
        $ses_nip    = trim($this->auth->username());
        $this->load->helper('dikbud');
        $this->load->model('pegawai/Pns_aktif_model');
        $this->load->model('pegawai/unitkerja_model');
        $this->load->model('ref_jabatan/jabatan_model');
        $id = $this->uri->segment(5);
        $kode_tabel = $this->uri->segment(6);
        $izin_pegawai = $this->izin_pegawai_model->find($kode_tabel);
        $ses_nip    = $izin_pegawai->NIP_PNS; 
        Template::set('izin_pegawai', $izin_pegawai);
        $verifikasidata = $this->get_data_verifikasi_izin($izin_pegawai->ID);

        Template::set('verifikasidata', $verifikasidata);

        $jenis_izin = $this->jenis_izin_model->find($id);
        $nama_izin  = isset($jenis_izin->NAMA_IZIN) ? $jenis_izin->NAMA_IZIN : "";
        $PERSETUJUAN      = isset($jenis_izin->PERSETUJUAN) ? $jenis_izin->PERSETUJUAN : "";

        $adata_pejabat = $this->get_data_pejabat($ses_nip);
        Template::set('aatasan', $adata_pejabat);

        $datapejabat = $this->get_data_pejabat_nip($ses_nip);
        Template::set('apejabat', $datapejabat);

        Template::set('NAMA_PEGAWAI', $this->getnama_pegawai_session($ses_nip));
        Template::set('PERSETUJUAN', $PERSETUJUAN);

        Template::set('toolbar_title', "Line Approval ".$nama_izin);

        Template::render();
    }
    private function getnama_pegawai_session($ses_nip = ""){
        $this->pegawai_model->select("NAMA");
        $data_pegawai = $this->pegawai_model->find_by("NIP_BARU",$ses_nip);
        $NAMA_PEGAWAI = isset($data_pegawai->NAMA) ? $data_pegawai->NAMA : "";
        return $NAMA_PEGAWAI;
    }
    private function list_status(){
        $status_arr = Liststatus_izin();
        foreach($status_arr as $row){
            $data[] = array(
                'id'=>$row['id'],
                'text'=>$row['value'],
            );
        }
        $output = array(
            'results'=>$data 
        );
        return json_encode($output);
    }
    private function get_data_pejabat($ses_nip = ""){
        $line_approval = $this->getatasan($ses_nip);
        $aatasan = array();
        if(isset($line_approval) && is_array($line_approval) && count($line_approval)):
            foreach ($line_approval as $record) {
                $aatasan[$record->SEBAGAI] = $record;
            }
        endif;
        return $aatasan;
    }
    private function get_data_pejabat_nip($ses_nip = ""){
        $line_approval = $this->getatasan($ses_nip);
        $aatasan = array();
        if(isset($line_approval) && is_array($line_approval) && count($line_approval)):
            foreach ($line_approval as $record) {
                $aatasan[$record->NIP_ATASAN] = $record->SEBAGAI;
            }
        endif;
        return $aatasan;
    }
    private function getpersetujuan($id){
        $jenis_izin = $this->jenis_izin_model->find($id);
        $level      = isset($jenis_izin->PERSETUJUAN) ? json_decode($jenis_izin->PERSETUJUAN) : "";
        return $level;
    }
    public function verifikasiusulan()
    {
        if (!$this->auth->has_permission($this->permissionVerifikasi) and !$this->is_pejabat_cuti) {
            Template::set_message("Anda tidak punya akses persetujuan cuti/izin", 'error');
            redirect(SITE_AREA . '/izin/izin_pegawai/perizinan');
        }
        $this->load->model('pegawai/Pns_aktif_model');
        $this->load->model('pegawai/unitkerja_model');
        $this->load->model('ref_jabatan/jabatan_model');
        $id = $this->uri->segment(5);
        $kode_tabel = $this->uri->segment(6); // id verifikasi

        $izin_verifikasi_detil = $this->izin_verifikasi_model->find($kode_tabel);
        $kode_pengajuan = isset($izin_verifikasi_detil->ID_PENGAJUAN) ? $izin_verifikasi_detil->ID_PENGAJUAN : "";

        $izin_pegawai = $this->izin_pegawai_model->find($kode_pengajuan);
        $ses_nip = isset($izin_pegawai->NIP_PNS) ? $izin_pegawai->NIP_PNS : "-";
        $DARI_TANGGAL = isset($izin_pegawai->DARI_TANGGAL) ? $izin_pegawai->DARI_TANGGAL : "-";
        $this->create_pdf_form($kode_pengajuan);

        Template::set('izin_pegawai', $izin_pegawai);

        $TAHUN      = date('Y', strtotime($DARI_TANGGAL));;
        $jenis_izin = $this->jenis_izin_model->find($id);
        $nama_izin  = isset($jenis_izin->NAMA_IZIN) ? $jenis_izin->NAMA_IZIN : "";
        $keterangan_izin = isset($jenis_izin->KETERANGAN) ? $jenis_izin->KETERANGAN : "";
        $level      = isset($jenis_izin->PERSETUJUAN) ? $jenis_izin->PERSETUJUAN : "";
        $KODE_JENIS_IZIN      = isset($jenis_izin->KODE) ? $jenis_izin->KODE : "";
        Template::set('KODE_JENIS_IZIN',$KODE_JENIS_IZIN);    
        if($id != ""){
            $data_cuti = getSisaCuti($ses_nip,$TAHUN);
            // $this->sisa_cuti_model->where("sisa_cuti.TAHUN = '".$TAHUN."'");
            // $data_cuti = $this->sisa_cuti_model->find_by("PNS_NIP",$ses_nip);
            Template::set('data_cuti',$data_cuti);   
        }
        $pegawai = $this->pegawai_model->find_detil_with_nip($ses_nip);
        $recpns_aktif = $this->Pns_aktif_model->find($pegawai->ID);
        Template::set("parent_path_array_unor",$this->unitkerja_model->get_parent_path($pegawai->UNOR_ID,true,true));

        $JABATAN_ID = $pegawai->JABATAN_INSTANSI_ID;
        $JABATAN_INSTANSI_REAL_ID = $pegawai->JABATAN_INSTANSI_REAL_ID;
        if($JABATAN_INSTANSI_REAL_ID != ""){
            $recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_INSTANSI_REAL_ID));
            Template::set('NAMA_JABATAN_REAL', $recjabatan->NAMA_JABATAN);
        }
        if($JABATAN_ID != ""){
            $recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_ID));
            Template::set('NAMA_JABATAN', $recjabatan->NAMA_JABATAN);
            Template::set('NAMA_JABATAN_BKN', $recjabatan->NAMA_JABATAN_BKN);
        }
        $foto_pegawai = trim($this->settings_lib->item('site.urlphoto'))."noimage.jpg";
        if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO)){
            $foto_pegawai =  trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO;
        }
        Template::set('foto_pegawai', $foto_pegawai);
        // CEK DI TABEL UNITKERJA
        $unor = $this->unitkerja_model->find_by("ID",trim($pegawai->UNOR_ID));
        
        Template::set('unit_kerja',$unor->NAMA_UNOR);
        $unor_induk = $this->unitkerja_model->find_by("ID",$unor->UNOR_INDUK);
        Template::set('unor_induk_id',$unor_induk->ID);
        Template::set('unor_induk',$unor_induk->NAMA_UNOR);

        $NIP = trim($this->auth->username());
        $pegawai_login = $this->pegawai_model->find_by("NIP_BARU",trim($NIP));
        Template::set("pegawai_login",$pegawai_login);
        // get jumlah izin bulan yang sama
        $jumlah_izin_bulan_sama = $this->getJumlahIzinBulanSama($DARI_TANGGAL,$ses_nip,$id);
        Template::set('jumlah_izin_bulan_sama', $jumlah_izin_bulan_sama);
        // end jumlah izin bulan yang sama
        Template::set('ID_VERIFIKASI', $kode_tabel);
        Template::set('kode_pengajuan', $kode_pengajuan);
        
        Template::set('pegawai', $pegawai);
        Template::set('recpns_aktif', $recpns_aktif);
        Template::set('level', $level);
        Template::set('keterangan_izin', $keterangan_izin);
        Template::set('kode_izin', $id);
        Template::set('NIP_PNS', $ses_nip);
        Template::set('nama_izin', $nama_izin);
        Template::set('toolbar_title', "Verifikasi Izin ".$nama_izin);
        //line approval
        $verifikasidata = $this->get_data_verifikasi_izin($izin_pegawai->ID);
        Template::set('verifikasidata', $verifikasidata);
        $adata_pejabat = $this->get_data_pejabat($ses_nip);
        Template::set('aatasan', $adata_pejabat);
        $datapejabat = $this->get_data_pejabat_nip($ses_nip);
        Template::set('apejabat', $datapejabat);
        Template::set('NAMA_PEGAWAI',$pegawai->NAMA);
        Template::set('PERSETUJUAN', $level);
        // end persetujuan
        Template::render();
    }
    private function getJumlahIzinBulanSama($tanggal,$nip,$jenis_izin){
        $tanggal = explode("-", $tanggal);
        $tahun = isset($tanggal[0]) ? $tanggal[0] : "";
        $bulan = isset($tanggal[1]) ? $tanggal[1] : "";
        $jumlah = $this->izin_pegawai_model->countPerBulan($tahun,$bulan,$nip,$jenis_izin);
        return $jumlah;
    }
    public function form_tangguhkan()
    {
        if (!$this->auth->has_permission($this->permissionVerifikasi) and !$this->is_pejabat_cuti) {
            Template::set_message("Anda tidak punya akses persetujuan cuti/izin", 'error');
            redirect(SITE_AREA . '/izin/izin_pegawai/perizinan');
        }
        $this->load->model('pegawai/Pns_aktif_model');
        $this->load->model('pegawai/unitkerja_model');
        $this->load->model('ref_jabatan/jabatan_model');
        $kode_tabel = $this->uri->segment(5);
        $izin_pegawai = $this->izin_pegawai_model->find($kode_tabel);
        $ses_nip = isset($izin_pegawai->NIP_PNS) ? $izin_pegawai->NIP_PNS : "-";
        $DARI_TANGGAL = isset($izin_pegawai->DARI_TANGGAL) ? $izin_pegawai->DARI_TANGGAL : "-";
        $id = isset($izin_pegawai->KODE_IZIN) ? $izin_pegawai->KODE_IZIN : "-";

        Template::set('izin_pegawai', $izin_pegawai);

        $TAHUN      = date('Y', strtotime($DARI_TANGGAL));;
        $jenis_izin = $this->jenis_izin_model->find($id);
        $nama_izin  = isset($jenis_izin->NAMA_IZIN) ? $jenis_izin->NAMA_IZIN : "";
        $keterangan_izin = isset($jenis_izin->KETERANGAN) ? $jenis_izin->KETERANGAN : "";
        $level      = isset($jenis_izin->PERSETUJUAN) ? $jenis_izin->PERSETUJUAN : "";
        $KODE_JENIS_IZIN      = isset($jenis_izin->KODE) ? $jenis_izin->KODE : "";
        Template::set('KODE_JENIS_IZIN',$KODE_JENIS_IZIN);    
        if($id != ""){
            $this->sisa_cuti_model->where("sisa_cuti.TAHUN = '".$TAHUN."'");
            $data_cuti = $this->sisa_cuti_model->find_by("PNS_NIP",$ses_nip);
            Template::set('data_cuti',$data_cuti);    
        }
        $pegawai = $this->pegawai_model->find_detil_with_nip($ses_nip);
        $recpns_aktif = $this->Pns_aktif_model->find($pegawai->ID);
        Template::set("parent_path_array_unor",$this->unitkerja_model->get_parent_path($pegawai->UNOR_ID,true,true));

        $JABATAN_ID = $pegawai->JABATAN_INSTANSI_ID;
        $JABATAN_INSTANSI_REAL_ID = $pegawai->JABATAN_INSTANSI_REAL_ID;
        if($JABATAN_INSTANSI_REAL_ID != ""){
            $recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_INSTANSI_REAL_ID));
            Template::set('NAMA_JABATAN_REAL', $recjabatan->NAMA_JABATAN);
        }
        if($JABATAN_ID != ""){
            $recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_ID));
            Template::set('NAMA_JABATAN', $recjabatan->NAMA_JABATAN);
            Template::set('NAMA_JABATAN_BKN', $recjabatan->NAMA_JABATAN_BKN);
        }
        $foto_pegawai = trim($this->settings_lib->item('site.urlphoto'))."noimage.jpg";
        if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO)){
            $foto_pegawai =  trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO;
        }
        Template::set('foto_pegawai', $foto_pegawai);
        // CEK DI TABEL UNITKERJA
        $unor = $this->unitkerja_model->find_by("ID",trim($pegawai->UNOR_ID));
        
        Template::set('unit_kerja',$unor->NAMA_UNOR);
        $unor_induk = $this->unitkerja_model->find_by("ID",$unor->UNOR_INDUK);
        Template::set('unor_induk_id',$unor_induk->ID);
        Template::set('unor_induk',$unor_induk->NAMA_UNOR);

        $NIP = trim($this->auth->username());
        $pegawai_login = $this->pegawai_model->find_by("NIP_BARU",trim($NIP));
        Template::set("pegawai_login",$pegawai_login);

        Template::set('ID_VERIFIKASI', $kode_tabel);
        Template::set('kode_pengajuan', $kode_pengajuan);
        
        Template::set('pegawai', $pegawai);
        Template::set('recpns_aktif', $recpns_aktif);
        Template::set('level', $level);
        Template::set('keterangan_izin', $keterangan_izin);
        Template::set('kode_izin', $id);
        Template::set('NIP_PNS', $ses_nip);
        Template::set('nama_izin', $nama_izin);
        Template::set('toolbar_title', "Verifikasi Izin ".$nama_izin);

        Template::render();
    }
    public function verifikasipybmc()
    {
        $this->auth->restrict($this->permissionPybmc);
        $this->load->model('pegawai/Pns_aktif_model');
        $this->load->model('pegawai/unitkerja_model');
        $this->load->model('ref_jabatan/jabatan_model');
        $id = $this->uri->segment(5);
        $kode_tabel = $this->uri->segment(6);
        $izin_pegawai = $this->izin_pegawai_model->find($kode_tabel);
        $ses_nip = isset($izin_pegawai->NIP_PNS) ? $izin_pegawai->NIP_PNS : "";
        Template::set('izin_pegawai', $izin_pegawai);

        $TAHUN      = trim(date("Y")." ");
        $jenis_izin = $this->jenis_izin_model->find($id);
        $nama_izin  = isset($jenis_izin->NAMA_IZIN) ? $jenis_izin->NAMA_IZIN : "";
        $keterangan_izin = isset($jenis_izin->KETERANGAN) ? $jenis_izin->KETERANGAN : "";
        $level      = isset($jenis_izin->PERSETUJUAN) ? $jenis_izin->PERSETUJUAN : "";
        
        if($id != ""){
            $this->sisa_cuti_model->where("sisa_cuti.TAHUN = '".$TAHUN."'");
            $data_cuti = $this->sisa_cuti_model->find_by("PNS_NIP",$ses_nip);
            Template::set('data_cuti',$data_cuti);    
        }
        $pegawai = $this->pegawai_model->find_detil_with_nip($ses_nip);
        $recpns_aktif = $this->Pns_aktif_model->find($pegawai->ID);
        Template::set("parent_path_array_unor",$this->unitkerja_model->get_parent_path($pegawai->UNOR_ID,true,true));

        $JABATAN_ID = $pegawai->JABATAN_INSTANSI_ID;
        $JABATAN_INSTANSI_REAL_ID = $pegawai->JABATAN_INSTANSI_REAL_ID;
        if($JABATAN_INSTANSI_REAL_ID != ""){
            $recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_INSTANSI_REAL_ID));
            Template::set('NAMA_JABATAN_REAL', $recjabatan->NAMA_JABATAN);
        }
        if($JABATAN_ID != ""){
            $recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_ID));
            Template::set('NAMA_JABATAN', $recjabatan->NAMA_JABATAN);
            Template::set('NAMA_JABATAN_BKN', $recjabatan->NAMA_JABATAN_BKN);
        }
        $foto_pegawai = trim($this->settings_lib->item('site.urlphoto'))."noimage.jpg";
        if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO)){
            $foto_pegawai =  trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO;
        }
        Template::set('foto_pegawai', $foto_pegawai);
        // CEK DI TABEL UNITKERJA
        $unor = $this->unitkerja_model->find_by("ID",trim($pegawai->UNOR_ID));
        
        Template::set('unit_kerja',$unor->NAMA_UNOR);
        $unor_induk = $this->unitkerja_model->find_by("ID",$unor->UNOR_INDUK);
        Template::set('unor_induk_id',$unor_induk->ID);
        Template::set('unor_induk',$unor_induk->NAMA_UNOR);

        Template::set('pegawai', $pegawai);
        Template::set('recpns_aktif', $recpns_aktif);
        Template::set('level', $level);
        Template::set('keterangan_izin', $keterangan_izin);
        Template::set('kode_izin', $id);
        Template::set('NIP_PNS', $ses_nip);
        Template::set('nama_izin', $nama_izin);
        Template::set('toolbar_title', "Verifikasi Izin ".$nama_izin);

        Template::render();
    }
    public function formpdf()
    {
        //$this->auth->restrict($this->permissionVerifikasi);
        $this->load->model('pegawai/Pns_aktif_model');
        $this->load->model('pegawai/unitkerja_model');
        $this->load->model('ref_jabatan/jabatan_model');
        $id = $this->uri->segment(5);
        $kode_tabel = $this->uri->segment(6);
        $izin_pegawai = $this->izin_pegawai_model->find($kode_tabel);
        $ses_nip = isset($izin_pegawai->NIP_PNS) ? $izin_pegawai->NIP_PNS : "";
        $IS_SIGNED = isset($izin_pegawai->IS_SIGNED) ? $izin_pegawai->IS_SIGNED : "";
        Template::set('izin_pegawai', $izin_pegawai);

        $TAHUN      = trim(date("Y")." ");
        $jenis_izin = $this->jenis_izin_model->find($id);
        $nama_izin  = isset($jenis_izin->NAMA_IZIN) ? $jenis_izin->NAMA_IZIN : "";
        $keterangan_izin = isset($jenis_izin->KETERANGAN) ? $jenis_izin->KETERANGAN : "";
        $PERSETUJUAN      = isset($jenis_izin->PERSETUJUAN) ? $jenis_izin->PERSETUJUAN : "";
        
        if($id != ""){
            $this->sisa_cuti_model->where("sisa_cuti.TAHUN = '".$TAHUN."'");
            $data_cuti = $this->sisa_cuti_model->find_by("PNS_NIP",$ses_nip);
            Template::set('data_cuti',$data_cuti);    
        }
        $pegawai = $this->pegawai_model->find_detil_with_nip($ses_nip);
        $recpns_aktif = $this->Pns_aktif_model->find($pegawai->ID);
        Template::set("parent_path_array_unor",$this->unitkerja_model->get_parent_path($pegawai->UNOR_ID,true,true));

        $JABATAN_ID = $pegawai->JABATAN_INSTANSI_ID;
        $JABATAN_INSTANSI_REAL_ID = $pegawai->JABATAN_INSTANSI_REAL_ID;
        if($JABATAN_INSTANSI_REAL_ID != ""){
            $recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_INSTANSI_REAL_ID));
            Template::set('NAMA_JABATAN_REAL', $recjabatan->NAMA_JABATAN);
        }
        if($JABATAN_ID != ""){
            $recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_ID));
            Template::set('NAMA_JABATAN', $recjabatan->NAMA_JABATAN);
            Template::set('NAMA_JABATAN_BKN', $recjabatan->NAMA_JABATAN_BKN);
        }
        $foto_pegawai = trim($this->settings_lib->item('site.urlphoto'))."noimage.jpg";
        if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO)){
            $foto_pegawai =  trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO;
        }
        Template::set('foto_pegawai', $foto_pegawai);
        // CEK DI TABEL UNITKERJA
        $unor = $this->unitkerja_model->find_by("ID",trim($pegawai->UNOR_ID));
        $unor_induk = $this->unitkerja_model->find_by("ID",$unor->UNOR_INDUK);
        $verifikasidata = $this->get_data_verifikasi_izin($izin_pegawai->ID);
        $adata_pejabat = $this->get_data_pejabat($ses_nip);

        $this->load->library('pdf');
        $this->pdf->load_view('izin/formpdf',array("izin_pegawai" => $izin_pegawai,"aatasan" => $adata_pejabat,"verifikasidata" => $verifikasidata,"pegawai" => $pegawai,"recjabatan" => $recjabatan,"unor_induk" => $unor_induk,"jenis_izin"=>$jenis_izin));
        $this->pdf->render();
        if (!file_exists(trim($this->settings_lib->item('site.pathlampiranizin')).md5($kode_tabel).'.pdf')) {
            file_put_contents(trim($this->settings_lib->item('site.pathlampiranizin')).md5($kode_tabel).'.pdf', $this->pdf->output());
        }else{
            if($IS_SIGNED != "1"){// jika belum ditandatangani digital
                file_put_contents(trim($this->settings_lib->item('site.pathlampiranizin')).md5($kode_tabel).'.pdf', $this->pdf->output());
            }
        }
        //file_put_contents(trim($this->settings_lib->item('site.pathlampiranizin')).md5($kode_tabel).'.pdf', $this->pdf->output());
        $this->pdf->stream("formspd.pdf", array("Attachment" => 0));
    }
    private function create_pdf_form($kode_tabel = "")
    {
        $izin_pegawai = $this->izin_pegawai_model->find($kode_tabel);
        // print_r($izin_pegawai);
        $ses_nip = isset($izin_pegawai->NIP_PNS) ? $izin_pegawai->NIP_PNS : "";
        $id_jenis_izin = isset($izin_pegawai->KODE_IZIN) ? $izin_pegawai->KODE_IZIN : "";
        $IS_SIGNED = isset($izin_pegawai->IS_SIGNED) ? $izin_pegawai->IS_SIGNED : "";

        $jenis_izin = $this->jenis_izin_model->find($id_jenis_izin);
        $nama_izin  = isset($jenis_izin->NAMA_IZIN) ? $jenis_izin->NAMA_IZIN : "";
        $keterangan_izin = isset($jenis_izin->KETERANGAN) ? $jenis_izin->KETERANGAN : "";
        $PERSETUJUAN      = isset($jenis_izin->PERSETUJUAN) ? $jenis_izin->PERSETUJUAN : "";
        
        $pegawai = $this->pegawai_model->find_detil_with_nip($ses_nip);
        
        $JABATAN_ID = $pegawai->JABATAN_INSTANSI_ID;
        $JABATAN_INSTANSI_REAL_ID = $pegawai->JABATAN_INSTANSI_REAL_ID;
        if($JABATAN_INSTANSI_REAL_ID != ""){
            $recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_INSTANSI_REAL_ID));
        }
        if($JABATAN_ID != ""){
            $recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_ID));
        }
         
        // CEK DI TABEL UNITKERJA
        $unor = $this->unitkerja_model->find_by("ID",trim($pegawai->UNOR_ID));
        $unor_induk = $this->unitkerja_model->find_by("ID",$unor->UNOR_INDUK);
        $verifikasidata = $this->get_data_verifikasi_izin($izin_pegawai->ID);
        $adata_pejabat = $this->get_data_pejabat($ses_nip);

        $this->load->library('pdf');
        $this->pdf->load_view('izin/formpdf',array("izin_pegawai" => $izin_pegawai,"aatasan" => $adata_pejabat,"verifikasidata" => $verifikasidata,"pegawai" => $pegawai,"recjabatan" => $recjabatan,"unor_induk" => $unor_induk,"jenis_izin"=>$jenis_izin));
        $this->pdf->render();
        if (!file_exists(trim($this->settings_lib->item('site.pathlampiranizin')).md5($kode_tabel).'.pdf')) {
            file_put_contents(trim($this->settings_lib->item('site.pathlampiranizin')).md5($kode_tabel).'.pdf', $this->pdf->output());
        }else{
            if($IS_SIGNED != "1"){// jika belum ditandatangani digital
                file_put_contents(trim($this->settings_lib->item('site.pathlampiranizin')).md5($kode_tabel).'.pdf', $this->pdf->output());
            }
        }
        
        
    }
    public function edit1()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('izin_pegawai_invalid_id'), 'error');

            redirect(SITE_AREA . '/izin/izin_pegawai');
        }
        
        if (isset($_POST['save'])) {
            $this->auth->restrict($this->permissionEdit);

            if ($this->save_izin_pegawai('update', $id)) {
                log_activity($this->auth->user_id(), lang('izin_pegawai_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'izin_pegawai');
                Template::set_message(lang('izin_pegawai_edit_success'), 'success');
                redirect(SITE_AREA . '/izin/izin_pegawai');
            }

            // Not validation error
            if ( ! empty($this->izin_pegawai_model->error)) {
                Template::set_message(lang('izin_pegawai_edit_failure') . $this->izin_pegawai_model->error, 'error');
            }
        }
        
        elseif (isset($_POST['delete'])) {
            $this->auth->restrict($this->permissionDelete);

            if ($this->izin_pegawai_model->delete($id)) {
                log_activity($this->auth->user_id(), lang('izin_pegawai_act_delete_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'izin_pegawai');
                Template::set_message(lang('izin_pegawai_delete_success'), 'success');

                redirect(SITE_AREA . '/izin/izin_pegawai');
            }

            Template::set_message(lang('izin_pegawai_delete_failure') . $this->izin_pegawai_model->error, 'error');
        }
        
        Template::set('izin_pegawai', $this->izin_pegawai_model->find($id));

        Template::set('toolbar_title', lang('izin_pegawai_edit_heading'));
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
    private function save_izin_pegawai($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['ID'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->izin_pegawai_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->izin_pegawai_model->prep_data($this->input->post());

        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        
        $data['DARI_TANGGAL']   = $this->input->post('DARI_TANGGAL') ? $this->input->post('DARI_TANGGAL') : '0000-00-00';
        $data['SAMPAI_TANGGAL'] = $this->input->post('SAMPAI_TANGGAL') ? $this->input->post('SAMPAI_TANGGAL') : '0000-00-00';
        $data['TGL_DIBUAT'] = $this->input->post('TGL_DIBUAT') ? $this->input->post('TGL_DIBUAT') : '0000-00-00';

        $return = false;
        if ($type == 'insert') {
            $id = $this->izin_pegawai_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->izin_pegawai_model->update($id, $data);
        }

        return $return;
    }
    // List izin untuk pegawai
    public function getdata_izin(){
        $this->auth->restrict($this->permissionView);
        $this->load->library('Convert');
        $convert = new Convert;
        $this->load->helper('dikbud');
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/izin/izin_pegawai');
        }
        $ses_nip    = trim($this->auth->username());
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

        $selectedUnors = array();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            
        }
        $this->db->start_cache();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            if($filters['nama_key']){
                $this->izin_pegawai_model->like('upper("NAMA")',strtoupper(trim($filters['nama_key'])),"BOTH"); 
            }
            if($filters['jenis_izin']){
                $this->izin_pegawai_model->where("KODE_IZIN",trim($filters['jenis_izin']));  
            }
            if($filters['dari_tanggal']){
                $this->izin_pegawai_model->where("DARI_TANGGAL >= '".$filters['dari_tanggal']."'");  
            }
            if($filters['sampai_tanggal']){
                $this->izin_pegawai_model->where("SAMPAI_TANGGAL <= '".$filters['sampai_tanggal']."'");  
            }
            if($filters['no_surat']){
                $this->izin_pegawai_model->like("NO_SURAT",trim($filters['no_surat']),"BOTH");  
            }
            if($filters['status_pengajuan']){
                $this->izin_pegawai_model->where("STATUS_PENGAJUAN",trim($filters['status_pengajuan']));  
            }
        }
        
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total= $this->izin_pegawai_model->count_all_pegawai($ses_nip);
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->izin_pegawai_model->order_by("NIP_PNS",$order['dir']);
            }
            if($order['column']==2){
                $this->izin_pegawai_model->order_by("NAMA",$order['dir']);
            }
            if($order['column']==3){
                $this->izin_pegawai_model->order_by("TAHUN",$order['dir']);
            }
            if($order['column']==4){
                $this->izin_pegawai_model->order_by("SISA",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->izin_pegawai_model->limit($length,$start);
        $records=$this->izin_pegawai_model->find_all_pegawai($ses_nip);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();

                $STATUS_ATASAN = "";
                $kode_izin = $record->KODE_IZIN;
                if($record->STATUS_ATASAN != ""){
                    $STATUS_ATASAN = "<br>".get_status_cuti($record->STATUS_ATASAN);
                }
                $CATATAN_ATASAN = "";
                if($record->CATATAN_ATASAN != ""){
                    $CATATAN_ATASAN = "<br><i>".$record->CATATAN_ATASAN."</i>";
                }
                $STATUS_PYBMC = "";
                if($record->STATUS_PYBMC != ""){
                    $STATUS_PYBMC = "<br>".get_status_cuti($record->STATUS_PYBMC);
                }

                $STATUS_PENGAJUAN = get_status_cuti($record->STATUS_PENGAJUAN).$CATATAN_ATASAN;
                $CATATAN_PYBMC = "";
                if($record->CATATAN_ATASAN != ""){
                    $CATATAN_PYBMC = "<br><i>".$record->CATATAN_PYBMC."</i>";
                }
                $row []  = $nomor_urut.".";
                $row []  = $record->NAMA_IZIN;
                $row []  = $convert->fmtDate($record->TGL_DIBUAT,"dd month yyyy");
                $row []  = $convert->fmtDate($record->DARI_TANGGAL,"dd month yyyy")." - ".$convert->fmtDate($record->SAMPAI_TANGGAL,"dd month yyyy")."<br>".$record->JUMLAH." ".$record->SATUAN;
                //$row []  = $record->NAMA_ATASAN.$STATUS_ATASAN.$CATATAN_ATASAN;
                //$row []  = $record->NAMA_PYBMC.$STATUS_PYBMC.$CATATAN_PYBMC;
                if($record->KODE_IZIN == "11"){
                    $row []  = $record->ALASAN_CUTI."<br><i>".$record->KETERANGAN."<br><i>Dari jam ".$record->SELAMA_JAM." - ".$record->SELAMA_MENIT."</i>";
                }else{
                    $row []  = $record->ALASAN_CUTI."<br><i>".$record->KETERANGAN;    
                }
                $row []  = $STATUS_PENGAJUAN;
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/izin/izin_pegawai/lineaproval/".$record->KODE_IZIN."/".$record->ID."' class='btn btn-sm btn-warning show-modal' tooltip='Lihat status Persetujuan ".$record->NAMA_IZIN."' title='Lihat status Persetujuan' data-toggle='tooltip' data-placement='top' title='Lihat status Persetujuan'><i class='fa fa-info-circle'></i> </a>";  
                if($kode_izin == "1" or $kode_izin == "2" or $kode_izin == "3" or $kode_izin == "4" or $kode_izin == "5" or $kode_izin == "6"){
                    if($record->IS_SIGNED == "1"){
                        $btn_actions  [] = "<a href='".base_url()."admin/izin/izin_pegawai/viewdoc/".$record->ID."' data-toggle='tooltip' title='Lihat Dokumen cuti ttd' tooltip='Lihat Dokumen Cuti' class='btn btn-sm btn-success show-modal'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                    }else{
                        $btn_actions  [] = "<a url='".base_url()."admin/izin/izin_pegawai/formpdf/".$record->KODE_IZIN."/".$record->ID."' class='btn btn-sm btn-info popup' data-toggle='tooltip' data-placement='top' title='Dokumen Cuti'><i class='fa fa-file-o'></i> </a>";      
                    }
                    
                }
                if($record->LAMPIRAN_FILE != ""){
                    $url_lampiran = $record->source == 2 ? "https://geo-kehadiran.kemdikbud.go.id/lampiran/" : trim($this->settings_lib->item('site.urllampiranizin'));
                    $btn_actions  [] = "<a url='".trim($url_lampiran).$record->LAMPIRAN_FILE."' class='btn btn-sm btn-info popup' data-toggle='tooltip' data-placement='top' title='Lihat Lampiran'><i class='fa fa-paperclip'></i> </a>"; 
                }
                /*
                if($this->auth->has_permission($this->permissionEdit)){
                    $btn_actions  [] = "<a href='".base_url()."admin/izin/izin_pegawai/edit/".$record->KODE_IZIN."/".$record->ID."' class='btn btn-sm btn-warning show-modal' tooltip='Edit Permintaan Izin ".$record->NAMA_IZIN."' title='Edit Permintaan Cuti' data-toggle='tooltip' data-placement='top' title='Edit data'><i class='glyphicon glyphicon-edit'></i> </a>";  
                }
                */ 
                if($this->auth->has_permission($this->permissionDelete)){
                    $btn_actions  [] = "<a kode='$record->ID' class='btn btn-sm btn-danger btn-hapus' data-toggle='tooltip' data-placement='top' data-original-title='Hapus data' title='Hapus data' tooltip='Hapus'><i class='glyphicon glyphicon-remove'></i> </a>";
                }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);        
    }
    public function getdata_izin_pegawai(){
        $this->auth->restrict($this->permissionView);
        $this->SATKER_ID = $this->pegawai_model->getunor_induk($this->auth->username());
        $this->load->helper('dikbud');
        $this->load->library('Convert');
        $convert = new Convert;
        if (!$this->input->is_ajax_request()) {
            // Template::set_message("Hanya request ajax", 'error');
            // redirect(SITE_AREA . '/izin/izin_pegawai');
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";
        $this->db->start_cache();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
             
            if($filters['nama_key']){
                $this->db->group_start();
                $this->izin_pegawai_model->like('upper("NAMA")',strtoupper(trim($filters['nama_key'])),"BOTH"); 
                $this->izin_pegawai_model->or_like('upper("NIP_PNS")',strtoupper(trim($filters['nama_key'])),"BOTH"); 
                $this->db->group_end();
            }
            if($filters['jenis_izin']){
                $this->izin_pegawai_model->where("KODE_IZIN",trim($filters['jenis_izin']));  
            }
            if($filters['dari_tanggal']){
                $this->izin_pegawai_model->where("DARI_TANGGAL >= '".$filters['dari_tanggal']."'");  
            }
            if($filters['sampai_tanggal']){
                $this->izin_pegawai_model->where("SAMPAI_TANGGAL <= '".$filters['sampai_tanggal']."'");  
            }
            if($filters['sampai_tanggal']){
                $this->izin_pegawai_model->like("NO_SURAT",trim($filters['no_surat']),"BOTH");  
            }
            if($filters['no_surat']){
                $this->izin_pegawai_model->like("NO_SURAT",trim($filters['no_surat']),"BOTH");  
            }
        }
        $this->izin_pegawai_model->where("STATUS_PENGAJUAN",3);  
        if($this->SATKER_ID != "")
            $this->izin_pegawai_model->where("UNIT_KERJA",$this->SATKER_ID);
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total= $this->izin_pegawai_model->count_all();
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==2){
                $this->izin_pegawai_model->order_by("NIP_PNS",$order['dir']);
            }
            if($order['column']==3){
                $this->izin_pegawai_model->order_by("KODE_IZIN",$order['dir']);
            }
            if($order['column']==4){
                $this->izin_pegawai_model->order_by("DARI_TANGGAL",$order['dir']);
            }
            
            if($order['column']==6){
                $this->izin_pegawai_model->order_by("STATUS_PENGAJUAN",$order['dir']);
            }
             
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->izin_pegawai_model->limit($length,$start);
        $records=$this->izin_pegawai_model->find_all();
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $foto_pegawai = base_url().trim($this->settings_lib->item('site.urlphoto'))."noimage.jpg";
                if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$record->PHOTO) and $record->PHOTO != ""){
                    $foto_pegawai =  base_url().trim($this->settings_lib->item('site.urlphoto')).$record->PHOTO;
                }
                $row = array();
                $STATUS_ATASAN = "";
                if($record->STATUS_ATASAN != ""){
                    $STATUS_ATASAN = "<br>".get_status_cuti($record->STATUS_ATASAN);
                }
                $CATATAN_ATASAN = "";
                if($record->CATATAN_ATASAN != ""){
                    $CATATAN_ATASAN = "<br><i>".$record->CATATAN_ATASAN."</i>";
                }
                $STATUS_PYBMC = "";
                if($record->STATUS_PYBMC != ""){
                    $STATUS_PYBMC = "<br>".get_status_cuti($record->STATUS_PYBMC);
                }
                $CATATAN_PYBMC = "";
                if($record->CATATAN_ATASAN != ""){
                    $CATATAN_PYBMC = "<br><i>".$record->CATATAN_PYBMC."</i>";
                }
                $STATUS_PENGAJUAN = get_status_cuti($record->STATUS_PENGAJUAN);
                $row []  = $nomor_urut.".";
                // $row []  = "<img src='".$foto_pegawai."' width='50%'>";
                $row []  = $record->NIP_PNS."<br><i>".$record->NAMA."</i>";
                $row []  = $record->NAMA_IZIN;
                $row []  = $convert->fmtDate($record->TGL_DIBUAT,"dd month yyyy");
                $row []  = $convert->fmtDate($record->DARI_TANGGAL,"dd month yyyy")." - ".$convert->fmtDate($record->SAMPAI_TANGGAL,"dd month yyyy")."<br>".$record->JUMLAH." ".$record->SATUAN;
                //$row []  = $record->NAMA_ATASAN.$STATUS_ATASAN.$CATATAN_ATASAN;
                //$row []  = $record->NAMA_PYBMC.$STATUS_PYBMC.$CATATAN_PYBMC;
                $row []  = $record->KETERANGAN;
                $row []  = $STATUS_PENGAJUAN;
                $btn_actions = array();
                
                $btn_actions  [] = "<a href='".base_url()."admin/izin/izin_pegawai/lineaproval/".$record->KODE_IZIN."/".$record->ID."' class='btn btn-sm btn-warning show-modal' tooltip='Line Approval ".$record->NAMA_IZIN."' title='Line Approval' data-toggle='tooltip' data-placement='top' title='Line Approval'><i class='fa fa-info-circle'></i> </a>";  
                if($record->LAMPIRAN_FILE != ""){
                    $url_lampiran = $record->source == 2 ? "https://geo-kehadiran.kemdikbud.go.id/lampiran/" : trim($this->settings_lib->item('site.urllampiranizin'));
                    $btn_actions  [] = "<a url='".trim($url_lampiran).$record->LAMPIRAN_FILE."' class='btn btn-sm btn-info popup' data-toggle='tooltip' data-placement='top' title='Lihat Lampiran'><i class='fa fa-paperclip'></i> </a>"; 
                }
                if($record->ID != "" and strpos(strtolower($record->NAMA_IZIN), "cuti") !== false){
                    $btn_actions  [] = "<a url='".base_url()."admin/izin/izin_pegawai/formpdf/".$record->KODE_IZIN."/".$record->ID."' class='btn btn-sm btn-success popup' data-toggle='tooltip' data-placement='top' title='Lihat Dokumen cuti'><i class='glyphicon glyphicon-open-file'></i> </a>";  
                }
                if($this->auth->has_permission($this->permissionDelete)){
                    $btn_actions  [] = "<a kode='$record->ID' class='btn btn-sm btn-danger btn-hapus' data-toggle='tooltip' data-placement='top' data-original-title='Hapus data' title='Hapus data' tooltip='Hapus'><i class='glyphicon glyphicon-remove'></i> </a>";
                }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);         
    }
    public function getdata_izin_all(){
        $this->auth->restrict($this->permissionView);
        $this->load->helper('dikbud');
        if (!$this->input->is_ajax_request()) {
            // Template::set_message("Hanya request ajax", 'error');
            // redirect(SITE_AREA . '/izin/izin_pegawai');
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

        $selectedUnors = array();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            
        }

        $this->db->start_cache();
        
        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
             
            if($filters['nama_key']){
                $this->izin_pegawai_model->like('upper("NAMA")',strtoupper(trim($filters['nama_key'])),"BOTH"); 
            }
            if($filters['nip_key']){
                $this->izin_pegawai_model->where("NIP_PNS",trim($filters['nip_key']));  
            }
            if($filters['STATUS']){
                $this->izin_pegawai_model->where("STATUS_PENGAJUAN",trim($filters['STATUS']));  
            }
            if($filters['status_kirim']){
                if($filters['status_kirim'] == "-")
                    $this->izin_pegawai_model->where("status_kirim is NULL",NULL, false);  
                if($filters['status_kirim'] == "1")
                    $this->izin_pegawai_model->where("status_kirim",trim($filters['status_kirim']));  
            }
        }
        
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total= $this->izin_pegawai_model->count_all();
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==2){
                $this->izin_pegawai_model->order_by("NIP_PNS",$order['dir']);
            }
            if($order['column']==3){
                $this->izin_pegawai_model->order_by("KODE_IZIN",$order['dir']);
            }
            if($order['column']==4){
                $this->izin_pegawai_model->order_by("DARI_TANGGAL",$order['dir']);
            }
            
            if($order['column']==6){
                $this->izin_pegawai_model->order_by("STATUS_VERIFIKASI",$order['dir']);
            }
             
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->izin_pegawai_model->limit($length,$start);
        $records=$this->izin_pegawai_model->find_all();
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $classStatusKirim = $record->status_kirim == "1" ? "success" : "warning";
                $foto_pegawai = base_url().trim($this->settings_lib->item('site.urlphoto'))."noimage.jpg";
                if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$record->PHOTO) and $record->PHOTO != ""){
                    $foto_pegawai =  base_url().trim($this->settings_lib->item('site.urlphoto')).$record->PHOTO;
                }
                $row = array();
                $CATATAN_ATASAN = "";
                if($record->CATATAN_ATASAN != ""){
                    $CATATAN_ATASAN = "<br><i>".$record->CATATAN_ATASAN."</i>";
                }

                $STATUS_ATASAN = "";
                if($record->STATUS_ATASAN != ""){
                    $STATUS_ATASAN = "<br>".get_status_cuti($record->STATUS_ATASAN).$CATATAN_ATASAN;
                }
                
                $STATUS_PYBMC = "";
                if($record->STATUS_PYBMC != ""){
                    $STATUS_PYBMC = "<br>".get_status_cuti($record->STATUS_PYBMC);
                }
                $CATATAN_PYBMC = "";
                if($record->CATATAN_ATASAN != ""){
                    $CATATAN_PYBMC = "<br><i>".$record->CATATAN_PYBMC."</i>";
                }
                $STATUS_PENGAJUAN = get_status_cuti($record->STATUS_PENGAJUAN).$CATATAN_ATASAN;
                $row []  = $nomor_urut.".";
                $row []  = "<img src='".$foto_pegawai."' width='100%'>";
                $row []  = $record->NIP_PNS."<br><i>".$record->NAMA."</i>";
                $row []  = $record->NAMA_IZIN."<br><i>".$record->JUMLAH." ".$record->SATUAN."</i>";
                $row []  = $record->DARI_TANGGAL."<br>s/d<br>".$record->SAMPAI_TANGGAL;
                //$row []  = $record->NAMA_ATASAN.$STATUS_ATASAN.$CATATAN_ATASAN;
                //$row []  = $record->NAMA_PYBMC.$STATUS_PYBMC.$CATATAN_PYBMC;
                $row []  = isset($record->ALASAN_CUTI) ? $record->ALASAN_CUTI."<br>".$record->KETERANGAN : $record->KETERANGAN;
                $row []  = $STATUS_PENGAJUAN;
                $btn_actions = array();
                
                $btn_actions  [] = "<a href='".base_url()."admin/izin/izin_pegawai/lineaproval/".$record->KODE_IZIN."/".$record->ID."' class='btn btn-sm btn-warning show-modal' tooltip='Line Approval ".$record->NAMA_IZIN."' title='Line Approval' data-toggle='tooltip' data-placement='top' title='Line Approval'><i class='fa fa-info-circle'></i> </a>";  
                if($record->LAMPIRAN_FILE != ""){
                    $url_lampiran = $record->source == 2 ? "https://geo-kehadiran.kemdikbud.go.id/lampiran/" : trim($this->settings_lib->item('site.urllampiranizin'));
                    $btn_actions  [] = "<a url='".trim($url_lampiran).$record->LAMPIRAN_FILE."' class='btn btn-sm btn-info popup' data-toggle='tooltip' data-placement='top' title='Lihat Lampiran'><i class='fa fa-paperclip'></i> </a>"; 
                }
                if($record->ID != "" and strpos(strtolower($record->NAMA_IZIN), "cuti") !== false){
                    $btn_actions  [] = "<a url='".base_url()."admin/izin/izin_pegawai/formpdf/".$record->KODE_IZIN."/".$record->ID."' class='btn btn-sm btn-danger popup' data-toggle='tooltip' data-placement='top' title='Lihat Dokumen cuti'><i class='glyphicon glyphicon-open-file'></i> </a>";  
                }
                if($this->auth->has_permission($this->permissionDelete)){
                    $btn_actions  [] = "<a kode='$record->ID' class='btn btn-sm btn-danger btn-hapus' data-toggle='tooltip' data-placement='top' data-original-title='Hapus data' title='Hapus data' tooltip='Hapus'><i class='glyphicon glyphicon-remove'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionKirimKehadiran)){
                    $btn_actions  [] = "<a kode='$record->ID' class='btn btn-sm btn-".$classStatusKirim." send-kehadiran' data-toggle='tooltip' data-placement='top' data-original-title='Kirim ke e-kehadiran' title='Kirim ke e-kehadiran' tooltip='Kirim ke e-kehadiran'><i class='glyphicon glyphicon-forward'></i> </a>";
                }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);         
    }
    public function getdata_rekap_izin(){
        $this->auth->restrict($this->permissionView);
        $ses_nip    = trim($this->auth->username());
        $this->load->helper('dikbud');
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/izin/izin_pegawai/laporanPegawai');
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

        $selectedUnors = array();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            
        }

        $this->db->start_cache();
        
        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
             
            if($filters['nama_key']){
                $this->izin_pegawai_model->like('upper("NAMA")',strtoupper(trim($filters['nama_key'])),"BOTH"); 
            }
            if($filters['nip_key']){
                $this->izin_pegawai_model->where("NIP_PNS",trim($filters['nip_key']));  
            }
            if($filters['STATUS_ATASAN']){
                $this->izin_pegawai_model->where("STATUS_ATASAN",trim($filters['STATUS_ATASAN']));  
            }
            if($filters['STATUS_PYBMC']){
                $this->izin_pegawai_model->where("STATUS_PENGAJUAN",trim($filters['STATUS_PYBMC']));  
            }
            if($filters['DARI_TANGGAL']){
                $this->db->group_start();
                $this->izin_pegawai_model->where("DARI_TANGGAL >= '{$filters['DARI_TANGGAL']}'");  
                $this->izin_pegawai_model->where("SAMPAI_TANGGAL <= '{$filters['SAMPAI_TANGGAL']}'");  
                $this->db->group_end();
            }
            
        }
        
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total= $this->izin_pegawai_model->count_rekap($ses_nip);
        $orders = $this->input->post('order');
        foreach($orders as $order){
             
            if($order['column']==1){
                $this->izin_pegawai_model->order_by("NAMA_IZIN",$order['dir']);
            }
             
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->izin_pegawai_model->limit($length,$start);
        $records=$this->izin_pegawai_model->find_rekap($ses_nip);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                 
                $row []  = $nomor_urut.".";
                $row []  = $record->NAMA_IZIN;
                $row []  = $record->jumlah_pengajuan;
                $row []  = $record->jumlah_hari;
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);        
    }
    //satker
    public function getdata_izin_satker(){
        $this->auth->restrict($this->permissionView);
        $UNIT_KERJA = $this->pegawai_model->getunor_induk($this->auth->username());
        $this->load->helper('dikbud');
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/izin/izin_pegawai');
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

        $selectedUnors = array();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
        }
        $this->db->start_cache();
        
        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
             
            if($filters['nama_key']){
                $this->izin_pegawai_model->like('upper(pegawai."NAMA")',strtoupper(trim($filters['nama_key'])),"BOTH"); 
            }
            if($filters['nip_key']){
                $this->izin_pegawai_model->where("NIP_PNS",trim($filters['nip_key']));  
            }
            if($filters['sampai_tanggal'] & $filters['sampai_tanggal']){
                $this->db->group_start();
                    $this->izin_pegawai_model->where("DARI_TANGGAL >= '{$filters['dari_tanggal']}'");  
                    $this->izin_pegawai_model->where("SAMPAI_TANGGAL <= '{$filters['sampai_tanggal']}'");  
                    $this->izin_pegawai_model->or_where("DARI_TANGGAL >= '{$filters['dari_tanggal']}'");  
                    $this->izin_pegawai_model->where("DARI_TANGGAL <= '{$filters['sampai_tanggal']}'");  
                $this->db->group_end();
                // $this->db->group_start();
                //     $this->izin_pegawai_model->or_where("DARI_TANGGAL >= '{$filters['dari_tanggal']}'");  
                //     $this->izin_pegawai_model->where("DARI_TANGGAL <= '{$filters['sampai_tanggal']}'");  
                // $this->db->group_end();
            }else{
                if($filters['dari_tanggal']){
                    $this->izin_pegawai_model->where("DARI_TANGGAL >= '".$filters['dari_tanggal']."'");  
                }
                if($filters['sampai_tanggal']){
                    $this->izin_pegawai_model->where("SAMPAI_TANGGAL <= '".$filters['sampai_tanggal']."'");  
                }
            }
            if($filters['jenis_izin']){
                $this->izin_pegawai_model->where("KODE_IZIN",trim($filters['jenis_izin']));  
            }
            if($filters['STATUS']){
                $this->izin_pegawai_model->where("STATUS_PENGAJUAN",trim($filters['STATUS']));  
            }
        }
        
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total= $this->izin_pegawai_model->count_all_satker($UNIT_KERJA);
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->izin_pegawai_model->order_by("NIP_PNS",$order['dir']);
            }
            if($order['column']==2){
                $this->izin_pegawai_model->order_by("NAMA",$order['dir']);
            }
            if($order['column']==3){
                $this->izin_pegawai_model->order_by("TAHUN",$order['dir']);
            }
            if($order['column']==4){
                $this->izin_pegawai_model->order_by("DARI_TANGGAL",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->izin_pegawai_model->limit($length,$start);
        $records=$this->izin_pegawai_model->find_all_satker($UNIT_KERJA);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $classStatusKirim = $record->status_kirim == "1" ? "success" : "warning";
                $foto_pegawai = base_url().trim($this->settings_lib->item('site.urlphoto'))."noimage.jpg";
                if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$record->PHOTO) and $record->PHOTO != ""){
                    $foto_pegawai =  base_url().trim($this->settings_lib->item('site.urlphoto')).$record->PHOTO;
                }
                $row = array();
                $STATUS_ATASAN = "";
                if($record->STATUS_ATASAN != ""){
                    $STATUS_ATASAN = "<br>".get_status_cuti($record->STATUS_ATASAN);
                }
                $CATATAN_ATASAN = "";
                if($record->CATATAN_ATASAN != ""){
                    $CATATAN_ATASAN = "<br><i>".$record->CATATAN_ATASAN."</i>";
                }
                $STATUS_PYBMC = "";
                if($record->STATUS_PYBMC != ""){
                    $STATUS_PYBMC = "<br>".get_status_cuti($record->STATUS_PYBMC);
                }
                $CATATAN_PYBMC = "";
                if($record->CATATAN_ATASAN != ""){
                    $CATATAN_PYBMC = "<br><i>".$record->CATATAN_PYBMC."</i>";
                }
                $STATUS_PENGAJUAN = get_status_cuti($record->STATUS_PENGAJUAN).$CATATAN_ATASAN;
                $row []  = $nomor_urut.".";
                $row []  = "<img src='".$foto_pegawai."' width='100%'>";
                $row []  = $record->NIP_PNS."<br><i>".$record->NAMA."</i>";
                $row []  = $record->NAMA_IZIN."<br><i>".$record->JUMLAH." ".$record->SATUAN."</i>";
                $row []  = $record->DARI_TANGGAL."<br>s/d<br>".$record->SAMPAI_TANGGAL;
                //$row []  = $record->NAMA_ATASAN.$STATUS_ATASAN.$CATATAN_ATASAN;
                //$row []  = $record->NAMA_PYBMC.$STATUS_PYBMC.$CATATAN_PYBMC;
                if($record->KODE_IZIN == "11"){
                    $row []  = $record->ALASAN_CUTI."<br><i>".$record->KETERANGAN."<br><i>Dari jam ".$record->SELAMA_JAM." - ".$record->SELAMA_MENIT."</i>";
                }else{
                    $row []  = $record->ALASAN_CUTI."<br><i>".$record->KETERANGAN;    
                }
                $row []  = $STATUS_PENGAJUAN;
                $btn_actions = array();
                if ($this->auth->has_permission($this->permissionVerifikasi) || $this->is_pejabat_cuti && ($record->STATUS_PENGAJUAN == 3)) {
                    $btn_actions  [] = "<a href='".base_url()."admin/izin/izin_pegawai/form_tangguhkan/".$record->ID."' class='btn btn-sm btn-success show-modal' tooltip='Tangguhkan Permintaan Izin' data-toggle='tooltip' data-placement='top' title='Tangguhkan Permintaan Izin'><i class='glyphicon glyphicon-pencil'></i> </a>";  
                }
                $btn_actions  [] = "<a href='".base_url()."admin/izin/izin_pegawai/lineaproval/".$record->KODE_IZIN."/".$record->ID."' class='btn btn-sm btn-danger show-modal' tooltip='Line Approval ".$record->NAMA_IZIN."' title='Line Approval' data-toggle='tooltip' data-placement='top' title='Line Approval'><i class='fa fa-info-circle'></i> </a>";  
                if($record->LAMPIRAN_FILE != ""){
                    $url_lampiran = $record->source == 2 ? "https://geo-kehadiran.kemdikbud.go.id/lampiran/" : trim($this->settings_lib->item('site.urllampiranizin'));
                    $btn_actions  [] = "<a url='".trim($url_lampiran).$record->LAMPIRAN_FILE."' class='btn btn-sm btn-info popup' data-toggle='tooltip' data-placement='top' title='Lihat Lampiran'><i class='fa fa-paperclip'></i> </a>"; 
                }
                if($record->ID != "" and strpos(strtolower($record->NAMA_IZIN), "cuti") !== false){
                    $btn_actions  [] = "<a url='".base_url()."admin/izin/izin_pegawai/formpdf/".$record->KODE_IZIN."/".$record->ID."' class='btn btn-sm btn-warning popup' data-toggle='tooltip' data-placement='top' title='Lihat Dokumen cuti'><i class='glyphicon glyphicon-open-file'></i> </a>";  
                }
                if($this->auth->has_permission($this->permissionKirimKehadiran)){
                    $btn_actions  [] = "<a kode='$record->ID' class='btn btn-sm btn-".$classStatusKirim." send-kehadiran' data-toggle='tooltip' data-placement='top' data-original-title='Kirim ke e-kehadiran' title='Kirim ke e-kehadiran' tooltip='Kirim ke e-kehadiran'><i class='glyphicon glyphicon-forward'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionDelete)){
                    $btn_actions  [] = "<a kode='$record->ID' class='btn btn-sm btn-danger btn-hapus' data-toggle='tooltip' data-placement='top' data-original-title='Hapus data' title='Hapus data' tooltip='Hapus'><i class='glyphicon glyphicon-remove'></i> </a>";
                }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);        
    }
    public function getdata_izin_matrik_satker(){
        $this->auth->restrict($this->permissionView);
        $UNIT_KERJA = $this->pegawai_model->getunor_induk($this->auth->username());
        $adata_matriks = $this->get_pegawai_status_pengajuan($UNIT_KERJA);
        $this->load->helper('dikbud');
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/izin/izin_pegawai');
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

        $selectedUnors = array();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            
        }

        $this->db->start_cache();
    
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
             
            if($filters['nama_key']){
                $this->izin_pegawai_model->like('upper("NAMA")',strtoupper(trim($filters['nama_key'])),"BOTH"); 
            }
            if($filters['nip_key']){
                $this->izin_pegawai_model->where("NIP_PNS",trim($filters['nip_key']));  
            }
            if($filters['dari_tanggal']){
                $this->izin_pegawai_model->where("DARI_TANGGAL >= '".$filters['dari_tanggal']."'");  
            }
            if($filters['sampai_tanggal']){
                $this->izin_pegawai_model->where("SAMPAI_TANGGAL <= '".$filters['sampai_tanggal']."'");  
            }
            if($filters['jenis_izin']){
                $this->izin_pegawai_model->where("KODE_IZIN",trim($filters['jenis_izin']));  
            }
            if($filters['STATUS']){
                $this->izin_pegawai_model->where("STATUS_PENGAJUAN",trim($filters['STATUS']));  
            }
        }
        
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total = count($this->izin_pegawai_model->find_group_pegawai($UNIT_KERJA));
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->izin_pegawai_model->order_by("NIP_PNS",$order['dir']);
            }
            if($order['column']==2){
                $this->izin_pegawai_model->order_by("NAMA",$order['dir']);
            }
           
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->izin_pegawai_model->limit($length,$start);
        $records = $this->izin_pegawai_model->find_group_pegawai($UNIT_KERJA);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $foto_pegawai = base_url().trim($this->settings_lib->item('site.urlphoto'))."noimage.jpg";
                if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$record->PHOTO) and $record->PHOTO != ""){
                    $foto_pegawai =  base_url().trim($this->settings_lib->item('site.urlphoto')).$record->PHOTO;
                }
                $row = array();
                $row []  = $nomor_urut.".";
                // $row []  = "<img src='".$foto_pegawai."' width='100%'>";
                $row []  = $record->NIP_PNS."<br><b><i>".$record->NAMA."</i></b>";
                $row []  = isset($adata_matriks[$record->NIP_PNS."_1"]) ? $adata_matriks[$record->NIP_PNS."_1"] : 0;
                $row []  = isset($adata_matriks[$record->NIP_PNS."_3"]) ? $adata_matriks[$record->NIP_PNS."_3"] : 0;
                $row []  = isset($adata_matriks[$record->NIP_PNS."_4"]) ? $adata_matriks[$record->NIP_PNS."_4"] : 0;
                $row []  = isset($adata_matriks[$record->NIP_PNS."_5"]) ? $adata_matriks[$record->NIP_PNS."_5"] : 0;
                $row []  = $record->jumlah;
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);        
    }
    public function getdata_izin_matrik_atasan(){
        $this->auth->restrict($this->permissionView);
        $ses_nip    = trim($this->auth->username());
        $UNIT_KERJA = $this->pegawai_model->getunor_induk($this->auth->username());
        $adata_matriks = $this->get_pegawai_status_pengajuan($UNIT_KERJA);
        $this->load->helper('dikbud');
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/izin/izin_pegawai');
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

        $selectedUnors = array();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            
        }

        $this->db->start_cache();
    
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
             
            if($filters['nama_key']){
                $this->izin_pegawai_model->like('upper("NAMA")',strtoupper(trim($filters['nama_key'])),"BOTH"); 
            }
            if($filters['nip_key']){
                $this->izin_pegawai_model->where("NIP_PNS",trim($filters['nip_key']));  
            }
            if($filters['dari_tanggal']){
                $this->izin_pegawai_model->where("DARI_TANGGAL >= '".$filters['dari_tanggal']."'");  
            }
            if($filters['sampai_tanggal']){
                $this->izin_pegawai_model->where("SAMPAI_TANGGAL <= '".$filters['sampai_tanggal']."'");  
            }
            if($filters['jenis_izin']){
                $this->izin_pegawai_model->where("KODE_IZIN",trim($filters['jenis_izin']));  
            }
            if($filters['STATUS']){
                $this->izin_pegawai_model->where("STATUS_PENGAJUAN",trim($filters['STATUS']));  
            }
        }
        
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total = count($this->izin_pegawai_model->find_group_pegawai_atasan($UNIT_KERJA,$ses_nip));
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->izin_pegawai_model->order_by("NIP_PNS",$order['dir']);
            }
            if($order['column']==2){
                $this->izin_pegawai_model->order_by("NAMA",$order['dir']);
            }
           
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->izin_pegawai_model->limit($length,$start);
        $records = $this->izin_pegawai_model->find_group_pegawai_atasan($UNIT_KERJA,$ses_nip);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $foto_pegawai = base_url().trim($this->settings_lib->item('site.urlphoto'))."noimage.jpg";
                if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$record->PHOTO) and $record->PHOTO != ""){
                    $foto_pegawai =  base_url().trim($this->settings_lib->item('site.urlphoto')).$record->PHOTO;
                }
                $row = array();
                $row []  = $nomor_urut.".";
                // $row []  = "<img src='".$foto_pegawai."' width='100%'>";
                $row []  = $record->NIP_PNS."<br><b><i>".$record->NAMA."</i></b>";
                $row []  = isset($adata_matriks[$record->NIP_PNS."_1"]) ? $adata_matriks[$record->NIP_PNS."_1"] : 0;
                $row []  = isset($adata_matriks[$record->NIP_PNS."_3"]) ? $adata_matriks[$record->NIP_PNS."_3"] : 0;
                $row []  = isset($adata_matriks[$record->NIP_PNS."_4"]) ? $adata_matriks[$record->NIP_PNS."_4"] : 0;
                $row []  = isset($adata_matriks[$record->NIP_PNS."_5"]) ? $adata_matriks[$record->NIP_PNS."_5"] : 0;
                $row []  = $record->jumlah;
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);        
    }
    public function getdata_verifikasi_izin(){
        if (!$this->auth->has_permission($this->permissionVerifikasi) and !$this->is_pejabat_cuti) {
            Template::set_message("Anda tidak punya akses persetujuan cuti/izin", 'error');
            die("masuk");
            // redirect(SITE_AREA . '/izin/izin_pegawai/perizinan');
        }
        $ses_nip    = trim($this->auth->username());
        $this->load->helper('dikbud');
        if (!$this->input->is_ajax_request()) {
            // Template::set_message("Hanya request ajax", 'error');
            // redirect(SITE_AREA . '/izin/izin_pegawai');
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

        $selectedUnors = array();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            
        }
        $this->db->start_cache();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            if($filters['nama_key']){
                $this->izin_pegawai_model->like('upper("NAMA")',strtoupper(trim($filters['nama_key'])),"BOTH"); 
            }
            if($filters['nip_key']){
                $this->izin_pegawai_model->where("NIP_PNS",trim($filters['nip_key']));  
            }
            if($filters['STATUS']){
                $this->izin_pegawai_model->where("STATUS_PENGAJUAN",trim($filters['STATUS']));  
            }
        }
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total= $this->izin_pegawai_model->count_all_atasan($ses_nip);
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==2){
                $this->izin_pegawai_model->order_by("NIP_PNS",$order['dir']);
            }
            if($order['column']==3){
                $this->izin_pegawai_model->order_by("KODE_IZIN",$order['dir']);
            }
            if($order['column']==4){
                $this->izin_pegawai_model->order_by("DARI_TANGGAL",$order['dir']);
            }
            
            if($order['column']==6){
                $this->izin_pegawai_model->order_by("STATUS_VERIFIKASI",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->izin_pegawai_model->limit($length,$start);
        $records=$this->izin_pegawai_model->find_all_atasan($ses_nip);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $foto_pegawai = base_url().trim($this->settings_lib->item('site.urlphoto'))."noimage.jpg";
                if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$record->PHOTO) and $record->PHOTO != ""){
                    $foto_pegawai =  base_url().trim($this->settings_lib->item('site.urlphoto')).$record->PHOTO;
                }
                $row = array();
                $STATUS_ATASAN = "";
                if($record->STATUS_ATASAN != ""){
                    $STATUS_ATASAN = "<br>".get_status_cuti($record->STATUS_ATASAN);
                }
                $CATATAN_ATASAN = "";
                if($record->CATATAN_ATASAN != ""){
                    $CATATAN_ATASAN = "<br><i>".$record->CATATAN_ATASAN."</i>";
                }
                $STATUS_PYBMC = "";
                if($record->STATUS_PYBMC != ""){
                    $STATUS_PYBMC = "<br>".get_status_cuti($record->STATUS_PYBMC);
                }
                $CATATAN_PYBMC = "";
                if($record->CATATAN_ATASAN != ""){
                    $CATATAN_PYBMC = "<br><i>".$record->CATATAN_PYBMC."</i>";
                }
                $STATUS_PENGAJUAN = get_status_cuti($record->STATUS_PENGAJUAN);
                $row []  = $nomor_urut.".";
                $row []  = "<img src='".$foto_pegawai."' width='100%'>";
                $row []  = $record->NIP_PNS."<br><i>".$record->NAMA."</i>";
                $row []  = $record->NAMA_IZIN."<br><i>".$record->JUMLAH." ".$record->SATUAN."</i>";
                $row []  = $record->DARI_TANGGAL."<br>s/d<br>".$record->SAMPAI_TANGGAL;
                //$row []  = $record->NAMA_ATASAN.$STATUS_ATASAN.$CATATAN_ATASAN;
                //$row []  = $record->NAMA_PYBMC.$STATUS_PYBMC.$CATATAN_PYBMC;
                if($record->KODE_IZIN == "11"){
                    $row []  = $record->ALASAN_CUTI."<br><i>".$record->KETERANGAN."<br><i>Dari jam ".$record->SELAMA_JAM." - ".$record->SELAMA_MENIT."</i>";
                }else{
                    $row []  = $record->ALASAN_CUTI."<br><i>".$record->KETERANGAN;    
                }
                $row []  = $STATUS_PENGAJUAN;
                $btn_actions = array();
                if($this->auth->has_permission($this->permissionVerifikasi) || $this->is_pejabat_cuti){
                    $btn_actions  [] = "<a href='".base_url()."admin/izin/izin_pegawai/verifikasiusulan/".$record->KODE_IZIN."/".$record->ID_VERIFIKASI."' class='btn btn-sm btn-success show-modal' tooltip='Verifikasi Permintaan Izin' data-toggle='tooltip' data-placement='top' title='Verifikasi Permintaan Izin'><i class='glyphicon glyphicon-pencil'></i> </a>";  
                }
                $btn_actions  [] = "<a href='".base_url()."admin/izin/izin_pegawai/lineaproval/".$record->KODE_IZIN."/".$record->ID."' class='btn btn-sm btn-warning show-modal' tooltip='Line Approval ".$record->NAMA_IZIN."' title='Line Approval' data-toggle='tooltip' data-placement='top' title='Line Approval'><i class='fa fa-info-circle'></i> </a>";  
                if($record->LAMPIRAN_FILE != ""){
                    $url_lampiran = $record->source == 2 ? "https://geo-kehadiran.kemdikbud.go.id/lampiran/" : trim($this->settings_lib->item('site.urllampiranizin'));
                    $btn_actions  [] = "<a url='".trim($url_lampiran).$record->LAMPIRAN_FILE."' class='btn btn-sm btn-info popup' data-toggle='tooltip' data-placement='top' title='Lihat Lampiran'><i class='fa fa-paperclip'></i> </a>"; 
                }
                if($record->ID != "" and strpos(strtolower($record->NAMA_IZIN), "cuti") !== false){
                    $btn_actions  [] = "<a url='".base_url()."admin/izin/izin_pegawai/formpdf/".$record->KODE_IZIN."/".$record->ID."' class='btn btn-sm btn-danger popup' data-toggle='tooltip' data-placement='top' title='Lihat Dokumen cuti'><i class='glyphicon glyphicon-open-file'></i> </a>";  
                }
                 
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);        
    }
    public function getdata_pybmc(){
        $this->auth->restrict($this->permissionPybmc);
        $ses_nip    = trim($this->auth->username());
        $this->load->helper('dikbud');
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/izin/izin_pegawai/pybmc');
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

        $selectedUnors = array();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            
        }

        $this->db->start_cache();
        
        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
             
            if($filters['nama_key']){
                $this->izin_pegawai_model->like('upper("NAMA")',strtoupper(trim($filters['nama_key'])),"BOTH"); 
            }
            if($filters['nip_key']){
                $this->izin_pegawai_model->where("NIP_PNS",trim($filters['nip_key']));  
            }
        }
        
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total= $this->izin_pegawai_model->count_all_ppk($ses_nip);
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==2){
                $this->izin_pegawai_model->order_by("NIP_PNS",$order['dir']);
            }
            if($order['column']==3){
                $this->izin_pegawai_model->order_by("KODE_IZIN",$order['dir']);
            }
            if($order['column']==4){
                $this->izin_pegawai_model->order_by("DARI_TANGGAL",$order['dir']);
            }
            if($order['column']==5){
                $this->izin_pegawai_model->order_by("NIP_ATASAN",$order['dir']);
            }
            if($order['column']==6){
                $this->izin_pegawai_model->order_by("NIP_PYBMC",$order['dir']);
            }
            if($order['column']==7){
                $this->izin_pegawai_model->order_by("KETERANGAN",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->izin_pegawai_model->limit($length,$start);
        $records=$this->izin_pegawai_model->find_all_ppk($ses_nip);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $foto_pegawai = base_url().trim($this->settings_lib->item('site.urlphoto'))."noimage.jpg";
                if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$record->PHOTO) and $record->PHOTO != ""){
                    $foto_pegawai =  base_url().trim($this->settings_lib->item('site.urlphoto')).$record->PHOTO;
                }
                $row = array();
                $STATUS_ATASAN = "";
                if($record->STATUS_ATASAN != ""){
                    $STATUS_ATASAN = "<br>".get_status_cuti($record->STATUS_ATASAN);
                }
                $CATATAN_ATASAN = "";
                if($record->CATATAN_ATASAN != ""){
                    $CATATAN_ATASAN = "<br><i>".$record->CATATAN_ATASAN."</i>";
                }
                $STATUS_PYBMC = "";
                if($record->STATUS_PYBMC != ""){
                    $STATUS_PYBMC = "<br>".get_status_cuti($record->STATUS_PYBMC);
                }
                $CATATAN_PYBMC = "";
                if($record->CATATAN_ATASAN != ""){
                    $CATATAN_PYBMC = "<br><i>".$record->CATATAN_PYBMC."</i>";
                }
                $row []  = $nomor_urut.".";
                $row []  = "<img src='".$foto_pegawai."' width='100%'>";
                $row []  = $record->NIP_PNS."<br><i>".$record->NAMA."</i>";
                $row []  = $record->NAMA_IZIN."<br><i>".$record->JUMLAH." ".$record->SATUAN."</i>";
                $row []  = $record->DARI_TANGGAL."-".$record->SAMPAI_TANGGAL;
                $row []  = $record->NAMA_ATASAN.$STATUS_ATASAN.$CATATAN_ATASAN;
                $row []  = $record->NAMA_PYBMC.$STATUS_PYBMC.$CATATAN_PYBMC;
                if($record->KODE_IZIN == "11"){
                    $row []  = $record->KETERANGAN."<br><i>Dari jam ".$record->SELAMA_JAM." - ".$record->SELAMA_MENIT."</i>";
                }else{
                    $row []  = $record->KETERANGAN;    
                }
                
                $btn_actions = array();
                if($record->ID != "" and strpos(strtolower($record->NAMA_IZIN), "cuti") !== false){
                    $btn_actions  [] = "<a url='".base_url()."admin/izin/izin_pegawai/formpdf/".$record->KODE_IZIN."/".$record->ID."' class='btn btn-sm btn-danger popup' data-toggle='tooltip' data-placement='top' title='Lihat Dokumen cuti'><i class='glyphicon glyphicon-open-file'></i> </a>";  
                }
                if($record->LAMPIRAN_FILE != ""){
                    $btn_actions  [] = "<a url='".trim($this->settings_lib->item('site.urllampiranizin')).$record->LAMPIRAN_FILE."' class='btn btn-sm btn-success popup' data-toggle='tooltip' data-placement='top' title='Lihat Lampiran'><i class='fa fa-paperclip'></i> </a>";  
                }
                if($this->auth->has_permission($this->permissionPybmc)){
                    $btn_actions  [] = "<a href='".base_url()."admin/izin/izin_pegawai/verifikasipybmc/".$record->KODE_IZIN."/".$record->ID."' class='btn btn-sm btn-warning show-modal' tooltip='Persetujuan Izin' data-toggle='tooltip' data-placement='top' title='Persetujuan Permintaan Izin'><i class='glyphicon glyphicon-pencil'></i> </a>";  
                }
                 
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);        
    }
    public function getdata_setting1(){
        $this->auth->restrict($this->permissionView);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/izin/izin_pegawai/setting');
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

        $selectedUnors = array();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            
        }

        $this->db->start_cache();
        
        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        $pegawai = $this->input->post('pegawai');
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            if($pegawai){
                $this->db->where_in("PNS_ID",$pegawai);    
            }
            if($filters['unit_id_key']){
                $this->db->group_start();
                $this->db->where('UNOR_ID',$filters['unit_id_key']);    
                $this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);   
                $this->db->group_end();
            } 
            if($filters['nama_key']){
                $this->mv_pegawai_cuti_model->like('upper("NAMA")',strtoupper(trim($filters['nama_key'])),"BOTH"); 
            }
            if($filters['nip_key']){
                $this->mv_pegawai_cuti_model->where("NIP_BARU",trim($filters['nip_key']));  
            }
        }
        
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total= $this->mv_pegawai_cuti_model->count_all();
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->mv_pegawai_cuti_model->order_by("NIP_BARU",$order['dir']);
            }
            if($order['column']==2){
                $this->mv_pegawai_cuti_model->order_by("NAMA",$order['dir']);
            }
            if($order['column']==3){
                $this->mv_pegawai_cuti_model->order_by("NAMA_ATASAN",$order['dir']);
            }
            if($order['column']==4){
                $this->mv_pegawai_cuti_model->order_by("NAMA_PPK",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->mv_pegawai_cuti_model->limit($length,$start);
        $records=$this->mv_pegawai_cuti_model->find_all();
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                //$row []  = $nomor_urut.".";
                $row []  = "<input name='checked[]'' type='checkbox' value='".$record->NIP_BARU."'>";
                $row []  = $record->NIP_BARU."<br>".$record->NAMA;
                $row []  = $record->NAMA_UNOR_FULL;
                $row []  = $record->NAMA_ATASAN;
                $row []  = $record->NAMA_PPK;
                $row []  = $record->KETERANGAN_TAMBAHAN;
                $btn_actions = array();
                if($this->auth->has_permission($this->permissionSettingDelete) && $record->ID_PEGAWAI_ATASAN != ""){
                    $btn_actions  [] = "<a kode='$record->ID_PEGAWAI_ATASAN' class='btn btn-sm btn-danger btn-hapus' data-toggle='tooltip' data-placement='top' data-original-title='Hapus data' title='Hapus data' tooltip='Hapus'><i class='glyphicon glyphicon-remove'></i> </a>";
                }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    public function getdata_setting(){
        $this->auth->restrict($this->permissionView);
        $this->SATKER_ID = $this->pegawai_model->getunor_induk($this->auth->username());
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai');
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

        $selectedUnors = array();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            
        }
        $kedudukan_hukum = "";
        $this->db->start_cache();
        $pegawai = $this->input->post('pegawai');
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            if($pegawai){
                $this->pegawai_model->where_in("PNS_ID",$pegawai);    
            }
            if($filters['unit_id_key']){
                $this->db->group_start();
                $this->db->where('UNOR_ID',$filters['unit_id_key']);    
                $this->db->or_where('ESELON_1',$filters['unit_id_key']);   
                $this->db->or_where('ESELON_2',$filters['unit_id_key']);   
                $this->db->or_where('ESELON_3',$filters['unit_id_key']);   
                $this->db->or_where('ESELON_4',$filters['unit_id_key']);   
                $this->db->group_end();
            }
            if($filters['nama_key']){
                $this->pegawai_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");    
            }
            if($filters['nip_key']){
                $this->pegawai_model->like('upper("NIP_BARU")',strtoupper($filters['nip_key']),"BOTH"); 
            }
        }
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $asatkers = null;
        if($this->auth->has_permission($this->UnitkerjaTerbatas)){
            $asatkers = json_decode($this->auth->get_satkers());
            $total= $this->pegawai_model->count_all($asatkers,false,$kedudukan_hukum);
        }else{
            $total= $this->pegawai_model->count_all($this->SATKER_ID,false,$kedudukan_hukum);
        }
        
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->pegawai_model->order_by("NIP_BARU",$order['dir']);
            }
            if($order['column']==2){
                $this->pegawai_model->order_by("pegawai.NAMA",$order['dir']);
            }
            if($order['column']==3){
                $this->pegawai_model->order_by("NAMA_PANGKAT",$order['dir']);
            }
            if($order['column']==4){
                $this->pegawai_model->order_by("NAMA_UNOR",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->pegawai_model->limit($length,$start);
        $auth = 'unit_kerja';
        if($this->auth->has_permission($this->UnitkerjaTerbatas)){
            $auth = 'unit_kerja_terbatas';
            $asatkers = json_decode($this->auth->get_satkers());
            $records=$this->pegawai_model->find_atasan($asatkers,false,$kedudukan_hukum);
        }else{
            $records=$this->pegawai_model->find_atasan($this->SATKER_ID,false,$kedudukan_hukum);
        }
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = "<input name='checked[]'' type='checkbox' value='".$record->NIP_BARU."'>";
                $row []  = $record->NIP_BARU."<br>".$record->NAMA;
                $row []  = $record->NAMA_UNOR_FULL;
                $str_atasan = "";
                if($record->ATASAN != ""){
                    $atasans = explode("@", $record->ATASAN);
                    foreach($atasans as $atasan) {    
                        $array_atasan = explode("-", $atasan);
                        $str_atasan .= get_pejabat_cuti($array_atasan[1])." : ".$array_atasan[0]."<br>";
                    }

                    //$jabatan = get_pejabat_cuti($atasans[])
                }
                $row []  = $str_atasan;
                $btn_actions = array();
                if($this->auth->has_permission($this->permissionSettingEdit) && $record->ATASAN != ""){
                    $btn_actions  [] = "<a href='".base_url()."admin/izin/izin_pegawai/pilihpejabatedit/".$record->NIP_BARU."' class='show-modal btn btn-sm btn-warning btn-edit' data-toggle='tooltip' data-placement='top' data-original-title='edit data' title='edit data' tooltip='Edit Data'><i class='glyphicon glyphicon-edit'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionSettingDelete) && $record->ATASAN != ""){
                    $btn_actions  [] = "<a kode='$record->NIP_BARU' class='btn btn-sm btn-danger btn-hapus' data-toggle='tooltip' data-placement='top' data-original-title='Hapus data' title='Hapus data' tooltip='Hapus'><i class='glyphicon glyphicon-remove'></i> </a>";
                }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $output['data'][] = $row;

                $nomor_urut++;
            }
        endif;
        $output['satker']=$this->SATKER_ID ;
        $output['auth']=$auth ;
        echo json_encode($output);
        
    }
    public function save(){
         // Validate the data
        $this->form_validation->set_rules($this->izin_pegawai_model->get_validation_rules());
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        $kode_izin = $this->input->post("KODE_IZIN");
        $NIP_PNS = $this->input->post("NIP_PNS");
        $dari_tanggal = $this->input->post("DARI_TANGGAL");
        $this->form_validation->set_rules('DARI_TANGGAL','DARI TANGGAL','required|max_length[30]');
        if($kode_izin != "7" and $kode_izin != "8" and $kode_izin != "9" and $kode_izin != "10" and $kode_izin != "11"){
            $this->form_validation->set_rules('SAMPAI_TANGGAL','SAMPAI TANGGAL ','required|max_length[30]');    
        }else if($kode_izin == "7" or $kode_izin == "8"){
            $this->form_validation->set_rules('SELAMA_JAM','SELAMA JAM ','required|max_length[20]');    
            $this->form_validation->set_rules('SELAMA_MENIT','SELAMA MENIT ','required|max_length[20]');    
        }else if($kode_izin == "9" or $kode_izin == "10"){
            $this->form_validation->set_rules('DARI_TANGGAL','TANGGAL','required|max_length[30]');
        }else if($kode_izin == "11"){
            $this->form_validation->set_rules('DARI_TANGGAL','TANGGAL','required|max_length[30]');
            $this->form_validation->set_rules('SELAMA_JAM','DARI JAM ','required|max_length[20]');    
            $this->form_validation->set_rules('SELAMA_MENIT','SAMPAI JAM ','required|max_length[20]');    
        }
        $aTAHUN_PENGAJUAN = explode("-",$this->input->post("DARI_TANGGAL"));
        $TAHUN_PENGAJUAN = isset($aTAHUN_PENGAJUAN[0]) ? $aTAHUN_PENGAJUAN[0] : date("Y");
        // cuti tahunan, cek jatah cuti
        if($kode_izin == "1"){
            $this->load->helper('dikbud');
            $jmlcutidiambil = (int)$this->input->post("JUMLAH");
            $datasisa = getSisaCuti($NIP_PNS,$TAHUN_PENGAJUAN);
            $jmltahunini = $datasisa->THISYEAR;
            $jmlsudahdiambil = $datasisa->SUDAH_DIAMBIL;
            $jmlsisa = $datasisa->SISA;
            if(($jmlsisa) < $jmlcutidiambil){
                $response['msg'] = "
                <div class='alert alert-block alert-error fade in'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Error
                    </h4>
                    Permintaan jumlah hari cuti melebihi jatah cuti anda
                </div>
                ";
                echo json_encode($response);
                exit();
            }
        }
        // cuti sakit
        if($kode_izin == "3"){
            if (empty($_FILES['lampiran']['name'])) {
               $this->form_validation->set_rules('lampiran','Lampiran','required');
            }
        }
        // CAP
        if($kode_izin == "5"){
            $alasan_cuti = $this->input->post("ALASAN_CUTI");
            if($alasan_cuti != 'Pemulihan Kejiwaaan Karena Tugas di daerah rawan' 
                && $alasan_cuti != 'Meninggal : Menantu'
                && $alasan_cuti != 'Meninggal : Mertua'
                && $alasan_cuti != 'Meninggal : Kakak'
                && $alasan_cuti != 'Meninggal : Adik'
                && $alasan_cuti != 'Meninggal : Ibu'
                && $alasan_cuti != 'Meninggal : Bapak'
                && $alasan_cuti != 'Meninggal : Istri'
                && $alasan_cuti != 'Meninggal : Suami'
                && $alasan_cuti != 'Meninggal : Anak'
                && $alasan_cuti != 'Menikah'
            ){
                if (empty($_FILES['lampiran']['name'])) {
                   $this->form_validation->set_rules('lampiran','Lampiran','required');
                }
            }
        }
        // melahirkan
        if($kode_izin == "4"){
            // cek jumlah pengajuan
            if($this->izin_pegawai_model->validate_cuti_melahirkan($NIP_PNS,$id_data)){
                $response['msg'] = "
                    <div class='alert alert-block alert-error fade in'>
                        <a class='close' data-dismiss='alert'>&times;</a>
                        <h4 class='alert-heading'>
                            Error
                        </h4>
                        Anda sudah tiga kali mengajukan cuti melahirkan...
                    </div>
                    ";
                    echo json_encode($response);
                    exit();
            }
        }
        // cek pembatasan 2 kali
        // cek hanya untuk rully dulu
        if (in_array($kode_izin, ['7', '8', '9', '10', '11','12','13'])) {
            $max_pengajuan = 2;
            $jumlahPengajuan = $this->count_pengajuan_izin_bulanan($NIP_PNS,$kode_izin,$dari_tanggal);
            if ($jumlahPengajuan >= $max_pengajuan) {
                $response['msg'] = "
                <div class='alert alert-block alert-error fade in'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Error
                    </h4>
                    Anda sudah mengajukan izin jenis ini sebanyak $max_pengajuan kali dalam bulan yang sama.
                </div>
                ";
                echo json_encode($response);
                exit();
            }
        }
        // end mak pengajuan
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
        $data = $this->izin_pegawai_model->prep_data($this->input->post());
        // lampiran file
        $this->load->helper('handle_upload');
        $file_lampiran = "";
        if (isset($_FILES['lampiran']) && $_FILES['lampiran']['name']) {
            $datetime = date("Ymdhis");
            $uploadData = handle_upload('lampiran',trim($this->settings_lib->item('site.pathlampiranizin')),"lampiran_".$this->auth->user_id().$datetime);
            if (isset($uploadData['error']) && !empty($uploadData['error']))
             {
                $tipefile = $_FILES['lampiran']['type'];
                log_activity($this->auth->user_id(), 'Gagal upload lampiran : '.$uploadData['error'].$tipefile.$this->input->ip_address(), 'izin_pegawai');
                $response['msg'] = "
                <div class='alert alert-block alert-error fade in'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Error
                    </h4>
                    Gagal upload lampiran : ".$uploadData['error']."
                </div>
                ";
                echo json_encode($response);
                exit();
             }else{
                $file_lampiran = $uploadData['data']['file_name'];
                $data['LAMPIRAN_FILE'] = $file_lampiran;
             }
        }else{
            unset($data['LAMPIRAN_FILE']);
        }
        
        
        $exist = $this->izin_pegawai_model->validate_tanggal($NIP_PNS,$this->input->post("DARI_TANGGAL"),$this->input->post("SAMPAI_TANGGAL"));
        if($exist){
            $response['msg'] = "
            <div class='alert alert-block alert-error fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                    Error
                </h4>
                Data sudah ada pada tanggal tersebut.
            </div>
            ";
            echo json_encode($response);
            exit();
        }

        $data_pegawai = $this->pegawai_model->find_by("NIP_BARU",$NIP_PNS);
        $NAMA_PEGAWAI = isset($data_pegawai->NAMA) ? $data_pegawai->NAMA : "";
        
        $data['NAMA'] = $NAMA_PEGAWAI;
        $data['JUMLAH'] = (int)$this->input->post("JUMLAH");

        $data['TGL_DIBUAT'] = date("Y-m-d");
        
        $data['TAHUN'] = $TAHUN_PENGAJUAN;
        $data['STATUS_ATASAN'] = 1; // menunggu persetujuan
        $data['STATUS_PYBMC'] = 1; // menunggu persetujuan
        $data['TAHUN'] = $TAHUN_PENGAJUAN;
        

        $data['TLP_SELAMA_CUTI'] = $this->input->post("TLP_SELAMA_CUTI") ? $this->input->post("TLP_SELAMA_CUTI") : null;
        $data['TAMBAHAN_HARI'] = $this->input->post("TAMBAHAN_HARI") ? $this->input->post("TAMBAHAN_HARI") : null;
        $data['LUAR_NEGERI'] = $this->input->post("LUAR_NEGERI") ? $this->input->post("LUAR_NEGERI") : null;
        
        if($data['MASA_KERJA_TAHUN'] == ""){
            unset($data['MASA_KERJA_TAHUN']);
        }
        if($data['MASA_KERJA_BULAN'] == ""){
            unset($data['MASA_KERJA_BULAN']);
        }
        if($data['KODE_IZIN'] == ""){
            unset($data['KODE_IZIN']);
        }
        if($data['JUMLAH'] == ""){
            unset($data['JUMLAH']);
        }
        if($data['SISA_CUTI_TAHUN_N2'] == ""){
            unset($data['SISA_CUTI_TAHUN_N2']);
        }
        if($data['SISA_CUTI_TAHUN_N1'] == ""){
            unset($data['SISA_CUTI_TAHUN_N1']);
        }
        if($data['SISA_CUTI_TAHUN_N'] == ""){
            unset($data['SISA_CUTI_TAHUN_N']);
        }
        if($data['STATUS_ATASAN'] == ""){
            unset($data['STATUS_ATASAN']);
        }
        if($data['STATUS_PYBMC'] == ""){
            unset($data['STATUS_PYBMC']);
        }
        if($data['DARI_TANGGAL'] == ""){
            unset($data['DARI_TANGGAL']);
        }
        if($data['SAMPAI_TANGGAL'] == ""){
            unset($data['SAMPAI_TANGGAL']);
        }
        if($data['TGL_PERKIRAAN_LAHIR'] == ""){
            unset($data['TGL_PERKIRAAN_LAHIR']);
        }
        $id_data = $this->input->post("ID");
        if(isset($id_data) && !empty($id_data)){
            log_activity($this->auth->user_id(), 'Edit data izin : ' . $id_data . ' : ' . $this->input->ip_address(), 'izin_pegawai');    
        }
        else{
            if($insert_id = $this->izin_pegawai_model->insert($data)){
                $this->save_izin_verifikasi($NIP_PNS,$insert_id,$kode_izin);
                log_activity($this->auth->user_id(), 'Save data izin : ' . $insert_id . ' : ' . $this->input->ip_address(), 'izin_pegawai');    
            }else{
                $response ['success']= false;
                $response ['msg']= "Ada kesalahan";
                echo json_encode($response);            
                exit;
            }
        } 
        $response ['success']= true;
        $response ['msg']= "Berhasil";
        echo json_encode($response);    

    }
    public function count_pengajuan_izin_bulanan($nip_pns, $kode_izin, $dari_tanggal)
    {
        // Ekstrak bulan dan tahun dari input
        $bulan = date('m', strtotime($dari_tanggal));
        $tahun = date('Y', strtotime($dari_tanggal));

        $this->db->where('NIP_PNS', $nip_pns);
        $this->db->where('KODE_IZIN', $kode_izin);
        $this->db->where("EXTRACT(MONTH FROM \"DARI_TANGGAL\") = ", $bulan, false); // Kutip ganda di kolom
        $this->db->where("EXTRACT(YEAR FROM \"DARI_TANGGAL\") = ", $tahun, false); // Kutip ganda di kolom

        return $this->db->count_all_results('izin'); // Ganti dengan nama tabel izin Anda
    }
    private function save_izin_verifikasi($nip_pegawai = "",$id_pengajuan = "",$id_jenis_izin = ""){
        $line_approval = $this->getpersetujuan($id_jenis_izin);
        $adata_pejabat = $this->get_data_pejabat($nip_pegawai);
        // print_r($line_approval);
        // print_r($adata_pejabat);
        $urutan = 1;
        foreach($line_approval as $values)
         {
            if(isset($adata_pejabat[$values]->NIP_ATASAN)){
                $data = array();
                $data['NIP_ATASAN'] = $adata_pejabat[$values]->NIP_ATASAN;
                $data['ID_PENGAJUAN'] = $id_pengajuan;
                if($urutan == 1){
                    $data['STATUS_VERIFIKASI'] = 1;
                }else
                $data['STATUS_VERIFIKASI'] = null;
                if($nip_pegawai == '198403192009121007'){
                	// print_r($data);
                }
                $insert = $this->izin_verifikasi_model->insert($data);
                if($insert){
                    log_activity($this->auth->user_id(), 'Save izin atasan '. $this->input->ip_address(), 'izin_pegawai');    
                }else{
                	log_activity($this->auth->user_id(), 'gagal save izin verifikasi : ' . json_encode($this->izin_verifikasi_model->error), 'izin_pegawai');    
                }
                $urutan++;
            }
         }
    }
    public function saveverifikasi(){
         // Validate the data
        ///$this->form_validation->set_rules($this->izin_pegawai_model->get_validation_rules());
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );

        $this->form_validation->set_rules('STATUS_ATASAN','STATUS','required|max_length[30]');
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
        if($this->input->post("STATUS_ATASAN") ==""){
             $response['msg'] = "
                <div class='alert alert-block alert-error fade in'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Error
                    </h4>
                   Silahkan Pilih Status
                </div>
                ";
                echo json_encode($response);
                exit();
        }
        // save perubahan status izin_verifikasi
        $ID_VERIFIKASI = $this->input->post("ID_VERIFIKASI");
        $id_pengajuan = $this->input->post("ID");
        $DARI_TANGGAL = $this->input->post("DARI_TANGGAL");
        $SAMPAI_TANGGAL = $this->input->post("SAMPAI_TANGGAL");
        $SAMPAI_TANGGAL = $SAMPAI_TANGGAL != "" ? $SAMPAI_TANGGAL : $DARI_TANGGAL;
        $NIP_PNS = $this->input->post("NIP_PNS");
        $KODE_JENIS_IZIN = $this->input->post("KODE_JENIS_IZIN");
        $KODE_IZIN      = $this->input->post("KODE_IZIN");
        $JUMLAH      = $this->input->post("JUMLAH");
        
        $atasan_selanjutnya = $this->cek_id_terakhir($ID_VERIFIKASI,$id_pengajuan);
        $msg_tambahan = "";
        if(isset($atasan_selanjutnya[0]->ID)){
            // update pengajuan menjadi proses persetujuan
            $this->save_verifikasi_atasan($ID_VERIFIKASI,$this->input->post("STATUS_ATASAN"),$this->input->post("CATATAN_ATASAN"));
            // cek jika statusnya disetujui
            if($this->input->post("STATUS_ATASAN") == "3"){
                // ubah status pengajuan menjadi di proses karena masih ada atasan selanjutnya
                $this->update_status_pengajuan($id_pengajuan,"2",$this->input->post("CATATAN_ATASAN"));
                $this->save_verifikasi_atasan($atasan_selanjutnya[0]->ID,"1"); // merubah status atasan selanjutnya menjadi siap di proses
            }else{
                // jika tidak diterima
                //$this->update_status_pengajuan($id_pengajuan,$this->input->post("STATUS_ATASAN"),$this->input->post("CATATAN_ATASAN"));
                // meskipun tidak diterima, tetapi proses izin tetap dilanjutkan ke proses selanjutnya
                $this->update_status_pengajuan($id_pengajuan,"2",$this->input->post("CATATAN_ATASAN"));
                $this->save_verifikasi_atasan($atasan_selanjutnya[0]->ID,"1"); // merubah status atasan selanjutnya menjadi siap di proses
            }
            
            // update status verifikasi selanjutnya menjadi 1 = siap di verifikasi

        }else{

            // jika level verifikasi terakhir
            $this->save_verifikasi_atasan($ID_VERIFIKASI,$this->input->post("STATUS_ATASAN"),$this->input->post("CATATAN_ATASAN"));
            // atasan terakhir
            $this->update_status_pengajuan($id_pengajuan,$this->input->post("STATUS_ATASAN"),$this->input->post("CATATAN_ATASAN"));
            // jika diterima
            if($this->input->post("STATUS_ATASAN") == "3"){
                // cuti tahunan
                if($KODE_IZIN == "1"){
                    $TAHUN      = date('Y', strtotime($DARI_TANGGAL));;
                    $this->sisa_cuti_model->where('TAHUN', $TAHUN);
                    $data_sisa = $this->sisa_cuti_model->find_by("PNS_NIP",$NIP_PNS);
                    $sisa_n = isset($data_sisa->SISA_N) ? (int)$data_sisa->SISA_N : 0;
                    $sisa_n1 = isset($data_sisa->SISA_N_1) ? (int)$data_sisa->SISA_N_1 : 0;
                    $sisa_n2 = isset($data_sisa->SISA_N_2) ? (int)$data_sisa->SISA_N_2 : 0;
                    
                    $diambil_sebelum = isset($data_sisa->SUDAH_DIAMBIL) ? $data_sisa->SUDAH_DIAMBIL : 0;
                    $jumlah_diambil = $diambil_sebelum + (int)$JUMLAH;
                    $jml_sisa = $sisa_n + $sisa_n1 + $sisa_n2 - $jumlah_diambil;

                    $dataupdate = array();
                    $dataupdate["SUDAH_DIAMBIL"] = $jumlah_diambil;
                    //$dataupdate["SISA"] = $jml_sisa;
                    $this->sisa_cuti_model->where('TAHUN', $TAHUN);
                    $this->sisa_cuti_model->skip_validation(true);
                    $this->sisa_cuti_model->update_where("PNS_NIP",$NIP_PNS, $dataupdate);
                }

                // kirim update ke server kehadiran
                $this->load->library('Api_kehadiran');
                $api_kehadiran = new Api_kehadiran;
                $check_in = "07:30:00";
                $check_out = "16:55:03";
                $terlambat = "0";
                $pulang_cepat = "0";
                $ot_before = "0";
                $ot_after = "0";
                $workinonholiday = "0";
                // $send_absen = $api_kehadiran->sendabsen($NIP_PNS,$DARI_TANGGAL,$SAMPAI_TANGGAL,$check_in,$check_out,$terlambat,$pulang_cepat,$ot_before,$ot_after,$workinonholiday,$KODE_JENIS_IZIN);
                // $result_kehadiran = json_decode($send_absen);
                // if($result_kehadiran->status){
                //     $msg_tambahan = "Data telah dikirim ke ekehadiran";
                //     log_activity($this->auth->user_id(), 'Kirim ke ekehadiran berhasil : ' . $NIP_PNS . ' Tanggal '.$DARI_TANGGAL." Kode ".$KODE_JENIS_IZIN.' : ' . $this->input->ip_address(), 'izin_pegawai');    
                // }
                 // save AT
                $dbhadir = $this->load->database('kehadiran', true);
                $cek_at = strpos($KODE_JENIS_IZIN, "AT_");
           
                $begin = new DateTime($DARI_TANGGAL);
                $end   = new DateTime($SAMPAI_TANGGAL);
                $jmlhari = 0;
                for($i = $begin; $i <= $end; $i->modify('+1 day')){
                    $jmlhari++;
                    $data_at = [
                        'userid' => $NIP_PNS,
                        'attendance' => $KODE_JENIS_IZIN,
                        'rosterdate' => $i->format("Y-m-d"),
                        'editby' => "dikbudhr"
                    ];
                    // print_r($data_at);
                    // $inserted_id = $dbhadir->insert('rosterdetailsatt', $data_at); // mas moda minta tidak perlu otomatis
                    
                }
                    
                

            }
        }
        $response ['success']= true;
        $response ['msg']= "Berhasil ".$msg_tambahan;
        echo json_encode($response);    

    }
    public function resendekehadiran(){
        $this->auth->restrict($this->permissionKirimKehadiran);
        // kirim update ke server kehadiran
        $id         = $this->input->post('kode');
        $data_izin = $this->izin_pegawai_model->find($id);
        $NIP_PNS = $data_izin->NIP_PNS; 
        $DARI_TANGGAL = $data_izin->DARI_TANGGAL;
        $SAMPAI_TANGGAL = $data_izin->SAMPAI_TANGGAL != "" ? $data_izin->SAMPAI_TANGGAL : $data_izin->DARI_TANGGAL;

        $jenis_izin = $this->jenis_izin_model->find($data_izin->KODE_IZIN);
        $KODE_JENIS_IZIN      = isset($jenis_izin->KODE) ? $jenis_izin->KODE : "";
        $this->load->library('Api_kehadiran');
        $api_kehadiran = new Api_kehadiran;
        $check_in = "07:30:00";
        $check_out = "16:55:03";
        $terlambat = "0";
        $pulang_cepat = "0";
        $ot_before = "0";
        $ot_after = "0";
        $workinonholiday = "0";
        $status = false;
        $msg = ""; 

        // $send_absen = $api_kehadiran->sendabsen($NIP_PNS,$DARI_TANGGAL,$SAMPAI_TANGGAL,$check_in,$check_out,$terlambat,$pulang_cepat,$ot_before,$ot_after,$workinonholiday,$KODE_JENIS_IZIN);
        // $result_kehadiran = json_decode($send_absen);
        // if($result_kehadiran->status){
        //     $msg_tambahan = "Data telah dikirim ke ekehadiran";
        //     log_activity($this->auth->user_id(), 'Kirim ke ekehadiran berhasil : ' . $NIP_PNS . ' Tanggal '.$DARI_TANGGAL." Kode ".$KODE_JENIS_IZIN.' : ' . $this->input->ip_address(), 'izin_pegawai');    
        // }
        // save AT
        $dbhadir = $this->load->database('kehadiran', true);
        $begin = new DateTime($DARI_TANGGAL);
        $end   = new DateTime($SAMPAI_TANGGAL);
        $jmlhari = 0;
        for($i = $begin; $i <= $end; $i->modify('+1 day')){
            $jmlhari++;
            $data_at = [
                'userid' => $NIP_PNS,
                'attendance' => $KODE_JENIS_IZIN,
                'rosterdate' => $i->format("Y-m-d"),
                'editby' => "dikbudhr"
            ];
            // print_r($data_at);
            $inserted_id = $dbhadir->insert('rosterdetailsatt', $data_at);
            if($inserted_id){
                $status = true;
                $msg = "Sukses kirimkan data ke e-kehadiran, jumlah hari ".$jmlhari;
            }
        }
        // update status sudah dikirim ke kehadiran
        $dataupdate = array();
        $dataupdate['status_kirim'] = 1;
        $this->izin_pegawai_model->skip_validation(true);
        $this->izin_pegawai_model->update($id,$dataupdate);
        // end update status
        $response ['success']= $status;
        $response ['msg']= $msg;
        echo json_encode($response);   
        exit();
        // end kirim izin ke database kehadiran

    }
    public function save_penangguhan(){
         // Validate the data
        ///$this->form_validation->set_rules($this->izin_pegawai_model->get_validation_rules());
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );

        $this->form_validation->set_rules('STATUS_ATASAN','STATUS','required|max_length[30]');
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
        // save perubahan status izin_verifikasi
        $ID_VERIFIKASI = $this->input->post("ID_VERIFIKASI");
        $id_pengajuan = $this->input->post("ID");
        $DARI_TANGGAL = $this->input->post("DARI_TANGGAL");
        $SAMPAI_TANGGAL = $this->input->post("SAMPAI_TANGGAL");
        $PNS_NIP = $this->input->post("NIP_PNS");
        $KODE_JENIS_IZIN = $this->input->post("KODE_JENIS_IZIN");
        $KODE_IZIN      = $this->input->post("KODE_IZIN");
        $JUMLAH      = $this->input->post("JUMLAH");
        $STATUS_ATASAN = $this->input->post("STATUS_ATASAN");
        // atasan terakhir
        $data['STATUS_PENGAJUAN'] = $this->input->post("STATUS_ATASAN");
        if($this->input->post("STATUS_ATASAN") != 3){
            $data['CATATAN_ATASAN'] = $this->input->post("CATATAN_ATASAN");
        }
        if(isset($id_pengajuan) && !empty($id_pengajuan)){
            $this->izin_pegawai_model->skip_validation(true);
            $this->izin_pegawai_model->update($id_pengajuan,$data);
            log_activity($this->auth->user_id(), 'Penangguhan data izin : ' . $id_pengajuan . ' status" '.$STATUS_ATASAN.' : ' . $this->input->ip_address(), 'izin_pegawai');    
        }
        // jika diterima
        if($this->input->post("STATUS_ATASAN") != "3"){
            // cuti tahunan
            if($KODE_IZIN == "1"){
                $TAHUN      = date('Y', strtotime($DARI_TANGGAL));;
                $this->sisa_cuti_model->where('TAHUN', $TAHUN);
                $data_sisa = $this->sisa_cuti_model->find_by("PNS_NIP",$PNS_NIP);
                $sisa_n = isset($data_sisa->SISA_N) ? (int)$data_sisa->SISA_N : 0;
                $sisa_n1 = isset($data_sisa->SISA_N_1) ? (int)$data_sisa->SISA_N_1 : 0;
                $sisa_n2 = isset($data_sisa->SISA_N_2) ? (int)$data_sisa->SISA_N_2 : 0;
                
                $diambil_sebelum = isset($data_sisa->SUDAH_DIAMBIL) ? $data_sisa->SUDAH_DIAMBIL : 0;
                $jumlah_diambil = $diambil_sebelum - (int)$JUMLAH;
                $jumlah_diambil = $jumlah_diambil < 0 ? 0 : $jumlah_diambil;
                $jml_sisa = $sisa_n + $sisa_n1 + $sisa_n2 - $jumlah_diambil;

                $dataupdate = array();
                $dataupdate["SUDAH_DIAMBIL"] = $jumlah_diambil;
                //$dataupdate["SISA"] = $jml_sisa;
                $this->sisa_cuti_model->where('TAHUN', $TAHUN);
                $this->sisa_cuti_model->skip_validation(true);
                $this->sisa_cuti_model->update_where("PNS_NIP",$this->input->post("NIP_PNS"), $dataupdate);
            }

            // kirim update ke server kehadiran
            $this->load->library('Api_kehadiran');
            $api_kehadiran = new Api_kehadiran;
            $check_in = "07:30:00";
            $check_out = "16:55:03";
            $terlambat = "0";
            $pulang_cepat = "0";
            $ot_before = "0";
            $ot_after = "0";
            $workinonholiday = "0";
            $send_absen = $api_kehadiran->del_absen($NIP_PNS,$DARI_TANGGAL,$SAMPAI_TANGGAL);
            $result_kehadiran = json_decode($send_absen);
            if($result_kehadiran->status){
                $msg_tambahan = "Data telah dikirim ke ekehadiran";
                log_activity($this->auth->user_id(), 'Hapus data ekehadiran berhasil : ' . $NIP_PNS . ' Tanggal '.$DARI_TANGGAL." Kode ".$KODE_JENIS_IZIN.' : ' . $this->input->ip_address(), 'izin_pegawai');    
            }

        }
        $response ['success']= true;
        $response ['msg']= "Berhasil ".$msg_tambahan;
        echo json_encode($response);    

    }
    private function cek_id_terakhir($id_verifikasi = "",$id_pengajuan = ""){
        $this->izin_verifikasi_model->where("izin_verifikasi.ID > ".$id_verifikasi."");
        $verifikasi_izin = $this->izin_verifikasi_model->find_all($id_pengajuan);
        return $verifikasi_izin;
    }
    private function get_data_verifikasi_izin($id_pengajuan = ""){
        $verifikasi_izin = $this->izin_verifikasi_model->find_all($id_pengajuan);
        $adata = array();
        if(isset($verifikasi_izin) && is_array($verifikasi_izin) && count($verifikasi_izin)):
            foreach ($verifikasi_izin as $record) {
                $adata[$record->NIP_ATASAN] = $record;
                // $adata[] = $record;
            }
        endif;

        return $adata;
    }
    private function save_verifikasi_atasan($id_verifikasi = "",$status_verifikasi = "",$catatan = ""){
        $dataverifikasi = array();
        $dataverifikasi['STATUS_VERIFIKASI']    = $status_verifikasi;
        $dataverifikasi['TANGGAL_VERIFIKASI']   = date("Y-m-d H:i:s");
        if($status_verifikasi != 3)
            $dataverifikasi['ALASAN_DITOLAK']       = $catatan;
        $this->izin_verifikasi_model->skip_validation(true);
        if($this->izin_verifikasi_model->update($id_verifikasi,$dataverifikasi)){
            log_activity($this->auth->user_id(), 'Verifikasi data izin : ' . $id_verifikasi . ' status" '.$status_verifikasi.' : ' . $this->input->ip_address(), 'izin_pegawai');    
        }

    }
    private function update_status_pengajuan($id_data = "",$status_atasan = "",$catatan_atasan = ""){
        $data['TGL_ATASAN'] = date("Y-m-d");
        $data['STATUS_PENGAJUAN'] = $status_atasan;
        if($status_verifikasi != 3){
            $data['CATATAN_ATASAN'] = $catatan_atasan;    
        }
        if(isset($id_data) && !empty($id_data)){
            $this->izin_pegawai_model->skip_validation(true);
            $this->izin_pegawai_model->update($id_data,$data);
            log_activity($this->auth->user_id(), 'Verifikasi data izin : ' . $id_data . ' status" '.$STATUS_ATASAN.' : ' . $this->input->ip_address(), 'izin_pegawai');    
        }
    }

    public function savepersetujuan(){
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        $this->form_validation->set_rules('STATUS_PYBMC','STATUS','required|max_length[30]');
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
        $KODE_IZIN = $this->input->post("KODE_IZIN");
        $JUMLAH = $this->input->post("JUMLAH");
        $data['TGL_PPK'] = date("Y-m-d");
        $data['STATUS_PYBMC'] = $this->input->post("STATUS_PYBMC");
        $data['CATATAN_PYBMC'] = $this->input->post("CATATAN_PYBMC");
        
        if($KODE_IZIN == "1" && $this->input->post("STATUS_PYBMC") == "2"){
            $this->sisa_cuti_model->where('TAHUN', $this->input->post("TAHUN"));
            $data_sisa = $this->sisa_cuti_model->find_by("PNS_NIP",$this->input->post("NIP_PNS"));
            $sisa_n = isset($data_sisa->SISA_N) ? (int)$data_sisa->SISA_N : 0;
            $sisa_n1 = isset($data_sisa->SISA_N_1) ? (int)$data_sisa->SISA_N_1 : 0;
            $sisa_n2 = isset($data_sisa->SISA_N_2) ? (int)$data_sisa->SISA_N_2 : 0;
            
            $diambil_sebelum = isset($data_sisa->SUDAH_DIAMBIL) ? $data_sisa->SUDAH_DIAMBIL : 0;
            $jumlah_diambil = $diambil_sebelum + (int)$JUMLAH;
            $jml_sisa = $sisa_n + $sisa_n1 + $sisa_n2 - $jumlah_diambil;

            $dataupdate = array();
            $dataupdate["SUDAH_DIAMBIL"] = $jumlah_diambil;
            $dataupdate["SISA"] = $jml_sisa;
            $this->sisa_cuti_model->where('TAHUN', $this->input->post("TAHUN"));
            $this->sisa_cuti_model->skip_validation(true);
            $this->sisa_cuti_model->update_where("PNS_NIP",$this->input->post("NIP_PNS"), $dataupdate);
        }

        $id_data = $this->input->post("ID");
        if(isset($id_data) && !empty($id_data)){
            $this->izin_pegawai_model->skip_validation(true);
            $this->izin_pegawai_model->update($id_data,$data);
            log_activity($this->auth->user_id(), 'persetujuan ppk data izin : ' . $id_data . ' status" '.$STATUS_ATASAN.' : ' . $this->input->ip_address(), 'izin_pegawai');
        }
          
        $response ['success']= true;
        $response ['msg']= "Berhasil";
        echo json_encode($response);    

    }
    
    public function savepejabat(){
         // Validate the data
        $this->form_validation->set_rules($this->pegawai_atasan_model->get_validation_rules());
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        //$this->form_validation->set_rules('ATASAN','ATASAN','required|max_length[30]');
        //$this->form_validation->set_rules('PPK','PPK','required|max_length[30]');
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
        $NIP_ATASAN = $this->input->post("ATASAN");
        $NIP_PPK = $this->input->post("PPK");
        $KETERANGAN_TAMBAHAN = $this->input->post("KETERANGAN_TAMBAHAN");
        $ATASAN_TAMBAHAN = $this->input->post("ATASAN_TAMBAHAN");
        $SEBAGAI = $this->input->post("SEBAGAI");
        
        $data_atasan = $this->pegawai_model->find_by("NIP_BARU",$NIP_ATASAN);
        $NAMA_ATASAN = isset($data_atasan->NAMA) ? $data_atasan->NAMA : "";
        $data_ppk = $this->pegawai_model->find_by("NIP_BARU",$NIP_PPK);
        $NAMA_PPK = isset($data_ppk->NAMA) ? $data_ppk->NAMA : "";
        $result = false;
        $msg = "Ada Kesalahan";
        // get nama atasan
        $index = 0;
        $anama_atasan = array();
        if (is_array($ATASAN_TAMBAHAN) && count($ATASAN_TAMBAHAN)) {
            foreach ($ATASAN_TAMBAHAN as $tambahan_atasan) {

                $data_atasan = $this->pegawai_model->find_by("NIP_BARU",$tambahan_atasan);
                $nama_atasan_tambahan = isset($data_atasan->NAMA) ? $data_atasan->NAMA : "";
                $anama_atasan[$tambahan_atasan] = $nama_atasan_tambahan;
                $index++;
            }
        }


        $checked = $this->input->post('checked');
        if (is_array($checked) && count($checked)) {
            foreach ($checked as $pid) {
                
                // save to line approval
                $index = 0;
                if (is_array($ATASAN_TAMBAHAN) && count($ATASAN_TAMBAHAN)) {
                    foreach ($ATASAN_TAMBAHAN as $tambahan_atasan) {
                        // delete line approval
                        if($SEBAGAI[$index] != ""){
                            $datadel_line_approval = array('PNS_NIP'=>$pid,'SEBAGAI'=>$SEBAGAI[$index]);
                            $this->line_approval_model->delete_where($datadel_line_approval);


                            $data = array();
                            $data['NIP_ATASAN']     = $tambahan_atasan;
                            $data['NAMA_ATASAN']    = isset($anama_atasan[$tambahan_atasan]) ? $anama_atasan[$tambahan_atasan] : "";
                            $data['SEBAGAI']        = $SEBAGAI[$index];
                            $data['PNS_NIP'] = $pid;
                            $data['KETERANGAN_TAMBAHAN'] = $KETERANGAN_TAMBAHAN;
                            if($id_line = $this->line_approval_model->insert($data)){
                                $result = true;
                                $msg = "Berhasil";
                                log_activity($this->auth->user_id(), 'Save line approval : ' . $insert_id . ' : ' . $this->input->ip_address(), 'line_approval');    
                            }
                            $index++;
                        }
                    }
                }
                 
            }
        }
        $response ['success']= $result;
        $response ['msg']= $msg;
        echo json_encode($response);    

    }
    public function saveeditpejabat(){
         // Validate the data
        $this->form_validation->set_rules($this->pegawai_atasan_model->get_validation_rules());
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        //$this->form_validation->set_rules('ATASAN','ATASAN','required|max_length[30]');
        //$this->form_validation->set_rules('PPK','PPK','required|max_length[30]');
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
        // cek nip ini apakah masih ada izin yang belum di proses
        $status_proses = array('1','2');
        $this->izin_pegawai_model->where_in("STATUS_PENGAJUAN",$status_proses);
        $this->izin_pegawai_model->where("NIP_PNS",$nip);
        $jumlahIzin = $this->izin_pegawai_model->count_all();
        if($jumlahIzin > 0){
            $response ['success']= false;
            $response ['msg']= "Data tidak dapat diubah karena masih ada pengajuan izin yang blm di proses";
            echo json_encode($response);    
            exit();
        }
        $nip = $this->input->post("nip");
        $NIP_ATASAN = $this->input->post("ATASAN");
        $NIP_PPK = $this->input->post("PPK");
        $KETERANGAN_TAMBAHAN = $this->input->post("KETERANGAN_TAMBAHAN");
        $ATASAN_TAMBAHAN = $this->input->post("ATASAN_TAMBAHAN");
        $SEBAGAI = $this->input->post("SEBAGAI");
        
        $data_atasan = $this->pegawai_model->find_by("NIP_BARU",$NIP_ATASAN);
        $NAMA_ATASAN = isset($data_atasan->NAMA) ? $data_atasan->NAMA : "";
        $data_ppk = $this->pegawai_model->find_by("NIP_BARU",$NIP_PPK);
        $NAMA_PPK = isset($data_ppk->NAMA) ? $data_ppk->NAMA : "";
        $result = false;
        $msg = "Ada Kesalahan";
        // get nama atasan
        $index = 0;
        $anama_atasan = array();
        if (is_array($ATASAN_TAMBAHAN) && count($ATASAN_TAMBAHAN)) {
            // hapus dulu data atasannya semua untuk nip ini
            $datadel_line_approval = array('PNS_NIP'=>$nip);
            $this->line_approval_model->delete_where($datadel_line_approval);
            foreach ($ATASAN_TAMBAHAN as $tambahan_atasan) {
                $data_atasan = $this->pegawai_model->find_by("NIP_BARU",$tambahan_atasan);
                $nama_atasan_tambahan = isset($data_atasan->NAMA) ? $data_atasan->NAMA : "";
                $anama_atasan[$tambahan_atasan] = $nama_atasan_tambahan;
                $index++;
            }
        }
        // save to line approval
        $index = 0;
        if (is_array($ATASAN_TAMBAHAN) && count($ATASAN_TAMBAHAN)) {
            foreach ($ATASAN_TAMBAHAN as $tambahan_atasan) {
                // delete line approval
                $data = array();
                $data['NIP_ATASAN']     = $tambahan_atasan;
                $data['NAMA_ATASAN']    = isset($anama_atasan[$tambahan_atasan]) ? $anama_atasan[$tambahan_atasan] : "";
                $data['SEBAGAI']        = $SEBAGAI[$index];
                $data['PNS_NIP']        = $nip;
                $data['KETERANGAN_TAMBAHAN'] = $KETERANGAN_TAMBAHAN;
                if($id_line = $this->line_approval_model->insert($data)){
                    $result = true;
                    $msg = "Berhasil";
                    log_activity($this->auth->user_id(), 'Edit line approval : ' . $insert_id . ' : ' . $this->input->ip_address(), 'line_approval');    
                }
                $index++;
            }
        }
        $response ['success']= $result;
        $response ['msg']= $msg;
        echo json_encode($response);    

    }
    public function savepejabat_topegawai_atasan(){
         // Validate the data
        $this->form_validation->set_rules($this->pegawai_atasan_model->get_validation_rules());
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        $this->form_validation->set_rules('NIP_ATASAN','ATASAN','required|max_length[30]');
        $this->form_validation->set_rules('NIP_PPK','PPK','required|max_length[30]');
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
        $NIP_ATASAN = $this->input->post("NIP_ATASAN");
        $NIP_PPK = $this->input->post("NIP_PPK");
        $KETERANGAN_TAMBAHAN = $this->input->post("KETERANGAN_TAMBAHAN");
        $data_atasan = $this->pegawai_model->find_by("NIP_BARU",$NIP_ATASAN);
        $NAMA_ATASAN = isset($data_atasan->NAMA) ? $data_atasan->NAMA : "";
        $data_ppk = $this->pegawai_model->find_by("NIP_BARU",$NIP_PPK);
        $NAMA_PPK = isset($data_ppk->NAMA) ? $data_ppk->NAMA : "";
        $result = false;
        $msg = "Ada Kesalahan";
        $checked = $this->input->post('checked');
        if (is_array($checked) && count($checked)) {
            foreach ($checked as $pid) {
                $datadel = array('PNS_NIP'=>$pid);
                $this->pegawai_atasan_model->delete_where($datadel);

                $data = array();
                $data['NIP_ATASAN'] = $NIP_ATASAN;
                $data['NAMA_ATASAN'] = $NAMA_ATASAN;
                $data['NAMA_PPK'] = $NAMA_PPK;
                $data['PPK'] = $NIP_PPK;
                $data['PNS_NIP'] = $pid;
                $data['KETERANGAN_TAMBAHAN'] = $KETERANGAN_TAMBAHAN;
                if($insert_id = $this->pegawai_atasan_model->insert($data)){
                    $result = true;
                    $msg = "Berhasil";
                    log_activity($this->auth->user_id(), 'Save data pejabat : ' . $insert_id . ' : ' . $this->input->ip_address(), 'pegawai_atasan');    
                }
            }
        }
        $response ['success']= $result;
        $response ['msg']= $msg;
        echo json_encode($response);    

    }
    public function refreshpejabat(){
        $query = "REFRESH MATERIALIZED VIEW mv_pegawai_cuti;";
        $result = false;
        $msg = "Ada kesalahan";
        if ($this->db->query($query))
        {
            $result = true;
            $msg = "Berhasil Refresh data";
        }
        else
        {
            $result = false;
        }

        $response ['success']= $result;
        $response ['msg']= $msg;
        echo json_encode($response);   
    }
    public function deletedata()
    {
        $this->auth->restrict($this->permissionDelete);
        $id         = $this->input->post('kode');
        $data       = $this->izin_pegawai_model->find($id);
        $user_id    = $data->NIP_PNS;
        $start_date = $data->DARI_TANGGAL;
        $end_date   = $data->SAMPAI_TANGGAL;
        $lampiran   = $data->LAMPIRAN_FILE;
        $status = false;
        $msg = ""; 
        if($data->STATUS_PENGAJUAN == "3"){
            $response ['success']= false;
            $response ['msg']= "Data tidak bisa dihapus karena sudah melewati Persetujuan";
            echo json_encode($response);   
            exit();
        }
        if ($this->izin_pegawai_model->delete($id)) {
            $this->delete_izin_verifikasi($id);
            if($lampiran != ""){
                unlink(trim($this->settings_lib->item('site.pathlampiranizin')).$lampiran);
            }
            log_activity($this->auth->user_id(), 'delete data izin : ' . $id . ' : ' . $this->input->ip_address(), 'izin_pegawai');
            Template::set_message("Sukses Hapus data", 'success');

            $return_del_absen = $this->delete_absen_ekehadiran($user_id,$start_date,$end_date);
            $status = true;
        }else{
            $status = false;
            $msg = "Ada kesalahan"; 
        }
        $response ['success']= $status;
        $response ['msg']= $msg;
        echo json_encode($response);   
        exit();
    }
    private function delete_absen_ekehadiran($user_id = "",$start_date = "",$end_date = ""){
        $this->load->library('Api_kehadiran');
        $api_kehadiran = new Api_kehadiran;
        $return_del_absen = $api_kehadiran->del_absen($user_id,$start_date,$end_date);
        return $return_del_absen;
    }
    private function delete_izin_verifikasi($id_pengajuan = ""){
        $datadel = array('ID_PENGAJUAN'=>$id_pengajuan);
        $this->izin_verifikasi_model->delete_where($datadel);
    }
    public function deletedata_pejabat()
    {
        $this->auth->restrict($this->permissionSettingDelete);
        $id     = $this->input->post('kode');
        $datadel = array('PNS_NIP'=>$id);
        if ($this->line_approval_model->delete_where($datadel)) 
        {
             log_activity($this->auth->user_id(), 'delete data pejabat : ' . $id . ' : ' . $this->input->ip_address(), 'pegawai_atasan');
             Template::set_message("Sukses Hapus data", 'success');
             echo "Sukses";
        }else{
            echo "Gagal";
        }

        exit();
    }
    public function absen(){
        $this->auth->restrict($this->permissionSettingKirimAbsen);
        $this->load->model('izin_pegawai/hari_libur_model');
        $this->load->library('Convert');
        $convert = new Convert;
        $ses_nip    = trim($this->auth->username());
        $tanggal = date("Y-m-d");
        //GET DATA HARI LIBUR
        $tahun = date("Y");
        $bulan = date("m");
        $record_hari_libur_tahunan = $this->hari_libur_model->find_by_tahun($tahun);
        $record_hari_libur_bulan_ini = $this->hari_libur_model->find_by_bulan($tahun,$bulan);
        Template::set("record_hari_libur_tahunan",$record_hari_libur_tahunan);
        Template::set("record_hari_libur_bulan_ini",$record_hari_libur_bulan_ini);

        $tanggal_indonesia = $convert->fmtDate($tanggal,"dd month yyyy");
        $hari = $convert->Gethari($tanggal);

        // get data atasan
        $dataatasan = $this->getatasan($ses_nip);//$this->line_approval_model->get_atasan_pegawai($ses_nip);
        $nama_atasan_langsung = isset($dataatasan[0]->NAMA_ATASAN) ? $dataatasan[0]->NAMA_ATASAN : "";
        $index_ppk = count($dataatasan) - 1;
        $nama_ppk = isset($dataatasan[$index_ppk]->NAMA_ATASAN) ? $dataatasan[$index_ppk]->NAMA_ATASAN : "";
        $tanggal = date("Y-m-d");
        $check_in = date("H:i:s");
        // cek ke data absen apakah sudah pernah kirim absen sebelumnya jika sudah pernah kirim absen berarti absen yang kedua itu adalah absen pulang/checkout
        $data_absen = $this->absen_model->getdata_absen($ses_nip,$tanggal);
        
        $jam_checkin = isset($data_absen[0]->JAM) ? $data_absen[0]->JAM : "";
        $jam_checkout = "";
        if(isset($data_absen[0]->JAM) and count($data_absen) > 1){
            $index_checkout =  count($data_absen) - 1;
            $jam_checkout = isset($data_absen[$index_checkout]->JAM) ? $data_absen[$index_checkout]->JAM : "";
        }


        Template::set_view("presensi/absen");
        Template::set('toolbar_title', "Status Presensi");
        Template::set("tanggal_indonesia",$tanggal_indonesia);
        Template::set("nip",$ses_nip);
        Template::set("hari",$hari);
        Template::set("jam_checkin",$jam_checkin);
        Template::set("jam_checkout",$jam_checkout);
        Template::set("nama_atasan_langsung",$nama_atasan_langsung);
        Template::set("nama_ppk",$nama_ppk);
        Template::render();
    }
    private function get_json_harilibur_tahunan($tahun = ""){
        $this->load->model('izin_pegawai/hari_libur_model');
        $aharilibur = array();
        $record_hari_libur_tahunan = $this->hari_libur_model->find_by_tahun($tahun);
        //print_r($record_hari_libur_tahunan);
        if(isset($record_hari_libur_tahunan) && is_array($record_hari_libur_tahunan) && count($record_hari_libur_tahunan)):
            foreach ($record_hari_libur_tahunan as $record) {
                $atanggal_libur = $this->returnBetweenDates($record->START_DATE,$record->END_DATE);
                $aharilibur = array_merge($aharilibur,$atanggal_libur);
            }
        endif;
        return json_encode($aharilibur);

    }
    function returnBetweenDates( $startDate, $endDate ){
        $startStamp = strtotime(  $startDate );
        $endStamp   = strtotime(  $endDate );

        if( $endStamp > $startStamp ){
            while( $endStamp >= $startStamp ){

                $dateArr[] = date( 'Y-m-d', $startStamp );

                $startStamp = strtotime( ' +1 day ', $startStamp );

            }
            return $dateArr;    
        }else{
            $dateArr[] = $startDate;
            return $dateArr;
        }

    }
    public function perizinan(){
        $this->auth->restrict($this->permissionSettingKirimAbsen);
        $this->load->model('izin_pegawai/hari_libur_model');
        $this->load->library('Convert');
        $convert = new Convert;
        $ses_nip    = trim($this->auth->username());
        $tanggal = date("Y-m-d");
        //GET DATA HARI LIBUR
        $tahun = date("Y");
        $bulan = date("m");
        $record_hari_libur_tahunan = $this->hari_libur_model->find_by_tahun($tahun);
        $record_hari_libur_bulan_ini = $this->hari_libur_model->find_by_bulan($tahun,$bulan);
        Template::set("record_hari_libur_tahunan",$record_hari_libur_tahunan);
        Template::set("record_hari_libur_bulan_ini",$record_hari_libur_bulan_ini);

        $tanggal_indonesia = $convert->fmtDate($tanggal,"dd month yyyy");
        $hari = $convert->Gethari($tanggal);

        $nama_bulan = $convert->getmonth($bulan);
        Template::set('nama_bulan', $nama_bulan);
        // jenis izin
        $jenis_izin = $this->jenis_izin_model->find_active();
        Template::set('jenis_izin', $jenis_izin);

        $status_izin = $this->get_group_status($ses_nip);
        Template::set('status_izin', $status_izin);

        // get data atasan
        $dataatasan = $this->getatasan($ses_nip);//$this->line_approval_model->get_atasan_pegawai($ses_nip);
        $nama_atasan_langsung = isset($dataatasan[0]->NAMA_ATASAN) ? $dataatasan[0]->NAMA_ATASAN : "";
        $index_ppk = count($dataatasan) - 1;
        $nama_ppk = isset($dataatasan[$index_ppk]->NAMA_ATASAN) ? $dataatasan[$index_ppk]->NAMA_ATASAN : "";
        $tanggal = date("Y-m-d");
        $check_in = date("H:i:s");
        // cek ke data absen apakah sudah pernah kirim absen sebelumnya jika sudah pernah kirim absen berarti absen yang kedua itu adalah absen pulang/checkout
        $data_absen = $this->absen_model->getdata_absen($ses_nip,$tanggal);
        
        $jam_checkin = isset($data_absen[0]->JAM) ? $data_absen[0]->JAM : "";
        $jam_checkout = "";
        if(isset($data_absen[0]->JAM) and count($data_absen) > 1){
            $index_checkout =  count($data_absen) - 1;
            $jam_checkout = isset($data_absen[$index_checkout]->JAM) ? $data_absen[$index_checkout]->JAM : "";
        }

        // group by jenis 
        $data_jenisizin = $this->getgroup_byjenis($ses_nip);
        Template::set("data_jenisizin",$data_jenisizin);
        // sum hari group by jenis 
        $data_jumlah_hari = $this->get_sum_hari_group_byjenis($ses_nip);
        Template::set("data_jumlah_hari",$data_jumlah_hari);
        

        Template::set('toolbar_title', "Perizinan Pegawai");
        Template::set("tanggal_indonesia",$tanggal_indonesia);
        Template::set("nip",$ses_nip);
        Template::set("hari",$hari);
        Template::set("jam_checkin",$jam_checkin);
        Template::set("jam_checkout",$jam_checkout);
        Template::set("nama_atasan_langsung",$nama_atasan_langsung);
        Template::set("nama_ppk",$nama_ppk);
        Template::render();
    }
    public function laporanpegawai(){
        $this->auth->restrict($this->permissionLaporanPegawai);
        $this->load->model('izin_pegawai/hari_libur_model');
        $this->load->library('Convert');
        $convert = new Convert;
        $ses_nip    = trim($this->auth->username());
        $tanggal = date("Y-m-d");
        //GET DATA HARI LIBUR
        $tahun = date("Y");
        $bulan = date("m");
        $record_hari_libur_tahunan = $this->hari_libur_model->find_by_tahun($tahun);
        $record_hari_libur_bulan_ini = $this->hari_libur_model->find_by_bulan($tahun,$bulan);
        Template::set("record_hari_libur_tahunan",$record_hari_libur_tahunan);
        Template::set("record_hari_libur_bulan_ini",$record_hari_libur_bulan_ini);

        $tanggal_indonesia = $convert->fmtDate($tanggal,"dd month yyyy");
        $hari = $convert->Gethari($tanggal);

        $nama_bulan = $convert->getmonth($bulan);
        Template::set('nama_bulan', $nama_bulan);
         
        $status_izin = $this->get_group_status($ses_nip);
        Template::set('status_izin', $status_izin);

        // group by jenis 
        $data_jenisizin = $this->getgroup_byjenis($ses_nip);
        Template::set("data_jenisizin",$data_jenisizin);
        // sum hari group by jenis 
        $data_jumlah_hari = $this->get_sum_hari_group_byjenis($ses_nip);
        Template::set("data_jumlah_hari",$data_jumlah_hari);
        // get jumlahperbulan
        $count_perbulan = $this->get_count_perbulan("",$ses_nip);
        Template::set("count_perbulan",$count_perbulan);
        

        Template::set('toolbar_title', "Laporan Izin Pegawai");
        Template::set("tanggal_indonesia",$tanggal_indonesia);
        Template::set("nip",$ses_nip);
        Template::set("hari",$hari);
        Template::render();
    }
    public function lihatgrafik(){
        $this->auth->restrict($this->permissionLaporanPegawai);
        $this->load->library('Convert');
        $convert = new Convert;
        $ses_nip    = trim($this->auth->username());

        $DARI_TANGGAL  = $this->input->post("DARI_TANGGAL");
        $SAMPAI_TANGGAL  = $this->input->post("SAMPAI_TANGGAL");
        
        $status_izin = $this->get_group_status($ses_nip,$DARI_TANGGAL,$SAMPAI_TANGGAL);
        Template::set('status_izin', $status_izin);

        // group by jenis 
        $data_jenisizin = $this->getgroup_byjenis($ses_nip,$DARI_TANGGAL,$SAMPAI_TANGGAL);
        Template::set("data_jenisizin",$data_jenisizin);
        // sum hari group by jenis 
        $data_jumlah_hari = $this->get_sum_hari_group_byjenis($ses_nip,$DARI_TANGGAL,$SAMPAI_TANGGAL);
        Template::set("data_jumlah_hari",$data_jumlah_hari);
        // get jumlahperbulan
        $count_perbulan = $this->get_count_perbulan("",$ses_nip,$DARI_TANGGAL,$SAMPAI_TANGGAL);
        Template::set("count_perbulan",$count_perbulan);
        
        //$output ="";
        $output = $this->load->view('izin/viewgrafik',array('status_izin'=>$status_izin,'data_jenisizin'=>$data_jenisizin,'data_jumlah_hari'=>$data_jumlah_hari,'count_perbulan'=>$count_perbulan),true);  
        echo $output;
    }
    private function getgroup_byjenis($nip = "",$DARI_TANGGAL = "",$SAMPAI_TANGGAL = ""){
        $json_jenis = array();
        $record_jenis_izin = $this->jenis_izin_model->grupby_jenis($nip,$DARI_TANGGAL,$SAMPAI_TANGGAL); 

        $dataprov = array();
        if (isset($record_jenis_izin) && is_array($record_jenis_izin) && count($record_jenis_izin)) :
            foreach ($record_jenis_izin as $record) :
                if($record->NAMA_IZIN != "")
                    $dataprov["NAMA"] = $record->NAMA_IZIN;
                else
                    $dataprov["NAMA"] = "-";
                $dataprov["jumlah"] = $record->jumlah;
                $json_jenis[]   = $dataprov;
            endforeach;
        endif;
        return json_encode($json_jenis);
    }
    private function get_count_perbulan($tahun = "",$nip = "",$DARI_TANGGAL = "",$SAMPAI_TANGGAL = ""){
        $datarealisasi          = array(); 
        for($i=1;$i<13;$i++){
            $datarealisasi[$i] = 0;
        }

        $json_jenis = array();
        $record_jenis_izin = $this->izin_pegawai_model->group_count_per_bulan($tahun,$nip,$DARI_TANGGAL,$SAMPAI_TANGGAL); 

        $dataprov = array();
        if (isset($record_jenis_izin) && is_array($record_jenis_izin) && count($record_jenis_izin)) :
            foreach ($record_jenis_izin as $record) :
                $datarealisasi[trim($record->month)] = $record->jumlah;
            endforeach;
        endif;
        $nilai = "[".$datarealisasi[1].",".$datarealisasi[2].",".$datarealisasi[3].",".$datarealisasi[4].",".$datarealisasi[5].",".$datarealisasi[6].",".$datarealisasi[7].",".$datarealisasi[8].",".$datarealisasi[9].",".$datarealisasi[10].",".$datarealisasi[11].",".$datarealisasi[12]."]";
        return $nilai;
    }
    private function get_sum_hari_group_byjenis($nip = "",$DARI_TANGGAL = "",$SAMPAI_TANGGAL = ""){
        $json_jenis = array();
        $record_jenis_izin = $this->izin_pegawai_model->sum_hari_grupby_jenis($nip,$DARI_TANGGAL,$SAMPAI_TANGGAL); 

        $dataprov = array();
        if (isset($record_jenis_izin) && is_array($record_jenis_izin) && count($record_jenis_izin)) :
            foreach ($record_jenis_izin as $record) :
                if($record->NAMA_IZIN != "")
                    $dataprov["NAMA"] = $record->NAMA_IZIN;
                else
                    $dataprov["NAMA"] = "-";
                $dataprov["jumlah"] = $record->jumlah;
                $json_jenis[]   = $dataprov;
            endforeach;
        endif;
        return json_encode($json_jenis);
    }
    private function get_group_status($nip = "",$DARI_TANGGAL = "",$SAMPAI_TANGGAL = ""){
        $astatus = array();
        $record_status = $this->izin_pegawai_model->grupby_status($nip,$DARI_TANGGAL,$SAMPAI_TANGGAL); 
        if (isset($record_status) && is_array($record_status) && count($record_status)) :
            foreach ($record_status as $record) :
                $astatus[$record->STATUS_PENGAJUAN]   = $record->jumlah;
            endforeach;
        endif;
        return $astatus;
    }
    
    private function getatasan($ses_nip = ""){
        $dataatasan = $this->line_approval_model->get_atasan_pegawai($ses_nip);
        return $dataatasan;
    }
    public function kirimkehadiran(){
        $this->auth->restrict($this->permissionSettingKirimAbsen);
        $this->load->library('Api_kehadiran');
        $api_kehadiran = new Api_kehadiran;
        $result = false;
        $msg = "";
        $response = array(
            'success'=>$result,
            'msg'=>'Unknown error'
        );
        $this->form_validation->set_rules('NIP','NIP','required|max_length[30]');
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

        $ses_nip    = trim($this->auth->username());
        $nip   = $this->input->post('NIP') ? $this->input->post('NIP') : $ses_nip;
        $tanggal = date("Y-m-d");
        $check_in = date("H:i:s");
        $waktu = $this->get_waktu();
        $check_in = $this->conversi_jam($check_in,$waktu);
        // cek ke data absen apakah sudah pernah kirim absen sebelumnya jika sudah pernah kirim absen berarti absen yang kedua itu adalah absen pulang/checkout
        $data_absen = $this->absen_model->getdata_absen($nip,$tanggal);
        if(isset($data_absen[0]->ID) && $data_absen[0]->ID != ""){
            // kirim ke server kehadiran
            log_activity($this->auth->user_id(), 'Kirim ke server kehadiran : ' .$data_absen[0]->ID. $this->input->ip_address(), 'absen');    
        }else{
            // save biasa
        }

        // save absen ke tabel absen
        $data_pegawai = $this->pegawai_model->find_by("NIP_BARU",$nip);
        $NAMA_PEGAWAI = isset($data_pegawai->NAMA) ? $data_pegawai->NAMA : "";
        if($NAMA_PEGAWAI == ""){
            $response['msg'] = "
            <div class='alert alert-block alert-error fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                    Error
                </h4>
                NIP tidak ditemukan.. silahkan hubungi administrator
            </div>
            ";
            echo json_encode($response);
            exit();
        }
        $data = array();
        $data['NIP'] = $nip;
        $data['NAMA'] = $NAMA_PEGAWAI;
        $data['TANGGAL'] = $tanggal;
        $data['JAM'] = $check_in;
        $this->absen_model->skip_validation(true);
        if($insert_id = $this->absen_model->insert($data)){
            log_activity($this->auth->user_id(), 'Save data absen dari web : ' . $insert_id . ' : ' . $this->input->ip_address(), 'absen');    
            $result = true;
            // kirim log absen ke kehdiran
             $send_absen = $api_kehadiran->send_log_absen($nip,$tanggal,$check_in);

        }
        $response ['success']= $result;
        $response ['msg']= $msg;
        $response ['jam']= $check_in;
        echo json_encode($response);    
        // $start_date = "2020-09-01";
        // $end_date = "2020-09-20";
        
        //$json_absen = $api_kehadiran->getabsen($ses_nip,$start_date,$end_date);
        // test kirim absen
        
        
        $check_out = "16:55:03";
        $terlambat = "0";
        $pulang_cepat = "0";
        $ot_before = "0";
        $ot_after = "0";
        //$send_absen = $api_kehadiran->sendabsen($ses_nip,$tanggal,$check_in,$check_out,$terlambat,$pulang_cepat,$ot_before,$ot_after,$workinonholiday);
    }
    public function tap_absen(){
        $this->auth->restrict($this->permissionSettingKirimAbsen);

        $s  = $this->input->post('o');
        for ($i = 1; $i <= 10; $i++) {
            $s = base64_decode($s);
        }
        $params = json_decode($s);
        $csrf_key = null;
        $csrf_value = null;
        foreach ($params as $key => $val) {
            if (strpos($key, "bismillah_sing_jujur_") === false) {
            } else {
                $csrf_key = $key;
                $csrf_value = $val;
            }
        }
        $this->load->library('Api_kehadiran');
        $api_kehadiran = new Api_kehadiran;
        $result = false;
        $msg = "";
        $response = array(
            'success'=>$result,
            'message'=>'Unknown error'
        );
        $nip    = trim($this->auth->username());
        $tanggal = date("Y-m-d");
        $check_in = date("H:i:s");
        $waktu = $this->get_waktu();
        $check_in = $this->conversi_jam($check_in,$waktu);
        // cek ke data absen apakah sudah pernah kirim absen sebelumnya jika sudah pernah kirim absen berarti absen yang kedua itu adalah absen pulang/checkout
        $data_absen = $this->absen_model->getdata_absen($nip,$tanggal);
        if(isset($data_absen[0]->ID) && $data_absen[0]->ID != ""){
            // kirim ke server kehadiran
            log_activity($this->auth->user_id(), 'Kirim ke server kehadiran : ' .$data_absen[0]->ID. $this->input->ip_address(), 'absen');    
        }else{
            // save biasa
        }
        // save absen ke tabel absen
        $data_pegawai = $this->pegawai_model->find_by("NIP_BARU",$nip);
        $NAMA_PEGAWAI = isset($data_pegawai->NAMA) ? $data_pegawai->NAMA : "";
        if($NAMA_PEGAWAI == ""){
            $response['message'] = "
            <div class='alert alert-block alert-error fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                    Error
                </h4>
                NIP tidak ditemukan.. silahkan hubungi administrator
            </div>
            ";
            echo json_encode($response);
            exit();
        }
        $latitude = @$params->lat;
        $longitude = @$params->lng;

        $data = array();
        $data['NIP'] = $nip;
        $data['NAMA'] = $NAMA_PEGAWAI;
        $data['TANGGAL'] = $tanggal;
        $data['JAM'] = $check_in;
        $data['input_type'] = 2;
        $data['keterangan'] = "Absen via Web GPS";
        $data['latitude'] = $latitude;
        $data['longitude'] = $longitude;
        $this->absen_model->skip_validation(true);
        if($insert_id = $this->absen_model->insert($data)){
            log_activity($this->auth->user_id(), 'Save data absen dari web : ' . $insert_id . ' : ' . $this->input->ip_address(), 'absen');    
            $result = true;
            $msg = "Berhasil simpan BDR";
            // kirim log absen ke kehdiran
            $send_absen = $api_kehadiran->send_log_absen($nip,$tanggal,$check_in);

        }
        $response ['success']= $result;
        $response ['message']= $msg;
        $response ['jam']= $check_in;
        print_json($response);    
    }
    public function tandatangansk()
    {
        $id_table = $this->input->post('id_file');
        $id_file = md5($id_table);
        $usercert = $this->input->post('username');
        $passphrase = $this->input->post('passphrase');
        $NIP = $this->input->post('NIP');
        $direktori = trim($this->settings_lib->item('site.pathlampiranizin'));
        $direktorispesimen = trim($this->settings_lib->item('site.pathspesimen'));
        
        $halaman_ttd = 1;
        $show_qrcode = 1;
        $letak_ttd = 2;

        $status = false;
        $msg = "";

        $this->load->library('signature/esign');
        $esign = new Esign();
        if($usercert != "" && $passphrase != ""){
            $spesimen = "default.png";
            if (file_exists($direktorispesimen.$NIP.'.png')) {
                $spesimen = $NIP.'.png';
            }
            //jika ada file yang pernah di ttd, di ttd ulang
            $file_siap_ttd = $direktori.$id_file.'.pdf';
            $return = $esign->sign($usercert,$passphrase,$file_siap_ttd,$direktorispesimen.$spesimen,$halaman_ttd,$show_qrcode,$letak_ttd);
            if($return == 1){
                // cek file signed
                $filesigned = $direktori.$id_file."_signed.pdf";
                $base64signed = "";
                if (file_exists($filesigned)) {
                    $base64signed = chunk_split(base64_encode(file_get_contents($filesigned)));
                    unlink($file_siap_ttd);
                    if (!copy($filesigned, $file_siap_ttd)) {
                        echo "failed to copy $file...\n";
                    }
                }
                $status = true;
                $msg = "Dokumen sudah ditandatangan";
                $data = array();
                if($base64signed != ""){
                    $data["TEXT_BASE64_SIGN"]      = $base64signed;    
                }
                $data["IS_SIGNED"]      = "1";
                $this->izin_pegawai_model->skip_validation(true);
                $this->izin_pegawai_model->update($id_table,$data);
                $this->save_log($id_table,$usercert,"Berhasil Tandatangan cuti",2);
                log_activity($this->auth->user_id(),"Berhasil Tandatangan cuti" . ': ' . $id_table . ' : ' . $this->input->ip_address(), 'izin_pegawai');
                // add log riwayat
                $ses_nip = trim($this->auth->username());
                $this->save_riwayat_ds($id_table,$ses_nip,"Berhasil Tandatangan","");
                // end log riwayat
            }else{
                // save log

                log_activity($this->auth->user_id(),"Gagal Tandatangan" . ': ' . $id_table ."-".$return. ' : ' . $this->input->ip_address(), 'izin_pegawai');
                $this->save_log($id_table,$usercert,$return,1);
                // add log riwayat
                $ses_nip = trim($this->auth->username());
                $this->save_riwayat_ds($id_table,$ses_nip,"Gagal tandatangan SK","");
                // end log riwayat
                $msg = $return;
            }
        }else{
            $msg = "Silahkan Lengkapi NIK dan Passphrase ";
        }
        
        $response ['success']= $status;
        $response ['msg']= $msg;
        echo json_encode($response);   
        exit();
    }
    private function save_log($ID_FILE = "",$NIK = "",$KETERANGAN = "",$STATUS = "")
    {
        date_default_timezone_set("Asia/Bangkok");
        // get data detil
        $data = array();
        if($NIK != ""){
            $data["ID_FILE"]    = $ID_FILE;
            $data["NIK"]        = $NIK;
            $data["KETERANGAN"]          = $KETERANGAN;
            $data["CREATED_DATE"]        = date("Y-m-d h:i:s");
            $data["CREATED_BY"]          = $this->auth->user_id();
            $data["STATUS"]          = $STATUS;
            
            $this->log_ds_model->insert($data);
        }
    }
    private function save_riwayat_ds($ID_FILE = "",$NIP = "",$TINDAKAN = "",$CATATAN_TINDAKAN = "")
    {
        date_default_timezone_set("Asia/Bangkok");
        // get data detil
        $data = array();
        if($NIP != ""){
            $data["id_file"]    = $ID_FILE;
            $data["id_pemroses"]        = $NIP;
            $data["tindakan"]          = $TINDAKAN;
            $data["catatan_tindakan"]          = $CATATAN_TINDAKAN;
            $data["waktu_tindakan"]        = date("Y-m-d h:i:s");
            $data["akses_pengguna"]          = "Web";
            $this->ds_riwayat_model->insert($data);
        }
    }
    public function viewdoc($id = "")
    {
        if (empty($id)) {
            Template::set_message("ID tidak ditemukan", 'error');
        }
        $datadetil = $this->izin_pegawai_model->find($id);
        $FILE_BASE64 = "data:application/pdf;base64,".$datadetil->TEXT_BASE64_SIGN;
        if (file_exists(trim($this->settings_lib->item('site.pathlampiranizin')).md5($id).'_signed.pdf')) {
            $FILE_BASE64 = trim($this->settings_lib->item('site.urllampiranizin')).md5($id).'_signed.pdf';
        }
        //die(" asda".$FILE_BASE64);
        echo '<embed src="'.$FILE_BASE64.'" width="100%" height="700" alt="pdf">';
        die();
    }
    private function conversi_jam($jam = "",$waktu = "WIB")
    {
        if(TRIM($waktu) == "WITA")
            $time = date('H:i:s', strtotime($jam.'+1 hour'));
        elseif(TRIM($waktu) == "WIT")
            $time = date('H:i:s', strtotime($jam.'+2 hour'));
        else
            $time = $jam;

        return $time;
    }
    private function get_waktu(){
        $this->load->model('pegawai/unitkerja_model');
        $UNOR_ID = $this->pegawai_model->getunor_induk($this->auth->username());
        $unor = "";
        if($UNOR_ID != ""){
            $unor = $this->unitkerja_model->find_by("ID",trim($UNOR_ID));    
        }
        $WAKTU = $unor->WAKTU ? trim($unor->WAKTU) : "WIB"; // default wib
        return $WAKTU;
    }
    public function upload_data_cuti(){
        $this->auth->restrict($this->permissionUploaddata);
        Template::set('toolbar_title', "Upload data cuti");
        Template::render();
    }
    public function proses_upload($nama_file){
        $this->auth->restrict($this->permissionUploaddata);
        $file = $nama_file;
        $this->load->library('Excel');
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();
        $nips = array();
        $usulans = array();
        for ($row = 2; $row <= $highestRow; $row++)
        {
            //$nip = trim(preg_replace('/\s\s+/', ' ', $string));
            $exist = $this->izin_pegawai_model->validate_tanggal($sheet->getCell('B'.$row)->getValue(),date("Y-m-d", strtotime($sheet->getCell('E'.$row)->getValue())),date("Y-m-d", strtotime($sheet->getCell('F'.$row)->getValue())));
            if(!$exist){
                $nips[] =  $sheet->getCell('B'.$row)->getValue();
                $rec = array(
                    'NIP_PNS'=>$sheet->getCell('B'.$row)->getValue(),
                    'NAMA'=>$sheet->getCell('C'.$row)->getValue(),
                    'KODE_IZIN'=>$sheet->getCell('D'.$row)->getValue(),
                    'DARI_TANGGAL'=>date("Y-m-d", strtotime($sheet->getCell('E'.$row)->getValue())),
                    'SAMPAI_TANGGAL'=>date("Y-m-d", strtotime($sheet->getCell('F'.$row)->getValue())),
                    'TAHUN'=>(int)$sheet->getCell('H'.$row)->getValue(),
                    'JUMLAH'=>$sheet->getCell('G'.$row)->getValue(),
                    'ALASAN_CUTI'=>$sheet->getCell('I'.$row)->getValue(),
                    'SATUAN'=>"Hari",
                    'STATUS_PYBMC'=>1,
                    'STATUS_ATASAN'=>1,
                    'STATUS_PENGAJUAN'=>3,
                    'SATUAN'=>"Hari",
                );
                $usulans[$sheet->getCell('B'.$row)->getValue()] = $rec;
            }
            
        }
        if(sizeof($nips)==0){
            return;
        }
        
        $data = array();
        foreach($usulans as $key=>$record){
            $data[] = $record;
        }
        $data =  $this->db->insert_batch("izin",$data);
        return $data;
    }
    public function do_upload(){
        $this->auth->restrict($this->permissionUploaddata);
        $this->load->helper('handle_upload');
        $result = false;
        $msg = "";
        if (isset($_FILES['userfile']) && is_array($_FILES['userfile']) && $_FILES['userfile']['error'] != 4)
        {
           $tmp_name = pathinfo($_FILES['userfile']['name'], PATHINFO_FILENAME);
           $data = $this->proses_upload($_FILES['userfile']['tmp_name']);
           if($data){
                $result = true;
                $msg = "Upload berhasil";
           }else{
            $msg = "Upload gagal";
            }  
        }else{
            $msg = "Silahkan lengkapi file";
        }   
        $output = array(
                'success'=>$result,
                'message'=> $msg
           );
        echo json_encode($output);
        exit();
    }
    public function bdr(){
        $this->load->library('Convert');
        $convert = new Convert;
        // absen WFH
        $tanggal = date("Y-m-d");
        $tanggal_indonesia = $convert->fmtDate($tanggal,"dd month yyyy");
        $hari = $convert->Gethari($tanggal);
        $ses_nip    = trim($this->auth->username());
        $WAKTU = $this->get_waktu();
        $tanggal = date("Y-m-d");
        // $data_absen = $this->absen_model->getdata_absen($ses_nip,$tanggal);
        // Template::set("data_absen",$data_absen);
        Template::set("WAKTU",$WAKTU);
        Template::set('tanggal_indonesia', $tanggal_indonesia);
        Template::set('hari', $hari);

        Template::set('toolbar_title', "Tap Presensi BDR");
        Template::render();
    }
    public function history_absen()
    {
        $ses_nip    = trim($this->auth->username());
        $tanggal = date("Y-m-d");
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;
        $total= $this->absen_model->countdata_absen($ses_nip,$tanggal);
        $orders = $this->input->post('order');
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        $this->izin_pegawai_model->limit($length,$start);
        $data_absen = $this->absen_model->getdata_absen($ses_nip,$tanggal);
        if(isset($data_absen) && is_array($data_absen) && count($data_absen)):
            foreach ($data_absen as $record) {
                $row = array();
                $row []  = $record->TANGGAL." ".$record->JAM ;
                $row []  = $record->latitude.",".$record->longitude;
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);   
    }
    private function get_pegawai_status_pengajuan($satker = ""){
        $data = $this->izin_pegawai_model->find_group_pegawai_status($satker);
        $adata = array();
        if(isset($data) && is_array($data) && count($data)):
            foreach ($data as $record) {
                if($record->STATUS_PENGAJUAN == 2)
                    $record->STATUS_PENGAJUAN = 1;
                $adata[$record->NIP_PNS."_".$record->STATUS_PENGAJUAN]  = $record->jumlah;
            }
        endif;
        return $adata;
    }
    public function create_saldo($ses_nip,$TAHUN){
        $this->load->helper('dikbud');
        $data_cuti = getSisaCuti($ses_nip,$TAHUN);
        if(!isset($data_cuti->ID) or $data_cuti->ID == ""){
            $SISA_N = 12;
            $SISA_N_1 = 0;
            $SISA_N_2 = 0;
            $SISA = 12;
            $tahun_1 = $TAHUN - 1;
            $data_cuti_1 = getSisaCuti($ses_nip,(string)$tahun_1);
            // echo "<pre>";
            // print_r($data_cuti_1);
            // echo "<pre>";
            if($data_cuti_1->ID != ""){
                if($data_cuti_1->SISA >= 6){
                    $SISA_N_1 = 6;
                    $SISA_N = 12;
                    $SISA = 12 + 6;
                }
                if($data_cuti_1->SISA < 6 && $data_cuti_1->SISA > 0){
                    $SISA_N_1 = (int)$data_cuti_1->SISA;
                    $SISA_N = 12;
                    $SISA = 12 + (int)$data_cuti_1->SISA;
                }
                if($data_cuti_1->SISA >= 12 && $data_cuti_1->SISA_N_1 >= 12){
                    $SISA_N_1 = 6;
                    $SISA_N_2 = 6;
                    $SISA_N = 12;
                    $SISA = 12 + 12;
                }
            }else{
                $SISA_N_1 = 0;
                $SISA_N_2 = 0;
                $SISA_N = 12;
                $SISA = 12;
            }
            $data_pegawai = $this->pegawai_model->find_by("NIP_BARU",$ses_nip);
            $NAMA_PEGAWAI = isset($data_pegawai->NAMA) ? $data_pegawai->NAMA : "";
            $data['PNS_NIP'] = $ses_nip;
            $data['NAMA'] = $NAMA_PEGAWAI;
            $data['TAHUN'] = $TAHUN;
            $data['SISA_N'] = (int)$SISA_N;
            $data['SISA_N_1'] = (int)$SISA_N_1;
            $data['SISA_N_2'] = (int)$SISA_N_2;
            $data['SISA'] = (int)$SISA;
            $this->sisa_cuti_model->skip_validation(true);
            if($insert_id = $this->sisa_cuti_model->insert($data)){
                log_activity($this->auth->user_id(), 'Save data sisa cuti : ' . $insert_id . ' : ' . $this->input->ip_address(), 'sisa_cuti');    
            }
        }
    }
}