<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Masters controller
 */
class Masters extends Admin_Controller
{
    protected $permissionCreate = 'Jenis_arsip.Masters.Create';
    protected $permissionDelete = 'Jenis_arsip.Masters.Delete';
    protected $permissionEdit   = 'Jenis_arsip.Masters.Edit';
    protected $permissionView   = 'Jenis_arsip.Masters.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->auth->restrict($this->permissionView);
        $this->load->model('jenis_arsip/jenis_arsip_model');
        $this->load->model('jenis_arsip/kategori_arsip_model');
        $this->lang->load('jenis_arsip');
        
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'masters/_sub_nav');

        Assets::add_module_js('jenis_arsip', 'jenis_arsip.js');

        $reckategori = $this->kategori_arsip_model->find_all();
        Template::set("reckategori",$reckategori);
    }

    /**
     * Display a list of jenis arsip data.
     *
     * @return void
     */
    public function index()
    {
        // Deleting anything?
        if (isset($_POST['delete'])) {
            $this->auth->restrict($this->permissionDelete);
            $checked = $this->input->post('checked');
            if (is_array($checked) && count($checked)) {

                // If any of the deletions fail, set the result to false, so
                // failure message is set if any of the attempts fail, not just
                // the last attempt

                $result = true;
                foreach ($checked as $pid) {
                    $deleted = $this->jenis_arsip_model->delete($pid);
                    if ($deleted == false) {
                        $result = false;
                    }
                }
                if ($result) {
                    Template::set_message(count($checked) . ' ' . lang('jenis_arsip_delete_success'), 'success');
                } else {
                    Template::set_message(lang('jenis_arsip_delete_failure') . $this->jenis_arsip_model->error, 'error');
                }
            }
        }
        
        
        
        $records = $this->jenis_arsip_model->find_all();

        Template::set('records', $records);
        
    Template::set('toolbar_title', lang('jenis_arsip_manage'));

        Template::render();
    }
    
    /**
     * Create a jenis arsip object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        
        if (isset($_POST['save'])) {
            if ($insert_id = $this->save_jenis_arsip()) {
                log_activity($this->auth->user_id(), lang('jenis_arsip_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'jenis_arsip');
                Template::set_message(lang('jenis_arsip_create_success'), 'success');

                redirect(SITE_AREA . '/masters/jenis_arsip');
            }

            // Not validation error
            if ( ! empty($this->jenis_arsip_model->error)) {
                Template::set_message(lang('jenis_arsip_create_failure') . $this->jenis_arsip_model->error, 'error');
            }
        }

        Template::set('toolbar_title', lang('jenis_arsip_action_create'));

        Template::render();
    }
    /**
     * Allows editing of jenis arsip data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('jenis_arsip_invalid_id'), 'error');

            redirect(SITE_AREA . '/masters/jenis_arsip');
        }
        
        if (isset($_POST['save'])) {
            $this->auth->restrict($this->permissionEdit);

            if ($this->save_jenis_arsip('update', $id)) {
                log_activity($this->auth->user_id(), lang('jenis_arsip_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'jenis_arsip');
                Template::set_message(lang('jenis_arsip_edit_success'), 'success');
                redirect(SITE_AREA . '/masters/jenis_arsip');
            }

            // Not validation error
            if ( ! empty($this->jenis_arsip_model->error)) {
                Template::set_message(lang('jenis_arsip_edit_failure') . $this->jenis_arsip_model->error, 'error');
            }
        }
        
        elseif (isset($_POST['delete'])) {
            $this->auth->restrict($this->permissionDelete);

            if ($this->jenis_arsip_model->delete($id)) {
                log_activity($this->auth->user_id(), lang('jenis_arsip_act_delete_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'jenis_arsip');
                Template::set_message(lang('jenis_arsip_delete_success'), 'success');

                redirect(SITE_AREA . '/masters/jenis_arsip');
            }

            Template::set_message(lang('jenis_arsip_delete_failure') . $this->jenis_arsip_model->error, 'error');
        }
        
        Template::set('jenis_arsip', $this->jenis_arsip_model->find($id));

        Template::set('toolbar_title', lang('jenis_arsip_edit_heading'));
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
    private function save_jenis_arsip($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['ID'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->jenis_arsip_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->jenis_arsip_model->prep_data($this->input->post());

        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->jenis_arsip_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->jenis_arsip_model->update($id, $data);
        }

        return $return;
    }
    public function ajax_data(){
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length');
        $start= $this->input->post('start');

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        
        //$this->jenis_arsip_model->where("deleted ",null);
        $total= $this->jenis_arsip_model->count_all();;
        $output=array();
        $output['draw']=$draw;

        
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();


        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        if($search!=""){
            $this->jenis_arsip_model->where('upper("NAMA_JENIS") LIKE \''.strtoupper($search).'%\'');
        }
        $this->jenis_arsip_model->limit($length,$start);
        /*Urutkan dari alphabet paling terkahir*/
        $kolom = $iSortCol != "" ? $iSortCol : "NAMA_JENIS";
        $sSortCol == "asc" ? "asc" : "desc";
        $this->jenis_arsip_model->order_by($iSortCol,$sSortCol);
        //$this->jenis_arsip_model->where("deleted",null);
        $records=$this->jenis_arsip_model->find_all();

        /*Ketika dalam mode pencarian, berarti kita harus
        'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
        yang mengandung keyword tertentu
        */
        if($search != "")
        {
            $this->jenis_arsip_model->where('upper("NAMA_JENIS") LIKE \''.strtoupper($search).'%\'');
            $jum    = $this->jenis_arsip_model->count_all();
            $output['recordsTotal']=$output['recordsFiltered']=$jum;
        }
        
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->NAMA_JENIS;
                $row []  = $record->KATEGORI;
                $row []  = $record->KETERANGAN;
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/masters/jenis_arsip/edit/".$record->ID."' kode='".$record->ID."' data-toggle='tooltip' title='Ubah' class='btn btn-sm btn-info show-modal'><i class='glyphicon glyphicon-edit'></i> </a>";
                $btn_actions  [] = "<a href='#' kode='".$record->ID."' data-toggle='tooltip' title='Hapus' class='btn btn-sm btn-danger btn-hapus'><i class='glyphicon glyphicon-trash'></i> </a>";

                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        die();
    }
    public function save(){
         // Validate the data
        $this->form_validation->set_rules($this->jenis_arsip_model->get_validation_rules());
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
        
        
        $data = $this->jenis_arsip_model->prep_data($this->input->post());
        //$data['KATEGORI_ARSIP'] = $this->input->post("KATEGORI_ARSIP") ? $this->input->post("KATEGORI_ARSIP") : null;
        $id_data = $this->input->post("ID");
        if(isset($id_data) && !empty($id_data)){
            $this->jenis_arsip_model->update($id_data,$data);
        }
        else $this->jenis_arsip_model->insert($data);
        $response ['success']= true;
        $response ['msg']= "Berhasil";
        echo json_encode($response);    

    }
    public function delete()
    {
        $this->auth->restrict($this->permissionDelete);
        $id     = $this->input->post('kode');
        if ($this->jenis_arsip_model->delete($id)) {
             log_activity($this->auth->user_id(), 'delete data jenis arsip : ' . $id . ' : ' . $this->input->ip_address(), 'jenis_arsip');
             Template::set_message("Sukses Hapus data", 'success');
             echo "Sukses";
        }else{
            echo "Gagal";
        }

        exit();
    }
}