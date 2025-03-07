<?php defined('BASEPATH') || exit('No direct script access allowed');

class Sk_ds_model extends BF_Model
{
    protected $table_name	= 'tbl_file_ds';
	protected $key			= 'id_file';
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
			'field' => 'waktu_buat',
			'label' => 'lang:sk_ds_field_waktu_buat',
			'rules' => '',
		),
		array(
			'field' => 'kategori',
			'label' => 'lang:sk_ds_field_kategori',
			'rules' => 'max_length[100]',
		),
		array(
			'field' => 'teks_base64',
			'label' => 'lang:sk_ds_field_teks_base64',
			'rules' => '',
		),
		array(
			'field' => 'id_pegawai_ttd',
			'label' => 'lang:sk_ds_field_id_pegawai_ttd',
			'rules' => 'max_length[255]',
		),
		array(
			'field' => 'is_signed',
			'label' => 'lang:sk_ds_field_is_signed',
			'rules' => 'max_length[16]',
		),
		array(
			'field' => 'nip_sk',
			'label' => 'lang:sk_ds_field_nip_sk',
			'rules' => 'max_length[50]',
		),
		array(
			'field' => 'nomor_sk',
			'label' => 'lang:sk_ds_field_nomor_sk',
			'rules' => 'max_length[50]',
		),
		array(
			'field' => 'tgl_sk',
			'label' => 'lang:sk_ds_field_tgl_sk',
			'rules' => '',
		),
		array(
			'field' => 'tmt_sk',
			'label' => 'lang:sk_ds_field_tmt_sk',
			'rules' => '',
		),
		array(
			'field' => 'lokasi_file',
			'label' => 'lang:sk_ds_field_lokasi_file',
			'rules' => '',
		),
		array(
			'field' => 'is_corrected',
			'label' => 'lang:sk_ds_field_is_corrected',
			'rules' => 'max_length[16]',
		),
		array(
			'field' => 'catatan',
			'label' => 'lang:sk_ds_field_catatan',
			'rules' => '',
		),
	);
	protected $insert_validation_rules  = array();
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
    public function find_all($satker_id, $strict_in_satker = false)
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
		$this->db->select('id_file,nomor_sk,tgl_sk,tmt_sk,kategori,pegawai."ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"', false);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where('pegawai."ID" is not null '. $where_clause);
		$this->db->order_by("pegawai.NAMA", "ASC");
		return parent::find_all();
	}
	public function find_all_admin($satker_id, $strict_in_satker = false)
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
		$this->db->select('id_file,nomor_sk,tgl_sk,tmt_sk,
			kategori,
			ds_ok,
			nip_sk,
			nama_pemilik_sk,
			jabatan_pemilik_sk,
			unit_kerja_pemilik_sk,
			pegawai."ID",
			pegawai."NIP_BARU",
			pegawai."NAMA",vw."NAMA_UNOR_FULL",
			waktu_buat,
			tgl_tandatangan,
			golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN","is_signed"', false);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where('"id_file" is not null '. $where_clause);
		$this->db->order_by("(CASE WHEN tgl_tandatangan IS NULL THEN 1 ELSE 0 end)",FALSE);
		$this->db->order_by("tgl_tandatangan", "DESC");
		$this->db->order_by("tbl_file_ds.id", "DESC");
		return parent::find_all();
	}
	public function find_all_satker_ttd($satker_id, $strict_in_satker = false)
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
		$this->db->select('id_file,nomor_sk,tgl_sk,tmt_sk,kategori,ds_ok,
			pegawai."ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"
			,tgl_tandatangan
			', false);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where('pegawai."ID" is not null '. $where_clause);
		$this->db->where("is_signed",1);
		$this->db->where("ds_ok",1);
		$this->db->where("telah_kirim",1);
		$this->db->order_by("(CASE WHEN tgl_tandatangan IS NULL THEN 1 ELSE 0 end)",FALSE);
		$this->db->order_by("tgl_tandatangan", "DESC");
		$this->db->order_by("tbl_file_ds.id", "DESC");
		return parent::find_all();
	}
	public function find_all_pegawai_ttd($nip = "")
	{

		$where_clause = '';
		$this->db->select('id_file,nomor_sk,tgl_sk,tmt_sk,kategori,ds_ok,lokasi_file,
			pegawai."ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"', false);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where("pegawai.NIP_BARU",$nip);
		$this->db->where('pegawai."ID" is not null ');
		$this->db->where("is_signed",1);
		$this->db->where("ds_ok",1);
		$this->db->where("telah_kirim",1);
		$this->db->order_by("tbl_file_ds.id", "DESC");
		return parent::find_all();
	}
	public function count_all_pegawai_ttd($nip = "")
	{

		$where_clause = '';
		$this->db->select('id_file,nomor_sk,tgl_sk,tmt_sk,kategori,ds_ok,
			pegawai."ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN",
			"NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2",
			"NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"', false);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where("pegawai.NIP_BARU",$nip);
		$this->db->where('pegawai."ID" is not null ');
		$this->db->where("is_signed",1);
		$this->db->where("telah_kirim",1);
		$this->db->where("ds_ok",1);
		return parent::count_all();
	}
	public function find_all_blm_ttd($satker_id, $strict_in_satker = false,$NIP = "")
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
		$this->db->select('id_file,nomor_sk,tgl_sk,tmt_sk,kategori,pegawai."ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"', false);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join('pegawai P', 'tbl_file_ds.id_pegawai_ttd = P.PNS_ID', 'left');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');

		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where("P.NIP_BARU",$NIP);
		$this->db->where('pegawai."ID" is not null '. $where_clause);
		$this->db->where('is_signed','0');
		$this->db->where('is_corrected','1');
		$this->db->order_by("pegawai.NAMA", "ASC");
		return parent::find_all();
	}
	public function find_all_blm_ttd_new($satker_id, $strict_in_satker = false,$id_pegawai_ttd = "")
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
		$this->db->select('id_file,nomor_sk,tgl_sk,tmt_sk,kategori,
			nama_pemilik_sk,
			nip_sk,
			unit_kerja_pemilik_sk,
			waktu_buat,
			pegawai."ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"', false);
		 
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where("id_pegawai_ttd",$id_pegawai_ttd);
		// $this->db->where('pegawai."ID" is not null '. $where_clause);
		$this->db->where('tbl_file_ds."id_file" is not null '. $where_clause);
		$this->db->where('is_signed','0');
		$this->db->where('is_corrected','1');
		$this->db->where('ds_ok','1');
		$this->db->order_by("(CASE WHEN kategori ='SK PENEMPATAN PEGAWAI EKS. RISTEK' THEN 1 ELSE 0 end)",true);
		$this->db->order_by("tbl_file_ds.id", "DESC");
		return parent::find_all();
	}



	public function find_all_sudah_ttd($satker_id, $strict_in_satker = false,$id_pegawai_ttd = "")
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
		$this->db->select('id_file,nomor_sk,tgl_sk,tmt_sk,
			kategori,
			nip_sk,
			nama_pemilik_sk,
			unit_kerja_pemilik_sk,
			pegawai."ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"', false);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where("id_pegawai_ttd",$id_pegawai_ttd);
		// $this->db->where('pegawai."ID" is not null '. $where_clause);
		$this->db->where('tbl_file_ds."id_file" is not null '. $where_clause);
		$this->db->where('is_signed','1');
		$this->db->order_by("tbl_file_ds.id", "DESC");
		return parent::find_all();
	}
	public function count_all_blm_ttd($satker_id, $strict_in_satker = false,$NIP = "")
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
		$this->db->select('id_file,nomor_sk,tgl_sk,tmt_sk,kategori,pegawai."ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"', false);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join('pegawai P', 'tbl_file_ds.id_pegawai_ttd = P.PNS_ID', 'left');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where("P.NIP_BARU",$NIP);
		$this->db->where('pegawai."ID" is not null '. $where_clause);
		$this->db->where('is_signed','0');
		$this->db->where('is_corrected','1');
		return parent::count_all();
	}
	public function count_all_blm_new($satker_id, $strict_in_satker = false,$id_pegawai_ttd = "")
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
		$this->db->select('id_file,nomor_sk,tgl_sk,tmt_sk,kategori,pegawai."ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"', false);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where("id_pegawai_ttd",$id_pegawai_ttd);
		// $this->db->where('pegawai."ID" is not null '. $where_clause);
		$this->db->where('tbl_file_ds."id_file" is not null '. $where_clause);
		$this->db->where('is_signed','0');
		$this->db->where('is_corrected','1');
		$this->db->where('ds_ok','1');
		return parent::count_all();
	}
	public function count_all_sudah_ttd($satker_id, $strict_in_satker = false,$id_pegawai_ttd = "")
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
		$this->db->select('id_file,nomor_sk,tgl_sk,tmt_sk,kategori,pegawai."ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"', false);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where("id_pegawai_ttd",$id_pegawai_ttd);
		// $this->db->where('pegawai."ID" is not null '. $where_clause);
		$this->db->where('tbl_file_ds."id_file" is not null '. $where_clause);
		$this->db->where('is_signed','1');
		return parent::count_all();
	}
	public function find_all_validasi($NIP = "",$satker_id, $strict_in_satker = false)
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
		$this->db->select('tbl_file_ds.id_file,nomor_sk,tgl_sk,tmt_sk,kategori,pegawai."ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN",t."id" AS "id_table"', false);
		$this->db->join('tbl_file_ds_corrector t', 'tbl_file_ds.id_file = t.id_file', 'inner');
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join('pegawai P', 'P.PNS_ID = t.id_pegawai_korektor', 'inner');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		if($NIP != ""){
			$this->db->where("P.NIP_BARU",$NIP);
		}
		$this->db->where("t.is_corrected",2); // 2 = siap koreksi
		$this->db->where('ds_ok','1');
		//$this->db->where('(t.is_corrected = 0 or t.is_corrected is null)');
		$this->db->where('pegawai."ID" is not null '. $where_clause);
		$this->db->order_by("pegawai.NAMA", "ASC");
		return parent::find_all();
	}
	public function find_all_validasi_with_idpegawai($ID_PEGAWAI = "",$satker_id, $strict_in_satker = false)
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
		$this->db->select('tbl_file_ds.id_file,nomor_sk,tgl_sk,tmt_sk,kategori,
			nama_pemilik_sk,
			nip_sk,
			jabatan_pemilik_sk,
			unit_kerja_pemilik_sk,
			pegawai."ID",pegawai."NIP_BARU",
			pegawai."NAMA",vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN",t."id" AS "id_table"', false);
		$this->db->join('tbl_file_ds_corrector t', 'tbl_file_ds.id_file = t.id_file', 'inner');
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		//$this->db->join('pegawai P', 'P.PNS_ID = t.id_pegawai_korektor', 'inner');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		if($ID_PEGAWAI != ""){
			$this->db->where("t.id_pegawai_korektor",$ID_PEGAWAI);
		}
		$this->db->where("t.is_corrected",2); // 2 = siap koreksi
		$this->db->where('ds_ok','1');
		//$this->db->where('(t.is_corrected = 0 or t.is_corrected is null)');
		$this->db->where('tbl_file_ds."id_file" is not null '. $where_clause);
		//$this->db->where('pegawai."ID" is not null '. $where_clause);
		$this->db->order_by("pegawai.NAMA", "ASC");
		return parent::find_all();
	}
	public function count_all_validasi_with_idpegawai($ID_PEGAWAI = "",$satker_id, $strict_in_satker = false)
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
		$this->db->select('id_file,nomor_sk,tgl_sk,tmt_sk,kategori,pegawai."ID",
			pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN",
			"NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1",
			"KATEGORI_JABATAN"', false);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join('tbl_file_ds_corrector t', 'tbl_file_ds.id_file = t.id_file', 'inner');
		//$this->db->join('pegawai P', 'P.PNS_ID = t.id_pegawai_korektor', 'inner');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		if($ID_PEGAWAI != ""){
			$this->db->where("t.id_pegawai_korektor",$ID_PEGAWAI);
		}
		$this->db->where("t.is_corrected",2); // 2 = siap koreksi
		//$this->db->where('(t.is_corrected = 0 or t.is_corrected is null)');
		// $this->db->where('pegawai."ID" is not null '. $where_clause);
		$this->db->where('tbl_file_ds."id_file" is not null '. $where_clause);
		$this->db->where('ds_ok','1');
		return parent::count_all();
	}
	public function find_all_sudah_validasi($NIP = "",$satker_id, $strict_in_satker = false)
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
		$this->db->select('tbl_file_ds.id_file,nomor_sk,tgl_sk,tmt_sk,kategori,pegawai."ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"', false);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join('tbl_file_ds_corrector t', 'tbl_file_ds.id_file = t.id_file', 'inner');
		$this->db->join('pegawai P', 'P.PNS_ID = t.id_pegawai_korektor', 'inner');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		if($NIP != ""){
			$this->db->where("P.NIP_BARU",$NIP);
		}
		$this->db->where('t.is_corrected','1');
		$this->db->where('pegawai."ID" is not null '. $where_clause);
		$this->db->order_by("pegawai.NAMA", "ASC");
		$this->db->distinct('tbl_file_ds.id_file');
		return parent::find_all();
	}
	public function find_all_sudah_validasi_with_idpegawai($ID_PEGAWAI = "",$satker_id, $strict_in_satker = false)
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
		$this->db->select('tbl_file_ds.id_file,nomor_sk,tgl_sk,tmt_sk,
			nama_pemilik_sk,
			nip_sk,
			kategori,unit_kerja_pemilik_sk,
			pegawai."ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"', false);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join('tbl_file_ds_corrector t', 'tbl_file_ds.id_file = t.id_file', 'inner');
		//$this->db->join('pegawai P', 'P.PNS_ID = t.id_pegawai_korektor', 'inner');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		if($ID_PEGAWAI != ""){
			$this->db->where("t.id_pegawai_korektor",$ID_PEGAWAI);
		}
		$this->db->where('t.is_corrected','1');
		// $this->db->where('pegawai."ID" is not null '. $where_clause);
		$this->db->where('tbl_file_ds."id_file" is not null '. $where_clause);
		$this->db->order_by("pegawai.NAMA", "ASC");
		$this->db->distinct('tbl_file_ds.id_file');
		return parent::find_all();
	}
	public function find_all_antrian_validasi($ID_PEGAWAI = "")
	{
		return $this->db->query("
            SELECT '-' as no, kategori as KATEGORI, count(kategori) as JUMLAH FROM hris.tbl_file_ds WHERE kategori<>'< Semua >' and ds_ok=1 and is_signed=0 and COALESCE((SELECT hris.tbl_file_ds_corrector.id_pegawai_korektor FROM hris.tbl_file_ds_corrector WHERE hris.tbl_file_ds_corrector.id_file=hris.tbl_file_ds.id_file and (hris.tbl_file_ds_corrector.is_corrected=0 or hris.tbl_file_ds_corrector.is_corrected=2) limit 1),'-') like '".$ID_PEGAWAI."' GROUP BY kategori
            ")->result();
		
	}
	public function find_all_antrian_ttd($ID_PEGAWAI = "")
	{
		return $this->db->query("SELECT '-' as NO, kategori as KATEGORI, COUNT(kategori) as JUMLAH FROM hris.tbl_file_ds WHERE kategori<>'< Semua >' and ds_ok=1  and kategori Like '%' and is_signed=0 and  (SELECT hris.tbl_file_ds_corrector.id_file FROM hris.tbl_file_ds_corrector WHERE hris.tbl_file_ds_corrector.id_file=hris.tbl_file_ds.id_file and (hris.tbl_file_ds_corrector.is_corrected='0' or hris.tbl_file_ds_corrector.is_corrected='2') limit 1) is null  and id_pegawai_ttd like '".$ID_PEGAWAI."'  GROUP BY kategori")->result();
		
	}
	public function count_all_validasi($NIP = "",$satker_id, $strict_in_satker = false)
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
		$this->db->select('id_file,nomor_sk,tgl_sk,tmt_sk,kategori,pegawai."ID",
			pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN",
			"NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1",
			"KATEGORI_JABATAN"', false);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join('tbl_file_ds_corrector t', 'tbl_file_ds.id_file = t.id_file', 'inner');
		$this->db->join('pegawai P', 'P.PNS_ID = t.id_pegawai_korektor', 'inner');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		if($NIP != ""){
			$this->db->where("P.NIP_BARU",$NIP);
		}
		$this->db->where("t.is_corrected",2); // 2 = siap koreksi
		//$this->db->where('(t.is_corrected = 0 or t.is_corrected is null)');
		$this->db->where('pegawai."ID" is not null '. $where_clause);
		$this->db->where('ds_ok','1');
		return parent::count_all();
	}
	public function count_all_sudah_validasi($NIP = "",$satker_id, $strict_in_satker = false)
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
		$this->db->select('id_file,nomor_sk,tgl_sk,tmt_sk,kategori,pegawai."ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"', false);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join('tbl_file_ds_corrector t', 'tbl_file_ds.id_file = t.id_file', 'inner');
		$this->db->join('pegawai P', 'P.PNS_ID = t.id_pegawai_korektor', 'inner');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		if($NIP != ""){
			$this->db->where("P.NIP_BARU",$NIP);
		}
		$this->db->where('t.is_corrected','1');
		$this->db->where('pegawai."ID" is not null '. $where_clause);
		
		return parent::count_all();
	}
	public function count_all_sudah_validasi_with_pns_id($ID_PEGAWAI = "",$satker_id, $strict_in_satker = false)
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
		$this->db->select('id_file,nomor_sk,tgl_sk,tmt_sk,kategori,pegawai."ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"', false);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join('tbl_file_ds_corrector t', 'tbl_file_ds.id_file = t.id_file', 'inner');
		//$this->db->join('pegawai P', 'P.PNS_ID = t.id_pegawai_korektor', 'inner');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		if($ID_PEGAWAI != ""){
			$this->db->where("t.id_pegawai_korektor",$ID_PEGAWAI);
		}
		$this->db->where('t.is_corrected','1');
		// $this->db->where('pegawai."ID" is not null '. $where_clause);
		$this->db->where('tbl_file_ds."id_file" is not null '. $where_clause);
		return parent::count_all();
	}
	public function count_all_dikoreksi($satker_id, $strict_in_satker = false)
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
		$this->db->select('id_file,nomor_sk,tgl_sk,tmt_sk,kategori,pegawai."ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"', false);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where('"id_file" is not null '. $where_clause);
		$this->db->where('is_corrected','3');
		return parent::count_all();
	}
	public function find_all_dikoreksi($satker_id, $strict_in_satker = false)
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
		$this->db->select('id_file,nomor_sk,tgl_sk,tmt_sk,kategori,pegawai."ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"', false);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where('"id_file" is not null '. $where_clause);
		$this->db->where('is_corrected','3');
		$this->db->order_by("pegawai.NAMA", "ASC");
		return parent::find_all();
	}
	public function count_all($satker_id, $strict_in_satker = false)
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
		$this->db->select('nomor_sk,tgl_sk,tmt_sk,kategori,pegawai."ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"', false);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where('pegawai."ID" is not null '. $where_clause);
		return parent::count_all();
	}
	public function count_all_admin($satker_id, $strict_in_satker = false)
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
		$this->db->select('nomor_sk,tgl_sk,tmt_sk,kategori,pegawai."ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"', false);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where('"id_file" is not null '. $where_clause);
		return parent::count_all();
	}
	public function count_all_satker_ttd($satker_id, $strict_in_satker = false)
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
		$this->db->select('nomor_sk,tgl_sk,tmt_sk,kategori,pegawai."ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"', false);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where('pegawai."ID" is not null '. $where_clause);
		$this->db->where("is_signed",1);
		$this->db->where("ds_ok",1);
		$this->db->where("telah_kirim",1);
		return parent::count_all();
	}
	public function count_all_pegawai($nip = "")
	{

		 
		$this->db->select('nomor_sk,tgl_sk,tmt_sk,kategori,pegawai."ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"', false);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where('pegawai."ID" is not null ');
		$this->db->where("pegawai.NIP_BARU",$nip);
		$this->db->where("is_signed",1);
		return parent::count_all();
	}
	public function find_all_api($satker_id, $strict_in_satker = false,$id_pegawai_ttd = "")
	{

		$where_clause = '';
		if ($satker_id and $satker_id != "") {
			if (is_array($satker_id) && sizeof($satker_id) > 0) {
				$where_clause = 'AND ( 1=2 ';
				foreach ($satker_id as $u_id) {
					$where_clause .= ' OR vw."ESELON_1" = \'' . $u_id . '\' OR vw."ESELON_2" = \'' . $u_id . '\' OR vw."ESELON_3" = \'' . $u_id . '\' OR vw."ESELON_4" = \'' . $u_id . '\' ';
				}
				$where_clause .= ')';
			} else $where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
		}
		$this->db->select('
			trim(id_file) as id_file,
			trim(nomor_sk) as nomor_sk,
			nama_pemilik_sk,
			tgl_sk,
			tmt_sk,
			trim(kategori) as kategori,
			pegawai."ID" ,
			trim(pegawai."NIP_BARU") AS NIP_BARU,
			
			trim(pegawai."NAMA") AS NAMA,
			trim(pegawai."PHOTO") as PHOTO,
			trim(vw."NAMA_UNOR_FULL") as "NAMA_UNOR_FULL"
			,golongan."NAMA" AS "NAMA_GOLONGAN",
			"NAMA_PANGKAT","NAMA_UNOR_ESELON_4",
			LOKASI_FILE,
			"NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"', false);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		//if($NIP != ""){
		//$this->db->where("P.NIP_BARU",$NIP);
		$this->db->where("id_pegawai_ttd",$id_pegawai_ttd);
		//}
		$this->db->where('is_signed',0);
		$this->db->where('is_corrected',1);
		$this->db->where('ds_ok','1');
		// $this->db->where('pegawai."ID" is not null '. $where_clause);
		$this->db->where('tbl_file_ds."id_file" is not null '. $where_clause);
		$this->db->order_by("pegawai.NAMA", "ASC");
		return parent::find_all();
	}
	public function find_all_sdh_ttd_api($satker_id, $strict_in_satker = false,$id_pegawai_ttd)
	{

		$where_clause = '';
		if ($satker_id and $satker_id != "") {
			if (is_array($satker_id) && sizeof($satker_id) > 0) {
				$where_clause = 'AND ( 1=2 ';
				foreach ($satker_id as $u_id) {
					$where_clause .= ' OR vw."ESELON_1" = \'' . $u_id . '\' OR vw."ESELON_2" = \'' . $u_id . '\' OR vw."ESELON_3" = \'' . $u_id . '\' OR vw."ESELON_4" = \'' . $u_id . '\' ';
				}
				$where_clause .= ')';
			} else $where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
		}
		$this->db->select('
			trim(id_file) as id_file,
			trim(nomor_sk) as nomor_sk,
			nama_pemilik_sk,
			tgl_sk,
			tmt_sk,
			trim(kategori) as kategori,
			pegawai."ID" ,
			trim(pegawai."NIP_BARU") AS NIP_BARU,
			trim(pegawai."NAMA") AS NAMA,
			trim(pegawai."PHOTO") as PHOTO,
			trim(vw."NAMA_UNOR_FULL") as "NAMA_UNOR_FULL"
			,golongan."NAMA" AS "NAMA_GOLONGAN",
			"NAMA_PANGKAT","NAMA_UNOR_ESELON_4",
			LOKASI_FILE,
			"NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"', false);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		//$this->db->join('pegawai P', 'tbl_file_ds.id_pegawai_ttd = P.PNS_ID', 'left');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where('is_signed',1);
		//$this->db->where("P.NIP_BARU",$NIP);
		$this->db->where("id_pegawai_ttd",$id_pegawai_ttd);
		//$this->db->where('is_corrected',1);
		$this->db->where('ds_ok','1');
		// $this->db->where('pegawai."ID" is not null '. $where_clause);
		$this->db->where('tbl_file_ds."id_file" is not null '. $where_clause);
		$this->db->order_by("pegawai.NAMA", "ASC");
		return parent::find_all();
	}
	public function find_all_api_blm_koreksi($NIP = "",$satker_id, $strict_in_satker = false)
	{

		$where_clause = '';
		if ($satker_id and $satker_id != "") {
			if (is_array($satker_id) && sizeof($satker_id) > 0) {
				$where_clause = 'AND ( 1=2 ';
				foreach ($satker_id as $u_id) {
					$where_clause .= ' OR vw."ESELON_1" = \'' . $u_id . '\' OR vw."ESELON_2" = \'' . $u_id . '\' OR vw."ESELON_3" = \'' . $u_id . '\' OR vw."ESELON_4" = \'' . $u_id . '\' ';
				}
				$where_clause .= ')';
			} else $where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
		}
		$this->db->select('
			t.id as id_table,
			trim(t.id_file) as id_file,
			trim(nomor_sk) as nomor_sk,
			nama_pemilik_sk,
			tgl_sk,
			tmt_sk,
			trim(kategori) as kategori,
			pegawai."ID" ,
			trim(pegawai."NIP_BARU") AS NIP_BARU,
			trim(pegawai."NAMA") AS NAMA,
			trim(pegawai."PHOTO") as PHOTO,
			trim(vw."NAMA_UNOR_FULL") as "NAMA_UNOR_FULL"
			,golongan."NAMA" AS "NAMA_GOLONGAN",
			"NAMA_PANGKAT","NAMA_UNOR_ESELON_4",
			LOKASI_FILE,
			"NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"', false);
		$this->db->join('tbl_file_ds_corrector t', 'tbl_file_ds.id_file = t.id_file', 'inner');
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join('pegawai P', 'P.PNS_ID = t.id_pegawai_korektor', 'inner');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');

		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where("P.NIP_BARU",$NIP);
		// $this->db->where('pegawai."ID" is not null '. $where_clause);
		$this->db->where('tbl_file_ds."id_file" is not null '. $where_clause);
		$this->db->where('t.is_corrected',2);// siap koreksi
		$this->db->where('ds_ok','1');
		$this->db->order_by("pegawai.NAMA", "ASC");
		return parent::find_all();
	}
	public function find_all_api_sdh_koreksi($NIP = "",$satker_id, $strict_in_satker = false)
	{

		$where_clause = '';
		if ($satker_id and $satker_id != "") {
			if (is_array($satker_id) && sizeof($satker_id) > 0) {
				$where_clause = 'AND ( 1=2 ';
				foreach ($satker_id as $u_id) {
					$where_clause .= ' OR vw."ESELON_1" = \'' . $u_id . '\' OR vw."ESELON_2" = \'' . $u_id . '\' OR vw."ESELON_3" = \'' . $u_id . '\' OR vw."ESELON_4" = \'' . $u_id . '\' ';
				}
				$where_clause .= ')';
			} else $where_clause = 'AND (vw."ESELON_1" = \'' . $satker_id . '\' OR vw."ESELON_2" = \'' . $satker_id . '\' OR vw."ESELON_3" = \'' . $satker_id . '\' OR vw."ESELON_4" = \'' . $satker_id . '\')';
		}
		$this->db->select('
			t.id as id_table,
			trim(t.id_file) as id_file,
			trim(nomor_sk) as nomor_sk,
			nama_pemilik_sk,
			tgl_sk,
			tmt_sk,
			trim(kategori) as kategori,
			pegawai."ID" ,
			trim(pegawai."NIP_BARU") AS NIP_BARU,
			trim(pegawai."NAMA") AS NAMA,
			trim(pegawai."PHOTO") as PHOTO,
			trim(vw."NAMA_UNOR_FULL") as "NAMA_UNOR_FULL"
			,golongan."NAMA" AS "NAMA_GOLONGAN",
			"NAMA_PANGKAT","NAMA_UNOR_ESELON_4",
			LOKASI_FILE,
			"NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"', false);
		$this->db->join('tbl_file_ds_corrector t', 'tbl_file_ds.id_file = t.id_file', 'inner');
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join('pegawai P', 'P.PNS_ID = t.id_pegawai_korektor', 'inner');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where("P.NIP_BARU",$NIP);
		$this->db->where('t.is_corrected',1);
		$this->db->where('ds_ok','1');
		$this->db->order_by("pegawai.NAMA", "ASC");
		return parent::find_all();
	}
	public function count_all_api($satker_id, $strict_in_satker = false,$id_pegawai_ttd = "")
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
		$this->db->select('nomor_sk,tgl_sk,tmt_sk,kategori,pegawai."ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"', false);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}

		//$this->db->where("P.NIP_BARU",$NIP);
		$this->db->where("id_pegawai_ttd",$id_pegawai_ttd);
		
		$this->db->where('is_signed',0);
		$this->db->where('is_corrected',1);
		$this->db->where('ds_ok','1');
		$this->db->where('pegawai."ID" is not null '. $where_clause);
		return parent::count_all();
	}
	public function count_all_sdhttd_api($satker_id, $strict_in_satker = false,$id_pegawai_ttd)
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
		$this->db->select('nomor_sk,tgl_sk,tmt_sk,kategori,pegawai."ID",pegawai."NIP_BARU",pegawai."NAMA",vw."NAMA_UNOR_FULL",golongan."NAMA" AS "NAMA_GOLONGAN","NAMA_PANGKAT","NAMA_UNOR_ESELON_4","NAMA_UNOR_ESELON_3","NAMA_UNOR_ESELON_2","NAMA_UNOR_ESELON_1","KATEGORI_JABATAN"', false);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join('pegawai P', 'tbl_file_ds.id_pegawai_ttd = P.PNS_ID', 'left');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where('is_signed',1);
		$this->db->where('ds_ok','1');
		//$this->db->where('is_corrected',1);

		//$this->db->where("P.NIP_BARU",$NIP);
		$this->db->where("id_pegawai_ttd",$id_pegawai_ttd);
		// $this->db->where('pegawai."ID" is not null '. $where_clause);
		$this->db->where('tbl_file_ds."id_file" is not null '. $where_clause);
		return parent::count_all();
	}
	public function count_all_api_blmkoreksi($NIP = "",$satker_id, $strict_in_satker = false)
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
		$this->db->join('tbl_file_ds_corrector t', 'tbl_file_ds.id_file = t.id_file', 'inner');
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join('pegawai P', 'P.PNS_ID = t.id_pegawai_korektor', 'inner');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where("P.NIP_BARU",$NIP);
		$this->db->where("t.is_corrected",2); // 2 = siap koreksi
		$this->db->where('ds_ok','1');
		// $this->db->where('pegawai."ID" is not null '. $where_clause);
		$this->db->where('tbl_file_ds."id_file" is not null '. $where_clause);
		return parent::count_all();
	}
	public function count_all_api_sdhkoreksi($NIP = "",$satker_id, $strict_in_satker = false)
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
		$this->db->join('tbl_file_ds_corrector t', 'tbl_file_ds.id_file = t.id_file', 'inner');
		$this->db->join('pegawai', 'pegawai.NIP_BARU = tbl_file_ds.nip_sk', 'left');
		$this->db->join('pegawai P', 'P.PNS_ID = t.id_pegawai_korektor', 'inner');
		$this->db->join("vw_unit_list as vw", "pegawai.\"UNOR_ID\"=vw.\"ID\" $where_clause ", 'left', false);
		$this->db->join('golongan', 'pegawai.GOL_ID = golongan.ID', 'left');
		$this->db->join('jabatan', 'pegawai.JABATAN_INSTANSI_ID = jabatan.KODE_JABATAN', 'left');
		
		if ($strict_in_satker) {
			$this->db->where('vw."ID" is not null');
		}
		$this->db->where("P.NIP_BARU",$NIP);
		$this->db->where('t.is_corrected',1);
		$this->db->where('ds_ok','1');
		// $this->db->where('pegawai."ID" is not null '. $where_clause);
		$this->db->where('tbl_file_ds."id_file" is not null '. $where_clause);
		return parent::count_all();
	}

	public function get_minimal_ds($nip=""){
		$this->db->select('nomor_sk,tgl_sk,tmt_sk,kategori,telah_kirim,is_corrected,is_returned,is_signed,lokasi_file', false);
		$this->db->where("nip_sk",$nip);
		return parent::find_all();
	}

}