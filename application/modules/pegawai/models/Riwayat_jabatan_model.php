<?php defined('BASEPATH') || exit('No direct script access allowed');

class Riwayat_jabatan_model extends BF_Model
{
	protected $table_name	= 'rwt_jabatan';
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
			'rules' => 'max_length[100]|required',
		),
		array(
			'field' => 'NOMOR_SK',
			'label' => 'NO SK',
			'rules' => 'required',
		),
		array(
			'field' => 'TANGGAL_SK',
			'label' => 'TANGGAL SK',
			'rules' => 'required',
		),
		array(
			'field' => 'TMT_JABATAN',
			'label' => 'TMT JABATAN',
			'rules' => 'required',
		),
		array(
			'field' => 'ID_JENIS_JABATAN',
			'label' => 'JENIS JABATAN',
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
			$this->select($this->table_name . '.*,satker."NAMA_UNOR" AS "NAMA_SATKER", unit."NAMA_UNOR",unit."ID" as "UNIT_ID"');
		}
		if ($PNS_ID != "") {
			$this->db->where('PNS_ID', $PNS_ID);
		}
		$this->db->join("hris.unitkerja as unit", "rwt_jabatan.ID_UNOR=unit.KODE_INTERNAL", 'left');
		$this->db->join("hris.unitkerja as satker", 'unit.UNOR_INDUK=satker.ID', 'left');
		return parent::find_all();
	}
	public function update($id = "", $data = "", $posts = null)
	{
		if (isset($posts['IS_ACTIVE']) and $posts['IS_ACTIVE'] == "1") {
			$dataupdate = array();
			$dataupdate["IS_ACTIVE"] = '';
			$this->riwayat_jabatan_model->update_where("PNS_ID", $posts["PNS_ID"], $dataupdate);
			// update jadi active yang terpilih
			$dataupdate = array();
			$rec_jenis  = $this->jenis_jabatan_model->find($posts["ID_JENIS_JABATAN"]);
			$dataupdate["JENIS_JABATAN_ID"] = $posts["ID_JENIS_JABATAN"];
			$dataupdate["JENIS_JABATAN_NAMA"] = $rec_jenis->NAMA;
			$dataupdate['JABATAN_INSTANSI_ID']	= $posts['ID_JABATAN'];
			$rec_jabatan = $this->jabatan_model->find_by("KODE_JABATAN", trim($posts["ID_JABATAN"]));
			$dataupdate["JABATAN_INSTANSI_NAMA"] 	= $rec_jabatan->NAMA_JABATAN;
			$dataupdate['TMT_JABATAN']				= $posts['TMT_JABATAN'];
			$this->pegawai_model->update_where("PNS_ID", $posts["PNS_ID"], $dataupdate);

			// update tabel unirkerja jika pilihan adalah pejabat struktural
			if ($posts["ID_JENIS_JABATAN"] == "1") {
				$adata = array();
				$this->pegawai_model->where("PNS_ID", $posts["PNS_ID"]);
				$pegawai_data = $this->pegawai_model->find_first_row();
				$adata["NAMA_PEJABAT"] = $pegawai_data->NAMA;
				$adata["PEMIMPIN_PNS_ID"] = trim($posts["PNS_ID"]);
				$this->unitkerja_model->update_where("ID", TRIM($posts["ID_UNOR"]), $adata);
				//die($posts["ID_UNOR").$pegawai_data->NAMA."masuk");
			}
		}
		return parent::update($id, $data);
	}

	/*public function insert($data,$posts){
		if(isset($posts['IS_ACTIVE']) and $posts['IS_ACTIVE'] == "1"){
			// update semua jadi inactive
			$dataupdate = array();
        	$dataupdate["IS_ACTIVE"] = '0';
			$this->riwayat_jabatan_model->update_where("PNS_ID",$posts["PNS_ID"], $dataupdate);
			// update jadi active yang terpilih
			$dataupdate = array();
			$rec_jenis = $this->jenis_jabatan_model->find($posts["ID_JENIS_JABATAN"]);
        	$dataupdate["JENIS_JABATAN_ID"] = $posts["ID_JENIS_JABATAN"];
			$dataupdate["JENIS_JABATAN_NAMA"] = $rec_jenis->NAMA;
			$dataupdate['JABATAN_INSTANSI_ID']	= $posts['ID_JABATAN'];
			$rec_jabatan = $this->jabatan_model->find_by("KODE_JABATAN",trim($posts["ID_JABATAN"]));
        	$dataupdate["JABATAN_INSTANSI_NAMA"] 	= $rec_jabatan->NAMA_JABATAN;
        	$dataupdate['TMT_JABATAN']				= $posts['TMT_JABATAN'];
			$this->pegawai_model->update_where("PNS_ID",$posts["PNS_ID"], $dataupdate);
			
			// update tabel unirkerja jika pilihan adalah pejabat struktural
			if($posts["ID_JENIS_JABATAN"] == "1"){
			   $adata = array();
			   $this->pegawai_model->where("PNS_ID",$posts["PNS_ID"]);
			   $pegawai_data = $this->pegawai_model->find_first_row();  
			   $adata["NAMA_PEJABAT"] = $pegawai_data->NAMA;
			   $adata["PEMIMPIN_PNS_ID"] = trim($posts["PNS_ID"]);
			   $this->unitkerja_model->update_where("ID",TRIM($posts["ID_UNOR"]), $adata);
			   //die($posts["ID_UNOR").$pegawai_data->NAMA."masuk");
			}
			// end
			
		}
		return parent::insert($data);
	}*/

	public function insert($data, $posts)
	{
		if (isset($data['IS_ACTIVE']) and $data['IS_ACTIVE'] == "1") {

			// update semua jadi inactive
			$dataupdate = array();
			$dataupdate["IS_ACTIVE"] = '0';
			$this->riwayat_jabatan_model->update_where("PNS_ID", $data["PNS_ID"], $dataupdate);

			// update jadi active yang terpilih
			$dataupdate = array();
			//$rec_jenis = $this->jenis_jabatan_model->find($data["ID_JENIS_JABATAN"]);
			//$rec_jenis = $this->jenis_jabatan_model->find_by("ID", $data["ID_JENIS_JABATAN"]);
			$dataupdate["JENIS_JABATAN_ID"] = $data["ID_JENIS_JABATAN"];
			//$dataupdate["JENIS_JABATAN_NAMA"] = $rec_jenis->NAMA;
			$dataupdate['JABATAN_INSTANSI_ID']	= $data['ID_JABATAN'];
			//$rec_jabatan = $this->jabatan_model->find_by("KODE_JABATAN",trim($data["ID_JABATAN"]));
			//$dataupdate["JABATAN_INSTANSI_NAMA"] = $rec_jabatan->NAMA_JABATAN;
			//$this->pegawai_model->update_where("PNS_ID",$data["PNS_ID"], $dataupdate);

			// update tabel unirkerja jika pilihan adalah pejabat struktural
			if ($data["ID_JENIS_JABATAN"] == "1") {
				$adata = array();
				$this->pegawai_model->where("PNS_ID", $data["PNS_ID"]);
				$pegawai_data = $this->pegawai_model->find_first_row();
				$adata["NAMA_PEJABAT"] = $pegawai_data->NAMA;
				$adata["PEMIMPIN_PNS_ID"] = trim($data["PNS_ID"]);
				$this->unitkerja_model->update_where("ID", TRIM($data["ID_UNOR"]), $adata);
				//die($posts["ID_UNOR").$pegawai_data->NAMA."masuk");
			}
			// end

		}
		return parent::insert($data);
	}
	public function find_first_row()
	{
		return $this->db->get($this->db->schema . "." . $this->table_name)->first_row();
	}
	public function insertmandiri($data, $posts)
	{
		return parent::insert($data);
	}
	public function updatemandiri($id, $data, $posts)
	{
		return parent::update($id, $data);
	}
	// ADD RIWAYAT JABATAN UNTUK API
	public function find_all_api($tahun = "", $bulan = "")
	{

		if (empty($this->selects)) {
			$this->select($this->table_name . '.*,satker."NAMA_UNOR" AS "NAMA_SATKER", unit."NAMA_UNOR",unit."ID" as "UNIT_ID"');
			//$this->select($this->table_name .'.STATUS_SATKER');
		}
		if ($tahun != "" and $bulan != "") {
			$this->db->where("EXTRACT(YEAR FROM \"TMT_JABATAN\") = '" . $tahun . "'");
			$this->db->where("EXTRACT(MONTH FROM \"TMT_JABATAN\") = '" . $bulan . "'");
		}
		$this->db->where("(\"STATUS_SATKER\" != 0 or \"STATUS_SATKER\" IS NULL)");
		$this->db->where("(\"STATUS_BIRO\" != 0 or \"STATUS_BIRO\" IS NULL)");
		$this->db->join("hris.unitkerja as unit", "rwt_jabatan.ID_UNOR=unit.KODE_INTERNAL", 'left');
		$this->db->join("hris.unitkerja as satker", 'unit.UNOR_INDUK=satker.ID', 'left');
		return parent::find_all();
	}
	public function count_all_api($tahun = "", $bulan = "")
	{

		if (empty($this->selects)) {
			$this->select($this->table_name . '.*,satker."NAMA_UNOR" AS "NAMA_SATKER", unit."NAMA_UNOR",unit."ID" as "UNIT_ID"');
		}
		if ($tahun != "" and $bulan != "") {
			$this->db->where("EXTRACT(YEAR FROM \"TMT_JABATAN\") = '" . $tahun . "'");
			$this->db->where("EXTRACT(MONTH FROM \"TMT_JABATAN\") = '" . $bulan . "'");
		}
		$this->db->where("(\"STATUS_SATKER\" != 0 or \"STATUS_SATKER\" IS NULL)");
		$this->db->where("(\"STATUS_BIRO\" != 0 or \"STATUS_BIRO\" IS NULL)");
		$this->db->join("hris.unitkerja as unit", "rwt_jabatan.ID_UNOR=unit.KODE_INTERNAL", 'left');
		$this->db->join("hris.unitkerja as satker", 'unit.UNOR_INDUK=satker.ID', 'left');
		return parent::count_all();
	}
	public function find_all_nip($PNS_NIP = "")
	{

		if (empty($this->selects)) {
			$this->select(
				'ID_BKN,
				TRIM("PNS_ID") "PNS_ID",
				TRIM("PNS_NIP") "PNS_NIP",
				TRIM("PNS_NAMA") "PNS_NAMA",
				TRIM("ID_UNOR") "ID_UNOR",
				TRIM("UNOR") "UNOR",
				TRIM("ID_JENIS_JABATAN") "ID_JENIS_JABATAN",
				TRIM("JENIS_JABATAN") "JENIS_JABATAN",
				TRIM("ID_JABATAN") "ID_JABATAN",
				TRIM("rwt_jabatan"."NAMA_JABATAN") "NAMA_JABATAN",
				TRIM("ID_ESELON") "ID_ESELON",
				TRIM("ESELON") "ESELON",
				TMT_JABATAN "TMT_JABATAN",
				TRIM("NOMOR_SK") "NOMOR_SK",
				TANGGAL_SK "TANGGAL_SK",
				TRIM("ID_SATUAN_KERJA") "ID_SATUAN_KERJA",
				TMT_PELANTIKAN "TMT_PELANTIKAN",
				TRIM("IS_ACTIVE") "IS_ACTIVE",
				TRIM("ESELON1") "ESELON1",
				TRIM("ESELON2") "ESELON2",
				TRIM("ESELON3") "ESELON3",
				TRIM("ESELON4") "ESELON4",
				TRIM("CATATAN") "CATATAN",
				TRIM("JENIS_SK") "JENIS_SK",
				LAST_UPDATED "LAST_UPDATED",
				STATUS_SATKER "STATUS_SATKER",
				STATUS_BIRO "STATUS_BIRO",
				TRIM("ID_JABATAN_BKN") "ID_JABATAN_BKN",
				TRIM("ID_UNOR_BKN") "ID_UNOR_BKN",
				"JABATAN_TERAKHIR" "JABATAN_TERAKHIR",
				satker."NAMA_UNOR" AS "NAMA_SATKER", unit."NAMA_UNOR",unit."ID" as "UNIT_ID"'
			);
			//$this->select($this->table_name .'.STATUS_SATKER');
		}
		if ($PNS_NIP != "") {
			$this->db->where('PNS_NIP', $PNS_NIP);
		}
		$this->db->where("(\"STATUS_SATKER\" != 0 or \"STATUS_SATKER\" IS NULL)");
		$this->db->where("(\"STATUS_BIRO\" != 0 or \"STATUS_BIRO\" IS NULL)");
		$this->db->join("hris.unitkerja as unit", "rwt_jabatan.ID_UNOR=unit.KODE_INTERNAL", 'left');
		$this->db->join("hris.unitkerja as satker", 'unit.UNOR_INDUK=satker.ID', 'left');
		return parent::find_all();
	}
	public function find_all_by_nip_unorid($PNS_ID = "", $UNOR_ID = "")
	{

		$sql = 'WITH RECURSIVE r as (SELECT "ID" FROM hris.unitkerja WHERE "ID" in (\'' . $UNOR_ID . '\')
		UNION ALL
		SELECT a."ID" FROM hris.unitkerja a JOIN r on a."DIATASAN_ID" = r."ID")
		SELECT jabatan.* FROM hris.pegawai pegawai JOIN r on pegawai."UNOR_ID" = r."ID"
		join hris.rwt_jabatan jabatan on pegawai."NIP_BARU" = jabatan."PNS_NIP" where pegawai."NIP_BARU"=?';
		$query = $this->db->query($sql, array($PNS_ID));
		return $query->result();
	}
	public function count_all_by_nip_unorid($PNS_ID = "", $UNOR_ID = "")
	{

		$sql = 'WITH RECURSIVE r as (SELECT "ID" FROM hris.unitkerja WHERE "ID" in (\'' . $UNOR_ID . '\')
		UNION ALL
		SELECT a."ID" FROM hris.unitkerja a JOIN r on a."DIATASAN_ID" = r."ID")
		SELECT count(*) as jml FROM hris.pegawai pegawai JOIN r on pegawai."UNOR_ID" = r."ID"
		join hris.rwt_jabatan jabatan on pegawai."NIP_BARU" = jabatan."PNS_NIP" where pegawai."NIP_BARU"=?';
		$query = $this->db->query($sql, array($PNS_ID));
		$row = $query->first_row();
		return $row->jml;
	}
}
