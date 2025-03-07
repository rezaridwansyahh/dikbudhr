<?php defined('BASEPATH') || exit('No direct script access allowed');

class Baperjakat_model extends BF_Model
{
    protected $table_name	= 'baperjakat';
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
			'field' => 'TANGGAL',
			'label' => 'lang:baperjakat_field_TANGGAL',
			'rules' => 'required',
		),
		array(
			'field' => 'KETERANGAN',
			'label' => 'lang:baperjakat_field_KETERANGAN',
			'rules' => 'max_length[50]',
		),
		array(
			'field' => 'TANGGAL_PENETAPAN',
			'label' => 'lang:baperjakat_field_TANGGAL_PENETAPAN',
			'rules' => '',
		),
		array(
			'field' => 'NO_SK_PENETAPAN',
			'label' => 'lang:baperjakat_field_NO_SK_PENETAPAN',
			'rules' => 'max_length[20]',
		),
		array(
			'field' => 'STATUS_AKTIF',
			'label' => 'lang:baperjakat_field_STATUS_AKTIF',
			'rules' => 'max_length[32]',
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
    public function find_aktif()
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*');
		}
		return parent::find_by("STATUS_AKTIF","1");
	}
	public function find_all()
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*,
				(select count("ID") from "kandidat_baperjakat" where "STATUS" = \'1\' and "ID_PERIODE" = baperjakat."ID") AS jumlah,
				(select count("ID") from "kandidat_baperjakat" where "STATUS_MENTERI" = \'1\' and "ID_PERIODE" = baperjakat."ID") AS jumlah_ditetapkan'

			);
		}
		
		return parent::find_all();
	}
	public function count_all()
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*');
		}
		return parent::count_all();
	}
	public function find_terpilih($periode){
		
		 
		$this->db->select('kandidat_baperjakat.*,pegawai."NAMA",pegawai."PHOTO",pegawai."NIP_BARU",vw."NAMA_UNOR_FULL",
					pegawai."ID" AS id_pegawai ,golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1"',false);
		
		$this->db->join('kandidat_baperjakat', 'baperjakat.ID = kandidat_baperjakat.ID_PERIODE', 'INNER');
		$this->db->join('pegawai', 'pegawai.NIP_BARU = kandidat_baperjakat.NIP', 'inner');
		$this->db->join("vw_unit_list as vw","kandidat_baperjakat.\"UNOR_ID\"=vw.\"ID\"", 'left',false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		if($periode != ""){
			$this->db->where("ID_PERIODE",$periode);	
		}
		$this->db->where("STATUS","1");
		$this->db->order_by("NAMA","ASC");
		return parent::find_all();
	}
	public function count_terpilih($periode){
		
		 
		$this->db->select('pegawai.*,vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1",kandidat_baperjakat."NAMA_JABATAN"',false);
		
		$this->db->join('kandidat_baperjakat', 'baperjakat.ID = kandidat_baperjakat.ID_PERIODE', 'INNER');
		$this->db->join('pegawai', 'pegawai.NIP_BARU = kandidat_baperjakat.NIP', 'inner');
		$this->db->join("vw_unit_list as vw","kandidat_baperjakat.\"UNOR_ID\"=vw.\"ID\"", 'left',false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		if($periode != ""){
			$this->db->where("ID_PERIODE",$periode);	
		}
		$this->db->where("STATUS","1");
		return parent::count_all();
	}
}