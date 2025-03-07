<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Ippns controller
 */
class Ippns extends Admin_Controller
{
    protected $permissionCreate = 'Penilaian.Ippns.Create';
    protected $permissionDelete = 'Penilaian.Ippns.Delete';
    protected $permissionEdit   = 'Penilaian.Ippns.Edit';
    protected $permissionView   = 'Penilaian.Ippns.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model("ippns_model");
        $this->load->model("pegawai/unitkerja_model");
        
        $this->auth->restrict($this->permissionView);
        $this->lang->load('penilaian');
        
        $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'ippns/_sub_nav');

        Assets::add_module_js('penilaian', 'penilaian.js');
    }

    public function ajax_data(){
        $list = $this->ippns_model->getAll();
        echo json_encode($list);
    }

    public function get_ippns_value(){
        
        if(isset($_GET['id'])){
            
            $retList = $this->ippns_model->populateIPASN($_GET['id']);

            //var_dump($retList);
            echo json_encode($retList);
        }else{
            $list = $this->ippns_model->get_all();
            echo json_encode($list);
        }
    }

    

    /**
     * Display a list of penilaian data.
     *
     * @return void
     */
    public function index()
    {
    
        //Template::set('unor',$this->ippns_model->getAllUnor());    
          
    
        Template::set('toolbar_title', lang('penilaian_manage'));

        Template::render();
    }
    
    
    /**
     * Create a penilaian object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        

        Template::set('toolbar_title', lang('penilaian_action_create'));

        Template::render();
    }
    /**
     * Allows editing of penilaian data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('penilaian_invalid_id'), 'error');

            redirect(SITE_AREA . '/ippns/penilaian');
        }
        
        
        

        Template::set('toolbar_title', lang('penilaian_edit_heading'));
        Template::render();
    }
}