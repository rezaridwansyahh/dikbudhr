<?php defined('BASEPATH') || exit('No direct script access allowed');

class Layanan_kpo_model extends BF_Model
{
    protected $table_name	= 'perkiraan_kpo';
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
		
	);
	protected $insert_validation_rules  = array(
		
	);
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
	
	public function get_active(){
		$this->db->join('layanan l','l.id = '.$this->table_name.".layanan_id");
		$this->db->where('l.active',true);
		return $this->find_all();
	}
	public function find_services()
    {
		$this->db->join("pegawai p",$this->table_name.".nip = p.NIP_BARU","left");	
		$this->db->join('layanan l','l.id = '.$this->table_name.".layanan_id");
		$this->db->where('l.active',true);
		$this->db->join("vw_unit_list as vw","p.\"UNOR_ID\"=vw.\"ID\" ", 'left',false);
		$this->db->join("unitkerja vw_satker","vw_satker.\"ID\"=vw.\"UNOR_INDUK\" ", 'left',false);
		if(empty($this->selects))
		{
			$this->select('p."NIP_BARU",p."NAMA",
			(
				select "NAMA_JENIS_HUKUMAN" from rwt_hukdis  where p."NIP_BARU" = "PNS_NIP" and "ID_JENIS_HUKUMAN" = \'07\'
			) as rwt_hukdis_kpo,
			(
				select "NILAI_PPK" from rwt_prestasi_kerja  where p."NIP_BARU" = "PNS_NIP" order by "TAHUN" desc limit 1
			) as last_ppk,
			'.$this->table_name.'.id as usulan_id,'.$this->table_name.'.no_surat_pengantar as no_surat_pengantar,no_surat_pengantar_es1 as no_surat_pengantar_sestama,l.name as layanan_name,vw_satker."NAMA_UNOR" AS nama_satker,vw."NAMA_UNOR_FULL",'.$this->table_name.".status".','.$this->table_name.".id",false);
		}
		$this->db->order_by("p.NAMA","ASC");
        return parent::find_all();
	}
	public function find_all()
    {
		$this->db->join("pegawai p",$this->table_name.".nip = p.NIP_BARU","left");	
		$this->db->join('layanan l','l.id = '.$this->table_name.".layanan_id");
		$this->db->where('l.active',true);
		$this->db->join("vw_unit_list as vw","p.\"UNOR_ID\"=vw.\"ID\" ", 'left',false);
		$this->db->join("unitkerja vw_satker","vw_satker.\"ID\"=vw.\"UNOR_INDUK\" ", 'left',false);
		if(empty($this->selects))
		{
			$this->select('p.*,
			(
				select "NAMA_JENIS_HUKUMAN" from rwt_hukdis  where p."NIP_BARU" = "PNS_NIP" and "ID_JENIS_HUKUMAN" = \'07\'
			) as rwt_hukdis_kpo,
			(
				select "NILAI_PPK" from rwt_prestasi_kerja  where p."NIP_BARU" = "PNS_NIP" order by "TAHUN" desc limit 1
			) as last_ppk,
			'.$this->table_name.'.id as usulan_id,'.$this->table_name.'.no_surat_pengantar as no_surat_pengantar,l.name as layanan_name,vw_satker."NAMA_UNOR" AS nama_satker,vw."NAMA_UNOR_FULL",'.$this->table_name.".status".','.$this->table_name.".id",false);
		}
		$this->db->order_by("p.NAMA","ASC");
        return parent::find_all();
	}
	public function usulan_is_exist($PNS_ID,$layanan_id){
		$this->db->join("pegawai p",$this->table_name.".nip = p.NIP_BARU","left");
		$this->db->where("p.PNS_ID",$PNS_ID);
		$this->db->where('layanan_id',$layanan_id);
		return $this->db->get($this->table_name)->first_row();
	}
	public function find($id)
    {
		$this->db->join("pegawai p",$this->table_name.".nip = p.NIP_BARU","left");
		$this->db->join("vw_unit_list as vw","p.\"UNOR_ID\"=vw.\"ID\" ", 'left',false);
		$this->db->select('p.*,vw."NAMA_UNOR_FULL",'.$this->table_name.".*",false);
		
        return parent::find($id);
	}
	public function count_all(){
		$this->db->join("pegawai p",$this->table_name.".nip = p.NIP_BARU","left");
		$this->db->join('layanan l','l.id = '.$this->table_name.".layanan_id");
		$this->db->where('l.active',true);
		$this->db->join("vw_unit_list as vw","p.\"UNOR_ID\"=vw.\"ID\" ", 'left',false);
		$this->db->select('p.*,vw."NAMA_UNOR_FULL",'.$this->table_name.".status".','.$this->table_name.".id",false);
		//$this->db->order_by("p.NAMA","ASC");
        return parent::count_all();
	}
}