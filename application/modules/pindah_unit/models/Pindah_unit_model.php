<?php defined('BASEPATH') || exit('No direct script access allowed');

class Pindah_unit_model extends BF_Model
{
    protected $table_name	= 'pindah_unit';
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
			'label' => 'PEGAWAI',
			'rules' => 'max_length[32]',
		),
		array(
			'field' => 'SURAT_PERMOHONAN_PINDAH',
			'label' => 'lang:pindah_unit_field_SURAT_PERMOHONAN_PINDAH',
			'rules' => 'max_length[100]',
		),
		array(
			'field' => 'UNIT_ASAL',
			'label' => 'lang:pindah_unit_field_UNIT_ASAL',
			'rules' => 'max_length[32]',
		),
		array(
			'field' => 'UNIT_TUJUAN',
			'label' => 'lang:pindah_unit_field_UNIT_TUJUAN',
			'rules' => 'max_length[32]',
		),
		array(
			'field' => 'SURAT_PERNYATAAN_MELEPAS',
			'label' => 'lang:pindah_unit_field_SURAT_PERNYATAAN_MELEPAS',
			'rules' => 'max_length[100]',
		),
		array(
			'field' => 'SK_KP_TERAKHIR',
			'label' => 'lang:pindah_unit_field_SK_KP_TERAKHIR',
			'rules' => 'max_length[100]',
		),
		array(
			'field' => 'SK_JABATAN',
			'label' => 'lang:pindah_unit_field_SK_JABATAN',
			'rules' => 'max_length[100]',
		),
		array(
			'field' => 'SKP',
			'label' => 'lang:pindah_unit_field_SKP',
			'rules' => 'max_length[10]',
		),
		array(
			'field' => 'SK_TUNKIN',
			'label' => 'lang:pindah_unit_field_SK_TUNKIN',
			'rules' => 'max_length[100]',
		),
		array(
			'field' => 'SURAT_PERNYATAAN_MENERIMA',
			'label' => 'lang:pindah_unit_field_SURAT_PERNYATAAN_MENERIMA',
			'rules' => 'max_length[100]',
		),
		array(
			'field' => 'NO_SK_PINDAH',
			'label' => 'lang:pindah_unit_field_NO_SK_PINDAH',
			'rules' => 'max_length[100]',
		),
		array(
			'field' => 'TANGGAL_SK_PINDAH',
			'label' => 'lang:pindah_unit_field_TANGGAL_SK_PINDAH',
			'rules' => 'max_length[10]',
		),
		array(
			'field' => 'FILE_SK',
			'label' => 'lang:pindah_unit_field_FILE_SK',
			'rules' => 'max_length[100]',
		),
	);
	protected $insert_validation_rules  = array();
	protected $skip_validation 			= false;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    public function find_all($PNS_ID ="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*,UA.NAMA_UNOR_FULL AS NAMA_UNOR,NAMA,UT.NAMA_UNOR_FULL AS NAMA_UNOR_TUJUAN');
		}
		if($PNS_ID!=""){
			$this->db->where('PNS_ID',$PNS_ID);
		}
		$this->db->join("vw_unit_list UA","pindah_unit.UNIT_ASAL=UA.ID", 'left');
		$this->db->join("vw_unit_list UT","pindah_unit.UNIT_TUJUAN=UT.ID", 'left');
		$this->db->join("pegawai","pindah_unit.NIP=pegawai.PNS_ID", 'left');
		return parent::find_all();
	}
	public function count_all($PNS_ID ="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*,UA.NAMA_UNOR,NAMA,UT.NAMA_UNOR AS NAMA_UNOR_TUJUAN');
		}
		if($PNS_ID!=""){
			$this->db->where('PNS_ID',$PNS_ID);
		}
		$this->db->join("vw_unit_list UA","pindah_unit.UNIT_ASAL=UA.ID", 'left');
		$this->db->join("vw_unit_list UT","pindah_unit.UNIT_TUJUAN=UT.ID", 'left');
		$this->db->join("pegawai","pindah_unit.NIP=pegawai.PNS_ID", 'left');
		return parent::count_all();
	}
	public function find_notif($UNIT_ASAL ="",$PNS_NIP = "")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*,UA.NAMA_UNOR,NAMA,UT.NAMA_UNOR AS NAMA_UNOR_TUJUAN');
		}
		 
		$this->db->where('(UA."ESELON_1" = \''.$UNIT_ASAL.'\' OR UA."ESELON_2" = \''.$UNIT_ASAL.'\' OR UA."ESELON_3" = \''.$UNIT_ASAL.'\' OR UA."ESELON_4" = \''.$UNIT_ASAL.'\' 
			OR
			UT."ESELON_1" = \''.$UNIT_ASAL.'\' OR UT."ESELON_2" = \''.$UNIT_ASAL.'\' OR UT."ESELON_3" = \''.$UNIT_ASAL.'\' OR UT."ESELON_4" = \''.$UNIT_ASAL.'\' 
			OR "NIP_BARU" = \''.$PNS_NIP.'\')');
		$this->db->where('"STATUS_BIRO" != 1 and "STATUS_BIRO" IS NOT NULL and pindah_unit."KETERANGAN" != \'\'');
		$this->db->join("unitkerja UA","pindah_unit.UNIT_ASAL=UA.ID", 'left');
		$this->db->join("unitkerja UT","pindah_unit.UNIT_TUJUAN=UT.ID", 'left');
		$this->db->join("pegawai","pindah_unit.NIP=pegawai.PNS_ID", 'left');
		return parent::find_all();
	}
	public function count_notif($UNIT_ASAL ="",$PNS_NIP = "")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*');
		}
		 
		$this->db->where('(UA."ESELON_1" = \''.$UNIT_ASAL.'\' OR UA."ESELON_2" = \''.$UNIT_ASAL.'\' OR UA."ESELON_3" = \''.$UNIT_ASAL.'\' OR UA."ESELON_4" = \''.$UNIT_ASAL.'\' 
			OR
			UT."ESELON_1" = \''.$UNIT_ASAL.'\' OR UT."ESELON_2" = \''.$UNIT_ASAL.'\' OR UT."ESELON_3" = \''.$UNIT_ASAL.'\' OR UT."ESELON_4" = \''.$UNIT_ASAL.'\' 
			OR "NIP_BARU" = \''.$PNS_NIP.'\')');
		$this->db->where('"STATUS_BIRO" != 1 and "STATUS_BIRO" IS NOT NULL and pindah_unit."KETERANGAN" != \'\'');
		$this->db->join("unitkerja UA","pindah_unit.UNIT_ASAL=UA.ID", 'left');
		$this->db->join("unitkerja UT","pindah_unit.UNIT_TUJUAN=UT.ID", 'left');
		$this->db->join("pegawai","pindah_unit.NIP=pegawai.PNS_ID", 'left');
		return parent::count_all();
	}

	public function find_notifpribadi($PNS_NIP = "")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*,UA.NAMA_UNOR,NAMA,UT.NAMA_UNOR AS NAMA_UNOR_TUJUAN');
		}
		 
		$this->db->where('("NIP_BARU" = \''.$PNS_NIP.'\')');
		$this->db->where('"STATUS_BIRO" != 1 and "STATUS_BIRO" IS NOT NULL and pindah_unit."KETERANGAN" != \'\'');
		$this->db->join("unitkerja UA","pindah_unit.UNIT_ASAL=UA.ID", 'left');
		$this->db->join("unitkerja UT","pindah_unit.UNIT_TUJUAN=UT.ID", 'left');
		$this->db->join("pegawai","pindah_unit.NIP=pegawai.PNS_ID", 'left');
		return parent::find_all();
	}
	public function count_notifpribadi($PNS_NIP = "")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*');
		}
		 
		$this->db->where('("NIP_BARU" = \''.$PNS_NIP.'\')');
		$this->db->where('"STATUS_BIRO" != 1 and "STATUS_BIRO" IS NOT NULL and pindah_unit."KETERANGAN" != \'\'');
		$this->db->join("unitkerja UA","pindah_unit.UNIT_ASAL=UA.ID", 'left');
		$this->db->join("unitkerja UT","pindah_unit.UNIT_TUJUAN=UT.ID", 'left');
		$this->db->join("pegawai","pindah_unit.NIP=pegawai.PNS_ID", 'left');
		return parent::count_all();
	}
	
}