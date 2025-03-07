<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Riwayatkursus extends Admin_Controller
{
    protected $permissionCreate = 'Riwayatkursus.Kepegawaian.Create';
    protected $permissionDelete = 'Riwayatkursus.Kepegawaian.Delete';
    protected $permissionEdit   = 'Riwayatkursus.Kepegawaian.Edit';
    protected $permissionView   = 'Riwayatkursus.Kepegawaian.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/riwayat_kursus_model');
        $this->load->model('pegawai/pegawai_model');
    }
    public function ajax_list(){
        $this->load->library('convert');
 		$convert = new convert();
        $draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
        $PNS_ID = $this->input->post('PNS_ID');
        if(empty($PNS_ID)){
            //die("PNS ID KOSONG");
        }
        $this->pegawai_model->where("PNS_ID",$PNS_ID);
        $pegawai_data = $this->pegawai_model->find_first_row();
       
		$length= $this->input->post('length');
		$start= $this->input->post('start');

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		//$this->riwayat_kursus_model->where("PNS_ID",$pegawai_data->ID);
		$this->riwayat_kursus_model->where("PNS_NIP",$pegawai_data->NIP_BARU);
		$total= $this->riwayat_kursus_model->count_all();;
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
        
		
        
		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if($search!=""){
			$this->riwayat_kursus_model->where('upper("NAMA_KURSUS") LIKE \''.strtoupper($search).'%\'');
		}
		$this->riwayat_kursus_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
        
		$kolom = $iSortCol != "" ? $iSortCol : "TANGGAL_KURSUS";
		$sSortCol == "desc" ? "desc" : "asc";
		$this->riwayat_kursus_model->order_by($iSortCol,$sSortCol);
        
        //$this->riwayat_kursus_model->where("PNS_ID",$pegawai_data->PNS_ID); 
        $this->riwayat_kursus_model->where("PNS_NIP",$pegawai_data->NIP_BARU); 
		$this->riwayat_kursus_model->ORDER_BY($kolom,$sSortCol);
        $records=$this->riwayat_kursus_model->find_all();
            
		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($search != "")
		{
			$this->riwayat_kursus_model->where('upper("NAMA_KURSUS") LIKE \''.strtoupper($search).'%\'');
			$jum	= $this->riwayat_kursus_model->count_all();
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}
		
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->TIPE_KURSUS;
                $row []  = $record->JENIS_KURSUS;
                $row []  = $record->NAMA_KURSUS;
                $row []  = $record->LAMA_KURSUS. " Jam";
                $row []  = $convert->fmtDate($record->TANGGAL_KURSUS,"dd-mm-yyyy");
                $row []  = $record->NO_SERTIFIKAT;
                $btn_actions = array();
                if($record->KETERANGAN_BERKAS != ""){
                    $btn_actions  [] = "<a href='".base_url()."pegawai/riwayatkursus/viewdoc/".$record->ID."' data-toggle='tooltip' title='Lihat Dokumen' tooltip='Lihat Dokumen ".$record->NOMOR."' class='btn btn-sm btn-info show-modal'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                }
                 
                 
                if($this->auth->has_permission($this->permissionEdit))
                {    
                    $btn_actions  [] = "<a href='".base_url()."pegawai/riwayatkursus/edit/".$PNS_ID."/".$record->ID."' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success show-modal-custom'><i class='fa fa-pencil'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionDelete))
                {    
                    $btn_actions  [] = "<a href='#' kode='$record->ID' data-toggle='tooltip' title='Hapus Data' class='btn btn-sm btn-danger btn-hapus'><i class='fa fa-trash-o'></i> </a>";
                }

                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";

                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		die();
    }
    public function show($PNS_ID,$record_id=''){
        if(empty($record_id)){
            $this->auth->restrict($this->permissionCreate);
            Template::set_view("kepegawaian/riwayat_kursus_crud");
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Riwayat Kursus");
            Template::render();
        }
        else {
    		$datadetil = $this->riwayat_kursus_model->find($record_id); 
            $this->auth->restrict($this->permissionEdit);
            Template::set_view("kepegawaian/riwayat_kursus_crud");
           
            Template::set('detail_riwayat', $datadetil);    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Riwayat Kursus");

            Template::render();
        }
    }
    public function add($PNS_ID){
        $this->show($PNS_ID);
    }
    public function edit($PNS_ID,$record_id=''){
        $this->show($PNS_ID,$record_id);
    }
    public function detil($PNS_ID,$record_id=''){
    	$datadetil = $this->riwayat_kursus_model->find($record_id); 
            $this->auth->restrict($this->permissionView);
            Template::set_view("kepegawaian/riwayat_kursus_detil");
           
            Template::set('detail_riwayat', $datadetil);    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Riwayat Kursus");

            Template::render();
		Template::render();
    }
    
    public function save(){
         // Validate the data
        $this->form_validation->set_rules($this->riwayat_kursus_model->get_validation_rules());
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
		
		
        $data = $this->riwayat_kursus_model->prep_data($this->input->post());
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
            $file_name =$_FILES['file_dokumen']['name'];
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
       	$this->pegawai_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $pegawai_data = $this->pegawai_model->find_first_row();  
        $data["PNS_NIP"]    = $pegawai_data->NIP_BARU;
        $data["PNS_ID"]     = $pegawai_data->PNS_ID;
        
        if(empty($data["SK_TANGGAL"])){
            unset($data["SK_TANGGAL"]);
        }
        
        $id_data = $this->input->post("ID");
        if(isset($id_data) && !empty($id_data)){
            $this->riwayat_kursus_model->update($id_data,$data);
        }
        else $this->riwayat_kursus_model->insert($data);
        $response ['success']= true;
        $response ['msg']= "berhasil";
        echo json_encode($response);    

    }
    public function delete($record_id){
        $this->auth->restrict($this->permissionDelete);
		if ($this->riwayat_kursus_model->delete($record_id)) {
			 log_activity($this->auth->user_id(), 'delete data Riwayat Kursus : ' . $record_id . ' : ' . $this->input->ip_address(), 'pegawai');
			 Template::set_message("Sukses Hapus data", 'success');
			 echo "Sukses";
		}else{
			echo "Gagal";
		}

		exit();
    }
    public function index($PNS_ID='1552260645'){
        Template::set_view("kepegawaian/tab_pane_riwayat_kursus");
        Template::set('PNS_ID', $PNS_ID);
        Template::set('TAB_ID', uniqid("TAB_CONTOH"));
        Template::render();
    }
    public function viewdoc($id)
    {
        if (empty($id)) {
            Template::set_message("ID tidak ditemukan", 'error');
        }
        $datadetil = $this->riwayat_kursus_model->find($id);
        $FILE_BASE64 = $datadetil->FILE_BASE64;
        echo '<embed src="'.$FILE_BASE64.'" width="100%" height="700" alt="pdf">';
        die();
    }
}
