<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Masters controller
 */
class Masters extends Admin_Controller
{
    protected $permissionCreate = 'Ref_jabatan.Masters.Create';
    protected $permissionDelete = 'Ref_jabatan.Masters.Delete';
    protected $permissionEdit   = 'Ref_jabatan.Masters.Edit';
    protected $permissionView   = 'Ref_jabatan.Masters.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        
        $this->load->model('ref_jabatan/ref_jabatan_model');
        $this->load->model('ref_jabatan/jabatan_model');
        $this->lang->load('ref_jabatan');
        
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'masters/_sub_nav');

        Assets::add_module_js('ref_jabatan', 'ref_jabatan.js');
    }

    /**
     * Display a list of ref_jabatan data.
     *
     * @return void
     */
    public function index($offset = 0)
    {
        $this->auth->restrict($this->permissionView);
    	Template::set('toolbar_title',"Jabatan");

        Template::render();
    }
    
    /**
     * Create a ref_jabatan object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        
        if (isset($_POST['save'])) {
            if ($insert_id = $this->save_ref_jabatan()) {
                log_activity($this->auth->user_id(), lang('ref_jabatan_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'ref_jabatan');
                Template::set_message(lang('ref_jabatan_create_success'), 'success');

                redirect(SITE_AREA . '/masters/ref_jabatan');
            }

            // Not validation error
            if ( ! empty($this->ref_jabatan_model->error)) {
                Template::set_message(lang('ref_jabatan_create_failure') . $this->ref_jabatan_model->error, 'error');
            }
        }

        Template::set('toolbar_title', lang('ref_jabatan_action_create'));

        Template::render();
    }
    
    /**
     * Allows editing of ref_jabatan data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('ref_jabatan_invalid_id'), 'error');

            redirect(SITE_AREA . '/masters/ref_jabatan');
        }
        
        if (isset($_POST['save'])) {
            $this->auth->restrict($this->permissionEdit);

            if ($this->save_ref_jabatan('update', $id)) {
                log_activity($this->auth->user_id(), lang('ref_jabatan_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'ref_jabatan');
                Template::set_message(lang('ref_jabatan_edit_success'), 'success');
                redirect(SITE_AREA . '/masters/ref_jabatan');
            }else{
                Template::set_message("Ada error" . $this->ref_jabatan_model->error, 'error');
            }

            // Not validation error
            if ( ! empty($this->ref_jabatan_model->error)) {
                Template::set_message(lang('ref_jabatan_edit_failure') . $this->ref_jabatan_model->error, 'error');
            }
        }
        
        elseif (isset($_POST['delete'])) {
            $this->auth->restrict($this->permissionDelete);

            if ($this->ref_jabatan_model->delete($id)) {
                log_activity($this->auth->user_id(), lang('ref_jabatan_act_delete_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'ref_jabatan');
                Template::set_message(lang('ref_jabatan_delete_success'), 'success');

                redirect(SITE_AREA . '/masters/ref_jabatan');
            }

            Template::set_message(lang('ref_jabatan_delete_failure') . $this->ref_jabatan_model->error, 'error');
        }
        
        Template::set('ref_jabatan', $this->ref_jabatan_model->find($id));

        Template::set('toolbar_title', lang('ref_jabatan_edit_heading'));
        Template::render();
    }

    public function delete()
	{
		$this->auth->restrict($this->permissionDelete);
		$id = $this->input->post('kode');
	//	die($id);
		if ($this->ref_jabatan_model->delete($id)) {
			 log_activity($this->auth->user_id(),"Delete data" . ': ' . $id . ' : ' . $this->input->ip_address(), 'ref_jabatan');
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
    private function save_ref_jabatan($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['ID'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->ref_jabatan_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->ref_jabatan_model->prep_data($this->input->post());
        //$data['TUNJANGAN1'] = $this->input->post("TUNJANGAN");
        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->ref_jabatan_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->ref_jabatan_model->update($id, $data);
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
		
		//$this->ref_jabatan_model->where("deleted ",null);
		$total= $this->ref_jabatan_model->count_all();;
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();


		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if($search!=""){
			$this->ref_jabatan_model->where('upper("NAMA_JABATAN") LIKE \'%'.strtoupper($search).'%\'');
			$this->ref_jabatan_model->or_where('upper("JENIS_JABATAN") LIKE \'%'.strtoupper($search).'%\'');
		}
		$this->ref_jabatan_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "NAMA_JABATAN";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->ref_jabatan_model->order_by($iSortCol,$sSortCol);
        //$this->ref_jabatan_model->where("deleted",null);
		$records=$this->ref_jabatan_model->find_all();

		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($search != "")
		{
			$this->ref_jabatan_model->where('upper("NAMA_JABATAN") LIKE \'%'.strtoupper($search).'%\'');
			$this->ref_jabatan_model->or_where('upper("JENIS_JABATAN") LIKE \'%'.strtoupper($search).'%\'');
			$jum	= $this->ref_jabatan_model->count_all();
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}
		
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->NAMA_JABATAN;
                $row []  = $record->JENIS_JABATAN;
				$row []  = $record->PENSIUN;
                $row []  = $record->TUNJANGAN;
                $btn_actions = array();
                $btn_actions  [] = "
                    <a href='".base_url()."admin/masters/ref_jabatan/edit/".$record->ID_JABATAN."'  data-toggle='tooltip' title='Ubah Data'><span class='fa-stack'>
					   	<i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-pencil fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";

                $btn_actions  [] = "
                        <a href='#' kode='$record->ID_JABATAN' class='btn-hapus' data-toggle='tooltip' title='Hapus data' >
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
    public function getbyjenis()
	{
        //$this->auth->restrict($this->permissionUpdateMandiri);
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
		$jenis = $this->input->get('jenis');
		$json = array(); 
		$records = $this->jabatan_model->find_all($jenis);
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) :
				$json['id'][] = $record->KODE_JABATAN;
				$json['nama'][] = $record->NAMA_JABATAN;
			endforeach;
		endif;
		echo json_encode($json);
		die();
	}
    public function ajax(){
        $json = array();
        $limit = 10;
        $page = $this->input->get('page') ? $this->input->get('page') : "1";
        $q= $this->input->get('term');
        $start = ($page-1)*$limit;
        
        if(!empty($q)){
            $json = $this->data_model($q,$start,$limit);
        }
        echo json_encode($json);
    }
    private function data_model($key,$start,$limit){
          
            $this->db->start_cache();
            $this->db->like('lower("NAMA_JABATAN")', strtolower($key));
            $this->db->from("hris.ref_jabatan");
            $this->db->stop_cache();
            $total = $this->db->get()->num_rows();
            $this->db->select('ID_JABATAN as id,NAMA_JABATAN as text');
            $this->db->limit($limit,$start);

            $data = $this->db->get()->result();

            $endCount = $start + $limit;
            $morePages = $endCount > $total;
            $o = array(
            "results" => $data,
                "pagination" => array(
                    "more" =>$morePages
                )
            );   
            $this->db->flush_cache();
            return $o;
    }
}