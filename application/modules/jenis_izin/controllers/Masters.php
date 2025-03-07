<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Masters controller
 */
class Masters extends Admin_Controller
{
    protected $permissionCreate = 'Jenis_izin.Masters.Create';
    protected $permissionDelete = 'Jenis_izin.Masters.Delete';
    protected $permissionEdit   = 'Jenis_izin.Masters.Edit';
    protected $permissionView   = 'Jenis_izin.Masters.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        
        $this->load->model('jenis_izin/jenis_izin_model');
        $this->lang->load('jenis_izin');
        $this->load->helper('dikbud');
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'masters/_sub_nav');

        Assets::add_module_js('jenis_izin', 'jenis_izin.js');
    }

    /**
     * Display a list of jenis izin data.
     *
     * @return void
     */
    public function index()
    {
         
        $this->auth->restrict($this->permissionView);
        Template::set('toolbar_title', "Manage jenis status presensi/absensi");

        Template::render();
    }
    
    /**
     * Create a jenis izin object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
         

        Template::set('toolbar_title', "Create jenis status presensi/absensi");

        Template::render();
    }
    /**
     * Allows editing of jenis izin data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('jenis_izin_invalid_id'), 'error');

            redirect(SITE_AREA . '/masters/jenis_izin');
        }
        
        if (isset($_POST['save'])) {
            $this->auth->restrict($this->permissionEdit);

            if ($this->save_jenis_izin('update', $id)) {
                log_activity($this->auth->user_id(), lang('jenis_izin_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'jenis_izin');
                Template::set_message(lang('jenis_izin_edit_success'), 'success');
                redirect(SITE_AREA . '/masters/jenis_izin');
            }

            // Not validation error
            if ( ! empty($this->jenis_izin_model->error)) {
                Template::set_message(lang('jenis_izin_edit_failure') . $this->jenis_izin_model->error, 'error');
            }
        }
        
        elseif (isset($_POST['delete'])) {
            $this->auth->restrict($this->permissionDelete);

            if ($this->jenis_izin_model->delete($id)) {
                log_activity($this->auth->user_id(), lang('jenis_izin_act_delete_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'jenis_izin');
                Template::set_message(lang('jenis_izin_delete_success'), 'success');

                redirect(SITE_AREA . '/masters/jenis_izin');
            }

            Template::set_message(lang('jenis_izin_delete_failure') . $this->jenis_izin_model->error, 'error');
        }
        
        Template::set('jenis_izin', $this->jenis_izin_model->find($id));

        Template::set('toolbar_title', "Edit jenis status presensi/absensi");
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
    private function save_jenis_izin($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['ID'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->jenis_izin_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->jenis_izin_model->prep_data($this->input->post());

        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->jenis_izin_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->jenis_izin_model->update($id, $data);
        }

        return $return;
    }
    public function ajax_data(){
        $this->auth->restrict($this->permissionView);
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length');
        $start= $this->input->post('start');

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        
        //$this->jenis_izin_model->where("deleted ",null);
        $total= $this->jenis_izin_model->count_all();;
        $output=array();
        $output['draw']=$draw;

        
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();


        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        if($search!=""){
            $this->jenis_izin_model->where('upper("NAMA_IZIN") LIKE \''.strtoupper($search).'%\'');
        }
        $this->jenis_izin_model->limit($length,$start);
        /*Urutkan dari alphabet paling terkahir*/
        $kolom = $iSortCol != "" ? $iSortCol : "NAMA_IZIN";
        $sSortCol == "asc" ? "asc" : "desc";
        $this->jenis_izin_model->order_by($iSortCol,$sSortCol);
        //$this->jenis_izin_model->where("deleted",null);
        $records=$this->jenis_izin_model->find_all();

        /*Ketika dalam mode pencarian, berarti kita harus
        'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
        yang mengandung keyword tertentu
        */
        if($search != "")
        {
            $this->jenis_izin_model->where('upper("NAMA_IZIN") LIKE \''.strtoupper($search).'%\'');
            $jum    = $this->jenis_izin_model->count_all();
            $output['recordsTotal']=$output['recordsFiltered']=$jum;
        }
        
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->KODE;
                $row []  = $record->NAMA_IZIN;
                $row []  = $record->KETERANGAN;
                $row []  = $record->URUTAN;
                $row []  = status_jenis_izin($record->STATUS);
                $apersetujuan = "";
                if($record->PERSETUJUAN != ""){

                    foreach(json_decode($record->PERSETUJUAN) as $values)
                     {
                          $apersetujuan .= get_pejabat_cuti($values)." ";
                     }
                }
                $row []  = $apersetujuan;
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/masters/jenis_izin/edit/".$record->ID."' kode='".$record->ID."' data-toggle='tooltip' title='Ubah' class='btn btn-sm btn-info show-modal'><i class='glyphicon glyphicon-edit'></i> </a>";
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
        $this->form_validation->set_rules($this->jenis_izin_model->get_validation_rules());
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
        
        
        $data = $this->jenis_izin_model->prep_data($this->input->post());
        $data['PERSETUJUAN'] = $this->input->post("PERSETUJUAN") != "" ? json_encode($this->input->post("PERSETUJUAN")) : null;
        $data['URUTAN'] = $this->input->post("URUTAN") != "" ? $this->input->post("URUTAN") : null;
        $id_data = $this->input->post("ID");
        if(isset($id_data) && !empty($id_data)){
            $this->jenis_izin_model->update($id_data,$data);
        }
        else $this->jenis_izin_model->insert($data);
        $response ['success']= true;
        $response ['msg']= "Berhasil";
        echo json_encode($response);    

    }
    public function delete()
    {
        $this->auth->restrict($this->permissionDelete);
        $id     = $this->input->post('kode');
        if ($this->jenis_izin_model->delete($id)) {
             log_activity($this->auth->user_id(), 'delete data jenis izin : ' . $id . ' : ' . $this->input->ip_address(), 'jenis_izin');
             Template::set_message("Sukses Hapus data", 'success');
             echo "Sukses";
        }else{
            echo "Gagal";
        }

        exit();
    }
    public function list_pejabat(){
        $status_arr = get_list_pejabat_cuti();
        foreach($status_arr as $row){
            $data[] = array(
                'id'=>$row['id'],
                'text'=>$row['value'],
            );
        }
        $output = array(
            'results'=>$data 
        );
        echo json_encode($output);
    }
}