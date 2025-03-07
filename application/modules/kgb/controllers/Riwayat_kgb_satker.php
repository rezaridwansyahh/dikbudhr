<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Reports controller
 */
class Riwayat_kgb_satker extends Admin_Controller
{
  
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
		$this->load->model('pegawai/pegawai_model');
		$this->load->helper('dikbud');
		$this->selectedSatker = $this->get_satker();
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
    	Template::set_view('v_list_kgb_satker');
        Template::render();
	}
	private function get_satker(){
		$pegawai_data = $this->db->query('
			select unitkerja.* from hris.pegawai 
			left join hris.unitkerja on pegawai."UNOR_ID" = unitkerja."ID" 
			where "NIP_BARU" = ? 
		',array(trim($this->auth->user()->username)))->first_row();

		$units = array();
		$units[] = $pegawai_data->ESELON_1;
		$units[] = $pegawai_data->ESELON_2;
		$units[] = $pegawai_data->ESELON_3;
		$units[] = $pegawai_data->ESELON_4;
		$units[] = $pegawai_data->ESELON_5;
		
		$satker  = $this->db->query('
			select * from unitkerja where "ID" in ? and "IS_SATKER" = 1
		',array($units))->first_row();
		return $satker;
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
        
        $this->db->from("hris.rwt_kgb");
        $this->db->join("hris.pegawai p",'rwt_kgb.pegawai_id = p."ID"',"INNER");
		$this->db->join("hris.vw_unit_list vw",'p.UNOR_ID=vw.ID','INNER');
		$this->db->join("hris.golongan g",'g.ID=p.GOL_ID','INNER');

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
		}
		$this->db->stop_cache();
		$output=array();
		$output['draw']=$draw;
		$total= $this->db->get()->num_rows();
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$this->db->select("
		rwt_kgb.id as riwayat_kgb_id,rwt_kgb.masa_kerja_thn,rwt_kgb.masa_kerja_bln,vw.NAMA_UNOR_FULL as unor_full,p.NAMA as name,p.NIP_BARU as nip,g.NAMA as golongan_name,g.NAMA_PANGKAT as pangkat_name,
			(
				select count(*) as total from rwt_hukdis where  \"ID_JENIS_HUKUMAN\" = '04' and trim(\"PNS_NIP\") = trim(p.\"NIP_BARU\")
			) as riwayat_hukdis ");
		$this->db->limit($length,$start);
		$rows = $this->db->get()->result();
		$this->db->flush_cache();
		$data = array();
		$index = 1;
		foreach($rows as $row){
			$btn_actions_array = array();
			if($row->riwayat_kgb_id){
				$btn_actions_array  [] = "
					<a class='download-ajax' href='#' data-url='".base_url()."kgb/kgb_satker/cetak_sk/".$row->id."'  data-toggle='tooltip' title='Cetak KGB DOCX'><span class='fa-stack'>
					<i class='fa fa-square fa-stack-2x'></i>
						<i class='fa fa-print fa-stack-1x fa-inverse'></i>
						</span>
						</a>
				";	
				
				$btn_actions_array  [] = "
					<a class='download-ajax' href='#' data-url='".base_url()."kgb/kgb_satker/cetak_sk_pdf/".$row->id."'  data-toggle='tooltip' title='Cetak KGB PDF'><span class='fa-stack'>
					<i class='fa fa-square fa-stack-2x'></i>
						<i class='fa fa-file-pdf-o fa-stack-1x fa-inverse'></i>
						</span>
						</a>
				";	
			}
			else {
			}
			$informasi_hukdis = "";
			if($row->total>0){
				$informasi_hukdis = "HUKDIS";
			}
			else $informasi_hukdis = "-";
			$data [] = array(
				$index,
				$row->name ."<Br>".
				$row->nip,
				$row->pangkat_name." - ".$row->golongan_name,
				$row->jabatan,
				$row->masa_kerja_thn."/".$row->masa_kerja_bln,
				$row->gaji,
				$row->gaji,
				$row->unor_full,
				$informasi_hukdis,
				implode(" ",$btn_actions_array)
			);
			$index++;
		}
		$output['data']= $data;
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
        $qr_data = base_url("kgb/services/detail/".$ref);
        QRcode::png($qr_data, $barcode_file_path, 'L', 4, 2);
        return $barcode_file_path;
	}
	public function cetak_sk_kolektif($thn = "",$bln = ""){
		
		$this->load->helper('dikbud');
		$template_data=$this->db->query("select * from mst_templates where id = ?",array(TEMPLATE_SK_KGB_KOLEKTIF))->first_row();
		$this->db->select("rwt_kgb.ref,rwt_kgb.pejabat, mv_kgb.dt_kgb_yad,rwt_kgb.no_sk,mv_kgb.id,vw.NAMA_UNOR_FULL as unor_full,p.NAMA as name,p.NIP_BARU as nip,mv_kgb.masa_kerja_thn,mv_kgb.masa_kerja_bln,g.NAMA2 as golongan2_name,g.NAMA as golongan_name,g.NAMA_PANGKAT as pangkat_name");
		$this->db->from("mv.mv_kgb");
		$this->db->join("hris.rwt_kgb","mv_kgb.id = rwt_kgb.mv_kgb_id");
        $this->db->join("hris.pegawai p",'mv_kgb.pegawai_id = p."ID"',"INNER");
		$this->db->join("hris.vw_unit_list vw",'p.UNOR_ID=vw.ID');
		$this->db->join("hris.golongan g",'g.ID=p.GOL_ID');
		$this->db->where("rwt_kgb.sk",$thn."-".$bln."-01");
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

		$gaji = $this->db->query("select * from wage where wage.\"GOLONGAN\" = ? and wage.\"WORKING_PERIOD\" = ? ",array(str_replace("/","",$kgb_data->golongan2_name),$kgb_data->masa_kerja_thn))->first_row();
		
		
		$this->load->library('Template_doc_lib');
		$TBS = $this->template_doc_lib->gen_xls(BASEPATH."../../assets/templates/".$template_data->template_file);
		$tembusan_data = array();
		$tembusan_data [] = 'Kepala BKN, u.p. Deputi Bidang Sistem Informasi Kepegawaian';
		$tembusan_data [] = 'Kepala Kantor Tata Usaha Anggaran, Kementerian Keuangan';
		$tembusan_data [] = 'Pegawai yang bersangkutan';
		$TBS->MergeBlock('tembusan', $tembusan_data);
		$sk_datas = array();
		foreach($kgb_datas as $kgb_data){
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
				'p_pangkat'=>$kgb_data->pangkat_name . "/".$kgb_data->golongan_name,
				'p_jabatan'=>'',
				'gaji_baru'=>rupiah($gaji->BASIC),
				'gaji_baru_text'=>Terbilang($gaji->BASIC),
				'gaji_lama'=>'',
				'gaji_lama_text'=>Terbilang($gaji->BASIC),
				'p_satker'=>'',
				'p_instansi'=>'',
				'kepala_satker_ybs'=>'',
				'foto'=>$this->gen_qrcode($kgb_data->ref),
				'pejabat'=>$kgb_data->pejabat,
			);
		}
		
		$TBS->MergeBlock('o',$sk_datas);
		$output_file_name = uniqid("KGB_KOLEKTIF_");
		$output_file_name = $output_file_name.".docx";
		$TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.	
	}
	public function cetak_sk($id = "",$tipe="docx"){
		$this->load->helper('dikbud');
		$template_data=$this->db->query("select * from mst_templates where id = ?",array(TEMPLATE_SK_KGB_KOLEKTIF))->first_row();
		$this->db->select("rwt_kgb.ref,rwt_kgb.pejabat, mv_kgb.dt_kgb_yad,rwt_kgb.no_sk,mv_kgb.id,vw.NAMA_UNOR_FULL as unor_full,p.NAMA as name,p.NIP_BARU as nip,mv_kgb.masa_kerja_thn,mv_kgb.masa_kerja_bln,g.NAMA2 as golongan2_name,g.NAMA as golongan_name,g.NAMA_PANGKAT as pangkat_name");
		$this->db->from("mv.mv_kgb");
		$this->db->join("hris.rwt_kgb","mv_kgb.id = rwt_kgb.mv_kgb_id");
        $this->db->join("hris.pegawai p",'mv_kgb.pegawai_id = p."ID"',"INNER");
		$this->db->join("hris.vw_unit_list vw",'p.UNOR_ID=vw.ID');
		$this->db->join("hris.golongan g",'g.ID=p.GOL_ID');
		$this->db->where("mv.mv_kgb.id",$id);
		$kgb_data = $this->db->get()->first_row();
		if(!$kgb_data){
			$output = array(
				'success'=>false,
				'message'=>'Tidak ada riwayat KGB'
			);
			echo json_encode($output);
			die();
		}
		header('Set-Cookie: fileDownload=true; path=/');
		$gaji = $this->db->query("select * from wage where wage.\"GOLONGAN\" = ? and wage.\"WORKING_PERIOD\" = ? ",array(str_replace("/","",$kgb_data->golongan2_name),$kgb_data->masa_kerja_thn))->first_row();
		
		
		$this->load->library('Template_doc_lib');
		$TBS = $this->template_doc_lib->gen_xls(BASEPATH."../../assets/templates/".$template_data->template_file);
		$tembusan_data = array();
		$tembusan_data [] = 'Kepala BKN, u.p. Deputi Bidang Sistem Informasi Kepegawaian';
		$tembusan_data [] = 'Kepala Kantor Tata Usaha Anggaran, Kementerian Keuangan';
		$tembusan_data [] = 'Pegawai yang bersangkutan';
		$TBS->MergeBlock('tembusan', $tembusan_data);
		$sk_data = array(
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
			'p_pangkat'=>$kgb_data->pangkat_name . "/".$kgb_data->golongan_name,
			'p_jabatan'=>'',
			'gaji_baru'=>rupiah($gaji->BASIC),
			'gaji_baru_text'=>Terbilang($gaji->BASIC),
			'gaji_lama'=>'',
			'gaji_lama_text'=>Terbilang($gaji->BASIC),
			'p_satker'=>'',
			'p_instansi'=>'',
			'kepala_satker_ybs'=>'',
			'foto'=>$this->gen_qrcode($kgb_data->ref),
			'pejabat'=>$kgb_data->pejabat,
			
		);
		
		$sk_datas = array();
		$sk_datas[] = $sk_data;
		$TBS->MergeBlock('o',$sk_datas);
		//$TBS->MergeField('r', $sk_data);
		$output_file_name = uniqid("KGB");
		if($tipe=="pdf"){
			$dir = APPPATH.'../assets/kgb'.DIRECTORY_SEPARATOR;
			$fullpath_docx =  APPPATH.'../assets/kgb'.DIRECTORY_SEPARATOR.$output_file_name.".docx";
			$fullpath_pdf =  APPPATH.'../assets/kgb'.DIRECTORY_SEPARATOR.$output_file_name.".pdf";

			$TBS->Show(OPENTBS_FILE,$fullpath_docx);
			//echo shell_exec("export HOME=/tmp && libreoffice --invisible --nologo --headless --convert-to pdf $fullpath_docx --outdir ./tmp $dir");
			shell_exec($dir."/script.sh");
			echo $dir."/script.sh";
			exec($dir."/script.sh");
			echo shell_exec("echo 123");
			echo "libreoffice --headless --convert-to pdf $fullpath_docx ";
			echo "oke";
			die();
			//$output_file_name = $output_file_name.".docx";
			//$TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields
			
		}
		else {
			$output_file_name = $output_file_name.".docx";
			$TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.	
		}
		
	}
    
}