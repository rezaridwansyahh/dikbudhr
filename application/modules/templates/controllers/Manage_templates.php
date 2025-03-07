<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Manage_Templates extends Admin_Controller
{
    protected $permissionCreate = 'ManageTemplates.Masters.Create';
    protected $permissionDelete = 'ManageTemplates.Masters.Delete';
    protected $permissionEdit   = 'ManageTemplates.Masters.Edit';
    protected $permissionView   = 'ManageTemplates.Masters.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('templates/manage_templates_model');
        
    }
    public function ajax_list(){
        $draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length');
		$start= $this->input->post('start');

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$total= $this->manage_templates_model->count_all();;
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();

		$this->manage_templates_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "NAMA";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->manage_templates_model->order_by($iSortCol,$sSortCol);
		$records=$this->manage_templates_model->find_all();

		
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->name;
                $row []  = $record->template_file;
                $btn_actions = array();
                if($this->auth->has_permission($this->permissionEdit)){
                    $btn_actions  [] = "
                        <a class='show-modal-custom' href='".base_url()."templates/manage_templates/crud/".$record->id."'  data-toggle='modal' title='Ubah Data'><span class='fa-stack'>
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
            Template::set('toolbar_title', "Tambah Manage templates");

            Template::render();
        }
        else {
            //$this->auth->restrict($this->permissionEdit);
            Template::set('data', $this->manage_templates_model->find($record_id)); 
            Template::set('toolbar_title', "Ubah Manage templates");

            Template::render();
        }
    }
    public function save(){
        $this->load->helper('handle_upload');
		$uploadData = array();
		$upload = true;
		$id = "";
        $namafile = "";
        $template_folder = "assets/templates";
        if (isset($_FILES['userfile']) && is_array($_FILES['userfile']) && $_FILES['userfile']['error'] != 4)
        {
           $tmp_name = pathinfo($_FILES['userfile']['name'], PATHINFO_FILENAME);
           $ext = pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION);
           $newFileName = uniqid("template_").".".$ext;
           $uploadData = handle_upload('userfile',$template_folder,$newFileName);
            if (isset($uploadData['error']) && !empty($uploadData['error']))
            {
                $tipefile=$_FILES['userfile']['type'];
                $upload = false;
            }else{
                $namafile = $uploadData['data']['file_name'];
            }
        }else{
            die("File tidak ditemukan");
            log_activity($this->auth->user_id(), 'File tidak ditemukan : ' . $this->input->ip_address(), 'pegawai');
        } 	

        $data = $this->manage_templates_model->prep_data($this->input->post());
        $data['template_file'] = $namafile;
        $id = $this->input->post("id");
        if(isset($id) && !empty($id)){
            $this->manage_templates_model->update($id,$data);
        }
        else $this->manage_templates_model->insert($data);
        $response ['success']= true;
        $response ['msg']= "Transaksi berhasil";
        echo json_encode($response);    

    }
    public function delete($record_id){
        if($this->auth->has_permission($this->permissionDelete)){
            if ($this->manage_templates_model->delete($record_id)) {
                log_activity($this->auth->user_id(), 'delete data templates : ' . $record_id . ' : ' . $this->input->ip_address(), 'pegawai');
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
