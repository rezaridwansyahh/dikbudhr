<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Ppo_deputi extends Admin_Controller
{
    protected $permissionCreate = 'LayananPpo.View.PengelolaPpoSatker';
    protected $permissionEdit   = 'LayananPpo.Masters.Edit';
    protected $permissionCetak   = 'LayananPpo.Cetak';
    protected $permissionKirimInboxInstansi   = 'LayananPpo.KirimInboxInstansi';
    

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('dikbud');
        $this->load->model('ppo/layanan_ppo_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->helper('ppo/layanan_ppo');
        $this->load->model('pegawai/unitkerja_model');
        $this->load->model('ppo/usulan_dokumen_model');
        $this->role = $this->session->userdata('role_id');
        Template::set('module_url',base_url('ppo/ppo-deputi'));
        $this->satker_pegawai = getEselon1Pegawai($this->auth->username());
    }
    public function select2_list_pegawai(){
        $term = $this->input->get('term');
        $this->pegawai_model->like('lower("NAMA")',strtolower($term),"BOTH");

        $records=$this->pegawai_model->find_all($this->satker_pegawai);
        $o= array();
        foreach($records as $record){
            $o['results'][] = array(
                'id'=>$record->PNS_ID,
                'text'=>$record->NIP_BARU ." | ". $record->NAMA ." | ". $record->nama_satker
            );
        }
        echo json_encode($o);
    }
    
    public function ajax_list(){
        
        $draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length');
		$start= $this->input->post('start');
        $filter_unit_kerja = $this->input->post('filter_unit_kerja');
        $filter_status = $this->input->post('filter_status');
		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        
        $this->db->where('perkiraan_ppo.status >','0');

        $this->layanan_ppo_model->db->group_start();
            $this->layanan_ppo_model->db->where("vw.ID",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_4",$this->satker_pegawai);
        $this->layanan_ppo_model->db->group_end();
        if($filter_unit_kerja){
            $this->layanan_ppo_model->db->group_start();
                $this->layanan_ppo_model->db->where("vw.ID",$filter_unit_kerja);
                $this->layanan_ppo_model->db->or_where("vw.ESELON_1",$filter_unit_kerja);
                $this->layanan_ppo_model->db->or_where("vw.ESELON_2",$filter_unit_kerja);
                $this->layanan_ppo_model->db->or_where("vw.ESELON_3",$filter_unit_kerja);
                $this->layanan_ppo_model->db->or_where("vw.ESELON_4",$filter_unit_kerja);
            $this->layanan_ppo_model->db->group_end();
        }
        if($filter_status){
            $this->layanan_ppo_model->db->where_in("status",$filter_status);
        }
        $total= $this->layanan_ppo_model->count_all();
        
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();


		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if($search!=""){
			$this->layanan_ppo_model->where('upper("NAMA") LIKE \'%'.strtoupper($search).'%\'');

        }
		$this->layanan_ppo_model->limit($length,$start);
		
		$kolom = $iSortCol != "" ? $iSortCol : "name";
		$sSortCol == "asc" ? "asc" : "desc";
        $this->layanan_ppo_model->order_by($iSortCol,$sSortCol);
        
        $this->db->where('perkiraan_ppo.status >','0');    
        $this->layanan_ppo_model->db->group_start();
            $this->layanan_ppo_model->db->where("vw.ID",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_4",$this->satker_pegawai);
        $this->layanan_ppo_model->db->group_end();
        $this->db->order_by('perkiraan_ppo.status','desc'); 

        if($filter_unit_kerja){
            $this->layanan_ppo_model->db->group_start();
                $this->layanan_ppo_model->db->where("vw.ID",$filter_unit_kerja);
                $this->layanan_ppo_model->db->or_where("vw.ESELON_1",$filter_unit_kerja);
                $this->layanan_ppo_model->db->or_where("vw.ESELON_2",$filter_unit_kerja);
                $this->layanan_ppo_model->db->or_where("vw.ESELON_3",$filter_unit_kerja);
                $this->layanan_ppo_model->db->or_where("vw.ESELON_4",$filter_unit_kerja);
            $this->layanan_ppo_model->db->group_end();
        }
        if($filter_status){
            $this->layanan_ppo_model->db->where_in("status",$filter_status);
        }
        
		$records=$this->layanan_ppo_model->find_all();

		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($search != "")
		{
			$this->layanan_ppo_model->where('upper("NAMA") LIKE \'%'.strtoupper($search).'%\'');
		    $jum	= $this->layanan_ppo_model->count_all();
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}
		
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = get_status_layanan_Ppo($record->status);
                $row []  = $record->no_surat_pengantar;
                $row []  = $record->NIP_BARU."<br>".$record->NAMA;
                $informasi_tambahan = array();
                $informasi_tambahan[] = $record->bup;
                $row []  = implode("<br>",$informasi_tambahan);
                $row []  = $record->nama_satker;
                
                $btn_actions = array();
                $btn_actions  [] = "
                    <a  href='".base_url()."admin/kepegawaian/pegawai/profile/".$record->ID."'  data-toggle='tooltip' title='Profile Pegawai'><span class='fa-stack'>
					   	<i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-eye fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
                if($record->status<7){
                    $btn_actions  [] = "
                        <a class='show-modal-custom' href='".base_url()."ppo/ppo-deputi/edit/".$record->usulan_id."'  data-toggle='tooltip' title='Ubah Data'><span class='fa-stack'>
                            <i class='fa fa-square fa-stack-2x'></i>
                            <i class='fa fa-pencil fa-stack-1x fa-inverse'></i>
                            </span>
                            </a>
                    ";
                }
                $btn_actions  [] = "
                <a  class='show-modal-custom' href='".base_url()."ppo/ppo-deputi/form_dok_pendukung/".$record->usulan_id."'  data-toggle='tooltip' title='Upload Dokumen Pendukung Data'><span class='fa-stack'>
                       <i class='fa fa-square fa-stack-2x'></i>
                       <i class='fa fa-upload fa-stack-1x fa-inverse'></i>
                       </span>
                       </a>
            ";
                if($this->auth->has_permission($this->permissionDelete))   {
                    $btn_actions  [] = "
                            <a href='#' kode='$record->usulan_id' class='btn-hapus' data-toggle='tooltip' title='Hapus data' >
                            <span class='fa-stack'>
                            <i class='fa fa-square fa-stack-2x'></i>
                            <i class='fa fa-trash-o fa-stack-1x fa-inverse'></i>
                            </span>
                            </a>
                    ";

                }
                $row[] = implode(" ",$btn_actions);
                

                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		die();
    }
    public function index(){
        Template::set('PNS_ID', $PNS_ID);
        Template::set('TAB_ID', uniqid("TAB_CONTOH"));
        Template::set_view('layanan_ppo/ppo_deputi/v_index');
        Template::set('canKirimInboxInstansi',$this->auth->has_permission($this->permissionKirimInboxInstansi));
        Template::set('canCetak',$this->auth->has_permission($this->permissionCetak));
        Template::render();
    }
	
   
	public function edit($ID)
    {
        $list_status = array(
            array(
                'name'=>'Memenuhi Syarat (MS)',
                'value'=>'6'
            ),
            array(
                'name'=>'Tidak Memenuhi Syarat (TMS)',
                'value'=>'5'
            ),
        );
        //$this->auth->restrict($this->permissionEdit); 
        if($this->input->post('save')){
          $response = $this->save();
          if($response['success']){
                Template::set_message($response['msg'], 'success');
                redirect(base_url('Ppo/Ppo-satker'));
          }
          else {
              if(isset($response['msg'])){
                Template::set_message($response['msg'], 'error');
              } 
          }
        }
        //$this->auth->restrict($this->permissionCreate); 
        $selectedData = $this->layanan_ppo_model->find($ID);
        Template::set('list_status',$list_status);
        Template::set('selectedData',$selectedData);
        //print_r($selectedData);
		Template::set_view('layanan_Ppo/Ppo_deputi/v_form_crud_usulan');
        Template::set('toolbar_title', "Form Usulan Ppo");

        Template::render();
       
    }
	
    public function save_usulan(){
         // Validate the data
         $this->form_validation->set_rules($this->layanan_ppo_model->get_validation_rules());
         $response = array(
             'success'=>false
         );
         $data = $this->layanan_ppo_model->prep_data($this->input->post());
             
         $id_data = $this->input->post("id");
         if(isset($id_data) && !empty($id_data)){    
             $this->layanan_ppo_model->update($id_data,$data);
             $store_data = array(
                 'usulan_id'=>$id_data,
                 'status'=>$data['status'],
                 'alasan'=>$data['alasan']
             );
             $this->db->insert('perkiraan_usulan_log',$store_data);
             $response ['success']= true;
             $response ['message']= "Transaksi berhasil";
         }           
         echo json_encode($response);
    }
    public function form_kirim_usulan(){
        
        $this->db->where('perkiraan_ppo.status >','6');
        $this->layanan_ppo_model->db->group_start();
            $this->layanan_ppo_model->db->where_in("vw.ID",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where_in("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where_in("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where_in("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where_in("vw.ESELON_4",$this->satker_pegawai);
        $this->layanan_ppo_model->db->group_end();
        $num_generate_xls=$this->layanan_ppo_model->count_all();
        if($num_generate_xls>0){
            echo "<center><b>Form Kirim ke Verifikator Instansi tidak dapat ditampilkan, Tidak ada data yang Memenuhi Syarat atau Data telah terkirim</b></center>";    
            return;
        }
       
        Template::set_view('layanan_Ppo/Ppo_deputi/v_form_kirim_usulan');
        Template::set('toolbar_title', "Form Usulan Ppo");

        Template::render();
    }
    function do_kirim_usulan(){
        $this->layanan_ppo_model->db->group_start();
            $this->layanan_ppo_model->db->where('perkiraan_ppo.status','6');
            $this->layanan_ppo_model->db->or_where('perkiraan_ppo.status','4');
            $this->layanan_ppo_model->db->or_where('perkiraan_ppo.status','1');
        $this->layanan_ppo_model->db->group_end();
        $this->layanan_ppo_model->db->group_start();
            $this->layanan_ppo_model->db->where_in("vw.ID",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where_in("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where_in("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where_in("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where_in("vw.ESELON_4",$this->satker_pegawai);
        $this->layanan_ppo_model->db->group_end();

        $records = $this->layanan_ppo_model->find_all();
        if(sizeof($records)>0){
            $no_surat_pengantar = $this->input->post('no_surat_pengantar');
            $update_data = array();
            foreach($records as $record){
                $update_data[] = array(
                    'status'=>'7',
                    'id'=>$record->usulan_id
                );
            }
            
            $this->db->update_batch('perkiraan_ppo',$update_data,'id');

            $output = array(
                'success'=>true
            );
            echo json_encode($output);
        }
        
        
    }
    public function form_dok_pendukung($ID){
        $selectedData = $this->layanan_ppo_model->find($ID);
        Template::set('selectedData',$selectedData);

        $documents = array();
        $this->db->where('perkiraan_id',$ID);
        $this->db->where('tipe','Ppo');
        $documents = $this->usulan_dokumen_model->find_all();
        Template::set('documents',$documents);
        Template::set_view('layanan_Ppo/Ppo_deputi/v_form_dok_pendukung');
        Template::set('toolbar_title', "Form Upload Dokumen Pendukung");

        Template::render();
    }
    public function ajax_list_dokumen(){
        $draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length');
        $start= $this->input->post('start');
        $output=array();
		$output['draw']=$draw;

        $perkiraan_id= $this->input->post('perkiraan_id');
        
        $this->usulan_dokumen_model->where('perkiraan_id',$perkiraan_id);
        $this->usulan_dokumen_model->where('tipe','Ppo');

        $records = $this->usulan_dokumen_model->find_all();
        $total =sizeof($records);
		$output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        $nomor_urut=$start+1;
        foreach($records as $record){
            $row = array();
            $row []  = $nomor_urut;
            $row []  = $record->title;
            $btn_actions = array();
                $btn_actions  [] = "
                        <a class='btn-view' href='#' data-view-url=".base_url('Ppo/Ppo_satker/download_dokumen_pendukung/'.$record->id)."  data-toggle='tooltip' title='Lihat Dokumen'><span class='fa-stack'>
					   	<i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-eye fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
                $row[] = implode(" ",$btn_actions);
            $output['data'][] = $row;
            $nomor_urut ++;
        }
        echo json_encode($output);
    }
    public function list_satker(){
        $this->db->order_by('NAMA_UNOR',"asc");
		$this->unitkerja_model->db->group_start();
			$this->unitkerja_model->db->where_in("vw.ID",$this->satker_pegawai);
			$this->unitkerja_model->db->or_where_in("vw.ESELON_1",$this->satker_pegawai);
			$this->unitkerja_model->db->or_where_in("vw.ESELON_2",$this->satker_pegawai);
			$this->unitkerja_model->db->or_where_in("vw.ESELON_3",$this->satker_pegawai);
			$this->unitkerja_model->db->or_where_in("vw.ESELON_4",$this->satker_pegawai);
		$this->unitkerja_model->db->group_end();
        $satkers = $this->unitkerja_model->find_satker_child();
        $data = array();
        foreach($satkers as $satker){
            //echo json_encode($satker);
            $data[] = array(
                'id'=>$satker->ID,
                'text'=>$satker->NAMA_UNOR,
            );
        }
        $output = array(
            'results'=>$data 
        );
        echo json_encode($output);
    }
    public function list_status(){
        $status_arr = get_list_status_layanan_ppo();
        foreach($status_arr as $row){
            $data[] = array(
                'id'=>$row['id'],
                'text'=>$row['value'],
            );
        }
        $output = array(
            'results'=>$data 
        );
        echo json_encode($output);
    }
    public function cetak_xls(){
		$this->load->helper('dikbud');
		$template_data=$this->db->query("select * from mst_templates where id = ?",array(TEMPLATE_LIST_USULAN_PPO))->first_row();
		
		header('Set-Cookie: fileDownload=true; path=/');


		
		$this->load->library('Template_doc_lib');
		$TBS = $this->template_doc_lib->gen_xls(BASEPATH."../../assets/templates/".$template_data->template_file);
        $data = array();
        $this->layanan_ppo_model->db->group_start();
            $this->layanan_ppo_model->db->where_in("vw.ID",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where_in("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where_in("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where_in("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where_in("vw.ESELON_4",$this->satker_pegawai);
        $this->layanan_ppo_model->db->group_end();
        $records=$this->layanan_ppo_model->find_all();
        foreach($records as $record){
            $informasi_tambahan = array();
            if($record->rwt_hukdis_Ppo){
                $informasi_tambahan[] = "Hukuman Disiplin : ".$record->rwt_hukdis_Ppo;
            }
            if($record->last_ppk){
                $informasi_tambahan[] = "Nilai PPK Terakhir : ".$record->last_ppk;
            }
            $data [] = array(
                'periode'=>$record->layanan_name,
                'nip'=>$record->NIP_BARU,
                'nama'=>$record->NAMA,
                'status'=>get_status_layanan_Ppo($record->status),
                'satuan_kerja'=>$record->nama_satker,
                'keterangan'=>implode("\n",$informasi_tambahan)
            );
            $row []  = $nomor_urut;
                $row []  = $record->layanan_name;
				$row []  = get_status_layanan_Ppo($record->status);
                $row []  = $record->NIP_BARU."<br>".$record->NAMA;
                $row []  = $record->nama_satker;
                $informasi_tambahan = array();
                if($record->rwt_hukdis_Ppo){
                    $informasi_tambahan[] = "Hukuman Disiplin : ".$record->rwt_hukdis_Ppo;
                }
                if($record->last_ppk){
                    $informasi_tambahan[] = "Nilai PPK Terakhir : ".$record->last_ppk;
                }
                $row []  = implode("<br>",$informasi_tambahan);
        }
		$TBS->MergeBlock('o',$data);
		$output_file_name = "Daftar Usulan Ppo.xlsx";
		$TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.
    }
}
