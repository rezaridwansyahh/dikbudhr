<?php defined('BASEPATH') || exit('No direct script access allowed');

class Log_transaksi_model extends BF_Model
{
    protected $table_name	= 'log_transaksi';
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
			'field' => 'NIP',
			'label' => 'NIP',
			'rules' => 'max_length[30]',
		),
		
	);
	protected $insert_validation_rules  = array();
	protected $skip_validation 			= true;
	function __construct()
    {
		
		
		 
    }//end __construct
    public function getrekap($dari="",$sampai="")
	{
		// koneksi ke server absen
		$this->configsas = $db['kita_db'] = array(
		      'hostname' => "118.98.234.154",
		      'username' => "mutasi",
		      'password' => 'windows_2',
		      'database' => "db_bagian_mutasi_2017",
		      'port' 	=> "3306",
		      'dbdriver' => 'mysql',
		      'dbprefix' => '',
		      'pconnect' => FALSE,
		      'db_debug' => (ENVIRONMENT !== 'production'),
		      'cache_on' => FALSE,
		      'cachedir' => '',
		      'char_set' => 'utf8',
		      'dbcollat' => 'utf8_general_ci',
		      'swap_pre' => '',
		      'encrypt' => FALSE,
		      'compress' => FALSE,
		      'stricton' => FALSE,
		      'failover' => array(),
		      'save_queries' => TRUE);

		$this->db2 = $this->load->database($this->configsas, TRUE);
		//$this->db2 = $this->load->database('sas_db', TRUE);


		$sql = "select * from data_pegawai_riwayat_proses where ID IS NOT NULL";
		
		if($dari!=""){
			$sql .=" and TGL_MODIFIKASI >= '".$dari."'";
		}
		if($sampai!=""){
			$sql .=" and TGL_MODIFIKASI <= '".$sampai."'";
		}
		$sql .=' order by ID asc';
		
		return $this->db2->query($sql)->result();
	}
	public function find_first_row(){
		return $this->db->get($this->db->schema.".".$this->table_name)->first_row();
	}
	
	public function find_all($satker_id,$strict_in_satker = false){
		
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
		$this->db->select('pegawai."ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"',false);
		$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left',false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join("pns_aktif pa","pegawai.ID=pa.ID","LEFT");
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if($strict_in_satker){
			$this->db->where('vw."ID" is not null');
		}

		$this->db->where('pa."ID" is not null',NULL,FALSE);
		$this->db->where('pegawai."KEDUDUKAN_HUKUM_ID" <> \'99\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'66\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'52\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'20\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'04\' ');
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) ".$where_clause);
		$this->db->order_by("NAMA","ASC");
		return parent::find_all();
	}
	public function log_activity_pegawai($NIP = "",$user_id = null, $activity = '', $module = 'any')
	{
        if (is_null($user_id) || ! is_integer($user_id) || $user_id < 0) {
            $this->error = "User tidak ada";
			return false;
		}

        if (empty($activity)) {
            $this->error = "Keterangan Aktifitas harus ada";
			return false;
		}

		return $this->insert(array(
			'NIP'	=> $NIP,
			'USER'	=> $user_id,
			'TGL_MODIFIKASI'	=> date("Y-m-d"),
			'JAM_MODIFIKASI'	=> date("H:i:s"),
			'YANG_DIUBAH'	=> $activity,
			'MODULE'	=> $module
		));
	}
	public function find_dari_sampai($dari = "",$sampai ="",$satker_id = ""){
		
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
		$this->db->select('log_transaksi."ID",
			log_transaksi."NIP",
			"NAMA_KOMPUTER",
			"TGL_MODIFIKASI",
			"YANG_DIUBAH" AS "PERUBAHAN"',false);

		if($dari != ""){
			$this->db->where("TGL_MODIFIKASI >= '".$dari."'");
		}
		if($sampai != ""){
			$this->db->where("TGL_MODIFIKASI <= '".$sampai."'");
		}

		$this->db->join("pegawai","pegawai.NIP_BARU = log_transaksi.NIP","left");
		$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left',false);
		$this->db->where('pegawai."ID" is not null'.$where_clause);
		$this->db->order_by("NAMA","ASC");
		return parent::find_all();
	}
	public function count_dari_sampai($dari = "",$sampai ="",$satker_id = ""){
		
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
		$this->db->select('log_transaksi."ID",
			log_transaksi."NIP",
			"NAMA_KOMPUTER",
			"TGL_MODIFIKASI",
			"YANG_DIUBAH" AS "PERUBAHAN"',false);
		if($dari != ""){
			$this->db->where("TGL_MODIFIKASI >= '".$dari."'");
		}
		if($sampai != ""){
			$this->db->where("TGL_MODIFIKASI <= '".$sampai."'");
		}
		$this->db->join("pegawai","pegawai.NIP_BARU = log_transaksi.NIP","left");
		$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left',false);
		$this->db->where('pegawai."ID" is not null'.$where_clause);
		return parent::count_all();
	}
}
