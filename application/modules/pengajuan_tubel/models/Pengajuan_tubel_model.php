<?php defined('BASEPATH') || exit('No direct script access allowed');

class Pengajuan_tubel_model extends BF_Model
{
    protected $table_name	= 'pengajuan_tubel';
	protected $key			= 'ID';
	protected $date_format	= 'datetime';

	protected $log_user 	= false;
	protected $set_created	= false;
	protected $set_modified = false;
	protected $soft_deletes	= false;


	// Customize the operations of the model without recreating the insert,
    // update, etc. methods by adding the method names to act as callbacks here.
	protected $before_insert 	= array();
	protected $after_insert 	= array();
	protected $before_update 	= array();
	protected $after_update 	= array();
	protected $before_find 	    = array();
	protected $after_find 		= array();
	protected $before_delete 	= array();
	protected $after_delete 	= array();

	// For performance reasons, you may require your model to NOT return the id
	// of the last inserted row as it is a bit of a slow method. This is
    // primarily helpful when running big loops over data.
	protected $return_insert_id = true;

	// The default type for returned row data.
	protected $return_type = 'object';

	// Items that are always removed from data prior to inserts or updates.
	protected $protected_attributes = array();

	// You may need to move certain rules (like required) into the
	// $insert_validation_rules array and out of the standard validation array.
	// That way it is only required during inserts, not updates which may only
	// be updating a portion of the data.
	protected $validation_rules 		= array(
		array(
			'field' => 'NIP',
			'label' => 'lang:pengajuan_tubel_field_NIP',
			'rules' => 'max_length[30]',
		),
		array(
			'field' => 'NOMOR_USUL',
			'label' => 'lang:pengajuan_tubel_field_NOMOR_USUL',
			'rules' => 'max_length[10]',
		),
		array(
			'field' => 'TANGGAL_USUL',
			'label' => 'lang:pengajuan_tubel_field_TANGGAL_USUL',
			'rules' => 'max_length[10]',
		),
		array(
			'field' => 'UNIVERSITAS',
			'label' => 'lang:pengajuan_tubel_field_UNIVERSITAS',
			'rules' => 'max_length[100]',
		),
		array(
			'field' => 'FAKULTAS',
			'label' => 'lang:pengajuan_tubel_field_FAKULTAS',
			'rules' => 'max_length[100]',
		),
		array(
			'field' => 'PRODI',
			'label' => 'lang:pengajuan_tubel_field_PRODI',
			'rules' => 'max_length[100]',
		),
		array(
			'field' => 'BEASISWA',
			'label' => 'lang:pengajuan_tubel_field_BEASISWA',
			'rules' => 'max_length[1]',
		),
		array(
			'field' => 'PEMBERI_BEASISWA',
			'label' => 'lang:pengajuan_tubel_field_PEMBERI_BEASISWA',
			'rules' => 'max_length[100]',
		),
		array(
			'field' => 'JENJANG',
			'label' => 'lang:pengajuan_tubel_field_JENJANG',
			'rules' => 'max_length[5]',
		),
		array(
			'field' => 'NEGARA',
			'label' => 'lang:pengajuan_tubel_field_NEGARA',
			'rules' => 'max_length[50]',
		),
		array(
			'field' => 'STATUS',
			'label' => 'lang:pengajuan_tubel_field_STATUS',
			'rules' => '',
		),
	);
	protected $insert_validation_rules  = array();
	protected $skip_validation 			= true;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    public function find_all($NIP ="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*,tkpendidikan.NAMA');
		}
		if($NIP!=""){
			$this->db->where('NIP',$NIP);
		}
		$this->db->join("tkpendidikan","tkpendidikan.ID = pengajuan_tubel.JENJANG", 'left');
		return parent::find_all();
	}
	public function count_all($NIP ="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*');
		}
		if($NIP!=""){
			$this->db->where('NIP',$NIP);
		}
		return parent::count_all();
	}
	public function find_all_admin($NIP ="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*,pegawai.NAMA,vw."NAMA_UNOR_FULL"');
		}
		if($NIP!=""){
			$this->db->where('NIP',$NIP);
		}
		$this->db->join("pegawai","pengajuan_tubel.NIP=pegawai.NIP_BARU", 'left');
		$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left',false);
		$this->db->join("tkpendidikan","tkpendidikan.ID = pengajuan_tubel.JENJANG", 'left');
		return parent::find_all();
	}
	public function count_all_admin($NIP ="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*,NAMA,vw."NAMA_UNOR_FULL"');
		}
		if($NIP!=""){
			$this->db->where('NIP',$NIP);
		}
		$this->db->join("pegawai","pengajuan_tubel.NIP=pegawai.NIP_BARU", 'left');
		$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left',false);
		return parent::count_all();
	}
}