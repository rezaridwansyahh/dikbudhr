<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Riwayatjabatan extends Admin_Controller
{
    protected $permissionCreate = 'riwayatjabatan.Kepegawaian.Create';
    protected $permissionDelete = 'riwayatjabatan.Kepegawaian.Delete';
    protected $permissionEdit   = 'riwayatjabatan.Kepegawaian.Edit';
    protected $permissionVerifikasiS   = 'riwayatjabatan.Kepegawaian.VerifikasiS';
    protected $permissionUpdateMandiri   = 'riwayatjabatan.Kepegawaian.Updmandiri';
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/riwayat_jabatan_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/jenis_jabatan_model');
        $this->load->model('ref_jabatan/jabatan_model');
        $jenis_jabatans = $this->jenis_jabatan_model->find_all();
		Template::set('jenis_jabatans', $jenis_jabatans);
        $this->load->model('pegawai/update_mandiri_model');
		
		$this->load->model('pegawai/unitkerja_model');
		
    }
	
    public function ajax_list(){
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
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
		//$this->riwayat_jabatan_model->where("PNS_ID",$pegawai_data->ID);
		$this->riwayat_jabatan_model->where("PNS_NIP",$pegawai_data->NIP_BARU);
		$total= $this->riwayat_jabatan_model->count_all();;
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
        
		
        
		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if($search!=""){
			$this->riwayat_jabatan_model->where('upper("NAMA_JABATAN") LIKE \''.strtoupper($search).'%\'');
		}
		$this->riwayat_jabatan_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
        
		$kolom = $iSortCol != "" ? $iSortCol : "TMT_JABATAN";
		$sSortCol == "desc" ? "desc" : "asc";
		$this->riwayat_jabatan_model->order_by($iSortCol,$sSortCol);
        
        //$this->riwayat_jabatan_model->where("PNS_ID",$pegawai_data->PNS_ID); 
        $this->riwayat_jabatan_model->where("PNS_NIP",$pegawai_data->NIP_BARU); 
		$this->riwayat_jabatan_model->ORDER_BY($kolom,$sSortCol);
        $records=$this->riwayat_jabatan_model->find_all();
            
		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($search != "")
		{
			$this->riwayat_jabatan_model->where('upper("NAMA_JABATAN") LIKE \''.strtoupper($search).'%\'');
			$jum	= $this->riwayat_jabatan_model->count_all();
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
                $row []  = strtoupper($record->NAMA_JABATAN).$msgverifikasi;
                $row []  = $record->UNOR."<br>".$record->NAMA_SATKER;
                $row []  = $convert->fmtDate($record->TMT_JABATAN,"dd-mm-yyyy");

                $btn_actions = array();
                if($record->KETERANGAN_BERKAS != ""){
                    $btn_actions  [] = "<a href='".base_url()."pegawai/riwayatjabatan/viewdoc/".$record->ID."' data-toggle='tooltip' title='Lihat Dokumen' tooltip='Lihat Dokumen ".$record->SK_NOMOR."' class='btn btn-sm btn-info show-modal'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionEdit))
                {    
                    $btn_actions  [] = "<a href='".base_url()."pegawai/riwayatjabatan/edit/".$PNS_ID."/".$record->ID."' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success show-modal-custom'><i class='fa fa-pencil'></i> </a>";
                }
                if($this->auth->has_permission("riwayatjabatan.Kepegawaian.VerifikasiS")){
                    $btn_actions  [] = "<a href='".base_url()."pegawai/riwayatjabatan/verifikasi/".$PNS_ID."/".$record->ID."' data-toggle='tooltip' title='Verifikasi data' tooltip='Verifikasi data' class='btn btn-sm btn-warning show-modal-custom'><i class='fa fa-key'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionDelete))
                {    
                    $btn_actions  [] = "<a href='#' kode='$record->ID' data-toggle='tooltip' title='Hapus Data' class='btn btn-sm btn-danger btn-hapus-jabatan'><i class='fa fa-trash-o'></i> </a>";
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
    	Template::set('recsatker', $this->unitkerja_model->find_satker());
        if(empty($record_id)){
            $this->auth->restrict($this->permissionCreate);
            Template::set_view("kepegawaian/riwayat_jabatan_crud");
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Riwayat Jabatan");
            Template::render();
        }
        else {
    		//$this->auth->restrict($this->permissionEdit);
            $datadetil = $this->riwayat_jabatan_model->find($record_id); 
        	$recordunors = $this->unitkerja_model->find_all($datadetil->ID_SATUAN_KERJA);
        	Template::set('recunor', $recordunors);
        	
            
            Template::set_view("kepegawaian/riwayat_jabatan_crud");
           
            $jenis_jabatan = $datadetil->ID_JENIS_JABATAN;
			if($datadetil->ID_JENIS_JABATAN == ""){
				$recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($datadetil->ID_JABATAN));
				$jenis_jabatan = ISSET($recjabatan->JENIS_JABATAN) ? TRIM($recjabatan->JENIS_JABATAN) : "";
			}
	           $record_jabatan = $this->jabatan_model->find_select(trim($jenis_jabatan));
			Template::set('jabatans', $this->jabatan_model->find_select($jenis_jabatan));    
		    Template::set('jenis_jabatan', $jenis_jabatan);    
            Template::set('detail_riwayat', $datadetil);    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Riwayat Jabatan");

            Template::render();
        }
    }
    public function showmandiri($PNS_ID,$record_id=''){
        Template::set('recsatker', $this->unitkerja_model->find_satker());
        if(empty($record_id)){
            $this->auth->restrict($this->permissionUpdateMandiri);
            Template::set_view("kepegawaian/riwayat_jabatan_crud_mandiri");
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Riwayat Jabatan");
            Template::render();
        }
        else {
            $datadetil = $this->riwayat_jabatan_model->find($record_id); 
            $recordunors = $this->unitkerja_model->find_all($datadetil->ID_SATUAN_KERJA);
            Template::set('recunor', $recordunors);
            
            $this->auth->restrict($this->permissionEdit);
            Template::set_view("kepegawaian/riwayat_jabatan_crud");
           
            $jenis_jabatan = $datadetil->ID_JENIS_JABATAN;
          
            if($datadetil->ID_JENIS_JABATAN == ""){
                $recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($datadetil->ID_JABATAN));
                $jenis_jabatan = ISSET($recjabatan->JENIS_JABATAN) ? TRIM($recjabatan->JENIS_JABATAN) : "";
            }
      
            Template::set('jabatans', $this->jabatan_model->find_select($jenis_jabatan));    
            Template::set('jenis_jabatan', $jenis_jabatan);    
            Template::set('detail_riwayat', $datadetil);    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Riwayat Jabatan");

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
    public function editmandiri($PNS_ID,$record_id=''){
        $this->showmandiri($PNS_ID,$record_id);
    }
    public function verifikasi($PNS_ID,$record_id=''){
        $this->show($PNS_ID,$record_id);
    }
    public function detil($PNS_ID,$record_id=''){
    	Template::set('recsatker', $this->unitkerja_model->find_satker());
        $datadetil = $this->riwayat_jabatan_model->find($record_id); 
		$recordunors = $this->unitkerja_model->find_all($datadetil->ID_SATUAN_KERJA);
		$jenis_jabatan = $datadetil->ID_JENIS_JABATAN;
		  
		if($datadetil->ID_JENIS_JABATAN == ""){
			$recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($datadetil->ID_JABATAN));
			$jenis_jabatan = ISSET($recjabatan->JENIS_JABATAN) ? TRIM($recjabatan->JENIS_JABATAN) : "";
		}
	  
		Template::set('jabatans', $this->jabatan_model->find_select($jenis_jabatan));    
		Template::set('jenis_jabatan', $jenis_jabatan);    
		
		Template::set('recunor', $recordunors);
	   
		//$this->auth->restrict($this->permissionEdit);
		Template::set_view("kepegawaian/detiljabatan");
	  
		Template::set('jabatans', $this->jabatan_model->find_select($jenis_jabatan));    
		Template::set('detail_riwayat', $datadetil);    
		Template::set('PNS_ID', $PNS_ID);
		Template::set('toolbar_title', "Riwayat Jabatan");

		Template::render();
    }
    
    public function save(){
         // Validate the data
        $this->auth->restrict($this->permissionVerifikasiS);
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
        $this->form_validation->set_rules($this->riwayat_jabatan_model->get_validation_rules());
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
		
		
        $data = $this->riwayat_jabatan_model->prep_data($this->input->post());
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
        
        $rec_jenis = $this->jenis_jabatan_model->find($this->input->post("ID_JENIS_JABATAN"));
        $data["ID_JENIS_JABATAN"]   = $rec_jenis->ID;
        $data["JENIS_JABATAN"]      = $rec_jenis->NAMA;

        $rec_jabatan = $this->jabatan_model->find_by("KODE_JABATAN",$this->input->post("ID_JABATAN"));
        $data["ID_JABATAN_BKN"] = isset($rec_jabatan->KODE_BKN) ? $rec_jabatan->KODE_BKN : "";

        // struktur
        $rec_jabatan_struktural = $this->unitkerja_model->find_by("ID",$this->input->post("ID_UNOR")); // POST
        $data["ID_UNOR_BKN"]    = isset($rec_jabatan_struktural->ID) ? $rec_jabatan_struktural->ID : "";
        $data["ID_UNOR_BKN"]    = isset($rec_jabatan_struktural->ID) ? $rec_jabatan_struktural->ID : "";
        $data["UNOR"]           = isset($rec_jabatan_struktural->NAMA_UNOR) ? $rec_jabatan_struktural->NAMA_UNOR : "";
        // jika jabatannya struktural
        if($this->input->post("ID_JENIS_JABATAN") == "1"){
            if($this->input->post("ID_UNOR") != ""){
                $NAMA_JABATAN  = isset($rec_jabatan_struktural->NAMA_JABATAN) ? $rec_jabatan_struktural->NAMA_JABATAN : "";
                $data["NAMA_JABATAN"] = $NAMA_JABATAN;
                if($this->input->post("IS_ACTIVE") == "1"){
                    $UNOR_ID           = isset($rec_jabatan_struktural->ID) ? $rec_jabatan_struktural->ID : "";
                    // jika jabatan aktif set ke update tabel pejabat pada tabel unitkerja
                    $dataupdate = array(
                        'PEMIMPIN_PNS_ID'     => $this->input->post("PNS_ID"),
                        'JABATAN_ID'     => $this->input->post("ID_JABATAN")
                    );
                    $this->unitkerja_model->skip_validation(true);
                    $this->unitkerja_model->update($UNOR_ID, $dataupdate);
                }
            }
        }else{
            if($this->input->post("ID_JABATAN") != ""){
                $rec_jabatan = $this->jabatan_model->find_by("KODE_JABATAN",$this->input->post("ID_JABATAN"));
                $data["NAMA_JABATAN"] = $rec_jabatan->NAMA_JABATAN;

            }

        }
        if($this->input->post("IS_ACTIVE") == "1"){
           $dataupdate = array();
            $dataupdate['JABATAN_INSTANSI_REAL_ID']           = $this->input->post('ID_JABATAN');
            $dataupdate['JABATAN_INSTANSI_ID']           = $this->input->post('ID_JABATAN');
            $dataupdate['UNOR_ID']           = $this->input->post('ID_UNOR');
            $dataupdate['UNOR_INDUK_ID']           = $this->input->post('ID_SATUAN_KERJA');

            $this->pegawai_model->skip_validation(true);
            $this->pegawai_model->update_where("PNS_ID",$this->input->post("PNS_ID"), $dataupdate);
        }
        $data["ID_SATUAN_KERJA"]    = trim($this->input->post("ID_SATUAN_KERJA"));
        if(empty($data["TMT_JABATAN"])){
            unset($data["TMT_JABATAN"]);
        }
        if(empty($data["TANGGAL_SK"])){
            unset($data["TANGGAL_SK"]);
        }
        if(empty($data["TMT_PELANTIKAN"])){
            unset($data["TMT_PELANTIKAN"]);
        }
        
        $data["LAST_UPDATED"] 	= date("Y-m-d");
        $id_data = $this->input->post("ID");
        if(isset($id_data) && !empty($id_data)){
            $this->riwayat_jabatan_model->update($id_data,$data,$this->input->post());
            // Update status pada update data mandiri untuk riwayat pendidikan berdasarkan ID
            $dataupdate = array();
            $data_update['STATUS']          = $data["STATUS_BIRO"];
            $data_update['VERIFIKASI_BY']   = $this->auth->user_id();
            $data_update['VERIFIKASI_TGL']  = date("Y-m-d");
            $this->update_mandiri_model->where("KOLOM","RIWAYAT_JABATAN");
            $result = $this->update_mandiri_model->update_where("ID_TABEL",$id_data,$data_update);
            // end update histori update mandiri
            // add log
            log_activity_pegawai($NIP_BARU,$this->auth->user_id(),"Update data Riwayat Jabatan", 'rwt_jabatan');
        }
        else{
             $this->riwayat_jabatan_model->insert($data,$this->input->post());
             // add log
            log_activity_pegawai($NIP_BARU,$this->auth->user_id(),"Tambah data Riwayat Jabatan", 'rwt_jabatan');
         }
        $response ['success']= true;
        $response ['msg']= "berhasil";
        echo json_encode($response);    

    }
    public function savemandiri(){
         // Validate the data
        $this->auth->restrict($this->permissionUpdateMandiri);
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
        $this->form_validation->set_rules($this->riwayat_jabatan_model->get_validation_rules());
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
        
        
        $data = $this->riwayat_jabatan_model->prep_data($this->input->post());
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
        
        $rec_jenis = $this->jenis_jabatan_model->find($this->input->post("ID_JENIS_JABATAN"));
        $data["ID_JENIS_JABATAN"]   = $rec_jenis->ID;
        $data["JENIS_JABATAN"]      = $rec_jenis->NAMA;

        $rec_jabatan = $this->jabatan_model->find_by("KODE_JABATAN",$this->input->post("ID_JABATAN"));
        $data["ID_JABATAN_BKN"] = isset($rec_jabatan->KODE_BKN) ? $rec_jabatan->KODE_BKN : "";

        // struktur
        $rec_jabatan_struktural = $this->unitkerja_model->find_by("KODE_INTERNAL",$this->input->post("ID_UNOR")); // POST
        $data["ID_UNOR_BKN"]    = isset($rec_jabatan_struktural->ID) ? $rec_jabatan_struktural->ID : "";
        $data["ID_UNOR_BKN"]    = isset($rec_jabatan_struktural->ID) ? $rec_jabatan_struktural->ID : "";
        $data["UNOR"]           = isset($rec_jabatan_struktural->NAMA_UNOR) ? $rec_jabatan_struktural->NAMA_UNOR : "";
        // jika jabatannya struktural
        if($this->input->post("ID_JENIS_JABATAN") == "1"){
            if($this->input->post("ID_UNOR") != ""){
            $NAMA_JABATAN  = isset($rec_jabatan_struktural->NAMA_JABATAN) ? $rec_jabatan_struktural->NAMA_JABATAN : "";
            $data["NAMA_JABATAN"] = $NAMA_JABATAN;
            
            }
        }else{
            if($this->input->post("ID_JABATAN") != ""){
                $rec_jabatan = $this->jabatan_model->find_by("KODE_JABATAN",$this->input->post("ID_JABATAN"));
                $data["NAMA_JABATAN"] = $rec_jabatan->NAMA_JABATAN;

            }
        }
        $data["ID_SATUAN_KERJA"]    = trim($this->input->post("ID_SATUAN_KERJA"));
        if(empty($data["TMT_JABATAN"])){
            unset($data["TMT_JABATAN"]);
        }
        if(empty($data["TANGGAL_SK"])){
            unset($data["SK_TANGTANGGAL_SKGAL"]);
        }
        if(empty($data["TMT_PELANTIKAN"])){
            unset($data["TMT_PELANTIKAN"]);
        }
        
        $data["LAST_UPDATED"]   = date("Y-m-d");
        $id_data = $this->input->post("ID");
        if(isset($id_data) && !empty($id_data)){
            $this->riwayat_jabatan_model->updatemandiri($id_data,$data,$this->input->post());
        }
        else{
            $data["STATUS_SATKER"]  = 0;
            $data["STATUS_BIRO"]    = 0;
            $insert_id  = $this->riwayat_jabatan_model->insertmandiri($data,$this->input->post());
            $ID_JABATAN = $this->input->post("ID_JABATAN");
            $PNS_ID     = $this->input->post('PNS_ID');
            $NOMOR_SK   = $this->input->post('NOMOR_SK');
            if($ID_JABATAN != ""){
                $data_update = array();
                $data_update['PNS_ID']      = $PNS_ID;
                $data_update['KOLOM']       = "RIWAYAT_JABATAN";
                $data_update['DARI']        = "-";
                $data_update['PERUBAHAN']   = "ID : ".$ID_JABATAN.", Nomor SK : ".$NOMOR_SK;
                $data_update['NAMA_KOLOM']  = "RIWAYAT JABATAN";
                $data_update['LEVEL_UPDATE']= 1; // LEVEL BIRO
                $data_update['UPDATE_TGL']  = date("Y-m-d");
                $data_update['ID_TABEL']    = $insert_id;
                $data_update['UPDATED_BY']  = $this->auth->user_id();
                $id_update = $this->update_mandiri_model->insert($data_update);
            }
        }
        $response ['success']= true;
        $response ['msg']= "berhasil";
        echo json_encode($response);    

    }
    public function delete($record_id){
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
        $this->auth->restrict($this->permissionDelete);
		if ($this->riwayat_jabatan_model->delete($record_id)) {
			 log_activity($this->auth->user_id(), 'delete data Riwayat Jabatan : ' . $record_id . ' : ' . $this->input->ip_address(), 'pegawai');
			 Template::set_message("Sukses Hapus data", 'success');
			 echo "Sukses";
		}else{
			echo "Gagal";
		}

		exit();
    }
    public function index($PNS_ID='1552260645'){
        Template::set_view("kepegawaian/tab_pane_riwayat_jabatan");
        Template::set('PNS_ID', $PNS_ID);
        Template::set('TAB_ID', uniqid("TAB_CONTOH"));
        Template::render();
    }
    public function viewdoc($id)
    {
        if (empty($id)) {
            Template::set_message("ID tidak ditemukan", 'error');
        }
        $datadetil = $this->riwayat_jabatan_model->find($id);
        $FILE_BASE64 = $datadetil->FILE_BASE64;
        echo '<embed src="'.$FILE_BASE64.'" width="100%" height="700" alt="pdf">';
        die();
    }
}
