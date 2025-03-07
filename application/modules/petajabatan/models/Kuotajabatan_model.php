<?php defined('BASEPATH') || exit('No direct script access allowed');

class Kuotajabatan_model extends BF_Model
{
    protected $table_name	= 'kuota_jabatan';
	protected $key			= 'ID';
	protected $date_format	= 'datetime';

	protected $log_user 	= false;
	protected $set_created	= false;
	protected $set_modified = false;
	protected $soft_deletes	= false;

    protected $deleted_field     = 'deleted';

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
			'field' => 'KODE_UNIT_KERJA',
			'label' => 'Unit Kerja',
			'rules' => 'required',
		),
		array(
			'field' => 'ID_JABATAN',
			'label' => 'Jabatan',
			'rules' => 'required',
		),
		array(
			'field' => 'JUMLAH_PEMANGKU_JABATAN',
			'label' => 'Kuota Jabatn',
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
    public function find_all($unitkerja ="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.KODE_UNIT_KERJA,kuota_jabatan.ID,jabatan.KODE_JABATAN,JUMLAH_PEMANGKU_JABATAN,jabatan.NAMA_JABATAN,KELAS,vw_unit_list.NAMA_UNOR,NAMA_UNOR_FULL,PERATURAN');
		}
		if($unitkerja != ""){
			$this->db->where('("ESELON_1" = \''.$unitkerja.'\' OR "ESELON_2" = \''.$unitkerja.'\' OR "ESELON_3" = \''.$unitkerja.'\' OR "ESELON_4" = \''.$unitkerja.'\')');
		}
		// if(trim($this->settings_lib->item('peta.permen'))!= "")
		// 	$this->db->where("PERATURAN",trim($this->settings_lib->item('peta.permen')));
		$this->db->join('jabatan', 'kuota_jabatan.ID_JABATAN = jabatan.KODE_JABATAN', 'left');
		$this->db->join('vw_unit_list', 'vw_unit_list.ID = kuota_jabatan.KODE_UNIT_KERJA', 'left');
		$this->db->order_by("jabatan.JENIS_JABATAN","ASC");
		return parent::find_all();
	}
	public function find_all_satker($unitkerja ="")
	{
		
		if(empty($this->selects))
		{
			$this->select('unitkerja.ID,vw_unit_list.UNOR_INDUK,unitkerja.NAMA_UNOR,COUNT(*) as jumlah_jabatan,sum("JUMLAH_PEMANGKU_JABATAN") as jml_kuota');
		}
		if($unitkerja != ""){
			$this->db->where('("ESELON_1" = \''.$unitkerja.'\' OR "ESELON_2" = \''.$unitkerja.'\' OR "ESELON_3" = \''.$unitkerja.'\' OR "ESELON_4" = \''.$unitkerja.'\')');
		}
		
		$this->db->join('jabatan', 'kuota_jabatan.ID_JABATAN = jabatan.KODE_JABATAN', 'left');
		$this->db->join('vw_unit_list', 'vw_unit_list.ID = kuota_jabatan.KODE_UNIT_KERJA', 'left');
		$this->db->join('unitkerja', 'vw_unit_list.UNOR_INDUK = unitkerja.ID', 'left');
		$this->db->group_by("unitkerja.ID");
		$this->db->group_by("unitkerja.NAMA_UNOR");
		$this->db->group_by("vw_unit_list.UNOR_INDUK");
		return parent::find_all();
	}
	public function count_all($unitkerja ="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.KODE_UNIT_KERJA,kuota_jabatan.ID,jabatan.KODE_JABATAN,JUMLAH_PEMANGKU_JABATAN,jabatan.NAMA_JABATAN,KELAS,unitkerja.NAMA_UNOR');
		}
		if($unitkerja != ""){
			$this->db->where('("ESELON_1" = \''.$unitkerja.'\' OR "ESELON_2" = \''.$unitkerja.'\' OR "ESELON_3" = \''.$unitkerja.'\' OR "ESELON_4" = \''.$unitkerja.'\')');
		}
		// if(trim($this->settings_lib->item('peta.permen'))!= "")
		// 	$this->db->where("PERATURAN",trim($this->settings_lib->item('peta.permen')));
		$this->db->join('jabatan', 'kuota_jabatan.ID_JABATAN = jabatan.KODE_JABATAN', 'left');
		$this->db->join('vw_unit_list', 'vw_unit_list.ID = kuota_jabatan.KODE_UNIT_KERJA', 'left');
		return parent::count_all();
	}
	public function count_all_satker($unitkerja ="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.KODE_UNIT_KERJA,kuota_jabatan.ID,jabatan.KODE_JABATAN,JUMLAH_PEMANGKU_JABATAN,jabatan.NAMA_JABATAN,KELAS,unitkerja.NAMA_UNOR');
		}
		if($unitkerja != ""){
			$this->db->where('("ESELON_1" = \''.$unitkerja.'\' OR "ESELON_2" = \''.$unitkerja.'\' OR "ESELON_3" = \''.$unitkerja.'\' OR "ESELON_4" = \''.$unitkerja.'\')');
		}
		if(trim($this->settings_lib->item('peta.permen'))!= "")
			$this->db->where("PERATURAN",trim($this->settings_lib->item('peta.permen')));
		$this->db->join('jabatan', 'kuota_jabatan.ID_JABATAN = jabatan.KODE_JABATAN', 'left');
		$this->db->join('vw_unit_list', 'vw_unit_list.ID = kuota_jabatan.KODE_UNIT_KERJA', 'left');
		return parent::count_all();
	}
	public function find_det()
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*');
		}
		return parent::find_all();
	}
	public function find_by_jabatan($unitkerja ="",$jabatan = "")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.KODE_UNIT_KERJA,unitkerja.ID,jabatan.KODE_JABATAN,JUMLAH_PEMANGKU_JABATAN,jabatan.NAMA_JABATAN,KELAS,NAMA_UNOR');
		}
		if($unitkerja != ""){
			$this->db->where('("ESELON_1" = \''.$unitkerja.'\' OR "ESELON_2" = \''.$unitkerja.'\' OR "ESELON_3" = \''.$unitkerja.'\' OR "ESELON_4" = \''.$unitkerja.'\')');
		}
		$this->unitkerja_model->where("ID_JABATAN",TRIM($jabatan));
		$this->db->join('jabatan', 'kuota_jabatan.ID_JABATAN = jabatan.KODE_JABATAN', 'left');
		$this->db->join('unitkerja', 'unitkerja.KODE_INTERNAL = kuota_jabatan.KODE_UNIT_KERJA', 'left');
		$this->db->order_by("NAMA_JABATAN","ASC");
		return parent::find_all();
	}
	public function find_quota($unitkerja ="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.KODE_UNIT_KERJA,
				kuota_jabatan.ID,jabatan.KODE_JABATAN,
				JUMLAH_PEMANGKU_JABATAN,jabatan.NAMA_JABATAN,KELAS,NAMA_UNOR_FULL,
				vw_unit_list.NAMA_UNOR,
				(
					select count(*) from pegawai 
					LEFT JOIN "vw_unit_list" ON "vw_unit_list"."ID" = "pegawai"."UNOR_ID" 
					where "JABATAN_INSTANSI_REAL_ID" = jabatan."KODE_JABATAN"
					and vw_unit_list."ID" = kuota_jabatan."KODE_UNIT_KERJA"
				) AS "JML_RIIL"
				');
		}
		if($unitkerja != ""){
			$this->db->where('("ESELON_1" = \''.$unitkerja.'\' OR "ESELON_2" = \''.$unitkerja.'\' OR "ESELON_3" = \''.$unitkerja.'\' OR "ESELON_4" = \''.$unitkerja.'\')');
		}
		$this->db->join('jabatan', 'kuota_jabatan.ID_JABATAN = jabatan.KODE_JABATAN', 'left');
		$this->db->join('vw_unit_list', 'vw_unit_list.ID = kuota_jabatan.KODE_UNIT_KERJA', 'left');
		$this->db->order_by("jabatan.JENIS_JABATAN","ASC");
		return parent::find_all();
	}
	public function count_quota($unitkerja ="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.KODE_UNIT_KERJA,kuota_jabatan.ID,jabatan.KODE_JABATAN,JUMLAH_PEMANGKU_JABATAN,jabatan.NAMA_JABATAN,KELAS,unitkerja.NAMA_UNOR');
		}
		if($unitkerja != ""){
			$this->db->where('("ESELON_1" = \''.$unitkerja.'\' OR "ESELON_2" = \''.$unitkerja.'\' OR "ESELON_3" = \''.$unitkerja.'\' OR "ESELON_4" = \''.$unitkerja.'\')');
		}
		 
		$this->db->join('jabatan', 'kuota_jabatan.ID_JABATAN = jabatan.KODE_JABATAN', 'left');
		$this->db->join('vw_unit_list', 'vw_unit_list.KODE_INTERNAL = kuota_jabatan.KODE_UNIT_KERJA', 'left');
		return parent::count_all();
	}
}