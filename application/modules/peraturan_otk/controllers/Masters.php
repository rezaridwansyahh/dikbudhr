<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Masters controller
 */
class Masters extends Admin_Controller
{
    protected $permissionCreate = 'Peraturan_otk.Masters.Create';
    protected $permissionDelete = 'Peraturan_otk.Masters.Delete';
    protected $permissionEdit   = 'Peraturan_otk.Masters.Edit';
    protected $permissionView   = 'Peraturan_otk.Masters.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('peraturan_otk/peraturan_otk_model');
        $this->lang->load('peraturan_otk');
        
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'masters/_sub_nav');

        Assets::add_module_js('peraturan_otk', 'peraturan_otk.js');
    }

    /**
     * Display a list of Peraturan otk data.
     *
     * @return void
     */
    public function index()
    {
        $this->auth->restrict($this->permissionView);
       
        Template::set('toolbar_title', lang('peraturan_otk_manage'));

        Template::render();
    }
    
    /**
     * Create a Peraturan otk object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        
        if (isset($_POST['save'])) {
            if ($insert_id = $this->save_peraturan_otk()) {
                log_activity($this->auth->user_id(), lang('peraturan_otk_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'peraturan_otk');
                Template::set_message(lang('peraturan_otk_create_success'), 'success');

                redirect(SITE_AREA . '/masters/peraturan_otk');
            }

            // Not validation error
            if ( ! empty($this->peraturan_otk_model->error)) {
                Template::set_message(lang('peraturan_otk_create_failure') . $this->peraturan_otk_model->error, 'error');
            }
        }

        Template::set('toolbar_title', lang('peraturan_otk_action_create'));

        Template::render();
    }
    /**
     * Allows editing of Peraturan otk data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('peraturan_otk_invalid_id'), 'error');

            redirect(SITE_AREA . '/masters/peraturan_otk');
        }
        
        if (isset($_POST['save'])) {
            $this->auth->restrict($this->permissionEdit);

            if ($this->save_peraturan_otk('update', $id)) {
                log_activity($this->auth->user_id(), lang('peraturan_otk_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'peraturan_otk');
                Template::set_message(lang('peraturan_otk_edit_success'), 'success');
                redirect(SITE_AREA . '/masters/peraturan_otk');
            }

            // Not validation error
            if ( ! empty($this->peraturan_otk_model->error)) {
                Template::set_message(lang('peraturan_otk_edit_failure') . $this->peraturan_otk_model->error, 'error');
            }
        }
        
        elseif (isset($_POST['delete'])) {
            $this->auth->restrict($this->permissionDelete);

            if ($this->peraturan_otk_model->delete($id)) {
                log_activity($this->auth->user_id(), lang('peraturan_otk_act_delete_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'peraturan_otk');
                Template::set_message(lang('peraturan_otk_delete_success'), 'success');

                redirect(SITE_AREA . '/masters/peraturan_otk');
            }

            Template::set_message(lang('peraturan_otk_delete_failure') . $this->peraturan_otk_model->error, 'error');
        }
        
        Template::set('peraturan_otk', $this->peraturan_otk_model->find($id));

        Template::set('toolbar_title', lang('peraturan_otk_edit_heading'));
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
    private function save_peraturan_otk($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['id_peraturan'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->peraturan_otk_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->peraturan_otk_model->prep_data($this->input->post());

        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->peraturan_otk_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->peraturan_otk_model->update($id, $data);
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
        $total= $this->peraturan_otk_model->count_all();;
        $output=array();
        $output['draw']=$draw;

        
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        if($search!=""){
            $this->peraturan_otk_model->where('upper("no_peraturan") LIKE \'%'.strtoupper($search).'%\'');
        }
        $this->peraturan_otk_model->limit($length,$start);
        $kolom = $iSortCol != "" ? $iSortCol : "id_peraturan";
        $sSortCol == "desc" ? "desc" : "asc";
        $this->peraturan_otk_model->order_by($iSortCol,$sSortCol);
        $records = $this->peraturan_otk_model->find_all();

        if($search != "")
        {
            $this->peraturan_otk_model->where('upper("no_peraturan") LIKE \'%'.strtoupper($search).'%\'');
            $jum    = $this->peraturan_otk_model->count_all();
            $output['recordsTotal']=$output['recordsFiltered']=$jum;
        }
        
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->no_peraturan;
                $btn_actions = array();
                if($this->auth->has_permission($this->permissionEdit))
                {    
                    $btn_actions  [] = "<a href='".base_url()."admin/masters/peraturan_otk/edit/".$record->id_peraturan."' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success show-modal-custom'><i class='fa fa-pencil'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionDelete))
                {    
                    $btn_actions  [] = "<a href='#' kode='$record->id_peraturan' data-toggle='tooltip' title='Hapus Data' class='btn btn-sm btn-danger btn-hapus'><i class='fa fa-trash-o'></i> </a>";
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
    public function delete()
    {
        $this->auth->restrict($this->permissionDelete);
        $id = $this->input->post('kode');
    //  die($id);
        if ($this->peraturan_otk_model->delete($id)) {
             log_activity($this->auth->user_id(),"Delete data Peraturan OTK " . ': ' . $id . ' : ' . $this->input->ip_address(), 'jabatan');
             Template::set_message("Delete Peraturan OTK sukses", 'success');
             echo "Sukses";
        }else{
            echo "Gagal";
        }

        exit();
    }
}