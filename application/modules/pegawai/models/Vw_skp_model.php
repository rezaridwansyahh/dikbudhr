<?php defined('BASEPATH') || exit('No direct script access allowed');

class Vw_skp_model extends BF_Model
{
    protected $table_name	= 'vw_skp';
	protected $key			= 'ID';
	protected $date_format	= 'datetime';

	protected $log_user 	= false;
	protected $set_created	= false;
	protected $set_modified = false;
	protected $soft_deletes	= true;


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
		
	);
	protected $insert_validation_rules  = array();
	protected $skip_validation 			= true;

    /**
     * Constructor
     *
     * @return void
     */
    
    public function find_all($satker = "")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*');
		}
		if($satker != ""){
			$this->db->where('ID',$satker);
		}
		$this->db->order_by('PNS_ID',"asc");
		$this->db->order_by('PERATURAN',"asc");
		return parent::find_all();
	}

	public function find_by_nip_and_tahun($nip = "",$tahun="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*');
		}
		if($nip != ""){
			$this->db->where('PNS_NIP',$nip);
			$this->db->where('TAHUN',$tahun);
		}
		$this->db->order_by('PNS_ID',"asc");
		$this->db->order_by('PERATURAN',"asc");
		return parent::find_all();
	}

	public function find_skp_2021($nip = "")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*');
		}
		if($nip != ""){
			$this->db->where('PNS_NIP',$nip);
			$this->db->where('TAHUN','2021');
			$this->db->group_start()->where('PERATURAN', 'PP46')->or_where('PERATURAN', 'PP30')->group_end(); // Close bracket
		}
		$this->db->order_by('PNS_ID',"asc");
		$this->db->order_by('PERATURAN',"asc");
		return parent::find_all();
	}

	public function count_all($satker = "")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*');
		}
		if($satker != ""){
			$this->db->where('ID',$satker);
		}
		return parent::count_all();
	}
	 
	
}