<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Riwayatpindahunitkerja extends Admin_Controller
{
    protected $permissionCreate = 'RiwayatPindahUnitKerja.Kepegawaian.Create';
    protected $permissionDelete = 'RiwayatPindahUnitKerja.Kepegawaian.Delete';
    protected $permissionEdit   = 'RiwayatPindahUnitKerja.Kepegawaian.Edit';
    protected $permissionView   = 'RiwayatPindahUnitKerja.Kepegawaian.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/riwayat_pindah_unit_kerja_model');
        $this->load->model('pegawai/jenis_kp_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/instansi_model');
        $this->load->model('pegawai/unitkerja_model');
    }
    public function get_unor_ajax(){
        $json = array();
        $limit = 10;
        $page = $this->input->get('page') ? $this->input->get('page') : "1";
        $q= $this->input->get('term');
        $start = ($page-1)*$limit;
        
        if(!empty($q)){
            $this->db->start_cache();
            $this->db->like('lower("NAMA_ESELON_I")', strtolower($q));
            $this->db->or_like('lower("NAMA_ESELON_II")', strtolower($q)); 
            $this->db->or_like('lower("NAMA_ESELON_III")', strtolower($q)); 
            $this->db->or_like('lower("NAMA_ESELON_IV")', strtolower($q)); 

            $this->db->from("hris.unitkerja");
            $this->db->stop_cache();
            $total = $this->db->get()->num_rows();
            //$this->db->select('ID as id,"NAMA_ESELON_I" ||\'-\'||"NAMA_ESELON_II" ||\'-\'||"NAMA_ESELON_III" ||\'-\'||"NAMA_ESELON_IV" as text');
            $this->db->limit($limit,$start);
            $this->db->like('lower("NAMA_ESELON_I")', strtolower($q));    
            $this->db->or_like('lower("NAMA_ESELON_II")', strtolower($q)); 
            $this->db->or_like('lower("NAMA_ESELON_III")', strtolower($q)); 
            $this->db->or_like('lower("NAMA_ESELON_IV")', strtolower($q)); 
            $data = $this->db->get()->result();

            $endCount = $start + $limit;
            $morePages = $endCount > $total;
            $rs = array();
            foreach($data as $record){
                $rs [] = array(
                            'id'=>$record->ID,
                            'text'=>$this->unitkerja_model->getFullNameWithData($record)
                    );
            }
            $o = array(
            "results" => $rs,
                "pagination" => array(
                    "more" =>$morePages,
                )
            );   
            $this->db->flush_cache();
            $json = $o;
        }
            echo json_encode($json);
    }
    public function get_instansi_ajax(){
        $json = array();
        $limit = 10;
        $page = $this->input->get('page') ? $this->input->get('page') : "1";
        $q= $this->input->get('term');
        $start = ($page-1)*$limit;
        
        if(!empty($q)){
            $this->db->start_cache();
            $this->db->like('lower("NAMA")', strtolower($q));
            $this->db->from("hris.instansi");
            $this->db->stop_cache();
            $total = $this->db->get()->num_rows();
            $this->db->select('ID as id,"NAMA" as text');
            $this->db->limit($limit,$start);

            $data = $this->db->get()->result();

            $endCount = $start + $limit;
            $morePages = $endCount > $total;
            $o = array(
            "results" => $data,
                "pagination" => array(
                    "more" =>$morePages,
                )
            );   
            $this->db->flush_cache();
            $json = $o;
        }
            echo json_encode($json);
    }
    
     public function mig2(){
        $uks = array();
        $uks2 = array();
        $unitkerjas = $this->unitkerja_model->find_all();
        foreach($unitkerjas as $record){
            $uks[$record->KODE_INTERNAL] = $record->ID;
        }
        $counter = 1;
        foreach($unitkerjas as $record){
            $split = explode(".",$record->KODE_INTERNAL);
            $parent = "";
            $has_parent = false;
            if(!$has_parent && $split[3]!="00"){
                $parent = $split[0].".".$split[1].".".$split[2].".00";
                if(isset($uks[$parent])){
                    $has_parent=true;
                }
            }        
            if(!$has_parent && $split[2]!="00"){
                $parent = $split[0].".".$split[1].".00".".00";
                if(isset($uks[$parent])){
                    $has_parent=true;
                }
            }        
            if(!$has_parent && $split[1]!="00"){
                $parent = $split[0].".00".".".".00".".00";
                if(isset($uks[$parent])){
                    $has_parent=true;
                }
            }   
            if(!$has_parent && $split[0]!="00"){
                $length = strlen($split[0]);
                if($split[0][$length-0]=="0"){
                    $parent = "00".".00".".00".".00";
                    if(isset($uks[$parent])){
                        $has_parent=true;
                    }
                }
                else {
                    $parent = substr($split[0],0,$length-1)."0".".00".".00".".00";
                    if(isset($uks[$parent])){
                        $has_parent=true;
                    }
                }
            }
            if($record->KODE_INTERNAL =='E10.00.01.00'){
               // echo $record->KODE_INTERNAL."<Br>";
               // echo $parent."<Br>";
               // exit();
            }
            if($has_parent){
                $parent_id =  $uks[$parent]; 
                $uks2[$record->KODE_INTERNAL]=array(
                    'KODE_INTERNAL'=>$record->KODE_INTERNAL,
                    'DIATASAN_ID'=>$parent_id
                );   
            }
          // if($counter>10){
              //  echo json_encode($uks2);
                //$this->db->update_batch('hris.unitkerja',$uks2,"KODE_INTERNAL");
          //      //break;
          // }
          $counter++;
        }
        //echo json_encode($uks2);
        $this->db->update_batch('hris.unitkerja',$uks2,"KODE_INTERNAL");
    }
    public function mig(){
        $uks = array();
        $uks2 = array();
        $unitkerjas = $this->unitkerja_model->find_all();
        foreach($unitkerjas as $record){
            $uks[$record->KODE_INTERNAL] = $record->ID;
            $uks2[$record->KODE_INTERNAL] = null;
        }
        foreach($unitkerjas as $record){
            $split = explode(".",$record->KODE_INTERNAL);
            $parent = "";

            if($split[0]=="00"){
                $parent = "00".".00".".00".".00";
            }
            else if($split[1]=="00"){
                $parent = "00".".00".".00".".00";
            }
            else if($split[2]=="00"){
                $parent = $split[0].".00".".00".".00";
            }
            else if($split[3]=="00"){
                $parent = $split[0].".".$split[1].".00".".00";
            }
            else {
                $parent = $split[0].".".$split[1].".".$split[2].".00";
            }
            $parent_id =  $uks[$parent];
            
            $uks2[$record->KODE_INTERNAL]=array(
                'KODE_INTERNAL'=>$record->KODE_INTERNAL,
                'DIATASAN_ID'=>$parent_id
            );
           
        }
        echo json_encode($uks2);
        $this->db->update_batch('hris.unitkerja',$uks2,"KODE_INTERNAL");
    }
    public function ajax_list(){
        
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
		$this->riwayat_pindah_unit_kerja_model->where("PNS_ID",$pegawai_data->PNS_ID);
		$total= $this->riwayat_pindah_unit_kerja_model->count_all();;
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
        
		
        
		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if($search!=""){
			$this->riwayat_pindah_unit_kerja_model->where('upper("NAMA_UNOR_BARU") LIKE \''.strtoupper($search).'%\'');
		}
		$this->riwayat_pindah_unit_kerja_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
        
		$kolom = $iSortCol != "" ? $iSortCol : "NAMA";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->riwayat_pindah_unit_kerja_model->order_by($iSortCol,$sSortCol);
        
        $this->riwayat_pindah_unit_kerja_model->where("PNS_ID",$pegawai_data->PNS_ID);  
		
        $records=$this->riwayat_pindah_unit_kerja_model->find_all();
            
		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($search != "")
		{
			$this->riwayat_pindah_unit_kerja_model->where('upper("NAMA_UNOR_BARU") LIKE \''.strtoupper($search).'%\'');
			$jum	= $this->riwayat_pindah_unit_kerja_model->count_all();
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}
		
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->NAMA_UNOR_BARU;
                $row []  = $record->NAMA_SATUAN_KERJA;
                $row []  = $record->SK_NOMOR;
                $row []  = $record->SK_TANGGAL;

                $btn_actions = array();
                if($record->KETERANGAN_BERKAS != ""){
                    $btn_actions  [] = "<a href='".base_url()."pegawai/riwayatpindahunitkerja/viewdoc/".$record->ID."' data-toggle='tooltip' title='Lihat Dokumen' tooltip='Lihat Dokumen ".$record->SK_NOMOR."' class='btn btn-sm btn-info show-modal'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionEdit))
                {    
                    $btn_actions  [] = "<a href='".base_url()."pegawai/riwayatpindahunitkerja/edit/".$PNS_ID."/".$record->ID."' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success show-modal-custom'><i class='fa fa-pencil'></i> </a>";
                }
                
                if($this->auth->has_permission($this->permissionDelete))
                {    
                    $btn_actions  [] = "<a href='#' kode='$record->ID' data-toggle='tooltip' title='Hapus Data' class='btn btn-sm btn-danger btn-hapus-pindahunit'><i class='fa fa-trash-o'></i> </a>";
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
        Template::set('jenis_kps', $this->jenis_kp_model->find_all());
       //Template::set('unit_organisasis', $this->unitkerja_model->find_all());
      
        if(empty($record_id)){
            $this->auth->restrict($this->permissionCreate);
            Template::set_view("kepegawaian/riwayat_pindah_unit_kerja_crud");
            
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Riwayat Pindah Unit Kerja");

            Template::render();
        }
        else {
            $this->auth->restrict($this->permissionEdit);
           
            Template::set_view("kepegawaian/riwayat_pindah_unit_kerja_crud");
            $detailRiwayat = $this->riwayat_pindah_unit_kerja_model->find($record_id);
            //die($detailRiwayat->ID_SATUAN_KERJA." addas");
            $recordunors = $this->unitkerja_model->find_all(trim($detailRiwayat->ID_SATUAN_KERJA));
        	Template::set('recunor', $recordunors);
        	
            Template::set('detail_riwayat',$detailRiwayat );    
            Template::set('selectedUnorBaru',$this->unitkerja_model->find($detailRiwayat->ID_UNOR_BARU));
            Template::set('selectedInstansiBaru',$this->instansi_model->find($detailRiwayat->ID_INSTANSI));
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Riwayat Pindah Unit Kerja");

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
     	Template::set('recsatker', $this->unitkerja_model->find_satker());
       	Template::set('jenis_kps', $this->jenis_kp_model->find_all());
    	Template::set('unit_organisasis', $this->unitkerja_model->find_all());
		Template::set_view("kepegawaian/detilunitkerja");
		$detailRiwayat = $this->riwayat_pindah_unit_kerja_model->find($record_id);
            //die($detailRiwayat->ID_SATUAN_KERJA." addas");
            $recordunors = $this->unitkerja_model->find_all(trim($detailRiwayat->ID_SATUAN_KERJA));
        	Template::set('recunor', $recordunors);
        	
            Template::set('detail_riwayat',$detailRiwayat );    
            Template::set('selectedUnorBaru',$this->unitkerja_model->find($detailRiwayat->ID_UNOR_BARU));
            Template::set('selectedInstansiBaru',$this->instansi_model->find($detailRiwayat->ID_INSTANSI));
            Template::set('PNS_ID', $PNS_ID);
		Template::set('toolbar_title', "Detil Riwayat Pindah Unit Kerja");
		Template::render();
    }
    public function save(){
        $this->form_validation->set_rules($this->riwayat_pindah_unit_kerja_model->get_validation_rules());
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

        $data = $this->riwayat_pindah_unit_kerja_model->prep_data($this->input->post());
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
        $NIP_BARU = $pegawai_data->NIP_BARU;
        $pegawai_data = $this->pegawai_model->find_first_row();  
        $data["PNS_ID"] = $pegawai_data->PNS_ID;
        $data["PNS_NAMA"] = $pegawai_data->NAMA;
        $data["PNS_NIP"] = $pegawai_data->NIP_BARU;
       
        $unor_baru_data = $this->unitkerja_model->find($data['ID_UNOR_BARU']);
        //$data["NAMA_UNOR_BARU"] = $unor_baru_data->NAMA_ESELON_I."-".$unor_baru_data->NAMA_ESELON_II."-".$unor_baru_data->NAMA_ESELON_III."-".$unor_baru_data->NAMA_ESELON_IV;
        $data["NAMA_UNOR_BARU"] = $unor_baru_data->NAMA_UNOR;
        $instansi_baru_data = $this->instansi_model->find($data['ID_INSTANSI']);
        $data["NAMA_INSTANSI"] = $instansi_baru_data->NAMA;
        
        $recordunors = $this->unitkerja_model->find($this->input->post("ID_SATUAN_KERJA"));
        $NAMA_SATKER = ISSET($recordunors->NAMA_UNOR) ? $recordunors->NAMA_UNOR : "";
 //       die($NAMA_SATKER."ini");
        $data["NAMA_SATUAN_KERJA"] = $NAMA_SATKER;


        if(empty($data["SK_TANGGAL"])){
            unset($data["SK_TANGGAL"]);
        }
        $id_data = $this->input->post("ID");
        if(isset($id_data) && !empty($id_data)){
            $this->riwayat_pindah_unit_kerja_model->update($id_data,$data);
            // add log
            log_activity_pegawai($NIP_BARU,$this->auth->user_id(),"Update data Riwayat Pindah Unit", 'rwt_pindahunitkerja');
        }
        else{ 
            $this->riwayat_pindah_unit_kerja_model->insert($data);
            // add log
            log_activity_pegawai($NIP_BARU,$this->auth->user_id(),"Tambah data Riwayat Pindah Unit", 'rwt_pindahunitkerja');
        }
        $response ['success']= true;
        $response ['msg']= "Transaksi berhasil";
        echo json_encode($response);    

    }
    public function delete($record_id){
        $this->auth->restrict($this->permissionDelete);
		if ($this->riwayat_pindah_unit_kerja_model->delete($record_id)) {
			 log_activity($this->auth->user_id(), 'delete data Riwayat Diklat Kepangkatan : ' . $record_id . ' : ' . $this->input->ip_address(), 'pegawai');
			 Template::set_message("Sukses Hapus data", 'success');
			 echo "Sukses";
		}else{
			echo "Gagal";
		}

		exit();
    }
    public function index($PNS_ID='1552260645'){
        Template::set_view("kepegawaian/tab_pane_riwayat_pindah_unit_kerja");
        Template::set('PNS_ID', $PNS_ID);
        Template::set('TAB_ID', uniqid("TAB_CONTOH"));
        Template::render();
    }
    public function viewdoc($id)
    {
        if (empty($id)) {
            Template::set_message("ID tidak ditemukan", 'error');
        }
        $datadetil = $this->riwayat_pindah_unit_kerja_model->find($id);
        $FILE_BASE64 = $datadetil->FILE_BASE64;
        echo '<embed src="'.$FILE_BASE64.'" width="100%" height="700" alt="pdf">';
        die();
    }
}
