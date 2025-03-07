<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Pendidikan extends Admin_Controller
{
    public function __construct()
    {
    	 
        parent::__construct();
    }
    public function ajax(){
        $json = array();
        $limit = 10;
        $page = $this->input->get('page') ? $this->input->get('page') : "1";
        $q= $this->input->get('term');
        $start = ($page-1)*$limit;
		
		if(!empty($q)){
            $json = $this->data_model($q,$start,$limit);
		}
        echo json_encode($json);
    }
	public function index(){
        die("masuk");
    }

    private function data_model($key,$start,$limit){
          
            $this->db->start_cache();
            $this->db->like('lower("NAMA")', strtolower($key));
            $this->db->from("hris.pendidikan");
            $this->db->stop_cache();
            $total = $this->db->get()->num_rows();
            $this->db->select('ID as id,NAMA as text');
            $this->db->limit($limit,$start);

            $data = $this->db->get()->result();

            $endCount = $start + $limit;
            $morePages = $endCount > $total;
            $o = array(
            "results" => $data,
                "pagination" => array(
                    "more" =>$morePages
                )
            );   
            $this->db->flush_cache();
            return $o;
    }
    public function getbytingkat()
	{
		$this->load->model('pegawai/pendidikan_model');
		$valuetingkat = $this->input->get('tingkat');
		$json = array(); 
		$records = $this->pendidikan_model->find_all($valuetingkat);
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) :
				$json['id'][] = $record->ID;
				$json['nama'][] = $record->NAMA;
			endforeach;
		endif;
		echo json_encode($json);
		die();
	}
    
}