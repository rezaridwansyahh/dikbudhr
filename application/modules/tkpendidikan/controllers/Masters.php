<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Masters controller
 */
class Masters extends Admin_Controller
{
    protected $permissionCreate = 'Tkpendidikan.Masters.Create';
    protected $permissionDelete = 'Tkpendidikan.Masters.Delete';
    protected $permissionEdit   = 'Tkpendidikan.Masters.Edit';
    protected $permissionView   = 'Tkpendidikan.Masters.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->auth->restrict($this->permissionView);
        $this->load->model('tkpendidikan/tkpendidikan_model');
        $this->lang->load('tkpendidikan');
        
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'masters/_sub_nav');

        Assets::add_module_js('tkpendidikan', 'Tkpendidikan.js');
    }

    /**
     * Display a list of tkpendidikan data.
     *
     * @return void
     */
    public function index($offset = 0)
    {
        
    	Template::set('toolbar_title',"Tingkat Pendidikan");

        Template::render();
    }
    
    /**
     * Create a tkpendidikan object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        
        if (isset($_POST['save'])) {
            if ($insert_id = $this->save_tkpendidikan()) {
                log_activity($this->auth->user_id(), lang('tkpendidikan_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'tkpendidikan');
                Template::set_message(lang('tkpendidikan_create_success'), 'success');

                redirect(SITE_AREA . '/masters/tkpendidikan');
            }

            // Not validation error
            if ( ! empty($this->tkpendidikan_model->error)) {
                Template::set_message(lang('tkpendidikan_create_failure') . $this->tkpendidikan_model->error, 'error');
            }
        }

        Template::set('toolbar_title', lang('tkpendidikan_action_create'));

        Template::render();
    }
    
    /**
     * Allows editing of tkpendidikan data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('tkpendidikan_invalid_id'), 'error');

            redirect(SITE_AREA . '/masters/tkpendidikan');
        }
        
        if (isset($_POST['save'])) {
            $this->auth->restrict($this->permissionEdit);

            if ($this->save_tkpendidikan('update', $id)) {
                log_activity($this->auth->user_id(), lang('tkpendidikan_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'tkpendidikan');
                Template::set_message(lang('tkpendidikan_edit_success'), 'success');
                redirect(SITE_AREA . '/masters/tkpendidikan');
            }

            // Not validation error
            if ( ! empty($this->tkpendidikan_model->error)) {
                Template::set_message(lang('tkpendidikan_edit_failure') . $this->tkpendidikan_model->error, 'error');
            }
        }
        
        elseif (isset($_POST['delete'])) {
            $this->auth->restrict($this->permissionDelete);

            if ($this->tkpendidikan_model->delete($id)) {
                log_activity($this->auth->user_id(), lang('tkpendidikan_act_delete_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'tkpendidikan');
                Template::set_message(lang('tkpendidikan_delete_success'), 'success');

                redirect(SITE_AREA . '/masters/tkpendidikan');
            }

            Template::set_message(lang('tkpendidikan_delete_failure') . $this->tkpendidikan_model->error, 'error');
        }
        
        Template::set('tkpendidikan', $this->tkpendidikan_model->find($id));

        Template::set('toolbar_title', lang('tkpendidikan_edit_heading'));
        Template::render();
    }

    public function delete()
	{
		$this->auth->restrict($this->permissionDelete);
		$id = $this->input->post('kode');
		if ($this->tkpendidikan_model->delete($id)) {
			 log_activity($this->auth->user_id(),"Delete data" . ': ' . $id . ' : ' . $this->input->ip_address(), 'tkpendidikan');
			 Template::set_message("Delete Jabatan sukses", 'success');
			 echo "Sukses";
		}else{
			echo "Gagal";
		}

		exit();
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
    private function save_tkpendidikan($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['ID'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->tkpendidikan_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->tkpendidikan_model->prep_data($this->input->post());

        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->tkpendidikan_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->tkpendidikan_model->update($id, $data);
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
		
		//$this->tkpendidikan_model->where("deleted ",null);
		$total= $this->tkpendidikan_model->count_all();;
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();


		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if($search!=""){
			$this->tkpendidikan_model->where('upper("NAMA") LIKE \'%'.strtoupper($search).'%\'');
		}
		$this->tkpendidikan_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "Nama_Jabatan";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->tkpendidikan_model->order_by($iSortCol,$sSortCol);
        //$this->tkpendidikan_model->where("deleted",null);
		$records=$this->tkpendidikan_model->find_all();

		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($search != "")
		{
			$this->tkpendidikan_model->where('upper("NAMA") LIKE \'%'.strtoupper($search).'%\'');
			$jum	= $this->tkpendidikan_model->count_all();
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}
		
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->NAMA;
                $btn_actions = array();
                $btn_actions  [] = "
                    <a href='".base_url()."admin/masters/tkpendidikan/edit/".$record->ID."'  data-toggle='tooltip' title='Ubah Data'><span class='fa-stack'>
					   	<i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-pencil fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";

                $btn_actions  [] = "
                        <a href='#' kode='$record->ID' class='btn-hapus' data-toggle='tooltip' title='Hapus data' >
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
}