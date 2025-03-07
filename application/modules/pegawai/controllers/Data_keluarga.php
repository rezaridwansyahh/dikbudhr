<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Data_keluarga extends Admin_Controller
{
    protected $permissionCreate = 'Data_Keluarga.Kepegawaian.Create';
    protected $permissionDelete = 'Data_Keluarga.Kepegawaian.Delete';
    protected $permissionEdit   = 'Data_Keluarga.Kepegawaian.Edit';
    protected $permissionView   = 'Data_Keluarga.Kepegawaian.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/orang_tua_model');
        $this->load->model('pegawai/istri_model');
        $this->load->model('pegawai/anak_model');
        $this->load->model('pegawai/pegawai_model');
		
    }
    public function ajax_list_ortu(){
        $this->load->library('convert');
 		$convert = new convert();
        $draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
        $PNS_ID = $this->input->post('PNS_ID');

        $this->pegawai_model->where("PNS_ID",$PNS_ID);
        $pegawai_data = $this->pegawai_model->find_first_row();
       
		$length= $this->input->post('length');
		$start= $this->input->post('start');

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$total= $this->orang_tua_model->count_all($pegawai_data->NIP_BARU);;
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
        
		 
		$this->orang_tua_model->limit($length,$start);
        $records=$this->orang_tua_model->find_all($pegawai_data->NIP_BARU);
            
		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		 
		$this->load->helper('dikbud');
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $hubungan = $record->HUBUNGAN == "1" ? "AYAH" : "IBU";
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $hubungan;
                $row []  = $record->GELAR_DEPAN." ".$record->NAMA." ".$record->GELAR_BELAKANG;
                $row []  = $convert->fmtDate($record->TANGGAL_LAHIR,"dd-mm-yyyy");
                $btn_actions = array();
                
                $btn_actions  [] = "
                    <a class='show-modal' href='".base_url()."pegawai/data_keluarga/detilortu/".$record->ID."'  data-toggle='modal' title='Lihat detil'><span class='fa-stack'>
					   	<i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-eye fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
                
                if($this->auth->has_permission("Data_keluarga.Kepegawaian.Edit")){
                	$btn_actions  [] = "
                    <a class='show-modal' href='".base_url()."pegawai/data_keluarga/addortu/".$record->PNS_ID."/".$record->ID."'  data-toggle='modal' title='Ubah Data'><span class='fa-stack'>
					   	<i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-pencil fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
                }
                if($this->auth->has_permission("Data_keluarga.Kepegawaian.Delete")){
                $btn_actions  [] = "
                        <a href='#' kode='$record->ID' class='btn-hapusortu' data-toggle='tooltip' title='Hapus data' >
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
    public function ajax_list_anak(){
        $this->load->library('convert');
        $convert = new convert();
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        $PNS_ID = $this->input->post('PNS_ID');

        $this->pegawai_model->where("PNS_ID",$PNS_ID);
        $pegawai_data = $this->pegawai_model->find_first_row();
       
        $length= $this->input->post('length');
        $start= $this->input->post('start');

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $total= $this->anak_model->count_all($pegawai_data->NIP_BARU);;
        $output=array();
        $output['draw']=$draw;

        
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
         
        $this->anak_model->limit($length,$start);
        $records=$this->anak_model->find_all($pegawai_data->NIP_BARU);
            
        /*Ketika dalam mode pencarian, berarti kita harus
        'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
        yang mengandung keyword tertentu
        */
         
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $jk = $record->JENIS_KELAMIN == "M" ? "LAK-LAKI" : "PEREMPUAN";
                $status = $record->STATUS_ANAK == "1" ? "KANDUNG" : "ANGKAT";
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->NAMA;
                $row []  = $jk;
                $row []  = $convert->fmtDate($record->TANGGAL_LAHIR,"dd-mm-yyyy");
                $row []  = $record->TEMPAT_LAHIR;
                $row []  = $status;
                $btn_actions = array();
                
                $btn_actions  [] = "
                    <a class='show-modal' href='".base_url()."pegawai/data_keluarga/detilanak/".$record->PNS_ID."/".$record->ID."'   data-toggle='modal' title='Lihat detil' tooltip='Lihat data'><span class='fa-stack'>
                        <i class='fa fa-square fa-stack-2x'></i>
                        <i class='fa fa-eye fa-stack-1x fa-inverse'></i>
                        </span>
                        </a>
                ";
                
                if($this->auth->has_permission("Data_keluarga.Kepegawaian.Edit")){
                    $btn_actions  [] = "
                    <a class='show-modal' href='".base_url()."pegawai/data_keluarga/addanak/".$record->PNS_ID."/".$record->ID."'  data-toggle='modal' title='Ubah Data'><span class='fa-stack'>
                        <i class='fa fa-square fa-stack-2x'></i>
                        <i class='fa fa-pencil fa-stack-1x fa-inverse'></i>
                        </span>
                        </a>
                ";
                }
                 
                if($this->auth->has_permission("Data_keluarga.Kepegawaian.Delete")){
                $btn_actions  [] = "
                        <a href='#' kode='$record->ID' class='btn-hapus' data-toggle='tooltip' title='Hapus data' >
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
    public function ajax_list_istri(){
        $this->load->library('convert');
        $convert = new convert();
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        $PNS_ID = $this->input->post('PNS_ID');

        $this->pegawai_model->where("PNS_ID",$PNS_ID);
        $pegawai_data = $this->pegawai_model->find_first_row();
       
        $length= $this->input->post('length');
        $start= $this->input->post('start');

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $this->istri_model->where("PNS_ID",$PNS_ID);
        $total= $this->istri_model->count_all($pegawai_data->NIP_BARU);;
        $output=array();
        $output['draw']=$draw;

        
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->istri_model->where("PNS_ID",$PNS_ID);
        $this->istri_model->limit($length,$start);
        $records=$this->istri_model->find_all($pegawai_data->NIP_BARU);
            
        /*Ketika dalam mode pencarian, berarti kita harus
        'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
        yang mengandung keyword tertentu
        */
        if($search != "")
        {
            $jum    = $this->istri_model->count_all();
            $output['recordsTotal']=$output['recordsFiltered']=$jum;
        }
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $hubungan = $record->HUBUNGAN == "1" ? "ISTRI" : "SUAMI";
                $STATUS = $record->STATUS == "1" ? "MENIKAH" : "CERAI";
                $PNS = $record->PNS == "1" ? "YA" : "BUKAN";
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->NAMA;
                $row []  = $STATUS;
                $row []  = $PNS;
                $btn_actions = array();
                
                $btn_actions  [] = "
                    <a class='show-modal' href='".base_url()."pegawai/data_keluarga/detilistri/".$record->ID."'  data-toggle='modal' title='Lihat detil'><span class='fa-stack'>
                        <i class='fa fa-square fa-stack-2x'></i>
                        <i class='fa fa-eye fa-stack-1x fa-inverse'></i>
                        </span>
                        </a>
                ";
                
                if($this->auth->has_permission("Data_keluarga.Kepegawaian.Edit")){
                    $btn_actions  [] = "
                    <a class='show-modal' href='".base_url()."pegawai/data_keluarga/addistri/".$record->PNS_ID."/".$record->ID."'  data-toggle='modal' title='Ubah Data'><span class='fa-stack'>
                        <i class='fa fa-square fa-stack-2x'></i>
                        <i class='fa fa-pencil fa-stack-1x fa-inverse'></i>
                        </span>
                        </a>
                ";
                }
                if($this->auth->has_permission("Data_keluarga.Kepegawaian.Delete")){
                $btn_actions  [] = "
                        <a href='#' kode='$record->ID' class='btn-hapus' data-toggle='tooltip' title='Hapus data' >
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
    public function showortu($PNS_ID,$record_id=''){
        $this->load->model('pegawai/agama_model');
        $agamas = $this->agama_model->find_all();
        Template::set('agamas', $agamas);
        if(empty($record_id)){
            $this->auth->restrict($this->permissionCreate);
            Template::set_view("data_keluarga/ortu_crud");
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Data orang tua");
            Template::render();
        }
        else {
    		$datadetil = $this->orang_tua_model->find($record_id); 
            $this->auth->restrict($this->permissionEdit);
            Template::set_view("data_keluarga/ortu_crud");
           
            Template::set('detail_riwayat', $datadetil);    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Data Orang tua");

            Template::render();
        }
    }
    public function showistri($PNS_ID,$record_id=''){
        if(empty($record_id)){
            $this->auth->restrict($this->permissionCreate);
            Template::set_view("data_keluarga/istri_crud");
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Data Pasangan");
            Template::render();
        }
        else {
            $datadetil = $this->istri_model->find($record_id); 
            $this->auth->restrict($this->permissionEdit);
            Template::set_view("data_keluarga/istri_crud");
           
            Template::set('detail_riwayat', $datadetil);    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Data Pasangan");

            Template::render();
        }
    }
    public function showanak($PNS_ID,$record_id=''){
        $this->istri_model->where("PNS_ID",$PNS_ID);
        $data_pasangans = $this->istri_model->find_all();
        Template::set('data_pasangans', $data_pasangans);    

        $this->pegawai_model->where("PNS_ID",$PNS_ID);
        $pegawai_data = $this->pegawai_model->find_first_row();  
        Template::set('pegawai_data', $pegawai_data);    
        if(empty($record_id)){
            $this->auth->restrict($this->permissionCreate);
            Template::set_view("data_keluarga/anak_crud");
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Data Anak");
            Template::render();
        }
        else {
            $datadetil = $this->anak_model->find($record_id); 
            $this->auth->restrict($this->permissionEdit);
            Template::set_view("data_keluarga/anak_crud");
           
            Template::set('detail_riwayat', $datadetil);    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Data Anak");

            Template::render();
        }
    }
    public function list_satker(){
        $this->db->order_by('NAMA_UNOR',"asc");
        $satkers = $this->unitkerja_model->find_satker();
        $data = array();
        foreach($satkers as $satker){
            //echo json_encode($satker);
            $data[] = array(
                'id'=>$satker->ID,
                'text'=>$satker->NAMA_UNOR,
            );
        }
        $output = array(
            'results'=>$data 
        );
        echo json_encode($output);
    }
    public function addortu($PNS_ID,$ID = ""){
        $this->showortu($PNS_ID,$ID);
    }
    public function addistri($PNS_ID,$ID = ""){
        $this->showistri($PNS_ID,$ID);
    }
    public function detilistri($ID){
        $datadetil = $this->istri_model->find($ID); 
        $this->auth->restrict($this->permissionEdit);
        Template::set_view("data_keluarga/istri_crud");
       
        Template::set('detail_riwayat', $datadetil);    
        Template::set('ID', $ID);
        Template::set('view', false);
        Template::set('toolbar_title', "View Data Pasangan");

        Template::render();
    }
    
    public function addanak($PNS_ID,$ID = ""){
        $this->showanak($PNS_ID,$ID);
    }
    public function detilanak($PNS_ID,$ID){

        $this->istri_model->where("PNS_ID",$PNS_ID);
        $data_pasangans = $this->istri_model->find_all();
        Template::set('data_pasangans', $data_pasangans);    
        
        $datadetil = $this->anak_model->find($ID); 
        $this->auth->restrict($this->permissionEdit);
        Template::set_view("data_keluarga/anak_crud");
       
        Template::set('detail_riwayat', $datadetil);    
        Template::set('PNS_ID', $PNS_ID);
        Template::set('toolbar_title', "View Data Anak");
        Template::set('view', false);
        Template::render();
    }
    public function edit($ref_id=''){
        $this->riwayat_kgb_model->where("ref",$ref_id);
        $kgb_data = $this->riwayat_kgb_model->first_row();  
        $this->pegawai_model->where("ID",$kgb_data->pegawai_id);
        $pegawai_data = $this->pegawai_model->find_first_row();  

        $list_golongan = $this->golongan_model->find_all();
       
        $selectedUnitKerjaInduk = $this->unitkerja_model->find_by_id($kgb_data->unit_kerja_induk_id);
        
        Template::set_view("kepegawaian/riwayat_kgb_crud");
        Template::set('detail_riwayat', $kgb_data);    
        Template::set('PNS_ID', $pegawai_data->PNS_ID);
        Template::set('list_golongan',$list_golongan);
        Template::set('toolbar_title', "Ubah Riwayat KGB");
        Template::set('selectedUnitKerjaInduk',$selectedUnitKerjaInduk);   
        Template::render();
    }
    public function detil($ref_id=''){
        $list_golongan = $this->golongan_model->find_all();
        $this->riwayat_kgb_model->where("ref",$ref_id);
        $kgb_data = $this->riwayat_kgb_model->first_row();  
        $selectedUnitKerjaInduk = $this->unitkerja_model->find_by_id($kgb_data->unit_kerja_induk_id);
        
        $this->pegawai_model->where("ID",$kgb_data->pegawai_id);
        $pegawai_data = $this->pegawai_model->find_first_row();  
        $this->auth->restrict($this->permissionEdit);
        Template::set_view("kepegawaian/riwayat_kgb_detil");
        Template::set('list_golongan',$list_golongan);
        Template::set('detail_riwayat', $kgb_data);    
        Template::set('PNS_ID', $pegawai_data->PNS_ID);
        Template::set('toolbar_title', "Riwayat Pekerjaan");
        Template::set('selectedUnitKerjaInduk',$selectedUnitKerjaInduk);   
        Template::render();
		Template::render();
    }
    public function contentorangtua(){
        $PNS_ID = $this->input->post('PNS_ID');
        $output = "";
        // AYAH
        $this->orang_tua_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $this->orang_tua_model->where("HUBUNGAN",1);
        $orangtua_data = $this->orang_tua_model->find_all();
        // IBU
        $this->orang_tua_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $this->orang_tua_model->where("HUBUNGAN",2);
        $dataibus = $this->orang_tua_model->find_all();
        // ISTRI
        $this->istri_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $dataistri = $this->istri_model->find_all();

        // anak
        $this->anak_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $dataanak = $this->anak_model->find_all();

        $output .= $this->load->view('data_keluarga/contentorangtua',array('orangtua_data'=>$orangtua_data,'dataibus'=>$dataibus,'dataistri'=>$dataistri,'dataanak'=>$dataanak),true);   
         
        echo $output;
        die();
    }
    public function saveortu(){
         // Validate the data
        $this->form_validation->set_rules($this->orang_tua_model->get_validation_rules());
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
		
		
        $data = $this->orang_tua_model->prep_data($this->input->post());
       	$this->pegawai_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $pegawai_data = $this->pegawai_model->find_first_row(); 
        
        $data["NIP"] = $pegawai_data->NIP_BARU;
        
        $data['TANGGAL_LAHIR']  = $this->input->post('TANGGAL_LAHIR') ? $this->input->post('TANGGAL_LAHIR') : null;
        $data['TGL_MENINGGAL']  = $this->input->post('TGL_MENINGGAL') ? $this->input->post('TGL_MENINGGAL') : null;

        
        $id_data = $this->input->post("id_data");
        if(isset($id_data) && !empty($id_data)){
            $this->orang_tua_model->update($id_data,$data);
        }
        else $this->orang_tua_model->insert($data);
        $response ['success']= true;
        $response ['msg']= "berhasil";
        echo json_encode($response);    

    }
    public function saveistri(){
         // Validate the data
        $this->form_validation->set_rules($this->istri_model->get_validation_rules());
        $msg = "";
        $status = false;
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
        $data = $this->istri_model->prep_data($this->input->post());
        $this->pegawai_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $pegawai_data = $this->pegawai_model->find_first_row(); 
        $jk = trim($pegawai_data->JENIS_KELAMIN);
        if($jk == "M")
            $data["HUBUNGAN"] = "1";    
        else
            $data["HUBUNGAN"] = "2";    

        $data["NIP"] = $pegawai_data->NIP_BARU;
        
        $data['TANGGAL_MENINGGAL']  = $this->input->post('TANGGAL_MENINGGAL') ? $this->input->post('TANGGAL_MENINGGAL') : null;
        $data['TANGGAL_MENIKAH']  = $this->input->post('TANGGAL_MENIKAH') ? $this->input->post('TANGGAL_MENIKAH') : null;
        $data['TANGGAL_CERAI']  = $this->input->post('TANGGAL_CERAI') ? $this->input->post('TANGGAL_CERAI') : null;
        
        $id_data = $this->input->post("id_data");
        if(isset($id_data) && !empty($id_data)){
            $this->istri_model->update($id_data,$data);
        }
        else{
            $result = $this->istri_model->insert($data);  
            if($result){
                $msg = "Berhasil";
                $status = true;
            }else{
                $msg = "Gagal ".$this->istri_model->error;
            }
        } 
        $response ['success']= $status;
        $response ['msg']= $msg;
        echo json_encode($response);    

    }
    public function saveanak(){
         // Validate the data
        $this->form_validation->set_rules($this->anak_model->get_validation_rules());
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
        $data = $this->anak_model->prep_data($this->input->post());
        $this->pegawai_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $pegawai_data = $this->pegawai_model->find_first_row(); 
        
        $data["NIP"] = $pegawai_data->NIP_BARU;
        $data['TANGGAL_LAHIR']  = $this->input->post('TANGGAL_LAHIR') ? $this->input->post('TANGGAL_LAHIR') : null;
        $data['PASANGAN']  = $this->input->post('PASANGAN') ? $this->input->post('PASANGAN') : null;
        $id_data = $this->input->post("id_data");
        if(isset($id_data) && !empty($id_data)){
            $this->anak_model->update($id_data,$data);
        }
        else $this->anak_model->insert($data);
        $response ['success']= true;
        $response ['msg']= "berhasil";
        echo json_encode($response);    

    }
    public function deleteanak($record_id){
        $this->auth->restrict($this->permissionDelete);
		if ($this->anak_model->delete($record_id)) {
			 log_activity($this->auth->user_id(), 'delete data Anak : ' . $record_id . ' : ' . $this->input->ip_address(), 'pegawai');
			 Template::set_message("Sukses Hapus data", 'success');
			 echo "Sukses";
		}else{
			echo "Gagal";
		}
		exit();
    }
    public function deleteistri($record_id){
        $this->auth->restrict($this->permissionDelete);
        if ($this->istri_model->delete($record_id)) {
             log_activity($this->auth->user_id(), 'delete data pasangan (istri/suami) : ' . $record_id . ' : ' . $this->input->ip_address(), 'pegawai');
             Template::set_message("Sukses Hapus data", 'success');
             echo "Sukses";
        }else{
            echo "Gagal";
        }
        exit();
    }
    public function deleteortu($record_id){
        $this->auth->restrict($this->permissionDelete);
        if ($this->orang_tua_model->delete($record_id)) {
             log_activity($this->auth->user_id(), 'delete data orangtua : ' . $record_id . ' : ' . $this->input->ip_address(), 'pegawai');
             Template::set_message("Sukses Hapus data", 'success');
             echo "Sukses";
        }else{
            echo "Gagal";
        }
        exit();
    }
    public function index($PNS_ID='1552260645'){
        Template::set_view("kepegawaian/tab_pane_riwayat_kgb");
        Template::set('PNS_ID', $PNS_ID);
        Template::set('TAB_ID', uniqid("TAB_CONTOH"));
        Template::render();
    }
}
