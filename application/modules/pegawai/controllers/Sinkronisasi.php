<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Sinkronisasi extends Admin_Controller
{
    protected $permissionSinkronisasi = 'Pegawai.Sinkronisasi.View';
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
		set_time_limit(0);
		parent::__construct(); 
        $this->load->model('pegawai/pegawai_model');
		$this->load->model('pegawai/riwayat_kepangkatan_model');
		$this->load->model('pegawai/riwayat_jabatan_model');
		$this->load->model('pegawai/riwayat_pindah_unit_kerja_model');
        $this->load->model('pegawai/unitkerja_model');
        $this->load->model('pegawai/synch_jumlah_model');
        $this->load->model('ref_jabatan/jabatan_model');
        Assets::add_css('flick/jquery-ui-1.8.13.custom.css');
        Assets::add_js('jquery-ui-1.8.13.min.js');
        $this->form_validation->set_error_delimiters("<span class='has-error'>", "</span>");
        $this->load->model("pegawai/jenis_satker_model");
        $jenis_satkers = $this->jenis_satker_model->find_all();
        Template::set("jenis_satkers",$jenis_satkers); 
    }

    /**
     * Display a list of pegawai data.
     *
     * @return void
     */
    public function index()
    {	

    	$this->auth->restrict($this->permissionSinkronisasi);
        Template::set('toolbar_title', "Sinkronisasi Pegawai");
		
        Template::render();
    }
    public function jumlah()
    {
        $this->auth->restrict($this->permissionSinkronisasi);
        Template::set('toolbar_title', "Sinkronisasi Jumlah Pegawai");
        Template::render();
    }
    public function sinch_jabatan_harian($view = ""){
        $this->auth->restrict($this->permissionSinkronisasi);
        $this->load->model('pegawai/riwayat_jabatan_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/jenis_jabatan_model');
        $this->load->model('ref_jabatan/jabatan_model');
        $this->load->model('pegawai/unitkerja_model');
        $tgl_awal = date("Ymd");
        $tgl_akhir = date("Ymd");

        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
             
            if($filters['dari_tanggal']){
                $tgl_awal = $filters['dari_tanggal'];
            }
            if($filters['sampai_tanggal']){
                $tgl_akhir = $filters['sampai_tanggal'];
            }
             
        }
        $records = $this->get_log_jabatan($tgl_awal,$tgl_akhir);
        $records = json_decode($records);
        $output['recordsTotal']= $output['recordsFiltered'] = count($records);
        $output['data']=array();
        $nomor = 1;
        $max_nomor = $start+$length;
        foreach ($records as $record) :
                $row = array();
                // pegawai
                $msg_aktif = "";
                if($record->aktif == "AKTIF"){
                    $msg_aktif = "<b>Jabatan Aktif</b>";
                }
                $pegawai_data = $this->pegawai_model->find_by("NIP_BARU",$record->nip);
                $row []  = $nomor.".";
                $row []  = $record->id_tabel;
                $row []  = $record->nip."<br><i>".$pegawai_data->NAMA."</i>";
                $row []  = $record->nomor_sk."<br><i>".$record->tanggal_sk."</i><br>".$msg_aktif;
                $row []  = $record->tindakan."<br>"."Tgl : ".$record->tgl_modifikasi;
                $row []  = $record->jenis_sk;
                $row []  = $record->kode_jabatan_real;
                if($nomor>$start && $nomor <= $max_nomor){
                    $output['data'][] = $row;
                }
                $nomor++;
        endforeach;
        echo json_encode($output);
        exit();
    }
    private function get_log_jabatan($tgl_awal,$tgl_akhir){
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://mutasi.sdm.kemdikbud.go.id/layanan/json/ws_log_riwayat_jabatan.php?awal={$tgl_awal}&&akhir={$tgl_akhir}",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "Cache-Control: no-cache",
            "Postman-Token: 5997b268-5f53-2dcb-41f2-a6850dc3f3ca"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          return $err;
        }
        return $response;
    }
    public function synchjumlah(){
        $this->load->model('pegawai/synch_jumlah_model');
        $unor_id_bkn = $this->input->post('satker_id') != "" ? $this->input->post('satker_id') : "all";
        $eselon_1 = $this->input->post('eselon_1') != "" ? $this->input->post('eselon_1') : "all";
        $data = $this->get_jumlah_pegawai($eselon_1,$unor_id_bkn);
        $records = json_decode($data);
// print_r($data);
        $jml = 0;
        $response = array();
        $response ['success']= true;
        $response ['msg']= "Data ditemukan ".$jml;
        foreach ($records as $record) :
            $cek_data = $this->synch_jumlah_model->exist_jumlah($record->ID_UNOR_BKN);
            $data = array();
            $data['kode_unit_kerja'] = $record->KODE_UNIT_KERJA;
            $data['id_unor_bkn'] = $record->ID_UNOR_BKN;
            $data['nama_eselon_1'] = $record->NAMA_ESELON_I;
            $data['satker'] = $record->SATKER;
            $data['satker_singkatan'] = $record->SATKER_SINGKATAN;
            $data['jumlah_mutasi'] = $record->JUMLAH_PEGAWAI;
            // print_r($data);
            if(!$cek_data){
                $data['jumlah_dikbudhr'] = 0;
                $this->synch_jumlah_model->insert($data);
            }else{
                $this->synch_jumlah_model->update($cek_data->id,$data);
            }
            $jml++;
        endforeach;
        if ($jml>0) {
            $query = "REFRESH MATERIALIZED VIEW mv_jml_unor_induk;";
            $this->db->query($query);
            $response ['success']= true;
            $response ['msg']= "Data ditemukan ".$jml;
        }
        echo json_encode($response);    
        exit();
    }
    private function get_jumlah_pegawai($eselon_1 = "all",$unor_bkn = "all",$nama_satker = "",$tampilan = ""){
        
        $curl = curl_init();
        
        $nomonatif =  "";
        if($tampilan == "nominatif"){
            $nomonatif = "&&laporan=nominatif";
        }
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://mutasi.sdm.kemdikbud.go.id/layanan/json/ws_rekap_pegawai_satker.php?kode_akses=ropeg.123.abc&&id_unor_bkn=".$unor_bkn."&&satker=".urlencode($nama_satker)."&&eselon_i=".$eselon_1.$nomonatif,
          // CURLOPT_URL => 'http://mutasi.sdm.kemdikbud.go.id/layanan/json/ws_rekap_pegawai_satker.php?kode_akses=ropeg.123.abc&&id_unor_bkn=&&satker=Sekretariat%20Jenderal&&eselon_i=&&laporan=nominatif',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          return $err;
        }
        return $response;
    }
    public function proses_sinkron(){
        $this->auth->restrict($this->permissionSinkronisasi);
        $this->load->model('pegawai/riwayat_jabatan_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/jenis_jabatan_model');
        $this->load->model('ref_jabatan/jabatan_model');
        $this->load->model('pegawai/unitkerja_model');

        $tgl_awal = date("Ymd");
        $tgl_akhir = date("Ymd");

        if($this->input->post("dari_tanggal")){
            $tgl_awal = $this->input->post("dari_tanggal");
        }
        if($this->input->post("sampai_tanggal")){
            $tgl_akhir = $this->input->post("sampai_tanggal");
        }
        $records = $this->get_log_jabatan($tgl_awal,$tgl_akhir);
        $records = json_decode($records);
        $nomor = 0;
        $nomor_tidak_ada = 0;
        $nomor_baru = 0;
        $nomor_perubahan = 0;
        $hapus_data = 0;
        $msg = "";
            foreach ($records as $record) :
                $data = array();
                // pegawai
                $pegawai_data = $this->pegawai_model->find_by("NIP_BARU",$record->nip);
                if($pegawai_data && $record->id_bkn_unor != "-"){
                    if (strpos($record->tindakan, 'Hapus Riwayat Jabatan') !== false) {
                        if($record->id_tabel != ""){
                            $datadel      = array('ID_TABEL_MUTASI '=>$record->id_tabel,'PNS_NIP'=>$record->nip);
                            $this->riwayat_jabatan_model->delete_where($datadel);
                            $hapus_data++;
                        }
                    }else{
                        $data["PNS_NIP"]    = $record->nip;
                        $data["PNS_ID"]     = $pegawai_data->PNS_ID;
                        $data["PNS_NAMA"]   = $pegawai_data->NAMA;
                        // jika ada perubahan jabatan aktif, maka ubah di profile dan riwayat jabatan pegawai tersebut
                        // nama jabatan
                        $rec_jabatan = $this->jabatan_model->find_by("KODE_JABATAN",$record->kode_jabatan);
                        $nama_jabatan = isset($rec_jabatan->NAMA_JABATAN) ? $rec_jabatan->NAMA_JABATAN : "";
                        $jenis_jabatan = isset($rec_jabatan->JENIS_JABATAN) ? $rec_jabatan->JENIS_JABATAN : "";
                        $data["ID_JABATAN"] = isset($rec_jabatan->KODE_JABATAN) ? $rec_jabatan->KODE_JABATAN : "";
                        $data["ID_JABATAN_BKN"] = isset($rec_jabatan->KODE_BKN) ? $rec_jabatan->KODE_BKN : "";
                        $data["NAMA_JABATAN"] = $nama_jabatan;
                        $data["IS_ACTIVE"] = "1";
                        
                        // unitkerja
                        $rec_unitkerja = $this->unitkerja_model->find_by("ID",trim($record->id_bkn_unor));
                        $nama_unor = isset($rec_unitkerja->NAMA_UNOR) ? $rec_unitkerja->NAMA_UNOR : "";
                        $UNOR_INDUK = isset($rec_unitkerja->UNOR_INDUK) ? $rec_unitkerja->UNOR_INDUK : "";
                        $data["ID_UNOR_BKN"]    = trim($record->id_bkn_unor);
                        $data["UNOR"]           = $nama_unor;
                        $data["ID_UNOR"]        = trim($record->id_bkn_unor);

                        //jenis jabatan
                        $rec_jenis = $this->jenis_jabatan_model->find($jenis_jabatan);
                        $data["ID_JENIS_JABATAN"]   = $rec_jenis->ID ? $rec_jenis->ID : "";
                        $data["JENIS_JABATAN"]      = $rec_jenis->NAMA ? $rec_jenis->NAMA : "";
                        $data["ID_SATUAN_KERJA"]    = $UNOR_INDUK;
                        $data["NOMOR_SK"]      = $record->nomor_sk;
                        $data["TMT_JABATAN"]      = $record->tmt_sk;
                        $data["TANGGAL_SK"]      = $record->tanggal_sk;
                        $data["ID_TABEL_MUTASI"]      = $record->id_tabel;
                        $cek_data = $this->exist_riwayat_jabatan($record->nip,$record->nomor_sk);
                        if($cek_data){
                            $this->riwayat_jabatan_model->update($cek_data->ID,$data);
                            $nomor_perubahan++;
                        }else{
                            $this->riwayat_jabatan_model->insert($data);
                            $nomor_baru++;
                        }
                        if($record->aktif == "AKTIF"){
                            $dataupdate = array();
                            if($record->kode_jabatan_real != "")
                                $dataupdate['JABATAN_INSTANSI_REAL_ID']     = $record->kode_jabatan_real;
                            if($record->kode_jabatan != "")
                                $dataupdate['JABATAN_INSTANSI_ID']          = $record->kode_jabatan;
                            if($UNOR_INDUK != "")
                                $dataupdate['UNOR_INDUK_ID']                = $UNOR_INDUK;
                            if(trim($record->id_bkn_unor) != "")
                                $dataupdate['UNOR_ID']                      = trim($record->id_bkn_unor);
                            $dataupdate['TMT_JABATAN']    = $record->tmt_sk ? $record->tmt_sk : null;

                            $this->pegawai_model->skip_validation(true);
                            $this->pegawai_model->update_where("NIP_BARU",$record->nip, $dataupdate);
                        }
                        $nomor++;
                    }
                }else{
                    $nomor_tidak_ada++;
                }
            endforeach;
        $msg = $nomor. " Pegawai sukses update, ".$nomor_tidak_ada. " gagal karena pegawai tidak ditemukan, ".$nomor_baru. " Data baru,".$nomor_perubahan." Perubahan data, ".$hapus_data." hapus data ";
        log_activity_pegawai($this->auth->user_id(),$this->auth->user_id(),$msg, 'sinkronisasi');

        $response ['success']= true;
        $response ['msg']= $msg;
        echo json_encode($response);    
        exit();
    }
    private function exist_riwayat_jabatan($nip,$no_sk = ""){
        $this->riwayat_jabatan_model->where('NOMOR_SK',$no_sk);
        $data_rwt_jabatan = $this->riwayat_jabatan_model->find_by("PNS_NIP",$nip);
        return $data_rwt_jabatan;
    }
    public function viewpersonal(){
        $this->load->model('ref_jabatan/jabatan_model');
        $this->load->model('pegawai/unitkerja_model');
        $nip = $this->input->post('nip');
        $hasil = $this->getLogPersonal($nip);
        $res = json_decode($hasil);
        $rec_jabatan = $this->jabatan_model->find_by("KODE_JABATAN",$res[0]->ID_JABATAN_INTERNAL);
        if(!$rec_jabatan)
            $rec_jabatan = $this->jabatan_model->find_by("KODE_BKN",$res[0]->ID_JABATAN_BKN);
        $rec_unitkerja = $this->unitkerja_model->find_by("ID",trim($res[0]->ID_UNIT_KERJA_BKN));
        // print_r($rec_unitkerja);
        if ($hasil) {
            $response ['success']= true;
            $response ['nip']= trim($nip);
            $response ['msg']= "Data ditemukan";
            $output = $this->load->view('mutasi/personal',array('personal'=>json_decode($hasil),"nip"=>trim($nip),"rec_jabatan"=>$rec_jabatan,"rec_unitkerja"=>$rec_unitkerja),true); 
            $response ['konten']= $output;
        }
        echo json_encode($response);    
        exit();
    }
    private function getLogPersonal($nip){
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://mutasi.sdm.kemdikbud.go.id/layanan/json/ws_data_pegawai_cek.php?nip=".$nip."&kode_akses=ropeg.123.abc",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_SSL_VERIFYHOST => false,
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_HTTPHEADER => array(
            'nip: '.$nip,
            'Authorization: Basic YWRtaW46NGRtMW4yMDE4IQ=='
          ),
        ));
        $hasil = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return $err;
        }
        return $hasil;
    }

    
    public function sinkron_personal(){
        $this->auth->restrict($this->permissionSinkronisasi);
        $this->load->model('pegawai/riwayat_jabatan_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/jenis_jabatan_model');
        $this->load->model('ref_jabatan/jabatan_model');
        $this->load->model('pegawai/unitkerja_model');
        $nip = $this->input->post("kode");
        $records = $this->getLogPersonal($nip);
        $records = json_decode($records);
        $nomor = 0;
        $nomor_tidak_ada = 0;
        $nomor_baru = 0;
        $nomor_perubahan = 0;
        $hapus_data = 0;
        $msg = "";
            foreach ($records as $record) :
                $data = array();
                // pegawai
                $pegawai_data = $this->pegawai_model->find_by("NIP_BARU",$record->NIP);
                if($pegawai_data && $record->ID_UNIT_KERJA_BKN != "-"){
                    $data["PNS_NIP"]    = $record->NIP;
                    $data["PNS_ID"]     = $pegawai_data->PNS_ID;
                    $data["PNS_NAMA"]   = $pegawai_data->NAMA;
                    // jika ada perubahan jabatan aktif, maka ubah di profile dan riwayat jabatan pegawai tersebut
                    // nama jabatan
                    $rec_jabatan = $this->jabatan_model->find_by("KODE_JABATAN",$record->ID_JABATAN_INTERNAL);
                    if(!$rec_jabatan)
                        $rec_jabatan = $this->jabatan_model->find_by("KODE_BKN",$record->ID_JABATAN_BKN);
                    $rec_unitkerja = $this->unitkerja_model->find_by("ID",trim($record->ID_UNIT_KERJA_BKN));
                    if($rec_jabatan && $rec_unitkerja){
                        $nama_jabatan = isset($rec_jabatan->NAMA_JABATAN) ? $rec_jabatan->NAMA_JABATAN : "";
                        $jenis_jabatan = isset($rec_jabatan->JENIS_JABATAN) ? $rec_jabatan->JENIS_JABATAN : "";
                        $data["ID_JABATAN"] = isset($rec_jabatan->KODE_JABATAN) ? $rec_jabatan->KODE_JABATAN : "";
                        $data["ID_JABATAN_BKN"] = isset($rec_jabatan->KODE_BKN) ? $rec_jabatan->KODE_BKN : "";
                        $data["NAMA_JABATAN"] = $nama_jabatan;
                        $data["IS_ACTIVE"] = "1";    
                        // unitkerja
                        $nama_unor = isset($rec_unitkerja->NAMA_UNOR) ? $rec_unitkerja->NAMA_UNOR : "";
                        $UNOR_INDUK = isset($rec_unitkerja->UNOR_INDUK) ? $rec_unitkerja->UNOR_INDUK : "";
                        $data["ID_UNOR_BKN"]    = trim($record->ID_UNIT_KERJA_BKN);
                        $data["UNOR"]           = $nama_unor;
                        $data["ID_UNOR"]        = trim($record->ID_UNIT_KERJA_BKN);

                        //jenis jabatan
                        $rec_jenis = $this->jenis_jabatan_model->find($jenis_jabatan);
                        $data["ID_JENIS_JABATAN"]   = $rec_jenis->ID ? $rec_jenis->ID : "";
                        $data["JENIS_JABATAN"]      = $rec_jenis->NAMA ? $rec_jenis->NAMA : "";
                        $data["ID_SATUAN_KERJA"]    = $UNOR_INDUK;
                        $data["NOMOR_SK"]      = $record->NOMOR_SK;
                        $data["TMT_JABATAN"]      = $record->TMT_SK;
                        $data["TANGGAL_SK"]      = $record->TGL_SK;
                        $data["ID_TABEL_MUTASI"]      = $record->ID_RIWAYAT;
                        $cek_data = $this->exist_riwayat_jabatan($record->NIP,$record->NOMOR_SK);
                        if($cek_data){
                            $this->riwayat_jabatan_model->update($cek_data->ID,$data);
                            $nomor_perubahan++;
                        }else{
                            $this->riwayat_jabatan_model->insert($data);
                            $nomor_baru++;
                        }
                        // UPDATE PROFILE PEGAWAI
                        $dataupdate = array();
                        if($rec_jabatan->KODE_JABATAN != ""){
                            $dataupdate['JABATAN_INSTANSI_REAL_ID']     = $rec_jabatan->KODE_JABATAN;
                            $dataupdate['JABATAN_INSTANSI_ID']          = $rec_jabatan->KODE_JABATAN;
                            $dataupdate['TMT_JABATAN']    = $record->TMT_SK ? $record->TMT_SK : null;
                        }
                        if($rec_unitkerja){
                            $dataupdate['UNOR_ID']                      = trim($record->ID_UNIT_KERJA_BKN);
                            $dataupdate['UNOR_INDUK_ID']                = $UNOR_INDUK;
                        }    
                        $this->pegawai_model->skip_validation(true);
                        $this->pegawai_model->update_where("NIP_BARU",$record->NIP, $dataupdate);
                        $nomor++;
                    }
                    $msg .= "";
                }else{
                    $nomor_tidak_ada++;
                }
            endforeach;
        $msg = $nomor. " Pegawai sukses update";
        log_activity($this->auth->user_id(), $msg . ' : ' . $this->input->ip_address().json_encode($data), 'sinkronisasi');

        $response ['success']= true;
        $response ['msg']= $msg;
        echo json_encode($response);    
        exit();
    }
    public function getjumlah(){
        $this->auth->restrict($this->permissionView);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
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
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            if($filters['unit_id_key']){
                $this->db->group_start();
                $this->db->where("id_unor_bkn",$filters['unit_id_key']);    
                $this->db->group_end();
            }
            if($filters['jenis']){
                $this->db->where("JENIS_SATKER",$filters['jenis']);    
            }
        }
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $asatkers = null;
        $total= $this->synch_jumlah_model->count_all();
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->synch_jumlah_model->order_by("satker",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->synch_jumlah_model->limit($length,$start);
        $records=$this->synch_jumlah_model->find_all();
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->satker."<br>".$record->nama_eselon_1."<br><i>".$record->JENIS_SATKER."</i>";
                $row []  = "<a href='".base_url()."pegawai/sinkronisasi/detilmutasi/?nama_satker=".urlencode($record->satker)."' kode='$record->satker' data-toggle='tooltip' title='lihat detil ".$record->satker."' class='show-modal'>".$record->jumlah_mutasi."</a>";    
                if((int)$record->jumlah > 0){
                    $row []  = "<a href='".base_url()."pegawai/sinkronisasi/detildikbudhr/".$record->id_unor_bkn."' kode='$record->id_unor_bkn' data-toggle='tooltip' title='lihat detil ".$record->satker."' class='show-modal'>".$record->jumlah."</a>";    
                }else{
                    $row []  = "";
                }
                
                $row []  = (int)$record->jumlah_mutasi-(int)$record->jumlah;
                $btn_actions = array();
                
                $btn_actions  [] = "<a href='#' kode='$record->id_unor_bkn' data-toggle='tooltip' title='Synch Satker ".$record->satker."' class='btn btn-sm btn-info btn_synch_one'><i class='fa fa-refresh'></i> </a>";

                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    public function detildikbudhr($satker_id)
    {   

        $this->auth->restrict($this->permissionSinkronisasi);
        Template::set('satker', $satker_id);
        Template::set('toolbar_title', "Nominatif Pegawai dikbudhr");
        Template::render();
    }
    public function detilmutasi()
    {   
        $satkernama = $this->input->get('nama_satker');
        $this->auth->restrict($this->permissionSinkronisasi);
        Template::set('satker', $satkernama);
        Template::set('toolbar_title', "Nominatif Pegawai Mutasi");
        Template::render();
    }
    public function getdetildikbudhr(){
        $this->auth->restrict($this->permissionView);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

        $selectedUnors = array();
        $satker  = $this->input->post("search[satker]");

        $kedudukan_hukum = "";
        $this->db->start_cache();
        $this->pegawai_model->where('"UNOR_INDUK_ID"',$satker); 
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $orders = $this->input->post('order');
        $total = $this->pegawai_model->count_all();

        foreach($orders as $order){
            if($order['column']==1){
                $this->pegawai_model->order_by("NIP_BARU",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        $this->pegawai_model->limit($length,$start);
        $records=$this->pegawai_model->find_all();
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->NIP_BARU."<br><i><b>".$record->NAMA."</b></i>";
                $row []  = $record->NAMA_UNOR_FULL;
                $row []  = $record->NAMA_PANGKAT."/".$record->NAMA_GOLONGAN;
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/profilen/".urlencode(base64_encode($record->ID))."' data-toggle='tooltip' title='Lihat profile' class='btn btn-sm btn-info'><i class='fa fa-user'></i> </a>";

                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    public function getdetilmutasi(){
        $this->auth->restrict($this->permissionView);
        if (!$this->input->is_ajax_request()) {
            // Template::set_message("Hanya request ajax", 'error');
        }
        $satker  = $this->input->post("search[satker]");
        $data = $this->get_jumlah_pegawai($eselon_1,"",$satker,"nominatif");
        $records = json_decode($data);
        $output=array();
        $output['data']=array();
        $total = count($records);
        $output['recordsTotal']= $output['recordsFiltered'] = $total;
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->NIP."<br><i><b>".$record->NAMA."</b></i>";
                $row []  = $record->SATUAN_KERJA;
                $row []  = $record->NAMA_JABATAN;
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/profilen/".urlencode(base64_encode($record->ID))."' data-toggle='tooltip' title='Lihat profile' class='btn btn-sm btn-info'><i class='fa fa-user'></i> </a>";

                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    public function download()
    {
        $records = $this->synch_jumlah_model->find_all();
        
        $this->load->library('Excel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel = PHPExcel_IOFactory::load(trim($this->settings_lib->item('site.pathuploaded')).'template.xls');

        $objPHPExcel->setActiveSheetIndex(0);
        $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,1,"No"); $col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,1,"Satker"); $col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,1,"Jumlah Mutasi"); $col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,1,"Jumlah Dikbudhr"); $col++;
        $row = 2;
        $no = 1;
        if (isset($records) && is_array($records) && count($records)) :
            foreach ($records as $record) :
                $col = 0;
                $type = PHPExcel_Cell_DataType::TYPE_STRING;
                $type_number = PHPExcel_Cell_DataType::TYPE_STRING;
                $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->setValueExplicit($no, $type); $col++;
                $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->setValueExplicit($record->satker, $type); $col++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$record->jumlah_mutasi); $col++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$record->jumlah); $col++;
                $row++;
                $no++;
            endforeach;
        endif;
          
        $filename = "jumlah_satker".mt_rand(1,100000).'.xls'; //just some random filename
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
        //$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
        $objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
        exit; //done.. exiting!
        
    }
    public function testapi(){

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'http://mutasi.sdm.kemdikbud.go.id/layanan/json/ws_rekap_pegawai_satker.php?kode_akses=ropeg.123.abc&&id_unor_bkn=&&satker=Sekretariat%20Jenderal&&eselon_i=&&laporan=nominatif',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
}