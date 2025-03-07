<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Bkn extends Admin_Controller
{
	protected $permissionCreate         = 'Pegawai.Kepegawaian.Create';
	protected $permissionViewPpnpn      = 'Pegawai.Kepegawaian.CreatePpnpn';

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
	protected $permissionSynJabatan   		= 'Riwayatjabatan.SyncBkn.View';
	protected $permissionSynKepangkatan   	= 'RiwayatKepangkatan.SyncBkn.View';
	protected $permissionSynPendidikan   	= 'RiwayatPendidikan.SyncBkn.View';
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
		$this->load->model('pegawai/riwayat_cltn_model');
		$this->lang->load('pegawai');
		$this->load->model('pegawai/pendidikan_model');
		$this->load->model('pegawai/unitkerja_model');
		$this->load->model('ref_jabatan/jabatan_model');
		$this->load->model('pegawai/riwayat_penghargaan_model');
		$this->load->model('pegawai/istri_model');
		$this->load->model('pegawai/orang_tua_model');
		$this->load->model('pegawai/anak_model');
		$this->load->model('pegawai/riwayat_angka_kredit_model');
		$this->load->model('pegawai/riwayat_huk_dis_model');
		$this->load->model('pegawai/riwayat_kursus_model');
		$this->load->model('pegawai/riwayat_kinerja_model');
		$this->load->helper('aplikasi');
		Assets::add_css('flick/jquery-ui-1.8.13.custom.css');
		Assets::add_js('jquery-ui-1.8.13.min.js');
		$this->form_validation->set_error_delimiters("<span class='has-error'>", "</span>");
		Template::set_block('sub_nav', 'kepegawaian/_sub_nav');
		Assets::add_module_js('pegawai', 'pegawai.js');
		$this->load->library('Api_s3_aws');
		$this->load->library('Api_bkn');
		$this->load->model('arsip_digital/arsip_digital_model');
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

	private function mapSKPToSIASN($rawskp){
		$skp = array(
			"atasanPejabatPenilai" => $rawskp['a_pns_id_atasan'], 
			"atasanPenilaiGolongan" => strval($rawskp['golongan_atasan_atasan']), 
			"atasanPenilaiJabatan" => $rawskp['ATASAN_ATASAN_LANGSUNG_PNS_JABATAN'], 
			"atasanPenilaiNama" => $rawskp['ATASAN_ATASAN_LANGSUNG_PNS_NAMA'], 
			"atasanPenilaiNipNrp" => $rawskp['ATASAN_ATASAN_LANGSUNG_PNS_NIP'], 
			"atasanPenilaiTmtGolongan" => date("d-m-Y", strtotime($rawskp['tmt_golongan_atasan_atasan'])),
			"atasanPenilaiUnorNama" => $rawskp['nama_unor_atasan_atasan'], 
			"disiplin" => floatval($rawskp['PERILAKU_DISIPLIN'])  == null ? floatval(1) : floatval($rawskp['PERILAKU_DISIPLIN']), 
			"id" => null, 
			"inisiatifKerja" => floatval($rawskp['PERILAKU_INISIATIF_KERJA']) == null ? floatval(1) : floatval($rawskp['PERILAKU_INISIATIF_KERJA']), 
			"integritas" => floatval($rawskp['PERILAKU_INTEGRITAS']) == null ? floatval(1) : floatval($rawskp['PERILAKU_INTEGRITAS']), 
			"jenisJabatan" => strval($rawskp['JABATAN_TIPE']), 
			"jenisPeraturanKinerjaKd" => strval($rawskp['PERATURAN']), 
			"jumlah" => floatval($rawskp['NILAI_SKP']), 
			"kepemimpinan" => floatval($rawskp['PERILAKU_KEPEMIMPINAN']), 
			"kerjasama" => floatval($rawskp['PERILAKU_KERJASAMA']), 
			"komitmen" => floatval($rawskp['PERILAKU_KOMITMEN']), 
			"konversiNilai" => floatval($rawskp['NILAI_SKP']), 
			"nilaiIntegrasi" => floatval($rawskp['NILAI_INTEGRASI']) == null ? flaotval(1) : floatval($rawskp['NILAI_SKP']), 
			"nilaiPerilakuKerja" => floatval($rawskp['NILAI_PERILAKU']), 
			"nilaiPrestasiKerja" => floatval($rawskp['NILAI_SKP']), 
			"nilaiSkp" => floatval($rawskp['NILAI_SKP']), 
			"nilairatarata" => floatval($rawskp['NILAI_SKP']), 
			"orientasiPelayanan" => floatval($rawskp['PERILAKU_ORIENTASI_PELAYANAN']), 
			"pejabatPenilai" => $rawskp['nip_atasan'], 
			"penilaiGolongan" => strval($rawskp['golongan_atasan']), 
			"penilaiJabatan" => $rawskp['ATASAN_LANGSUNG_PNS_JABATAN'], 
			"penilaiNama" => $rawskp['ATASAN_LANGSUNG_PNS_NAMA'], 
			"penilaiNipNrp" => $rawskp['ATASAN_LANGSUNG_PNS_NIP'], 
			"penilaiTmtGolongan" => date("d-m-Y", strtotime($rawskp['tmt_golongan_atasan'])), 
			"penilaiUnorNama" => $rawskp['nama_unor_atasan'], 
			"pnsDinilaiOrang" => $rawskp['PNS_ID'], 
			"statusAtasanPenilai" => "ASN", 
			"statusPenilai" => "ASN", 
			"tahun" => 2021 ,
			
		); 
	
		return $skp;
	}

	public function sync_skp_2021(){
		$this->load->library('Api_bkn');
		$this->load->model('pegawai/Vw_skp_model', null, true);
		$nip = $this->input->post('nip');

		//$result = $this->Vw_skp_model->find_by_nip_and_tahun($nip,2021);
		$result = $this->Vw_skp_model->find_skp_2021($nip);
		$message = array();
		$api_bkn = new Api_bkn;

		if(sizeof($result)!=2){
			array_push($message,array("peraturan"=>"","response"=>array("message"=>"pastikan terdapat 2 skp 2021")));
		}else{
			$average = (($result[0]->NILAI_SKP)+($result[1]->NILAI_SKP))/2;
			foreach ($result as $key => $value) {
				$arrObj = ((array)$value);
				$arrObj['NILAI_INTEGRASI'] = $average;
				$param = $this->mapSKPToSIASN($arrObj);
				$resp = $api_bkn->postDataSKP2021($param);
				array_push($message,array("peraturan"=>$arrObj['PERATURAN'],"response"=>$resp));	
			}

		}

		echo json_encode($message);
	}

	public function profile_bkn($pns_id = '')
	{
		$this->load->model('pegawai/Pns_aktif_model');
		$this->load->model('pegawai/Riwayat_pns_cpns_model');
		$model = $this->uri->segment(6);
		$this->load->library('convert');
		Template::set('collapse', true);
		$pegawai = $this->pegawai_model->find_by("PNS_ID", $pns_id);
		$id = isset($pegawai->ID) ? $pegawai->ID : "";
		$nip_baru = isset($pegawai->NIP_BARU) ? $pegawai->NIP_BARU : "";

		$recpns_aktif = $this->Pns_aktif_model->find($id);
		Template::set('recpns_aktif', $recpns_aktif);

		$pegawai = $this->pegawai_model->find_detil($id);

		$foto_pegawai =  END_POINT_PHOTO . $pegawai->username_intra;

		$data_utama_pegawai = $this->cache->get('data_utama_' . $nip_baru);
		$data_golongan = $this->cache->get('golongan_' . $nip_baru);
		$data_pasangan = $this->cache->get('data_pasangan_' . $nip_baru);
		$data_penghargaan = $this->cache->get('data_penghargaan_' . $nip_baru);
		$data_skp = $this->cache->get('data_rwt_skp_' . $nip_baru);
		$data_pendidikan = $this->cache->get('rwt_pendidikan_' . $nip_baru);
		$data_jabatan = $this->cache->get('rwt_jabatan_' . $nip_baru);
		$data_diklat = $this->cache->get('rwt_diklat_' . $nip_baru);
		$data_masa_kerja = $this->cache->get('rwt_masakerja_' . $nip_baru);
		$data_pwk = $this->cache->get('rwt_pwk_' . $nip_baru);
		$data_ppo_hist = $this->cache->get('ppo_sk_hist_' . $nip_baru);
		$data_kpo_sk_hist = $this->cache->get('data_kpo_sk_hist_' . $nip_baru);
		$data_hukdis = $this->cache->get('data_rwt_hukdis_' . $nip_baru);
		$data_pns_unor = $this->cache->get('data_rwt_pnsunor_' . $nip_baru);
		$data_anak = $this->cache->get('data_anak_' . $nip_baru);
		$data_ortu = $this->cache->get('data_ortu_' . $nip_baru);
		$data_dp3 = $this->cache->get('data_dp3_' . $nip_baru);

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


		Template::set('selectedPendidikanID', $this->pendidikan_model->find(trim($pegawai->PENDIDIKAN_ID)));
		$recgolongan = $this->golongan_model->find(trim($pegawai->GOL_ID));
		Template::set('GOLONGAN_AKHIR', $recgolongan->NAMA);
		Template::set('NAMA_PANGKAT', $recgolongan->NAMA_PANGKAT);
		if ($pegawai->JABATAN_INSTANSI_REAL_ID != "") {
			$recjabatan = $this->jabatan_model->find_by("KODE_JABATAN", TRIM($pegawai->JABATAN_INSTANSI_REAL_ID));
			Template::set('NAMA_JABATAN_REAL', $recjabatan->NAMA_JABATAN);
		}
		Template::set('selectedTempatLahirPegawai', $this->lokasi_model->find($pegawai->TEMPAT_LAHIR_ID));

		if (trim($pegawai->UNOR_ID) != "") {
			$unor = $this->unitkerja_model->find_by("ID", trim($pegawai->UNOR_ID));
			$pemimpin_pns_id = isset($unor->PEMIMPIN_PNS_ID) ? $unor->PEMIMPIN_PNS_ID : "";
			$nama_jabatan_struktural = isset($unor->NAMA_JABATAN) ? $unor->NAMA_JABATAN : "";
			if ($pemimpin_pns_id == $pegawai->PNS_ID or $pegawai->JENIS_JABATAN_ID == "1") {
				Template::set('NAMA_JABATAN_REAL', $nama_jabatan_struktural);
				Template::set('JENIS_JABATAN', "Struktural");
			}
			Template::set('NAMA_UNOR', $unor->NAMA_UNOR);
		}
		$recgolongan = null;
		if ($pegawai->GOL_AWAL_ID != "")
			$recgolongan = $this->golongan_model->find(trim($pegawai->GOL_AWAL_ID));
		Template::set('GOLONGAN_AWAL', isset($recgolongan->NAMA) ? $recgolongan->NAMA : "");


		Template::set('foto_pegawai', $foto_pegawai);
		Template::set('pegawai', $pegawai);
		Template::set('PNS_ID', $pegawai->PNS_ID);
		Template::set_view('bkn/profile_bkn');

		Template::render();
	}
	public function viewpegawaibkn()
	{
		$nip_baru 	= $this->input->post('kode');
		// $nip_baru = '198503012010121001';
		// $pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU",$nip_baru);
		$data_utama_pegawai = $this->cache->get('data_utama_' . $nip_baru);
		if ($data_utama_pegawai != null) {
			$response['success'] = true;
			$response['id'] = $data_utama_pegawai->id;
			$response['msg'] = "Selesai ambil data BKN";
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
		if (isset($data_utama_pegawai->id) and $data_utama_pegawai->id != "") {
			$result = true;
			$id = $data_utama_pegawai->id;
		}
		if ($result) {
			$response['success'] = true;
			$response['id'] = $id;
			$response['msg'] = "Selesai ambil data BKN";
		} else {
			$response['success'] = false;
			$response['id'] = $id;
			$response['msg'] = "Ada kesalahan, data BKN tidak berhasil didapatkan";
		}
		echo json_encode($response);
	}
	public function viewpendidikan()
	{
		$this->auth->restrict($this->permissionSynPendidikan);
		$kode 	= $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("PNS_ID", $kode);
		$this->load->library('Api_bkn');
		$api_bkn = new Api_bkn;
		$data_pendidikan = $this->getdata_rwt_pendidikan(trim($pegawai_lokal->NIP_BARU));

		$result = false;
		$id = "";
		if ($data_pendidikan != null) {
			$result = true;
		}
		if ($result) {
			$response['success'] = true;
			$response['nip'] = trim($pegawai_lokal->NIP_BARU);
			$response['msg'] = "Data ditemukan";
			$output = $this->load->view('bkn/pendidikan', array('data_pendidikan' => $data_pendidikan, "nipBaru" => trim($pegawai_lokal->NIP_BARU)), true);
			$response['konten'] = $output;
			//$response['output'] = $data_pendidikan;
		} else {
			$response['success'] = false;
			$response['nip'] = trim($pegawai_lokal->NIP_BARU);
			$response['msg'] = "Data tidak ada";
			$response['konten'] = "";
		}
		echo json_encode($response);
	}
	public function viewkepangkatan()
	{
		$this->auth->restrict($this->permissionSynKepangkatan);
		$kode 	= $this->input->post('kode');
		$golongan_id	= $this->input->post('golongan_id');
		$pegawai_lokal = $this->pegawai_model->find_by("PNS_ID", $kode);
		$apangakt = $this->genPangkat();
		$this->load->library('Api_bkn');
		$api_bkn = new Api_bkn;
		$data_golongan = $this->getdata_riwayat_golongan_bkn(trim($pegawai_lokal->NIP_BARU));
		$result = false;
		$id = "";
		if ($data_golongan != null) {
			$result = true;
		}
		if ($result) {
			$response['success'] = true;
			$response['nip'] = trim($pegawai_lokal->NIP_BARU);
			$response['msg'] = "Data ditemukan";
			$output = $this->load->view('bkn/kepangkatan', array('data_golongan' => $data_golongan,'apangakt' => $apangakt, "nipBaru" => trim($pegawai_lokal->NIP_BARU), "golongan_id" => $golongan_id), true);
			$response['konten'] = $output;
		} else {
			$response['success'] = false;
			$response['nip'] = trim($pegawai_lokal->NIP_BARU);
			$response['msg'] = "Data tidak ada";
			$response['konten'] = "";
		}
		echo json_encode($response);
	}
	public function viewcltn()
	{
		$kode   = $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("PNS_ID", $kode);
		$this->load->library('Api_bkn');
		$api_bkn = new Api_bkn;
		$data_cltn = $this->getdata_riwayat_cltn(trim($pegawai_lokal->NIP_BARU));
		$result = false;
		$id = "";
		if ($data_cltn != null) {
			$result = true;
		}
		if ($result) {
			$response['success'] = true;
			$response['nip'] = trim($pegawai_lokal->NIP_BARU);
			$response['msg'] = "Data ditemukan";
			$output = $this->load->view('bkn/cltn', array('data_cltn' => $data_cltn, "nipBaru" => trim($pegawai_lokal->NIP_BARU)), true);
			$response['konten'] = $output;
		} else {
			$response['success'] = false;
			$response['nip'] = trim($pegawai_lokal->NIP_BARU);
			$response['msg'] = "Data tidak ada";
			$response['konten'] = "";
		}
		echo json_encode($response);
	}
	public function viewjabatan()
	{
		$this->auth->restrict($this->permissionSynJabatan);
		$kode 	= $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("PNS_ID", $kode);
		$this->load->library('Api_bkn');
		$api_bkn = new Api_bkn;
		$data_jabatan = $this->getdata_rwt_jabatan(trim($pegawai_lokal->NIP_BARU));

		$result = false;
		$id = "";
		if ($data_jabatan != null) {
			$result = true;
		}
		if ($result) {
			$index = 0;
			$tmt_selesai_jabatan = array();
			foreach ($data_jabatan as $row) {
				$tmt_selesai_jabatan[$index] = $row->tmtJabatan;
				$index++;
			}
			$response['success'] = true;
			$response['nip'] = trim($pegawai_lokal->NIP_BARU);
			$response['msg'] = "Data ditemukan";
			$output = $this->load->view('bkn/jabatan', array('data_jabatan' => $data_jabatan, "nipBaru" => trim($pegawai_lokal->NIP_BARU), "tmt_selesai_jabatan" => $tmt_selesai_jabatan), true);
			$response['konten'] = $output;
		} else {
			$response['success'] = false;
			$response['nip'] = trim($pegawai_lokal->NIP_BARU);
			$response['msg'] = "Data tidak ada";
			$response['konten'] = "";
		}
		echo json_encode($response);
	}
	public function viewdiklat()
	{
		$kode 	= $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("PNS_ID", $kode);
		$this->load->library('Api_bkn');
		$api_bkn = new Api_bkn;
		$data_diklat = $this->getdata_rwt_diklat(trim($pegawai_lokal->NIP_BARU));

		$result = false;
		$id = "";
		if ($data_diklat != null) {
			$result = true;
		}
		if ($result) {
			$response['success'] = true;
			$response['nip'] = trim($pegawai_lokal->NIP_BARU);
			$response['msg'] = "Data ditemukan";
			$output = $this->load->view('bkn/diklatstruktural', array('data_diklat' => $data_diklat, "nipBaru" => trim($pegawai_lokal->NIP_BARU)), true);
			$response['konten'] = $output;
		} else {
			$response['success'] = false;
			$response['nip'] = trim($pegawai_lokal->NIP_BARU);
			$response['msg'] = "Data tidak ada";
			$response['konten'] = "";
		}
		echo json_encode($response);
	}
	public function viewpenghargaan()
	{
		$kode 	= $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("PNS_ID", $kode);
		$this->load->library('Api_bkn');
		$api_bkn = new Api_bkn;
		$data_penghargaan = $this->getdata_penghargaan(trim($pegawai_lokal->NIP_BARU));
		$result = false;
		$id = "";
		if ($data_penghargaan != null) {
			$result = true;
		}
		if ($result) {
			$response['success'] = true;
			$response['nip'] = trim($pegawai_lokal->NIP_BARU);
			$response['msg'] = "Data ditemukan";
			$output = $this->load->view('bkn/penghargaan', array('data_penghargaan' => $data_penghargaan, "nipBaru" => trim($pegawai_lokal->NIP_BARU)), true);
			$response['konten'] = $output;
		} else {
			$response['success'] = false;
			$response['nip'] = trim($pegawai_lokal->NIP_BARU);
			$response['msg'] = "Data tidak ada";
			$response['konten'] = "";
		}
		echo json_encode($response);
	}
	public function viewortu()
	{
		$kode 	= $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("PNS_ID", $kode);
		$this->load->library('Api_bkn');
		$api_bkn = new Api_bkn;
		$data_ortu = $this->getdata_ortu(trim($pegawai_lokal->NIP_BARU));
		$result = false;
		$id = "";
		if ($data_ortu != null) {
			$result = true;
		}
		if ($result) {
			$response['success'] = true;
			$response['nip'] = trim($pegawai_lokal->NIP_BARU);
			$response['msg'] = "Data ditemukan";
			$output = $this->load->view('bkn/ortu', array('data_ortu' => $data_ortu, "nipBaru" => trim($pegawai_lokal->NIP_BARU)), true);
			$response['konten'] = $output;
		} else {
			$response['success'] = false;
			$response['nip'] = trim($pegawai_lokal->NIP_BARU);
			$response['msg'] = "Data tidak ada";
			$response['konten'] = "";
		}
		echo json_encode($response);
	}
	public function viewpasangan()
	{
		$kode 	= $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("PNS_ID", $kode);
		$this->load->library('Api_bkn');
		$api_bkn = new Api_bkn;
		$data_pasangan = $this->getdata_pasangan(trim($pegawai_lokal->NIP_BARU));
		$result = false;
		$id = "";
		if ($data_pasangan != null) {
			$result = true;
		}
		if ($result) {
			$response['success'] = true;
			$response['nip'] = trim($pegawai_lokal->NIP_BARU);
			$response['msg'] = "Data ditemukan";
			$output = $this->load->view('bkn/pasangan', array('data_pasangan' => $data_pasangan, "nipBaru" => trim($pegawai_lokal->NIP_BARU)), true);
			$response['konten'] = $output;
		} else {
			$response['success'] = false;
			$response['nip'] = trim($pegawai_lokal->NIP_BARU);
			$response['msg'] = "Data tidak ada";
			$response['konten'] = "";
		}
		echo json_encode($response);
	}
	public function viewanak()
	{
		$kode 	= $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("PNS_ID", $kode);
		$this->load->library('Api_bkn');
		$api_bkn = new Api_bkn;
		$data_anak = $this->getdata_anak(trim($pegawai_lokal->NIP_BARU));
		$result = false;
		$id = "";
		if ($data_anak != null) {
			$result = true;
		}
		if ($result) {
			$response['success'] = true;
			$response['nip'] = trim($pegawai_lokal->NIP_BARU);
			$response['msg'] = "Data ditemukan";
			$output = $this->load->view('bkn/anak', array('data_anak' => $data_anak, "nipBaru" => trim($pegawai_lokal->NIP_BARU)), true);
			$response['konten'] = $output;
		} else {
			$response['success'] = false;
			$response['nip'] = trim($pegawai_lokal->NIP_BARU);
			$response['msg'] = "Data tidak ada";
			$response['konten'] = "";
		}
		echo json_encode($response);
	}
	public function viewangkakredit()
	{
		$kode 	= $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("PNS_ID", $kode);
		$this->load->library('Api_bkn');
		$api_bkn = new Api_bkn;
		$data_angka_kredit = $this->getdata_angka_kredit(trim($pegawai_lokal->NIP_BARU));
		$result = false;
		$id = "";
		if ($data_angka_kredit != null) {
			$result = true;
		}
		if ($result) {
			$response['success'] = true;
			$response['nip'] = trim($pegawai_lokal->NIP_BARU);
			$response['msg'] = "Data ditemukan";
			$output = $this->load->view('bkn/angka_kredit', array('data_angka_kredit' => $data_angka_kredit, "nipBaru" => trim($pegawai_lokal->NIP_BARU)), true);
			$response['konten'] = $output;
		} else {
			$response['success'] = false;
			$response['nip'] = trim($pegawai_lokal->NIP_BARU);
			$response['msg'] = "Data tidak ada";
			$response['konten'] = "";
		}
		echo json_encode($response);
	}
	public function viewhukdis()
	{
		$kode 	= $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("PNS_ID", $kode);
		$this->load->library('Api_bkn');
		$api_bkn = new Api_bkn;
		$data_hukdis = $this->getdata_rwt_hukdis(trim($pegawai_lokal->NIP_BARU));
		$result = false;
		$id = "";
		if ($data_hukdis != null) {
			$result = true;
		}
		if ($result) {
			$response['success'] = true;
			$response['nip'] = trim($pegawai_lokal->NIP_BARU);
			$response['msg'] = "Data ditemukan";
			$output = $this->load->view('bkn/hukdis', array('data_hukdis' => $data_hukdis, "nipBaru" => trim($pegawai_lokal->NIP_BARU)), true);
			$response['konten'] = $output;
		} else {
			$response['success'] = false;
			$response['nip'] = trim($pegawai_lokal->NIP_BARU);
			$response['msg'] = "Data tidak ada";
			$response['konten'] = "";
		}
		echo json_encode($response);
	}
	private function getdata_utama_bkn($nip_baru)
	{
		$this->load->library('Api_bkn');
		$api_bkn = new Api_bkn;
		// $data_utama_pegawai = $this->cache->get('data_utama_' . $nip_baru);
		// if ($data_utama_pegawai == null) {
			// get data utama
			$jsonsatkers   = $api_bkn->getDataPNS("data-utama", $nip_baru);
			$decodejson    = json_decode($jsonsatkers);
			$decodejson    = json_decode($decodejson);
			$data_utama_pegawai 	= $decodejson->data;
			$this->cache->write($data_utama_pegawai, 'data_utama_' . $nip_baru, 43200);
		// }
		return $data_utama_pegawai;
	}
	private function getdata_riwayat_golongan_bkn($nip_baru)
	{
		$this->load->library('Api_bkn');
		$api_bkn = new Api_bkn;
		//$data_golongan = $this->cache->get('golongan_' . $nip_baru);
		$data_golongan = null;
		if ($data_golongan == null) {
			// data riwayat golongan
			$jsonrwt_golongan   = $api_bkn->getDataPNS("rw-golongan", $nip_baru);
			// $jsonrwt_golongan = $api_bkn->data_rwt_golongan_bkn($nip_baru);
			// die(json_encode($jsonrwt_golongan));
			$jsonrwt_golongan = json_decode($jsonrwt_golongan);
			$jsonrwt_golongan = json_decode($jsonrwt_golongan);
			$data_golongan = $jsonrwt_golongan->data;
			$this->cache->write($data_golongan, 'golongan_' . $nip_baru, 3200);
		}
		return $data_golongan;
	}
	private function getdata_riwayat_cltn($nip_baru)
	{
		$this->load->library('Api_bkn');
		$api_bkn = new Api_bkn;
		$data_golongan = $this->cache->get('cltn_' . $nip_baru);
		if ($data_golongan == null) {
			// data riwayat golongan
			$jsonrwt_golongan   = $api_bkn->getDataPNS("rw-cltn", $nip_baru);
			$jsonrwt_golongan = json_decode($jsonrwt_golongan);
			$jsonrwt_golongan = json_decode($jsonrwt_golongan);
			$data_golongan = $jsonrwt_golongan->data;
			$this->cache->write($data_golongan, 'cltn_' . $nip_baru, 43200);
		}
		return $data_golongan;
	}
	private function getdata_riwayat_dp3($nip_baru)
	{
		$api_bkn = new Api_bkn;
		$data_dp3 = $this->cache->get('data_dp3_' . $nip_baru);
		if ($data_dp3 == null) {
			// data riwayat golongan
			$jsonrwt_golongan = $api_bkn->getDataPNS('rw-dp3', $nip_baru);
			$jsonrwt_golongan = json_decode($jsonrwt_golongan);
			$jsonrwt_golongan = json_decode($jsonrwt_golongan);
			$data_dp3 = $jsonrwt_golongan->data;
			$this->cache->write($data_dp3, 'data_dp3_' . $nip_baru, 43200);
		}
		return $data_dp3;
	}
	private function getdata_pasangan($nip_baru)
	{
		$api_bkn = new Api_bkn;
		$data_pasangan = $this->cache->get('data_pasangan_' . $nip_baru);
		if ($data_pasangan == null) {
			// data riwayat golongan
			$json_return = $api_bkn->getDataPNS('data-pasangan', $nip_baru);
			$json_return = json_decode($json_return);
			$json_return = json_decode($json_return);
			$data_pasangan = $json_return->data;
			$this->cache->write($data_pasangan, 'data_pasangan_' . $nip_baru, 43200);
		}
		return $data_pasangan;
	}
	private function getdata_ortu($nip_baru)
	{
		$api_bkn = new Api_bkn;
		$data_ortu = $this->cache->get('data_ortu_' . $nip_baru);
		if ($data_ortu == null) {
			// data riwayat golongan
			$json_return = $api_bkn->getDataPNS('data-ortu', $nip_baru);
			$json_return = json_decode($json_return);
			$json_return = json_decode($json_return);
			$data_ortu = $json_return->data;
			$this->cache->write($data_ortu, 'data_ortu_' . $nip_baru, 43200);
		}
		return $data_ortu;
	}
	private function getdata_anak($nip_baru)
	{
		$api_bkn = new Api_bkn;
		$this->cache->delete('data_anak_' . $nip_baru);
		$data_return = $this->cache->get('data_anak_' . $nip_baru);
		if ($data_return == null) {
			// data anak
			$json_return = $api_bkn->getDataPNS('data-anak', $nip_baru);
			$json_return = json_decode($json_return);
			$json_return = json_decode($json_return);
			$data_return = $json_return->data;
			$this->cache->write($data_return, 'data_anak_' . $nip_baru, 43200);
		}
		return $data_return;
	}
	private function getdata_kursus($nip_baru)
	{
		$api_bkn = new Api_bkn;
		$data_kursus = $this->cache->get('data_kursus_' . $nip_baru);
		if ($data_kursus == null) {
			// data riwayat kursus
			$json_return = $api_bkn->getDataPNS('rw-kursus', $nip_baru);
			$json_return = json_decode($json_return);
			$json_return = json_decode($json_return);
			$data_kursus = $json_return->data;
			$this->cache->write($data_kursus, 'data_kursus_' . $nip_baru, 43200);
		}
		return $data_kursus;
	}
	private function getdata_penghargaan($nip_baru)
	{
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('data_penghargaan_' . $nip_baru);
		if ($data_return == null) {
			// data riwayat penghargaan
			$json_return = $api_bkn->getDataPNS('rw-penghargaan', $nip_baru);
			$json_return = json_decode($json_return);
			$json_return = json_decode($json_return);
			// $data_return = $json_return->data;
			$data_return = $json_return->data;
			$this->cache->write($data_return, 'data_penghargaan_' . $nip_baru, 43200);
		}
		return $data_return;
	}
	private function getdata_pindahinstansi($nip_baru)
	{
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('data_pindah_instansi_' . $nip_baru);
		if ($data_return == null) {
			// data riwayat golongan
			$json_return = $api_bkn->getDataPNS('rw-pindahinstansi', $nip_baru);
			$json_return = json_decode($json_return);
			$json_return = json_decode($json_return);
			$data_return = $json_return->data;
			$this->cache->write($data_return, 'data_pindah_instansi_' . $nip_baru, 43200);
		}
		return $data_return;
	}
	private function getdata_angka_kredit($nip_baru)
	{
		$api_bkn = new Api_bkn;
		$this->cache->delete('data_angka_kredit_' . $nip_baru);
		$data_return = $this->cache->get('data_angka_kredit_' . $nip_baru);
		if ($data_return == null) {
			// data ak
			$json_return = $api_bkn->getDataPNS('rw-angkakredit', $nip_baru);
			$json_return = json_decode($json_return);
			$json_return = json_decode($json_return);
			$data_return = $json_return->mapData->data;
			$this->cache->write($data_return, 'data_angka_kredit_' . $nip_baru, 43200);
		}
		return $data_return;
	}
	public function viewskp()
	{
		$kode   = $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("PNS_ID", $kode);
		$this->load->library('Api_bkn');
		$api_bkn = new Api_bkn;
		$data_skp = $this->getdata_rwt_skp(trim($pegawai_lokal->NIP_BARU));
		$result = false;
		$id = "";
		if ($data_skp != null) {
			$result = true;
		}
		if ($result) {
			$response['success'] = true;
			$response['nip'] = trim($pegawai_lokal->NIP_BARU);
			$response['msg'] = "Data ditemukan";
			$output = $this->load->view('bkn/skp', array('data_skp' => $data_skp, "nipBaru" => trim($pegawai_lokal->NIP_BARU)), true);
			$response['konten'] = $output;
		} else {
			$response['success'] = false;
			$response['nip'] = trim($pegawai_lokal->NIP_BARU);
			$response['msg'] = "Data tidak ada";
			$response['konten'] = "";
		}
		echo json_encode($response);
	}
	public function viewkinerja()
	{
		$kode   = $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("PNS_ID", $kode);
		$this->load->library('Api_bkn');
		$api_bkn = new Api_bkn;
		$data_skp = $this->getdata_rwt_kinerja(trim($pegawai_lokal->NIP_BARU));
		$result = false;
		$id = "";
		if ($data_skp != null) {
			$result = true;
		}
		if ($result) {
			$response['success'] = true;
			$response['nip'] = trim($pegawai_lokal->NIP_BARU);
			$response['msg'] = "Data ditemukan";
			$output = $this->load->view('bkn/kinerja', array('data_skp' => $data_skp, "nipBaru" => trim($pegawai_lokal->NIP_BARU)), true);
			$response['konten'] = $output;
		} else {
			$response['success'] = false;
			$response['nip'] = trim($pegawai_lokal->NIP_BARU);
			$response['msg'] = "Data tidak ada";
			$response['konten'] = "";
		}
		echo json_encode($response);
	}
	private function getdata_rwt_skp($nip_baru)
	{
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('data_rwt_skp_' . $nip_baru);
		if ($data_return == null) {
			// data riwayat golongan
			$json_return = $api_bkn->getDataPNS('rw-skp', $nip_baru);
			$json_return = json_decode($json_return);
			$json_return = json_decode($json_return);
			$data_return = $json_return->data;
			$this->cache->write($data_return, 'data_rwt_skp_' . $nip_baru, 43200);
		}
		return $data_return;
	}
	private function getdata_rwt_kinerja($nip_baru)
	{
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('data_rwt_kinerja_' . $nip_baru);
		if ($data_return == null) {
			// data riwayat kinerja
			$json_return = $api_bkn->getDataPNS('rw-skp22', $nip_baru);
			$json_return = json_decode($json_return);
			$json_return = json_decode($json_return);
			$data_return = $json_return->data;
			$this->cache->write($data_return, 'data_rwt_kinerja_' . $nip_baru, 43200);
		}
		return $data_return;
	}
	private function getdata_rwt_cltn($nip_baru)
	{
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('data_rwt_cltn_' . $nip_baru);
		if ($data_return == null) {
			$json_return = $api_bkn->getDataPNS('rw-cltn', $nip_baru);
			$json_return = json_decode($json_return);
			$json_return = json_decode($json_return);
			$data_return = $json_return->data;
			$this->cache->write($data_return, 'data_rwt_cltn_' . $nip_baru, 43200);
		}
		return $data_return;
	}
	private function getdata_rwt_pemberhentian($nip_baru)
	{
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('data_rwt_pemberhentian_' . $nip_baru);
		if ($data_return == null) {
			$json_return = $api_bkn->getDataPNS('rw-pemberhentian', $nip_baru);
			$json_return = json_decode($json_return);
			$json_return = json_decode($json_return);
			$data_return = $json_return->data;
			$this->cache->write($data_return, 'data_rwt_pemberhentian_' . $nip_baru, 43200);
		}
		return $data_return;
	}
	private function getdata_rwt_ak($nip_baru)
	{
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('data_rwt_ak_' . $nip_baru);
		if ($data_return == null) {
			$json_return = $api_bkn->getDataPNS('rw-angkakredit', $nip_baru);
			$json_return = json_decode($json_return);
			$json_return = json_decode($json_return);
			$data_return = $json_return->data;
			$this->cache->write($data_return, 'data_rwt_ak_' . $nip_baru, 43200);
		}
		return $data_return;
	}
	private function getdata_rwt_pnsunor($nip_baru)
	{
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('data_rwt_pnsunor_' . $nip_baru);
		if ($data_return == null) {
			$json_return = $api_bkn->getDataPNS('rw-pnsunor', $nip_baru);
			$json_return = json_decode($json_return);
			$json_return = json_decode($json_return);
			$data_return = $json_return->data;
			$this->cache->write($data_return, 'data_rwt_pnsunor_' . $nip_baru, 43200);
		}
		return $data_return;
	}
	private function getdata_rwt_hukdis($nip_baru)
	{
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('data_rwt_hukdis_' . $nip_baru);
		if ($data_return == null) {
			$json_return = $api_bkn->getDataPNS('rw-hukdis', $nip_baru);
			$json_return = json_decode($json_return);
			$json_return = json_decode($json_return);
			$data_return = $json_return->data;
			$this->cache->write($data_return, 'data_rwt_hukdis_' . $nip_baru, 43200);
		}
		return $data_return;
	}
	private function getdata_kpo_sk($nip_baru)
	{
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('data_kpo_sk_' . $nip_baru);
		if ($data_return == null) {
			$json_return = $api_bkn->get_data_kpo_sk($nip_baru);
			$json_return = json_decode($json_return);
			$json_return = json_decode($json_return);
			$data_return = $json_return->data;
			$this->cache->write($data_return, 'data_kpo_sk_' . $nip_baru, 43200);
		}
		return $data_return;
	}
	public function getdata_sk_kpo_hist($from, $to)
	{
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get("data_kpo_sk_hist_{$from}_{$to}");
		if ($data_return == null) {
			$json_return = $api_bkn->get_data_kpo_sk_hist($from, $to);
			$json_return = json_decode($json_return);
			$json_return = json_decode($json_return);
			$data_return = $json_return->data;
			foreach ($data_return as $data) {
				$this->cache->write($data, "data_kpo_sk_{$data->id}", 43200);
			}
			$this->cache->write($data_return, "data_kpo_sk_hist_{$from}_{$to}", 43200);
		}
		return $data_return;
	}
	public function getdata_sk_kpo()
	{
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get("data_kpo_sk");
		if ($data_return == null) {
			$json_return = $api_bkn->get_data_kpo_sk();
			$json_return = json_decode($json_return);
			$data_return = $json_return->data;
			$this->cache->write($data_return, "data_kpo_sk", 43200);
		}
		return $data_return;
	}
	private function getdata_ppo_sk($nip_baru)
	{
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('data_ppo_sk_' . $nip_baru);
		if ($data_return == null) {
			$json_return = $api_bkn->get_data_ppo_sk($nip_baru);
			$json_return = json_decode($json_return);
			$json_return = json_decode($json_return);
			$data_return = $json_return->data;
			$this->cache->write($data_return, 'data_ppo_sk_' . $nip_baru, 43200);
		}
		return $data_return;
	}
	private function getdata_ppo_sk_hist($nip_baru)
	{
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('ppo_sk_hist_' . $nip_baru);
		if ($data_return == null) {
			$json_return = $api_bkn->get_data_ppo_sk_hist($nip_baru);
			$json_return = json_decode($json_return);
			$json_return = json_decode($json_return);
			$data_return = $json_return->data;
			$this->cache->write($data_return, 'ppo_sk_hist_' . $nip_baru, 43200);
		}
		return $data_return;
	}
	private function getdata_rwt_pendidikan($nip_baru)
	{
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('rwt_pendidikan_' . $nip_baru);
		if ($data_return == null) {
			$json_return = $api_bkn->getDataPNS('rw-pendidikan', $nip_baru);
			$json_return = json_decode($json_return);
			$json_return = json_decode($json_return);
			$data_return = $json_return->data;
			$data_return->info = "anjing";
			$this->cache->write($data_return, 'rwt_pendidikan_' . $nip_baru, 43200);
		}else{
			$data_return->info = "tetot";
		}

		return $data_return;
	}
	private function getdata_rwt_jabatan($nip_baru)
	{
		$api_bkn = new Api_bkn;
		/**
		 * ECHA WAS HERE 
		 * 29 December 2023 
		 * Why I commented this code below, it's simply because it always bring data from cahce which
		 * is probably doesn't same with the current data. 
		 */
		
		//$data_return = $this->cache->get('rwt_jabatan_' . $nip_baru);
		$data_return = null;
		if ($data_return == null) {
			$json_return = $api_bkn->getDataPNS('rw-jabatan', $nip_baru);
			//$json_return = [];
			$json_return = json_decode($json_return);
			$json_return = json_decode($json_return);
			$data_return = $json_return->data;
			$this->cache->write($data_return, 'rwt_jabatan_' . $nip_baru, 43200);
		}
		return $data_return;
	}
	private function getdata_rwt_diklat($nip_baru)
	{
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('rwt_diklat_' . $nip_baru);
		if ($data_return == null) {
			$json_return = $api_bkn->getDataPNS('rw-diklat', $nip_baru);
			$json_return = json_decode($json_return);
			$json_return = json_decode($json_return);
			$data_return = $json_return->data;
			$this->cache->write($data_return, 'rwt_diklat_' . $nip_baru, 43200);
		}
		return $data_return;
	}
	private function getdata_rwt_masakerja($nip_baru)
	{
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('rwt_masakerja_' . $nip_baru);
		if ($data_return == null) {
			$json_return = $api_bkn->getDataPNS('rw-masakerja', $nip_baru);
			$json_return = json_decode($json_return);
			$json_return = json_decode($json_return);
			$data_return = $json_return->data;
			$this->cache->write($data_return, 'rwt_masakerja_' . $nip_baru, 43200);
		}
		return $data_return;
	}
	private function getdata_rwt_pwk($nip_baru)
	{
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('rwt_pwk_' . $nip_baru);
		if ($data_return == null) {
			$json_return = $api_bkn->getDataPNS('rw-pwk', $nip_baru);
			$json_return = json_decode($json_return);
			$json_return = json_decode($json_return);
			$data_return = $json_return->data;
			$this->cache->write($data_return, 'rwt_pwk_' . $nip_baru, 43200);
		}
		return $data_return;
	}
	private function getdata_usul_wafat($nip_baru)
	{
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('usul_wafat_' . $nip_baru);
		if ($data_return == null) {
			$json_return = $api_bkn->get_data_usul_wafat($nip_baru);
			$json_return = json_decode($json_return);
			$json_return = json_decode($json_return);
			$data_return = $json_return->data;
			$this->cache->write($data_return, 'usul_wafat_' . $nip_baru, 43200);
		}
		return $data_return;
	}
	private function getdata_usul_wafat_hist($nip_baru)
	{
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('usul_wafat_hist_' . $nip_baru);
		if ($data_return == null) {
			$json_return = $api_bkn->get_data_usul_wafat_hist($nip_baru);
			$json_return = json_decode($json_return);
			$json_return = json_decode($json_return);
			$data_return = $json_return->data;
			$this->cache->write($data_return, 'usul_wafat_hist_' . $nip_baru, 43200);
		}
		return $data_return;
	}
	private function getdata_update_pns($nip_baru)
	{
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('update_pns_' . $nip_baru);
		if ($data_return == null) {
			$json_return = $api_bkn->get_data_update_pns($nip_baru);
			$json_return = json_decode($json_return);
			$json_return = json_decode($json_return);
			$data_return = $json_return->data;
			$this->cache->write($data_return, 'update_pns_' . $nip_baru, 43200);
		}
		return $data_return;
	}
	private function getdata_update_pns_hist($nip_baru)
	{
		$api_bkn = new Api_bkn;
		$data_return = $this->cache->get('update_pns_hist_' . $nip_baru);
		if ($data_return == null) {
			$json_return = $api_bkn->get_data_update_pns_hist($nip_baru);
			$json_return = json_decode($json_return);
			$json_return = json_decode($json_return);
			$data_return = $json_return->data;
			$this->cache->write($data_return, 'update_pns_hist_' . $nip_baru, 43200);
		}
		return $data_return;
	}
	private function cek_prestasi_kerja($nip = "", $tahun = "", $nilai = "")
	{
		$this->load->model('pegawai/riwayat_prestasi_kerja_model');
		$this->riwayat_prestasi_kerja_model->where("TAHUN", $tahun);
		if ($nilai) {
			$this->riwayat_prestasi_kerja_model->where("NILAI_SKP", $nilai);
		}
		$data_prestasi_kerja = $this->riwayat_prestasi_kerja_model->find_by("PNS_NIP", $nip);
		return $data_prestasi_kerja;
	}
	public function sinkron_cache()
	{
		$nip_baru 	= $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU", $nip_baru);
		$result = $this->sinkron_panggol($pegawai_lokal);
		$result = $this->sinkron_skp($pegawai_lokal);
		$result = $this->sinkron_pendidikan($pegawai_lokal);
		$result = $this->sinkron_jabatan($pegawai_lokal);
		$result = $this->sinkron_pns_unor($pegawai_lokal);
		$result = $this->sinkron_diklat($pegawai_lokal);
		if ($result) {
			$response['success'] = true;
			$response['msg'] = "Sukses sinkron data";
		} else {
			$response['success'] = false;
			$response['msg'] = "Ada kesalahan";
		}
		echo json_encode($response);
	}
	public function sinkron_cache_pendidikan()
	{
		$nip_baru 	= $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU", $nip_baru);
		$result = $this->sinkron_pendidikan($pegawai_lokal);
		if ($result) {
			$response['success'] = true;
			$response['msg'] = "Sukses sinkron data pendidikan";
		} else {
			$response['success'] = false;
			$response['msg'] = "Ada kesalahan";
		}
		echo json_encode($response);
	}
	public function sinkron_cache_golongan()
	{
		$nip_baru 	= $this->input->post('kode');
		$golongan_id 	= $this->input->post('golongan_id');
		$pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU", $nip_baru);
		$result = $this->sinkron_panggol($pegawai_lokal, $golongan_id);
		if ($result) {
			$response['success'] = true;
			$response['msg'] = "Sukses sinkron data riwayat pangkat golongan";
		} else {
			$response['success'] = false;
			$response['msg'] = "Ada kesalahan";
		}
		echo json_encode($response);
	}
	public function sinkron_cache_cltn()
	{
		$nip_baru   = $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU", $nip_baru);
		$result = $this->sinkron_cltn($pegawai_lokal);
		if ($result) {
			$response['success'] = true;
			$response['msg'] = "Sukses sinkron data riwayat CLTN";
		} else {
			$response['success'] = false;
			$response['msg'] = "Ada kesalahan";
		}
		echo json_encode($response);
	}
	public function sinkron_cache_jabatan()
	{
		$nip_baru 	= $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU", $nip_baru);
		$result = $this->sinkron_jabatan($pegawai_lokal);
		if ($result) {
			$response['success'] = true;
			$response['msg'] = "Sukses sinkron data riwayat jabatan";
		} else {
			$response['success'] = false;
			$response['msg'] = "Ada kesalahan";
		}
		echo json_encode($response);
	}
	public function sinkron_cache_diklat()
	{
		$nip_baru 	= $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU", $nip_baru);
		$result = $this->sinkron_diklat($pegawai_lokal);
		if ($result) {
			$response['success'] = true;
			$response['msg'] = "Sukses sinkron data riwayat diklat";
		} else {
			$response['success'] = false;
			$response['msg'] = "Ada kesalahan";
		}
		echo json_encode($response);
	}
	public function sinkron_cache_skp()
	{
		$nip_baru   = $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU", $nip_baru);
		$result = $this->sinkron_skp($pegawai_lokal);
		if ($result) {
			$response['success'] = true;
			$response['msg'] = "Sukses sinkron data SKP";
		} else {
			$response['success'] = false;
			$response['msg'] = "Ada kesalahan";
		}
		echo json_encode($response);
	}
	public function sinkron_cache_kinerja()
	{
		$nip_baru   = $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU", $nip_baru);
		$result = $this->sinkron_kinerja($pegawai_lokal);
		if ($result) {
			$response['success'] = true;
			$response['msg'] = "Sukses sinkron data Kinerja";
		} else {
			$response['success'] = false;
			$response['msg'] = "Ada kesalahan";
		}
		echo json_encode($response);
	}
	public function sinkron_cache_penghargaan()
	{
		$nip_baru   = $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU", $nip_baru);
		$result = $this->sinkron_penghargaan($pegawai_lokal);
		if ($result) {
			$response['success'] = true;
			$response['msg'] = "Sukses sinkron data SKP";
		} else {
			$response['success'] = false;
			$response['msg'] = "Ada kesalahan";
		}
		echo json_encode($response);
	}
	public function sinkron_cache_ortu()
	{
		$nip_baru   = $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU", $nip_baru);
		$result = $this->sinkron_data_ortu($pegawai_lokal);
		if ($result) {
			$response['success'] = true;
			$response['msg'] = "Sukses sinkron data orang tua";
		} else {
			$response['success'] = false;
			$response['msg'] = "Ada kesalahan";
		}
		echo json_encode($response);
	}
	public function sinkron_cache_pasangan()
	{
		$nip_baru   = $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU", $nip_baru);
		$result = $this->sinkron_data_pasangan($pegawai_lokal);
		if ($result) {
			$response['success'] = true;
			$response['msg'] = "Sukses sinkron data pasangan";
		} else {
			$response['success'] = false;
			$response['msg'] = "Ada kesalahan";
		}
		echo json_encode($response);
	}
	public function sinkron_cache_anak()
	{
		$nip_baru   = $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU", $nip_baru);
		$result = $this->sinkron_data_anak($pegawai_lokal);
		if ($result) {
			$response['success'] = true;
			$response['msg'] = "Sukses sinkron data anak";
		} else {
			$response['success'] = false;
			$response['msg'] = "Ada kesalahan";
		}
		echo json_encode($response);
	}
	public function sinkron_cache_angka_kredit()
	{
		$nip_baru   = $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU", $nip_baru);
		$result = $this->sinkron_data_angka_kredit($pegawai_lokal);
		if ($result) {
			$response['success'] = true;
			$response['msg'] = "Sukses sinkron data angka kredit";
		} else {
			$response['success'] = false;
			$response['msg'] = "Ada kesalahan";
		}
		echo json_encode($response);
	}
	private function sinkron_panggol($pegawai_lokal = null, $golongan_id = null)
	{
		$nip_baru = trim($pegawai_lokal->NIP_BARU);
		$data_golongan = $this->cache->get('golongan_' . $nip_baru);
		$result = true;
		$index = 0;
		// data riwayat pangkat golongan
		if ($data_golongan != null) {
			foreach ($data_golongan as $row) {
				$last = $index == sizeof($data_golongan) - 1 ? 1 : 0;
				if (empty($golongan_id)) {
					$this->save_golongan($row, $last, (array) $pegawai_lokal);
				} else {
					if ($golongan_id == $row->golonganId) {
						$this->save_golongan($row, 1, (array) $pegawai_lokal);
						break;
					}
				}
				$index++;
			}
			$this->set_pangkat_aktif($nip_baru);
		}
		return $result;
	}
	public function save_golongan($row, $last = false, $pegawai_lokal = array())
	{
		$this->load->library('Api_s3_aws');
        $aws = new Api_s3_aws;
		$row = (object) $row;
		$pegawai_lokal = (array) $pegawai_lokal;
		$golongans = $this->golongan_model->find_by("ID", $row->golonganId);
		$idPns 		= $row->idPns;
		$golonganId = $row->golonganId;
		$NAMA_PANGKAT = isset($golongans->NAMA_PANGKAT) ? $golongans->NAMA_PANGKAT : "";
		$KODE_PANGKAT = isset($golongans->NAMA) ? $golongans->NAMA : "";
		$golongan 	= $row->golongan;
		$skNomor 	= TRIM($row->skNomor);
		$skTanggal 	= $row->skTanggal;
		$tmtGolongan 	= $row->tmtGolongan;
		$noPertekBkn 	= $row->noPertekBkn;
		$tglPertekBkn 	= $row->tglPertekBkn;
		$this->riwayat_kepangkatan_model->where("ID_GOLONGAN", $golonganId);
		$this->riwayat_kepangkatan_model->where("SK_NOMOR", $skNomor);
		// $this->riwayat_kepangkatan_model->where("JENIS_RIWAYAT", "KP");
		$count_golongan = $this->riwayat_kepangkatan_model->count_all($row->idPns);
		$keterangan = "Golongan {$golongan} TMT {$tmtGolongan}";
		if ($row->golonganId == 11) {
			$id_jenis_arsip = 64;
		} else if ($row->golonganId == 12) {
			$id_jenis_arsip = 3;
		} else if ($row->golonganId == 13) {
			$id_jenis_arsip = 30;
		} else if ($row->golonganId == 14) {
			$id_jenis_arsip = 31;
		} else if ($row->golonganId == 21) {
			$id_jenis_arsip = 32;
		} else if ($row->golonganId == 22) {
			$id_jenis_arsip = 33;
		} else if ($row->golonganId == 23) {
			$id_jenis_arsip = 34;
		} else if ($row->golonganId == 24) {
			$id_jenis_arsip = 35;
		} else if ($row->golonganId == 31) {
			$id_jenis_arsip = 36;
		} else if ($row->golonganId == 32) {
			$id_jenis_arsip = 37;
		} else if ($row->golonganId == 33) {
			$id_jenis_arsip = 38;
		} else if ($row->golonganId == 34) {
			$id_jenis_arsip = 39;
		} else if ($row->golonganId == 41) {
			$id_jenis_arsip = 40;
		} else if ($row->golonganId == 42) {
			$id_jenis_arsip = 41;
		} else if ($row->golonganId == 43) {
			$id_jenis_arsip = 42;
		} else if ($row->golonganId == 44) {
			$id_jenis_arsip = 43;
		} else if ($row->golonganId == 45) {
			$id_jenis_arsip = 45;
		} else {
		}

		if ($count_golongan <= 0) {
			$data = array();
			$row->nipBaru = $pegawai_lokal['NIP_BARU'];
			$row->golongan = $KODE_PANGKAT;
			$data["ID_BKN"] = $row->id;
			$data["PNS_NIP"] = $row->nipBaru;
			$data["PNS_ID"] = $row->idPns;
			$data["PNS_NAMA"] = isset($pegawai_lokal['NAMA']) ? $pegawai_lokal['NAMA'] : '';
			$data["JENIS_KP"] = $row->jenisKPNama ? $row->jenisKPNama : null;
			$data["ID_GOLONGAN"] = $row->golonganId ? $row->golonganId : null;
			$data["PANGKAT"] = $NAMA_PANGKAT;
			$data["SK_NOMOR"] = $row->skNomor != "" ? $row->skNomor : "-";
			$data["NOMOR_BKN"] = $row->noPertekBkn;
			$data["MK_GOLONGAN_TAHUN"] = $row->masaKerjaGolonganTahun ? (int)$row->masaKerjaGolonganTahun : 0;
			$data["MK_GOLONGAN_BULAN"] = $row->masaKerjaGolonganBulan ? (int)$row->masaKerjaGolonganBulan : 0;

			$data["KODE_JENIS_KP"] = $row->jenisKPId ? $row->jenisKPId : null;
			$data["GOLONGAN"] = $row->golongan ? $row->golongan : null;
			$data['JUMLAH_ANGKA_KREDIT_UTAMA'] = $row->jumlahKreditUtama != 'null' ? $row->jumlahKreditUtama : null;
			$data['JUMLAH_ANGKA_KREDIT_TAMBAHAN'] = $row->jumlahKreditTambahan != 'null' ? $row->jumlahKreditTambahan : null;

			$data["TMT_GOLONGAN"] 	= date("Y-m-d", strtotime($row->tmtGolongan));
			$data["SK_TANGGAL"] 	= date("Y-m-d", strtotime($row->skTanggal));
			$data['JENIS_RIWAYAT'] = "KP";
			$data['BASIC'] = $this->getGapok(date("Y-m-d", strtotime($row->tmtGolongan)), $row->golonganId, (int)$row->masaKerjaGolonganTahun);
			$data['PANGKAT_TERAKHIR'] = $last;
			if (empty($data["MK_GOLONGAN_BULAN"])) {
				unset($data["MK_GOLONGAN_BULAN"]);
			}
			if (empty($data["MK_GOLONGAN_TAHUN"])) {
				unset($data["MK_GOLONGAN_TAHUN"]);
			}
			if (empty($data["TMT_GOLONGAN"])) {
				unset($data["TMT_GOLONGAN"]);
			}
			if (empty($data["SK_TANGGAL"])) {
				unset($data["SK_TANGGAL"]);
			}
			// save berkas
			if ($row->path) {
				foreach($row->path as $i => $i_value) {
                    $file = urlencode($i_value->dok_uri);
                    if($file != ""){
						$file = urlencode($i_value->dok_uri);
						$response   = $this->getDokumen($file);
						$file_name = $this->saveArsip($i_value->dok_uri, $response);
						$id_arsip = $aws->insertArsip($data["PNS_NIP"],$data["SK_NOMOR"],$id_jenis_arsip,$keterangan, $response,$file,1);
					}
				}
			}
			$data['id_arsip'] = $id_arsip;
			$result = $this->riwayat_kepangkatan_model->insert($data);
			log_activity_pegawai($row->nipBaru, $this->auth->user_id(), "sinkron data rwt_golongan dari BKN " . $NAMA_PANGKAT, 'pegawai');
		} else if ($count_golongan == 1) {
			$dataupdate = array();
			if ($last) {
				$dataupdate["PANGKAT_TERAKHIR"] = 0;

				$this->riwayat_kepangkatan_model->skip_validation(true);
				$this->riwayat_kepangkatan_model->update_where("PNS_NIP", $row->nipBaru, $dataupdate);
				$dataupdate["PANGKAT_TERAKHIR"] = 1;
			}
			$gapok = $this->getGapok(date("Y-m-d", strtotime($row->tmtGolongan)), $row->golonganId, (int)$row->masaKerjaGolonganTahun);
			if ($gapok) {
				$data_update['BASIC'] = $gapok;
			}
			$dataupdate["ID_BKN"] = $row->id;
			$dataupdate["MK_GOLONGAN_TAHUN"] = $row->masaKerjaGolonganTahun ? (int)$row->masaKerjaGolonganTahun : 0;
			$dataupdate["MK_GOLONGAN_BULAN"] = $row->masaKerjaGolonganBulan ? (int)$row->masaKerjaGolonganBulan : 0;

			$data["SK_NOMOR"] = $row->skNomor != "" ? $row->skNomor : "-";
			$dataupdate["KODE_JENIS_KP"] = $row->jenisKPId ? $row->jenisKPId : null;

			$dataupdate['JUMLAH_ANGKA_KREDIT_UTAMA'] = $row->jumlahKreditUtama != 'null' ? $row->jumlahKreditUtama : null;
			$dataupdate['JUMLAH_ANGKA_KREDIT_TAMBAHAN'] = $row->jumlahKreditTambahan != 'null' ? $row->jumlahKreditTambahan : null;
			if ($row->path) {
				foreach($row->path as $i => $i_value) {
                    $file = urlencode($i_value->dok_uri);
                    if($file != ""){
						$response   = $this->getDokumen($file);
						$id_arsip = $aws->insertArsip($row->nipBaru,$data["SK_NOMOR"],$id_jenis_arsip,$keterangan, $response,$file,1);
					}
				}
			}
			if ($id_arsip) {
				$data_update['id_arsip'] = $id_arsip;
			}
			$this->riwayat_kepangkatan_model->skip_validation(true);
			$this->riwayat_kepangkatan_model->where("ID_GOLONGAN", $row->golonganId);
			$this->riwayat_kepangkatan_model->update_where("PNS_NIP", $row->nipBaru, $dataupdate);
			log_activity($this->auth->user_id(), "sinkron data rwt_golongan dari BKN " . $NAMA_PANGKAT, 'pegawai');
		}
	}
	public function set_pangkat_aktif($nip)
	{
		$this->riwayat_kepangkatan_model->order_by('TMT_GOLONGAN', 'desc');
		$golongan_aktifs = $this->riwayat_kepangkatan_model->limit(1)->find_aktif($nip);
		if ($golongan_aktifs[0]->ID_GOLONGAN != "") {
			$dataupdate = array();
			$dataupdate['GOL_ID']           = $golongan_aktifs[0]->ID_GOLONGAN;
			$dataupdate['TMT_GOLONGAN']     = $golongan_aktifs[0]->TMT_GOLONGAN;
			$dataupdate['MK_TAHUN']         = $golongan_aktifs[0]->MK_GOLONGAN_TAHUN;
			$dataupdate['MK_BULAN']         = $golongan_aktifs[0]->MK_GOLONGAN_BULAN;
			$this->pegawai_model->skip_validation(true);
			$this->pegawai_model->update_where("NIP_BARU", $nip, $dataupdate);
		}
	}
	private function sinkron_cltn($pegawai_lokal = null)
	{
		$nip_baru = trim($pegawai_lokal->NIP_BARU);
		$data_cltn = $this->cache->get('cltn_' . $nip_baru);
		$result = true;
		// data riwayat pangkat golongan
		if ($data_cltn != null) {
			foreach ($data_cltn as $row) {
				$data = array();
				$data["cltn_id"]            = $row->cltnId;
				$data["jenis"]            	= 1;
				$data["nomor_sk"]           = $row->skNomor;
				$data["tanggal_sk"]         = $row->skTanggal;
				$data["tanggal_awal"]       = $row->tanggalAwal;
				$data["tanggal_akhir"]      = $row->tanggalAkhir;
				$data["tanggal_aktif"]      = $row->tanggalAktif;
				$data["nomor_letter_bkn"]   = $row->nomorLetterBkn;
				$data["tanggal_letter_bkn"] = $row->tanggalLetterBkn;
				$data["pns_id"]             = $row->pnsOrangId;
				$data["pns_nip"]            = $nip_baru;
				$data["id_bkn"]             = $row->id;

				if (empty($data["tanggal_aktif"])) {
					unset($data["tanggal_aktif"]);
				}
				if (empty($data["tanggal_awal"])) {
					unset($data["tanggal_awal"]);
				}
				if (empty($data["tanggal_letter_bkn"])) {
					unset($data["tanggal_letter_bkn"]);
				}
				if (!$this->riwayat_cltn_model->is_exist_cltn($row->id)) {
					$result = $this->riwayat_cltn_model->insert($data);
					log_activity_pegawai($nip_baru, $this->auth->user_id(), "sinkron data riwayat_cltn dari BKN " . $row->skNomor, 'pegawai');
				}
			}
		}
		return $result;
	}
	private function sinkron_skp($pegawai_lokal = null)
	{
		$this->load->helper('aplikasi');
		$nip_baru = trim($pegawai_lokal->NIP_BARU);
		$data_skp = $this->cache->get('data_rwt_skp_' . $nip_baru);
		$result = true;
		if ($data_skp != null) {
			foreach ($data_skp as $row) {
				if ($row->tahun != 0) {
					$nilai = $row->nilaiSkp;
					if ($row->tahun < 2021) {
						$nilai = null;
					}
					$record_skp = $this->cek_prestasi_kerja($nip_baru, $row->tahun, $nilai);
					$idPns 		= $row->pns;
					// if(!isset($record_skp->ID)){
					$data = array();
					$data["PNS_ID"] = $idPns;
					$data["PNS_NAMA"] = $pegawai_lokal->NAMA;
					$data["PNS_NIP"] = $pegawai_lokal->NIP_BARU;
					$data['TAHUN'] 		= $row->tahun;
					$data['NILAI_SKP'] = $row->nilaiSkp;
					$data['NILAI_PROSENTASE_SKP'] = null;
					$data['NILAI_SKP_AKHIR'] = $row->nilaiSkp * 0.6;
					$data['NILAI_PROSENTASE_PERILAKU'] = null;
					$data['NILAI_PERILAKU'] = $row->nilairatarata;
					$data['PERILAKU_KOMITMEN'] = $row->komitmen;
					$data['PERILAKU_INTEGRITAS'] = $row->integritas;
					$data['PERILAKU_DISIPLIN'] = $row->disiplin;
					$data['PERILAKU_KERJASAMA'] = $row->kerjasama;
					$data['PERILAKU_ORIENTASI_PELAYANAN'] = $row->orientasiPelayanan;
					$data['NILAI_PERILAKU_AKHIR'] = $row->nilaiPerilakuKerja;
					$data['NILAI_PPK'] = $row->nilaiPrestasiKerja;
					$data['BKN_ID'] = $row->id;
					$data['ATASAN_LANGSUNG_PNS_NIP'] = $row->penilaiNipNrp;
					$data['ATASAN_LANGSUNG_PNS_NAMA'] = $row->penilaiNama;
					$data['UNOR_PENILAI'] = $row->penilaiUnorNama;
					$data['UNOR_ATASAN_PENILAI'] = $row->atasanPenilaiUnorNama;
					$data['PENILAI_PNS'] = $row->statusPenilai == "PNS" ? 1 : 0;
					$data['ATASAN_PENILAI_PNS'] = $row->statusAtasanPenilai == "PNS" ? 1 : 0;
					$data['GOL_PENILAI'] = $row->penilaiGolongan;
					$data['GOL_ATASAN_PENILAI'] = $row->atasanPenilaiGolongan;
					$data['TMT_GOL_PENILAI'] = validateDate($row->penilaiTmtGolongan) ? $row->penilaiTmtGolongan : null;
					$data['TMT_GOL_ATASAN_PENILAI'] = validateDate($row->atasanPenilaiTmtGolongan) ? $row->atasanPenilaiTmtGolongan : null;
					$data['ATASAN_LANGSUNG_PNS_JABATAN'] = $row->penilaiJabatan;
					$data['ATASAN_ATASAN_LANGSUNG_PNS_JABATAN'] = $row->atasanPenilaiJabatan;

					if (empty($data['TMT_GOL_PENILAI'])) {
						unset($data['TMT_GOL_PENILAI']);
					}
					if (empty($data['TMT_GOL_ATASAN_PENILAI'])) {
						unset($data['TMT_GOL_ATASAN_PENILAI']);
					}
					if (!isset($record_skp->ID)) {
						$result = $this->riwayat_prestasi_kerja_model->insert($data);
					} else {
						$result = $this->riwayat_prestasi_kerja_model->update($record_skp->ID, $data);
					}
					log_activity_pegawai($nip_baru, $this->auth->user_id(), "sinkron data SKP dari BKN " . $row->tahun, 'pegawai');
				}
			}
		}
		return $result;
	}
	private function sinkron_kinerja($pegawai_lokal = null)
	{
		$this->load->helper('aplikasi');
		$nip_baru = trim($pegawai_lokal->NIP_BARU);
		$data_skp = $this->cache->get('data_rwt_kinerja_' . $nip_baru);
		$result = true;
		if ($data_skp != null) {
			foreach ($data_skp as $row) {
				if ($row->tahun != 0) {
					$record = $this->riwayat_kinerja_model->get($nip_baru, $row->tahun);
					$data = array();
					$data['nama'] = $pegawai_lokal->NAMA;
					$data['nip'] = $nip_baru;
					$data['tahun'] = $row->tahun;
					$data['nama_penilai'] = $row->namaPenilai;
					$data['jabatan_penilai'] = $row->penilaiJabatanNm;
					$data['nip_penilai'] = $row->nipNrpPenilai;
					$data['unit_kerja_penilai'] = $row->penilaiUnorNm;
					
					$data['nama_penilai_realisasi'] = $row->namaPenilai;
					$data['jabatan_penilai_realisasi'] = $row->penilaiJabatanNm;
					$data['nip_penilai_realisasi'] = $row->nipNrpPenilai;
					$data['unit_kerja_penilai_realisasi'] = $row->penilaiUnorNm;

					$data['rating_hasil_kerja'] = $row->hasilKinerja;
					$data['rating_perilaku_kerja'] = $row->perilakuKerja;
					$data['predikat_kinerja'] = $row->kuadranKinerja;

					$arsip = $this->arsip_digital_model->find_by(array('NIP' => $nip_baru, 'ID_JENIS_ARSIP' => 85, 'sk_number' => $row->tahun));
					if ($arsip){
						$data['id_arsip'] = $arsip->ID;
					}

					$this->riwayat_kinerja_model->skip_validation(true);
					if ($record) {
						$this->riwayat_kinerja_model->update($record->ref, $data);
					} else {
						$this->riwayat_kinerja_model->insert($data);
					}
					log_activity($this->auth->user_id(), "sinkron data Kinerja {$nip_baru} dari BKN Tahun" . $row->tahun, 'pegawai');
				}
			}
		}
		return $result;
	}
	private function sinkron_pendidikan($pegawai_lokal = null)
	{
		$nip_baru = trim($pegawai_lokal->NIP_BARU);
		$data_pendidikan = $this->cache->get('rwt_pendidikan_' . $nip_baru);
		$result = true;
		if ($data_pendidikan != null) {
			foreach ($data_pendidikan as $row) {
				$this->riwayat_pendidikan_model->where("PENDIDIKAN_ID", $row->pendidikanId);
				$data_riwayat = $this->riwayat_pendidikan_model->find_by("NIP", trim($nip_baru));
				$idPns 		= $row->idPns;
				if (!isset($data_riwayat->ID)) {
					$data = array();
					$data["PNS_ID"] 			= $pegawai_lokal->PNS_ID;
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
					//var_dump($data);
					$result = $this->riwayat_pendidikan_model->insert($data);
					
					log_activity_pegawai($nip_baru, $this->auth->user_id(), "sinkron data riwayat pendidikan dari BKN " . $row->pendidikanId, 'pegawai');
				}
			}
		}
		return $result;
	}
	private function sinkron_jabatan($pegawai_lokal = null)
	{
		$nip_baru = trim($pegawai_lokal->NIP_BARU);
		$data_jabatan = $this->cache->get('rwt_jabatan_' . $nip_baru);
		$result = true;
		if ($data_jabatan != null) {
			$index = 0;
			$tmt_selesai_jabatan = array();
			foreach ($data_jabatan as $row) {
				$tmt_selesai_jabatan[$index] = $row->tmtJabatan;
				$index++;
			}
			$no = 1;
			foreach ($data_jabatan as $row) {
				//isset($tmt_selesai_jabatan[$no]) ? $tmt_selesai_jabatan[$no] : null;
				$this->riwayat_jabatan_model->where("TANGGAL_SK", date('Y-m-d', strtotime($row->tanggalSk)));
				$data_riwayat = $this->riwayat_jabatan_model->find_by("PNS_NIP", trim($nip_baru));
				$unor_local = $this->unitkerja_model->find_by("ID", $row->unorId);
				$idPns 		= $row->idPns;
				if (!isset($data_riwayat->ID)) {
					$data = array();
					if ($row->jenisJabatan == "2") {
						$recjabatan = $this->jabatan_model->find_by("KODE_BKN", TRIM($row->jabatanFungsionalId));

						$data["ID_JENIS_JABATAN"]      = 2;
						$data["JENIS_JABATAN"]      = "Jabatan Fungsional Tertentu";
						$data["ID_JABATAN_BKN"]     = $row->jabatanFungsionalId;
						$data["NAMA_JABATAN"]   = $row->jabatanFungsionalNama;
						$data["ID_JABATAN"]   = isset($recjabatan->KODE_JABATAN) ? $recjabatan->KODE_JABATAN : "";
					}
					if ($row->jenisJabatan == "4") {
						$recjabatan = $this->jabatan_model->find_by("KODE_BKN", TRIM($row->jabatanFungsionalUmumId));
						$data["ID_JENIS_JABATAN"]   = 4;
						$data["JENIS_JABATAN"]      = "Jabatan Fungsional Umum";
						$data["ID_JABATAN_BKN"]     = $row->jabatanFungsionalUmumId;
						$data["NAMA_JABATAN"]       = $row->jabatanFungsionalUmumNama;
						$data["ID_JABATAN"]         = isset($recjabatan->KODE_JABATAN) ? $recjabatan->KODE_JABATAN : "";
					}
					if ($row->jenisJabatan == "1") {
						$recjabatan = $this->jabatan_model->find_by("KODE_BKN", TRIM($row->unorId));
						$data["ID_JENIS_JABATAN"]      = 1;
						$data["JENIS_JABATAN"]      = "Struktural";
						$data["ID_JABATAN_BKN"]     = "221";
						$data["NAMA_JABATAN"]   = $row->namaJabatan;
						$data["ID_JABATAN"]   = isset($recjabatan->KODE_JABATAN) ? $recjabatan->KODE_JABATAN : "";
					}

					$data["PNS_NIP"]        = $pegawai_lokal->NIP_BARU;
					$data["PNS_ID"]         = trim($pegawai_lokal->PNS_ID);
					$data["PNS_NAMA"]       = trim($pegawai_lokal->NAMA);
					$data["TANGGAL_SK"]     = date('Y-m-d', strtotime($row->tanggalSk));
					$data["TMT_JABATAN"]    = date('Y-m-d', strtotime($row->tmtJabatan));
					if (isset($tmt_selesai_jabatan[$no]))
						$data["TERMINATED_DATE"]    = date('Y-m-d', strtotime($tmt_selesai_jabatan[$no]));
					$data["NOMOR_SK"]       = $row->nomorSk;
					// struktur
					$data["ID_UNOR_BKN"]    = $row->unorId;
					$data["ID_UNOR"]        = isset($unor_local->ID) ? $unor_local->ID : null;
					$data["UNOR"]           = $row->unorNama;

					$data["ID_SATUAN_KERJA"]    = $row->satuanKerjaId;
					if (empty($data["TMT_JABATAN"])) {
						unset($data["TMT_JABATAN"]);
					}
					if (empty($data["TANGGAL_SK"])) {
						unset($data["TANGGAL_SK"]);
					}
					if (empty($data["TMT_PELANTIKAN"])) {
						unset($data["TMT_PELANTIKAN"]);
					}
					$data['ID_BKN'] = $row->id;
					$data["LAST_UPDATED"]   = date("Y-m-d");
					$this->riwayat_jabatan_model->skip_validation(true);
					$result = $this->riwayat_jabatan_model->insert($data);
					log_activity_pegawai($nip_baru, $this->auth->user_id(), "sinkron data riwayat jabatan dari BKN " . $row->namaJabatan, 'pegawai');
				} else {
					$data = array();
					if ($row->jenisJabatan == "FUNGSIONAL_TERTENTU") {
						$recjabatan = $this->jabatan_model->find_by("KODE_BKN", TRIM($row->jabatanFungsionalId));

						$data["ID_JENIS_JABATAN"]      = 2;
						$data["JENIS_JABATAN"]      = "Jabatan Fungsional Tertentu";
						$data["ID_JABATAN_BKN"]     = $row->jabatanFungsionalId;
						$data["NAMA_JABATAN"]   = $row->jabatanFungsionalNama;
						$data["ID_JABATAN"]   = isset($recjabatan->KODE_JABATAN) ? $recjabatan->KODE_JABATAN : "";
					}
					if ($row->jenisJabatan == "FUNGSIONAL_UMUM") {
						$recjabatan = $this->jabatan_model->find_by("KODE_BKN", TRIM($row->jabatanFungsionalUmumId));
						$data["ID_JENIS_JABATAN"]      = 4;
						$data["JENIS_JABATAN"]      = "Jabatan Fungsional Umum";
						$data["ID_JABATAN_BKN"]     = $row->jabatanFungsionalUmumId;
						$data["NAMA_JABATAN"]   = $row->jabatanFungsionalUmumNama;
						$data["ID_JABATAN"]   = isset($recjabatan->KODE_JABATAN) ? $recjabatan->KODE_JABATAN : "";
					}
					if ($row->jenisJabatan == "STRUKTURAL") {
						$recjabatan = $this->jabatan_model->find_by("KODE_BKN", TRIM($row->unorId));
						$data["ID_JENIS_JABATAN"]      = 1;
						$data["JENIS_JABATAN"]      = "Struktural";
						$data["ID_JABATAN_BKN"]     = $row->unorId;
						$data["NAMA_JABATAN"]   = $row->namaJabatan;
						$data["ID_JABATAN"]   = isset($recjabatan->KODE_JABATAN) ? $recjabatan->KODE_JABATAN : "";
					}

					$data["PNS_NIP"]        = $pegawai_lokal->NIP_BARU;
					$data["PNS_ID"]         = trim($pegawai_lokal->PNS_ID);
					$data["PNS_NAMA"]       = trim($pegawai_lokal->NAMA);
					$data["TANGGAL_SK"]     = date('Y-m-d', strtotime($row->tanggalSk));
					$data["TMT_JABATAN"]    = date('Y-m-d', strtotime($row->tmtJabatan));
					$data["NOMOR_SK"]       = $row->nomorSk;
					// struktur
					$data["ID_UNOR_BKN"]    = $row->unorId;
					$data["ID_UNOR"]        = isset($unor_local->ID) ? $unor_local->ID : null;
					$data["UNOR"]           = $row->unorNama;
					$data['ID_BKN'] 		= $row->id;

					$data["ID_SATUAN_KERJA"]    = $row->satuanKerjaId;
					if (empty($data["TMT_JABATAN"])) {
						unset($data["TMT_JABATAN"]);
					}
					if (empty($data["TANGGAL_SK"])) {
						unset($data["TANGGAL_SK"]);
					}
					if (empty($data["TMT_PELANTIKAN"])) {
						unset($data["TMT_PELANTIKAN"]);
					}
					$data["LAST_UPDATED"]   = date("Y-m-d");
					$this->riwayat_jabatan_model->skip_validation(true);
					// print_r($data);
					$result = $this->riwayat_jabatan_model->update($data_riwayat->ID, $data);
					log_activity_pegawai($nip_baru, $this->auth->user_id(), "Update sinkron data riwayat jabatan dari BKN " . $row->namaJabatan, 'pegawai');
				}
				$no++;
			}
		}
		return $result;
	}
	private function sinkron_pns_unor($pegawai_lokal = null)
	{
		$nip_baru = trim($pegawai_lokal->NIP_BARU);
		$data_cache = $this->cache->get('data_rwt_pnsunor_' . $nip_baru);
		$result = true;
		if ($data_cache != null) {
			foreach ($data_cache as $row) {
				$this->riwayat_pindah_unit_kerja_model->where("ID_UNOR_BARU", $row->unorBaru);
				$data_riwayat = $this->riwayat_pindah_unit_kerja_model->find_by("PNS_NIP", trim($nip_baru));
				$idPns 		= $row->idPns;
				if (!isset($data_riwayat->ID)) {
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

					if (empty($data["SK_TANGGAL"])) {
						unset($data["SK_TANGGAL"]);
					}
					$this->riwayat_pindah_unit_kerja_model->skip_validation(true);
					$result = $this->riwayat_pindah_unit_kerja_model->insert($data);
					log_activity_pegawai($nip_baru, $this->auth->user_id(), "sinkron data riwayat unor dari BKN " . $row->unorBaru, 'pegawai');
				}
			}
		}
		return $result;
	}
	private function sinkron_diklat($pegawai_lokal = null)
	{
		$nip_baru = trim($pegawai_lokal->NIP_BARU);
		$data_cache = $this->cache->get('rwt_diklat_' . $nip_baru);
		$result = true;
		if ($data_cache != null) {
			foreach ($data_cache as $row) {
				$this->db->group_start();
				$this->diklat_struktural_model->where("TANGGAL", date('Y-m-d', strtotime($row->tanggal)));
				$this->diklat_struktural_model->or_where("NAMA_DIKLAT", $row->latihanStrukturalNama);
				$this->db->group_end();
				$data_riwayat = $this->diklat_struktural_model->find_by("PNS_NIP", trim($nip_baru));
				$idPns 		= $row->idPns;
				if (!isset($data_riwayat->ID)) {
					$data = array();
					$data["PNS_ID"] 	= trim($pegawai_lokal->PNS_ID);
					$data["PNS_NIP"] 	= $pegawai_lokal->NIP_BARU;
					$data["PNS_NAMA"] 	= trim($pegawai_lokal->NAMA);

					$data["ID_DIKLAT"] = $row->latihanStrukturalId;
					$data["NAMA_DIKLAT"] = $row->latihanStrukturalNama;
					$data["NOMOR"] = $row->nomor;
					$data["TANGGAL"] = date('Y-m-d', strtotime($row->tanggal));
					$data["TAHUN"] 	= $row->tahun;

					if (empty($data["TANGGAL"])) {
						unset($data["TANGGAL"]);
					}
					$this->diklat_struktural_model->skip_validation(true);
					$result = $this->diklat_struktural_model->insert($data);
					log_activity_pegawai($nip_baru, $this->auth->user_id(), "sinkron data riwayat diklat dari BKN " . $row->latihanStrukturalNama, 'pegawai');
				}
			}
		}
		return $result;
	}
	private function sinkron_penghargaan($pegawai_lokal = null)
	{
		$nip_baru = trim($pegawai_lokal->NIP_BARU);
		$data_cache = $this->cache->get('data_penghargaan_' . $nip_baru);
		$result = true;
		if ($data_cache != null) {
			foreach ($data_cache as $row) {
				$this->db->group_start();
				$this->riwayat_penghargaan_model->where("SK_NOMOR", $row->skNomor);
				$this->riwayat_penghargaan_model->or_where("ID_JENIS_PENGHARGAAN", $row->jenisHarga);
				$this->db->group_end();
				$data_riwayat = $this->riwayat_penghargaan_model->find_by("PNS_NIP", trim($nip_baru));
				$idPns 		= $row->idPns;
				if (!isset($data_riwayat->ID)) {
					$data = array();
					$data["PNS_ID"] 	= trim($pegawai_lokal->PNS_ID);
					$data["PNS_NIP"] 	= $pegawai_lokal->NIP_BARU;
					$data["NAMA"] 	= trim($pegawai_lokal->NAMA);

					$data["ID_JENIS_PENGHARGAAN"] = $row->jenisHarga;
					$data["NAMA_JENIS_PENGHARGAAN"] = $row->namaHarga;
					$data["SK_TANGGAL"] = date('Y-m-d', strtotime($row->skDate)) ? date('Y-m-d', strtotime($row->skDate)) : null;
					$data["ID_BKN"] = $row->id;
					$data["SK_NOMOR"] = $row->skNomor;
					$data["TAHUN"] 	= $row->tahun;

					if (empty($data["SK_TANGGAL"])) {
						unset($data["SK_TANGGAL"]);
					}
					$this->riwayat_penghargaan_model->skip_validation(true);
					// die(json_encode($data));
					$result = $this->riwayat_penghargaan_model->insert($data);
					log_activity_pegawai($nip_baru, $this->auth->user_id(), "sinkron data riwayat penghargaan dari BKN " . $row->namaHarga, 'pegawai');
				}
			}
		}
		return $result;
	}
	public function sinkron_cache_hukdis()
	{
		$nip_baru   = $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU", $nip_baru);
		$result = $this->sinkron_data_hukdis($pegawai_lokal);
		if ($result) {
			$response['success'] = true;
			$response['msg'] = "Sukses sinkron data hukuman disiplin";
		} else {
			$response['success'] = false;
			$response['msg'] = "Ada kesalahan";
		}
		echo json_encode($response);
	}
	public function save_data_utama()
	{
		$this->load->model('pegawai/golongan_model');
		$kolom   = $this->input->post('kode');
		$nip_baru   = $this->input->post('nip');
		$pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU", $nip_baru);

		$this->load->library('Api_bkn');
		$api_lipi = new Api_bkn;

		$pegawai = $this->cache->get('data_utama_' . $nip_baru);
		$data       = array();
		// print_r($pegawai);
		$status = false;
		$msg = "";
		if ($kolom == "agamaId") {
			if ($pegawai->agamaId != "")
				$data["AGAMA_ID"]   =   isset($pegawai->agamaId)    ? (int)$pegawai->agamaId :  "";
		}
		if ($kolom == "akteKelahiran")
			$data["AKTE_KELAHIRAN"] =   isset($pegawai->akteKelahiran)  ?   $pegawai->akteKelahiran :   "";
		if ($kolom == "akteMeninggal")
			$data["AKTE_MENINGGAL"] =   isset($pegawai->akteMeninggal)  ?   $pegawai->akteMeninggal :   "";
		if ($kolom == "alamat")
			$data["ALAMAT"] =   isset($pegawai->alamat) ?   $pegawai->alamat :  "";
		if ($kolom == "bpjs")
			$data["BPJS"]   =   isset($pegawai->bpjs)   ?   $pegawai->bpjs :    "";
		if ($kolom == "email")
			$data["EMAIL"]  =   isset($pegawai->email)  ?   $pegawai->email :   "";
		if ($kolom == "gelarBelakang")
			$data["GELAR_BELAKANG"] =   isset($pegawai->gelarBelakang)  ?   $pegawai->gelarBelakang :   "";

		if ($kolom == "gelarDepan")
			$data["GELAR_DEPAN"]    =   isset($pegawai->gelarDepan) ?   $pegawai->gelarDepan :  "";
		if ($kolom == "golRuangAwal")
			$data["GOL_AWAL_ID"]    =   isset($pegawai->golRuangAwalId) ?   $pegawai->golRuangAwalId :  "";
		if ($kolom == "golRuangAkhirId")
			$data["GOL_ID"] =   isset($pegawai->golRuangAkhirId)    ?   $pegawai->golRuangAkhirId :     "";
		if ($kolom == "instansiIndukId")
			$data["INSTANSI_INDUK_ID"]  =   isset($pegawai->instansiIndukId)    ?   $pegawai->instansiIndukId :     "";
		if (isset($pegawai_lokal->INSTANSI_INDUK_NAMA) and $pegawai_lokal->INSTANSI_INDUK_NAMA == "" and $kolom == "instansiIndukNama")
			$data["INSTANSI_INDUK_NAMA"]    =   isset($pegawai->instansiIndukNama)  ?   $pegawai->instansiIndukNama :   "";
		if (isset($pegawai_lokal->INSTANSI_KERJA_ID) and $pegawai_lokal->INSTANSI_KERJA_ID == "" and $kolom == "instansiKerjaId")
			$data["INSTANSI_KERJA_ID"]  =   isset($pegawai->instansiKerjaId)    ?   $pegawai->instansiKerjaId :     "";
		if (isset($pegawai_lokal->INSTANSI_KERJA_NAMA) and $pegawai_lokal->INSTANSI_KERJA_NAMA == "" and $kolom == "instansiKerjaNama")
			$data["INSTANSI_KERJA_NAMA"]    =   isset($pegawai->instansiKerjaNama)  ?   $pegawai->instansiKerjaNama :   "";
		// if(isset($pegawai_lokal->JABATAN_ID) and $pegawai_lokal->JABATAN_ID == "" and $kolom == "JABATAN_ID") 
		//     $data["JABATAN_ID"] =   isset($pegawai->jabatanStrukturalId)    ?   $pegawai->jabatanStrukturalId :     "";

		if ($kolom == "jenisJabatanId")
			$data["JENIS_JABATAN_ID"]   =   isset($pegawai->jenisJabatanId) ?   $pegawai->jenisJabatanId :  "";
		if ($kolom == "jenisJabatan")
			$data["JENIS_JABATAN_NAMA"] =   isset($pegawai->jenisJabatan)   ?   $pegawai->jenisJabatan :    "";
		if ($kolom == "jenisKawinId")
			$data["JENIS_KAWIN_ID"] =   isset($pegawai->jenisKawinId)   ?   $pegawai->jenisKawinId :    "";

		if ($kolom == "jenisKelamin") {
			$jenis_kelamin = isset($pegawai->jenisKelamin)  ?   TRIM($pegawai->jenisKelamin) :  "";
			if ($jenis_kelamin == "Pria")
				$jk = "M";
			if ($jenis_kelamin == "Wanita")
				$jk = "F";

			$data["JENIS_KELAMIN"]  =   $jk;
			// $data["JENIS_KELAMIN"]   =   isset($pegawai->jenisKelamin)   ?   $pegawai->jenisKelamin :    "";
		}
		if ($kolom == "jenisPegawaiId")
			$data["JENIS_PEGAWAI_ID"]   =   isset($pegawai->jenisPegawaiId) ?   $pegawai->jenisPegawaiId :  "";
		if ($kolom == "jumlahAnak")
			$data["JML_ANAK"]   =   isset($pegawai->jumlahAnak) ?   $pegawai->jumlahAnak :  "";
		if ($kolom == "jumlahIstriSuami")
			$data["JML_ISTRI"]  =   isset($pegawai->jumlahIstriSuami)   ?   $pegawai->jumlahIstriSuami :    "";
		if ($kolom == "noSeriKarpeg")
			$data["KARTU_PEGAWAI"]  =   isset($pegawai->noSeriKarpeg)   ?   $pegawai->noSeriKarpeg :    "";
		if ($kolom == "kedudukanPnsId")
			$data["KEDUDUKAN_HUKUM_ID"] =   isset($pegawai->kedudukanPnsId) ?   $pegawai->kedudukanPnsId :  "";


		if ($kolom == "nama")
			$data["NAMA"]   =   isset($pegawai->nama)   ?   $pegawai->nama :    "";
		if ($kolom == "nik")
			$data["NIK"]    =   isset($pegawai->nik)    ?   $pegawai->nik :     "";

		if ($kolom == "noAskes")
			$data["NO_ASKES"]   =   isset($pegawai->noAskes)    ?   trim($pegawai->noAskes) :   "";
		if ($kolom == "noSuratKeteranganBebasNarkoba")
			$data["NO_BEBAS_NARKOBA"]   =   isset($pegawai->noSuratKeteranganBebasNarkoba)  ?   $pegawai->noSuratKeteranganBebasNarkoba :   "";
		if ($kolom == "skck")
			$data["NO_CATATAN_POLISI"]  =   isset($pegawai->skck)   ?   $pegawai->skck :    "";
		if ($kolom == "noSuratKeteranganDokter")
			$data["NO_SURAT_DOKTER"]    =   isset($pegawai->noSuratKeteranganDokter)    ?   $pegawai->noSuratKeteranganDokter :     "";
		if ($kolom == "noTaspen")
			$data["NO_TASPEN"]  =   isset($pegawai->noTaspen)   ?   trim($pegawai->noTaspen) :  "";
		if ($kolom == "noHp")
			$data["NOMOR_HP"]   =   isset($pegawai->noHp)   ?   $pegawai->noHp :    "";
		if ($kolom == "nomorSkCpns")
			$data["NOMOR_SK_CPNS"]  =   isset($pegawai->nomorSkCpns)    ?   $pegawai->nomorSkCpns :     "";

		if ($kolom == "noNpwp")
			$data["NPWP"]   =   isset($pegawai->noNpwp) ?   $pegawai->noNpwp :  "";
		if ($kolom == "pendidikanTerakhirId")
			$data["PENDIDIKAN"] =   isset($pegawai->pendidikanTerakhirId)   ?   $pegawai->pendidikanTerakhirId :    "";

		if ($kolom == "satuanKerjaIndukId")
			$data["SATUAN_KERJA_INDUK_ID"]  =   isset($pegawai->satuanKerjaIndukId) ?   $pegawai->satuanKerjaIndukId :  "";

		if ($kolom == "statusPegawai")
			$data["STATUS_CPNS_PNS"]    =   isset($pegawai->statusPegawai)  ?   $pegawai->statusPegawai :   "";
		if ($kolom == "statusHidup")
			$data["STATUS_HIDUP"]   =   isset($pegawai->statusHidup)    ?   $pegawai->statusHidup :     "";
		if ($kolom == "tempatLahir")
			$data["TEMPAT_LAHIR"]   =   isset($pegawai->tempatLahir)    ?   $pegawai->tempatLahir :     "";
		if ($kolom == "tempatLahir")
			$data["TEMPAT_LAHIR_ID"]    =   isset($pegawai->tempatLahirId)  ?   $pegawai->tempatLahirId :   "";
		if ($kolom == "tglSuratKeteranganBebasNarkoba")
			$data["TGL_BEBAS_NARKOBA"]  =   isset($pegawai->tglSuratKeteranganBebasNarkoba) ?   date('Y-m-d', strtotime($pegawai->tglSuratKeteranganBebasNarkoba)) :    "";
		if ($kolom == "tglSkck")
			$data["TGL_CATATAN_POLISI"] =   isset($pegawai->tglSkck)    ?   date('Y-m-d', strtotime($pegawai->tglSkck)) :   "";
		if ($kolom == "tglLahir")
			$data["TGL_LAHIR"]  =   isset($pegawai->tglLahir)   ?   date('Y-m-d', strtotime($pegawai->tglLahir)) :  "";
		if ($kolom == "tglMeninggal")
			$data["TGL_MENINGGAL"]  =   isset($pegawai->tglMeninggal)   ?   date('Y-m-d', strtotime($pegawai->tglMeninggal)) :  "";
		if ($kolom == "tglNpwp")
			$data["TGL_NPWP"]   =   isset($pegawai->tglNpwp)    ?   date('Y-m-d', strtotime($pegawai->tglNpwp)) :   "";
		if ($kolom == "tglSuratKeteranganDokter")
			$data["TGL_SURAT_DOKTER"]   =   isset($pegawai->tglSuratKeteranganDokter)   ?   date('Y-m-d', strtotime($pegawai->tglSuratKeteranganDokter)) :  "";

		if ($kolom == "tkPendidikanTerakhirId")
			$data["TK_PENDIDIKAN"]  =   isset($pegawai->tkPendidikanTerakhirId) ?   $pegawai->tkPendidikanTerakhirId :  "";
		if ($kolom == "tmtCpns")
			$data["TMT_CPNS"]   =   isset($pegawai->tmtCpns)    ?   date('Y-m-d', strtotime($pegawai->tmtCpns)) :   "";
		if ($kolom == "tglSkCpns")
			$data["TGL_SK_CPNS"]    =   isset($pegawai->tglSkCpns)  ?   date('Y-m-d', strtotime($pegawai->tglSkCpns)) :     "";
		if ($kolom == "tmtGolAkhir")
			$data["TMT_GOLONGAN"]   =   isset($pegawai->tmtGolAkhir)    ?   date('Y-m-d', strtotime($pegawai->tmtGolAkhir)) :   "";
		if ($kolom == "tmtJabatan")
			$data["TMT_JABATAN"]    =   isset($pegawai->tmtJabatan) ?   date('Y-m-d', strtotime($pegawai->tmtJabatan)) :    "";
		if ($kolom == "lokasiKerjaId")
			$data["LOKASI_KERJA_ID"]    =   isset($pegawai->lokasiKerjaId)  ?   $pegawai->lokasiKerjaId :   "";
		if ($pegawai_lokal->UNOR_ID == "" and $kolom == "tmtPns")
			$data["UNOR_ID"]    =   isset($pegawai->tmtPns) ?   $pegawai->tmtPns :  "";
		if ($pegawai_lokal->UNOR_ID == "" and $kolom == "unorId")
			$data["UNOR_ID"]    =   isset($pegawai->unorId) ?   $pegawai->unorId :  "";
		if ($pegawai_lokal->UNOR_INDUK_ID == "" and $kolom == "unorIndukId")
			$data["UNOR_INDUK_ID"]  =   isset($pegawai->unorIndukId)    ?   $pegawai->unorIndukId :     "";

		if (isset($pegawai_lokal->NIP_BARU)) {
			if (isset($pegawai->id) and $pegawai->id != "") {
				if ($this->pegawai_model->update_where("NIP_BARU", $pegawai_lokal->NIP_BARU, $data)) {
					$status = true;
					$msg = "Update Berhasil";
				} else {
					$status = false;
					$msg = "Update gagal";
				}
			} else {
				$status = false;
				$msg = "Data tidak ditemukan";
			}
		}
		$response['success'] = true;
		$response['id'] = $pegawai->id;
		$response['msg'] = "Update berhasil";
		echo json_encode($response);
		exit();
	}

	private function sinkron_data_ortu($pegawai_lokal = null)
	{
		$nip_baru = trim($pegawai_lokal->NIP_BARU);
		$data_cache = $this->cache->get('data_ortu_' . $nip_baru);
		$result = true;
		if ($data_cache != null) {
			if ($data_cache->ayah->id) {
				$row = $data_cache->ayah;
				$this->db->group_start();
				$this->orang_tua_model->where("\"NAMA\" ilike '" . trim($row->nama) . "'");
				$this->db->group_end();
				$data_riwayat = $this->orang_tua_model->find_by("NIP", trim($nip_baru));
				if (!isset($data_riwayat->ID)) {
					$data = array();
					$data["PNS_ID"] = trim($pegawai_lokal->PNS_ID);
					$data["NIP"] 	= $pegawai_lokal->NIP_BARU;
					$data["NAMA"] 	= trim($row->nama);
					$data["HUBUNGAN"] 	= 1;
					$data["TEMPAT_LAHIR"] 	= trim($row->tempatLahir);
					$data["TANGGAL_LAHIR"] 	= trim($row->tglLahir);
					$data["AKTE_MENINGGAL"] 	= trim($row->aktaMeninggal);
					$data["TGL_MENINGGAL"] 	= trim($row->tglMeninggal);
					$data["JENIS_KELAMIN"] 	= trim($row->jenisKelamin);

					if (empty($data['TGL_MENINGGAL'])) {
						unset($data['TGL_MENINGGAL']);
					}
					$this->orang_tua_model->skip_validation(true);
					$result = $this->orang_tua_model->insert($data);
					log_activity_pegawai($nip_baru, $this->auth->user_id(), "sinkron data orang tua dari BKN " . $data["NAMA"], 'pegawai');
				}
			}
			if ($data_cache->ibu->id) {
				$row = $data_cache->ibu;
				$this->db->group_start();
				$this->orang_tua_model->where("\"NAMA\" ilike '" . trim($row->nama) . "'");
				$this->db->group_end();
				$data_riwayat = $this->orang_tua_model->find_by("NIP", trim($nip_baru));
				if (!isset($data_riwayat->ID)) {
					$data = array();
					$data["PNS_ID"] = trim($pegawai_lokal->PNS_ID);
					$data["NIP"] 	= $pegawai_lokal->NIP_BARU;
					$data["NAMA"] 	= ucwords(strtolower(trim($row->nama)));
					$data["HUBUNGAN"] 	= 2;
					$data["TEMPAT_LAHIR"] 	= trim($row->tempatLahir);
					$data["TANGGAL_LAHIR"] 	= trim($row->tglLahir);
					$data["AKTE_MENINGGAL"] 	= trim($row->aktaMeninggal);
					$data["TGL_MENINGGAL"] 	= trim($row->tglMeninggal);
					$data["JENIS_KELAMIN"] 	= trim($row->jenisKelamin);

					if (empty($data['TGL_MENINGGAL'])) {
						unset($data['TGL_MENINGGAL']);
					}

					$this->orang_tua_model->skip_validation(true);
					$result = $this->orang_tua_model->insert($data);
					log_activity_pegawai($nip_baru, $this->auth->user_id(), "sinkron data orang tua dari BKN " . $data["NAMA"], 'pegawai');
				}
			}
		}
		return $result;
	}

	private function sinkron_data_pasangan($pegawai_lokal = null)
	{
		$nip_baru = trim($pegawai_lokal->NIP_BARU);
		$data_cache = $this->cache->get('data_pasangan_' . $nip_baru);
		$result = true;
		if ($data_cache != null) {
			foreach ($data_cache->listPasangan as $row) {
				$this->db->group_start();
				$this->istri_model->where("\"NAMA\" ilike '" . trim($row->dataOrang->nama) . "'");
				$this->db->group_end();
				$data_riwayat = $this->istri_model->find_by("NIP", trim($nip_baru));
				$idPns 		= $row->idPns;
				if (!isset($data_riwayat->ID)) {
					$data = array();
					$data["PNS_ID"] = trim($pegawai_lokal->PNS_ID);
					$data["NIP"] 	= $pegawai_lokal->NIP_BARU;
					$data["NAMA"] 	= ucwords(strtolower(trim($row->dataOrang->nama)));
					$data["TANGGAL_MENIKAH"] 	= trim($row->dataPernikahan->tgglMenikah);
					$data["AKTE_NIKAH"] 	= trim($row->dataPernikahan->aktaMenikah);
					$data["STATUS"] 	= $row->dataPernikahan->status;
					$data["PNS"] 	= $row->dataPernikahan->isPns ? 1 : 0;
					$data["HUBUNGAN"] 	= $pegawai_lokal->JENIS_KELAMIN == 'M' ? 1 : 2;
					$data["AKTE_CERAI"] 	= trim($row->dataPernikahan->aktaCerai);
					$data["TANGGAL_CERAI"] 	= trim($row->dataPernikahan->tgglCerai);
					$data["AKTE_MENINGGAL"] 	= trim($row->dataPernikahan->aktaMeninggal);
					$data["TANGGAL_MENINGGAL"] 	= trim($row->dataPernikahan->tgglMeninggal);
					$data["ORANG_ID"] 	= $row->dataPernikahan->orangId;

					if (empty($data["TANGGAL_MENIKAH"])) {
						unset($data["TANGGAL_MENIKAH"]);
					}
					if (empty($data["TANGGAL_CERAI"])) {
						unset($data["TANGGAL_CERAI"]);
					}
					if (empty($data["TANGGAL_MENINGGAL"])) {
						unset($data["TANGGAL_MENINGGAL"]);
					}

					$this->istri_model->skip_validation(true);
					// die(json_encode($data));
					$result = $this->istri_model->insert($data);
					log_activity_pegawai($nip_baru, $this->auth->user_id(), "sinkron data pasangan dari BKN " . $data["NAMA"], 'pegawai');
				}
			}
		}
		return $result;
	}
	private function sinkron_data_anak($pegawai_lokal = null)
	{
		$this->load->helper('aplikasi');
		$nip_baru = trim($pegawai_lokal->NIP_BARU);
		$data_cache = $this->cache->get('data_anak_' . $nip_baru);
		$result = true;
		if ($data_cache != null) {
			foreach ($data_cache->listAnak as $row) {
				$this->db->group_start();
				$this->anak_model->where("\"NAMA\" ilike '" . trim($row->nama) . "'");
				$this->db->group_end();
				$data_riwayat = $this->anak_model->find_by("NIP", trim($nip_baru));
				$idPns 		= $row->idPns;
				if (!isset($data_riwayat->ID)) {
					$pasangan = $this->istri_model->find_by('ORANG_ID', $pegawai_lokal->JENIS_KELAMIN == 'M' ? $row->ibuId : $row->ayahId);
					$data = array();
					$data["PNS_ID"] = trim($pegawai_lokal->PNS_ID);
					$data["NIP"] 	= $pegawai_lokal->NIP_BARU;
					$data["NAMA"] 	= ucwords(strtolower(trim($row->nama)));
					$data["JENIS_KELAMIN"] 	= trim($row->jenisKelamin) == "Pria" ? "M" : (trim($row->jenisKelamin) == "Wanita" ? "F" : null);
					$data["TANGGAL_LAHIR"] 	= trim($row->tglLahir) ? convertDate($row->tglLahir, 'd-m-Y', 'Y-m-d') : null;
					$data["TEMPAT_LAHIR"] 	= ucwords(strtolower(trim($row->tempatLahir)));
					$data["STATUS_ANAK"] 	= trim($row->jenisAnak) == "-" ? null : (trim($row->jenisAnak) == "KANDUNG" ? 1 : 2);
					$data["PASANGAN"] 	= $pasangan->ID ? $pasangan->ID : null;


					$this->anak_model->skip_validation(true);
					$result = $this->anak_model->insert($data);
					log_activity_pegawai($nip_baru, $this->auth->user_id(), "sinkron data anak dari BKN " . $data["NAMA"], 'pegawai');
				}
			}
		}
		return $result;
	}
	private function sinkron_data_angka_kredit($pegawai_lokal = null)
	{
		$this->load->helper('aplikasi');
		$nip_baru = trim($pegawai_lokal->NIP_BARU);
		$data_cache = $this->cache->get('data_angka_kredit_' . $nip_baru);
		$result = true;
		if ($data_cache != null) {
			foreach ($data_cache as $row) {
				$this->db->group_start();
				$this->riwayat_angka_kredit_model->where('no_sk', $row->nomorSk);
				$this->db->group_end();
				$data_riwayat = $this->riwayat_angka_kredit_model->find_by("nip", trim($nip_baru));
				$idPns 		= $row->pns;
				if (!isset($data_riwayat->id)) {
					$data = array();
					$data['pns_id'] = trim($pegawai_lokal->PNS_ID);
					$data['nip'] 	= $pegawai_lokal->NIP_BARU;
					$data['nama'] 	= ucwords(strtolower(trim($pegawai_lokal->nama)));
					$data['no_sk']  = $row->nomorSk;
					$data['tgl_sk']  = $row->tanggalSk;
					$data['bln_mulai']  = $row->bulanMulaiPenailan;
					$data['thn_mulai']  = $row->tahunMulaiPenailan;
					$data['bln_selesai']  = $row->bulanSelesaiPenailan;
					$data['thn_selesai']  = $row->tahunSelesaiPenailan;
					$data['ak_utama_baru']  = $row->kreditUtamaBaru;
					$data['ak_penunjang_baru']  = $row->kreditPenunjangBaru;
					$data['ak_baru_total']  = $row->kreditBaruTotal;
					$data['nama_jabatan']  = $row->namaJabatan;
					$data['ak_pertama']  = (int) $row->isAngkaKreditPertama;
					$data['rwt_jabatan_id']  = $row->rwJabatan;
					$data['is_sync_bkn'] = 1;
					if ($data['nama_jabatan'] == '-')
						unset($data['nama_jabatan']);
					$this->riwayat_angka_kredit_model->skip_validation(true);
					$result = $this->riwayat_angka_kredit_model->insert($data);
					log_activity_pegawai($nip_baru, $this->auth->user_id(), "sinkron data angka kredit dari BKN NIP:" . $data['nip'] . " nomor SK :" . $data['no_sk'], 'pegawai');
				} else {
					$data = array();
					$data['pns_id'] = trim($pegawai_lokal->PNS_ID);
					$data['nip'] 	= $pegawai_lokal->NIP_BARU;
					$data['nama'] 	= ucwords(strtolower(trim($pegawai_lokal->nama)));
					$data['no_sk']  = $row->nomorSk;
					$data['tgl_sk']  = $row->tanggalSk;
					$data['bln_mulai']  = $row->bulanMulaiPenailan;
					$data['thn_mulai']  = $row->tahunMulaiPenailan;
					$data['bln_selesai']  = $row->bulanSelesaiPenailan;
					$data['thn_selesai']  = $row->tahunSelesaiPenailan;
					$data['ak_utama_baru']  = $row->kreditUtamaBaru;
					$data['ak_penunjang_baru']  = $row->kreditPenunjangBaru;
					$data['ak_baru_total']  = $row->kreditBaruTotal;
					$data['nama_jabatan']  = $row->namaJabatan;
					$data['ak_pertama']  = (int) $row->isAngkaKreditPertama;
					$data['rwt_jabatan_id']  = $row->rwJabatan;
					$data['is_sync_bkn'] = 1;

					$this->riwayat_angka_kredit_model->skip_validation(true);
					$result = $this->riwayat_angka_kredit_model->update($data_riwayat->id, $data);
					log_activity_pegawai($nip_baru, $this->auth->user_id(), "sinkron data angka kredit dari BKN NIP:" . $data['nip'] . " nomor SK :" . $data['no_sk'], 'pegawai');
				}
			}
		}
		return $result;
	}

	private function sinkron_data_hukdis($pegawai_lokal = null)
	{
		$this->load->helper('aplikasi');
		$nip_baru = trim($pegawai_lokal->NIP_BARU);
		$data_cache = $this->cache->get('data_rwt_hukdis_' . $nip_baru);
		$result = true;
		if ($data_cache != null) {
			foreach ($data_cache as $row) {
				$this->db->group_start();
				$this->riwayat_huk_dis_model->where('SK_NOMOR', $row->skNomor);
				$this->db->group_end();
				$data_riwayat = $this->riwayat_huk_dis_model->find_by("PNS_NIP", trim($nip_baru));
				$idPns 		= $row->pns;
				if (!isset($data_riwayat->id)) {
					$data = array();
					$data['PNS_ID'] = trim($pegawai_lokal->PNS_ID);
					$data['PNS_NIP'] 	= $pegawai_lokal->NIP_BARU;
					$data['NAMA'] 	= ucwords(strtolower(trim($pegawai_lokal->nama)));
					$data['SK_NOMOR']  = $row->skNomor;
					$data['SK_TANGGAL']  = $row->skTanggal;
					$data['ID_JENIS_HUKUMAN']  = $row->jenisHukuman;
					$data['NAMA_JENIS_HUKUMAN']  = (int) $row->jenisHukumanNama;
					$data['TANGGAL_MULAI_HUKUMAN']  = $row->hukumanTanggal;
					$data['TANGGAL_AKHIR_HUKUMAN']  = $row->akhirHukumTanggal;
					$data['MASA_TAHUN']  = $row->masaTahun;
					$data['MASA_BULAN']  = $row->masaBulan;
					$data['NO_PP']  = $row->nomorPp;
					$data['NO_SK_PEMBATALAN']  = $row->skPembatalanNomor;
					$data['TANGGAL_SK_PEMBATALAN']  = $row->skPembatalanTanggal;
					$data['bkn_id'] = $row->id;
					if (!isDateFormat($data['SK_TANGGAL']))
						unset($data['SK_TANGGAL']);

					if (!isDateFormat($data['TANGGAL_MULAI_HUKUMAN']))
						unset($data['TANGGAL_MULAI_HUKUMAN']);

					if (!isDateFormat($data['TANGGAL_AKHIR_HUKUMAN']))
						unset($data['TANGGAL_AKHIR_HUKUMAN']);

					if (!isDateFormat($data['TANGGAL_SK_PEMBATALAN']))
						unset($data['TANGGAL_SK_PEMBATALAN']);

					$this->riwayat_huk_dis_model->skip_validation(true);
					$result = $this->riwayat_huk_dis_model->insert($data);
					log_activity_pegawai($nip_baru, $this->auth->user_id(), "sinkron data hukdis dari BKN NIP:" . $data['nip'] . " nomor SK :" . $data['no_sk'], 'pegawai');
				} else {
					$data = array();
					$data['PNS_ID'] = trim($pegawai_lokal->PNS_ID);
					$data['PNS_NIP'] 	= $pegawai_lokal->NIP_BARU;
					$data['NAMA'] 	= ucwords(strtolower(trim($pegawai_lokal->nama)));
					$data['SK_NOMOR']  = $row->skNomor;
					$data['SK_TANGGAL']  = $row->skTanggal;
					$data['ID_JENIS_HUKUMAN']  = $row->jenisHukuman;
					$data['NAMA_JENIS_HUKUMAN']  = (int) $row->jenisHukumanNama;
					$data['TANGGAL_MULAI_HUKUMAN']  = $row->hukumanTanggal;
					$data['TANGGAL_AKHIR_HUKUMAN']  = $row->akhirHukumTanggal;
					$data['MASA_TAHUN']  = $row->masaTahun;
					$data['MASA_BULAN']  = $row->masaBulan;
					$data['NO_PP']  = $row->nomorPp;
					$data['NO_SK_PEMBATALAN']  = $row->skPembatalanNomor;
					$data['TANGGAL_SK_PEMBATALAN']  = $row->skPembatalanTanggal;
					$data['bkn_id'] = $row->id;

					if (!isDateFormat($data['SK_TANGGAL']))
						unset($data['SK_TANGGAL']);

					if (!isDateFormat($data['TANGGAL_MULAI_HUKUMAN']))
						unset($data['TANGGAL_MULAI_HUKUMAN']);

					if (!isDateFormat($data['TANGGAL_AKHIR_HUKUMAN']))
						unset($data['TANGGAL_AKHIR_HUKUMAN']);

					if (!isDateFormat($data['TANGGAL_SK_PEMBATALAN']))
						unset($data['TANGGAL_SK_PEMBATALAN']);

					$this->riwayat_huk_dis_model->skip_validation(true);
					$result = $this->riwayat_huk_dis_model->update($data_riwayat->id, $data);
					log_activity_pegawai($nip_baru, $this->auth->user_id(), "sinkron data hukdis dari BKN NIP:" . $data['nip'] . " nomor SK :" . $data['no_sk'], 'pegawai');
				}
			}
		}
		return $result;
	}


	// upload data ke BKN
	public function uploadNilaiSkp($nip_baru, $tahun)
	{
		$result = $this->cek_prestasi_kerja($nip_baru, $tahun);
		if ($result) {
			if ($tahun <= 2020) {
				$param['id'] = $result->BKN_ID;
				$param['tahun'] = $result->TAHUN;
				$param['nilaiSkp'] = $result->NILAI_SKP;
				$param['orientasiPelayanan'] = $result->PERILAKU_ORIENTASI_PELAYANAN;
				$param['integritas'] = $result->PERILAKU_INTEGRITAS;
				$param['komitmen'] = $result->PERILAKU_KOMITMEN;
				$param['disiplin'] = $result->PERILAKU_DISIPLIN;
				$param['kerjasama'] = $result->PERILAKU_KERJASAMA;
				$param['nilaiPerilakuKerja'] = $result->NILAI_PERILAKU;
				$param['nilaiPrestasiKerja'] = $result->NILAI_PPK;
				$param['kepemimpinan'] = $result->PERILAKU_KEPEMIMPINAN ? $result->PERILAKU_KEPEMIMPINAN : 0;
				$param['jumlah'] = $result->PERILAKU_INTEGRITAS + $result->PERILAKU_KOMITMEN + $result->PERILAKU_DISIPLIN + $result->PERILAKU_KERJASAMA + $result->PERILAKU_ORIENTASI_PELAYANAN + $result->PERILAKU_KEPEMIMPINAN;
				$rata_rata = $result->PERILAKU_KEPEMIMPINAN == 0 ? $param['jumlah'] / 5 : $param['jumlah'] / 6;
				$param['nilairatarata'] = number_format((float)$rata_rata, 2, '.', '');
				$param['atasanPejabatPenilai'] = $result->atasan_penilai ? $result->atasan_penilai->bkn_id : null;
				$param['pejabatPenilai'] = $result->ATASAN_LANGSUNG_PNS_ID;
				$param['pnsDinilaiOrang'] = $result->PNS_ID;
				$param['penilaiNipNrp'] = $result->ATASAN_LANGSUNG_PNS_NIP;
				$param['atasanPenilaiNipNrp'] = $result->ATASAN_ATASAN_LANGSUNG_PNS_NIP;
				$param['penilaiNama'] = $result->ATASAN_LANGSUNG_PNS_NAMA;
				$param['atasanPenilaiNama'] = $result->ATASAN_ATASAN_LANGSUNG_PNS_NAMA;
				$param['penilaiUnorNama'] = $result->UNOR_PENILAI;
				$param['atasanPenilaiUnorNama'] = $result->UNOR_ATASAN_PENILAI;
				$param['penilaiJabatan'] = $result->ATASAN_LANGSUNG_PNS_JABATAN;
				$param['atasanPenilaiJabatan'] = $result->ATASAN_ATASAN_LANGSUNG_PNS_JABATAN;
				$param['penilaiGolongan'] = $result->GOL_PENILAI;
				$param['atasanPenilaiGolongan'] = $result->GOL_ATASAN_PENILAI;
				$param['penilaiTmtGolongan'] = $result->TMT_GOL_PENILAI;
				$param['atasanPenilaiTmtGolongan'] = $result->TMT_GOL_ATASAN_PENILAI;
				$param['statusPenilai'] = $result->PENILAI_PNS == 1 ? "PNS" : "";
				$param['statusAtasanPenilai'] = $result->ATASAN_PENILAI_PNS == 1 ? "PNS" : "";
				$param['jenisJabatan'] = $result->JABATAN_TIPE;
				$param['pnsUserId'] = "A8ACA74839063912E040640A040269BB";


				$this->load->library('Api_bkn');
				$api_bkn = new Api_bkn;
				$result = $api_bkn->postData('skp', $param);

				if ($result->success) {
				} else {
				}

				$response['success'] = $result->success;
				$response['id'] = $result->mapData;
				$response['msg'] = $result->message;
			} else {
				$response['success'] = false;
				$response['id'] = 0;
				$response['msg'] = "Masih dalam pengembangan";
			}
		}
	}
	//end 

	public function uploadRwAngkaKreditMultiple()
	{
		$ids = $this->input->post('id_data');
		if ($this->auth->has_permission('Riwayatangkakredit.Kepegawaian.UploadBkn')) {
			$error = array();
			foreach ($ids as $id) {
				$result = $this->uploadRwAngkaKredit($id, true);
				if (!$result['success']) {
					$error[] = $result;
				}
			}

			if (sizeof($error) > 0) {
				$response['success'] = false;
				$msg = "";
				foreach ($error as $er) {
					$msg .= $er['msg'] . '\\n';
				}
				$response['msg'] = $msg;
			} else {
				$response['success'] = true;
				$response['msg'] = "Berhasil upload data ke BKN";
			}
			echo json_encode($response);
		}
	}
	public function uploadRwAngkaKredit($id, $is_multiple = false)
	{
		$response = array();
		$response['success'] = false;
		$response['id'] = $id;
		$response['msg'] = "Masih dalam pengembangan";
		if ($this->auth->has_permission('Riwayatangkakredit.Kepegawaian.UploadBkn')) {
			$result = $this->riwayat_angka_kredit_model->find($id);
			if ($result) {
				$param['id'] = $result->bkn_id;
				$param['pnsId'] = $result->pns_id;
				$param['nomorSk'] = $result->no_sk;
				$param['tanggalSk'] = convertDate($result->tgl_sk, 'Y-m-d', 'd-m-Y'); //reformat d-m-Y
				$param['bulanMulaiPenilaian'] = $result->bln_mulai;
				$param['tahunMulaiPenilaian'] = $result->thn_mulai;
				$param['bulanSelesaiPenilaian'] = $result->bln_selesai;
				$param['tahunSelesaiPenilaian'] = $result->thn_selesai;
				$param['kreditUtamaBaru'] = $result->ak_utama_baru;
				$param['kreditPenunjangBaru'] = $result->ak_penunjang_baru;
				$param['kreditBaruTotal'] = $result->ak_baru_total;
				$param['rwJabatanId'] = $result->rwt_jabatan_id;
				$param['isAngkaKreditPertama'] = $result->ak_pertama;
				$param['namaJabatan'] = $result->ak_pertama;
				$param['pnsUserId'] = "A8ACA74839063912E040640A040269BB";
				$this->load->library('Api_bkn');
				$api_bkn = new Api_bkn;
				$result = $api_bkn->postData('angkakredit', $param);
				$response['success'] = $result->success;
				$response['msg'] = $param['nomorSk'] . ' ' . isset($result->message) ? $result->message : "gagal";
				if ($result->success) {
					$data_update = array(
						'is_sync_bkn' => 1,
						'bkn_id' => $result->mapData->rwAngkaKreditId
					);
					$this->riwayat_angka_kredit_model->update($id, $data_update);
					$response['id'] = $result->mapData->rwAngkaKreditId;
				}
				log_activity($this->auth->user_id(), 'Integrasi data AK ke BKN ' . json_encode($param) . ' response ' . json_encode($result), 'Integrasi');
			}
			if ($is_multiple)
				return $response;
			else
				echo json_encode($response);
		}
		return $response;
	}

	public function uploadRwKursusMultiple()
	{
		$ids = $this->input->post('id_data');
		if ($this->auth->has_permission('Riwayatkursus.Kepegawaian.UploadBkn')) {
			$error = array();
			foreach ($ids as $id) {
				$result = $this->uploadRwKursus($id, true);
				if (!$result['success']) {
					$error[] = $result;
				}
			}

			if (sizeof($error) > 0) {
				$response['success'] = false;
				$msg = "";
				foreach ($error as $er) {
					$msg .= $er['msg'] . '\\n';
				}
				$response['msg'] = $msg;
			} else {
				$response['success'] = true;
				$response['msg'] = "Berhasil upload data ke BKN";
			}
			echo json_encode($response);
		}
	}
	public function uploadRwKursus($id, $is_multiple = false)
	{
		$response = array();
		$response['success'] = false;
		$response['id'] = $id;
		$response['msg'] = "Masih dalam pengembangan";
		if ($this->auth->has_permission('Riwayatkursus.Kepegawaian.UploadBkn')) {
			$result = $this->riwayat_kursus_model->getUnsyncBkn($id);
			if (sizeof($result) > 0) {
				$result = $result[0];
				$api_bkn = new Api_bkn;
				$path = null;
				if ($result->id_arsip) {
					$arsip = $this->arsip_digital_model->find($result->id_arsip);
					$aws = new Api_s3_aws;
					$url = $aws->preSignUrl('simpeg', $arsip->location . $arsip->name);
					$upload_result = $api_bkn->uploadDokumen($url, 874);
					if (isset($upload_result->data)) {
						$path = $upload_result->data;
					}
				}
				$param['id'] = $result->bknId;
				$param['instansiId'] = $result->instansiId;
				$param['institusiPenyelenggara'] = $result->institusiPenyelenggara;
				$param['jenisDiklatId'] = $result->jenisDiklatId;
				$param['jenisKursus'] = $result->jenisKursus;
				$param['jenisKursusSertipikat'] = $result->jenisKursusSertipikat;
				$param['jumlahJam'] = $result->jumlahJam ? intval($result->jumlahJam) : 0;
				$param['lokasiId'] = $result->lokasiId;
				$param['namaKursus'] = $result->namaKursus;
				$param['nomorSertipikat'] = $result->nomorSertipikat;
				$param['pnsOrangId'] = $result->pnsOrangId;
				$param['tahunKursus'] = intval($result->tahunKursus);
				$param['tanggalKursus'] = convertDate($result->tanggalKursus, 'Y-m-d', 'd-m-Y'); //reformat d-m-Y
				$param['tanggalSelesaiKursus'] = convertDate($result->tanggalSelesaiKursus, 'Y-m-d', 'd-m-Y'); //reformat d-m-Y
				$param['path'] = $path;

				$post_result = $api_bkn->postData('kursus', $param);
				$response['success'] = $post_result->success;
				$response['msg'] = $param['nomorSk'] . ' ' . isset($post_result->message) ? $post_result->message : "gagal";
				if ($post_result->success) {
					$data_update = array(
						'sync_bkn' => 1,
						'BKN_ID' => $post_result->mapData->rwKursusId
					);
					$this->riwayat_kursus_model->update($id, $data_update);
					$response['id'] = $result->mapData->rwKursusId;
					log_activity($this->auth->user_id(), 'Integrasi data Kursus ke BKN ' . json_encode($param) . ' response ' . json_encode($result), 'Integrasi');
				}
			}
			if ($is_multiple)
				return $response;
			else
				echo json_encode($response);
		}
		return $response;
	}
	public function getpegawaibknnew()
    {
        $this->load->model('pegawai/golongan_model');
        $adataunor  = $this->genCacheUnorAll();
        $agolongan  = $this->genPangkat();
        $nip_baru   = $this->input->post('nip_bkn');
        $nips= explode(",",$nip_baru);
        $jumlahSukses = 0;
        $jumlahGagal = 0;
		$msg = "";
        foreach ($nips as $nip_baru) {
            $pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU",$nip_baru);       
            // get data utama
            $pegawai = $this->getdata_utama_bkn($nip_baru);
            $data       = array();
            $hasil = false;
            $msg =  "";
            if(!isset($pegawai->id)){
                $response ['success']= false;
                $response ['msg']= "Data BKN dengan NIP {$nip_baru} tidak ditemukan";
                $jumlahGagal++;
            }else{
                
                if(isset($pegawai->id) and $pegawai->id != "" and isset($pegawai_lokal->NIP_BARU))
                {
                    // if(isset($pegawai->id) and $pegawai->id != "") 
                        $data["PNS_ID"] =   isset($pegawai->id) ?   $pegawai->id :  "";

                    // if(isset($pegawai_lokal->AGAMA_ID) and $pegawai_lokal->AGAMA_ID == "") 
                        $data["AGAMA_ID"]   =   isset($pegawai->agamaId)    ?   $pegawai->agamaId :     "";
                    // if(isset($pegawai_lokal->AKTE_KELAHIRAN) and $pegawai_lokal->AKTE_KELAHIRAN == "") 
                        $data["AKTE_KELAHIRAN"] =   isset($pegawai->akteKelahiran)  ?   $pegawai->akteKelahiran :   "";
                    // if(isset($pegawai_lokal->AKTE_MENINGGAL) and $pegawai_lokal->AKTE_MENINGGAL == "") 
                        $data["AKTE_MENINGGAL"] =   isset($pegawai->akteMeninggal)  ?   $pegawai->akteMeninggal :   "";
                    // if(isset($pegawai_lokal->ALAMAT) and $pegawai_lokal->ALAMAT == "") 
                        $data["ALAMAT"] =   isset($pegawai->alamat) ?   $pegawai->alamat :  "";
                    // if(isset($pegawai_lokal->BPJS) and $pegawai_lokal->BPJS == "") 
                        $data["BPJS"]   =   isset($pegawai->bpjs)   ?   $pegawai->bpjs :    "";
                    // if(isset($pegawai_lokal->EMAIL) and $pegawai_lokal->EMAIL == "") 
                        $data["EMAIL"]  =   isset($pegawai->email)  ?   $pegawai->email :   "";
                    // if(isset($pegawai_lokal->GELAR_BELAKANG) and $pegawai_lokal->GELAR_BELAKANG == "") 
                        $data["GELAR_BELAKANG"] =   isset($pegawai->gelarBelakang)  ?   $pegawai->gelarBelakang :   "";

                    // if(isset($pegawai_lokal->GELAR_DEPAN) and $pegawai_lokal->GELAR_DEPAN == "") 
                        $data["GELAR_DEPAN"]    =   isset($pegawai->gelarDepan) ?   $pegawai->gelarDepan :  "";
                    // if(isset($pegawai_lokal->GOL_AWAL_ID) and $pegawai_lokal->GOL_AWAL_ID == "") 
                        $data["GOL_AWAL_ID"]    =   isset($pegawai->golRuangAwalId) ?   $pegawai->golRuangAwalId :  "";
                    // if(isset($pegawai_lokal->GOL_ID) and (trim($pegawai_lokal->GOL_ID) == "" or trim($pegawai_lokal->GOL_ID) == "0")) 
                        $data["GOL_ID"] =   isset($pegawai->golRuangAkhirId)    ?   $pegawai->golRuangAkhirId :     "";
                    // if(isset($pegawai_lokal->INSTANSI_INDUK_ID) and $pegawai_lokal->INSTANSI_INDUK_ID == "") 
                         $data["INSTANSI_INDUK_ID"]  =   isset($pegawai->instansiIndukId)    ?   $pegawai->instansiIndukId :     "";
                    // if(isset($pegawai_lokal->INSTANSI_INDUK_NAMA) and $pegawai_lokal->INSTANSI_INDUK_NAMA == "") 
                         $data["INSTANSI_INDUK_NAMA"]    =   isset($pegawai->instansiIndukNama)  ?   $pegawai->instansiIndukNama :   "";
                    // if(isset($pegawai_lokal->INSTANSI_KERJA_ID) and $pegawai_lokal->INSTANSI_KERJA_ID == "") 
                         $data["INSTANSI_KERJA_ID"]  =   isset($pegawai->instansiKerjaId)    ?   $pegawai->instansiKerjaId :     "";
                    // if(isset($pegawai_lokal->INSTANSI_KERJA_NAMA) and $pegawai_lokal->INSTANSI_KERJA_NAMA == "") 
                         $data["INSTANSI_KERJA_NAMA"]    =   isset($pegawai->instansiKerjaNama)  ?   $pegawai->instansiKerjaNama :   "";
                    
						 $data["MASA_KERJA"]  =  isset($pegawai->masaKerja)  ?   $pegawai->masaKerja :   "";
					/**
					 * Echa was here
					 */
					if(isset($pegawai->unorId)){
						$data["UNOR_ID"]  =   isset($pegawai->unorId)    ?   $pegawai->unorId :     "";
					}
					
					if(isset($pegawai->unorIndukId)){
						$data["UNOR_INDUK_ID"]  =   isset($pegawai->unorIndukId)    ?   $pegawai->unorIndukId :     "";
					}
					
					 

                    $jabatanId = $pegawai->jabatanFungsionalUmumId;
                    if($pegawai->jabatanFungsionalId != ""){
						$jabatanId = $pegawai->jabatanFungsionalId;
					}
                        
                    $datajabatan_real = $this->jabatan_model->find_by("KODE_BKN",trim($jabatanId));
                    // print_r($datajabatan_real);
                    if(isset($datajabatan_real->KODE_JABATAN) and $datajabatan_real->KODE_JABATAN != "") {
                        $data["JABATAN_INSTANSI_REAL_ID"]   =   isset($datajabatan_real->KODE_JABATAN)  ?   $datajabatan_real->KODE_JABATAN :   "";
                        $data["JABATAN_ID"] =   isset($datajabatan_real->KODE_JABATAN)  ?   $datajabatan_real->KODE_JABATAN :   "";
                        $data["JABATAN_INSTANSI_ID"] =   isset($datajabatan_real->KODE_JABATAN)  ?   $datajabatan_real->KODE_JABATAN :   "";
                    }

                    //if(isset($pegawai->jenisJabatanId) and $pegawai->jenisJabatanId != "") 
                        $data["JENIS_JABATAN_ID"]   =   isset($pegawai->jenisJabatanId) ?   $pegawai->jenisJabatanId :  "";
                    //if(isset($pegawai->jenisJabatan) and $pegawai->jenisJabatan != "") 
                        $data["JENIS_JABATAN_NAMA"] =   isset($pegawai->jenisJabatan)   ?   $pegawai->jenisJabatan :    "";
                    // if(isset($pegawai_lokal->JENIS_KAWIN_ID) and $pegawai_lokal->JENIS_KAWIN_ID == "") 
                        $data["JENIS_KAWIN_ID"] =   isset($pegawai->jenisKawinId)   ?   $pegawai->jenisKawinId :    "";

                    if(isset($pegawai_lokal->JENIS_KELAMIN) and $pegawai_lokal->JENIS_KELAMIN == ""){
                        $jenis_kelamin = isset($pegawai->jenisKelamin)  ?   TRIM($pegawai->jenisKelamin) :  "";
                        if($jenis_kelamin == "Pria")
                            $jk = "M";
                        if($jenis_kelamin == "Wanita")
                            $jk = "F";

                        $data["JENIS_KELAMIN"]  =   $jk;
                    } 
                        
                    // if(isset($pegawai_lokal->JENIS_PEGAWAI_ID) and $pegawai_lokal->JENIS_PEGAWAI_ID == "") 
                        $data["JENIS_PEGAWAI_ID"]   =   isset($pegawai->jenisPegawaiId) ?   $pegawai->jenisPegawaiId :  "";
                    // if(isset($pegawai_lokal->JML_ANAK) and $pegawai_lokal->JML_ANAK == "") 
                        $data["JML_ANAK"]   =   isset($pegawai->jumlahAnak) ?   $pegawai->jumlahAnak :  "";
                    // if(isset($pegawai_lokal->JML_ISTRI) and $pegawai_lokal->JML_ISTRI == "") 
                        $data["JML_ISTRI"]  =   isset($pegawai->jumlahIstriSuami)   ?   $pegawai->jumlahIstriSuami :    "";
                    // if(isset($pegawai_lokal->KARTU_PEGAWAI) and $pegawai_lokal->KARTU_PEGAWAI == "") 
                        $data["KARTU_PEGAWAI"]  =   isset($pegawai->noSeriKarpeg)   ?   $pegawai->noSeriKarpeg :    "";
                    // if(isset($pegawai_lokal->KEDUDUKAN_HUKUM_ID) and $pegawai_lokal->KEDUDUKAN_HUKUM_ID == "") 
                        $data["KEDUDUKAN_HUKUM_ID"] =   isset($pegawai->kedudukanPnsId) ?   $pegawai->kedudukanPnsId :  "";
                    // if(isset($pegawai_lokal->KODECEPAT) and $pegawai_lokal->KODECEPAT == "") 
                        $data["KODECEPAT"]  =   isset($pegawai->instansiKerjaKodeCepat) ?   $pegawai->instansiKerjaKodeCepat :  "";
                    // if(isset($pegawai_lokal->KPKN_ID) and $pegawai_lokal->KPKN_ID == "") 
                        $data["KPKN_ID"]    =   isset($pegawai->kpknId) ?   $pegawai->kpknId :  "";
                    // if(isset($pegawai_lokal->MK_BULAN) and $pegawai_lokal->MK_BULAN == "") 
                        $data["MK_BULAN"]   =   isset($pegawai->mkBulan)    ?   $pegawai->mkBulan :     "";
                    // if(isset($pegawai_lokal->MK_TAHUN) and $pegawai_lokal->MK_TAHUN == "") 
                        $data["MK_TAHUN"]   =   isset($pegawai->mkTahun)    ?   $pegawai->mkTahun :     "";
                    // if(isset($pegawai_lokal->NAMA) and $pegawai_lokal->NAMA == "") 
                        $data["NAMA"]   =   isset($pegawai->nama)   ?   $pegawai->nama :    "";
                    // if(isset($pegawai_lokal->NIK) and $pegawai_lokal->NIK == "") 
                        $data["NIK"]    =   isset($pegawai->nik)    ?   $pegawai->nik :     "";
                    // if(isset($pegawai_lokal->NIP_LAMA) and $pegawai_lokal->NIP_LAMA == "") 
                        $data["NIP_LAMA"]   =   isset($pegawai->nipLama)    ?   $pegawai->nipLama :     "";
                    // if(isset($pegawai_lokal->NO_ASKES) and $pegawai_lokal->NO_ASKES == "") 
                        $data["NO_ASKES"]   =   isset($pegawai->noAskes)    ?   $pegawai->noAskes :     "";
                    // if(isset($pegawai_lokal->NO_BEBAS_NARKOBA) and $pegawai_lokal->NO_BEBAS_NARKOBA == "") 
                        $data["NO_BEBAS_NARKOBA"]   =   isset($pegawai->noSuratKeteranganBebasNarkoba)  ?   $pegawai->noSuratKeteranganBebasNarkoba :   "";
                    // if(isset($pegawai_lokal->NO_CATATAN_POLISI) and $pegawai_lokal->NO_CATATAN_POLISI == "") 
                        $data["NO_CATATAN_POLISI"]  =   isset($pegawai->skck)   ?   $pegawai->skck :    "";
                    // if(isset($pegawai_lokal->NO_SURAT_DOKTER) and $pegawai_lokal->NO_SURAT_DOKTER == "") 
                        $data["NO_SURAT_DOKTER"]    =   isset($pegawai->noSuratKeteranganDokter)    ?   $pegawai->noSuratKeteranganDokter :     "";
                    // if(isset($pegawai_lokal->NO_TASPEN) and $pegawai_lokal->NO_TASPEN == "") 
                        $data["NO_TASPEN"]  =   isset($pegawai->noTaspen)   ?   $pegawai->noTaspen :    "";
                    // if(isset($pegawai_lokal->NOMOR_HP) and $pegawai_lokal->NOMOR_HP == "") 
                        $data["NOMOR_HP"]   =   isset($pegawai->noHp)   ?   $pegawai->noHp :    "";
                    // if(isset($pegawai_lokal->NOMOR_SK_CPNS) and $pegawai_lokal->NOMOR_SK_CPNS == "") 
                        $data["NOMOR_SK_CPNS"]  =   isset($pegawai->nomorSkCpns)    ?   $pegawai->nomorSkCpns :     "";
                    // if(isset($pegawai_lokal->NPWP) and $pegawai_lokal->NPWP == "") 
                        $data["NPWP"]   =   isset($pegawai->noNpwp) ?   $pegawai->noNpwp :  "";
                    //if(isset($pegawai_lokal->TK_PENDIDIKAN) and $pegawai_lokal->TK_PENDIDIKAN == "") 
                        $data["TK_PENDIDIKAN"]  =   isset($pegawai->tkPendidikanTerakhirId) ?   $pegawai->tkPendidikanTerakhirId :  "";
                    // if(isset($pegawai_lokal->PENDIDIKAN) and $pegawai_lokal->PENDIDIKAN == "") 
                    	$data["PENDIDIKAN"] =   isset($pegawai->pendidikanTerakhirId)   ?   $pegawai->pendidikanTerakhirId :    "";
                    	$data["PENDIDIKAN_ID"] =   isset($pegawai->pendidikanTerakhirId)   ?   $pegawai->pendidikanTerakhirId :    "";
                    //if(isset($pegawai_lokal->SATUAN_KERJA_INDUK_ID) and $pegawai_lokal->SATUAN_KERJA_INDUK_ID == "") 
                        // $data["SATUAN_KERJA_INDUK_ID"]  =   isset($pegawai->satuanKerjaIndukId) ?   $pegawai->satuanKerjaIndukId :  "";
                    //if(isset($pegawai_lokal->SATUAN_KERJA_INDUK_NAMA) and $pegawai_lokal->SATUAN_KERJA_INDUK_NAMA == "") 
                        // $data["SATUAN_KERJA_INDUK_NAMA"]    =   isset($pegawai->satuanKerjaIndukNama)   ?   $pegawai->satuanKerjaIndukNama :    "";
                    //if(isset($pegawai_lokal->SATUAN_KERJA_KERJA_ID) and $pegawai_lokal->SATUAN_KERJA_KERJA_ID == "") 
                        // $data["SATUAN_KERJA_KERJA_ID"]  =   isset($pegawai->satuanKerjaKerjaId) ?   $pegawai->satuanKerjaKerjaId :  "";
                    //if(isset($pegawai_lokal->SATUAN_KERJA_NAMA) and $pegawai_lokal->SATUAN_KERJA_NAMA == "") 
                        // $data["SATUAN_KERJA_NAMA"]  =   isset($pegawai->satuanKerjaKerjaNama)   ?   $pegawai->satuanKerjaKerjaNama :    "";
                    //if(isset($pegawai_lokal->STATUS_CPNS_PNS) and $pegawai_lokal->STATUS_CPNS_PNS == "") 
                        $data["STATUS_CPNS_PNS"]    =   isset($pegawai->statusPegawai)  ?   $pegawai->statusPegawai :   "";
                    //if(isset($pegawai_lokal->STATUS_HIDUP) and $pegawai_lokal->STATUS_HIDUP == "") 
                        $data["STATUS_HIDUP"]   =   isset($pegawai->statusHidup)    ?   $pegawai->statusHidup :     "";
                    //if(isset($pegawai_lokal->TEMPAT_LAHIR) and trim($pegawai_lokal->TEMPAT_LAHIR) == "") 
                        $data["TEMPAT_LAHIR"]   =   isset($pegawai->tempatLahir)    ?   $pegawai->tempatLahir :     "";
                    //if(isset($pegawai_lokal->TEMPAT_LAHIR_ID) and trim($pegawai_lokal->TEMPAT_LAHIR_ID) == "") 
                        $data["TEMPAT_LAHIR_ID"]    =   isset($pegawai->tempatLahirId)  ?   $pegawai->tempatLahirId :   "";
                    if($pegawai->tglSuratKeteranganBebasNarkoba != "") 
                        $data["TGL_BEBAS_NARKOBA"]  =   isset($pegawai->tglSuratKeteranganBebasNarkoba) ?   date('Y-m-d', strtotime($pegawai->tglSuratKeteranganBebasNarkoba)) :    null;
                    if($pegawai->tglSkck != "") 
                    	$data["TGL_CATATAN_POLISI"]  =   isset($pegawai->tglSkck) ?   date('Y-m-d', strtotime($pegawai->tglSkck)) :    null;
                    if($pegawai->tglLahir != "") 
                    	$data["TGL_LAHIR"]  =   isset($pegawai->tglLahir) ?   date('Y-m-d', strtotime($pegawai->tglLahir)) :    null;
                    if($pegawai->tglMeninggal != "") 
                    	$data["TGL_MENINGGAL"]  =   isset($pegawai->tglMeninggal) ?   date('Y-m-d', strtotime($pegawai->tglMeninggal)) :    null;
                    if($pegawai->tglNpwp != "") 
                    	$data["TGL_NPWP"]  =   isset($pegawai->tglNpwp) ?   date('Y-m-d', strtotime($pegawai->tglNpwp)) :    null;
                    if($pegawai->tglSuratKeteranganDokter != "") 
                    	$data["TGL_SURAT_DOKTER"]  =   isset($pegawai->tglSuratKeteranganDokter) ?   date('Y-m-d', strtotime($pegawai->tglSuratKeteranganDokter)) :    "";
                    if($pegawai->tmtCpns != "") 
                    	$data["TMT_CPNS"]  =   isset($pegawai->tmtCpns) ?   date('Y-m-d', strtotime($pegawai->tmtCpns)) :    null;
                    if($pegawai->tglSkCpns != "") 
                    	$data["TGL_SK_CPNS"]  =   isset($pegawai->tglSkCpns) ?   date('Y-m-d', strtotime($pegawai->tglSkCpns)) :    null;
                    if($pegawai->tmtGolAkhir != "") 
                    	$data["TMT_GOLONGAN"]  =   isset($pegawai->tmtGolAkhir) ?   date('Y-m-d', strtotime($pegawai->tmtGolAkhir)) :    null;
                    if($pegawai->tmtJabatan != "") 
                    	$data["TMT_JABATAN"]  =   isset($pegawai->tmtJabatan) ?   date('Y-m-d', strtotime($pegawai->tmtJabatan)) :    null;
                    
                    //if(isset($pegawai_lokal->LOKASI_KERJA_ID) and $pegawai_lokal->LOKASI_KERJA_ID == "") 
                        $data["LOKASI_KERJA_ID"]    =   isset($pegawai->lokasiKerjaId)  ?   $pegawai->lokasiKerjaId :   "";

                     $unor = $this->unitkerja_model->find_by("ID", trim($pegawai->unorId));
                     if(isset($unor->ID)){
                        $data["UNOR_ID"]    =   $unor->ID;
                        $data["UNOR_INDUK_ID"]    =   $unor->UNOR_INDUK;
                     }
                    //if(isset($pegawai_lokal->UNOR_ID) and $pegawai_lokal->UNOR_ID == "") 
                        // $data["UNOR_ID"]    =   isset($adataunor[$pegawai->unorId]) ?   $adataunor[$pegawai->unorId]->ID :  "";
                    //if(isset($pegawai_lokal->UNOR_INDUK_ID) and $pegawai_lokal->UNOR_INDUK_ID == "") 
                        // $data["UNOR_INDUK_ID"]  =   isset($adataunor[$pegawai->unorIndukId]) ?   $adataunor[$pegawai->unorIndukId]->ID :  "";

					$jabatanId = "";
					if($pegawai->jabatanStrukturalId!=""){
						$jabatanId = $pegawai->jabatanStrukturalId;
					}else if($pegawai->jabatanFungsionalUmumId!=""){
						$jabatanId = $pegawai->jabatanFungsionalUmumId;
					}else if($pegawai->jabatanFungsionalId!=""){
						$jabatanId = $pegawai->jabatanFungsionalId;
					}

					$data["JABATAN_ID"]	=	$jabatanId;

                    if(isset($pegawai_lokal->NIP_BARU)){
                        $this->load->helper('aplikasi');
                        $perubahan = arrDiff($pegawai_lokal,$data);
                        if(isset($pegawai->id) and $pegawai->id != "") {
                            if($this->pegawai_model->update_where("NIP_BARU",$pegawai_lokal->NIP_BARU, $data))
                            {
                            	$jumlahSukses++;
                                $hasil = true;
                                $msg =  "Berhasil update data";
                                log_activity($this->auth->user_id(),"Update Data Pegawai sync BKN NIP ".$pegawai_lokal->NIP_BARU.', data update : '.json_encode($perubahan), 'pegawai');
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
                    
                    $data["JENIS_JABATAN_ID"]   =   isset($pegawai->jenisJabatanId) ?   $pegawai->jenisJabatanId :  "";
                    
                    $jabatanId = $pegawai->jabatanFungsionalUmumId;
                    if($pegawai->jabatanFungsionalId != ""){
						$jabatanId = $pegawai->jabatanFungsionalId;
					}
                        
                    $datajabatan_real = $this->jabatan_model->find_by("KODE_BKN",trim($jabatanId));

                    $data["JABATAN_INSTANSI_REAL_ID"]   =   isset($datajabatan_real->KODE_JABATAN)  ?   $datajabatan_real->KODE_JABATAN :   "";
                    $data["JABATAN_ID"] =   isset($datajabatan_real->KODE_JABATAN)  ?   $datajabatan_real->KODE_JABATAN :   "";

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
                    $data["PENDIDIKAN_ID"] =   isset($pegawai->pendidikanTerakhirId)   ?   $pegawai->pendidikanTerakhirId :    "";
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
                        $data["TGL_BEBAS_NARKOBA"]  =   isset($pegawai->tglSuratKeteranganBebasNarkoba) ?   date('Y-m-d', strtotime($pegawai->tglSuratKeteranganBebasNarkoba)) :    null;
                    if($pegawai->tglSkck != "")
                        $data["TGL_CATATAN_POLISI"] =   isset($pegawai->tglSkck)    ?   date('Y-m-d', strtotime($pegawai->tglSkck)) :   null;
                    if($pegawai->tglLahir != "")
                        $data["TGL_LAHIR"]  =   isset($pegawai->tglLahir)   ?   date('Y-m-d', strtotime($pegawai->tglLahir)) :  null;
                    if($pegawai->tglMeninggal != "")
                        $data["TGL_MENINGGAL"]  =   isset($pegawai->tglMeninggal)   ?   date('Y-m-d', strtotime($pegawai->tglMeninggal)) :  null;
                    if($pegawai->tglNpwp != "")
                        $data["TGL_NPWP"]   =   isset($pegawai->tglNpwp)    ?   date('Y-m-d', strtotime($pegawai->tglNpwp)) :   null;
                    if($pegawai->tglSuratKeteranganDokter != "")
                        $data["TGL_SURAT_DOKTER"]   =   isset($pegawai->tglSuratKeteranganDokter)   ?   date('Y-m-d', strtotime($pegawai->tglSuratKeteranganDokter)) :  null;
                    if($pegawai->tkPendidikanTerakhirId != "")
                    $data["TK_PENDIDIKAN"]  =   isset($pegawai->tkPendidikanTerakhirId) ?   $pegawai->tkPendidikanTerakhirId :  "";
                    if($pegawai->tmtPns != "")
                        $data["TMT_CPNS"]   =   isset($pegawai->tmtPns) ?   date('Y-m-d', strtotime($pegawai->tmtPns)) :    null;
                    if($pegawai->tmtCpns != "")
                        $data["TMT_CPNS"]   =   isset($pegawai->tmtCpns)    ?   date('Y-m-d', strtotime($pegawai->tmtCpns)) :   null;
                    $data["TGL_SK_CPNS"]    =   isset($pegawai->tglSkCpns)  ?   date('Y-m-d', strtotime($pegawai->tglSkCpns)) :     null;
                        $data["GOL_ID"] =   isset($pegawai->golRuangAkhirId)    ?   $pegawai->golRuangAkhirId :     "";
                    $data["TMT_GOLONGAN"]   =   isset($pegawai->tmtGolAkhir)    ?   date('Y-m-d', strtotime($pegawai->tmtGolAkhir)) :   null;
                    $data["TMT_JABATAN"]    =   isset($pegawai->tmtJabatan) ?   date('Y-m-d', strtotime($pegawai->tmtJabatan)) :    null;
                    $data["LOKASI_KERJA_ID"]    =   isset($pegawai->lokasiKerjaId)  ?   $pegawai->lokasiKerjaId :   "";
                    $unor = $this->unitkerja_model->find_by("ID", trim($pegawai->unorId));
                     if(isset($unor->ID)){
                        $data["UNOR_ID"]    =   $unor->ID;
                        $data["UNOR_INDUK_ID"]    =   $unor->UNOR_INDUK;
                     }

                    if($pegawai->jenisJabatanId != "")
                        $data["JENIS_JABATAN_IDx"]  =   isset($pegawai->jenisJabatanId) ?   $pegawai->jenisJabatanId :  "";

                    $data["status_pegawai"] =   1;
					$response['mode'] = 'insert';
					$id = $this->pegawai_model->insert($data);
                    if($id)
                    {
                        $hasil = true;
                        $msg =  "Berhasil Tambah data ".$id;
                        log_activity($this->auth->user_id(), $msg . ' : ' . $this->input->ip_address(), 'pegawai');
                    }else{
						$msg = $this->pegawai_model->db->error();
					}
                }
                
            }
        }
        // end history golongan
        $hasil = true;
		$response ['djb'] = $datajabatan_real;
		$response ['data'] = $pegawai;
        $response ['success']= $hasil;
        $response ['msg']= "Sukses ".$jumlahSukses." Gagal ".$jumlahGagal;
		$response ['message'] = $msg;
        echo json_encode($response);    
    }
	private function getGapok($tmt, $golongan_id, $mk)
	{
		$this->load->model('pegawai/riwayat_kgb_model');
		if (strtotime($tmt) > strtotime('2019-01-01')) {
			$result = $this->riwayat_kgb_model->getWage2($golongan_id, $mk);
			return $result ? $result->BASIC : null;
		}
		return null;
	}

	public function saveBerkas($nip, $no_sk, $keterangan, $id_jenis_arsip, $berkas)
	{
		$aws = new Api_s3_aws;
		$this->load->library('Api_bkn');
		$api_bkn = new Api_bkn;
		$berkas = (array) $berkas;
		$berkas = array_values($berkas);
		$file = $api_bkn->downloadDokumen($berkas[0]->dok_uri);
		if ($file) {
			$location       = "repo_dokumen_digital_pegawai/repo1/" . $nip . '/';
			$path_parts     = pathinfo($berkas[0]->dok_uri);
			$name           = $path_parts['filename'] . '.' . $path_parts['extension'];
			$path           = $aws->createFileAws("simpeg", $file, $location . $name, "application/pdf");
			log_activity(1, "Upload dokumen BKN = {$nip} data = " . json_encode($berkas) . " " . $this->input->ip_address(), "Home");

			// insert arsip
			$arsip = $this->arsip_digital_model->find_by(array('sk_number' => $no_sk, 'NIP' => $nip, 'ID_JENIS_ARSIP' => $id_jenis_arsip));
			$data = array();
			$data['NIP']                = $nip;
			$data['ID_JENIS_ARSIP']     = $id_jenis_arsip;
			$data['sk_number']          = $no_sk;
			$data['location']           = $location;
			$data['name']               = $name;
			$extension                  = $path_parts['extension'];
			$data['EXTENSION_FILE']     = isset($extension) ? $extension : null;
			$data['KETERANGAN']         = $berkas[0]->dok_nama . ' ' . $keterangan;
			$data['ISVALID']            = 1;
			$this->arsip_digital_model->skip_validation(true);
			if (isset($arsip) && !empty($arsip->ID)) {
				$data['UPDATED_DATE']    = date("Y-m-d");
				$data['UPDATED_BY']    = $this->current_user->id;
				$this->arsip_digital_model->update($arsip->ID, $data);
				$id = $arsip->ID;
				log_activity(1, 'Update Dokumen : ' . $arsip->ID . ' : ' . $this->input->ip_address(), 'Sync BKN');
			} else {
				$data['CREATED_DATE']    = date("Y-m-d");
				$data['CREATED_BY']    = $this->current_user->id;
				$id = $this->arsip_digital_model->insert($data);
				log_activity(1, 'Upload Dokumen : ' . $id . ' : ' . $this->input->ip_address(), 'Sync BKN');
			}
			return $id;
		}
		return null;
	}
	 public function lihatdokumen(){
        $file   = urlencode($this->input->get('file'));
        if($file != ""){
            $response   = $this->getDokumen($file);
            if($response != ""){
                $this->saveArsip($this->input->get('file'), $response);
                header('Content-Disposition: inline; filename="example.pdf"');
                header('Content-Type: application/pdf');
                echo $response;
            }
        }
    }
    private function getDokumen($file){
        $api_bkn = new Api_bkn;
        $response = $api_bkn->downloadDokumen($file);
        return $response;
    }
    private function saveArsip($file,$response){
        $filename = end((explode("/", $file)));
        if($response != "")
            file_put_contents($this->settings_lib->item('site.urluploaded').$filename, $response);
        return $filename;
    }
    private function genCacheUnorAll(){
        $records = $this->unitkerja_model->find_all();
        $json = array();
        foreach ($records as $record) {
            if($record->bkn_id != "")
                $json[$record->bkn_id] = $record;
        }
        $this->cache->write($json, 'aunorAll',3600);
        return $json;
    }
    private function genPangkat(){
        $golongan_data = $this->golongan_model->find_all();
        $agolongan = array();
        foreach($golongan_data as $row)
        {
            $agolongan[$row->ID] = $row->NAMA_PANGKAT;
        }
        return $agolongan;        
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
                    $unor = $this->unitkerja_model->find_by("ID", trim($pegawai->unorId));
	                if(isset($unor->ID)){
	                    $data["UNOR_ID"]    =   $unor->ID;
	                    $data["UNOR_INDUK_ID"]    =   $unor->UNOR_INDUK;
	                }

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
                    $unor = $this->unitkerja_model->find_by("ID", trim($pegawai->unorId));
	                if(isset($unor->ID)){
	                    $data["UNOR_ID"]    =   $unor->ID;
	                    $data["UNOR_INDUK_ID"]    =   $unor->UNOR_INDUK;
	                }

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
