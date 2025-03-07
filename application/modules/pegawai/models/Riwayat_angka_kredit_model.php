<?php defined('BASEPATH') || exit('No direct script access allowed');

class Riwayat_angka_kredit_model extends BF_Model
{
    protected $table_name	= 'rwt_angka_kredit';
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
			'label' => 'NIP',
			'rules' => 'max_length[18]|required',
		),
		array(
			'field' => 'no_sk',
			'label' => 'NOMOR SK',
			'rules' => 'required',
		),
		array(
			'field' => 'ak_utama_baru',
			'label' => 'AK Utama',
			'rules' => 'required',
		),
		array(
			'field' => 'ak_penunjang_baru',
			'label' => 'AK Penunjang',
			'rules' => 'required',
		),
		array(
			'field' => 'ak_baru_total',
			'label' => 'AK Total',
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
			$this->select($this->table_name .'.*, concat(thn_mulai,\'-\',bln_mulai,\'-01\') as tanggal_awal, concat(thn_selesai,\'-\',bln_selesai,\'-01\') as tanggal_akhir');
		}
		if($pns_id!=""){
			$this->db->where('pns_id',$pns_id);
		}
		$this->db->order_by('thn_selesai', 'ASC');
		$this->db->order_by('bln_selesai', 'ASC');
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
		return parent::count_all();
	}
	public function is_exist_angka_kredit($no_bkn ="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*');
		}
		$this->db->where('id_bkn',$no_bkn);
		return parent::count_all()>0;
	}
	public function first_row(){
		// return $this->db->get($this->db->schema.".".$this->table_name)->first_row();
		return $this->db
			->select($this->table_name .'.*, concat(thn_mulai,\'-\',lpad(bln_mulai::text,2,\'0\')) as tanggal_awal, concat(thn_selesai,\'-\',lpad(bln_selesai::text,2,\'0\')) as tanggal_akhir')
			->get($this->db->schema.".".$this->table_name)
			->first_row();
	}

	public function getUnsyncBkn($id_table = null)
	{
		if ($id_table){
			$this->db->where('id_tabel', $id_table);
		}
		$result = $this->db->from('hris.vw_unsync_ak')->get()->result();
		return $result;
	}
	public function countUnsyncBkn($id_table = null)
	{
		if ($id_table){
			$this->db->where('id_tabel', $id_table);
		}
		$this->db->select('id_tabel');
		$result = $this->db->from('hris.vw_unsync_ak')->get()->num_rows();
		return $result;
	}
}