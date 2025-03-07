<?php defined('BASEPATH') || exit('No direct script access allowed');

class Jenis_izin_model extends BF_Model
{
    protected $table_name	= 'jenis_izin';
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
			'field' => 'KODE',
			'label' => 'lang:jenis_izin_field_KODE',
			'rules' => 'required|unique[jenis_izin.KODE,jenis_izin.ID]|max_length[8]',
		),
		array(
			'field' => 'NAMA_IZIN',
			'label' => 'lang:jenis_izin_field_NAMA_IZIN',
			'rules' => 'required|unique[jenis_izin.NAMA_IZIN,jenis_izin.ID]|max_length[50]',
		),
		array(
			'field' => 'KETERANGAN',
			'label' => 'lang:jenis_izin_field_KETERANGAN',
			'rules' => '',
		),
		array(
			'field' => 'PERSETUJUAN',
			'label' => 'PERSETUJUAN',
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
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.ID,KODE,NAMA_IZIN,KETERANGAN,PERSETUJUAN,URUTAN,STATUS');
		}
		$this->db->order_by("URUTAN","ASC");
		return parent::find_all();
	}
	public function grupby_jenis($nip = '',$DARI_TANGGAL = "",$SAMPAI_TANGGAL = "")
	{
		 
		if (empty($this->selects)) {
			$this->select('jenis_izin.ID AS "KODE_IZIN",NAMA_IZIN,count("izin"."ID") as jumlah');
		}
		if($DARI_TANGGAL != ""){
			$this->izin_pegawai_model->where("DARI_TANGGAL >= '{$DARI_TANGGAL}'");  
        	$this->izin_pegawai_model->where("SAMPAI_TANGGAL <= '{$SAMPAI_TANGGAL}'");  
		}
		$this->db->group_by('jenis_izin.ID');
		$this->db->group_by('NAMA_IZIN');
		$this->db->where("NIP_PNS",$nip);
		$this->db->join('izin', 'izin.KODE_IZIN = jenis_izin.ID', 'left');
		return parent::find_all();
	}
	public function find_active()
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.ID,KODE,NAMA_IZIN,KETERANGAN,PERSETUJUAN,URUTAN,STATUS');
		}
		$this->db->where("STATUS","1");
		$this->db->order_by("URUTAN","ASC");

		return parent::find_all();
	}
	public function find_mobile()
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.ID,KODE,NAMA_IZIN,KETERANGAN,PERSETUJUAN,URUTAN,STATUS');
		}
		$this->db->where("mobile",1);
		$this->db->order_by("URUTAN","ASC");
		return parent::find_all();
	}
}