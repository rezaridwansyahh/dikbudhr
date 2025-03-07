<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Reports extends Admin_Controller
{
    protected $permissionCreate = 'Pegawai.Kepegawaian.Create';
    protected $permissionDelete = 'Pegawai.Kepegawaian.Delete';
    protected $permissionEdit   = 'Pegawai.Kepegawaian.Edit';
    protected $permissionView   = 'Pegawai.Kepegawaian.View';
    protected $permissionAddpendidikan   = 'Pegawai.Kepegawaian.Addpendidikan';
    protected $permissionUbahfoto   = 'Pegawai.Kepegawaian.Ubahfoto';
	public $UNOR_ID = null;
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        
        $this->load->model('pegawai/pegawai_model');
        $this->lang->load('pegawai');
        
            Assets::add_css('flick/jquery-ui-1.8.13.custom.css');
            Assets::add_js('jquery-ui-1.8.13.min.js');
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'kepegawaian/_sub_nav');

        Assets::add_module_js('pegawai', 'pegawai.js');
        
        //load referensi
        $this->load->model('pegawai/jenis_jabatan_model');

		//Jika ada role executive ?
		if($this->CI->auth->role_id() =="5"){
			//???
			$this->UNOR_ID = $this->pegawai_model->getunor_id($this->CI->auth->username());
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
        
    	Template::set('toolbar_title', "Pegawai");
		
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
    public function kelompokjabatan()
    {	
    	$this->auth->restrict($this->permissionView);
    	Template::set('toolbar_title', "Pegawai Kelompok Jabatan");
		
        Template::render();
    }
    public function getkelompokjabatan(){
		$draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length');
		$start= $this->input->post('start');

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		 
		$total= $this->pegawai_model->count_all();;
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();


		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if($search!=""){
			$this->pegawai_model->or_where('upper(golongan."NAMA") LIKE \'%'.strtoupper($search).'%\'');
			$this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
			
		}
		
		$this->pegawai_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "NAMA";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->pegawai_model->order_by($iSortCol,$sSortCol);
		$records=$this->pegawai_model->find_kelompokjabatan();
		
		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($search != "")
		{
			$this->pegawai_model->or_where('upper(golongan."NAMA") LIKE \'%'.strtoupper($search).'%\'');
			$this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
			//$this->pegawai_model->or_where('NIP_BARU',$search);
			$jum	= $this->pegawai_model->count_kelompokjabatan();
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}
		
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->NIP_BARU;
                $row []  = $record->NAMA;
                $row []  = $record->NAMA_GOLONGAN;
                
                $btn_actions = array();
                $btn_actions  [] = "
                    <a class='show-modal-custom' href='".base_url()."admin/kepegawaian/pegawai/profile/".$record->ID."'  data-toggle='modal' title='Ubah Data'><span class='fa-stack'>
					   <i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-eye fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
                $btn_actions  [] = "
                    <a class='show-modal-custom' href='".base_url()."admin/kepegawaian/pegawai/edit/".$record->ID."'  data-toggle='modal' title='Ubah Data'><span class='fa-stack'>
					   	<i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-pencil fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
                $btn_actions  [] = "
                        <a href='#' kode='$record->ID' class='btn-hapus' data-toggle='tooltip' title='Hapus data' >
					   	<span class='fa-stack'>
					   	<i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-trash-o fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
                
                $row[] = implode(" ",$btn_actions);
                

                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		die();

	}
	public function getdatapensiun(){
		$draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length');
		$start= $this->input->post('start');

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		 
		
		$output=array();
		

		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if($search!=""){
			$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
			$this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
		}
		
		$this->pegawai_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "NAMA";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->pegawai_model->order_by($iSortCol,$sSortCol);
		$records=$this->pegawai_model->find_all_pensiun($this->UNOR_ID);

		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($search != "")
		{
			$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
			$this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
			//$this->pegawai_model->or_where('NIP_BARU',$search);
			$jum	= $this->pegawai_model->count_pensiun($this->UNOR_ID);
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}else{
			$total= $this->pegawai_model->count_pensiun($this->UNOR_ID);
			$output['draw']=$draw;
			$output['recordsTotal']= $output['recordsFiltered']=$total;
			$output['data']=array();

		}
		
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->NIP_BARU;
                $row []  = $record->NAMA;
                $row []  = $record->TGL_LAHIR;
                $row []  = $record->umur;
                $row []  = $record->NAMA_UNOR;
                
                $btn_actions = array();
                $btn_actions  [] = "
                    <a class='show-modal-custom' href='".base_url()."admin/kepegawaian/pegawai/profile/".$record->ID."'  data-toggle='modal' title='Ubah Data'><span class='fa-stack'>
					   <i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-eye fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
                $btn_actions  [] = "
                    <a class='show-modal-custom' href='".base_url()."admin/kepegawaian/pegawai/edit/".$record->ID."'  data-toggle='modal' title='Ubah Data'><span class='fa-stack'>
					   	<i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-pencil fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
                $btn_actions  [] = "
                        <a href='#' kode='$record->ID' class='btn-hapus' data-toggle='tooltip' title='Hapus Pegawai' >
					   	<span class='fa-stack'>
					   	<i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-trash-o fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
                
                $row[] = implode(" ",$btn_actions);
                

                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		die();

	}
    public function listpensiun()
    {	
    	$this->auth->restrict($this->permissionView);
        $records = $this->pegawai_model->find_all_pensiun($this->UNOR_ID);
        Template::set('records', $records);
    	Template::set('toolbar_title', "Estimasi Pegawai Pensiun");
		
        Template::render();
    }
}