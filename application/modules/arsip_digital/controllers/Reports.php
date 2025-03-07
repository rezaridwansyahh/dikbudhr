<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Arsip controller
 */
class Reports extends Admin_Controller
{
    protected $permissionDashboardAdmin = 'Arsip_digital.Reports.DashboardAdmin';
     
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        
        $this->load->model('arsip_digital/arsip_digital_model');
        $this->load->model('jenis_arsip/jenis_arsip_model');
        $this->load->model('jenis_arsip/kategori_arsip_model');
        $this->load->model('pegawai/pegawai_model');
        
        $this->lang->load('arsip_digital');
        
            $this->form_validation->set_error_delimiters("<span class='error danger'>", "</span>");
        
        Template::set_block('sub_nav', 'arsip/_sub_nav');

        Assets::add_module_js('arsip_digital', 'arsip_digital.js');

        $reckategori = $this->jenis_arsip_model->find_all();
        Template::set("reckategori",$reckategori);
    }

    /**
     * Display a list of arsip digital data.
     *
     * @return void
     */
    public function index()
    {
        $this->auth->restrict($this->permissionDashboardAdmin);   
        $this->load->model('pegawai/unitkerja_model');

        $reckategori = $this->kategori_arsip_model->find_all();
        Template::set("reckategori",$reckategori);

        Template::set('toolbar_title', "Laporan Arsip Digital Pegawai");
        $data_unitkerja = $this->unitkerja_model->find_unitkerja();
        $data_arsip = $this->arsip_digital_model->find_group_es1();
        $data_arsip_es1 = $this->arsip_digital_model->find_group_es1_kategori_arsip();
        
        $adata_arsip = array();
        if(isset($data_arsip) && is_array($data_arsip) && count($data_arsip)):
            foreach ($data_arsip as $record) {
                $adata_arsip[$record->ESELON_1] = $record->JUMLAH_ARSIP;
            }
        endif;
        $adata_arsip_es1 = array();
        if(isset($data_arsip_es1) && is_array($data_arsip_es1) && count($data_arsip_es1)):
            foreach ($data_arsip_es1 as $record) {
                $adata_arsip_es1[$record->ESELON_1."_".$record->KATEGORI_ARSIP] = $record->JUMLAH_ARSIP;
            }
        endif;
        Template::set('data_unitkerja', $data_unitkerja);
        Template::set('adata_arsip', $adata_arsip);
        Template::set('adata_arsip_es1', $adata_arsip_es1);
        Template::render();
    }
    public function detiles2($ID)
    {
        $this->auth->restrict($this->permissionDashboardAdmin);   
        $this->load->model('pegawai/unitkerja_model');

        $reckategori = $this->kategori_arsip_model->find_all();
        Template::set("reckategori",$reckategori);

        Template::set('toolbar_title', "Laporan Arsip Digital Pegawai");
        $data_unitkerja = $this->unitkerja_model->find_unitkerja($ID);
        $data_arsip = $this->arsip_digital_model->find_group_es2($ID);
        $data_arsip_es1 = $this->arsip_digital_model->find_group_es2_kategori_arsip($ID);
        
        $adata_arsip = array();
        if(isset($data_arsip) && is_array($data_arsip) && count($data_arsip)):
            foreach ($data_arsip as $record) {
                $adata_arsip[$record->ESELON_2] = $record->JUMLAH_ARSIP;
            }
        endif;
        $adata_arsip_es1 = array();
        if(isset($data_arsip_es1) && is_array($data_arsip_es1) && count($data_arsip_es1)):
            foreach ($data_arsip_es1 as $record) {
                $adata_arsip_es1[$record->ESELON_1."_".$record->KATEGORI_ARSIP] = $record->JUMLAH_ARSIP;
            }
        endif;
        Template::set('data_unitkerja', $data_unitkerja);
        Template::set('adata_arsip', $adata_arsip);
        Template::set('adata_arsip_es1', $adata_arsip_es1);
        Template::render();
    }
     public function download()
    {
      
        $this->load->model('pegawai/unitkerja_model');

        $reckategori = $this->kategori_arsip_model->find_all();
        Template::set("reckategori",$reckategori);

        Template::set('toolbar_title', "Laporan Arsip Digital Pegawai");
        $records = $this->unitkerja_model->find_unitkerja();
        $data_arsip = $this->arsip_digital_model->find_group_es1();
        $data_arsip_es1 = $this->arsip_digital_model->find_group_es1_kategori_arsip();
        
        $adata_arsip = array();
        if(isset($data_arsip) && is_array($data_arsip) && count($data_arsip)):
            foreach ($data_arsip as $record) {
                $adata_arsip[$record->ESELON_1] = $record->JUMLAH_ARSIP;
            }
        endif;
        $adata_arsip_es1 = array();
        if(isset($data_arsip_es1) && is_array($data_arsip_es1) && count($data_arsip_es1)):
            foreach ($data_arsip_es1 as $record) {
                $adata_arsip_es1[$record->ESELON_1."_".$record->KATEGORI_ARSIP] = $record->JUMLAH_ARSIP;
            }
        endif;
        Template::set('adata_arsip', $adata_arsip);
        Template::set('adata_arsip_es1', $adata_arsip_es1);

        
        $this->load->library('Excel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel = PHPExcel_IOFactory::load(trim($this->settings_lib->item('site.pathuploaded')).'template.xls');

        $objPHPExcel->setActiveSheetIndex(0);
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,"No");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,1,"NAMA");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,1,"JUMLAH"); 

        $col = 3;
        $ajml_kategori = array();
        if(isset($reckategori) && is_array($reckategori) && count($reckategori)):
            foreach ($reckategori as $recordkat) {
                $ajml_kategori[$recordkat->ID] = 0;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,1,$recordkat->KATEGORI); 
                 $col++;
            }
        endif;

        
        
        $row = 2;
        $no = 1;
        
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $type = PHPExcel_Cell_DataType::TYPE_STRING;
                $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $row)->setValueExplicit($no, $type);
                $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $row)->setValueExplicit($record->NAMA_UNOR, $type);
                $jumlah = 0;
                if(isset($adata_arsip[$record->ID])){
                    $jumlah = (int)$adata_arsip[$record->ID];
                }
                $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $row)->setValueExplicit($jumlah, $type);
                $col = 3;
                if(isset($reckategori) && is_array($reckategori) && count($reckategori)):
                    foreach ($reckategori as $recordkat) {
                        if(isset($adata_arsip_es1[$record->ID."_".$recordkat->ID])){
                            $ajml_kategori[$recordkat->ID] = $ajml_kategori[$recordkat->ID] + (int)$adata_arsip_es1[$record->ID."_".$recordkat->ID];    
                            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->setValueExplicit((int)$adata_arsip_es1[$record->ID."_".$recordkat->ID], $type);
                        }else{
                            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->setValueExplicit(0, $type);
                        }
                    $col++;        
                    }
                endif;
                $row++;
                $no++;
            }
        endif;
          
        $filename = "rekap_arsip_".mt_rand(1,100000).'.xls'; //just some random filename
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
        //$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
        $objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
        exit; //done.. exiting!
        
    }
}