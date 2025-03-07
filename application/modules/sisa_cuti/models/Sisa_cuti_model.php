<?php defined('BASEPATH') || exit('No direct script access allowed');

class Sisa_cuti_model extends BF_Model
{
    protected $table_name	= 'sisa_cuti';
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
			'field' => 'PNS_NIP',
			'label' => 'lang:sisa_cuti_field_PNS_NIP',
			'rules' => 'required|max_length[18]',
		),
		array(
			'field' => 'TAHUN',
			'label' => 'lang:sisa_cuti_field_TAHUN',
			'rules' => 'max_length[4]',
		),
		array(
			'field' => 'SISA',
			'label' => 'lang:sisa_cuti_field_SISA',
			'rules' => '',
		),
		array(
			'field' => 'SISA_N',
			'label' => 'SISA TAHUN INI',
			'rules' => '',
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
    public function find_all($satker_id = "")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.ID,PNS_NIP,
				pegawai.NAMA,SISA_N,SISA_N_1,SISA_N_2,SISA,SUDAH_DIAMBIL,TAHUN');
		}
		if($satker_id != "")
			$this->sisa_cuti_model->where("UNOR_INDUK_ID",$satker_id);
		$this->sisa_cuti_model->join('pegawai', 'pegawai.NIP_BARU = sisa_cuti.PNS_NIP', 'left');
		$this->sisa_cuti_model->order_by("TAHUN","desc");
		$this->sisa_cuti_model->order_by("pegawai.NAMA","desc");
		$this->sisa_cuti_model->order_by("ID","desc");
		return parent::find_all();
	}
	public function count_all($satker_id = "")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.ID,PNS_NIP,NAMA,SISA_N,SISA_N_1,SISA_N_2,SISA,SUDAH_DIAMBIL,TAHUN');
		}
		if($satker_id != "")
			$this->sisa_cuti_model->where("UNOR_INDUK_ID",$satker_id);
		$this->sisa_cuti_model->join('pegawai', 'pegawai.NIP_BARU = sisa_cuti.PNS_NIP', 'left');
		
		return parent::count_all();
	}
}