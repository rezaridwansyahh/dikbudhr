<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Riwayatkepangkatan extends Admin_Controller
{
    protected $permissionCreate = 'RiwayatKepangkatan.Kepegawaian.Create';
    protected $permissionDelete = 'RiwayatKepangkatan.Kepegawaian.Delete';
    protected $permissionEdit   = 'RiwayatKepangkatan.Kepegawaian.Edit';
    protected $permissionView   = 'RiwayatKepangkatan.Kepegawaian.View';
    protected $permissionUpdMandiri   = 'RiwayatKepangkatan.Kepegawaian.Updmandiri';
    protected $permissionVerifikasiS   = 'RiwayatKepangkatan.Kepegawaian.VerifikasiS';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/riwayat_kepangkatan_model');
        $this->load->model('pegawai/jenis_kp_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/golongan_model');
        $this->load->model('pegawai/update_mandiri_model');
    }
    public function ajax_list(){
        $this->load->library('convert');
 		$convert = new convert();
        $draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
        $PNS_ID = $this->input->post('PNS_ID');
        if(empty($PNS_ID)){
            ECHO "die";
            die();
        }
        $this->pegawai_model->where("PNS_ID",$PNS_ID);
        $pegawai_data = $this->pegawai_model->find_first_row();
       
		$length= $this->input->post('length');
		$start= $this->input->post('start');

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$this->riwayat_kepangkatan_model->where("PNS_ID",$pegawai_data->PNS_ID);  
		$total= $this->riwayat_kepangkatan_model->count_all();;
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
        
		
        
		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if($search!=""){
			$this->riwayat_kepangkatan_model->where('upper("PANGKAT") LIKE \''.strtoupper($search).'%\'');
		}
		$this->riwayat_kepangkatan_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
        
		$kolom = $iSortCol != "" ? $iSortCol : "NAMA";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->riwayat_kepangkatan_model->order_by($iSortCol,$sSortCol);
        $this->riwayat_kepangkatan_model->order_by("TMT_GOLONGAN","asc");
        $this->riwayat_kepangkatan_model->where("PNS_ID",$pegawai_data->PNS_ID);  
		
        $records=$this->riwayat_kepangkatan_model->find_all();
            
		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($search != "")
		{
			$this->riwayat_kepangkatan_model->where('upper("PANGKAT") LIKE \''.strtoupper($search).'%\'');
			$jum	= $this->riwayat_kepangkatan_model->count_all();
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}
		
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                 $STATUS_SATKER  = $record->STATUS_SATKER;
                $STATUS_BIRO    = $record->STATUS_BIRO;
                $msgverifikasi = "";
                if($STATUS_SATKER == "0")
                    $msgverifikasi = "<br><b class='text-red'>(Perlu Verifikasi Satker)</b>";
                if($STATUS_BIRO == "0")
                    $msgverifikasi = "<br><b class='text-red'>(Perlu Verifikasi Biro)</b>";
                elseif($STATUS_BIRO == "1")
                    $msgverifikasi = "<br><b class='text-blue'>(Sudah diverifikasi Biro)</b>";

                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->PANGKAT.$msgverifikasi;
                $row []  = $record->GOLONGAN;
                $row []  = $convert->fmtDate($record->TMT_GOLONGAN,"dd-mm-yyyy")."";
                $row []  = $record->MK_GOLONGAN_TAHUN." tahun/".$record->MK_GOLONGAN_BULAN." bulan";
                
                $btn_actions = array();
                if($record->KETERANGAN_BERKAS != ""){
                    $btn_actions  [] = "<a href='".base_url()."pegawai/riwayatkepangkatan/viewdoc/".$record->ID."' data-toggle='tooltip' title='Lihat Dokumen' tooltip='Lihat Dokumen ".$record->SK_NOMOR."' class='btn btn-sm btn-info show-modal'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionEdit))
                {    
                    $btn_actions  [] = "<a href='".base_url()."pegawai/riwayatkepangkatan/edit/".$PNS_ID."/".$record->ID."' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success show-modal-custom'><i class='fa fa-pencil'></i> </a>";
                }
                if($this->auth->has_permission("RiwayatKepangkatan.Kepegawaian.VerifikasiS")){
                    $btn_actions  [] = "<a href='".base_url()."pegawai/riwayatkepangkatan/verifikasi/".$PNS_ID."/".$record->ID."' data-toggle='tooltip' title='Verifikasi data' tooltip='Verifikasi data' class='btn btn-sm btn-warning show-modal-custom'><i class='fa fa-key'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionDelete))
                {    
                    $btn_actions  [] = "<a href='#' kode='$record->ID' data-toggle='tooltip' title='Hapus Data' class='btn btn-sm btn-danger btn-hapus-kepangkatan'><i class='fa fa-trash-o'></i> </a>";
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
        
        Template::set('golongans', $this->golongan_model->find_all());
        Template::set('jenis_kps', $this->jenis_kp_model->find_all());
        if(empty($record_id)){
            $this->auth->restrict($this->permissionCreate);
            Template::set_view("kepegawaian/riwayat_kepangkatan_crud");
            
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Riwayat Diklat Kepangkatan");

            Template::render();
        }
        else {
            //$this->auth->restrict($this->permissionEdit);
            Template::set_view("kepegawaian/riwayat_kepangkatan_crud");
            Template::set('detail_riwayat', $this->riwayat_kepangkatan_model->find($record_id));    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Riwayat Diklat Kepangkatan");

            Template::render();
        }
    }
    public function showmandiri($PNS_ID,$record_id=''){
        
        Template::set('golongans', $this->golongan_model->find_all());
        Template::set('jenis_kps', $this->jenis_kp_model->find_all());
        if(empty($record_id)){
            $this->auth->restrict($this->permissionUpdMandiri);
            Template::set_view("kepegawaian/riwayat_kepangkatan_crud_mandiri");
            
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Riwayat Diklat Kepangkatan");

            Template::render();
        }
        else {
            $this->auth->restrict($this->permissionUpdMandiri);
            Template::set_view("kepegawaian/riwayat_kepangkatan_crud_mandiri");
            Template::set('detail_riwayat', $this->riwayat_kepangkatan_model->find($record_id));    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Riwayat Diklat Kepangkatan");

            Template::render();
        }
    }
    public function add($PNS_ID){
        $this->show($PNS_ID);
    }
    public function addmandiri($PNS_ID){
        $this->showmandiri($PNS_ID);
    }
    public function edit($PNS_ID,$record_id=''){
        $this->show($PNS_ID,$record_id);
    }
    public function verifikasi($PNS_ID,$record_id=''){
        $this->show($PNS_ID,$record_id);
    }
    public function editmandiri($PNS_ID,$record_id=''){
        $this->showmandiri($PNS_ID,$record_id);
    }
    public function detil($PNS_ID,$record_id=''){
        Template::set('golongans', $this->golongan_model->find_all());
        Template::set('jenis_kps', $this->jenis_kp_model->find_all());
		Template::set_view("kepegawaian/detilkepangkatan");
		Template::set('detail_riwayat', $this->riwayat_kepangkatan_model->find($record_id));    
		Template::set('PNS_ID', $PNS_ID);
		Template::set('toolbar_title', "Detil Riwayat Diklat Kepangkatan");

		Template::render();
    }
    public function save(){
        $this->auth->restrict($this->permissionVerifikasiS);
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
         // Validate the data
        $this->form_validation->set_rules($this->riwayat_kepangkatan_model->get_validation_rules());
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

        $data = $this->riwayat_kepangkatan_model->prep_data($this->input->post());
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
        $this->pegawai_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $pegawai_data = $this->pegawai_model->find_first_row();  
        $NIP_BARU = $pegawai_data->NIP_BARU;
        $data["PNS_NIP"] = $pegawai_data->NIP_BARU;
        $data["PNS_ID"] = $pegawai_data->PNS_ID;
        $data["PNS_NAMA"] = $pegawai_data->NAMA;
        
        $jenis_kp_data = $this->jenis_kp_model->find($data['KODE_JENIS_KP']);
        $data["JENIS_KP"] = $jenis_kp_data->NAMA;
        
        $this->golongan_model->where("ID",$data['ID_GOLONGAN']);
        $golongan_data = $this->golongan_model->find_first_row();
        $data["GOLONGAN"] = $golongan_data->NAMA;
        $data["PANGKAT"] = $golongan_data->NAMA_PANGKAT;
        
        if(empty($data["TMT_GOLONGAN"])){
            unset($data["TMT_GOLONGAN"]);
        }
        if(empty($data["SK_TANGGAL"])){
            unset($data["SK_TANGGAL"]);
        }
        if(empty($data["TANGGAL_BKN"])){
            unset($data["TANGGAL_BKN"]);
        }
        if($this->input->post("STATUS_SATKER") == "1"){
            $data["STATUS_SATKER"]    = 1;
        }else{
            $data["STATUS_SATKER"]    = 0;
        }
        if($this->input->post("STATUS_BIRO") == "1"){
            $data["STATUS_BIRO"]    = 1;
        }else{
            $data["STATUS_BIRO"]    = 0;
        }
        if($this->input->post("PANGKAT_TERAKHIR") == "1"){
            $dataupdate = array();
            $dataupdate["PANGKAT_TERAKHIR"] = 0;
            $this->riwayat_kepangkatan_model->skip_validation(true);
            $this->riwayat_kepangkatan_model->update_where("PNS_ID",$this->input->post("PNS_ID"), $dataupdate);
            $dataupdate = array();
            $dataupdate['GOL_ID']           = $this->input->post('ID_GOLONGAN');
            $dataupdate['TMT_GOLONGAN']     = $this->input->post('TMT_GOLONGAN') ? $this->input->post('TMT_GOLONGAN') : null;
            $this->pegawai_model->skip_validation(true);
            $this->pegawai_model->update_where("PNS_ID",$this->input->post("PNS_ID"), $dataupdate);

            $data["PANGKAT_TERAKHIR"]    = 1;
        }

        $id_data = $this->input->post("ID");
        if(isset($id_data) && !empty($id_data)){
            $this->riwayat_kepangkatan_model->skip_validation(true);
            $this->riwayat_kepangkatan_model->update($id_data,$data);
            // Update status pada update data mandiri untuk riwayat pendidikan berdasarkan ID
            $dataupdate = array();
            $data_update['STATUS']          = $data["STATUS_BIRO"];
            $data_update['VERIFIKASI_BY']   = $this->auth->user_id();
            $data_update['VERIFIKASI_TGL']  = date("Y-m-d");
            $this->update_mandiri_model->where("KOLOM","RIWAYAT_KEPANGKATAN");
            $result = $this->update_mandiri_model->update_where("ID_TABEL",$id_data,$data_update);
            // end update histori update mandiri
            // add log
            log_activity_pegawai($NIP_BARU,$this->auth->user_id(),"Update data Riwayat Pangkat", 'rwt_pangkat');
        }
        else{
            $ID_GOLONGAN = $this->input->post('ID_GOLONGAN');
            $PNS_ID = $this->input->post('PNS_ID');
            $insert_id = $this->riwayat_kepangkatan_model->insert($data);
            // add log
            log_activity_pegawai($NIP_BARU,$this->auth->user_id(),"Tambah data Riwayat Pangkat", 'rwt_pangkat');
            
        } 
        $response ['success']= true;
        $response ['msg']= "Transaksi berhasil";
        echo json_encode($response);    

    }
    public function savemandiri(){
         // Validate the data
        $this->auth->restrict($this->permissionUpdMandiri);
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
        $this->form_validation->set_rules($this->riwayat_kepangkatan_model->get_validation_rules());
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

        $data = $this->riwayat_kepangkatan_model->prep_data($this->input->post());
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
        $this->pegawai_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $pegawai_data = $this->pegawai_model->find_first_row();  
        $data["PNS_NIP"] = $pegawai_data->NIP_BARU;
        $data["PNS_ID"] = $pegawai_data->PNS_ID;
        $data["PNS_NAMA"] = $pegawai_data->NAMA;
        
        $jenis_kp_data = $this->jenis_kp_model->find($data['KODE_JENIS_KP']);
        $data["JENIS_KP"] = $jenis_kp_data->NAMA;
        
        $this->golongan_model->where("ID",$data['ID_GOLONGAN']);
        $golongan_data = $this->golongan_model->find_first_row();
        $data["GOLONGAN"] = $golongan_data->NAMA;
        $data["PANGKAT"] = $golongan_data->NAMA_PANGKAT;
        
        if(empty($data["TMT_GOLONGAN"])){
            unset($data["TMT_GOLONGAN"]);
        }
        if(empty($data["SK_TANGGAL"])){
            unset($data["SK_TANGGAL"]);
        }
        if(empty($data["TANGGAL_BKN"])){
            unset($data["TANGGAL_BKN"]);
        }
        $id_data = $this->input->post("ID");
        if(isset($id_data) && !empty($id_data)){
            $this->riwayat_kepangkatan_model->update($id_data,$data);
        }
        else{
            $data["STATUS_SATKER"]  = 0;
            $data["STATUS_BIRO"]    = 0;
            $ID_GOLONGAN = $this->input->post('ID_GOLONGAN');
            $PNS_ID = $this->input->post('PNS_ID');
            $SK_NOMOR = $this->input->post('SK_NOMOR');
            $insert_id = $this->riwayat_kepangkatan_model->insert($data);
            // ADD TO NOTIFIKASI
            if($ID_GOLONGAN != ""){
                $data_update = array();
                $data_update['PNS_ID']      = $PNS_ID;
                $data_update['KOLOM']       = "RIWAYAT_KEPANGKATAN";
                $data_update['DARI']        = "-";
                $data_update['PERUBAHAN']   = "ID : ".$ID_GOLONGAN.", Nomor SK : ".$SK_NOMOR;
                $data_update['NAMA_KOLOM']  = "RIWAYAT KEPANGKATAN";
                $data_update['LEVEL_UPDATE']= 1; // LEVEL BIRO
                $data_update['UPDATE_TGL']  = date("Y-m-d");
                $data_update['ID_TABEL']    = $insert_id;
                $data_update['UPDATED_BY']  = $this->auth->user_id();
                $id_update = $this->update_mandiri_model->insert($data_update);
            }
            // END ADD TO NOTIFIKASI
        }
        $response ['success']= true;
        $response ['msg']= "Transaksi berhasil";
        echo json_encode($response);    

    }
    public function delete($record_id){
        $this->auth->restrict($this->permissionDelete);
		if ($this->riwayat_kepangkatan_model->delete($record_id)) {
			 log_activity($this->auth->user_id(), 'delete data Riwayat Diklat Kepangkatan : ' . $record_id . ' : ' . $this->input->ip_address(), 'pegawai');
			 Template::set_message("Sukses Hapus data", 'success');
			 echo "Sukses";
		}else{
			echo "Gagal";
		}

		exit();
    }
    public function index($PNS_ID='1552260645'){
        Template::set_view("kepegawaian/tab_pane_riwayat_kepangkatan");
        Template::set('PNS_ID', $PNS_ID);
        Template::set('TAB_ID', uniqid("TAB_CONTOH"));
        Template::render();
    }
    public function viewdoc($id)
    {
        if (empty($id)) {
            Template::set_message("ID tidak ditemukan", 'error');
        }
        $datadetil = $this->riwayat_kepangkatan_model->find($id);
        $FILE_BASE64 = $datadetil->FILE_BASE64;
        echo '<embed src="'.$FILE_BASE64.'" width="100%" height="700" alt="pdf">';
        die();
    }
}
