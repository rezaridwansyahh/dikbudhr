<?php defined('BASEPATH') || exit('No direct script access allowed');

class Kondisi_pegawai_model extends BF_Model
{
    protected $table_name	= 'rpt_golongan_bulan';
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
			'field' => 'NIP_LAMA',
			'label' => 'lang:pegawai_field_NIP_LAMA',
			'rules' => 'max_length[9]',
		),
		 
	);
	protected $insert_validation_rules  = array();
	protected $skip_validation 			= true;

    
	public function group_by_golonganbulan($bulan="",$tahun = ""){		
		 

		if(empty($this->selects))
		{
			$this->select('GOLONGAN_NAMA,BULAN,JUMLAH');
		}
		if($bulan != ""){
			$this->db->where("BULAN",$bulan);	
		}
		if($tahun != ""){
			$this->db->where("TAHUN",$tahun);	
		}
		//$this->db->join('golongan', 'golongan.ID = rpt_golongan_bulan.GOLONGAN_ID', 'left');
		//$this->db->group_by('rpt_golongan_bulan.GOLONGAN_ID');
		//$this->db->group_by('rpt_golongan_bulan.BULAN');
		//$this->db->group_by('rpt_golongan_bulan.TAHUN');
		return parent::find_all();
	}
}
