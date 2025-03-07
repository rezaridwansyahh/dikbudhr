<?php defined('BASEPATH') || exit('No direct script access allowed');

class Arsip_digital_model extends BF_Model
{
    protected $table_name	= 'arsip';
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
			'field' => 'ID_JENIS_ARSIP',
			'label' => 'lang:arsip_digital_field_ID_JENIS_ARSIP',
			'rules' => 'max_length[32]',
		),
		array(
			'field' => 'NIP',
			'label' => 'lang:arsip_digital_field_NIP',
			'rules' => 'max_length[25]',
		),
		array(
			'field' => 'KETERANGAN',
			'label' => 'lang:arsip_digital_field_KETERANGAN',
			'rules' => 'max_length[255]',
		),
		array(
			'field' => 'EXTENSION_FILE',
			'label' => 'lang:arsip_digital_field_EXTENSION_FILE',
			'rules' => 'max_length[20]',
		),
		array(
			'field' => 'JENIS_FILE',
			'label' => 'lang:arsip_digital_field_JENIS_FILE',
			'rules' => 'max_length[50]',
		),
		array(
			'field' => 'FILE_SIZE',
			'label' => 'lang:arsip_digital_field_FILE_SIZE',
			'rules' => 'max_length[20]',
		),
		array(
			'field' => 'FILE_BASE64',
			'label' => 'lang:arsip_digital_field_FILE_BASE64',
			'rules' => '',
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
    public function find_all()
	{
 
		$this->db->select('arsip.ID,ID_JENIS_ARSIP,NIP,arsip.KETERANGAN,
			JENIS_FILE,pegawai."NIP_BARU",pegawai."NAMA",pegawai."ID" as "ID_PEGAWAI",NAMA_JENIS,FILE_SIZE,EXTENSION_FILE');

		$this->db->join('pegawai', 'pegawai.NIP_BARU = arsip.NIP', 'left');
		$this->db->join('jenis_arsip', 'arsip.ID_JENIS_ARSIP = jenis_arsip.ID', 'left');
		$this->db->order_by("pegawai.NAMA", "ASC");
		return parent::find_all();
	}
	public function count_all()
	{
 
		$this->db->select('arsip.ID,ID_JENIS_ARSIP,NIP,arsip.KETERANGAN,
			JENIS_FILE,pegawai."NIP_BARU",pegawai."NAMA",pegawai."ID" as "ID_PEGAWAI",NAMA_JENIS');

		$this->db->join('pegawai', 'pegawai.NIP_BARU = arsip.NIP', 'left');
		$this->db->join('jenis_arsip', 'arsip.ID_JENIS_ARSIP = jenis_arsip.ID', 'left');
		return parent::count_all();
	}
	public function find_all_pegawai()
	{
 
		$this->db->select('arsip.ID,ID_JENIS_ARSIP,NIP,arsip.KETERANGAN,
			JENIS_FILE,NAMA_JENIS,FILE_SIZE,EXTENSION_FILE,ISVALID,location,name');

		$this->db->join('jenis_arsip', 'arsip.ID_JENIS_ARSIP = jenis_arsip.ID', 'left');
		return parent::find_all();
	}
	public function count_all_pegawai()
	{
 
		$this->db->select('arsip.ID,ID_JENIS_ARSIP,NIP,arsip.KETERANGAN,
			JENIS_FILE,NAMA_JENIS');

		$this->db->join('jenis_arsip', 'arsip.ID_JENIS_ARSIP = jenis_arsip.ID', 'left');
		return parent::count_all();
	}
	public function find_group_es1($parent_id = "")
	{
 
		$this->db->select("ESELON_1,count(*) \"JUMLAH_ARSIP\"");

		$this->db->join('mv_pegawai', 'mv_pegawai.NIP_BARU = arsip.NIP', 'left');
		$this->db->group_by("ESELON_1");
		return parent::find_all();
	}
	public function find_group_es2($parent_id = "")
	{
 
		$this->db->select("ESELON_2,count(*) \"JUMLAH_ARSIP\"");

		$this->db->join('mv_pegawai', 'mv_pegawai.NIP_BARU = arsip.NIP', 'left');
		$this->db->group_by("ESELON_2");
		return parent::find_all();
	}
	public function find_group_es1_kategori_arsip($parent_id = "")
	{
 
		$this->db->select("ESELON_1,KATEGORI_ARSIP,count(*) \"JUMLAH_ARSIP\"");
		$this->db->join('jenis_arsip', 'arsip.ID_JENIS_ARSIP = jenis_arsip.ID', 'left');
		$this->db->join('mv_pegawai', 'mv_pegawai.NIP_BARU = arsip.NIP', 'left');
		$this->db->group_by("ESELON_1");
		$this->db->group_by("KATEGORI_ARSIP");
		return parent::find_all();
	}
	public function find_group_es2_kategori_arsip($parent_id = "")
	{
 
		$this->db->select("ESELON_2,KATEGORI_ARSIP,count(*) \"JUMLAH_ARSIP\"");
		$this->db->join('jenis_arsip', 'arsip.ID_JENIS_ARSIP = jenis_arsip.ID', 'left');
		$this->db->join('mv_pegawai', 'mv_pegawai.NIP_BARU = arsip.NIP', 'left');
		$this->db->group_by("ESELON_2");
		$this->db->group_by("KATEGORI_ARSIP");
		return parent::find_all();
	}
}