<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Sk controller
 */
class Sk extends Admin_Controller
{
    protected $permissionCreate = 'Ds_riwayat.Sk.Create';
    protected $permissionDelete = 'Ds_riwayat.Sk.Delete';
    protected $permissionEdit   = 'Ds_riwayat.Sk.Edit';
    protected $permissionView   = 'Ds_riwayat.Sk.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        
        $this->load->model('ds_riwayat/ds_riwayat_model');
        $this->lang->load('ds_riwayat');
        
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'sk/_sub_nav');

        Assets::add_module_js('ds_riwayat', 'ds_riwayat.js');
    }

    /**
     * Display a list of ds riwayat data.
     *
     * @return void
     */
    public function index()
    {
        $this->auth->restrict($this->permissionView); 
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
                    $deleted = $this->ds_riwayat_model->delete($pid);
                    if ($deleted == false) {
                        $result = false;
                    }
                }
                if ($result) {
                    Template::set_message(count($checked) . ' ' . lang('ds_riwayat_delete_success'), 'success');
                } else {
                    Template::set_message(lang('ds_riwayat_delete_failure') . $this->ds_riwayat_model->error, 'error');
                }
            }
        }
        
        
        
        $records = $this->ds_riwayat_model->find_all();

        Template::set('records', $records);
        
    Template::set('toolbar_title', lang('ds_riwayat_manage'));

        Template::render();
    }
    
    /**
     * Create a ds riwayat object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        
        if (isset($_POST['save'])) {
            if ($insert_id = $this->save_ds_riwayat()) {
                log_activity($this->auth->user_id(), lang('ds_riwayat_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'ds_riwayat');
                Template::set_message(lang('ds_riwayat_create_success'), 'success');

                redirect(SITE_AREA . '/sk/ds_riwayat');
            }

            // Not validation error
            if ( ! empty($this->ds_riwayat_model->error)) {
                Template::set_message(lang('ds_riwayat_create_failure') . $this->ds_riwayat_model->error, 'error');
            }
        }

        Template::set('toolbar_title', lang('ds_riwayat_action_create'));

        Template::render();
    }
    /**
     * Allows editing of ds riwayat data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('ds_riwayat_invalid_id'), 'error');

            redirect(SITE_AREA . '/sk/ds_riwayat');
        }
        
        if (isset($_POST['save'])) {
            $this->auth->restrict($this->permissionEdit);

            if ($this->save_ds_riwayat('update', $id)) {
                log_activity($this->auth->user_id(), lang('ds_riwayat_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'ds_riwayat');
                Template::set_message(lang('ds_riwayat_edit_success'), 'success');
                redirect(SITE_AREA . '/sk/ds_riwayat');
            }

            // Not validation error
            if ( ! empty($this->ds_riwayat_model->error)) {
                Template::set_message(lang('ds_riwayat_edit_failure') . $this->ds_riwayat_model->error, 'error');
            }
        }
        
        elseif (isset($_POST['delete'])) {
            $this->auth->restrict($this->permissionDelete);

            if ($this->ds_riwayat_model->delete($id)) {
                log_activity($this->auth->user_id(), lang('ds_riwayat_act_delete_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'ds_riwayat');
                Template::set_message(lang('ds_riwayat_delete_success'), 'success');

                redirect(SITE_AREA . '/sk/ds_riwayat');
            }

            Template::set_message(lang('ds_riwayat_delete_failure') . $this->ds_riwayat_model->error, 'error');
        }
        
        Template::set('ds_riwayat', $this->ds_riwayat_model->find($id));

        Template::set('toolbar_title', lang('ds_riwayat_edit_heading'));
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
    private function save_ds_riwayat($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['id_riwayat'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->ds_riwayat_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->ds_riwayat_model->prep_data($this->input->post());

        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->ds_riwayat_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->ds_riwayat_model->update($id, $data);
        }

        return $return;
    }
}