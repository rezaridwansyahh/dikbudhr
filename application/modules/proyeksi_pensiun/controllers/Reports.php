<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Reports controller
 */
class Reports extends Admin_Controller
{
    public $UNOR_ID = null;
    protected $permissionCreate = 'Proyeksi_pensiun.Reports.Create';
    protected $permissionDelete = 'Proyeksi_pensiun.Reports.Delete';
    protected $permissionEdit   = 'Proyeksi_pensiun.Reports.Edit';
    protected $permissionView   = 'Proyeksi_pensiun.Reports.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->lang->load('proyeksi_pensiun');
        $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        Template::set_block('sub_nav', 'reports/_sub_nav');
        Assets::add_module_js('proyeksi_pensiun', 'proyeksi_pensiun.js');
        $this->load->model('pegawai/unitkerja_model');
        $this->load->model('ref_jabatan/jabatan_model');
        $this->load->model('pegawai/pegawai_model', null, true);

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

    /**
     * Display a list of proyeksi pensiun data.
     *
     * @return void
     */
    public function index()
    {
        $this->auth->restrict($this->permissionView);
        //$pensiuntahun = $this->pegawai_model->stats_pensiun_pertahun($this->UNOR_ID);

        Template::set('toolbar_title', "Proyeksi Pensiun Perperiode");

        Template::render();
    }
    public function listpensiun($TAHUN = "")
    {   
        $this->auth->restrict($this->permissionView);
        Template::set('tahun', $TAHUN);
        Template::set('toolbar_title', "Estimasi Pegawai Pensiun Tahun ".$TAHUN);
        
        Template::render();
    }
    public function listpensiunjjabatan($TAHUN = "",$jenisjabatan = "")
    {   

        $this->auth->restrict($this->permissionView);
        Template::set('tahun', $TAHUN);
        Template::set('jenisjabatan', $jenisjabatan);

        $jjabatan = "";
        if($jenisjabatan == "1"){
            $jjabatan = "Struktural";
        }
        if($jenisjabatan == "2"){
            $jjabatan = "Jabatan Fungsional Tertentu";
        }
        if($jenisjabatan == "4"){
            $jjabatan = "Jabatan Fungsional Umum";
        }
        Template::set('jjabatan', $jjabatan);
        Template::set('toolbar_title', "Estimasi Pegawai Pensiun Tahun ".$TAHUN.", Jabatan ".$jjabatan);
        
        Template::render();
    }
    public function listpensiunjabatan($TAHUN = "",$jabatan = "")
    {   

        $this->auth->restrict($this->permissionView);
        $datajabatan = $this->jabatan_model->find_by("KODE_JABATAN",$jabatan);
        Template::set('tahun', $TAHUN);
        Template::set('nama_jabatan', $datajabatan->NAMA_JABATAN);
        Template::set('jabatan', $jabatan);
        Template::set('toolbar_title', "Estimasi Pegawai Pensiun Tahun ".$TAHUN);
        
        Template::render();
    }
    public function getdatapensiun($tahun = ''){
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length');
        $start= $this->input->post('start');

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
         
        
        $output=array();
        

        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        if($search!=""){
            $this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
            $this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
        }
        
        $this->pegawai_model->limit($length,$start);
        /*Urutkan dari alphabet paling terkahir*/
        $kolom = $iSortCol != "" ? $iSortCol : "NAMA";
        $sSortCol == "asc" ? "asc" : "desc";
        $this->pegawai_model->order_by($iSortCol,$sSortCol);
        $records=$this->pegawai_model->find_all_pensiun_tahun($tahun,$this->UNOR_ID);

        /*Ketika dalam mode pencarian, berarti kita harus
        'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
        yang mengandung keyword tertentu
        */
        if($search != "")
        {
            $this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
            $this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
            //$this->pegawai_model->or_where('NIP_BARU',$search);
            $jum    = $this->pegawai_model->count_all_pensiun_tahun($tahun,$this->UNOR_ID);
            $output['recordsTotal']=$output['recordsFiltered']=$jum;
        }else{
            $total= $this->pegawai_model->count_all_pensiun_tahun($tahun,$this->UNOR_ID);
            $output['draw']=$draw;
            $output['recordsTotal']= $output['recordsFiltered']=$total;
            $output['data']=array();

        }
        
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->NIP_BARU;
                $row []  = $record->NAMA;
                $row []  = $record->NAMA_JABATAN." (".$record->PENSIUN.")";
                $row []  = $record->TGL_LAHIR;
                $row []  = $record->umur;
                $row []  = $record->tahunpensiun;
                $row []  = $record->NAMA_UNOR;
                
                $btn_actions = array();
                $btn_actions  [] = "
                    <a class='show-modal-custom' href='".base_url()."admin/kepegawaian/pegawai/profilen/".$record->ID."'  data-toggle='modal' title='Ubah Data'><span class='fa-stack'>
                       <i class='fa fa-square fa-stack-2x'></i>
                        <i class='fa fa-eye fa-stack-1x fa-inverse'></i>
                        </span>
                        </a>
                ";
                $row[] = implode(" ",$btn_actions);
                

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        die();

    }
    public function getdatapensiunjjabatan($tahun = '',$jenisjabatan = ''){
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length');
        $start= $this->input->post('start');

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
         
        
        $output=array();
        

        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        if($search!=""){
            $this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
            $this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
        }
        
        $this->pegawai_model->limit($length,$start);
        /*Urutkan dari alphabet paling terkahir*/
        $kolom = $iSortCol != "" ? $iSortCol : "NAMA";
        $sSortCol == "asc" ? "asc" : "desc";
        $this->pegawai_model->order_by($iSortCol,$sSortCol);
        $records=$this->pegawai_model->find_all_pensiun_tahun_jjabatan($tahun,$jenisjabatan,$this->UNOR_ID);

        /*Ketika dalam mode pencarian, berarti kita harus
        'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
        yang mengandung keyword tertentu
        */
        if($search != "")
        {
            $this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
            $this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
            //$this->pegawai_model->or_where('NIP_BARU',$search);
            $jum    = $this->pegawai_model->count_all_pensiun_tahun_jjabatan($tahun,$jenisjabatan,$this->UNOR_ID);
            $output['recordsTotal']=$output['recordsFiltered']=$jum;
        }else{
            $total= $this->pegawai_model->count_all_pensiun_tahun_jjabatan($tahun,$jenisjabatan,$this->UNOR_ID);
            $output['draw']=$draw;
            $output['recordsTotal']= $output['recordsFiltered']=$total;
            $output['data']=array();

        }
        
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->NIP_BARU;
                $row []  = $record->NAMA;
                $row []  = $record->NAMA_JABATAN." (".$record->PENSIUN.")";
                $row []  = $record->TGL_LAHIR;
                $row []  = $record->umur;
                $row []  = $record->tahunpensiun;
                $row []  = $record->NAMA_UNOR;
                
                $btn_actions = array();
                $btn_actions  [] = "
                    <a class='show-modal-custom' href='".base_url()."admin/kepegawaian/pegawai/profilen/".$record->ID."'  data-toggle='modal' title='Ubah Data'><span class='fa-stack'>
                       <i class='fa fa-square fa-stack-2x'></i>
                        <i class='fa fa-eye fa-stack-1x fa-inverse'></i>
                        </span>
                        </a>
                ";
                $row[] = implode(" ",$btn_actions);
                

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        die();

    }
    public function getdatapensiunjabatan($tahun = '',$jabatan = ''){
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length');
        $start= $this->input->post('start');

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
         
        
        $output=array();
        

        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        if($search!=""){
            $this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
            $this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
        }
        
        $this->pegawai_model->limit($length,$start);
        /*Urutkan dari alphabet paling terkahir*/
        $kolom = $iSortCol != "" ? $iSortCol : "NAMA";
        $sSortCol == "asc" ? "asc" : "desc";
        $this->pegawai_model->order_by($iSortCol,$sSortCol);
        $records=$this->pegawai_model->find_all_pensiun_tahun_jabatan($tahun,$jabatan,$this->UNOR_ID);

        /*Ketika dalam mode pencarian, berarti kita harus
        'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
        yang mengandung keyword tertentu
        */
        if($search != "")
        {
            $this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
            $this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
            //$this->pegawai_model->or_where('NIP_BARU',$search);
            $jum    = $this->pegawai_model->count_all_pensiun_tahun_jabatan($tahun,$jabatan,$this->UNOR_ID);
            $output['recordsTotal']=$output['recordsFiltered']=$jum;
        }else{
            $total= $this->pegawai_model->count_all_pensiun_tahun_jabatan($tahun,$jabatan,$this->UNOR_ID);
            $output['draw']=$draw;
            $output['recordsTotal']= $output['recordsFiltered']=$total;
            $output['data']=array();

        }
        
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->NIP_BARU;
                $row []  = $record->NAMA;
                $row []  = $record->NAMA_JABATAN." (".$record->PENSIUN.")";
                $row []  = $record->TGL_LAHIR;
                $row []  = $record->umur;
                $row []  = $record->tahunpensiun;
                $row []  = $record->NAMA_UNOR;
                
                 
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        die();

    }
    public function ajax_datapertahun(){
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length');
        $start= $this->input->post('start');

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        
        if($search!=""){
            $this->pegawai_model->where('upper("NAMA") LIKE \'%'.strtoupper($search).'%\'');
            $this->pegawai_model->or_where('upper("NAMA_PANGKAT") LIKE \'%'.strtoupper($search).'%\'');
        }
        $this->pegawai_model->limit($length,$start);

        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->pegawai_model->order_by("perkiraan_tahun_pensiun",$order['dir']);
            }
            if($order['column']==2){
                $this->pegawai_model->order_by("total",$order['dir']);
            }
            
        }

        //$this->golongan_model->where("deleted",null);
        $records=$this->pegawai_model->stats_pensiun_pertahun($this->UNOR_ID,9);
        

        $total= count($records);
        $output=array();
        $output['draw']=$draw;

        
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();

        /*Ketika dalam mode pencarian, berarti kita harus
        'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
        yang mengandung keyword tertentu
        */
        if($search != "")
        {
            $this->pegawai_model->where('upper("NAMA") LIKE \'%'.strtoupper($search).'%\'');
            $this->pegawai_model->or_where('upper("NAMA_PANGKAT") LIKE \'%'.strtoupper($search).'%\'');
            $jum    = $this->pegawai_model->count_all();
            $output['recordsTotal']= count($records);
        }
        
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record['tahun'];
                $row []  = "<a href='".base_url()."admin/reports/proyeksi_pensiun/listpensiun/".$record['tahun']."'>".$record['jumlah']."</a>";
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        die();
    }
    public function jenisjabatan()
    {
        $this->auth->restrict($this->permissionView);
        $row = array();
        $pensiunjenisjabatan = $this->pegawai_model->find_pensiunbyjenisjabatan($this->UNOR_ID);
        if(isset($pensiunjenisjabatan) && is_array($pensiunjenisjabatan) && count($pensiunjenisjabatan)):
            foreach ($pensiunjenisjabatan as $record) {
                $row[$record->perkiraan_tahun_pensiun."_".$record->ID_JENIS_JABATAN]  = $record->total;
            }
        endif;

        Template::set('row', $row);
        Template::set('records', $pensiunjenisjabatan);
        Template::set('toolbar_title', "Proyeksi Pensiun Perjenis jabatan");

        Template::render();
    }
    public function jabatan()
    {
        $this->auth->restrict($this->permissionView);
         
        Template::set('toolbar_title', "Proyeksi Pensiun Perjabatan");

        Template::render();
    }
    public function ajax_dataperjabatan(){
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length');
        $start= $this->input->post('start');

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        if($search!=""){
            $this->pegawai_model->where('upper(jabatan."NAMA_JABATAN") LIKE \'%'.strtoupper($search).'%\'');
        }
        $records=$this->pegawai_model->find_pensiunbyjabatan($this->UNOR_ID);
        $total= count($records);

        if($search!=""){
            $this->pegawai_model->where('upper(jabatan."NAMA_JABATAN") LIKE \'%'.strtoupper($search).'%\'');
        }
        $this->pegawai_model->limit($length,$start);
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->pegawai_model->order_by("perkiraan_tahun_pensiun",$order['dir']);
            }
            if($order['column']==2){
                $this->pegawai_model->order_by("total",$order['dir']);
            }
            
        }
        //$this->golongan_model->where("deleted",null);
        $records=$this->pegawai_model->find_pensiunbyjabatan($this->UNOR_ID,10);
        

        
        $output=array();
        $output['draw']=$draw;
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        /*Ketika dalam mode pencarian, berarti kita harus
        'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
        yang mengandung keyword tertentu
        */
         
        
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                
                $row []  = $record->perkiraan_tahun_pensiun;
                $row []  = $record->NAMA_JABATAN;
                $row []  = "<a href='".base_url()."admin/reports/proyeksi_pensiun/listpensiunjabatan/".$record->perkiraan_tahun_pensiun."/".$record->KODE_JABATAN."'>".$record->total."</a>";
                $row []  = $record->total;
                 

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        die();
    }
}