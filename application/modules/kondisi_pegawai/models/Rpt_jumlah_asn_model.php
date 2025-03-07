<?php defined('BASEPATH') || exit('No direct script access allowed');

class Rpt_jumlah_asn_model extends BF_Model
{
    protected $table_name	= 'rpt_jumlah_asn';
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
			'field' => 'BULAN',
			'label' => 'BULAN',
			'rules' => 'max_length[2]|required',
		),
		array(
			'field' => 'TAHUN',
			'label' => 'TAHUN',
			'rules' => 'max_length[4]',
		),
		 
	);
	protected $insert_validation_rules  = array();
	protected $skip_validation 			= true;

    
	public function find_first_row(){
		return $this->db->get($this->db->schema.".".$this->table_name)->first_row();
	}
	
	public function find_all($BULAN = "",$TAHUN = ""){
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*');
		}
		if($BULAN!=""){
			$this->db->where('BULAN',$BULAN);
		}
		if($TAHUN!=""){
			$this->db->where('TAHUN',$TAHUN);
		}
		$this->db->order_by('BULAN',"ASC");
		$this->db->order_by('TAHUN',"ASC");

		return parent::count_all();
	}
	public function group_by_asn_tahun($jenis = "",$tahun = ""){		
		 

		if(empty($this->selects))
		{
			$this->select('TAHUN,JUMLAH');
		}
		if($jenis != ""){
			$this->db->where("JENIS",$jenis);	
		}
		if($tahun != ""){
			$this->db->where("TAHUN",$tahun);	
		}
		$this->db->group_by("TAHUN");
		$this->db->group_by("JUMLAH");
		//$this->db->order_by('BULAN',"DESC");
		$this->db->order_by('TAHUN',"ASC");
		return parent::find_all();
	}
	public function group_by_asn_bulan($jenis = "",$tahun = ""){		
		 

		if(empty($this->selects))
		{
			$this->select('BULAN,JUMLAH');
		}
		if($jenis != ""){
			$this->db->where("JENIS",$jenis);	
		}
		if($tahun != ""){
			$this->db->where("TAHUN",$tahun);	
		}
		$this->db->group_by("TAHUN");
		$this->db->group_by("JUMLAH");
		$this->db->group_by("BULAN");
		$this->db->order_by('BULAN',"ASC");
		return parent::find_all();
	}
	public function group_by_jk($jenis = "",$tahun = ""){		
		 

		if(empty($this->selects))
		{
			$this->select('TAHUN,JUMLAH,KETERANGAN');
		}
		if($jenis != ""){
			$this->db->where("JENIS",$jenis);	
		}
		if($tahun != ""){
			$this->db->where("TAHUN",$tahun);	
		}
		$this->db->order_by('TAHUN',"ASC");
		return parent::find_all();
	}
	public function group_by_gol($jenis = "",$tahun = ""){		
		 

		if(empty($this->selects))
		{
			$this->select('TAHUN,JUMLAH,KETERANGAN');
		}
		if($jenis != ""){
			$this->db->where("JENIS",$jenis);	
		}
		if($tahun != ""){
			$this->db->where("TAHUN",$tahun);	
		}
		$this->db->order_by('TAHUN',"ASC");
		return parent::find_all();
	}
	public function group_by_tahun($jenis = "",$tahun = ""){		
		 

		if(empty($this->selects))
		{
			$this->select('KETERANGAN,JUMLAH');
		}
		if($jenis != ""){
			$this->db->where("JENIS",$jenis);	
		}
		if($tahun != ""){
			$this->db->where("TAHUN",$tahun);	
		}
		$this->db->order_by('ID',"ASC");
		return parent::find_all();
	}
	public function jenis_by_bulan($jenis = "",$tahun = ""){		
		 

		if(empty($this->selects))
		{
			$this->select('BULAN,KETERANGAN,JUMLAH');
		}
		if($jenis != ""){
			$this->db->where("JENIS",$jenis);	
		}
		if($tahun != ""){
			$this->db->where("TAHUN",$tahun);	
		}
		$this->db->order_by('ID',"ASC");
		return parent::find_all();
	}
}
