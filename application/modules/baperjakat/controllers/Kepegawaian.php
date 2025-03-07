<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Kepegawaian extends Admin_Controller
{
    protected $permissionCreate = 'Baperjakat.Kepegawaian.Create';
    protected $permissionDelete = 'Baperjakat.Kepegawaian.Delete';
    protected $permissionEdit   = 'Baperjakat.Kepegawaian.Edit';
    protected $permissionView   = 'Baperjakat.Kepegawaian.View';
    public $kategori = array('1'=>'Rotasi','2'=>'Promosi');
    public $ahari = array('Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat');
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        
        $this->load->model('baperjakat/baperjakat_model');
        $this->load->model('petajabatan/kandidat_baperjakat_model');
        $this->load->model("pegawai/unitkerja_model");
        $this->load->model('ref_jabatan/jabatan_model');
        $this->load->model('petajabatan/kuotajabatan_model');
        $this->load->model('pegawai/riwayat_jabatan_model');
        $this->lang->load('baperjakat');
        
            Assets::add_css('flick/jquery-ui-1.8.13.custom.css');
            Assets::add_js('jquery-ui-1.8.13.min.js');
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'kepegawaian/_sub_nav');

        Assets::add_module_js('baperjakat', 'baperjakat.js');

        $this->load->model('pegawai/agama_model');
        
        $agamas = $this->agama_model->find_all();
        Template::set('agamas', $agamas);
        $this->load->model('pegawai/tingkatpendidikan_model');
        $tkpendidikans = $this->tingkatpendidikan_model->find_all();
        Template::set('tkpendidikans', $tkpendidikans);
    }

    /**
     * Display a list of baperjakat data.
     *
     * @return void
     */
    public function index()
    {   
        $this->auth->restrict($this->permissionView);
        $records = $this->baperjakat_model->find_all();
        Template::set('records', $records);
        
    Template::set('toolbar_title', lang('baperjakat_manage'));

        Template::render();
    }
    public function lihatpenetapan()
    {
        $id = $this->uri->segment(5);
        Template::set('periode',$id);
        Template::set('toolbar_title', "List Penetapan");
        Template::render();
    }
    public function lihatpenetapanmenteri()
    {
        $id = $this->uri->segment(5);
        Template::set('periode',$id);
        Template::set('toolbar_title', "List Penetapan Menteri");
        Template::render();
    }
    /**
     * Create a baperjakat object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        
        if (isset($_POST['save'])) {
            if ($insert_id = $this->save_baperjakat()) {
                log_activity($this->auth->user_id(), lang('baperjakat_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'baperjakat');
                Template::set_message(lang('baperjakat_create_success'), 'success');

                redirect(SITE_AREA . '/kepegawaian/baperjakat');
            }

            // Not validation error
            if ( ! empty($this->baperjakat_model->error)) {
                Template::set_message(lang('baperjakat_create_failure') . $this->baperjakat_model->error, 'error');
            }
        }

        Template::set('toolbar_title', "Tambah Baperjakat");

        Template::render();
    }
    /**
     * Allows editing of baperjakat data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('baperjakat_invalid_id'), 'error');

            redirect(SITE_AREA . '/kepegawaian/baperjakat');
        }
        
        if (isset($_POST['save'])) {
            $this->auth->restrict($this->permissionEdit);

            if ($this->save_baperjakat('update', $id)) {
                log_activity($this->auth->user_id(), lang('baperjakat_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'baperjakat');
                Template::set_message(lang('baperjakat_edit_success'), 'success');
                redirect(SITE_AREA . '/kepegawaian/baperjakat');
            }

            // Not validation error
            if ( ! empty($this->baperjakat_model->error)) {
                Template::set_message(lang('baperjakat_edit_failure') . $this->baperjakat_model->error, 'error');
            }
        }
        
         
        
        Template::set('baperjakat', $this->baperjakat_model->find($id));

        Template::set('toolbar_title', lang('baperjakat_edit_heading'));
        Template::render();
    }
    public function editprofile($id)
    {
        if (empty($id)) {
            Template::set_message(lang('baperjakat_invalid_id'), 'error');

            redirect(SITE_AREA . '/kepegawaian/baperjakat');
        }
        $kandidats = $this->kandidat_baperjakat_model->find($id);
        $UNOR_ID = isset($kandidats->UNOR_ID) ? $kandidats->UNOR_ID : "";
        $UNITKERJA = $this->unitkerja_model->find_by("ID",$UNOR_ID);
        $KODE_INTERNAL = isset($UNITKERJA->KODE_INTERNAL) ? $UNITKERJA->KODE_INTERNAL : "";
        $this->kuotajabatan_model->where("JENIS_JABATAN","1");
        $jabatan    = $this->kuotajabatan_model->find_all($UNOR_ID);
        Template::set('jabatans',$jabatan);
        Template::set('kandidat',$kandidats);

        Template::set('toolbar_title', "Edit Profile");
        Template::render();
    }
    //--------------------------------------------------------------------------
    // !PRIVATE METHODS
    //--------------------------------------------------------------------------

    /**
     * Save the data.
     *
     * @param string $type Either 'insert' or 'update'.
     * @param int    $id   The ID of the record to update, ignored on inserts.
     *
     * @return boolean|integer An ID for successful inserts, true for successful
     * updates, else false.
     */
    private function save_baperjakat($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['ID'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->baperjakat_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->baperjakat_model->prep_data($this->input->post());

        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        
		$data['TANGGAL']	= $this->input->post('TANGGAL') ? $this->input->post('TANGGAL') : NULL;
		$data['TANGGAL_PENETAPAN']	= $this->input->post('TANGGAL_PENETAPAN') ? $this->input->post('TANGGAL_PENETAPAN') : NULL;
        $data['TANGGAL_PELANTIKAN']    = $this->input->post('TANGGAL_PELANTIKAN') ? $this->input->post('TANGGAL_PELANTIKAN') : NULL;
        $data['STATUS_AKTIF']    = $this->input->post('STATUS_AKTIF') ? $this->input->post('STATUS_AKTIF') : NULL;
        // update status semua dl
        if($data['STATUS_AKTIF'] == "1"){
            $datas = array(
                'STATUS_AKTIF'     => null
            );
            
            $this->baperjakat_model->update_where('STATUS_AKTIF', 1, $datas);
        }
        
        // end update status
        $return = false;
        if ($type == 'insert') {
            $id = $this->baperjakat_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->baperjakat_model->update($id, $data);
        }

        return $return;
    }
    public function getdata(){
        $this->auth->restrict($this->permissionView);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/kepegawaian/baperjakat');
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
            if($filters['KETERANGAN'] != ""){
                //$this->pegawai_model->where('upper("KETERANGAN") LIKE \''.strtoupper($KETERANGAN).'%\'');
                $this->baperjakat_model->like('upper("KETERANGAN")',strtoupper($filters['KETERANGAN']),"BOTH");    
            }
        }
        $output=array();
        $output['draw']=$draw;
        $total= $this->baperjakat_model->count_all();
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        $this->db->start_cache();
        
        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
             
            if($filters['KETERANGAN'] != ""){
                //$this->pegawai_model->where('upper("KETERANGAN") LIKE \''.strtoupper($KETERANGAN).'%\'');
                $this->baperjakat_model->like('upper("KETERANGAN")',strtoupper($filters['KETERANGAN']),"BOTH");    
            }
            
            
        }
        
        $this->db->stop_cache(); 
        $this->baperjakat_model->limit($length,$start);
        $records=$this->baperjakat_model->find_all();
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $status = "<span class='label label-danger'>Tidak aktif</span>";
                $statusjumlah = "0";
                $statusjumlah_menteri = "0";
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->TANGGAL;
                $row []  = $record->KETERANGAN;
                $row []  = $record->NO_SK_PENETAPAN;
                $row []  = $record->TANGGAL_PENETAPAN;
                if($record->STATUS_AKTIF == "1"){
                    $status = "<span class='label label-success'>Aktif</span>";
                }
                $row []  = $status;
                if($record->jumlah > 0){
                    $statusjumlah = "<a class='show-modal-custom' href='".base_url()."admin/kepegawaian/baperjakat/lihatpenetapan/".$record->ID."' data-toggle='tooltip' title='Lihat Kandidat'><span class='label label-success'>".$record->jumlah."</span></a>";
                }
                $row []  = $statusjumlah;

                if($record->jumlah_ditetapkan > 0){
                    $statusjumlah_menteri = "<a class='show-modal-custom' href='".base_url()."admin/kepegawaian/baperjakat/lihatpenetapanmenteri/".$record->ID."' data-toggle='tooltip' title='Lihat Penetapan'><span class='label label-success'>".$record->jumlah_ditetapkan."</span></a>";
                }
                $row []  = $statusjumlah_menteri;
                $btn_actions = array();
                if($this->auth->has_permission("Baperjakat.Kepegawaian.Edit")){
                    $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/baperjakat/edit/".$record->ID."' data-toggle='tooltip' title='Edit' class='btn btn-sm btn-warning btn-batalkan'><i class='glyphicon glyphicon-edit'></i> </a>";  
                }
                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/baperjakat/rekap/".$record->ID."' data-toggle='tooltip' title='Lihat rekapitulasi' class='btn btn-sm btn-info'><i class='glyphicon glyphicon-book'></i> </a>";
                if($this->auth->has_permission("Baperjakat.Kepegawaian.Delete")){
                    $btn_actions  [] = "<a href='#' kode='".$record->ID."' data-toggle='tooltip' title='Hapus' class='btn btn-sm btn-danger btn-hapus'><i class='glyphicon glyphicon-trash'></i> </a>";
                }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    public function deletedata()
    {
        $this->auth->restrict($this->permissionDelete);
        $this->load->model('petajabatan/kandidat_baperjakat_model');
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/kepegawaian/baperjakat');
        }
        $id     = $this->input->post('kode');
        if ($this->baperjakat_model->delete($id)) {
            $datadelete = array('ID_PERIODE'=>$id);
            $this->kandidat_baperjakat_model->delete_where($datadelete);

             log_activity($this->auth->user_id(),"Delete data" . ': ' . $id . ' : ' . $this->input->ip_address(), 'baperjakat');
             Template::set_message("Delete data sukses", 'baperjakat');
             echo "Sukses";
        }else{
            echo "Gagal";
        }

        exit();
    }
    public function getdatapenetapan(){
        $this->auth->restrict($this->permissionView);
        $periode = $this->uri->segment(5);
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

            $this->baperjakat_model->where("pegawai.\"NAMA\" != ''"); 
            if($filters['nama_cb']){
                $this->baperjakat_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");    
            }
            if($filters['nip_cb']){
                $this->baperjakat_model->like('upper("NIP_BARU")',strtoupper($filters['nip_key']),"BOTH"); 
            }
            if($filters['agama_cb']){
                if(isset($filters['agama_key']) and $filters['agama_key'] != "")
                $this->baperjakat_model->where("AGAMA_ID",$filters['agama_key']); 
            }
            if($filters['eselon_cb']){
                if($filters['eselon_key'] == "1")
                    $this->baperjakat_model->where_in("ESELON_ID",'11','12');    
                if($filters['eselon_key'] == "2")
                    $this->baperjakat_model->where_in("ESELON_ID",'21','22');    
                if($filters['eselon_key'] == "3")
                    $this->baperjakat_model->where_in("ESELON_ID",'31','32');    
                if($filters['eselon_key'] == "4")
                    $this->baperjakat_model->where_in("ESELON_ID",'41','42');    
            }
            if($filters['umur_cb']){
                if($filters['umur_operator']=="="){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR")',$filters['umur_key']*12);   
                }
                if($filters['umur_operator']==">="){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") >=',$filters['umur_key']*12);    
                }
                if($filters['umur_operator']==">"){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") >',$filters['umur_key']*12); 
                }
                if($filters['umur_operator']=="<="){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") <=',$filters['umur_key']*12);    
                }
                if($filters['umur_operator']=="<"){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR")<',$filters['umur_key']*12);  
                }
                if($filters['umur_operator']=="!="){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") !=',$filters['umur_key']*12);    
                }
            }
            if($filters['kedudukan_cb']){
                if(isset($filters['kedudukan']) and $filters['kedudukan'] != "")
                $this->baperjakat_model->where("JENIS_SATKER",$filters['kedudukan']); 
            }
            if($filters['pendidikan_cb']){
                if(isset($filters['tingkat_pendidikan']) and $filters['tingkat_pendidikan'] != "")
                $this->baperjakat_model->where("TK_PENDIDIKAN",$filters['tingkat_pendidikan']); 
            }
            if($filters['kategori_cb']){
                if(isset($filters['kategori']) and $filters['kategori'] != "")
                $this->baperjakat_model->where("KATEGORI",$filters['kategori']); 
            }
            if($filters['tahun_cb']){
                if(isset($filters['tahun_pelantikan']) and $filters['tahun_pelantikan'] != "")
                $this->baperjakat_model->where("date_part('year', \"TGL_PELANTIKAN\") = '".$filters['tahun_pelantikan']."'"); 
            }
        }
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total= $this->baperjakat_model->count_terpilih($periode);
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->baperjakat_model->order_by("NIP_BARU",$order['dir']);
            }
            if($order['column']==2){
                $this->baperjakat_model->order_by("pegawai.NAMA",$order['dir']);
            }
            if($order['column']==3){
                $this->baperjakat_model->order_by("NAMA_PANGKAT",$order['dir']);
            }
            if($order['column']==4){
                $this->baperjakat_model->order_by("NAMA_UNOR",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==4){
                $this->baperjakat_model->order_by("NAMA",$order['dir']);
            }
            if($order['column']==5){
                $this->baperjakat_model->order_by("NAMA_PANGKAT",$order['dir']);
            }
             
        }

        $this->baperjakat_model->limit($length,$start);
        $records=$this->baperjakat_model->find_terpilih($periode);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $foto_pegawai = trim($this->settings_lib->item('site.urlphoto'))."noimage.jpg";
                if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$record->PHOTO)){
                    $foto_pegawai =  trim($this->settings_lib->item('site.urlphoto')).$record->PHOTO;
                }

                $row = array();
                $pilih = "";
                if($record->TGL_PELANTIKAN != ""){
                    $pilih = "checked";
                }
                $row []  = "<input type='checkbox' name='chk_pelantikan[]' class='".$pilih."' value='".$record->ID."'/>";
                $row []  = $nomor_urut.".";
                $row []  = "<img src='".base_url().$foto_pegawai."' width='100px'/>";
                $row []  = "<span class='".$pilih."'>".$record->NIP_BARU."<br><i>".$record->NAMA."</i>"."</span>";
                $row []  = $record->NAMA_PANGKAT."/".$record->NAMA_GOLONGAN;
                $row []  = $record->NAMA_UNOR_FULL;
                $row []  = $record->NAMA_JABATAN;
                $btn_actions = array();
                if($record->STATUS_MENTERI == 1){
                    $btn_actions  [] = "<a href='#' kode='".$record->ID."' data-toggle='tooltip' title='Batalkan Penetapan' class='btn btn-sm btn-success btn-batalkan_menteri'><i class='glyphicon glyphicon-edit'></i> </a>";    
                }else{
                    $btn_actions  [] = "<a href='#' kode='".$record->ID."' data-toggle='tooltip' title='Tetapkan menteri' class='btn btn-sm btn-warning btn-tetapkan'><i class='glyphicon glyphicon-edit'></i> </a>";
                }
                
                $btn_actions  [] = "
                <a href='".base_url()."admin/kepegawaian/pegawai/profilen/".$record->id_pegawai."'  data-toggle='tooltip' title='Lihat Profile'  class='btn btn-sm btn-danger'><i class='glyphicon glyphicon-user'></i> </a>

                ";/*add by bana 04_02_2019*/
            
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
    }
    public function getdatapenetapanmenteri(){
        $this->auth->restrict($this->permissionView);
        $periode = $this->uri->segment(5);
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

            $this->baperjakat_model->where("pegawai.\"NAMA\" != ''"); 
            if($filters['nama_cb']){
                $this->baperjakat_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");    
            }
            if($filters['nip_cb']){
                $this->baperjakat_model->like('upper("NIP_BARU")',strtoupper($filters['nip_key']),"BOTH"); 
            }
            if($filters['agama_cb']){
                if(isset($filters['agama_key']) and $filters['agama_key'] != "")
                $this->baperjakat_model->where("AGAMA_ID",$filters['agama_key']); 
            }
            if($filters['eselon_cb']){
                if($filters['eselon_key'] == "1")
                    $this->baperjakat_model->where_in("ESELON_ID",'11','12');    
                if($filters['eselon_key'] == "2")
                    $this->baperjakat_model->where_in("ESELON_ID",'21','22');    
                if($filters['eselon_key'] == "3")
                    $this->baperjakat_model->where_in("ESELON_ID",'31','32');    
                if($filters['eselon_key'] == "4")
                    $this->baperjakat_model->where_in("ESELON_ID",'41','42');    
            }
            if($filters['umur_cb']){
                if($filters['umur_operator']=="="){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR")',$filters['umur_key']*12);   
                }
                if($filters['umur_operator']==">="){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") >=',$filters['umur_key']*12);    
                }
                if($filters['umur_operator']==">"){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") >',$filters['umur_key']*12); 
                }
                if($filters['umur_operator']=="<="){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") <=',$filters['umur_key']*12);    
                }
                if($filters['umur_operator']=="<"){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR")<',$filters['umur_key']*12);  
                }
                if($filters['umur_operator']=="!="){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") !=',$filters['umur_key']*12);    
                }
            }
            if($filters['kedudukan_cb']){
                if(isset($filters['kedudukan']) and $filters['kedudukan'] != "")
                $this->baperjakat_model->where("JENIS_SATKER",$filters['kedudukan']); 
            }
            if($filters['pendidikan_cb']){
                if(isset($filters['tingkat_pendidikan']) and $filters['tingkat_pendidikan'] != "")
                $this->baperjakat_model->where("TK_PENDIDIKAN",$filters['tingkat_pendidikan']); 
            }
            if($filters['kategori_cb']){
                if(isset($filters['kategori']) and $filters['kategori'] != "")
                $this->baperjakat_model->where("KATEGORI",$filters['kategori']); 
            }
            if($filters['tahun_cb']){
                if(isset($filters['tahun_pelantikan']) and $filters['tahun_pelantikan'] != "")
                $this->baperjakat_model->where("date_part('year', \"TGL_PELANTIKAN\") = '".$filters['tahun_pelantikan']."'"); 
            }
            if($filters['tgl_cb']){
                if(isset($filters['tanggal_pelantikan']) and $filters['tanggal_pelantikan'] != "")
                $this->baperjakat_model->where("TGL_PELANTIKAN",$filters['tanggal_pelantikan']); 
            }
        }
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $this->baperjakat_model->where("STATUS_MENTERI",1);
        $total= $this->baperjakat_model->count_terpilih($periode);
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->baperjakat_model->order_by("NIP_BARU",$order['dir']);
            }
            if($order['column']==2){
                $this->baperjakat_model->order_by("pegawai.NAMA",$order['dir']);
            }
            if($order['column']==3){
                $this->baperjakat_model->order_by("NAMA_PANGKAT",$order['dir']);
            }
            if($order['column']==4){
                $this->baperjakat_model->order_by("NAMA_UNOR",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==4){
                $this->baperjakat_model->order_by("NAMA",$order['dir']);
            }
            if($order['column']==5){
                $this->baperjakat_model->order_by("NAMA_PANGKAT",$order['dir']);
            }
             
        }

        $this->baperjakat_model->limit($length,$start);
        $this->baperjakat_model->where("STATUS_MENTERI",1);
        $records=$this->baperjakat_model->find_terpilih($periode);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $foto_pegawai = trim($this->settings_lib->item('site.urlphoto'))."noimage.jpg";
                if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$record->PHOTO)){
                    $foto_pegawai =  trim($this->settings_lib->item('site.urlphoto')).$record->PHOTO;
                }

                $row = array();
                $pilih = "";
                if($record->TGL_PELANTIKAN != ""){
                    $pilih = "checked";
                }
                $row []  = "<input type='checkbox' name='chk_pelantikan[]' class='".$pilih."' value='".$record->ID."'/>";
                $row []  = $nomor_urut.".";
                $row []  = "<img src='".base_url().$foto_pegawai."' width='100px'/>";
                $row []  = "<span class='".$pilih."'>".$record->NIP_BARU."<br><i>".$record->NAMA."</i>"."</span>";
                $row []  = $record->NAMA_PANGKAT."/".$record->NAMA_GOLONGAN;
                $row []  = $record->NAMA_UNOR_FULL;
                $row []  = $record->NAMA_JABATAN;
                $btn_actions = array();
                if($record->STATUS_MENTERI == 1){
                    $btn_actions  [] = "<a href='#' kode='".$record->ID."' data-toggle='tooltip' title='Batalkan Penetapan' class='btn btn-sm btn-success btn-batalkan_menteri'><i class='glyphicon glyphicon-edit'></i> </a>";    
                }else{
                    $btn_actions  [] = "<a href='#' kode='".$record->ID."' data-toggle='tooltip' title='Tetapkan menteri' class='btn btn-sm btn-warning btn-tetapkan'><i class='glyphicon glyphicon-edit'></i> </a>";
                }
                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/baperjakat/editprofile/".$record->ID."' kode='".$record->ID."' data-toggle='tooltip' title='Ubah data' class='btn btn-sm btn-warning show-modal'><i class='fa fa-gear'></i> </a>";
                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/baperjakat/downloadundangan/".$record->ID."' kode='".$record->ID."' data-toggle='tooltip' title='Download Surat Undangan' class='btn btn-sm btn-warning'><i class='fa fa-download'></i> </a>";
                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/baperjakat/downloadbap/".$record->ID."' kode='".$record->ID."' data-toggle='tooltip' title='Download Berita Acara Pelantikan' class='btn btn-sm btn-warning'><i class='fa fa-download'></i> </a>";
                $btn_actions  [] = "
                <a href='".base_url()."admin/kepegawaian/pegawai/profilen/".$record->id_pegawai."'  data-toggle='tooltip' title='Lihat Profile'  class='btn btn-sm btn-danger'><i class='glyphicon glyphicon-user'></i> </a>

                ";/*add by bana 04_02_2019*/
            
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
    }
    public function downloadundangan($id){
        $pegawai = $this->kandidat_baperjakat_model->find_detil($id);
        $tanggal_pelantikan = isset($pegawai->TGL_PELANTIKAN) ? $pegawai->TGL_PELANTIKAN : "";
        $this->load->library('LibOpenTbs');
        $template_name = APPPATH."..".DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'MASTER_UNDANGAN.docx';
        //$template_name = '/Applications/XAMPP/xamppfiles/htdocs/dikbudhrd/assets/templates'.DIRECTORY_SEPARATOR.'MASTER_UNDANGAN.docx';
        $TBS = $this->libopentbs->TBS;
        $TBS->LoadTemplate($template_name, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
        //die($foto_pegawai);
        $hari = date('l', strtotime($tanggal_pelantikan));
        $jabatan = isset($pegawai->JABATAN) ? $pegawai->JABATAN : "";
        $TBS->MergeField('a', array(
            'fullnama'=>$pegawai->GELAR_DEPAN." ".$pegawai->NAMA." ".$pegawai->GELAR_BELAKANG,
            'hari'=>isset($this->ahari[$hari]) ? $this->ahari[$hari] : "",
            'jabatan'=>$jabatan,
            'tgl_pelantikan'=>$tanggal_pelantikan,
        )); 

        $output_file_name = 'undangan.docx';
        $output_file_name = str_replace('.', '_'.date('Y-m-d').$pegawai->NIP_BARU.'.', $output_file_name);
        $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.
    }
    public function downloadbap($id){
        $this->load->model('daftar_rohaniawan/daftar_rohaniawan_model');
        $rohaniawans = $this->daftar_rohaniawan_model->find_aktif("");
        $rohaniawan = array();
        if (isset($rohaniawans) && is_array($rohaniawans) && count($rohaniawans)) :
            foreach ($rohaniawans as $record) :
                $rohaniawan["nip_".$record->agama] = $record->nip;
                $rohaniawan["nama_".$record->agama] = $record->nama;
                $rohaniawan["pangkat_gol_".$record->agama] = $record->pangkat_gol;
            endforeach;
        endif;
        $pegawai = $this->kandidat_baperjakat_model->find_detil($id);
        $tanggal_pelantikan = isset($pegawai->TGL_PELANTIKAN) ? $pegawai->TGL_PELANTIKAN : "";
        $this->load->library('LibOpenTbs');
        $template_name = APPPATH."..".DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'FORMAT_BERITA_ACARA_PELANTIKAN.docx';
        $TBS = $this->libopentbs->TBS;
        $TBS->LoadTemplate($template_name, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
        //die($foto_pegawai);
        $hari = date('l', strtotime($tanggal_pelantikan));
        $jabatan = isset($pegawai->JABATAN) ? $pegawai->JABATAN : "";
        $AGAMA_ID = isset($pegawai->AGAMA_ID) ? $pegawai->AGAMA_ID : "";
        
        $TBS->MergeField('a', array(
            'fullnama'=>$pegawai->GELAR_DEPAN." ".$pegawai->NAMA." ".$pegawai->GELAR_BELAKANG,
            'hari'=>isset($this->ahari[$hari]) ? $this->ahari[$hari] : "",
            'jabatan'=>$jabatan,
            'tgl_pelantikan'=>$tanggal_pelantikan,
            'nip'=>$pegawai->NIP_BARU,
            'no_sk'=>$pegawai->NO_SK_PELANTIKAN,
            'nama_rohaniawan'=>isset($rohaniawan["nama_".$AGAMA_ID]) ? $rohaniawan["nama_".$AGAMA_ID] : "",
            'nip_rohaniawan'=>isset($rohaniawan["nip_".$AGAMA_ID]) ? $rohaniawan["nip_".$AGAMA_ID] : "",
            'pangkat_rohaniawan'=>isset($rohaniawan["pangkat_gol_".$AGAMA_ID]) ? $rohaniawan["pangkat_gol_".$AGAMA_ID] : "",
        )); 

        $output_file_name = 'BAP.docx';
        $output_file_name = str_replace('.', '_'.date('Y-m-d').$pegawai->NIP_BARU.'.', $output_file_name);
        $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.
    }
    public function savepelantikan(){
         // Validate the data
        $this->load->model('petajabatan/kandidat_baperjakat_model');
        $this->form_validation->set_rules('tgl_pelantikan','Tanggal Pelantikan','required|max_length[20]');
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = "
            <div class='alert alert-block alert-error fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                    Error
                </h4>
                ".validation_errors()."
            </div>
            ";
            echo json_encode($response);
            exit();
        }
        $data['TGL_PELANTIKAN'] = $this->input->post("tgl_pelantikan");
        $chk_pelantikan = $this->input->post('chk_pelantikan');
        if (is_array($chk_pelantikan) && count($chk_pelantikan)) {
            $result = true;
            foreach ($chk_pelantikan as $pid) {
                $this->kandidat_baperjakat_model->update($pid,$data);
            }
            $response ['success']= true;
            $response ['msg']= "Update Berhasil";
        }else{

            $response ['success']= false;
            $response['msg'] = "
            <div class='alert alert-block alert-error fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                    Error
                </h4>
                Silahkan Pilih Pegawai
            </div>
            ";
              
        }
        echo json_encode($response);  
    }
    public function saveprofile($id_data){
         // Validate the data
        $this->form_validation->set_rules('ID_JABATAN','Silahkan pilih jabatan','required|max_length[20]');
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = "
            <div class='alert alert-block alert-error fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                    Error
                </h4>
                ".validation_errors()."
            </div>
            ";
            echo json_encode($response);
            exit();
        }
        $dataupdate['TGL_PELANTIKAN'] = $this->input->post("TGL_PELANTIKAN");
        $dataupdate["JABATAN_ID"]      = $this->input->post("ID_JABATAN");
        $this->kandidat_baperjakat_model->update($id_data,$dataupdate);
        // save to riwahat jabatan
        $this->pegawai_model->where("NIP_BARU",trim($this->input->post("NIP")));
        $pegawai_data = $this->pegawai_model->find_first_row();  

        $NIP_BARU = $pegawai_data->NIP_BARU;
        $PNS_ID = $pegawai_data->PNS_ID;
        $data["PNS_NIP"] = $pegawai_data->NIP_BARU;
        $data["PNS_ID"] = $pegawai_data->PNS_ID;
        $data["PNS_NAMA"] = $pegawai_data->NAMA;
        
        $data["ID_JENIS_JABATAN"]   = "1";
        $data["IS_ACTIVE"]          = "1";
        $data["JENIS_JABATAN"]      = "Struktural";
        
        $rec_jabatan = $this->jabatan_model->find_by("KODE_JABATAN",$this->input->post("ID_JABATAN"));
        $data["ID_JABATAN_BKN"] = isset($rec_jabatan->KODE_BKN) ? $rec_jabatan->KODE_BKN : "";
        $data["ID_JABATAN"] = $this->input->post("ID_JABATAN");

        // struktur
        $rec_jabatan_struktural = $this->unitkerja_model->find_by("ID",$this->input->post("UNOR_ID")); // POST
        $data["ID_UNOR_BKN"]    = isset($rec_jabatan_struktural->ID) ? $rec_jabatan_struktural->ID : "";
        $data["ID_UNOR"]        = isset($rec_jabatan_struktural->KODE_INTERNAL) ? $rec_jabatan_struktural->KODE_INTERNAL : "";
        $data["UNOR"]           = isset($rec_jabatan_struktural->NAMA_UNOR) ? $rec_jabatan_struktural->NAMA_UNOR : "";
        $data["ESELON"]           = isset($rec_jabatan_struktural->ESELON_ID) ? $rec_jabatan_struktural->ESELON_ID : "";
        $ID_SATUAN_KERJA           = isset($rec_jabatan_struktural->UNOR_INDUK) ? $rec_jabatan_struktural->UNOR_INDUK : "";
        
        $data["NOMOR_SK"]         = $this->input->post("NO_SK_PELANTIKAN");
        $NAMA_JABATAN  = isset($rec_jabatan_struktural->NAMA_JABATAN) ? $rec_jabatan_struktural->NAMA_JABATAN : "";
        $data["NAMA_JABATAN"] = $NAMA_JABATAN;
        
        // jika jabatannya struktural, update ketabel unitkerja
        // hilangkan pemimpin PNS pada unitkerja lama jika dia adalah salahsatu pemimpin di unitkerja (promosi/rotasi)
        if($PNS_ID != ""){
            $dataupdate = array(
                'PEMIMPIN_PNS_ID'   => 'null'
            );
            $this->unitkerja_model->update_where("PEMIMPIN_PNS_ID",$PNS_ID, $dataupdate);
        }
        //input pemimpin baru Pada unitkerja baru
        if($this->input->post("UNOR_ID") != ""){
            if($this->input->post("ID_JABATAN") != ""){
                $UNOR_ID           = $this->input->post("UNOR_ID");
                // jika jabatan aktif set ke update tabel pejabat pada tabel unitkerja
                $dataupdate = array(
                    'PEMIMPIN_PNS_ID'   => $PNS_ID,
                    'JABATAN_ID'        => $this->input->post("ID_JABATAN")
                );
                $this->unitkerja_model->update($UNOR_ID, $dataupdate);
            }
        }
         
        $data["ID_SATUAN_KERJA"]    = $ID_SATUAN_KERJA;

        $data["TMT_JABATAN"]    = $this->input->post("TMT_JABATAN");;
        if(empty($data["TMT_JABATAN"])){
            unset($data["TMT_JABATAN"]);
        }
        $data["TANGGAL_SK"]    = $this->input->post("TGL_SK");;
        if(empty($data["TANGGAL_SK"])){
            unset($data["TANGGAL_SK"]);
        }
        $data["TMT_PELANTIKAN"]    = $this->input->post("TGL_PELANTIKAN");;
        if(empty($data["TMT_PELANTIKAN"])){
            unset($data["TMT_PELANTIKAN"]);
        }
        
        $data["LAST_UPDATED"]   = date("Y-m-d");
        $data_riwayat = $this->riwayat_jabatan_model->find_by("NOMOR_SK",$this->input->post("NO_SK_PELANTIKAN"));
        $id_riwayat = isset($data_riwayat->ID) ? $data_riwayat->ID : "";
        if(isset($id_riwayat) && !empty($id_riwayat)&& !empty($this->input->post("NO_SK_PELANTIKAN"))){
            $this->riwayat_jabatan_model->update($id_riwayat,$data,$this->input->post());
            // add log
            log_activity_pegawai($NIP_BARU,$this->auth->user_id(),"Update data Riwayat Jabatan", 'rwt_jabatan');
        }
        else{
             $result = $this->riwayat_jabatan_model->insert($data,$this->input->post());
            if($result){
                log_activity_pegawai($NIP_BARU,$this->auth->user_id(),"Tambah data Riwayat Jabatan", 'rwt_jabatan');
                log_activity($this->auth->user_id(), 'Simpan ke riwayat jabatan sukses, id : ' . $result . ' : ' . $this->input->ip_address(), 'rwt_jabatan');
            }
            else{
                log_activity_pegawai($NIP_BARU,$this->auth->user_id(),"Tambah data Riwayat Jabatan", 'rwt_jabatan');
                log_activity($this->auth->user_id(), 'Simpan ke riwayat jabatan gagal, dari : ' . $this->input->ip_address(), 'rwt_jabatan');
            }
         }
        $response ['success']= true;
        $response ['msg']= "berhasil";
        echo json_encode($response);    
    }
    // tetapkan kandidat yang sudah dipilih menteri
    public function tetapkankandidatmenteri($record_id = ""){
        //$this->auth->restrict($this->permissionDelete);
        $this->load->model('petajabatan/kandidat_baperjakat_model');
        $datastruktur   = $this->kandidat_baperjakat_model->find($record_id);
        $UNOR_ID     = $datastruktur->UNOR_ID ? $datastruktur->UNOR_ID : 0;

        // update yang telah ditetapkan menjadi 0 semua
        $datas = array(
            'STATUS_MENTERI'     => 0,
            'TGL_PELANTIKAN'     => NULL
        );
        $this->kandidat_baperjakat_model->where('UNOR_ID',$UNOR_ID);
        $this->kandidat_baperjakat_model->update_where('STATUS_MENTERI', 1, $datas);

        $data           = array();
        $data["STATUS_MENTERI"]     = 1;
        $data["UPDATE_BY"]          = $this->auth->user_id();
        $data["UPDATE_DATE"]        = date("Y-m-d");
        if ($this->kandidat_baperjakat_model->update($record_id,$data)) {
            log_activity($this->auth->user_id(), 'Penetapan data pejabat dari menteri: ' . $record_id . ' : ' . $this->input->ip_address(), 'struktural');
            Template::set_message("Sukses menetapkan pejabat", 'success');
            echo "Sukses";
        }else{
            echo "Gagal";
        }
        exit();
    }
    public function batanlkankandidatmenteri($record_id = ""){
        //$this->auth->restrict($this->permissionDelete);
        $this->load->model('petajabatan/kandidat_baperjakat_model');
        $datastruktur   = $this->kandidat_baperjakat_model->find($record_id);
        $UNOR_ID     = $datastruktur->UNOR_ID ? $datastruktur->UNOR_ID : 0;

        
        $data           = array();
        $data["STATUS_MENTERI"]     = NULL;
        $data["TGL_PELANTIKAN"]     = NULL;
        $data["UPDATE_BY"]          = $this->auth->user_id();
        $data["UPDATE_DATE"]        = date("Y-m-d");
        if ($this->kandidat_baperjakat_model->update($record_id,$data)) {
            log_activity($this->auth->user_id(), 'Batalkan Penetapan dari menteri: ' . $record_id . ' : ' . $this->input->ip_address(), 'struktural');
            Template::set_message("Sukses Pembatalan pejabat", 'success');
            echo "Sukses";
        }else{
            echo "Gagal";
        }
        exit();
    }
    public function rekap()
    {
        $this->load->model('petajabatan/kandidat_baperjakat_model');
        $id = $this->uri->segment(5);
        $STATUS = $this->uri->segment(6);
        if (empty($id)) {
            Template::set_message(lang('baperjakat_invalid_id'), 'error');

            redirect(SITE_AREA . '/kepegawaian/baperjakat');
        }
        $rekapagamas = $this->kandidat_baperjakat_model->rekap_agama($id,$STATUS);
        $rekapeselons = $this->kandidat_baperjakat_model->rekap_eselon($id,$STATUS);
        $rekapjks = $this->kandidat_baperjakat_model->rekap_jk($id,$STATUS);
        $rekap_tkpendidikan = $this->kandidat_baperjakat_model->rekap_tkpendidikan($id,$STATUS);
        $rekap_satker = $this->kandidat_baperjakat_model->rekap_satker($id,$STATUS);
        Template::set('periode',$id);
        Template::set('rakapagamas',$rekapagamas);
        Template::set('rekapeselons',$rekapeselons);
        Template::set('rekapjks',$rekapjks);
        Template::set('rekap_tkpendidikan',$rekap_tkpendidikan);
        Template::set('rekap_satker',$rekap_satker);
        Template::set('STATUS',$STATUS);
        Template::set('toolbar_title',"Rekap");
        Template::render();
    }
    public function downloadpenetapan()
    {
        $periode = $this->uri->segment(5);
        $databaperjakat = $this->baperjakat_model->find($periode);
        $keterangan = $databaperjakat->KETERANGAN;

        $advanced_search_filters  = $_GET;

        if($advanced_search_filters){
         
            $filters = array();
            foreach($advanced_search_filters as $key => $item){
                $filters[$key] = $item;
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

            $this->baperjakat_model->where("pegawai.\"NAMA\" != ''"); 
            if($filters['nama_cb']){
                $this->baperjakat_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");    
            }
            if($filters['nip_cb']){
                $this->baperjakat_model->like('upper("NIP_BARU")',strtoupper($filters['nip_key']),"BOTH"); 
            }
            if($filters['agama_cb']){
                if(isset($filters['agama_key']) and $filters['agama_key'] != "")
                $this->baperjakat_model->where("AGAMA_ID",$filters['agama_key']); 
            }
            if($filters['eselon_cb']){
                if($filters['eselon_key'] == "1")
                    $this->baperjakat_model->where_in("ESELON_ID",'11','12');    
                if($filters['eselon_key'] == "2")
                    $this->baperjakat_model->where_in("ESELON_ID",'21','22');    
                if($filters['eselon_key'] == "3")
                    $this->baperjakat_model->where_in("ESELON_ID",'31','32');    
                if($filters['eselon_key'] == "4")
                    $this->baperjakat_model->where_in("ESELON_ID",'41','42');    
            }
            if($filters['umur_cb']){
                if($filters['umur_operator']=="="){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR")',$filters['umur_key']*12);   
                }
                if($filters['umur_operator']==">="){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") >=',$filters['umur_key']*12);    
                }
                if($filters['umur_operator']==">"){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") >',$filters['umur_key']*12); 
                }
                if($filters['umur_operator']=="<="){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") <=',$filters['umur_key']*12);    
                }
                if($filters['umur_operator']=="<"){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR")<',$filters['umur_key']*12);  
                }
                if($filters['umur_operator']=="!="){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") !=',$filters['umur_key']*12);    
                }
            }
            if($filters['kedudukan_cb']){
                if(isset($filters['kedudukan']) and $filters['kedudukan'] != "")
                $this->baperjakat_model->where("JENIS_SATKER",$filters['kedudukan']); 
            }
            if($filters['pendidikan_cb']){
                if(isset($filters['tingkat_pendidikan']) and $filters['tingkat_pendidikan'] != "")
                $this->baperjakat_model->where("TK_PENDIDIKAN",$filters['tingkat_pendidikan']); 
            }
            if($filters['kategori_cb']){
                if(isset($filters['kategori']) and $filters['kategori'] != "")
                $this->baperjakat_model->where("KATEGORI",$filters['kategori']); 
            }
            if($filters['tahun_cb']){
                if(isset($filters['tahun_pelantikan']) and $filters['tahun_pelantikan'] != "")
                $this->baperjakat_model->where("date_part('year', \"TGL_PELANTIKAN\") = '".$filters['tahun_pelantikan']."'"); 
            }
        }

        $datapegwai=$this->baperjakat_model->find_terpilih($periode);
        $this->load->library('Excel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel = PHPExcel_IOFactory::load(trim($this->settings_lib->item('site.pathuploaded')).'template.xls');

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,"Baperjakat Periode : ".$keterangan);

        $objPHPExcel->setActiveSheetIndex(0);
        $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,3,"No");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,3,"Photo");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,3,"NIP");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,3,"Nama");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,3,"Golongan");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,3,"Status");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,3,"Unitkerja");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,3,"Jabatan");$col++;
        
        
        $row = 4;
        if (isset($datapegwai) && is_array($datapegwai) && count($datapegwai)) :
            $nomor_urut = 1;
            foreach ($datapegwai as $record) :
                $col = 0;
                $type = PHPExcel_Cell_DataType::TYPE_STRING;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$row,$nomor_urut,PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,$record->NIP." ",PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,$record->NAMA." ",PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row,$record->NAMA_PANGKAT."/".$record->NAMA_GOLONGAN." ",PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row,$this->kategori[$record->KATEGORI]." ",PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$row,$record->NAMA_UNOR_FULL." ",PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$row,$record->NAMA_JABATAN." ",PHPExcel_Cell_DataType::TYPE_STRING);

                $photo = ISSET($record->PHOTO) ? trim($this->settings_lib->item('site.pathphoto')).trim($record->PHOTO) : "";
                if(file_exists(trim($this->settings_lib->item('site.pathphoto')).$record->PHOTO) and $photo != ""){
                    if($record->PHOTO != ""){

                        $objDrawing = new PHPExcel_Worksheet_Drawing();
                        $objDrawing->setName('photo');
                        $objDrawing->setDescription('Photo kandidat');
                        $objDrawing->setPath($photo);
                        $objDrawing->setOffsetX(25);                     //setOffsetX works properly
                        $objDrawing->setOffsetY(10);                     //setOffsetY works properly
                        $objDrawing->setCoordinates("B".$row);             //set image to cell
                        $objDrawing->setWidth(150);  
                        $objDrawing->setHeight(150);                     //signature0 height  
                        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save
                        //$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('Z')->setAutoSize(true);
                        $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(150);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
                    }else{
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$row,"Foto tidak ada ",PHPExcel_Cell_DataType::TYPE_STRING);    
                    }                    
                }
                //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$row,$photo,PHPExcel_Cell_DataType::TYPE_STRING);    
                //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$row,$photo." ",PHPExcel_Cell_DataType::TYPE_STRING);
            $row++;
            $nomor_urut ++;
            endforeach;
        endif;
          
        $filename = "penetapan_".$periode."_".mt_rand(1,100000).'.xls'; //just some random filename
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
        //$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
        $objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
        exit; //done.. exiting!
        
    }
    public function downloadpenetapanmenteri()
    {
        $periode = $this->uri->segment(5);
        $databaperjakat = $this->baperjakat_model->find($periode);
        $keterangan = $databaperjakat->KETERANGAN;

        $advanced_search_filters  = $_GET;

        if($advanced_search_filters){
         
            $filters = array();
            foreach($advanced_search_filters as $key => $item){
                $filters[$key] = $item;
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

            $this->baperjakat_model->where("pegawai.\"NAMA\" != ''"); 
            if($filters['nama_cb']){
                $this->baperjakat_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");    
            }
            if($filters['nip_cb']){
                $this->baperjakat_model->like('upper("NIP_BARU")',strtoupper($filters['nip_key']),"BOTH"); 
            }
            if($filters['agama_cb']){
                if(isset($filters['agama_key']) and $filters['agama_key'] != "")
                $this->baperjakat_model->where("AGAMA_ID",$filters['agama_key']); 
            }
            if($filters['eselon_cb']){
                if($filters['eselon_key'] == "1")
                    $this->baperjakat_model->where_in("ESELON_ID",'11','12');    
                if($filters['eselon_key'] == "2")
                    $this->baperjakat_model->where_in("ESELON_ID",'21','22');    
                if($filters['eselon_key'] == "3")
                    $this->baperjakat_model->where_in("ESELON_ID",'31','32');    
                if($filters['eselon_key'] == "4")
                    $this->baperjakat_model->where_in("ESELON_ID",'41','42');    
            }
            if($filters['umur_cb']){
                if($filters['umur_operator']=="="){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR")',$filters['umur_key']*12);   
                }
                if($filters['umur_operator']==">="){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") >=',$filters['umur_key']*12);    
                }
                if($filters['umur_operator']==">"){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") >',$filters['umur_key']*12); 
                }
                if($filters['umur_operator']=="<="){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") <=',$filters['umur_key']*12);    
                }
                if($filters['umur_operator']=="<"){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR")<',$filters['umur_key']*12);  
                }
                if($filters['umur_operator']=="!="){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") !=',$filters['umur_key']*12);    
                }
            }
            if($filters['kedudukan_cb']){
                if(isset($filters['kedudukan']) and $filters['kedudukan'] != "")
                $this->baperjakat_model->where("JENIS_SATKER",$filters['kedudukan']); 
            }
            if($filters['pendidikan_cb']){
                if(isset($filters['tingkat_pendidikan']) and $filters['tingkat_pendidikan'] != "")
                $this->baperjakat_model->where("TK_PENDIDIKAN",$filters['tingkat_pendidikan']); 
            }
            if($filters['kategori_cb']){
                if(isset($filters['kategori']) and $filters['kategori'] != "")
                $this->baperjakat_model->where("KATEGORI",$filters['kategori']); 
            }
            if($filters['tahun_cb']){
                if(isset($filters['tahun_pelantikan']) and $filters['tahun_pelantikan'] != "")
                $this->baperjakat_model->where("date_part('year', \"TGL_PELANTIKAN\") = '".$filters['tahun_pelantikan']."'"); 
            }
            if($filters['tgl_cb']){
                if(isset($filters['tanggal_pelantikan']) and $filters['tanggal_pelantikan'] != "")
                $this->baperjakat_model->where("TGL_PELANTIKAN",$filters['tanggal_pelantikan']); 
            }

        }
        $this->baperjakat_model->where("STATUS_MENTERI",1);
        $datapegwai=$this->baperjakat_model->find_terpilih($periode);
        $this->load->library('Excel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel = PHPExcel_IOFactory::load(trim($this->settings_lib->item('site.pathuploaded')).'template.xls');

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,"Baperjakat Periode : ".$keterangan);

        $objPHPExcel->setActiveSheetIndex(0);
        $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,3,"No");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,3,"NIP");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,3,"Nama");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,3,"Golongan");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,3,"Unitkerja");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,3,"Jabatan");$col++;

        $row = 4;
        if (isset($datapegwai) && is_array($datapegwai) && count($datapegwai)) :
            $nomor_urut = 1;
            foreach ($datapegwai as $record) :
                $col = 0;
                $type = PHPExcel_Cell_DataType::TYPE_STRING;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$row,$nomor_urut,PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$row,$record->NIP." ",PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,$record->NAMA." ",PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,$record->NAMA_PANGKAT."/".$record->NAMA_GOLONGAN." ",PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row,$record->NAMA_UNOR_FULL." ",PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row,$record->NAMA_JABATAN." ",PHPExcel_Cell_DataType::TYPE_STRING);
            $row++;
            $nomor_urut ++;
            endforeach;
        endif;
          
        $filename = "penetapan_".$periode."_".mt_rand(1,100000).'.xls'; //just some random filename
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
        //$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
        $objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
        exit; //done.. exiting!
        
    }
    public function downloadabsen()
    {
        $periode = $this->uri->segment(5);
        $databaperjakat = $this->baperjakat_model->find($periode);
        $keterangan = $databaperjakat->KETERANGAN;

        $advanced_search_filters  = $_GET;

        if($advanced_search_filters){
         
            $filters = array();
            foreach($advanced_search_filters as $key => $item){
                $filters[$key] = $item;
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

            $this->baperjakat_model->where("pegawai.\"NAMA\" != ''"); 
            if($filters['nama_cb']){
                $this->baperjakat_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");    
            }
            if($filters['nip_cb']){
                $this->baperjakat_model->like('upper("NIP_BARU")',strtoupper($filters['nip_key']),"BOTH"); 
            }
            if($filters['agama_cb']){
                if(isset($filters['agama_key']) and $filters['agama_key'] != "")
                $this->baperjakat_model->where("AGAMA_ID",$filters['agama_key']); 
            }
            if($filters['eselon_cb']){
                if($filters['eselon_key'] == "1")
                    $this->baperjakat_model->where_in("ESELON_ID",'11','12');    
                if($filters['eselon_key'] == "2")
                    $this->baperjakat_model->where_in("ESELON_ID",'21','22');    
                if($filters['eselon_key'] == "3")
                    $this->baperjakat_model->where_in("ESELON_ID",'31','32');    
                if($filters['eselon_key'] == "4")
                    $this->baperjakat_model->where_in("ESELON_ID",'41','42');    
            }
            if($filters['umur_cb']){
                if($filters['umur_operator']=="="){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR")',$filters['umur_key']*12);   
                }
                if($filters['umur_operator']==">="){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") >=',$filters['umur_key']*12);    
                }
                if($filters['umur_operator']==">"){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") >',$filters['umur_key']*12); 
                }
                if($filters['umur_operator']=="<="){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") <=',$filters['umur_key']*12);    
                }
                if($filters['umur_operator']=="<"){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR")<',$filters['umur_key']*12);  
                }
                if($filters['umur_operator']=="!="){
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") !=',$filters['umur_key']*12);    
                }
            }
            if($filters['kedudukan_cb']){
                if(isset($filters['kedudukan']) and $filters['kedudukan'] != "")
                $this->baperjakat_model->where("JENIS_SATKER",$filters['kedudukan']); 
            }
            if($filters['pendidikan_cb']){
                if(isset($filters['tingkat_pendidikan']) and $filters['tingkat_pendidikan'] != "")
                $this->baperjakat_model->where("TK_PENDIDIKAN",$filters['tingkat_pendidikan']); 
            }
            if($filters['kategori_cb']){
                if(isset($filters['kategori']) and $filters['kategori'] != "")
                $this->baperjakat_model->where("KATEGORI",$filters['kategori']); 
            }
            if($filters['tahun_cb']){
                if(isset($filters['tahun_pelantikan']) and $filters['tahun_pelantikan'] != "")
                $this->baperjakat_model->where("date_part('year', \"TGL_PELANTIKAN\") = '".$filters['tahun_pelantikan']."'"); 
            }
            if($filters['tgl_cb']){
                if(isset($filters['tanggal_pelantikan']) and $filters['tanggal_pelantikan'] != "")
                $this->baperjakat_model->where("TGL_PELANTIKAN",$filters['tanggal_pelantikan']); 
            }

        }
        $this->baperjakat_model->where("STATUS_MENTERI",1);
        $datapegwai=$this->baperjakat_model->find_terpilih($periode);
        $this->load->library('Excel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel = PHPExcel_IOFactory::load(trim($this->settings_lib->item('site.pathuploaded')).'template.xls');

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,"Absensi Pelantikan");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,2,"Tanggal ".$filters['tanggal_pelantikan']);

        $objPHPExcel->setActiveSheetIndex(0);
        $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,3,"No");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,3,"NIP");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,3,"Nama");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,3,"Jabatan");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,3,"Tanda Tangan");$col++;

        $row = 4;
        if (isset($datapegwai) && is_array($datapegwai) && count($datapegwai)) :
            $nomor_urut = 1;
            foreach ($datapegwai as $record) :
                $col = 0;
                $type = PHPExcel_Cell_DataType::TYPE_STRING;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$row,$nomor_urut,PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$row,$record->NIP." ",PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,$record->NAMA." ",PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,$record->NAMA_JABATAN.", ".$record->NAMA_UNOR_ESELON_2,PHPExcel_Cell_DataType::TYPE_STRING);
            $row++;
            $nomor_urut ++;
            endforeach;
        endif;
          
        $filename = "penetapan_".$periode."_".mt_rand(1,100000).'.xls'; //just some random filename
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
        //$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
        $objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
        exit; //done.. exiting!
        
    }
}