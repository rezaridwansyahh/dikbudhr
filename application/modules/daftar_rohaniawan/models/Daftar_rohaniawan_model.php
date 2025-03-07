<?php defined('BASEPATH') || exit('No direct script access allowed');

class Daftar_rohaniawan_model extends BF_Model
{
    protected $table_name	= 'daftar_rohaniawan';
	protected $key			= 'id';
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
			'field' => 'nip',
			'label' => 'lang:daftar_rohaniawan_field_nip',
			'rules' => 'max_length[30]',
		),
		array(
			'field' => 'nama',
			'label' => 'lang:daftar_rohaniawan_field_nama',
			'rules' => 'max_length[100]',
		),
		array(
			'field' => 'jabatan',
			'label' => 'lang:daftar_rohaniawan_field_jabatan',
			'rules' => 'max_length[100]',
		),
		array(
			'field' => 'agama',
			'label' => 'lang:daftar_rohaniawan_field_agama',
			'rules' => '',
		),
		array(
			'field' => 'aktif',
			'label' => 'lang:daftar_rohaniawan_field_aktif',
			'rules' => 'max_length[5]',
		),
		array(
			'field' => 'pangkat_gol',
			'label' => 'lang:daftar_rohaniawan_field_pangkat_gol',
			'rules' => 'max_length[30]',
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
    public function find_all($agama = ""){
		
		 
		$this->db->select('daftar_rohaniawan.*,agama."NAMA" AS NAMA_AGAMA');
		
		$this->db->join('agama', 'daftar_rohaniawan.agama = agama.ID', 'left');
		if($agama != ""){
			$this->db->where("agama",$agama);	
		}
		$this->db->order_by("id","ASC");
		return parent::find_all();
	}
	 public function find_aktif($agama = ""){
		
		 
		$this->db->select('daftar_rohaniawan.*,agama."NAMA" AS NAMA_AGAMA');
		
		$this->db->join('agama', 'daftar_rohaniawan.agama = agama.ID', 'left');
		if($agama != ""){
			$this->db->where("agama",$agama);	
		}
		$this->db->where("aktif","1");
		$this->db->order_by("id","ASC");
		return parent::find_all();
	}
}