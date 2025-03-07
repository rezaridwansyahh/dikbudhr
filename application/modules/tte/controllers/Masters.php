<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Masters controller
 */
class Masters extends Admin_Controller
{
    protected $permissionCreate = 'Tte.Masters.Create';
    protected $permissionDelete = 'Tte.Masters.Delete';
    protected $permissionEdit   = 'Tte.Masters.Edit';
    protected $permissionView   = 'Tte.Masters.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->auth->restrict($this->permissionView);
        $this->load->model('tte/tte_model');
        $this->load->model('tte/variable_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('tte/proses_variable_model');
        $this->load->model('tte/tte_master_korektor_model');
        $this->lang->load('tte');
        
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'masters/_sub_nav');

        Assets::add_module_js('tte', 'tte.js');
    }

    /**
     * Display a list of tte data.
     *
     * @return void
     */
    public function index()
    {
         
        
        Template::set('toolbar_title', "Manage Variable TTE");

        Template::render();
    }
    public function variable()
    {
        
        Template::set('toolbar_title', lang('tte_manage'));
        Template::render();
    }
    
    /**
     * Create a tte object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        
         
        Template::set('toolbar_title', lang('tte_action_create'));

        Template::render();
    }
    public function createvariable()
    {
        $this->auth->restrict($this->permissionCreate);
        
         
        Template::set('toolbar_title', "Tambah Variable");

        Template::render();
    }
    public function ajax_data(){
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length');
        $start= $this->input->post('start');

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        
        //$this->tte_model->where("deleted ",null);
        $total= $this->tte_model->count_all();;
        $output=array();
        $output['draw']=$draw;

        
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();


        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        if($search!=""){
            $this->tte_model->where('upper("nama_proses") LIKE \'%'.strtoupper($search).'%\'');
        }
        $this->tte_model->limit($length,$start);
        /*Urutkan dari alphabet paling terkahir*/
        $kolom = $iSortCol != "" ? $iSortCol : "id";
        $sSortCol == "asc" ? "asc" : "desc";
        $this->tte_model->order_by($iSortCol,$sSortCol);
        //$this->tte_model->where("deleted",null);
        $records=$this->tte_model->find_all();

        /*Ketika dalam mode pencarian, berarti kita harus
        'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
        yang mengandung keyword tertentu
        */
        if($search != "")
        {
            $this->tte_model->where('upper("nama_proses") LIKE \'%'.strtoupper($search).'%\'');
            $jum    = $this->tte_model->count_all();
            $output['recordsTotal']=$output['recordsFiltered']=$jum;
        }
        
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->nama_proses;
                $row []  = $record->NAMA;
                $row []  = $record->keterangan_proses;
                
                $btn_actions = array();
                 
                $btn_actions  [] = "<a href='".base_url()."admin/masters/tte/edit/".$record->id."' data-toggle='tooltip' title='Edit data' class='btn btn-sm btn-success'><i class='fa fa-pencil'></i> </a>";
                
                 
                $btn_actions  [] = "<a href='#' kode='".$record->id."'  data-toggle='tooltip' title='Hapus' class='btn btn-hapus btn-sm btn-warning'><i class='fa fa-trash-o'></i> </a>";
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";

                 

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        die();
    }
    public function ajax_datavariable(){
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length');
        $start= $this->input->post('start');

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        
        //$this->tte_model->where("deleted ",null);
        $total= $this->variable_model->count_all();;
        $output=array();
        $output['draw']=$draw;

        
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();


        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        if($search!=""){
            $this->variable_model->where('upper("nama_variable") LIKE \'%'.strtoupper($search).'%\'');
        }
        $this->variable_model->limit($length,$start);
        /*Urutkan dari alphabet paling terkahir*/
        $kolom = $iSortCol != "" ? $iSortCol : "id";
        $sSortCol == "asc" ? "asc" : "desc";
        $this->variable_model->order_by($iSortCol,$sSortCol);
        $records=$this->variable_model->find_all();

        /*Ketika dalam mode pencarian, berarti kita harus
        'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
        yang mengandung keyword tertentu
        */
        if($search != "")
        {
            $this->variable_model->where('upper("nama_variable") LIKE \'%'.strtoupper($search).'%\'');
            $jum    = $this->variable_model->count_all();
            $output['recordsTotal']=$output['recordsFiltered']=$jum;
        }
        
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->label_variable;
                $row []  = $record->nama_variable;
                $row []  = $record->tipe;
                $row []  = $record->keterangan;
            
                $btn_actions = array();
                $btn_actions  [] = "
                    <a href='".base_url()."admin/masters/tte/variableedit/".$record->id."'  data-toggle='tooltip' title='Ubah Data'><span class='fa-stack'>
                        <i class='fa fa-square fa-stack-2x'></i>
                        <i class='fa fa-pencil fa-stack-1x fa-inverse'></i>
                        </span>
                        </a>
                ";

                $btn_actions  [] = "
                        <a href='#' kode='$record->id' class='btn-hapus' data-toggle='tooltip' title='Hapus data' >
                        <span class='fa-stack'>
                        <i class='fa fa-square fa-stack-2x'></i>
                        <i class='fa fa-trash-o fa-stack-1x fa-inverse'></i>
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
    function act_saveproses(){
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
        //$this->load->library('upload');
        $this->load->helper('handle_upload');
        $status = false;
        $msg = "";  
        $post = $this->input->post();
        $penandatangan_sk = $this->input->post('penandatangan_sk');
        $keterangan_proses = $this->input->post('keterangan_proses');
        $kode = $this->input->post('id');
        
        $direktori = trim($this->settings_lib->item('site.pathsk'))."template/";

        if (!file_exists($direktori)) {
            mkdir($direktori, 0755);
        }
            // file SK
            $template_sk = "";
            if (isset($_FILES['template_sk']) && $_FILES['template_sk']['name']) {
                $uploadData = handle_upload('template_sk',$direktori);
                if (isset($uploadData['error']) && !empty($uploadData['error']))
                 {
                    $tipefile = $_FILES['template_sk']['type'];
                    log_activity($this->input->post('nip'), 'Gagal error template_sk: '.$uploadData['error'].$tipefile.$this->input->ip_address(), 'upload SK');
                 }else{
                    $template_sk = $uploadData['data']['file_name'];
                 }
            }
             
            if($penandatangan_sk ==""){
                $status = FALSE;
                $msg = "silahkan isi penandatangan SK";
            }
            $data["nama_proses"]      = $this->input->post('nama_proses');
            if($template_sk != "")
                $data["template_sk"]      = $template_sk;    
            //$data['id_file']           = md5($nama_sk);
            //$data['waktu_buat']                  = date("Y-m-d");
            $data['penandatangan_sk']             = $penandatangan_sk;
            $data['keterangan_proses']             = $keterangan_proses;
            if($kode != ""){
                $id = $this->tte_model->update($kode,$data);
            }else{
                $id = $this->tte_model->insert($data);    
            }
            if($id){
                $status = true;
                $msg = "Berhasil";   
            }else{
                $status = false;
                $msg = "Gagal";   
            }
        // save variable
            if($kode != ""){
                $avariable = $this->input->post('variable');
                $datadel = array('id_proses '=>$kode);
                $this->proses_variable_model->delete_where($datadel);
                foreach ($avariable as $pid) {
                    if($pid != ""){
                        $datavar = array();
                        $datavar["id_variable"]      = $pid;
                        $datavar['id_proses']        = $kode;
                        $id_proses = $this->proses_variable_model->insert($datavar);
                    }
                }
            }
            if($kode != ""){
                $averifikator = $this->input->post('verifikator');
                $datadel = array('id_tte_master_proses '=>$kode);
                $this->tte_master_korektor_model->delete_where($datadel);
                foreach ($averifikator as $pid) {
                    if($pid != ""){
                        $datavar = array();
                        $datavar["id_pegawai_korektor"]      = $pid;
                        $datavar['id_tte_master_proses']        = $kode;
                        $id_proses = $this->tte_master_korektor_model->insert($datavar);
                    }
                }
            }
        $data_json = array(
            'status' => $status,
            'msg' => $msg

        );

        $json_data = json_encode($data_json);
        echo $json_data;
    }
    function act_savevariable(){
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
        //$this->load->library('upload');
        $this->load->helper('handle_upload');
        $status = false;
        $msg = "";  
        $post = $this->input->post();
        $kode = $this->input->post('id');
     
        $data['label_variable']         = $this->input->post('label_variable');
        $data['nama_variable']          = $this->input->post('nama_variable');
        $data['tipe']          = $this->input->post('tipe');
        $data['keterangan']          = $this->input->post('keterangan');
        if($kode != ""){
            $id = $this->variable_model->update($kode,$data);
        }else{
            $id = $this->variable_model->insert($data);    
        }
        
        if($id){
            $status = true;
            $msg = "Berhasil";   
        }else{
            $status = false;
            $msg = "Gagal";   
        }
     
        $data_json = array(
            'status' => $status,
            'msg' => $msg

        );

        $json_data = json_encode($data_json);
        echo $json_data;
    }
    /**
     * Allows editing of tte data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('tte_invalid_id'), 'error');

            redirect(SITE_AREA . '/masters/tte');
        }
        $recordvariables = $this->variable_model->find_all();
        Template::set('recordvariables', $recordvariables);

        $tte = $this->tte_model->find($id);
        $penandatangan_sk = $tte->penandatangan_sk;
        $apenandatangan_sk = $this->pegawai_model->find_by("PNS_ID",$penandatangan_sk);

        $this->proses_variable_model->where("id_proses",$id);
        $proses_variable = $this->proses_variable_model->find_all();
        Template::set('proses_variable', $proses_variable);

        $this->tte_master_korektor_model->where("id_tte_master_proses",$id);
        $verifikators = $this->tte_master_korektor_model->find_all();
        Template::set('verifikators', $verifikators);

        Template::set('tte', $tte);
        Template::set('apenandatangan_sk', $apenandatangan_sk);

        Template::set('toolbar_title', lang('tte_edit_heading'));
        Template::render();
    }
    public function variableedit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('tte_invalid_id'), 'error');

            redirect(SITE_AREA . '/masters/tte/variable');
        }

        $variable = $this->variable_model->find($id);
        Template::set('variable', $variable);
        Template::set('toolbar_title', "Edit Variable");
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
    private function save_tte($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['id'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->tte_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->tte_model->prep_data($this->input->post());

        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->tte_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->tte_model->update($id, $data);
        }

        return $return;
    }
    public function delete()
    {
        $this->auth->restrict($this->permissionDelete);
        $id = $this->input->post('kode');
        $tte = $this->tte_model->find($id);
        $template_sk = $tte->template_sk;
        $direktori = trim($this->settings_lib->item('site.pathsk'))."template/";
        if (file_exists($direktori.$template_sk) and $template_sk != "") {
            unlink($direktori.$template_sk);
        }
        if ($this->tte_model->delete($id)) {
             log_activity($this->auth->user_id(),"Delete data" . ': ' . $id . ' : ' . $this->input->ip_address(), 'tte_model');
             Template::set_message("Delete Master Proses TTE sukses", 'success');
             echo "Sukses";
        }else{
            echo "Gagal";
        }

        exit();
    }
    public function deletevariable()
    {
        $this->auth->restrict($this->permissionDelete);
        $id = $this->input->post('kode');
        
        if ($this->variable_model->delete($id)) {
             log_activity($this->auth->user_id(),"Delete data Variable " . ': ' . $id . ' : ' . $this->input->ip_address(), 'variable_tte');
             Template::set_message("Delete Master Variable TTE sukses", 'success');
             echo "Sukses";
        }else{
            echo "Gagal";
        }

        exit();
    }
    public function getdatavariable()
    {
        $json = array(); 
        $records = $this->variable_model->find_all();
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) :
                $json['id'][] = $record->id;
                $json['text'][] = $record->label_variable;
            endforeach;
        endif;
        echo json_encode($json);
        die();
    }
}