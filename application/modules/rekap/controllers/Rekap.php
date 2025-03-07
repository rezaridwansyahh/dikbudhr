<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * reports controller
 */
class rekap extends Admin_Controller
{

	//--------------------------------------------------------------------
	protected $permissionFiltersatker   = 'Pegawai.Kepegawaian.Filtersatker';
	protected $permissionEselon1   = 'Pegawai.Kepegawaian.permissionEselon1';
	protected $permissionViewSkp   = 'RiwayatPrestasiKerja.Kepegawaian.Rekap';
	public $satker_id= null;
	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('pegawai/pegawai_model', null, true);
		$this->load->model('pegawai/Vw_skp_model', null, true);
		$this->load->model('golongan/golongan_model', null, true);
		$this->load->model('pegawai/unitkerja_model');
		Template::set_block('sub_nav', 'reports/_sub_nav');
		Assets::add_module_js('dashboard', 'dashboard.js');

		$unit_id = $this->input->get("unit_id");
		
		if($unit_id){
			$satker = $this->unitkerja_model->find_by_id($unit_id);
			$nama_unor = array();
			$this->satker_id= $satker->ID;

			Template::set('selectedSatker', $satker);
			
		}
		// filter untuk role yang filtersatkernya aktif
		if($this->auth->has_permission($this->permissionFiltersatker)){
			$this->satker_id = $this->pegawai_model->getunor_induk($this->auth->username());
		}
		if($this->auth->has_permission($this->permissionEselon1)){
			$this->satker_id = $this->pegawai_model->getunor_eselon1($this->auth->username());
		}
 
	}
	//--------------------------------------------------------------------
	/**
	 * Displays a list of form data.
	 *
	 * @return void
	 */
	public function index()
	{
		
		$this->auth->restrict('Dashboard.Reports.View');
		
		Template::render();
	}
	
	
	public function bup_usia(){
		$data_bup_per_range_umur = $this->pegawai_model->get_bup_per_range_umur($this->satker_id); 
		Template::set('data_bup_per_range_umur', $data_bup_per_range_umur);

		Template::set('download_url',base_url('rekap/bup_usia?unit_id='.$this->satker_id.'&action=download'));
		$action = $this->input->get('action');
		if($action=='download'){
			$this->load->library('Excel');
			$objPHPExcel = new PHPExcel();
			$objPHPExcel = PHPExcel_IOFactory::load(FCPATH.DIRECTORY_SEPARATOR.'/templates/template_data_rekap_bup_usia.xlsx');

			$objPHPExcel->setActiveSheetIndex(0);
			$start_row = 5;
			foreach($data_bup_per_range_umur as $row){
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$start_row, $row['range']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$start_row, $row['bup_58']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$start_row, $row['bup_60']);
				$start_row++;			
			}
			$filename = 'REKAP_BUP_USIA';
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
			//$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
			$objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
			exit; //done.. exiting!
		}
		else {
			Template::set_view('rekap/bup_usia');
			Template::render();
		}
	}
	public function golongan_usia(){
		$data_rekap_golongan_per_usia = $this->pegawai_model->get_rekap_golongan_per_usia($this->satker_id);
		Template::set('data_rekap_golongan_per_usia', $data_rekap_golongan_per_usia);		
		
		Template::set('download_url',base_url('rekap/golongan_usia?unit_id='.$this->satker_id.'&action=download'));
		$action = $this->input->get('action');
		if($action=='download'){
			$this->load->library('Excel');
			$objPHPExcel = new PHPExcel();
			$objPHPExcel = PHPExcel_IOFactory::load(FCPATH.DIRECTORY_SEPARATOR.'/templates/template_data_rekap_golongan_usia.xlsx');

			$objPHPExcel->setActiveSheetIndex(0);
			
			$data = Template::get('data_rekap_golongan_per_usia');
			
			$start_row = 5;
			foreach($data as $row){
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$start_row, $row['nama']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$start_row, $row['<25']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$start_row, $row['25-30']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$start_row, $row['31-35']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$start_row, $row['36-40']);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$start_row, $row['41-45']);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$start_row, $row['46-50']);
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$start_row, $row['>50']);
				$start_row++;			
			}
			$filename = 'REKAP_GOLONGAN_USIA.xlsx';
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
			//$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
			$objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
			exit; //done.. exiting!
		}
		else {
			Template::set_view('rekap/golongan_usia');
			Template::render();
		}
		
	} 
	public function gender_usia(){
		$data_rekap_jenis_kelamin_per_usia = $this->pegawai_model->get_rekap_jenis_kelamin_per_usia($this->satker_id);
		Template::set('data_rekap_jenis_kelamin_per_usia', $data_rekap_jenis_kelamin_per_usia);
		
		Template::set('download_url',base_url('rekap/gender_usia?unit_id='.$this->satker_id.'&action=download'));
		$action = $this->input->get('action');
		if($action=='download'){
			$this->load->library('Excel');
			$objPHPExcel = new PHPExcel();
			$objPHPExcel = PHPExcel_IOFactory::load(FCPATH.DIRECTORY_SEPARATOR.'/templates/template_data_rekap_gender_usia.xlsx');

			$objPHPExcel->setActiveSheetIndex(0);
			$start_row = 5;
			foreach($data_rekap_jenis_kelamin_per_usia as $row){
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$start_row, $row['JENIS KELAMIN']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$start_row, $row['<25']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$start_row, $row['25-30']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$start_row, $row['31-35']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$start_row, $row['36-40']);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$start_row, $row['41-45']);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$start_row, $row['46-50']);
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$start_row, $row['>50']);
				$start_row++;			
			}
			$filename = 'REKAP_JENISKELAMIN_USIA';
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
			//$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
			$objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
			exit; //done.. exiting!
		}
		else {
			Template::set_view('rekap/gender_usia');
			Template::render();
		}
	} 
	public function pendidikan_usia(){
		$data_rekap_pendidikan_per_usia = $this->pegawai_model->get_rekap_pendidikan_per_usia($this->satker_id);
		Template::set('data_rekap_pendidikan_per_usia', $data_rekap_pendidikan_per_usia);
		Template::set('download_url',base_url('rekap/pendidikan_usia?unit_id='.$this->satker_id.'&action=download'));
		$action = $this->input->get('action');
		if($action=='download'){
			$this->load->library('Excel');
			$objPHPExcel = new PHPExcel();
			$objPHPExcel = PHPExcel_IOFactory::load(FCPATH.DIRECTORY_SEPARATOR.'/templates/template_data_rekap_pendidikan_usia.xlsx');

			$objPHPExcel->setActiveSheetIndex(0);
			$start_row = 5;
			foreach($data_rekap_pendidikan_per_usia as $row){
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$start_row, $row['TK PENDIDIKAN']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$start_row, $row['<25']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$start_row, $row['25-30']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$start_row, $row['31-35']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$start_row, $row['36-40']);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$start_row, $row['41-45']);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$start_row, $row['46-50']);
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$start_row, $row['>50']);
				$start_row++;			
			}
			$filename = 'REKAP_PENDIDIKAN_USIA';
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
			//$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
			$objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
			exit; //done.. exiting!
		}
		else {
			Template::set_view('rekap/pendidikan_usia');
			Template::render();
		}
		
	} 
	public function golongan_gender(){
		$data_rekap_golongan_per_jenis_kelamin = $this->pegawai_model->get_rekap_golongan_per_jenis_kelamin($this->satker_id);
		Template::set('data_rekap_golongan_per_jenis_kelamin', $data_rekap_golongan_per_jenis_kelamin);
		Template::set('download_url',base_url('rekap/golongan_gender?unit_id='.$this->satker_id.'&action=download'));
		$action = $this->input->get('action');
		if($action=='download'){
			$this->load->library('Excel');
			$objPHPExcel = new PHPExcel();
			$objPHPExcel = PHPExcel_IOFactory::load(FCPATH.DIRECTORY_SEPARATOR.'/templates/template_data_rekap_golongan_jenis_kelamin.xlsx');

			$objPHPExcel->setActiveSheetIndex(0);
			$start_row = 5;
			foreach($data_rekap_golongan_per_jenis_kelamin as $row){
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$start_row, $row['nama']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$start_row, $row['M']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$start_row, $row['F']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$start_row, $row['-']);
				$start_row++;			
			}
			$filename = 'REKAP_GOLONGAN_JENIS_KELAMIN';
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
			//$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
			$objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
			exit; //done.. exiting!
		}
		else {
			Template::set_view('rekap/golongan_gender');
			Template::render();
		}
		
	} 
	public function golongan_pendidikan(){
		$data_rekap_golongan_per_pendidikan = $this->pegawai_model->get_rekap_golongan_per_pendidikan($this->satker_id);
		Template::set('data_rekap_golongan_per_pendidikan', $data_rekap_golongan_per_pendidikan);
	
		Template::set('download_url',base_url('rekap/golongan_pendidikan?unit_id='.$this->satker_id.'&action=download'));
		$action = $this->input->get('action');
		if($action=='download'){
			$this->load->library('Excel');
			$objPHPExcel = new PHPExcel();
			$objPHPExcel = PHPExcel_IOFactory::load(FCPATH.DIRECTORY_SEPARATOR.'/templates/template_data_rekap_golongan_pendidikan.xlsx');

			$objPHPExcel->setActiveSheetIndex(0);
			
			$data = Template::get('data_rekap_golongan_per_pendidikan');
			
			$start_row = 5;
			foreach($data as $row){
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$start_row, $row['GOLONGAN']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$start_row, $row['SLTP Kejuruan']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$start_row, $row['SLTA Kejuruan']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$start_row, $row['Sekolah Dasar']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$start_row, $row['SLTP']);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$start_row, $row['SLTA']);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$start_row, $row['Diploma I']);
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$start_row, $row['Diploma II']);
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$start_row, $row['Diploma III/Sarjana Muda']);
				$objPHPExcel->getActiveSheet()->setCellValue('K'.$start_row, $row['Diploma IV']);
				$objPHPExcel->getActiveSheet()->setCellValue('L'.$start_row, $row['S-1/Sarjana']);
				$objPHPExcel->getActiveSheet()->setCellValue('M'.$start_row, $row['S-2']);
				$objPHPExcel->getActiveSheet()->setCellValue('N'.$start_row, $row['S-3/Doktor']);
				$objPHPExcel->getActiveSheet()->setCellValue('O'.$start_row, $row['SLTA Keguruan']);
				$start_row++;			
			}
			$filename = 'REKAP_GOLONGAN_PENDIDIKAN';
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
			//$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
			$objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
			exit; //done.. exiting!
		}
		else {
			Template::set_view('rekap/golongan_pendidikan');
		Template::render();
		}
		
	} 
	public function pendidikan_gender(){
		$data_rekap_pendidikan_per_jenis_kelamin = $this->pegawai_model->get_rekap_pendidikan_per_jenis_kelamin($this->satker_id);
		Template::set('data_rekap_pendidikan_per_jenis_kelamin', $data_rekap_pendidikan_per_jenis_kelamin);
		
		Template::set('download_url',base_url('rekap/pendidikan_gender?unit_id='.$this->satker_id.'&action=download'));
		$action = $this->input->get('action');
		if($action=='download'){
			$this->load->library('Excel');
			$objPHPExcel = new PHPExcel();
			$objPHPExcel = PHPExcel_IOFactory::load(FCPATH.DIRECTORY_SEPARATOR.'/templates/template_data_rekap_pendidikan_per_jenis_kelamin.xlsx');

			$objPHPExcel->setActiveSheetIndex(0);
			
			$data = Template::get('data_rekap_pendidikan_per_jenis_kelamin');
			
			$start_row = 5;
			foreach($data as $row){
				
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$start_row, $row['nama'])
                            ->setCellValue('C'.$start_row, $row['M'])
							->setCellValue('D'.$start_row, $row['F'])
							->setCellValue('E'.$start_row, $row['-']);
				$start_row++;			
			}
			$filename = 'REKAP_PENDIDIKAN_GENDER';
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
			//$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
			$objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
			exit; //done.. exiting!
		}
		else {
			Template::set_view('rekap/pendidikan_gender');
			Template::render();
		}
		
	} 
	public function agama_gender(){
		$data_jumlah_pegawai_per_agama_jeniskelamin = $this->pegawai_model->get_jumlah_pegawai_per_agama_jeniskelamin($this->satker_id);
		Template::set('data_jumlah_pegawai_per_agama_jeniskelamin', $data_jumlah_pegawai_per_agama_jeniskelamin);

		Template::set('download_url',base_url('rekap/agama_gender?unit_id='.$this->satker_id.'&action=download'));
		$action = $this->input->get('action');
		if($action=='download'){
			$this->load->library('Excel');
			$objPHPExcel = new PHPExcel();
			$objPHPExcel = PHPExcel_IOFactory::load(FCPATH.DIRECTORY_SEPARATOR.'/templates/template_data_rekap_agama_gender.xlsx');

			$objPHPExcel->setActiveSheetIndex(0);
			
			$data = Template::get('data_jumlah_pegawai_per_agama_jeniskelamin');
			
			$start_row = 5;
			foreach($data as $row){
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$start_row, $row->nama)
                            ->setCellValue('C'.$start_row, $row->m)
							->setCellValue('D'.$start_row, $row->f);
				$start_row++;			
			}
			$filename = 'REKAP_AGAMA_GENDER';
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
			//$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
			$objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
			exit; //done.. exiting!
		}
		else {
			Template::set_view('rekap/agama_gender');
			Template::render();
		}
	} 	
	public function stats_jabatan(){
		$data_jumlah_pegawai_per_jabatan = $this->pegawai_model->get_jumlah_pegawai_per_jabatan($this->satker_id);
		Template::set('data_jumlah_pegawai_per_jabatan', $data_jumlah_pegawai_per_jabatan);

		Template::set('download_url',base_url('rekap/stats_jabatan?unit_id='.$this->satker_id.'&action=download'));
		$action = $this->input->get('action');
		if($action=='download'){
			$this->load->library('Excel');
			$objPHPExcel = new PHPExcel();
			$objPHPExcel = PHPExcel_IOFactory::load(FCPATH.DIRECTORY_SEPARATOR.'/templates/template_data_rekap_jumlah_pegawai_per_jabatan.xlsx');

			$objPHPExcel->setActiveSheetIndex(0);
			
			$data = Template::get('data_jumlah_pegawai_per_jabatan');
			
			$start_row = 5;
			foreach($data as $row){
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$start_row, $row->NAMA)
                            ->setCellValue('C'.$start_row, $row->JUMLAH)
							;
				$start_row++;			
			}
			$filename = 'REKAP_Stats_Jabatan';
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
			//$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
			$objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
			exit; //done.. exiting!
		}
		else {
			Template::set_view('rekap/jumlah_pegawai_per_jabatan');
			Template::render();
		}
	} 	
	public function skp(){
		Template::set_view('rekap/skp');
		Template::render();
		
	}

	public function getdataskp(){
    	$this->auth->restrict($this->permissionViewSkp);
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
		
		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		$advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
		if($advanced_search_filters){
			$filters = array();
			foreach($advanced_search_filters as  $filter){
				$filters[$filter['name']] = $filter["value"];
			}
			if($filters['unit_id_cb']){
				$this->db->group_start();
				$this->db->where('vw."ID"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);	
				$this->db->group_end();
			}
			if($filters['dari_tanggal']){
				$this->db->group_start();
					$this->pegawai_model->where('created_date >=',$filters['dari_tanggal']);	
					$this->pegawai_model->where('created_date <=',$filters['sampai_tanggal']);	
				$this->db->group_end();
			}

			if($filters['nip']){
				$this->pegawai_model->where('PNS_NIP =',$filters['nip']);
			}
		}
		
		$this->db->stop_cache();
		$output=array();
		$output['draw']=$draw;
		$asatkers = null;
		if($this->auth->has_permission($this->UnitkerjaTerbatas)){
			$asatkers = json_decode($this->auth->get_satkers());
			$total= $this->Vw_skp_model->count_all($asatkers);
		}else{
			$total= $this->Vw_skp_model->count_all($this->satker_id);
		}
		
		$orders = $this->input->post('order');
		foreach($orders as $order){
			if($order['column']==1){
				$this->vw_skp_model->order_by("NIP_BARU",$order['dir']);
			}
			if($order['column']==2){
				$this->vw_skp_model->order_by("pegawai.NAMA",$order['dir']);
			}
			if($order['column']==3){
				$this->vw_skp_model->order_by("NAMA_PANGKAT",$order['dir']);
			}
			if($order['column']==4){
				$this->vw_skp_model->order_by("NAMA_UNOR",$order['dir']);
			}
		}
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
		
		$this->Vw_skp_model->limit($length,$start);
		if($this->auth->has_permission($this->UnitkerjaTerbatas)){
			$asatkers = json_decode($this->auth->get_satkers());
			$records=$this->Vw_skp_model->find_all($asatkers);
		}else{
			$records=$this->Vw_skp_model->find_all($this->satker_id);
		}
		
		
		$this->db->flush_cache();
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = "<b>".$record->PNS_NIP."</b><br>".$record->PNS_NAMA;
                $row []  = $record->TAHUN;
                $row []  = $record->NILAI_SKP;
                $row []  = $record->NILAI_PPK;
                $row []  = $record->nama_atasan;
                $row []  = $record->a_nama_atasan;
                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		
	}
	public function download_skp(){
    	$this->auth->restrict($this->permissionViewSkp);
    	$this->load->library('Convert');
        $convert = new Convert;
    	$this->load->library('Excel');
		$objPHPExcel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load(FCPATH.'assets/templates/formatrekonppkp2021.xls');

		$objPHPExcel->setActiveSheetIndex(0);
		$itemfield = $this->db->list_fields('pegawai');
		$row = 2;
		$asatkers = null;
		$dari_tanggal = $this->input->get('dari_tanggal');
		$sampai_tanggal = $this->input->get('sampai_tanggal');
		if($sampai_tanggal != ""){
			$this->db->group_start();
				$this->pegawai_model->where('created_date >=',$dari_tanggal);	
				$this->pegawai_model->where('created_date <=',$sampai_tanggal);	
			$this->db->group_end();
		}
		if($this->auth->has_permission($this->UnitkerjaTerbatas)){
			$asatkers = json_decode($this->auth->get_satkers());
			$records=$this->Vw_skp_model->find_all($asatkers);
		}else{
			$records=$this->Vw_skp_model->find_all($this->satker_id);
		}
		if (isset($records) && is_array($records) && count($records)) :
			foreach ($records as $record) :
				$status_atasan = $record->status_pns_atasan == "1" ? "ASN" : "NON ASN";
				$status_atasan_atasan = $record->status_pns_atasan_atasan == "1" ? "ASN" : "NON ASN";
				$nama_unor_atasan = $record->nama_unor_atasan != "" ? $record->nama_unor_atasan : "Kementerian Pendidikan, Kebudayaan, Riset dan Teknologi";
				$nama_unor_atasan_atasan = $record->nama_unor_atasan_atasan != "" ? $record->nama_unor_atasan_atasan : "Kementerian Pendidikan, Kebudayaan, Riset dan Teknologi";
				$golongan_atasan = $record->golongan_atasan != "" ? $record->golongan_atasan : "45";
				$golongan_atasan_atasan = $record->golongan_atasan_atasan != "" ? $record->golongan_atasan_atasan : "45";
				$col = 0;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$record->a_pns_id_atasan,PHPExcel_Cell_DataType::TYPE_STRING);$col++;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$record->pns_id_atasan,PHPExcel_Cell_DataType::TYPE_STRING);$col++;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$record->PNS_ID,PHPExcel_Cell_DataType::TYPE_STRING);$col++;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$record->TAHUN,PHPExcel_Cell_DataType::TYPE_STRING);$col++;
				$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->setValueExplicit($record->NILAI_SKP, PHPExcel_Cell_DataType::TYPE_STRING);$col++;
			   	$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->setValueExplicit($record->PERILAKU_ORIENTASI_PELAYANAN, PHPExcel_Cell_DataType::TYPE_STRING);$col++;
			   	$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->setValueExplicit($record->PERILAKU_INTEGRITAS, PHPExcel_Cell_DataType::TYPE_STRING);$col++;
			   	$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->setValueExplicit($record->PERILAKU_KOMITMEN, PHPExcel_Cell_DataType::TYPE_STRING);$col++;
			   	$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->setValueExplicit($record->PERILAKU_DISIPLIN, PHPExcel_Cell_DataType::TYPE_STRING);$col++;
			   	$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->setValueExplicit($record->PERILAKU_KERJASAMA, PHPExcel_Cell_DataType::TYPE_STRING);$col++;
			   	$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->setValueExplicit($record->PERILAKU_KEPEMIMPINAN, PHPExcel_Cell_DataType::TYPE_STRING);$col++;
			   	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$record->ATASAN_ATASAN_LANGSUNG_PNS_JABATAN,PHPExcel_Cell_DataType::TYPE_STRING);$col++;
			   	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$record->ATASAN_LANGSUNG_PNS_JABATAN,PHPExcel_Cell_DataType::TYPE_STRING);$col++;
			   	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$golongan_atasan,PHPExcel_Cell_DataType::TYPE_STRING);$col++;
			   	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$golongan_atasan_atasan,PHPExcel_Cell_DataType::TYPE_STRING);$col++;
			   	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$convert->fmtDate($record->tmt_golongan_atasan,"dd/mm/yyyy"),PHPExcel_Cell_DataType::TYPE_STRING);$col++;
			   	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$convert->fmtDate($record->tmt_golongan_atasan_atasan,"dd/mm/yyyy"),PHPExcel_Cell_DataType::TYPE_STRING);$col++;
			   	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$nama_unor_atasan,PHPExcel_Cell_DataType::TYPE_STRING);$col++;
			   	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$nama_unor_atasan_atasan,PHPExcel_Cell_DataType::TYPE_STRING);$col++;
			   	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$record->ATASAN_LANGSUNG_PNS_NAMA,PHPExcel_Cell_DataType::TYPE_STRING);$col++;
			   	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$record->ATASAN_ATASAN_LANGSUNG_PNS_NAMA,PHPExcel_Cell_DataType::TYPE_STRING);$col++;
			   	$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->setValueExplicit($record->ATASAN_LANGSUNG_PNS_NIP, PHPExcel_Cell_DataType::TYPE_STRING);$col++;
			   	$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->setValueExplicit($record->ATASAN_ATASAN_LANGSUNG_PNS_NIP, PHPExcel_Cell_DataType::TYPE_STRING);$col++;
			   	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$status_atasan,PHPExcel_Cell_DataType::TYPE_STRING);$col++;
			   	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$status_atasan_atasan,PHPExcel_Cell_DataType::TYPE_STRING);$col++;
			   	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$record->JENIS_JABATAN_ID,PHPExcel_Cell_DataType::TYPE_STRING);$col++;
			   	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$record->PERATURAN,PHPExcel_Cell_DataType::TYPE_STRING);$col++;
			   	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$record->PERILAKU_INISIATIF_KERJA,PHPExcel_Cell_DataType::TYPE_STRING);$col++;
				$row++;
			endforeach;
		endif;
		  
		$filename = "skp".mt_rand(1,100000).'.xls'; //just some random filename
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
		//$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
		$objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
		exit; //done.. exiting!
    }
}