<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Arsip controller
 */
class Arsip extends Admin_Controller
{
    protected $permissionCreate = 'Arsip_digital.Arsip.Create';
    protected $permissionDelete = 'Arsip_digital.Arsip.Delete';
    protected $permissionEdit   = 'Arsip_digital.Arsip.Edit';
    protected $permissionView   = 'Arsip_digital.Arsip.View';
    protected $permissionViewAll   = 'Arsip_digital.Arsip.ViewAll';
    protected $permissionValidasi   = 'Arsip_digital.Arsip.Validasi';
    
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
        $this->auth->restrict($this->permissionViewAll);   
        Template::set('toolbar_title', lang('arsip_digital_manage'));

        Template::render();
    }
    
    /**
     * Create a arsip digital object.
     *
     * @return void
     */
    public function create()
    {
        $PNS_ID = $this->uri->segment(5);
        $this->auth->restrict($this->permissionCreate);

        $settingarsip = $this->settings_lib->find_all_by('module', 'arsip');
        Template::set('settingarsip', $settingarsip);
        $this->pegawai_model->where("PNS_ID",$PNS_ID);
        $pegawai_data = $this->pegawai_model->find_first_row();
        Template::set('NIP', $pegawai_data->NIP_BARU);
        Template::set('toolbar_title', lang('arsip_digital_action_create'));

        Template::render();
    }
    public function createadm()
    {
        $PNS_ID = $this->uri->segment(5);
        $this->auth->restrict($this->permissionCreate);

        Template::set('toolbar_title', lang('arsip_digital_action_create'));

        Template::render();
    }
    /**
     * Allows editing of arsip digital data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        $settingarsip = $this->settings_lib->find_all_by('module', 'arsip');
        Template::set('settingarsip', $settingarsip);
        $this->auth->restrict($this->permissionEdit);
        if (empty($id)) {
            Template::set_message(lang('arsip_digital_invalid_id'), 'error');

            redirect(SITE_AREA . '/arsip/arsip_digital');
        }
        $datadetil = $this->arsip_digital_model->find($id);
        Template::set('arsip_digital', $datadetil);

        Template::set('toolbar_title', lang('arsip_digital_edit_heading'));
        Template::render();
    }
    public function validasi()
    {
        $id = $this->uri->segment(5);
        $this->auth->restrict($this->permissionValidasi);
        if (empty($id)) {
            Template::set_message(lang('arsip_digital_invalid_id'), 'error');

            redirect(SITE_AREA . '/arsip/arsip_digital');
        }
        $datadetil = $this->arsip_digital_model->find($id);
        Template::set('arsip_digital', $datadetil);

        Template::set('toolbar_title', lang('arsip_digital_edit_heading'));
        Template::render();
    }
    public function editadm()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('arsip_digital_invalid_id'), 'error');

            redirect(SITE_AREA . '/arsip/arsip_digital');
        }
        $datadetil = $this->arsip_digital_model->find($id);

        $pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($datadetil->NIP));
        Template::set("pegawai",$pegawai);
        Template::set('arsip_digital', $datadetil);

        Template::set('toolbar_title', lang('arsip_digital_edit_heading'));
        Template::render();
    }
    public function viewdoc($id)
    {
        if (empty($id)) {
            Template::set_message(lang('arsip_digital_invalid_id'), 'error');
            redirect(SITE_AREA . '/arsip/arsip_digital');
        }
        $datadetil = $this->arsip_digital_model->find($id);
        $FILE_BASE64 = $datadetil->FILE_BASE64;
        echo '<embed src="'.$FILE_BASE64.'" width="100%" height="700" alt="pdf">';
        die();
    }
    public function download($id)
    {
        if (empty($id)) {
            Template::set_message(lang('arsip_digital_invalid_id'), 'error');
            redirect(SITE_AREA . '/arsip/arsip_digital');
        }
        log_activity($this->auth->user_id(), 'Download Dokumen id : ' . $id . ' : ' . $this->input->ip_address(), 'arsip_digital');

        $datadetil = $this->arsip_digital_model->find($id);
        $FILE_BASE64 = $datadetil->FILE_BASE64;
        $JENIS_FILE = $datadetil->JENIS_FILE;
        $EXTENSION_FILE = $datadetil->EXTENSION_FILE;
        $FILE_SIZE = $datadetil->FILE_SIZE;
        header('Content-Description: File Transfer');
        header("Content-type: ".$JENIS_FILE);
        header('Content-Disposition: attachment; filename=download.'.  $EXTENSION_FILE);  
        header('Content-Length: ' . $FILE_SIZE);
        readfile($FILE_BASE64);

        exit; 
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
    private function save_arsip_digital($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['ID'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->arsip_digital_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->arsip_digital_model->prep_data($this->input->post());

        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->arsip_digital_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->arsip_digital_model->update($id, $data);
        }

        return $return;
    }
    public function getdataall(){
        $this->auth->restrict($this->permissionViewAll);
        
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/sk/arsip_digital');
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
            if($filters['nama_cb']){
                $this->arsip_digital_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");    
            }
            if($filters['nip_cb']){
                $this->arsip_digital_model->like('upper(pegawai."NIP_BARU")',strtoupper($filters['nip_key']),"BOTH"); 
            }
            if($filters['jenis_arsip']){
                $this->arsip_digital_model->where('ID_JENIS_ARSIP',$filters['jenis_arsip']); 
            }
        }

        $output=array();
        $output['draw']=$draw;
        $total= $this->arsip_digital_model->count_all();
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->arsip_digital_model->order_by("pegawai.NAMA",$order['dir']);
            }
            if($order['column']==2){
                $this->arsip_digital_model->order_by("ID_JENIS_ARSIP",$order['dir']);
            }
            
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            if($filters['nama_cb']){
                $this->arsip_digital_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");    
            }
            if($filters['nip_cb']){
                $this->arsip_digital_model->like('upper(pegawai."NIP_BARU")',strtoupper($filters['nip_key']),"BOTH"); 
            }
            if($filters['jenis_arsip']){
                $this->arsip_digital_model->where('ID_JENIS_ARSIP',$filters['jenis_arsip']); 
            }
        }
        $this->arsip_digital_model->limit($length,$start);
        $records = $this->arsip_digital_model->find_all();
        
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = "<b><a href='".base_url()."admin/kepegawaian/pegawai/profilen/".$record->ID_PEGAWAI."'>".$record->NIP."</a></b><br>".$record->NAMA;
                $row []  = "<b>".$record->NAMA_JENIS."</b>";
                $row []  = $record->KETERANGAN."<br><i>".$record->JENIS_FILE." ".$this->formatSizeUnits($record->FILE_SIZE)."</i>"; 
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/arsip/arsip_digital/viewdoc/".$record->ID."' data-toggle='tooltip' title='Lihat Dokumen' tooltip='".$record->NAMA_JENIS." - ".$record->KETERANGAN."' class='btn btn-sm btn-info show-modal'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                $btn_actions  [] = "<a href='".base_url()."admin/arsip/arsip_digital/download/".$record->ID."' data-toggle='tooltip' title='Download Dokumen' class='btn btn-sm btn-warning'><i class='glyphicon glyphicon-download'></i> </a>";
                if($this->auth->has_permission($this->permissionEdit))
                {    
                    $btn_actions  [] = "<a href='".base_url()."admin/arsip/arsip_digital/editadm/".$record->ID."' data-toggle='tooltip' title='Edit Dokumen' tooltip='Edit Dokumen' class='btn btn-sm btn-success show-modal'><i class='fa fa-edit'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionDelete))
                {    
                    $btn_actions  [] = "<a href='#' kode='$record->ID' data-toggle='tooltip' title='Hapus Dokumen' class='btn btn-sm btn-danger btn-hapus'><i class='fa fa-trash-o'></i> </a>";
                }

                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);   
    }
    public function ajax_list(){
        $this->auth->restrict($this->permissionView);
        
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            die("Hanya request ajax");
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        $PNS_ID = $this->input->post('PNS_ID');

        $this->pegawai_model->where("PNS_ID",$PNS_ID);
        $pegawai_data = $this->pegawai_model->find_first_row();
        $nip = $pegawai_data->NIP_BARU;
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
            
            if($filters['jenis_arsip']){
                $this->arsip_digital_model->where('ID_JENIS_ARSIP',$filters['jenis_arsip']); 
            }
        }

        $output=array();
        $output['draw']=$draw;
        $this->arsip_digital_model->where("NIP",$nip);
        $total= $this->arsip_digital_model->count_all_pegawai();
        $orders = $this->input->post('order');
        foreach($orders as $order){
            
            if($order['column']==2){
                $this->arsip_digital_model->order_by("ID_JENIS_ARSIP",$order['dir']);
            }
            
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            if($filters['nama_cb']){
                $this->arsip_digital_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");    
            }
            if($filters['nip_cb']){
                $this->arsip_digital_model->like('upper(pegawai."NIP_BARU")',strtoupper($filters['nip_key']),"BOTH"); 
            }
            if($filters['jenis_arsip']){
                $this->arsip_digital_model->where('ID_JENIS_ARSIP',$filters['jenis_arsip']); 
            }
        }
        $this->arsip_digital_model->limit($length,$start);
        $this->arsip_digital_model->where("NIP",$nip);
        $records = $this->arsip_digital_model->find_all_pegawai();
        
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $isvalid = "<small class='label bg-red'>Blm Valid</small>";
                if($record->ISVALID == "1"){
                    $isvalid = "<small class='label  bg-green'>Valid</small>";
                }
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = "<b>".$record->NAMA_JENIS."</b>";
                $row []  = $record->KETERANGAN."<br><i>".$record->JENIS_FILE." ".$this->formatSizeUnits($record->FILE_SIZE)."</i>"; 
                $row []  = $isvalid;
                $btn_actions = array();
                $btn_actions2 = array();
                $btn_actions  [] = "<a href='".base_url()."admin/arsip/arsip_digital/viewdoc/".$record->ID."' data-toggle='tooltip' title='Lihat Dokumen' tooltip='".$record->NAMA_JENIS." - ".$record->KETERANGAN."' class='btn btn-sm btn-info modal-custom-global'><i class='glyphicon glyphicon-eye-open'></i> </a>";

                $btn_actions  [] = "<a href='".base_url()."admin/arsip/arsip_digital/download/".$record->ID."' data-toggle='tooltip' title='Download Dokumen' class='btn btn-sm btn-warning'><i class='glyphicon glyphicon-download'></i> </a>";
                if($this->auth->has_permission($this->permissionEdit))
                {    
                    $btn_actions2  [] = "<a href='".base_url()."admin/arsip/arsip_digital/edit/".$record->ID."' data-toggle='tooltip' title='Edit Dokumen' tooltip='Edit Dokumen' class='btn btn-sm btn-success modal-custom-global'><i class='fa fa-edit'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionDelete))
                {    
                    $btn_actions2  [] = "<a href='#' kode='$record->ID' data-toggle='tooltip' title='Hapus Dokumen' class='btn btn-sm btn-danger btn-hapus_arsip'><i class='fa fa-trash-o'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionValidasi))
                {    
                    $btn_actions2  [] = "<a href='".base_url()."admin/arsip/arsip_digital/validasi/".$record->ID."' data-toggle='tooltip' title='Validasi Dokumen' tooltip='Validasi Dokumen' class='btn btn-sm btn-success modal-custom-global'><i class='fa fa-key'></i> </a>";
                }
                

                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>
                        <div class='btn-group'>".implode(" ",$btn_actions2)."</div>";

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);   
    }
    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }
        return $bytes;
    }
    public function act_save()
    {
        $this->auth->restrict($this->permissionCreate);
        $id_data = $this->input->post("ID");   
        $NIP = $this->input->post('NIP');
        $this->form_validation->set_rules('NIP','NIP','required|max_length[23]');
        $this->form_validation->set_rules('ID_JENIS_ARSIP','Jenis Dokumen','required|max_length[10]');
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = "
            <div class='alert alert-block alert-error fade in note note-danger'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                    Ada kesalahan
                </h4>
                ".validation_errors()."
            </div>
            ";
            echo json_encode($response);
            exit();
        }
         
        // Make sure we only pass in the fields we want
        $base64     = "";
        $file_ext   = "";
        $file_size  = "";
        $file_tmp   = "";
        $type       = "";
        if (isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['name']) 
        {
            $errors=array();
            $allowed_ext = array('jpg','jpeg','png','gif','bpm','tiff','tif','pdf','doc','xls','xlsx','ppt','pptx','docx','zip','rar');
            $file_name =$_FILES['file_dokumen']['name'];
            // $file_name =$_FILES['image']['tmp_name'];
            $file_ext   = explode('.',$file_name);
            $jmltitik   = count($file_ext);
            $file_size  = $_FILES['file_dokumen']['size'];
            $file_tmp   = $_FILES['file_dokumen']['tmp_name'];
            $type       = $_FILES['file_dokumen']['type'];
            //echo $file_ext[1];echo "<br>";
            $data = file_get_contents($file_tmp);
            $base64 = 'data:' . $type . ';base64,' . base64_encode($data);

            if(in_array(end($file_ext),$allowed_ext) === false)
            {
                $errors[]='Extension not allowed';
                $response['msg'] = "
                <div class='alert alert-block alert-error fade in note note-danger'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Ada kesalahan
                    </h4>
                    <p>Extension not allowed</p>
                </div>
                ";
                echo json_encode($response);
                exit();
            }
            if($file_size > 50097152)
            {
                $errors[]= 'File size must be under 2mb';
                $response['msg'] = "
                <div class='alert alert-block alert-error fade in note note-danger'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Ada kesalahan
                    </h4>
                    <p>File size must be under 50Mb</p>
                </div>
                ";
                echo json_encode($response);
                exit();
            }
        }
        $data = $this->arsip_digital_model->prep_data($this->input->post());
        $ISVALID = $this->input->post('ISVALID');
        $data['NIP']  = $this->input->post('NIP');
        $data['KETERANGAN']    = $this->input->post('KETERANGAN') ? $this->input->post('KETERANGAN') : null;
        if($base64 == "")
            unset($data['FILE_BASE64']);
        else{
            $data['FILE_BASE64']   = $base64;
            $data['EXTENSION_FILE'] = isset($file_ext[$jmltitik-1]) ? $file_ext[$jmltitik-1] : null;
            $data['JENIS_FILE']     = $type;
            $data['FILE_SIZE']      = $file_size;
        }

        
        $return = false;
        $msg = "";
        $this->arsip_digital_model->skip_validation(true);
        if(isset($id_data) && !empty($id_data)){
            $data['UPDATED_DATE']    = date("Y-m-d");
            $data['UPDATED_BY']    = $this->auth->user_id();
            $this->arsip_digital_model->update($id_data, $data);
            $msg = "Berhasil update data";

            log_activity($this->auth->user_id(), 'Update Dokumen : ' . $id_data . ' : ' . $this->input->ip_address(), 'arsip_digital');
        }
        else{
            $data['CREATED_DATE']    = date("Y-m-d");
            $data['CREATED_BY']    = $this->auth->user_id();

            $id = $this->arsip_digital_model->insert($data);
            $msg = "Berhasil tambah arsip";

            log_activity($this->auth->user_id(), 'Upload Dokumen : ' . $id . ' : ' . $this->input->ip_address(), 'arsip_digital');
        } 
        $response ['success']= true;
        $response ['msg']= $msg;
        echo json_encode($response);
    }
    public function act_save_validasi()
    {
        $this->auth->restrict($this->permissionCreate);
        $id_data = $this->input->post("ID");   
        $NIP = $this->input->post('NIP');
        $this->form_validation->set_rules('NIP','NIP','required|max_length[23]');
        $this->form_validation->set_rules('ID_JENIS_ARSIP','Jenis Dokumen','required|max_length[10]');
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = "
            <div class='alert alert-block alert-error fade in note note-danger'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                    Ada kesalahan
                </h4>
                ".validation_errors()."
            </div>
            ";
            echo json_encode($response);
            exit();
        }
         
        // Make sure we only pass in the fields we want
        $base64     = "";
        $file_ext   = "";
        $file_size  = "";
        $file_tmp   = "";
        $type       = "";
        if (isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['name']) 
        {
            $errors=array();
            $allowed_ext = array('jpg','jpeg','png','gif','bpm','tiff','tif','pdf','doc','xls','xlsx','ppt','pptx','docx','zip','rar');
            $file_name =$_FILES['file_dokumen']['name'];
            // $file_name =$_FILES['image']['tmp_name'];
            $file_ext   = explode('.',$file_name);
            $jmltitik   = count($file_ext);
            $file_size  = $_FILES['file_dokumen']['size'];
            $file_tmp   = $_FILES['file_dokumen']['tmp_name'];
            $type       = $_FILES['file_dokumen']['type'];
            //echo $file_ext[1];echo "<br>";
            $data = file_get_contents($file_tmp);
            $base64 = 'data:' . $type . ';base64,' . base64_encode($data);

            if(in_array(end($file_ext),$allowed_ext) === false)
            {
                $errors[]='Extension not allowed';
                $response['msg'] = "
                <div class='alert alert-block alert-error fade in note note-danger'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Ada kesalahan
                    </h4>
                    <p>Extension not allowed</p>
                </div>
                ";
                echo json_encode($response);
                exit();
            }
            if($file_size > 50097152)
            {
                $errors[]= 'File size must be under 2mb';
                $response['msg'] = "
                <div class='alert alert-block alert-error fade in note note-danger'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Ada kesalahan
                    </h4>
                    <p>File size must be under 50Mb</p>
                </div>
                ";
                echo json_encode($response);
                exit();
            }
        }
        $data = $this->arsip_digital_model->prep_data($this->input->post());
        $ISVALID = $this->input->post('ISVALID');
        $data['NIP']  = $this->input->post('NIP');
        $data['KETERANGAN']    = $this->input->post('KETERANGAN') ? $this->input->post('KETERANGAN') : null;
        if($base64 == "")
            unset($data['FILE_BASE64']);
        else{
            $data['FILE_BASE64']   = $base64;
            $data['EXTENSION_FILE'] = isset($file_ext[$jmltitik-1]) ? $file_ext[$jmltitik-1] : null;
            $data['JENIS_FILE']     = $type;
            $data['FILE_SIZE']      = $file_size;
        }

        if($ISVALID == "")
            $data['ISVALID']   = 0;
        else{
            $data['ISVALID']   = $ISVALID;
        }

        $return = false;
        $msg = "";
        $this->arsip_digital_model->skip_validation(true);
        if(isset($id_data) && !empty($id_data)){
            $data['UPDATED_DATE']    = date("Y-m-d");
            $data['UPDATED_BY']    = $this->auth->user_id();
            $this->arsip_digital_model->update($id_data, $data);
            $msg = "Berhasil update data";

            log_activity($this->auth->user_id(), 'Validasi Dokumen : ' . $id_data . ' : ' . $this->input->ip_address(), 'arsip_digital');
        }
        else{
            $data['CREATED_DATE']    = date("Y-m-d");
            $data['CREATED_BY']    = $this->auth->user_id();

            $id = $this->arsip_digital_model->insert($data);
            $msg = "Berhasil tambah arsip";
            log_activity($this->auth->user_id(), 'Validasi Dokumen : ' . $id . ' : ' . $this->input->ip_address(), 'arsip_digital');
        } 
        $response ['success']= true;
        $response ['msg']= $msg;

        

        echo json_encode($response);
    }
    public function deletedata()
    {
        $this->auth->restrict($this->permissionDelete);
        $id     = $this->input->post('kode');
        if ($this->arsip_digital_model->delete($id)) {
             log_activity($this->auth->user_id(), 'Hapus arsip digital : ' . $id . ' : ' . $this->input->ip_address(), 'arsip_digital');
             Template::set_message("Berhasil Hapus dokumen", 'success');
             echo "Sukses";
        }else{
            echo "Gagal";
        }

        exit();
    }
}