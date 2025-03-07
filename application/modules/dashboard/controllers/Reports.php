<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * reports controller
 */
class reports extends Admin_Controller
{

	//--------------------------------------------------------------------
	public $UNOR_ID = null;
	protected $CI;
	protected $permissionView   = 'Dashboard.Reports.View';
	protected $permissionFiltersatker   = 'Pegawai.Kepegawaian.Filtersatker';
	protected $permissionEselon1   = 'Pegawai.Kepegawaian.permissionEselon1';
	protected $permissionViewPribadi   = 'Dashboard.Pribadi.View';
	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->CI = &get_instance();
		$this->lang->load('dashboard');
		Template::set_block('sub_nav', 'reports/_sub_nav');
		Assets::add_module_js('dashboard', 'dashboard.js');
		$this->load->model('pegawai/unitkerja_model');
		$this->load->model('pegawai/pegawai_model', null, true);
		$this->load->model('sk_ds/vw_ds_jumlah_pernip_model');
		$this->load->model('sk_ds/sk_ds_model');
		
		//CEK DARI INPUTAN FILTER UNOR
		$unit_id = $this->input->get("unit_id");
		if($unit_id){
			$satker = $this->unitkerja_model->find_by_id($unit_id);
			
			$nama_unor = array();
			$this->UNOR_ID= $satker->ID;
			
			Template::set('selectedSatker', $satker);
			
		}
		 
		// filter untuk role yang filtersatkernya aktif
		if($this->auth->has_permission($this->permissionFiltersatker)){
			$this->UNOR_ID = $this->pegawai_model->getunor_induk($this->auth->username());
		}
		if($this->auth->has_permission($this->permissionEselon1)){
			$this->UNOR_ID = $this->pegawai_model->getunor_eselon1($this->auth->username());
		}
		if($unit_id != ""){
			$this->UNOR_ID = $unit_id;
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
		$this->auth->restrict($this->permissionView);
		$recsatker = $this->unitkerja_model->count_satker($this->UNOR_ID);
		Template::set('jmlsatker', $recsatker[0]->jumlah ? $recsatker[0]->jumlah : 0);
		// jml pegawai
		$total= $this->pegawai_model->count_all($this->UNOR_ID);
		Template::set('totalpegawai', $total);
		$jmlpensiun = $this->pegawai_model->count_pensiun($this->UNOR_ID); 
		Template::set('jmlpensiun', $jmlpensiun);

		
		
		$jsonpendidikan = array();
		$recordpendidikan = $this->pegawai_model->grupbypendidikan($this->UNOR_ID); 
		$dataprov = array();
		if (isset($recordpendidikan) && is_array($recordpendidikan) && count($recordpendidikan)) :
			foreach ($recordpendidikan as $record) :
				if($record->NAMA_PENDIDIKAN != "")
					$dataprov["NAMA"] = $record->NAMA_PENDIDIKAN;
				else
					$dataprov["NAMA"] = "-";
				$dataprov["jumlah"] = $record->jumlah;
				$jsonpendidikan[] 	= $dataprov;
			endforeach;
		endif;
		Template::set('jsonpendidikan', json_encode($jsonpendidikan));
		
		$recordumur = $this->pegawai_model->group_by_range_umur($this->UNOR_ID);
		
		$colors=    array('#FCD202', '#B0DE09','#FF6600', '#0D8ECF', '#2A0CD0', '#CD0D74', '#CC0000', '#00CC00', '#0000CC', '#DDDDDD', '#999999', '#333333', '#990000');
		$ajsonumur = array();
		$index = 0 ;
		foreach(array_keys($recordumur[0]) as $key){
			$jsonumur = array("color"=>$colors[$index],"label"=>$key,"jumlah"=>isset($recordumur[0][$key]) ? $recordumur[0][$key] : 0);
			$ajsonumur[] 	= $jsonumur;
			$index++;
		}
		
		Template::set('jsonumur', json_encode($ajsonumur));
		// generasi
		$json_generasi = $this->get_jumlah_generasi($this->UNOR_ID);
		Template::set('json_generasi', $json_generasi);
		// agama
		$agamas = $this->pegawai_model->find_grupagama($this->UNOR_ID);
		foreach($agamas as $rec):
			if($rec->label =="")
				$rec->label = "Kosong";
		endforeach;
		Template::set('agamas', $agamas);
		
		// jenis kelamin
		$jks = $this->pegawai_model->grupbyjk($this->UNOR_ID);
		$jsonjk = array();
		$datajk = array();
		if (isset($jks) && is_array($jks) && count($jks)) :
			foreach ($jks as $record) :
				if($record->JENIS_KELAMIN != "")
					$datajk["Jenis_Kelamin"] = $record->JENIS_KELAMIN;
				else
					$datajk["Jenis_Kelamin"] = "Kosong";
				$datajk["jumlah"] = $record->jumlah;
				$jsonjk[] 	= $datajk;
			endforeach;
		endif;
		Template::set('jsonjk', json_encode($jsonjk));
		// pensiun pertahun
		// belum di kasih filter buat role executive
		$pensiuntahun = $this->pegawai_model->stats_pensiun_pertahun($this->UNOR_ID);
		
		$index = 0;
		foreach($pensiuntahun as &$row){
			$row['color'] = $colors[$index];
			$index++;
		}
		Template::set('jsonpensiuntahun', json_encode($pensiuntahun));
		
		// rekap_pangkat_golongan
		$data_jumlah_pegawai_per_golongan = $this->pegawai_model->get_jumlah_pegawai_per_golongan($this->UNOR_ID);
		
		$index = 0;
		foreach($data_jumlah_pegawai_per_golongan as &$row){
			$row['color'] = $colors[$index];
			$index++;
		}
		Template::set('data_jumlah_pegawai_per_golongan', json_encode($data_jumlah_pegawai_per_golongan));
		
		// rekap kategori jabatan eselon 1
		$data_jml_pegawai_perkategori_jabatan = $this->pegawai_model->grupby_kategori_jabatan($this->UNOR_ID);
		$index = 0;
		foreach($data_jml_pegawai_perkategori_jabatan as &$row){
			// $row['color'] = $colors[$index];
			$index++;
		}
		Template::set('json_kategori_jabatan', json_encode($data_jml_pegawai_perkategori_jabatan));

		$action = $this->input->get("action");
		if($action=="download"){
			$data = $this->input->get("data");
			if($data =='golongan'){
				$o = json_decode(Template::get('data_jumlah_pegawai_per_golongan'));
				$this->load->library('Excel');
				$objPHPExcel = new PHPExcel();
				$objPHPExcel = PHPExcel_IOFactory::load(FCPATH.DIRECTORY_SEPARATOR.'/templates/template_data_dashboard_golongan.xlsx');

				$objPHPExcel->setActiveSheetIndex(0);
				$start_row = 5;
				foreach($o as $row){
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$start_row, $row->nama);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$start_row, $row->total);
					$start_row++;			
				}
				$filename = 'DASHBOARD_GOLONGAN.xls';
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="'.$filename.'"');
				header('Cache-Control: max-age=0');

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
				//$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
				$objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
				exit; //done.. exiting!

			}
			else if($data =='proyeksi_pensiun'){
				$o = json_decode(Template::get('jsonpensiuntahun'));
				$this->load->library('Excel');
				$objPHPExcel = new PHPExcel();
				$objPHPExcel = PHPExcel_IOFactory::load(FCPATH.DIRECTORY_SEPARATOR.'/templates/template_data_dashboard_proyeksi_pensiun.xlsx');

				$objPHPExcel->setActiveSheetIndex(0);
				$start_row = 5;
				foreach($o as $row){
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$start_row, $row->tahun);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$start_row, $row->jumlah);
					$start_row++;			
				}
				$filename = 'DASHBOARD_PROYEKSI_PENSIUN.xls';
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="'.$filename.'"');
				header('Cache-Control: max-age=0');

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
				//$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
				$objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
				exit; //done.. exiting!
			}
			else if($data =='pendidikan'){				
				$o = json_decode(Template::get('jsonpendidikan'));
				$this->load->library('Excel');
				$objPHPExcel = new PHPExcel();
				$objPHPExcel = PHPExcel_IOFactory::load(FCPATH.DIRECTORY_SEPARATOR.'/templates/template_data_dashboard_pendidikan.xlsx');

				$objPHPExcel->setActiveSheetIndex(0);
				$start_row = 5;
				foreach($o as $row){
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$start_row, $row->NAMA);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$start_row, $row->jumlah);
					$start_row++;			
				}
				$filename = 'DASHBOARD_PENDIDIKAN.xls';
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="'.$filename.'"');
				header('Cache-Control: max-age=0');

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
				//$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
				$objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
				exit; //done.. exiting!
			}
			else if($data =='agama'){
				$o = Template::get('agamas');
				$this->load->library('Excel');
				$objPHPExcel = new PHPExcel();
				$objPHPExcel = PHPExcel_IOFactory::load(FCPATH.DIRECTORY_SEPARATOR.'/templates/template_data_dashboard_agama.xlsx');

				$objPHPExcel->setActiveSheetIndex(0);
				$start_row = 5;
				foreach($o as $row){
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$start_row, $row->label);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$start_row, $row->value);
					$start_row++;			
				}
				$filename = 'DASHBOARD_AGAMA.xls';
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="'.$filename.'"');
				header('Cache-Control: max-age=0');

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
				//$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
				$objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
				exit; //done.. exiting!
			}
			else if($data =='umur'){
				
				$o = json_decode(Template::get('jsonumur'));
				$this->load->library('Excel');
				$objPHPExcel = new PHPExcel();
				$objPHPExcel = PHPExcel_IOFactory::load(FCPATH.DIRECTORY_SEPARATOR.'/templates/template_data_dashboard_umur.xlsx');

				$objPHPExcel->setActiveSheetIndex(0);
				$start_row = 5;
				foreach($o as $row){
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$start_row, $row->label);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$start_row, $row->jumlah);
					$start_row++;			
				}
				$filename = 'DASHBOARD_UMUR.xls';
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="'.$filename.'"');
				header('Cache-Control: max-age=0');

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
				//$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
				$objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
				exit; //done.. exiting!
			}
			else if($data =='jenis_kelamin'){
				$o = json_decode(Template::get('jsonjk'));
				$this->load->library('Excel');
				$objPHPExcel = new PHPExcel();
				$objPHPExcel = PHPExcel_IOFactory::load(FCPATH.DIRECTORY_SEPARATOR.'/templates/template_data_dashboard_jenis_kelamin.xlsx');

				$objPHPExcel->setActiveSheetIndex(0);
				$start_row = 5;
				foreach($o as $row){
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$start_row, $row->Jenis_Kelamin);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$start_row, $row->jumlah);
					$start_row++;			
				}
				$filename = 'DASHBOARD_JENISKELAMIN.xls';
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="'.$filename.'"');
				header('Cache-Control: max-age=0');

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
				//$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
				$objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
				exit; //done.. exiting!
			}
			else if($data =='generasi'){
				$o = json_decode(Template::get('json_generasi'));
				$this->load->library('Excel');
				$objPHPExcel = new PHPExcel();
				$objPHPExcel = PHPExcel_IOFactory::load(FCPATH.DIRECTORY_SEPARATOR.'/templates/template_data_dashboard_jenis_kelamin.xlsx');

				$objPHPExcel->getActiveSheet()->setCellValue('B2', "Matriks Generasi dan Jumlah Pegawai");
				$objPHPExcel->getActiveSheet()->setCellValue('B4', "Generasi");
				$objPHPExcel->setActiveSheetIndex(0);
				$start_row = 5;
				foreach($o as $row){
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$start_row, $row->generasi);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$start_row, $row->jumlah);
					$start_row++;			
				}
				$filename = 'dashboard_generasi.xls';
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="'.$filename.'"');
				header('Cache-Control: max-age=0');

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
				//$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
				$objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
				exit; //done.. exiting!
			}
			else if($data =='kategori_jabatan'){
				$o = json_decode(Template::get('json_kategori_jabatan'));
				$this->load->library('Excel');
				$objPHPExcel = new PHPExcel();
				$objPHPExcel = PHPExcel_IOFactory::load(FCPATH.DIRECTORY_SEPARATOR.'/templates/template_data_dashboard_jenis_kelamin.xlsx');

				$objPHPExcel->setActiveSheetIndex(0);
				$objPHPExcel->getActiveSheet()->setCellValue('B2', "Rekap Berdasarkan Kategori Jabatan");
				$objPHPExcel->getActiveSheet()->setCellValue('B4', "Kategori Jabatan");
				$start_row = 5;
				foreach($o as $row){
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$start_row, $row->KATEGORI_JABATAN);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$start_row, $row->jumlah);
					$start_row++;			
				}
				$filename = 'kategori_jabatan.xls';
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="'.$filename.'"');
				header('Cache-Control: max-age=0');

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
				$objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
				exit; //done.. exiting!
			}
			else if($data =='kategori_jabatan_eselon1'){
				$o = json_decode(Template::get('json_kategori_jabatan'));
				$this->load->library('Excel');
				$objPHPExcel = new PHPExcel();
				$objPHPExcel = PHPExcel_IOFactory::load(FCPATH.DIRECTORY_SEPARATOR.'/templates/template_ketegori_jabatan_eselon1.xlsx');
				$unitkerja = 'A8ACA7397AEB3912E040640A040269BB';
				$datadetil = $this->unitkerja_model->find_view_unit_list($unitkerja);
				$aunors = Modules::run('pegawai/manage_unitkerja/getcache_unor',$unitkerja);
		        $json_unor = $this->cache->get('json_unor');
		        array_unshift($json_unor[$unitkerja], $datadetil[0]);
		        $satker = $json_unor[$unitkerja];

		        // jumlah
		        $data_jml_pegawai_perkategori_jabatan = $this->pegawai_model->grupby_kategori_jabatan_eselon1($this->UNOR_ID);
				$index = 0;
				$akategori_jabatan = array();
				foreach($data_jml_pegawai_perkategori_jabatan as &$row){
					$akategori_jabatan[str_replace(" ", "_", $row->KATEGORI_JABATAN)."_".$row->ESELON_1] = $row->jumlah;
					$index++;
				}

		        // echo "<pre>";
		        // print_r($akategori_jabatan);
		        // echo "</pre>";
		        // die();
				$objPHPExcel->setActiveSheetIndex(0);
				// $objPHPExcel->getActiveSheet()->setCellValue('B4', "Kategori Jabatan");
				$start_row = 7;
				$no = 1;
				$jml_Karir = 0;
				$jml_JPT_Madya = 0;
				$jml_JPT_Pratama = 0;
				$jml_Administrator = 0;
				$jml_Pengawas = 0;
				$jml_Fungsional = 0;
				$jml_Pelaksana = 0;
				$total = 0;
				foreach($satker as $row){
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$start_row, $no);
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$start_row, $row->NAMA_UNOR);
					
					$Karir = isset($akategori_jabatan["Karir_".$row->ID]) ? $akategori_jabatan["Karir_".$row->ID] : 0;
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$start_row, $Karir);
					$jml_Karir = $jml_Karir + $Karir;

					$JPT_Madya = isset($akategori_jabatan["JPT_Madya_".$row->ID]) ? $akategori_jabatan["JPT_Madya_".$row->ID] : 0;
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$start_row, $JPT_Madya);
					$jml_JPT_Madya = $jml_JPT_Madya + $JPT_Madya;

					$JPT_Pratama = isset($akategori_jabatan["JPT_Pratama_".$row->ID]) ? $akategori_jabatan["JPT_Pratama_".$row->ID] : 0;
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$start_row, $JPT_Pratama);
					$jml_JPT_Pratama = $jml_JPT_Pratama + $JPT_Pratama;

					$Administrator = isset($akategori_jabatan["Administrator_".$row->ID]) ? $akategori_jabatan["Administrator_".$row->ID] : 0;
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$start_row, $Administrator);
					$jml_Administrator = $jml_Administrator + $Administrator;

					$Pengawas = isset($akategori_jabatan["Pengawas_".$row->ID]) ? $akategori_jabatan["Pengawas_".$row->ID] : 0;
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$start_row, $Pengawas);
					$jml_Pengawas = $jml_Pengawas + $Pengawas;

					$Fungsional = isset($akategori_jabatan["Fungsional_".$row->ID]) ? $akategori_jabatan["Fungsional_".$row->ID] : 0;
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$start_row, $Fungsional);
					$jml_Fungsional = $jml_Fungsional + $Fungsional;

					$Pelaksana = isset($akategori_jabatan["Pelaksana_".$row->ID]) ? $akategori_jabatan["Pelaksana_".$row->ID] : 0;
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$start_row, $Pelaksana);
					$jml_Pelaksana = $jml_Pelaksana + $Pelaksana;
					// $Pelaksana = isset($akategori_jabatan["Pelaksana_".$row->ID]) ? $akategori_jabatan["Pelaksana_".$row->ID] : 0;
					// $objPHPExcel->getActiveSheet()->setCellValue('J'.$start_row, $Pelaksana);

					$jumlah = (int)$Karir + (int)$JPT_Madya + (int)$JPT_Pratama + (int)$Administrator + (int)$Pengawas + (int)$Fungsional + (int)$Pelaksana;
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$start_row, $jumlah);
					$total = $total + $jumlah;
					$start_row++;			
					$no++;
				}
				// jumlah
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$start_row, "JUMLAH KESELURUHAN");
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$start_row, $jml_Karir);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$start_row, $jml_JPT_Madya);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$start_row, $jml_JPT_Pratama);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$start_row, $jml_Administrator);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$start_row, $jml_Pengawas);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$start_row, $jml_Fungsional);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$start_row, $jml_Pelaksana);
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$start_row, $total);

				$filename = 'kategori_jabatan.xls';
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="'.$filename.'"');
				header('Cache-Control: max-age=0');

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
				$objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
				exit; //done.. exiting!
			}
			return;
		}
		Template::render();
	}
	private function get_jumlah_generasi($id_unor = ""){
		$record_generasi = $this->pegawai_model->group_by_range_tahun_lahir($id_unor);
		$json_generasi = array();
		$json_generasi[] 	= array(
				"generasi"=>'Boomer',
				"jumlah"=>isset($record_generasi[0]['boomer']) ? $record_generasi[0]['boomer'] : 0,
				"color"=>'#FF6600'
			);
		$json_generasi[] 	= array(
				"generasi"=>'GenX',
				"jumlah"=>isset($record_generasi[0]['Genx']) ? $record_generasi[0]['Genx'] : 0,
				"color"=>'#FCD202'
			);
		$json_generasi[] 	= array(
				"generasi"=>'GenY',
				"jumlah"=>isset($record_generasi[0]['GenY']) ? $record_generasi[0]['GenY'] : 0,
				"color"=>'#33cc33'
			);
		$json_generasi[] 	= array(
				"generasi"=>'GenZ',
				"jumlah"=>isset($record_generasi[0]['GenZ']) ? $record_generasi[0]['GenZ'] : 0,
				"color"=>'#2A0CD0'
			);

		return json_encode($json_generasi);
	}
	// menu dashboard pribadi setiap pegawai

	private function getListSK($nip){

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://mutasi.sdm.kemdikbud.go.id/layanan/json/ws_status_proses_ds_layanan_mutasi.php?nip=$nip",
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
		return json_decode($response);

	}

	public function pribadi()
	{
		$this->auth->restrict($this->permissionViewPribadi);
		$this->load->model('izin_pegawai/izin_pegawai_model');

		$ses_nip    = trim($this->auth->username());

		
		$list_sk = $this->getListSK($ses_nip);
		$temp_array = array();
		foreach ($list_sk as $key => $value) {
			if(!isset($temp_array[$value->nomor_sk])){
				$temp_array[$value->nomor_sk] = $value;
			}
		}

		

		$list_sk = array();
		foreach ($temp_array as $key => $value) {
			array_push($list_sk,$value);
		}

		
		Template::set("sk",$list_sk);
		Template::set("nip",$ses_nip);

		
		Template::set('toolbar_title', "Dashboard Pribadi");
		Template::render();
	}
	public function getdataupdatemandiri_pribadi(){
    	$this->auth->restrict($this->permissionViewPribadi);
    	$ses_nip    = trim($this->auth->username());
    	$this->load->model('pegawai/update_mandiri_model');
    	if (!$this->input->is_ajax_request()) {
   			Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/reports/dashboard/pribadi');
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

		$this->db->start_cache();
		$advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
		if($advanced_search_filters){
			$filters = array();
			foreach($advanced_search_filters as  $filter){
				$filters[$filter['name']] = $filter["value"];
			}
			
			if($filters['nama_cb']){
				$this->update_mandiri_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");	
			}
			if($filters['nip_cb']){
				$this->update_mandiri_model->like('upper("NIP_BARU")',strtoupper($filters['nip_key']),"BOTH");	
			}
		}
		$this->update_mandiri_model->where('upper("NIP_BARU")',strtoupper($ses_nip));	
		$this->db->stop_cache();
		$output=array();
		$output['draw']=$draw;
		$total= $this->update_mandiri_model->count_notif_pribadi($this->UNOR_ID);
		$orders = $this->input->post('order');
		foreach($orders as $order){
			if($order['column']==1){
				$this->update_mandiri_model->order_by("NAMA_KOLOM",$order['dir']);
			}
		}
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
		
		$this->update_mandiri_model->limit($length,$start);
		$records=$this->update_mandiri_model->find_notif_pribadi($this->UNOR_ID);
		$this->db->flush_cache();
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = str_replace("_", " ", $record->NAMA_KOLOM);
                $row []  = $record->STATUS == "1" ? "<small class='label text-center bg-green'>Sudah Verifikasi</small>" : "<small class='label text-center bg-yellow'>Belum Verifikasi</small>" ;
                $btn_actions = array();
                
            	if(trim($record->NAMA_KOLOM) == "RIWAYAT PENDIDIKAN"){
            		$btn_actions  [] = "<a href='".base_url()."pegawai/riwayatpendidikan/editmandiri/".$record->PNS_ID."/".$record->ID_TABEL."' class='btn btn-sm btn-info show-modal'><i class='fa fa-eye'></i> </a>";
            	}else if(trim($record->NAMA_KOLOM) == "RIWAYAT KEPANGKATAN"){
            		$btn_actions  [] = "<a href='".base_url()."pegawai/riwayatkepangkatan/editmandiri/".$record->PNS_ID."/".$record->ID_TABEL."' class='btn btn-sm btn-info show-modal'><i class='fa fa-eye'></i> </a>";
            	}else if(trim($record->NAMA_KOLOM) == "RIWAYAT JABATAN"){
            		$btn_actions  [] = "<a href='".base_url()."pegawai/riwayatjabatan/editmandiri/".$record->PNS_ID."/".$record->ID_TABEL."' class='btn btn-sm btn-info show-modal'><i class='fa fa-eye'></i> </a>";
            	}
            	else{
            		$btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/lihatupdate_pribadi/".$record->ID."' class='btn btn-sm btn-info show-modal'><i class='fa fa-eye'></i> </a>";	
            	}
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		
	}	 
}