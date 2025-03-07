<?php defined('BASEPATH') || exit('No direct script access allowed');

class Pegawai_model extends BF_Model
{
	protected $table_name	= 'pegawai';
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
			'field' => 'PNS_ID',
			'label' => 'PNS ID',
			'rules' => 'max_length[36]|required',
		),
		array(
			'field' => 'NIP_LAMA',
			'label' => 'lang:pegawai_field_NIP_LAMA',
			'rules' => 'max_length[9]',
		),
		array(
			'field' => 'NIP_BARU',
			'label' => 'lang:pegawai_field_NIP_BARU',
			'rules' => 'max_length[18]',
		),
		array(
			'field' => 'NAMA',
			'label' => 'lang:pegawai_field_NAMA',
			'rules' => 'max_length[50]',
		),
		array(
			'field' => 'GELAR_DEPAN',
			'label' => 'lang:pegawai_field_GELAR_DEPAN',
			'rules' => 'max_length[60]',
		),
		array(
			'field' => 'GELAR_BELAKANG',
			'label' => 'lang:pegawai_field_GELAR_BELAKANG',
			'rules' => 'max_length[60]',
		),
		array(
			'field' => 'TEMPAT_LAHIR_ID',
			'label' => 'lang:pegawai_field_TEMPAT_LAHIR_ID',
			'rules' => 'max_length[60]',
		),
		array(
			'field' => 'TGL_LAHIR',
			'label' => 'lang:pegawai_field_TGL_LAHIR',
			'rules' => '',
		),
		array(
			'field' => 'JENIS_KELAMIN',
			'label' => 'lang:pegawai_field_JENIS_KELAMIN',
			'rules' => 'max_length[2]',
		),
		array(
			'field' => 'AGAMA_ID',
			'label' => 'lang:pegawai_field_AGAMA_ID',
			'rules' => 'max_length[32]',
		),
		array(
			'field' => 'JENIS_KAWIN_ID',
			'label' => 'lang:pegawai_field_JENIS_KAWIN_ID',
			'rules' => 'max_length[32]',
		),
		array(
			'field' => 'NIK',
			'label' => 'lang:pegawai_field_NIK',
			'rules' => 'max_length[32]',
		),
		
		array(
			'field' => 'KK',
			'label' => 'lang:pegawai_field_KK',
			'rules' => 'max_length[32]',
		),
		array(
			'field' => 'NOMOR_DARURAT',
			'label' => 'lang:pegawai_field_NOMOR_DARURAT',
			'rules' => 'max_length[32]',
		),
		array(
			'field' => 'NOMOR_HP',
			'label' => 'lang:pegawai_field_NOMOR_HP',
			'rules' => 'max_length[32]',
		),
		array(
			'field' => 'EMAIL',
			'label' => 'lang:pegawai_field_EMAIL',
			'rules' => 'max_length[200]',
		),
		array(
			'field' => 'ALAMAT',
			'label' => 'lang:pegawai_field_ALAMAT',
			'rules' => '',
		),
		array(
			'field' => 'NPWP',
			'label' => 'lang:pegawai_field_NPWP',
			'rules' => 'max_length[25]',
		),
		array(
			'field' => 'BPJS',
			'label' => 'lang:pegawai_field_BPJS',
			'rules' => 'max_length[25]',
		),
		array(
			'field' => 'JENIS_PEGAWAI_ID',
			'label' => 'lang:pegawai_field_JENIS_PEGAWAI_ID',
			'rules' => 'max_length[50]',
		),
		array(
			'field' => 'KEDUDUKAN_HUKUM_ID',
			'label' => 'lang:pegawai_field_KEDUDUKAN_HUKUM_ID',
			'rules' => 'max_length[50]',
		),
		array(
			'field' => 'STATUS_CPNS_PNS',
			'label' => 'lang:pegawai_field_STATUS_CPNS_PNS',
			'rules' => 'max_length[11]',
		),
		array(
			'field' => 'KARTU_PEGAWAI',
			'label' => 'lang:pegawai_field_KARTU_PEGAWAI',
			'rules' => 'max_length[100]',
		),
		array(
			'field' => 'KARTU_ASN',
			'label' => 'lang:pegawai_field_KARTU_ASN',
			'rules' => 'max_length[100]',
		),
		array(
			'field' => 'NOMOR_SK_CPNS',
			'label' => 'lang:pegawai_field_NOMOR_SK_CPNS',
			'rules' => 'max_length[30]',
		),
		array(
			'field' => 'TGL_SK_CPNS',
			'label' => 'lang:pegawai_field_TGL_SK_CPNS',
			'rules' => '',
		),
		array(
			'field' => 'TMT_CPNS',
			'label' => 'lang:pegawai_field_TMT_CPNS',
			'rules' => '',
		),
		array(
			'field' => 'TMT_PNS',
			'label' => 'lang:pegawai_field_TMT_PNS',
			'rules' => '',
		),
		array(
			'field' => 'GOL_AWAL_ID',
			'label' => 'lang:pegawai_field_GOL_AWAL_ID',
			'rules' => 'max_length[11]',
		),
		array(
			'field' => 'GOL_ID',
			'label' => 'lang:pegawai_field_GOL_ID',
			'rules' => 'max_length[11]',
		),
		array(
			'field' => 'TMT_GOLONGAN',
			'label' => 'lang:pegawai_field_TMT_GOLONGAN',
			'rules' => '',
		),
		array(
			'field' => 'MK_TAHUN',
			'label' => 'lang:pegawai_field_MK_TAHUN',
			'rules' => 'max_length[4]',
		),
		array(
			'field' => 'MK_BULAN',
			'label' => 'lang:pegawai_field_MK_BULAN',
			'rules' => 'max_length[10]',
		),
		array(
			'field' => 'JENIS_JABATAN_ID',
			'label' => 'lang:pegawai_field_JENIS_JABATAN_ID',
			'rules' => 'max_length[21]',
		),
		array(
			'field' => 'JABATAN_ID',
			'label' => 'lang:pegawai_field_JABATAN_ID',
			'rules' => 'max_length[32]',
		),
		array(
			'field' => 'TMT_JABATAN',
			'label' => 'lang:pegawai_field_TMT_JABATAN',
			'rules' => '',
		),
		array(
			'field' => 'PENDIDIKAN_ID',
			'label' => 'lang:pegawai_field_PENDIDIKAN_ID',
			'rules' => 'max_length[32]',
		),
		array(
			'field' => 'TAHUN_LULUS',
			'label' => 'lang:pegawai_field_TAHUN_LULUS',
			'rules' => 'max_length[4]',
		),
		array(
			'field' => 'KPKN_ID',
			'label' => 'lang:pegawai_field_KPKN_ID',
			'rules' => 'max_length[32]',
		),
		array(
			'field' => 'LOKASI_KERJA_ID',
			'label' => 'lang:pegawai_field_LOKASI_KERJA_ID',
			'rules' => 'max_length[32]',
		),
		array(
			'field' => 'UNOR_ID',
			'label' => 'lang:pegawai_field_UNOR_ID',
			'rules' => 'max_length[36]',
		),
		array(
			'field' => 'UNOR_INDUK_ID',
			'label' => 'lang:pegawai_field_UNOR_INDUK_ID',
			'rules' => 'max_length[36]',
		),
		array(
			'field' => 'INSTANSI_INDUK_ID',
			'label' => 'lang:pegawai_field_INSTANSI_INDUK_ID',
			'rules' => 'max_length[11]',
		),
		array(
			'field' => 'INSTANSI_KERJA_ID',
			'label' => 'lang:pegawai_field_INSTANSI_KERJA_ID',
			'rules' => 'max_length[11]',
		),
		array(
			'field' => 'SATUAN_KERJA_INDUK_ID',
			'label' => 'lang:pegawai_field_SATUAN_KERJA_INDUK_ID',
			'rules' => 'max_length[11]',
		),
		array(
			'field' => 'SATUAN_KERJA_KERJA_ID',
			'label' => 'lang:pegawai_field_SATUAN_KERJA_KERJA_ID',
			'rules' => 'max_length[11]',
		),
		array(
			'field' => 'GOLONGAN_DARAH',
			'label' => 'lang:pegawai_field_GOLONGAN_DARAH',
			'rules' => 'max_length[11]',
		),
	);
	protected $insert_validation_rules  = array();
	protected $skip_validation 			= true;
	protected $STATUS_NON_ACTIVE 		= array('14','52','66','67','77','88','98','99','100');

	public function find_first_row()
	{
		return $this->db->get($this->db->schema . "." . $this->table_name)->first_row();
	}

	public function count_all_ppnpn($satker_id = "")
	{

		$where_clause = '';
		if ($satker_id) {
			$where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
		}
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		// $this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		// $this->db->where('pa."ID" is not null', NULL, FALSE);
		 // $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);

		$this->db->where("status_pegawai = 3 " . $where_clause);

		return parent::count_all();
	}
	public function count_list($satker_id, $strict_in_satker = false)
	{
		$this->db->from('hris.pegawai pegawai');
		$where_clause = '';
		if ($satker_id) {
			if (is_array($satker_id) && sizeof($satker_id) > 0) {
				$where_clause = 'AND ( 1=2 ';
				foreach ($satker_id as $u_id) {
					$where_clause .= ' OR vw."ESELON_1" = \'' . $u_id . '\' OR vw."ESELON_2" = \'' . $u_id . '\' OR vw."ESELON_3" = \'' . $u_id . '\' OR vw."ESELON_4" = \'' . $u_id . '\' ';
				}
				$where_clause .= ')';
			} else $where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
		}
		$this->db->select('pegawai."ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL"', false);
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}

		$this->db->where('pa."ID" is not null', NULL, FALSE);
		 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);

		$this->db->order_by("NAMA", "ASC");
		//return parent::find_all();
		return $this->db->get()->num_rows();
	}
	public function find_all($satker_id = null, $strict_in_satker = false, $status_aktif = "")
	{
		$where_clause = '';
		if ($satker_id) {
			if (is_array($satker_id) && sizeof($satker_id) > 0) {
				$where_clause = 'AND ( 1=2 ';
				foreach ($satker_id as $u_id) {
					$where_clause .= ' OR vw."ESELON_1" = \'' . $u_id . '\' OR vw."ESELON_2" = \'' . $u_id . '\' OR vw."ESELON_3" = \'' . $u_id . '\' OR vw."ESELON_4" = \'' . $u_id . '\' OR "UNOR_INDUK_ID" = \'' . $u_id . '\' ';
				}
				$where_clause .= ')';
			} else $where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\' OR "UNOR_INDUK_ID" = \'' . $satker_id . '\')';
		}
		$this->db->select('pegawai."ID",pegawai."PNS_ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",
			golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","GOL_PPPK","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN","PHOTO","KEDUDUKAN_HUKUM_ID"', false);
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}

		$this->db->where('pa."ID" is not null', NULL, FALSE);
		if ($status_aktif == "") {
			 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		} else {
			$this->db->where('pegawai."KEDUDUKAN_HUKUM_ID"', $status_aktif);
		}

		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);
		$this->db->order_by("NAMA", "ASC");

		//var_dump($this->db);
		//$this->db->last_query();
		return parent::find_all();
	}
	public function find_all_detil($satker_id, $strict_in_satker = false)
	{
		$where_clause = '';
		if ($satker_id) {
			if (is_array($satker_id) && sizeof($satker_id) > 0) {
				$where_clause = 'AND ( 1=2 ';
				foreach ($satker_id as $u_id) {
					$where_clause .= ' OR vw."ESELON_1" = \'' . $u_id . '\' OR vw."ESELON_2" = \'' . $u_id . '\' OR vw."ESELON_3" = \'' . $u_id . '\' OR vw."ESELON_4" = \'' . $u_id . '\' ';
				}
				$where_clause .= ')';
			} else $where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
		}
		$this->db->select('TRIM(pegawai."PNS_ID") "PNS_ID",
			TRIM(pegawai."NIP_LAMA") "NIP_LAMA",
			TRIM(pegawai."NIP_BARU") "NIP_BARU",
			TRIM(pegawai."NAMA") "NAMA",
			TRIM(pegawai."GELAR_DEPAN") "GELAR_DEPAN",
			TRIM(pegawai."GELAR_BELAKANG") "GELAR_BELAKANG",
			TRIM(pegawai."TEMPAT_LAHIR_ID") "TEMPAT_LAHIR_ID",
			pegawai."TGL_LAHIR" "TGL_LAHIR",
			TRIM(pegawai."JENIS_KELAMIN") "JENIS_KELAMIN",
			pegawai."AGAMA_ID" "AGAMA_ID",
			TRIM(pegawai."JENIS_KAWIN_ID") "JENIS_KAWIN_ID",
			TRIM(pegawai."NIK") "NIK",
			TRIM(pegawai."NOMOR_DARURAT") "NOMOR_DARURAT",
			TRIM(pegawai."NOMOR_HP") "NOMOR_HP",
			TRIM(pegawai."EMAIL") "EMAIL",
			TRIM(pegawai."ALAMAT") "ALAMAT",
			TRIM(pegawai."NPWP") "NPWP",
			TRIM(pegawai."BPJS") "BPJS",
			TRIM(pegawai."JENIS_PEGAWAI_ID") "JENIS_PEGAWAI_ID",
			TRIM(pegawai."KEDUDUKAN_HUKUM_ID") "KEDUDUKAN_HUKUM_ID",
			TRIM(pegawai."STATUS_CPNS_PNS") "STATUS_CPNS_PNS",
			TRIM(pegawai."KARTU_PEGAWAI") "KARTU_PEGAWAI",
			TRIM(pegawai."NOMOR_SK_CPNS") "NOMOR_SK_CPNS",
			pegawai."TGL_SK_CPNS" "TGL_SK_CPNS",
			pegawai."TMT_CPNS" "TMT_CPNS",
			pegawai."TMT_PNS" "TMT_PNS",
			TRIM(pegawai."GOL_AWAL_ID") "GOL_AWAL_ID",
			pegawai."GOL_ID" "GOL_ID",
			pegawai."TMT_GOLONGAN" "TMT_GOLONGAN",
			TRIM(pegawai."MK_TAHUN") "MK_TAHUN",
			TRIM(pegawai."MK_BULAN") "MK_BULAN",
			TRIM(pegawai."JENIS_JABATAN_IDx") "JENIS_JABATAN_IDx",
			TRIM(pegawai."JABATAN_ID") "JABATAN_ID",
			pegawai."TMT_JABATAN" "TMT_JABATAN",
			TRIM(pegawai."PENDIDIKAN_ID") "PENDIDIKAN_ID",
			TRIM(pegawai."TAHUN_LULUS") "TAHUN_LULUS",
			TRIM(pegawai."KPKN_ID") "KPKN_ID",
			TRIM(pegawai."LOKASI_KERJA_ID") "LOKASI_KERJA_ID",
			TRIM(pegawai."UNOR_ID") "UNOR_ID",
			TRIM(pegawai."UNOR_INDUK_ID") "UNOR_INDUK_ID",
			TRIM(pegawai."INSTANSI_INDUK_ID") "INSTANSI_INDUK_ID",
			TRIM(pegawai."INSTANSI_KERJA_ID") "INSTANSI_KERJA_ID",
			TRIM(pegawai."SATUAN_KERJA_INDUK_ID") "SATUAN_KERJA_INDUK_ID",
			TRIM(pegawai."SATUAN_KERJA_KERJA_ID") "SATUAN_KERJA_KERJA_ID",
			TRIM(pegawai."GOLONGAN_DARAH") "GOLONGAN_DARAH",
			TRIM(pegawai."PHOTO") "PHOTO",
			pegawai."TMT_PENSIUN" "TMT_PENSIUN",
			TRIM(pegawai."LOKASI_KERJA") "LOKASI_KERJA",
			TRIM(pegawai."JML_ISTRI") "JML_ISTRI",
			TRIM(pegawai."JML_ANAK") "JML_ANAK",
			TRIM(pegawai."NO_SURAT_DOKTER") "NO_SURAT_DOKTER",
			pegawai."TGL_SURAT_DOKTER" "TGL_SURAT_DOKTER",
			TRIM(pegawai."NO_BEBAS_NARKOBA") "NO_BEBAS_NARKOBA",
			pegawai."TGL_BEBAS_NARKOBA" "TGL_BEBAS_NARKOBA",
			TRIM(pegawai."NO_CATATAN_POLISI") "NO_CATATAN_POLISI",
			pegawai."TGL_CATATAN_POLISI" "TGL_CATATAN_POLISI",
			TRIM(pegawai."AKTE_KELAHIRAN") "AKTE_KELAHIRAN",
			TRIM(pegawai."STATUS_HIDUP") "STATUS_HIDUP",
			TRIM(pegawai."AKTE_MENINGGAL") "AKTE_MENINGGAL",
			pegawai."TGL_MENINGGAL" "TGL_MENINGGAL",
			TRIM(pegawai."NO_ASKES") "NO_ASKES",
			TRIM(pegawai."NO_TASPEN") "NO_TASPEN",
			pegawai."TGL_NPWP" "TGL_NPWP",
			TRIM(pegawai."TEMPAT_LAHIR") "TEMPAT_LAHIR",
			TRIM(pegawai."PENDIDIKAN") "PENDIDIKAN",
			TRIM(pegawai."TK_PENDIDIKAN") "TK_PENDIDIKAN",
			TRIM(pegawai."TEMPAT_LAHIR_NAMA") "TEMPAT_LAHIR_NAMA",
			TRIM(pegawai."JENIS_JABATAN_NAMA") "JENIS_JABATAN_NAMA",
			TRIM(pegawai."JABATAN_NAMA") "JABATAN_NAMA",
			TRIM(pegawai."KPKN_NAMA") "KPKN_NAMA",
			TRIM(pegawai."INSTANSI_INDUK_NAMA") "INSTANSI_INDUK_NAMA",
			TRIM(pegawai."INSTANSI_KERJA_NAMA") "INSTANSI_KERJA_NAMA",
			TRIM(pegawai."SATUAN_KERJA_INDUK_NAMA") "SATUAN_KERJA_INDUK_NAMA",
			TRIM(pegawai."SATUAN_KERJA_NAMA") "SATUAN_KERJA_NAMA",
			TRIM(pegawai."JABATAN_INSTANSI_ID") "JABATAN_INSTANSI_ID",
			TRIM(pegawai."JABATAN_INSTANSI_NAMA") "JABATAN_INSTANSI_NAMA",
			pegawai."JENIS_JABATAN_ID" "JENIS_JABATAN_ID",
			pegawai."terminated_date" "terminated_date",
			pegawai."status_pegawai" "status_pegawai",
			TRIM(pegawai."JABATAN_PPNPN") "JABATAN_PPNPN",
			TRIM(pegawai."JABATAN_INSTANSI_REAL_ID") "JABATAN_INSTANSI_REAL_ID",
			pegawai."CREATED_DATE" "CREATED_DATE",
			pegawai."CREATED_BY" "CREATED_BY",
			pegawai."UPDATED_DATE" "UPDATED_DATE",
			pegawai."UPDATED_BY" "UPDATED_BY",
			TRIM(pegawai."EMAIL_DIKBUD") "EMAIL_DIKBUD",
			TRIM(pegawai."KODECEPAT") "KODECEPAT",
			lokasi."NAMA" as "TEMPAT_LAHIR_NAMA",
			pendidikan."NAMA" AS "NAMA_PENDIDIKAN",
			kedudukan_hukum."NAMA" AS "KEDUDUKAN_HUKUM_NAMA",
			,vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"', false);
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->join('kedudukan_hukum', 'kedudukan_hukum.ID = pegawai.KEDUDUKAN_HUKUM_ID', 'left');
		$this->db->join('lokasi', 'lokasi.ID = pegawai.TEMPAT_LAHIR_ID', 'left');
		$this->db->join('pendidikan', 'pendidikan.ID = pegawai.PENDIDIKAN_ID', 'left');
		$this->db->where('pa."ID" is not null', NULL, FALSE);
		 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);
		$this->db->order_by("NAMA", "ASC");
		return parent::find_all();
	}
	public function find_pensiun_all($satker_id, $strict_in_satker = false)
	{

		$where_clause = '';
		if ($satker_id) {
			if (is_array($satker_id) && sizeof($satker_id) > 0) {
				$where_clause = 'AND ( 1=2 ';
				foreach ($satker_id as $u_id) {
					$where_clause .= ' OR vw."ESELON_1" = \'' . $u_id . '\' OR vw."ESELON_2" = \'' . $u_id . '\' OR vw."ESELON_3" = \'' . $u_id . '\' OR vw."ESELON_4" = \'' . $u_id . '\' ';
				}
				$where_clause .= ')';
			} else $where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
		}
		$this->db->select('pegawai."ID",pegawai."PNS_ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",
			golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"', false);
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		$this->db->where('pegawai."ID" is not null ' . $where_clause);
		$this->db->where("(\"pegawai\".\"KEDUDUKAN_HUKUM_ID\" = '99' or \"pegawai\".\"status_pegawai\" = '3')");
		//$this->db->where("(TMT_PENSIUN IS NOT NULL)");
		$this->db->order_by("NAMA", "ASC");
		return parent::find_all();
	}

	public function count_all($satker_id = "", $strict_in_satker = false, $status_aktif = "")
	{

		//$this->db->_compile_select(); 
		$where_clause = '';
		if ($satker_id) {
			if (is_array($satker_id) && sizeof($satker_id) > 0) {
				$where_clause = 'AND ( 1=2 ';
				foreach ($satker_id as $u_id) {
					$where_clause .= ' OR vw."ESELON_1" = \'' . $u_id . '\' OR vw."ESELON_2" = \'' . $u_id . '\' OR vw."ESELON_3" = \'' . $u_id . '\' OR vw."ESELON_4" = \'' . $u_id . '\' OR "UNOR_INDUK_ID" = \'' . $u_id . '\' ';
				}
				$where_clause .= ')';
			} else $where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\' OR "UNOR_INDUK_ID" = \'' . $satker_id . '\')';
		}
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		$this->db->where('pa."ID" is not null', NULL, FALSE);
		if ($status_aktif == "") {
			 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		} else {
			$this->db->where('pegawai."KEDUDUKAN_HUKUM_ID"', $status_aktif);
		}
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);
		//$this->db->last_query(); 
		//var_dump( $this->db );
		return parent::count_all();
	}
	public function count_pensiun_all($satker_id = "", $strict_in_satker = false)
	{

		$where_clause = '';
		if ($satker_id) {
			$where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
		}
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		$this->db->where('pegawai."ID" is not null ' . $where_clause);

		$this->db->where("(\"pegawai\".\"KEDUDUKAN_HUKUM_ID\" = '99' or \"pegawai\".\"status_pegawai\" = '3')");

		return parent::count_all();
	}
	public function find_all_api($satker_id, $strict_in_satker = false)
	{

		$where_clause = '';
		if ($satker_id) {
			if (is_array($satker_id) && sizeof($satker_id) > 0) {
				$where_clause = 'AND ( 1=2 ';
				foreach ($satker_id as $u_id) {
					$where_clause .= ' OR vw."ESELON_1" = \'' . $u_id . '\' OR vw."ESELON_2" = \'' . $u_id . '\' OR vw."ESELON_3" = \'' . $u_id . '\' OR vw."ESELON_4" = \'' . $u_id . '\' ';
				}
				$where_clause .= ')';
			} else $where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
		}
		$this->db->select('
			TRIM(pegawai."PNS_ID") "PNS_ID",
			TRIM(pegawai."NIP_LAMA") "NIP_LAMA",
			TRIM(pegawai."NIP_BARU") "NIP_BARU",
			TRIM(pegawai."NAMA") "NAMA",
			TRIM(pegawai."GELAR_DEPAN") "GELAR_DEPAN",
			TRIM(pegawai."GELAR_BELAKANG") "GELAR_BELAKANG",
			TRIM(pegawai."TEMPAT_LAHIR_ID") "TEMPAT_LAHIR_ID",
			pegawai."TGL_LAHIR" "TGL_LAHIR",
			TRIM(pegawai."JENIS_KELAMIN") "JENIS_KELAMIN",
			pegawai."AGAMA_ID" "AGAMA_ID",
			TRIM(pegawai."JENIS_KAWIN_ID") "JENIS_KAWIN_ID",
			TRIM(pegawai."NIK") "NIK",
			TRIM(pegawai."NOMOR_DARURAT") "NOMOR_DARURAT",
			TRIM(pegawai."NOMOR_HP") "NOMOR_HP",
			TRIM(pegawai."EMAIL") "EMAIL",
			TRIM(pegawai."ALAMAT") "ALAMAT",
			TRIM(pegawai."NPWP") "NPWP",
			TRIM(pegawai."BPJS") "BPJS",
			TRIM(pegawai."JENIS_PEGAWAI_ID") "JENIS_PEGAWAI_ID",
			TRIM(pegawai."KEDUDUKAN_HUKUM_ID") "KEDUDUKAN_HUKUM_ID",
			TRIM(pegawai."STATUS_CPNS_PNS") "STATUS_CPNS_PNS",
			TRIM(pegawai."KARTU_PEGAWAI") "KARTU_PEGAWAI",
			TRIM(pegawai."NOMOR_SK_CPNS") "NOMOR_SK_CPNS",
			pegawai."TGL_SK_CPNS" "TGL_SK_CPNS",
			pegawai."TMT_CPNS" "TMT_CPNS",
			pegawai."TMT_PNS" "TMT_PNS",
			TRIM(pegawai."GOL_AWAL_ID") "GOL_AWAL_ID",
			pegawai."GOL_ID" "GOL_ID",
			pegawai."TMT_GOLONGAN" "TMT_GOLONGAN",
			TRIM(pegawai."MK_TAHUN") "MK_TAHUN",
			TRIM(pegawai."MK_BULAN") "MK_BULAN",
			TRIM(pegawai."JENIS_JABATAN_IDx") "JENIS_JABATAN_IDx",
			TRIM(pegawai."JABATAN_ID") "JABATAN_ID",
			pegawai."TMT_JABATAN" "TMT_JABATAN",
			TRIM(pegawai."PENDIDIKAN_ID") "PENDIDIKAN_ID",
			TRIM(pegawai."TAHUN_LULUS") "TAHUN_LULUS",
			TRIM(pegawai."KPKN_ID") "KPKN_ID",
			TRIM(pegawai."LOKASI_KERJA_ID") "LOKASI_KERJA_ID",
			TRIM(pegawai."UNOR_ID") "UNOR_ID",
			TRIM(pegawai."UNOR_INDUK_ID") "UNOR_INDUK_ID",
			TRIM(pegawai."INSTANSI_INDUK_ID") "INSTANSI_INDUK_ID",
			TRIM(pegawai."INSTANSI_KERJA_ID") "INSTANSI_KERJA_ID",
			TRIM(pegawai."SATUAN_KERJA_INDUK_ID") "SATUAN_KERJA_INDUK_ID",
			TRIM(pegawai."SATUAN_KERJA_KERJA_ID") "SATUAN_KERJA_KERJA_ID",
			TRIM(pegawai."GOLONGAN_DARAH") "GOLONGAN_DARAH",
			TRIM(pegawai."PHOTO") "PHOTO",
			pegawai."TMT_PENSIUN" "TMT_PENSIUN",
			TRIM(pegawai."LOKASI_KERJA") "LOKASI_KERJA",
			TRIM(pegawai."JML_ISTRI") "JML_ISTRI",
			TRIM(pegawai."JML_ANAK") "JML_ANAK",
			TRIM(pegawai."NO_SURAT_DOKTER") "NO_SURAT_DOKTER",
			pegawai."TGL_SURAT_DOKTER" "TGL_SURAT_DOKTER",
			TRIM(pegawai."NO_BEBAS_NARKOBA") "NO_BEBAS_NARKOBA",
			pegawai."TGL_BEBAS_NARKOBA" "TGL_BEBAS_NARKOBA",
			TRIM(pegawai."NO_CATATAN_POLISI") "NO_CATATAN_POLISI",
			pegawai."TGL_CATATAN_POLISI" "TGL_CATATAN_POLISI",
			TRIM(pegawai."AKTE_KELAHIRAN") "AKTE_KELAHIRAN",
			TRIM(pegawai."STATUS_HIDUP") "STATUS_HIDUP",
			TRIM(pegawai."AKTE_MENINGGAL") "AKTE_MENINGGAL",
			pegawai."TGL_MENINGGAL" "TGL_MENINGGAL",
			TRIM(pegawai."NO_ASKES") "NO_ASKES",
			TRIM(pegawai."NO_TASPEN") "NO_TASPEN",
			pegawai."TGL_NPWP" "TGL_NPWP",
			TRIM(pegawai."TEMPAT_LAHIR") "TEMPAT_LAHIR",
			TRIM(pegawai."PENDIDIKAN") "PENDIDIKAN",
			TRIM(pegawai."TK_PENDIDIKAN") "TK_PENDIDIKAN",
			TRIM(pegawai."TEMPAT_LAHIR_NAMA") "TEMPAT_LAHIR_NAMA",
			
			TRIM(pegawai."JENIS_JABATAN_NAMA") "JENIS_JABATAN_NAMA",
			TRIM(pegawai."JABATAN_NAMA") "JABATAN_NAMA",
			
			TRIM(pegawai."KPKN_NAMA") "KPKN_NAMA",
			TRIM(pegawai."INSTANSI_INDUK_NAMA") "INSTANSI_INDUK_NAMA",
			TRIM(pegawai."INSTANSI_KERJA_NAMA") "INSTANSI_KERJA_NAMA",
			TRIM(pegawai."SATUAN_KERJA_INDUK_NAMA") "SATUAN_KERJA_INDUK_NAMA",
			TRIM(pegawai."SATUAN_KERJA_NAMA") "SATUAN_KERJA_NAMA",
			
			TRIM(pegawai."JABATAN_INSTANSI_ID") "JABATAN_INSTANSI_ID",
			TRIM(pegawai."JABATAN_INSTANSI_NAMA") "JABATAN_INSTANSI_NAMA",
			pegawai."JENIS_JABATAN_ID" "JENIS_JABATAN_ID",
			
			pegawai."terminated_date" "terminated_date",
			pegawai."status_pegawai" "status_pegawai",
			TRIM(pegawai."JABATAN_PPNPN") "JABATAN_PPNPN",
			
			TRIM(jr."NAMA_JABATAN") "NAMA_JABATAN_REAL",
			TRIM(jr."KATEGORI_JABATAN") "KATEGORI_JABATAN_REAL",
			jr."JENIS_JABATAN" "JENIS_JABATAN_REAL",

			pegawai."CREATED_DATE" "CREATED_DATE",
			pegawai."CREATED_BY" "CREATED_BY",
			pegawai."UPDATED_DATE" "UPDATED_DATE",
			pegawai."UPDATED_BY" "UPDATED_BY",
			TRIM(pegawai."EMAIL_DIKBUD") "EMAIL_DIKBUD",
			TRIM(pegawai."KODECEPAT") "KODECEPAT"
			,vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1"', false);
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		$this->db->join('jabatan jr', 'pegawai.JABATAN_INSTANSI_REAL_ID = jr.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}

		$this->db->where('pa."ID" is not null', NULL, FALSE);
		 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);
		$this->db->order_by("pegawai.NAMA", "ASC");
		return parent::find_all();
	}
	public function find_downloadXX($satker_id, $strict_in_satker = false)
	{

		$where_clause = '';
		if ($satker_id) {
			if (is_array($satker_id) && sizeof($satker_id) > 0) {
				$where_clause = 'AND ( 1=2 ';
				foreach ($satker_id as $u_id) {
					$where_clause .= ' OR vw."ESELON_1" = \'' . $u_id . '\' OR vw."ESELON_2" = \'' . $u_id . '\' OR vw."ESELON_3" = \'' . $u_id . '\' OR vw."ESELON_4" = \'' . $u_id . '\' ';
				}
				$where_clause .= ')';
			} else $where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
		}
		$this->db->select('pegawai.*,vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1",
			lokasi."NAMA" as "TEMPAT_LAHIR_NAMA","TEMPAT_LAHIR_ID",
			pendidikan."NAMA" AS "NAMA_PENDIDIKAN",
			kedudukan_hukum."NAMA" AS "KEDUDUKAN_HUKUM_NAMA",
			ref_jabatan."NAMA_JABATAN" AS "NAMA_JABATAN","KELAS","JENIS_JABATAN",
			agama."NAMA" AS "NAMA_AGAMA"', false);
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('lokasi', 'lokasi.ID = pegawai.TEMPAT_LAHIR_ID', 'left');
		$this->db->join('pendidikan', 'pendidikan.ID = pegawai.PENDIDIKAN_ID', 'left');
		$this->db->join('agama', 'agama.ID = pegawai.AGAMA_ID', 'left');
		$this->db->join('kedudukan_hukum', 'kedudukan_hukum.ID = pegawai.KEDUDUKAN_HUKUM_ID', 'left');
		$this->db->join('ref_jabatan', 'pegawai.JABATAN_INSTANSI_ID = CAST ("ref_jabatan"."ID_JABATAN" AS TEXT) ', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);
		$this->db->order_by("NAMA", "ASC");
		return parent::find_all();
	}

	public function find_download($satker_id, $strict_in_satker = false)
	{

		$where_clause = '';
		if ($satker_id) {
			if (is_array($satker_id) && sizeof($satker_id) > 0) {
				$where_clause = 'AND ( 1=2 ';
				foreach ($satker_id as $u_id) {
					$where_clause .= ' OR vw."ESELON_1" = \'' . $u_id . '\' OR vw."ESELON_2" = \'' . $u_id . '\' OR vw."ESELON_3" = \'' . $u_id . '\' OR vw."ESELON_4" = \'' . $u_id . '\' ';
				}
				$where_clause .= ')';
			} else $where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
		}
		$this->db->select('pegawai.*,
			jabatan."PENSIUN",
			date("TGL_LAHIR"::DATE + interval  \'1 YEAR\' * "PENSIUN") AS estimasi_pensiun,
			EXTRACT(YEAR from AGE("TGL_LAHIR")) as "age",
			vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT",
			vw."JENIS_SATKER",vw."ESELON_ID",
			"NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1",
			lokasi."NAMA" as "TEMPAT_LAHIR_NAMA","TEMPAT_LAHIR_ID",
			pendidikan."NAMA" AS "NAMA_PENDIDIKAN",
			kedudukan_hukum."NAMA" AS "KEDUDUKAN_HUKUM_NAMA",
			jabatan."NAMA_JABATAN" AS "NAMA_JABATAN","KELAS",jenis_jabatan."NAMA" AS "JENIS_JABATAN",
			uk."NAMA_UNOR" AS nama_satker,
			"NOMOR_SK_CPNS","TGL_SK_CPNS",
			agama."NAMA" AS "NAMA_AGAMA"', false);
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join("pns_aktif as pa", 'pegawai.ID=pa.ID', 'left');
		$this->db->join("unitkerja as uk", 'uk.ID=vw.UNOR_INDUK', 'left');
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('lokasi', 'lokasi.ID = pegawai.TEMPAT_LAHIR_ID', 'left');
		$this->db->join('pendidikan', 'pendidikan.ID = pegawai.PENDIDIKAN_ID', 'left');
		$this->db->join('agama', 'agama.ID = pegawai.AGAMA_ID', 'left');
		$this->db->join('kedudukan_hukum', 'kedudukan_hukum.ID = pegawai.KEDUDUKAN_HUKUM_ID', 'left');
		//$this->db->join('ref_jabatan', 'pegawai.JABATAN_INSTANSI_ID = CAST ("ref_jabatan"."ID_JABATAN" AS TEXT) ', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_REAL_ID = jabatan.KODE_JABATAN', 'left');
		$this->db->join('jenis_jabatan', 'jenis_jabatan.ID = jabatan.JENIS_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where('pa."ID" is not null');
		 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		// $this->db->where('pegawai."KEDUDUKAN_HUKUM_ID" <> \'99\'');
		// $this->db->where('pegawai."KEDUDUKAN_HUKUM_ID" <> \'66\'');
		// $this->db->where('pegawai."KEDUDUKAN_HUKUM_ID" <> \'52\'');
		// $this->db->where('pegawai."KEDUDUKAN_HUKUM_ID" <> \'20\'');
		// $this->db->where('pegawai."KEDUDUKAN_HUKUM_ID" <> \'04\'');
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);
		$this->db->order_by("NAMA", "ASC");
		return parent::find_all();
	}
	public function count_download($satker_id, $strict_in_satker = false)
	{
		$where_clause = '';
		if ($satker_id) {
			if (is_array($satker_id) && sizeof($satker_id) > 0) {
				$where_clause = 'AND ( 1=2 ';
				foreach ($satker_id as $u_id) {
					$where_clause .= ' OR vw."ESELON_1" = \'' . $u_id . '\' OR vw."ESELON_2" = \'' . $u_id . '\' OR vw."ESELON_3" = \'' . $u_id . '\' OR vw."ESELON_4" = \'' . $u_id . '\' ';
				}
				$where_clause .= ')';
			} else $where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
		}
		$this->db->select('pegawai.*,
			jabatan."PENSIUN",
			date("TGL_LAHIR"::DATE + interval  \'1 YEAR\' * "PENSIUN") AS estimasi_pensiun,
			EXTRACT(YEAR from AGE("TGL_LAHIR")) as "age",
			vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT",
			vw."JENIS_SATKER",vw."ESELON_ID",
			"NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1",
			lokasi."NAMA" as "TEMPAT_LAHIR_NAMA","TEMPAT_LAHIR_ID",
			pendidikan."NAMA" AS "NAMA_PENDIDIKAN",
			kedudukan_hukum."NAMA" AS "KEDUDUKAN_HUKUM_NAMA",
			jabatan."NAMA_JABATAN" AS "NAMA_JABATAN","KELAS",jenis_jabatan."NAMA" AS "JENIS_JABATAN",
			uk."NAMA_UNOR" AS nama_satker,
			"NOMOR_SK_CPNS","TGL_SK_CPNS",
			agama."NAMA" AS "NAMA_AGAMA"', false);
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\"", 'left', false);
		$this->db->join("pns_aktif as pa", 'pegawai.ID=pa.ID', 'left');
		$this->db->join("unitkerja as uk", 'uk.ID=vw.UNOR_INDUK', 'left');
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('lokasi', 'lokasi.ID = pegawai.TEMPAT_LAHIR_ID', 'left');
		$this->db->join('pendidikan', 'pendidikan.ID = pegawai.PENDIDIKAN_ID', 'left');
		$this->db->join('agama', 'agama.ID = pegawai.AGAMA_ID', 'left');
		$this->db->join('kedudukan_hukum', 'kedudukan_hukum.ID = pegawai.KEDUDUKAN_HUKUM_ID', 'left');
		//$this->db->join('ref_jabatan', 'pegawai.JABATAN_INSTANSI_ID = CAST ("ref_jabatan"."ID_JABATAN" AS TEXT) ', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_REAL_ID = jabatan.KODE_JABATAN', 'left');
		$this->db->join('jenis_jabatan', 'jenis_jabatan.ID = jabatan.JENIS_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where('pa."ID" is not null');
		$this->db->where('pegawai."KEDUDUKAN_HUKUM_ID" NOT IN (\'14\',\'52\',\'66\',\'67\',\'77\',\'88\',\'98\',\'99\',\'100\') '.$where_clause .'');
		return parent::count_all();
	}

	public function find_all_ppnpn($satker_id, $strict_in_satker = false)
	{

		$where_clause = '';
		if ($satker_id) {
			if (is_array($satker_id) && sizeof($satker_id) > 0) {
				$where_clause = 'AND ( 1=2 ';
				foreach ($satker_id as $u_id) {
					$where_clause .= ' OR vw."ESELON_1" = \'' . $u_id . '\' OR vw."ESELON_2" = \'' . $u_id . '\' OR vw."ESELON_3" = \'' . $u_id . '\' OR vw."ESELON_4" = \'' . $u_id . '\' ';
				}
				$where_clause .= ')';
			} else $where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
		}
		$this->db->select('pegawai.*,vw."NAMA_UNOR_FULL"', false);
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where("status_pegawai = 3 " . $where_clause);
		$this->db->order_by("NAMA", "ASC");
		return parent::find_all();
	}

	public function find_kelompokjabatan()
	{
		$this->db->select('pegawai."ID",pegawai."NAMA","NIP_BARU",golongan."NAMA"  as "NAMA_GOLONGAN"', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->order_by("pegawai.GOL_ID", "DESC");
		return parent::find_all();
	}
	public function count_kelompokjabatan()
	{
		$this->db->select('pegawai."ID",pegawai."NAMA","NIP_BARU",golongan."NAMA"  as "NAMA_GOLONGAN"', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		return parent::count_all();
	}
	public function get_drh($id)
	{
		return $this->db->from('vw_drh')->where('PNS_ID', $id)->get()->first_row();
	}
	public function find_detil($ID = "")
	{

		if (empty($this->selects)) {
			$this->select('
			pegawai."ID",
			TRIM(pegawai."PNS_ID") "PNS_ID",
			TRIM(pegawai."NIP_LAMA") "NIP_LAMA",
			TRIM(pegawai."NIP_BARU") "NIP_BARU",
			TRIM(pegawai."NAMA") "NAMA",
			TRIM(pegawai."GELAR_DEPAN") "GELAR_DEPAN",
			TRIM(pegawai."GELAR_BELAKANG") "GELAR_BELAKANG",
			TRIM(pegawai."TEMPAT_LAHIR_ID") "TEMPAT_LAHIR_ID",
			pegawai."TGL_LAHIR" "TGL_LAHIR",
			TRIM(pegawai."JENIS_KELAMIN") "JENIS_KELAMIN",
			pegawai."AGAMA_ID" "AGAMA_ID",
			TRIM(pegawai."JENIS_KAWIN_ID") "JENIS_KAWIN_ID",
			TRIM(pegawai."NIK") "NIK",
			TRIM(pegawai."KK") "KK",
			TRIM(pegawai."NOMOR_DARURAT") "NOMOR_DARURAT",
			TRIM(pegawai."NOMOR_HP") "NOMOR_HP",
			TRIM(pegawai."EMAIL") "EMAIL",
			TRIM(pegawai."ALAMAT") "ALAMAT",
			TRIM(pegawai."NPWP") "NPWP",
			TRIM(pegawai."BPJS") "BPJS",
			TRIM(pegawai."JENIS_PEGAWAI_ID") "JENIS_PEGAWAI_ID",
			TRIM(pegawai."KEDUDUKAN_HUKUM_ID") "KEDUDUKAN_HUKUM_ID",
			TRIM(pegawai."STATUS_CPNS_PNS") "STATUS_CPNS_PNS",
			TRIM(pegawai."KARTU_PEGAWAI") "KARTU_PEGAWAI",
			TRIM(pegawai."NOMOR_SK_CPNS") "NOMOR_SK_CPNS",
			pegawai."TGL_SK_CPNS" "TGL_SK_CPNS",
			pegawai."TMT_CPNS" "TMT_CPNS",
			pegawai."TMT_PNS" "TMT_PNS",
			TRIM(pegawai."GOL_AWAL_ID") "GOL_AWAL_ID",
			pegawai."GOL_ID" "GOL_ID",
			pegawai."TMT_GOLONGAN" "TMT_GOLONGAN",
			TRIM(pegawai."MK_TAHUN") "MK_TAHUN",
			TRIM(pegawai."MK_BULAN") "MK_BULAN",
			TRIM(pegawai."JENIS_JABATAN_IDx") "JENIS_JABATAN_IDx",
			TRIM(pegawai."JABATAN_ID") "JABATAN_ID",
			pegawai."TMT_JABATAN" "TMT_JABATAN",
			TRIM(pegawai."PENDIDIKAN_ID") "PENDIDIKAN_ID",
			TRIM(pegawai."TAHUN_LULUS") "TAHUN_LULUS",
			TRIM(pegawai."KPKN_ID") "KPKN_ID",
			TRIM(pegawai."LOKASI_KERJA_ID") "LOKASI_KERJA_ID",
			TRIM(pegawai."UNOR_ID") "UNOR_ID",
			TRIM(pegawai."UNOR_INDUK_ID") "UNOR_INDUK_ID",
			TRIM(pegawai."INSTANSI_INDUK_ID") "INSTANSI_INDUK_ID",
			TRIM(pegawai."INSTANSI_KERJA_ID") "INSTANSI_KERJA_ID",
			TRIM(pegawai."SATUAN_KERJA_INDUK_ID") "SATUAN_KERJA_INDUK_ID",
			TRIM(pegawai."SATUAN_KERJA_KERJA_ID") "SATUAN_KERJA_KERJA_ID",
			TRIM(pegawai."GOLONGAN_DARAH") "GOLONGAN_DARAH",
			TRIM(pegawai."PHOTO") "PHOTO",
			pegawai."TMT_PENSIUN" "TMT_PENSIUN",
			TRIM(pegawai."LOKASI_KERJA") "LOKASI_KERJA",
			TRIM(pegawai."JML_ISTRI") "JML_ISTRI",
			TRIM(pegawai."JML_ANAK") "JML_ANAK",
			TRIM(pegawai."NO_SURAT_DOKTER") "NO_SURAT_DOKTER",
			pegawai."TGL_SURAT_DOKTER" "TGL_SURAT_DOKTER",
			TRIM(pegawai."NO_BEBAS_NARKOBA") "NO_BEBAS_NARKOBA",
			pegawai."TGL_BEBAS_NARKOBA" "TGL_BEBAS_NARKOBA",
			TRIM(pegawai."NO_CATATAN_POLISI") "NO_CATATAN_POLISI",
			pegawai."TGL_CATATAN_POLISI" "TGL_CATATAN_POLISI",
			TRIM(pegawai."AKTE_KELAHIRAN") "AKTE_KELAHIRAN",
			TRIM(pegawai."STATUS_HIDUP") "STATUS_HIDUP",
			TRIM(pegawai."AKTE_MENINGGAL") "AKTE_MENINGGAL",
			pegawai."TGL_MENINGGAL" "TGL_MENINGGAL",
			TRIM(pegawai."NO_ASKES") "NO_ASKES",
			TRIM(pegawai."NO_TASPEN") "NO_TASPEN",
			pegawai."TGL_NPWP" "TGL_NPWP",
			TRIM(pegawai."TEMPAT_LAHIR") "TEMPAT_LAHIR",
			TRIM(pegawai."PENDIDIKAN") "PENDIDIKAN",
			TRIM(pegawai."TK_PENDIDIKAN") "TK_PENDIDIKAN",
			TRIM(pegawai."TEMPAT_LAHIR_NAMA") "TEMPAT_LAHIR_NAMA",
			TRIM(pegawai."JENIS_JABATAN_NAMA") "JENIS_JABATAN_NAMA",
			TRIM(pegawai."JABATAN_NAMA") "JABATAN_NAMA",
			TRIM(pegawai."KPKN_NAMA") "KPKN_NAMA",
			TRIM(pegawai."INSTANSI_INDUK_NAMA") "INSTANSI_INDUK_NAMA",
			TRIM(pegawai."INSTANSI_KERJA_NAMA") "INSTANSI_KERJA_NAMA",
			TRIM(pegawai."SATUAN_KERJA_INDUK_NAMA") "SATUAN_KERJA_INDUK_NAMA",
			TRIM(pegawai."SATUAN_KERJA_NAMA") "SATUAN_KERJA_NAMA",
			TRIM(pegawai."JABATAN_INSTANSI_ID") "JABATAN_INSTANSI_ID",
			TRIM(pegawai."JABATAN_INSTANSI_NAMA") "JABATAN_INSTANSI_NAMA",
			pegawai."JENIS_JABATAN_ID" "JENIS_JABATAN_ID",
			pegawai."terminated_date" "terminated_date",
			pegawai."status_pegawai" "status_pegawai",
			TRIM(pegawai."JABATAN_PPNPN") "JABATAN_PPNPN",
			TRIM(pegawai."JABATAN_INSTANSI_REAL_ID") "JABATAN_INSTANSI_REAL_ID",
			pegawai."CREATED_DATE" "CREATED_DATE",
			pegawai."CREATED_BY" "CREATED_BY",
			pegawai."UPDATED_DATE" "UPDATED_DATE",
			pegawai."UPDATED_BY" "UPDATED_BY",
			TRIM(pegawai."EMAIL_DIKBUD") "EMAIL_DIKBUD",
			TRIM(pegawai."KODECEPAT") "KODECEPAT"
			,jenis_pegawai.NAMA as JENIS_PEGAWAI,kedudukan_hukum.NAMA AS KEDUDUKAN_HUKUM,pa.masa_kerja_th,pa.masa_kerja_bl,pegawai."MASA_KERJA"');
		}
		$this->db->join('jenis_pegawai', 'pegawai.JENIS_PEGAWAI_ID = jenis_pegawai.ID', 'left');
		$this->db->join('pns_aktif pa', 'pa.ID = pegawai.ID', 'left');
		$this->db->join('kedudukan_hukum', 'pegawai.KEDUDUKAN_HUKUM_ID = kedudukan_hukum.ID', 'left');

		return parent::find($ID);
	}
	public function find_detil_nip($PNS_ID = "")
	{

		if (empty($this->selects)) {
			$this->select($this->table_name . '.*,NAMA_UNOR_FULL');
		}
		$this->db->where("PNS_ID", $PNS_ID);
		$this->db->join('vw_unit_list vw', 'pegawai.UNOR_ID = vw.ID', 'left');
		return parent::find_all();
	}
	public function find_detil_with_nip($NIP_BARU = "")
	{

		if (empty($this->selects)) {
			$this->select('
			pegawai."ID",
			TRIM(pegawai."PNS_ID") "PNS_ID",
			TRIM(pegawai."NIP_LAMA") "NIP_LAMA",
			TRIM(pegawai."NIP_BARU") "NIP_BARU",
			TRIM(pegawai."NAMA") "NAMA",
			TRIM(pegawai."GELAR_DEPAN") "GELAR_DEPAN",
			TRIM(pegawai."GELAR_BELAKANG") "GELAR_BELAKANG",
			TRIM(pegawai."TEMPAT_LAHIR_ID") "TEMPAT_LAHIR_ID",
			pegawai."TGL_LAHIR" "TGL_LAHIR",
			TRIM(pegawai."JENIS_KELAMIN") "JENIS_KELAMIN",
			pegawai."AGAMA_ID" "AGAMA_ID",
			TRIM(pegawai."JENIS_KAWIN_ID") "JENIS_KAWIN_ID",
			TRIM(pegawai."NIK") "NIK",
			TRIM(pegawai."NOMOR_DARURAT") "NOMOR_DARURAT",
			TRIM(pegawai."NOMOR_HP") "NOMOR_HP",
			TRIM(pegawai."EMAIL") "EMAIL",
			TRIM(pegawai."ALAMAT") "ALAMAT",
			TRIM(pegawai."NPWP") "NPWP",
			TRIM(pegawai."BPJS") "BPJS",
			TRIM(pegawai."JENIS_PEGAWAI_ID") "JENIS_PEGAWAI_ID",
			TRIM(pegawai."KEDUDUKAN_HUKUM_ID") "KEDUDUKAN_HUKUM_ID",
			TRIM(pegawai."STATUS_CPNS_PNS") "STATUS_CPNS_PNS",
			TRIM(pegawai."KARTU_PEGAWAI") "KARTU_PEGAWAI",
			TRIM(pegawai."NOMOR_SK_CPNS") "NOMOR_SK_CPNS",
			pegawai."TGL_SK_CPNS" "TGL_SK_CPNS",
			pegawai."TMT_CPNS" "TMT_CPNS",
			pegawai."TMT_PNS" "TMT_PNS",
			TRIM(pegawai."GOL_AWAL_ID") "GOL_AWAL_ID",
			pegawai."GOL_ID" "GOL_ID",
			pegawai."TMT_GOLONGAN" "TMT_GOLONGAN",
			TRIM(pegawai."MK_TAHUN") "MK_TAHUN",
			TRIM(pegawai."MK_BULAN") "MK_BULAN",
			TRIM(pegawai."JENIS_JABATAN_IDx") "JENIS_JABATAN_IDx",
			TRIM(pegawai."JABATAN_ID") "JABATAN_ID",
			pegawai."TMT_JABATAN" "TMT_JABATAN",
			TRIM(pegawai."PENDIDIKAN_ID") "PENDIDIKAN_ID",
			TRIM(pegawai."TAHUN_LULUS") "TAHUN_LULUS",
			TRIM(pegawai."KPKN_ID") "KPKN_ID",
			TRIM(pegawai."LOKASI_KERJA_ID") "LOKASI_KERJA_ID",
			TRIM(pegawai."UNOR_ID") "UNOR_ID",
			TRIM(pegawai."UNOR_INDUK_ID") "UNOR_INDUK_ID",
			TRIM(pegawai."INSTANSI_INDUK_ID") "INSTANSI_INDUK_ID",
			TRIM(pegawai."INSTANSI_KERJA_ID") "INSTANSI_KERJA_ID",
			TRIM(pegawai."SATUAN_KERJA_INDUK_ID") "SATUAN_KERJA_INDUK_ID",
			TRIM(pegawai."SATUAN_KERJA_KERJA_ID") "SATUAN_KERJA_KERJA_ID",
			TRIM(pegawai."GOLONGAN_DARAH") "GOLONGAN_DARAH",
			TRIM(pegawai."PHOTO") "PHOTO",
			pegawai."TMT_PENSIUN" "TMT_PENSIUN",
			TRIM(pegawai."LOKASI_KERJA") "LOKASI_KERJA",
			TRIM(pegawai."JML_ISTRI") "JML_ISTRI",
			TRIM(pegawai."JML_ANAK") "JML_ANAK",
			TRIM(pegawai."NO_SURAT_DOKTER") "NO_SURAT_DOKTER",
			pegawai."TGL_SURAT_DOKTER" "TGL_SURAT_DOKTER",
			TRIM(pegawai."NO_BEBAS_NARKOBA") "NO_BEBAS_NARKOBA",
			pegawai."TGL_BEBAS_NARKOBA" "TGL_BEBAS_NARKOBA",
			TRIM(pegawai."NO_CATATAN_POLISI") "NO_CATATAN_POLISI",
			pegawai."TGL_CATATAN_POLISI" "TGL_CATATAN_POLISI",
			TRIM(pegawai."AKTE_KELAHIRAN") "AKTE_KELAHIRAN",
			TRIM(pegawai."STATUS_HIDUP") "STATUS_HIDUP",
			TRIM(pegawai."AKTE_MENINGGAL") "AKTE_MENINGGAL",
			pegawai."TGL_MENINGGAL" "TGL_MENINGGAL",
			TRIM(pegawai."NO_ASKES") "NO_ASKES",
			TRIM(pegawai."NO_TASPEN") "NO_TASPEN",
			pegawai."TGL_NPWP" "TGL_NPWP",
			TRIM(pegawai."TEMPAT_LAHIR") "TEMPAT_LAHIR",
			TRIM(pegawai."PENDIDIKAN") "PENDIDIKAN",
			TRIM(pegawai."TK_PENDIDIKAN") "TK_PENDIDIKAN",
			TRIM(pegawai."TEMPAT_LAHIR_NAMA") "TEMPAT_LAHIR_NAMA",
			TRIM(pegawai."JENIS_JABATAN_NAMA") "JENIS_JABATAN_NAMA",
			TRIM(pegawai."JABATAN_NAMA") "JABATAN_NAMA",
			TRIM(pegawai."KPKN_NAMA") "KPKN_NAMA",
			TRIM(pegawai."INSTANSI_INDUK_NAMA") "INSTANSI_INDUK_NAMA",
			TRIM(pegawai."INSTANSI_KERJA_NAMA") "INSTANSI_KERJA_NAMA",
			TRIM(pegawai."SATUAN_KERJA_INDUK_NAMA") "SATUAN_KERJA_INDUK_NAMA",
			TRIM(pegawai."SATUAN_KERJA_NAMA") "SATUAN_KERJA_NAMA",
			TRIM(pegawai."JABATAN_INSTANSI_ID") "JABATAN_INSTANSI_ID",
			TRIM(pegawai."JABATAN_INSTANSI_NAMA") "JABATAN_INSTANSI_NAMA",
			pegawai."JENIS_JABATAN_ID" "JENIS_JABATAN_ID",
			pegawai."terminated_date" "terminated_date",
			pegawai."status_pegawai" "status_pegawai",
			TRIM(pegawai."JABATAN_PPNPN") "JABATAN_PPNPN",
			TRIM(pegawai."JABATAN_INSTANSI_REAL_ID") "JABATAN_INSTANSI_REAL_ID",
			pegawai."CREATED_DATE" "CREATED_DATE",
			pegawai."CREATED_BY" "CREATED_BY",
			pegawai."UPDATED_DATE" "UPDATED_DATE",
			pegawai."UPDATED_BY" "UPDATED_BY",
			TRIM(pegawai."EMAIL_DIKBUD") "EMAIL_DIKBUD",
			TRIM(pegawai."KODECEPAT") "KODECEPAT"
			,jenis_pegawai.NAMA as JENIS_PEGAWAI,kedudukan_hukum.NAMA AS KEDUDUKAN_HUKUM,pa.masa_kerja_th,pa.masa_kerja_bl,pegawai."MASA_KERJA');
		}
		$this->db->join('jenis_pegawai', 'pegawai.JENIS_PEGAWAI_ID = jenis_pegawai.ID', 'left');
		$this->db->join('pns_aktif pa', 'pa.ID = pegawai.ID', 'left');
		$this->db->join('kedudukan_hukum', 'pegawai.KEDUDUKAN_HUKUM_ID = kedudukan_hukum.ID', 'left');

		return parent::find_by("NIP_BARU", $NIP_BARU);
	}
	public function find_grupjabatan($eselon2 = "")
	{

		if (empty($this->selects)) {
			$this->select($this->table_name . '.JABATAN_ID,UNOR_ID,count(pegawai."JABATAN_ID") as jumlah');
		}
		if ($eselon2 != "") {
			$this->db->where('"UNOR_ID" LIKE \'' . strtoupper($eselon2) . '%\'');
		}
		//$this->db->join('ref_jabatan', 'ref_jabatan.ID_Jabatan = JABATAN_ID', 'left');
		$this->db->group_by("JABATAN_ID");
		$this->db->group_by("UNOR_ID");
		return parent::find_all();
	}
	public function find_grupjabataninstansi($eselon2 = "")
	{

		if (empty($this->selects)) {
			$this->select($this->table_name . '.JABATAN_INSTANSI_ID,KODE_INTERNAL,count(pegawai."JABATAN_INSTANSI_ID") as jumlah');
		}
		if ($eselon2 != "") {
			$this->db->where('"KODE_INTERNAL" LIKE \'' . strtoupper($eselon2) . '%\'');
		}
		//add by bana
		 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		//	 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		//$this->db->join('ref_jabatan', 'ref_jabatan.ID_Jabatan = JABATAN_ID', 'left');
		$this->db->join("unitkerja", "pegawai.UNOR_ID=unitkerja.ID", 'left');
		$this->db->group_by("JABATAN_INSTANSI_ID");
		//$this->db->group_by("UNOR_ID");
		$this->db->group_by("KODE_INTERNAL");
		return parent::find_all();
	}
	public function find_grupjabatan_unor_induk($unor_id = "")
	{

		if (empty($this->selects)) {
			$this->select($this->table_name . '.JABATAN_INSTANSI_ID,UNOR_ID,count(pegawai."JABATAN_INSTANSI_ID") as jumlah');
		}
		if ($unor_id != "") {
			$this->db->where("UNOR_INDUK",$unor_id);
		}
		//add by bana
		 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->join("unitkerja", "pegawai.UNOR_ID=unitkerja.ID", 'left');
		$this->db->group_by("JABATAN_INSTANSI_ID");
		$this->db->group_by("UNOR_ID");
		// $this->db->group_by("unitkerja.ID");
		return parent::find_all();
	}
	public function find_pegawai_jabatan($eselon2 = "")
	{

		if (empty($this->selects)) {
			$this->select('pegawai.ID,NAMA,NIP_BARU,JABATAN_INSTANSI_ID,KODE_INTERNAL');
		}
		if ($eselon2 != "") {
			$this->db->where('"KODE_INTERNAL" LIKE \'' . strtoupper($eselon2) . '%\'');
		}
		//add by bana
		 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		//$this->db->join('ref_jabatan', 'ref_jabatan.ID_Jabatan = JABATAN_ID', 'left');
		$this->db->join("unitkerja", "pegawai.UNOR_ID=unitkerja.ID", 'left');
		return parent::find_all();
	}
	public function find_pegawai_jabatan_unor_induk($unor_induk = "")
	{

		if (empty($this->selects)) {
			$this->select('pegawai.ID,NAMA,NIP_BARU,JABATAN_INSTANSI_ID,KODE_INTERNAL,UNOR_ID');
		}
		if ($unor_induk != "") {
			$this->db->where("UNOR_INDUK",$unor_induk);
		}
		 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		//$this->db->join('ref_jabatan', 'ref_jabatan.ID_Jabatan = JABATAN_ID', 'left');
		$this->db->join("unitkerja", "pegawai.UNOR_ID=unitkerja.ID", 'left');
		return parent::find_all();
	}
	// update yanarazor
	public function find_grupagama($satker_id)
	{
		$where_clause = '';
		if ($satker_id) {
			$where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
		}
		//$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left',false);
		if (empty($this->selects)) {
			$this->select($this->table_name . '.AGAMA_ID,agama.NAMA AS label,sum(case when vw."ID" is not null  then 1 else 0  end) as value');
		}
		$eselon2 = null;
		if ($eselon2 != "") {
			$this->db->where('"UNOR_ID" LIKE \'' . strtoupper($eselon2) . '%\'');
		}
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		$this->db->join('agama', 'pegawai.AGAMA_ID = agama.ID', 'left');

		$this->db->where('pa."ID" is not null', NULL, FALSE);
		 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);

		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);

		$this->db->group_by('pegawai."AGAMA_ID"');
		$this->db->group_by("agama.NAMA");
		$this->db->order_by('pegawai."AGAMA_ID"');
		return parent::find_all();
	}
	public function group_by_range_umur($unor_id = '')
	{
		$where_clause = '';
		if ($unor_id != '') {
			$where_clause = 'AND (vw."ESELON_1" = \'' . $unor_id . '\' OR vw."ESELON_2" = \'' . $unor_id . '\' OR vw."ESELON_3" = \'' . $unor_id . '\' OR vw."ESELON_4" = \'' . $unor_id . '\')';
		}
		$this->db->select('	 sum(CASE WHEN pa."ID" is not null AND  "AGE" < 25 THEN 1 else 0 END) "<25"
							,sum(CASE WHEN pa."ID" is not null AND  "AGE" >= 25  AND "AGE" <= 30 THEN 1 else 0 END) "25-30"
							,sum(CASE WHEN pa."ID" is not null AND  "AGE" >= 31  AND "AGE" <= 35 THEN 1 else 0 END) "31-35"
							,sum(CASE WHEN pa."ID" is not null AND  "AGE" >= 36  AND "AGE" <= 40 THEN 1 else 0 END) "36-40"
							,sum(CASE WHEN pa."ID" is not null AND  "AGE" >= 41  AND "AGE" <= 45 THEN 1 else 0 END) "41-45"
							,sum(CASE WHEN pa."ID" is not null AND  "AGE" >= 46  AND "AGE" <= 50 THEN 1 else 0 END) "46-50"
							,sum(CASE WHEN pa."ID" is not null AND  "AGE" > 50 THEN 1 END) ">50"
										', false);

		$this->db->from("daftar_pegawai");
		$this->db->join("pegawai", "daftar_pegawai.ID=pegawai.ID", "LEFT");
		$this->db->join("pns_aktif pa", "daftar_pegawai.ID=pa.ID", "LEFT");
		$this->db->where('pa."ID" is not null', NULL, FALSE);
		// $this->db->where('daftar_pegawai."KEDUDUKAN_HUKUM_ID" <> \'99\' and daftar_pegawai."KEDUDUKAN_HUKUM_ID" <> \'66\' and daftar_pegawai."KEDUDUKAN_HUKUM_ID" <> \'52\' and daftar_pegawai."KEDUDUKAN_HUKUM_ID" <> \'20\' and daftar_pegawai."KEDUDUKAN_HUKUM_ID" <> \'04\' ');
		// $this->db->where('daftar_pegawai."KEDUDUKAN_HUKUM_ID" NOT IN (\'14\',\'52\',\'66\',\'67\',\'77\',\'88\',\'98\',\'99\',\'100\')');
		$this->db->where_not_in("daftar_pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->join("vw_unit_list as vw", "daftar_pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);



		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);


		return $this->db->get()->result('array');
	}
	public function group_by_range_tahun_lahir($unor_id = '')
	{
		$where_clause = '';
		if ($unor_id != '') {
			$where_clause = 'AND (vw."ESELON_1" = \'' . $unor_id . '\' OR vw."ESELON_2" = \'' . $unor_id . '\' OR vw."ESELON_3" = \'' . $unor_id . '\' OR vw."ESELON_4" = \'' . $unor_id . '\')';
		}
		$this->db->select('sum(CASE WHEN pa."ID" is not null AND  "tahun_lahir" < 1964 THEN 1 else 0 END) "boomer"
							,sum(CASE WHEN pa."ID" is not null AND  "tahun_lahir" >= 1965  AND "tahun_lahir" <= 1980 THEN 1 else 0 END) "Genx"
							,sum(CASE WHEN pa."ID" is not null AND  "tahun_lahir" >= 1981  AND "tahun_lahir" <= 1996 THEN 1 else 0 END) "GenY"
							,sum(CASE WHEN pa."ID" is not null AND  "tahun_lahir" >= 1997 THEN 1 else 0 END) "GenZ"
										', false);

		$this->db->from("daftar_pegawai");
		$this->db->join("pegawai", "daftar_pegawai.ID=pegawai.ID", "LEFT");
		$this->db->join("pns_aktif pa", "daftar_pegawai.ID=pa.ID", "LEFT");
		$this->db->where('pa."ID" is not null', NULL, FALSE);
		// $this->db->where('daftar_pegawai."KEDUDUKAN_HUKUM_ID" <> \'99\' and daftar_pegawai."KEDUDUKAN_HUKUM_ID" <> \'66\' and daftar_pegawai."KEDUDUKAN_HUKUM_ID" <> \'52\' and daftar_pegawai."KEDUDUKAN_HUKUM_ID" <> \'20\' and daftar_pegawai."KEDUDUKAN_HUKUM_ID" <> \'04\' ');
		// $this->db->where('daftar_pegawai."KEDUDUKAN_HUKUM_ID" NOT IN (\'14\',\'52\',\'66\',\'67\',\'77\',\'78\',\'98\',\'99\')');
		$this->db->where_not_in("daftar_pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->join("vw_unit_list as vw", "daftar_pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);



		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);


		return $this->db->get()->result('array');
	}
	public function group_by_range_umur_jk($unor_id = '')
	{
		$where_clause = '';
		if ($unor_id != '') {
			$where_clause = 'AND (vw."ESELON_1" = \'' . $unor_id . '\' OR vw."ESELON_2" = \'' . $unor_id . '\' OR vw."ESELON_3" = \'' . $unor_id . '\' OR vw."ESELON_4" = \'' . $unor_id . '\')';
		}
		$this->db->select('	pegawai."JENIS_KELAMIN"
							,sum(CASE WHEN pa."ID" is not null AND  "AGE" < 25 THEN 1 else 0 END) "<25"
							,sum(CASE WHEN pa."ID" is not null AND  "AGE" >= 25  AND "AGE" <= 30 THEN 1 else 0 END) "25-30"
							,sum(CASE WHEN pa."ID" is not null AND  "AGE" >= 31  AND "AGE" <= 35 THEN 1 else 0 END) "31-35"
							,sum(CASE WHEN pa."ID" is not null AND  "AGE" >= 36  AND "AGE" <= 40 THEN 1 else 0 END) "36-40"
							,sum(CASE WHEN pa."ID" is not null AND  "AGE" >= 41  AND "AGE" <= 45 THEN 1 else 0 END) "41-45"
							,sum(CASE WHEN pa."ID" is not null AND  "AGE" >= 46  AND "AGE" <= 50 THEN 1 else 0 END) "46-50"
							,sum(CASE WHEN pa."ID" is not null AND  "AGE" >= 51  AND "AGE" <= 55 THEN 1 else 0 END) "51-55"
							,sum(CASE WHEN pa."ID" is not null AND  "AGE" >= 56  AND "AGE" <= 60 THEN 1 else 0 END) "56-60"
							,sum(CASE WHEN pa."ID" is not null AND  "AGE" > 60 THEN 1 END) ">60"
							
										', false);

		$this->db->from("daftar_pegawai");
		$this->db->join("pegawai", "daftar_pegawai.ID=pegawai.ID", "LEFT");
		$this->db->join("pns_aktif pa", "daftar_pegawai.ID=pa.ID", "LEFT");
		$this->db->where('pa."ID" is not null', NULL, FALSE);
		$this->db->where('daftar_pegawai."KEDUDUKAN_HUKUM_ID" <> \'99\' and daftar_pegawai."KEDUDUKAN_HUKUM_ID" <> \'66\' and daftar_pegawai."KEDUDUKAN_HUKUM_ID" <> \'52\' and daftar_pegawai."KEDUDUKAN_HUKUM_ID" <> \'20\' and daftar_pegawai."KEDUDUKAN_HUKUM_ID" <> \'04\' ');
		$this->db->join("vw_unit_list as vw", "daftar_pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);
		$this->db->where('pegawai."JENIS_KELAMIN" IS NOT NULL');
		$this->db->group_by('pegawai."JENIS_KELAMIN"');

		return $this->db->get()->result('array');
	}

	public function grupbypendidikan($unor_id = '')
	{
		$where_clause = '';
		if ($unor_id != '') {
			$where_clause = 'AND (vw."ESELON_1" = \'' . $unor_id . '\' OR vw."ESELON_2" = \'' . $unor_id . '\' OR vw."ESELON_3" = \'' . $unor_id . '\' OR vw."ESELON_4" = \'' . $unor_id . '\')';
		}

		if (empty($this->selects)) {
			$this->select('tkpendidikan.NAMA as NAMA_PENDIDIKAN,sum(case when vw."ID" is not null  then 1 else 0  end) as jumlah');
		}
		$this->db->join('pendidikan', 'pegawai.PENDIDIKAN_ID = pendidikan.ID', 'left');
		$this->db->join('tkpendidikan', 'pendidikan.TINGKAT_PENDIDIKAN_ID = tkpendidikan.ID', 'left');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		$this->db->where('pa."ID" is not null', NULL, FALSE);

		 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);

		$this->db->group_by('tkpendidikan.NAMA');
		$this->db->group_by('tkpendidikan.ID');
		$this->db->order_by('tkpendidikan.ID', "ASC");
		return parent::find_all();
	}
	public function grupbytingkatpendidikan($unor_id = '')
	{
		$where_clause = '';
		if ($unor_id != '') {
			$where_clause = 'AND (vw."ESELON_1" = \'' . $unor_id . '\' OR vw."ESELON_2" = \'' . $unor_id . '\' OR vw."ESELON_3" = \'' . $unor_id . '\' OR vw."ESELON_4" = \'' . $unor_id . '\')';
		}

		if (empty($this->selects)) {
			$this->select('TINGKAT,sum(case when vw."ID" is not null  then 1 else 0  end) as jumlah');
		}
		$this->db->join('pendidikan', 'pegawai.PENDIDIKAN_ID = pendidikan.ID', 'left');
		$this->db->join('tkpendidikan', 'pendidikan.TINGKAT_PENDIDIKAN_ID = tkpendidikan.ID', 'left');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		$this->db->where('pa."ID" is not null', NULL, FALSE);

		 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);

		$this->db->group_by('tkpendidikan.TINGKAT');
		$this->db->order_by('tkpendidikan.TINGKAT', "ASC");
		return parent::find_all();
	}
	public function grupby_kategori_jabatan($unor_id = '')
	{
		$where_clause = '';
		if ($unor_id != '') {
			$where_clause = 'AND (vw."ESELON_1" = \'' . $unor_id . '\' OR vw."ESELON_2" = \'' . $unor_id . '\' OR vw."ESELON_3" = \'' . $unor_id . '\' OR vw."ESELON_4" = \'' . $unor_id . '\')';
		}

		if (empty($this->selects)) {
			$this->select('case when "KATEGORI_JABATAN" is not null  then "KATEGORI_JABATAN" else \'Kosong\' END AS "KATEGORI_JABATAN",
				sum(case when vw."ID" is not null  then 1 else 0  end) as jumlah');
		}
		$this->db->join("vw_unit_list as vw", "pegawai.UNOR_ID=vw.ID", 'left');
		$this->db->join("hris.jabatan ", "pegawai.JABATAN_INSTANSI_REAL_ID=jabatan.KODE_JABATAN", 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		$this->db->where('pa."ID" is not null', NULL, FALSE);
		 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->group_by('KATEGORI_JABATAN');
		$this->db->order_by('KATEGORI_JABATAN', "ASC");
		return parent::find_all();
	}
	public function grupby_kategori_jabatan_eselon1($unor_id = '')
	{
		$where_clause = '';
		if ($unor_id != '') {
			$where_clause = 'AND (vw."ESELON_1" = \'' . $unor_id . '\' OR vw."ESELON_2" = \'' . $unor_id . '\' OR vw."ESELON_3" = \'' . $unor_id . '\' OR vw."ESELON_4" = \'' . $unor_id . '\')';
		}

		if (empty($this->selects)) {
			$this->select('case when "KATEGORI_JABATAN" is not null  then "KATEGORI_JABATAN" else \'Kosong\' END AS "KATEGORI_JABATAN",
				ESELON_1,sum(case when vw."ID" is not null  then 1 else 0  end) as jumlah');
		}
		$this->db->join("vw_unit_list as vw", "pegawai.UNOR_ID=vw.ID", 'left');
		$this->db->join("hris.jabatan ", "pegawai.JABATAN_INSTANSI_REAL_ID=jabatan.KODE_JABATAN", 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		$this->db->where('pa."ID" is not null', NULL, FALSE);
		 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->group_by('KATEGORI_JABATAN');
		$this->db->group_by('ESELON_1');
		$this->db->order_by('KATEGORI_JABATAN', "ASC");
		return parent::find_all();
	}
	public function grupbyjk($unor_id = '')
	{
		$where_clause = '';
		if ($unor_id != '') {
			$where_clause = 'AND (vw."ESELON_1" = \'' . $unor_id . '\' OR vw."ESELON_2" = \'' . $unor_id . '\' OR vw."ESELON_3" = \'' . $unor_id . '\' OR vw."ESELON_4" = \'' . $unor_id . '\')';
		}
		if (empty($this->selects)) {
			$this->select('pegawai.JENIS_KELAMIN,sum(case when vw."ID" is not null  then 1 else 0  end) as jumlah');
		}
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		$this->db->where('pa."ID" is not null', NULL, FALSE);
		 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->group_by('pegawai.JENIS_KELAMIN');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		return parent::find_all();
	}
	// pensiun dari umur default 58
	public function count_pensiunold($unor_id = '')
	{
		if ($unor_id != '') {
			$this->db->where("(unitkerja.ID = '" . $unor_id . "' or unitkerja.ESELON_1 = '" . $unor_id . "' or unitkerja.ESELON_2 = '" . $unor_id . "' or unitkerja.ESELON_3 = '" . $unor_id . "' or unitkerja.ESELON_4 = '" . $unor_id . "')");
		}
		$this->db->select('pegawai.*');
		$this->db->where('EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) > 58');
		$this->db->join("hris.unitkerja ", "pegawai.UNOR_ID=unitkerja.ID", 'left');
		return parent::count_all();
	}
	// jumlah pensiun berdasarkan tanggal sekarang
	public function count_pensiun($unor_id = '')
	{
		if ($unor_id != '') {
			$this->db->where("(unitkerja.ID = '" . $unor_id . "' or unitkerja.ESELON_1 = '" . $unor_id . "' or unitkerja.ESELON_2 = '" . $unor_id . "' or unitkerja.ESELON_3 = '" . $unor_id . "' or unitkerja.ESELON_4 = '" . $unor_id . "')");
		}
		$this->db->select('pegawai.*');
		$this->db->where('EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) >= jabatan."PENSIUN"');
		$this->db->where("(status_pegawai != 3 or status_pegawai is null)");
		$this->db->where("(TMT_PENSIUN IS NULL)");
		$this->db->join("hris.unitkerja ", "pegawai.UNOR_ID=unitkerja.ID", 'left');
		$this->db->join("hris.jabatan ", "pegawai.JABATAN_INSTANSI_REAL_ID=jabatan.KODE_JABATAN", 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "inner");
		return parent::count_all();
	}
	public function find_all_pensiunall($unor_id = '')
	{
		if ($unor_id != '') {
			$this->db->where("(unitkerja.ID = '" . $unor_id . "' or unitkerja.ESELON_1 = '" . $unor_id . "' or unitkerja.ESELON_2 = '" . $unor_id . "' or unitkerja.ESELON_3 = '" . $unor_id . "' or unitkerja.ESELON_4 = '" . $unor_id . "')");
		}
		$this->db->select('pegawai.*,unitkerja."NAMA_UNOR",EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) as umur');
		$this->db->where('EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) > 58');
		$this->db->join("unitkerja", "pegawai.UNOR_ID=unitkerja.ID", 'left');
		return parent::find_all();
	}
	public function find_all_pensiun($unor_id = '')
	{
		if ($unor_id != '') {
			$this->db->where("(unitkerja.ID = '" . $unor_id . "' or unitkerja.ESELON_1 = '" . $unor_id . "' or unitkerja.ESELON_2 = '" . $unor_id . "' or unitkerja.ESELON_3 = '" . $unor_id . "' or unitkerja.ESELON_4 = '" . $unor_id . "')");
		}
		$this->db->select('pegawai.NIP_BARU,pegawai.ID,NAMA,TGL_LAHIR,vw."NAMA_UNOR_FULL" AS NAMA_UNOR,EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) as umur,jabatan."NAMA_JABATAN",jabatan."PENSIUN"');
		$this->db->where('EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) >= jabatan."PENSIUN"');
		$this->db->where("(status_pegawai != 3 or status_pegawai is null)");
		$this->db->where("(TMT_PENSIUN IS NULL)");
		$this->db->join("vw_unit_list as vw", "pegawai.UNOR_ID=vw.ID", 'left');
		$this->db->join("hris.jabatan ", "pegawai.JABATAN_INSTANSI_REAL_ID=jabatan.KODE_JABATAN", 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "inner");
		return parent::find_all();
	}
	public function find_all_pensiun_tahun($tahun = "", $unor_id = '')
	{
		if ($unor_id != '') {
			$this->db->where("(unitkerja.ID = '" . $unor_id . "' or unitkerja.ESELON_1 = '" . $unor_id . "' or unitkerja.ESELON_2 = '" . $unor_id . "' or unitkerja.ESELON_3 = '" . $unor_id . "' or unitkerja.ESELON_4 = '" . $unor_id . "')");
		}
		$this->db->select('pegawai.NIP_BARU,pegawai.ID,NAMA,
			TGL_LAHIR,vw."NAMA_UNOR_FULL" AS NAMA_UNOR,
			EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) as umur,
			date_part(\'year\',"TGL_LAHIR")+"PENSIUN" AS tahunpensiun,
			jabatan."NAMA_JABATAN",jabatan."PENSIUN"');
		$this->db->where('date_part(\'year\',"TGL_LAHIR")+"PENSIUN" = ' . $tahun . '');
		$this->db->where("(status_pegawai != 3 or status_pegawai is null)");
		//$this->db->where("(TMT_PENSIUN IS NULL)");
		$this->db->join("vw_unit_list as vw", "pegawai.UNOR_ID=vw.ID", 'left');
		$this->db->join("hris.jabatan ", "pegawai.JABATAN_INSTANSI_REAL_ID=jabatan.KODE_JABATAN", 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "inner");
		$this->db->order_by('vw."NAMA_UNOR_FULL"', "asc");
		return parent::find_all();
	}
	public function find_all_pensiun_tahun_jjabatan($tahun = "", $jenis_jabatan = "", $unor_id = '')
	{
		if ($unor_id != '') {
			$this->db->where("(unitkerja.ID = '" . $unor_id . "' or unitkerja.ESELON_1 = '" . $unor_id . "' or unitkerja.ESELON_2 = '" . $unor_id . "' or unitkerja.ESELON_3 = '" . $unor_id . "' or unitkerja.ESELON_4 = '" . $unor_id . "')");
		}
		$this->db->select('pegawai.NIP_BARU,pegawai.ID,pegawai.NAMA,
			TGL_LAHIR,vw."NAMA_UNOR_FULL" AS NAMA_UNOR,
			EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) as umur,
			date_part(\'year\',"TGL_LAHIR")+"PENSIUN" AS tahunpensiun,
			jabatan."NAMA_JABATAN",jabatan."PENSIUN"');
		$this->db->where('date_part(\'year\',"TGL_LAHIR")+"PENSIUN" = ' . $tahun . '');
		$this->db->where("(status_pegawai != 3 or status_pegawai is null)");
		$this->db->where('jenis_jabatan."ID', $jenis_jabatan);
		//$this->db->where("(TMT_PENSIUN IS NULL)");
		$this->db->join("vw_unit_list as vw", "pegawai.UNOR_ID=vw.ID", 'left');
		$this->db->join("hris.jabatan ", "pegawai.JABATAN_INSTANSI_REAL_ID=jabatan.KODE_JABATAN", 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "inner");
		$this->db->join("hris.jenis_jabatan ", "jenis_jabatan.ID=jabatan.JENIS_JABATAN", 'left');
		$this->db->order_by('vw."NAMA_UNOR_FULL"', "asc");
		return parent::find_all();
	}
	public function count_all_pensiun_tahun_jjabatan($tahun = "", $jenis_jabatan = "", $unor_id = '')
	{
		if ($unor_id != '') {
			$this->db->where("(unitkerja.ID = '" . $unor_id . "' or unitkerja.ESELON_1 = '" . $unor_id . "' or unitkerja.ESELON_2 = '" . $unor_id . "' or unitkerja.ESELON_3 = '" . $unor_id . "' or unitkerja.ESELON_4 = '" . $unor_id . "')");
		}
		$this->db->select('pegawai.NIP_BARU,pegawai.ID,pegawai.NAMA,
			TGL_LAHIR,vw."NAMA_UNOR_FULL" AS NAMA_UNOR,
			EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) as umur,
			date_part(\'year\',"TGL_LAHIR")+"PENSIUN" AS tahunpensiun,
			jabatan."NAMA_JABATAN",jabatan."PENSIUN"');
		$this->db->where('date_part(\'year\',"TGL_LAHIR")+"PENSIUN" = ' . $tahun . '');
		$this->db->where("(status_pegawai != 3 or status_pegawai is null)");
		$this->db->where('jenis_jabatan."ID', $jenis_jabatan);
		//$this->db->where("(TMT_PENSIUN IS NULL)");
		$this->db->join("vw_unit_list as vw", "pegawai.UNOR_ID=vw.ID", 'left');
		$this->db->join("hris.jabatan ", "pegawai.JABATAN_INSTANSI_REAL_ID=jabatan.KODE_JABATAN", 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "inner");
		$this->db->join("hris.jenis_jabatan ", "jenis_jabatan.ID=jabatan.JENIS_JABATAN", 'left');
		return parent::count_all();
	}
	public function find_all_pensiun_tahun_jabatan($tahun = "", $jabatan = "", $unor_id = '')
	{
		if ($unor_id != '') {
			$this->db->where("(unitkerja.ID = '" . $unor_id . "' or unitkerja.ESELON_1 = '" . $unor_id . "' or unitkerja.ESELON_2 = '" . $unor_id . "' or unitkerja.ESELON_3 = '" . $unor_id . "' or unitkerja.ESELON_4 = '" . $unor_id . "')");
		}
		$this->db->select('pegawai.NIP_BARU,pegawai.ID,pegawai.NAMA,
			TGL_LAHIR,vw."NAMA_UNOR_FULL" AS NAMA_UNOR,
			EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) as umur,
			date_part(\'year\',"TGL_LAHIR")+"PENSIUN" AS tahunpensiun,
			jabatan."NAMA_JABATAN",jabatan."PENSIUN"');
		$this->db->where('date_part(\'year\',"TGL_LAHIR")+"PENSIUN" = ' . $tahun . '');
		$this->db->where("(status_pegawai != 3 or status_pegawai is null)");
		$this->db->where('jabatan."KODE_JABATAN', $jabatan);
		//$this->db->where("(TMT_PENSIUN IS NULL)");
		$this->db->join("vw_unit_list as vw", "pegawai.UNOR_ID=vw.ID", 'left');
		$this->db->join("hris.jabatan ", "pegawai.JABATAN_INSTANSI_REAL_ID=jabatan.KODE_JABATAN", 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "inner");
		$this->db->join("hris.jenis_jabatan ", "jenis_jabatan.ID=jabatan.JENIS_JABATAN", 'left');
		$this->db->order_by('vw."NAMA_UNOR_FULL"', "asc");
		return parent::find_all();
	}
	public function count_all_pensiun_tahun_jabatan($tahun = "", $jabatan = "", $unor_id = '')
	{
		if ($unor_id != '') {
			$this->db->where("(unitkerja.ID = '" . $unor_id . "' or unitkerja.ESELON_1 = '" . $unor_id . "' or unitkerja.ESELON_2 = '" . $unor_id . "' or unitkerja.ESELON_3 = '" . $unor_id . "' or unitkerja.ESELON_4 = '" . $unor_id . "')");
		}
		$this->db->select('pegawai.NIP_BARU,pegawai.ID,pegawai.NAMA,
			TGL_LAHIR,vw."NAMA_UNOR_FULL" AS NAMA_UNOR,
			EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) as umur,
			date_part(\'year\',"TGL_LAHIR")+"PENSIUN" AS tahunpensiun,
			jabatan."NAMA_JABATAN",jabatan."PENSIUN"');
		$this->db->where('date_part(\'year\',"TGL_LAHIR")+"PENSIUN" = ' . $tahun . '');
		$this->db->where("(status_pegawai != 3 or status_pegawai is null)");
		$this->db->where('jabatan."KODE_JABATAN', $jabatan);
		//$this->db->where("(TMT_PENSIUN IS NULL)");
		$this->db->join("vw_unit_list as vw", "pegawai.UNOR_ID=vw.ID", 'left');
		$this->db->join("hris.jabatan ", "pegawai.JABATAN_INSTANSI_REAL_ID=jabatan.KODE_JABATAN", 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "inner");
		return parent::count_all();
	}

	public function count_all_pensiun_tahun($tahun = "", $unor_id = '')
	{
		if ($unor_id != '') {
			$this->db->where("(unitkerja.ID = '" . $unor_id . "' or unitkerja.ESELON_1 = '" . $unor_id . "' or unitkerja.ESELON_2 = '" . $unor_id . "' or unitkerja.ESELON_3 = '" . $unor_id . "' or unitkerja.ESELON_4 = '" . $unor_id . "')");
		}
		$this->db->select('pegawai.NIP_BARU,NAMA,TGL_LAHIR,vw."NAMA_UNOR_FULL" AS NAMA_UNOR,EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) as umur,jabatan."NAMA_JABATAN",jabatan."PENSIUN"');
		$this->db->where('date_part(\'year\',"TGL_LAHIR")+"PENSIUN" = ' . $tahun . '');
		$this->db->where("(status_pegawai != 3 or status_pegawai is null)");
		//$this->db->where("(TMT_PENSIUN IS NULL)");
		$this->db->join("vw_unit_list as vw", "pegawai.UNOR_ID=vw.ID", 'left');
		$this->db->join("hris.jabatan ", "pegawai.JABATAN_INSTANSI_REAL_ID=jabatan.KODE_JABATAN", 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "inner");
		return parent::count_all();
	}
	public function stats_pensiun_pertahun($satker_id, $tahun_kedepan = 10)
	{
		$limit = $tahun_kedepan + 1;
		$where_clause = '';
		$tahun = date('Y');
		if ($satker_id) {
			$where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
		}

		$db_stats = $this->db->query('
			select perkiraan_tahun_pensiun,sum(CASE  WHEN  temp."ID" is not null THEN 1 else 0 END) 
				as total from (
				select vw."ID","TGL_LAHIR","NIP_BARU","PENSIUN","NAMA", date_part(\'year\', "TGL_LAHIR")+"PENSIUN" as perkiraan_tahun_pensiun 
					from hris.pegawai pegawai
					inner join hris.pns_aktif pa on pegawai."ID"  = pa."ID"
					LEFT JOIN "hris"."jabatan" ON "pegawai"."JABATAN_INSTANSI_REAL_ID" = "jabatan"."KODE_JABATAN"
					left join vw_unit_list as vw on pegawai."UNOR_ID" = vw."ID" ' . $where_clause . '
					where 1=1  
				) as temp
				where perkiraan_tahun_pensiun >= ' . $tahun . ' 
				group by perkiraan_tahun_pensiun
				ORDER BY perkiraan_tahun_pensiun asc
				limit (' . $limit . ')
		')->result('array');

		$tahuns = array();
		for ($t = $tahun; $t <= $tahun + $tahun_kedepan; $t++) {
			$tahuns[] = array("tahun" => $t, "jumlah" => 0);
		}
		foreach ($tahuns as &$tahun) {
			foreach ($db_stats as $row) {
				if ($tahun['tahun'] == $row['perkiraan_tahun_pensiun']) {
					$tahun['jumlah'] = $row['total'];
					break;
				}
			}
		}
		//print_r($db_stats);
		return $tahuns;
	}
	public function find_pensiunbyjenisjabatan($unor_id = '')
	{
		$sepuluhtahun = date("Y") + 10;
		if ($unor_id != '') {
			$this->db->where("(unitkerja.ID = '" . $unor_id . "' or unitkerja.ESELON_1 = '" . $unor_id . "' or unitkerja.ESELON_2 = '" . $unor_id . "' or unitkerja.ESELON_3 = '" . $unor_id . "' or unitkerja.ESELON_4 = '" . $unor_id . "')");
		}
		$this->db->select('jenis_jabatan."ID" as ID_JENIS_JABATAN,jenis_jabatan."NAMA" as JENIS_JABATAN,
			date_part(\'year\', "TGL_LAHIR" ) + "PENSIUN" AS perkiraan_tahun_pensiun,  
 			count(*) as total');

		$this->db->where("(status_pegawai != 3 or status_pegawai is null)");
		$this->db->where("(TMT_PENSIUN IS NULL)");
		$this->db->where('date_part(\'year\', "TGL_LAHIR" ) + "PENSIUN" >= ' . date("Y") . '');
		$this->db->where('date_part(\'year\', "TGL_LAHIR" ) + "PENSIUN" <= ' . $sepuluhtahun . '');

		$this->db->join("unitkerja", "pegawai.UNOR_ID=unitkerja.ID", 'left');
		$this->db->join("hris.jabatan ", "pegawai.JABATAN_INSTANSI_REAL_ID=jabatan.KODE_JABATAN", 'left');
		$this->db->join("hris.jenis_jabatan ", "jenis_jabatan.ID=jabatan.JENIS_JABATAN", 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "inner");
		$this->db->order_by('perkiraan_tahun_pensiun', "asc");
		$this->db->group_by('jenis_jabatan."NAMA"');
		$this->db->group_by('jenis_jabatan."ID"');
		$this->db->group_by('perkiraan_tahun_pensiun');
		return parent::find_all();
	}
	public function find_pensiunbyjabatan($unor_id = '')
	{
		$sepuluhtahun = date("Y") + 10;
		if ($unor_id != '') {
			$this->db->where("(unitkerja.ID = '" . $unor_id . "' or unitkerja.ESELON_1 = '" . $unor_id . "' or unitkerja.ESELON_2 = '" . $unor_id . "' or unitkerja.ESELON_3 = '" . $unor_id . "' or unitkerja.ESELON_4 = '" . $unor_id . "')");
		}
		$this->db->select('jabatan."KODE_JABATAN",jabatan."NAMA_JABATAN",
			date_part(\'year\', "TGL_LAHIR" ) + "PENSIUN" AS perkiraan_tahun_pensiun,  
 			count(*) as total');

		$this->db->where("(status_pegawai != 3 or status_pegawai is null)");
		$this->db->where("(TMT_PENSIUN IS NULL)");
		$this->db->where('date_part(\'year\', "TGL_LAHIR" ) + "PENSIUN" >= ' . date("Y") . '');
		$this->db->where('date_part(\'year\', "TGL_LAHIR" ) + "PENSIUN" <= ' . $sepuluhtahun . '');

		$this->db->join("unitkerja", "pegawai.UNOR_ID=unitkerja.ID", 'left');
		$this->db->join("hris.jabatan ", "pegawai.JABATAN_INSTANSI_REAL_ID=jabatan.KODE_JABATAN", 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "inner");
		$this->db->order_by('perkiraan_tahun_pensiun', "asc");
		$this->db->group_by('KODE_JABATAN');
		$this->db->group_by('jabatan."NAMA_JABATAN"');
		$this->db->group_by('perkiraan_tahun_pensiun');
		return parent::find_all();
	}
	public function find_by_golonganbulan($unor_id = '')
	{
		$sepuluhtahun = date("Y") + 10;
		if ($unor_id != '') {
			$this->db->where("(unitkerja.ID = '" . $unor_id . "' or unitkerja.ESELON_1 = '" . $unor_id . "' or unitkerja.ESELON_2 = '" . $unor_id . "' or unitkerja.ESELON_3 = '" . $unor_id . "' or unitkerja.ESELON_4 = '" . $unor_id . "')");
		}
		$this->db->select('pegawai."GOL_ID",golongan."NAMA",  
 			count(*) as total');

		 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->where("(status_pegawai != 3 or status_pegawai is null)");
		$this->db->where("(TMT_PENSIUN IS NULL)");

		$this->db->join("unitkerja", "pegawai.UNOR_ID=unitkerja.ID", 'left');
		$this->db->join("hris.golongan ", "pegawai.GOL_ID=golongan.ID", 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "inner");
		$this->db->order_by('GOL_ID', "asc");
		$this->db->group_by('GOL_ID');
		$this->db->group_by('golongan."NAMA"');
		return parent::find_all();
	}
	public function find_by_goljenis($unor_id = '')
	{
		if ($unor_id != '') {
			$this->db->where("(unitkerja.ID = '" . $unor_id . "' or unitkerja.ESELON_1 = '" . $unor_id . "' or unitkerja.ESELON_2 = '" . $unor_id . "' or unitkerja.ESELON_3 = '" . $unor_id . "' or unitkerja.ESELON_4 = '" . $unor_id . "')");
		}
		$this->db->select('golongan."GOL",  
 			count(*) as jumlah');

		 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->where("(status_pegawai != 3 or status_pegawai is null)");
		$this->db->where("(TMT_PENSIUN IS NULL)");

		$this->db->join("unitkerja", "pegawai.UNOR_ID=unitkerja.ID", 'left');
		$this->db->join("hris.golongan ", "pegawai.GOL_ID=golongan.ID", 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "inner");
		$this->db->order_by('GOL', "asc");
		$this->db->group_by('GOL');
		return parent::find_all();
	}
	public function find_by_jenisjabatan($unor_id = '')
	{
		if ($unor_id != '') {
			$this->db->where("(unitkerja.ID = '" . $unor_id . "' or unitkerja.ESELON_1 = '" . $unor_id . "' or unitkerja.ESELON_2 = '" . $unor_id . "' or unitkerja.ESELON_3 = '" . $unor_id . "' or unitkerja.ESELON_4 = '" . $unor_id . "')");
		}
		$this->db->select('jabatan."JENIS_JABATAN",  
 			count(*) as jumlah');

		 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->where("(status_pegawai != 3 or status_pegawai is null)");
		$this->db->where("(TMT_PENSIUN IS NULL)");

		$this->db->join("unitkerja", "pegawai.UNOR_ID=unitkerja.ID", 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "inner");
		$this->db->order_by('JENIS_JABATAN', "asc");
		$this->db->group_by('jabatan."JENIS_JABATAN"');
		return parent::find_all();
	}
	public function find_by_pendidikanbulan($unor_id = '')
	{
		$sepuluhtahun = date("Y") + 10;
		if ($unor_id != '') {
			$this->db->where("(unitkerja.ID = '" . $unor_id . "' or unitkerja.ESELON_1 = '" . $unor_id . "' or unitkerja.ESELON_2 = '" . $unor_id . "' or unitkerja.ESELON_3 = '" . $unor_id . "' or unitkerja.ESELON_4 = '" . $unor_id . "')");
		}
		$this->db->select('tkpendidikan."ID" as TINGKAT_PENDIDIKAN,tkpendidikan."NAMA" as NAMA_TINGKAT,  
 			count(*) as total');

		 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->where("(status_pegawai != 3 or status_pegawai is null)");
		$this->db->where("(TMT_PENSIUN IS NULL)");

		$this->db->join("unitkerja", "pegawai.UNOR_ID=unitkerja.ID", 'left');
		$this->db->join("hris.pendidikan ", "pegawai.PENDIDIKAN_ID=pendidikan.ID", 'left');
		$this->db->join("hris.tkpendidikan ", "pendidikan.TINGKAT_PENDIDIKAN_ID=tkpendidikan.ID", 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "inner");
		$this->db->order_by('TINGKAT_PENDIDIKAN', "asc");
		$this->db->group_by('tkpendidikan."ID"');
		$this->db->group_by('tkpendidikan."NAMA"');
		return parent::find_all();
	}
	public function count_all_report()
	{


		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\"", 'left', false);
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		$this->db->where('pa."ID" is not null', NULL, FALSE);
		 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);

		$this->db->where("(status_pegawai != 3 or status_pegawai is null) ");

		return parent::count_all();
	}
	public function get_duk_list($unit_id = null, $start, $length)
	{
		$output = new stdClass;
		$this->db->start_cache();
		if ($unit_id) {
			$this->db->group_start();
			$this->db->or_where("ESELON_1", $unit_id);
			$this->db->or_where("ESELON_2", $unit_id);
			$this->db->or_where("ESELON_3", $unit_id);
			$this->db->or_where("ESELON_4", $unit_id);
			$this->db->group_end();
		}
		$this->db->from('vw_duk_list');
		$this->db->stop_cache();
		$total = $this->db->get()->num_rows();
		$this->db->limit($length, $start);
		$data = $this->db->get()->result();
		$output->total = $total;
		$output->data = $data;
		$this->db->flush_cache();
		return $output;
	}
	public function get_duk_list_satker($unit_id = null, $start, $length)
	{
		$output = new stdClass;
		$this->db->start_cache();
		if ($unit_id) {
			$this->db->group_start();
			$this->db->or_where("ESELON_1", $unit_id);
			$this->db->or_where("ESELON_2", $unit_id);
			$this->db->or_where("ESELON_3", $unit_id);
			$this->db->or_where("ESELON_4", $unit_id);
			$this->db->group_end();
		}
		// $this->db->where('vw_duk."KEDUDUKAN_HUKUM_ID" NOT IN (\'14\',\'52\',\'66\',\'67\',\'77\',\'78\',\'98\',\'99\')');
		$this->db->where_not_in("vw_duk.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->from('vw_duk');
		$this->db->stop_cache();
		$total = $this->db->get()->num_rows();
		$this->db->limit($length, $start);
		$data = $this->db->get()->result();
		$output->total = $total;
		$output->data = $data;
		$this->db->flush_cache();
		return $output;
	}
	public function get_jumlah_pegawai_per_golongan($satker_id)
	{
		$where_clause = '';
		if ($satker_id) {
			$where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
		}
		$where_clause .= 'AND pegawai."KEDUDUKAN_HUKUM_ID" <> \'99\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'66\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'52\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'20\' and pegawai."KEDUDUKAN_HUKUM_ID" <> \'04\' ';

		return $this->db->query('
			select golongan."ID" as "id",golongan."NAMA" as "nama",count(vw."ID") as total from hris.golongan
			left join hris.pegawai on  golongan."ID" = pegawai."GOL_ID" 
			inner join hris.pns_aktif pa on pegawai."ID" = pa."ID" 
			left join vw_unit_list vw on pegawai."UNOR_ID"= vw."ID" ' . $where_clause . '
			group by golongan."ID",golongan."NAMA"
			order by golongan."ID" asc
		')->result('array');
	}
	public function get_bup_per_range_umur($satker_id)
	{
		$where_clause = '';
		if ($satker_id) {
			$where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
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
				SELECT vw."ID",EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) age,pegawai."PENSIUN" as "bup"
				FROM hris.pegawai pegawai 
				left join hris.pns_aktif pa on pa."ID" = pegawai."ID" 
				left join vw_unit_list vw on pegawai."UNOR_ID"= vw."ID" ' . $where_clause . '
				where 1=1  
				) AS TEMPx 
				group by TEMPx."bup"
			')->result('array');
		$bups = array('58', '60');
		$range_umur = array('<25' => array(), '25-30' => array(), '31-35' => array(), '36-40' => array(), '41-45' => array(), '46-50' => array(), '>50' => array());

		foreach ($range_umur as $key => &$rumur) {
			$rumur['range'] = $key;
			foreach ($bups as $bup) {
				$rumur["bup_" . $bup] = 0;
				foreach ($data as $row) {
					$rec = new stdClass;
					if (isset($row[$key]) && $row['bup'] == $bup) {
						$rumur["bup_" . $bup] = $row[$key];
					} else if (!isset($row[$key]) && $row['bup'] == $bup) {
						$rumur["bup_" . $bup] = 0;
					}
				}
			}
		}
		return array_values($range_umur);
	}
	public function get_rekap_golongan_per_jenis_kelamin($satker_id)
	{
		$where_clause = '';
		if ($satker_id) {
			$where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
		}
		$data = $this->db->query('
			select TEMPx."nama",sum(CASE  WHEN TEMPX."ID" is not null and jenis_kelamin =\'M\' THEN 1 ELSE 0 END) "M"
																			,sum(CASE  WHEN TEMPX."ID" is not null and jenis_kelamin =\'F\' THEN 1 ELSE 0 END) "F"
																			,sum(CASE  WHEN TEMPX."ID" is not null and jenis_kelamin =NULL THEN 1 ELSE 0 END) "-"
								FROM (
										select vw."ID",golongan."NAMA" as "nama",pegawai."JENIS_KELAMIN" as "jenis_kelamin" from hris.golongan  
										
										left join hris.pegawai pegawai on golongan."ID" = pegawai."GOL_ID"
										left join hris.pns_aktif pa on pa."ID" = pegawai."ID"
										left join vw_unit_list vw on pegawai."UNOR_ID"= vw."ID" ' . $where_clause . '
										where 1=1 
			)AS TEMPx 
								group by TEMPx."nama"
			order by TEMPx."nama"
		')->result('array');
		return $data;
	}
	public function get_rekap_golongan_per_pendidikan($satker_id)
	{
		$where_clause = '';
		if ($satker_id) {
			$where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
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
										left join vw_unit_list vw on pegawai."UNOR_ID"= vw."ID" ' . $where_clause . '
										left join hris.pendidikan on pendidikan."ID" = pegawai."PENDIDIKAN_ID"
										left join hris.tkpendidikan on tkpendidikan."ID" = pendidikan."TINGKAT_PENDIDIKAN_ID"
										where 1=1 

			)AS TEMPx 
								group by TEMPx."golongan"
			order by TEMPx."golongan"	
		')->result('array');
		return $data;
	}
	public function get_rekap_jenis_kelamin_per_usia($satker_id = "")
	{
		$where_clause = '';
		if ($satker_id) {
			$where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
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
										
										left join vw_unit_list vw on 1=1 and pegawai."UNOR_ID"= vw."ID" ' . $where_clause . '
										WHERE 1=1
			) as tempx
			group by tempx."JENIS KELAMIN"

		')->result('array');
		return $data;
	}
	public function get_rekap_pendidikan_per_jenis_kelamin($satker_id = "")
	{
		$where_clause = '';
		if ($satker_id) {
			$where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
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
										left join vw_unit_list vw on pegawai."UNOR_ID"= vw."ID"  ' . $where_clause . '
										WHERE 1=1 
			) as tempx
			group by tempx."nama"
		')->result('array');

		return $data;
	}
	public function get_rekap_pendidikan_per_usia($satker_id)
	{
		$where_clause = '';
		if ($satker_id) {
			$where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
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
										left join vw_unit_list vw on 1=1 and pegawai."UNOR_ID"= vw."ID" ' . $where_clause . '
										where 1=1 
			) as tempx
			group by tempx."TK PENDIDIKAN"

		')->result('array');
		return $data;
	}
	public function get_rekap_golongan_per_usia($satker_id)
	{
		$where_clause = '';
		if ($satker_id) {
			$where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
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
										left join vw_unit_list vw on  pegawai."UNOR_ID"= vw."ID" ' . $where_clause . '
										where 1=1
			)AS TEMPx 
								group by TEMPx."nama"
			order by TEMPx."nama"
		')->result('array');

		return $data;
	}
	public function get_jumlah_pegawai_per_agama_jeniskelamin($satker_id)
	{
		$where_clause = '';
		if ($satker_id) {
			$where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
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
					left join vw_unit_list vw on pegawai."UNOR_ID"= vw."ID"   ' . $where_clause . '
					where 1=1
				GROUP BY
					pegawai."JENIS_KELAMIN",
					agama."ID",
					agama."NAMA"
				ORDER BY
					agama."NAMA";
			')->result('array');
		$agamas = array('Budha', 'Hindu', 'Islam', 'Katholik', 'Protestan', 'Lainnya', 'Belum terdata');
		$output = array();
		foreach ($agamas as $agama) {
			if (isset($output[$agama])) {
				$rec = new stdClass;
			} else {
				$rec = $output[$agama];
			}
			$rec->nama = $agama;
			$rec->m = 0;
			$rec->f = 0;
			foreach ($data as $row) {
				if ($agama == $row['nama']) {
					if ($row['jenis_kelamin'] == 'M') {
						$rec->m =  $row['total'];
					} else if ($row['jenis_kelamin'] == 'F') {
						$rec->f =  $row['total'];
					}
				} else if ('Kristen' == $row['nama'] && $agama == 'Protestan') {
					if ($row['jenis_kelamin'] == 'M') {
						$rec->m =  $row['total'];
					} else if ($row['jenis_kelamin'] == 'F') {
						$rec->f =  $row['total'];
					}
				} else if (null == $row['nama'] && $agama == 'Belum terdata') {
					if ($row['jenis_kelamin'] == 'M') {
						$rec->m =  $row['total'];
					} else if ($row['jenis_kelamin'] == 'F') {
						$rec->f =  $row['total'];
					}
				}
			}
			$output[$agama] = $rec;
		}

		return array_values($output);
	}
	public function get_jumlah_pegawai_per_jabatan($satker_id)
	{
		$where_clause = '';
		if ($satker_id) {
			$where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
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
				left join vw_unit_list vw on pegawai."UNOR_ID"= vw."ID"   ' . $where_clause . '
				where 1=1
			GROUP BY
			jenis_jabatan."ID"	
			ORDER BY
				jenis_jabatan."NAMA"
		')->result();


		return $data;
	}
	public function getunor_id($nip = "")
	{
		$where_clause = 'AND pegawai."NIP_BARU" = \'' . trim($nip) . '\'';
		$unor_id = "";
		$data = $this->db->query('
				SELECT
					"UNOR_ID" 
				FROM
					hris.pegawai
					 
					where 1=1 ' . $where_clause . ';
			')->result('array');
		foreach ($data as $row) {
			//echo "masuk bro";
			$unor_id = $row['UNOR_ID'];
		}
		return $unor_id;
	}
	public function getunor_induk($nip = "")
	{
		$where_clause = 'AND pegawai."NIP_BARU" = \'' . trim($nip) . '\'';
		$unor_id = "";
		$data = $this->db->query('
				SELECT
					"UNOR_INDUK" 
				FROM
					hris.pegawai
					left join unitkerja vw on pegawai."UNOR_ID"= vw."ID"
					where 1=1 ' . $where_clause . ';
			')->result('array');
		foreach ($data as $row) {
			//echo "masuk bro";
			$unor_id = $row['UNOR_INDUK'];
		}
		//echo var_dump($unor_id);
		return $unor_id;
	}
	// add yana
	public function getunor_eselon1($nip = "")
	{
		$where_clause = 'AND pegawai."NIP_BARU" = \'' . trim($nip) . '\'';
		$unor_id = "";
		$data = $this->db->query('
				SELECT
					"ESELON_1" 
				FROM
					hris.pegawai
					left join vw_unit_list vw on pegawai."UNOR_ID"= vw."ID"
					where 1=1 ' . $where_clause . ';
			')->result('array');
		foreach ($data as $row) {
			//echo "masuk bro";
			$unor_id = $row['ESELON_1'];
		}
		return $unor_id;
	}
	public function get_count_jabatan_instansi($eselon2 = "", $jabatan = "")
	{

		if (empty($this->selects)) {
			$this->select($this->table_name . '.JABATAN_INSTANSI_ID,KODE_INTERNAL,count(pegawai."JABATAN_INSTANSI_ID") as jumlah');
		}
		if ($eselon2 != "") {
			$this->db->where('"ESELON_2" LIKE \'' . strtoupper($eselon2) . '%\'');
		}
		$this->db->where("JABATAN_INSTANSI_ID", $jabatan);
		$this->db->join("unitkerja", "pegawai.UNOR_ID=unitkerja.ID", 'left');
		$this->db->group_by("JABATAN_INSTANSI_ID");
		return parent::count_all();
	}
	public function get_count_jabatan_instansi_unor($unitkerja = "", $jabatan = "")
	{

		if (empty($this->selects)) {
			$this->select($this->table_name . '.JABATAN_INSTANSI_ID,KODE_INTERNAL,UNOR_ID,count(pegawai."JABATAN_INSTANSI_ID") as jumlah');
		}

		if ($unitkerja != "") {
			$this->db->where('("ESELON_1" = \'' . $unitkerja . '\' OR "ESELON_2" = \'' . $unitkerja . '\' OR "ESELON_3" = \'' . $unitkerja . '\' OR "ESELON_4" = \'' . $unitkerja . '\')');
		}
		$this->db->where("JABATAN_INSTANSI_ID", $jabatan);
		$this->db->join("unitkerja", "pegawai.UNOR_ID=unitkerja.ID", 'left');
		$this->db->group_by("JABATAN_INSTANSI_ID");
		$this->db->group_by("KODE_INTERNAL");
		$this->db->group_by("UNOR_ID");
		return parent::find_all();
	}
	public function find_photo($satker_id, $strict_in_satker = false)
	{

		$where_clause = '';
		if ($satker_id) {
			$where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
		}
		$this->db->select('pegawai.*,vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1"', false);
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}

		$this->db->where('pa."ID" is not null', NULL, FALSE);
		 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);

		$this->db->where('pegawai."PHOTO" is not null', NULL, FALSE);
		$this->db->where('pegawai."PHOTO" != \'0\'', NULL, FALSE);
		$this->db->where('pegawai."PHOTO" != \'\'', NULL, FALSE);
		$this->db->order_by("NAMA", "ASC");
		return parent::find_all();
	}
	public function find_baperjakat($satker_id, $strict_in_satker = false)
	{

		$where_clause = '';

		$this->db->select('pegawai."ID",pegawai."NAMA","NIP_BARU","GOL_ID","TK_PENDIDIKAN","PNS_ID"
			,(
				select "ID_JENIS_HUKUMAN" from rwt_hukdis left join jenis_hukuman on(jenis_hukuman."ID" = rwt_hukdis."ID_JENIS_HUKUMAN")
				where pegawai."NIP_BARU" = "PNS_NIP" order by rwt_hukdis."ID" DESC LIMIT 1
			) as rwt_hukuman,(
				select CAST("NILAI" as DOUBLE PRECISION) from rwt_assesmen
				where pegawai."NIP_BARU" = "PNS_NIP" order by rwt_assesmen."TAHUN" DESC LIMIT 1
			) as rwt_assesment
			', false);
		//$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\"", 'left',false);
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		$this->db->where('pa."ID" is not null', NULL, FALSE);
		 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) ");
		return parent::find_all();
	}
	public function find_by_baperjakat($kolom = "", $val = "")
	{

		$where_clause = '';

		$this->db->select('pegawai."PNS_ID",pegawai."ID",pegawai."NAMA","NIP_BARU","GOL_ID","TK_PENDIDIKAN","JENIS_JABATAN",golongan."NAMA" AS "NAMA_GOLONGAN"
			,(
				select "ID_JENIS_HUKUMAN" from rwt_hukdis left join jenis_hukuman on(jenis_hukuman."ID" = rwt_hukdis."ID_JENIS_HUKUMAN")
				where pegawai."NIP_BARU" = "PNS_NIP" order by rwt_hukdis."ID" DESC LIMIT 1
			) as rwt_hukuman,(
				select CAST("NILAI" as DOUBLE PRECISION) from rwt_assesmen
				where pegawai."NIP_BARU" = "PNS_NIP" order by rwt_assesmen."TAHUN" DESC LIMIT 1
			) as rwt_assesment
			', false);
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		return parent::find_by($kolom, $val);
	}
	public function find_atasan($satker_id = null, $strict_in_satker = false, $status_aktif = "")
	{
		$where_clause = '';
		if ($satker_id) {
			if (is_array($satker_id) && sizeof($satker_id) > 0) {
				$where_clause = 'AND ( 1=2 ';
				foreach ($satker_id as $u_id) {
					$where_clause .= ' OR vw."ESELON_1" = \'' . $u_id . '\' OR vw."ESELON_2" = \'' . $u_id . '\' OR vw."ESELON_3" = \'' . $u_id . '\' OR vw."ESELON_4" = \'' . $u_id . '\' OR "UNOR_INDUK_ID" = \'' . $u_id . '\' ';
				}
				$where_clause .= ')';
			} else $where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\' OR "UNOR_INDUK_ID" = \'' . $satker_id . '\')';
		}


		$this->db->select('pegawai."ID",pegawai."PNS_ID",
			pegawai."NIP_BARU",
			pegawai."NAMA",vw."NAMA_UNOR_FULL",
			golongan."NAMA" AS "NAMA_GOLONGAN",
			"NAMA_PANGKAT",
			string_agg(l."NAMA_ATASAN"|| \'-\' || l."SEBAGAI"::text,\'@\') as "ATASAN",
			"PHOTO"', false);
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		$this->db->join("line_approval_izin l", "pegawai.NIP_BARU = l.PNS_NIP", "LEFT");
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}

		$this->db->where('pa."ID" is not null', NULL, FALSE);
		if ($status_aktif == "") {
			 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		} else {
			$this->db->where('pegawai."KEDUDUKAN_HUKUM_ID"', $status_aktif);
		}

		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);
		$this->db->order_by("pegawai.NAMA", "ASC");
		$this->db->group_by('pegawai."ID",pegawai."PNS_ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",
			golongan."NAMA","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2",
			"NAMA_UNOR_ESELON_1","PHOTO"');
		//echo $this->db->last_query();
		//var_dump($this->db);
		return parent::find_all();
	}
	public function find_pensiunbysatker($unor_id = '')
	{
		$sepuluhtahun = date("Y") + 10;
		if ($unor_id != '') {
			$this->db->where("(unitkerja.ID = '" . $unor_id . "' or unitkerja.ESELON_1 = '" . $unor_id . "' or unitkerja.ESELON_2 = '" . $unor_id . "' or unitkerja.ESELON_3 = '" . $unor_id . "' or unitkerja.ESELON_4 = '" . $unor_id . "')");
		}
		$this->db->select('vw.ID,vw."NAMA_UNOR_FULL",vw."NAMA_UNOR",
			date_part(\'year\', "TGL_LAHIR" ) + "PENSIUN" AS perkiraan_tahun_pensiun,
			vw."ESELON_1",
 			count(*) as total');

		$this->db->where("(status_pegawai != 3 or status_pegawai is null)");
		$this->db->where("(TMT_PENSIUN IS NULL)");
		$this->db->where('date_part(\'year\', "TGL_LAHIR" ) + "PENSIUN" >= ' . date("Y") . '');
		$this->db->where('date_part(\'year\', "TGL_LAHIR" ) + "PENSIUN" <= ' . $sepuluhtahun . '');

		$this->db->join("mv_unit_list_all as vui", "pegawai.\"UNOR_ID\"=vui.\"ID\" $where_clause ", 'left', false);
		$this->db->join("mv_unit_list_all as vw", "vui.\"UNOR_INDUK\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join("hris.jabatan ", "pegawai.JABATAN_INSTANSI_REAL_ID=jabatan.KODE_JABATAN", 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "inner");
		$this->db->order_by('vw.ESELON_1', "asc");
		$this->db->group_by('vw.ID');
		$this->db->group_by('vw.ESELON_1');
		$this->db->group_by('vw."NAMA_UNOR_FULL"');
		$this->db->group_by('vw."NAMA_UNOR"');
		$this->db->group_by('perkiraan_tahun_pensiun');
		return parent::find_all();
	}
	public function umurbySatker($unor_id = '')
	{
		$where_clause = '';
		if ($unor_id != '') {
			$where_clause = 'AND (vw."ESELON_1" = \'' . $unor_id . '\' OR vw."ESELON_2" = \'' . $unor_id . '\' OR vw."ESELON_3" = \'' . $unor_id . '\' OR vw."ESELON_4" = \'' . $unor_id . '\')';
		}
		$this->db->select('vw."UNOR_INDUK" as "UNOR_INDUK_ID",
							vw."NAMA_UNOR",
							vw."ESELON_1",
							sum(CASE WHEN pa."ID" is not null AND  "AGE" < 25 THEN 1 else 0 END) "dualima"
							,sum(CASE WHEN pa."ID" is not null AND  "AGE" >= 25  AND "AGE" <= 30 THEN 1 else 0 END) "dualimatigapuluh"
							,sum(CASE WHEN pa."ID" is not null AND  "AGE" >= 31  AND "AGE" <= 35 THEN 1 else 0 END) "tigasatutigalima"
							,sum(CASE WHEN pa."ID" is not null AND  "AGE" >= 36  AND "AGE" <= 40 THEN 1 else 0 END) "tigaenamempatpuluh"
							,sum(CASE WHEN pa."ID" is not null AND  "AGE" >= 41  AND "AGE" <= 45 THEN 1 else 0 END) "empatsatuempatlima"
							,sum(CASE WHEN pa."ID" is not null AND  "AGE" >= 46  AND "AGE" <= 50 THEN 1 else 0 END) "empatenamlimapuluh"
							,sum(CASE WHEN pa."ID" is not null AND  "AGE" > 50 THEN 1 END) "limapuluh"
										', false);

		$this->db->join("daftar_pegawai", "daftar_pegawai.ID=pegawai.ID", "LEFT");
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		$this->db->where('pa."ID" is not null', NULL, FALSE);
		$this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->join("mv_unit_list_all as vui", "pegawai.\"UNOR_ID\"=vui.\"ID\" $where_clause ", 'left', false);
		$this->db->join("mv_unit_list_all as vw", "vui.\"UNOR_INDUK\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);
		$this->db->order_by('vw.ESELON_1', "asc");
		$this->db->group_by('vw.UNOR_INDUK');
		$this->db->group_by('vw.ESELON_1');
		$this->db->group_by('vw."NAMA_UNOR"');
		return parent::find_all();
	}
	public function findPegawaiTingkatPendidikanSatker($unor_id = '')
	{
		if ($unor_id != '') {
			$this->db->where("(unitkerja.ID = '" . $unor_id . "' or unitkerja.ESELON_1 = '" . $unor_id . "' or unitkerja.ESELON_2 = '" . $unor_id . "' or unitkerja.ESELON_3 = '" . $unor_id . "' or unitkerja.ESELON_4 = '" . $unor_id . "')");
		}
		$this->db->select('vw."UNOR_INDUK" as "UNOR_INDUK_ID",
							vw."ESELON_1",
							pendidikan.TINGKAT_PENDIDIKAN_ID as "TINGKAT",
 							count(*) as total');
		$this->db->where("(status_pegawai != 3 or status_pegawai is null)");
		$this->db->where('pa."ID" is not null', NULL, FALSE);
		$this->db->join('pendidikan', 'pegawai.PENDIDIKAN_ID = pendidikan.ID', 'left');
		$this->db->join('tkpendidikan', 'pendidikan.TINGKAT_PENDIDIKAN_ID = tkpendidikan.ID', 'left');
		$this->db->join("mv_unit_list_all as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "left");
		$this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->group_by('pendidikan.TINGKAT_PENDIDIKAN_ID');
		$this->db->group_by('vw.UNOR_INDUK');
		$this->db->group_by('vw.ESELON_1');
		return parent::find_all();
	}
	public function count_all_pensiun_tahun_satker($tahun = "", $satker_id = '')
	{
		$this->db->select('pegawai.NIP_BARU,pegawai.ID,pegawai.NAMA,
			TGL_LAHIR,vw."NAMA_UNOR_FULL" AS NAMA_UNOR,
			EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) as umur,
			date_part(\'year\',"TGL_LAHIR")+"PENSIUN" AS tahunpensiun,
			jabatan."NAMA_JABATAN",jabatan."PENSIUN"');
		$this->db->where('date_part(\'year\',"TGL_LAHIR")+"PENSIUN" = ' . $tahun . '');
		$this->db->where("(status_pegawai != 3 or status_pegawai is null)");
		if($satker_id != "-")
			$this->db->where('vw."ID"', $satker_id);
		else
			$this->db->where("vw.\"ID\"  IS NULL");
		$this->db->where("(TMT_PENSIUN IS NULL)");
		$this->db->join("mv_unit_list_all as vui", "pegawai.\"UNOR_ID\"=vui.\"ID\" $where_clause ", 'left', false);
		$this->db->join("mv_unit_list_all as vw", "vui.\"UNOR_INDUK\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join("hris.jabatan ", "pegawai.JABATAN_INSTANSI_REAL_ID=jabatan.KODE_JABATAN", 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "inner");
		return parent::count_all();
	}
	public function find_all_detil_umur_satker($satker_id = null, $strict_in_satker = false, $status_aktif = "")
	{
		$where_clause = '';
		if ($satker_id) {
			if (is_array($satker_id) && sizeof($satker_id) > 0) {
				$where_clause = 'AND ( 1=2 ';
				foreach ($satker_id as $u_id) {
					$where_clause .= ' OR vw."ESELON_1" = \'' . $u_id . '\' OR vw."ESELON_2" = \'' . $u_id . '\' OR vw."ESELON_3" = \'' . $u_id . '\' OR vw."ESELON_4" = \'' . $u_id . '\' ';
				}
				$where_clause .= ')';
			} else $where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
		}
		$this->db->select('pegawai."ID",pegawai."PNS_ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",
			golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN","PHOTO"', false);
		$this->db->join("mv_unit_list_all as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		$this->db->join('pendidikan', 'pegawai.PENDIDIKAN_ID = pendidikan.ID', 'left');
		$this->db->join('tkpendidikan', 'pendidikan.TINGKAT_PENDIDIKAN_ID = tkpendidikan.ID', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}

		$this->db->where('pa."ID" is not null', NULL, FALSE);
		if ($status_aktif == "") {
			 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		} else {
			$this->db->where('pegawai."KEDUDUKAN_HUKUM_ID"', $status_aktif);
		}

		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);
		$this->db->order_by("pegawai,NAMA", "ASC");

		//var_dump($this->db);
		return parent::find_all();
	}
	public function count_all_detil_umur_satker($satker_id = null, $strict_in_satker = false, $status_aktif = "")
	{
		$where_clause = '';
		if ($satker_id) {
			if (is_array($satker_id) && sizeof($satker_id) > 0) {
				$where_clause = 'AND ( 1=2 ';
				foreach ($satker_id as $u_id) {
					$where_clause .= ' OR vw."ESELON_1" = \'' . $u_id . '\' OR vw."ESELON_2" = \'' . $u_id . '\' OR vw."ESELON_3" = \'' . $u_id . '\' OR vw."ESELON_4" = \'' . $u_id . '\' ';
				}
				$where_clause .= ')';
			} else $where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
		}
		$this->db->select('pegawai."ID",pegawai."PNS_ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",
			golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN","PHOTO"', false);
		$this->db->join("mv_unit_list_all as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		$this->db->join('pendidikan', 'pegawai.PENDIDIKAN_ID = pendidikan.ID', 'left');
		$this->db->join('tkpendidikan', 'pendidikan.TINGKAT_PENDIDIKAN_ID = tkpendidikan.ID', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}

		$this->db->where('pa."ID" is not null', NULL, FALSE);
		if ($status_aktif == "") {
			 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		} else {
			$this->db->where('pegawai."KEDUDUKAN_HUKUM_ID"', $status_aktif);
		}
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);
		//var_dump($this->db);
		return parent::count_all();
	}
	public function find_all_pensiun_tahun_satker($tahun = "", $satker_id = '')
	{
		$this->db->select('pegawai.NIP_BARU,pegawai.ID,pegawai.NAMA,
			TGL_LAHIR,vw."NAMA_UNOR" AS NAMA_UNOR,
			EXTRACT(YEAR FROM age(cast("TGL_LAHIR" as date))) as umur,
			date_part(\'year\',"TGL_LAHIR")+"PENSIUN" AS tahunpensiun,
			jabatan."NAMA_JABATAN",jabatan."PENSIUN"');
		$this->db->where('date_part(\'year\',"TGL_LAHIR")+"PENSIUN" = ' . $tahun . '');
		$this->db->where("(status_pegawai != 3 or status_pegawai is null)");
		if($satker_id != "-")
			$this->db->where('vw."ID"', $satker_id);
		else
			$this->db->where("vw.\"ID\"  IS NULL");
		$this->db->where("(TMT_PENSIUN IS NULL)");
		$this->db->join("mv_unit_list_all as vui", "pegawai.\"UNOR_ID\"=vui.\"ID\" $where_clause ", 'left', false);
		$this->db->join("mv_unit_list_all as vw", "vui.\"UNOR_INDUK\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join("hris.jabatan ", "pegawai.JABATAN_INSTANSI_REAL_ID=jabatan.KODE_JABATAN", 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "inner");
		$this->db->order_by('vw."NAMA_UNOR"', "asc");
		return parent::find_all();
	}
	public function find_all_api_nip($satker_id, $strict_in_satker = false)
	{

		$where_clause = '';
		if ($satker_id) {
			if (is_array($satker_id) && sizeof($satker_id) > 0) {
				$where_clause = 'AND ( 1=2 ';
				foreach ($satker_id as $u_id) {
					$where_clause .= ' OR vw."ESELON_1" = \'' . $u_id . '\' OR vw."ESELON_2" = \'' . $u_id . '\' OR vw."ESELON_3" = \'' . $u_id . '\' OR vw."ESELON_4" = \'' . $u_id . '\' ';
				}
				$where_clause .= ')';
			} else $where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
		}
		$this->db->select('
			TRIM(pegawai."NIP_BARU") "nip"', false);
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);
		return parent::find_all();
	}
	public function count_all_api_nip($satker_id, $strict_in_satker = false)
	{

		$where_clause = '';
		if ($satker_id) {
			if (is_array($satker_id) && sizeof($satker_id) > 0) {
				$where_clause = 'AND ( 1=2 ';
				foreach ($satker_id as $u_id) {
					$where_clause .= ' OR vw."ESELON_1" = \'' . $u_id . '\' OR vw."ESELON_2" = \'' . $u_id . '\' OR vw."ESELON_3" = \'' . $u_id . '\' OR vw."ESELON_4" = \'' . $u_id . '\' ';
				}
				$where_clause .= ')';
			} else $where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
		}
		$this->db->select('
			TRIM(pegawai."NIP_BARU") "nip"', false);
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);
		return parent::count_all();
	}

	public function FindCountKursus($satker_id = "", $strict_in_satker = false)
	{

		$where_clause = '';
		if ($satker_id) {
			if (is_array($satker_id) && sizeof($satker_id) > 0) {
				$where_clause = 'AND ( 1=2 ';
				foreach ($satker_id as $u_id) {
					$where_clause .= ' OR vw."ESELON_1" = \'' . $u_id . '\' OR vw."ESELON_2" = \'' . $u_id . '\' OR vw."ESELON_3" = \'' . $u_id . '\' OR vw."ESELON_4" = \'' . $u_id . '\' ';
				}
				$where_clause .= ')';
			} else $where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
		}
		$this->db->select('
			TRIM(pegawai."NIP_BARU") AS "NIP_BARU",pegawai."NAMA",count(rk."ID") as jumlah', false);
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		$this->db->join("rwt_kursus rk", "pegawai.PNS_ID = rk.PNS_ID", "LEFT");
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);
		$this->db->group_by("NIP_BARU");
		$this->db->group_by("NAMA");
		return parent::find_all();
	}
	
	public function CountKursus($satker_id, $strict_in_satker = false)
	{

		$where_clause = '';
		if ($satker_id) {
			if (is_array($satker_id) && sizeof($satker_id) > 0) {
				$where_clause = 'AND ( 1=2 ';
				foreach ($satker_id as $u_id) {
					$where_clause .= ' OR vw."ESELON_1" = \'' . $u_id . '\' OR vw."ESELON_2" = \'' . $u_id . '\' OR vw."ESELON_3" = \'' . $u_id . '\' OR vw."ESELON_4" = \'' . $u_id . '\' ';
				}
				$where_clause .= ')';
			} else $where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
		}
		$this->db->select('
			TRIM(pegawai."NIP_BARU") "nip"', false);
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		$this->db->join("rwt_kursus rk", "pegawai.PNS_ID = rk.PNS_ID", "LEFT");
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);
		return parent::count_all();
	}
	public function getPangkatPendidikanKosong(){
		$this->db->select('
			TRIM(pegawai."PNS_ID") "PNS_ID",
			TRIM(pegawai."NIP_LAMA") "NIP_LAMA",
			TRIM(pegawai."NIP_BARU") "NIP_BARU",
			TRIM(pegawai."NAMA") "NAMA",
			"KEDUDUKAN_HUKUM_ID"
			,"JABATAN_INSTANSI_REAL_ID"
			,"GOL_ID"
			,"JENIS_KELAMIN"
			,"PENDIDIKAN_ID",
			"AGAMA_ID",
			"JABATAN_INSTANSI_REAL_ID"
			',false);
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->group_start();
			// $this->db->where("JABATAN_INSTANSI_REAL_ID IS NULL");
			// $this->db->or_where("GOL_ID  IS NULL");
			// $this->db->or_where("JENIS_KELAMIN  IS NULL");
			$this->db->where("PENDIDIKAN_ID  IS NULL");
		$this->db->group_end();
		
		$this->db->where('pa."ID" is not null', NULL, FALSE);
		$this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);
		$this->db->order_by("random()");
		return parent::find_all();
	}
	public function getPangkatKosong(){
		$this->db->select('
			TRIM(pegawai."PNS_ID") "PNS_ID",
			TRIM(pegawai."NIP_LAMA") "NIP_LAMA",
			TRIM(pegawai."NIP_BARU") "NIP_BARU",
			TRIM(pegawai."NAMA") "NAMA",
			"KEDUDUKAN_HUKUM_ID"
			,"JABATAN_INSTANSI_REAL_ID"
			,"GOL_ID"
			,"JENIS_KELAMIN"
			,"PENDIDIKAN_ID",
			"AGAMA_ID",
			"JABATAN_INSTANSI_REAL_ID"
			',false);
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->group_start();
			$this->db->where("JABATAN_INSTANSI_REAL_ID IS NULL");
			$this->db->or_where("GOL_ID  IS NULL");
			// $this->db->or_where("JENIS_KELAMIN  IS NULL");
			// $this->db->or_where("PENDIDIKAN_ID  IS NULL");
		$this->db->group_end();
		
		$this->db->where('pa."ID" is not null', NULL, FALSE);
		$this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);
		$this->db->order_by("random()");
		return parent::find_all();
	}
	public function getJkKosong(){
		$this->db->select('
			TRIM(pegawai."PNS_ID") "PNS_ID",
			TRIM(pegawai."NIP_LAMA") "NIP_LAMA",
			TRIM(pegawai."NIP_BARU") "NIP_BARU",
			TRIM(pegawai."NAMA") "NAMA",
			"KEDUDUKAN_HUKUM_ID"
			,"JABATAN_INSTANSI_REAL_ID"
			,"GOL_ID"
			,"JENIS_KELAMIN"
			,"PENDIDIKAN_ID",
			"AGAMA_ID",
			"JABATAN_INSTANSI_REAL_ID"
			',false);
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->group_start();
			// $this->db->where("JABATAN_INSTANSI_REAL_ID IS NULL");
			// $this->db->or_where("GOL_ID  IS NULL");
			$this->db->where("JENIS_KELAMIN  IS NULL");
			// $this->db->or_where("PENDIDIKAN_ID  IS NULL");
		$this->db->group_end();
		
		$this->db->where('pa."ID" is not null', NULL, FALSE);
		$this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);
		$this->db->order_by("random()");
		return parent::find_all();
	}
	public function getAgamaKosong(){
		$this->db->select('
			TRIM(pegawai."PNS_ID") "PNS_ID",
			TRIM(pegawai."NIP_LAMA") "NIP_LAMA",
			TRIM(pegawai."NIP_BARU") "NIP_BARU",
			TRIM(pegawai."NAMA") "NAMA",
			"KEDUDUKAN_HUKUM_ID"
			,"JABATAN_INSTANSI_REAL_ID"
			,"GOL_ID"
			,"JENIS_KELAMIN"
			,"PENDIDIKAN_ID",
			"AGAMA_ID",
			"JABATAN_INSTANSI_REAL_ID"
			',false);
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->group_start();
			// $this->db->where("JABATAN_INSTANSI_REAL_ID IS NULL");
			$this->db->where("AGAMA_ID  IS NULL");
			// $this->db->or_where("JENIS_KELAMIN  IS NULL");
			// $this->db->or_where("PENDIDIKAN_ID  IS NULL");
		$this->db->group_end();
		
		$this->db->where('pa."ID" is not null', NULL, FALSE);
		$this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);
		$this->db->order_by("random()");
		return parent::find_all();
	}
	public function getUnorKosong(){
		$this->db->select('
			TRIM(pegawai."PNS_ID") "PNS_ID",
			TRIM(pegawai."NIP_LAMA") "NIP_LAMA",
			TRIM(pegawai."NIP_BARU") "NIP_BARU",
			TRIM(pegawai."NAMA") "NAMA",
			"KEDUDUKAN_HUKUM_ID"
			,"JABATAN_INSTANSI_REAL_ID"
			,"GOL_ID"
			,"JENIS_KELAMIN"
			,"PENDIDIKAN_ID",
			"AGAMA_ID",
			"UNOR_INDUK_ID",
			"UNOR_ID",
			"JABATAN_INSTANSI_REAL_ID"
			',false);
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		// if ($strict_in_satker) {
		// 	$this->db->where('vw."ID" is not null');
		// }
		$this->db->group_start();
			$this->db->where("UNOR_ID  IS NULL");
			$this->db->or_where("UNOR_ID = ''");
			$this->db->or_where("UNOR_INDUK_ID IS NULL");
			$this->db->or_where("UNOR_INDUK_ID = ''");
		$this->db->group_end();
		
		$this->db->where('pa."ID" is not null', NULL, FALSE);
		// $this->db->where("NIP_BARU",'198102112008011007');
		$this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);
		$this->db->order_by("random()");
		return parent::find_all();
	}
	public function find_all_pejabat($satker_id = null, $strict_in_satker = false, $status_aktif = "")
	{
		$where_clause = '';
		if ($satker_id) {
			if (is_array($satker_id) && sizeof($satker_id) > 0) {
				$where_clause = 'AND ( 1=2 ';
				foreach ($satker_id as $u_id) {
					$where_clause .= ' OR vw."ESELON_1" = \'' . $u_id . '\' OR vw."ESELON_2" = \'' . $u_id . '\' OR vw."ESELON_3" = \'' . $u_id . '\' OR vw."ESELON_4" = \'' . $u_id . '\' ';
				}
				$where_clause .= ')';
			} else $where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
		}
		$this->db->select('pegawai."ID",
			pegawai."PNS_ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",
			golongan."NAMA" AS "NAMA_GOLONGAN",
			"NAMA_PANGKAT","NAMA_UNOR_ESELON_4",
			"NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2",
			"NAMA_UNOR_ESELON_1","KATEGORI_JABATAN",
			jabatan."NAMA_JABATAN",
			"PHOTO",
			"EMAIL",
			"NOMOR_HP",
			EXTRACT(YEAR from AGE("TGL_LAHIR")) as "age",
			pegawai."TGL_LAHIR" "TGL_LAHIR",
			TRIM(pegawai."JENIS_KELAMIN") "JENIS_KELAMIN",
			vw."JENIS_SATKER",vw."ESELON_ID",
			"EMAIL_DIKBUD","NOMOR_DARURAT",
			lokasi."NAMA" as "TEMPAT_LAHIR_NAMA","TEMPAT_LAHIR_ID",
			pendidikan."NAMA" AS "NAMA_PENDIDIKAN",
			kedudukan_hukum."NAMA" AS "KEDUDUKAN_HUKUM_NAMA",
			"KELAS",
			uk."NAMA_UNOR" AS nama_satker,
			"NOMOR_SK_CPNS","TGL_SK_CPNS",
			agama."NAMA" AS "NAMA_AGAMA"', false);
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('kedudukan_hukum', 'kedudukan_hukum.ID = pegawai.KEDUDUKAN_HUKUM_ID', 'left');
		$this->db->join('lokasi', 'lokasi.ID = pegawai.TEMPAT_LAHIR_ID', 'left');
		$this->db->join('pendidikan', 'pendidikan.ID = pegawai.PENDIDIKAN_ID', 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		$this->db->join('agama', 'agama.ID = pegawai.AGAMA_ID', 'left');
		$this->db->join("unitkerja as uk", 'uk.ID=vw.UNOR_INDUK', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_REAL_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where("JENIS_JABATAN","1");
		$this->db->where('pa."ID" is not null', NULL, FALSE);
		if ($status_aktif == "") {
			 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		} else {
			$this->db->where('pegawai."KEDUDUKAN_HUKUM_ID"', $status_aktif);
		}

		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);
		$this->db->order_by("NAMA", "ASC");
		return parent::find_all();
	}
	public function count_all_pejabat($satker_id = null, $strict_in_satker = false, $status_aktif = "")
	{
		$where_clause = '';
		if ($satker_id) {
			if (is_array($satker_id) && sizeof($satker_id) > 0) {
				$where_clause = 'AND ( 1=2 ';
				foreach ($satker_id as $u_id) {
					$where_clause .= ' OR vw."ESELON_1" = \'' . $u_id . '\' OR vw."ESELON_2" = \'' . $u_id . '\' OR vw."ESELON_3" = \'' . $u_id . '\' OR vw."ESELON_4" = \'' . $u_id . '\' ';
				}
				$where_clause .= ')';
			} else $where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
		}
		$this->db->select('pegawai."ID",pegawai."PNS_ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",
			golongan."NAMA" AS "NAMA_GOLONGAN",
			"NAMA_PANGKAT","NAMA_UNOR_ESELON_4",
			"NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2",
			"NAMA_UNOR_ESELON_1","KATEGORI_JABATAN",
			jabatan."NAMA_JABATAN",
			"PHOTO"', false);
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_REAL_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where("JENIS_JABATAN","1");
		$this->db->where('pa."ID" is not null', NULL, FALSE);
		if ($status_aktif == "") {
			 $this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		} else {
			$this->db->where('pegawai."KEDUDUKAN_HUKUM_ID"', $status_aktif);
		}
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);
		return parent::count_all();
	}
	public function findGroupSatker($UNOR_INDUK = "")
	{

		if (empty($this->selects)) {
			$this->select($this->table_name . '.UNOR_INDUK_ID,
				SUM ( CASE WHEN "JENIS_KELAMIN" = \'M\' THEN 1 END )  AS jml_laki, 
				SUM ( CASE WHEN "JENIS_KELAMIN" = \'F\' THEN 1 END )  AS jml_wanita,
				SUM ( CASE WHEN "GOL_ID" IN (11,12,13,14) THEN 1 END )  AS jml_gol1,
				SUM ( CASE WHEN "GOL_ID" IN (21,22,23,24) THEN 1 END )  AS jml_gol2,
				SUM ( CASE WHEN "GOL_ID" IN (31,32,33,34) THEN 1 END )  AS jml_gol3,
				SUM ( CASE WHEN "GOL_ID" IN (41,42,43,44) THEN 1 END )  AS jml_gol4,
				SUM ( CASE WHEN "JENIS_JABATAN_ID" IN (1) THEN 1 END )  AS jml_str,
				SUM ( CASE WHEN "JENIS_JABATAN_ID" IN (2) THEN 1 END )  AS jml_jft,
				SUM ( CASE WHEN "JENIS_JABATAN_ID" IN (4) THEN 1 END )  AS jml_jfu,
				SUM ( CASE WHEN "IS_DOSEN" IN (1) THEN 1 END )  AS jml_dosen,
				SUM ( CASE WHEN "TINGKAT_PENDIDIKAN_ID" IN (\'05\') THEN 1 END )  AS jml_sd,
				SUM ( CASE WHEN "TINGKAT_PENDIDIKAN_ID" IN (\'10\',\'12\') THEN 1 END )  AS jml_smp,
				SUM ( CASE WHEN "TINGKAT_PENDIDIKAN_ID" IN (\'15\',\'17\',\'18\') THEN 1 END )  AS jml_sma,
				SUM ( CASE WHEN "TINGKAT_PENDIDIKAN_ID" IN (\'20\',\'25\',\'30\',\'35\') THEN 1 END )  AS jml_d,
				SUM ( CASE WHEN "TINGKAT_PENDIDIKAN_ID" IN (\'40\') THEN 1 END )  AS jml_s1,
				SUM ( CASE WHEN "TINGKAT_PENDIDIKAN_ID" IN (\'45\') THEN 1 END )  AS jml_s2,
				SUM ( CASE WHEN "TINGKAT_PENDIDIKAN_ID" IN (\'50\') THEN 1 END )  AS jml_s3,
				count(pegawai."ID") as jumlah');
		}
		if ($UNOR_INDUK != "") {
			$this->db->where("UNOR_INDUK_ID",$UNOR_INDUK);
		}
		$this->db->join("pns_aktif pa", "pegawai.ID=pa.ID", "LEFT");
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('pendidikan', 'pegawai.PENDIDIKAN_ID = pendidikan.ID', 'left');
		$this->db->join('tkpendidikan', 'pendidikan.TINGKAT_PENDIDIKAN_ID = tkpendidikan.ID', 'left');

		$this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",$this->STATUS_NON_ACTIVE);
		$this->db->where('pa."ID" is not null', NULL, FALSE);
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) ");
		$this->db->group_by("UNOR_INDUK_ID");
		return parent::find_all();
	}
}
