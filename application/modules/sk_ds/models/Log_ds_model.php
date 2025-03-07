<?php defined('BASEPATH') || exit('No direct script access allowed');

class Log_ds_model extends BF_Model
{
    protected $table_name	= 'log_ds';
	protected $key			= 'id_file';
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
			'field' => 'ID_FILE',
			'label' => 'ID FILE',
			'rules' => '',
		),
		array(
			'field' => 'KETERANGAN',
			'label' => 'KETERANGAN',
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
    public function find_all($ID_FILE = "")
	{

		$this->db->select('ID_FILE,log_ds.NIK,KETERANGAN,log_ds.CREATED_DATE,log_ds.CREATED_BY,STATUS,NAMA');
		$this->db->join('pegawai', 'pegawai.NIK = log_ds.NIK', 'left');
		if ($ID_FILE) {
			$this->db->where("ID_FILE",$ID_FILE);
		}
		
		$this->db->order_by("log_ds.CREATED_DATE", "DESC");
		$this->db->order_by("log_ds.ID", "DESC");
		//
		return parent::find_all();
	}
	public function count_all($ID_FILE = "")
	{

		 
		$this->db->select('ID_FILE,NIK,KETERANGAN');
		$this->db->join('pegawai', 'pegawai.NIK = log_ds.NIK', 'left');
		if ($ID_FILE) {
			$this->db->where("ID_FILE",$ID_FILE);
		}
		return parent::count_all();
	}
	 
}