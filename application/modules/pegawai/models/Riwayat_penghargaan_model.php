<?php defined('BASEPATH') || exit('No direct script access allowed');

class Riwayat_penghargaan_model extends BF_Model
{
	protected $table_name	= 'rwt_penghargaan';
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
			'field' => 'PNS_ID',
			'label' => 'PNS ID',
			'rules' => 'max_length[32]|required',
		),
		array(
			'field' => 'ID_JENIS_PENGHARGAAN',
			'label' => 'Jenis Penghargaan',
			'rules' => 'required',
		),
		array(
			'field' => 'SK_NOMOR',
			'label' => 'Nomor SK',
			'rules' => 'required',
		),
		array(
			'field' => 'SK_TANGGAL',
			'label' => 'Tanggal SK',
			'rules' => 'required',
		),
		// array(
		// 	'field' => 'NOMOR_IJASAH',
		// 	'label' => 'Nomor Ijasah',
		// 	'rules' => 'required',
		// ),
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
	public function find_all($PNS_ID = "")
	{

		if (empty($this->selects)) {
			$this->select($this->table_name . '.*,jp.NAMA as jenis_penghargaan_text');
		}
		if ($PNS_ID != "") {
			$this->db->where('PNS_ID', $PNS_ID);
		}
		$this->db->join('jenis_penghargaan as jp', 'jp.ID = rwt_penghargaan.ID_JENIS_PENGHARGAAN', 'left');
		$this->db->order_by("SK_TANGGAL","ASC");
		return parent::find_all();
	}

	public function count_all($PNS_ID = "")
	{

		if (empty($this->selects)) {
			$this->select($this->table_name . '.*,jp.NAMA as jenis_penghargaan_text');
		}
		if ($PNS_ID != "") {
			$this->db->where('PNS_ID', $PNS_ID);
		}
		return parent::count_all();
	}
}
