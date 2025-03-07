<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Reports controller
 */
class Masters extends Admin_Controller
{
    protected $permissionCreate = 'Petajabatan.Masters.Create';
    protected $permissionDelete = 'Petajabatan.Masters.Delete';
    protected $permissionEdit   = 'Petajabatan.Masters.Edit';
    protected $permissionView   = 'Petajabatan.Masters.View';
    public $default_permen = null;
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('petajabatan/kuotajabatan_model');
        $this->load->model('petajabatan/permen_model');
        $this->load->model('petajabatan/request_formasi_model');
        $this->auth->restrict($this->permissionView);
        $this->lang->load('petajabatan');
        $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        Template::set_block('sub_nav', 'reports/_sub_nav');
        Assets::add_module_js('petajabatan', 'petajabatan.js');
        $record_permens = $this->permen_model->find_all();
        Template::set("record_permens",$record_permens);
        $this->default_permen = trim($this->settings_lib->item('peta.permen'));
        Template::set("permen",$this->default_permen);
    }

    /**
     * Display a list of petajabatan data.
     *
     * @return void
     */
    public function index()
    {
        
    	Template::set('toolbar_title', "Peta Jabatan");
		Template::set("collapse",true);
        Template::render();
    }
    public function satker()
    {
        $id = $this->uri->segment(5);
        Template::set("id_satker",$id);
        $permen = $this->input->get('permen');
        $this->load->model('pegawai/unitkerja_model');
        $data_unit = $this->unitkerja_model->find($id);
        Template::set("data_unit",$data_unit);
        Template::set("permen",$permen);
        Template::set('toolbar_title', "Peta Jabatan ".$data_unit->NAMA_UNOR);
        Template::set("collapse",true);

        Template::render();
    }
    public function permen()
    {

        Template::set('toolbar_title', "Permen Peta Jabatan");
        Template::set("collapse",true);
        Template::set_view("permen/index");
        Template::render();
    }
    public function create_permen()
    {
        if (isset($_POST['save'])) {
            $this->auth->restrict($this->permissionEdit);

            if ($this->save_permen()) {
                log_activity($this->auth->user_id(), "Save Permen Sukses" . ': ' . $id . ' : ' . $this->input->ip_address(), 'permen');
                Template::set_message("Save Permen Sukses", 'success');
                redirect(SITE_AREA . '/masters/petajabatan/permen');
            }

            // Not validation error
            if ( ! empty($this->permen_model->error)) {
                Template::set_message("Ada kesalahan" . $this->permen_model->error, 'error');
            }
        }
        Template::set('toolbar_title', "Tambah Permen Petajabatan");
        Template::set_view("permen/create");
        Template::render();
    }
    public function edit_permen()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message("ID tidak ditemukan", 'error');
            redirect(SITE_AREA . '/masters/petajatan/permen');
        }
        if (isset($_POST['save'])) {
            $this->auth->restrict($this->permissionEdit);

            if ($this->save_permen('update', $id)) {
                log_activity($this->auth->user_id(), "Update Permen Sukses" . ': ' . $id . ' : ' . $this->input->ip_address(), 'permen');
                Template::set_message("Update Permen Sukses", 'success');
                redirect(SITE_AREA . '/masters/petajabatan/permen');
            }

            // Not validation error
            if ( ! empty($this->permen_model->error)) {
                Template::set_message("Gagal " . $this->permen_model->error, 'error');
            }
        }
        Template::set('permen', $this->permen_model->find($id));

        Template::set('toolbar_title', "Edit Permen Petajabatan");
        Template::set_view("permen/edit");
        Template::render();
    }
    public function kosong()
    {
    	Template::set('toolbar_title', "Peta Jabatan Kosong");
		Template::set("collapse",true);
        Template::render();
    }
    public function getdata(){
        $this->auth->restrict($this->permissionView);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai');
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        $id_satker = $this->input->post('id_satker');
        $permen = $this->input->post('permen');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

        $this->db->start_cache();
            if($id_satker != ""){
                $this->db->group_start();
                $this->db->where('vw_unit_list."UNOR_INDUK"',$id_satker);    
                // $this->db->or_where('vw_unit_list."ESELON_1"',$id_satker);   
                // $this->db->or_where('vw_unit_list."ESELON_2"',$id_satker);   
                // $this->db->or_where('vw_unit_list."ESELON_3"',$id_satker);
                $this->db->group_end();
            }
            if($permen != ""){
                $peraturan = trim($permen);
            }else{
                if(trim($this->settings_lib->item('peta.permen')) != ""){
                    $peraturan = trim($this->default_permen);
                }
            }

            $this->db->like('kuota_jabatan."PERATURAN"',trim($peraturan),"BOTH");
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total= $this->kuotajabatan_model->count_all($this->UNOR_ID);
        $orders = $this->input->post('order');
        $this->kuotajabatan_model->order_by("KODE_UNIT_KERJA","ASC");
        foreach($orders as $order){
            if($order['column']==3){
                $this->kuotajabatan_model->order_by("ID_JABATAN",$order['dir']);
            }
             
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->kuotajabatan_model->limit($length,$start);
        $records=$this->kuotajabatan_model->find_all($this->UNOR_ID);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->NAMA_UNOR_FULL;
                $row []  = $record->NAMA_JABATAN;
                $row []  = $record->JUMLAH_PEMANGKU_JABATAN;
                $row []  = $record->PERATURAN;
                $btn_actions = array();
                 
                
                if($this->auth->has_permission("Petajabatan.Masters.Edit")){
                $btn_actions  [] = "<a href='".base_url()."admin/masters/petajabatan/editkuota/".$record->ID."' class='btn btn-sm btn-warning' title='Update data'><i class='glyphicon glyphicon-edit'></i> </a>";
                }
                if($this->auth->has_permission("Petajabatan.Masters.Delete")){
                    $btn_actions  [] = "<a kode='$record->ID' class='btn btn-sm btn-danger btn-hapus' data-toggle='tooltip' data-placement='top' data-original-title='Hapus data' title='Hapus data' tooltip='Hapus'><i class='glyphicon glyphicon-remove'></i> </a>";
                }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    public function getdata_satker(){
        $this->auth->restrict($this->permissionView);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/masters/petajabatan/satker');
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";
        $peraturan = "";
        $this->db->start_cache();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            if($filters['unit_id_key']){
                $this->db->group_start();
                $this->db->where('unitkerja."ID"',$filters['unit_id_key']);    
                // $this->db->or_where('vw_unit_list."ESELON_1"',$filters['unit_id_key']);   
                // $this->db->or_where('vw_unit_list."ESELON_2"',$filters['unit_id_key']);   
                // $this->db->or_where('vw_unit_list."ESELON_3"',$filters['unit_id_key']);   
                // $this->db->or_where('vw_unit_list."ESELON_4"',$filters['unit_id_key']);   
                $this->db->group_end();
            }
            if($filters['peraturan']){
                $peraturan = trim($filters['peraturan']);
                // $this->db->like('"kuota_jabatan.PERATURAN"',trim($filters['peraturan']),"BOTH");    
            }else{
                if(trim($this->settings_lib->item('peta.permen')) != ""){
                    $peraturan = trim($this->default_permen);
                    // $this->db->like('"kuota_jabatan.PERATURAN"',trim($this->default_permen),"BOTH");    
                }
            }

            $this->db->like('kuota_jabatan."PERATURAN"',trim($peraturan),"BOTH");
        }
        
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total = count($this->kuotajabatan_model->find_all_satker($this->UNOR_ID));
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==3){
                $this->kuotajabatan_model->order_by("NAMA_UNOR",$order['dir']);
            }
             
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->kuotajabatan_model->limit($length,$start);
        $records=$this->kuotajabatan_model->find_all_satker($this->UNOR_ID);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->NAMA_UNOR;
                $row []  = $record->jumlah_jabatan;
                $row []  = $record->jml_kuota;
                $btn_actions = array();
                 
                $btn_actions  [] = "<a href='".base_url()."admin/masters/petajabatan/satker/".$record->ID."?permen=".urlencode($peraturan)."' class='btn btn-sm btn-warning show-modal' title='Update data'><i class='fa fa-eye'></i> </a>";
        
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    public function getquota()
    {
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
        $unitkerja = $this->input->post('unitkerja');
        $jabatan_instansi = $this->input->post('jabatan_instansi');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/unitkerja_model');
        
        $kuotajabatan = $this->kuotajabatan_model->find_by_jabatan($unitkerja,$jabatan_instansi);
        Template::set('kuotajabatan', $kuotajabatan);

        $recordpegawaiquota = $this->pegawai_model->get_count_jabatan_instansi_unor($unitkerja,$jabatan_instansi);
        //echo $unitkerja." - ".$jabatan_instansi;
        $akuota[] = array(); 
        if (isset($recordpegawaiquota) && is_array($recordpegawaiquota) && count($recordpegawaiquota)):
            foreach($recordpegawaiquota as $record):
                $akuota[trim($record->UNOR_ID)]    = trim($record->jumlah);
            endforeach;
        endif;
        //print_r($akuota);
        $output = $this->load->view('reports/contentquota',array('kuotajabatan'=>$kuotajabatan,'akuota'=>$akuota),true); 
         
        echo $output;
        die();
    }
	public function addkuota()
    {
    	$this->auth->restrict($this->permissionCreate);
        $kode_satker = $this->uri->segment(5);
        $this->load->model('ref_jabatan/jabatan_model');
        $jabatans = $this->jabatan_model->find_all();
		$this->load->model('pegawai/unitkerja_model');
        $data_unit = $this->unitkerja_model->find($kode_satker);
        Template::set("data_unit",$data_unit);

		Template::set('jabatans', $jabatans);
        Template::set('toolbar_title', "Tambah Kuota Jabatan");
		
        Template::render();
    }
    public function editkuota($id = "")
    {
        $this->auth->restrict($this->permissionEdit);
        $kuota_jabatan = $this->kuotajabatan_model->find($id);
        Template::set('kuota_jabatan', $kuota_jabatan);
        $this->load->model('pegawai/unitkerja_model');
        $data_unit = $this->unitkerja_model->find($kuota_jabatan->KODE_UNIT_KERJA);
        Template::set("data_unit",$data_unit);

        $this->load->model('ref_jabatan/jabatan_model');
        $jabatans = $this->jabatan_model->find_all();
		Template::set('jabatans', $jabatans);
        Template::set('toolbar_title', "Edit Kuota Jabatan");
        Template::render();
    }
    public function getdata_permen(){
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length');
        $start= $this->input->post('start');

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $total= $this->permen_model->count_all();;
        $output=array();
        $output['draw']=$draw;

        
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        if($search!=""){
            $this->permen_model->where('upper("permen") LIKE \'%'.strtoupper($search).'%\'');
        }
        $this->permen_model->limit($length,$start);
        $kolom = $iSortCol != "" ? $iSortCol : "id";
        $sSortCol == "desc" ? "desc" : "asc";
        $this->permen_model->order_by($iSortCol,$sSortCol);
        $records = $this->permen_model->find_all();

        if($search != "")
        {
            $this->permen_model->where('upper("permen") LIKE \'%'.strtoupper($search).'%\'');
            $jum    = $this->permen_model->count_all();
            $output['recordsTotal']=$output['recordsFiltered']=$jum;
        }
        
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->permen;
                $btn_actions = array();
                if($this->auth->has_permission($this->permissionEdit))
                {    
                    $btn_actions  [] = "<a href='".base_url()."admin/masters/petajabatan/edit_permen/".$record->id."' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success show-modal-custom'><i class='fa fa-pencil'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionDelete))
                {    
                    $btn_actions  [] = "<a href='#' kode='$record->id' data-toggle='tooltip' title='Hapus Data' class='btn btn-sm btn-danger btn-hapus-permen'><i class='fa fa-trash-o'></i> </a>";
                }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $row[] = implode(" ",$btn_actions);
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        die();
    }
    
    public function savekuota(){
         // Validate the data
		if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
        $this->form_validation->set_rules($this->kuotajabatan_model->get_validation_rules());
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

        $data = $this->kuotajabatan_model->prep_data($this->input->post());
        $id_data = $this->input->post("ID");
        if(isset($id_data) && !empty($id_data)){
            $this->kuotajabatan_model->update($id_data,$data);
        }
        else {
            // $data['PERATURAN'] = $this->default_permen;
            $this->kuotajabatan_model->insert($data);
        }
        $response ['success']= true;
        $response ['msg']= "Transaksi berhasil";
        echo json_encode($response);    

    }
    public function save_ajuan(){
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
        $this->form_validation->set_rules($this->request_formasi_model->get_validation_rules());
        $this->form_validation->set_rules('id_jabatan','Jabatan','required|max_length[32]');
        $this->form_validation->set_rules('jumlah_ajuan','Jumlah','required|max_length[10]');
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
        $data = $this->request_formasi_model->prep_data($this->input->post());
        $id_data = $this->input->post("id");
        $data['jumlah_ajuan'] = $this->input->post("jumlah_ajuan") ? $this->input->post("jumlah_ajuan") : null;
        $data['skala_prioritas'] = $this->input->post("skala_prioritas") ? $this->input->post("skala_prioritas") : null;
        if(isset($id_data) && !empty($id_data)){
            $this->request_formasi_model->update($id_data,$data);
        }
        else {
            $this->request_formasi_model->insert($data);
        }
        $response ['success']= true;
        $response ['msg']= "Transaksi berhasil";
        echo json_encode($response);    

    }
    public function deletekuota()
	{
		$this->auth->restrict($this->permissionDelete);
		$id 	= $this->input->post('kode');
		if ($this->kuotajabatan_model->delete($id)) {
			 log_activity($this->auth->user_id(),"Delete data" . ': ' . $id . ' : ' . $this->input->ip_address(), 'kuota_jabatan');
			 Template::set_message("Delete kuota jabatan sukses", 'success');
			 echo "Sukses";
		}else{
			echo "Gagal";
		}

		exit();
	}
    public function delete_permen()
    {
        $this->auth->restrict($this->permissionDelete);
        $id = $this->input->post('kode');
        if ($this->permen_model->delete($id)) {
             log_activity($this->auth->user_id(),"Delete data Permen " . ': ' . $id . ' : ' . $this->input->ip_address(), 'jabatan');
             Template::set_message("Delete permen sukses", 'success');
             echo "Sukses";
        }else{
            echo "Gagal";
        }

        exit();
    }
    private function save_permen($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['id'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->permen_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->permen_model->prep_data($this->input->post());

        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->permen_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->permen_model->update($id, $data);
        }

        return $return;
    }
}