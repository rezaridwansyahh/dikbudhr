<?php defined('BASEPATH') || exit('No direct script access allowed');

class Rpt_golongan_bulan_model extends BF_Model
{
    protected $table_name	= 'rpt_golongan_bulan';
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
			'field' => 'GOL ID',
			'label' => 'GOL ID',
			'rules' => 'max_length[32]|required',
		),
		array(
			'field' => 'NIP_LAMA',
			'label' => 'lang:pegawai_field_NIP_LAMA',
			'rules' => 'max_length[9]',
		),
		 
	);
	protected $insert_validation_rules  = array();
	protected $skip_validation 			= true;

    
	public function find_first_row(){
		return $this->db->get($this->db->schema.".".$this->table_name)->first_row();
	}
	
	public function count_all_ppnpn($satker_id = ""){
		
		$where_clause = '';
		if($satker_id){
			$where_clause = 'AND (vw."ESELON_1" = \''.$satker_id.'\' OR vw."ESELON_2" = \''.$satker_id.'\' OR vw."ESELON_3" = \''.$satker_id.'\' OR vw."ESELON_4" = \''.$satker_id.'\')' ;
		}
		$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left',false);
		$this->db->join("pns_aktif pa","pegawai.ID=pa.ID","LEFT");
		$this->db->where('pa."ID" is not null',NULL,FALSE);
		$this->db->where('pegawai."KEDUDUKAN_HUKUM_ID" <> \'99\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'66\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'52\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'20\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'04\' ');
		
		$this->db->where("status_pegawai = 3 ".$where_clause);

		return parent::count_all();
	}
	public function count_list($satker_id,$strict_in_satker = false){
		$this->db->from('hris.pegawai pegawai');
		$where_clause = '';
		if($satker_id){
			if(is_array($satker_id) && sizeof($satker_id)>0){
				$where_clause = 'AND ( 1=2 ';
				foreach($satker_id as $u_id){
					$where_clause .= ' OR vw."ESELON_1" = \''.$u_id.'\' OR vw."ESELON_2" = \''.$u_id.'\' OR vw."ESELON_3" = \''.$u_id.'\' OR vw."ESELON_4" = \''.$u_id.'\' ' ;
				}
				$where_clause .= ')';
			}
			else $where_clause = 'AND (vw."ESELON_1" = \''.$satker_id.'\' OR vw."ESELON_2" = \''.$satker_id.'\' OR vw."ESELON_3" = \''.$satker_id.'\' OR vw."ESELON_4" = \''.$satker_id.'\')' ;
		}
		$this->db->select('pegawai.*,vw."NAMA_UNOR_FULL"',false);
		$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left',false);
		if($strict_in_satker){
			$this->db->where('vw."ID" is not null');
		}

		$this->db->where("(status_pegawai != 3 or status_pegawai is null)");

		$this->db->order_by("NAMA","ASC");
		//return parent::find_all();
		return $this->db->get()->num_rows();
	}
	public function find_all($satker_id,$strict_in_satker = false){
		
		$where_clause = '';
		if($satker_id){
			$where_clause = 'AND (vw."ESELON_1" = \''.$satker_id.'\' OR vw."ESELON_2" = \''.$satker_id.'\' OR vw."ESELON_3" = \''.$satker_id.'\' OR vw."ESELON_4" = \''.$satker_id.'\')' ;
		}
		$this->db->select('pegawai.*,vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1"',false);
		$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left',false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join("pns_aktif pa","pegawai.ID=pa.ID","LEFT");
		if($strict_in_satker){
			$this->db->where('vw."ID" is not null');
		}

		$this->db->where('pa."ID" is not null',NULL,FALSE);
		$this->db->where('pegawai."KEDUDUKAN_HUKUM_ID" <> \'99\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'66\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'52\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'20\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'04\' ');
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) ".$where_clause);
		$this->db->order_by("NAMA","ASC");
		return parent::find_all();
	}
	public function count_all($satker_id = "",$strict_in_satker = false){
		
		$where_clause = '';
		if($satker_id){
			$where_clause = 'AND (vw."ESELON_1" = \''.$satker_id.'\' OR vw."ESELON_2" = \''.$satker_id.'\' OR vw."ESELON_3" = \''.$satker_id.'\' OR vw."ESELON_4" = \''.$satker_id.'\')' ;
		}
		$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left',false);
		$this->db->join("pns_aktif pa","pegawai.ID=pa.ID","LEFT");
		$this->db->where('pa."ID" is not null',NULL,FALSE);
		$this->db->where('pegawai."KEDUDUKAN_HUKUM_ID" <> \'99\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'66\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'52\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'20\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'04\' ');
		if($strict_in_satker){
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) ".$where_clause);

		return parent::count_all();
	}
	public function find_downloadXX($satker_id,$strict_in_satker = false){
		
		$where_clause = '';
		if($satker_id){
			if(is_array($satker_id) && sizeof($satker_id)>0){
				$where_clause = 'AND ( 1=2 ';
				foreach($satker_id as $u_id){
					$where_clause .= ' OR vw."ESELON_1" = \''.$u_id.'\' OR vw."ESELON_2" = \''.$u_id.'\' OR vw."ESELON_3" = \''.$u_id.'\' OR vw."ESELON_4" = \''.$u_id.'\' ' ;
				}
				$where_clause .= ')';
			}
			else $where_clause = 'AND (vw."ESELON_1" = \''.$satker_id.'\' OR vw."ESELON_2" = \''.$satker_id.'\' OR vw."ESELON_3" = \''.$satker_id.'\' OR vw."ESELON_4" = \''.$satker_id.'\')' ;
		}
		$this->db->select('pegawai.*,vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1",
			lokasi."NAMA" as "TEMPAT_LAHIR_NAMA","TEMPAT_LAHIR_ID",
			pendidikan."NAMA" AS "NAMA_PENDIDIKAN",
			kedudukan_hukum."NAMA" AS "KEDUDUKAN_HUKUM_NAMA",
			ref_jabatan."NAMA_JABATAN" AS "NAMA_JABATAN","KELAS","JENIS_JABATAN",
			agama."NAMA" AS "NAMA_AGAMA"',false);
		$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left',false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('lokasi', 'lokasi.ID = pegawai.TEMPAT_LAHIR_ID', 'left');
		$this->db->join('pendidikan', 'pendidikan.ID = pegawai.PENDIDIKAN_ID', 'left');
		$this->db->join('agama', 'agama.ID = pegawai.AGAMA_ID', 'left');
		$this->db->join('kedudukan_hukum', 'kedudukan_hukum.ID = pegawai.KEDUDUKAN_HUKUM_ID', 'left');
		$this->db->join('ref_jabatan', 'pegawai.JABATAN_INSTANSI_ID = CAST ("ref_jabatan"."ID_JABATAN" AS TEXT) ', 'left');
		if($strict_in_satker){
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) ".$where_clause);
		$this->db->order_by("NAMA","ASC");
		return parent::find_all();
	}

	public function find_download($satker_id,$strict_in_satker = false){
		
		$where_clause = '';
		if($satker_id){
			if(is_array($satker_id) && sizeof($satker_id)>0){
				$where_clause = 'AND ( 1=2 ';
				foreach($satker_id as $u_id){
					$where_clause .= ' OR vw."ESELON_1" = \''.$u_id.'\' OR vw."ESELON_2" = \''.$u_id.'\' OR vw."ESELON_3" = \''.$u_id.'\' OR vw."ESELON_4" = \''.$u_id.'\' ' ;
				}
				$where_clause .= ')';
			}
			else $where_clause = 'AND (vw."ESELON_1" = \''.$satker_id.'\' OR vw."ESELON_2" = \''.$satker_id.'\' OR vw."ESELON_3" = \''.$satker_id.'\' OR vw."ESELON_4" = \''.$satker_id.'\')' ;
		}
		$this->db->select('pegawai.*,vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1",
			lokasi."NAMA" as "TEMPAT_LAHIR_NAMA","TEMPAT_LAHIR_ID",
			pendidikan."NAMA" AS "NAMA_PENDIDIKAN",
			kedudukan_hukum."NAMA" AS "KEDUDUKAN_HUKUM_NAMA",
			ref_jabatan."NAMA_JABATAN" AS "NAMA_JABATAN","KELAS","JENIS_JABATAN",
			agama."NAMA" AS "NAMA_AGAMA"',false);
		$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left',false);
		$this->db->join("pns_aktif as pa", 'pegawai.ID=pa.ID', 'left');
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('lokasi', 'lokasi.ID = pegawai.TEMPAT_LAHIR_ID', 'left');
		$this->db->join('pendidikan', 'pendidikan.ID = pegawai.PENDIDIKAN_ID', 'left');
		$this->db->join('agama', 'agama.ID = pegawai.AGAMA_ID', 'left');
		$this->db->join('kedudukan_hukum', 'kedudukan_hukum.ID = pegawai.KEDUDUKAN_HUKUM_ID', 'left');
		$this->db->join('ref_jabatan', 'pegawai.JABATAN_INSTANSI_ID = CAST ("ref_jabatan"."ID_JABATAN" AS TEXT) ', 'left');
		if($strict_in_satker){
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where('pa."ID" is not null');
		$this->db->where('pegawai."KEDUDUKAN_HUKUM_ID" <> \'99\'');
		$this->db->where('pegawai."KEDUDUKAN_HUKUM_ID" <> \'66\'');
		$this->db->where('pegawai."KEDUDUKAN_HUKUM_ID" <> \'52\'');
		$this->db->where('pegawai."KEDUDUKAN_HUKUM_ID" <> \'20\'');
		$this->db->where('pegawai."KEDUDUKAN_HUKUM_ID" <> \'04\'');
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) ".$where_clause);
		$this->db->order_by("NAMA","ASC");
		return parent::find_all();
	}
	public function find_all_ppnpn($satker_id,$strict_in_satker = false){
		
		$where_clause = '';
		if($satker_id){
			if(is_array($satker_id) && sizeof($satker_id)>0){
				$where_clause = 'AND ( 1=2 ';
				foreach($satker_id as $u_id){
					$where_clause .= ' OR vw."ESELON_1" = \''.$u_id.'\' OR vw."ESELON_2" = \''.$u_id.'\' OR vw."ESELON_3" = \''.$u_id.'\' OR vw."ESELON_4" = \''.$u_id.'\' ' ;
				}
				$where_clause .= ')';
			}
			else $where_clause = 'AND (vw."ESELON_1" = \''.$satker_id.'\' OR vw."ESELON_2" = \''.$satker_id.'\' OR vw."ESELON_3" = \''.$satker_id.'\' OR vw."ESELON_4" = \''.$satker_id.'\')' ;
		}
		$this->db->select('pegawai.*,vw."NAMA_UNOR_FULL"',false);
		$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left',false);
		if($strict_in_satker){
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where("status_pegawai = 3 ".$where_clause);
		$this->db->order_by("NAMA","ASC");
		return parent::find_all();
	}
	
	public function find_kelompokjabatan(){
		$this->db->select('pegawai."ID",pegawai."NAMA","NIP_BARU",golongan."NAMA"  as "NAMA_GOLONGAN"',false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->order_by("pegawai.GOL_ID","DESC");
		return parent::find_all();
	}
	public function count_kelompokjabatan(){
		$this->db->select('pegawai."ID",pegawai."NAMA","NIP_BARU",golongan."NAMA"  as "NAMA_GOLONGAN"',false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		return parent::count_all();
	}
	public function get_drh($id){
		return $this->db->from('vw_drh')->where('PNS_ID',$id)->get()->first_row();
	}
    public function find_detil($ID ="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*,jenis_pegawai.NAMA as JENIS_PEGAWAI,kedudukan_hukum.NAMA AS KEDUDUKAN_HUKUM,pa.masa_kerja_th,pa.masa_kerja_bl');
		}
		$this->db->join('jenis_pegawai', 'pegawai.JENIS_PEGAWAI_ID = jenis_pegawai.ID', 'left');
		$this->db->join('pns_aktif pa', 'pa.ID = pegawai.ID', 'left');
		$this->db->join('kedudukan_hukum', 'pegawai.KEDUDUKAN_HUKUM_ID = kedudukan_hukum.ID', 'left');
		
		return parent::find($ID);
	}
	public function find_detil_nip($PNS_ID ="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*,NAMA_UNOR_FULL');
		}
		$this->db->where("PNS_ID",$PNS_ID);
		$this->db->join('vw_unit_list vw', 'pegawai.UNOR_ID = vw.ID', 'left');
		return parent::find_all();
	}
	public function find_grupjabatan($eselon2 ="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.JABATAN_ID,UNOR_ID,count(pegawai."JABATAN_ID") as jumlah');
		}
		if($eselon2 != ""){
			$this->db->where('"UNOR_ID" LIKE \''.strtoupper($eselon2).'%\'');
		}
		//$this->db->join('ref_jabatan', 'ref_jabatan.ID_Jabatan = JABATAN_ID', 'left');
		$this->db->group_by("JABATAN_ID");
		$this->db->group_by("UNOR_ID");
		return parent::find_all();
	}
	public function find_grupjabataninstansi($eselon2 ="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.JABATAN_INSTANSI_ID,KODE_INTERNAL,count(pegawai."JABATAN_INSTANSI_ID") as jumlah');
		}
		if($eselon2 != ""){
			$this->db->where('"KODE_INTERNAL" LIKE \''.strtoupper($eselon2).'%\'');
		}
		//$this->db->join('ref_jabatan', 'ref_jabatan.ID_Jabatan = JABATAN_ID', 'left');
		$this->db->join("unitkerja","pegawai.UNOR_ID=unitkerja.ID", 'left');
		$this->db->group_by("JABATAN_INSTANSI_ID");
		//$this->db->group_by("UNOR_ID");
		$this->db->group_by("KODE_INTERNAL");
		return parent::find_all();
	}
	public function find_pegawai_jabatan($eselon2 ="")
	{
		
		if(empty($this->selects))
		{
			$this->select('NAMA,NIP_BARU,JABATAN_INSTANSI_ID,KODE_INTERNAL');
		}
		if($eselon2 != ""){
			$this->db->where('"KODE_INTERNAL" LIKE \''.strtoupper($eselon2).'%\'');
		}
		//$this->db->join('ref_jabatan', 'ref_jabatan.ID_Jabatan = JABATAN_ID', 'left');
		$this->db->join("unitkerja","pegawai.UNOR_ID=unitkerja.ID", 'left');
		return parent::find_all();
	}
	// update yanarazor
	public function find_grupagama($satker_id){		
		$where_clause = '';
		if($satker_id){
			$where_clause = 'AND (vw."ESELON_1" = \''.$satker_id.'\' OR vw."ESELON_2" = \''.$satker_id.'\' OR vw."ESELON_3" = \''.$satker_id.'\' OR vw."ESELON_4" = \''.$satker_id.'\')' ;
		}
		//$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left',false);
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.AGAMA_ID,agama.NAMA AS label,sum(case when vw."ID" is not null  then 1 else 0  end) as value');
		}
		if($eselon2 != ""){
			$this->db->where('"UNOR_ID" LIKE \''.strtoupper($eselon2).'%\'');
		}
		$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left',false);
		$this->db->join("pns_aktif pa","pegawai.ID=pa.ID","LEFT");
		$this->db->join('agama', 'pegawai.AGAMA_ID = agama.ID', 'left');

		$this->db->where('pa."ID" is not null',NULL,FALSE);
		$this->db->where('pegawai."KEDUDUKAN_HUKUM_ID" <> \'99\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'66\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'52\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'20\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'04\' ');
		
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) ".$where_clause);

		$this->db->group_by('pegawai."AGAMA_ID"');
		$this->db->group_by("agama.NAMA");
		$this->db->order_by('pegawai."AGAMA_ID"');
		return parent::find_all();
	}
	public function group_by_range_umur($unor_id=''){		
		$where_clause = '';
		if($unor_id!=''){
			$where_clause = 'AND (vw."ESELON_1" = \''.$unor_id.'\' OR vw."ESELON_2" = \''.$unor_id.'\' OR vw."ESELON_3" = \''.$unor_id.'\' OR vw."ESELON_4" = \''.$unor_id.'\')' ;
		}
		$this->db->select('	 sum(CASE WHEN pa."ID" is not null AND  "AGE" < 25 THEN 1 else 0 END) "<25"
							,sum(CASE WHEN pa."ID" is not null AND  "AGE" >= 25  AND "AGE" <= 30 THEN 1 else 0 END) "25-30"
							,sum(CASE WHEN pa."ID" is not null AND  "AGE" >= 31  AND "AGE" <= 35 THEN 1 else 0 END) "31-35"
							,sum(CASE WHEN pa."ID" is not null AND  "AGE" >= 36  AND "AGE" <= 40 THEN 1 else 0 END) "36-40"
							,sum(CASE WHEN pa."ID" is not null AND  "AGE" >= 41  AND "AGE" <= 45 THEN 1 else 0 END) "41-45"
							,sum(CASE WHEN pa."ID" is not null AND  "AGE" >= 46  AND "AGE" <= 50 THEN 1 else 0 END) "46-50"
							,sum(CASE WHEN pa."ID" is not null AND  "AGE" > 50 THEN 1 END) ">50"
										',false);
		
		$this->db->from("daftar_pegawai");
		$this->db->join("pegawai","daftar_pegawai.ID=pegawai.ID","LEFT");
		$this->db->join("pns_aktif pa","daftar_pegawai.ID=pa.ID","LEFT");
		$this->db->where('pa."ID" is not null',NULL,FALSE);
		$this->db->where('daftar_pegawai."KEDUDUKAN_HUKUM_ID" <> \'99\' and daftar_pegawai."KEDUDUKAN_HUKUM_ID" <> \'66\' and daftar_pegawai."KEDUDUKAN_HUKUM_ID" <> \'52\' and daftar_pegawai."KEDUDUKAN_HUKUM_ID" <> \'20\' and daftar_pegawai."KEDUDUKAN_HUKUM_ID" <> \'04\' ');
		$this->db->join("vw_unit_list as vw","daftar_pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left',false);


		
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) ".$where_clause);
		

		return $this->db->get()->result('array');								
	}

	
	public function grupbypendidikan($unor_id=''){		
		$where_clause = '';
		if($unor_id!=''){
			$where_clause = 'AND (vw."ESELON_1" = \''.$unor_id.'\' OR vw."ESELON_2" = \''.$unor_id.'\' OR vw."ESELON_3" = \''.$unor_id.'\' OR vw."ESELON_4" = \''.$unor_id.'\')' ;
		}

		if(empty($this->selects))
		{
			$this->select('tkpendidikan.NAMA as NAMA_PENDIDIKAN,sum(case when vw."ID" is not null  then 1 else 0  end) as jumlah');
		}
		$this->db->join('pendidikan', 'pegawai.PENDIDIKAN_ID = pendidikan.ID', 'left');
		$this->db->join('tkpendidikan', 'pendidikan.TINGKAT_PENDIDIKAN_ID = tkpendidikan.ID', 'left');
		$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left',false);
		$this->db->join("pns_aktif pa","pegawai.ID=pa.ID","LEFT");
		$this->db->where('pa."ID" is not null',NULL,FALSE);

		$this->db->where('pegawai."KEDUDUKAN_HUKUM_ID" <> \'99\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'66\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'52\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'20\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'04\' ');

		$this->db->group_by('tkpendidikan.NAMA');
		$this->db->group_by('tkpendidikan.ID');
		$this->db->order_by('tkpendidikan.ID',"ASC");
		return parent::find_all();
	}
	public function grupbyjk($unor_id=''){		
		$where_clause = '';
		if($unor_id!=''){
			$where_clause = 'AND (vw."ESELON_1" = \''.$unor_id.'\' OR vw."ESELON_2" = \''.$unor_id.'\' OR vw."ESELON_3" = \''.$unor_id.'\' OR vw."ESELON_4" = \''.$unor_id.'\')' ;
		}
		if(empty($this->selects))
		{
			$this->select('pegawai.JENIS_KELAMIN,sum(case when vw."ID" is not null  then 1 else 0  end) as jumlah');
		}
		$this->db->join("pns_aktif pa","pegawai.ID=pa.ID","LEFT");
		$this->db->where('pa."ID" is not null',NULL,FALSE);
		$this->db->where('pegawai."KEDUDUKAN_HUKUM_ID" <> \'99\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'66\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'52\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'20\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'04\' ');
		$this->db->group_by('pegawai.JENIS_KELAMIN');
		$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left',false);
		return parent::find_all();
	}
	// pensiun dari umur default 58
	public function count_pensiunold($unor_id=''){
		if($unor_id!=''){
			$this->db->where("(unitkerja.ID = '".$unor_id."' or unitkerja.ESELON_1 = '".$unor_id."' or unitkerja.ESELON_2 = '".$unor_id."' or unitkerja.ESELON_3 = '".$unor_id."' or unitkerja.ESELON_4 = '".$unor_id."')");
		}
		$this->db->select('pegawai.*');
		$this->db->where('EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) > 58');
		$this->db->join("hris.unitkerja ","pegawai.UNOR_ID=unitkerja.ID", 'left');
		return parent::count_all();
	}
	public function count_pensiun($unor_id=''){
		if($unor_id!=''){
			$this->db->where("(unitkerja.ID = '".$unor_id."' or unitkerja.ESELON_1 = '".$unor_id."' or unitkerja.ESELON_2 = '".$unor_id."' or unitkerja.ESELON_3 = '".$unor_id."' or unitkerja.ESELON_4 = '".$unor_id."')");
		}
		$this->db->select('pegawai.*');
		$this->db->where('EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) >= jabatan."PENSIUN"');
		$this->db->where("(status_pegawai != 3 or status_pegawai is null)");
		$this->db->where("(TMT_PENSIUN IS NULL)");
		$this->db->join("hris.unitkerja ","pegawai.UNOR_ID=unitkerja.ID", 'left');
		$this->db->join("hris.jabatan ","pegawai.JABATAN_INSTANSI_REAL_ID=jabatan.KODE_JABATAN", 'left');
		$this->db->join("pns_aktif pa","pegawai.ID=pa.ID","inner");
		return parent::count_all();
	}
	public function find_all_pensiunall($unor_id=''){
		if($unor_id!=''){
			$this->db->where("(unitkerja.ID = '".$unor_id."' or unitkerja.ESELON_1 = '".$unor_id."' or unitkerja.ESELON_2 = '".$unor_id."' or unitkerja.ESELON_3 = '".$unor_id."' or unitkerja.ESELON_4 = '".$unor_id."')");
		}
		$this->db->select('pegawai.*,unitkerja."NAMA_UNOR",EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) as umur');
		$this->db->where('EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) > 58');
		$this->db->join("unitkerja","pegawai.UNOR_ID=unitkerja.ID", 'left');
		return parent::find_all();
	}
	public function find_all_pensiun($unor_id=''){
		if($unor_id!=''){
			$this->db->where("(unitkerja.ID = '".$unor_id."' or unitkerja.ESELON_1 = '".$unor_id."' or unitkerja.ESELON_2 = '".$unor_id."' or unitkerja.ESELON_3 = '".$unor_id."' or unitkerja.ESELON_4 = '".$unor_id."')");
		}
		$this->db->select('pegawai.*,unitkerja."NAMA_UNOR",EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) as umur,jabatan."NAMA_JABATAN",jabatan."PENSIUN"');
		$this->db->where('EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) >= jabatan."PENSIUN"');
		$this->db->where("(status_pegawai != 3 or status_pegawai is null)");
		$this->db->where("(TMT_PENSIUN IS NULL)");

		$this->db->join("unitkerja","pegawai.UNOR_ID=unitkerja.ID", 'left');
		$this->db->join("hris.jabatan ","pegawai.JABATAN_INSTANSI_REAL_ID=jabatan.KODE_JABATAN", 'left');
		$this->db->join("pns_aktif pa","pegawai.ID=pa.ID","inner");
		return parent::find_all();
	}
	public function stats_pensiun_pertahun($satker_id,$tahun_kedepan=5){		
		$where_clause = '';
		if($satker_id){
			$where_clause = 'AND (vw."ESELON_1" = \''.$satker_id.'\' OR vw."ESELON_2" = \''.$satker_id.'\' OR vw."ESELON_3" = \''.$satker_id.'\' OR vw."ESELON_4" = \''.$satker_id.'\')' ;
		}

		$db_stats =$this->db->query('
			select perkiraan_tahun_pensiun,sum(CASE  WHEN  temp."ID" is not null THEN 1 else 0 END) 
				as total from (
				select vw."ID","TGL_LAHIR","NIP_BARU","BUP","NAMA", date_part(\'year\', "TGL_LAHIR")+"BUP" as perkiraan_tahun_pensiun 
					from hris.pegawai pegawai
					inner join hris.pns_aktif pa on pegawai."ID"  = pa."ID"
					LEFT JOIN "hris"."jabatan" ON "pegawai"."JABATAN_INSTANSI_REAL_ID" = "jabatan"."KODE_JABATAN"
					left join vw_unit_list as vw on pegawai."UNOR_ID" = vw."ID" '.$where_clause.'
					where 1=1  
				) as temp
				group by perkiraan_tahun_pensiun
				
				limit ('.$tahun_kedepan.')
		')->result('array');
		$tahun = date('Y');
		$tahuns = array();
		for($t=$tahun;$t<=$tahun+$tahun_kedepan;$t++){
			$tahuns[] = array("tahun"=>$t,"jumlah"=>0);
		}
		foreach($tahuns as &$tahun){
			foreach($db_stats as $row){
				if($tahun['tahun']==$row['perkiraan_tahun_pensiun']){
					$tahun['jumlah'] = $row['total'];
					break;
				}
			}
		}
		return $tahuns;
	}
	public function find_pensiunbyjenisjabatan($unor_id=''){
		$sepuluhtahun = date("Y") + 10;
		if($unor_id!=''){
			$this->db->where("(unitkerja.ID = '".$unor_id."' or unitkerja.ESELON_1 = '".$unor_id."' or unitkerja.ESELON_2 = '".$unor_id."' or unitkerja.ESELON_3 = '".$unor_id."' or unitkerja.ESELON_4 = '".$unor_id."')");
		}
		$this->db->select('jenis_jabatan."ID" as ID_JENIS_JABATAN,jenis_jabatan."NAMA" as JENIS_JABATAN,
			date_part(\'year\', "TGL_LAHIR" ) + "BUP" AS perkiraan_tahun_pensiun,  
 			count(*) as total');

		$this->db->where("(status_pegawai != 3 or status_pegawai is null)");
		$this->db->where("(TMT_PENSIUN IS NULL)");
		$this->db->where('date_part(\'year\', "TGL_LAHIR" ) + "BUP" >= '.date("Y").'');
		$this->db->where('date_part(\'year\', "TGL_LAHIR" ) + "BUP" <= '.$sepuluhtahun.'');

		$this->db->join("unitkerja","pegawai.UNOR_ID=unitkerja.ID", 'left');
		$this->db->join("hris.jabatan ","pegawai.JABATAN_INSTANSI_REAL_ID=jabatan.KODE_JABATAN", 'left');
		$this->db->join("hris.jenis_jabatan ","jenis_jabatan.ID=jabatan.JENIS_JABATAN", 'left');
		$this->db->join("pns_aktif pa","pegawai.ID=pa.ID","inner");
		$this->db->order_by('perkiraan_tahun_pensiun',"asc");
		$this->db->group_by('jenis_jabatan."NAMA"');
		$this->db->group_by('jenis_jabatan."ID"');
		$this->db->group_by('perkiraan_tahun_pensiun');
		return parent::find_all();
	}
	public function find_pensiunbyjabatan($unor_id=''){
		$sepuluhtahun = date("Y") + 10;
		if($unor_id!=''){
			$this->db->where("(unitkerja.ID = '".$unor_id."' or unitkerja.ESELON_1 = '".$unor_id."' or unitkerja.ESELON_2 = '".$unor_id."' or unitkerja.ESELON_3 = '".$unor_id."' or unitkerja.ESELON_4 = '".$unor_id."')");
		}
		$this->db->select('jabatan."KODE_JABATAN",jabatan."NAMA_JABATAN",
			date_part(\'year\', "TGL_LAHIR" ) + "BUP" AS perkiraan_tahun_pensiun,  
 			count(*) as total');

		$this->db->where("(status_pegawai != 3 or status_pegawai is null)");
		$this->db->where("(TMT_PENSIUN IS NULL)");
		$this->db->where('date_part(\'year\', "TGL_LAHIR" ) + "BUP" >= '.date("Y").'');
		$this->db->where('date_part(\'year\', "TGL_LAHIR" ) + "BUP" <= '.$sepuluhtahun.'');

		$this->db->join("unitkerja","pegawai.UNOR_ID=unitkerja.ID", 'left');
		$this->db->join("hris.jabatan ","pegawai.JABATAN_INSTANSI_REAL_ID=jabatan.KODE_JABATAN", 'left');
		$this->db->join("pns_aktif pa","pegawai.ID=pa.ID","inner");
		$this->db->order_by('perkiraan_tahun_pensiun',"asc");
		$this->db->group_by('KODE_JABATAN');
		$this->db->group_by('jabatan."NAMA_JABATAN"');
		$this->db->group_by('perkiraan_tahun_pensiun');
		return parent::find_all();
	}
	public function find_by_golonganbulan($unor_id=''){
		$sepuluhtahun = date("Y") + 10;
		if($unor_id!=''){
			$this->db->where("(unitkerja.ID = '".$unor_id."' or unitkerja.ESELON_1 = '".$unor_id."' or unitkerja.ESELON_2 = '".$unor_id."' or unitkerja.ESELON_3 = '".$unor_id."' or unitkerja.ESELON_4 = '".$unor_id."')");
		}
		$this->db->select('pegawai."GOL_ID",golongan."NAMA",  
 			count(*) as total');

		$this->db->where('pegawai."KEDUDUKAN_HUKUM_ID" <> \'99\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'66\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'52\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'20\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'04\' ');
		$this->db->where("(status_pegawai != 3 or status_pegawai is null)");
		$this->db->where("(TMT_PENSIUN IS NULL)");

		$this->db->join("unitkerja","pegawai.UNOR_ID=unitkerja.ID", 'left');
		$this->db->join("hris.golongan ","pegawai.GOL_ID=golongan.ID", 'left');
		$this->db->join("pns_aktif pa","pegawai.ID=pa.ID","inner");
		$this->db->order_by('GOL_ID',"asc");
		$this->db->group_by('GOL_ID');
		$this->db->group_by('golongan."NAMA"');
		return parent::find_all();
	}
	public function get_duk_list($unit_id=null,$start,$length){
		$output = new stdClass;
		$this->db->start_cache();
		if($unit_id){
			$this->db->group_start();
			$this->db->or_where("ESELON_1",$unit_id);
			$this->db->or_where("ESELON_2",$unit_id);
			$this->db->or_where("ESELON_3",$unit_id);
			$this->db->or_where("ESELON_4",$unit_id);
			$this->db->group_end();
		}
		$this->db->from('vw_duk_list');
		$this->db->stop_cache();
		$total = $this->db->get()->num_rows();
		$this->db->limit($length,$start);
		$data = $this->db->get()->result();
		$output->total = $total;
		$output->data = $data;
		$this->db->flush_cache();
		return $output;
	}	

	public function get_jumlah_pegawai_per_golongan($satker_id){
		$where_clause = '';
		if($satker_id){
			$where_clause = 'AND (vw."ESELON_1" = \''.$satker_id.'\' OR vw."ESELON_2" = \''.$satker_id.'\' OR vw."ESELON_3" = \''.$satker_id.'\' OR vw."ESELON_4" = \''.$satker_id.'\')' ;
		}
		$where_clause .= 'AND pegawai."KEDUDUKAN_HUKUM_ID" <> \'99\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'66\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'52\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'20\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'04\' ';

		return $this->db->query('
			select golongan."ID" as "id",golongan."NAMA" as "nama",count(vw."ID") as total from hris.golongan
			left join hris.pegawai on  golongan."ID" = pegawai."GOL_ID" 
			inner join hris.pns_aktif pa on pegawai."ID" = pa."ID" 
			left join vw_unit_list vw on pegawai."UNOR_ID"= vw."ID" '.$where_clause.'
			group by golongan."ID",golongan."NAMA"
			order by golongan."ID" asc
		')->result('array');
	}
	public function get_bup_per_range_umur($satker_id){
		$where_clause = '';
		if($satker_id){
			$where_clause = 'AND (vw."ESELON_1" = \''.$satker_id.'\' OR vw."ESELON_2" = \''.$satker_id.'\' OR vw."ESELON_3" = \''.$satker_id.'\' OR vw."ESELON_4" = \''.$satker_id.'\')' ;
		}
			$data = $this->db->query('
				select 
					 sum(CASE  WHEN  TEMPX."ID" is not null AND age < 25 THEN 1 END) "<25"
					,sum(CASE  WHEN  TEMPX."ID" is not null AND age >= 25  AND age <= 30 THEN 1 END) "25-30"
					,sum(CASE  WHEN  TEMPX."ID" is not null AND age >= 31  AND age <= 35 THEN 1 END) "31-35"
					,sum(CASE  WHEN  TEMPX."ID" is not null AND age >= 36  AND age <= 40 THEN 1 END) "36-40"
					,sum(CASE  WHEN  TEMPX."ID" is not null AND age >= 41  AND age <= 45 THEN 1 END) "41-45"
					,sum(CASE  WHEN  TEMPX."ID" is not null AND age >= 46  AND age <= 50 THEN 1 END) "46-50"
					,sum(CASE  WHEN  TEMPX."ID" is not null AND age > 50 THEN 1 END) ">50",TEMPx."bup"
				FROM (
				SELECT vw."ID",EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) age,pegawai."BUP" as "bup"
				FROM hris.pegawai pegawai 
				left join hris.pns_aktif pa on pa."ID" = pegawai."ID" 
				left join vw_unit_list vw on pegawai."UNOR_ID"= vw."ID" '.$where_clause.'
				where 1=1  
				) AS TEMPx 
				group by TEMPx."bup"
			')->result('array');
		$bups = array('58','60');
		$range_umur = array('<25'=>array(),'25-30'=>array(),'31-35'=>array(),'36-40'=>array(),'41-45'=>array(),'46-50'=>array(),'>50'=>array());

		foreach($range_umur as $key=>&$rumur){
			$rumur['range'] = $key;
			foreach($bups as $bup){
				$rumur["bup_".$bup] = 0;	
				foreach($data as $row){
					$rec = new stdClass;
					if(isset($row[$key]) && $row['bup'] == $bup){
						$rumur["bup_".$bup] = $row[$key];	
					}
					else if( !isset($row[$key]) && $row['bup'] == $bup){
						$rumur["bup_".$bup] = 0;	
					}
				}
			}
		}
		return array_values($range_umur);
	}
	public function get_rekap_golongan_per_jenis_kelamin($satker_id){
		$where_clause = '';
		if($satker_id){
			$where_clause = 'AND (vw."ESELON_1" = \''.$satker_id.'\' OR vw."ESELON_2" = \''.$satker_id.'\' OR vw."ESELON_3" = \''.$satker_id.'\' OR vw."ESELON_4" = \''.$satker_id.'\')' ;
		}
			$data = $this->db->query('
			select TEMPx."nama",sum(CASE  WHEN TEMPX."ID" is not null and jenis_kelamin =\'M\' THEN 1 ELSE 0 END) "M"
																			,sum(CASE  WHEN TEMPX."ID" is not null and jenis_kelamin =\'F\' THEN 1 ELSE 0 END) "F"
																			,sum(CASE  WHEN TEMPX."ID" is not null and jenis_kelamin =NULL THEN 1 ELSE 0 END) "-"
								FROM (
										select vw."ID",golongan."NAMA" as "nama",pegawai."JENIS_KELAMIN" as "jenis_kelamin" from hris.golongan  
										
										left join hris.pegawai pegawai on golongan."ID" = pegawai."GOL_ID"
										left join hris.pns_aktif pa on pa."ID" = pegawai."ID"
										left join vw_unit_list vw on pegawai."UNOR_ID"= vw."ID" '.$where_clause.'
										where 1=1 
			)AS TEMPx 
								group by TEMPx."nama"
			order by TEMPx."nama"
		')->result('array');
		return $data;
	}
	public function get_rekap_golongan_per_pendidikan($satker_id){
		$where_clause = '';
		if($satker_id){
			$where_clause = 'AND (vw."ESELON_1" = \''.$satker_id.'\' OR vw."ESELON_2" = \''.$satker_id.'\' OR vw."ESELON_3" = \''.$satker_id.'\' OR vw."ESELON_4" = \''.$satker_id.'\')' ;
		}
		$data = $this->db->query('
			select TEMPx."golongan" as "GOLONGAN"
				,sum(case when TEMPx."ID" is not null AND TEMPx."nama" = \'SLTP Kejuruan\' then 1 else 0  end) as "SLTP Kejuruan"
				,sum(case when TEMPx."ID" is not null AND TEMPx."nama" = \'SLTA Kejuruan\' then 1 else 0  end) as "SLTA Kejuruan"
				,sum(case when TEMPx."ID" is not null AND TEMPx."nama" = \'Sekolah Dasar\' then 1 else 0  end) as "Sekolah Dasar"
				,sum(case when TEMPx."ID" is not null AND TEMPx."nama" = \'SLTP\' then 1 else 0  end) as "SLTP"
				,sum(case when TEMPx."ID" is not null AND TEMPx."nama" = \'SLTA\' then 1 else 0  end) as "SLTA"
				,sum(case when TEMPx."ID" is not null AND TEMPx."nama" = \'Diploma I\' then 1 else 0  end) as "Diploma I"
				,sum(case when TEMPx."ID" is not null AND TEMPx."nama" = \'Diploma II\' then 1 else 0  end) as "Diploma II"
				,sum(case when TEMPx."ID" is not null AND TEMPx."nama" = \'Diploma III/Sarjana Muda\' then 1 else 0  end) as "Diploma III/Sarjana Muda"
				,sum(case when TEMPx."ID" is not null AND TEMPx."nama" = \'Diploma IV\' then 1 else 0  end) as "Diploma IV"
				,sum(case when TEMPx."ID" is not null AND TEMPx."nama" = \'S-1/Sarjana\' then 1 else 0  end) as "S-1/Sarjana"
				,sum(case when TEMPx."ID" is not null AND TEMPx."nama" = \'S-2\' then 1 else 0  end) as "S-2"
				,sum(case when TEMPx."ID" is not null AND TEMPx."nama" = \'S-3/Doktor\' then 1 else 0  end) as "S-3/Doktor"
				,sum(case when TEMPx."ID" is not null AND TEMPx."nama" = \'SLTA Keguruan\' then 1 else 0  end) as "SLTA Keguruan"
								FROM (
										select vw."ID",golongan."NAMA" as "golongan",tkpendidikan."NAMA" as "nama" from hris.golongan  
										left join hris.pegawai pegawai on golongan."ID" = pegawai."GOL_ID"
										left join hris.pns_aktif pa on pa."ID" = pegawai."ID"
										left join vw_unit_list vw on pegawai."UNOR_ID"= vw."ID" '.$where_clause.'
										left join hris.pendidikan on pendidikan."ID" = pegawai."PENDIDIKAN_ID"
										left join hris.tkpendidikan on tkpendidikan."ID" = pendidikan."TINGKAT_PENDIDIKAN_ID"
										where 1=1 

			)AS TEMPx 
								group by TEMPx."golongan"
			order by TEMPx."golongan"	
		')->result('array');
		return $data;
	}
	public function get_rekap_jenis_kelamin_per_usia($satker_id){
		$where_clause = '';
		if($satker_id){
			$where_clause = 'AND (vw."ESELON_1" = \''.$satker_id.'\' OR vw."ESELON_2" = \''.$satker_id.'\' OR vw."ESELON_3" = \''.$satker_id.'\' OR vw."ESELON_4" = \''.$satker_id.'\')' ;
		}
		$data = $this->db->query('
			select tempx."JENIS KELAMIN"
				,sum(CASE  WHEN tempx."ID" is not null AND age < 25 THEN 1 else 0 END) "<25"
				,sum(CASE  WHEN tempx."ID" is not null AND age >= 25  AND age <= 30 THEN 1 else 0  END) "25-30"
				,sum(CASE  WHEN tempx."ID" is not null AND age >= 31  AND age <= 35 THEN 1 else 0  END) "31-35"
				,sum(CASE  WHEN tempx."ID" is not null AND age >= 36  AND age <= 40 THEN 1 else 0  END) "36-40"
				,sum(CASE  WHEN tempx."ID" is not null AND age >= 41  AND age <= 45 THEN 1 else 0  END) "41-45"
				,sum(CASE  WHEN tempx."ID" is not null AND age >= 46  AND age <= 50 THEN 1 else 0  END) "46-50"
				,sum(CASE WHEN tempx."ID" is not null AND age > 50 THEN 1 else 0 END) ">50"
			from (
										select vw."ID",pegawai."JENIS_KELAMIN" as "JENIS KELAMIN",EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) age 
										from (select \'M\' as sex union select \'F\') as d 
										left join hris.pegawai pegawai on pegawai."JENIS_KELAMIN" = d.sex 
										left join hris.pns_aktif pa on  pa."ID" = pegawai."ID" 
										
										left join vw_unit_list vw on 1=1 and pegawai."UNOR_ID"= vw."ID" '.$where_clause.'
										WHERE 1=1
			) as tempx
			group by tempx."JENIS KELAMIN"

		')->result('array');
		return $data;
	}
	public function get_rekap_pendidikan_per_jenis_kelamin($satker_id){
		$where_clause = '';
		if($satker_id){
			$where_clause = 'AND (vw."ESELON_1" = \''.$satker_id.'\' OR vw."ESELON_2" = \''.$satker_id.'\' OR vw."ESELON_3" = \''.$satker_id.'\' OR vw."ESELON_4" = \''.$satker_id.'\')' ;
		}

		$data = $this->db->query('
			select tempx."nama",sum(CASE  WHEN tempx."ID" is not null AND tempx.jenis_kelamin = \'M\' THEN 1 else 0 END) "M"
																,sum(CASE  WHEN tempx."ID" is not null AND tempx.jenis_kelamin = \'F\' THEN 1 else 0 END) "F"
																,sum(CASE  WHEN tempx."ID" is not null AND tempx.jenis_kelamin = null  THEN 1 else 0 END) "-"
			from (
										select vw."ID",tkpendidikan."NAMA" as "nama",pegawai."JENIS_KELAMIN" as "jenis_kelamin" 
										from hris.tkpendidikan 
										left join hris.pendidikan on tkpendidikan."ID" = pendidikan."TINGKAT_PENDIDIKAN_ID"
										left join hris.pegawai pegawai  on pendidikan."ID" = pegawai."PENDIDIKAN_ID" 
										left join hris.pns_aktif pa  on pa."ID" = pegawai."ID" 
										left join vw_unit_list vw on pegawai."UNOR_ID"= vw."ID"  '.$where_clause.'
										WHERE 1=1 
			) as tempx
			group by tempx."nama"
		')->result('array');
		
		return $data;
	}
	public function get_rekap_pendidikan_per_usia ($satker_id){
		$where_clause = '';
		if($satker_id){
			$where_clause = 'AND (vw."ESELON_1" = \''.$satker_id.'\' OR vw."ESELON_2" = \''.$satker_id.'\' OR vw."ESELON_3" = \''.$satker_id.'\' OR vw."ESELON_4" = \''.$satker_id.'\')' ;
		}
		$data = $this->db->query('
			select tempx."TK PENDIDIKAN"
				,sum(CASE  WHEN tempx."ID" is not null AND age < 25 THEN 1 else 0 END) "<25"
				,sum(CASE  WHEN tempx."ID" is not null AND age >= 25  AND age <= 30 THEN 1 else 0  END) "25-30"
				,sum(CASE  WHEN tempx."ID" is not null AND age >= 31  AND age <= 35 THEN 1 else 0  END) "31-35"
				,sum(CASE  WHEN tempx."ID" is not null AND age >= 36  AND age <= 40 THEN 1 else 0  END) "36-40"
				,sum(CASE  WHEN tempx."ID" is not null AND age >= 41  AND age <= 45 THEN 1 else 0  END) "41-45"
				,sum(CASE  WHEN tempx."ID" is not null AND age >= 46  AND age <= 50 THEN 1 else 0  END) "46-50"
				,sum(CASE WHEN tempx."ID" is not null AND age > 50 THEN 1 else 0 END) ">50"
			from (
										select vw."ID",tkpendidikan."NAMA" as "TK PENDIDIKAN",EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) age 
										from hris.tkpendidikan 
										left join hris.pendidikan on tkpendidikan."ID" = pendidikan."TINGKAT_PENDIDIKAN_ID"
										left join hris.pegawai pegawai  on  pendidikan."ID" = pegawai."PENDIDIKAN_ID" 
										left join hris.pns_aktif pa on pa."ID" = pegawai."ID"
										left join vw_unit_list vw on 1=1 and pegawai."UNOR_ID"= vw."ID" '.$where_clause.'
										where 1=1 
			) as tempx
			group by tempx."TK PENDIDIKAN"

		')->result('array');
		return $data;
	}
	public function get_rekap_golongan_per_usia($satker_id){
		$where_clause = '';
		if($satker_id){
			$where_clause = 'AND (vw."ESELON_1" = \''.$satker_id.'\' OR vw."ESELON_2" = \''.$satker_id.'\' OR vw."ESELON_3" = \''.$satker_id.'\' OR vw."ESELON_4" = \''.$satker_id.'\')' ;
		}
			$data = $this->db->query('
			select TEMPx."nama" ,sum(CASE  WHEN TEMPX."ID" IS NOT NULL AND age < 25 THEN 1 ELSE 0 END) "<25"
								,sum(CASE  WHEN TEMPX."ID" IS NOT NULL AND age >= 25  AND age <= 30 THEN 1 else 0 END) "25-30"
								,sum(CASE  WHEN TEMPX."ID" IS NOT NULL AND age >= 31  AND age <= 35 THEN 1 else 0 END) "31-35"
								,sum(CASE  WHEN TEMPX."ID" IS NOT NULL AND age >= 36  AND age <= 40 THEN 1 else 0 END) "36-40"
								,sum(CASE  WHEN TEMPX."ID" IS NOT NULL AND age >= 41  AND age <= 45 THEN 1 else 0 END) "41-45"
								,sum(CASE  WHEN TEMPX."ID" IS NOT NULL AND age >= 46  AND age <= 50 THEN 1 else 0 END) "46-50"
								,sum(CASE WHEN TEMPX."ID" IS NOT NULL AND age > 50 THEN 1 else 0 END) ">50"
								FROM (
										select vw."ID",golongan."NAMA" as "nama",EXTRACT(YEAR FROM age(cast(pegawai."TGL_LAHIR" as date))) age from hris.golongan  
										left join hris.pegawai pegawai on  golongan."ID" = pegawai."GOL_ID" 
									    left join hris.pns_aktif pa on pa."ID" = pegawai."ID" 
										left join vw_unit_list vw on  pegawai."UNOR_ID"= vw."ID" '.$where_clause.'
										where 1=1
			)AS TEMPx 
								group by TEMPx."nama"
			order by TEMPx."nama"
		')->result('array');
		
		return $data;
	}
	public function get_jumlah_pegawai_per_agama_jeniskelamin($satker_id){
		$where_clause = '';
		if($satker_id){
			$where_clause = 'AND (vw."ESELON_1" = \''.$satker_id.'\' OR vw."ESELON_2" = \''.$satker_id.'\' OR vw."ESELON_3" = \''.$satker_id.'\' OR vw."ESELON_4" = \''.$satker_id.'\')' ;
		}
			$data = $this->db->query('
				SELECT
					pegawai."JENIS_KELAMIN" as "jenis_kelamin",
					agama."ID" as "id",
					agama."NAMA" as "nama",
					COUNT (vw."ID") AS total
				FROM
					hris.agama 
					left join hris.pegawai pegawai ON pegawai."AGAMA_ID" = agama."ID" 
					left join hris.pns_aktif pa  on pa."ID" = pegawai."ID" 
					left join vw_unit_list vw on pegawai."UNOR_ID"= vw."ID"   '.$where_clause.'
					where 1=1
				GROUP BY
					pegawai."JENIS_KELAMIN",
					agama."ID",
					agama."NAMA"
				ORDER BY
					agama."NAMA";
			')->result('array');
		$agamas = array('Budha','Hindu','Islam','Katholik','Protestan','Lainnya','Belum terdata');
		$output = array();
		foreach($agamas as $agama){
			if(isset($output[$agama])){
				$rec = new stdClass;
			}
			else {
				$rec = $output[$agama];
			}
			$rec->nama = $agama;
			$rec->m = 0;
			$rec->f = 0;
			foreach($data as $row){
				if($agama==$row['nama']){
					if($row['jenis_kelamin']=='M'){
						$rec->m =  $row['total'];
					}
					else if($row['jenis_kelamin']=='F'){
						$rec->f =  $row['total'];
					}
				}
				else if('Kristen'==$row['nama'] && $agama=='Protestan'){ 
					if($row['jenis_kelamin']=='M'){
						$rec->m =  $row['total'];
					}
					else if($row['jenis_kelamin']=='F'){
						$rec->f =  $row['total'];
					}
				}
				else if(null==$row['nama'] && $agama=='Belum terdata'){ 
					if($row['jenis_kelamin']=='M'){
						$rec->m =  $row['total'];
					}
					else if($row['jenis_kelamin']=='F'){
						$rec->f =  $row['total'];
					}
				}
			}
			$output[$agama] = $rec;
		}
		
		return array_values($output);
	}
	public function get_jumlah_pegawai_per_jabatan($satker_id){
		$where_clause = '';
		if($satker_id){
			$where_clause = 'AND (vw."ESELON_1" = \''.$satker_id.'\' OR vw."ESELON_2" = \''.$satker_id.'\' OR vw."ESELON_3" = \''.$satker_id.'\' OR vw."ESELON_4" = \''.$satker_id.'\')' ;
		}
		$data = $this->db->query('
			SELECT
				jenis_jabatan."ID"	,
				jenis_jabatan."NAMA",	
				SUM(CASE WHEN vw."ID" is not null and pegawai."JENIS_JABATAN_ID" is not null then 1 else 0 end) as "JUMLAH"
			FROM
				hris.jenis_jabatan
				left join hris.pegawai pegawai on jenis_jabatan."ID" = pegawai."JENIS_JABATAN_ID" 
				left join hris.pns_aktif pa  on pa."ID" = pegawai."ID" 
				left join vw_unit_list vw on pegawai."UNOR_ID"= vw."ID"   '.$where_clause.'
				where 1=1
			GROUP BY
			jenis_jabatan."ID"	
			ORDER BY
				jenis_jabatan."NAMA"
		')->result();
	
		
		return $data;
	}
	public function getunor_id($nip = ""){
		$where_clause = 'AND pegawai."NIP_BARU" = \''.trim($nip).'\'' ;
		$unor_id = "";
		$data = $this->db->query('
				SELECT
					"UNOR_ID" 
				FROM
					hris.pegawai
					 
					where 1=1 '.$where_clause.';
			')->result('array');
		foreach($data as $row){
			//echo "masuk bro";
			$unor_id = $row['UNOR_ID'];
		}
		return $unor_id;
	}
	public function getunor_induk($nip = ""){
		$where_clause = 'AND pegawai."NIP_BARU" = \''.trim($nip).'\'' ;
		$unor_id = "";
		$data = $this->db->query('
				SELECT
					"UNOR_INDUK" 
				FROM
					hris.pegawai
					left join unitkerja vw on pegawai."UNOR_ID"= vw."ID"
					where 1=1 '.$where_clause.';
			')->result('array');
		foreach($data as $row){
			//echo "masuk bro";
			$unor_id = $row['UNOR_INDUK'];
		}
		//echo var_dump($unor_id);
		return $unor_id;
	}
	// add yana
	public function getunor_eselon1($nip = ""){
		$where_clause = 'AND pegawai."NIP_BARU" = \''.trim($nip).'\'' ;
		$unor_id = "";
		$data = $this->db->query('
				SELECT
					"ESELON_1" 
				FROM
					hris.pegawai
					left join unitkerja vw on pegawai."UNOR_ID"= vw."ID"
					where 1=1 '.$where_clause.';
			')->result('array');
		foreach($data as $row){
			//echo "masuk bro";
			$unor_id = $row['ESELON_1'];
		}
		return $unor_id;
	}
	public function get_count_jabatan_instansi($eselon2 ="",$jabatan = "")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.JABATAN_INSTANSI_ID,KODE_INTERNAL,count(pegawai."JABATAN_INSTANSI_ID") as jumlah');
		}
		if($eselon2 != ""){
			$this->db->where('"ESELON_2" LIKE \''.strtoupper($eselon2).'%\'');
		}
		$this->db->where("JABATAN_INSTANSI_ID",$jabatan);
		$this->db->join("unitkerja","pegawai.UNOR_ID=unitkerja.ID", 'left');
		$this->db->group_by("JABATAN_INSTANSI_ID");
		return parent::count_all();
	}
	public function get_count_jabatan_instansi_unor($unitkerja ="",$jabatan = "")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.JABATAN_INSTANSI_ID,KODE_INTERNAL,UNOR_ID,count(pegawai."JABATAN_INSTANSI_ID") as jumlah');
		}
		 
		if($unitkerja != ""){
			$this->db->where('("ESELON_1" = \''.$unitkerja.'\' OR "ESELON_2" = \''.$unitkerja.'\' OR "ESELON_3" = \''.$unitkerja.'\' OR "ESELON_4" = \''.$unitkerja.'\')');
		}
		$this->db->where("JABATAN_INSTANSI_ID",$jabatan);
		$this->db->join("unitkerja","pegawai.UNOR_ID=unitkerja.ID", 'left');
		$this->db->group_by("JABATAN_INSTANSI_ID");
		$this->db->group_by("KODE_INTERNAL");
		$this->db->group_by("UNOR_ID");
		return parent::find_all();
	}
	public function find_photo($satker_id,$strict_in_satker = false){
		
		$where_clause = '';
		if($satker_id){
			$where_clause = 'AND (vw."ESELON_1" = \''.$satker_id.'\' OR vw."ESELON_2" = \''.$satker_id.'\' OR vw."ESELON_3" = \''.$satker_id.'\' OR vw."ESELON_4" = \''.$satker_id.'\')' ;
		}
		$this->db->select('pegawai.*,vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1"',false);
		$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left',false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join("pns_aktif pa","pegawai.ID=pa.ID","LEFT");
		if($strict_in_satker){
			$this->db->where('vw."ID" is not null');
		}

		$this->db->where('pa."ID" is not null',NULL,FALSE);
		$this->db->where('pegawai."KEDUDUKAN_HUKUM_ID" <> \'99\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'66\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'52\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'20\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'04\' ');
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) ".$where_clause);

		$this->db->where('pegawai."PHOTO" is not null',NULL,FALSE);
		$this->db->where('pegawai."PHOTO" != \'0\'',NULL,FALSE);
		$this->db->where('pegawai."PHOTO" != \'\'',NULL,FALSE);
		$this->db->order_by("NAMA","ASC");
		return parent::find_all();
	}
	public function find_baperjakat($satker_id,$strict_in_satker = false){
		
		$where_clause = '';
		 
		$this->db->select('"NAMA","NIP_BARU","GOL_ID","TK_PENDIDIKAN"
			,(
				select "ID_JENIS_HUKUMAN" from rwt_hukdis left join jenis_hukuman on(jenis_hukuman."ID" = rwt_hukdis."ID_JENIS_HUKUMAN")
				where pegawai."NIP_BARU" = "PNS_NIP" order by rwt_hukdis."ID" DESC LIMIT 1
			) as rwt_hukuman,(
				select CAST("NILAI" as DOUBLE PRECISION) from rwt_assesmen
				where pegawai."NIP_BARU" = "PNS_NIP" order by rwt_assesmen."TAHUN" DESC LIMIT 1
			) as rwt_assesment
			',false);
		//$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\"", 'left',false);
		$this->db->join("pns_aktif pa","pegawai.ID=pa.ID","LEFT");
		$this->db->where('pa."ID" is not null',NULL,FALSE);
		$this->db->where('pegawai."KEDUDUKAN_HUKUM_ID" <> \'99\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'66\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'52\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'20\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'04\' ');
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) ");
		return parent::find_all();
	}
}
