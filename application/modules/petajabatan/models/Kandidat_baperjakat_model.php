<?php defined('BASEPATH') || exit('No direct script access allowed');

class Kandidat_baperjakat_model extends BF_Model
{
    protected $table_name	= 'kandidat_baperjakat';
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
			'field' => 'NIP',
			'label' => 'NIP',
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
    public function find_all($unitkerja ="",$tahun = "")
	{
		
		if(empty($this->selects))
		{
			$this->db->select('pegawai."NAMA",pegawai."ID", "NIP_BARU","GOL_ID","TK_PENDIDIKAN",kandidat_baperjakat."STATUS", kandidat_baperjakat."ID" as "KODE",kandidat_baperjakat."URUTAN_DEFAULT","URUTAN_UPDATE",pendidikan."NAMA" AS "NAMA_PENDIDIKAN",golongan."NAMA" AS "NAMA_GOLONGAN",(
				select jenis_hukuman."NAMA" from rwt_hukdis left join jenis_hukuman on(jenis_hukuman."ID" = rwt_hukdis."ID_JENIS_HUKUMAN")
				where pegawai."NIP_BARU" = "PNS_NIP" order by rwt_hukdis."ID" DESC LIMIT 1
			) as rwt_hukuman,(
				select "NILAI" from rwt_assesmen
				where pegawai."NIP_BARU" = "PNS_NIP" order by rwt_assesmen."TAHUN" DESC LIMIT 1
			) as rwt_assesment
			',false);
		}
		if($unitkerja != ""){
			$this->db->where('kandidat_baperjakat.UNOR_ID',$unitkerja);
		}
		if($tahun != ""){
			$this->db->where('TAHUN',$tahun);
		}
		
		$this->db->join("pegawai","pegawai.NIP_BARU = kandidat_baperjakat.NIP","inner");
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join("pns_aktif pa","pegawai.ID=pa.ID","LEFT");
		$this->db->join('pendidikan', 'pendidikan.ID = pegawai.PENDIDIKAN_ID', 'left');
		$this->db->where('pa."ID" is not null',NULL,FALSE);
		$this->db->where('pegawai."KEDUDUKAN_HUKUM_ID" <> \'99\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'66\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'52\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'20\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'04\' ');
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) ");

		return parent::find_all();
	}
	public function count_all($unitkerja ="",$tahun = "")
	{
		
		if(empty($this->selects))
		{
			$this->db->select('pegawai.*,kandidat_baperjakat."ID" as "KODE",kandidat_baperjakat."URUTAN_DEFAULT","URUTAN_UPDATE"',false);
		}
		if($unitkerja != ""){
			$this->db->where('kandidat_baperjakat.UNOR_ID',$unitkerja);
		}
		if($tahun != ""){
			$this->db->where('TAHUN',$tahun);
		}
		
		$this->db->join("pegawai","pegawai.NIP_BARU = kandidat_baperjakat.NIP","inner");
		$this->db->join("pns_aktif pa","pegawai.ID=pa.ID","LEFT");
		$this->db->where('pa."ID" is not null',NULL,FALSE);
		$this->db->where('pegawai."KEDUDUKAN_HUKUM_ID" <> \'99\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'66\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'52\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'20\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'04\' ');
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) ");

		return parent::count_all();
	}
	public function find_terpilih($unitkerja ="",$tahun = "")
	{
		
		if(empty($this->selects))
		{
			$this->db->select('pegawai."ID","NAMA",kandidat_baperjakat."ID" as "KODE",kandidat_baperjakat."URUTAN_DEFAULT","URUTAN_UPDATE"',false);
		}
		if($unitkerja != ""){
			$this->db->where('kandidat_baperjakat.UNOR_ID',$unitkerja);
		}
		if($tahun != ""){
			$this->db->where('TAHUN',$tahun);
		}
		$this->db->where('STATUS',1);
		$this->db->join("pegawai","pegawai.NIP_BARU = kandidat_baperjakat.NIP","inner");
		$this->db->join("pns_aktif pa","pegawai.ID=pa.ID","LEFT");
		$this->db->where('pa."ID" is not null',NULL,FALSE);
		$this->db->where('pegawai."KEDUDUKAN_HUKUM_ID" <> \'99\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'66\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'52\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'20\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'04\' ');
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) ");

		return parent::find_all();
	}
	public function count_terpilih($unitkerja ="",$tahun = "")
	{
		
		if(empty($this->selects))
		{
			$this->db->select('pegawai.*,kandidat_baperjakat."ID" as "KODE",kandidat_baperjakat."URUTAN_DEFAULT","URUTAN_UPDATE"',false);
		}
		if($unitkerja != ""){
			$this->db->where('kandidat_baperjakat.UNOR_ID',$unitkerja);
		}
		if($tahun != ""){
			$this->db->where('TAHUN',$tahun);
		}
		$this->db->where('STATUS',1);
		$this->db->join("pegawai","pegawai.NIP_BARU = kandidat_baperjakat.NIP","inner");
		$this->db->join("pns_aktif pa","pegawai.ID=pa.ID","LEFT");
		$this->db->where('pa."ID" is not null',NULL,FALSE);
		$this->db->where('pegawai."KEDUDUKAN_HUKUM_ID" <> \'99\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'66\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'52\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'20\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'04\' ');
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) ");

		return parent::count_all();
	}
	public function rekap_agama($id = "",$statuspelantikan = 1)
	{
		
		if(empty($this->selects))
		{
			$this->db->select('pegawai."AGAMA_ID",agama."NAMA",count("AGAMA_ID") AS jumlah');
		}
		$this->db->join("pegawai","pegawai.NIP_BARU = kandidat_baperjakat.NIP","inner");
		$this->db->join("agama","pegawai.AGAMA_ID = agama.ID","left");
		$this->db->where('ID_PERIODE',$id);
		$this->db->where('STATUS_MENTERI',1);
		if($statuspelantikan == 1){
			$this->db->where('TGL_PELANTIKAN IS NOT NULL');
		}
		$this->db->group_by('AGAMA_ID');
		$this->db->group_by('agama."NAMA"');
		return parent::find_all();
	}
	public function rekap_eselon($id = "",$statuspelantikan = 1)
	{
		
		if(empty($this->selects))
		{
			$this->db->select('vw."ESELON_ID",count(kandidat_baperjakat."ID") AS jumlah');
		}
		$this->db->join("vw_unit_list as vw","kandidat_baperjakat.\"UNOR_ID\"=vw.\"ID\"", 'left',false);
		$this->db->where('ID_PERIODE',$id);
		$this->db->where('STATUS_MENTERI',1);
		if($statuspelantikan == 1){
			$this->db->where('TGL_PELANTIKAN IS NOT NULL');
		}
		$this->db->group_by('ESELON_ID');
		return parent::find_all();
	}
	public function rekap_jk($id = "",$statuspelantikan = 1)
	{
		
		if(empty($this->selects))
		{
			$this->db->select('pegawai."JENIS_KELAMIN",count(kandidat_baperjakat."ID") AS jumlah');
		}
		$this->db->join("pegawai","pegawai.NIP_BARU = kandidat_baperjakat.NIP","inner");
		$this->db->where('ID_PERIODE',$id);
		$this->db->where('STATUS_MENTERI',1);
		if($statuspelantikan == 1){
			$this->db->where('TGL_PELANTIKAN IS NOT NULL');
		}
		$this->db->group_by('JENIS_KELAMIN');
		return parent::find_all();
	}
	public function rekap_tkpendidikan($id = "",$statuspelantikan = 1)
	{
		
		if(empty($this->selects))
		{
			$this->db->select('tkpendidikan."NAMA" as NAMA_PENDIDIKAN,count(kandidat_baperjakat."ID") AS jumlah');
		}
		$this->db->join("pegawai","pegawai.NIP_BARU = kandidat_baperjakat.NIP","inner");
		$this->db->join("hris.pendidikan ","pegawai.PENDIDIKAN_ID=pendidikan.ID", 'left');
		$this->db->join("hris.tkpendidikan ","pendidikan.TINGKAT_PENDIDIKAN_ID=tkpendidikan.ID", 'left');
		$this->db->where('ID_PERIODE',$id);
		$this->db->where('STATUS_MENTERI',1);
		if($statuspelantikan == 1){
			$this->db->where('TGL_PELANTIKAN IS NOT NULL');
		}
		$this->db->group_by('NAMA_PENDIDIKAN');
		return parent::find_all();
	}
	public function rekap_satker($id = "",$statuspelantikan = 1)
	{
		
		if(empty($this->selects))
		{
			$this->db->select('NAMA_UNOR_FULL,count(kandidat_baperjakat."ID") AS jumlah');
		}
		$this->db->join("unitkerja ","kandidat_baperjakat.UNOR_ID=unitkerja.ID", 'left');
		$this->db->join("vw_unit_list ","vw_unit_list.ID=unitkerja.UNOR_INDUK", 'left');

		$this->db->where('ID_PERIODE',$id);
		$this->db->where('STATUS_MENTERI',1);
		if($statuspelantikan == 1){
			$this->db->where('TGL_PELANTIKAN IS NOT NULL');
		}
		$this->db->group_by('NAMA_UNOR_FULL');
		return parent::find_all();
	}
	public function find_dilantik($tahun ="",$bulan = "")
	{
		
		if(empty($this->selects))
		{
			$this->db->select('pegawai."ID","NAMA",kandidat_baperjakat."ID" as "KODE",kandidat_baperjakat."URUTAN_DEFAULT","URUTAN_UPDATE",TGL_PELANTIKAN,
				vw_unit_list.ESELON_1,vw_unit_list.ESELON_2,kandidat_baperjakat.UNOR_ID,
				kandidat_baperjakat.NAMA_JABATAN,NAMA_UNOR_FULL');
		}
		$this->kandidat_baperjakat_model->where("EXTRACT(YEAR FROM \"TGL_PELANTIKAN\") = ".$tahun.""); 
        $this->kandidat_baperjakat_model->where("EXTRACT(MONTH FROM \"TGL_PELANTIKAN\") =".$bulan.""); 
		$this->db->where('TGL_PELANTIKAN IS NOT NULL');
		$this->db->where('STATUS_MENTERI',1);
		$this->db->join("pegawai","pegawai.NIP_BARU = kandidat_baperjakat.NIP","inner");
		$this->db->join("unitkerja","unitkerja.ID=kandidat_baperjakat.UNOR_ID","LEFT");
		$this->db->join("vw_unit_list ","vw_unit_list.ID=unitkerja.UNOR_INDUK", 'left');
		return parent::find_all();
	}
	public function find_detil($ID = "")
	{

		if (empty($this->selects)) {
			$this->select($this->table_name . '.*,NIP_BARU,pegawai."ID","NAMA",GELAR_DEPAN,GELAR_BELAKANG,
				jabatan.NAMA_JABATAN AS JABATAN,AGAMA_ID');
		}
		$this->db->join("pegawai","pegawai.NIP_BARU = kandidat_baperjakat.NIP","inner");
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		return parent::find($ID);
	}
}