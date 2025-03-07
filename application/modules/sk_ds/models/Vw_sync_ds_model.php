<?php defined('BASEPATH') || exit('No direct script access allowed');

class Vw_sync_ds_model extends BF_Model
{
    protected $table_name	= 'vw_sync_ds';
	protected $key			= 'ID';
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    public function find_all($jumlah)
	{
		$this->db->select('*', false);
		$this->db->order_by("CREATED_DATE", "ASC");
		$this->db->limit($jumlah);
		return parent::find_all();
	}
}