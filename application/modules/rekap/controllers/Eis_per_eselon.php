<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * reports controller
 */
class Eis_per_eselon extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('pegawai/pegawai_model', null, true);
		$this->load->model('golongan/golongan_model', null, true);
		$this->load->model('pegawai/unitkerja_model');
	}
	public function index($es_id=''){
		
		$list_es1 = $this->db->query('
			SELECT
				"ID",
				"NAMA_UNOR" AS NAMA_UNOR 
			FROM
				vw_list_eselon1
		')->result();
		if($es_id ==''){
			redirect("rekap/eis_per_eselon/index/".$list_es1[0]->ID);
		}
		//PRINT_R($list_es1);
		//DIE();
		$list_tk = $this->db->query('
			select "ID","NAMA" from tkpendidikan
		')->result();
		Template::set('selectedEselon',$es_id);
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
		Template::set_view('rekap/eis_per_eselon.php');
		Template::render();
		
	}
	
	public function ajax_group_by_tk_pendidikan($tk_id){
		$eselon_id = $this->input->post('eselon_id');
		$result_pendidikan  = $this->db->query('
			SELECT
				es."ABBREVIATION" as nama,
				(
					select count(*) from daftar_pns_aktif p 
					left join unitkerja uk on p."UNOR_ID" = uk."ID" 
					left join pendidikan pend on p."PENDIDIKAN_ID" = pend."ID" 
					where uk."ESELON_1" = es."ID" and pend."TINGKAT_PENDIDIKAN_ID" = tk."ID"
				) total
			FROM
				vw_list_eselon2 es 
				left join tkpendidikan tk on true 
				where tk."ID" = ? and es."ESELON_1"  = ?
		',array($tk_id,$eselon_id))->result();
		echo json_encode($result_pendidikan);
	}
	public function ajax_group_by_eselon_total_pegawai(){
		$eselon_id = $this->input->post('eselon_id');
		$result  = $this->db->query('
			select  es."ID",es."ABBREVIATION" as nama,count(*) as total  from vw_list_eselon2 es 
			left join unitkerja uk on uk."ESELON_1" = es."ID"
			left join daftar_pns_aktif p on p."UNOR_ID"  = uk."ID"
			where es."ESELON_1" = ? 
			group by es."ID",es."ABBREVIATION" 
			order by es."ABBREVIATION" desc  
		',array($eselon_id))->result();
		echo json_encode($result);
	}
	public function ajax_group_by_usia($usia_id){ 
		$added_query = " 1 ";
		if($usia_id == '21-25'){
			$added_query = ' case when p."AGE" >= 21 AND p."AGE" <= 25 THEN 1 END ';
		}
		if($usia_id == '31-35'){
			$added_query = ' case when p."AGE" >= 31 AND p."AGE" <= 35 THEN 1 END ';
		}
		$eselon_id = $this->input->post('eselon_id');
		$result  = $this->db->query('
			SELECT
				es."ID",
				es."ABBREVIATION" as nama ,
				SUM ( '.$added_query.' ) as total  
			FROM
				vw_list_eselon2 es
				LEFT JOIN unitkerja uk ON uk."ESELON_1" = es."ID"
				LEFT JOIN daftar_pns_aktif P ON P."UNOR_ID" = uk."ID"  
			where es."ESELON_1" = ? 
			GROUP BY
				es."ID",
				es."ABBREVIATION" 
			ORDER BY
				es."ABBREVIATION" DESC
		',array($eselon_id))->result();
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
		$eselon_id = $this->input->post('eselon_id');
		$result  = $this->db->query('
			SELECT
				es."ID",
				es."ABBREVIATION" as nama ,
				SUM ( '.$added_query.' ) as total  
			FROM
				vw_list_eselon2 es
				LEFT JOIN unitkerja uk ON uk."ESELON_1" = es."ID"
				LEFT JOIN daftar_pns_aktif P ON P."UNOR_ID" = uk."ID" 
				left join vw_last_jabatan lj on p."NIP_BARU" = lj."PNS_NIP"  
				where es."ESELON_1" = ? 
			GROUP BY
				es."ID",
				es."ABBREVIATION" 
			ORDER BY
				es."ABBREVIATION" DESC
		',array($eselon_id))->result();
		echo json_encode($result);
	}
}	