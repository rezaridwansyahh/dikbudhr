<?php defined('BASEPATH') || exit('No direct script access allowed');

class Request_formasi_model extends BF_Model
{
    protected $table_name	= 'request_formasi';
	protected $key			= 'id';
	protected $date_format	= 'datetime';

	protected $log_user 	= false;
	protected $set_created	= false;
	protected $set_modified = false;
	protected $soft_deletes	= false;

    protected $deleted_field     = 'deleted';

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
			'field' => 'id_jabatan',
			'label' => 'Jabatan',
			'rules' => 'required',
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
    public function find_all($satker_id = "")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.id,unit_id,jumlah_ajuan,kualifikasi_pendidikan,id_jabatan,satker_id,jabatan.NAMA_JABATAN,NAMA_UNOR');
		}
		if($satker_id != "")
			$this->db->where("satker_id",trim($satker_id));
		$this->db->join('jabatan', 'request_formasi.id_jabatan = jabatan.KODE_JABATAN', 'left');
		$this->db->join('vw_unit_list', 'vw_unit_list.ID = request_formasi.satker_id', 'left');
		$this->db->order_by("jabatan.KODE_JABATAN","ASC");
		return parent::find_all();
	}

	public function count_all($satker_id = "")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.id,unit_id,jumlah_ajuan,kualifikasi_pendidikan,id_jabatan,satker_id');
		}
		if($satker_id != "")
			$this->db->where("satker_id",trim($satker_id));
		return parent::count_all();
	}
	public function find_rekap($satker_id = "")
	{
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.satker_id,NAMA_UNOR,sum(jumlah_ajuan) as jumlah_ajuan');
		}
		if($satker_id != "")
			$this->db->where("satker_id",trim($satker_id));

		$this->db->join('vw_unit_list', 'vw_unit_list.ID = request_formasi.satker_id', 'left');
		$this->db->group_by("satker_id");
		$this->db->group_by("NAMA_UNOR");
		return parent::find_all();
	}
	public function find_group_jabatan($tahun = "",$satker_id = ""){
		if(empty($this->selects))
		{
			$this->select('sum(jumlah_ajuan) as jumlah,id_jabatan');
		}
		if($satker_id != "")
			$this->db->where("satker_id",trim($satker_id));
		if($satker_id != "")
			$this->db->where("satker_id",trim($satker_id));

		// $this->db->join('vw_unit_list', 'vw_unit_list.ID = request_formasi.satker_id', 'left');
		$this->db->group_by("id_jabatan");
		return parent::find_all();
	}
	 
}