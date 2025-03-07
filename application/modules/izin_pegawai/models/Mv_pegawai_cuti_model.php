<?php defined('BASEPATH') || exit('No direct script access allowed');

class Mv_pegawai_cuti_model extends BF_Model
{
    protected $table_name	= 'mv_pegawai_cuti';
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
			'field' => 'NIP_PNS',
			'label' => 'lang:izin_pegawai_field_NIP_PNS',
			'rules' => 'required|max_length[18]',
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
    public function find_all()
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.ID,
				ID_PEGAWAI_ATASAN,
				NIP_BARU,
				PNS_ID,
				GELAR_DEPAN,
				NAMA,
				GELAR_BELAKANG,
				NAMA_GOLONGAN,
				NAMA_JABATAN,
				NAMA_ATASAN,
				NAMA_PPK,
				KETERANGAN_TAMBAHAN,
				NAMA_UNOR_ESELON_2,
				NAMA_UNOR_ESELON_3,
				NAMA_UNOR_ESELON_1,
				NAMA_UNOR_FULL');
		}
		return parent::find_all();
	}
	public function count_all()
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.ID,
				NIP_BARU,
				PNS_ID,
				GELAR_DEPAN,
				NAMA,
				GELAR_BELAKANG,
				NAMA_GOLONGAN,
				NAMA_JABATAN,
				NAMA_ATASAN,
				NAMA_PPK,
				NAMA_UNOR_ESELON_2,
				NAMA_UNOR_ESELON_3,
				NAMA_UNOR_ESELON_1,
				NAMA_UNOR_FULL');
		}
		return parent::count_all();
	}
}