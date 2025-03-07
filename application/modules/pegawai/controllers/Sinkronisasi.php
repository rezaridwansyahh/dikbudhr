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
        $this->load->model('ref_jabatan/jabatan_model');
        Assets::add_css('flick/jquery-ui-1.8.13.custom.css');
        Assets::add_js('jquery-ui-1.8.13.min.js');
        $this->form_validation->set_error_delimiters("<span class='has-error'>", "</span>");
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
        $msg = "";
            foreach ($records as $record) :
                    $data = array();
                    // pegawai
                    $pegawai_data = $this->pegawai_model->find_by("NIP_BARU",$record->nip);
                    if($pegawai_data){
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
                            $dataupdate['JABATAN_INSTANSI_REAL_ID']     = $record->kode_jabatan_real;
                            $dataupdate['JABATAN_INSTANSI_ID']          = $record->kode_jabatan;
                            $dataupdate['UNOR_ID']                      = trim($record->id_bkn_unor);
                            $dataupdate['UNOR_INDUK_ID']                = $UNOR_INDUK;
                            $dataupdate['TMT_JABATAN']    = $record->tmt_sk ? $record->tmt_sk : null;

                            $this->pegawai_model->skip_validation(true);
                            $this->pegawai_model->update_where("NIP_BARU",$record->nip, $dataupdate);
                        }
                        $nomor++;
                    }else{
                        $nomor_tidak_ada++;
                    }
            endforeach;
        $msg = $nomor. " Pegawai sukses update, ".$nomor_tidak_ada. " gagal karena pegawai tidak ditemukan, ".$nomor_baru. " Data baru,".$nomor_perubahan." Perubahan data";
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
}