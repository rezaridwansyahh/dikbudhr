<?php defined('BASEPATH') || exit('No direct script access allowed');

class File_ttd_model extends BF_Model
{
    protected $table_name	= 'tbl_file_ttd';
	protected $key			= 'id_file';
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
			'field' => 'waktu_buat',
			'label' => 'lang:sk_ds_field_waktu_buat',
			'rules' => '',
		),
		array(
			'field' => 'kategori',
			'label' => 'lang:sk_ds_field_kategori',
			'rules' => 'max_length[100]',
		),
		array(
			'field' => 'teks_base64',
			'label' => 'lang:sk_ds_field_teks_base64',
			'rules' => '',
		),
		array(
			'field' => 'id_pegawai_ttd',
			'label' => 'lang:sk_ds_field_id_pegawai_ttd',
			'rules' => 'max_length[255]',
		),
		array(
			'field' => 'is_signed',
			'label' => 'lang:sk_ds_field_is_signed',
			'rules' => 'max_length[16]',
		),
		array(
			'field' => 'nip_sk',
			'label' => 'lang:sk_ds_field_nip_sk',
			'rules' => 'max_length[50]',
		),
		array(
			'field' => 'nomor_sk',
			'label' => 'lang:sk_ds_field_nomor_sk',
			'rules' => 'max_length[50]',
		),
		array(
			'field' => 'tgl_sk',
			'label' => 'lang:sk_ds_field_tgl_sk',
			'rules' => '',
		),
		array(
			'field' => 'tmt_sk',
			'label' => 'lang:sk_ds_field_tmt_sk',
			'rules' => '',
		),
		array(
			'field' => 'lokasi_file',
			'label' => 'lang:sk_ds_field_lokasi_file',
			'rules' => '',
		),
		array(
			'field' => 'is_corrected',
			'label' => 'lang:sk_ds_field_is_corrected',
			'rules' => 'max_length[16]',
		),
		array(
			'field' => 'catatan',
			'label' => 'lang:sk_ds_field_catatan',
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
    public function find_all($nip = "")
	{
 
		$this->db->select('id_pns_bkn,nip,base64ttd', false);
		if($nip != ""){
			$this->db->where("nip",$nip);
		}
		return parent::find_all();
	}
	 }