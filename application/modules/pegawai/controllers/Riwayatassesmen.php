<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Riwayatassesmen extends Admin_Controller
{
    protected $permissionCreate = 'Riwayatassesmen.Kepegawaian.Create';
    protected $permissionDelete = 'Riwayatassesmen.Kepegawaian.Delete';
    protected $permissionEdit   = 'Riwayatassesmen.Kepegawaian.Edit';
    protected $permissionView   = 'Riwayatassesmen.Kepegawaian.View';
    protected $permissionViewAdmin   = 'Riwayatassesmen.Kepegawaian.ViewAdmin';
    protected $permissionNineBox   = 'Riwayatassesmen.Kepegawaian.Nine_box';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/riwayat_assesmen_model');
        $this->load->model('pegawai/vw_rwt_assesmen_pegawai_model');
        $this->load->model('pegawai/riwayat_nine_box_model');
        $this->load->model('pegawai/pegawai_model');
    }

    public function list_by_satker(){
        $satker_id = $this->input->get("satker_id");
        $unor_id = $this->input->get("unor_id");
        $unor_induk_id = $this->input->get("unor_induk_id");

        $satker_id = $satker_id=="" ? "xxx" : $satker_id;
        $unor_id = $unor_id=="" ? "xxx" : $unor_id;
        $unor_induk_id = $unor_induk_id=="" ? "xxx" : $unor_induk_id;

        

        
        $tahun = $this->input->get("tahun");
        $this->vw_rwt_assesmen_pegawai_model->where("TAHUN",$tahun);
        $this->db->group_start();
        $this->vw_rwt_assesmen_pegawai_model->where("SATKER_ID",$satker_id)->or_where("UNOR_PEGAWAI",$unor_id)->or_where("UNOR_PEGAWAI",$unor_induk_id)->or_where("UNOR_INDUK_PEGAWAI",$unor_id); 
        //$this->riwayat_assesmen_model->where("UNIT_ORG_ID",$satker_id)->or_where("SATKER_ID",$unor_id); 
        
		$this->db->group_end();
        $records=$this->vw_rwt_assesmen_pegawai_model->find_all();
        //echo $this->db->last_query();
        
        foreach ($records as $key => $value) {
            $records[$key]->FILE_UPLOAD = $this->generateUrlAsesmen($value->FILE_UPLOAD);
            $records[$key]->FILE_UPLOAD_FB_POTENSI = $this->generateUrlAsesmen($value->FILE_UPLOAD_FB_POTENSI);
            $records[$key]->FILE_UPLOAD_FB_PT = $this->generateUrlAsesmen($value->FILE_UPLOAD_FB_PT);
        }
        $response['data'] = $records;
        //$list_pegawai_satker = $this->riwayat_assesmen_model->get_asesmen_by_satker($satker_id);
        echo json_encode($response);
        //echo $satker_id;
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
		//$this->riwayat_assesmen_model->where("PNS_ID",$pegawai_data->ID);
		$this->riwayat_assesmen_model->where("PNS_NIP",$pegawai_data->NIP_BARU);
		$total= $this->riwayat_assesmen_model->count_all();;
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
        
		
        
		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if($search!=""){
			$this->riwayat_assesmen_model->where('upper("TAHUN") LIKE \''.strtoupper($search).'%\'');
		}
		$this->riwayat_assesmen_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
        
		$kolom = $iSortCol != "" ? $iSortCol : "TAHUN";
		$sSortCol == "desc" ? "desc" : "asc";
		$this->riwayat_assesmen_model->order_by($iSortCol,$sSortCol);
        
        //$this->riwayat_assesmen_model->where("PNS_ID",$pegawai_data->PNS_ID); 
        $this->riwayat_assesmen_model->where("PNS_NIP",$pegawai_data->NIP_BARU); 
		$this->riwayat_assesmen_model->ORDER_BY($kolom,$sSortCol);
        $records=$this->riwayat_assesmen_model->find_all();
            
		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($search != "")
		{
			$this->riwayat_assesmen_model->where('upper("TAHUN") LIKE \''.strtoupper($search).'%\'');
			$jum	= $this->riwayat_assesmen_model->count_all();
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}
		
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->TAHUN;
                if($record->FILE_UPLOAD_FB_POTENSI != "" && $this->auth->has_permission("Riwayatassesmen.Kepegawaian.P_feedback")){
                    $row []  = "<a href='".$this->generateUrlAsesmen($record->FILE_UPLOAD_FB_POTENSI)."' target='_blank' data-toggle='modal' title='Lihat detil'><span class='fa-stack'>
                        Lihat
                        </span>
                        </a>
                        ";    
                }else{
                    $row []  = "-";
                }

                if($record->FILE_UPLOAD_FB_PT != ""  && $this->auth->has_permission("Riwayatassesmen.Kepegawaian.L_feedback")){
                    $row []  = "<a href='".$this->generateUrlAsesmen($record->FILE_UPLOAD_FB_PT)."' target='_blank' data-toggle='modal' title='Lihat detil'><span class='fa-stack'>
                        Lihat
                        </span>
                        </a>
                        ";    
                }else{
                    $row []  = "-";
                }

                
                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		die();
    }

    private function generateUrlAsesmen($url){
        if($url!=''){
            $baseUrl = trim($this->settings_lib->item('site.urlassesment'));
            if(strpos($url,"http")===false){
                $url = $baseUrl.$url;
            }
        }
        return $url;
    }



    public function ajax_list_admin(){
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
        //$this->riwayat_assesmen_model->where("PNS_ID",$pegawai_data->ID);
        $this->riwayat_assesmen_model->where("PNS_NIP",$pegawai_data->NIP_BARU);
        $total= $this->riwayat_assesmen_model->count_all();;
        $output=array();
        $output['draw']=$draw;

        
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        
        
        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        if($search!=""){
            $this->riwayat_assesmen_model->where('upper("TAHUN") LIKE \''.strtoupper($search).'%\'');
        }
        $this->riwayat_assesmen_model->limit($length,$start);
        /*Urutkan dari alphabet paling terkahir*/
        
        $kolom = $iSortCol != "" ? $iSortCol : "TAHUN";
        $sSortCol == "desc" ? "desc" : "asc";
        $this->riwayat_assesmen_model->order_by($iSortCol,$sSortCol);
        
        //$this->riwayat_assesmen_model->where("PNS_ID",$pegawai_data->PNS_ID); 
        $this->riwayat_assesmen_model->where("PNS_NIP",$pegawai_data->NIP_BARU); 
        $this->riwayat_assesmen_model->ORDER_BY($kolom,$sSortCol);
        $records=$this->riwayat_assesmen_model->find_all();
            
        /*Ketika dalam mode pencarian, berarti kita harus
        'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
        yang mengandung keyword tertentu
        */
        if($search != "")
        {
            $this->riwayat_assesmen_model->where('upper("TAHUN") LIKE \''.strtoupper($search).'%\'');
            $jum    = $this->riwayat_assesmen_model->count_all();
            $output['recordsTotal']=$output['recordsFiltered']=$jum;
        }
        
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->TAHUN. " (".$record->TAHUN_PENILAIAN_ID.")";
                if($this->auth->has_permission("Riwayatassesmen.Kepegawaian.ViewNilai")){
                    if($record->FILE_UPLOAD_FB_POTENSI != ""){
                    $row []  = $record->NILAI;
                    $row []  = $record->NILAI_KINERJA;
                    }else{
                        $row []  = $record->NILAI;
                        $row []  = $record->NILAI_KINERJA;
                    }
                }
                if($this->auth->has_permission("Riwayatassesmen.Kepegawaian.P_feedback")){
                    if($record->FILE_UPLOAD_FB_POTENSI != ""){
                        $row []  = "<a href='".$this->generateUrlAsesmen($record->FILE_UPLOAD_FB_POTENSI)."' target='_blank' data-toggle='modal' title='Lihat detil'><span class='fa-stack'>
                        Lihat
                        </span>
                        </a>
                        ";  
                    }else{
                        $row []  = "-";
                    }   
                }
                if($this->auth->has_permission("Riwayatassesmen.Kepegawaian.P_Lengkap")){
                    if($record->FILE_UPLOAD != ""){
                        $row []  = "<a href='".$this->generateUrlAsesmen($record->FILE_UPLOAD)."' target='_blank' data-toggle='modal' title='Lihat detil'><span class='fa-stack'>
                        Lihat
                        </span>
                        </a>
                        ";   
                    }else{
                        $row []  = "-";
                    }   
                }
                
                if($this->auth->has_permission("Riwayatassesmen.Kepegawaian.L_feedback")){
                    if($record->FILE_UPLOAD_FB_PT != ""){
                        $row []  = "<a href='".$this->generateUrlAsesmen($record->FILE_UPLOAD_FB_PT)."' target='_blank' data-toggle='modal' title='Lihat detil'><span class='fa-stack'>
                        Lihat
                        </span>
                        </a>
                        ";  
                    }else{
                        $row []  = "-";
                    }  
                      
                } 
                if($this->auth->has_permission("Riwayatassesmen.Kepegawaian.L_Lengkap")){
                   if($record->FILE_UPLOAD_LENGKAP_PT != "")
                   {
                        $row []  = "<a href='".$this->generateUrlAsesmen($record->FILE_UPLOAD_LENGKAP_PT)."' target='_blank' data-toggle='modal' title='Lihat detil'><span class='fa-stack'>
                        Lihat
                        </span>
                        </a>
                        ";  
                    }else{
                        $row []  = "-";
                    }  
                }
                $btn_actions = array();
                $saran = json_decode($record->SARANPENGEMBANGAN);
                $jmlsaran = count($saran);

                $isi_saran = "";
                if($jmlsaran > 0){
                    for($i=0;$i<$jmlsaran;$i++){
                        //print_r($mydata->saranpengembangan[$i]);
                        $isi_saran  .= "<b>".$saran[$i]->jenis_saran."</b> : ".$saran[$i]->isi_saran."<br>";
                    }
                    $row[] = $isi_saran;
                }else{
                    $row[]  = "";
                }

                //$row []  = $record->SARANPENGEMBANGAN;
               // $row []  = $saran;//$saran[0]->jenis_saran;

                
                if($this->auth->has_permission("Riwayatassesmen.Kepegawaian.Edit")){
                    $btn_actions  [] = "
                    <a class='show-modal-custom' href='".base_url()."pegawai/riwayatassesmen/edit/".$PNS_ID."/".$record->ID."'  data-toggle='modal' title='Ubah Data'><span class='fa-stack'>
                        <i class='fa fa-square fa-stack-2x'></i>
                        <i class='fa fa-pencil fa-stack-1x fa-inverse'></i>
                        </span>
                        </a>
                ";
                }
                if($this->auth->has_permission("Riwayatassesmen.Kepegawaian.Delete")){
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
        //print_r($saran[0]);
        echo json_encode($output);
        die();
    }
    public function ajax_list_nine(){

        $this->auth->restrict($this->permissionNineBox);
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
        $this->riwayat_nine_box_model->where("PNS_NIP",$pegawai_data->NIP_BARU);
        $total= $this->riwayat_nine_box_model->count_all();;
        $output=array();
        $output['draw']=$draw;

        
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        
        
        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        if($search!=""){
            $this->riwayat_nine_box_model->where('upper("TAHUN") LIKE \''.strtoupper($search).'%\'');
        }
        $this->riwayat_nine_box_model->limit($length,$start);
        /*Urutkan dari alphabet paling terkahir*/
        
        $kolom = $iSortCol != "" ? $iSortCol : "TAHUN";
        $sSortCol == "desc" ? "desc" : "asc";
        $this->riwayat_nine_box_model->order_by($iSortCol,$sSortCol);
        
        //$this->riwayat_nine_box_model->where("PNS_ID",$pegawai_data->PNS_ID); 
        $this->riwayat_nine_box_model->where("PNS_NIP",$pegawai_data->NIP_BARU); 
        $this->riwayat_nine_box_model->ORDER_BY($kolom,$sSortCol);
        $records=$this->riwayat_nine_box_model->find_all();
            
        /*Ketika dalam mode pencarian, berarti kita harus
        'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
        yang mengandung keyword tertentu
        */
        if($search != "")
        {
            $this->riwayat_nine_box_model->where('upper("TAHUN") LIKE \''.strtoupper($search).'%\'');
            $jum    = $this->riwayat_nine_box_model->count_all();
            $output['recordsTotal']=$output['recordsFiltered']=$jum;
        }
        
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row  = array();
                $row []  = $nomor_urut;
                $row []  = $record->TAHUN;
                $row []  = $record->KESIMPULAN;

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        //print_r($saran[0]);
        echo json_encode($output);
        die();
    }
    public function ajax_listws(){
        $this->auth->restrict($this->permissionView);
        $PNS_ID = $this->input->post('PNS_ID');

        $this->pegawai_model->where("PNS_ID",$PNS_ID);
        $pegawai_data = $this->pegawai_model->find_first_row();
        //print_r($pegawai_data);

        $output=array();
        $output['draw']="";
        $output['data']=array();
        $nomor_urut = 1;

        //get data assesment
        $this->load->library('Apiserviceassesment');
        $Classassesment = new Apiserviceassesment();
        $dataassesment  = $Classassesment->getdataassesment(trim($pegawai_data->NIP_BARU));
        //print_r($dataassesment);
       // die($pegawai_data->NIP_BARU);
        $dataassesment = json_decode($dataassesment);
        $output['recordsTotal']= 0;
        if($dataassesment->message != "Not Found"){
            //print_r($dataassesment->data);
           foreach($dataassesment->data as $mydata)
            {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $mydata->tahun_penilaian_title." (".$mydata->tahun_penilaian_id.")";
                $asaran_pengembangan = "";
                //print_r($mydata->saranpengembangan);
                $jmlsaran = count($mydata->saranpengembangan);
                $isi_saran = "";
                if($jmlsaran > 0){
                    for($i=0;$i<$jmlsaran;$i++){
                        //print_r($mydata->saranpengembangan[$i]);
                        $isi_saran  .= "<b>".$mydata->saranpengembangan[$i]->jenis_saran."</b> : ".$mydata->saranpengembangan[$i]->isi_saran."<br>";
                    }
                    $row[] = $isi_saran;
                }else{
                    $row[]  = "";
                }
                

                $output['data'][] = $row;
                $nomor_urut++;

            }    
            $output['recordsTotal']= $output['recordsFiltered']= $nomor_urut - 1;
        }else{
            //print_r($dataassesment);
            $output['recordsTotal']= $output['recordsFiltered']= 0;
        }
        
        //$output['recordsTotal'] = $nomor_urut - 1;
        echo json_encode($output);
        die();
    }
    public function ajax_list_adminwa(){
        $this->auth->restrict($this->permissionViewAdmin);
        $PNS_ID = $this->input->post('PNS_ID');

        $this->pegawai_model->where("PNS_ID",$PNS_ID);
        $pegawai_data = $this->pegawai_model->find_first_row();
        //print_r($pegawai_data);

        $output=array();
        $output['draw']="";
        $output['data']=array();
        $nomor_urut = 1;

        //get data assesment
        $this->load->library('Apiserviceassesment');
        $Classassesment = new Apiserviceassesment();
        $dataassesment  = $Classassesment->getdataassesment(trim($pegawai_data->NIP_BARU));
        //print_r($dataassesment);
       // die($pegawai_data->NIP_BARU);
        $dataassesment = json_decode($dataassesment);
        $output['recordsTotal']= 0;
        if($dataassesment->message != "Not Found"){
            //print_r($dataassesment->data);
           foreach($dataassesment->data as $mydata)
            {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $mydata->tahun_penilaian_title." (".$mydata->tahun_penilaian_id.")";
                $row []  = $mydata->nilai_potensi;
                $row []  = $mydata->nilai_kinerja;
                $asaran_pengembangan = "";
                //print_r($mydata->saranpengembangan);
                $jmlsaran = count($mydata->saranpengembangan);
                $isi_saran = "";
                if($jmlsaran > 0){
                    for($i=0;$i<$jmlsaran;$i++){
                        //print_r($mydata->saranpengembangan[$i]);
                        $isi_saran  .= "<b>".$mydata->saranpengembangan[$i]->jenis_saran."</b> : ".$mydata->saranpengembangan[$i]->isi_saran."<br>";
                    }
                    $row[] = $isi_saran;
                }else{
                    $row[]  = "";
                }
                

                $output['data'][] = $row;
                $nomor_urut++;

            }    
            $output['recordsTotal']= $output['recordsFiltered']= $nomor_urut - 1;
        }else{
            //print_r($dataassesment);
            $output['recordsTotal']= $output['recordsFiltered']= 0;
        }
        
        //$output['recordsTotal'] = $nomor_urut - 1;
        echo json_encode($output);
        die();
    }
    public function show($PNS_ID,$record_id=''){
        if(empty($record_id)){
            $this->auth->restrict($this->permissionCreate);
            Template::set_view("kepegawaian/riwayat_assesmen_crud");
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Riwayat Assesmen");
            Template::render();
        }
        else {
    		$datadetil = $this->riwayat_assesmen_model->find($record_id); 
            $this->auth->restrict($this->permissionEdit);
            Template::set_view("kepegawaian/riwayat_assesmen_crud");
           
            Template::set('detail_riwayat', $datadetil);    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Riwayat Assesmen");

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
    	$datadetil = $this->riwayat_assesmen_model->find($record_id); 
            $this->auth->restrict($this->permissionView);
            Template::set_view("kepegawaian/riwayat_assesmen_detil");
           
            Template::set('detail_riwayat', $datadetil);    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Riwayat Assesmen");

            Template::render();
		Template::render();
    }
    
    public function save(){
         // Validate the data
        $this->form_validation->set_rules($this->riwayat_assesmen_model->get_validation_rules());
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
		
		
        $data = $this->riwayat_assesmen_model->prep_data($this->input->post());
       	$this->pegawai_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $pegawai_data = $this->pegawai_model->find_first_row();  
        $data["PNS_NIP"]    = $pegawai_data->NIP_BARU;
        $data["PNS_ID"]     = $pegawai_data->PNS_ID;

        $data["FILE_UPLOAD_FB_POTENSI"]     = $this->input->post("FILE_UPLOAD_FB_POTENSI");
        $data["FILE_UPLOAD_LENGKAP_PT"]     = $this->input->post("FILE_UPLOAD_LENGKAP_PT");
        $data["FILE_UPLOAD_FB_PT"]          = $this->input->post("FILE_UPLOAD_FB_PT");
        
        $id_data = $this->input->post("ID");
        if(isset($id_data) && !empty($id_data)){
            $this->riwayat_assesmen_model->update($id_data,$data);
        }
        else $this->riwayat_assesmen_model->insert($data);
        $response ['success']= true;
        $response ['msg']= "berhasil";
        echo json_encode($response);    

    }
    public function saveimport(){
         // Validate the data
        $year = $this->input->post("TAHUN");
        $this->load->library('Apiserviceassesment');
        $Classassesment = new Apiserviceassesment();
        $dataassesment  = $Classassesment->getdataassesmentTahun(trim($year));
        //$dataassesment  = $Classassesment->getdataassesment("197208052006052001");

        //print_r($dataassesment);
        //die("masuk".$year);
        $dataassesment = json_decode($dataassesment);
        $output['recordsTotal']= 0;
        $data = array();
        $msg = "Daa";
        if($dataassesment->status == true){
           foreach($dataassesment->data as $mydata)
            {
                if($mydata->nip == "197208052006052001"){
                    
                }
                //echo $mydata->tahun_penilaian_awal." ".$mydata->tahun_penilaian_id."<br>";
                $TAHUN        = $this->input->post('kode_material');
                $datadel      = array('TAHUN '=>$mydata->tahun_penilaian_awal,'PNS_NIP'=>$mydata->nip,'TAHUN_PENILAIAN_ID'=>$mydata->tahun_penilaian_id);
                $this->riwayat_assesmen_model->delete_where($datadel);

                $record['PNS_NIP'] = $mydata->nip;
                $record['TAHUN'] = $mydata->tahun_penilaian_awal;
                $record['NILAI'] = $mydata->nilai_potensi;
                $record['NILAI_KINERJA'] = $mydata->nilai_kinerja;
                $record['TAHUN_PENILAIAN_ID'] = $mydata->tahun_penilaian_id;
                $record['TAHUN_PENILAIAN_TITLE'] = $mydata->tahun_penilaian_title;

                $record['FULLNAME'] = $mydata->fullname;
                $record['POSISI_ID'] = $mydata->posisi_id;
                $record['UNIT_ORG_ID'] = $mydata->unit_org_kd;
                $record['NAMA_UNOR'] = $mydata->nama_unor;
                $record['SARANPENGEMBANGAN'] = strip_tags(json_encode($mydata->saranpengembangan));

                if($mydata->tahun_penilaian_awal > 2018){
                    $record['FILE_UPLOAD'] = 'a_p_'.$mydata->nip.'_lkp_'.$mydata->tahun_penilaian_awal;
                    $record['FILE_UPLOAD_FB_POTENSI'] = 'a_p_'.$mydata->nip.'_fb_'.$mydata->tahun_penilaian_awal;
                    $record['FILE_UPLOAD_LENGKAP_PT'] = 'a_l_'.$mydata->nip.'_lkp_'.$mydata->tahun_penilaian_awal;
                    $record['FILE_UPLOAD_FB_PT'] = 'a_l_'.$mydata->nip.'_fb_'.$mydata->tahun_penilaian_awal;
                }
                

                $data[] = $record;

            }
            $status = $this->db->insert_batch("rwt_assesmen",$data);
            if($status){
                $msg = "Berhasil";
            }

        }else{
            $response ['success']= false;
            $response ['msg']= $dataassesment->message;
            echo json_encode($response);    
            exit();
        }
        

        
        $response ['success']= true;
        $response ['msg']= $msg;
        echo json_encode($response);    

    }
    public function delete($record_id){
        $this->auth->restrict($this->permissionDelete);
        $this->load->helper('handle_upload');
        $datadetil = $this->riwayat_assesmen_model->find($record_id);
        $file_upload = $datadetil->FILE_UPLOAD != "" ? trim($datadetil->FILE_UPLOAD) : ""; 
        deletefile($file_upload,trim($this->settings_lib->item('site.pathassesment')));

		if ($this->riwayat_assesmen_model->delete($record_id)) {
			 log_activity($this->auth->user_id(), 'delete data Riwayat Kursus : ' . $record_id . ' : ' . $this->input->ip_address(), 'riwayat_assesmen');
			 Template::set_message("Sukses Hapus data", 'success');
			 echo "Sukses";
		}else{
			echo "Gagal";
		}

		exit();
    }
    public function deletefile($record_id){
        $this->auth->restrict($this->permissionDelete);
        $this->load->helper('handle_upload');
        $datadetil = $this->riwayat_assesmen_model->find($record_id);
        $file_upload = $datadetil->FILE_UPLOAD != "" ? trim($datadetil->FILE_UPLOAD) : ""; 
        if(deletefile($file_upload,trim($this->settings_lib->item('site.pathassesment')))){
            echo "Sukses";
        }else{
            echo "Gagal";
        }
        exit();
    }
    public function index($PNS_ID='1552260645'){
        Template::set_view("kepegawaian/tab_pane_riwayat_assesmen");
        Template::set('PNS_ID', $PNS_ID);
        Template::set('TAB_ID', uniqid("TAB_CONTOH"));
        Template::render();
    }
    function uploadberkas(){
        $tahun = $this->input->post('tahun');
        $kode = $this->input->post('kode');
        $satker = $this->input->post('satker');
        $kolom = $this->input->post('kolom');
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error',
            'namafile'=>''
        );

        $this->load->helper('handle_upload');
         $uploadData = array();
         $upload = true;
         $id = "";
         $namafile = "";
         if (isset($_FILES['userfile']) && is_array($_FILES['userfile']) && $_FILES['userfile']['error'] != 4)
         {
            $tmp_name = pathinfo($_FILES['userfile']['name'], PATHINFO_FILENAME);
            $uploadData = handle_upload('userfile',trim($this->settings_lib->item('site.pathassesment')));
             if (isset($uploadData['error']) && !empty($uploadData['error']))
             {
                $tipefile=$_FILES['userfile']['type'];
                //$tipefile = $_FILES['userfile']['name'];
                 $upload = false;
                 log_activity($this->auth->user_id(), 'Gagal : '.$uploadData['error'].$tipefile.$this->input->ip_address(), 'pegawai');
             }else{
                
                $namafile = $uploadData['data']['file_name'];
             }
         }else{
            die("File tidak ditemukan");
            log_activity($this->auth->user_id(), 'File tidak ditemukan : ' . $this->input->ip_address(), 'pegawai');
         }  
        //print_r($uploadData);
        //die(trim($this->settings_lib->item('site.pathassesment')));
        echo $namafile;
        //echo json_encode($response);    
       exit();
    }
}
