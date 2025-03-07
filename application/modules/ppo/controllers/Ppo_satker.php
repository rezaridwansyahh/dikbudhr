<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Ppo_satker extends Admin_Controller
{
    protected $permissionCreate = 'LayananPPO.View.PengelolaPPOSatker';
    protected $permissionEdit   = 'LayananPPO.Masters.Edit';
    protected $permissionCetak  = 'LayananPPO.Cetak';
    protected $permissionUploadDokumenPendukung = 'LayananPPO.UploadDokumenPendukung';
    protected $permissionKirimInboxSekretariat = 'LayananPPO.KirimInboxSekretariat';
    protected $permissionTambahUsulan = 'LayananPPO.TambahUsulan';
    
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ppo/layanan_ppo_model');
        $this->load->model('ppo/usulan_dokumen_model');
        $this->load->model('pegawai/pegawai_model');
		$this->load->helper('dikbud');
        $this->role = $this->session->userdata('role_id');
        Template::set('module_url',base_url('ppo/ppo-satker'));
        $this->satker_pegawai = getSatkerPegawai($this->auth->username());
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
        $this->usulan_dokumen_model->where('tipe','ppo');

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
                        <a class='btn-view' href='#' data-view-url=".base_url('ppo/ppo_satker/download_dokumen_pendukung/'.$record->id)."  data-toggle='tooltip' title='Lihat Dokumen'><span class='fa-stack'>
					   	<i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-eye fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
                $btn_actions  [] = "
                        <a href='#' data-remove-url=".base_url('ppo/ppo_satker/delete_dokumen_pendukung/'.$record->id)." class='btn-hapus' data-toggle='tooltip' title='Hapus data' >
                        <span class='fa-stack'>
                        <i class='fa fa-square fa-stack-2x'></i>
                        <i class='fa fa-trash-o fa-stack-1x fa-inverse'></i>
                        </span>
                        </a>
                ";
                $row[] = implode(" ",$btn_actions);
            $output['data'][] = $row;
            $nomor_urut ++;
        }
        echo json_encode($output);
    }
    public function ajax_list(){
        
        $draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length');
		$start= $this->input->post('start');
        $filter_status = $this->input->post('filter_status');
		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		
        $this->layanan_ppo_model->db->group_start();
            $this->layanan_ppo_model->db->where("vw.ID",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_4",$this->satker_pegawai);
        $this->layanan_ppo_model->db->group_end();
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
        
        $this->layanan_ppo_model->db->group_start();
            $this->layanan_ppo_model->db->where("vw.ID",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_4",$this->satker_pegawai);
        $this->layanan_ppo_model->db->group_end();
        $this->db->order_by('perkiraan_ppo.status','desc'); 
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
                $row []  = get_status_layanan_ppo($record->status);
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
                if($record->status<4){
                    $btn_actions  [] = "
                        <a class='show-modal-custom' href='".base_url()."ppo/ppo-satker/edit/".$record->usulan_id."'  data-toggle='tooltip' title='Ubah Data'><span class='fa-stack'>
                            <i class='fa fa-square fa-stack-2x'></i>
                            <i class='fa fa-pencil fa-stack-1x fa-inverse'></i>
                            </span>
                            </a>
                    ";
                }
                $btn_actions  [] = "
                    <a  class='show-modal-custom' href='".base_url()."ppo/ppo-satker/form_dok_pendukung/".$record->usulan_id."'  data-toggle='tooltip' title='Upload Dokumen Pendukung Data'><span class='fa-stack'>
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
    
	public function usulkan_pensiun($pns_id){
		$layanan_ppo = $this->db->query("select * from layanan where layanan_tipe_id = 1 and active = true")->first_row();
             
		$usulan_data = $this->layanan_ppo_model->usulan_is_exist($pns_id,$layanan_ppo->id);
		 if($usulan_data){
			 $output ['success']= true;
			 $output ['message']= "Data sudah ada";
		 }
		 else {
			 $this->pegawai_model->where("PNS_ID",$pns_id);
			 $user = $this->pegawai_model->find_first_row();
			 $data['nip']=$user->NIP_BARU;
			 $data['layanan_id']=$layanan_ppo->id;
			 $this->layanan_ppo_model->insert($data);
			 $output ['success']= true;
			 $output ['message']= "Transaksi berhasil";
		 }
		$output = array(
			'success'=>true,
			'message'=>'Data berhasil di tambah'
		);
		print_json($output);
	}
	public function ajax_list_perkiraan_pensiun(){
        
        $draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length');
		$start= $this->input->post('start');
        $filter_status = $this->input->post('filter_status');
		$filter_tahun = $this->input->post('filter_tahun');
		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$this->layanan_ppo_model->db->group_start();
            $this->layanan_ppo_model->db->where("vw.ID",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_4",$this->satker_pegawai);
        $this->layanan_ppo_model->db->group_end();
        $total= $this->layanan_ppo_model->count_all_pensiun($this->satker_pegawai,$filter_tahun);
        
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();


		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
        $this->layanan_ppo_model->db->group_start();
            $this->layanan_ppo_model->db->where("vw.ID",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_4",$this->satker_pegawai);
        $this->layanan_ppo_model->db->group_end();
		if($search!=""){
			$this->layanan_ppo_model->where('upper("NAMA") LIKE \'%'.strtoupper($search).'%\'');

        }
		$this->layanan_ppo_model->limit($length,$start);
		
		$kolom = $iSortCol != "" ? $iSortCol : "name";
		$sSortCol == "asc" ? "asc" : "desc";
        $this->layanan_ppo_model->order_by($iSortCol,$sSortCol);
      
        if($filter_status){
            $this->layanan_ppo_model->db->where_in("status",$filter_status);
        }
		$records=$this->layanan_ppo_model->find_all_pensiun($this->satker_pegawai,$filter_tahun);

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
                $row []  = $record->NIP_BARU."<br>".$record->NAMA;
				$row []  = $record->umur;
				$row []  = $record->NAMA_JABATAN;
				$row []  = $record->PENSIUN;
				$row []  = $record->tahun_pensiun;
				$row []  = $record->NAMA_UNOR;
               
                $btn_actions = array();
				if(!$record->usulan_nip){
					$btn_actions  [] = "
                        <a class='btn-usulkan' href='".base_url()."ppo/ppo-satker/usulkan_pensiun/".$record->PNS_ID."'  data-toggle='tooltip' title='Masukkan daftar usulan pensiun'><span class='fa-stack'>
                            <i class='fa fa-square fa-stack-2x'></i>
                            <i class='fa fa-arrow-right fa-stack-1x fa-inverse'></i>
                            </span>
                            </a>
                    ";	
				}
				else {
					$btn_actions [] = "<label class='label label-info'>Sedang di Usulkan</label>";
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
        Template::set_view('layanan_ppo/ppo_satker/v_index');
        Template::set('canKirimInboxSekretariat',$this->auth->has_permission($this->permissionKirimInboxSekretariat));
        Template::set('canTambahUsulan',$this->auth->has_permission($this->permissionTambahUsulan));
        Template::set('canCetak',$this->auth->has_permission($this->permissionCetak));
        
        Template::render();
    }
	/**
     * Create a Agama object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate); 
        $this->db->where('perkiraan_ppo.status > ','3');
        $this->layanan_ppo_model->db->group_start();
            $this->layanan_ppo_model->db->where("vw.ID",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_4",$this->satker_pegawai);
        $this->layanan_ppo_model->db->group_end();
        $num_generate_xls=$this->layanan_ppo_model->count_all();
        if($num_generate_xls>0){
            echo "<center>Form <b>Tambah Usulan</b> tidak dapat ditampilkan, Data telah terkirim </center>";    
            return;
        }
        $list_status = array(
            array(
                'name'=>'Memenuhi Syarat (MS)',
                'value'=>'3'
            ),
            array(
                'name'=>'Tidak Memenuhi Syarat (TMS)',
                'value'=>'2'
            ),
        );
		if($this->input->post('save')){
          $response = $this->save();
          if($response['success']){
                Template::set_message($response['msg'], 'success');
                redirect(base_url('ppo/ppo-satker'));
          }
          else {
              if(isset($response['msg'])){
                Template::set_message($response['msg'], 'error');
              } 
          }
        }
        Template::set_view('layanan_ppo/ppo_satker/v_form_crud_usulan');
        Template::set('toolbar_title', "Form Usulan ppo");
        Template::set('list_status',$list_status);
        Template::render();
    }

   
	public function edit($ID)
    {
        $list_status = array(
            array(
                'name'=>'Memenuhi Syarat (MS)',
                'value'=>'3'
            ),
            array(
                'name'=>'Tidak Memenuhi Syarat (TMS)',
                'value'=>'2'
            ),
        );
        //$this->auth->restrict($this->permissionEdit); 
        if($this->input->post('save')){
          $response = $this->save();
          if($response['success']){
                Template::set_message($response['msg'], 'success');
                redirect(base_url('ppo/ppo-satker'));
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
		Template::set_view('layanan_ppo/ppo_satker/v_form_crud_usulan');
        Template::set('toolbar_title', "Form Usulan ppo");

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
             );
             $this->db->insert('perkiraan_usulan_log',$store_data);
             $response ['success']= true;
             $response ['message']= "Transaksi berhasil";
         }
         else {
             $layanan_ppo = $this->db->query("select * from layanan where layanan_tipe_id = 1 and active = true")->first_row();
             
             $usulan_data = $this->layanan_ppo_model->usulan_is_exist($_POST['pegawai_id'],$layanan_ppo->id);
             if($usulan_data){
                 $response ['success']= true;
                 $response ['message']= "Data sudah ada";
             }
             else {
                 $this->pegawai_model->where("PNS_ID",$_POST['pegawai_id']);
                 $user = $this->pegawai_model->find_first_row();
                 $data['nip']=$user->NIP_BARU;
                 $data['layanan_id']=$layanan_ppo->id;
                 $this->layanan_ppo_model->insert($data);
                 $response ['success']= true;
                 $response ['message']= "Transaksi berhasil";
             }
         }                
         echo json_encode($response);
    }
    public function form_kirim_usulan(){
        $this->db->where('perkiraan_ppo.status','1');
        $this->layanan_ppo_model->db->group_start();
            $this->layanan_ppo_model->db->where("vw.ID",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_4",$this->satker_pegawai);
        $this->layanan_ppo_model->db->group_end();
        $num_generate_xls=$this->layanan_ppo_model->count_all();
        if($num_generate_xls>0){
            echo "<center><b>Data tidak dapat di kirim, masih ada yang belum di verifikasi</b></center>";    
            return;
        }

        $this->db->where('perkiraan_ppo.status >','3');
        $this->layanan_ppo_model->db->group_start();
            $this->layanan_ppo_model->db->where("vw.ID",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_4",$this->satker_pegawai);
        $this->layanan_ppo_model->db->group_end();
        $num_generate_xls=$this->layanan_ppo_model->count_all();
        if($num_generate_xls>0){
            echo "<center><b>Form Kirim ke Verifikator Biro tidak dapat ditampilkan, Tidak ada data yang Memenuhi Syarat atau Data telah terkirim</b></center>";    
            return;
        }
       
        Template::set_view('layanan_ppo/ppo_satker/v_form_kirim_usulan');
        Template::set('toolbar_title', "Form Usulan ppo");

        Template::render();
    }
    function do_kirim_usulan(){
        $this->layanan_ppo_model->db->group_start();
            $this->db->where('perkiraan_ppo.status','1');
            $this->db->or_where('perkiraan_ppo.status','3');
        $this->layanan_ppo_model->db->group_end();
        $this->layanan_ppo_model->db->group_start();
            $this->layanan_ppo_model->db->where("vw.ID",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_4",$this->satker_pegawai);
        $this->layanan_ppo_model->db->group_end();

        $records = $this->layanan_ppo_model->find_all();
        if(sizeof($records)>0){
            $no_surat_pengantar = $this->input->post('no_surat_pengantar');
            $update_data = array();
            foreach($records as $record){
                $update_data[] = array(
                    'status'=>'7',
                    'id'=>$record->usulan_id,
                    'no_surat_pengantar'=>$no_surat_pengantar
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
        $this->db->where('tipe','ppo');
        $documents = $this->usulan_dokumen_model->find_all();
        Template::set('documents',$documents);
        Template::set_view('layanan_ppo/ppo_satker/v_form_dok_pendukung');
        Template::set('toolbar_title', "Form Upload Dokumen Pendukung");

        Template::render();
    }
    public function save_dokumen_pendukung(){
        $this->load->helper('handle_upload');
		$uploadData = array();
		$upload = true;
		$id = "";
        $namafile = "";
        $template_folder = "assets/dok_usulan";
        if (isset($_FILES['userfile']) && is_array($_FILES['userfile']) && $_FILES['userfile']['error'] != 4)
        {
           $tmp_name = pathinfo($_FILES['userfile']['name'], PATHINFO_FILENAME);
           $ext = pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION);
           $newFileName = uniqid("usulan_ppo_").".".$ext;
           $uploadData = handle_upload('userfile',$template_folder,$newFileName);
            if (isset($uploadData['error']) && !empty($uploadData['error']))
            {
                $tipefile=$_FILES['userfile']['type'];
                $upload = false;
            }else{
                $namafile = $uploadData['data']['file_name'];
            }
        }else{
            die("File tidak ditemukan");
            log_activity($this->auth->user_id(), 'File tidak ditemukan : ' . $this->input->ip_address(), 'pegawai');
        } 	

        $data = $this->usulan_dokumen_model->prep_data($this->input->post());
        $data['file_upload'] = $namafile;
        $data['tipe'] = 'ppo';
        $data['title'] = $this->input->post("nama_dokumen");
        $data['perkiraan_id'] = $this->input->post("perkiraan_id");
        $id = $this->input->post("id");
        if(isset($id) && !empty($id)){
            $this->usulan_dokumen_model->update($id,$data);
        }
        else $this->usulan_dokumen_model->insert($data);
        $response ['success']= true;
        $response ['msg']= "Transaksi berhasil";
        echo json_encode($response);    
    }

    public function delete_dokumen_pendukung($record_id){
        if ($this->usulan_dokumen_model->delete($record_id)) {
            log_activity($this->auth->user_id(), 'delete data templates : ' . $record_id . ' : ' . $this->input->ip_address(), 'pegawai');
            //Template::set_message("Sukses Hapus data", 'success');
            echo "Sukses";
        }else{
            echo "Gagal";
        }   
    }
    public function download_dokumen_pendukung($record_id){
        $data = $this->usulan_dokumen_model->find($record_id);
		$template_file = "assets/dok_usulan/".$data->file_upload;
		$this->load->helper('download');
		force_download($template_file, NULL);
		
    }
    public function cetak_xls(){
		$this->load->helper('dikbud');
		$template_data=$this->db->query("select * from mst_templates where id = ?",array(TEMPLATE_LIST_USULAN_PPO))->first_row();
		
		header('Set-Cookie: fileDownload=true; path=/');


		
		$this->load->library('Template_doc_lib');
		$TBS = $this->template_doc_lib->gen_xls(BASEPATH."../../assets/templates/".$template_data->template_file);
        $data = array();
        $this->layanan_ppo_model->db->group_start();
            $this->layanan_ppo_model->db->where("vw.ID",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_ppo_model->db->or_where("vw.ESELON_4",$this->satker_pegawai);
        $this->layanan_ppo_model->db->group_end();
        $records=$this->layanan_ppo_model->find_all();
        foreach($records as $record){
            $informasi_tambahan = array();
            if($record->rwt_hukdis_Ppo){
                $informasi_tambahan[] = "Hukuman Disiplin : ".$record->rwt_hukdis_Ppo."<br>";
            }
            $informasi_tambahan[] = "BUP : ".$record->bup;
            $data [] = array(
                'periode'=>$record->layanan_name,
                'nip'=>$record->NIP_BARU,
                'nama'=>$record->NAMA,
                'status'=>get_status_layanan_Ppo($record->status),
                'satuan_kerja'=>$record->nama_satker,
                'keterangan'=>implode("\n",$informasi_tambahan)
            );
            
        }
		$TBS->MergeBlock('o',$data);
		$output_file_name = "Daftar Usulan Ppo.xlsx";
		$TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.
    }
}
