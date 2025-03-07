<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Sk controller
 */
class Arsip extends Admin_Controller
{
    protected $permissionCreate = 'Sk_ds.Sk.Create';
    protected $permissionDelete = 'Sk_ds.Sk.Delete';
    protected $permissionEdit   = 'Sk_ds.Sk.Edit';
    protected $permissionView   = 'Sk_ds.Sk.View';
    protected $permissionValidasi   = 'Sk_ds.Sk.Validasi';
    protected $permissionttd   = 'Sk_ds.Sk.Tandatangan';

    protected $permissionBarier   = 'Sk_ds.Sk.ReqBarier';
    protected $permissionToken   = 'Sk_ds.Sk.ReqToken';
    protected $permissionViewall   = 'Sk_ds.Sk.Viewall';
    protected $permissionViewlog   = 'Sk_ds.Sk.Viewlog';
    protected $permissionViewSatker   = 'Sk_ds.Arsip.ViewSatker';
    protected $permissionViewPegawai   = 'Sk_ds.Arsip.Pegawai';
    protected $permissionViewEs   = 'Sk_ds.Arsip.ViewEs1';
    protected $permissionDownloadall   = 'Sk_ds.Downloadttd.View';
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('sk_ds/sk_ds_model');
        $this->load->model('sk_ds/sk_ds_korektor_model');
        $this->load->model('sk_ds/kategori_ds_model');
        $this->load->model('sk_ds/log_ds_model');
        $this->load->model('pegawai/pegawai_model');
        $this->lang->load('sk_ds');
        
            Assets::add_css('flick/jquery-ui-1.8.13.custom.css');
            Assets::add_js('jquery-ui-1.8.13.min.js');
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'sk/_sub_nav');

        Assets::add_module_js('sk_ds', 'sk_ds.js');
    }

    /**
     * Display a list of sk ds data.
     *
     * @return void
     */
    public function index()
    {
        $this->auth->restrict($this->permissionView); 

        Template::render();
    }
     
    public function viewallsatker()
    {
        $this->auth->restrict($this->permissionViewSatker); 
        Template::set('toolbar_title', "Daftar Semua SK Elektronik Unit Kerja");  
        $reckategori_ds = $this->kategori_ds_model->find_all();
        Template::set("reckategori_ds",$reckategori_ds);
        Template::render();
    }
    public function viewalles()
    {
        $this->auth->restrict($this->permissionViewEs); 
        Template::set('toolbar_title', "Daftar Semua SK Elektronik Eselon 1");  
        $reckategori_ds = $this->kategori_ds_model->find_all();
        Template::set("reckategori_ds",$reckategori_ds);
        Template::render();
    }
    public function viewallpegawai()
    {
        $this->auth->restrict($this->permissionViewPegawai); 
        Template::set('toolbar_title', "Daftar Semua SK Elektronik Anda");  
        $reckategori_ds = $this->kategori_ds_model->find_all();
        Template::set("reckategori_ds",$reckategori_ds);
        Template::render();
    }
      
    public function readsk($id)
    {
        $id = $this->uri->segment(5);
        $data = $this->sk_ds_model->find($id);

        $file_name = $id.".pdf";
        $b64 = $data->teks_base64;
        $id_penandatangan = trim($data->id_pegawai_ttd);

        $pejabat = $this->pegawai_model->find_by("PNS_ID",trim($id_penandatangan));
        Template::set("pejabat",$pejabat);
        $bin = base64_decode($b64, true);
        if (strpos($bin, '%PDF') !== 0) {
          throw new Exception('Missing the PDF file signature');
        }
        $direktori = trim($this->settings_lib->item('site.pathsk'));
        //die(trim($direktori).$file_name);
        $url_sk = trim($this->settings_lib->item('site.urlsk'));
        
        if (file_exists($direktori.$file_name)) {
           unlink($direktori.$file_name);
        }
        file_put_contents(trim($direktori).$file_name, $bin);    
        
        # Write the PDF contents to a local file

        $pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($data->nip_sk));
        
        Template::set("pegawai",$pegawai);

        $NIP = trim($this->auth->username());
        $pegawai_login = $this->pegawai_model->find_by("NIP_BARU",trim($NIP));
        Template::set("pegawai_login",$pegawai_login);
        
        $foto_pegawai = trim($this->settings_lib->item('site.urlphoto'))."noimage.jpg";
        if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO)){
            $foto_pegawai =  trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO;
        }
        Template::set('foto_pegawai', $foto_pegawai);


        Template::set("collapse",true);
        Template::set("data",$data);
        Template::set("b64",$b64);
        Template::set("url_sk",$url_sk.$file_name);
        Template::set('toolbar_title', "Lihat SK");
        Template::render();
    }
    public function generatespesimen()
    {
        $this->load->model('sk_ds/file_ttd_model');
        $id = $this->uri->segment(5);
        $records = $this->file_ttd_model->find_all($id);
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $file_name = $record->nip.".png";
                $b64 = trim($record->base64ttd);
                $bin = base64_decode($b64, true);
                $direktorispesimen = trim($this->settings_lib->item('site.pathspesimen'));
                if (file_exists($direktorispesimen.$file_name)) {
                   unlink($direktorispesimen.$file_name);
                }
                echo $direktorispesimen.$file_name;
                if(file_put_contents(trim($direktorispesimen).$file_name, $bin)){
                    echo "berhasil<br>";
                }else{
                    echo "Gagal<br>";
                }

            }
        endif;
        die("generate selesai");
        Template::set('toolbar_title', "Lihat SK");
        Template::render();
    }
    public function lihatsk($id)
    {
        $id = $this->uri->segment(5);
        $data = $this->sk_ds_model->find($id);
        if(!isset($data->id) and $data->is_signed != "1"){
            die("SK tidak ditemukan");
        }else{

        }
        $file_name = $id.".pdf";
        $b64_sign = $data->teks_base64_sign;
        $id_penandatangan = trim($data->id_pegawai_ttd);

        $pejabat = $this->pegawai_model->find_by("PNS_ID",trim($id_penandatangan));
        Template::set("pejabat",$pejabat);
  
        $pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($data->nip_sk));
        Template::set("pegawai",$pegawai);

        $NIP = trim($this->auth->username());
        $pegawai_login = $this->pegawai_model->find_by("NIP_BARU",trim($NIP));
        Template::set("pegawai_login",$pegawai_login);

        $foto_pegawai = trim($this->settings_lib->item('site.urlphoto'))."noimage.jpg";
        if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO)){
            $foto_pegawai =  trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO;
        }
        Template::set('foto_pegawai', $foto_pegawai);

        
        Template::set("collapse",true);
        Template::set("data",$data);
        Template::set("b64",$b64_sign);
         
        Template::set("url_sk",$url_sk.$file_name);
        Template::set('toolbar_title', "Lihat SK");
        Template::render();
    }
    public function readsksign($id)
    {
        $id = $this->uri->segment(5);
        $data = $this->sk_ds_model->find($id);
        $file_name = $id."_signed.pdf";
        $b64 = $data->teks_base64_sign;
        $id_penandatangan = trim($data->id_pegawai_ttd);

        $pejabat = $this->pegawai_model->find_by("PNS_ID",trim($id_penandatangan));
        Template::set("pejabat",$pejabat);

        
        $direktori = trim($this->settings_lib->item('site.pathsk'));
        //die(trim($direktori).$file_name);
        $url_sk = trim($this->settings_lib->item('site.urlsk'));
        
          
        # Write the PDF contents to a local file

        $pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($data->nip_sk));
        Template::set("pegawai",$pegawai);

        $foto_pegawai = trim($this->settings_lib->item('site.urlphoto'))."noimage.jpg";
        if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO)){
            $foto_pegawai =  trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO;
        }
        Template::set('foto_pegawai', $foto_pegawai);


        Template::set("collapse",true);
        Template::set("data",$data);
        Template::set("b64",$b64);
        Template::set("url_sk",$url_sk.$file_name);
        Template::set('toolbar_title', "Lihat SK");
        Template::render();
    }
    
    public function downloadsksigned($id)
    {
        $id = $this->uri->segment(5);
        $data = $this->sk_ds_model->find($id);
        $teks_base64_sign = $data->teks_base64_sign ? $data->teks_base64_sign : "";
        $this->load->helper('download');
        $direktori = trim($this->settings_lib->item('site.pathsk'));
        $file_name = $id."_signed.pdf";

        if (file_exists($direktori.$file_name)) {
            force_download($direktori.$file_name, NULL);
        }else{
            if ($teks_base64_sign != "") {
                $decoded = base64_decode($teks_base64_sign);
                $file = $direktori.$file_name;
                file_put_contents($direktori.$file_name, $decoded);

                if (file_exists($file)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="'.basename($file).'"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));
                    readfile($file);
                    exit;
                }else{
                    die("dokumen tidak ditemukan");
                }

            }
        }
    }
    
     
    public function getdataall_satker(){
        $this->auth->restrict($this->permissionViewSatker);
        $UNOR_ID = $this->pegawai_model->getunor_induk($this->auth->username());

        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/sk/sk_ds/viewall');
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
            
            if($filters['nama_cb']){
                $this->sk_ds_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");    
            }
            if($filters['nip_cb']){
                $this->sk_ds_model->like('upper(pegawai."NIP_BARU")',strtoupper($filters['nip_key']),"BOTH"); 
            }
             
            if($filters['golongan_cb']){
                $this->sk_ds_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));   
            }
            
            if($filters['chkqr']){
                $this->sk_ds_model->where('tbl_file_ds.lokasi_file',$filters['textqrcode']); 
            }
            if($filters['ds_ok']){
                if($filters['ds_ok'] != ""){
                    if($filters['ds_ok'] == "1"){
                    $this->sk_ds_model->where('tbl_file_ds.ds_ok',$filters['ds_ok']);     
                    }
                    if($filters['ds_ok'] == "-"){
                        $this->sk_ds_model->where('tbl_file_ds.ds_ok != 1 or tbl_file_ds.ds_ok is null');     
                    }
                }
            }else{
                $this->sk_ds_model->where('tbl_file_ds.ds_ok',1);
            }
            if($filters['kategori_cb']){
                $this->sk_ds_model->where('tbl_file_ds.kategori',$filters['kategori_sk']); 
            }
            
        }
        $this->db->group_start();
            $this->db->where('vw."ID"',$UNOR_ID);    
            //$this->db->or_where('vw."ESELON_1"',$UNOR_ID);   
            $this->db->or_where('vw."ESELON_2"',$UNOR_ID);   
            $this->db->or_where('vw."ESELON_3"',$UNOR_ID);   
            $this->db->or_where('vw."ESELON_4"',$UNOR_ID);   
            $this->db->group_end();
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total= $this->sk_ds_model->count_all_satker_ttd($this->UNOR_ID);
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->sk_ds_model->order_by("pegawai.NAMA",$order['dir']);
            }
            if($order['column']==2){
                $this->sk_ds_model->order_by("kategori",$order['dir']);
            }
            if($order['column']==3){
                $this->sk_ds_model->order_by("nomor_sk",$order['dir']);
            }
            if($order['column']==4){
                $this->sk_ds_model->order_by("NAMA_UNOR",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->sk_ds_model->limit($length,$start);
        $records=$this->sk_ds_model->find_all_satker_ttd($this->UNOR_ID);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $tgl_tandatangan = "";
                if($record->tgl_tandatangan){
                    $tgl_tandatangan = "<b>Ttd: </b>".$record->tgl_tandatangan;
                }
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = "<b>".$record->NAMA."</b><br>".$record->NIP_BARU;
                $row []  = $record->kategori;
                $row []  = $record->nomor_sk."<br>".$record->tgl_sk.$tgl_tandatangan;
                $row []  = $record->NAMA_UNOR_FULL;
                if($record->ds_ok == "1"){
                    $row []  = "DS";    
                }else{
                    $row []  = "Manual";
                }
                
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/arsip/sk_ds/lihatsk/".$record->id_file."' data-toggle='tooltip' title='Lihat SK' class='btn btn-sm btn-info'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                $btn_actions  [] = "<a href='".base_url()."admin/arsip/sk_ds/downloadsksigned/".$record->id_file."' data-toggle='tooltip' title='Download SK' class='btn btn-sm btn-warning'><i class='glyphicon glyphicon-download'></i> </a>";
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    public function getdataall_es(){
        $this->auth->restrict($this->permissionViewEs);
        // $UNOR_ID = $this->pegawai_model->getunor_induk($this->auth->username());
        $UNOR_ID = $this->pegawai_model->getunor_eselon1($this->auth->username());
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/sk/sk_ds/viewall');
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
            
            if($filters['nama_cb']){
                $this->sk_ds_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");    
            }
            if($filters['nip_cb']){
                $this->sk_ds_model->like('upper(pegawai."NIP_BARU")',strtoupper($filters['nip_key']),"BOTH"); 
            }
             
            if($filters['golongan_cb']){
                $this->sk_ds_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));   
            }
            
            if($filters['chkqr']){
                $this->sk_ds_model->where('tbl_file_ds.lokasi_file',$filters['textqrcode']); 
            }
            if($filters['ds_ok']){
                if($filters['ds_ok'] != ""){
                    if($filters['ds_ok'] == "1"){
                    $this->sk_ds_model->where('tbl_file_ds.ds_ok',$filters['ds_ok']);     
                    }
                    if($filters['ds_ok'] == "-"){
                        $this->sk_ds_model->where('tbl_file_ds.ds_ok != 1 or tbl_file_ds.ds_ok is null');     
                    }
                }
            }else{
                $this->sk_ds_model->where('tbl_file_ds.ds_ok',1);
            }
            if($filters['kategori_cb']){
                $this->sk_ds_model->where('tbl_file_ds.kategori',$filters['kategori_sk']); 
            }
            
        }
        $this->db->group_start();
            $this->db->where('vw."ID"',$UNOR_ID);    
            $this->db->or_where('vw."ESELON_1"',$UNOR_ID);   
            $this->db->or_where('vw."ESELON_2"',$UNOR_ID);   
            $this->db->or_where('vw."ESELON_3"',$UNOR_ID);   
            $this->db->or_where('vw."ESELON_4"',$UNOR_ID);   
            $this->db->group_end();
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total= $this->sk_ds_model->count_all_satker_ttd($this->UNOR_ID);
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->sk_ds_model->order_by("pegawai.NAMA",$order['dir']);
            }
            if($order['column']==2){
                $this->sk_ds_model->order_by("kategori",$order['dir']);
            }
            if($order['column']==3){
                $this->sk_ds_model->order_by("nomor_sk",$order['dir']);
            }
            if($order['column']==4){
                $this->sk_ds_model->order_by("NAMA_UNOR",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->sk_ds_model->limit($length,$start);
        $records=$this->sk_ds_model->find_all_satker_ttd($this->UNOR_ID);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $tgl_tandatangan = "";
                if($record->tgl_tandatangan){
                    $tgl_tandatangan = "<b>Ttd: </b>".$record->tgl_tandatangan;
                }
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = "<b>".$record->NAMA."</b><br>".$record->NIP_BARU;
                $row []  = $record->kategori;
                $row []  = $record->nomor_sk."<br>".$record->tgl_sk.$tgl_tandatangan;
                $row []  = $record->NAMA_UNOR_FULL;
                if($record->ds_ok == "1"){
                    $row []  = "DS";    
                }else{
                    $row []  = "Manual";
                }
                
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/arsip/sk_ds/lihatsk/".$record->id_file."' data-toggle='tooltip' title='Lihat SK' class='btn btn-sm btn-info'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                $btn_actions  [] = "<a href='".base_url()."admin/arsip/sk_ds/downloadsksigned/".$record->id_file."' data-toggle='tooltip' title='Download SK' class='btn btn-sm btn-warning'><i class='glyphicon glyphicon-download'></i> </a>";
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    public function getdataall_pegawai(){
        $this->auth->restrict($this->permissionViewPegawai);
        $nip_login = trim($this->auth->username());
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/sk/sk_ds/viewall');
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
            
            if($filters['nama_cb']){
                $this->sk_ds_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");    
            }
            if($filters['nip_cb']){
                $this->sk_ds_model->like('upper(pegawai."NIP_BARU")',strtoupper($filters['nip_key']),"BOTH"); 
            }
             
            if($filters['golongan_cb']){
                $this->sk_ds_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));   
            }
            
            if($filters['chkqr']){
                $this->sk_ds_model->where('tbl_file_ds.lokasi_file',$filters['textqrcode']); 
            }
            if($filters['ds_ok']){
                if($filters['ds_ok'] != ""){
                    if($filters['ds_ok'] == "1"){
                    $this->sk_ds_model->where('tbl_file_ds.ds_ok',$filters['ds_ok']);     
                    }
                    if($filters['ds_ok'] == "-"){
                        $this->sk_ds_model->where('tbl_file_ds.ds_ok != 1 or tbl_file_ds.ds_ok is null');     
                    }
                }
            }else{
                $this->sk_ds_model->where('tbl_file_ds.ds_ok',1);
            }
            if($filters['kategori_cb']){
                $this->sk_ds_model->where('tbl_file_ds.kategori',$filters['kategori_sk']); 
            }
            
        }

        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total= $this->sk_ds_model->count_all_pegawai_ttd($nip_login);
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->sk_ds_model->order_by("pegawai.NAMA",$order['dir']);
            }
            if($order['column']==2){
                $this->sk_ds_model->order_by("kategori",$order['dir']);
            }
            if($order['column']==3){
                $this->sk_ds_model->order_by("nomor_sk",$order['dir']);
            }
            if($order['column']==4){
                $this->sk_ds_model->order_by("NAMA_UNOR",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->sk_ds_model->limit($length,$start);
        $records=$this->sk_ds_model->find_all_pegawai_ttd($nip_login);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = "<b>".$record->NAMA."</b><br>".$record->NIP_BARU;
                $row []  = $record->kategori;
                $row []  = $record->nomor_sk."<br>".$record->tgl_sk;
                $row []  = $record->NAMA_UNOR_FULL;
                if($record->ds_ok == "1"){
                    $row []  = "DS";    
                }else{
                    $row []  = "Manual";
                }
                
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/arsip/sk_ds/lihatsk/".$record->id_file."' data-toggle='tooltip' title='Lihat SK' class='btn btn-sm btn-info'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                $btn_actions  [] = "<a href='".base_url()."admin/arsip/sk_ds/downloadsksigned/".$record->id_file."' data-toggle='tooltip' title='Download SK' class='btn btn-sm btn-warning'><i class='glyphicon glyphicon-download'></i> </a>";
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    
    public function scanqr()
    {
        $text = $this->input->get("text");
        $tabel = $this->input->get("tabel");
        Template::set('textbox', $text);
        Template::set('tabel', $tabel);
        Template::set('toolbar_title', "Scan QrCode");
        Template::render();
    }
    public function downloadskall_ttd()
    {
        $this->auth->restrict($this->permissionDownloadall);
        $advanced_search_filters  = $_GET;
        $this->load->helper('download');
        if($advanced_search_filters){
            $filters = $advanced_search_filters;
            if($filters['unit_id_cb']){
                $this->db->group_start();
                $this->db->where('vw."ID"',$filters['unit_id_key']);    
                $this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);   
                $this->db->group_end();
            }
            if($filters['nama_cb']){
                $this->db->group_start();
                $this->sk_ds_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");    
                $this->sk_ds_model->or_like('upper("nama_pemilik_sk")',strtoupper($filters['nama_key']),"BOTH");    
                $this->db->group_end();
            }
            if($filters['nip_cb']){
                $this->db->group_start();
                $this->sk_ds_model->like('upper(pegawai."NIP_BARU")',strtoupper($filters['nip_key']),"BOTH"); 
                $this->sk_ds_model->or_like('upper("nip_sk")',strtoupper($filters['nip_key']),"BOTH");    
                $this->db->group_end();
                
            }
            if($filters['ch_nosk']){
                $this->sk_ds_model->like('upper(tbl_file_ds."nomor_sk")',strtoupper($filters['nomor_sk']),"BOTH"); 
            }
             
            if($filters['golongan_cb']){
                $this->sk_ds_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));   
            }
            
            if($filters['chkqr']){
                $this->sk_ds_model->where('tbl_file_ds.lokasi_file',$filters['textqrcode']); 
            }
             if($filters['chkpenandatangan']){
                $this->sk_ds_model->where('id_pegawai_ttd',$filters['id_pegawai_ttd']); 
            }
            if($filters['chkidfile']){
                $this->sk_ds_model->where('tbl_file_ds.id_file',$filters['id_file']); 
            }
            
            if($filters['chkstatus']){
                //if($filters['is_signed'] == "1" or $filters['is_signed'] == "3"){
                $this->sk_ds_model->where('tbl_file_ds.is_signed',$filters['is_signed']);     
                
            }

            
            if($filters['chkstatuskoreksi']){
                $this->sk_ds_model->where('tbl_file_ds.is_corrected',$filters['is_corrected']);     
            }
            
            if($filters['ds_ok']){
                if($filters['ds_ok'] != ""){
                    if($filters['ds_ok'] == "1"){
                    $this->sk_ds_model->where('tbl_file_ds.ds_ok',$filters['ds_ok']);     
                    }
                    if($filters['ds_ok'] == "-"){
                        $this->sk_ds_model->where('tbl_file_ds.ds_ok != 1 or tbl_file_ds.ds_ok is null');     
                    }
                }
            }else{
                $this->sk_ds_model->where('tbl_file_ds.ds_ok',1);
            }
            if($filters['kategori_cb']){
                $this->sk_ds_model->where('tbl_file_ds.kategori',$filters['kategori_sk']); 
            }
        }
        $records = $this->sk_ds_model->find_all_admin($this->UNOR_ID);
        $this->load->helper('handle_upload');
        $zip = new ZipArchive();
        $zip_file = "dokumen_ttd.zip";
        $direktori = trim($this->settings_lib->item('site.pathsk'));
        if(file_exists($direktori."zip/".$zip_file)) {
            deletefile($zip_file,$direktori."zip/");
        }
        if ($zip->open($direktori."zip/".$zip_file, ZIPARCHIVE::CREATE) != TRUE) {
            die ("Could not open archive");
        }
        if (isset($records) && is_array($records) && count($records)) :
            foreach ($records as $record) :
                $dokumen_pdf = ISSET($record->id_file) ? trim($record->id_file)."_signed.pdf" : "-";
                if(file_exists($direktori.$dokumen_pdf) && $dokumen_pdf != ""){
                    $zip->addFile($direktori.trim($dokumen_pdf),TRIM($dokumen_pdf));
                }
            endforeach;
        endif;
        // close and save archive
        $zip->close();
        force_download($direktori."zip/".$zip_file,NULL);
        exit; //done.. exiting!
        
    }   
    public function downloadxls(){
        $advanced_search_filters  = $_GET;
        if($advanced_search_filters){
            $filters = $advanced_search_filters;
            if($filters['unit_id_cb']){
                $this->db->group_start();
                $this->db->where('vw."ID"',$filters['unit_id_key']);    
                $this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);   
                $this->db->group_end();
            }
            if($filters['nama_cb']){
                $this->db->group_start();
                $this->sk_ds_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");    
                $this->sk_ds_model->or_like('upper("nama_pemilik_sk")',strtoupper($filters['nama_key']),"BOTH");    
                $this->db->group_end();
            }
            if($filters['nip_cb']){
                $this->db->group_start();
                $this->sk_ds_model->like('upper(pegawai."NIP_BARU")',strtoupper($filters['nip_key']),"BOTH"); 
                $this->sk_ds_model->or_like('upper("nip_sk")',strtoupper($filters['nip_key']),"BOTH");    
                $this->db->group_end();
                
            }
            if($filters['ch_nosk']){
                $this->sk_ds_model->like('upper(tbl_file_ds."nomor_sk")',strtoupper($filters['nomor_sk']),"BOTH"); 
            }
             
            if($filters['golongan_cb']){
                $this->sk_ds_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));   
            }
            
            if($filters['chkqr']){
                $this->sk_ds_model->where('tbl_file_ds.lokasi_file',$filters['textqrcode']); 
            }
             if($filters['chkpenandatangan']){
                $this->sk_ds_model->where('id_pegawai_ttd',$filters['id_pegawai_ttd']); 
            }
            if($filters['chkidfile']){
                $this->sk_ds_model->where('tbl_file_ds.id_file',$filters['id_file']); 
            }
            
            if($filters['chkstatus']){
                //if($filters['is_signed'] == "1" or $filters['is_signed'] == "3"){
                $this->sk_ds_model->where('tbl_file_ds.is_signed',$filters['is_signed']);     
                
            }

            
            if($filters['chkstatuskoreksi']){
                $this->sk_ds_model->where('tbl_file_ds.is_corrected',$filters['is_corrected']);     
            }
            
            if($filters['ds_ok']){
                if($filters['ds_ok'] != ""){
                    if($filters['ds_ok'] == "1"){
                    $this->sk_ds_model->where('tbl_file_ds.ds_ok',$filters['ds_ok']);     
                    }
                    if($filters['ds_ok'] == "-"){
                        $this->sk_ds_model->where('tbl_file_ds.ds_ok != 1 or tbl_file_ds.ds_ok is null');     
                    }
                }
            }else{
                $this->sk_ds_model->where('tbl_file_ds.ds_ok',1);
            }
            if($filters['kategori_cb']){
                $this->sk_ds_model->where('tbl_file_ds.kategori',$filters['kategori_sk']); 
            }
        }
        
        $records = $this->sk_ds_model->find_all_admin($this->UNOR_ID);
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                if($record->NAMA != ""){
                    $record->NAMA = $record->NAMA;
                    $record->NIP_BARU = $record->NIP_BARU;
                }else{
                    $record->NAMA = $record->nama_pemilik_sk;
                    $record->NIP_BARU = $record->nip_sk;
                }
                if($record->NAMA_UNOR_FULL != ""){
                    $record->unit_kerja_pemilik_sk = $record->NAMA_UNOR_FULL;
                } 
            }
        endif;
        $this->load->library('LibOpenTbs');
        $template_name = trim($this->settings_lib->item('site.pathuploaded')).'template_skds.xlsx';
        $TBS = $this->libopentbs->TBS;
        $TBS->LoadTemplate($template_name, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
        $TBS->MergeBlock('r', $records);
        $TBS->MergeField('a', array(
            'tanggal'=>date("d-m-Y"),
        )); 

        $output_file_name = 'daftar_tandatangan.xlsx';
        $output_file_name = str_replace('.', '_'.date('Y-m-d').'.', $output_file_name);
        $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.
    }
    public function downloadxls_blmttd(){
        $advanced_search_filters  = $_GET;
        $NIP = trim($this->auth->username());
        $this->pegawai_model->select("PNS_ID,NIK,NIP_BARU");
        $pegawai_login = $this->pegawai_model->find_by("NIP_BARU",trim($NIP));
        $PNS_ID = isset($pegawai_login->PNS_ID) ? $pegawai_login->PNS_ID : "";
        if($advanced_search_filters){
            $filters = $advanced_search_filters;
            if($filters['unit_id_cb']){
                $this->db->group_start();
                $this->db->where('vw."ID"',$filters['unit_id_key']);    
                $this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);   
                $this->db->group_end();
            }
            if($filters['nama_cb']){
                $this->db->group_start();
                $this->sk_ds_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");    
                $this->sk_ds_model->or_like('upper("nama_pemilik_sk")',strtoupper($filters['nama_key']),"BOTH");    
                $this->db->group_end();
            }
            if($filters['nip_cb']){
                $this->db->group_start();
                $this->sk_ds_model->like('upper(pegawai."NIP_BARU")',strtoupper($filters['nip_key']),"BOTH"); 
                $this->sk_ds_model->or_like('upper("nip_sk")',strtoupper($filters['nip_key']),"BOTH");    
                $this->db->group_end();
            }
            if($filters['ch_nosk']){
                $this->sk_ds_model->like('upper(tbl_file_ds."nomor_sk")',strtoupper($filters['nomor_sk']),"BOTH"); 
            }
            if($filters['golongan_cb']){
                $this->sk_ds_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));   
            }
            
            if($filters['kategori_jb']){
                $this->sk_ds_model->where('"KATEGORI_JABATAN"',$filters['kategori_jabatan']); 
            }
            if($filters['kategori_cb']){
                $this->sk_ds_model->where('tbl_file_ds.kategori',$filters['kategori_sk']); 
            }
        }
        
        $records = $this->sk_ds_model->find_all_blm_ttd_new($this->UNOR_ID,false,$PNS_ID);
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                if($record->NAMA != ""){
                    $record->NAMA = $record->NAMA;
                    $record->NIP_BARU = $record->NIP_BARU;
                }else{
                    $record->NAMA = $record->nama_pemilik_sk;
                    $record->NIP_BARU = $record->nip_sk;
                }
                if($record->NAMA_UNOR_FULL != ""){
                    $record->unit_kerja_pemilik_sk = $record->NAMA_UNOR_FULL;
                } 
            }
        endif;
        $this->load->library('LibOpenTbs');
        $template_name = trim($this->settings_lib->item('site.pathuploaded')).'template_skds.xlsx';
        $TBS = $this->libopentbs->TBS;
        $TBS->LoadTemplate($template_name, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
        $TBS->MergeBlock('r', $records);
        $TBS->MergeField('a', array(
            'tanggal'=>date("d-m-Y"),
        )); 

        $output_file_name = 'daftar_tandatangan.xlsx';
        $output_file_name = str_replace('.', '_'.date('Y-m-d').'.', $output_file_name);
        $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.
    }
}