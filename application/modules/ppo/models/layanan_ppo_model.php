<?php defined('BASEPATH') || exit('No direct script access allowed');

class Layanan_ppo_model extends BF_Model
{
    protected $table_name	= 'perkiraan_ppo';
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
	public function count_all_pensiun($unor_id='',$tahun_pensiun=''){
		$this->table_name = 'pegawai';
		if($unor_id!='' && false){
			$this->db->where("(unitkerja.ID = '".$unor_id."' or unitkerja.ESELON_1 = '".$unor_id."' or unitkerja.ESELON_2 = '".$unor_id."' or unitkerja.ESELON_3 = '".$unor_id."' or unitkerja.ESELON_4 = '".$unor_id."')");
		}
		$this->db->select('pegawai.*,unitkerja."NAMA_UNOR",EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) as umur,jabatan."NAMA_JABATAN",jabatan."PENSIUN"');
		$this->db->where('EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) > jabatan."PENSIUN"');
		if($tahun_pensiun !=''){
			$this->db->where('EXTRACT(YEAR FROM "TGL_LAHIR" ) + jabatan."PENSIUN" <= '.$tahun_pensiun);
		}
		$this->db->where("(status_pegawai != 3 or status_pegawai is null)");
		$this->db->where("(TMT_PENSIUN IS NULL)");

		$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\" ", 'left',false);
		$this->db->join("unitkerja","pegawai.UNOR_ID=unitkerja.ID", 'left');
		$this->db->join("hris.jabatan ","pegawai.JABATAN_INSTANSI_REAL_ID=jabatan.KODE_JABATAN", 'left');
		$this->db->join("pns_aktif pa","pegawai.ID=pa.ID","inner");
		return parent::count_all();
	}
	public function find_all_pensiun($unor_id='',$tahun_pensiun=''){
		$this->table_name = 'pegawai';
		if($unor_id!='' && false){
			$this->db->where("(unitkerja.ID = '".$unor_id."' or unitkerja.ESELON_1 = '".$unor_id."' or unitkerja.ESELON_2 = '".$unor_id."' or unitkerja.ESELON_3 = '".$unor_id."' or unitkerja.ESELON_4 = '".$unor_id."')");
		}
		$this->db->select('EXTRACT(YEAR FROM "TGL_LAHIR" ) as tahun_lahir,pegawai.*,unitkerja."NAMA_UNOR",EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) as umur,jabatan."NAMA_JABATAN",jabatan."PENSIUN",(EXTRACT(YEAR FROM "TGL_LAHIR" ) + jabatan."PENSIUN") AS tahun_pensiun');
		$this->db->where('EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) > jabatan."PENSIUN"');
		
		$this->db->select("perkiraan_ppo.nip as usulan_nip");
		if($tahun_pensiun !=''){
			$this->db->where('EXTRACT(YEAR FROM "TGL_LAHIR" ) + jabatan."PENSIUN" <= '.$tahun_pensiun);
		}
		$this->db->where("(status_pegawai != 3 or status_pegawai is null)");
		$this->db->where("(TMT_PENSIUN IS NULL)");

		$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\" ", 'left',false);
		$this->db->join("unitkerja","pegawai.UNOR_ID=unitkerja.ID", 'left');
		$this->db->join("perkiraan_ppo","pegawai.NIP_BARU = perkiraan_ppo.nip", 'left');
		$this->db->join("hris.jabatan ","pegawai.JABATAN_INSTANSI_REAL_ID=jabatan.KODE_JABATAN", 'left');
		$this->db->join("pns_aktif pa","pegawai.ID=pa.ID","inner");
		return parent::find_all();
	}
	public function find_all()
    {
		$this->db->join("pegawai p",$this->table_name.".nip = p.NIP_BARU","left");	
		$this->db->join('layanan l','l.id = '.$this->table_name.".layanan_id",'left');
		$this->db->where('l.active',true);
		$this->db->join("vw_unit_list as vw","p.\"UNOR_ID\"=vw.\"ID\" ", 'left',false);
		$this->db->join("unitkerja vw_satker","vw_satker.\"ID\"=vw.\"UNOR_INDUK\" ", 'left',false);
		if(empty($this->selects))
		{
			$this->select('p.*,
			(
				select "NAMA_JENIS_HUKUMAN" from rwt_hukdis  where p."NIP_BARU" = "PNS_NIP" and "ID_JENIS_HUKUMAN" = \'07\'
			) as rwt_hukdis_kpo,( 
				SELECT "PENSIUN" FROM rwt_jabatan 
				left join jabatan j on rwt_jabatan."ID_JABATAN_BKN" = j."KODE_BKN"
				WHERE "PNS_NIP" = '.$this->table_name.'.nip AND "TMT_JABATAN" IS NOT NULL ORDER BY "TMT_JABATAN" DESC LIMIT 1 ) as bup,
			(
				select "NILAI_PPK" from rwt_prestasi_kerja  where p."NIP_BARU" = "PNS_NIP" order by "TAHUN" desc limit 1
			) as last_ppk,
			'.$this->table_name.'.id as usulan_id,'.$this->table_name.'.no_surat_pengantar as no_surat_pengantar,l.name as layanan_name,vw_satker."NAMA_UNOR" AS nama_satker,alasan,vw."NAMA_UNOR_FULL",'.$this->table_name.".status".','.$this->table_name.".id",false);
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