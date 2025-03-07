<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * reports controller
 */
class Test extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('pegawai/pegawai_model', null, true);
		$this->load->model('golongan/golongan_model', null, true);
		$this->load->model('pegawai/unitkerja_model');
	}
	public function test(){
		$this->load->helper('dikbud');
		$rows = $this->db->query("
			select es1.\"ABBREVIATION\" nama from vw_list_eselon1 es1 
		")->result();
		foreach($rows as $row){
			$row->color = "#".random_color();
		}
		$this->cache->write($rows,'corporate_color');
		echo __LINE__;
		$corporate_color = $this->cache->get('corporate_color');
		print_r($corporate_color);
	}
}