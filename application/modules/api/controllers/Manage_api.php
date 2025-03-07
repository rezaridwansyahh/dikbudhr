<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Manage_api extends Admin_Controller
{
    protected $permissionCreate = 'ManageApi.Masters.Create';
    protected $permissionDelete = 'ManageApi.Masters.Delete';
    protected $permissionEdit   = 'ManageApi.Masters.Edit';
    protected $permissionView   = 'ManageApi.Masters.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/manage_api_model');
        
    }
    public function ajax_list(){
        $draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length');
		$start= $this->input->post('start');

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$total= $this->manage_api_model->count_all();;
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();

		$this->manage_api_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "NAMA";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->manage_api_model->order_by($iSortCol,$sSortCol);
		$records=$this->manage_api_model->find_all();

		
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->name;
                $row []  = $record->description;
                $row []  = $record->url;
                $btn_actions = array();
                if($this->auth->has_permission($this->permissionEdit)){
                    $btn_actions  [] = "
                        <a class='show-modal-custom' href='".base_url()."api/manage_api/crud/".$record->id."'  data-toggle='modal' title='Ubah Data'><span class='fa-stack'>
                            <i class='fa fa-square fa-stack-2x'></i>
                            <i class='fa fa-pencil fa-stack-1x fa-inverse'></i>
                            </span>
                            </a>
                    ";
                }   
                if($this->auth->has_permission($this->permissionDelete)){
                    $btn_actions  [] = "
                            <a href='#' kode='$record->id' class='btn-hapus' data-toggle='tooltip' title='Hapus data' >
                            <span class='fa-stack'>
                            <i class='fa fa-square fa-stack-2x'></i>
                            <i class='fa fa-trash-o fa-stack-1x fa-inverse'></i>
                            </span>
                            </a>
                    ";
                }
                
                $row[] = implode(" ",$btn_actions);
                

                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		die();
    }
    public function crud($record_id=''){
        if(empty($record_id)){
            $this->auth->restrict($this->permissionCreate);
            Template::set('toolbar_title', "Tambah Manage API");

            Template::render();
        }
        else {
            //$this->auth->restrict($this->permissionEdit);
            Template::set('data', $this->manage_api_model->find($record_id)); 
            Template::set('toolbar_title', "Ubah Manage API");

            Template::render();
        }
    }
    public function save(){
         // Validate the data
        $this->form_validation->set_rules($this->manage_api_model->get_validation_rules());
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

        $data = $this->manage_api_model->prep_data($this->input->post());
       
        $id = $this->input->post("id");
        if(isset($id) && !empty($id)){
            $this->manage_api_model->update($id,$data);
        }
        else $this->manage_api_model->insert($data);
        $response ['success']= true;
        $response ['msg']= "Transaksi berhasil";
        echo json_encode($response);    

    }
    public function delete($record_id){
        if($this->auth->has_permission($this->permissionDelete)){
            if ($this->manage_api_model->delete($record_id)) {
                log_activity($this->auth->user_id(), 'delete data API : ' . $record_id . ' : ' . $this->input->ip_address(), 'pegawai');
                //Template::set_message("Sukses Hapus data", 'success');
                echo "Sukses";
            }else{
                echo "Gagal";
            }    
        }
        else {
            if(IS_AJAX){
                echo json_encode(array(
                    'success'=>false,
                    'msg'=>'insufficient privilege'
                ));       
            }
            else {
                $this->auth->restrict($this->permissionDelete);
            }
        }
		exit();
    }
    public function index($PNS_ID=8){
       // Template::set_view("kepegawaian/tab_pane_riwayat_diklat_struktural");
        Template::set('PNS_ID', $PNS_ID);
        Template::set('TAB_ID', uniqid("TAB_CONTOH"));
        Template::render();
    }
}
