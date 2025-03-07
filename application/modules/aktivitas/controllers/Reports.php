<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Reports controller
 */
class Reports extends Admin_Controller
{
    protected $permissionView           = 'Aktivitas.Reports.View';
    protected $permissionDownload       = 'Aktivitas.Reports.Download';
    protected $permissionFullAccess     = 'Aktivitas.Reports.FullAccess';
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('aktivitas/activity_model');
        $modules = $this->activity_model->get_modul_all();
        Template::set("modules", $modules);
    }

    /**
     * Display a list of petajabatan data.
     *
     * @return void
     */
    public function index()
    {
        $this->auth->restrict($this->permissionView);
        Template::set('toolbar_title', "Aktivitas");
        Template::set("collapse", true);
        Template::set_view("aktivitas/v_reports");
        Template::render();
    }

    public function ajax_list()
    {
        $this->auth->restrict($this->permissionView);
        $this->load->helper('dikbud');
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');

        $length = $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start = $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $this->db->start_cache();
        $advanced_search_filters  = $this->input->post("advanced_search_filters");
        if ($advanced_search_filters) {
            $filters = array();
            foreach ($advanced_search_filters as  $filter) {
                $filters[$filter['name']] = $filter["value"];
            }

            if ($filters['user_id']) {
                $this->activity_model->where('NIP_BARU', $filters['user_id']);
            }
            if ($filters['key']) {
                $this->activity_model->where('activity ilike \'%' .$filters['key'].'%\'');
            }
            if ($filters['module']) {
                $this->activity_model->where('module', $filters['module']);
            }
            if ($filters['tgl_awal']) {
                $this->activity_model->where('activities.created_on > \'' . $filters['tgl_awal'] . '\'::date');
            }
            if ($filters['tgl_akhir']) {
                $this->activity_model->where('activities.created_on < \'' . $filters['tgl_akhir'] . '\'::date');
            }
            if ($filters['tgl_awal'] && $filters['tgl_akhir']) {
                $this->activity_model->where('activities.created_on between \'' . $filters['tgl_awal'] . '\' and \'' . $filters['tgl_akhir'] . '\'');
            }
            if ($filters['exclude_login'] == 1) {
                $this->activity_model->where('activities.activity not ilike \'%Login from%\'');
            }
        }

        if (!$this->auth->has_permission($this->permissionFullAccess)) {
            $this->activity_model->where('u.id', $this->current_user->id);
        }

        $this->db->stop_cache();

        $total = $this->activity_model->count_all();
        $output['recordsTotal'] = $output['recordsFiltered'] = $total;

        $output['data'] = array();

        $this->activity_model->limit($length, $start);
        $records = $this->activity_model->find_all();

        $this->db->flush_cache();
        $nomor_urut = $start + 1;

        if (isset($records) && is_array($records) && count($records)) :
            foreach ($records as $record) {
                $row = array();
                $row[]  = $nomor_urut . ".";
                $row[]  = $record->NAMA . '<br>' . $record->NIP_BARU;
                $row[]  = $record->activity;
                // $row[] = "<div class='text-wrap width-200'>".$record->activity."</div>";
                $row[]  = $record->module;
                $row[]  = $record->created_on;

                // $btn_actions = array();
                // $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/profilen/".$record->ref."' class='btn btn-sm btn-info'><i class='glyphicon glyphicon-user'></i> </a>"; 
                // $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";

                // $row[] = implode(" ",$btn_actions);


                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        die();
    }

    public function Download()
    {
        $this->auth->restrict($this->permissionDownload);
        $this->load->helper('aplikasi');
        $advanced_search_filters  = $_GET;
        if ($advanced_search_filters) {
            $filters = $advanced_search_filters;
            if ($filters['user_id']) {
                $this->activity_model->where('NIP_BARU', $filters['user_id']);
            }
            if ($filters['module']) {
                $this->activity_model->where('module', $filters['module']);
            }
            if ($filters['key']) {
                $this->activity_model->where('activity ilike \'%' .$filters['key'].'%\'');
            }
            if ($filters['tgl_awal']) {
                $this->activity_model->where('activities.created_on > \'' . $filters['tgl_awal'] . '\'::date');
            }
            if ($filters['tgl_akhir']) {
                $this->activity_model->where('activities.created_on < \'' . $filters['tgl_akhir'] . '\'::date');
            }
            if ($filters['tgl_awal'] && $filters['tgl_akhir']) {
                $this->activity_model->where('activities.created_on between \'' . $filters['tgl_awal'] . '\' and \'' . $filters['tgl_akhir' . '\'']);
            }
        }

        if (!$this->auth->has_permission($this->permissionFullAccess)) {
            $this->activity_model->where('u.id', $this->current_user->id);
        }
        
        $records = $this->activity_model->find_all();
        $this->load->library('Excel');
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0);

        $type = PHPExcel_Cell_DataType::TYPE_STRING;

        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, 1)->setValueExplicit("Log Aktivitas" , $type);
        $row = 4;

        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $row)->setValueExplicit("No", $type);
        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $row)->setValueExplicit("Nama", $type);
        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $row)->setValueExplicit("NIP", $type);
        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $row)->setValueExplicit("Aktivitas", $type);
        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4, $row)->setValueExplicit("Modul", $type);
        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, $row)->setValueExplicit("Tanggal", $type);
        $no = 1;
        // $keys = array_keys($records);
        if (isset($records) && is_array($records) && count($records)) :
            foreach ($records as $record) {
                $row++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $no);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $record->NAMA);
                $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $row)->setValueExplicit($record->NIP_BARU);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $record->activity);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $record->module);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, getIndonesiaFormat($record->created_on));
                $no++;
            }
        endif;

        $filename = "Rekap Aktivitas ". date('Ymdhi') . '.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
        //$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
        ob_end_clean();
        $objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
        exit; //done.. exiting!
    }
}
