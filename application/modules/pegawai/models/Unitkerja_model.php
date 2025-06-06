<?php defined('BASEPATH') || exit('No direct script access allowed');

class Unitkerja_model extends BF_Model
{
	protected $table_name	= 'unitkerja';
	protected $key			= 'ID';
	protected $date_format	= 'datetime';

	protected $log_user 	= false;
	protected $set_created	= false;
	protected $set_modified = false;
	protected $soft_deletes	= true;


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
			'field' => 'NAMA_UNOR',
			'label' => 'NAMA UNOR',
			'rules' => 'required',
		),
		//ADD BY BANA
		array(
			'field' => 'KODE_INTERNAL',
			'label' => 'KODE INTERNAL',
			//'rules' => 'required',
			'rules' => 'required',
		),
		array(
			'field' => 'ID',
			'label' => ' ID',
			//'rules' => 'required',
			'rules' => 'required',
		),
		array(
			'field' => 'EXPIRED_DATE',
			'label' => ' EXPIRED_DATE',
			//'rules' => 'required',
		),
		array(
			'field' => 'UNOR_INDUK',
			'label' => ' SATUAN KERJA',
			'rules' => 'required',
		)
		//END
	);
	protected $insert_validation_rules  = array();
	protected $skip_validation 			= true;

	/**
	 * Constructor
	 *
	 * @return void
	 */

	public function find_all($satker = "")
	{

		if (empty($this->selects)) {
			$this->select($this->table_name . '.*');
		}
		if ($satker != "") {
			$this->unitkerja_model->where('UNOR_INDUK', trim($satker));
		}
		return parent::find_all();
	}
	public function find_all_active($satker = "")
	{

		if (empty($this->selects)) {
			$this->select($this->table_name . '.*');
		}
		if ($satker != "") {
			$this->unitkerja_model->where('UNOR_INDUK', trim($satker));
		}
		$this->unitkerja_model->where('EXPIRED_DATE IS NULL');
		return parent::find_all();
	}
	public function find_all_pimpinan($satker = "",$non_aktif = false)
	{

		if (empty($this->selects)) {
			$this->select('"KODE_INTERNAL",unitkerja."ID","NAMA_UNOR","ESELON_ID","NAMA_JABATAN","GELAR_DEPAN","NAMA","GELAR_BELAKANG",NIP_BARU,WAKTU,JENIS_SATKER,EXPIRED_DATE');
		}
		if ($satker != "") {
			$this->unitkerja_model->where('UNOR_INDUK', trim($satker));
		}
		if(!$non_aktif)
			$this->unitkerja_model->where('EXPIRED_DATE IS NULL');

		$this->db->join("pegawai p", "unitkerja.PEMIMPIN_PNS_ID=p.PNS_ID", "LEFT");
		return parent::find_all();
	}
	public function count_all_pimpinan($satker = "",$non_aktif = false)
	{

		if (empty($this->selects)) {
			$this->select('"KODE_INTERNAL",unitkerja."ID","NAMA_UNOR","ESELON_ID","NAMA_JABATAN","GELAR_DEPAN","NAMA","GELAR_BELAKANG",NIP_BARU,WAKTU');
		}
		if ($satker != "") {
			$this->unitkerja_model->where('UNOR_INDUK', trim($satker));
		}
		if(!$non_aktif)
			$this->unitkerja_model->where('EXPIRED_DATE IS NULL');
		
		$this->db->join("pegawai p", "unitkerja.PEMIMPIN_PNS_ID=p.PNS_ID", "LEFT");
		return parent::count_all();
	}
	
	public function get_cache()
	{
		$data = $this->cache->get('unors');
		if ($data == null) {
			$rows =  $this->db->query('
				select unor.* from (
					select uk."ID",uk."NO",uk."NAMA_UNOR",uk."NAMA_JABATAN",uk."NAMA_PEJABAT",uk."DIATASAN_ID",count(children."ID")as "TOTAL_CHILD",uk."PEMIMPIN_PNS_ID" 
					from hris.unitkerja uk
					left join  hris.unitkerja children on uk."ID" = children."DIATASAN_ID"
					group by uk."ID",uk."NO",uk."NAMA_UNOR",uk."DIATASAN_ID",uk."PEMIMPIN_PNS_ID",uk."NAMA_JABATAN",uk."NAMA_PEJABAT"
				) as unor 
				left join hris.pegawai pejabat on pejabat."PNS_ID" = unor."PEMIMPIN_PNS_ID"	
			')->result();
			foreach ($rows as $row) {
				$data[$row->ID] = $row;
			}
			$this->cache->write($data, 'unors');
		}
		return $data;
	}
	public function get_unor()
	{
		$data = $this->cache->get('unors');
		//print_r($data);
		//if($data==null){
		$rows =  $this->db->query('
				select unor.*,"pejabat"."NAMA"  
					from (
					select uk."ID",uk."NO",uk."NAMA_UNOR",uk."NAMA_JABATAN",uk."NAMA_PEJABAT",uk."DIATASAN_ID",
					count(children."ID")as "TOTAL_CHILD",uk."PEMIMPIN_PNS_ID" 
					from hris.unitkerja uk
					left join  hris.unitkerja children on uk."ID" = children."DIATASAN_ID"
					group by uk."ID",uk."NO",
					uk."NAMA_UNOR",
					uk."DIATASAN_ID",
					uk."PEMIMPIN_PNS_ID",
					uk."NAMA_JABATAN",
					uk."NAMA_PEJABAT"
				) as unor 
				left join hris.pegawai pejabat on pejabat."PNS_ID" = unor."PEMIMPIN_PNS_ID"	
			')->result();
		foreach ($rows as $row) {
			$data[$row->ID] = $row;
		}
		$this->cache->write($data, 'unors');
		//}
		return $data;
	}
	public function get_satker($unor_id_inc, $withme = true)
	{
		$data = $this->get_cache();
		if ($data != null) {
			$node = isset($data[$unor_id_inc]) ? $data[$unor_id_inc] : null;
			$parent = isset($data[$data[$unor_id_inc]->DIATASAN_ID]) ? $data[$data[$unor_id_inc]->DIATASAN_ID] : null;
			while ($parent != NULL) {
				echo "tx";
				if ($parent->IS_SATKER) {
					echo "found";
					return $parent;
				}
				$parent = isset($data[$data[$parent->ID]->DIATASAN_ID]) ? $data[$data[$parent->ID]->DIATASAN_ID] : null;
			}
		}
		return null;
	}
	public function get_parent_path($unor_id_inc, $withme = true, $as_array = true)
	{
		$data = $data = $this->get_cache();
		$path = array();
		$maxlooping = 10;
		$loop = 0;
		if ($data != null) {
			$node = isset($data[$unor_id_inc]) ? $data[$unor_id_inc] : null;
			if ($node && $withme) {
				$path[] = $node;
			}
			$parent = isset($data[$data[$unor_id_inc]->DIATASAN_ID]) ? $data[$data[$unor_id_inc]->DIATASAN_ID] : null;

			while ($parent != NULL) {
				if ($loop > $maxlooping) break;
				$path[] = $parent;
				if ($parent->IS_SATKER) break;
				$parent = isset($data[$data[$parent->ID]->DIATASAN_ID]) ? $data[$data[$parent->ID]->DIATASAN_ID] : null;

				$loop++;
			}
		}
		if ($as_array) {
			return $path;
		} else {
			$path_string = array();
			foreach ($path as $row) {
				$path_string[] = $row->NAMA_UNOR;
			}
			return implode(" - ", $path_string);
		}
	}
	public function find_first_row()
	{
		return $this->db->get($this->db->schema . "." . $this->table_name)->first_row();
	}
	public function getFullNameWithData($data)
	{
		if (!$data) {
			return "";
		}
		$names = array();
		if (!empty($data->NAMA_ESELON_I)) {
			$names[] = $data->NAMA_ESELON_I;
		}
		if (!empty($data->NAMA_ESELON_II)) {
			$names[] = $data->NAMA_ESELON_II;
		}
		if (!empty($data->NAMA_ESELON_III)) {
			$names[] = $data->NAMA_ESELON_III;
		}
		if (!empty($data->NAMA_ESELON_IV)) {
			$names[] = $data->NAMA_ESELON_IV;
		}
		return implode(" - ", $names);
	}
	public function getFullName($id)
	{
		$data = $this->find($id);
		$names = array();
		if (!empty($data->NAMA_ESELON_I)) {
			$names[] = $data->NAMA_ESELON_I;
		}
		if (!empty($data->NAMA_ESELON_II)) {
			$names[] = $data->NAMA_ESELON_II;
		}
		if (!empty($data->NAMA_ESELON_III)) {
			$names[] = $data->NAMA_ESELON_III;
		}
		if (!empty($data->NAMA_ESELON_IV)) {
			$names[] = $data->NAMA_ESELON_IV;
		}
		return implode(" - ", $names);
	}
	public function getShortNameWithData($data)
	{
		$names = array();
		$eselon = $data->ESELON;
		$eselon_arr = explode(".", $eselon);
		if ($eselon_arr[0] == "I") {
			return $data->NAMA_ESELON_I;
		} else if ($eselon_arr[0] == "II") {
			return $data->NAMA_ESELON_II;
		} else if ($eselon_arr[0] == "III") {
			return $data->NAMA_ESELON_III;
		} else if ($eselon_arr[0] == "IV") {
			return $data->NAMA_ESELON_IV;
		}
		return "-";
	}
	public function getShortName($id)
	{
		$data = $this->find($id);
		$names = array();
		$eselon = $data->ESELON;
		$eselon_arr = explode(".", $eselon);
		if ($eselon_arr[0] == "I") {
			return $data->NAMA_ESELON_I;
		} else if ($eselon_arr[0] == "II") {
			return $data->NAMA_ESELON_II;
		} else if ($eselon_arr[0] == "III") {
			return $data->NAMA_ESELON_III;
		} else if ($eselon_arr[0] == "IV") {
			return $data->NAMA_ESELON_IV;
		}
		return "-";
	}
	public function getChildren($parents = array(), &$children = array(), $onlyID = true, $include_sub = false, $includeMe = false, $first = true, $propertyKey = "ID")
	{
		$data = $this->get_cache();
		if (!is_array($parents)) {
			$parents = array($parents);
		}

		foreach ($parents as $parent) {
			foreach ($data as $node) {
				if ($first && $includeMe) { //memasukan me sebagai child dari me
					if ($node->ID == $parent) {
						if ($onlyID) {
							if ($propertyKey == "ID") {
								$children[] = $node->ID;
							} else {
								$children[] = $node->ID;
							}
						} else $children[] = $node;
					}
				}
				if ($node->DIATASAN_ID == $parent) {
					if ($onlyID) {
						if ($propertyKey == "ID") {
							$children[] = $node->ID;
						} else {
							$children[] = $node->ID;
						}
					} else $children[] = $node;
					if ($include_sub) {
						$this->getChildren($node->ID, $children, $onlyID, $include_sub, $includeMe, false, $propertyKey);
					}
				}
			}
		}
	}
	public function find_eselon3($eselon2 = "")
	{

		if (empty($this->selects)) {
			$this->select($this->table_name . '.*');
		}
		if ($eselon2 != "") {
			//$this->unitkerja_model->where("DIATASAN_ID",strtoupper($eselon2));
			$this->unitkerja_model->where('"KODE_INTERNAL" LIKE \'' . strtoupper($eselon2) . '%\'');
			//$this->unitkerja_model->where('"ESELON" LIKE \'III.%\'');
		}
		return parent::find_all();
	}

	//add by bana
	public function delete_custom($id)
	{
		$sql = "SELECT * FROM hris.unitkerja WHERE \"DIATASAN_ID\" = ?";
		$query = $this->db->query($sql, array($id));
		//check apakah unit kerja memiliki node anak
		//kalo tidak ada berarti aman untuk dihapus
		if ($query->num_rows() == 0) {

			$sql = "DELETE FROM hris.unitkerja WHERE \"ID\" = ?";
			$this->db->query($sql, array($id));
		} else {
			return;
		}
	}
	//end

	public function find_eselon4($eselon2 = "")
	{

		if (empty($this->selects)) {
			$this->select($this->table_name . '.*');
		}
		if ($eselon2 != "") {
			//$this->unitkerja_model->where("DIATASAN_ID",strtoupper($eselon2));
			$this->unitkerja_model->where('"KODE_INTERNAL" LIKE \'' . strtoupper($eselon2) . '%\'');
			//$this->unitkerja_model->where('"ESELON" LIKE \'IV.%\'');
		}
		return parent::find_all();
	}
	public function find_satker($id = "")
	{

		if (empty($this->selects)) {
			$this->select($this->table_name . '.*');
		}
		if ($id != '') {
			$this->db->group_start();
			$this->db->where("ID", $id);
			$this->db->or_where("ESELON_1", $id);
			$this->db->or_where("ESELON_2", $id);
			$this->db->group_end();
		}
		$this->db->group_start();
		$this->unitkerja_model->where('"ID" in (select "UNOR_INDUK" from hris.unitkerja)');
		$this->unitkerja_model->or_where('IS_SATKER', 1);
		$this->db->group_end();
		$this->unitkerja_model->where('EXPIRED_DATE IS NULL');
		return parent::find_all();
	}
	public function find_by_id($id = '', $key = '')
	{
		$this->db->from('vw_unit_list vw');
		$this->db->like('lower(vw."NAMA_UNOR")', strtolower($key), "BOTH");
		if ($id != '') {
			$this->db->group_start();
			$this->db->where('vw."ID"', $id);
			$this->db->group_end();
		}
		$this->db->order_by('vw."NAMA_UNOR_ESELON_1"', "ASC");
		$this->db->order_by('vw."NAMA_UNOR_ESELON_2"', "ASC");
		$this->db->order_by('vw."NAMA_UNOR_ESELON_3"', "ASC");
		$this->db->order_by('vw."NAMA_UNOR_ESELON_4"', "ASC");
		if ($id != '') {
			$row =  $this->db->get()->first_row();

			return $row;
		}
		return $this->db->get()->result();
	}
	public function find_unit($key = '', $id = '', $asatkers = null)
	{
		$this->db->SELECT('ID,NAMA_UNOR,NAMA_JABATAN,NAMA_UNOR_FULL,EXPIRED_DATE');
		$this->db->from('vw_unit_list vw');
		$this->db->like('lower(vw."NAMA_UNOR")', strtolower($key), "BOTH");
		if ($id != '' && count($asatkers) <= 0) {
			$this->db->group_start();
			$this->db->where('vw."ID"', $id);
			$this->db->or_where('vw."ESELON_1"', $id);
			$this->db->or_where('vw."ESELON_2"', $id);
			$this->db->or_where('vw."ESELON_3"', $id);
			$this->db->or_where('vw."ESELON_4"', $id);
			$this->db->group_end();
		}
		//die(count($asatkers)." jml");
		if (count($asatkers) > 0) {
			$this->db->group_start();
			$this->db->or_where_in('vw."ID"', $asatkers);
			$this->db->or_where_in('vw."ESELON_1"', $asatkers);
			$this->db->or_where_in('vw."ESELON_2"', $asatkers);
			//$this->db->or_where_in('vw."ESELON_3"',$asatkers);
			$this->db->group_end();
		}
		$this->db->where('vw."NAMA_UNOR_FULL" != \'\' ', FALSE, FALSE);
		$this->db->order_by('vw."NAMA_UNOR_FULL"', "ASC");
		$this->db->limit(10000);
		return $this->db->get()->result();
	}
	public function count_satker($unor_id = '')
	{
		if ($unor_id != '') {
			$this->db->where("(ID = '" . $unor_id . "' or ESELON_1 = '" . $unor_id . "' or ESELON_2 = '" . $unor_id . "' or ESELON_3 = '" . $unor_id . "' or ESELON_4 = '" . $unor_id . "')");
		}
		if (empty($this->selects)) {
			$this->select('count(distinct "UNOR_INDUK") AS jumlah');
		}
		return parent::find_all();
	}
	public function find_satkerold()
	{

		if (empty($this->selects)) {
			$this->select($this->table_name . '.*');
		}
		$this->unitkerja_model->where('IS_SATKER', 1);
		return parent::find_all();
	}
	public function findnamajabatan($UNOR = "")
	{

		if (empty($this->selects)) {
			$this->select($this->table_name . '.*');
		}
		return parent::find_by('KODE_INTERNAL', $UNOR);
	}
	public function findnamajabatan_by_id($ID = "")
	{

		if (empty($this->selects)) {
			$this->select($this->table_name . '.*');
		}
		return parent::find_by('ID', $ID);
	}
	// update yanarazor
	public function get_unit_by_unorinduk($unor_induk = "")
	{

		if (empty($this->selects)) {
			$this->select('"KODE_INTERNAL",unitkerja."ID","NAMA_UNOR","ESELON_ID","NAMA_JABATAN","GELAR_DEPAN","NAMA","GELAR_BELAKANG",NIP_BARU');
		}
		if ($unor_induk != "") {
			$this->unitkerja_model->where('("UNOR_INDUK" LIKE \'' . strtoupper($unor_induk) . '%\' or unitkerja."ID" LIKE \'' . strtoupper($unor_induk) . '%\')');
		}
		$this->db->join("pegawai p", "unitkerja.PEMIMPIN_PNS_ID=p.PNS_ID", "LEFT");
		$this->db->order_by("KODE_INTERNAL", "ASC");
		return parent::find_all();
	}
	public function get_pejabat($unor_induk = "")
	{

		if (empty($this->selects)) {
			$this->select('unitkerja."ID","NAMA_UNOR",
				"ESELON_ID",
				"NAMA_JABATAN",
				NIP_BARU as NIP_PEJABAT,
				"GELAR_DEPAN","NAMA" AS NAMA_PEJABAT,"GELAR_BELAKANG",
				TRIM("NOMOR_HP") "NOMOR_HP",
				TRIM("EMAIL") "EMAIL",
				TRIM("ALAMAT") "ALAMAT"
				');
		}
		if ($unor_induk != "" and $unor_induk != "A8ACA7397AEB3912E040640A040269BB") {
			$this->db->group_start();
			$this->db->where("unitkerja.ID", $unor_induk);
			$this->db->or_where("ESELON_1", $unor_induk);
			$this->db->or_where("ESELON_2", $unor_induk);
			$this->db->or_where("ESELON_3", $unor_induk);
			$this->db->or_where("ESELON_4", $unor_induk);
			$this->db->or_where("UNOR_INDUK", $unor_induk);
			$this->db->or_where("DIATASAN_ID", $unor_induk);
			$this->db->group_end();
		}
		if ($unor_induk != "" and $unor_induk != "A8ACA7397AEB3912E040640A040269BB") {
			// $this->unitkerja_model->or_where('("UNOR_INDUK" LIKE \'' . strtoupper($unor_induk) . '%\' or unitkerja."ID" LIKE \'' . strtoupper($unor_induk) . '%\')');
		}
		$this->db->where("EXPIRED_DATE IS NULL");
		$this->db->join("pegawai p", "unitkerja.PEMIMPIN_PNS_ID=p.PNS_ID", "LEFT");
		$this->db->order_by("KODE_INTERNAL", "ASC");
		return parent::find_all();
	}
	public function count_pejabat($unor_induk = "")
	{

		if (empty($this->selects)) {
			$this->select('"KODE_INTERNAL",unitkerja."ID","NAMA_UNOR","ESELON_ID","NAMA_JABATAN","GELAR_DEPAN","NAMA","GELAR_BELAKANG",NIP_BARU');
		}
		if ($unor_induk != '' and $unor_induk != "A8ACA7397AEB3912E040640A040269BB") {
			$this->db->group_start();
			$this->db->where("unitkerja.ID", $unor_induk);
			$this->db->or_where("ESELON_1", $unor_induk);
			$this->db->or_where("ESELON_2", $unor_induk);
			$this->db->or_where("ESELON_3", $unor_induk);
			$this->db->or_where("ESELON_4", $unor_induk);
			$this->db->or_where("UNOR_INDUK", $unor_induk);
			$this->db->or_where("DIATASAN_ID", $unor_induk);
			$this->db->group_end();
		}
		if ($unor_induk != "" and $unor_induk != "A8ACA7397AEB3912E040640A040269BB") {
			// $this->unitkerja_model->where('("UNOR_INDUK" LIKE \'' . strtoupper($unor_induk) . '%\' or unitkerja."ID" LIKE \'' . strtoupper($unor_induk) . '%\')');
		}
		$this->db->where("EXPIRED_DATE IS NULL");
		$this->db->join("pegawai p", "unitkerja.PEMIMPIN_PNS_ID=p.PNS_ID", "LEFT");
		return parent::count_all();
	}

	public function find_all_by_id($id)
	{
		$sql = 'WITH RECURSIVE r AS (
			SELECT "ID","DIATASAN_ID","NAMA_UNOR" FROM hris.unitkerja WHERE "ID"=?
			UNION ALL
			SELECT a."ID",a."DIATASAN_ID",a."NAMA_UNOR" FROM hris.unitkerja a JOIN r ON a."DIATASAN_ID" = r."ID"
		  )
		  SELECT * FROM r';
		$query = $this->db->query($sql, array($id));
		$data = $query->result();
		//return parent::find_all();
		//var_dump($sql);
		return $data;
	}
	public function find_rekap_satker($ID = "")
	{
		if (empty($this->selects)) {
			$this->select($this->table_name . '."UNOR_INDUK","NAMA_UNOR",count(p."NIP_BARU") as jml_pegawai');
		}
		if($ID != ""){
			$this->db->where("unitkerja.ID",$ID);	
		}
		$this->db->join("pegawai p", "unitkerja.ID=p.UNOR_ID", "LEFT");
		$this->db->where('"unitkerja.ID" in (select "UNOR_INDUK" from hris.unitkerja)');
		$this->db->where("IS_SATKER",1);
		$this->db->where('EXPIRED_DATE IS NULL');
		$this->db->group_by("UNOR_INDUK");
		$this->db->group_by("NAMA_UNOR");
		return parent::find_all();
	}
	public function count_rekap_satker()
	{
		if (empty($this->selects)) {
			$this->select($this->table_name . '.ID,NAMA_UNOR,UNOR_INDUK');
		}
		$this->db->where('"unitkerja.ID" in (select "UNOR_INDUK" from hris.unitkerja)');
		$this->db->where("IS_SATKER",1);
		$this->db->where('EXPIRED_DATE IS NULL');
		
		return parent::count_all();
	}
	public function find_unitkerja($diatasan_id = "")
	{
		if ($diatasan_id == "") {
			$diatasan_id = "A8ACA7397AEB3912E040640A040269BB";
		}
		if (empty($this->selects)) {
			$this->select($this->table_name . '.ID,NAMA_UNOR,UNOR_INDUK,NAMA_JABATAN,DIATASAN_ID');
		}
		$this->unitkerja_model->where("DIATASAN_ID", $diatasan_id);
		$this->unitkerja_model->where('EXPIRED_DATE IS NULL');
		return parent::find_all();
	}
	public function count_unitkerja($diatasan_id = "")
	{
		if ($diatasan_id == "") {
			$diatasan_id = "A8ACA7397AEB3912E040640A040269BB";
		}
		if (empty($this->selects)) {
			$this->select($this->table_name . '.ID,NAMA_UNOR,UNOR_INDUK,NAMA_JABATAN,DIATASAN_ID');
		}
		$this->unitkerja_model->where('EXPIRED_DATE IS NULL');
		$this->unitkerja_model->where("DIATASAN_ID", $diatasan_id);
		return parent::count_all();
	}
	public function find_view_unit_list($unor_id = "")
	{
		$records = $this->db->query('
        select parent."ID",parent."NAMA_UNOR","IS_SATKER","DIATASAN_ID","ESELON_4","ESELON_3","ESELON_2","ESELON_1","PEJABAT_NAMA","PEMIMPIN_PNS_ID",
        "NIP_BARU","GELAR_DEPAN","GELAR_BELAKANG","NAMA_JABATAN","JENIS_SATKER",parent."NAMA_UNOR" as "NAMA"
        from vw_unit_list_pejabat parent
        where 
        deleted is null 
        and parent."EXPIRED_DATE" is null 
        and "ID"=?
        ORDER BY "DIATASAN_ID" asc, parent."ORDER" asc',array($unor_id))->result();
		return $records;
	}

	//bana
	public function unor_tree_to_array($unor_id)
	{
		$sql = 'with RECURSIVE r as (select "ID"::text as "ID" from hris.unitkerja WHERE "ID"=?
			union all
			select  a."ID" from hris.unitkerja a join r on a."DIATASAN_ID" = r."ID")
			select string_agg(r."ID", \',\') as array_unor_id from r';
		$query =  $this->db->query($sql, array($unor_id));
		$row = $query->first_row();
		return $row->array_unor_id;
	}
	//end	
}
