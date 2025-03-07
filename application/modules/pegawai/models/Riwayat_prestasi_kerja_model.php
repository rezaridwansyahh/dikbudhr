<?php defined('BASEPATH') || exit('No direct script access allowed');

class Riwayat_prestasi_kerja_model extends BF_Model
{
    protected $table_name	= 'rwt_prestasi_kerja';
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
			'field' => 'JABATAN_TIPE',
			'label' => 'TIPE JABATAN',
			'rules' => 'numeric|required',
		),
		array(
			'field' => 'JABATAN_NAMA',
			'label' => 'NAMA JABATAN',
			'rules' => 'required',
		),
		array(
			'field' => 'ATASAN_LANGSUNG_PNS_NIP',
			'label' => 'NIP ATASAN LANGSUNG',
			'rules' => 'required',
		),
		array(
			'field' => 'ATASAN_LANGSUNG_PNS_NAMA',
			'label' => 'NAMA ATASAN LANGSUNG',
			'rules' => 'required',
		),
		array(
			'field' => 'ATASAN_LANGSUNG_PNS_JABATAN',
			'label' => 'JABATAN ATASAN LANGSUNG',
			'rules' => 'required',
		),
		array(
			'field' => 'ATASAN_ATASAN_LANGSUNG_PNS_NIP',
			'label' => 'NIP ATASAN ATASAN LANGSUNG',
			'rules' => 'required',
		),
		array(
			'field' => 'ATASAN_ATASAN_LANGSUNG_PNS_NAMA',
			'label' => 'NAMA ATASAN ATASAN LANGSUNG',
			'rules' => 'required',
		),
		array(
			'field' => 'ATASAN_ATASAN_LANGSUNG_PNS_JABATAN',
			'label' => 'JABATAN ATASAN ATASAN LANGSUNG',
			'rules' => 'required',
		),
		array(
			'field' => 'NILAI_SKP',
			'label' => 'NILAI SKP',
			'rules' => 'numeric|required',
		),
		array(
			'field' => 'TAHUN',
			'label' => 'TAHUN SKP',
			'rules' => 'integer|required|greater_than_equal_to[2014]|less_than_equal_to[2300]',
		),
		array(
			'field' => 'PERILAKU_DISIPLIN',
			'label' => 'PERILAKU DISIPLIN',
			'rules' => 'numeric|required',
		),
		array(
			'field' => 'PERILAKU_INTEGRITAS',
			'label' => 'PERILAKU INTEGRITAS',
			'rules' => 'numeric|required',
		),
		array(
			'field' => 'PERILAKU_KEPEMIMPINAN',
			'label' => 'PERILAKU KEPEMIMPINAN',
			'rules' => 'numeric|required',
		),
		array(
			'field' => 'PERILAKU_KERJASAMA',
			'label' => 'PERILAKU KERJASAMA',
			'rules' => 'numeric|required',
		),
		array(
			'field' => 'PERILAKU_KOMITMEN',
			'label' => 'PERILAKU KOMITMEN',
			'rules' => 'numeric|required',
		),
		array(
			'field' => 'PERILAKU_ORIENTASI_PELAYANAN',
			'label' => 'PERILAKU ORIENTASI PELAYANAN',
			'rules' => 'numeric|required',
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
    public function find_all($PNS_ID ="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*');
		}
		if($PNS_ID!=""){
			$this->db->where('PNS_ID',$PNS_ID);
		}
		$this->db->order_by('TAHUN',"ASC");
		return parent::find_all();
	}
}