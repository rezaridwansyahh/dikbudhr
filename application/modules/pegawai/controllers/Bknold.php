<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Bkn extends Admin_Controller
{
    protected $permissionCreate = 'Pegawai.Kepegawaian.Create';
    protected $permissionViewPpnpn = 'Pegawai.Kepegawaian.CreatePpnpn';
    
    protected $permissionDelete = 'Pegawai.Kepegawaian.Delete';
    protected $permissionEdit   = 'Pegawai.Kepegawaian.Edit';
    protected $permissionView   = 'Pegawai.Kepegawaian.View';
    protected $permissionFiltersatker   = 'Pegawai.Kepegawaian.Filtersatker';
    protected $permissionAddpendidikan   = 'Pegawai.Kepegawaian.Addpendidikan';
    protected $permissionUpdateMandiri   = 'Pegawai.Kepegawaian.Updatemandiri';
    protected $permissionViewProfileOther   = 'Pegawai.Kepegawaian.LihatProfileLain';
    protected $permissionEselon1   = 'Pegawai.Kepegawaian.permissionEselon1';
	
    protected $permissionUbahfoto   = 'Pegawai.Kepegawaian.Ubahfoto';

    protected $VerifikasiUpdate   = 'Pegawai.Kepegawaian.VerifikasiUpdate';
    protected $VerifikasiUpdate3   = 'Pegawai.Kepegawaian.VerifikasiUpdate3';
    protected $UnitkerjaTerbatas   = 'Unitkerja.Kepegawaian.Terbatas';
    protected $permissionCreateUser   = 'Pegawai.CreateUser.View';
    protected $permissionViewDataBkn   = 'Pegawai.ViewDataBkn.View';
    
	public $UNOR_ID = null;
	public $UkerTerbatas = false;
	
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
		$this->load->model('pegawai/riwayat_pendidikan_model');
		$this->load->model('pegawai/riwayat_kepangkatan_model');
		$this->load->model('pegawai/golongan_model');
		$this->load->model('pegawai/riwayat_jabatan_model');
		$this->load->model('pegawai/riwayat_pindah_unit_kerja_model');
		$this->load->model('pegawai/diklat_struktural_model');
        $this->load->model('pegawai/lokasi_model');
        $this->lang->load('pegawai');
        $this->load->model('pegawai/pendidikan_model');
        $this->load->model('pegawai/unitkerja_model');
        $this->load->model('ref_jabatan/jabatan_model');
        Assets::add_css('flick/jquery-ui-1.8.13.custom.css');
        Assets::add_js('jquery-ui-1.8.13.min.js');
        $this->form_validation->set_error_delimiters("<span class='has-error'>", "</span>");
        Template::set_block('sub_nav', 'kepegawaian/_sub_nav');
        Assets::add_module_js('pegawai', 'pegawai.js');
    }

    /**
     * Display a list of pegawai data.
     *
     * @return void
     */
    public function index()
    {	

    	$this->auth->restrict($this->permissionView);
        Template::set('toolbar_title', lang('pegawai_manage'));
		
        Template::render();
    }
    public function profile_bkn($pns_id='')
    {	
    	$this->load->model('pegawai/Pns_aktif_model');
    	$this->load->model('pegawai/Riwayat_pns_cpns_model');
    	$model = $this->uri->segment(6);
    	$this->load->library('convert');
    	Template::set('collapse', true);
        $pegawai = $this->pegawai_model->find_by("PNS_ID",$pns_id);
		$id = isset($pegawai->ID) ? $pegawai->ID : "";
		$nip_baru = isset($pegawai->NIP_BARU) ? $pegawai->NIP_BARU : "";
		
       	$recpns_aktif = $this->Pns_aktif_model->find($id);
 		Template::set('recpns_aktif', $recpns_aktif);

        $pegawai = $this->pegawai_model->find_detil($id);

        $foto_pegawai = trim($this->settings_lib->item('site.urlphoto'))."noimage.jpg";
		if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO)){
			$foto_pegawai =  trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO;
		}

		$data_utama_pegawai = $this->cache->get('data_utama_'.$nip_baru);
		$data_golongan = $this->cache->get('golongan_'.$nip_baru);
		$data_pasangan = $this->cache->get('data_pasangan_'.$nip_baru);
		$data_penghargaan = $this->cache->get('data_penghargaan_'.$nip_baru);
		$data_skp = $this->cache->get('data_rwt_skp_'.$nip_baru);
		$data_pendidikan = $this->cache->get('rwt_pendidikan_'.$nip_baru);
		$data_jabatan = $this->cache->get('rwt_jabatan_'.$nip_baru);
		$data_diklat = $this->cache->get('rwt_diklat_'.$nip_baru);
		$data_masa_kerja = $this->cache->get('rwt_masakerja_'.$nip_baru);
		$data_pwk = $this->cache->get('rwt_pwk_'.$nip_baru);
		$data_ppo_hist = $this->cache->get('ppo_sk_hist_'.$nip_baru);
		$data_kpo_sk_hist = $this->cache->get('data_kpo_sk_hist_'.$nip_baru);
		$data_hukdis = $this->cache->get('data_rwt_hukdis_'.$nip_baru);
		$data_pns_unor = $this->cache->get('data_rwt_pnsunor_'.$nip_baru);
		$data_anak = $this->cache->get('data_anak_'.$nip_baru);
		$data_ortu = $this->cache->get('data_ortu_'.$nip_baru);
		$data_dp3 = $this->cache->get('data_dp3_'.$nip_baru);

		Template::set('pegawai_bkn', $data_utama_pegawai);
		Template::set('data_golongan', $data_golongan);
		Template::set('data_penghargaan', $data_penghargaan);
		Template::set('data_skp', $data_skp);
		Template::set('data_pasangan', $data_pasangan);
		Template::set('data_pendidikan', $data_pendidikan);
		Template::set('data_jabatan', $data_jabatan);
		Template::set('data_diklat', $data_diklat);
		Template::set('data_masa_kerja', $data_masa_kerja);
		Template::set('data_pwk', $data_pwk);
		Template::set('data_ppo_hist', $data_ppo_hist);
		Template::set('data_kpo_sk_hist', $data_kpo_sk_hist);
		Template::set('data_hukdis', $data_hukdis);
		Template::set('data_pns_unor', $data_pns_unor);
		Template::set('data_anak', $data_anak);
		Template::set('data_dp3', $data_dp3);

		
        Template::set('selectedPendidikanID',$this->pendidikan_model->find(trim($pegawai->PENDIDIKAN_ID)));
        $recgolongan = $this->golongan_model->find(trim($pegawai->GOL_ID));
        Template::set('GOLONGAN_AKHIR', $recgolongan->NAMA);
        Template::set('NAMA_PANGKAT', $recgolongan->NAMA_PANGKAT);
        if($pegawai->JABATAN_INSTANSI_REAL_ID != ""){
            $recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($pegawai->JABATAN_INSTANSI_REAL_ID));
            Template::set('NAMA_JABATAN_REAL', $recjabatan->NAMA_JABATAN);
        }
        Template::set('selectedTempatLahirPegawai',$this->lokasi_model->find($pegawai->TEMPAT_LAHIR_ID));

        if(trim($pegawai->UNOR_ID) != ""){
            $unor = $this->unitkerja_model->find_by("ID",trim($pegawai->UNOR_ID));
            $pemimpin_pns_id = isset($unor->PEMIMPIN_PNS_ID) ? $unor->PEMIMPIN_PNS_ID : "";
            $nama_jabatan_struktural = isset($unor->NAMA_JABATAN) ? $unor->NAMA_JABATAN : "";
            if($pemimpin_pns_id == $pegawai->PNS_ID or $pegawai->JENIS_JABATAN_ID == "1")
            {
                Template::set('NAMA_JABATAN_REAL', $nama_jabatan_struktural);
                Template::set('JENIS_JABATAN', "Struktural");
            }
            Template::set('NAMA_UNOR', $unor->NAMA_UNOR);
        }
        $recgolongan = null;
        if($pegawai->GOL_AWAL_ID != "")
            $recgolongan = $this->golongan_model->find(trim($pegawai->GOL_AWAL_ID));
        Template::set('GOLONGAN_AWAL', isset($recgolongan->NAMA) ? $recgolongan->NAMA : "");


		Template::set('foto_pegawai', $foto_pegawai);
        Template::set('pegawai', $pegawai);
        Template::set('PNS_ID', $pegawai->PNS_ID);
        Template::set_view('bkn/profile_bkn');

        Template::render();
    }
	public function viewpegawaibkn(){
        $nip_baru 	= $this->input->post('kode');
		// $pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU",$nip_baru);
        $data_utama_pegawai = $this->cache->get('data_utama_'.$nip_baru);
        if($data_utama_pegawai != null)
        {
            $response ['success']= true;
            $response ['id']= $data_utama_pegawai->id;
            $response ['msg']= "Selesai ambil data BKN";
            echo json_encode($response);  
            exit();
        }
		$this->load->library('Api_bkn');
		$api_bkn = new Api_bkn;
		$data_utama_pegawai = $this->getdata_utama_bkn($nip_baru);
        $data_golongan = $this->getdata_riwayat_golongan_bkn($nip_baru);
        $data_dp3 = $this->getdata_riwayat_dp3($nip_baru);
        $data_pasangan = $this->getdata_pasangan($nip_baru);
        $data_ortu = $this->getdata_ortu($nip_baru);
        $data_anak = $this->getdata_anak($nip_baru);
        $data_kursus = $this->getdata_kursus($nip_baru);
        $data_penghargaan = $this->getdata_penghargaan($nip_baru);
        $data_pindah_instansi = $this->getdata_pindahinstansi($nip_baru);
        // $data_rwt_skp = $this->getdata_rwt_skp($nip_baru);
        
        // $data_rwt_cltn = $this->getdata_rwt_cltn($nip_baru);
        // $data_rwt_pemberhentian = $this->getdata_rwt_pemberhentian($nip_baru);
        // $data_rwt_ak = $this->getdata_rwt_ak($nip_baru);

        $data_rwt_pnsunor = $this->getdata_rwt_pnsunor($nip_baru);
        $data_rwt_hukdis = $this->getdata_rwt_hukdis($nip_baru);
        
        // $data_kpo_sk = $this->getdata_kpo_sk($nip_baru);
        // $data_sk_history = $this->getdata_sk_hist($nip_baru);
        // $data_ppo_sk = $this->getdata_ppo_sk($nip_baru);
        // $data_ppo_sk_hist = $this->getdata_ppo_sk_hist($nip_baru);
        $data_rwt_pendidikan = $this->getdata_rwt_pendidikan($nip_baru);
        $data_rwt_jabatan = $this->getdata_rwt_jabatan($nip_baru);
        
        $data_rwt_diklat = $this->getdata_rwt_diklat($nip_baru);
        // $data_rwt_masakerja = $this->getdata_rwt_masakerja($nip_baru);
        // $data_rwt_pwk = $this->getdata_rwt_pwk($nip_baru);
        // $data_usul_wafat = $this->getdata_usul_wafat($nip_baru);
        // $data_usul_wafat_hist = $this->getdata_usul_wafat_hist($nip_baru);
        // $data_update_pns = $this->getdata_update_pns($nip_baru);
        // $data_update_pns_hist = $this->getdata_update_pns_hist($nip_baru);
        $result = false;
        $id = "";
        if(isset($data_utama_pegawai->id) and $data_utama_pegawai->id != "") {
			$result = true;
			$id = $data_utama_pegawai->id;
		} 
        if($result){
 	       	$response ['success']= true;
 	       	$response ['id']= $id;
    		$response ['msg']= "Selesai ambil data BKN";
    	}else{
    		$response ['success']= false;
    		$response ['id']= $id;
    		$response ['msg']= "Ada kesalahan, data BKN tidak berhasil didapatkan";
    	}
        echo json_encode($response);  
	}
	public function viewpendidikan(){
        $kode 	= $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("PNS_ID",$kode);
		$this->load->library('Api_bkn');
		$api_bkn = new Api_bkn;
        $data_pendidikan = $this->getdata_rwt_pendidikan(trim($pegawai_lokal->NIP_BARU));
        
        $result = false;
        $id = "";
        if($data_pendidikan != null) {
			$result = true;
		} 
        if($result){
 	       	$response ['success']= true;
    		$response ['nip']= trim($pegawai_lokal->NIP_BARU);
    		$response ['msg']= "Data ditemukan";
    		$output = $this->load->view('bkn/pendidikan',array('data_pendidikan'=>$data_pendidikan,"nipBaru"=>trim($pegawai_lokal->NIP_BARU)),true);	
    		$response ['konten']= $output;
    	}else{
    		$response ['success']= false;
    		$response ['nip']= trim($pegawai_lokal->NIP_BARU);
    		$response ['msg']= "Data tidak ada";
    		$response ['konten']= "";
    	}
        echo json_encode($response);  
	}
	public function viewkepangkatan(){
        $kode 	= $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("PNS_ID",$kode);
		$this->load->library('Api_bkn');
		$api_bkn = new Api_bkn;
        $data_golongan = $this->getdata_riwayat_golongan_bkn(trim($pegawai_lokal->NIP_BARU));
        
        $result = false;
        $id = "";
        if($data_golongan != null) {
			$result = true;
		} 
        if($result){
 	       	$response ['success']= true;
    		$response ['nip']= trim($pegawai_lokal->NIP_BARU);
    		$response ['msg']= "Data ditemukan";
    		$output = $this->load->view('bkn/kepangkatan',array('data_golongan'=>$data_golongan,"nipBaru"=>trim($pegawai_lokal->NIP_BARU)),true);	
    		$response ['konten']= $output;
    	}else{
    		$response ['success']= false;
    		$response ['nip']= trim($pegawai_lokal->NIP_BARU);
    		$response ['msg']= "Data tidak ada";
    		$response ['konten']= "";
    	}
        echo json_encode($response);  
	}
	public function viewjabatan(){
        $kode 	= $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("PNS_ID",$kode);
		$this->load->library('Api_bkn');
		$api_bkn = new Api_bkn;
        $data_jabatan = $this->getdata_rwt_jabatan(trim($pegawai_lokal->NIP_BARU));
        
        $result = false;
        $id = "";
        if($data_jabatan != null) {
			$result = true;
		} 
        if($result){
 	       	$response ['success']= true;
    		$response ['nip']= trim($pegawai_lokal->NIP_BARU);
    		$response ['msg']= "Data ditemukan";
    		$output = $this->load->view('bkn/jabatan',array('data_jabatan'=>$data_jabatan,"nipBaru"=>trim($pegawai_lokal->NIP_BARU)),true);	
    		$response ['konten']= $output;
    	}else{
    		$response ['success']= false;
    		$response ['nip']= trim($pegawai_lokal->NIP_BARU);
    		$response ['msg']= "Data tidak ada";
    		$response ['konten']= "";
    	}
        echo json_encode($response);  
	}
	public function viewdiklat(){
        $kode 	= $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("PNS_ID",$kode);
		$this->load->library('Api_bkn');
		$api_bkn = new Api_bkn;
        $data_diklat = $this->getdata_rwt_diklat(trim($pegawai_lokal->NIP_BARU));
        
        $result = false;
        $id = "";
        if($data_diklat != null) {
			$result = true;
		} 
        if($result){
 	       	$response ['success']= true;
    		$response ['nip']= trim($pegawai_lokal->NIP_BARU);
    		$response ['msg']= "Data ditemukan";
    		$output = $this->load->view('bkn/diklatstruktural',array('data_diklat'=>$data_diklat,"nipBaru"=>trim($pegawai_lokal->NIP_BARU)),true);	
    		$response ['konten']= $output;
    	}else{
    		$response ['success']= false;
    		$response ['nip']= trim($pegawai_lokal->NIP_BARU);
    		$response ['msg']= "Data tidak ada";
    		$response ['konten']= "";
    	}
        echo json_encode($response);  
	}
	private function getdata_utama_bkn($nip_baru){
		$this->load->library('Api_bkn');
		$api_bkn = new Api_bkn;
		$data_utama_pegawai = $this->cache->get('data_utama_'.$nip_baru);
        if($data_utama_pegawai==null){
        	// get data utama
	        $jsonsatkers   = $api_bkn->data_utama($nip_baru);
	        $decodejson    = json_decode($jsonsatkers);
	        $decodejson    = json_decode($decodejson);
	        $data_utama_pegawai 	= json_decode($decodejson->data);
            $this->cache->write($data_utama_pegawai,'data_utama_'.$nip_baru,43200);
        }
        return $data_utama_pegawai;
	}
	private function getdata_riwayat_golongan_bkn($nip_baru){
		$this->load->library('Api_bkn');
		$api_bkn = new Api_bkn;
		$data_golongan = $this->cache->get('golongan_'.$nip_baru);
        if($data_golongan==null){
        	// data riwayat golongan
			$jsonrwt_golongan = $api_bkn->data_rwt_golongan_bkn($nip_baru);
	        $jsonrwt_golongan = json_decode($jsonrwt_golongan);
	        $jsonrwt_golongan = json_decode($jsonrwt_golongan);
	        $data_golongan = $jsonrwt_golongan->data;
            $this->cache->write($data_golongan,'golongan_'.$nip_baru,43200);
        }
        return $data_golongan;
	}
	private function getdata_riwayat_dp3($nip_baru){
		$api_bkn = new Api_bkn;
		$data_dp3 = $this->cache->get('data_dp3_'.$nip_baru);
        if($data_dp3==null){
        	// data riwayat golongan
			$jsonrwt_golongan = $api_bkn->data_dp3($nip_baru);
	        $jsonrwt_golongan = json_decode($jsonrwt_golongan);
	        $jsonrwt_golongan = json_decode($jsonrwt_golongan);
	        $data_dp3 = $jsonrwt_golongan->data;
            $this->cache->write($data_dp3,'data_dp3_'.$nip_baru,43200);
        }
        return $data_dp3;
	}
	private function getdata_pasangan($nip_baru){
		$api_bkn = new Api_bkn;
		$data_pasangan = $this->cache->get('data_pasangan_'.$nip_baru);
        if($data_pasangan==null){
        	// data riwayat golongan
			$json_return = $api_bkn->get_data_pasangan($nip_baru);
	        $json_return = json_decode($json_return);
	        $json_return = json_decode($json_return);
	        $data_pasangan = $json_return->data;
            $this->cache->write($data_pasangan,'data_pasangan_'.$nip_baru,43200);
        }
        return $data_pasangan;
	}
	private function getdata_ortu($nip_baru){
		$api_bkn = new Api_bkn;
		$data_ortu = $this->cache->get('data_ortu_'.$nip_baru);
        if($data_ortu==null){
        	// data riwayat golongan
			$json_return = $api_bkn->get_data_ortu($nip_baru);
	        $json_return = json_decode($json_return);
	        $json_return = json_decode($json_return);
	        $data_ortu = $json_return->data;
            $this->cache->write($data_ortu,'data_ortu_'.$nip_baru,43200);
        }
        return $data_ortu;
	}
	private function getdata_anak($nip_baru){
		$api_bkn = new Api_bkn;
		$this->cache->delete('data_anak_'.$nip_baru);  
		$data_return = $this->cache->get('data_anak_'.$nip_baru);
        // if($data_return==null){
        	// data riwayat golongan
			$json_return = $api_bkn->get_data_anak($nip_baru);
	        $json_return = json_decode($json_return);
	        $json_return = json_decode($json_return);
	        $data_return = $json_return->data;
            $this->cache->write($data_return,'data_anak_'.$nip_baru,43200);
        // }
        print_r($data_return);
        return $data_return;
	}
	private function getdata_kursus($nip_baru){
		$api_bkn = new Api_bkn;
		$data_kursus = $this->cache->get('data_kursus_'.$nip_baru);
        if($data_kursus==null){
        	// data riwayat golongan
			$json_return = $api_bkn->get_data_kursus($nip_baru);
	        $json_return = json_decode($json_return);
	        $json_return = json_decode($json_return);
	        $data_kursus = $json_return->data;
            $this->cache->write($data_kursus,'data_kursus_'.$nip_baru,43200);
        }
        return $data_kursus;
	}
	private function getdata_penghargaan($nip_baru){
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('data_penghargaan_'.$nip_baru);
        if($data_return==null){
        	// data riwayat golongan
			$json_return = $api_bkn->get_data_penghargaan($nip_baru);
	        $json_return = json_decode($json_return);
	        $json_return = json_decode($json_return);
	        $data_return = $json_return->data;
            $this->cache->write($data_return,'data_penghargaan_'.$nip_baru,43200);
        }
        return $data_return;
	}
	private function getdata_pindahinstansi($nip_baru){
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('data_pindah_instansi_'.$nip_baru);
        if($data_return==null){
        	// data riwayat golongan
			$json_return = $api_bkn->get_data_pindah_instansi($nip_baru);
	        $json_return = json_decode($json_return);
	        $json_return = json_decode($json_return);
	        $data_return = $json_return->data;
            $this->cache->write($data_return,'data_pindah_instansi_'.$nip_baru,43200);
        }
        return $data_return;
	}
	private function getdata_rwt_skp($nip_baru){
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('data_rwt_skp_'.$nip_baru);
        if($data_return==null){
        	// data riwayat golongan
			$json_return = $api_bkn->get_data_rwt_skp($nip_baru);
	        $json_return = json_decode($json_return);
	        $json_return = json_decode($json_return);
	        $data_return = $json_return->data;
            $this->cache->write($data_return,'data_rwt_skp_'.$nip_baru,43200);
        }
        return $data_return;
	}
	private function getdata_rwt_cltn($nip_baru){
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('data_rwt_cltn_'.$nip_baru);
        if($data_return==null){
			$json_return = $api_bkn->get_data_rwt_cltn($nip_baru);
	        $json_return = json_decode($json_return);
	        $json_return = json_decode($json_return);
	        $data_return = $json_return->data;
            $this->cache->write($data_return,'data_rwt_cltn_'.$nip_baru,43200);
        }
        return $data_return;
	}
	private function getdata_rwt_pemberhentian($nip_baru){
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('data_rwt_pemberhentian_'.$nip_baru);
        if($data_return==null){
			$json_return = $api_bkn->get_data_rwt_pemberhentian($nip_baru);
	        $json_return = json_decode($json_return);
	        $json_return = json_decode($json_return);
	        $data_return = $json_return->data;
            $this->cache->write($data_return,'data_rwt_pemberhentian_'.$nip_baru,43200);
        }
        return $data_return;
	}
	private function getdata_rwt_ak($nip_baru){
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('data_rwt_ak_'.$nip_baru);
        if($data_return==null){
			$json_return = $api_bkn->get_data_rwt_ak($nip_baru);
	        $json_return = json_decode($json_return);
	        $json_return = json_decode($json_return);
	        $data_return = $json_return->data;
            $this->cache->write($data_return,'data_rwt_ak_'.$nip_baru,43200);
        }
        return $data_return;
	}
	private function getdata_rwt_pnsunor($nip_baru){
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('data_rwt_pnsunor_'.$nip_baru);
        if($data_return==null){
			$json_return = $api_bkn->get_data_rwt_pnsunor($nip_baru);
	        $json_return = json_decode($json_return);
	        $json_return = json_decode($json_return);
	        $data_return = $json_return->data;
            $this->cache->write($data_return,'data_rwt_pnsunor_'.$nip_baru,43200);
        }
        return $data_return;
	}
	private function getdata_rwt_hukdis($nip_baru){
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('data_rwt_hukdis_'.$nip_baru);
        if($data_return==null){
			$json_return = $api_bkn->get_data_rwt_hukdis($nip_baru);
	        $json_return = json_decode($json_return);
	        $json_return = json_decode($json_return);
	        $data_return = $json_return->data;
            $this->cache->write($data_return,'data_rwt_hukdis_'.$nip_baru,43200);
        }
        return $data_return;
	}
	private function getdata_kpo_sk($nip_baru){
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('data_kpo_sk_'.$nip_baru);
        if($data_return==null){
			$json_return = $api_bkn->get_data_kpo_sk($nip_baru);
	        $json_return = json_decode($json_return);
	        $json_return = json_decode($json_return);
	        $data_return = $json_return->data;
            $this->cache->write($data_return,'data_kpo_sk_'.$nip_baru,43200);
        }
        return $data_return;
	}
	private function getdata_sk_hist($nip_baru){
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('data_kpo_sk_hist_'.$nip_baru);
        if($data_return==null){
			$json_return = $api_bkn->get_data_kpo_sk_hist($nip_baru);
	        $json_return = json_decode($json_return);
	        $json_return = json_decode($json_return);
	        $data_return = $json_return->data;
            $this->cache->write($data_return,'data_kpo_sk_hist_'.$nip_baru,43200);
        }
        return $data_return;
	}
	private function getdata_ppo_sk($nip_baru){
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('data_ppo_sk_'.$nip_baru);
        if($data_return==null){
			$json_return = $api_bkn->get_data_ppo_sk($nip_baru);
	        $json_return = json_decode($json_return);
	        $json_return = json_decode($json_return);
	        $data_return = $json_return->data;
            $this->cache->write($data_return,'data_ppo_sk_'.$nip_baru,43200);
        }
        return $data_return;
	}
	private function getdata_ppo_sk_hist($nip_baru){
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('ppo_sk_hist_'.$nip_baru);
        if($data_return==null){
			$json_return = $api_bkn->get_data_ppo_sk_hist($nip_baru);
	        $json_return = json_decode($json_return);
	        $json_return = json_decode($json_return);
	        $data_return = $json_return->data;
            $this->cache->write($data_return,'ppo_sk_hist_'.$nip_baru,43200);
        }
        return $data_return;
	}
	private function getdata_rwt_pendidikan($nip_baru){
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('rwt_pendidikan_'.$nip_baru);
        if($data_return==null){
			$json_return = $api_bkn->get_data_rwt_pendidikan($nip_baru);
	        $json_return = json_decode($json_return);
	        $json_return = json_decode($json_return);
	        $data_return = $json_return->data;
            $this->cache->write($data_return,'rwt_pendidikan_'.$nip_baru,43200);
        }
        return $data_return;
	}
	private function getdata_rwt_jabatan($nip_baru){
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('rwt_jabatan_'.$nip_baru);
        if($data_return==null){
			$json_return = $api_bkn->get_data_rwt_jabatan($nip_baru);
	        $json_return = json_decode($json_return);
	        $json_return = json_decode($json_return);
	        $data_return = $json_return->data;
            $this->cache->write($data_return,'rwt_jabatan_'.$nip_baru,43200);
        }
        return $data_return;
	}
	private function getdata_rwt_diklat($nip_baru){
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('rwt_diklat_'.$nip_baru);
        if($data_return==null){
			$json_return = $api_bkn->get_data_rwt_diklat($nip_baru);
	        $json_return = json_decode($json_return);
	        $json_return = json_decode($json_return);
	        $data_return = $json_return->data;
            $this->cache->write($data_return,'rwt_diklat_'.$nip_baru,43200);
        }
        return $data_return;
	}
	private function getdata_rwt_masakerja($nip_baru){
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('rwt_masakerja_'.$nip_baru);
        if($data_return==null){
			$json_return = $api_bkn->get_data_rwt_masakerja($nip_baru);
	        $json_return = json_decode($json_return);
	        $json_return = json_decode($json_return);
	        $data_return = $json_return->data;
            $this->cache->write($data_return,'rwt_masakerja_'.$nip_baru,43200);
        }
        return $data_return;
	}
	private function getdata_rwt_pwk($nip_baru){
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('rwt_pwk_'.$nip_baru);
        if($data_return==null){
			$json_return = $api_bkn->get_data_rwt_pwk($nip_baru);
	        $json_return = json_decode($json_return);
	        $json_return = json_decode($json_return);
	        $data_return = $json_return->data;
            $this->cache->write($data_return,'rwt_pwk_'.$nip_baru,43200);
        }
        return $data_return;
	}
	private function getdata_usul_wafat($nip_baru){
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('usul_wafat_'.$nip_baru);
        if($data_return==null){
			$json_return = $api_bkn->get_data_usul_wafat($nip_baru);
	        $json_return = json_decode($json_return);
	        $json_return = json_decode($json_return);
	        $data_return = $json_return->data;
            $this->cache->write($data_return,'usul_wafat_'.$nip_baru,43200);
        }
        return $data_return;
	}
	private function getdata_usul_wafat_hist($nip_baru){
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('usul_wafat_hist_'.$nip_baru);
        if($data_return==null){
			$json_return = $api_bkn->get_data_usul_wafat_hist($nip_baru);
	        $json_return = json_decode($json_return);
	        $json_return = json_decode($json_return);
	        $data_return = $json_return->data;
            $this->cache->write($data_return,'usul_wafat_hist_'.$nip_baru,43200);
        }
        return $data_return;
	}
	private function getdata_update_pns($nip_baru){
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('update_pns_'.$nip_baru);
        if($data_return==null){
			$json_return = $api_bkn->get_data_update_pns($nip_baru);
	        $json_return = json_decode($json_return);
	        $json_return = json_decode($json_return);
	        $data_return = $json_return->data;
            $this->cache->write($data_return,'update_pns_'.$nip_baru,43200);
        }
        return $data_return;
	}
	private function getdata_update_pns_hist($nip_baru){
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('update_pns_hist_'.$nip_baru);
        if($data_return==null){
			$json_return = $api_bkn->get_data_update_pns_hist($nip_baru);
	        $json_return = json_decode($json_return);
	        $json_return = json_decode($json_return);
	        $data_return = $json_return->data;
            $this->cache->write($data_return,'update_pns_hist_'.$nip_baru,43200);
        }
        return $data_return;
	}
	private function cek_prestasi_kerja($nip = "",$tahun = ""){
		$this->load->model('pegawai/riwayat_prestasi_kerja_model');
		$this->riwayat_prestasi_kerja_model->where("TAHUN",$tahun);
		$data_prestasi_kerja = $this->riwayat_prestasi_kerja_model->find_by("PNS_NIP",$nip);
		return $data_prestasi_kerja;
	}
	public function sinkron_cache(){
		$nip_baru 	= $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU",$nip_baru);		
		$result = $this->sinkron_panggol($pegawai_lokal);
		$result = $this->sinkron_skp($pegawai_lokal);
		$result = $this->sinkron_pendidikan($pegawai_lokal);
		$result = $this->sinkron_jabatan($pegawai_lokal);
		$result = $this->sinkron_pns_unor($pegawai_lokal);
		$result = $this->sinkron_diklat($pegawai_lokal);
		if($result){
 	       	$response ['success']= true;
    		$response ['msg']= "Sukses sinkron data";
    	}else{
    		$response ['success']= false;
    		$response ['msg']= "Ada kesalahan";
    	}
        echo json_encode($response); 
        
	}
	public function sinkron_cache_pendidikan(){
		$nip_baru 	= $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU",$nip_baru);		
		$result = $this->sinkron_pendidikan($pegawai_lokal);
		if($result){
 	       	$response ['success']= true;
    		$response ['msg']= "Sukses sinkron data pendidikan";
    	}else{
    		$response ['success']= false;
    		$response ['msg']= "Ada kesalahan";
    	}
        echo json_encode($response); 
        
	}
	public function sinkron_cache_golongan(){
		$nip_baru 	= $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU",$nip_baru);		
		$result = $this->sinkron_panggol($pegawai_lokal);
		if($result){
 	       	$response ['success']= true;
    		$response ['msg']= "Sukses sinkron data riwayat pangkat golongan";
    	}else{
    		$response ['success']= false;
    		$response ['msg']= "Ada kesalahan";
    	}
        echo json_encode($response); 
        
	}
	public function sinkron_cache_jabatan(){
		$nip_baru 	= $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU",$nip_baru);		
		$result = $this->sinkron_jabatan($pegawai_lokal);
		if($result){
 	       	$response ['success']= true;
    		$response ['msg']= "Sukses sinkron data riwayat jabatan";
    	}else{
    		$response ['success']= false;
    		$response ['msg']= "Ada kesalahan";
    	}
        echo json_encode($response); 
        
	}
	public function sinkron_cache_diklat(){
		$nip_baru 	= $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU",$nip_baru);		
		$result = $this->sinkron_diklat($pegawai_lokal);
		if($result){
 	       	$response ['success']= true;
    		$response ['msg']= "Sukses sinkron data riwayat diklat";
    	}else{
    		$response ['success']= false;
    		$response ['msg']= "Ada kesalahan";
    	}
        echo json_encode($response); 
        
	}
	private function sinkron_panggol($pegawai_lokal = null){
		$nip_baru = trim($pegawai_lokal->NIP_BARU);
		$data_golongan = $this->cache->get('golongan_'.$nip_baru);
		$result = true;
		// data riwayat pangkat golongan
		if($data_golongan != null){
			foreach($data_golongan as $row)
			{
				$golongans = $this->golongan_model->find_by("ID",$row->golonganId);
				$idPns 		= $row->idPns;
				$golonganId = $row->golonganId;
				$NAMA_PANGKAT = isset($golongans->NAMA_PANGKAT) ? $golongans->NAMA_PANGKAT : "";
				$golongan 	= $row->golongan;
				$skNomor 	= TRIM($row->skNomor);
				$skTanggal 	= $row->skTanggal;
				$tmtGolongan 	= $row->tmtGolongan;
				$noPertekBkn 	= $row->noPertekBkn;
				$tglPertekBkn 	= $row->tglPertekBkn;
	        	$this->riwayat_kepangkatan_model->where("ID_GOLONGAN",$golonganId);  
	        	$count_golongan = $this->riwayat_kepangkatan_model->count_all($idPns);
	        	if($count_golongan <= 0){
	        		$data = array();
			        $data["ID_BKN"] = $row->id;
			        $data["PNS_NIP"] = $row->nipBaru;
			        $data["PNS_ID"] = $row->idPns;
			        $data["PNS_NAMA"] = $pegawai_lokal->NAMA;
			        $data["JENIS_KP"] = $row->jenisKPNama;
			        $data["ID_GOLONGAN"] = $row->golonganId;
			        $data["PANGKAT"] = $NAMA_PANGKAT;
			        $data["SK_NOMOR"] = $row->skNomor != "" ? $row->skNomor : "-";
			        $data["NOMOR_BKN"] = $row->noPertekBkn;
			        $data["MK_GOLONGAN_TAHUN"] = $row->masaKerjaGolonganTahun;
			        $data["MK_GOLONGAN_BULAN"] = $row->masaKerjaGolonganBulan;

			        $data["KODE_JENIS_KP"] = $row->jenisKPId;
			        $data["GOLONGAN"] = $row->golongan;
			        
			        $data["TMT_GOLONGAN"] 	= date("Y-m-d", strtotime($row->tmtGolongan));
			        $data["SK_TANGGAL"] 	= date("Y-m-d", strtotime($row->skTanggal));
			        if(empty($data["TMT_GOLONGAN"])){
			            unset($data["TMT_GOLONGAN"]);
			        }
			        if(empty($data["SK_TANGGAL"])){
			            unset($data["SK_TANGGAL"]);
			        }
				    $result = $this->riwayat_kepangkatan_model->insert($data);
				    log_activity_pegawai($NIP_BARU,$this->auth->user_id(),"sinkron data rwt_golongan dari BKN ".$NAMA_PANGKAT, 'pegawai');
	        	}

			}	
		}
		return $result;
	}
	private function sinkron_skp($pegawai_lokal = null){
		$nip_baru = trim($pegawai_lokal->NIP_BARU);
		$data_skp = $this->cache->get('data_rwt_skp_'.$nip_baru);
		$result = true;
		if($data_skp != null){
			foreach($data_skp as $row)
			{
				$record_skp = $this->cek_prestasi_kerja($nip_baru,$row->tahun);
				$idPns 		= $row->pns;
	        	if(!isset($record_skp->ID)){
	        		$data = array();
			        $data["PNS_ID"] = $idPns;
			        $data["PNS_NAMA"] = $pegawai_lokal->NAMA;
			        $data["PNS_NIP"] = $pegawai_lokal->NIP_BARU;
			        $data['TAHUN'] 		= $row->tahun;
			        $data['NILAI_SKP'] = $row->nilaiSkp;
			        $data['NILAI_PROSENTASE_SKP'] = null;
			        $data['NILAI_SKP_AKHIR'] = $row->nilaiSkp;
			        $data['NILAI_PROSENTASE_PERILAKU'] = null;
			        $data['NILAI_PERILAKU'] = $row->nilaiPerilakuKerja;
			        $data['PERILAKU_KOMITMEN'] = $row->komitmen;
			        $data['PERILAKU_INTEGRITAS'] = $row->integritas;
			        $data['PERILAKU_DISIPLIN'] = $row->disiplin;
			        $data['PERILAKU_KERJASAMA'] = $row->kerjasama;
			        $data['PERILAKU_ORIENTASI_PELAYANAN'] = $row->orientasiPelayanan;
			        $data['NILAI_PERILAKU_AKHIR'] = $row->nilaiPerilakuKerja;
			        $data['NILAI_PPK'] = $row->nilaiPrestasiKerja;
			        $result = $this->riwayat_prestasi_kerja_model->insert($data);	
				    log_activity_pegawai($NIP_BARU,$this->auth->user_id(),"sinkron data SKP dari BKN ".$row->tahun, 'pegawai');
	        	}

			}	
		}
		return $result;
	}
    private function sinkron_pendidikan($pegawai_lokal = null){
		$nip_baru = trim($pegawai_lokal->NIP_BARU);
		$data_pendidikan = $this->cache->get('rwt_pendidikan_'.$nip_baru);
		$result = true;
		if($data_pendidikan != null){
			foreach($data_pendidikan as $row)
			{
				$this->riwayat_pendidikan_model->where("PENDIDIKAN_ID",$row->pendidikanId);
				$data_riwayat = $this->riwayat_pendidikan_model->find_by("PNS_ID",trim($pegawai_lokal->PNS_ID));		
				$idPns 		= $row->idPns;
	        	if(!isset($data_riwayat->ID)){
	        		$data = array();
			        $data["PNS_ID"] 			= $idPns;
			        $data["NIP"] 				= $pegawai_lokal->NIP_BARU;
			        $data['TINGKAT_PENDIDIKAN_ID'] 	= $row->tkPendidikanId;
			        $data['PENDIDIKAN_ID'] 			= $row->pendidikanId;
			        $data['TANGGAL_LULUS'] 			= date('Y-m-d', strtotime($row->tglLulus));
			        $data['NOMOR_IJASAH'] 			= $row->nomorIjasah;
			        $data['NAMA_SEKOLAH'] 			= $row->namaSekolah;
			        $data['GELAR_DEPAN'] 			= $row->gelarDepan;
			        $data['GELAR_BELAKANG'] 		= $row->gelarBelakang;
			        $data['TAHUN_LULUS'] 			= $row->tahunLulus;
			        $data['DIAKUI_BKN'] 			= 1;
			        $data['PENDIDIKAN_PERTAMA'] 	= $row->isPendidikanPertama;
			        $result = $this->riwayat_pendidikan_model->insert($data);	
				    log_activity_pegawai($NIP_BARU,$this->auth->user_id(),"sinkron data riwayat pendidikan dari BKN ".$row->pendidikanId, 'pegawai');
	        	}

			}	
		}
		return $result;
	}
	private function sinkron_jabatan($pegawai_lokal = null){
		$nip_baru = trim($pegawai_lokal->NIP_BARU);
		$data_jabatan = $this->cache->get('rwt_jabatan_'.$nip_baru);
		$result = true;
		if($data_jabatan != null){
			foreach($data_jabatan as $row)
			{
				$this->riwayat_jabatan_model->where("TANGGAL_SK",date('Y-m-d', strtotime($row->tanggalSk)));
				$data_riwayat = $this->riwayat_jabatan_model->find_by("PNS_ID",trim($pegawai_lokal->PNS_ID));
				$idPns 		= $row->idPns;
	        	if(!isset($data_riwayat->ID))
	        	{
	        		$data = array();
                    if($row->jenisJabatan == "FUNGSIONAL_TERTENTU"){
                        $recjabatan = $this->jabatan_model->find_by("KODE_BKN",TRIM($row->jabatanFungsionalId));

                        $data["ID_JENIS_JABATAN"]      = 2;
                        $data["JENIS_JABATAN"]      = "Jabatan Fungsional Tertentu";
                        $data["ID_JABATAN_BKN"]     = $row->jabatanFungsionalId;
                        $data["NAMA_JABATAN"]   = $row->jabatanFungsionalNama;
                        $data["ID_JABATAN"]   = isset($recjabatan->KODE_JABATAN) ? $recjabatan->KODE_JABATAN : "";
                    }
                    if($row->jenisJabatan == "FUNGSIONAL_UMUM"){
                        $recjabatan = $this->jabatan_model->find_by("KODE_BKN",TRIM($row->jabatanFungsionalUmumId));
                        $data["ID_JENIS_JABATAN"]      = 4;
                        $data["JENIS_JABATAN"]      = "Jabatan Fungsional Umum";
                        $data["ID_JABATAN_BKN"]     = $row->jabatanFungsionalUmumId;
                        $data["NAMA_JABATAN"]   = $row->jabatanFungsionalUmumNama;
                        $data["ID_JABATAN"]   = isset($recjabatan->KODE_JABATAN) ? $recjabatan->KODE_JABATAN : "";
                    }
                    if($row->jenisJabatan == "STRUKTURAL"){
                        $recjabatan = $this->jabatan_model->find_by("KODE_BKN",TRIM($row->unorId));
                        $data["ID_JENIS_JABATAN"]      = 1;
                        $data["JENIS_JABATAN"]      = "Struktural";
                        $data["ID_JABATAN_BKN"]     = $row->unorId;   
                        $data["NAMA_JABATAN"]   = $row->namaJabatan;
                        $data["ID_JABATAN"]   = isset($recjabatan->KODE_JABATAN) ? $recjabatan->KODE_JABATAN : "";
                    }
                    
                    $data["PNS_NIP"]    = $pegawai_lokal->NIP_BARU;
                    $data["PNS_ID"]     = trim($pegawai_lokal->PNS_ID);
                    $data["PNS_NAMA"]   = trim($pegawai_lokal->NAMA);
                    $data["TANGGAL_SK"] = date('Y-m-d', strtotime($row->tanggalSk));
                    $data["TMT_JABATAN"] = date('Y-m-d', strtotime($row->tmtJabatan));
                    $data["NOMOR_SK"]      = $row->nomorSk;
                    // struktur
                    $data["ID_UNOR_BKN"]    = $row->unorId;
                    $data["ID_UNOR"]    = $row->unorId;
                    $data["UNOR"]           = $row->unorNama;
                    
                    $data["ID_SATUAN_KERJA"]    = $row->satuanKerjaId;
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
			        $this->riwayat_jabatan_model->skip_validation(true);
			        $result = $this->riwayat_jabatan_model->insert($data);
				    log_activity_pegawai($NIP_BARU,$this->auth->user_id(),"sinkron data riwayat jabatan dari BKN ".$row->namaJabatan, 'pegawai');
	        	}else{
                    $data = array();
                    if($row->jenisJabatan == "FUNGSIONAL_TERTENTU"){
                        $recjabatan = $this->jabatan_model->find_by("KODE_BKN",TRIM($row->jabatanFungsionalId));

                        $data["ID_JENIS_JABATAN"]      = 2;
                        $data["JENIS_JABATAN"]      = "Jabatan Fungsional Tertentu";
                        $data["ID_JABATAN_BKN"]     = $row->jabatanFungsionalId;
                        $data["NAMA_JABATAN"]   = $row->jabatanFungsionalNama;
                        $data["ID_JABATAN"]   = isset($recjabatan->KODE_JABATAN) ? $recjabatan->KODE_JABATAN : "";
                    }
                    if($row->jenisJabatan == "FUNGSIONAL_UMUM"){
                        $recjabatan = $this->jabatan_model->find_by("KODE_BKN",TRIM($row->jabatanFungsionalUmumId));
                        $data["ID_JENIS_JABATAN"]      = 4;
                        $data["JENIS_JABATAN"]      = "Jabatan Fungsional Umum";
                        $data["ID_JABATAN_BKN"]     = $row->jabatanFungsionalUmumId;
                        $data["NAMA_JABATAN"]   = $row->jabatanFungsionalUmumNama;
                        $data["ID_JABATAN"]   = isset($recjabatan->KODE_JABATAN) ? $recjabatan->KODE_JABATAN : "";
                    }
                    if($row->jenisJabatan == "STRUKTURAL"){
                        $recjabatan = $this->jabatan_model->find_by("KODE_BKN",TRIM($row->unorId));
                        $data["ID_JENIS_JABATAN"]      = 1;
                        $data["JENIS_JABATAN"]      = "Struktural";
                        $data["ID_JABATAN_BKN"]     = $row->unorId;   
                        $data["NAMA_JABATAN"]   = $row->namaJabatan;
                        $data["ID_JABATAN"]   = isset($recjabatan->KODE_JABATAN) ? $recjabatan->KODE_JABATAN : "";
                    }
                    
                    $data["PNS_NIP"]    = $pegawai_lokal->NIP_BARU;
                    $data["PNS_ID"]     = trim($pegawai_lokal->PNS_ID);
                    $data["PNS_NAMA"]   = trim($pegawai_lokal->NAMA);
                    $data["TANGGAL_SK"] = date('Y-m-d', strtotime($row->tanggalSk));
                    $data["TMT_JABATAN"] = date('Y-m-d', strtotime($row->tmtJabatan));
                    $data["NOMOR_SK"]      = $row->nomorSk;
                    // struktur
                    $data["ID_UNOR_BKN"]    = $row->unorId;
                    $data["ID_UNOR"]    = $row->unorId;
                    $data["UNOR"]           = $row->unorNama;
                    
                    $data["ID_SATUAN_KERJA"]    = $row->satuanKerjaId;
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
                    $this->riwayat_jabatan_model->skip_validation(true);
                    // print_r($data);
                    $result = $this->riwayat_jabatan_model->update($data_riwayat->ID,$data);
                    log_activity_pegawai($NIP_BARU,$this->auth->user_id(),"Update sinkron data riwayat jabatan dari BKN ".$row->namaJabatan, 'pegawai');
                }

			}	
		}
		return $result;
	}
	private function sinkron_pns_unor($pegawai_lokal = null){
		$nip_baru = trim($pegawai_lokal->NIP_BARU);
		$data_cache = $this->cache->get('data_rwt_pnsunor_'.$nip_baru);
		$result = true;
		if($data_cache != null){
			foreach($data_cache as $row)
			{
				$this->riwayat_pindah_unit_kerja_model->where("ID_UNOR_BARU",$row->unorBaru);
				$data_riwayat = $this->riwayat_pindah_unit_kerja_model->find_by("PNS_ID",trim($pegawai_lokal->PNS_ID));
				$idPns 		= $row->idPns;
	        	if(!isset($data_riwayat->ID))
	        	{
	        		$data = array();
			        $data["PNS_NIP"] 	= $pegawai_lokal->NIP_BARU;
			        $data["PNS_ID"] 	= trim($pegawai_lokal->PNS_ID);
			        $data["PNS_NAMA"] 	= trim($pegawai_lokal->NAMA);
			        $data["ID_UNOR_BARU"] = $row->unorBaru;
			        $data["NAMA_UNOR_BARU"] = $row->namaUnorBaru;
			        $data["ID_INSTANSI"] = $row->instansi;
			        $data["NAMA_INSTANSI"] = $row->instansi;
			        $data["SK_TANGGAL"] = date('Y-m-d', strtotime($row->skTanggal));
			        $data["ASAL_ID"] 	= $row->asalId;
			        $data["ASAL_NAMA"] 	= $row->asalNama;
			        $data["SK_NOMOR"] 	= $row->skNomor;

			        if(empty($data["SK_TANGGAL"])){
			            unset($data["SK_TANGGAL"]);
			        }
			        $this->riwayat_pindah_unit_kerja_model->skip_validation(true);
			        $result = $this->riwayat_pindah_unit_kerja_model->insert($data);
				    log_activity_pegawai($NIP_BARU,$this->auth->user_id(),"sinkron data riwayat unor dari BKN ".$row->unorBaru, 'pegawai');
	        	}

			}	
		}
		return $result;
	}
	private function sinkron_diklat($pegawai_lokal = null){
		$nip_baru = trim($pegawai_lokal->NIP_BARU);
		$data_cache = $this->cache->get('rwt_diklat_'.$nip_baru);
		$result = true;
		if($data_cache != null){
			foreach($data_cache as $row)
			{
				$this->db->group_start();
				$this->diklat_struktural_model->where("TANGGAL",date('Y-m-d', strtotime($row->tanggal)));
				$this->diklat_struktural_model->or_where("NAMA_DIKLAT",$row->latihanStrukturalNama);
				$this->db->group_end();
				$data_riwayat = $this->diklat_struktural_model->find_by("PNS_ID",trim($pegawai_lokal->PNS_ID));
				$idPns 		= $row->idPns;
	        	if(!isset($data_riwayat->ID))
	        	{
	        		$data = array();
	        		$data["PNS_ID"] 	= trim($pegawai_lokal->PNS_ID);
			        $data["PNS_NIP"] 	= $pegawai_lokal->NIP_BARU;
			        $data["PNS_NAMA"] 	= trim($pegawai_lokal->NAMA);

			        $data["ID_DIKLAT"] = $row->latihanStrukturalId;
			        $data["NAMA_DIKLAT"] = $row->latihanStrukturalNama;
			        $data["NOMOR"] = $row->nomor;
			        $data["TANGGAL"] = date('Y-m-d', strtotime($row->tanggal));
			        $data["TAHUN"] 	= $row->tahun;

			        if(empty($data["TANGGAL"])){
			            unset($data["TANGGAL"]);
			        }
			        $this->diklat_struktural_model->skip_validation(true);
			        $result = $this->diklat_struktural_model->insert($data);
				    log_activity_pegawai($NIP_BARU,$this->auth->user_id(),"sinkron data riwayat diklat dari BKN ".$row->latihanStrukturalNama, 'pegawai');
	        	}

			}	
		}
		return $result;
	}
    public function save_data_utama(){
        $this->load->model('pegawai/golongan_model');
        $kolom   = $this->input->post('kode');
        $nip_baru   = $this->input->post('nip');
        $pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU",$nip_baru);       

        $this->load->library('Api_bkn');
        $api_lipi = new Api_bkn;

        $pegawai = $this->cache->get('data_utama_'.$nip_baru);
        $data       = array();
        // print_r($pegawai);
        $status = false;
        $msg = "";
        if($kolom == "agamaId"){
            if($pegawai->agamaId != "")
                $data["AGAMA_ID"]   =   isset($pegawai->agamaId)    ? (int)$pegawai->agamaId :  "";
        } 
        if($kolom == "akteKelahiran") 
            $data["AKTE_KELAHIRAN"] =   isset($pegawai->akteKelahiran)  ?   $pegawai->akteKelahiran :   "";
        if($kolom == "akteMeninggal") 
            $data["AKTE_MENINGGAL"] =   isset($pegawai->akteMeninggal)  ?   $pegawai->akteMeninggal :   "";
        if($kolom == "alamat") 
            $data["ALAMAT"] =   isset($pegawai->alamat) ?   $pegawai->alamat :  "";
        if($kolom == "bpjs") 
            $data["BPJS"]   =   isset($pegawai->bpjs)   ?   $pegawai->bpjs :    "";
        if($kolom == "email") 
            $data["EMAIL"]  =   isset($pegawai->email)  ?   $pegawai->email :   "";
        if($kolom == "gelarBelakang") 
            $data["GELAR_BELAKANG"] =   isset($pegawai->gelarBelakang)  ?   $pegawai->gelarBelakang :   "";

        if($kolom == "gelarDepan") 
            $data["GELAR_DEPAN"]    =   isset($pegawai->gelarDepan) ?   $pegawai->gelarDepan :  "";
        if($kolom == "golRuangAwal") 
            $data["GOL_AWAL_ID"]    =   isset($pegawai->golRuangAwalId) ?   $pegawai->golRuangAwalId :  "";
        if($kolom == "golRuangAkhirId") 
            $data["GOL_ID"] =   isset($pegawai->golRuangAkhirId)    ?   $pegawai->golRuangAkhirId :     "";
        if($kolom == "instansiIndukId") 
            $data["INSTANSI_INDUK_ID"]  =   isset($pegawai->instansiIndukId)    ?   $pegawai->instansiIndukId :     "";
        if(isset($pegawai_lokal->INSTANSI_INDUK_NAMA) and $pegawai_lokal->INSTANSI_INDUK_NAMA == "" and $kolom == "instansiIndukNama") 
            $data["INSTANSI_INDUK_NAMA"]    =   isset($pegawai->instansiIndukNama)  ?   $pegawai->instansiIndukNama :   "";
        if(isset($pegawai_lokal->INSTANSI_KERJA_ID) and $pegawai_lokal->INSTANSI_KERJA_ID == "" and $kolom == "instansiKerjaId") 
            $data["INSTANSI_KERJA_ID"]  =   isset($pegawai->instansiKerjaId)    ?   $pegawai->instansiKerjaId :     "";
        if(isset($pegawai_lokal->INSTANSI_KERJA_NAMA) and $pegawai_lokal->INSTANSI_KERJA_NAMA == "" and $kolom == "instansiKerjaNama") 
            $data["INSTANSI_KERJA_NAMA"]    =   isset($pegawai->instansiKerjaNama)  ?   $pegawai->instansiKerjaNama :   "";
        // if(isset($pegawai_lokal->JABATAN_ID) and $pegawai_lokal->JABATAN_ID == "" and $kolom == "JABATAN_ID") 
        //     $data["JABATAN_ID"] =   isset($pegawai->jabatanStrukturalId)    ?   $pegawai->jabatanStrukturalId :     "";
        
        if($kolom == "jenisJabatanId") 
            $data["JENIS_JABATAN_ID"]   =   isset($pegawai->jenisJabatanId) ?   $pegawai->jenisJabatanId :  "";
        if($kolom == "jenisJabatan") 
            $data["JENIS_JABATAN_NAMA"] =   isset($pegawai->jenisJabatan)   ?   $pegawai->jenisJabatan :    "";
        if($kolom == "jenisKawinId") 
            $data["JENIS_KAWIN_ID"] =   isset($pegawai->jenisKawinId)   ?   $pegawai->jenisKawinId :    "";

        if($kolom == "jenisKelamin"){
            $jenis_kelamin = isset($pegawai->jenisKelamin)  ?   TRIM($pegawai->jenisKelamin) :  "";
                if($jenis_kelamin == "Pria")
                    $jk = "M";
                if($jenis_kelamin == "Wanita")
                    $jk = "F";

                $data["JENIS_KELAMIN"]  =   $jk;
                // $data["JENIS_KELAMIN"]   =   isset($pegawai->jenisKelamin)   ?   $pegawai->jenisKelamin :    "";
        } 
        if($kolom == "jenisPegawaiId") 
            $data["JENIS_PEGAWAI_ID"]   =   isset($pegawai->jenisPegawaiId) ?   $pegawai->jenisPegawaiId :  "";
        if($kolom == "jumlahAnak") 
            $data["JML_ANAK"]   =   isset($pegawai->jumlahAnak) ?   $pegawai->jumlahAnak :  "";
        if($kolom == "jumlahIstriSuami") 
            $data["JML_ISTRI"]  =   isset($pegawai->jumlahIstriSuami)   ?   $pegawai->jumlahIstriSuami :    "";
        if($kolom == "noSeriKarpeg") 
            $data["KARTU_PEGAWAI"]  =   isset($pegawai->noSeriKarpeg)   ?   $pegawai->noSeriKarpeg :    "";
        if($kolom == "kedudukanPnsId") 
            $data["KEDUDUKAN_HUKUM_ID"] =   isset($pegawai->kedudukanPnsId) ?   $pegawai->kedudukanPnsId :  "";
        
        
        if($kolom == "nama") 
            $data["NAMA"]   =   isset($pegawai->nama)   ?   $pegawai->nama :    "";
        if($kolom == "nik") 
            $data["NIK"]    =   isset($pegawai->nik)    ?   $pegawai->nik :     "";
        
        if($kolom == "noAskes") 
            $data["NO_ASKES"]   =   isset($pegawai->noAskes)    ?   trim($pegawai->noAskes) :   "";
        if($kolom == "noSuratKeteranganBebasNarkoba") 
            $data["NO_BEBAS_NARKOBA"]   =   isset($pegawai->noSuratKeteranganBebasNarkoba)  ?   $pegawai->noSuratKeteranganBebasNarkoba :   "";
        if($kolom == "skck") 
            $data["NO_CATATAN_POLISI"]  =   isset($pegawai->skck)   ?   $pegawai->skck :    "";
        if($kolom == "noSuratKeteranganDokter") 
            $data["NO_SURAT_DOKTER"]    =   isset($pegawai->noSuratKeteranganDokter)    ?   $pegawai->noSuratKeteranganDokter :     "";
        if($kolom == "noTaspen") 
            $data["NO_TASPEN"]  =   isset($pegawai->noTaspen)   ?   trim($pegawai->noTaspen) :  "";
        if($kolom == "noHp") 
            $data["NOMOR_HP"]   =   isset($pegawai->noHp)   ?   $pegawai->noHp :    "";
        if($kolom == "nomorSkCpns") 
            $data["NOMOR_SK_CPNS"]  =   isset($pegawai->nomorSkCpns)    ?   $pegawai->nomorSkCpns :     "";
        
        if($kolom == "noNpwp") 
            $data["NPWP"]   =   isset($pegawai->noNpwp) ?   $pegawai->noNpwp :  "";
        if($kolom == "pendidikanTerakhirId") 
            $data["PENDIDIKAN"] =   isset($pegawai->pendidikanTerakhirId)   ?   $pegawai->pendidikanTerakhirId :    "";
        
        if($kolom == "satuanKerjaIndukId") 
            $data["SATUAN_KERJA_INDUK_ID"]  =   isset($pegawai->satuanKerjaIndukId) ?   $pegawai->satuanKerjaIndukId :  "";
        
        if($kolom == "statusPegawai") 
            $data["STATUS_CPNS_PNS"]    =   isset($pegawai->statusPegawai)  ?   $pegawai->statusPegawai :   "";
        if($kolom == "statusHidup") 
            $data["STATUS_HIDUP"]   =   isset($pegawai->statusHidup)    ?   $pegawai->statusHidup :     "";
        if($kolom == "tempatLahir") 
            $data["TEMPAT_LAHIR"]   =   isset($pegawai->tempatLahir)    ?   $pegawai->tempatLahir :     "";
        if($kolom == "tempatLahir") 
            $data["TEMPAT_LAHIR_ID"]    =   isset($pegawai->tempatLahirId)  ?   $pegawai->tempatLahirId :   "";
        if($kolom == "tglSuratKeteranganBebasNarkoba") 
            $data["TGL_BEBAS_NARKOBA"]  =   isset($pegawai->tglSuratKeteranganBebasNarkoba) ?   date('Y-m-d', strtotime($pegawai->tglSuratKeteranganBebasNarkoba)) :    "";
        if($kolom == "tglSkck") 
            $data["TGL_CATATAN_POLISI"] =   isset($pegawai->tglSkck)    ?   date('Y-m-d', strtotime($pegawai->tglSkck)) :   "";
        if($kolom == "tglLahir") 
            $data["TGL_LAHIR"]  =   isset($pegawai->tglLahir)   ?   date('Y-m-d', strtotime($pegawai->tglLahir)) :  "";
        if($kolom == "tglMeninggal") 
            $data["TGL_MENINGGAL"]  =   isset($pegawai->tglMeninggal)   ?   date('Y-m-d', strtotime($pegawai->tglMeninggal)) :  "";
        if($kolom == "tglNpwp") 
            $data["TGL_NPWP"]   =   isset($pegawai->tglNpwp)    ?   date('Y-m-d', strtotime($pegawai->tglNpwp)) :   "";
        if($kolom == "tglSuratKeteranganDokter") 
            $data["TGL_SURAT_DOKTER"]   =   isset($pegawai->tglSuratKeteranganDokter)   ?   date('Y-m-d', strtotime($pegawai->tglSuratKeteranganDokter)) :  "";

        if($kolom == "tkPendidikanTerakhirId") 
            $data["TK_PENDIDIKAN"]  =   isset($pegawai->tkPendidikanTerakhirId) ?   $pegawai->tkPendidikanTerakhirId :  "";
        if($kolom == "tmtCpns") 
            $data["TMT_CPNS"]   =   isset($pegawai->tmtCpns)    ?   date('Y-m-d', strtotime($pegawai->tmtCpns)) :   "";
        if($kolom == "tglSkCpns") 
            $data["TGL_SK_CPNS"]    =   isset($pegawai->tglSkCpns)  ?   date('Y-m-d', strtotime($pegawai->tglSkCpns)) :     "";
        if($kolom == "tmtGolAkhir") 
            $data["TMT_GOLONGAN"]   =   isset($pegawai->tmtGolAkhir)    ?   date('Y-m-d', strtotime($pegawai->tmtGolAkhir)) :   "";
        if($kolom == "tmtJabatan") 
            $data["TMT_JABATAN"]    =   isset($pegawai->tmtJabatan) ?   date('Y-m-d', strtotime($pegawai->tmtJabatan)) :    "";
        if($kolom == "lokasiKerjaId") 
            $data["LOKASI_KERJA_ID"]    =   isset($pegawai->lokasiKerjaId)  ?   $pegawai->lokasiKerjaId :   "";
        if($pegawai_lokal->UNOR_ID == "" and $kolom == "tmtPns") 
            $data["UNOR_ID"]    =   isset($pegawai->tmtPns) ?   $pegawai->tmtPns :  "";
        if($pegawai_lokal->UNOR_ID == "" and $kolom == "unorId") 
            $data["UNOR_ID"]    =   isset($pegawai->unorId) ?   $pegawai->unorId :  "";
        if($pegawai_lokal->UNOR_INDUK_ID == "" and $kolom == "unorIndukId") 
            $data["UNOR_INDUK_ID"]  =   isset($pegawai->unorIndukId)    ?   $pegawai->unorIndukId :     "";

        if(isset($pegawai_lokal->NIP_BARU)){
            if(isset($pegawai->id) and $pegawai->id != "") {
                if($this->pegawai_model->update_where("NIP_BARU",$pegawai_lokal->NIP_BARU, $data))
                {
                    $status = true;
                    $msg = "Update Berhasil";
                }else{
                    $status = false;
                    $msg = "Update gagal";
                }   
            }else{
                $status = false;
                $msg = "Data tidak ditemukan";
            }
            
        }
        $response ['success']= true;
        $response ['id']= $pegawai->id;
        $response ['msg']= "Update berhasil";
        echo json_encode($response);  
        exit();
    }
    public function getpegawaibknnew()
    {
        $this->load->library('Api_bkn');
        $api_lipi = new Api_bkn;
        $this->load->model('pegawai/golongan_model');
        $golongan_data = $this->golongan_model->find_all();
        $agolongan = array();
        foreach($golongan_data as $row)
        {
            $agolongan[$row->ID] = $row->NAMA_PANGKAT;
        }
        $nip_baru   = $this->input->post('nip_bkn');
        $nips = explode(",",$nip_baru);
        $jml_sukses = 0;
        $hasil = false;
        foreach ($nips as $nip) {
            $pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU",$nip);
            // get data utama
            $pegawai = $this->getdata_utama_bkn($nip);
            $data       = array();
            //jika sudah ada di data lokal
            if(isset($pegawai->id) and $pegawai->id != "")
            {
                $hasil = true;
                $jml_sukses++;
                if(isset($pegawai_lokal->NIP_BARU)){
                    if(isset($pegawai->id) and $pegawai->id != "") 
                    $data["PNS_ID"] =   isset($pegawai->id) ?   $pegawai->id :  "";

                    if(isset($pegawai_lokal->AGAMA_ID) and $pegawai_lokal->AGAMA_ID == "") 
                        $data["AGAMA_ID"]   =   isset($pegawai->agamaId)    ?   $pegawai->agamaId :     "";
                    if(isset($pegawai_lokal->AKTE_KELAHIRAN) and $pegawai_lokal->AKTE_KELAHIRAN == "") 
                        $data["AKTE_KELAHIRAN"] =   isset($pegawai->akteKelahiran)  ?   $pegawai->akteKelahiran :   "";
                    if(isset($pegawai_lokal->AKTE_MENINGGAL) and $pegawai_lokal->AKTE_MENINGGAL == "") 
                        $data["AKTE_MENINGGAL"] =   isset($pegawai->akteMeninggal)  ?   $pegawai->akteMeninggal :   "";
                    if(isset($pegawai_lokal->ALAMAT) and $pegawai_lokal->ALAMAT == "") 
                        $data["ALAMAT"] =   isset($pegawai->alamat) ?   $pegawai->alamat :  "";
                    if(isset($pegawai_lokal->BPJS) and $pegawai_lokal->BPJS == "") 
                        $data["BPJS"]   =   isset($pegawai->bpjs)   ?   $pegawai->bpjs :    "";
                    if(isset($pegawai_lokal->EMAIL) and $pegawai_lokal->EMAIL == "") 
                        $data["EMAIL"]  =   isset($pegawai->email)  ?   $pegawai->email :   "";
                    if(isset($pegawai_lokal->GELAR_BELAKANG) and $pegawai_lokal->GELAR_BELAKANG == "") 
                        $data["GELAR_BELAKANG"] =   isset($pegawai->gelarBelakang)  ?   $pegawai->gelarBelakang :   "";

                    if(isset($pegawai_lokal->GELAR_DEPAN) and $pegawai_lokal->GELAR_DEPAN == "") 
                        $data["GELAR_DEPAN"]    =   isset($pegawai->gelarDepan) ?   $pegawai->gelarDepan :  "";
                    if(isset($pegawai_lokal->GOL_AWAL_ID) and $pegawai_lokal->GOL_AWAL_ID == "") 
                        $data["GOL_AWAL_ID"]    =   isset($pegawai->golRuangAwalId) ?   $pegawai->golRuangAwalId :  "";
                    if(isset($pegawai_lokal->GOL_ID) and (trim($pegawai_lokal->GOL_ID) == "" or trim($pegawai_lokal->GOL_ID) == "0")) 
                        $data["GOL_ID"] =   isset($pegawai->golRuangAkhirId)    ?   $pegawai->golRuangAkhirId :     "";
                    if(isset($pegawai_lokal->INSTANSI_INDUK_ID) and $pegawai_lokal->INSTANSI_INDUK_ID == "") 
                        $data["INSTANSI_INDUK_ID"]  =   isset($pegawai->instansiIndukId)    ?   $pegawai->instansiIndukId :     "";
                    if(isset($pegawai_lokal->INSTANSI_INDUK_NAMA) and $pegawai_lokal->INSTANSI_INDUK_NAMA == "") 
                        $data["INSTANSI_INDUK_NAMA"]    =   isset($pegawai->instansiIndukNama)  ?   $pegawai->instansiIndukNama :   "";
                    if(isset($pegawai_lokal->INSTANSI_KERJA_ID) and $pegawai_lokal->INSTANSI_KERJA_ID == "") 
                        $data["INSTANSI_KERJA_ID"]  =   isset($pegawai->instansiKerjaId)    ?   $pegawai->instansiKerjaId :     "";
                    if(isset($pegawai_lokal->INSTANSI_KERJA_NAMA) and $pegawai_lokal->INSTANSI_KERJA_NAMA == "") 
                        $data["INSTANSI_KERJA_NAMA"]    =   isset($pegawai->instansiKerjaNama)  ?   $pegawai->instansiKerjaNama :   "";
                    if(isset($pegawai_lokal->JABATAN_ID) and $pegawai_lokal->JABATAN_ID == "") 
                        $data["JABATAN_ID"] =   isset($pegawai->jabatanStrukturalId)    ?   $pegawai->jabatanStrukturalId :     "";
                    
                    if(isset($pegawai_lokal->JENIS_JABATAN_ID) and $pegawai_lokal->JENIS_JABATAN_ID == "") 
                        $data["JENIS_JABATAN_ID"]   =   isset($pegawai->jenisJabatanId) ?   $pegawai->jenisJabatanId :  "";
                    if(isset($pegawai_lokal->JENIS_JABATAN_NAMA) and $pegawai_lokal->JENIS_JABATAN_NAMA == "") 
                        $data["JENIS_JABATAN_NAMA"] =   isset($pegawai->jenisJabatan)   ?   $pegawai->jenisJabatan :    "";
                    if(isset($pegawai_lokal->JENIS_KAWIN_ID) and $pegawai_lokal->JENIS_KAWIN_ID == "") 
                        $data["JENIS_KAWIN_ID"] =   isset($pegawai->jenisKawinId)   ?   $pegawai->jenisKawinId :    "";

                    if(isset($pegawai_lokal->JENIS_KELAMIN) and $pegawai_lokal->JENIS_KELAMIN == ""){
                        $jenis_kelamin = isset($pegawai->jenisKelamin)  ?   TRIM($pegawai->jenisKelamin) :  "";
                        if($jenis_kelamin == "Pria")
                            $jk = "M";
                        if($jenis_kelamin == "Wanita")
                            $jk = "F";

                        $data["JENIS_KELAMIN"]  =   $jk;
                    } 
                        
                    if(isset($pegawai_lokal->JENIS_PEGAWAI_ID) and $pegawai_lokal->JENIS_PEGAWAI_ID == "") 
                        $data["JENIS_PEGAWAI_ID"]   =   isset($pegawai->jenisPegawaiId) ?   $pegawai->jenisPegawaiId :  "";
                    if(isset($pegawai_lokal->JML_ANAK) and $pegawai_lokal->JML_ANAK == "") 
                        $data["JML_ANAK"]   =   isset($pegawai->jumlahAnak) ?   $pegawai->jumlahAnak :  "";
                    if(isset($pegawai_lokal->JML_ISTRI) and $pegawai_lokal->JML_ISTRI == "") 
                        $data["JML_ISTRI"]  =   isset($pegawai->jumlahIstriSuami)   ?   $pegawai->jumlahIstriSuami :    "";
                    if(isset($pegawai_lokal->KARTU_PEGAWAI) and $pegawai_lokal->KARTU_PEGAWAI == "") 
                        $data["KARTU_PEGAWAI"]  =   isset($pegawai->noSeriKarpeg)   ?   $pegawai->noSeriKarpeg :    "";
                    if(isset($pegawai_lokal->KEDUDUKAN_HUKUM_ID) and $pegawai_lokal->KEDUDUKAN_HUKUM_ID == "") 
                        $data["KEDUDUKAN_HUKUM_ID"] =   isset($pegawai->kedudukanPnsId) ?   $pegawai->kedudukanPnsId :  "";
                    if(isset($pegawai_lokal->KODECEPAT) and $pegawai_lokal->KODECEPAT == "") 
                        $data["KODECEPAT"]  =   isset($pegawai->instansiKerjaKodeCepat) ?   $pegawai->instansiKerjaKodeCepat :  "";
                    if(isset($pegawai_lokal->KPKN_ID) and $pegawai_lokal->KPKN_ID == "") 
                        $data["KPKN_ID"]    =   isset($pegawai->kpknId) ?   $pegawai->kpknId :  "";
                    if(isset($pegawai_lokal->MK_BULAN) and $pegawai_lokal->MK_BULAN == "") 
                        $data["MK_BULAN"]   =   isset($pegawai->mkBulan)    ?   $pegawai->mkBulan :     "";
                    if(isset($pegawai_lokal->MK_TAHUN) and $pegawai_lokal->MK_TAHUN == "") 
                        $data["MK_TAHUN"]   =   isset($pegawai->mkTahun)    ?   $pegawai->mkTahun :     "";
                    if(isset($pegawai_lokal->NAMA) and $pegawai_lokal->NAMA == "") 
                        $data["NAMA"]   =   isset($pegawai->nama)   ?   $pegawai->nama :    "";
                    if(isset($pegawai_lokal->NIK) and $pegawai_lokal->NIK == "") 
                        $data["NIK"]    =   isset($pegawai->nik)    ?   $pegawai->nik :     "";
                    
                    if(isset($pegawai_lokal->NIP_LAMA) and $pegawai_lokal->NIP_LAMA == "") 
                        $data["NIP_LAMA"]   =   isset($pegawai->nipLama)    ?   $pegawai->nipLama :     "";
                    if(isset($pegawai_lokal->NO_ASKES) and $pegawai_lokal->NO_ASKES == "") 
                        $data["NO_ASKES"]   =   isset($pegawai->noAskes)    ?   $pegawai->noAskes :     "";
                    if(isset($pegawai_lokal->NO_BEBAS_NARKOBA) and $pegawai_lokal->NO_BEBAS_NARKOBA == "") 
                        $data["NO_BEBAS_NARKOBA"]   =   isset($pegawai->noSuratKeteranganBebasNarkoba)  ?   $pegawai->noSuratKeteranganBebasNarkoba :   "";
                    if(isset($pegawai_lokal->NO_CATATAN_POLISI) and $pegawai_lokal->NO_CATATAN_POLISI == "") 
                        $data["NO_CATATAN_POLISI"]  =   isset($pegawai->skck)   ?   $pegawai->skck :    "";
                    if(isset($pegawai_lokal->NO_SURAT_DOKTER) and $pegawai_lokal->NO_SURAT_DOKTER == "") 
                        $data["NO_SURAT_DOKTER"]    =   isset($pegawai->noSuratKeteranganDokter)    ?   $pegawai->noSuratKeteranganDokter :     "";
                    if(isset($pegawai_lokal->NO_TASPEN) and $pegawai_lokal->NO_TASPEN == "") 
                        $data["NO_TASPEN"]  =   isset($pegawai->noTaspen)   ?   $pegawai->noTaspen :    "";
                    if(isset($pegawai_lokal->NOMOR_HP) and $pegawai_lokal->NOMOR_HP == "") 
                        $data["NOMOR_HP"]   =   isset($pegawai->noHp)   ?   $pegawai->noHp :    "";
                    if(isset($pegawai_lokal->NOMOR_SK_CPNS) and $pegawai_lokal->NOMOR_SK_CPNS == "") 
                        $data["NOMOR_SK_CPNS"]  =   isset($pegawai->nomorSkCpns)    ?   $pegawai->nomorSkCpns :     "";
                    if(isset($pegawai_lokal->NPWP) and $pegawai_lokal->NPWP == "") 
                        $data["NPWP"]   =   isset($pegawai->noNpwp) ?   $pegawai->noNpwp :  "";
                    if(isset($pegawai_lokal->PENDIDIKAN) and $pegawai_lokal->PENDIDIKAN == "") 
                        $data["PENDIDIKAN"] =   isset($pegawai->pendidikanTerakhirId)   ?   $pegawai->pendidikanTerakhirId :    "";
                    
                    if(isset($pegawai_lokal->SATUAN_KERJA_INDUK_ID) and $pegawai_lokal->SATUAN_KERJA_INDUK_ID == "") 
                        $data["SATUAN_KERJA_INDUK_ID"]  =   isset($pegawai->satuanKerjaIndukId) ?   $pegawai->satuanKerjaIndukId :  "";
                    if(isset($pegawai_lokal->SATUAN_KERJA_INDUK_NAMA) and $pegawai_lokal->SATUAN_KERJA_INDUK_NAMA == "") 
                        $data["SATUAN_KERJA_INDUK_NAMA"]    =   isset($pegawai->satuanKerjaIndukNama)   ?   $pegawai->satuanKerjaIndukNama :    "";
                    if(isset($pegawai_lokal->SATUAN_KERJA_KERJA_ID) and $pegawai_lokal->SATUAN_KERJA_KERJA_ID == "") 
                        $data["SATUAN_KERJA_KERJA_ID"]  =   isset($pegawai->satuanKerjaKerjaId) ?   $pegawai->satuanKerjaKerjaId :  "";
                    if(isset($pegawai_lokal->SATUAN_KERJA_NAMA) and $pegawai_lokal->SATUAN_KERJA_NAMA == "") 
                        $data["SATUAN_KERJA_NAMA"]  =   isset($pegawai->satuanKerjaKerjaNama)   ?   $pegawai->satuanKerjaKerjaNama :    "";
                    if(isset($pegawai_lokal->STATUS_CPNS_PNS) and $pegawai_lokal->STATUS_CPNS_PNS == "") 
                        $data["STATUS_CPNS_PNS"]    =   isset($pegawai->statusPegawai)  ?   $pegawai->statusPegawai :   "";
                    if(isset($pegawai_lokal->STATUS_HIDUP) and $pegawai_lokal->STATUS_HIDUP == "") 
                        $data["STATUS_HIDUP"]   =   isset($pegawai->statusHidup)    ?   $pegawai->statusHidup :     "";
                    if(isset($pegawai_lokal->TEMPAT_LAHIR) and trim($pegawai_lokal->TEMPAT_LAHIR) == "") 
                        $data["TEMPAT_LAHIR"]   =   isset($pegawai->tempatLahir)    ?   $pegawai->tempatLahir :     "";
                    if(isset($pegawai_lokal->TEMPAT_LAHIR_ID) and trim($pegawai_lokal->TEMPAT_LAHIR_ID) == "") 
                        $data["TEMPAT_LAHIR_ID"]    =   isset($pegawai->tempatLahirId)  ?   $pegawai->tempatLahirId :   "";
                    if(isset($pegawai_lokal->TGL_BEBAS_NARKOBA) and $pegawai_lokal->TGL_BEBAS_NARKOBA == "") 
                        $data["TGL_BEBAS_NARKOBA"]  =   isset($pegawai->tglSuratKeteranganBebasNarkoba) ?   date('Y-m-d', strtotime($pegawai->tglSuratKeteranganBebasNarkoba)) :    "";
                    if(isset($pegawai_lokal->TGL_CATATAN_POLISI) and $pegawai_lokal->TGL_CATATAN_POLISI == "") 
                        $data["TGL_CATATAN_POLISI"] =   isset($pegawai->tglSkck)    ?   $pegawai->tglSkck :     "";
                    if(isset($pegawai_lokal->TGL_LAHIR) and $pegawai_lokal->TGL_LAHIR == "") 
                        $data["TGL_LAHIR"]  =   isset($pegawai->tglLahir)   ?   $pegawai->tglLahir :    "";
                    if(isset($pegawai_lokal->TGL_MENINGGAL) and $pegawai_lokal->TGL_MENINGGAL == "") 
                        $data["TGL_MENINGGAL"]  =   isset($pegawai->tglMeninggal)   ?   $pegawai->tglMeninggal :    "";
                    if(isset($pegawai_lokal->TGL_NPWP) and $pegawai_lokal->TGL_NPWP == "") 
                        $data["TGL_NPWP"]   =   isset($pegawai->tglNpwp)    ?   $pegawai->tglNpwp :     "";
                    if(isset($pegawai_lokal->TGL_SURAT_DOKTER) and $pegawai_lokal->TGL_SURAT_DOKTER == "") 
                        $data["TGL_SURAT_DOKTER"]   =   isset($pegawai->tglSuratKeteranganDokter)   ?   $pegawai->tglSuratKeteranganDokter :    "";
                    if(isset($pegawai_lokal->TK_PENDIDIKAN) and $pegawai_lokal->TK_PENDIDIKAN == "") 
                        $data["TK_PENDIDIKAN"]  =   isset($pegawai->tkPendidikanTerakhirId) ?   $pegawai->tkPendidikanTerakhirId :  "";
                    if(isset($pegawai_lokal->TMT_CPNS) and $pegawai_lokal->TMT_CPNS == "") 
                        $data["TMT_CPNS"]   =   isset($pegawai->tmtCpns)    ?   $pegawai->tmtCpns :     "";
                    if(isset($pegawai_lokal->TGL_SK_CPNS) and $pegawai_lokal->TGL_SK_CPNS == "") 
                        $data["TGL_SK_CPNS"]    =   isset($pegawai->tglSkCpns)  ?   $pegawai->tglSkCpns :   "";
                    if(isset($pegawai_lokal->TMT_GOLONGAN) and $pegawai_lokal->TMT_GOLONGAN == "") 
                        $data["TMT_GOLONGAN"]   =   isset($pegawai->tmtGolAkhir)    ?   $pegawai->tmtGolAkhir :     "";
                    if(isset($pegawai_lokal->TMT_JABATAN) and $pegawai_lokal->TMT_JABATAN == "") 
                        $data["TMT_JABATAN"]    =   isset($pegawai->tmtJabatan) ?   $pegawai->tmtJabatan :  "";
                    if(isset($pegawai_lokal->LOKASI_KERJA_ID) and $pegawai_lokal->LOKASI_KERJA_ID == "") 
                        $data["LOKASI_KERJA_ID"]    =   isset($pegawai->lokasiKerjaId)  ?   $pegawai->lokasiKerjaId :   "";
                    //if(isset($pegawai_lokal->UNOR_ID) and $pegawai_lokal->UNOR_ID == "") 
                    //  $data["UNOR_ID"]    =   isset($pegawai->tmtPns) ?   $pegawai->tmtPns :  "";
                    if(isset($pegawai_lokal->UNOR_ID) and $pegawai_lokal->UNOR_ID == "") 
                        $data["UNOR_ID"]    =   isset($pegawai->unorId) ?   $pegawai->unorId :  "";
                    if(isset($pegawai_lokal->UNOR_INDUK_ID) and $pegawai_lokal->UNOR_INDUK_ID == "") 
                        $data["UNOR_INDUK_ID"]  =   isset($pegawai->unorIndukId)    ?   $pegawai->unorIndukId :     "";

                    if(isset($pegawai_lokal->NIP_BARU)){
                        if(isset($pegawai->id) and $pegawai->id != "") {
                            if($this->pegawai_model->update_where("NIP_BARU",$pegawai_lokal->NIP_BARU, $data))
                            {
                                $hasil = true;
                                $msg =  "Berhasil update data";
                            }   
                        }else{
                            $hasil = false;
                            $msg =  "Data BKN tidak didapatkan..";
                        }
                        
                    }else{
                        if($this->pegawai_model->insert($data)){
                            {
                                echo "Berhasil Tambah data";
                            }
                        }
                    }
                }else{
                    $data["PNS_ID"] =   isset($pegawai->id) ?   $pegawai->id :  "";
                    $data["NIP_BARU"]   =   isset($pegawai->nipBaru)    ?   $pegawai->nipBaru :     "";
                    if($pegawai->agamaId != "")
                        $data["AGAMA_ID"]   =   isset($pegawai->agamaId)    ? (int)$pegawai->agamaId :  "";
                    if($pegawai->akteKelahiran != "")
                        $data["AKTE_KELAHIRAN"] =   isset($pegawai->akteKelahiran)  ?   $pegawai->akteKelahiran :   "";
                    if($pegawai->akteMeninggal != "")
                        $data["AKTE_MENINGGAL"] =   isset($pegawai->akteMeninggal)  ?   $pegawai->akteMeninggal :   "";
                    $data["ALAMAT"] =   isset($pegawai->alamat) ?   $pegawai->alamat :  "";
                    $data["BPJS"]   =   isset($pegawai->bpjs)   ?   $pegawai->bpjs :    "";
                    $data["EMAIL"]  =   isset($pegawai->email)  ?   $pegawai->email :   "";
                    $data["GELAR_BELAKANG"] =   isset($pegawai->gelarBelakang)  ?   $pegawai->gelarBelakang :   "";

                    $data["GELAR_DEPAN"]    =   isset($pegawai->gelarDepan) ?   $pegawai->gelarDepan :  "";
                    $data["GOL_AWAL_ID"]    =   isset($pegawai->golRuangAwalId) ?   $pegawai->golRuangAwalId :  "";
                    if($pegawai->golRuangAwal != "")
                        $data["GOL_ID"] =   isset($pegawai->golRuangAwal)   ?   (int)$pegawai->golRuangAwal :   "";
                    if($pegawai->instansiIndukId != "")
                        $data["INSTANSI_INDUK_ID"]  =   isset($pegawai->instansiIndukId)    ?   $pegawai->instansiIndukId :     "";
                    if($pegawai->instansiIndukNama != "")
                        $data["INSTANSI_INDUK_NAMA"]    =   isset($pegawai->instansiIndukNama)  ?   $pegawai->instansiIndukNama :   "";
                    if($pegawai->instansiKerjaId != "")
                        $data["INSTANSI_KERJA_ID"]  =   isset($pegawai->instansiKerjaId)    ?   $pegawai->instansiKerjaId :     "";
                    $data["INSTANSI_KERJA_NAMA"]    =   isset($pegawai->instansiKerjaNama)  ?   $pegawai->instansiKerjaNama :   "";
                    $data["JABATAN_ID"] =   isset($pegawai->jabatanStrukturalId)    ?   $pegawai->jabatanStrukturalId :     "";
                
                    $data["JENIS_JABATAN_ID"]   =   isset($pegawai->jenisJabatanId) ?   $pegawai->jenisJabatanId :  "";
                    $data["JENIS_JABATAN_NAMA"] =   isset($pegawai->jenisJabatan)   ?   $pegawai->jenisJabatan :    "";
                    $data["JENIS_KAWIN_ID"] =   isset($pegawai->jenisKawinId)   ?   $pegawai->jenisKawinId :    "";
                    $jenis_kelamin = isset($pegawai->jenisKelamin)  ?   $pegawai->jenisKelamin :    "";

                        if($jenis_kelamin == "Pria")
                            $jk = "M";
                        if($jenis_kelamin == "Wanita")
                            $jk = "F";
                    
                    $data["JENIS_KELAMIN"]  =   $jk;
                    $data["JENIS_PEGAWAI_ID"]   =   isset($pegawai->jenisPegawaiId) ?   $pegawai->jenisPegawaiId :  "";
                    $data["JML_ANAK"]   =   isset($pegawai->jumlahAnak) ?   $pegawai->jumlahAnak :  "";
                    $data["JML_ISTRI"]  =   isset($pegawai->jumlahIstriSuami)   ?   $pegawai->jumlahIstriSuami :    "";
                    $data["KARTU_PEGAWAI"]  =   isset($pegawai->noSeriKarpeg)   ?   $pegawai->noSeriKarpeg :    "";
                    $data["KEDUDUKAN_HUKUM_ID"] =   isset($pegawai->kedudukanPnsId) ?   $pegawai->kedudukanPnsId :  "";
                    $data["KODECEPAT"]  =   isset($pegawai->instansiKerjaKodeCepat) ?   $pegawai->instansiKerjaKodeCepat :  "";
                    $data["KPKN_ID"]    =   isset($pegawai->kpknId) ?   $pegawai->kpknId :  "";
                    $data["MK_BULAN"]   =   isset($pegawai->mkBulan)    ?   $pegawai->mkBulan :     "";
                    $data["MK_TAHUN"]   =   isset($pegawai->mkTahun)    ?   $pegawai->mkTahun :     "";
                    $data["NAMA"]   =   isset($pegawai->nama)   ?   $pegawai->nama :    "";
                    $data["NIK"]    =   isset($pegawai->nik)    ?   $pegawai->nik :     "";
                    $data["NIP_LAMA"]   =   isset($pegawai->nipLama)    ?   $pegawai->nipLama :     "";
                    $data["NO_ASKES"]   =   isset($pegawai->noAskes)    ?   $pegawai->noAskes :     "";
                    $data["NO_BEBAS_NARKOBA"]   =   isset($pegawai->noSuratKeteranganBebasNarkoba)  ?   $pegawai->noSuratKeteranganBebasNarkoba :   "";
                    $data["NO_CATATAN_POLISI"]  =   isset($pegawai->skck)   ?   $pegawai->skck :    "";
                    $data["NO_SURAT_DOKTER"]    =   isset($pegawai->noSuratKeteranganDokter)    ?   $pegawai->noSuratKeteranganDokter :     "";
                    $data["NO_TASPEN"]  =   isset($pegawai->noTaspen)   ?   $pegawai->noTaspen :    "";
                    $data["NOMOR_HP"]   =   isset($pegawai->noHp)   ?   $pegawai->noHp :    "";
                    $data["NOMOR_SK_CPNS"]  =   isset($pegawai->nomorSkCpns)    ?   $pegawai->nomorSkCpns :     "";
                    $data["NPWP"]   =   isset($pegawai->noNpwp) ?   $pegawai->noNpwp :  "";
                    $data["PENDIDIKAN"] =   isset($pegawai->pendidikanTerakhirId)   ?   $pegawai->pendidikanTerakhirId :    "";
                    if($pegawai->satuanKerjaIndukId != "")
                        $data["SATUAN_KERJA_INDUK_ID"]  =   isset($pegawai->satuanKerjaIndukId) ?   $pegawai->satuanKerjaIndukId :  "";
                    if($pegawai->satuanKerjaIndukNama != "")
                        $data["SATUAN_KERJA_INDUK_NAMA"]    =   isset($pegawai->satuanKerjaIndukNama)   ?   $pegawai->satuanKerjaIndukNama :    "";
                    if($pegawai->satuanKerjaKerjaId != "")
                        $data["SATUAN_KERJA_KERJA_ID"]  =   isset($pegawai->satuanKerjaKerjaId) ?   $pegawai->satuanKerjaKerjaId :  "";
                    if($pegawai->satuanKerjaKerjaNama != "")
                        $data["SATUAN_KERJA_NAMA"]  =   isset($pegawai->satuanKerjaKerjaNama)   ?   $pegawai->satuanKerjaKerjaNama :    "";
                    if($pegawai->statusPegawai != "")
                        $data["STATUS_CPNS_PNS"]    =   isset($pegawai->statusPegawai)  ?   $pegawai->statusPegawai :   "";
                    if($pegawai->statusHidup != "")
                        $data["STATUS_HIDUP"]   =   isset($pegawai->statusHidup)    ?   $pegawai->statusHidup :     "";
                    if($pegawai->tempatLahir != "")
                        $data["TEMPAT_LAHIR"]   =   isset($pegawai->tempatLahir)    ?   $pegawai->tempatLahir :     "";
                    if($pegawai->tempatLahirId != "")
                        $data["TEMPAT_LAHIR_ID"]    =   isset($pegawai->tempatLahirId)  ?   $pegawai->tempatLahirId :   "";
                    if($pegawai->tglSuratKeteranganBebasNarkoba != "")
                        $data["TGL_BEBAS_NARKOBA"]  =   isset($pegawai->tglSuratKeteranganBebasNarkoba) ?   date('Y-m-d', strtotime($pegawai->tglSuratKeteranganBebasNarkoba)) :    "";
                    if($pegawai->tglSkck != "")
                        $data["TGL_CATATAN_POLISI"] =   isset($pegawai->tglSkck)    ?   date('Y-m-d', strtotime($pegawai->tglSkck)) :   "";
                    if($pegawai->tglLahir != "")
                        $data["TGL_LAHIR"]  =   isset($pegawai->tglLahir)   ?   date('Y-m-d', strtotime($pegawai->tglLahir)) :  "";
                    if($pegawai->tglMeninggal != "")
                        $data["TGL_MENINGGAL"]  =   isset($pegawai->tglMeninggal)   ?   date('Y-m-d', strtotime($pegawai->tglMeninggal)) :  "";
                    if($pegawai->tglNpwp != "")
                        $data["TGL_NPWP"]   =   isset($pegawai->tglNpwp)    ?   date('Y-m-d', strtotime($pegawai->tglNpwp)) :   "";
                    if($pegawai->tglSuratKeteranganDokter != "")
                        $data["TGL_SURAT_DOKTER"]   =   isset($pegawai->tglSuratKeteranganDokter)   ?   date('Y-m-d', strtotime($pegawai->tglSuratKeteranganDokter)) :  "";
                    if($pegawai->tkPendidikanTerakhirId != "")
                    $data["TK_PENDIDIKAN"]  =   isset($pegawai->tkPendidikanTerakhirId) ?   $pegawai->tkPendidikanTerakhirId :  "";
                    if($pegawai->tmtPns != "")
                        $data["TMT_CPNS"]   =   isset($pegawai->tmtPns) ?   date('Y-m-d', strtotime($pegawai->tmtPns)) :    "";
                    if($pegawai->tmtCpns != "")
                        $data["TMT_CPNS"]   =   isset($pegawai->tmtCpns)    ?   date('Y-m-d', strtotime($pegawai->tmtCpns)) :   "";
                    $data["TGL_SK_CPNS"]    =   isset($pegawai->tglSkCpns)  ?   date('Y-m-d', strtotime($pegawai->tglSkCpns)) :     "";
                        $data["GOL_ID"] =   isset($pegawai->golRuangAkhirId)    ?   $pegawai->golRuangAkhirId :     "";
                    $data["TMT_GOLONGAN"]   =   isset($pegawai->tmtGolAkhir)    ?   date('Y-m-d', strtotime($pegawai->tmtGolAkhir)) :   "";
                    $data["TMT_JABATAN"]    =   isset($pegawai->tmtJabatan) ?   date('Y-m-d', strtotime($pegawai->tmtJabatan)) :    "";
                    $data["LOKASI_KERJA_ID"]    =   isset($pegawai->lokasiKerjaId)  ?   $pegawai->lokasiKerjaId :   "";
                    $data["UNOR_ID"]    =   isset($pegawai->unorId) ?   $pegawai->unorId :  "";
                    $data["UNOR_INDUK_ID"]  =   isset($pegawai->unorIndukId)    ?   $pegawai->unorIndukId :     "";

                    if($pegawai->jenisJabatanId != "")
                        $data["JENIS_JABATAN_IDx"]  =   isset($pegawai->jenisJabatanId) ?   $pegawai->jenisJabatanId :  "";

                    $data["status_pegawai"] =   1;

                    if($id = $this->pegawai_model->insert($data))
                    {
                        $hasil = true;
                        $msg =  "Berhasil Tambah data ".$id;
                        log_activity($this->auth->user_id(), $msg . ' : ' . $this->input->ip_address(), 'pegawai');
                    }
                }
                // data riwayat golongan
                $jsonrwt_golongan = $api_lipi->data_rwt_golongan_bkn($nip);
                $jsonrwt_golongan = json_decode($jsonrwt_golongan);
                $jsonrwt_golongan = json_decode($jsonrwt_golongan);
                $datajsonrwt_golongan = $jsonrwt_golongan->data;
                foreach($datajsonrwt_golongan as $row)
                {
                    $idPns      = $row->idPns;
                    $golonganId = $row->golonganId;
                    $golongan   = $row->golongan;
                    $skNomor    = TRIM($row->skNomor);
                    $skTanggal  = $row->skTanggal;
                    $tmtGolongan    = $row->tmtGolongan;
                    $noPertekBkn    = $row->noPertekBkn;
                    $tglPertekBkn   = $row->tglPertekBkn;
                    $this->riwayat_kepangkatan_model->where("SK_NOMOR",$skNomor);  
                    $count_golongan = $this->riwayat_kepangkatan_model->count_all($idPns);
                    if($count_golongan <= 0){
                        $data = array();
                        $NIP_BARU = $pegawai_lokal->NIP_BARU;
                        $data["ID_BKN"] = $row->id;
                        $data["PNS_NIP"] = $pegawai_lokal->NIP_BARU;
                        $data["PNS_ID"] = $pegawai_lokal->PNS_ID;
                        $data["PNS_NAMA"] = $pegawai_lokal->NAMA;
                        $data["JENIS_KP"] = $row->jenisKPNama;
                        $data["ID_GOLONGAN"] = $row->golonganId;
                        $data["SK_NOMOR"] = $row->skNomor;
                        $data["NOMOR_BKN"] = $row->noPertekBkn;
                        $data["MK_GOLONGAN_TAHUN"] = $row->masaKerjaGolonganTahun;
                        $data["MK_GOLONGAN_BULAN"] = $row->masaKerjaGolonganBulan;

                        $data["KODE_JENIS_KP"] = $row->jenisKPId;
                        $data["GOLONGAN"] = $row->golongan;
                        $data["PANGKAT"] = isset($agolongan[$row->golonganId]) ? $agolongan[$row->golonganId] : "";
                        
                        $data["TMT_GOLONGAN"]   = date("Y-m-d", strtotime($row->tmtGolongan));
                        $data["SK_TANGGAL"]     = date("Y-m-d", strtotime($row->skTanggal));
                        if(empty($data["TMT_GOLONGAN"])){
                            unset($data["TMT_GOLONGAN"]);
                        }
                        if(empty($data["SK_TANGGAL"])){
                            unset($data["SK_TANGGAL"]);
                        }
                        $insert_id = $this->riwayat_kepangkatan_model->insert($data);
                        log_activity_pegawai($NIP_BARU,$this->auth->user_id(),"Tambah data rwt_golongan", 'pegawai');
                    }

                }
                // end history golongan
            }
            
        }
        $response ['success']= $hasil;
        $response ['msg']= $msg.", ditemukan ".$jml_sukses." data";
        echo json_encode($response);    
    }
    public function getdatabyexcell()
    {
        $this->load->library('Api_bkn');
        $api_lipi = new Api_bkn;
        $hasil = false;
        $msg = "Data tidak ditemukan";
        $jml_sukses = 0;
        $jml_gagal = 0;
        $statusPegawai = "";
        if (isset($_FILES['file_nips']) && $_FILES['file_nips']['name']) {
            if(!$_FILES['file_nips']['error']){
                $errors=array();
                $allowed_ext= array('xls','xlsx');
                $file_name =$_FILES['file_nips']['name'];
                $file_ext = explode('.',$file_name);
                $file_size=$_FILES['file_nips']['size'];
                $file_tmp= $_FILES['file_nips']['tmp_name'];
                $type= $_FILES['file_nips']['type'];
                if(in_array(end($file_ext),$allowed_ext) === false)
                {
                    $errors[]='Extension not allowed';
                    $response ['success']= false;
                    $response['msg'] = "
                    <div class='alert alert-block alert-error fade in note note-danger'>
                        <a class='close' data-dismiss='alert'>&times;</a>
                        <h4 class='alert-heading'>
                            Ada kesalahan
                        </h4>
                        <p>Extension Database tidak diizinkan, silahkan pilih file excel</p>
                    </div>
                    ";
                    echo json_encode($response);
                    exit();
                }
                $this->load->library('Excel');
                $objPHPExcel = new PHPExcel();
                $inputFileName = $file_tmp;
                //  Read your Excel workbook
                try {
                    PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
                    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    // $objReader = PHPExcel_IOFactory::createReader(end($file_ext));
                    $objPHPExcel = $objReader->load($inputFileName);
                } catch(Exception $e) {
                    die($e->getMessage());
                    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
                }

                //  Get worksheet dimensions
                $sheet = $objPHPExcel->getSheet(0); 
                $highestRow = $sheet->getHighestRow(); 
                $highestColumn = $sheet->getHighestColumn();
                for ($row = 2; $row <= $highestRow; $row++){ 
                    $data = array();
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
                    $nip = $rowData[0][0];
                    $pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU",$nip);
                    $pegawai = $this->getdata_utama_bkn($nip);
                    // print_r($pegawai);
                    if($pegawai->id){
                        $statusPegawai .= $nip." => ok <br>";
                        $hasil = true;
                        $jml_sukses++;
                        $msg = "Berhasil ";
                    }else{
                        $jml_gagal++;
                        $statusPegawai .= $nip." => tidak ditemukan <br>";
                    }
                    $this->ActSinkron($pegawai,$pegawai_lokal);
                }

            }
        }
        // end history golongan
        $response ['success']= $hasil;
        $response ['msg']= $msg.", ditemukan ".$jml_sukses." data";
        $response ['notes']= "ditemukan ".$jml_sukses.",gagal ".$jml_gagal." data<br>".$statusPegawai;
        echo json_encode($response);    
    }
    private function ActSinkron($pegawai,$pegawai_lokal){
        if(isset($pegawai->id) and $pegawai->id != "")
            {
                $hasil = true;
                $jml_sukses++;
                if(isset($pegawai_lokal->NIP_BARU)){
                    if(isset($pegawai->id) and $pegawai->id != "") 
                    $data["PNS_ID"] =   isset($pegawai->id) ?   $pegawai->id :  "";

                    if(isset($pegawai_lokal->AGAMA_ID) and $pegawai_lokal->AGAMA_ID == "") 
                        $data["AGAMA_ID"]   =   isset($pegawai->agamaId)    ?   $pegawai->agamaId :     "";
                    if(isset($pegawai_lokal->AKTE_KELAHIRAN) and $pegawai_lokal->AKTE_KELAHIRAN == "") 
                        $data["AKTE_KELAHIRAN"] =   isset($pegawai->akteKelahiran)  ?   $pegawai->akteKelahiran :   "";
                    if(isset($pegawai_lokal->AKTE_MENINGGAL) and $pegawai_lokal->AKTE_MENINGGAL == "") 
                        $data["AKTE_MENINGGAL"] =   isset($pegawai->akteMeninggal)  ?   $pegawai->akteMeninggal :   "";
                    if(isset($pegawai_lokal->ALAMAT) and $pegawai_lokal->ALAMAT == "") 
                        $data["ALAMAT"] =   isset($pegawai->alamat) ?   $pegawai->alamat :  "";
                    if(isset($pegawai_lokal->BPJS) and $pegawai_lokal->BPJS == "") 
                        $data["BPJS"]   =   isset($pegawai->bpjs)   ?   $pegawai->bpjs :    "";
                    if(isset($pegawai_lokal->EMAIL) and $pegawai_lokal->EMAIL == "") 
                        $data["EMAIL"]  =   isset($pegawai->email)  ?   $pegawai->email :   "";
                    if(isset($pegawai_lokal->GELAR_BELAKANG) and $pegawai_lokal->GELAR_BELAKANG == "") 
                        $data["GELAR_BELAKANG"] =   isset($pegawai->gelarBelakang)  ?   $pegawai->gelarBelakang :   "";

                    if(isset($pegawai_lokal->GELAR_DEPAN) and $pegawai_lokal->GELAR_DEPAN == "") 
                        $data["GELAR_DEPAN"]    =   isset($pegawai->gelarDepan) ?   $pegawai->gelarDepan :  "";
                    if(isset($pegawai_lokal->GOL_AWAL_ID) and $pegawai_lokal->GOL_AWAL_ID == "") 
                        $data["GOL_AWAL_ID"]    =   isset($pegawai->golRuangAwalId) ?   $pegawai->golRuangAwalId :  "";
                    if(isset($pegawai_lokal->GOL_ID) and (trim($pegawai_lokal->GOL_ID) == "" or trim($pegawai_lokal->GOL_ID) == "0")) 
                        $data["GOL_ID"] =   isset($pegawai->golRuangAkhirId)    ?   $pegawai->golRuangAkhirId :     "";
                    if(isset($pegawai_lokal->INSTANSI_INDUK_ID) and $pegawai_lokal->INSTANSI_INDUK_ID == "") 
                        $data["INSTANSI_INDUK_ID"]  =   isset($pegawai->instansiIndukId)    ?   $pegawai->instansiIndukId :     "";
                    if(isset($pegawai_lokal->INSTANSI_INDUK_NAMA) and $pegawai_lokal->INSTANSI_INDUK_NAMA == "") 
                        $data["INSTANSI_INDUK_NAMA"]    =   isset($pegawai->instansiIndukNama)  ?   $pegawai->instansiIndukNama :   "";
                    if(isset($pegawai_lokal->INSTANSI_KERJA_ID) and $pegawai_lokal->INSTANSI_KERJA_ID == "") 
                        $data["INSTANSI_KERJA_ID"]  =   isset($pegawai->instansiKerjaId)    ?   $pegawai->instansiKerjaId :     "";
                    if(isset($pegawai_lokal->INSTANSI_KERJA_NAMA) and $pegawai_lokal->INSTANSI_KERJA_NAMA == "") 
                        $data["INSTANSI_KERJA_NAMA"]    =   isset($pegawai->instansiKerjaNama)  ?   $pegawai->instansiKerjaNama :   "";
                    if(isset($pegawai_lokal->JABATAN_ID) and $pegawai_lokal->JABATAN_ID == "") 
                        $data["JABATAN_ID"] =   isset($pegawai->jabatanStrukturalId)    ?   $pegawai->jabatanStrukturalId :     "";
                    
                    if(isset($pegawai_lokal->JENIS_JABATAN_ID) and $pegawai_lokal->JENIS_JABATAN_ID == "") 
                        $data["JENIS_JABATAN_ID"]   =   isset($pegawai->jenisJabatanId) ?   $pegawai->jenisJabatanId :  "";
                    if(isset($pegawai_lokal->JENIS_JABATAN_NAMA) and $pegawai_lokal->JENIS_JABATAN_NAMA == "") 
                        $data["JENIS_JABATAN_NAMA"] =   isset($pegawai->jenisJabatan)   ?   $pegawai->jenisJabatan :    "";
                    if(isset($pegawai_lokal->JENIS_KAWIN_ID) and $pegawai_lokal->JENIS_KAWIN_ID == "") 
                        $data["JENIS_KAWIN_ID"] =   isset($pegawai->jenisKawinId)   ?   $pegawai->jenisKawinId :    "";

                    if(isset($pegawai_lokal->JENIS_KELAMIN) and $pegawai_lokal->JENIS_KELAMIN == ""){
                        $jenis_kelamin = isset($pegawai->jenisKelamin)  ?   TRIM($pegawai->jenisKelamin) :  "";
                        if($jenis_kelamin == "Pria")
                            $jk = "M";
                        if($jenis_kelamin == "Wanita")
                            $jk = "F";

                        $data["JENIS_KELAMIN"]  =   $jk;
                    } 
                        
                    if(isset($pegawai_lokal->JENIS_PEGAWAI_ID) and $pegawai_lokal->JENIS_PEGAWAI_ID == "") 
                        $data["JENIS_PEGAWAI_ID"]   =   isset($pegawai->jenisPegawaiId) ?   $pegawai->jenisPegawaiId :  "";
                    if(isset($pegawai_lokal->JML_ANAK) and $pegawai_lokal->JML_ANAK == "") 
                        $data["JML_ANAK"]   =   isset($pegawai->jumlahAnak) ?   $pegawai->jumlahAnak :  "";
                    if(isset($pegawai_lokal->JML_ISTRI) and $pegawai_lokal->JML_ISTRI == "") 
                        $data["JML_ISTRI"]  =   isset($pegawai->jumlahIstriSuami)   ?   $pegawai->jumlahIstriSuami :    "";
                    if(isset($pegawai_lokal->KARTU_PEGAWAI) and $pegawai_lokal->KARTU_PEGAWAI == "") 
                        $data["KARTU_PEGAWAI"]  =   isset($pegawai->noSeriKarpeg)   ?   $pegawai->noSeriKarpeg :    "";
                    if(isset($pegawai_lokal->KEDUDUKAN_HUKUM_ID) and $pegawai_lokal->KEDUDUKAN_HUKUM_ID == "") 
                        $data["KEDUDUKAN_HUKUM_ID"] =   isset($pegawai->kedudukanPnsId) ?   $pegawai->kedudukanPnsId :  "";
                    if(isset($pegawai_lokal->KODECEPAT) and $pegawai_lokal->KODECEPAT == "") 
                        $data["KODECEPAT"]  =   isset($pegawai->instansiKerjaKodeCepat) ?   $pegawai->instansiKerjaKodeCepat :  "";
                    if(isset($pegawai_lokal->KPKN_ID) and $pegawai_lokal->KPKN_ID == "") 
                        $data["KPKN_ID"]    =   isset($pegawai->kpknId) ?   $pegawai->kpknId :  "";
                    if(isset($pegawai_lokal->MK_BULAN) and $pegawai_lokal->MK_BULAN == "") 
                        $data["MK_BULAN"]   =   isset($pegawai->mkBulan)    ?   $pegawai->mkBulan :     "";
                    if(isset($pegawai_lokal->MK_TAHUN) and $pegawai_lokal->MK_TAHUN == "") 
                        $data["MK_TAHUN"]   =   isset($pegawai->mkTahun)    ?   $pegawai->mkTahun :     "";
                    if(isset($pegawai_lokal->NAMA) and $pegawai_lokal->NAMA == "") 
                        $data["NAMA"]   =   isset($pegawai->nama)   ?   $pegawai->nama :    "";
                    if(isset($pegawai_lokal->NIK) and $pegawai_lokal->NIK == "") 
                        $data["NIK"]    =   isset($pegawai->nik)    ?   $pegawai->nik :     "";
                    
                    if(isset($pegawai_lokal->NIP_LAMA) and $pegawai_lokal->NIP_LAMA == "") 
                        $data["NIP_LAMA"]   =   isset($pegawai->nipLama)    ?   $pegawai->nipLama :     "";
                    if(isset($pegawai_lokal->NO_ASKES) and $pegawai_lokal->NO_ASKES == "") 
                        $data["NO_ASKES"]   =   isset($pegawai->noAskes)    ?   $pegawai->noAskes :     "";
                    if(isset($pegawai_lokal->NO_BEBAS_NARKOBA) and $pegawai_lokal->NO_BEBAS_NARKOBA == "") 
                        $data["NO_BEBAS_NARKOBA"]   =   isset($pegawai->noSuratKeteranganBebasNarkoba)  ?   $pegawai->noSuratKeteranganBebasNarkoba :   "";
                    if(isset($pegawai_lokal->NO_CATATAN_POLISI) and $pegawai_lokal->NO_CATATAN_POLISI == "") 
                        $data["NO_CATATAN_POLISI"]  =   isset($pegawai->skck)   ?   $pegawai->skck :    "";
                    if(isset($pegawai_lokal->NO_SURAT_DOKTER) and $pegawai_lokal->NO_SURAT_DOKTER == "") 
                        $data["NO_SURAT_DOKTER"]    =   isset($pegawai->noSuratKeteranganDokter)    ?   $pegawai->noSuratKeteranganDokter :     "";
                    if(isset($pegawai_lokal->NO_TASPEN) and $pegawai_lokal->NO_TASPEN == "") 
                        $data["NO_TASPEN"]  =   isset($pegawai->noTaspen)   ?   $pegawai->noTaspen :    "";
                    if(isset($pegawai_lokal->NOMOR_HP) and $pegawai_lokal->NOMOR_HP == "") 
                        $data["NOMOR_HP"]   =   isset($pegawai->noHp)   ?   $pegawai->noHp :    "";
                    if(isset($pegawai_lokal->NOMOR_SK_CPNS) and $pegawai_lokal->NOMOR_SK_CPNS == "") 
                        $data["NOMOR_SK_CPNS"]  =   isset($pegawai->nomorSkCpns)    ?   $pegawai->nomorSkCpns :     "";
                    if(isset($pegawai_lokal->NPWP) and $pegawai_lokal->NPWP == "") 
                        $data["NPWP"]   =   isset($pegawai->noNpwp) ?   $pegawai->noNpwp :  "";
                    if(isset($pegawai_lokal->PENDIDIKAN) and $pegawai_lokal->PENDIDIKAN == "") 
                        $data["PENDIDIKAN"] =   isset($pegawai->pendidikanTerakhirId)   ?   $pegawai->pendidikanTerakhirId :    "";
                    
                    if(isset($pegawai_lokal->SATUAN_KERJA_INDUK_ID) and $pegawai_lokal->SATUAN_KERJA_INDUK_ID == "") 
                        $data["SATUAN_KERJA_INDUK_ID"]  =   isset($pegawai->satuanKerjaIndukId) ?   $pegawai->satuanKerjaIndukId :  "";
                    if(isset($pegawai_lokal->SATUAN_KERJA_INDUK_NAMA) and $pegawai_lokal->SATUAN_KERJA_INDUK_NAMA == "") 
                        $data["SATUAN_KERJA_INDUK_NAMA"]    =   isset($pegawai->satuanKerjaIndukNama)   ?   $pegawai->satuanKerjaIndukNama :    "";
                    if(isset($pegawai_lokal->SATUAN_KERJA_KERJA_ID) and $pegawai_lokal->SATUAN_KERJA_KERJA_ID == "") 
                        $data["SATUAN_KERJA_KERJA_ID"]  =   isset($pegawai->satuanKerjaKerjaId) ?   $pegawai->satuanKerjaKerjaId :  "";
                    if(isset($pegawai_lokal->SATUAN_KERJA_NAMA) and $pegawai_lokal->SATUAN_KERJA_NAMA == "") 
                        $data["SATUAN_KERJA_NAMA"]  =   isset($pegawai->satuanKerjaKerjaNama)   ?   $pegawai->satuanKerjaKerjaNama :    "";
                    if(isset($pegawai_lokal->STATUS_CPNS_PNS) and $pegawai_lokal->STATUS_CPNS_PNS == "") 
                        $data["STATUS_CPNS_PNS"]    =   isset($pegawai->statusPegawai)  ?   $pegawai->statusPegawai :   "";
                    if(isset($pegawai_lokal->STATUS_HIDUP) and $pegawai_lokal->STATUS_HIDUP == "") 
                        $data["STATUS_HIDUP"]   =   isset($pegawai->statusHidup)    ?   $pegawai->statusHidup :     "";
                    if(isset($pegawai_lokal->TEMPAT_LAHIR) and trim($pegawai_lokal->TEMPAT_LAHIR) == "") 
                        $data["TEMPAT_LAHIR"]   =   isset($pegawai->tempatLahir)    ?   $pegawai->tempatLahir :     "";
                    if(isset($pegawai_lokal->TEMPAT_LAHIR_ID) and trim($pegawai_lokal->TEMPAT_LAHIR_ID) == "") 
                        $data["TEMPAT_LAHIR_ID"]    =   isset($pegawai->tempatLahirId)  ?   $pegawai->tempatLahirId :   "";
                    if(isset($pegawai_lokal->TGL_BEBAS_NARKOBA) and $pegawai_lokal->TGL_BEBAS_NARKOBA == "") 
                        $data["TGL_BEBAS_NARKOBA"]  =   isset($pegawai->tglSuratKeteranganBebasNarkoba) ?   date('Y-m-d', strtotime($pegawai->tglSuratKeteranganBebasNarkoba)) :    "";
                    if(isset($pegawai_lokal->TGL_CATATAN_POLISI) and $pegawai_lokal->TGL_CATATAN_POLISI == "") 
                        $data["TGL_CATATAN_POLISI"] =   isset($pegawai->tglSkck)    ?   $pegawai->tglSkck :     "";
                    if(isset($pegawai_lokal->TGL_LAHIR) and $pegawai_lokal->TGL_LAHIR == "") 
                        $data["TGL_LAHIR"]  =   isset($pegawai->tglLahir)   ?   $pegawai->tglLahir :    "";
                    if(isset($pegawai_lokal->TGL_MENINGGAL) and $pegawai_lokal->TGL_MENINGGAL == "") 
                        $data["TGL_MENINGGAL"]  =   isset($pegawai->tglMeninggal)   ?   $pegawai->tglMeninggal :    "";
                    if(isset($pegawai_lokal->TGL_NPWP) and $pegawai_lokal->TGL_NPWP == "") 
                        $data["TGL_NPWP"]   =   isset($pegawai->tglNpwp)    ?   $pegawai->tglNpwp :     "";
                    if(isset($pegawai_lokal->TGL_SURAT_DOKTER) and $pegawai_lokal->TGL_SURAT_DOKTER == "") 
                        $data["TGL_SURAT_DOKTER"]   =   isset($pegawai->tglSuratKeteranganDokter)   ?   $pegawai->tglSuratKeteranganDokter :    "";
                    if(isset($pegawai_lokal->TK_PENDIDIKAN) and $pegawai_lokal->TK_PENDIDIKAN == "") 
                        $data["TK_PENDIDIKAN"]  =   isset($pegawai->tkPendidikanTerakhirId) ?   $pegawai->tkPendidikanTerakhirId :  "";
                    if(isset($pegawai_lokal->TMT_CPNS) and $pegawai_lokal->TMT_CPNS == "") 
                        $data["TMT_CPNS"]   =   isset($pegawai->tmtCpns)    ?   $pegawai->tmtCpns :     "";
                    if(isset($pegawai_lokal->TGL_SK_CPNS) and $pegawai_lokal->TGL_SK_CPNS == "") 
                        $data["TGL_SK_CPNS"]    =   isset($pegawai->tglSkCpns)  ?   $pegawai->tglSkCpns :   "";
                    if(isset($pegawai_lokal->TMT_GOLONGAN) and $pegawai_lokal->TMT_GOLONGAN == "") 
                        $data["TMT_GOLONGAN"]   =   isset($pegawai->tmtGolAkhir)    ?   $pegawai->tmtGolAkhir :     "";
                    if(isset($pegawai_lokal->TMT_JABATAN) and $pegawai_lokal->TMT_JABATAN == "") 
                        $data["TMT_JABATAN"]    =   isset($pegawai->tmtJabatan) ?   $pegawai->tmtJabatan :  "";
                    if(isset($pegawai_lokal->LOKASI_KERJA_ID) and $pegawai_lokal->LOKASI_KERJA_ID == "") 
                        $data["LOKASI_KERJA_ID"]    =   isset($pegawai->lokasiKerjaId)  ?   $pegawai->lokasiKerjaId :   "";
                    //if(isset($pegawai_lokal->UNOR_ID) and $pegawai_lokal->UNOR_ID == "") 
                    //  $data["UNOR_ID"]    =   isset($pegawai->tmtPns) ?   $pegawai->tmtPns :  "";
                    if(isset($pegawai_lokal->UNOR_ID) and $pegawai_lokal->UNOR_ID == "") 
                        $data["UNOR_ID"]    =   isset($pegawai->unorId) ?   $pegawai->unorId :  "";
                    if(isset($pegawai_lokal->UNOR_INDUK_ID) and $pegawai_lokal->UNOR_INDUK_ID == "") 
                        $data["UNOR_INDUK_ID"]  =   isset($pegawai->unorIndukId)    ?   $pegawai->unorIndukId :     "";

                    if(isset($pegawai_lokal->NIP_BARU)){
                        if(isset($pegawai->id) and $pegawai->id != "") {
                            if($this->pegawai_model->update_where("NIP_BARU",$pegawai_lokal->NIP_BARU, $data))
                            {
                                $hasil = true;
                                $msg =  "Berhasil update data";
                            }   
                        }else{
                            $hasil = false;
                            $msg =  "Data BKN tidak didapatkan..";
                        }
                        
                    }else{
                        if($this->pegawai_model->insert($data)){
                            {
                                echo "Berhasil Tambah data";
                            }
                        }
                    }
                }else{
                    $data["PNS_ID"] =   isset($pegawai->id) ?   $pegawai->id :  "";
                    $data["NIP_BARU"]   =   isset($pegawai->nipBaru)    ?   $pegawai->nipBaru :     "";
                    if($pegawai->agamaId != "")
                        $data["AGAMA_ID"]   =   isset($pegawai->agamaId)    ? (int)$pegawai->agamaId :  "";
                    if($pegawai->akteKelahiran != "")
                        $data["AKTE_KELAHIRAN"] =   isset($pegawai->akteKelahiran)  ?   $pegawai->akteKelahiran :   "";
                    if($pegawai->akteMeninggal != "")
                        $data["AKTE_MENINGGAL"] =   isset($pegawai->akteMeninggal)  ?   $pegawai->akteMeninggal :   "";
                    $data["ALAMAT"] =   isset($pegawai->alamat) ?   $pegawai->alamat :  "";
                    $data["BPJS"]   =   isset($pegawai->bpjs)   ?   $pegawai->bpjs :    "";
                    $data["EMAIL"]  =   isset($pegawai->email)  ?   $pegawai->email :   "";
                    $data["GELAR_BELAKANG"] =   isset($pegawai->gelarBelakang)  ?   $pegawai->gelarBelakang :   "";

                    $data["GELAR_DEPAN"]    =   isset($pegawai->gelarDepan) ?   $pegawai->gelarDepan :  "";
                    $data["GOL_AWAL_ID"]    =   isset($pegawai->golRuangAwalId) ?   $pegawai->golRuangAwalId :  "";
                    if($pegawai->golRuangAwal != "")
                        $data["GOL_ID"] =   isset($pegawai->golRuangAwal)   ?   (int)$pegawai->golRuangAwal :   "";
                    if($pegawai->instansiIndukId != "")
                        $data["INSTANSI_INDUK_ID"]  =   isset($pegawai->instansiIndukId)    ?   $pegawai->instansiIndukId :     "";
                    if($pegawai->instansiIndukNama != "")
                        $data["INSTANSI_INDUK_NAMA"]    =   isset($pegawai->instansiIndukNama)  ?   $pegawai->instansiIndukNama :   "";
                    if($pegawai->instansiKerjaId != "")
                        $data["INSTANSI_KERJA_ID"]  =   isset($pegawai->instansiKerjaId)    ?   $pegawai->instansiKerjaId :     "";
                    $data["INSTANSI_KERJA_NAMA"]    =   isset($pegawai->instansiKerjaNama)  ?   $pegawai->instansiKerjaNama :   "";
                    $data["JABATAN_ID"] =   isset($pegawai->jabatanStrukturalId)    ?   $pegawai->jabatanStrukturalId :     "";
                
                    $data["JENIS_JABATAN_ID"]   =   isset($pegawai->jenisJabatanId) ?   $pegawai->jenisJabatanId :  "";
                    $data["JENIS_JABATAN_NAMA"] =   isset($pegawai->jenisJabatan)   ?   $pegawai->jenisJabatan :    "";
                    $data["JENIS_KAWIN_ID"] =   isset($pegawai->jenisKawinId)   ?   $pegawai->jenisKawinId :    "";
                    $jenis_kelamin = isset($pegawai->jenisKelamin)  ?   $pegawai->jenisKelamin :    "";

                        if($jenis_kelamin == "Pria")
                            $jk = "M";
                        if($jenis_kelamin == "Wanita")
                            $jk = "F";
                    
                    $data["JENIS_KELAMIN"]  =   $jk;
                    $data["JENIS_PEGAWAI_ID"]   =   isset($pegawai->jenisPegawaiId) ?   $pegawai->jenisPegawaiId :  "";
                    $data["JML_ANAK"]   =   isset($pegawai->jumlahAnak) ?   $pegawai->jumlahAnak :  "";
                    $data["JML_ISTRI"]  =   isset($pegawai->jumlahIstriSuami)   ?   $pegawai->jumlahIstriSuami :    "";
                    $data["KARTU_PEGAWAI"]  =   isset($pegawai->noSeriKarpeg)   ?   $pegawai->noSeriKarpeg :    "";
                    $data["KEDUDUKAN_HUKUM_ID"] =   isset($pegawai->kedudukanPnsId) ?   $pegawai->kedudukanPnsId :  "";
                    $data["KODECEPAT"]  =   isset($pegawai->instansiKerjaKodeCepat) ?   $pegawai->instansiKerjaKodeCepat :  "";
                    $data["KPKN_ID"]    =   isset($pegawai->kpknId) ?   $pegawai->kpknId :  "";
                    $data["MK_BULAN"]   =   isset($pegawai->mkBulan)    ?   $pegawai->mkBulan :     "";
                    $data["MK_TAHUN"]   =   isset($pegawai->mkTahun)    ?   $pegawai->mkTahun :     "";
                    $data["NAMA"]   =   isset($pegawai->nama)   ?   $pegawai->nama :    "";
                    $data["NIK"]    =   isset($pegawai->nik)    ?   $pegawai->nik :     "";
                    $data["NIP_LAMA"]   =   isset($pegawai->nipLama)    ?   $pegawai->nipLama :     "";
                    $data["NO_ASKES"]   =   isset($pegawai->noAskes)    ?   $pegawai->noAskes :     "";
                    $data["NO_BEBAS_NARKOBA"]   =   isset($pegawai->noSuratKeteranganBebasNarkoba)  ?   $pegawai->noSuratKeteranganBebasNarkoba :   "";
                    $data["NO_CATATAN_POLISI"]  =   isset($pegawai->skck)   ?   $pegawai->skck :    "";
                    $data["NO_SURAT_DOKTER"]    =   isset($pegawai->noSuratKeteranganDokter)    ?   $pegawai->noSuratKeteranganDokter :     "";
                    $data["NO_TASPEN"]  =   isset($pegawai->noTaspen)   ?   $pegawai->noTaspen :    "";
                    $data["NOMOR_HP"]   =   isset($pegawai->noHp)   ?   $pegawai->noHp :    "";
                    $data["NOMOR_SK_CPNS"]  =   isset($pegawai->nomorSkCpns)    ?   $pegawai->nomorSkCpns :     "";
                    $data["NPWP"]   =   isset($pegawai->noNpwp) ?   $pegawai->noNpwp :  "";
                    $data["PENDIDIKAN"] =   isset($pegawai->pendidikanTerakhirId)   ?   $pegawai->pendidikanTerakhirId :    "";
                    if($pegawai->satuanKerjaIndukId != "")
                        $data["SATUAN_KERJA_INDUK_ID"]  =   isset($pegawai->satuanKerjaIndukId) ?   $pegawai->satuanKerjaIndukId :  "";
                    if($pegawai->satuanKerjaIndukNama != "")
                        $data["SATUAN_KERJA_INDUK_NAMA"]    =   isset($pegawai->satuanKerjaIndukNama)   ?   $pegawai->satuanKerjaIndukNama :    "";
                    if($pegawai->satuanKerjaKerjaId != "")
                        $data["SATUAN_KERJA_KERJA_ID"]  =   isset($pegawai->satuanKerjaKerjaId) ?   $pegawai->satuanKerjaKerjaId :  "";
                    if($pegawai->satuanKerjaKerjaNama != "")
                        $data["SATUAN_KERJA_NAMA"]  =   isset($pegawai->satuanKerjaKerjaNama)   ?   $pegawai->satuanKerjaKerjaNama :    "";
                    if($pegawai->statusPegawai != "")
                        $data["STATUS_CPNS_PNS"]    =   isset($pegawai->statusPegawai)  ?   $pegawai->statusPegawai :   "";
                    if($pegawai->statusHidup != "")
                        $data["STATUS_HIDUP"]   =   isset($pegawai->statusHidup)    ?   $pegawai->statusHidup :     "";
                    if($pegawai->tempatLahir != "")
                        $data["TEMPAT_LAHIR"]   =   isset($pegawai->tempatLahir)    ?   $pegawai->tempatLahir :     "";
                    if($pegawai->tempatLahirId != "")
                        $data["TEMPAT_LAHIR_ID"]    =   isset($pegawai->tempatLahirId)  ?   $pegawai->tempatLahirId :   "";
                    if($pegawai->tglSuratKeteranganBebasNarkoba != "")
                        $data["TGL_BEBAS_NARKOBA"]  =   isset($pegawai->tglSuratKeteranganBebasNarkoba) ?   date('Y-m-d', strtotime($pegawai->tglSuratKeteranganBebasNarkoba)) :    "";
                    if($pegawai->tglSkck != "")
                        $data["TGL_CATATAN_POLISI"] =   isset($pegawai->tglSkck)    ?   date('Y-m-d', strtotime($pegawai->tglSkck)) :   "";
                    if($pegawai->tglLahir != "")
                        $data["TGL_LAHIR"]  =   isset($pegawai->tglLahir)   ?   date('Y-m-d', strtotime($pegawai->tglLahir)) :  "";
                    if($pegawai->tglMeninggal != "")
                        $data["TGL_MENINGGAL"]  =   isset($pegawai->tglMeninggal)   ?   date('Y-m-d', strtotime($pegawai->tglMeninggal)) :  "";
                    if($pegawai->tglNpwp != "")
                        $data["TGL_NPWP"]   =   isset($pegawai->tglNpwp)    ?   date('Y-m-d', strtotime($pegawai->tglNpwp)) :   "";
                    if($pegawai->tglSuratKeteranganDokter != "")
                        $data["TGL_SURAT_DOKTER"]   =   isset($pegawai->tglSuratKeteranganDokter)   ?   date('Y-m-d', strtotime($pegawai->tglSuratKeteranganDokter)) :  "";
                    if($pegawai->tkPendidikanTerakhirId != "")
                    $data["TK_PENDIDIKAN"]  =   isset($pegawai->tkPendidikanTerakhirId) ?   $pegawai->tkPendidikanTerakhirId :  "";
                    if($pegawai->tmtPns != "")
                        $data["TMT_CPNS"]   =   isset($pegawai->tmtPns) ?   date('Y-m-d', strtotime($pegawai->tmtPns)) :    "";
                    if($pegawai->tmtCpns != "")
                        $data["TMT_CPNS"]   =   isset($pegawai->tmtCpns)    ?   date('Y-m-d', strtotime($pegawai->tmtCpns)) :   "";
                    $data["TGL_SK_CPNS"]    =   isset($pegawai->tglSkCpns)  ?   date('Y-m-d', strtotime($pegawai->tglSkCpns)) :     "";
                        $data["GOL_ID"] =   isset($pegawai->golRuangAkhirId)    ?   $pegawai->golRuangAkhirId :     "";
                    $data["TMT_GOLONGAN"]   =   isset($pegawai->tmtGolAkhir)    ?   date('Y-m-d', strtotime($pegawai->tmtGolAkhir)) :   "";
                    $data["TMT_JABATAN"]    =   isset($pegawai->tmtJabatan) ?   date('Y-m-d', strtotime($pegawai->tmtJabatan)) :    "";
                    $data["LOKASI_KERJA_ID"]    =   isset($pegawai->lokasiKerjaId)  ?   $pegawai->lokasiKerjaId :   "";
                    $data["UNOR_ID"]    =   isset($pegawai->unorId) ?   $pegawai->unorId :  "";
                    $data["UNOR_INDUK_ID"]  =   isset($pegawai->unorIndukId)    ?   $pegawai->unorIndukId :     "";

                    if($pegawai->jenisJabatanId != "")
                        $data["JENIS_JABATAN_IDx"]  =   isset($pegawai->jenisJabatanId) ?   $pegawai->jenisJabatanId :  "";

                    $data["status_pegawai"] =   1;

                    if($id = $this->pegawai_model->insert($data))
                    {
                        $hasil = true;
                        $msg =  "Berhasil Tambah data ".$id;
                        log_activity($this->auth->user_id(), $msg . ' : ' . $this->input->ip_address(), 'pegawai');
                    }
                }
                // data riwayat golongan
                $datajsonrwt_golongan = $this->getdata_riwayat_golongan_bkn($nip);

                foreach($datajsonrwt_golongan as $row)
                {
                    $idPns      = $row->idPns;
                    $golonganId = $row->golonganId;
                    $golongan   = $row->golongan;
                    $skNomor    = TRIM($row->skNomor);
                    $skTanggal  = $row->skTanggal;
                    $tmtGolongan    = $row->tmtGolongan;
                    $noPertekBkn    = $row->noPertekBkn;
                    $tglPertekBkn   = $row->tglPertekBkn;
                    $this->riwayat_kepangkatan_model->where("SK_NOMOR",$skNomor);  
                    $count_golongan = $this->riwayat_kepangkatan_model->count_all($idPns);
                    if($count_golongan <= 0){
                        $data = array();
                        $NIP_BARU = $pegawai_lokal->NIP_BARU;
                        $data["ID_BKN"] = $row->id;
                        $data["PNS_NIP"] = $pegawai_lokal->NIP_BARU;
                        $data["PNS_ID"] = $pegawai_lokal->PNS_ID;
                        $data["PNS_NAMA"] = $pegawai_lokal->NAMA;
                        $data["JENIS_KP"] = $row->jenisKPNama;
                        $data["ID_GOLONGAN"] = $row->golonganId;
                        $data["SK_NOMOR"] = $row->skNomor;
                        $data["NOMOR_BKN"] = $row->noPertekBkn;
                        $data["MK_GOLONGAN_TAHUN"] = $row->masaKerjaGolonganTahun;
                        $data["MK_GOLONGAN_BULAN"] = $row->masaKerjaGolonganBulan;

                        $data["KODE_JENIS_KP"] = $row->jenisKPId;
                        $data["GOLONGAN"] = $row->golongan;
                        $data["PANGKAT"] = isset($agolongan[$row->golonganId]) ? $agolongan[$row->golonganId] : "";
                        
                        $data["TMT_GOLONGAN"]   = date("Y-m-d", strtotime($row->tmtGolongan));
                        $data["SK_TANGGAL"]     = date("Y-m-d", strtotime($row->skTanggal));
                        if(empty($data["TMT_GOLONGAN"])){
                            unset($data["TMT_GOLONGAN"]);
                        }
                        if(empty($data["SK_TANGGAL"])){
                            unset($data["SK_TANGGAL"]);
                        }
                        $insert_id = $this->riwayat_kepangkatan_model->insert($data);
                        log_activity_pegawai($NIP_BARU,$this->auth->user_id(),"Tambah data rwt_golongan", 'pegawai');
                    }

                }
                // end history golongan
            }
    }
}

	
