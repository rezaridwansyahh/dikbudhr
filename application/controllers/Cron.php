<?php defined("BASEPATH") OR exit("No direct script access allowed");

class Cron extends MX_Controller
{
    public $file;
    public $path;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper("file");
        $this->load->helper("directory");
        $this->path = APPPATH . "cache" . DIRECTORY_SEPARATOR;
        $this->file = $this->path . "cron.txt";
        if (!$this->input->is_cli_request())
        {
            // die("anda tidak ada akses");
        }

        $this->db_mysql_local = $this->load->database('db_mysql_local',true);
        $this->load->model('pegawai/riwayat_jabatan_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/jenis_jabatan_model');
        $this->load->model('ref_jabatan/jabatan_model');
        $this->load->model('pegawai/unitkerja_model');
        $this->load->model('pegawai/riwayat_kepangkatan_model');
        $this->load->model('sk_ds/log_ds_model');
        // $this->db_hrms = $this->load->database('db_hrms',true);
    }

    public function index()
    {
        if ($this->input->is_cli_request())
        {
            $date = date("Y:m:d h:i:s");
            $data = $date . " --- Cron test from CI";

            $this->write_file($data);
        }
        else
        {
            exit;
        }
    }
    public function synch_ds($jumlah = 10){
        $this->load->model('sk_ds/vw_sync_ds_model');
        $record_ds = $this->vw_sync_ds_model->find_all($jumlah);
        $a_kategory = $this->get_array_kategori();
        if(isset($record_ds) && is_array($record_ds) && count($record_ds)){
            foreach ($record_ds as $record) {
                if(isset($a_kategory[$record->kategori])){
                    if($a_kategory[$record->kategori] == "JABATAN" or $a_kategory[$record->kategori] == "data_pegawai_jabatan"){
                        $this->sync_jabatan($record->nip_sk);
                        $this->update_log_ds($record->ID);
                    }
                    if($a_kategory[$record->kategori] == "PANGKAT" or $a_kategory[$record->kategori] == "data_pegawai_pangkat"){   
                        $this->synct_pangkat($record->nip_sk);  
                        $this->update_log_ds($record->ID);
                    }
                }
            }
        }
    }
    private function sync_jabatan($nip){
        $records_jabatan = $this->get_pegawai_jabatan($nip);  
         // print_r($records_jabatan);
        if(isset($records_jabatan) && is_array($records_jabatan) && count($records_jabatan)){
            foreach ($records_jabatan as $record) {
                if($this->exist_jabatan($nip,$record->NOMOR_SK_PELANTIKAN)){
                    $this->pegawai_model->where("NIP_BARU",$nip);
                    $pegawai_data = $this->pegawai_model->find_first_row();  
                    $NIP_BARU = $pegawai_data->NIP_BARU;
                    $data["PNS_NIP"] = $pegawai_data->NIP_BARU;
                    $data["PNS_ID"] = $pegawai_data->PNS_ID;
                    $data["PNS_NAMA"] = $pegawai_data->NAMA;
                    
                    
                    $jenis_jabatan = "";
                    if($record->KODE_JABATAN != ""){
                        $rec_jabatan = $this->jabatan_model->find_by("KODE_JABATAN",$record->KODE_JABATAN);
                        $data["ID_JABATAN"] = isset($rec_jabatan->KODE_JABATAN) ? $rec_jabatan->KODE_JABATAN : "";
                        $data["NAMA_JABATAN"] = $rec_jabatan->NAMA_JABATAN;
                        $data["ID_JABATAN_BKN"] = isset($rec_jabatan->KODE_BKN) ? $rec_jabatan->KODE_BKN : "";
                        $jenis_jabatan = isset($rec_jabatan->JENIS_JABATAN) ? $rec_jabatan->JENIS_JABATAN : "";
                    }
                    if($jenis_jabatan != ""){
                        $rec_jenis = $this->jenis_jabatan_model->find($jenis_jabatan);
                        $data["ID_JENIS_JABATAN"]   = $rec_jenis->ID;
                        $data["JENIS_JABATAN"]      = $rec_jenis->NAMA;
                    }

                    // struktur
                    $unit_kerja = $this->unitkerja_model->find_by("KODE_INTERNAL",$record->KODE_UNIT_KERJA); // POST
                    $data["ID_UNOR_BKN"]    = isset($unit_kerja->ID) ? $unit_kerja->ID : "";
                    $data["ID_UNOR"]    = isset($unit_kerja->ID) ? $unit_kerja->ID : "";
                    $data["UNOR"]           = isset($unit_kerja->NAMA_UNOR) ? $unit_kerja->NAMA_UNOR : "";
                    
                    $data["NOMOR_SK"] = $record->NOMOR_SK_PELANTIKAN;
                    $data["TANGGAL_SK"] = $record->TGL_SK_PELANTIKAN;
                    $data["TMT_JABATAN"] = $record->TGL_SK_PELANTIKAN;
                    $data["TMT_PELANTIKAN"] = $record->TGL_SK_PELANTIKAN;
                    $data["ID_SATUAN_KERJA"]    = isset($unit_kerja->ID) ? $unit_kerja->ID : "";
                    if($record->AKTIF == "AKTIF"){
                        $data["IS_ACTIVE"] = "1";
                    }
                    if(empty($data["TMT_JABATAN"])){
                        unset($data["TMT_JABATAN"]);
                    }
                    if(empty($data["TANGGAL_SK"])){
                        unset($data["TANGGAL_SK"]);
                    }
                    if(empty($data["TMT_PELANTIKAN"])){
                        unset($data["TMT_PELANTIKAN"]);
                    }
                    $data["LAST_UPDATED"]   = date("Y-m-d");
                    $this->riwayat_jabatan_model->insert($data,"");
                }
            }
        }else{
            echo "Riwayat jabatan tidak ditemukan NIP ".$nip;
        }              
    }
    private function synct_pangkat($nip){
        $records_pangkat = $this->get_pegawai_pangkat($nip); 
        $a_pangkat = $this->get_array_golongan();
        if(isset($records_pangkat) && is_array($records_pangkat) && count($records_pangkat)){
            foreach ($records_pangkat as $record) {
                if($this->exist_pangkat($nip,$record->GOL)){
                    $master_pangkat = isset($a_pangkat[$record->GOL]) ? $a_pangkat[$record->GOL] : NULL;
                    $ID_PANGKAT = $master_pangkat->ID;
                    $golongan = $master_pangkat->NAMA;
                    $NAMA_PANGKAT = $master_pangkat->NAMA_PANGKAT;
                    // update riwayat
                    $this->pegawai_model->where("NIP_BARU",$nip);
                    $pegawai_data = $this->pegawai_model->find_first_row();  
                    $NIP_BARU = $pegawai_data->NIP_BARU;
                    $data["PNS_NIP"] = $pegawai_data->NIP_BARU;
                    $data["PNS_ID"] = $pegawai_data->PNS_ID;
                    $data["PNS_NAMA"] = $pegawai_data->NAMA;
                    
                    $data["JENIS_KP"] = $record->JENIS_KP;
                    $data["KODE_JENIS_KP"] = NULL;// tidak ada di tabel
                    
                    $data["ID_GOLONGAN"] = $ID_PANGKAT; //contoh 21
                    $data["GOLONGAN"] = $golongan; // contoh III/a
                    $data["PANGKAT"] = $NAMA_PANGKAT; // nama pangkat contoh Pengatur Muda

                    $data["NOMOR_BKN"] = $record->NOMOR_BKN;
                    $data["SK_NOMOR"] = $record->NOMOR_SK;
                    $data["SK_TANGGAL"] = $record->TGL_SK;
                    $data["TMT_GOLONGAN"] = $record->TMT;
                    $data["TANGGAL_BKN"] = $record->TGL_BKN;

                    $data["MK_GOLONGAN_TAHUN"] = $record->MASA_KERJA_TAHUN;
                    $data["MK_GOLONGAN_BULAN"] = $record->MASA_KERJA_BULAN;

                    if(empty($data["TMT_GOLONGAN"])){
                        unset($data["TMT_GOLONGAN"]);
                    }
                    if(empty($data["SK_TANGGAL"])){
                        unset($data["SK_TANGGAL"]);
                    }
                    if(empty($data["TANGGAL_BKN"])){
                        unset($data["TANGGAL_BKN"]);
                    }
                    $insert_id = $this->riwayat_kepangkatan_model->insert($data);
                }
            }
        }else{
            echo "Riwayat pangkat tidak ditemukan NIP ".$nip;
        }
    }
    private function exist_jabatan($nip,$no_sk){
        $this->riwayat_jabatan_model->where("PNS_NIP",$nip); 
        $this->riwayat_jabatan_model->where("NOMOR_SK",$no_sk); 
        $jumlah = $this->riwayat_jabatan_model->count_all();
        return $jumlah<=0;
    }
    private function exist_pangkat($nip,$golongan){
        $this->riwayat_kepangkatan_model->where("PNS_NIP",$nip); 
        $this->riwayat_kepangkatan_model->where("GOLONGAN",$golongan); 
        $jumlah = $this->riwayat_kepangkatan_model->count_all();
        return $jumlah<=0;
    }
    private function get_pegawai_jabatan($nip){
        $this->db_mysql_local->select("ID_JABATAN,NIP,KODE_JABATAN,NOMOR_SK_PELANTIKAN,
            TGL_SK_PELANTIKAN,PENETAP_SK,TGL_PELANTIKAN,AKTIF,CATATAN,KODE_UNIT_KERJA,
            JENIS_SK,LOKASI_PELANTIKAN,JAM_LANTIK,PEJABAT_PELANTIK,ANGKA_KREDIT,NOMOR_PERMEN_TUKIN");
        $this->db_mysql_local->where("NIP",$nip);
        $this->db_mysql_local->from("data_pegawai_jabatan");
        $records = $this->db_mysql_local->get()->result();
        return $records;
    }
    private function get_pegawai_pangkat($nip){
        $this->db_mysql_local->select("NIP,NOMOR_SK,TGL_SK,
            PANGKAT,GOL,TMT,MASA_KERJA_TAHUN,MASA_KERJA_BULAN,
            GAJI_POKOK,NOMOR_BKN,TGL_BKN,JENIS_KP");
        $this->db_mysql_local->where("NIP",$nip);
        $this->db_mysql_local->from("data_pegawai_pangkat");
        $records = $this->db_mysql_local->get()->result();
        return $records;
    }
    private function get_array_kategori(){
        $this->db->where("kaitan is not null");
        $this->db->from("tbl_kategori_dokumen");
        $records = $this->db->get()->result();
        $a_kategory = array();
        if(isset($records) && is_array($records) && count($records)){
            foreach ($records as $record) {
                $a_kategory[trim($record->kategori_dokumen)] = trim($record->kaitan);
            }
        }
        return $a_kategory;
    }
    private function get_array_golongan(){
        $this->db->from("golongan");
        $records = $this->db->get()->result();
        $a_golongan = array();
        if(isset($records) && is_array($records) && count($records)){
            foreach ($records as $record) {
                $a_golongan[trim($record->NAMA)] = $record;
            }
        }
        return $a_golongan;
    }
    private function update_log_ds($id){
        $data = array();
        $data['PROSES_CRON'] = 1;
        if($id != ""){
            $this->log_ds_model->skip_validation(true);
            $return = $this->log_ds_model->update_where("ID",$id,$data);
        }
    }
    private function write_file($data)
    {
        write_file($this->file, $data . "\n", "a");
    }
    public function test_jabatan()
    {
        $data_jabatan = $this->get_pegawai_jabatan("198608302010121005");
        print_r($data_jabatan);
        die("masuk");
    }
}