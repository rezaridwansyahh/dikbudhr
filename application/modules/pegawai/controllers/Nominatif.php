<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Nominatif extends Admin_Controller
{
    protected $permissionView   = 'Nominatif.Pegawai.View';
    
	public $UNOR_ID = null;
	public $UkerTerbatas = false;
	
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        

		set_time_limit(0);
		parent::__construct(); 
        $this->load->model('pegawai/pegawai_model');
		$this->load->model('pegawai/riwayat_pendidikan_model');
		$this->load->model('pegawai/riwayat_kepangkatan_model');
        $this->lang->load('pegawai');
        
		// filter untuk role yang filtersatkernya aktif
		if($this->auth->has_permission($this->permissionFiltersatker)){
			$this->UNOR_ID = $this->pegawai_model->getunor_induk($this->auth->username());
			if($this->UNOR_ID == "")
				$this->UNOR_ID = "-";
		}
		if($this->auth->has_permission($this->permissionEselon1)){
			$this->UNOR_ID = $this->pegawai_model->getunor_eselon1($this->auth->username());
			if($this->UNOR_ID == "")
				$this->UNOR_ID = "-";
		}
		
    }

    /**
     * Display a list of pegawai data.
     *
     * @return void
     */
    public function index()
    {	

    	$this->auth->restrict($this->permissionView);
        Template::set('toolbar_title', lang('pegawai_manage'));
		
        Template::render();
    }
    public function pejabat()
    {	
    	$this->auth->restrict($this->permissionView);
        Template::set('toolbar_title', "Daftar Pejabat");		
        Template::render();
    }
    
    //--------------------------------------------------------------------------
    // !PRIVATE METHODS
    //--------------------------------------------------------------------------

    /**
     * Save the data.
     *
     * @param string $type Either 'insert' or 'update'.
     * @param int    $id   The ID of the record to update, ignored on inserts.
     *
     * @return boolean|integer An ID for successful inserts, true for successful
     * updates, else false.
     */
    public function getdatapejabat(){
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
			
		}
		$kedudukan_hukum = "";
		$this->db->start_cache();
		
		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		$advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
		if($advanced_search_filters){
			$filters = array();
			foreach($advanced_search_filters as  $filter){
				$filters[$filter['name']] = $filter["value"];
			}
			if($filters['unit_id_key']){
				$this->db->group_start();
				$this->db->where('vw."ID"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);	
				$this->db->group_end();
			}
			if($filters['nama_key']){
				$this->pegawai_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");	
			}
			if($filters['nip_key']){
				$this->pegawai_model->like('upper("NIP_BARU")',strtoupper($filters['nip_key']),"BOTH");	
			}
			if($filters['nama_jabatan_key']){
				$this->pegawai_model->like('upper(jabatan."NAMA_JABATAN")',strtoupper($filters['nama_jabatan_key']),"BOTH");	
			}
		}
		
		$this->db->stop_cache();
		$output=array();
		$output['draw']=$draw;
		$asatkers = null;
		if($this->auth->has_permission($this->UnitkerjaTerbatas)){
			$asatkers = json_decode($this->auth->get_satkers());
			$total= $this->pegawai_model->count_all_pejabat($asatkers,false,$kedudukan_hukum);
		}else{
			$total= $this->pegawai_model->count_all_pejabat($this->UNOR_ID,false,$kedudukan_hukum);
		}
		
		$orders = $this->input->post('order');
		foreach($orders as $order){
			if($order['column']==1){
				$this->pegawai_model->order_by("NIP_BARU",$order['dir']);
			}
			if($order['column']==2){
				$this->pegawai_model->order_by("jabatan.NAMA_JABATAN",$order['dir']);
			}
			if($order['column']==3){
				$this->pegawai_model->order_by("NAMA_PANGKAT",$order['dir']);
			}
			if($order['column']==4){
				$this->pegawai_model->order_by("NAMA_UNOR",$order['dir']);
			}
		}
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
		
		$this->pegawai_model->limit($length,$start);
		if($this->auth->has_permission($this->UnitkerjaTerbatas)){
			$asatkers = json_decode($this->auth->get_satkers());
			$records=$this->pegawai_model->find_all_pejabat($asatkers,false,$kedudukan_hukum);
		}else{
			$records=$this->pegawai_model->find_all_pejabat($this->UNOR_ID,false,$kedudukan_hukum);
		}
		
		
		$this->db->flush_cache();
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
				$foto_pegawai = base_url().trim($this->settings_lib->item('site.urlphoto'))."noimage.jpg";
                if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$record->PHOTO) and $record->PHOTO != ""){
                    $foto_pegawai =  base_url().trim($this->settings_lib->item('site.urlphoto')).$record->PHOTO;
                }
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = "<img src='".$foto_pegawai."' width='100%'>";
                $row []  = $record->NIP_BARU."<br><b>".$record->NAMA."</b>";
                $row []  = $record->NAMA_JABATAN;
                $row []  = $record->NAMA_PANGKAT."/".$record->NAMA_GOLONGAN;
                $row []  = $record->NAMA_UNOR_FULL;;
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/profilen/".urlencode(base64_encode($record->ID))."' data-toggle='tooltip' title='Lihat Profile' tooltip='Lihat Profile' class='btn btn-sm btn-info'><i class='fa fa-user'></i> </a>";
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		
	}
    public function downloadnominatifpejabat(){
		$advanced_search_filters  = $_GET;
		if($advanced_search_filters){
			$filters = $advanced_search_filters;
			if(isset($filters['unit_id_cb']) and $filters['unit_id_cb'] != ""){
				$this->db->group_start();
				$this->db->where('vw."ID"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);	
				$this->db->group_end();
			}
			if($filters['nama_key']){
				$this->pegawai_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");	
			}
			if($filters['nip_key']){
				$this->pegawai_model->like('upper("NIP_BARU")',strtoupper($filters['nip_key']),"BOTH");	
			}
			if($filters['nama_jabatan_key']){
				$this->pegawai_model->like('upper("NIP_BARU")',strtoupper($filters['nama_jabatan_key']),"BOTH");	
			}
		}
		
		$datapegwai = $this->pegawai_model->find_all_pejabat();
        $this->load->library('LibOpenTbs');
        $template_name = trim($this->settings_lib->item('site.pathuploaded')).'templatenominatif_pejabat.xlsx';
        $TBS = $this->libopentbs->TBS;
        $TBS->LoadTemplate($template_name, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
        $TBS->MergeBlock('r', $datapegwai);
        $TBS->MergeField('a', array(
            'bulan'=>'maret',
        )); 

        $output_file_name = 'Nominatif_pegawai.xlsx';
        $output_file_name = str_replace('.', '_'.date('Y-m-d').'.', $output_file_name);
        $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.
	}
}

	
