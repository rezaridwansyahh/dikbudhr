<?php defined('BASEPATH') || exit('No direct script access allowed');

class Sk_ds_korektor_model extends BF_Model
{
    protected $table_name	= 'tbl_file_ds_corrector';
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
	public function find_all_file_nip($NIP = "",$id_file)
	{

		$this->db->select('tbl_file_ds.*,tbl_file_ds_corrector.id as id_table,
			tbl_file_ds_corrector.is_corrected as status_koreksi,
			tbl_file_ds_corrector.korektor_ke,
			tbl_file_ds_corrector.is_returned as status_kembali');
		
		$this->db->join('tbl_file_ds', 'tbl_file_ds_corrector.id_file = tbl_file_ds.id_file', 'inner');
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join('pegawai P', 'P.PNS_ID = tbl_file_ds_corrector.id_pegawai_korektor', 'inner');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		 
		if($NIP != ""){
			$this->db->where("P.NIP_BARU",$NIP);
		}
		//if($id_file != ""){
			$this->db->where("tbl_file_ds.id_file",$id_file);
		//}
		return parent::find_all();
	}
	public function find_all_korektor($id_file = "")
	{

		$this->db->select('*,NAMA,NIP_BARU');
		$this->db->join('pegawai P', 'P.PNS_ID = tbl_file_ds_corrector.id_pegawai_korektor', 'inner');
		 
		//if($id_file != ""){
			$this->db->where("id_file",$id_file);
		//}
			$this->db->order_by("korektor_ke","asc");
		return parent::find_all();
	}
}