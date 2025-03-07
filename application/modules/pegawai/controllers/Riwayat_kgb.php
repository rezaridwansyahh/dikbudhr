<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Riwayat_kgb extends Admin_Controller
{
    protected $permissionCreate = 'riwayatkgb.Kepegawaian.Create';
    protected $permissionDelete = 'riwayatkgb.Kepegawaian.Delete';
    protected $permissionEdit   = 'riwayatkgb.Kepegawaian.Edit';
    protected $permissionView   = 'riwayatkgb.Kepegawaian.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/riwayat_kgb_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/golongan_model');
        $this->load->model('pegawai/lokasi_model');
        $this->load->model('pegawai/jenis_jabatan_model');
        $this->load->model('ref_jabatan/jabatan_model');
        $jenis_jabatans = $this->jenis_jabatan_model->find_all();
		Template::set('jenis_jabatans', $jenis_jabatans);
		
		$this->load->model('pegawai/unitkerja_model');
		
    }
    public function ajax_list(){
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
		$this->riwayat_kgb_model->where("pegawai_nip",$pegawai_data->NIP_BARU);
		$total= $this->riwayat_kgb_model->count_all();;
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
        
		
        
		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if($search!=""){
			$this->riwayat_kgb_model->where('upper("SEBAGAI") LIKE \''.strtoupper($search).'%\'');
		}
		$this->riwayat_kgb_model->limit($length,$start);
		
        $this->riwayat_kgb_model->where("pegawai_nip",$pegawai_data->NIP_BARU); 
        $records=$this->riwayat_kgb_model->find_all();
            
		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($search != "")
		{
			$this->riwayat_kgb_model->where('upper("SEBAGAI") LIKE \''.strtoupper($search).'%\'');
			$jum	= $this->riwayat_kgb_model->count_all();
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}
		$this->load->helper('dikbud');
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                
                $row []  = $convert->fmtDate($record->tmt_sk,"dd-mm-yyyy");
                $row []  = $record->no_sk;
                $row []  = $convert->fmtDate($record->tgl_sk,"dd-mm-yyyy");
                $row []  = $record->n_gol_ruang;
                $row []  = rupiah($record->n_gapok);

                $btn_actions = array();
                if($record->KETERANGAN_BERKAS != ""){
                    $btn_actions  [] = "<a href='".base_url()."pegawai/riwayat_kgb/viewdoc/".$record->id."' data-toggle='tooltip' title='Lihat Dokumen' tooltip='Lihat Dokumen ".$record->no_sk."' class='btn btn-sm btn-info show-modal'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionEdit))
                {    
                    $btn_actions  [] = "<a href='".base_url()."pegawai/riwayat_kgb/edit/".$record->ref."' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success show-modal-custom'><i class='fa fa-pencil'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionDelete))
                {    
                    $btn_actions  [] = "<a href='#' kode='$record->ref' data-toggle='tooltip' title='Hapus Data' class='btn btn-sm btn-danger btn-hapus-kgb'><i class='fa fa-trash-o'></i> </a>";
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
            Template::set_view("kepegawaian/riwayat_kgb_crud");
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Riwayat KGB");
            Template::render();
        }
        else {
    		$datadetil = $this->riwayat_kgb_model->find($record_id); 
            $this->auth->restrict($this->permissionEdit);
            Template::set_view("kepegawaian/riwayat_kgb_crud");
           
            Template::set('detail_riwayat', $datadetil);    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Riwayat KGB");

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
    public function add($PNS_ID){
        $list_golongan = $this->golongan_model->find_all();
        Template::set('list_golongan',$list_golongan);
        $this->show($PNS_ID);
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
    
    public function save(){
         // Validate the data
        $this->form_validation->set_rules($this->riwayat_kgb_model->get_validation_rules());
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
		
		
        $data = $this->riwayat_kgb_model->prep_data($this->input->post());
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
        $golongan_data = $this->golongan_model->find($this->input->post('n_golongan_id'));
        $unit_kerja_induk_data = $this->unitkerja_model->find($this->input->post('unit_kerja_induk_id'));
        $lokasi_lahir_data = $this->lokasi_model->find($pegawai_data->TEMPAT_LAHIR_ID);
        
        $data["pegawai_nip"] = $pegawai_data->NIP_BARU;
        $data["pegawai_nama"] = $pegawai_data->NAMA;
        $data["birth_place"] = $lokasi_lahir_data->NAMA;
        $data["birth_date"] = $pegawai_data->TGL_LAHIR;
        $data["pegawai_id"] = $pegawai_data->ID;
        $data["n_gol_ruang"] = $golongan_data->NAMA_PANGKAT." - ".$golongan_data->NAMA;
        $data["unit_kerja_induk_text"] = $unit_kerja_induk_data->NAMA_UNOR;
        
        if(empty($data["tgl_sk"])){
            unset($data["tgl_sk"]);
        }
        if(empty($data["tmt_sk"])){
            unset($data["tmt_sk"]);
        }
        $id_data = $this->input->post("id");
        if(isset($id_data) && !empty($id_data)){
            $this->riwayat_kgb_model->update($id_data,$data);
        }
        else $this->riwayat_kgb_model->insert($data);
        $response ['success']= true;
        $response ['msg']= "berhasil";
        echo json_encode($response);    

    }
    public function delete($record_id){
        $this->auth->restrict($this->permissionDelete);
		if ($this->riwayat_kgb_model->delete($record_id)) {
			 log_activity($this->auth->user_id(), 'delete data Riwayat KGB : ' . $record_id . ' : ' . $this->input->ip_address(), 'pegawai');
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
    public function viewdoc($id)
    {
        if (empty($id)) {
            Template::set_message("ID tidak ditemukan", 'error');
        }
        $datadetil = $this->riwayat_kgb_model->find($id);
        $FILE_BASE64 = $datadetil->FILE_BASE64;
        echo '<embed src="'.$FILE_BASE64.'" width="100%" height="700" alt="pdf">';
        die();
    }
}
