<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * reports controller
 */
class Eis extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('pegawai/pegawai_model', null, true);
		$this->load->model('golongan/golongan_model', null, true);
		$this->load->model('pegawai/unitkerja_model');
	}
	public function index(){
		
		$list_es1 = $this->db->query('
			SELECT
				"ID",
				"NAMA_UNOR" 
			FROM
				vw_list_eselon1
		')->result();
		$list_tk = $this->db->query('
			select "ID","NAMA" from tkpendidikan
		')->result();
		
		Template::set('list_es1',$list_es1);
		Template::set('list_tk',$list_tk);
		Template::set('list_usia',array(
			array(
				"ID"=>'21-25',
				"NAMA"=>'21-25'
			),
			array(
				"ID"=>'31-35',
				"NAMA"=>'31-35'
			)
		));
		Template::set('list_jf_type',array(
			array(
				"ID"=>'ja',
				"NAMA"=>'Jabatan Administrator'
			),
			array(
				"ID"=>'jft',
				"NAMA"=>'Jabatan Fungsional Tertentu'
			)
		));
		Template::set_view('rekap/eis.php');
		Template::render();
	}
	private function get_color($corporate_name){
		$corporate_color = $this->cache->get('corporate_color');
		if(!$corporate_color){
			$this->load->helper('dikbud');
			$rows = $this->db->query("
				select es1.\"ABBREVIATION\" nama from vw_list_eselon1 es1 
			")->result();
			foreach($rows as $row){
				$row->color = "#".random_color();
			}
			$this->cache->write($rows,'corporate_color');
			$corporate_color = $this->cache->get('corporate_color');
		}
		
		$selectedColor = '#67b7dc';
		foreach($corporate_color as $color){
			if($color->nama==$corporate_name){
				//return $color['value'];
				$selectedColor = $color->color;
			}
		}
		return $selectedColor;
	}
	public function ajax_group_by_tk_pendidikan($tk_id){
		$result_pendidikan  = $this->db->query('
			SELECT
				es1."ID",
				es1."ABBREVIATION" as nama,
				(
					select count(*) from daftar_pns_aktif p 
					left join unitkerja uk on p."UNOR_ID" = uk."ID" 
					left join pendidikan pend on p."PENDIDIKAN_ID" = pend."ID" 
					where uk."ESELON_1" = es1."ID" and pend."TINGKAT_PENDIDIKAN_ID" = tk."ID"
				) total,\'red\' color
			FROM
				vw_list_eselon1 es1 
				left join tkpendidikan tk on true 
				where tk."ID" = ? 
				AND es1."ID" not in(
				\'19A091A2174D64DAE050640A150263FC\',
				\'19A091A2174C64DAE050640A150263FC\',
				\'192B7FDA1EC63054E050640A15024542\',
				\'19A091A2174A64DAE050640A150263FC\',
				\'19A091A2174B64DAE050640A150263FC\'
				)
				order by es1."ID"  asc 
		',array($tk_id))->result();
		foreach($result_pendidikan as $row){
			$row->color = $this->get_colornew($row->ID);
		}
		header("Content-type:application/json");
		echo json_encode($result_pendidikan);
	}
	public function ajax_group_by_eselon_total_pegawai(){
		$result  = $this->db->query('
			select  es1."ID",es1."ABBREVIATION" as nama,es1."NAMA_UNOR",count(*) as total  from vw_list_eselon1 es1 
			left join unitkerja uk on uk."ESELON_1" = es1."ID"
			left join daftar_pns_aktif p on p."UNOR_ID"  = uk."ID"
			where es1."ID" not in(
			\'19A091A2174D64DAE050640A150263FC\',
			\'19A091A2174C64DAE050640A150263FC\',
			\'192B7FDA1EC63054E050640A15024542\',
			\'19A091A2174A64DAE050640A150263FC\',
			\'19A091A2174B64DAE050640A150263FC\'
			)
			group by es1."ID",es1."ABBREVIATION",es1."NAMA_UNOR"
			order by es1."ID" asc
		',array())->result();
		foreach($result as $row){
			$row->color = $this->get_colornew($row->ID);
		}
		header("Content-type:application/json");
		echo json_encode($result);
	}
	private function get_colornew($ID){
		if(trim($ID) == "192B7FDA1EC73054E050640A15024542"){
			// SETJEN
			$selectedColor = "#d09529";
		}
		if(trim($ID) == "192B7FDA1EC83054E050640A15024542"){
			//GTK
			$selectedColor = "#3495da";
		}
		if(trim($ID) == "192B7FDA1EC93054E050640A15024542"){
			// PAUD DAN DIKMAS
			$selectedColor = "#af4e15";
		}
		if(trim($ID) == "192B7FDA1ECA3054E050640A15024542"){
			// DASMEN
			$selectedColor = "#16dc65";
		}
		if(trim($ID) == "192B7FDA1ECB3054E050640A15024542"){
			// ITJEN
			$selectedColor = "#d62626";
		}
		if(trim($ID) == "192B7FDA1ECE3054E050640A15024542"){
			// BAHASA
			$selectedColor = "#a21ac3";
		}
		if(trim($ID) == "192B7FDA1ECD3054E050640A15024542"){
			// BALITBANG
			$selectedColor = "#8c8787";
		}
		if(trim($ID) == "192B7FDA1ECC3054E050640A15024542"){
			// BALITBANG
			$selectedColor = "#4c2121";
		}
		 

		return $selectedColor;
	}
	public function ajax_group_by_usia($usia_id){ 
		$added_query = " 1 ";
		if($usia_id == '21-25'){
			$added_query = ' case when p."AGE" >= 21 AND p."AGE" <= 25 THEN 1 END ';
		}
		if($usia_id == '31-35'){
			$added_query = ' case when p."AGE" >= 31 AND p."AGE" <= 35 THEN 1 END ';
		}
		$result  = $this->db->query('
			SELECT
				es1."ID",
				es1."ABBREVIATION" as nama ,
				SUM ( '.$added_query.' ) as total  
			FROM
				vw_list_eselon1 es1
				LEFT JOIN unitkerja uk ON uk."ESELON_1" = es1."ID"
				LEFT JOIN daftar_pns_aktif P ON P."UNOR_ID" = uk."ID" 
			where es1."ID" not in(
			\'19A091A2174D64DAE050640A150263FC\',
			\'19A091A2174C64DAE050640A150263FC\',
			\'192B7FDA1EC63054E050640A15024542\',
			\'19A091A2174A64DAE050640A150263FC\',
			\'19A091A2174B64DAE050640A150263FC\'
			)

			GROUP BY
				es1."ID",
				es1."ABBREVIATION" 
			ORDER BY
				es1."ID" asc
		',array())->result();
		foreach($result as $row){
			if(!$row->total){
				$row->total = 0;
			}
			$row->color = $this->get_colornew($row->ID);
		}
		header("Content-type:application/json");
		echo json_encode($result);
	}
	
	public function ajax_group_jabatan($jf_type){ 
		$added_query = " 1 ";
		if($jf_type == 'jft'){
			$added_query = ' case when lj."ID_JENIS_JABATAN"::INTEGER =2 then 1 end ';
		}
		if($jf_type == 'ja'){
			$added_query = ' case when lj."ID_JENIS_JABATAN"::INTEGER !=2 then 1 end ';
		}
		$result  = $this->db->query('
			SELECT
				es1."ID",
				es1."ABBREVIATION" as nama ,
				SUM ( '.$added_query.' ) as total  
			FROM
				vw_list_eselon1 es1
				LEFT JOIN unitkerja uk ON uk."ESELON_1" = es1."ID"
				LEFT JOIN daftar_pns_aktif P ON P."UNOR_ID" = uk."ID" 
				left join vw_last_jabatan lj on p."NIP_BARU" = lj."PNS_NIP" 
			where es1."ID" not in(
			\'19A091A2174D64DAE050640A150263FC\',
			\'19A091A2174C64DAE050640A150263FC\',
			\'192B7FDA1EC63054E050640A15024542\',
			\'19A091A2174A64DAE050640A150263FC\',
			\'19A091A2174B64DAE050640A150263FC\'
			)

			GROUP BY
				es1."ID",
				es1."ABBREVIATION" 
			ORDER BY
				es1."ID" asc
		',array())->result();
		foreach($result as $row){
			if(!$row->total){
				$row->total = 0;
			}
			$row->color = $this->get_colornew($row->ID);
		}
		header("Content-type:application/json");
		echo json_encode($result);
	}
}	