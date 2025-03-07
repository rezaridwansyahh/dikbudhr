<?php defined('BASEPATH') || exit('No direct script access allowed');

class Riwayat_cltn_model extends BF_Model
{
    protected $table_name	= 'rwt_cltn';
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
			'field' => 'pns_nip',
			'label' => 'NIP',
			'rules' => 'max_length[18]|required',
		),
		array(
			'field' => 'nomor_sk',
			'label' => 'NOMOR SK',
			'rules' => 'required',
		),
		array(
			'field' => 'tanggal_awal',
			'label' => 'TANGGAL AWAL',
			'rules' => 'required',
		)
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
    public function find_all($pns_id ="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*');
		}
		if($pns_id!=""){
			$this->db->where('pns_id',$pns_id);
		}		
		$this->db->order_by('tanggal_awal','ASC');
		return parent::find_all();
	}
	public function count_all($pns_id ="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*');
		}
		if($pns_id!=""){
			$this->db->where('pns_id',$pns_id);
		}
		$this->db->join('pegawai', 'pegawai.NIP_BARU = rwt_cltn.pns_nip', 'inner');
		return parent::count_all();
	}
	public function is_exist_cltn($no_bkn ="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*');
		}
		$this->db->where('id_bkn',$no_bkn);
		return parent::count_all()>0;
	}
	public function first_row(){
		return $this->db->get($this->db->schema.".".$this->table_name)->first_row();
	}
	public function find_detil()
	{
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*, 
			pegawai.NAMA, pegawai.NIP_BARU, pegawai.username_intra, pegawai.ref, unitkerja.NAMA_UNOR');
		}
		$this->db->join('pegawai', 'pegawai.NIP_BARU = rwt_cltn.pns_nip', 'inner');
		$this->db->join('unitkerja', 'unitkerja.ID = pegawai.UNOR_INDUK_ID', 'left');
		$this->db->order_by('tanggal_awal','ASC');
		return parent::find_all();
	}
}