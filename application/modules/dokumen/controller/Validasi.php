<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Masters controller
 */
class Masters extends Admin_Controller
{
    protected $permissionCreate = 'Daftar_rohaniawan.Masters.Create';
    protected $permissionDelete = 'Daftar_rohaniawan.Masters.Delete';
    protected $permissionEdit   = 'Daftar_rohaniawan.Masters.Edit';
    protected $permissionView   = 'Daftar_rohaniawan.Masters.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->auth->restrict($this->permissionView);
        $this->load->model('daftar_rohaniawan/daftar_rohaniawan_model');
        $this->load->model('pegawai/agama_model');
        $this->lang->load('daftar_rohaniawan');
        
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'masters/_sub_nav');

        Assets::add_module_js('daftar_rohaniawan', 'daftar_rohaniawan.js');

        $agamas = $this->agama_model->find_all();
        Template::set('agamas', $agamas);
    }

    /**
     * Display a list of daftar rohaniawan data.
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
                    $deleted = $this->daftar_rohaniawan_model->delete($pid);
                    if ($deleted == false) {
                        $result = false;
                    }
                }
                if ($result) {
                    Template::set_message(count($checked) . ' ' . lang('daftar_rohaniawan_delete_success'), 'success');
                } else {
                    Template::set_message(lang('daftar_rohaniawan_delete_failure') . $this->daftar_rohaniawan_model->error, 'error');
                }
            }
        }
        
        
        
        $records = $this->daftar_rohaniawan_model->find_all();

        Template::set('records', $records);
        
    Template::set('toolbar_title', lang('daftar_rohaniawan_manage'));

        Template::render();
    }
    
    /**
     * Create a daftar rohaniawan object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        
        if (isset($_POST['save'])) {
            if ($insert_id = $this->save_daftar_rohaniawan()) {
                log_activity($this->auth->user_id(), lang('daftar_rohaniawan_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'daftar_rohaniawan');
                Template::set_message(lang('daftar_rohaniawan_create_success'), 'success');

                redirect(SITE_AREA . '/masters/daftar_rohaniawan');
            }

            // Not validation error
            if ( ! empty($this->daftar_rohaniawan_model->error)) {
                Template::set_message(lang('daftar_rohaniawan_create_failure') . $this->daftar_rohaniawan_model->error, 'error');
            }
        }

        Template::set('toolbar_title', lang('daftar_rohaniawan_action_create'));

        Template::render();
    }
    /**
     * Allows editing of daftar rohaniawan data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('daftar_rohaniawan_invalid_id'), 'error');

            redirect(SITE_AREA . '/masters/daftar_rohaniawan');
        }
        
        if (isset($_POST['save'])) {
            $this->auth->restrict($this->permissionEdit);

            if ($this->save_daftar_rohaniawan('update', $id)) {
                log_activity($this->auth->user_id(), lang('daftar_rohaniawan_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'daftar_rohaniawan');
                Template::set_message(lang('daftar_rohaniawan_edit_success'), 'success');
                redirect(SITE_AREA . '/masters/daftar_rohaniawan');
            }

            // Not validation error
            if ( ! empty($this->daftar_rohaniawan_model->error)) {
                Template::set_message(lang('daftar_rohaniawan_edit_failure') . $this->daftar_rohaniawan_model->error, 'error');
            }
        }
        
        elseif (isset($_POST['delete'])) {
            $this->auth->restrict($this->permissionDelete);

            if ($this->daftar_rohaniawan_model->delete($id)) {
                log_activity($this->auth->user_id(), lang('daftar_rohaniawan_act_delete_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'daftar_rohaniawan');
                Template::set_message(lang('daftar_rohaniawan_delete_success'), 'success');

                redirect(SITE_AREA . '/masters/daftar_rohaniawan');
            }

            Template::set_message(lang('daftar_rohaniawan_delete_failure') . $this->daftar_rohaniawan_model->error, 'error');
        }
        
        Template::set('daftar_rohaniawan', $this->daftar_rohaniawan_model->find($id));

        Template::set('toolbar_title', lang('daftar_rohaniawan_edit_heading'));
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
    private function save_daftar_rohaniawan($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['id'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->daftar_rohaniawan_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->daftar_rohaniawan_model->prep_data($this->input->post());

        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->daftar_rohaniawan_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->daftar_rohaniawan_model->update($id, $data);
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
        
        //$this->daftar_rohaniawan_model->where("deleted ",null);
        $total= $this->daftar_rohaniawan_model->count_all();;
        $output=array();
        $output['draw']=$draw;

        
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();


        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        if($search!=""){
            $this->daftar_rohaniawan_model->where('upper("nama") LIKE \''.strtoupper($search).'%\'');
        }
        $this->daftar_rohaniawan_model->limit($length,$start);
        /*Urutkan dari alphabet paling terkahir*/
        $kolom = $iSortCol != "" ? $iSortCol : "nama";
        $sSortCol == "asc" ? "asc" : "desc";
        $this->daftar_rohaniawan_model->order_by($iSortCol,$sSortCol);
        //$this->daftar_rohaniawan_model->where("deleted",null);
        $records=$this->daftar_rohaniawan_model->find_all();

        /*Ketika dalam mode pencarian, berarti kita harus
        'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
        yang mengandung keyword tertentu
        */
        if($search != "")
        {
            $this->daftar_rohaniawan_model->where('upper("nama") LIKE \''.strtoupper($search).'%\'');
            $jum    = $this->daftar_rohaniawan_model->count_all();
            $output['recordsTotal']=$output['recordsFiltered']=$jum;
        }
        
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->nama;
                $row []  = $record->NAMA_AGAMA;
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/masters/daftar_rohaniawan/edit/".$record->id."' kode='".$record->id."' data-toggle='tooltip' title='Ubah' class='btn btn-sm btn-info show-modal'><i class='glyphicon glyphicon-edit'></i> </a>";
                $btn_actions  [] = "<a href='#' kode='".$record->id."' data-toggle='tooltip' title='Hapus' class='btn btn-sm btn-danger btn-hapus'><i class='glyphicon glyphicon-trash'></i> </a>";

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
        $this->form_validation->set_rules($this->daftar_rohaniawan_model->get_validation_rules());
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
        
        
        $data = $this->daftar_rohaniawan_model->prep_data($this->input->post());
        $data['agama'] = $this->input->post("agama") ? $this->input->post("agama") : null;
        $id_data = $this->input->post("id");
        if(isset($id_data) && !empty($id_data)){
            $this->daftar_rohaniawan_model->update($id_data,$data);
        }
        else $this->daftar_rohaniawan_model->insert($data);
        $response ['success']= true;
        $response ['msg']= "berhasil";
        echo json_encode($response);    

    }
    public function delete()
    {
        $this->auth->restrict($this->permissionDelete);
        $id     = $this->input->post('kode');
        if ($this->daftar_rohaniawan_model->delete($id)) {
             log_activity($this->auth->user_id(), 'delete data daftar rohaniawan : ' . $id . ' : ' . $this->input->ip_address(), 'daftar_rohaniawan');
             Template::set_message("Sukses Hapus data", 'success');
             echo "Sukses";
        }else{
            echo "Gagal";
        }

        exit();
    }
}