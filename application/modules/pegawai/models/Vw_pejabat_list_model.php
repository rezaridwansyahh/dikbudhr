<?php defined('BASEPATH') || exit('No direct script access allowed');

class Vw_pejabat_list_model extends BF_Model
{
    protected $table_name	= 'vw_unit_list_pejabat';
	protected $key			= 'ID';
	protected $date_format	= 'datetime';

	protected $log_user 	= false;
	protected $set_created	= false;
	protected $set_modified = false;
	protected $soft_deletes	= true;


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
			'field' => 'NAMA_UNOR',
			'label' => 'NAMA UNOR',
			'rules' => 'required',
		),
		//ADD BY BANA
		array(
			'field' => 'KODE_INTERNAL',
			'label' => 'KODE INTERNAL',
			//'rules' => 'required',
			'rules' => 'required',
		),
		array(
			'field' => 'ID',
			'label' => ' ID',
			//'rules' => 'required',
			'rules' => 'required',
		),
		array(
			'field' => 'EXPIRED_DATE',
			'label' => ' EXPIRED_DATE',
			//'rules' => 'required',
		),
		//END
	);
	protected $insert_validation_rules  = array();
	protected $skip_validation 			= true;

    /**
     * Constructor
     *
     * @return void
     */
    
    public function find_all()
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*');
		}
		return parent::find_all();
	}
	public function get_pejabat($unor_induk = "")
	{

		if (empty($this->selects)) {
			$this->select('vw_unit_list_pejabat."ID","NAMA_UNOR",
				"ESELON_ID",
				"NAMA_JABATAN",
				p.NIP_BARU as NIP_PEJABAT,
				p."GELAR_DEPAN",
				"NAMA" AS NAMA_PEJABAT,
				p."GELAR_BELAKANG",
				TRIM("NOMOR_HP") "NOMOR_HP",
				TRIM("EMAIL") "EMAIL",
				TRIM("ALAMAT") "ALAMAT"
				');
		}
		if ($unor_induk != "" and $unor_induk != "A8ACA7397AEB3912E040640A040269BB") {
			$this->db->group_start();
			$this->db->where("vw_unit_list_pejabat.ID", $unor_induk);
			$this->db->or_where("ESELON_1", $unor_induk);
			$this->db->or_where("ESELON_2", $unor_induk);
			$this->db->or_where("ESELON_3", $unor_induk);
			$this->db->or_where("ESELON_4", $unor_induk);
			$this->db->or_where("UNOR_INDUK", $unor_induk);
			$this->db->or_where("DIATASAN_ID", $unor_induk);
			$this->db->group_end();
		}
		if ($unor_induk != "" and $unor_induk != "A8ACA7397AEB3912E040640A040269BB") {
			// $this->unitkerja_model->or_where('("UNOR_INDUK" LIKE \'' . strtoupper($unor_induk) . '%\' or vw_unit_list_pejabat."ID" LIKE \'' . strtoupper($unor_induk) . '%\')');
		}
		$this->db->where("EXPIRED_DATE IS NULL");
		$this->db->join("pegawai p", "vw_unit_list_pejabat.PEMIMPIN_PNS_ID=p.PNS_ID", "LEFT");
		$this->db->order_by("KODE_INTERNAL", "ASC");
		return parent::find_all();
	}
	public function count_pejabat($unor_induk = "")
	{

		if (empty($this->selects)) {
			$this->select('"KODE_INTERNAL",vw_unit_list_pejabat."ID","NAMA_UNOR","ESELON_ID","NAMA_JABATAN","GELAR_DEPAN","NAMA","GELAR_BELAKANG",NIP_BARU');
		}
		if ($unor_induk != '' and $unor_induk != "A8ACA7397AEB3912E040640A040269BB") {
			$this->db->group_start();
			$this->db->where("vw_unit_list_pejabat.ID", $unor_induk);
			$this->db->or_where("ESELON_1", $unor_induk);
			$this->db->or_where("ESELON_2", $unor_induk);
			$this->db->or_where("ESELON_3", $unor_induk);
			$this->db->or_where("ESELON_4", $unor_induk);
			$this->db->or_where("UNOR_INDUK", $unor_induk);
			$this->db->or_where("DIATASAN_ID", $unor_induk);
			$this->db->group_end();
		}
		if ($unor_induk != "" and $unor_induk != "A8ACA7397AEB3912E040640A040269BB") {
			// $this->unitkerja_model->where('("UNOR_INDUK" LIKE \'' . strtoupper($unor_induk) . '%\' or vw_unit_list_pejabat."ID" LIKE \'' . strtoupper($unor_induk) . '%\')');
		}
		$this->db->where("EXPIRED_DATE IS NULL");
		$this->db->join("pegawai p", "vw_unit_list_pejabat.PEMIMPIN_PNS_ID=p.PNS_ID", "LEFT");
		return parent::count_all();
	}
	 
}