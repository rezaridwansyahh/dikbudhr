<?php 
require APPPATH . '/libraries/LIPIAPI_REST_Controller.php';
class Izin extends  LIPIAPI_REST_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
	    $this->load->model('pegawai/ropeg_model');
       
    }
     protected $methods = [
			'index_get' => ['level' => 10, 'limit' => 10],
            'xlist_get' => ['level' => 0, 'limit' => 10],
			'sub_get' => ['level' => 10, 'limit' => 10],
    ];


    public function list_jenis_izin_get(){
        $this->load->model('jenis_izin/jenis_izin_model');
        $output = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        $records = $this->jenis_izin_model->find_mobile();
        $this->db->flush_cache();
         $output = array(
            'success' => true,
            'total'=>count($records),
            'data'=>$records
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function process_post(){
        $NIP_PNS = $this->input->post("nip");
        $TANGGAL = $this->input->post("date");
        $kode_izin = $this->input->post("kode_izin");
        $keterangan = $this->input->post("keterangan");

        $verify = $this->izin_pegawai_model->validate_tanggal($NIP_PNS,$TANGGAL);
        $data_pegawai = null;
        $data = $this->izin_pegawai_model->prep_data($this->input->post());

        $message = "Gagal mengajukan izin";
        if(!$verify){
            $data_pegawai = $this->pegawai_model->find_by("NIP_BARU",$NIP_PNS);
            $NAMA_PEGAWAI = isset($data_pegawai->NAMA) ? $data_pegawai->NAMA : "";
            $dataatasan =  $this->get_data_pejabat($NIP_PNS);

            /**
             * VERIFY UNTUK CONFIG ATASAN
             */

            $NIP_ATASAN     = isset($dataatasan[2]->NIP_ATASAN) ? $dataatasan[2]->NIP_ATASAN : "";
            if($NIP_ATASAN!=""){
                $data['NAMA'] = $NAMA_PEGAWAI;
                $data['JUMLAH'] = 1;
                $data['TGL_DIBUAT'] = date("Y-m-d");
                $aTAHUN_PENGAJUAN = explode("-",$TANGGAL);
                $TAHUN_PENGAJUAN = isset($aTAHUN_PENGAJUAN[0]) ? $aTAHUN_PENGAJUAN[0] : date("Y");
                $data['TAHUN'] = $TAHUN_PENGAJUAN;
                $data['STATUS_ATASAN'] = 1; // menunggu persetujuan
                $data['STATUS_PYBMC'] = 1; // menunggu persetujuan
                $data['NIP_ATASAN'] = $NIP_ATASAN;
                $data["KODE_IZIN"] = $kode_izin;
                $data["KETERANGAN"] = $keterangan;
                $data["ALASAN_CUTI"] = $keterangan;
                $data["UNIT_KERJA"] = $data_pegawai->UNOR_INDUK_ID;
                $data["source"] = 99;
                $data['DARI_TANGGAL'] =$TANGGAL;
                $data['NIP_PNS'] = $NIP_PNS;
                $data['created_at']= date("Y-m-d");
                $data['updated_at']= date("Y-m-d");
                $data['JAM']= date("H:i:s");


                if($insert_id = $this->izin_pegawai_model->insert($data)){
                    $this->save_izin_verifikasi($NIP_PNS,$insert_id,$kode_izin);   
                    $message = "Success Mengajukan Izin dengan id $insert_id";
                }else{
                    $insert_id = "false";
                }
            }else{
                $message = "Belum ada atasan";
            }
        }else{
            $message = "Izin sudah pernah diajukan di tanggal tersebut";
        }

        $output = array(
            "result"=>$verify,
            "nip"=>$this->input->post("nip"),
            "tanggal"=>$this->input->post("date"),
            "izin_id"=>$insert_id,
            "message"=>$message
        );

        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

    }

    private function save_izin_verifikasi($nip_pegawai = "",$id_pengajuan = "",$id_jenis_izin = ""){
        $line_approval = $this->getpersetujuan($id_jenis_izin);
        $adata_pejabat = $this->get_data_pejabat($nip_pegawai);
        $urutan = 1;
        foreach($line_approval as $values)
         {
            if(isset($adata_pejabat[$values]->NIP_ATASAN)){
                $data = array();
                $data['NIP_ATASAN'] = $adata_pejabat[$values]->NIP_ATASAN;
                $data['ID_PENGAJUAN'] = $id_pengajuan;
                if($urutan == 1){
                    $data['STATUS_VERIFIKASI'] = 1;
                }
                $insert_id = $this->izin_verifikasi_model->insert($data);

                $urutan++;
            }
         }
    }

    private function getpersetujuan($id){
        $jenis_izin = $this->jenis_izin_model->find($id);
        $level      = isset($jenis_izin->PERSETUJUAN) ? json_decode($jenis_izin->PERSETUJUAN) : "";
        return $level;
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

    private function getatasan($ses_nip = ""){
        $dataatasan = $this->line_approval_model->get_atasan_pegawai($ses_nip);
        return $dataatasan;
    }
    
}
