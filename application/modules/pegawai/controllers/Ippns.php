<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Ippns extends Admin_Controller
{
    protected $permissionIppns 			= 'Pegawai.Rekapippns.View';
    protected $permissionFiltersatker   = 'Pegawai.Kepegawaian.Filtersatker';
    protected $permissionEselon1   		= 'Pegawai.Kepegawaian.permissionEselon1';
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
		$this->load->model('pegawai/mv_pegawai_model');
		$this->load->model('pegawai/riwayat_kursus_model');
        $this->lang->load('pegawai');
        
        Assets::add_css('flick/jquery-ui-1.8.13.custom.css');
        Assets::add_js('jquery-ui-1.8.13.min.js');
        $this->form_validation->set_error_delimiters("<span class='has-error'>", "</span>");
		
		// filter untuk role yang filtersatkernya aktif
		if($this->auth->has_permission($this->permissionFiltersatker)){
			$this->UNOR_ID = $this->pegawai_model->getunor_induk($this->auth->username());
		}
		if($this->auth->has_permission($this->permissionEselon1)){
			$this->UNOR_ID = $this->pegawai_model->getunor_eselon1($this->auth->username());
		}
		
    }

    /**
     * Display a list of pegawai data.
     *
     * @return void
     */
    public function rekapippns()
    {	
    	$this->auth->restrict($this->permissionIppns);
        Template::set('toolbar_title', "Rekap IPPNS");
        Template::render();
    }

	public function belum_kursus(){
		$this->auth->restrict($this->permissionIppns);

		if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
        }

		$length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

		$advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
		$filters = array();
		$unor_id = null;
		$tahun = null;
		$nama = null;

		foreach($advanced_search_filters as  $filter){
			$filters[$filter['name']] = $filter["value"];
			
			if($filter=='unit_id_key'){
				$unor_id = $filter['value'];
			}

			if($filter=='nama'){
				$nama = $filter['value'];
			}

			if($filter=='tahun'){
				$tahun = $filter['value'];
			}
		}

		$this->mv_pegawai_model->limit($length,$start);
		$records = $this->mv_pegawai_model->rekap_diklat_pegawai($unor_id,$tahun,$nama);

		$output['recordsTotal']= $output['recordsFiltered']=sizeof($data);
        $output['data']=array();

		$nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->NIP_BARU."<br><i><b>".$record->NAMA."</b></i>";
                $row []  = $record->jumlah;
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);


	}

    public function belumkursus(){
    	$this->auth->restrict($this->permissionIppns);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

        $selectedUnors = array();

        $this->db->start_cache();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
		if($advanced_search_filters){
			$filters = array();
			foreach($advanced_search_filters as  $filter){
				$filters[$filter['name']] = $filter["value"];
			}
			
			if($this->UNOR_ID == null){
				if($filters['unit_id_key']){
					$this->db->group_start();
					$this->db->where('vw."ID"',$filters['unit_id_key']);	
					$this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);	
					$this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);	
					$this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);	
					$this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);	
					$this->db->group_end();
				}
			}
			if($filters['nama']){
				$this->pegawai_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama']),"BOTH");
			}
			if($filters['tahun']){
				$this->db->where('date_part(\'year\',"TANGGAL_KURSUS") = ' . $filters['tahun'] . '');
			}
			if($filters['status'] == "1"){
				$this->pegawai_model->having("count(rk.\"ID\") <=",0);
			}
		}
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $orders = $this->input->post('order');
        $data = $this->pegawai_model->FindCountKursus($this->UNOR_ID);
        $total = 0;
        if($data)
        	$total = count($data);
        foreach($orders as $order){
            if($order['column']==1){
                $this->pegawai_model->order_by("NIP_BARU",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        $this->pegawai_model->limit($length,$start);
        $records=$this->pegawai_model->FindCountKursus($this->UNOR_ID);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->NIP_BARU."<br><i><b>".$record->NAMA."</b></i>";
                $row []  = $record->jumlah;
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
    }
	
    public function download()
	{
	  
		$advanced_search_filters  = $_GET;
		
		if($advanced_search_filters){
			$filters = $advanced_search_filters;
			if($this->UNOR_ID == null){
				if($filters['unit_id_key']){
					$this->db->group_start();
					$this->db->where('vw."ID"',$filters['unit_id_key']);	
					$this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);	
					$this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);	
					$this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);	
					$this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);	
					$this->db->group_end();
				}
			}
			 
			if($filters['nama']){
				$this->pegawai_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama']),"BOTH");	
			}
			if($filters['status'] == "1"){
				$this->pegawai_model->having("count(rk.\"ID\") <=",0);
			}
			if($filters['tahun']){
				$this->db->where('date_part(\'year\',"TANGGAL_KURSUS") = ' . $filters['tahun'] . '');
			}
		}
		$datapegwai = $this->pegawai_model->FindCountKursus($this->UNOR_ID);		
		$this->load->library('Excel');
		$objPHPExcel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load(trim($this->settings_lib->item('site.pathuploaded')).'template.xls');

		$objPHPExcel->setActiveSheetIndex(0);
		$col = 0;
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,1,"No");$col++;
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,1,"NIP");$col++;
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,1,"NAMA");$col++;
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,1,"Jumlah Kursus");$col++;
		$row = 2;
		$no = 1;
		if (isset($datapegwai) && is_array($datapegwai) && count($datapegwai)) :
			foreach ($datapegwai as $record) :
				$col = 0;
				$type = PHPExcel_Cell_DataType::TYPE_STRING;
				$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->setValueExplicit($no, $type);$col++;
				$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->setValueExplicit($record->NIP_BARU, $type);$col++;
				$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->setValueExplicit($record->NAMA, $type);$col++;
				$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->setValueExplicit($record->jumlah, $type);$col++;
			$row++;
			$no++;
			endforeach;
		endif;
		  
		$filename = "ippns_".mt_rand(1,100000).'.xls'; //just some random filename
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
		//$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
		$objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
		exit; //done.. exiting!
		
	}

}
