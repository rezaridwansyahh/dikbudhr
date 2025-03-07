<?php defined('BASEPATH') || exit('No direct script access allowed');

class Vw_pejabat_cuti_model extends BF_Model
{
    protected $table_name	= 'vw_pejabat_cuti';
	
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
    public function count_me($NIP = "")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.NIP_ATASAN,
				jumlah');
		}	
		$this->db->where('NIP_ATASAN', $NIP);	
		return parent::count_all()>0;
	}
	 
}