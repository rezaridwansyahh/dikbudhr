<?php defined('BASEPATH') || exit('No direct script access allowed');

class Riwayat_kgb_model extends BF_Model
{
    protected $table_name	= 'rwt_kgb';
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
			'field' => 'PNS_ID',
			'label' => 'PNS ID',
			'rules' => 'max_length[32]|required',
		),
		array(
			'field' => 'last_education_date',
			'label' => 'TGL LULUS PENDIDIKAN',
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
	public function first_row(){
		return $this->db->get($this->db->schema.".".$this->table_name)->first_row();
	}
    public function find_all($PNS_NIP ="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*');
		}
		if($PNS_NIP!=""){
			$this->db->where('pegawai_nip',$PNS_NIP);
		}
		return parent::find_all();
	}
	public function count_all($PNS_NIP ="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*,g.NAMA AS golongan_name,g.NAMA2 AS golongan2_name');
		}
		if($PNS_NIP!=""){
			$this->db->where('pegawai_nip',$PNS_NIP);
		}
		$this->db->join("golongan g", "g.ID=rwt_kgb.n_golongan_id", "LEFT");
		return parent::count_all();
	}
	public function is_uniq($nip ="",$n_golongan_id = "",$n_masakerja_thn = "",$n_masakerja_bln = "")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*');
		}
		$this->db->where('pegawai_nip',$nip);
		$this->db->where('n_golongan_id',$n_golongan_id);
		$this->db->where('n_masakerja_thn',$n_masakerja_thn);
		$this->db->where('n_masakerja_bln',$n_masakerja_bln);
		return parent::count_all()>0;
	}
	public function find_ref($ref ="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*,g.NAMA2 AS golongan2_name');
		}
		$this->db->join("golongan g", "g.ID=rwt_kgb.n_golongan_id", "LEFT");
		return parent::find_by("ref",$ref);
	}
	public function getTemplate($id_module = 1){
		$data = $this->db->query("select * from mst_templates where id = ?",array($id_module))->first_row();
		return $data;
	}
	public function getWage($golongan,$working_periode){
		$data = $this->cache->get('wage');
		$adata = array();
		if ($data == null) {
			$rows = $this->db->query("select * from wage")->result();
			foreach ($rows as $row) {
				// print_r($row->GOLONGAN."_".$row->WORKING_PERIOD."<br>");
				$adata[$row->GOLONGAN."_".$row->WORKING_PERIOD] = $row;
			}
			$data = $adata;
			$this->cache->write($data, 'wage');
		}
		return isset($data[$golongan."_".$working_periode]) ? $data[$golongan."_".$working_periode] : null;
	}
	public function getWage2($golongan,$working_periode){
		$data = $this->cache->get('wage2');
		$adata = array();
		if ($data == null) {
			$rows = $this->db->query("select * from wage")->result();
			foreach ($rows as $row) {
				// print_r($row->GOLONGAN."_".$row->WORKING_PERIOD."<br>");
				$adata[$row->golongan_id."_".$row->WORKING_PERIOD] = $row;
			}
			$data = $adata;
			$this->cache->write($data, 'wage2');
		}
		return isset($data[$golongan."_".$working_periode]) ? $data[$golongan."_".$working_periode] : null;
	}
	public function getLastRiwayat($nip = "",$tanggal = ""){
		$data = $this->db->query("select * from mv_kgb_all  where pegawai_nip = ? and tmt_sk < ? order by tmt_sk desc NULLS LAST",array($nip,$tanggal))->first_row();
		return $data;
	}	
}