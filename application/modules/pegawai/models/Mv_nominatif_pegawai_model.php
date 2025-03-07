<?php defined('BASEPATH') || exit('No direct script access allowed');

class Mv_nominatif_pegawai_model extends BF_Model
{
	protected $table_name	= 'mv_nominatif_pegawai';
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
		
	);
	protected $insert_validation_rules  = array();
	protected $skip_validation 			= true;
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
		$this->db->select('*', false);
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
		$this->db->select('*', false);
		return parent::count_all();
	}

}
