<?php defined('BASEPATH') || exit('No direct script access allowed');

class Ds_riwayat_model extends BF_Model
{
    protected $table_name	= 'tbl_file_ds_riwayat';
	protected $key			= 'id_riwayat';
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
			'field' => 'id_pemroses',
			'label' => 'lang:ds_riwayat_field_id_pemroses',
			'rules' => 'max_length[255]',
		),
		array(
			'field' => 'tindakan',
			'label' => 'lang:ds_riwayat_field_tindakan',
			'rules' => '',
		),
		array(
			'field' => 'catatan_tindakan',
			'label' => 'lang:ds_riwayat_field_catatan_tindakan',
			'rules' => '',
		),
		array(
			'field' => 'waktu_tindakan',
			'label' => 'lang:ds_riwayat_field_waktu_tindakan',
			'rules' => '',
		),
		array(
			'field' => 'akses_pengguna',
			'label' => 'lang:ds_riwayat_field_akses_pengguna',
			'rules' => 'max_length[200]',
		),
		array(
			'field' => 'id_riwayat',
			'label' => 'lang:ds_riwayat_field_id_riwayat',
			'rules' => 'max_length[64]',
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
}