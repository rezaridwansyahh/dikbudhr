<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Reports controller
 */
class Reports extends Admin_Controller
{
    protected $permissionCreate = 'Petajabatan.Reports.Create';
    protected $permissionDelete = 'Petajabatan.Reports.Delete';
    protected $permissionEdit   = 'Petajabatan.Reports.Edit';
    protected $permissionView   = 'Petajabatan.Reports.View';
    protected $permissionViewRequest   = 'Petajabatan.Reports.Request';
    protected $permissionFiltersatker   = 'Pegawai.Kepegawaian.Filtersatker';
    public $UNOR_ID = null;
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('petajabatan/kuotajabatan_model');
        $this->load->model('petajabatan/request_formasi_model');
        $this->load->model('pegawai/unitkerja_model');
        $this->lang->load('petajabatan');
        
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'reports/_sub_nav');

        Assets::add_module_js('petajabatan', 'petajabatan.js');
        if($this->auth->has_permission($this->permissionFiltersatker)){
            $this->UNOR_ID = $this->pegawai_model->getunor_induk($this->auth->username());
        }
    }

    /**
     * Display a list of petajabatan data.
     *
     * @return void
     */
    public function index()
    {
        $this->auth->restrict($this->permissionView);
        $unit_kerja = $this->unitkerja_model->find($this->UNOR_ID);
        Template::set('unit_kerja', $unit_kerja);
    	Template::set('toolbar_title', "Peta Jabatan");
		Template::set("collapse",true);
        Template::render();
    }
    public function req_formasi()
    {
        $this->auth->restrict($this->permissionViewRequest);
        Template::set('toolbar_title', "Request Formasi");
        Template::set("collapse",true);
        Template::render();
    }
    public function detil_satker($satker_id = "")
    {
        $this->auth->restrict($this->permissionViewRequest);
        $detil_satker = $this->unitkerja_model->find_by("ID",$satker_id);
        Template::set("detil_satker",$detil_satker);
        Template::set("satker_id",$satker_id);
        Template::set('toolbar_title', "Request Formasi");
        Template::set("collapse",true);
        Template::render();
    }
    public function kebutuhanjabatan()
    {
        Template::set('toolbar_title', "Peta Jabatan");
        Template::set("collapse",true);
        Template::render();
    }
    public function kosong()
    {
    	Template::set('toolbar_title', "Peta Jabatan Kosong");
		Template::set("collapse",true);
        Template::render();
    }
    public function viewdata()
    {
    	$unitkerja = $this->input->get('unitkerja');
    	$this->load->model('pegawai/pegawai_model');
    	$this->load->model('pegawai/unitkerja_model');

    	$datadetil = $this->unitkerja_model->find_by("ID",$unitkerja);
        $detil_unor = $this->unitkerja_model->find_view_unit_list($unitkerja);
        $aunors = Modules::run('pegawai/manage_unitkerja/getcache_unor',$unitkerja);
        $json_unor = $this->cache->get('json_unor');
        $satker = $json_unor[$unitkerja];
        // echo "<pre>";
        // print_r($satker);
        // echo "</pre>";
		// Mulai kuota jabatan 
        $peraturan = trim($this->settings_lib->item('peta.permen'));
        $this->kuotajabatan_model->like('kuota_jabatan."PERATURAN"',trim($peraturan),"BOTH");
		$kuotajabatan = $this->kuotajabatan_model->find_all($unitkerja);
        if (isset($kuotajabatan) && is_array($kuotajabatan) && count($kuotajabatan)):
            foreach($kuotajabatan as $record):
                $akuota[trim($record->KODE_UNIT_KERJA)."-ID_JABATAN"][]     = trim($record->KODE_JABATAN);
                $akuota[trim($record->KODE_UNIT_KERJA)."-NAMA_Jabatan"][]   = trim($record->NAMA_JABATAN);
                $akuota[trim($record->KODE_UNIT_KERJA)."-KELAS"][]  = trim($record->KELAS);
                $akuota[trim($record->KODE_UNIT_KERJA)."-JML"][] = trim($record->JUMLAH_PEMANGKU_JABATAN);
            endforeach;
        endif;
		// echo "<pre>";
  //       print_r($akuota);
  //       echo "</pre>";
		$pegawaijabatan = $this->pegawai_model->find_grupjabatan_unor_induk($unitkerja);
        $apegawai = array(); 
        if (isset($pegawaijabatan) && is_array($pegawaijabatan) && count($pegawaijabatan)):
            foreach($pegawaijabatan as $record):
                // print_r($record);
                $apegawai[trim($record->UNOR_ID)."-jml-".trim($record->JABATAN_INSTANSI_ID)] = $record->jumlah;
            endforeach;
        endif;
        // echo "<pre>";
        // print_r($apegawai);
        // echo "</pre>";
    	$output = $this->load->view('reports/content',array('datadetil'=>$datadetil,"satker"=>$satker,"akuota"=>$akuota,"apegawai"=>$apegawai),true);	
		 
		echo $output;
        die();
    }
    public function viewdatakosong()
    {
    	$unitkerja = $this->input->get('unitkerja');
    	$this->load->model('pegawai/pegawai_model');
    	$this->load->model('pegawai/unitkerja_model');
    	$datadetil = $this->unitkerja_model->find_by("ID",$unitkerja);
    	//print_r($datadetil);
    	//die("unit ".$unitkerja);
    	$ideselon2 = isset($datadetil->KODE_INTERNAL) ? substr($datadetil->KODE_INTERNAL,0,5) : "";
    	$idsatker = isset($datadetil->ID) ? $datadetil->ID : "";
    	// eselon III
    	//$eselon3 = $this->unitkerja_model->find_by("DIATASAN_ID",$idsatker);
    	$eselon3[] = array(); 
    	$aeselon4[] = array(); 
    	$satker = $this->unitkerja_model->find_eselon3($ideselon2);
    	
    	if (isset($satker) && is_array($satker) && count($satker)):
			foreach($satker as $record):
				if($record->DIATASAN_ID == $idsatker){
					$eselon3["ID"][] = $record->ID;
					$eselon3["NAMA_UNOR"][] 	= $record->NAMA_UNOR;
				}else{
					$aeselon4[$record->DIATASAN_ID][] = $record->NAMA_UNOR;
					$aeselon4[$record->DIATASAN_ID."-ID"][] = $record->KODE_INTERNAL;
				}
			endforeach;
		endif;
		//echo count($eselon3["ID"]);
		//die();
    	/*
    	$eselon4 = $this->unitkerja_model->find_eselon4($ideselon2);
    	//print_r($eselon3);
    	if (isset($eselon4) && is_array($eselon4) && count($eselon4)):
			foreach($eselon4 as $record):
				$ideselon3 = isset($record->KODE_UNIT_KERJA) ? substr($record->KODE_UNIT_KERJA,0,8) : "";
				$aeselon4[$ideselon3][] = $record->NAMA_ESELON_IV;
				$aeselon4[$ideselon3."-ID"][] = $record->KODE_UNIT_KERJA;
				//echo $record->NAMA_ESELON_IV;
			endforeach;
		endif;
		*/
		// Mulai kuota jabatan 
        $peraturan = trim($this->settings_lib->item('peta.permen'));  
        $this->kuotajabatan_model->like('kuota_jabatan."PERATURAN"',trim($peraturan),"BOTH");
		$kuotajabatan = $this->kuotajabatan_model->find_all($idsatker);
		$akuota[] = array(); 
		if (isset($kuotajabatan) && is_array($kuotajabatan) && count($kuotajabatan)):
			foreach($kuotajabatan as $record):
				//echo $record->ID_JABATAN;
				$akuota[trim($record->KODE_UNIT_KERJA)."-ID_JABATAN"][] 	= trim($record->KODE_JABATAN);
				$akuota[trim($record->KODE_UNIT_KERJA)."-NAMA_Jabatan"][] 	= trim($record->NAMA_JABATAN);
				$akuota[trim($record->KODE_UNIT_KERJA)."-KELAS"][] 	= trim($record->KELAS);
				$akuota[trim($record->KODE_UNIT_KERJA)."-JML"][] = trim($record->JUMLAH_PEMANGKU_JABATAN);
			endforeach;
		endif;
		$pegawaijabatan = $this->pegawai_model->find_grupjabataninstansi($ideselon2);
		$apegawai = array(); 
		if (isset($pegawaijabatan) && is_array($pegawaijabatan) && count($pegawaijabatan)):
			foreach($pegawaijabatan as $record):
				//echo trim($record->KODE_INTERNAL)."-jml-".trim($record->JABATAN_INSTANSI_ID)." = ".$record->jumlah."<br>";
				$apegawai[trim($record->KODE_INTERNAL)."-jml-".trim($record->JABATAN_INSTANSI_ID)] = trim($record->jumlah);
			endforeach;
		endif;
		//print_r($apegawai);
    	$output = $this->load->view('reports/contentkosong',array('datadetil'=>$datadetil,"eselon3"=>$eselon3,"aeselon4"=>$aeselon4,"akuota"=>$akuota,"apegawai"=>$apegawai),true);	
		 
		echo $output;
        die();
    }
    public function getquota()
    {
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
        $unitkerja = $this->input->post('unitkerja');
        $jabatan_instansi = $this->input->post('jabatan_instansi');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/unitkerja_model');
        
        $kuotajabatan = $this->kuotajabatan_model->find_by_jabatan($unitkerja,$jabatan_instansi);
        Template::set('kuotajabatan', $kuotajabatan);

        $recordpegawaiquota = $this->pegawai_model->get_count_jabatan_instansi_unor($unitkerja,$jabatan_instansi);
        //echo $unitkerja." - ".$jabatan_instansi;
        $akuota[] = array(); 
        if (isset($recordpegawaiquota) && is_array($recordpegawaiquota) && count($recordpegawaiquota)):
            foreach($recordpegawaiquota as $record):
                $akuota[trim($record->UNOR_ID)]    = trim($record->jumlah);
            endforeach;
        endif;
        //print_r($akuota);
        $output = $this->load->view('reports/contentquota',array('kuotajabatan'=>$kuotajabatan,'akuota'=>$akuota),true); 
         
        echo $output;
        die();
    }
	public function addkuota()
    {
    	$this->auth->restrict($this->permissionCreate);
        $kode_satker = $this->uri->segment(5);
        $this->load->model('ref_jabatan/jabatan_model');
        $jabatans = $this->jabatan_model->find_all();
		Template::set('kode_satker', $kode_satker);
		Template::set('jabatans', $jabatans);
        Template::set('toolbar_title', "Tambah Kuota Jabatan");
		
        Template::render();
    }
    public function editkuota()
    {
        $this->auth->restrict($this->permissionEdit);
        $kode_satker = $this->uri->segment(5);
        $kode_jabatan = $this->uri->segment(6);
        $this->kuotajabatan_model->where("kuota_jabatan.KODE_UNIT_KERJA",$kode_satker);
        $this->kuotajabatan_model->where("kuota_jabatan.ID_JABATAN",$kode_jabatan);
        $kuota_jabatan = $this->kuotajabatan_model->find_det();
        //print_r($kuota_jabatan);
        //die($kuota_jabatan[0]->ID);
        Template::set('kuota_jabatan', $kuota_jabatan);
        
        $this->load->model('ref_jabatan/jabatan_model');
        $jabatans = $this->jabatan_model->find_all();
		Template::set('kode_satker', $kode_satker);
		Template::set('jabatans', $jabatans);
        Template::set('toolbar_title', "Tambah Kuota Jabatan");
        Template::render();
    }
    
    public function savekuota(){
         // Validate the data
		if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
        $this->form_validation->set_rules($this->kuotajabatan_model->get_validation_rules());
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

        $data = $this->kuotajabatan_model->prep_data($this->input->post());
        $id_data = $this->input->post("ID");
        if(isset($id_data) && !empty($id_data)){
            $this->kuotajabatan_model->update($id_data,$data);
        }
        else $this->kuotajabatan_model->insert($data);
        $response ['success']= true;
        $response ['msg']= "Transaksi berhasil";
        echo json_encode($response);    

    }
    public function deletekuota()
	{
		$this->auth->restrict($this->permissionDelete);
		$id 	= $this->input->post('kode');
		if ($this->kuotajabatan_model->delete($id)) {
			 log_activity($this->auth->user_id(),"Delete data" . ': ' . $id . ' : ' . $this->input->ip_address(), 'kuota_jabatan');
			 Template::set_message("Delete kuota jabatan sukses", 'success');
			 echo "Sukses";
		}else{
			echo "Gagal";
		}

		exit();
	}
    public function getdata(){
        $this->auth->restrict($this->permissionView);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai');
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

        $selectedUnors = array();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            if($filters['unit_id_key']){
                $datapensiun = $this->get_jabatan_estimasi_pensiun($filters['unit_id_key']);
            }   
        }
        $this->db->start_cache();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            if($filters['unit_id_key']){
                $this->db->group_start();
                $this->db->where('vw_unit_list."ID"',$filters['unit_id_key']);    
                $this->db->or_where('vw_unit_list."ESELON_1"',$filters['unit_id_key']);   
                $this->db->or_where('vw_unit_list."ESELON_2"',$filters['unit_id_key']);   
                $this->db->or_where('vw_unit_list."ESELON_3"',$filters['unit_id_key']);   
                $this->db->or_where('vw_unit_list."ESELON_4"',$filters['unit_id_key']);   
                $this->db->group_end();

            }else{
                $this->db->group_start();
                $this->db->where('vw_unit_list."ID"',"kosong");    
                $this->db->or_where('vw_unit_list."ESELON_1"',"kosong");   
                $this->db->or_where('vw_unit_list."ESELON_2"',"kosong");   
                $this->db->or_where('vw_unit_list."ESELON_3"',"kosong");   
                $this->db->or_where('vw_unit_list."ESELON_4"',"kosong");   
                $this->db->group_end();
            }
            if($filters['nama_jabatan']){
                $this->db->like('jabatan."NAMA_JABATAN"',trim($filters['nama_jabatan']),"BOTH");    
            }
        }
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        if(trim($this->settings_lib->item('peta.permen')) != "")
            $this->kuotajabatan_model->where("PERATURAN",trim($this->settings_lib->item('peta.permen')));
        $total= $this->kuotajabatan_model->count_quota($this->UNOR_ID);
        $orders = $this->input->post('order');
        $this->kuotajabatan_model->order_by("KODE_UNIT_KERJA","ASC");
        foreach($orders as $order){
            if($order['column']==3){
                $this->kuotajabatan_model->order_by("ID_JABATAN",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->kuotajabatan_model->limit($length,$start);
        if(trim($this->settings_lib->item('peta.permen')) != "")
            $this->kuotajabatan_model->where("PERATURAN",trim($this->settings_lib->item('peta.permen')));
        $records = $this->kuotajabatan_model->find_quota($this->UNOR_ID);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        $tahunini = date("Y");
        $batastahun = $tahunini + 5;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->NAMA_UNOR_FULL;
                $row []  = $record->NAMA_JABATAN;
                $row []  = $record->JUMLAH_PEMANGKU_JABATAN;
                $row []  = $record->JML_RIIL;
                for($i=$tahunini;$i<=$batastahun;$i++){
                    $row []  = isset($datapensiun[$record->KODE_JABATAN."_".$i]) ? $datapensiun[$record->KODE_JABATAN."_".$i] : 0;    
                }
                
                
                $btn_actions = array();
                 
                
                if($this->auth->has_permission("Petajabatan.Masters.Edit")){
                $btn_actions  [] = "<a href='".base_url()."admin/masters/petajabatan/editkuota/".$record->ID."' class='btn btn-sm btn-warning show-modal' title='Update data'><i class='glyphicon glyphicon-edit'></i> </a>";
                }
                if($this->auth->has_permission("Petajabatan.Masters.Delete")){
                    $btn_actions  [] = "<a kode='$record->ID' class='btn btn-sm btn-danger btn-hapus' data-toggle='tooltip' data-placement='top' data-original-title='Hapus data' title='Hapus data' tooltip='Hapus'><i class='glyphicon glyphicon-remove'></i> </a>";
                }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    public function getrekap_request(){
        $this->auth->restrict($this->permissionViewRequest);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/reports/petajabatan');
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

        $selectedUnors = array();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
        }
        $this->db->start_cache();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            if($filters['unit_id_key']){
                $this->db->group_start();
                $this->db->where('vw_unit_list."ID"',$filters['unit_id_key']);    
                $this->db->or_where('vw_unit_list."ESELON_1"',$filters['unit_id_key']);   
                $this->db->or_where('vw_unit_list."ESELON_2"',$filters['unit_id_key']);   
                $this->db->or_where('vw_unit_list."ESELON_3"',$filters['unit_id_key']);   
                $this->db->or_where('vw_unit_list."ESELON_4"',$filters['unit_id_key']);   
                $this->db->group_end();

            }else{
                $this->db->group_start();
                $this->db->where('vw_unit_list."ID"',"kosong");    
                $this->db->or_where('vw_unit_list."ESELON_1"',"kosong");   
                $this->db->or_where('vw_unit_list."ESELON_2"',"kosong");   
                $this->db->or_where('vw_unit_list."ESELON_3"',"kosong");   
                $this->db->or_where('vw_unit_list."ESELON_4"',"kosong");   
                $this->db->group_end();
            }
             
        }
        $this->request_formasi_model->where("tahun",trim($this->settings_lib->item('peta_tahun')));
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total = count($this->request_formasi_model->find_rekap());
        $orders = $this->input->post('order');
        $this->request_formasi_model->order_by("satker_id","ASC");
        foreach($orders as $order){
            if($order['column']==3){
                $this->request_formasi_model->order_by("ID_JABATAN",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->request_formasi_model->limit($length,$start);
        $records = $this->request_formasi_model->find_rekap();
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->NAMA_UNOR;
                $row []  = "<a href='".base_url()."admin/reports/petajabatan/detil_satker/".$record->satker_id."' class='show-modal'>".$record->jumlah_ajuan."</a>";
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    public function getrekap_request_detil(){
        $this->auth->restrict($this->permissionViewRequest);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/reports/petajabatan');
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

        $this->db->start_cache();
        $satker_id  = $this->input->post("satker_id");
        $this->request_formasi_model->where("tahun",trim($this->settings_lib->item('peta_tahun')));
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total = $this->request_formasi_model->count_all($satker_id);
        $orders = $this->input->post('order');
        $this->request_formasi_model->order_by("id","ASC");
        foreach($orders as $order){
            if($order['column']==3){
                $this->request_formasi_model->order_by("ID_JABATAN",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->request_formasi_model->limit($length,$start);
        $records = $this->request_formasi_model->find_all($satker_id);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->NAMA_JABATAN;
                $row []  = $record->kualifikasi_pendidikan;
                $row []  = $record->jumlah_ajuan;
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    private function get_jabatan_estimasi_pensiun($unor_id = ""){
        $data = array();
        $records=$this->pegawai_model->find_pensiunbyjabatan($unor_id);
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $data[$record->KODE_JABATAN."_".$record->perkiraan_tahun_pensiun] = $record->total;
            }
        endif;
        return $data;
    }
    public function download()
    {
        $this->load->library('Excel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel = PHPExcel_IOFactory::load(trim($this->settings_lib->item('site.pathuploaded')).'template_usul_formasi.xlsx');
        $objPHPExcel->setActiveSheetIndex(0);
        $this->db->start_cache();
        $satker_id  = $this->input->get("satker_id");
        $this->request_formasi_model->where("tahun",trim($this->settings_lib->item('peta_tahun')));
        $this->db->stop_cache();
        $records = $this->request_formasi_model->find_all($satker_id);
        $this->db->flush_cache();
        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, 5)->setValueExplicit("TAHUN ANGGARAN ".trim($this->settings_lib->item('peta_tahun')), PHPExcel_Cell_DataType::TYPE_STRING);
        $row = 10;
        $no = 1;
        $jabatan = "";
        if (isset($records) && is_array($records) && count($records)) :
            foreach ($records as $record) :
                $col = 0;
                $type = PHPExcel_Cell_DataType::TYPE_STRING;
                $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->setValueExplicit($no, $type);
                $col++;
                if($jabatan != trim($record->NAMA_JABATAN)){
                    $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->setValueExplicit(strtoupper($record->NAMA_JABATAN), $type);
                    $jabatan = trim($record->NAMA_JABATAN);
                }else{
                    $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->setValueExplicit(strtoupper(""), $type);
                }
                $col++;
                $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->setValueExplicit(strtoupper($record->kualifikasi_pendidikan), $type);
                $col++;
                $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->setValueExplicit("", $type);
                $col++;
                $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->setValueExplicit($record->jumlah_ajuan, $type);
                $col++;
                $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->setValueExplicit(strtoupper($record->NAMA_UNOR), $type);
                $no++;
                $row++;
            endforeach;
        endif;
          
        $filename = "kebutuhan_".mt_rand(1,100000).'.xls'; //just some random filename
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
        //$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
        $objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
        exit; //done.. exiting!
        
    }
    public function download_all()
    {
        $this->load->library('Excel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel = PHPExcel_IOFactory::load(trim($this->settings_lib->item('site.pathuploaded')).'template_usul_formasi.xlsx');
        $objPHPExcel->setActiveSheetIndex(0);

        $rec_jumlah = $this->request_formasi_model->find_group_jabatan(trim($this->settings_lib->item('peta_tahun')));
        $ajabatan = array();
        if (isset($rec_jumlah) && is_array($rec_jumlah) && count($rec_jumlah)) :
            foreach ($rec_jumlah as $record) :
                $ajabatan[trim($record->id_jabatan)] = $record->jumlah;
            endforeach;
        endif;
        $this->request_formasi_model->where("tahun",trim($this->settings_lib->item('peta_tahun')));
        $records = $this->request_formasi_model->find_all();
        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, 5)->setValueExplicit("TAHUN ANGGARAN ".trim($this->settings_lib->item('peta_tahun')), PHPExcel_Cell_DataType::TYPE_STRING);
        $row = 10;
        $no = 1;
        $jabatan = "";
        if (isset($records) && is_array($records) && count($records)) :
            foreach ($records as $record) :
                $col = 0;
                $type = PHPExcel_Cell_DataType::TYPE_STRING;
                $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $row)->setValueExplicit($no, $type);
                $col++;
                if($jabatan != trim($record->NAMA_JABATAN)){
                    $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $row)->setValueExplicit(strtoupper($record->NAMA_JABATAN), $type);
                    $jabatan = trim($record->NAMA_JABATAN);
                    if(isset($ajabatan[trim($record->id_jabatan)])){
                        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $row)->setValueExplicit($ajabatan[trim($record->id_jabatan)], $type);    
                    }else{
                        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $row)->setValueExplicit("", $type);    
                    }
                }else{
                    $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $row)->setValueExplicit(strtoupper(""), $type);
                }
                $col++;
                $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $row)->setValueExplicit(strtoupper($record->kualifikasi_pendidikan), $type);
                $col++;
                $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4, $row)->setValueExplicit($record->jumlah_ajuan, $type);
                $col++;
                $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, $row)->setValueExplicit(strtoupper($record->NAMA_UNOR), $type);
                $no++;
                $row++;
            endforeach;
        endif;
        $filename = "kebutuhan_".mt_rand(1,100000).'.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
        //$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
        $objWriter->save('php://output'); 
        exit;
    }
    public function downloadexcell(){
        $this->load->library('Excel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel = PHPExcel_IOFactory::load(trim($this->settings_lib->item('site.pathuploaded')).'template_petajabatan.xlsx');
        $objPHPExcel->setActiveSheetIndex(0);

        $unitkerja = $this->input->get('unitkerja');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/unitkerja_model');

        $datadetil = $this->unitkerja_model->find_by("ID",$unitkerja);
        $detil_unor = $this->unitkerja_model->find_view_unit_list($unitkerja);
        $aunors = Modules::run('pegawai/manage_unitkerja/getcache_unor',$unitkerja);
        $json_unor = $this->cache->get('json_unor');
        $satker = $json_unor[$unitkerja];
        $satker = array_merge($detil_unor,$satker);
        // echo "<pre>";
        // print_r($satker);
        // print_r($detil_unor);
        // echo "</pre>";
        // Mulai kuota jabatan 
        $peraturan = trim($this->settings_lib->item('peta.permen'));  
        $this->kuotajabatan_model->like('kuota_jabatan."PERATURAN"',trim($peraturan),"BOTH");
        $kuotajabatan = $this->kuotajabatan_model->find_all($unitkerja);
        if (isset($kuotajabatan) && is_array($kuotajabatan) && count($kuotajabatan)):
            foreach($kuotajabatan as $record):
                $akuota[trim($record->KODE_UNIT_KERJA)."-ID_JABATAN"][]     = trim($record->KODE_JABATAN);
                $akuota[trim($record->KODE_UNIT_KERJA)."-NAMA_Jabatan"][]   = trim($record->NAMA_JABATAN);
                $akuota[trim($record->KODE_UNIT_KERJA)."-KELAS"][]  = trim($record->KELAS);
                $akuota[trim($record->KODE_UNIT_KERJA)."-JML"][] = trim($record->JUMLAH_PEMANGKU_JABATAN);
            endforeach;
        endif;
        $pegawaijabatan = $this->pegawai_model->find_grupjabatan_unor_induk($unitkerja);
        $apegawai = array(); 
        if (isset($pegawaijabatan) && is_array($pegawaijabatan) && count($pegawaijabatan)):
            foreach($pegawaijabatan as $record):
                $apegawai[trim($record->UNOR_ID)."-jml-".trim($record->JABATAN_INSTANSI_ID)] = $record->jumlah;
            endforeach;
        endif;
        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, 2)->setValueExplicit(trim($datadetil->NAMA_UNOR), PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, 3)->setValueExplicit("TAHUN ANGGARAN ".trim($this->settings_lib->item('peta_tahun')), PHPExcel_Cell_DataType::TYPE_STRING);
        $row = 7;
        $no = 1;
        $jabatan = "";
        $type = PHPExcel_Cell_DataType::TYPE_STRING;
        $numerik = PHPExcel_Cell_DataType::TYPE_NUMERIC;
        if (isset($satker) && is_array($satker) && count($satker)) :
            foreach ($satker as $record) :
                $col = 0;
                $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $row)->setValueExplicit($no, $type);
                $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $row)->setValueExplicit(strtoupper($record->NAMA_UNOR), $type);
                $row++;
                if(isset($akuota[$record->ID."-ID_JABATAN"])){
                    for($a=0;$a < count($akuota[$record->ID."-ID_JABATAN"]);$a++){
                        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $row)->setValueExplicit(strtoupper($akuota[$record->ID."-NAMA_Jabatan"][$a]), $type);
                        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $row)->setValueExplicit(strtoupper($akuota[$record->ID."-KELAS"][$a]), $type);
                        $jmlada = isset($apegawai[$record->ID."-jml-".$akuota[$record->ID."-ID_JABATAN"][$a]]) ? $apegawai[$record->ID."-jml-".$akuota[$record->ID."-ID_JABATAN"][$a]] : "0";
                        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $row)->setValueExplicit($jmlada, $numerik);
                        $quota = $akuota[$record->ID."-JML"][$a];
                        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4, $row)->setValueExplicit($quota, $numerik);
                        $sisa = (int)$jmlada - (int)$quota;
                        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, $row)->setValueExplicit($sisa, $numerik);
                        $row++;
                    }
                }
                $no++;
            endforeach;
        endif;
        $filename = "petajabatan_".mt_rand(1,100000).'.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
        //$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
        $objWriter->save('php://output'); 
        exit;

    }
}