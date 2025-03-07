<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Manage_application extends Admin_Controller
{
    protected $permissionCreate = 'ManageApplication.Kepegawaian.Create';
    protected $permissionDelete = 'ManageApplication.Kepegawaian.Delete';
    protected $permissionEdit   = 'ManageApplication.Kepegawaian.Edit';
    protected $permissionView   = 'ManageApplication.Kepegawaian.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/Manage_application_model');
        
    }
    public function ajax_list(){
        $draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length');
		$start= $this->input->post('start');

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$total= $this->Manage_application_model->count_all();;
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();

		$this->Manage_application_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "NAMA";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->Manage_application_model->order_by($iSortCol,$sSortCol);
		$records=$this->Manage_application_model->find_all();

		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->app_name;
                $row []  = $record->key;
                $row []  = $record->satkers;
                $row []  = $record->controllers;
                $btn_actions = array();
                $btn_actions  [] = "
                    <a class='show-modal-custom' href='".base_url()."api/Manage_application/crud/".$record->id."'  data-toggle='modal' title='Ubah Data'><span class='fa-stack'>
					   	<i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-pencil fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
                $btn_actions  [] = "
                        <a href='#' kode='$record->id' class='btn-hapus' data-toggle='tooltip' title='Hapus data' >
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
    public function get_controller_list(){
        $json = array();
        $limit = 10;
        $page = $this->input->get('page') ? $this->input->get('page') : "1";
        $key= $this->input->get('term');
        $start = ($page-1)*$limit;
        $this->db->start_cache();
        if(!empty($key)){
            $this->db->like('lower("name")', strtolower($key));
            $this->db->or_like('lower("url")', strtolower($key));
		}
            $this->db->from("webservice.api_controllers");
            $this->db->stop_cache();
            $total = $this->db->get()->num_rows();
            $this->db->select('id,name || \' |  \' || url as text',false);
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
            echo json_encode($o);
    }
     public function get_satker_list(){
        $json = array();
        $limit = 10;
        $page = $this->input->get('page') ? $this->input->get('page') : "1";
        $q= $this->input->get('term');
        $start = ($page-1)*$limit;
		
        $this->load->model('pegawai/unitkerja_model');
        $this->db->like('lower("NAMA_UNOR")',strtolower($q),'BOTH');
        $this->db->order_by('"NAMA_UNOR"','asc');
        $satkers = $this->unitkerja_model->find_satker();
        $output = array();
        $output['results'] = array();
        foreach($satkers as $satker){
            $output['results'] [] = array(
                'id'=>$satker->ID,
                'text'=>$satker->NAMA_UNOR
            );
        }
        $output['pagination'] = array("more"=>false);
        echo json_encode($output); 
    }
    
    public function crud($record_id=''){
        if(empty($record_id)){
            $this->auth->restrict($this->permissionCreate);
            Template::set('toolbar_title', "Tambah Manage API");

            Template::render();
        }
        else {
            $data = $this->Manage_application_model->find($record_id);
            //get all satkers 
            if($data->satker_auth!=null){
                $satker_ids = explode(",",$data->satker_auth);
                $data->selectedSatkers = $this->db->from("hris.unitkerja")->where_in("ID",$satker_ids)->get()->result();
               
            }
            $data->selectedControllers = $this->db->select("act.*",false)->
            from("webservice.api_access acc")->
            join("webservice.api_controllers act","act.id = acc.controller_id")->
            where_in("acc.app_id",$record_id)->get()->result();

            Template::set('data', $data); 
            Template::set('toolbar_title', "Ubah Manage API");

            Template::render();
        }
    }
    public function save(){
         // Validate the data
        $this->form_validation->set_rules($this->Manage_application_model->get_validation_rules());
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

        $data = $this->Manage_application_model->prep_data($this->input->post());
       
        $id = $this->input->post("id");
        if(isset($id) && !empty($id)){
            $this->Manage_application_model->update($id,$data);
        }
        else {
            $this->Manage_application_model->insert($data);
            $id = $this->db->insert_id();
        }

        if($id){
            $Controllers_ID = $this->input->post("Controllers_ID");
            $this->db->where("app_id",$id);
            $this->db->delete("webservice.api_access");
            $data =  array();
            foreach($Controllers_ID as $row){
                $data[] = array(
                    'app_id'=>$id,
                    'controller_id'=>$row
                );
            }

            $this->db->insert_batch('webservice.api_access',$data);
            
           $Satker_Auth = $this->input->post("Satker_Auth");
           if(sizeof($Satker_Auth)>0){
               $satkers = implode(',',$Satker_Auth);
               $data = array(
                    'satker_auth'=>$satkers
               );
               $this->Manage_application_model->update($id,$data);
           }
           else {
               $data = array(
                    'satker_auth'=>null
               );
               $this->Manage_application_model->update($id,$data);
           }
            
        }
        $response ['success']= true;
        $response ['msg']= "Transaksi berhasil";
        echo json_encode($response);    

    }
    public function delete($record_id){
        if($this->auth->has_permission($this->permissionDelete)){
            if ($this->Manage_application_model->delete($record_id)) {
                log_activity($this->auth->user_id(), 'delete data API : ' . $record_id . ' : ' . $this->input->ip_address(), 'pegawai');
                Template::set_message("Sukses Hapus data", 'success');
                 echo json_encode(array(
                    'success'=>true,
                    'msg'=>'Data berhasil di hapus'
                ));  
            }else{
                echo json_encode(array(
                    'success'=>false,
                    'msg'=>'Data gagal di hapus'
                ));  
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
        Template::set_view("Manage_application/index");
        Template::set('PNS_ID', $PNS_ID);
        Template::set('TAB_ID', uniqid("TAB_CONTOH"));
        Template::render();
    }
}
