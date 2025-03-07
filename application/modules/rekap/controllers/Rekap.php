<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * reports controller
 */
class rekap extends Admin_Controller
{

	//--------------------------------------------------------------------
	protected $permissionFiltersatker   = 'Pegawai.Kepegawaian.Filtersatker';
	protected $permissionEselon1   = 'Pegawai.Kepegawaian.permissionEselon1';
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
}