<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Kpo_instansi extends Admin_Controller
{
	protected $permissionView = 'LayananKpo.View.PengelolaKPOInstansi';
    protected $permissionVerifikasiUsulan   = 'LayananKpo.VerifikasiUsulan';
    protected $permissionCetak   = 'LayananKpo.Cetak';
    protected $permissionTambahUsulan   = 'LayananKpo.TambahUsulan';
    protected $permissionUploadDataBKN   = 'LayananKpo.UploadDataBKN';
    protected $permissionKirimInboxInstansi   = 'LayananKpo.KirimInboxInstansi';
	protected $permissionFinalisasiUsulan   = 'LayananKpo.FinalisasiUsulan';
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
        $this->load->helper('dikbud');
        $this->load->model('kpo/usulan_dokumen_model');
        $this->load->model('pegawai/unitkerja_model');
        $this->role = $this->session->userdata('role_id');
        Template::set('module_url',base_url('kpo/kpo-instansi'));
    }
    
    public function proses_usulan($nama_file){
        $file = $nama_file;
        $this->load->library('Excel');
        $objPHPExcel = PHPExcel_IOFactory::load($file);
            //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();
        $nips = array();
        $usulans = array();
        for ($row = 12; $row <= $highestRow; $row++)
        {
            //$nip = trim(preg_replace('/\s\s+/', ' ', $string));
            $nips[] =  $sheet->getCell('B'.$row)->getValue();
            $rec = array(
                'nip'=>$sheet->getCell('B'.$row)->getValue(),
                'nama'=>$sheet->getCell('C'.$row)->getValue(),
                'birth_place'=>$sheet->getCell('D'.$row)->getValue(),
                'birth_date'=>convertDate($sheet->getCell('E'.$row)->getValue(),'d-m-Y','Y-m-d'),
                'last_edu'=>$sheet->getCell('F'.$row)->getValue(),
                'tahun_lulus'=>(int)$sheet->getCell('G'.$row)->getValue(),
                'o_gol_ruang'=>$sheet->getCell('H'.$row)->getValue(),
                'o_gol_tmt'=>convertDate($sheet->getCell('I'.$row)->getValue(),'d-m-Y','Y-m-d'),
                'o_masakerja_thn'=>(int)$sheet->getCell('J'.$row)->getValue(),
                'o_masakerja_bln'=>(int)$sheet->getCell('K'.$row)->getValue(),
                'o_gapok'=>$sheet->getCell('L'.$row)->getValue(),
                'o_jabatan'=>$sheet->getCell('M'.$row)->getValue(),
                'o_tmt_jabatan'=>convertDate($sheet->getCell('N'.$row)->getValue(),'d-m-Y','Y-m-d'),
                'n_gol_ruang'=>$sheet->getCell('O'.$row)->getValue(),
                'n_gol_tmt'=>$sheet->getCell('P'.$row)->getValue(),
                'n_masakerja_thn'=>(int)$sheet->getCell('Q'.$row)->getValue(),
                'n_masakerja_bln'=>(int)$sheet->getCell('R'.$row)->getValue(),
                'n_gapok'=>$sheet->getCell('S'.$row)->getValue(),
                'n_jabatan'=>$sheet->getCell('T'.$row)->getValue(),
                'n_tmt_jabatan'=>convertDate($sheet->getCell('U'.$row)->getValue(),'d-m-Y','Y-m-d'),
                'unit_kerja'=>$sheet->getCell('V'.$row)->getValue(),
                'unit_kerja_induk'=>$sheet->getCell('W'.$row)->getValue(),
                'kantor_pembayaran'=>$sheet->getCell('X'.$row)->getValue()
            );
            $usulans[$sheet->getCell('B'.$row)->getValue()] = $rec;
        }
        
        $layanan_name = $sheet->getCell('A5')->getValue();

        $layanan_data = $this->db->query("select nextval('hris.layanan_id_seq1'::regclass) as id")->first_row();
        
        $this->db->where('layanan_tipe_id',2);
        $this->db->update('layanan',array(
            'active'=>false
        ));
        $this->db->insert('layanan',array(
            'name'=>$layanan_name,
			'active'=>true,
            'layanan_tipe_id'=>2,
            'id'=>$layanan_data->id 
        ));
    
        if(sizeof($nips)==0){
            return;
        }
        
        $data = array();
        foreach($usulans as $key=>$record){
            $record['layanan_id']=$layanan_data->id;
			$record->status = 1;
            $data[] = $record;
        }
        
        $this->db->insert_batch("perkiraan_kpo",$data);
    }
    public function do_upload(){
        $this->load->helper('handle_upload');
        $id = "";
        $namafile = "";
        if (isset($_FILES['userfile']) && is_array($_FILES['userfile']) && $_FILES['userfile']['error'] != 4)
        {
           $tmp_name = pathinfo($_FILES['userfile']['name'], PATHINFO_FILENAME);
           //print_r($_FILES['userfile']);
           $data = $this->proses_usulan($_FILES['userfile']['tmp_name']);
           $output = array(
                'success'=>true,
                'message'=> $data
           );
           echo json_encode($output);
           die();
        }else{
            die("File tidak ditemukan");
        } 	
        echo '{"namafile":"'.$namafile.'"}';
        exit();
    }
    public function upload_data_bkn_form(){
        Template::set_view('layanan_kpo/kpo_instansi/upload_data_bkn_form');
        Template::render();
    }
    public function upload_data_status_bkn(){
        Template::set_view('layanan_kpo/kpo_instansi/upload_data_status_bkn');
        Template::render();
    }
    public function do_uploadstatus(){
        $this->load->helper('handle_upload');
        $id = "";
        $namafile = "";
        if (isset($_FILES['userfile']) && is_array($_FILES['userfile']) && $_FILES['userfile']['error'] != 4)
        {
           $tmp_name = pathinfo($_FILES['userfile']['name'], PATHINFO_FILENAME);
           //print_r($_FILES['userfile']);
           $data = $this->proses_status_usulan($_FILES['userfile']['tmp_name']);
           $output = array(
                'success'=>true,
                'message'=> $data
           );
           echo json_encode($output);
           die();
        }else{
            die("File tidak ditemukan");
        }   
        
        exit();
    }
    public function proses_status_usulan($nama_file){
        $file = $nama_file;
        $this->load->library('Excel');
        $objPHPExcel = PHPExcel_IOFactory::load($file);
            //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();
        $nips = array();
        $usulans = array();
        $rowperiode = 3;
        $update_data = array();
        for ($row = 8; $row <= $highestRow; $row++)
        {
            //$nip = trim(preg_replace('/\s\s+/', ' ', $string));
            $NIP        =  $sheet->getCell('S'.$row)->getValue();
            $periode    =  $sheet->getCell('A'.$rowperiode)->getValue();
            $periode    = str_replace("Periode : ", "", $periode);
            $status     =  trim($sheet->getCell('J'.$row)->getValue());
            $statusapp = 11;
            if($status == "Belum Diproses")
                $statusapp = "11";
            if($status == "Batal Berkas")
                $statusapp = "12";
            if($status == "NP")
                $statusapp = "13";
            if($status == "BTL")
                $statusapp = "14";
            if($status == "TMS")
                $statusapp = "15";
            if($status == "Proses KP Selesai")
                $statusapp = "16";

                $update_data[] = array(
                    'status'=>$statusapp,
                    'nip'=>$NIP
                );
               // echo $NIP;
        }
        $this->db->update_batch('perkiraan_kpo',$update_data,'nip');
    }
    public function list_satker(){
        $this->db->order_by('NAMA_UNOR',"asc");
        $satkers = $this->unitkerja_model->find_satker();
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
        $filter_pegawai = $this->input->post('filter_pegawai');
        $filter_unit_kerja = $this->input->post('filter_unit_kerja');
        $filter_status = $this->input->post('filter_status');
        $no_surat_pengantar_es1 = $this->input->post('no_surat_pengantar_es1');
        
		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		if($filter_unit_kerja){
            $this->layanan_kpo_model->db->group_start();
                $this->layanan_kpo_model->db->where("vw.ID",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where("vw.ESELON_1",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where("vw.ESELON_2",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where("vw.ESELON_3",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where("vw.ESELON_4",$filter_unit_kerja);
            $this->layanan_kpo_model->db->group_end();
        }
        if($filter_pegawai){
            $this->layanan_kpo_model->db->group_start();
                $this->layanan_kpo_model->db->where("NIP_BARU",$filter_pegawai);
                $this->layanan_kpo_model->db->or_where("lower(NAMA) like '%".strtolower($filter_pegawai)."%'");
            $this->layanan_kpo_model->db->group_end();
        }
        if($filter_status){
            $this->layanan_kpo_model->db->where_in("status",$filter_status);
        }

        if($no_surat_pengantar_es1){
            $this->layanan_kpo_model->db->where("no_surat_pengantar_es1",$no_surat_pengantar_es1);
        }
		$total= $this->layanan_kpo_model->count_all();;
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
        
		$this->db->order_by('perkiraan_kpo.unit_kerja_induk','asc'); 
		$this->db->order_by('perkiraan_kpo.nama','asc'); 

        if($filter_unit_kerja){
            $this->layanan_kpo_model->db->group_start();
                $this->layanan_kpo_model->db->where("vw.ID",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where("vw.ESELON_1",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where("vw.ESELON_2",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where("vw.ESELON_3",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where("vw.ESELON_4",$filter_unit_kerja);
            $this->layanan_kpo_model->db->group_end();
        }
        if($filter_pegawai){
            $this->layanan_kpo_model->db->group_start();
                $this->layanan_kpo_model->db->where("NIP_BARU",$filter_pegawai);
                $this->layanan_kpo_model->db->or_where("lower(NAMA) like '%".strtolower($filter_pegawai)."%'");
            $this->layanan_kpo_model->db->group_end();
        }
        if($filter_status){
            $this->layanan_kpo_model->db->where_in("status",$filter_status);
        }
        if($no_surat_pengantar_es1){
            $this->layanan_kpo_model->db->where("no_surat_pengantar_es1",$no_surat_pengantar_es1);
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
                $row []  = $record->layanan_name;
                $status = get_status_layanan_kpo($record->status);
                if($status != ""){
                    $status = $status."<br>Nomor Surat : ".$record->no_surat_pengantar_es1;
                }

				$row []  = $status;
                $row []  = $record->NIP_BARU."<br>".$record->NAMA;
                $row []  = $record->nama_satker;
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
				
                $btn_actions  [] = "
                    <a  target='_blank' href='".base_url()."admin/kepegawaian/pegawai/profile/".$record->ID."'  data-toggle='tooltip' title='Profile Pegawai'><span class='fa-stack'>
					   	<i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-eye fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
                if($record->status>=7 and $record->status<=9){
                    $btn_actions  [] = "
                        <a class='btn-verifikasi-usulan' href='".base_url()."kpo/kpo-instansi/edit/".$record->usulan_id."'  data-toggle='tooltip' title='Verifikasi Usulan Data'><span class='fa-stack'>
                            <i class='fa fa-square fa-stack-2x'></i>
                            <i class='fa fa-pencil fa-stack-1x fa-inverse'></i>
                            </span>
                            </a>
                    ";
                }
				
				if($this->auth->has_permission($this->permissionViewDokumenPendukung)){
                $btn_actions  [] = "
					<a  class='show-modal-custom' href='".base_url()."kpo/kpo-instansi/form_dok_pendukung/".$record->usulan_id."'  data-toggle='tooltip' title='Lihat Dokumen Pendukung'><span class='fa-stack'>
							   <i class='fa fa-square fa-stack-2x'></i>
							   <i class='fa fa-upload fa-stack-1x fa-inverse'></i>
							   </span>
							   </a>
					";
				}
                $btn_actions  [] = "";
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
        Template::set('canUploadDataBKN',$this->auth->has_permission($this->permissionUploadDataBKN));
		Template::set('canTambahUsulan',$this->auth->has_permission($this->permissionTambahUsulan));
        Template::set('canFinalisasiUsulan',$this->auth->has_permission($this->permissionFinalisasiUsulan));
        Template::set('canCetak',$this->auth->has_permission($this->permissionCetak));
		Template::set('canTambahUsulan',$this->auth->has_permission($this->permissionTambahUsulan));
        Template::set('PNS_ID', $PNS_ID);
        Template::set('TAB_ID', uniqid("TAB_CONTOH"));
        Template::set_view('layanan_kpo/kpo_instansi/v_index');
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
                'name'=>'Memenuhi Syarat (MS INSTANSI) ',
                'value'=>'9'
            ),
            array(
                'name'=>'Tidak Memenuhi Syarat (TMS INSTANSI)',
                'value'=>'8'
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
	public function edit($ID)
    {
        $list_status = array(
            array(
                'name'=>'Memenuhi Syarat (MS)',
                'value'=>'9'
            ),
            array(
                'name'=>'Tidak Memenuhi Syarat (TMS)',
                'value'=>'8'
            ),
        );

        if($this->input->post('save')){
          $response = $this->save();
          if($response['success']){
                Template::set_message($response['msg'], 'success');
                redirect(base_url('kpo/kpo-instansi'));
          }
          else {
              if(isset($response['msg'])){
                Template::set_message($response['msg'], 'error');
              } 
          }
        }
        //$this->auth->restrict($this->permissionCreate); 
        $selectedData = $this->layanan_kpo_model->find($ID);
        Template::set('list_status',$list_status);
        Template::set('selectedData',$selectedData);
        //print_r($selectedData);
		Template::set_view('layanan_kpo/kpo_instansi/v_form_crud_usulan');
        Template::set('toolbar_title', "Form Usulan KPO");

        Template::render();
       
    }
	
    public function save_usulanold(){
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
         }           
         echo json_encode($response);
    }
    public function form_finalisasi_usulan(){

        $this->db->where('perkiraan_kpo.status','10');
        $this->layanan_kpo_model->db->group_start();
            $this->layanan_kpo_model->db->where("vw.ID",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_4",$this->satker_pegawai);
        $this->layanan_kpo_model->db->group_end();
        $num_generate_xls=$this->layanan_kpo_model->count_all();
        if($num_generate_xls>0){
            echo "<center><b>Data sudah di finalisasi</b></center>";    
            return;
        }
       
        Template::set_view('layanan_kpo/kpo_instansi/v_form_finalisasi_usulan');
        Template::set('toolbar_title', "Form Usulan KPO");

        Template::render();
    }
    function do_finalisasi_usulan(){
        $this->db->group_start();
            //$this->db->where('perkiraan_kpo.status','1');
            //$this->db->or_where('perkiraan_kpo.status','3');
            //$this->db->or_where('perkiraan_kpo.status','4');
            //$this->db->or_where('perkiraan_kpo.status','6');
            $this->db->where('perkiraan_kpo.status','7'); // dikirim ke pusat
            $this->db->or_where('perkiraan_kpo.status','9'); // MS pusat
        $this->db->group_end();
        
        $records = $this->layanan_kpo_model->find_all();
		
        if(is_array($records)){
			
            $no_surat_pengantar = $this->input->post('no_surat_pengantar');
            $update_data = array();
            foreach($records as $record){
                $update_data[] = array(
                    'status'=>'10',
                    'id'=>$record->usulan_id
                );
            }
            
            $this->db->update_batch('perkiraan_kpo',$update_data,'id');

            $output = array(
                'success'=>true
            );
            echo json_encode($output);
        }
		else {
			$output = array(
				'success'=>false,
				'message'=>'Tidak ada data dari Sekretariat Utama'
			);
			echo json_encode($output);
		}
    }
    public function cetak_xls(){
		$this->load->helper('dikbud');
		$template_data=$this->db->query("select * from mst_templates where id = ?",array(TEMPLATE_LIST_USULAN_KPO))->first_row();
		
		header('Set-Cookie: fileDownload=true; path=/');


		$filter_unit_kerja = $this->input->post('filter_unit_kerja');
        $filter_status = $this->input->post('filter_status[]');

		$this->load->library('Template_doc_lib');
		$TBS = $this->template_doc_lib->gen_xls(BASEPATH."../../assets/templates/".$template_data->template_file);
        $data = array();
		if($filter_unit_kerja){
            $this->layanan_kpo_model->db->group_start();
                $this->layanan_kpo_model->db->where("vw.ID",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where("vw.ESELON_1",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where("vw.ESELON_2",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where("vw.ESELON_3",$filter_unit_kerja);
                $this->layanan_kpo_model->db->or_where("vw.ESELON_4",$filter_unit_kerja);
            $this->layanan_kpo_model->db->group_end();
        }
        if(sizeof($filter_status)>0 && is_array($filter_status)){
            $this->layanan_kpo_model->db->where_in("status",$filter_status);
        }
		$this->layanan_kpo_model->db->order_by('perkiraan_kpo.unit_kerja_induk','asc');
		$this->layanan_kpo_model->db->order_by('perkiraan_kpo.nama','asc');
		
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
    public function form_dok_pendukung($ID){
        $selectedData = $this->layanan_kpo_model->find($ID);
        Template::set('selectedData',$selectedData);

        $documents = array();
        $this->db->where('perkiraan_id',$ID);
        $this->db->where('tipe','kpo');
        $documents = $this->usulan_dokumen_model->find_all();
        Template::set('documents',$documents);
        Template::set_view('layanan_kpo/kpo_instansi/v_form_dok_pendukung');
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
}
