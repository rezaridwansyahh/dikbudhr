<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Masters controller
 */
class Masters extends Admin_Controller
{
    protected $permissionCreate = 'Jenis_satker.Masters.Create';
    protected $permissionDelete = 'Jenis_satker.Masters.Delete';
    protected $permissionEdit   = 'Jenis_satker.Masters.Edit';
    protected $permissionView   = 'Jenis_satker.Masters.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        
        $this->load->model('jenis_satker/jenis_satker_model');
        $this->lang->load('jenis_satker');
        
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'masters/_sub_nav');

        Assets::add_module_js('jenis_satker', 'jenis_satker.js');
    }

    /**
     * Display a list of Jenis Satker data.
     *
     * @return void
     */
    public function index()
    {
        $this->auth->restrict($this->permissionView);
        Template::set('toolbar_title', lang('jenis_satker_manage'));

        Template::render();
    }
    
    /**
     * Create a Jenis Satker object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        
        if (isset($_POST['save'])) {
            if ($insert_id = $this->save_jenis_satker()) {
                log_activity($this->auth->user_id(), lang('jenis_satker_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'jenis_satker');
                Template::set_message(lang('jenis_satker_create_success'), 'success');

                redirect(SITE_AREA . '/masters/jenis_satker');
            }

            // Not validation error
            if ( ! empty($this->jenis_satker_model->error)) {
                Template::set_message(lang('jenis_satker_create_failure') . $this->jenis_satker_model->error, 'error');
            }
        }

        Template::set('toolbar_title', lang('jenis_satker_action_create'));

        Template::render();
    }
    /**
     * Allows editing of Jenis Satker data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('jenis_satker_invalid_id'), 'error');

            redirect(SITE_AREA . '/masters/jenis_satker');
        }
        
        if (isset($_POST['save'])) {
            $this->auth->restrict($this->permissionEdit);

            if ($this->save_jenis_satker('update', $id)) {
                log_activity($this->auth->user_id(), lang('jenis_satker_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'jenis_satker');
                Template::set_message(lang('jenis_satker_edit_success'), 'success');
                redirect(SITE_AREA . '/masters/jenis_satker');
            }

            // Not validation error
            if ( ! empty($this->jenis_satker_model->error)) {
                Template::set_message(lang('jenis_satker_edit_failure') . $this->jenis_satker_model->error, 'error');
            }
        }
        
        elseif (isset($_POST['delete'])) {
            $this->auth->restrict($this->permissionDelete);

            if ($this->jenis_satker_model->delete($id)) {
                log_activity($this->auth->user_id(), lang('jenis_satker_act_delete_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'jenis_satker');
                Template::set_message(lang('jenis_satker_delete_success'), 'success');

                redirect(SITE_AREA . '/masters/jenis_satker');
            }

            Template::set_message(lang('jenis_satker_delete_failure') . $this->jenis_satker_model->error, 'error');
        }
        
        Template::set('jenis_satker', $this->jenis_satker_model->find($id));

        Template::set('toolbar_title', lang('jenis_satker_edit_heading'));
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
    private function save_jenis_satker($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['id_jenis'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->jenis_satker_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->jenis_satker_model->prep_data($this->input->post());

        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->jenis_satker_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->jenis_satker_model->update($id, $data);
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
        $total= $this->jenis_satker_model->count_all();;
        $output=array();
        $output['draw']=$draw;

        
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();


        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        if($search!=""){
            $this->jenis_satker_model->where('upper("nama_jenis_satker") LIKE \'%'.strtoupper($search).'%\'');
        }
        $this->jenis_satker_model->limit($length,$start);
        $kolom = $iSortCol != "" ? $iSortCol : "id_jenis";
        $sSortCol == "desc" ? "desc" : "asc";
        $this->jenis_satker_model->order_by($iSortCol,$sSortCol);
        $records = $this->jenis_satker_model->find_all();

        if($search != "")
        {
            $this->jenis_satker_model->where('upper("nama_jenis_satker") LIKE \'%'.strtoupper($search).'%\'');
            $jum    = $this->jenis_satker_model->count_all();
            $output['recordsTotal']=$output['recordsFiltered']=$jum;
        }
        
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->nama_jenis_satker;
                $btn_actions = array();
                $btn_actions  [] = "
                    <a href='".base_url()."admin/masters/jenis_satker/edit/".$record->id_jenis."'  data-toggle='tooltip' title='Ubah Data'><span class='fa-stack'>
                        <i class='fa fa-square fa-stack-2x'></i>
                        <i class='fa fa-pencil fa-stack-1x fa-inverse'></i>
                        </span>
                        </a>
                ";

                $btn_actions  [] = "
                        <a href='#' kode='$record->id_jenis' class='btn-hapus' data-toggle='tooltip' title='Hapus data' >
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
    public function delete()
    {
        $this->auth->restrict($this->permissionDelete);
        $id = $this->input->post('kode');
    //  die($id);
        if ($this->jenis_satker_model->delete($id)) {
             log_activity($this->auth->user_id(),"Delete data jenis_satker_model" . ': ' . $id . ' : ' . $this->input->ip_address(), 'jabatan');
             Template::set_message("Delete Jenis Satker sukses", 'success');
             echo "Sukses";
        }else{
            echo "Gagal";
        }

        exit();
    }
}