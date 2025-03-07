<?php defined('BASEPATH') || exit('No direct script access allowed');

class Mv_pegawai_model extends BF_Model
{
	protected $table_name	= 'mv_pegawai';
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
			'rules' => 'max_length[32]|required',
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
			'rules' => 'max_length[20]',
		),
		array(
			'field' => 'GELAR_BELAKANG',
			'label' => 'lang:pegawai_field_GELAR_BELAKANG',
			'rules' => 'max_length[20]',
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
			'rules' => 'max_length[11]',
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
			'rules' => 'max_length[32]',
		),
		array(
			'field' => 'UNOR_INDUK_ID',
			'label' => 'lang:pegawai_field_UNOR_INDUK_ID',
			'rules' => 'max_length[32]',
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


	public function find_with_limit_offset($limit,$offset){
		$dbdefault = $this->load->database('default', true);
		
		$list = $dbdefault->query("
                    select userid, min (checktime) as checkin, max(checktime) as checkout
                    from checkinout
                    where userid=? and date(checktime)>=? and date(checktime)<=?
                    group by userid,date(checktime)
                    order by date(checktime) asc 
                    ", array($nip, $tgl_mulai,$tgl_selesai))->result();
	}
	 
	public function find_all_api($satker_id, $strict_in_satker = false)
	{

		$where_clause = '';
		if ($satker_id) {
			if (is_array($satker_id) && sizeof($satker_id) > 0) {
				$where_clause = 'AND ( 1=2 ';
				foreach ($satker_id as $u_id) {
					$where_clause .= ' OR "ESELON_1" = \'' . $u_id . '\' OR "ESELON_2" = \'' . $u_id . '\' OR "ESELON_3" = \'' . $u_id . '\' OR "ESELON_4" = \'' . $u_id . '\' ';
				}
				$where_clause .= ')';
			} else $where_clause = 'AND ("ESELON_1" = \'' . $satker_id . '\' OR "ESELON_2" = \'' . $satker_id . '\' OR "ESELON_3" = \'' . $satker_id . '\' OR "ESELON_4" = \'' . $satker_id . '\')';
		}
		$this->db->select('
			TRIM("PNS_ID") "PNS_ID",
			TRIM("NIP_LAMA") "NIP_LAMA",
			TRIM("NIP_BARU") "NIP_BARU",
			TRIM("NAMA") "NAMA",
			TRIM("GELAR_DEPAN") "GELAR_DEPAN",
			TRIM("GELAR_BELAKANG") "GELAR_BELAKANG",
			TRIM("TEMPAT_LAHIR_ID") "TEMPAT_LAHIR_ID",
			"TGL_LAHIR" "TGL_LAHIR",
			TRIM("JENIS_KELAMIN") "JENIS_KELAMIN",
			"AGAMA_ID" "AGAMA_ID",
			TRIM("JENIS_KAWIN_ID") "JENIS_KAWIN_ID",
			TRIM("NIK") "NIK",
			TRIM("NOMOR_DARURAT") "NOMOR_DARURAT",
			TRIM("NOMOR_HP") "NOMOR_HP",
			TRIM("EMAIL") "EMAIL",
			TRIM("ALAMAT") "ALAMAT",
			TRIM("NPWP") "NPWP",
			TRIM("BPJS") "BPJS",
			TRIM("JENIS_PEGAWAI_ID") "JENIS_PEGAWAI_ID",
			TRIM("KEDUDUKAN_HUKUM_ID") "KEDUDUKAN_HUKUM_ID",
			TRIM("STATUS_CPNS_PNS") "STATUS_CPNS_PNS",
			TRIM("KARTU_PEGAWAI") "KARTU_PEGAWAI",
			TRIM("NOMOR_SK_CPNS") "NOMOR_SK_CPNS",
			"TGL_SK_CPNS" "TGL_SK_CPNS",
			"TMT_CPNS" "TMT_CPNS",
			"TMT_PNS" "TMT_PNS",
			TRIM("GOL_AWAL_ID") "GOL_AWAL_ID",
			"GOL_ID" "GOL_ID",
			"TMT_GOLONGAN" "TMT_GOLONGAN",
			TRIM("MK_TAHUN") "MK_TAHUN",
			TRIM("MK_BULAN") "MK_BULAN",
			TRIM("JENIS_JABATAN_IDx") "JENIS_JABATAN_IDx",
			TRIM("JABATAN_ID") "JABATAN_ID",
			"TMT_JABATAN" "TMT_JABATAN",
			TRIM("PENDIDIKAN_ID") "PENDIDIKAN_ID",
			TRIM("NAMA_PENDIDIKAN") "PENDIDIKAN",
			TRIM("TK_PENDIDIKAN") "TK_PENDIDIKAN",
			TRIM("TINGKAT_PENDIDIKAN_NAMA") "TK_PENDIDIKAN_NAMA",
			TRIM("TAHUN_LULUS") "TAHUN_LULUS",
			TRIM("KPKN_ID") "KPKN_ID",
			TRIM("LOKASI_KERJA_ID") "LOKASI_KERJA_ID",
			TRIM("UNOR_ID") "UNOR_ID",
			TRIM("UNOR_INDUK_ID") "UNOR_INDUK_ID",
			TRIM("INSTANSI_INDUK_ID") "INSTANSI_INDUK_ID",
			TRIM("INSTANSI_KERJA_ID") "INSTANSI_KERJA_ID",
			TRIM("SATUAN_KERJA_INDUK_ID") "SATUAN_KERJA_INDUK_ID",
			TRIM("SATUAN_KERJA_KERJA_ID") "SATUAN_KERJA_KERJA_ID",
			TRIM("GOLONGAN_DARAH") "GOLONGAN_DARAH",
			TRIM("PHOTO") "PHOTO",
			"TMT_PENSIUN" "TMT_PENSIUN",
			TRIM("LOKASI_KERJA") "LOKASI_KERJA",
			TRIM("JML_ISTRI") "JML_ISTRI",
			TRIM("JML_ANAK") "JML_ANAK",
			TRIM("NO_SURAT_DOKTER") "NO_SURAT_DOKTER",
			"TGL_SURAT_DOKTER" "TGL_SURAT_DOKTER",
			TRIM("NO_BEBAS_NARKOBA") "NO_BEBAS_NARKOBA",
			"TGL_BEBAS_NARKOBA" "TGL_BEBAS_NARKOBA",
			TRIM("NO_CATATAN_POLISI") "NO_CATATAN_POLISI",
			"TGL_CATATAN_POLISI" "TGL_CATATAN_POLISI",
			TRIM("AKTE_KELAHIRAN") "AKTE_KELAHIRAN",
			TRIM("STATUS_HIDUP") "STATUS_HIDUP",
			TRIM("AKTE_MENINGGAL") "AKTE_MENINGGAL",
			"TGL_MENINGGAL" "TGL_MENINGGAL",
			TRIM("NO_ASKES") "NO_ASKES",
			TRIM("NO_TASPEN") "NO_TASPEN",
			"TGL_NPWP" "TGL_NPWP",
			TRIM("TEMPAT_LAHIR") "TEMPAT_LAHIR",
			TRIM("TEMPAT_LAHIR_NAMA") "TEMPAT_LAHIR_NAMA",
			
			TRIM("JENIS_JABATAN_NAMA") "JENIS_JABATAN_NAMA",
			TRIM("JABATAN_NAMA") "JABATAN_NAMA",
			
			TRIM("KPKN_NAMA") "KPKN_NAMA",
			TRIM("INSTANSI_INDUK_NAMA") "INSTANSI_INDUK_NAMA",
			TRIM("INSTANSI_KERJA_NAMA") "INSTANSI_KERJA_NAMA",
			TRIM("SATUAN_KERJA_INDUK_NAMA") "SATUAN_KERJA_INDUK_NAMA",
			TRIM("SATUAN_KERJA_NAMA") "SATUAN_KERJA_NAMA",
			
			TRIM("JABATAN_INSTANSI_ID") "JABATAN_INSTANSI_ID",
			TRIM("JABATAN_INSTANSI_NAMA") "JABATAN_INSTANSI_NAMA",
			"JENIS_JABATAN_ID" "JENIS_JABATAN_ID",
			
			"terminated_date" "terminated_date",
			"status_pegawai" "status_pegawai",
			TRIM("JABATAN_PPNPN") "JABATAN_PPNPN",
			
			TRIM("NAMA_JABATAN_REAL") "NAMA_JABATAN_REAL",
			TRIM("KATEGORI_JABATAN_REAL") "KATEGORI_JABATAN_REAL",
			"JENIS_JABATAN_REAL" "JENIS_JABATAN_REAL",

			"CREATED_DATE" "CREATED_DATE",
			"CREATED_BY" "CREATED_BY",
			"UPDATED_DATE" "UPDATED_DATE",
			"UPDATED_BY" "UPDATED_BY",
			TRIM("EMAIL_DIKBUD") "EMAIL_DIKBUD",
			TRIM("KODECEPAT") "KODECEPAT"
			,"NAMA_UNOR_FULL",
			"NAMA_GOLONGAN",
			"NAMA_PANGKAT",
			"KELAS_JABATAN",
			"NAMA_UNOR_ESELON_4",
			"NAMA_UNOR_ESELON_3",
			"NAMA_UNOR_ESELON_2",
			"NAMA_UNOR_ESELON_1"', false);
		 
		if ($strict_in_satker) {
			//$this->db->where('vw."ID" is not null');
		}
		$this->db->where('"PNS_AKTIF_ID" is not null', NULL, FALSE);
		$this->db->where('"KEDUDUKAN_HUKUM_ID" <> \'99\' and "KEDUDUKAN_HUKUM_ID" <> \'66\' and "KEDUDUKAN_HUKUM_ID" <> \'52\' and "KEDUDUKAN_HUKUM_ID" <> \'20\' and "KEDUDUKAN_HUKUM_ID" <> \'04\' ');
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);
		$this->db->order_by("NAMA", "ASC");
		return parent::find_all();
	}

	public function count_all_api($satker_id, $strict_in_satker = false)
	{

		$where_clause = '';
		if ($satker_id) {
			if (is_array($satker_id) && sizeof($satker_id) > 0) {
				$where_clause = 'AND ( 1=2 ';
				foreach ($satker_id as $u_id) {
					$where_clause .= ' OR "ESELON_1" = \'' . $u_id . '\' OR "ESELON_2" = \'' . $u_id . '\' OR "ESELON_3" = \'' . $u_id . '\' OR "ESELON_4" = \'' . $u_id . '\' ';
				}
				$where_clause .= ')';
			} else $where_clause = 'AND ("ESELON_1" = \'' . $satker_id . '\' OR "ESELON_2" = \'' . $satker_id . '\' OR "ESELON_3" = \'' . $satker_id . '\' OR "ESELON_4" = \'' . $satker_id . '\')';
		}
		$this->db->select('
			TRIM("PNS_ID") "PNS_ID",
			TRIM("NIP_LAMA") "NIP_LAMA",
			TRIM("NIP_BARU") "NIP_BARU",
			TRIM("NAMA") "NAMA",
			TRIM("GELAR_DEPAN") "GELAR_DEPAN",
			TRIM("GELAR_BELAKANG") "GELAR_BELAKANG",
			TRIM("TEMPAT_LAHIR_ID") "TEMPAT_LAHIR_ID",
			"TGL_LAHIR" "TGL_LAHIR",
			TRIM("JENIS_KELAMIN") "JENIS_KELAMIN",
			"AGAMA_ID" "AGAMA_ID",
			TRIM("JENIS_KAWIN_ID") "JENIS_KAWIN_ID",
			TRIM("NIK") "NIK",
			TRIM("NOMOR_DARURAT") "NOMOR_DARURAT",
			TRIM("NOMOR_HP") "NOMOR_HP",
			TRIM("EMAIL") "EMAIL",
			TRIM("ALAMAT") "ALAMAT",
			TRIM("NPWP") "NPWP",
			TRIM("BPJS") "BPJS",
			TRIM("JENIS_PEGAWAI_ID") "JENIS_PEGAWAI_ID",
			TRIM("KEDUDUKAN_HUKUM_ID") "KEDUDUKAN_HUKUM_ID",
			TRIM("STATUS_CPNS_PNS") "STATUS_CPNS_PNS",
			TRIM("KARTU_PEGAWAI") "KARTU_PEGAWAI",
			TRIM("NOMOR_SK_CPNS") "NOMOR_SK_CPNS",
			"TGL_SK_CPNS" "TGL_SK_CPNS",
			"TMT_CPNS" "TMT_CPNS",
			"TMT_PNS" "TMT_PNS",
			TRIM("GOL_AWAL_ID") "GOL_AWAL_ID",
			"GOL_ID" "GOL_ID",
			"TMT_GOLONGAN" "TMT_GOLONGAN",
			TRIM("MK_TAHUN") "MK_TAHUN",
			TRIM("MK_BULAN") "MK_BULAN",
			TRIM("JENIS_JABATAN_IDx") "JENIS_JABATAN_IDx",
			TRIM("JABATAN_ID") "JABATAN_ID",
			"TMT_JABATAN" "TMT_JABATAN",
			TRIM("PENDIDIKAN_ID") "PENDIDIKAN_ID",
			TRIM("NAMA_PENDIDIKAN") "PENDIDIKAN",
			TRIM("TK_PENDIDIKAN") "TK_PENDIDIKAN",
			TRIM("TINGKAT_PENDIDIKAN_NAMA") "TK_PENDIDIKAN_NAMA",
			TRIM("TAHUN_LULUS") "TAHUN_LULUS",
			TRIM("KPKN_ID") "KPKN_ID",
			TRIM("LOKASI_KERJA_ID") "LOKASI_KERJA_ID",
			TRIM("UNOR_ID") "UNOR_ID",
			TRIM("UNOR_INDUK_ID") "UNOR_INDUK_ID",
			TRIM("INSTANSI_INDUK_ID") "INSTANSI_INDUK_ID",
			TRIM("INSTANSI_KERJA_ID") "INSTANSI_KERJA_ID",
			TRIM("SATUAN_KERJA_INDUK_ID") "SATUAN_KERJA_INDUK_ID",
			TRIM("SATUAN_KERJA_KERJA_ID") "SATUAN_KERJA_KERJA_ID",
			TRIM("GOLONGAN_DARAH") "GOLONGAN_DARAH",
			TRIM("PHOTO") "PHOTO",
			"TMT_PENSIUN" "TMT_PENSIUN",
			TRIM("LOKASI_KERJA") "LOKASI_KERJA",
			TRIM("JML_ISTRI") "JML_ISTRI",
			TRIM("JML_ANAK") "JML_ANAK",
			TRIM("NO_SURAT_DOKTER") "NO_SURAT_DOKTER",
			"TGL_SURAT_DOKTER" "TGL_SURAT_DOKTER",
			TRIM("NO_BEBAS_NARKOBA") "NO_BEBAS_NARKOBA",
			"TGL_BEBAS_NARKOBA" "TGL_BEBAS_NARKOBA",
			TRIM("NO_CATATAN_POLISI") "NO_CATATAN_POLISI",
			"TGL_CATATAN_POLISI" "TGL_CATATAN_POLISI",
			TRIM("AKTE_KELAHIRAN") "AKTE_KELAHIRAN",
			TRIM("STATUS_HIDUP") "STATUS_HIDUP",
			TRIM("AKTE_MENINGGAL") "AKTE_MENINGGAL",
			"TGL_MENINGGAL" "TGL_MENINGGAL",
			TRIM("NO_ASKES") "NO_ASKES",
			TRIM("NO_TASPEN") "NO_TASPEN",
			"TGL_NPWP" "TGL_NPWP",
			TRIM("TEMPAT_LAHIR") "TEMPAT_LAHIR",

			TRIM("TEMPAT_LAHIR_NAMA") "TEMPAT_LAHIR_NAMA",
			
			TRIM("JENIS_JABATAN_NAMA") "JENIS_JABATAN_NAMA",
			TRIM("JABATAN_NAMA") "JABATAN_NAMA",
			
			TRIM("KPKN_NAMA") "KPKN_NAMA",
			TRIM("INSTANSI_INDUK_NAMA") "INSTANSI_INDUK_NAMA",
			TRIM("INSTANSI_KERJA_NAMA") "INSTANSI_KERJA_NAMA",
			TRIM("SATUAN_KERJA_INDUK_NAMA") "SATUAN_KERJA_INDUK_NAMA",
			TRIM("SATUAN_KERJA_NAMA") "SATUAN_KERJA_NAMA",
			
			TRIM("JABATAN_INSTANSI_ID") "JABATAN_INSTANSI_ID",
			TRIM("JABATAN_INSTANSI_NAMA") "JABATAN_INSTANSI_NAMA",
			"JENIS_JABATAN_ID" "JENIS_JABATAN_ID",
			
			"terminated_date" "terminated_date",
			"status_pegawai" "status_pegawai",
			TRIM("JABATAN_PPNPN") "JABATAN_PPNPN",
			
			TRIM("NAMA_JABATAN_REAL") "NAMA_JABATAN_REAL",
			TRIM("KATEGORI_JABATAN_REAL") "KATEGORI_JABATAN_REAL",
			"JENIS_JABATAN_REAL" "JENIS_JABATAN_REAL",

			"CREATED_DATE" "CREATED_DATE",
			"CREATED_BY" "CREATED_BY",
			"UPDATED_DATE" "UPDATED_DATE",
			"UPDATED_BY" "UPDATED_BY",
			TRIM("EMAIL_DIKBUD") "EMAIL_DIKBUD",
			TRIM("KODECEPAT") "KODECEPAT"
			,"NAMA_UNOR_FULL",
			"NAMA_GOLONGAN",
			"NAMA_PANGKAT",
			"NAMA_UNOR_ESELON_4",
			"NAMA_UNOR_ESELON_3",
			"NAMA_UNOR_ESELON_2",
			"NAMA_UNOR_ESELON_1"', false);
		 
		if ($strict_in_satker) {
			//$this->db->where('vw."ID" is not null');
		}

		$this->db->where('"PNS_AKTIF_ID" is not null', NULL, FALSE);
		$this->db->where('"KEDUDUKAN_HUKUM_ID" <> \'99\' and "KEDUDUKAN_HUKUM_ID" <> \'66\' and "KEDUDUKAN_HUKUM_ID" <> \'52\' and "KEDUDUKAN_HUKUM_ID" <> \'20\' and "KEDUDUKAN_HUKUM_ID" <> \'04\' ');
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);
		return parent::count_all();
	}

	public function find_download($satker_id = null, $strict_in_satker = false)
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
			mv_pegawai."ID",
			TRIM("PNS_ID") "PNS_ID",
			TRIM("GELAR_DEPAN") "GELAR_DEPAN",
			TRIM("GELAR_BELAKANG") "GELAR_BELAKANG",
			TRIM("NAMA") "NAMA",
			TRIM("NIP_LAMA") "NIP_LAMA",
			TRIM("NIP_BARU") "NIP_BARU",
			TRIM("JENIS_KELAMIN") "JENIS_KELAMIN",
			TRIM("TEMPAT_LAHIR_ID") "TEMPAT_LAHIR_ID",
			"TGL_LAHIR" "TGL_LAHIR",
			EXTRACT(YEAR from AGE("TGL_LAHIR")) as "UMUR",
			"TMT_PENSIUN" "TMT_PENSIUN",
			TRIM("PENDIDIKAN_ID") "PENDIDIKAN_ID",
			TRIM("NAMA_PENDIDIKAN") AS "NAMA_PENDIDIKAN",
			TRIM("NAMA_AGAMA") AS "NAMA_AGAMA",
			TRIM("JENIS_JABATAN_REAL") AS "JENIS_JABATAN",
			"KELAS_JABATAN",
			TRIM("NAMA_JABATAN_REAL") AS "NAMA_JABATAN",
			TRIM("NAMA_GOLONGAN") AS "NAMA_GOLONGAN",
			TRIM("NAMA_PANGKAT") as "NAMA_PANGKAT",
			"TMT_GOLONGAN" "TMT_GOLONGAN",
			TRIM("NAMA_UNOR_ESELON_4") as "NAMA_UNOR_ESELON_4",
			TRIM("NAMA_UNOR_ESELON_3") as "NAMA_UNOR_ESELON_3",
			TRIM("NAMA_UNOR_ESELON_2") as "NAMA_UNOR_ESELON_2",
			TRIM("NAMA_UNOR_ESELON_1") as "NAMA_UNOR_ESELON_1",
			TRIM("ESELON_ID") as "ESELON_ID",
			TRIM("KEDUDUKAN_HUKUM_NAMA") AS "KEDUDUKAN_HUKUM_NAMA",
			TRIM("NOMOR_SK_CPNS") "NOMOR_SK_CPNS",
			"TMT_CPNS" "TMT_CPNS",
			TRIM("UNOR_INDUK_NAMA") AS "NAMA_SATKER",
			TRIM("NIK") "NIK",
			TRIM("NOMOR_HP") "NOMOR_HP",
			TRIM("NOMOR_DARURAT") "NOMOR_DARURAT",
			TRIM("EMAIL") "EMAIL",
			TRIM("EMAIL_DIKBUD") "EMAIL_DIKBUD"
			', false);
		$this->db->where('"PNS_AKTIF_ID" is not null', NULL, FALSE);
		// $this->db->where('"KEDUDUKAN_HUKUM_ID" <> \'99\' and "KEDUDUKAN_HUKUM_ID" <> \'66\' and "KEDUDUKAN_HUKUM_ID" <> \'52\' and "KEDUDUKAN_HUKUM_ID" <> \'20\' and "KEDUDUKAN_HUKUM_ID" <> \'04\' ');
		// $this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);
		$this->db->order_by("NAMA", "ASC");
		return parent::find_all();
	}
	public function count_download($satker_id =null, $strict_in_satker = false)
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
		$this->db->where('"PNS_AKTIF_ID" is not null', NULL, FALSE);
		// $this->db->where('"KEDUDUKAN_HUKUM_ID" <> \'99\' and "KEDUDUKAN_HUKUM_ID" <> \'66\' and "KEDUDUKAN_HUKUM_ID" <> \'52\' and "KEDUDUKAN_HUKUM_ID" <> \'20\' and "KEDUDUKAN_HUKUM_ID" <> \'04\' ');
		// $this->db->where("(status_pegawai != 3 or status_pegawai is null) " . $where_clause);
		return parent::count_all();
	}

	public function rekap_diklat_pegawai($unor_id=null, $tahun=null, $nama=null){
		$this->db->select('mv_pegawai."ID",mv_pegawai."NAMA"', false);
		// $this->db->join("hris.rwt_diklat as rd", 'mv_pegawai."NIP_BARU"=rd."nip_baru"', "LEFT");
		// $this->db->join("hris.vw_unit_list as vul", 'mv_pegawai."UNOR_ID"=vul."ID"', "LEFT");
		
		// if($unor_id!=null){
		// 	$this->db->group_start();
		// 	$this->db->where('vul."ID"',$unor_id);	
		// 	$this->db->or_where('vul."ESELON_1"',$unor_id);	
		// 	$this->db->or_where('vul."ESELON_2"',$unor_id);	
		// 	$this->db->or_where('vul."ESELON_3"',$unor_id);	
		// 	$this->db->or_where('vul."ESELON_4"',$unor_id);	
		// 	$this->db->group_end();
		// }

		// if($tahun!=null){
		// 	$this->db->where('rd."tahun_diklat"',$tahun);
		// }

		// if($nama!=null){
		// 	$this->db->like('upper(mv_pegawai."NAMA")',strtoupper($nama),"BOTH");
		// }

		
		
		// $this->db->group_by('mv_pegawai."ID"');
		// $this->db->group_by('mv_pegawai."NAMA"');
		// $this->db->group_by('"PNS_ID"');
		// $this->db->group_by('rd."tahun_diklat"');
		
		// $this->db->group_by('mv_pegawai."NIP_BARU"');
		// $this->db->group_by('"UNOR_ID"');
		// $this->db->group_by('vul."NAMA_UNOR"');
		// $this->db->group_by('vul."ESELON_1"');
		// $this->db->group_by('vul."ESELON_2"');
		// $this->db->group_by('vul."ESELON_3"');
		// $this->db->group_by('vul."ESELON_4');
		return parent::find_all();

	} 

	public function count_rekap_diklat_pegawai($unor_id=null, $tahun=null, $nama=null){
		$this->db->select('"ID"', false);
		$this->db->join("rwt_diklat rd", 'mv_pegawai."NIP_BARU"=rd."nip_baru"', "LEFT");

		

		$this->db->group_by("ID");
		$this->db->group_by("PNS_ID");
		$this->db->group_by('mv_pegawai."NIP_BARU"');
		$this->db->group_by("UNOR_ID");
		$this->db->group_by("NAMA_UNOR_FULL");
		return parent::count_all();

	} 



}
