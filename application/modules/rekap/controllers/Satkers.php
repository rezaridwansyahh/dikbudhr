<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * reports controller
 */
class Satkers extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('pegawai/pegawai_model', null, true);
		$this->load->model('golongan/golongan_model', null, true);
		$this->load->model('pegawai/unitkerja_model');
		$this->load->model('pegawai/vwm_jml_pegawai_model');
	}
	public function index(){
		
		Template::set('toolbar_title', "Rekap Satker");
		Template::set_view('rekap/satkers.php');
		Template::render();
	}
	 
	public function getdatarekap(){
    	$this->auth->restrict($this->permissionView);
    	if (!$this->input->is_ajax_request()) {
   			Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/rekap/satkers');
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
		
		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		$advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
		if($advanced_search_filters){
			$filters = array();
			foreach($advanced_search_filters as  $filter){
				$filters[$filter['name']] = $filter["value"];
			}
			if($filters['unit_id_key']){
				$this->db->group_start();
				$this->db->where('unitkerja."ID"',$filters['unit_id_key']);	
				// $this->db->or_where('unitkerja."DIATASAN_ID"',$filters['unit_id_key']);	
				$this->db->group_end();
			}
			if($filters['jenis_satker']){
				$this->db->where('"JENIS_SATKER"',$filters['jenis_satker']);	
			}
			
		}
		
		$this->db->stop_cache();
		$output=array();
		$output['draw']=$draw;
		$asatkers = null;
		
		$total = $this->unitkerja_model->count_rekap_satker();
		
		$orders = $this->input->post('order');
		foreach($orders as $order){
			if($order['column']==1){
				$this->unitkerja_model->order_by("NAMA_UNOR",$order['dir']);
			}
			 
		}
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
		
		$this->pegawai_model->limit($length,$start);
		$records=$this->unitkerja_model->find_rekap_satker();
		$this->db->flush_cache();
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->NAMA_UNOR;
                $row []  = number_format($record->jml_pegawai, 0, ',', '.');  

                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		
	}
	public function getdatarekapold(){
    	$this->auth->restrict($this->permissionView);
    	if (!$this->input->is_ajax_request()) {
   			Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/rekap/satkers');
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
		
		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		$advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
		if($advanced_search_filters){
			$filters = array();
			foreach($advanced_search_filters as  $filter){
				$filters[$filter['name']] = $filter["value"];
			}
			if($filters['unit_id_key']){
				$this->db->group_start();
				$this->db->where('unitkerja."ID"',$filters['unit_id_key']);	
				$this->db->group_end();
			}
			if($filters['jenis_satker']){
				$this->db->where('"JENIS_SATKER"',$filters['jenis_satker']);	
			}
			
		}
		
		$this->db->stop_cache();
		$output=array();
		$output['draw']=$draw;
		$asatkers = null;
		
		$total = $this->vwm_jml_pegawai_model->count_all();
		
		$orders = $this->input->post('order');
		foreach($orders as $order){
			if($order['column']==1){
				$this->vwm_jml_pegawai_model->order_by("NAMA_UNOR",$order['dir']);
			}
			 
		}
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
		
		$this->pegawai_model->limit($length,$start);
		$records=$this->vwm_jml_pegawai_model->find_all();
		$this->db->flush_cache();
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->NAMA_UNOR;
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
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
		if($advanced_search_filters){
			$filters = array();
			foreach($advanced_search_filters as  $filter){
				$filters[$filter['name']] = $filter["value"];
			}
			if($filters['unit_id_key']){
				$this->db->group_start();
				$this->db->where('unitkerja."ID"',$filters['unit_id_key']);	
				$this->db->group_end();
			}
			if($filters['jenis_satker']){
				$this->db->where('"JENIS_SATKER"',$filters['jenis_satker']);	
			}
			
		}
		//$this->unitkerja_model->limit(100);
        $records = $this->unitkerja_model->find_rekap_satker();
        $this->load->library('Excel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel = PHPExcel_IOFactory::load(trim($this->settings_lib->item('site.pathuploaded')).'template.xls');

        $objPHPExcel->setActiveSheetIndex(0);
        $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,1,"No");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,1,"Satuan Kerja");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,1,"Jumlah");
        $col++;
        
        $row = 2;
        $no = 1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $col = 0;
                $type = PHPExcel_Cell_DataType::TYPE_STRING;
                $typenumber = PHPExcel_Cell_DataType::TYPE_NUMERIC;
                $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $row)->setValueExplicit($no, $type);
                $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $row)->setValueExplicit($record->NAMA_UNOR, $type);
                $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $row)->setValueExplicit($record->jml_pegawai,$typenumber);
                $row++;
                $no++;
            }
        endif;
          
        $filename = "rekap_".mt_rand(1,100000).'.xls'; //just some random filename
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
        //$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
        $objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
        exit; //done.. exiting!
        
    }
	 
}	