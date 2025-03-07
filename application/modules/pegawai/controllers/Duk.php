<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Duk extends Admin_Controller
{
    protected $permissionCreate = 'DiklatFungsional.Kepegawaian.Create';
    protected $permissionDelete = 'DiklatFungsional.Kepegawaian.Delete';
    protected $permissionEdit   = 'DiklatFungsional.Kepegawaian.Edit';
    protected $permissionView   = 'DiklatFungsional.Kepegawaian.View';
    protected $permissionViewDuksatker   = 'Rekap.DukSatker.View';
	public $UNOR_ID = null;
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/pegawai_model');
        // filter untuk role executive
		if($this->auth->has_permission($this->permissionFiltersatker)){
			$this->UNOR_ID = $this->pegawai_model->getunor_induk($this->auth->username());
		}
		if($this->auth->has_permission($this->permissionEselon1)){
			$this->UNOR_ID = $this->pegawai_model->getunor_eselon1($this->auth->username());
		}
    }
    public function ajax_list(){
        $draw= $this->input->post('draw');
		$length= $this->input->post('length');
		$start= $this->input->post('start');
		$unit_id = $this->input->post('unit_id') != "" ? $this->input->post('unit_id') : $this->UNOR_ID;
		$o = $this->pegawai_model->get_duk_list($unit_id,$start,$length);
		
		$nomor_urut=$start+1;
		$records = array();
		foreach ($o->data as $record) {
			$row = array();
			$nama_full = array();
			if($record->GELAR_DEPAN)$nama_full[] = $record->GELAR_DEPAN;
			$nama_full[] = $record->NAMA;
			$temp = implode(" ",$nama_full);
			$nama_full = array();
			$nama_full[] = $temp;
			if($record->GELAR_BELAKANG)$nama_full[] = $record->GELAR_BELAKANG;
			$temp = implode(", ",$nama_full);
			$usia_tahun = (int)($record->bulan_usia/12);
			$usia_bulan = (int)$record->bulan_usia%12;
			$row[]  = $nomor_urut;			
			$row[] = $temp;
			$row[] = $record->golongan_text;
			$row[] = $record->JABATAN_NAMA;
			$row[] = $record->MK_TAHUN." Thn ".$record->MK_BLN." Bln";
			$row[] = $record->TMT_CPNS;
			$row[] = $record->TMT_GOLONGAN;
			$row[] = $record->PENDIDIKAN;
			$row[] = $usia_tahun." Thn ".$usia_bulan." Bln";
			$row[] = $record->NAMA_UNOR;
			
			$records[] = $row;
			$nomor_urut++;
		}
	
		$output = array(
			'draw'=>$draw,
			'recordsFiltered'=>$o->total,
			'recordsTotal'=>$o->total,
			'data'=>$records
		);
		echo json_encode($output);
		die();
    }
    
    public function index(){
        Template::render();
    }
    public function duksatker(){
    	$this->auth->restrict($this->permissionViewDuksatker);
    	$this->load->model("pegawai/unitkerja_model");
    	$this->load->library('Convert');
        $convert = new Convert;
		$UNOR_ID = $this->pegawai_model->getunor_induk($this->auth->username());
		$data_satker = $this->unitkerja_model->find_by("ID",$UNOR_ID);

		$nama_bulan = $convert->getmonth(date("m"));
		Template::set('nama_bulan', $nama_bulan);
		$NAMA_UNOR = $data_satker->NAMA_UNOR ? $data_satker->NAMA_UNOR : "";
    	Template::set('toolbar_title', "Daftar urut kepangkatan satker".$NAMA_UNOR);
    	Template::set('NAMA_UNOR', $NAMA_UNOR);
        Template::render();
    }
    public function ajax_list_satker(){
    	$UNOR_ID = $this->pegawai_model->getunor_induk($this->auth->username());
        $draw= $this->input->post('draw');
		$length= $this->input->post('length');
		$start= $this->input->post('start');
		$unit_id = $this->input->post('unit_id') != "" ? $this->input->post('unit_id') : $UNOR_ID;
		$o = $this->pegawai_model->get_duk_list_satker($unit_id,$start,$length);
		
		$nomor_urut=$start+1;
		$records = array();
		foreach ($o->data as $record) {
			$row = array();
			$nama_full = array();
			if($record->GELAR_DEPAN)$nama_full[] = $record->GELAR_DEPAN;
			$nama_full[] = $record->NAMA;
			$temp = implode(" ",$nama_full);
			$nama_full = array();
			$nama_full[] = $temp;
			if($record->GELAR_BELAKANG)$nama_full[] = $record->GELAR_BELAKANG;
			$temp = implode(", ",$nama_full);
			$usia_tahun = (int)($record->bulan_usia/12);
			$usia_bulan = (int)$record->bulan_usia%12;
			$row[]  = $nomor_urut;			
			$row[] = $record->NAMA_UNOR;
			$row[] = "<i>".$temp."</i><br>".$record->NIP_BARU;
			$row[] = $record->KARTU_PEGAWAI;
			$row[] = $record->golongan_text;
			$row[] = $record->TMT_GOLONGAN;
			$row[] = $record->JABATAN_NAMA;
			$row[] = $record->MK_TAHUN." Thn ".$record->MK_BLN." Bln";
			$row[] = $record->TMT_CPNS;
			$row[] = $record->PENDIDIKAN;
			$row[] = $usia_tahun." Thn ".$usia_bulan." Bln";
			
			
			$records[] = $row;
			$nomor_urut++;
		}
	
		$output = array(
			'draw'=>$draw,
			'recordsFiltered'=>$o->total,
			'recordsTotal'=>$o->total,
			'data'=>$records
		);
		echo json_encode($output);
		die();
    }
    public function duksatker_download()
	{
	  	$this->load->library('Convert');
        $convert = new Convert;
		$UNOR_ID = $this->pegawai_model->getunor_induk($this->auth->username());
		$this->load->model("pegawai/unitkerja_model");
		$data_satker = $this->unitkerja_model->find_by("ID",$UNOR_ID);
		$NAMA_UNOR = $data_satker->NAMA_UNOR ? $data_satker->NAMA_UNOR : "";
		$nama_bulan = $convert->getmonth(date("m"));
		Template::set('nama_bulan', $nama_bulan);

        $draw= $this->input->post('draw');
		$length= $this->input->post('length');
		$start= $this->input->post('start');
		$unit_id = $this->input->post('unit_id') != "" ? $this->input->post('unit_id') : $UNOR_ID;
		$o = $this->pegawai_model->get_duk_list_satker($unit_id,$start,$length);

		$nomor_urut=$start+1;
		$records = array();
		
		
		$this->load->library('Excel');
		$objPHPExcel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load(trim($this->settings_lib->item('site.pathuploaded')).'template_duk.xls');
		$objPHPExcel->setActiveSheetIndex(0);
		$col = 0;
		$row = 11;
		$no = 1;
		$type = PHPExcel_Cell_DataType::TYPE_STRING;
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,2,$NAMA_UNOR,$type);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,4,"Keadaan Bulan ".$nama_bulan." ".date("Y"),$type);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,7,$NAMA_UNOR,$type);
		foreach ($o->data as $record) {
			$usia_tahun = (int)($record->bulan_usia/12);
			$usia_bulan = (int)$record->bulan_usia%12;

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$row,$no,$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$row,$record->NAMA_UNOR,$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,$record->NAMA,$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,$record->NIP_BARU." ",$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row,$record->KARTU_PEGAWAI,$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row,$record->TMT_CPNS,$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$row,$record->TGL_LAHIR,$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$row,$usia_tahun." Thn ".$usia_bulan." Bln",$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row,$record->JENIS_KELAMIN,$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,$row,$record->AGAMA_ID,$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,$row,$record->golongan_text,$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11,$row,$record->TMT_GOLONGAN,$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12,$row,"",$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13,$row,$record->JABATAN_NAMA,$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14,$row,$record->JABATAN_NAMA,$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15,$row,$record->TMT_JABATAN,$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16,$row,"",$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17,$row,"",$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18,$row,"",$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19,$row,$record->PENDIDIKAN,$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20,$row,$record->PENDIDIKAN,$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21,$row,$record->TAHUN_LULUS,$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22,$row,"",$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(23,$row,$record->STATUS_CPNS_PNS,$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(24,$row,$record->BUP,$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(25,$row,$record->KEDUDUKAN_HUKUM_NAMA,$type);

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(26,$row,$record->NIK." ",$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(27,$row,$record->NOMOR_HP." ",$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(28,$row,$record->NOMOR_DARURAT." ",$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(29,$row,$record->EMAIL,$type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20,$row,$record->EMAIL_DIKBUD,$type);
			$row++;
			$no++;
		}
	 
		 
		  
		$filename = "duk".mt_rand(1,100000).'.xls'; //just some random filename
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
		//$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
		$objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
		exit; //done.. exiting!
		
	}
	public function cetak($unit_id=null){
		 
		$this->load->model("pegawai/unitkerja_model");
		if($unit_id == null || $unit_id == 'null'){
			$unit_id = $this->UNOR_ID;
			$unor = $this->unitkerja_model->where("ID",$unit_id)->find_first_row();
			$unit_id = $unor->ID ?  $unor->ID : $this->UNOR_ID;
		}
		else {
			$unor = $this->unitkerja_model->where("ID",$unit_id)->find_first_row();
		}
		$satuan_kerja = "";
		if($unor){
			$satuan_kerja = $unor->NAMA_UNOR;
		}
		else {
			echo "unor tidak diketahui";
			die();
		}
		$this->load->library("tcpdf_lib");
		$pdf=  new DUK_Template($satuan_kerja,PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetMargins(5, 15, 5);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		$pdf->AddPage();
		$o = $this->pegawai_model->get_duk_list($unit_id,0,null);
		$index=1;
		
		//echo json_encode($o->data);
		$pdf->SetFont('times', 'R', 7);
		$html = <<<EOD
		<style type="text/css">
			table td {
				border : 0.1px solid black;	
			}
			table thead th {
				border : 0.1px solid black;	
			}
			table { page-break-inside:auto }
   			tr,td    { page-break-inside:avoid; page-break-after:auto }
           
		</style>
		<table  cellspacing=0 cellpadding="2">
			<thead>
				<tr class='header'>
					<td width="20">NO</td>
					<td width="100">PEGAWAI</td>
					<td width="40">TMT CPNS</td>
					<td width="100">JABATAN</td>
					<td width="40">GOL.</td>
					<td width="40">MASA KERJA</td>
					<td width="100">PENDIDIKAN</td>
					<td width="120">UNIT KERJA</td>
				</tr>
			</thead>
		<tbody>
EOD;
		$index = 1;
		foreach($o->data as $record){
			$html .= "<tr>";
				$html .= "<td style=\"text-align:center\" width=\"20\">"; 
						$html .= $index;
				$html .= "</td>";
				$html .= "<td width=\"100\">"; 
						$html .= $record->NIP_BARU; 
						$html .= "<br>".$record->NAMA;
				$html .= "</td>";
				$html .= "<td width=\"40\">"; 
						$html .= $record->TMT_CPNS; 
				$html .= "</td>";
				$html .= "<td width=\"100\">"; 
						$html .= $record->JABATAN_NAMA; 
						$html .= "<br>".$record->TMT_JABATAN; 
				$html .= "</td>";
				$html .= "<td width=\"40\">"; 
						$html .= $record->GOLONGAN; 
						$html .= "<br>".$record->TMT_GOLONGAN;
				$html .= "</td>";
				$html .= "<td width=\"40\">"; 
						$html .= $record->MK_TAHUN." T ".$record->MK_BULAN." B"; 
				$html .= "</td>";
				$html .= "<td  width=\"100\">"; 
						$html .= $record->PENDIDIKAN; 
				$html .= "</td>";
				$html .= "<td  width=\"120\">"; 
						$html .= $record->NAMA_UNOR; 
				$html .= "</td>";
			$html .= "</tr>";
			$index++;
		}
		$html .= "</tbody>";
		$html .= "</table>";

		//$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
		$pdf->writeHTML($html, true, false, false, false, '');

		$pdf->Output('daftar_duk.pdf', 'I');
	}
	public function ajax_unit_list(){
		$where_clause = "";
		if($this->UNOR_ID){
			$where_clause = 'AND (uk."ESELON_1" = \''.$this->UNOR_ID.'\' OR uk."ESELON_2" = \''.$this->UNOR_ID.'\' OR uk."ESELON_3" = \''.$this->UNOR_ID.'\' OR uk."ESELON_4" = \''.$this->UNOR_ID.'\')' ;
		}
		$length = 10;
		$term = $this->input->get('term');
		$page = $this->input->get('page');
		$this->db->flush_cache();
		$data = $this->db->query("
			select 
			uk.*,
			parent.\"NAMA_UNOR\" as parent_name,
			grand.\"NAMA_UNOR\" as grand_name
			from 
			hris.unitkerja uk
			left join hris.unitkerja parent on parent.\"ID\" = uk.\"DIATASAN_ID\"
			left join hris.unitkerja grand on grand.\"ID\" = parent.\"DIATASAN_ID\"
			where 1=1 ".$where_clause." and lower(uk.\"NAMA_UNOR\") like ?
			",array("%".strtolower($term)."%"))->result();
		
		$output = array();
		$output['results'] = array();
		foreach($data as $row){
			$nama_unor = array();
			if($row->grand_name){
				$nama_unor[] = $row->grand_name;
			}
			if($row->parent_name){
				$nama_unor[] = $row->parent_name;
			}
			if($row->NAMA_UNOR){
				$nama_unor[] = $row->NAMA_UNOR;
			}
			$output['results'] [] = array(
				'id'=>$row->ID,
				'text'=>implode(" - ",$nama_unor)
			);
		}
		$output['pagination'] = array("more"=>false);
		
		echo json_encode($output);
	}
}
require_once(APPPATH.'libraries/tcpdf/tcpdf.php');
class DUK_Template extends TCPDF {
	public $unit_kerja = "AA";
	public function __construct($unit_kerja,$_PDF_PAGE_ORIENTATION, $_PDF_UNIT, $_PDF_PAGE_FORMAT, $_true, $_UTF, $_false){
		parent::__construct($_PDF_PAGE_ORIENTATION, $_PDF_UNIT, $_PDF_PAGE_FORMAT, $_true, $_UTF, $_false);
		$this->unit_kerja = strtoupper($unit_kerja);
	}
	public function Header() {
		$this->SetFont('times', 'BR', 8);
		$this->Cell(0, 2, 'DAFTAR URUTAN KEPANGKATAN', 0, false, 'C', 0, '', 0, false, 'M', 'M');
		$this->Ln();
		$this->Cell(0, 2, $this->unit_kerja, 0, true, 'C', 0, '', 0, false, 'M', 'M');
	}
}