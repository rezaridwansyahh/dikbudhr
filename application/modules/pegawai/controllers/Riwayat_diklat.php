<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Riwayat_diklat extends Admin_Controller
{
    
    protected $permissionCreate = 'Riwayatkursus.Kepegawaian.Create';
    protected $permissionDelete = 'Riwayatkursus.Kepegawaian.Delete';
    protected $permissionEdit   = 'Riwayatkursus.Kepegawaian.Edit';
    protected $permissionView   = 'Riwayatkursus.Kepegawaian.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/riwayat_diklat_model');
        $this->load->model('pegawai/vw_riwayat_diklat_model');
        $this->load->library('Api_bkn');
		
    }

    public function get_file() {
        $id = $this->input->post('id');
        $file = $this->riwayat_diklat_model->find($id);
        echo json_encode(['file_base64' => $file->file_base64]);
        die();
    }


    public function ajax_list(){
        $NIP_BARU = $this->input->post('NIP_BARU');
        $listDiklat = $this->vw_riwayat_diklat_model->find_all($NIP_BARU);
        echo json_encode(array("data"=>$listDiklat));
		die();
    }

    public function save(){
        $response ['success']= true;
        $response ['msg']= "berhasil";
        $APIBKN = new Api_bkn;
        $riwayatDiklat = array();
        $riwayatDiklat = $this->riwayat_diklat_model->prep_data($this->input->post());

        if (isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['name']) 
        {
            $errors=array();
            $file_name =$_FILES['file_dokumen']['name'];
            $file_ext   = explode('.',$file_name);
            $jmltitik   = count($file_ext);
            $file_size  = $_FILES['file_dokumen']['size'];
            $file_tmp   = $_FILES['file_dokumen']['tmp_name'];
            $type       = $_FILES['file_dokumen']['type'];
            $data_base64 = file_get_contents($file_tmp);
            $base64 = 'data:' . $type . ';base64,' . base64_encode($data_base64);
            $riwayatDiklat['file_base64'] = $base64;
        }
        
        $id_data = $this->input->post("id");
        if(isset($id_data) && !empty($id_data)){
            $updateStatus = $this->riwayat_diklat_model->update($id_data,$riwayatDiklat);

            if($updateStatus){
                $response ['success']= true;
                $response ['msg']= "berhasil";
            }
            if($riwayatDiklat['siasn_id']!=null){
                try {
                    //code...
                    if($riwayatDiklat['jenis_diklat_id']=='1'){
                        $mapped = $this->mapDiklatToStruktural($riwayatDiklat);
                        $mapped['id'] = $riwayatDiklat['siasn_id'];
                        $response['return'] = $APIBKN->uploadDiklatStruktural($mapped);
                        
                    }else{
                        $mapped = $this->mapDiklatToKursus($riwayatDiklat);
                        $mapped['id'] = $riwayatDiklat['siasn_id'];
                        $response['return'] = $APIBKN->uploadKursus($mapped);
                       
                    }
                } catch (\Throwable $th) {
                    //throw $th;
                    $response ['success']= false;
                    $response ['msg']= "gagal saat mengupdate ke SIASN";
                }
                
            }
        }else {
            $this->riwayat_diklat_model->insert($riwayatDiklat);
            $response ['success']= true;
            $response ['msg']= "berhasil";
        }
        
        
       
        echo json_encode($response);    
    }

    public function delete(){
        $record_id = $this->input->post('id');
        $APIBKN = new Api_bkn;
        $riwayatDiklat = $this->riwayat_diklat_model->prep_data($this->input->post());

        $response ['success']= false;
        $response ['msg']= "gagal";

        if ($this->riwayat_diklat_model->delete($record_id)) {
            log_activity($this->auth->user_id(), 'delete data Riwayat Kursus : ' . $record_id . ' : ' . $this->input->ip_address(), 'pegawai');
            $response ['success']= true;
            $response ['msg']= "berhasil menghapus riwayat diklat";
        }

        if($riwayatDiklat['siasn_id']!=null){
            if($riwayatDiklat['jenis_diklat']=='Diklat Struktural'){
                $response['resp'] = $APIBKN->deleteDiklatStruktural($riwayatDiklat['siasn_id']);
            }else{
                $response['resp'] = $APIBKN->deleteKursus($riwayatDiklat['siasn_id']);
            }
        }

        echo json_encode($response);    
    }

    public function send_siasn(){
        $APIBKN = new Api_bkn;
        $id = (int)$this->input->post('id');

        $response ['success']= false;
        $response ['msg']= "gagal";

        $riwayatDiklat = $this->vw_riwayat_diklat_model->prep_data($this->input->post());
        
        $idSiasn = null;
        if($riwayatDiklat['jenis_diklat_id']=='1'){
            $mapped = $this->mapDiklatToStruktural($riwayatDiklat);
            $response = $APIBKN->uploadDiklatStruktural($mapped);
            $idSiasn =  $response['mapData']['rwDiklatId'];
        }else{
            $mapped = $this->mapDiklatToKursus($riwayatDiklat);
            $response = $APIBKN->uploadKursus($mapped);
            $idSiasn =  $response['mapData']['rwKursusId'];
        }
        
        $riwayatDiklatResp = $this->riwayat_diklat_model->prep_data($this->input->post());
        $riwayatDiklatResp['siasn_id'] = $idSiasn;
        if($idSiasn!=null){
            $riwayatDiklatResp['sudah_kirim_siasn'] ='sudah';
        }
        
        $riwayatDiklatResp['id'] = $id;
        $status =  $this->riwayat_diklat_model->update($id,$riwayatDiklatResp);
        $riwayatDiklatResp['status'] = $status;

        echo json_encode($response);

    }
    

    private function mapDiklatToKursus($diklat){
        $kursus = array(
            "jumlahJam"=>(int)$diklat['durasi_jam'],
            "namaKursus"=>$diklat['nama_diklat'],
            "nomorSertipikat"=>$diklat['nomor_sertifikat'],
            "instansiId"=>"",
            "institusiPenyelenggara"=>$diklat['institusi_penyelenggara'],
            "tanggalKursus"=> date_format(date_create($diklat['tanggal_kursus']),'d-m-Y'),
            "tanggalSelesaiKursus" => date_format(date_create($diklat['tanggal_selesai']),'d-m-Y'),
            "tahunKursus"=> (int)$diklat['tahun_diklat'],
            "pnsOrangId"=> $diklat['pns_orang_id'],
            "jenisDiklatId"=> $diklat['jenis_diklat_id'] == 2 || $diklat['jenis_diklat_id'] == 3 ? $diklat['jenis_diklat_id'] : '3',
            "jenisKursusSertipikat" => 'Sertipikat',
            "lokasiId"=>"Indonesia",
            "jenisKursus"=>'T',
            "diklatFunsgionalId"=>1,
            "path"=>array(),
            "id"=>null,
        );

        return $kursus;
    }

    private function mapDiklatToStruktural($diklat){
        $diklatStruktural = array(
            "bobot"=>99,
            "institusiPenyelenggara"=>$diklat['institusi_penyelenggara'],
            "jenisKompetensi"=>$diklat['rumpun_diklat'],
            "jumlahJam"=>(int)$diklat['durasi_jam'],
            "latihanStrukturalId"=>$diklat['diklat_struktural_id'],
            "nomor"=>$diklat['nomor_sertifikat'],
            "pnsOrangId"=>$diklat['pns_orang_id'],
            "tahun"=>(int)$diklat['tahun_diklat'],
            "tanggal"=>date_format(date_create($diklat['tanggal_kursus']),'d-m-Y'),
            "tanggalSelesai"=>date_format(date_create($diklat['tanggal_selesai']),'d-m-Y'),
            "id"=>null,
            "path"=>array()
        );

        return $diklatStruktural;

    }


    
}
