<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Masters controller
 */
class Masters extends Admin_Controller
{
    protected $permissionCreate = 'Golongan.Masters.Create';
    protected $permissionDelete = 'Golongan.Masters.Delete';
    protected $permissionEdit   = 'Golongan.Masters.Edit';
    protected $permissionView   = 'Golongan.Masters.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->auth->restrict($this->permissionView);
        $this->load->model('golongan/golongan_model');
        $this->lang->load('golongan');
        
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'masters/_sub_nav');

        Assets::add_module_js('golongan', 'Golongan.js');
    }

    /**
     * Display a list of golongan data.
     *
     * @return void
     */
    public function index($offset = 0)
    {
        
    	Template::set('toolbar_title',"Pangkat/Golongan");

        Template::render();
    }
    
    /**
     * Create a golongan object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        
        if (isset($_POST['save'])) {
            if ($insert_id = $this->save_golongan()) {
                log_activity($this->auth->user_id(), lang('golongan_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'golongan');
                Template::set_message(lang('golongan_create_success'), 'success');

                redirect(SITE_AREA . '/masters/golongan');
            }

            // Not validation error
            if ( ! empty($this->golongan_model->error)) {
                Template::set_message(lang('golongan_create_failure') . $this->golongan_model->error, 'error');
            }
        }

        Template::set('toolbar_title', lang('golongan_action_create'));

        Template::render();
    }
    
    /**
     * Allows editing of golongan data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('golongan_invalid_id'), 'error');

            redirect(SITE_AREA . '/masters/golongan');
        }
        
        if (isset($_POST['save'])) {
            $this->auth->restrict($this->permissionEdit);

            if ($this->save_golongan('update', $id)) {
                log_activity($this->auth->user_id(), lang('golongan_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'golongan');
                Template::set_message(lang('golongan_edit_success'), 'success');
                redirect(SITE_AREA . '/masters/golongan');
            }

            // Not validation error
            if ( ! empty($this->golongan_model->error)) {
                Template::set_message(lang('golongan_edit_failure') . $this->golongan_model->error, 'error');
            }
        }
        
        elseif (isset($_POST['delete'])) {
            $this->auth->restrict($this->permissionDelete);

            if ($this->golongan_model->delete($id)) {
                log_activity($this->auth->user_id(), lang('golongan_act_delete_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'golongan');
                Template::set_message(lang('golongan_delete_success'), 'success');

                redirect(SITE_AREA . '/masters/golongan');
            }

            Template::set_message(lang('golongan_delete_failure') . $this->golongan_model->error, 'error');
        }
        
        Template::set('golongan', $this->golongan_model->find($id));

        Template::set('toolbar_title', lang('golongan_edit_heading'));
        Template::render();
    }

    public function delete()
	{
		$this->auth->restrict($this->permissionDelete);
		$id = $this->input->post('kode');
		if ($this->golongan_model->delete($id)) {
			 log_activity($this->auth->user_id(),"Delete data" . ': ' . $id . ' : ' . $this->input->ip_address(), 'golongan');
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
    private function save_golongan($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['ID'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->golongan_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->golongan_model->prep_data($this->input->post());

        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->golongan_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->golongan_model->update($id, $data);
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
		
		//$this->golongan_model->where("deleted ",null);
		$total= $this->golongan_model->count_all();;
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();


		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if($search!=""){
			$this->golongan_model->where('upper("NAMA") LIKE \'%'.strtoupper($search).'%\'');
			$this->golongan_model->or_where('upper("NAMA_PANGKAT") LIKE \'%'.strtoupper($search).'%\'');
		}
		$this->golongan_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "NAMA_PANGKAT";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->golongan_model->order_by($iSortCol,$sSortCol);
        //$this->golongan_model->where("deleted",null);
		$records=$this->golongan_model->find_all();

		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($search != "")
		{
			$this->golongan_model->where('upper("NAMA") LIKE \'%'.strtoupper($search).'%\'');
			$this->golongan_model->or_where('upper("NAMA_PANGKAT") LIKE \'%'.strtoupper($search).'%\'');
			$jum	= $this->golongan_model->count_all();
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}
		
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->NAMA;
                $row []  = $record->NAMA_PANGKAT;
                $btn_actions = array();
                $btn_actions  [] = "
                    <a href='".base_url()."admin/masters/golongan/edit/".$record->ID."'  data-toggle='tooltip' title='Ubah Data'><span class='fa-stack'>
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