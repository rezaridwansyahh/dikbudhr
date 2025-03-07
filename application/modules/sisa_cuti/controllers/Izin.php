<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Izin controller
 */
class Izin extends Admin_Controller
{
    protected $permissionCreate = 'Sisa_cuti.Izin.Create';
    protected $permissionDelete = 'Sisa_cuti.Izin.Delete';
    protected $permissionEdit   = 'Sisa_cuti.Izin.Edit';
    protected $permissionView   = 'Sisa_cuti.Izin.View';
    protected $permissionFiltersatker   = 'Pegawai.Kepegawaian.Filtersatker';
    public $SATKER_ID = null;
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->auth->restrict($this->permissionView);
        $this->load->model('sisa_cuti/sisa_cuti_model');
        $this->load->model('pegawai/pegawai_model');
        $this->lang->load('sisa_cuti');
        
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'izin/_sub_nav');

        Assets::add_module_js('sisa_cuti', 'sisa_cuti.js');
        // filter untuk role yang filtersatkernya aktif
        if($this->auth->has_permission($this->permissionFiltersatker)){
            $this->SATKER_ID = $this->pegawai_model->getunor_induk($this->auth->username());
        }
    }

    /**
     * Display a list of sisa cuti data.
     *
     * @return void
     */
    public function index()
    {
        
        
        Template::set('toolbar_title', lang('sisa_cuti_manage'));

        Template::render();
    }
    
    /**
     * Create a sisa cuti object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        
        if (isset($_POST['save'])) {
            if ($insert_id = $this->save_sisa_cuti()) {
                log_activity($this->auth->user_id(), lang('sisa_cuti_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'sisa_cuti');
                Template::set_message(lang('sisa_cuti_create_success'), 'success');

                redirect(SITE_AREA . '/izin/sisa_cuti');
            }

            // Not validation error
            if ( ! empty($this->sisa_cuti_model->error)) {
                Template::set_message(lang('sisa_cuti_create_failure') . $this->sisa_cuti_model->error, 'error');
            }
        }

        Template::set('toolbar_title', lang('sisa_cuti_action_create'));

        Template::render();
    }
    /**
     * Allows editing of sisa cuti data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('sisa_cuti_invalid_id'), 'error');

            redirect(SITE_AREA . '/izin/sisa_cuti');
        }
        $data_detil = $this->sisa_cuti_model->find($id);
        $selectedpegawai = $this->pegawai_model->find_by("NIP_BARU",$data_detil->PNS_NIP);
        Template::set('selectedpegawai', $selectedpegawai);

        Template::set('sisa_cuti', $data_detil);

        Template::set('toolbar_title', lang('sisa_cuti_edit_heading'));
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
    private function save_sisa_cuti($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['ID'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->sisa_cuti_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->sisa_cuti_model->prep_data($this->input->post());

        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->sisa_cuti_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->sisa_cuti_model->update($id, $data);
        }

        return $return;
    }
    public function getdata_sisa_cuti(){
        $this->auth->restrict($this->permissionView);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/izin/sisa_cuti');
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
             
            if($filters['nama_key']){
                $this->sisa_cuti_model->like('upper(pegawai."NAMA")',strtoupper(trim($filters['nama_key'])),"BOTH"); 
            }
            if($filters['nip_key']){
                $this->sisa_cuti_model->like('upper("PNS_NIP")',strtoupper(trim($filters['nip_key'])),"BOTH");  
            }
        }
        
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total= $this->sisa_cuti_model->count_all($this->SATKER_ID);
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->sisa_cuti_model->order_by("PNS_NIP",$order['dir']);
            }
            if($order['column']==2){
                $this->sisa_cuti_model->order_by("NAMA",$order['dir']);
            }
            if($order['column']==3){
                $this->sisa_cuti_model->order_by("TAHUN",$order['dir']);
            }
            if($order['column']==4){
                $this->sisa_cuti_model->order_by("SISA",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->sisa_cuti_model->limit($length,$start);
        $records=$this->sisa_cuti_model->find_all($this->SATKER_ID);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->PNS_NIP;
                $row []  = $record->NAMA;
                $row []  = $record->TAHUN;

                $row []  = $record->SISA_N;
                $row []  = $record->SISA_N_1;
                $row []  = $record->SISA_N_2;
                $row []  = $record->SISA;
                $SUDAH_DIAMBIL = $record->SUDAH_DIAMBIL;
                if((int)$record->SUDAH_DIAMBIL > (int)$record->V_SUDAH_DIAMBIL)
                    $row []  = $record->SUDAH_DIAMBIL;
                else{
                    $SUDAH_DIAMBIL = $record->V_SUDAH_DIAMBIL;
                    $row []  = $record->V_SUDAH_DIAMBIL;
                }
                $row []  = (int)$record->SISA - (int)$SUDAH_DIAMBIL;
                $btn_actions = array();
                if($this->auth->has_permission($this->permissionEdit)){
                    $btn_actions  [] = "<a href='".base_url()."admin/izin/sisa_cuti/edit/".$record->ID."' class='btn btn-sm btn-warning show-modal'title='Edit Sisa Cuti' tooltip='Edit Sisa Cuti'><i class='glyphicon glyphicon-edit'></i> </a>";  
                }
                if($this->auth->has_permission($this->permissionDelete)){
                    $btn_actions  [] = "<a kode='$record->ID' class='btn btn-sm btn-danger btn-hapus' data-toggle='tooltip' data-placement='top' data-original-title='Hapus data' title='Hapus data' tooltip='Hapus'><i class='glyphicon glyphicon-remove'></i> </a>";
                }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    public function save(){
         // Validate the data
        $this->form_validation->set_rules($this->sisa_cuti_model->get_validation_rules());
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
        
        $PNS_NIP = $this->input->post("PNS_NIP");
        $TAHUN = $this->input->post("TAHUN");
        $id_data = $this->input->post("ID");
        $this->sisa_cuti_model->where("sisa_cuti.TAHUN",$TAHUN);
        $data_cuti = $this->sisa_cuti_model->find_by("PNS_NIP",$PNS_NIP);
        if($data_cuti->ID != "" && $data_cuti->ID != $id_data){
            $response ['success']= false;
            $response['msg'] = "
            <div class='alert alert-block alert-error fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                    Data sisa cuti Pegawai tahun ".$TAHUN." sudah ada
                ".validation_errors()."
            </div>
            ";
            echo json_encode($response); 
            die();   
        }
        $data_pegawai = $this->pegawai_model->find_by("NIP_BARU",$PNS_NIP);
        $NAMA_PEGAWAI = isset($data_pegawai->NAMA) ? $data_pegawai->NAMA : "";
        $data = $this->sisa_cuti_model->prep_data($this->input->post());
        $data['NAMA'] = $NAMA_PEGAWAI;

        $data['SISA_N'] = (int)$this->input->post("SISA_N");
        $data['SISA_N_1'] = (int)$this->input->post("SISA_N_1");
        $data['SISA_N_2'] = (int)$this->input->post("SISA_N_2");
        $data['SISA'] = (int)$this->input->post("SISA");
        $data['SUDAH_DIAMBIL'] = (int)$this->input->post("SUDAH_DIAMBIL");
        
        if(isset($id_data) && !empty($id_data)){
            $this->sisa_cuti_model->update($id_data,$data);
            log_activity($this->auth->user_id(), 'Edit data sisa cuti : ' . $id_data . ' : ' . $this->input->ip_address(), 'sisa_cuti');    
        }
        else{
            if($insert_id = $this->sisa_cuti_model->insert($data)){
                log_activity($this->auth->user_id(), 'Save data sisa cuti : ' . $insert_id . ' : ' . $this->input->ip_address(), 'sisa_cuti');    
            }
        } 
        $response ['success']= true;
        $response ['msg']= "Berhasil";
        echo json_encode($response);    

    }
    public function delete_sisa_cuti()
    {
        $this->auth->restrict($this->permissionDelete);
        $id     = $this->input->post('kode');
        if ($this->sisa_cuti_model->delete($id)) {
             log_activity($this->auth->user_id(), 'delete data sisa cuti : ' . $id . ' : ' . $this->input->ip_address(), 'sisa_cuti');
             Template::set_message("Sukses Hapus data", 'success');
             echo "Sukses";
        }else{
            echo "Gagal";
        }

        exit();
    }
}