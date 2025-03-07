<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Reports controller
 */
class Kgb_satker extends Admin_Controller
{
   protected $permissionProsesKGB   = 'LayananKGB.ProsesKGB';
   protected $permissionCetakPersonal   = 'LayananKGB.CetakSKPersonal';
   protected $permissionCetakKolektif   = 'LayananKGB.CetakSKKolektif';
	
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
		$this->load->model('pegawai/pegawai_model');
		$this->load->model('pegawai/unitkerja_model');
		$this->load->helper('dikbud');
		$this->satker_pegawai = getSatkerPegawai($this->auth->username());
		$this->selectedSatker = $this->unitkerja_model->find($this->satker_pegawai);
		Template::set('module_url',base_url('kgb/kgb-satker'));
		Template::set('canCetakSKKolektif',$this->permissionCetakKolektif);
    }

    /**
     * Display a list of petajabatan data.
     *
     * @return void
     */
    public function index()
    {
		if(!$this->auth->has_permission('LayananKGB.View.PengelolaKGBSatker')){
			redirect("");
		}
	
		Template::set('selectedSatker',$this->selectedSatker);
    	Template::set_view('kgb_satker/v_list');
        Template::render();
	}
	
    public function ajax_list(){
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
        
        $this->db->from("mv.mv_kgb");
        $this->db->join("hris.pegawai p",'mv_kgb.pegawai_id = p."ID"',"INNER");
		$this->db->join("hris.vw_unit_list vw",'p.UNOR_ID=vw.ID','INNER');
		$this->db->join("hris.unitkerja vw_satker",'vw_satker.ID=vw.UNOR_INDUK','INNER');
		$this->db->join("hris.golongan g",'g.ID=p.GOL_ID','INNER');
		$this->db->join("hris.rwt_kgb",'mv_kgb.id=rwt_kgb.mv_kgb_id','left');
		$this->db->join("mv.kgb_tunda kgbt","kgbt.mv_kgb_id=mv_kgb.id","LEFT");

		if($this->selectedSatker){
			$this->db->group_start();
				$this->db->where('vw."ID"',$this->selectedSatker->ID);	
				$this->db->or_where('vw."ESELON_1"',$this->selectedSatker->ID);	;	
				$this->db->or_where('vw."ESELON_2"',$this->selectedSatker->ID);	
				$this->db->or_where('vw."ESELON_3"',$this->selectedSatker->ID);	
				$this->db->or_where('vw."ESELON_4"',$this->selectedSatker->ID);	
			$this->db->group_end();
		}

		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		$advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
		if($advanced_search_filters){
			$filters = array();
			foreach($advanced_search_filters as  $filter){
				$filters[$filter['name']] = $filter["value"];
			}
			
			if($filters['filter_unit_kerja']){
				
				$this->db->group_start();
				$this->db->where('vw."ID"',$filters['filter_unit_kerja']);	
				$this->db->or_where('vw."ESELON_1"',$filters['filter_unit_kerja']);	
				$this->db->or_where('vw."ESELON_2"',$filters['filter_unit_kerja']);	
				$this->db->or_where('vw."ESELON_3"',$filters['filter_unit_kerja']);	
				$this->db->or_where('vw."ESELON_4"',$filters['filter_unit_kerja']);	
				$this->db->group_end();
			}
			if($filters['filter_tahun']) {
				$this->db->where("date_part('year',mv_kgb.dt_kgb_yad)",date('Y'));
			}
			if($filters['filter_bulan']) {
				$this->db->where("date_part('month',mv_kgb.dt_kgb_yad)",$filters['filter_bulan']);
			}
			
			if($filters['golongan_cb']){
				//$this->pegawai_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));	
			}
		}
		
		$this->db->stop_cache();
		$output=array();
		$output['draw']=$draw;
		$total= $this->db->get()->num_rows();
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$this->db->select("kgbt.id as tunda_id,kgbt.alasan as kgbt_alasan,mv_kgb.pegawai_id,
		rwt_kgb.id as riwayat_kgb_id,vw_satker.NAMA_UNOR as nama_satker,mv_kgb.id,vw.NAMA_UNOR_FULL as unor_full,p.NAMA as name,p.NIP_BARU as nip,mv_kgb.masa_kerja_thn,mv_kgb.masa_kerja_bln,g.NAMA as golongan_name,g.NAMA_PANGKAT as pangkat_name,
			(
				select count(*) as total from rwt_hukdis where  \"ID_JENIS_HUKUMAN\" = '04' and trim(\"PNS_NIP\") = trim(p.\"NIP_BARU\")
			) as riwayat_hukdis,
			(
				select \"BASIC\" from wage 
				left join golongan g on wage.\"GOLONGAN\" = g.\"NAMA2\"
				where g.\"ID\" = mv_kgb.golongan_id and wage.\"WORKING_PERIOD\" = mv_kgb.masa_kerja_thn
				limit 1
			) as gaji ");
		$this->db->limit($length,$start);
		$rows = $this->db->get()->result();
		$this->db->flush_cache();
		$data = array();
		$index = 1;
		foreach($rows as $row){
			$btn_actions_array = array();
			if($row->riwayat_kgb_id){
				if($this->auth->has_permission($this->permissionCetakPersonal)){
					$btn_actions_array  [] = "
						<a class='download-ajax btn-cetak-personal' href='".base_url()."kgb/kgb_satker/cetak_personal/".$row->id."'  data-toggle='tooltip' title='Cetak KGB DOCX'><span class='fa-stack'>
							<i class='fa fa-square fa-stack-2x'></i>
								<i class='fa fa-print fa-stack-1x fa-inverse'></i>
								</span>
								</a>
					";	
				}
				if($this->auth->has_permission($this->permissionProsesKGB)){
					$btn_actions_array  [] = "
						<a class='btn-proses-kgb' href='".base_url()."kgb/kgb_satker/proses_kgb_form/".$row->id."/".$row->pegawai_id."'  data-toggle='modal' data-toggle='tooltip' title='Proses KGB'><span class='fa-stack'>
							<i class='fa fa-square fa-stack-2x'></i>
							<i class='fa fa-cog fa-stack-1x fa-inverse'></i>
							</span>
						</a>
					";	
					$btn_actions_array  [] = "
						<a class='btn-proses-kgb' href='".base_url()."kgb/kgb_satker/tunda_kgb/".$row->id."'  data-toggle='modal' data-toggle='tooltip' title='Tunda KGB'><span class='fa-stack'>
							<i class='fa fa-square fa-stack-2x'></i>
							<i class='fa fa-lock fa-stack-1x fa-inverse'></i>
							</span>
						</a>
					";	
				}	
			}
			else {
				if($this->auth->has_permission($this->permissionProsesKGB)){
					$btn_actions_array  [] = "
					<a class='btn-proses-kgb' href='".base_url()."kgb/kgb_satker/proses_kgb_form/".$row->id."/".$row->pegawai_id."'  data-toggle='modal' data-toggle='tooltip' title='Proses KGB'><span class='fa-stack'>
						<i class='fa fa-square fa-stack-2x'></i>
							<i class='fa fa-cog fa-stack-1x fa-inverse'></i>
							</span>
							</a>
					";	
					$btn_actions_array  [] = "
						<a class='btn-proses-kgb' href='".base_url()."kgb/kgb_satker/tunda_kgb/".$row->id."'  data-toggle='modal' data-toggle='tooltip' title='Tunda KGB'><span class='fa-stack'>
							<i class='fa fa-square fa-stack-2x'></i>
							<i class='fa fa-lock fa-stack-1x fa-inverse'></i>
							</span>
						</a>
					";	
				}
			}
			$informasi = array();
			if($row->riwayat_hukdis>0){
				$informasi[]= "HUKDIS";
			}
			$tunda_class=  '';
			if($row->tunda_id){
				$tunda_class='tunda';
				$informasi[] = $row->kgbt_alasan;
			}
			$data [] = array(
				$index,
				"<div class='$tunda_class'>".$row->name ."<Br>". $row->nip."</div>",
				$row->pangkat_name."-".$row->golongan_name,
				$row->masa_kerja_thn."/".$row->masa_kerja_bln,
				rupiah($row->gaji),
				$row->nama_satker,
				implode("<br>",$informasi),
				implode(" ",$btn_actions_array)
			);
			$index++;
		}
		$output['data']= $data;
		echo json_encode($output);
    }
	public function proses_kgb_form($id,$pegawai_id){

		$this->load->model('pegawai/kpkn_model');
        $kpkns = $this->kpkn_model->find_all();
		Template::set('kpkns', $kpkns);

		$selectedData = $this->db->query("select * from rwt_kgb where mv_kgb_id = ? ",array($id))->first_row();
		$settings = $this->db->query("select * from hris.settings where name = ?",array("kgb_settings_".$this->satker_pegawai))->first_row();
		$pejabat = '';
		if($settings){
			$obj = json_decode($settings->value);
			$pejabat = $obj->pejabat;
		}
		$pegawai_id = (int)$pegawai_id;
		if($selectedData){
			$pejabat = $selectedData->pejabat;
		}

		$pegawaiData = $this->pegawai_model->find((int)$pegawai_id);
        Template::set('pegawai', $pegawaiData);

		Template::set('selectMv',$id);
		Template::set('pejabat',$pejabat);
		Template::set('selectedData',$selectedData);
        Template::set_view('kgb_satker/proses_kgb_form');
        Template::render();
	}
	public function tunda_kgb($id){
		$selectedData = $this->db->query("select * from rwt_kgb where mv_kgb_id = ? ",array($id))->first_row();
		$settings = $this->db->query("select * from hris.settings where name = ?",array("kgb_settings_".$this->satker_pegawai))->first_row();
		$pejabat = '';
		if($settings){
			$obj = json_decode($settings->value);
			$pejabat = $obj->pejabat;
		}
		if($selectedData){
			$pejabat = $selectedData->pejabat;
		}
		Template::set('selectMv',$id);
		Template::set('pejabat',$pejabat);
		Template::set('selectedData',$selectedData);
        Template::set_view('kgb_satker/tunda_kgb_form');
        Template::render();
	}
	public function do_tunda_kgb(){
		$id = $this->input->post('id');
		$alasan = $this->input->post('alasan');
		$num = $this->input->post('num');
		$data_kgb = $this->db->select("dt_kgb_yad+ INTERVAL '".$num." YEAR'  as next",false)->from('mv.mv_kgb')->where("id",$id)->get()->first_row();
		if($data_kgb){
			$this->db->where("mv_kgb_id",$id);
			$this->db->delete("mv.kgb_tunda");
			$store_data = array(
				'mv_kgb_id'=>$id,
				'dt_kgb_yad'=>$data_kgb->next,
				'alasan'=>$alasan
			);
			$this->db->insert("mv.kgb_tunda",$store_data);
			$output = array(
				'success'=>true
			);
			echo json_encode($output);
		}
	}
	public function do_proses_kgb(){
		$id = $this->input->post('id');
		$no_sk = $this->input->post('no_sk');
		$pejabat = $this->input->post('pejabat');
		$alasan = $this->input->post('alasan');
		$KPKN_ID = $this->input->post('KPKN_ID');

		$selectedData = $this->db->query("select * from rwt_kgb where mv_kgb_id = ? ",array($id))->first_row();
		$store_data = array(
			'mv_kgb_id'=>$id,
			'no_sk'=>$no_sk,
			'pejabat'=>$pejabat,
			'alasan'=>$alasan,
		);

		$selectedMv = $this->db->query("select * from mv.mv_kgb where id = ? ",array($id))->first_row();

		$selectedPegawai = $this->db->query("select \"GOL_ID\" as golongan,\"PNS_ID\" from pegawai where \"ID\" = ?",array($selectedMv->pegawai_id))->first_row();
		$selectedLastJabatan = $this->db->query("
			select rwt_jabatan.* from rwt_jabatan 
			left join jabatan on trim(rwt_jabatan.\"ID_JABATAN\")  = trim(jabatan.\"KODE_BKN\")
			where \"PNS_ID\" = ? and \"TMT_JABATAN\" is not null  order by \"TMT_JABATAN\" desc limit 1 ",array($selectedPegawai->PNS_ID))->first_row();
		
		if($selectedMv){
			$pegawai_id = $selectedMv->pegawai_id;
			$store_data['pegawai_id'] = $selectedMv->pegawai_id;
			$store_data['n_golongan_id'] = $selectedMv->golongan_id;
			$store_data['unit_kerja_induk_text'] = $selectedMv->satuan_kerja_text;
			$store_data['n_jabatan_text'] = $selectedLastJabatan->NAMA_JABATAN;
			
			$store_data['n_masakerja_thn'] = $selectedMv->masa_kerja_thn;
			$store_data['n_masakerja_bln'] = $selectedMv->masa_kerja_bln;
			$store_data['tmt_sk'] = $selectedMv->dt_kgb_yad;
		}
		if($selectedData){
			$this->db->where("mv_kgb_id",$id);
			$this->db->update("rwt_kgb",$store_data);
		}
		else {
			$store_data['ref'] = sha1("kgb".time()."kemendikbud");
			$this->db->insert("rwt_kgb",$store_data);
		}
		if($KPKN_ID != ""){
			$data = array();
			$data['KPKN_ID']	= $KPKN_ID;
			$result = $this->pegawai_model->update($pegawai_id, $data);
		}
		$output = array(
			'success'=>true
		);
		echo json_encode($output);
	}
	
	public function cetak_sk_pdf($id = ""){
		$this->cetak_sk($id,"pdf");
		
	}
	private function gen_qrcode($gen = ""){
		$this->load->library('phpqrcode_lib');
        $ref = uniqid("sk_kgb");
        $barcode_file = $ref.".png";
        $barcode_file_path = APPPATH."..".DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."qrcodes".DIRECTORY_SEPARATOR."".$barcode_file; 
        die($barcode_file_path);
		$qr_data = base_url("kgb/services/detail/".$ref);
		QRcode::png($qr_data, $barcode_file_path, 'L', 4, 2);
        return $barcode_file_path;
	}
	public function cetak_sk_kolektif($thn = "",$bln = ""){
		$this->cetak_sk(null,$thn,$bln);		
	}
	public function cetak_personal($id = ""){
		//die($id." id");
		$this->cetak_sk($id);
	}
	private function cetak_sk($id = "",$thn = "",$bln = ""){
		$this->load->helper('dikbud');
		$template_data=$this->db->query("select * from mst_templates where id = ?",array(TEMPLATE_SK_KGB_KOLEKTIF))->first_row();
		// print_r($template_data);
		// die();
		$this->db->select("rwt_kgb.ref,rwt_kgb.pejabat,kpkn.NAMA AS nama_kppn,vw.UNOR_INDUK,
					rwt_kgb.n_jabatan_text,rwt_kgb.unit_kerja_induk_text,
					mv_kgb.dt_kgb_yad,rwt_kgb.no_sk,mv_kgb.id,vw.NAMA_UNOR_FULL as unor_full,
					p.NAMA as name,p.NIP_BARU as nip,mv_kgb.masa_kerja_thn,mv_kgb.masa_kerja_bln,
					g.NAMA2 as golongan2_name,g.NAMA as golongan_name,g.NAMA_PANGKAT as pangkat_name");
		$this->db->from("mv.mv_kgb");
		$this->db->join("hris.rwt_kgb","mv_kgb.id = rwt_kgb.mv_kgb_id");
        $this->db->join("hris.pegawai p",'mv_kgb.pegawai_id = p."ID"',"INNER");
		$this->db->join("hris.vw_unit_list vw",'p.UNOR_ID=vw.ID');
		$this->db->join("hris.golongan g",'g.ID=p.GOL_ID');
		$this->db->join("hris.kpkn",'kpkn.ID=p.KPKN_ID');
		if($id != null){
			$output_file_name = uniqid("KGB_PERSONAL_");
			$this->db->where("mv.mv_kgb.id",$id);
		}
		else {
			$output_file_name = uniqid("KGB_KOLEKTIF_");
			$this->db->where("rwt_kgb.tmt_sk",$thn."-".$bln."-01");
		}
		$kgb_datas = $this->db->get()->result();
		
		if(sizeof($kgb_datas)==0){
			$output = array(
				'success'=>false,
				'message'=>'Tidak ada riwayat KGB'
			);
			echo json_encode($output);
			die();
		}
		header('Set-Cookie: fileDownload=true; path=/');

		
		
		$this->load->library('Template_doc_lib');
		$TBS = $this->template_doc_lib->gen_xls(BASEPATH."../../assets/templates/".$template_data->template_file);
		$tembusan_data = array();
		$tembusan_data [] = 'Kepala BKN, u.p. Deputi Bidang Sistem Informasi Kepegawaian';
		$tembusan_data [] = 'Kepala Kantor Tata Usaha Anggaran, Kementerian Keuangan';
		$tembusan_data [] = 'Pegawai yang bersangkutan';
		$TBS->MergeBlock('tembusan', $tembusan_data);
		$sk_datas = array();
		

		$gaji = $this->db->query("select * from wage where wage.\"GOLONGAN\" = ? and wage.\"WORKING_PERIOD\" = ? ",array(str_replace("/","",$kgb_datas[0]->golongan2_name),$kgb_datas[0]->masa_kerja_thn))->first_row();
//print_r($gaji);
//print_r($kgb_datas);
//		die();
		$UNOR_INDUK = "";
		foreach($kgb_datas as $kgb_data){
			$UNOR_INDUK = $kgb_data->UNOR_INDUK;
			$PEJABAT = $kgb_data->pejabat;
			// get kepala satker
			$datasatker = $this->unitkerja_model->find_by("ID",$UNOR_INDUK);
			$NAMA_JABATAN = isset($datasatker->NAMA_JABATAN) ? $datasatker->NAMA_JABATAN : "";

			// get detil pejabat
			$datapejabat = $this->pegawai_model->find_by("NIP_BARU",$PEJABAT);
			$nama_pejabat = isset($datapejabat->NAMA) ? $datapejabat->GELAR_DEPAN." ".$datapejabat->NAMA." ".$datapejabat->GELAR_BELAKANG : "";
			$nip_pejabat = isset($datapejabat->NIP_BARU) ? $datapejabat->NIP_BARU : "";
			$sk_datas[] = array(
				'p_nip'=>$kgb_data->nip,
				'no'=>$kgb_data->no_sk,
				'to'=>'Kepala Kantor Pelayanan Perbendaharaan Negara',
				'tgl_sk'=>date('d-m-Y'),
				'tempat'=>'Jakarta',
				'p_birth_place'=>'',
				'p_birth_date'=>'',
				'lokasi_sk'=>'',
				'uu'=>'PP No 30 Tahun 2015',
				'p_nama'=>$kgb_data->name,
				'masa_kerja_thn'=>$kgb_data->masa_kerja_thn,
				'masa_kerja_bln'=>$kgb_data->masa_kerja_bln,
				'tmt'=>$kgb_data->dt_kgb_yad,
				'p_pangkat'=>$kgb_data->pangkat_name . "-".$kgb_data->golongan_name,
				'p_jabatan'=>$kgb_data->n_jabatan_text,
				'gaji_baru'=>rupiah($gaji->BASIC),
				'gaji_baru_text'=>Terbilang($gaji->BASIC),
				'gaji_lama'=>rupiah($gaji->BASIC),
				'gaji_lama_text'=>Terbilang($gaji->BASIC),
				'p_satker'=>$kgb_data->unit_kerja_induk_text,
				'p_instansi'=>'',
				'p_kpkn_nama'=>$kgb_data->nama_kppn,
				'kepala_satker_ybs'=>$NAMA_JABATAN,
				'foto'=>$this->gen_qrcode($kgb_data->ref),
				'nip_pejabat'=>$nip_pejabat,
				'pejabat'=>$nama_pejabat,
			);
		}
		

		$TBS->MergeBlock('o',$sk_datas);
		$output_file_name = $output_file_name.".docx";
		$TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.
	}
	function form_settings(){
		$selectedData = $this->db->query("select * from hris.settings where name = ?",array("kgb_settings_".$this->satker_pegawai))->first_row();
		$pejabat = '';
		if($selectedData){
			$obj = json_decode($selectedData->value);
			$pejabat = $obj->pejabat;
		}
		Template::set('pejabat',$pejabat);
		Template::set_view('kgb_satker/v_form_settings');
        Template::render();
	}
	function save_form_settings(){
		$selectedData = $this->db->query("select * from hris.settings where name = ?",array("kgb_settings_".$this->satker_pegawai))->first_row();
		$store_data = array(
			'module'=>'core',
			'value'=>json_encode(array(
				'pejabat'=>$this->input->post('pejabat')
			))
		);
		if(!$selectedData){
			$store_data['name']='kgb_settings_'.$this->satker_pegawai;
			$this->db->insert('hris.settings',$store_data);	
		}	
		else {
			$this->db->where('name','kgb_settings_'.$this->satker_pegawai);
			$this->db->update('hris.settings',$store_data);	
		}	
		echo json_encode(array(
			'success'=>true
		));
	}
}