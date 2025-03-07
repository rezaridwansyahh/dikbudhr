<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Masters controller
 */
class Masters extends Admin_Controller
{
    protected $permissionCreate = 'Agama.Masters.Create';
    protected $permissionDelete = 'Agama.Masters.Delete';
    protected $permissionEdit   = 'Agama.Masters.Edit';
    protected $permissionView   = 'Agama.Masters.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->auth->restrict($this->permissionView);
        $this->load->model('agama/agama_model');
        $this->lang->load('agama');
        
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'masters/_sub_nav');

        Assets::add_module_js('agama', 'agama.js');
    }

    /**
     * Display a list of Agama data.
     *
     * @return void
     */
    public function index($offset = 0)
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
                    $deleted = $this->agama_model->delete($pid);
                    if ($deleted == false) {
                        $result = false;
                    }
                }
                if ($result) {
                    Template::set_message(count($checked) . ' ' . lang('agama_delete_success'), 'success');
                } else {
                    Template::set_message(lang('agama_delete_failure') . $this->agama_model->error, 'error');
                }
            }
        }
        $pagerUriSegment = 5;
        $pagerBaseUrl = site_url(SITE_AREA . '/masters/agama/index') . '/';
        
        $limit  = $this->settings_lib->item('site.list_limit') ?: 15;

        $this->load->library('pagination');
        $pager['base_url']    = $pagerBaseUrl;
        $pager['total_rows']  = $this->agama_model->count_all();
        $pager['per_page']    = $limit;
        $pager['uri_segment'] = $pagerUriSegment;

        $this->pagination->initialize($pager);
        $this->agama_model->limit($limit, $offset);
        
        $records = $this->agama_model->find_all();

        Template::set('records', $records);
        
    Template::set('toolbar_title', lang('agama_manage'));

        Template::render();
    }
    
    /**
     * Create a Agama object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        
        if (isset($_POST['save'])) {
            if ($insert_id = $this->save_agama()) {
                log_activity($this->auth->user_id(), lang('agama_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'agama');
                Template::set_message(lang('agama_create_success'), 'success');

                redirect(SITE_AREA . '/masters/agama');
            }

            // Not validation error
            if ( ! empty($this->agama_model->error)) {
                Template::set_message(lang('agama_create_failure') . $this->agama_model->error, 'error');
            }
        }

        Template::set('toolbar_title', lang('agama_action_create'));

        Template::render();
    }
    
    /**
     * Allows editing of Agama data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('agama_invalid_id'), 'error');

            redirect(SITE_AREA . '/masters/agama');
        }
        
        if (isset($_POST['save'])) {
            $this->auth->restrict($this->permissionEdit);

            if ($this->save_agama('update', $id)) {
                log_activity($this->auth->user_id(), lang('agama_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'agama');
                Template::set_message(lang('agama_edit_success'), 'success');
                redirect(SITE_AREA . '/masters/agama');
            }

            // Not validation error
            if ( ! empty($this->agama_model->error)) {
                Template::set_message(lang('agama_edit_failure') . $this->agama_model->error, 'error');
            }
        }
        
        elseif (isset($_POST['delete'])) {
            $this->auth->restrict($this->permissionDelete);

            if ($this->agama_model->delete($id)) {
                log_activity($this->auth->user_id(), lang('agama_act_delete_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'agama');
                Template::set_message(lang('agama_delete_success'), 'success');

                redirect(SITE_AREA . '/masters/agama');
            }

            Template::set_message(lang('agama_delete_failure') . $this->agama_model->error, 'error');
        }
        
        Template::set('agama', $this->agama_model->find($id));

        Template::set('toolbar_title', lang('agama_edit_heading'));
        Template::render();
    }

    public function delete($id)
	{
		$this->auth->restrict($this->permissionDelete);
		if ($this->agama_model->delete($id)) {
			 log_activity($this->auth->user_id(),"Delete data" . ': ' . $id . ' : ' . $this->input->ip_address(), 'pegawai');
			 Template::set_message("Delete pegawai sukses", 'success');
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
    private function save_agama($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['ID'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->agama_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->agama_model->prep_data($this->input->post());

        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->agama_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->agama_model->update($id, $data);
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
		
		//$this->agama_model->where("deleted ",null);
		$total= $this->agama_model->count_all();;
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();


		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if($search!=""){
			$this->agama_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
		}
		$this->agama_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "NAMA";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->agama_model->order_by($iSortCol,$sSortCol);
        //$this->agama_model->where("deleted",null);
		$records=$this->agama_model->find_all();

		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($search != "")
		{
			$this->agama_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
			$jum	= $this->agama_model->count_all();
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
                    <a href='".base_url()."admin/masters/agama/edit/".$record->ID."'  data-toggle='tooltip' title='Ubah Data'><span class='fa-stack'>
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