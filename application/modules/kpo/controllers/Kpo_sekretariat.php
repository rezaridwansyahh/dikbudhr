<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Kpo_sekretariat extends Admin_Controller
{
	protected $permissionView = 'LayananKpo.View.PengelolaKPOSekretariat';
    protected $permissionVerifikasiUsulan   = 'LayananKpo.VerifikasiUsulan';
    protected $permissionCetak   = 'LayananKpo.Cetak';
    protected $permissionTambahUsulan   = 'LayananKpo.TambahUsulan';
    protected $permissionKirimInboxInstansi   = 'LayananKpo.KirimInboxInstansi';
    protected $permissionUploadDokumenPendukung = 'LayananKpo.UploadDokumenPendukung';
	protected $permissionViewDokumenPendukung = 'LayananKpo.ViewDokumenPendukung';
	

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('kpo/layanan_kpo_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->helper('kpo/layanan_kpo');
        $this->load->model('kpo/usulan_dokumen_model');
		$this->load->model('pegawai/unitkerja_model');
        $this->load->helper('dikbud');
        $this->role = $this->session->userdata('role_id');
        Template::set('module_url',base_url('kpo/kpo-sekretariat'));
        $this->satker_pegawai = getEselon1Pegawai($this->auth->username());
    }
    public function select2_list_pegawai(){
        $term = $this->input->get('term');
        $this->pegawai_model->like('lower("NAMA")',strtolower($term),"BOTH");
        $this->pegawai_model->or_like('lower("NIP_BARU")',strtolower($term),"BOTH");

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
        
        $this->db->where('perkiraan_kpo.status >','0');

        $this->layanan_kpo_model->db->group_start();
            $this->layanan_kpo_model->db->where_in("vw.ID",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where_in("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where_in("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where_in("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where_in("vw.ESELON_4",$this->satker_pegawai);
        $this->layanan_kpo_model->db->group_end();
        if($filter_unit_kerja){
            $this->layanan_kpo_model->db->group_start();
                $this->layanan_kpo_model->db->where_in("vw.ID",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where_in("vw.ESELON_1",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where_in("vw.ESELON_2",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where_in("vw.ESELON_3",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where_in("vw.ESELON_4",$filter_unit_kerja);
            $this->layanan_kpo_model->db->group_end();
        }
        if($filter_status){
            $this->layanan_kpo_model->db->where_in("status",$filter_status);
        }
        $total= $this->layanan_kpo_model->count_all();
        
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();


		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if($search!=""){
			$this->layanan_kpo_model->where('upper("NAMA") LIKE \'%'.strtoupper($search).'%\'');

        }
		$this->layanan_kpo_model->limit($length,$start);
		
		$kolom = $iSortCol != "" ? $iSortCol : "name";
		$sSortCol == "asc" ? "asc" : "desc";
        $this->layanan_kpo_model->order_by($iSortCol,$sSortCol);
        
        $this->db->where('perkiraan_kpo.status >','0');    
        $this->layanan_kpo_model->db->group_start();
            $this->layanan_kpo_model->db->where_in("vw.ID",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where_in("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where_in("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where_in("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where_in("vw.ESELON_4",$this->satker_pegawai);
        $this->layanan_kpo_model->db->group_end();
        $this->db->order_by('perkiraan_kpo.status','desc'); 

        if($filter_unit_kerja){
            $this->layanan_kpo_model->db->group_start();
                $this->layanan_kpo_model->db->where_in("vw.ID",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where_in("vw.ESELON_1",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where_in("vw.ESELON_2",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where_in("vw.ESELON_3",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where_in("vw.ESELON_4",$filter_unit_kerja);
            $this->layanan_kpo_model->db->group_end();
        }
        if($filter_status){
            $this->layanan_kpo_model->db->where_in("status",$filter_status);
        }
        
		$records=$this->layanan_kpo_model->find_all();

		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($search != "")
		{
			$this->layanan_kpo_model->where('upper("NAMA") LIKE \'%'.strtoupper($search).'%\'');
		    $jum	= $this->layanan_kpo_model->count_all();
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}
		
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $status = get_status_layanan_kpo($record->status);
                if($status != ""){
                    $status = $status."<br>Nomor Surat : ".$record->no_surat_pengantar_es1;
                }
                $row []  = $status;

                $row []  = $record->no_surat_pengantar;
                $row []  = $record->NIP_BARU."<br>".$record->NAMA;
                $informasi_tambahan = array();
                if($record->rwt_hukdis_kpo){
                    $informasi_tambahan[] = "Hukuman Disiplin : ".$record->rwt_hukdis_kpo;
                }
                if($record->last_ppk){
                    $informasi_tambahan[] = "Nilai PPK Terakhir : ".$record->last_ppk;
                }
                $row []  = 
                "
                    <a  class='show-modal-custom' href='".base_url()."pegawai/kepegawaian/detilppk/".$record->NIP_BARU."'  data-toggle='tooltip' tooltip='Informasi PPK' title='Informasi Nilai PPK'>
                    ".implode("<br>",$informasi_tambahan)."
                        </a>
                ";
                $row []  = $record->nama_satker;
                
                $btn_actions = array();
                $btn_actions  [] = "
                    <a  href='".base_url()."admin/kepegawaian/pegawai/profile/".$record->ID."'  data-toggle='tooltip' title='Profile Pegawai'><span class='fa-stack'>
					   	<i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-eye fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
                if($record->status<7 && $this->auth->has_permission($this->permissionVerifikasiUsulan)){
                    $btn_actions  [] = "
                        <a class='show-modal-custom' href='".base_url()."kpo/kpo-sekretariat/edit/".$record->usulan_id."'  data-toggle='tooltip' title='Ubah Data'><span class='fa-stack'>
                            <i class='fa fa-square fa-stack-2x'></i>
                            <i class='fa fa-pencil fa-stack-1x fa-inverse'></i>
                            </span>
                            </a>
                    ";
                }
				if($this->auth->has_permission($this->permissionViewDokumenPendukung))   {
                $btn_actions  [] = "
					<a  class='show-modal-custom' href='".base_url()."kpo/kpo-sekretariat/form_dok_pendukung/".$record->usulan_id."'  data-toggle='tooltip' title='Form Dokumen Pendukung Data'><span class='fa-stack'>
						   <i class='fa fa-square fa-stack-2x'></i>
						   <i class='fa fa-upload fa-stack-1x fa-inverse'></i>
						   </span>
						   </a>
				";
				}
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
		
		$this->auth->restrict($this->permissionView); 
        Template::set('PNS_ID', $PNS_ID);
        Template::set('TAB_ID', uniqid("TAB_CONTOH"));
		
        Template::set('canKirimInboxInstansi',$this->auth->has_permission($this->permissionKirimInboxInstansi));
        Template::set('canCetak',$this->auth->has_permission($this->permissionCetak));
		
		Template::set_view('layanan_kpo/kpo_sekretariat/v_index');
        Template::render();
    }
	public function cetak_xls(){
		$this->load->helper('dikbud');
		$template_data=$this->db->query("select * from mst_templates where id = ?",array(TEMPLATE_LIST_USULAN_KPO))->first_row();
		
		header('Set-Cookie: fileDownload=true; path=/');


		
		$this->load->library('Template_doc_lib');
		$TBS = $this->template_doc_lib->gen_xls(BASEPATH."../../assets/templates/".$template_data->template_file);
        $data = array();
		$filter_pegawai = $this->input->post('filter_pegawai');
        $filter_status = $this->input->post('filter_status');
        $filter_unit_kerja = $this->input->post('filter_unit_kerja');
		if($filter_pegawai){
            $this->layanan_kpo_model->db->group_start();
                $this->layanan_kpo_model->db->where("NIP_BARU",$filter_pegawai);
                $this->layanan_kpo_model->db->or_where("lower(NAMA) like '%".strtolower($filter_pegawai)."%'");
            $this->layanan_kpo_model->db->group_end();
        }
       if($filter_unit_kerja){
            $this->layanan_kpo_model->db->group_start();
                $this->layanan_kpo_model->db->where_in("vw.ID",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where_in("vw.ESELON_1",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where_in("vw.ESELON_2",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where_in("vw.ESELON_3",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where_in("vw.ESELON_4",$filter_unit_kerja);
            $this->layanan_kpo_model->db->group_end();
        }else{
            $this->layanan_kpo_model->db->group_start();
                $this->layanan_kpo_model->db->where("vw.ID",$this->satker_pegawai);
                $this->layanan_kpo_model->db->or_where("vw.ESELON_1",$this->satker_pegawai);
                $this->layanan_kpo_model->db->or_where("vw.ESELON_2",$this->satker_pegawai);
                $this->layanan_kpo_model->db->or_where("vw.ESELON_3",$this->satker_pegawai);
                $this->layanan_kpo_model->db->or_where("vw.ESELON_4",$this->satker_pegawai);
            $this->layanan_kpo_model->db->group_end();
        }
        $this->db->order_by('perkiraan_kpo.status','desc'); 
        if($filter_status){
            $this->layanan_kpo_model->db->where_in("status",$filter_status);
        }
        $this->layanan_kpo_model->order_by("vw.ESELON_2","asc");
        $records=$this->layanan_kpo_model->find_all();
        foreach($records as $record){
            $informasi_tambahan = array();
            if($record->rwt_hukdis_kpo){
                $informasi_tambahan[] = "Hukuman Disiplin : ".$record->rwt_hukdis_kpo;
            }
            if($record->last_ppk){
                $informasi_tambahan[] = "Nilai PPK Terakhir : ".$record->last_ppk;
            }
            $data [] = array(
                'periode'=>$record->layanan_name,
                'nip'=>$record->NIP_BARU,
                'nama'=>$record->NAMA,
                'status'=>get_status_layanan_kpo($record->status),
                'satuan_kerja'=>$record->nama_satker,
                'keterangan'=>implode("\n",$informasi_tambahan)
            );
            $row []  = $nomor_urut;
                $row []  = $record->layanan_name;
				$row []  = get_status_layanan_kpo($record->status);
                $row []  = $record->NIP_BARU."<br>".$record->NAMA;
                $row []  = $record->nama_satker;
                $informasi_tambahan = array();
                if($record->rwt_hukdis_kpo){
                    $informasi_tambahan[] = "Hukuman Disiplin : ".$record->rwt_hukdis_kpo;
                }
                if($record->last_ppk){
                    $informasi_tambahan[] = "Nilai PPK Terakhir : ".$record->last_ppk;
                }
                $row []  = implode("<br>",$informasi_tambahan);
        }
		$TBS->MergeBlock('o',$data);
		$output_file_name = "Daftar Usulan KPO.xlsx";
		$TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.
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
         $this->auth->restrict($this->permissionVerifikasiUsulan); 
        if($this->input->post('save')){
          $response = $this->save();
          if($response['success']){
                Template::set_message($response['msg'], 'success');
                redirect(base_url('kpo/kpo-satker'));
          }
          else {
              if(isset($response['msg'])){
                Template::set_message($response['msg'], 'error');
              } 
          }
        }
        $selectedData = $this->layanan_kpo_model->find($ID);
        Template::set('list_status',$list_status);
        Template::set('selectedData',$selectedData);
        //print_r($selectedData);
		Template::set_view('layanan_kpo/kpo_sekretariat/v_form_crud_usulan');
        Template::set('toolbar_title', "Form Usulan KPO");

        Template::render();
       
    }
    public function act_edit()
    {
        print_r($_POST);
        $status_ms = $this->input->post('status_ms');
        $id = $this->input->post('id');
        for($i=0;$i<count($id);$i++){
            $update_data[] = array(
                'status'=>$status_ms,
                'id'=>$id[$i]
            );
        }

        
        $this->db->update_batch('perkiraan_kpo',$update_data,'id');

        $output = array(
            'success'=>true
        );
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
        $status_arr = get_list_status_layanan_kpo();
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
    public function save_usulan(){
         // Validate the data
         $this->form_validation->set_rules($this->layanan_kpo_model->get_validation_rules());
         $response = array(
             'success'=>false
         );
         $data = $this->layanan_kpo_model->prep_data($this->input->post());
             
         $id_data = $this->input->post("id");
         if(isset($id_data) && !empty($id_data)){    
             $this->layanan_kpo_model->update($id_data,$data);
             $store_data = array(
                 'usulan_id'=>$id_data,
                 'status'=>$data['status'],
                 'alasan'=>$data['alasan']
             );
             $this->db->insert('perkiraan_usulan_log',$store_data);
             $response ['success']= true;
             $response ['message']= "Transaksi berhasil";
        }else {
             $layanan_kpo = $this->db->query("select * from layanan where layanan_tipe_id = 2 and active = true")->first_row();
             
             $usulan_data = $this->layanan_kpo_model->usulan_is_exist($_POST['pegawai_id'],$layanan_kpo->id);
             if($usulan_data){
                 $response ['success']= true;
                 $response ['message']= "Data sudah ada";
             }
             else {
                 $this->pegawai_model->where("PNS_ID",$_POST['pegawai_id']);
                 $user = $this->pegawai_model->find_first_row();
                 $data['nip']=$user->NIP_BARU;
                 $data['layanan_id']=$layanan_kpo->id;
                 $this->layanan_kpo_model->insert($data);
                 $response ['success']= true;
                 $response ['message']= "Transaksi berhasil";
             }
         }           
        // kirim email notif ke pegawai
        $subjek             = "Notifikasi Perubahan status KPO ";
        $isi                = "Proses KPO anda sedang dalam proses<br>".
                                $data['alasan'];
        $this->load->library('emailer/emailer');
        $dataemail = array (
            'subject'   => $subjek,
            'message'   => $isi,
        );
        $resultmail = FALSE;
        $dataemail['to'] = "rifky.zulfikar@kemdikbud.go.id";//$email;
        $resultmail = $this->emailer->send($dataemail,true);// di set false supaya langsung mengirimkan email dan tidak masuk antrian dulu
        if(!$resultmail){
            $resultmail = $this->emailer->send($dataemail,true);
        }
        // end email

         echo json_encode($response);
    }
    public function form_kirim_usulan(){
       
        $this->db->where('perkiraan_kpo.status >','6');
        $this->layanan_kpo_model->db->group_start();
            $this->layanan_kpo_model->db->where_in("vw.ID",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where_in("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where_in("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where_in("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where_in("vw.ESELON_4",$this->satker_pegawai);
        $this->layanan_kpo_model->db->group_end();
        $num_generate_xls=$this->layanan_kpo_model->count_all();
        if($num_generate_xls>0){
            $msg =  "<center><b>Sebelumnya Pernah melakukan pengiriman</b></center>";    
            //return;
        }
        Template::set('msg', $msg);
        Template::set_view('layanan_kpo/kpo_sekretariat/v_form_kirim_usulan');
        Template::set('toolbar_title', "Form Usulan KPO");

        Template::render();
    }
    function do_kirim_usulan(){
        $this->layanan_kpo_model->db->group_start();
            $this->db->where('perkiraan_kpo.status','6');
            $this->db->or_where('perkiraan_kpo.status','4');
            $this->db->or_where('perkiraan_kpo.status','1');
        $this->layanan_kpo_model->db->group_end();
        $this->layanan_kpo_model->db->group_start();
            $this->layanan_kpo_model->db->where_in("vw.ID",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where_in("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where_in("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where_in("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where_in("vw.ESELON_4",$this->satker_pegawai);
        $this->layanan_kpo_model->db->group_end();

        $records = $this->layanan_kpo_model->find_all();
        if (isset($records) && is_array($records) && count($records)){
            $no_surat_pengantar_es1 = $this->input->post('no_surat_pengantar_es1');
            $update_data = array();
            foreach($records as $record){
                $update_data[] = array(
                    'status'=>'7',
                    'id'=>$record->usulan_id,
                    'no_surat_pengantar_es1'=>$no_surat_pengantar_es1
                );
            }
            
            $this->db->update_batch('perkiraan_kpo',$update_data,'id');

            $output = array(
                'success'=>true
            );
            echo json_encode($output);
        }else{
            $output = array(
                'success'=>false
            );
            echo json_encode($output);
        }
        
    }
    function do_kirim_usulan_pending(){
        $this->layanan_kpo_model->db->group_start();
            $this->db->where('perkiraan_kpo.status','6'); // ms sekretariat
            //$this->db->or_where('perkiraan_kpo.status','4'); // data yang telah dikirim dari satker
            //$this->db->or_where('perkiraan_kpo.status','1'); // awal perkiraan BKN
        $this->layanan_kpo_model->db->group_end();
        $this->layanan_kpo_model->db->group_start();
            $this->layanan_kpo_model->db->where_in("vw.ID",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where_in("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where_in("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where_in("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where_in("vw.ESELON_4",$this->satker_pegawai);
        $this->layanan_kpo_model->db->group_end();

        $records = $this->layanan_kpo_model->find_all();
        if (isset($records) && is_array($records) && count($records)){
            $no_surat_pengantar_es1 = $this->input->post('no_surat_pengantar_es1');
            $update_data = array();
            foreach($records as $record){
                $update_data[] = array(
                    'status'=>'7',
                    'id'=>$record->usulan_id,
                    'no_surat_pengantar_es1'=>$no_surat_pengantar_es1
                );
            }
            
            $this->db->update_batch('perkiraan_kpo',$update_data,'id');

            $output = array(
                'msg'=>"Berhasil",
                'success'=>true
            );
            echo json_encode($output);
        }else{
            $output = array(
                'msg'=>"Tidak ada data Memenuhi Syarat",
                'success'=>false
            );
            echo json_encode($output);
        }
        
        
    }
    public function form_dok_pendukung($ID){
        $selectedData = $this->layanan_kpo_model->find($ID);
        Template::set('selectedData',$selectedData);

        $documents = array();
        $this->db->where('perkiraan_id',$ID);
        $this->db->where('tipe','kpo');
        $documents = $this->usulan_dokumen_model->find_all();
        Template::set('documents',$documents);
        Template::set_view('layanan_kpo/kpo_sekretariat/v_form_dok_pendukung');
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
        $this->usulan_dokumen_model->where('tipe','kpo');

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
                        <a class='btn-view' href='#' data-view-url=".base_url('kpo/kpo_satker/download_dokumen_pendukung/'.$record->id)."  data-toggle='tooltip' title='Lihat Dokumen'><span class='fa-stack'>
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
    public function indexall(){
        
        $this->auth->restrict($this->permissionView); 
        
        Template::set('PNS_ID', $PNS_ID);
        Template::set('TAB_ID', uniqid("TAB_CONTOH"));
        Template::set('canTambahUsulan',$this->auth->has_permission($this->permissionTambahUsulan));
        Template::set('canKirimInboxSekretariat',$this->auth->has_permission($this->permissionKirimInboxSekretariat));
        Template::set('canKirimInboxInstansi',$this->auth->has_permission($this->permissionKirimInboxInstansi));
        Template::set('canCetak',$this->auth->has_permission($this->permissionCetak));
        Template::set_view('layanan_kpo/kpo_sekretariat/v_indexall');
        Template::render();
    }
    public function create()
    {
        $this->auth->restrict($this->permissionTambahUsulan); 
        $this->db->where('perkiraan_kpo.status > ','3');
        $this->layanan_kpo_model->db->group_start();
            $this->layanan_kpo_model->db->where("vw.ID",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_4",$this->satker_pegawai);
        $this->layanan_kpo_model->db->group_end();
        $num_generate_xls = $this->layanan_kpo_model->count_all();
        $msg  = "";
        if($num_generate_xls>0){
            $msg  = "<center> <b>Informasi :</b> Usulan pengajuan Data telah dikirim sebelumnya dari satker anda</center>";    
        }
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
        if($this->input->post('save')){
          $response = $this->save();
          if($response['success']){
                Template::set_message($response['msg'], 'success');
                redirect(base_url('kpo/kpo-satker'));
          }
          else {
              if(isset($response['msg'])){
                Template::set_message($response['msg'], 'error');
              } 
          }
        }
        Template::set_view('layanan_kpo/kpo_sekretariat/v_form_crud_usulan');
        Template::set('toolbar_title', "Form Usulan KPO");
        Template::set('list_status',$list_status);
        Template::set('msg',$msg);
        Template::render();
    }
    public function ajax_listall(){
        
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length');
        $start= $this->input->post('start');
        $filter_pegawai = $this->input->post('filter_pegawai');
        $filter_status = $this->input->post('filter_status');
        $filter_unit_kerja = $this->input->post('filter_unit_kerja');
        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $filter_status = $this->input->post('filter_status[]');
        if(sizeof($filter_status)>0 && is_array($filter_status)){
            $this->layanan_kpo_model->db->where_in("status",$filter_status);
        }

        if($filter_pegawai){
            $this->layanan_kpo_model->db->group_start();
                $this->layanan_kpo_model->db->where("NIP_BARU",$filter_pegawai);
                $this->layanan_kpo_model->db->or_where("lower(NAMA) like '%".strtolower($filter_pegawai)."%'");
            $this->layanan_kpo_model->db->group_end();
        }

        if($filter_unit_kerja){
            $this->layanan_kpo_model->db->group_start();
                $this->layanan_kpo_model->db->where_in("vw.ID",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where_in("vw.ESELON_1",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where_in("vw.ESELON_2",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where_in("vw.ESELON_3",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where_in("vw.ESELON_4",$filter_unit_kerja);
            $this->layanan_kpo_model->db->group_end();
        }else{
            $this->layanan_kpo_model->db->group_start();
            $this->layanan_kpo_model->db->where("vw.ID",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_4",$this->satker_pegawai);
            $this->layanan_kpo_model->db->group_end();
        }

        
        if($filter_status){
            $this->layanan_kpo_model->db->where_in("status",$filter_status);
        }
        $total= $this->layanan_kpo_model->count_all();
        
        $output=array();
        $output['draw']=$draw;

        
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();


        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        if($search!=""){
            $this->layanan_kpo_model->where('upper("NAMA") LIKE \'%'.strtoupper($search).'%\'');

        }
        $this->layanan_kpo_model->limit($length,$start);
        
        $kolom = $iSortCol != "" ? $iSortCol : "name";
        $sSortCol == "asc" ? "asc" : "desc";
        $this->layanan_kpo_model->order_by($iSortCol,$sSortCol);
        
        if($filter_pegawai){
            $this->layanan_kpo_model->db->group_start();
                $this->layanan_kpo_model->db->where("NIP_BARU",$filter_pegawai);
                $this->layanan_kpo_model->db->or_where("lower(NAMA) like '%".strtolower($filter_pegawai)."%'");
            $this->layanan_kpo_model->db->group_end();
        }
       if($filter_unit_kerja){
            $this->layanan_kpo_model->db->group_start();
                $this->layanan_kpo_model->db->where_in("vw.ID",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where_in("vw.ESELON_1",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where_in("vw.ESELON_2",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where_in("vw.ESELON_3",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where_in("vw.ESELON_4",$filter_unit_kerja);
            $this->layanan_kpo_model->db->group_end();
        }else{
            $this->layanan_kpo_model->db->group_start();
                $this->layanan_kpo_model->db->where("vw.ID",$this->satker_pegawai);
                $this->layanan_kpo_model->db->or_where("vw.ESELON_1",$this->satker_pegawai);
                $this->layanan_kpo_model->db->or_where("vw.ESELON_2",$this->satker_pegawai);
                $this->layanan_kpo_model->db->or_where("vw.ESELON_3",$this->satker_pegawai);
                $this->layanan_kpo_model->db->or_where("vw.ESELON_4",$this->satker_pegawai);
            $this->layanan_kpo_model->db->group_end();
        }
        $this->db->order_by('perkiraan_kpo.status','desc'); 
        if($filter_status){
            $this->layanan_kpo_model->db->where_in("status",$filter_status);
        }
        $this->layanan_kpo_model->order_by("vw.ESELON_2","asc");
        $records=$this->layanan_kpo_model->find_all();

        /*Ketika dalam mode pencarian, berarti kita harus
        'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
        yang mengandung keyword tertentu
        */
        if($search != "")
        {
            $this->layanan_kpo_model->where('upper("NAMA") LIKE \'%'.strtoupper($search).'%\'');
            $jum    = $this->layanan_kpo_model->count_all();
            $output['recordsTotal']=$output['recordsFiltered']=$jum;
        }
        
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = "<input name='id[]'' type='checkbox' value='".$record->id."'>";
                //$row []  = $nomor_urut;
                $row []  = $record->NIP_BARU."<br>".$record->NAMA;
                $row []  = $record->nama_satker;
                $status = get_status_layanan_kpo($record->status);
                if($status != ""){
                    $status = $status."<br>Nomor Surat : ".$record->no_surat_pengantar;
                }
                $row []  = $status;
                $informasi_tambahan = array();
                if($record->rwt_hukdis_kpo){
                    $informasi_tambahan[] = "Hukuman Disiplin : ".$record->rwt_hukdis_kpo;
                }
                if($record->last_ppk){
                    $informasi_tambahan[] = "Nilai PPK Terakhir : ".$record->last_ppk;
                }
                $row []  = 
                "
                    <a  class='show-modal-custom' href='".base_url()."pegawai/kepegawaian/detilppk/".$record->NIP_BARU."'  data-toggle='tooltip' tooltip='Informasi PPK' title='Informasi Nilai PPK'>
                    ".implode("<br>",$informasi_tambahan)."
                        </a>
                ";

                
                
                $btn_actions = array();
                 
                if($record->status<4){
                    $btn_actions  [] = "
                        <a class='show-modal-custom' href='".base_url()."kpo/kpo-sekretariat/edit/".$record->usulan_id."'  data-toggle='tooltip' title='Verifikasi Data'><span class='fa-stack'>
                            <i class='fa fa-square fa-stack-2x'></i>
                            <i class='fa fa-pencil fa-stack-1x fa-inverse'></i>
                            </span>
                            </a>
                    ";
                }
                if($this->auth->has_permission($this->permissionViewDokumenPendukung)) {
                $btn_actions  [] = "
                    <a  class='show-modal-custom' href='".base_url()."kpo/kpo-satker/form_dok_pendukung/".$record->usulan_id."'  data-toggle='tooltip' title='Form Dokumen Pendukung Data'><span class='fa-stack'>
                        <i class='fa fa-square fa-stack-2x'></i>
                        <i class='fa fa-upload fa-stack-1x fa-inverse'></i>
                        </span>
                        </a>
                ";
                }
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
}
