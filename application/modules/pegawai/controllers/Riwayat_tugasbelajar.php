<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Riwayat_tugasbelajar extends Admin_Controller
{
    protected $permissionCreate = 'Riwayattb.Kepegawaian.Create';
    protected $permissionDelete = 'Riwayattb.Kepegawaian.Delete';
    protected $permissionEdit   = 'Riwayattb.Kepegawaian.Edit';
    protected $permissionView   = 'Riwayattb.Kepegawaian.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/riwayat_tugasbelajar_model');
        $this->load->model('pegawai/pegawai_model');
		
    }
    public function ajax_list(){
        $this->load->library('convert');
 		$convert = new convert();
        $draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
        $NIP_BARU = $this->input->post('NIP_BARU');
       
		$length= $this->input->post('length');
		$start= $this->input->post('start');

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$this->riwayat_tugasbelajar_model->where("NIP",$NIP_BARU);
        if($search!=""){
            $this->riwayat_tugasbelajar_model->where('upper("NOMOR_SK") LIKE \''.strtoupper($search).'%\'');
        }
		$total= $this->riwayat_tugasbelajar_model->count_all();;
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
        
		$this->riwayat_tugasbelajar_model->where("NIP",$NIP_BARU); 
        if($search!=""){
			$this->riwayat_tugasbelajar_model->where('upper("NOMOR_SK") LIKE \''.strtoupper($search).'%\'');
		}
		$this->riwayat_tugasbelajar_model->limit($length,$start);
		
        $records=$this->riwayat_tugasbelajar_model->find_all();
            
		 
		$this->load->helper('dikbud');
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $nomorpengajuan = "";
                if($record->ID_PENGAJUAN != "")
                    $nomorpengajuan = "<br>No Pengajuan :<i>".$record->ID_PENGAJUAN."</i>";
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->NOMOR_SK."<br><i>TANGGAL : ".$convert->fmtDate($record->TANGGAL_SK,"dd-mm-yyyy")."</i>".$nomorpengajuan;
                $row []  = $record->UNIVERSITAS;
                $row []  = $record->FAKULTAS."<BR><i>".$record->PROGRAM_STUDI."</i>";

                $btn_actions = array();
                if($record->KETERANGAN_BERKAS != ""){
                    $btn_actions  [] = "<a href='".base_url()."pegawai/riwayat_tugasbelajar/viewdoc/".$record->ID."' data-toggle='tooltip' title='Lihat Dokumen' tooltip='Lihat Dokumen ".$record->NOMOR_SK."' class='btn btn-sm btn-info show-modal'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionEdit))
                {    
                    $btn_actions  [] = "<a href='".base_url()."pegawai/riwayat_tugasbelajar/edit/".$record->ID."' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success show-modal-custom'><i class='fa fa-pencil'></i> </a>";
                }
                
                if($this->auth->has_permission($this->permissionDelete))
                {    
                    $btn_actions  [] = "<a href='#' kode='$record->ID' data-toggle='tooltip' title='Hapus Data' class='btn btn-sm btn-danger btn-hapus-tb'><i class='fa fa-trash-o'></i> </a>";
                }

                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
 

                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		die();
    }
    public function show($NIP_BARU = "",$record_id=''){
        if(empty($record_id)){
            $this->auth->restrict($this->permissionCreate);
            Template::set_view("kepegawaian/riwayat_tb_crud");
            Template::set('NIP_BARU', $NIP_BARU);
            Template::set('toolbar_title', "Tambah Riwayat Tugas Belajar");
            Template::render();
        }
        else {
    		$datadetil = $this->riwayat_tugasbelajar_model->find($record_id); 
            $this->auth->restrict($this->permissionEdit);
            Template::set_view("kepegawaian/riwayat_tb_crud");
           
            Template::set('detail_riwayat', $datadetil);    
            Template::set('NIP_BARU', $NIP_BARU);
            Template::set('toolbar_title', "Ubah Riwayat Tugas Belajar");

            Template::render();
        }
    }
     
    public function add($NIP_BARU = ""){
        $this->show($NIP_BARU);
    }
    public function edit($ID=''){
        $this->riwayat_tugasbelajar_model->where("ID",$ID);
        $tb_data = $this->riwayat_tugasbelajar_model->first_row();  

        Template::set_view("kepegawaian/riwayat_tb_crud");
        Template::set('detail_riwayat', $tb_data);    
        Template::set('NIP_BARU', $tb_data->NIP);
        Template::set('toolbar_title', "Ubah Riwayat Tugas Belajar"); 
        Template::render();
    }
    public function detil($ID=''){
        
        $this->riwayat_tugasbelajar_model->where("ID",$ID);
        $tb_data = $this->riwayat_tugasbelajar_model->first_row();  
        $this->pegawai_model->where("NIP_BARU",$tb_data->NIP);
        $pegawai_data = $this->pegawai_model->find_first_row();  
        Template::set_view("kepegawaian/riwayat_tb_view");
        Template::set('detail_riwayat', $tb_data);    
        Template::set('NIP_BARU', $pegawai_data->NIP_BARU);
        Template::set('toolbar_title', "Lihat Riwayat Tugas Belajar"); 
		Template::render();
    }
    public function save(){
        //Validate the data
        $this->form_validation->set_rules($this->riwayat_tugasbelajar_model->get_validation_rules());
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
		
		
        $data = $this->riwayat_tugasbelajar_model->prep_data($this->input->post());
        // Make sure we only pass in the fields we want
        $base64     = "";
        $file_ext   = "";
        $file_size  = "";
        $file_tmp   = "";
        $type       = "";
        if (isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['name']) 
        {
            $errors=array();
            $allowed_ext = array('pdf');
            $file_name  = $_FILES['file_dokumen']['name'];
            // $file_name =$_FILES['image']['tmp_name'];
            $file_ext   = explode('.',$file_name);
            $jmltitik   = count($file_ext);
            $file_size  = $_FILES['file_dokumen']['size'];
            $file_tmp   = $_FILES['file_dokumen']['tmp_name'];
            $type       = $_FILES['file_dokumen']['type'];
            //echo $file_ext[1];echo "<br>";
            $data_base64 = file_get_contents($file_tmp);
            $base64 = 'data:' . $type . ';base64,' . base64_encode($data_base64);

            if(in_array(end($file_ext),$allowed_ext) === false)
            {
                $errors[]='Extension not allowed';
                $response['msg'] = "
                <div class='alert alert-block alert-error fade in note note-danger'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Ada kesalahan
                    </h4>
                    <p>Extension not allowed</p>
                </div>
                ";
                echo json_encode($response);
                exit();
            }
            if($file_size > 50097152)
            {
                $errors[]= 'File size must be under 50mb';
                $response['msg'] = "
                <div class='alert alert-block alert-error fade in note note-danger'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Ada kesalahan
                    </h4>
                    <p>File size must be under 50Mb</p>
                </div>
                ";
                echo json_encode($response);
                exit();
            }
        }
        if($base64 == "")
            unset($data['FILE_BASE64']);
        else{
            $data['FILE_BASE64']        = $base64;
            $data['KETERANGAN_BERKAS']  = isset($file_ext[$jmltitik-1]) ? $file_ext[$jmltitik-1]."  ".$type. " ".$file_size." KB" : null;
        }
        // end upload berkas
       	$this->pegawai_model->where("NIP_BARU",$this->input->post("NIP"));
        $pegawai_data = $this->pegawai_model->find_first_row();  
        
        if(empty($data["TANGGAL_SK"])){
            unset($data["TANGGAL_SK"]);
        }
        if(empty($data["MULAI_BELAJAR"])){
            unset($data["MULAI_BELAJAR"]);
        }
        if(empty($data["AKHIR_BELAJAR"])){
            unset($data["AKHIR_BELAJAR"]);
        }
        $id_data = $this->input->post("id");
        if(isset($id_data) && !empty($id_data)){
            $this->riwayat_tugasbelajar_model->update($id_data,$data);
        }
        else $this->riwayat_tugasbelajar_model->insert($data);
        $msgtb = "";
        // Aktif Kembali
        if($this->input->post("STATUS_TB") == "1"){
            $datapegawai['KEDUDUKAN_HUKUM_ID'] = "01";
            $result = $this->pegawai_model->update($pegawai_data->ID, $datapegawai);
            if($result){
                $msgtb = "Status Pegawai menjadi aktif Kembali";
            }
        }
        // Aktif Tugas Belajar
        if($this->input->post("STATUS_TB") == "2"){
            $datapegawai['KEDUDUKAN_HUKUM_ID'] = "03";
            $result = $this->pegawai_model->update($pegawai_data->ID, $datapegawai);  
            if($result){
                $msgtb = "Status Pegawai menjadi tugas belajar";
            }          
        }
        $response ['success']= true;
        $response ['msg']= "Berhasil ".$msgtb;
        echo json_encode($response);    

    }
    public function delete($record_id = null){
        $this->auth->restrict($this->permissionDelete);
		if ($this->riwayat_tugasbelajar_model->delete($record_id)) {
			 log_activity($this->auth->user_id(), 'delete data Riwayat Tugas Belajar : ' . $record_id . ' : ' . $this->input->ip_address(), 'pegawai');
			 Template::set_message("Sukses Hapus data", 'success');
			 echo "Sukses";
		}else{
			echo "Gagal";
		}

		exit();
    }
    public function index($NIP_BARU=''){
        Template::set_view("kepegawaian/tab_pane_tugasbelajar");
        Template::set('NIP_BARU', $NIP_BARU);
        Template::set('TAB_ID', uniqid("TAB_CONTOH"));
        Template::render();
    }
    public function viewdoc($id)
    {
        if (empty($id)) {
            Template::set_message("ID tidak ditemukan", 'error');
        }
        $datadetil = $this->riwayat_tugasbelajar_model->find($id);
        $FILE_BASE64 = $datadetil->FILE_BASE64;
        echo '<embed src="'.$FILE_BASE64.'" width="100%" height="700" alt="pdf">';
        die();
    }
}
