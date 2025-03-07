<?php defined('BASEPATH') || exit('No direct script access allowed');

class Riwayat_pendidikan_model extends BF_Model
{
	protected $table_name	= 'rwt_pendidikan';
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
			'field' => 'TINGKAT_PENDIDIKAN_ID',
			'label' => 'Jenjang Pendidikan',
			'rules' => 'required',
		),
		array(
			'field' => 'NAMA_SEKOLAH',
			'label' => 'NAMA Sekolah',
			'rules' => 'required',
		),
		array(
			'field' => 'TAHUN_LULUS',
			'label' => 'Tahun Lulus',
			'rules' => 'required',
		),
		array(
			'field' => 'NOMOR_IJASAH',
			'label' => 'Nomor Ijasah',
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
	public function find_all($PNS_ID = "")
	{

		if (empty($this->selects)) {
			$this->select($this->table_name . '.*,tk.NAMA as tk_pendidikan_text,pendidikan.NAMA AS NAMA_PENDIDIKAN');
		}
		if ($PNS_ID != "") {
			$this->db->where('PNS_ID', $PNS_ID);
		}
		$this->db->join('tkpendidikan as tk', 'tk.ID = rwt_pendidikan.TINGKAT_PENDIDIKAN_ID', 'left');
		$this->db->join('pendidikan', 'rwt_pendidikan.PENDIDIKAN_ID = pendidikan.ID', 'left');
		return parent::find_all();
	}
	public function find_all_by_nip_unorid($PNS_ID = "", $UNOR_ID = "")
	{

		$sql = 'WITH RECURSIVE r as (SELECT "ID" FROM hris.unitkerja WHERE "ID" in (\'' . $UNOR_ID . '\')
		UNION ALL
		SELECT a."ID" FROM hris.unitkerja a JOIN r on a."DIATASAN_ID" = r."ID")
		SELECT pendidikan.* FROM hris.pegawai pegawai JOIN r on pegawai."UNOR_ID" = r."ID"
		join hris.rwt_pendidikan pendidikan on pegawai."PNS_ID" = pendidikan."PNS_ID" where pegawai."NIP_BARU"=?';
		$query = $this->db->query($sql, array($PNS_ID));
		return $query->result();
	}
	public function count_all_by_nip_unorid($PNS_ID = "", $UNOR_ID = "")
	{

		$sql = 'WITH RECURSIVE r as (SELECT "ID" FROM hris.unitkerja WHERE "ID" in (\'' . $UNOR_ID . '\')
		UNION ALL
		SELECT a."ID" FROM hris.unitkerja a JOIN r on a."DIATASAN_ID" = r."ID")
		SELECT count(*) as jml FROM hris.pegawai pegawai JOIN r on pegawai."UNOR_ID" = r."ID"
		join hris.rwt_pendidikan pendidikan on pegawai."PNS_ID" = pendidikan."PNS_ID" where pegawai."NIP_BARU"=?';
		$query = $this->db->query($sql, array($PNS_ID));
		$row = $query->first_row();
		return $row->jml;
	}
}
