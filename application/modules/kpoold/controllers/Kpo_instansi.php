<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Kpo_instansi extends Admin_Controller
{
    protected $permissionUpload = 'LayananKpo.UploadDataBKN';
    protected $permissionView   = 'LayananKpo.Cetak';
    
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
                'birth_date'=>$sheet->getCell('E'.$row)->getValue(),
                'last_edu'=>$sheet->getCell('F'.$row)->getValue(),
                'tahun_lulus'=>$sheet->getCell('G'.$row)->getValue(),
                'o_gol_ruang'=>$sheet->getCell('H'.$row)->getValue(),
                'o_gol_tmt'=>$sheet->getCell('I'.$row)->getValue(),
                'o_masakerja_thn'=>$sheet->getCell('J'.$row)->getValue(),
                'o_masakerja_bln'=>$sheet->getCell('K'.$row)->getValue(),
                'o_gapok'=>$sheet->getCell('L'.$row)->getValue(),
                'o_jabatan'=>$sheet->getCell('M'.$row)->getValue(),
                'o_tmt_jabatan'=>$sheet->getCell('N'.$row)->getValue(),
                'n_gol_ruang'=>$sheet->getCell('O'.$row)->getValue(),
                'n_gol_tmt'=>$sheet->getCell('P'.$row)->getValue(),
                'n_masakerja_thn'=>$sheet->getCell('Q'.$row)->getValue(),
                'n_masakerja_bln'=>$sheet->getCell('R'.$row)->getValue(),
                'n_gapok'=>$sheet->getCell('S'.$row)->getValue(),
                'n_jabatan'=>$sheet->getCell('T'.$row)->getValue(),
                'n_tmt_jabatan'=>$sheet->getCell('U'.$row)->getValue(),
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
    public function ajax_list(){
        
        $draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length');
		$start= $this->input->post('start');

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		
        
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
        
        $this->db->order_by('perkiraan_kpo.status','desc'); 
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
                
                $btn_actions = array();
                $btn_actions  [] = "
                    <a  target='_blank' href='".base_url()."admin/kepegawaian/pegawai/profile/".$record->ID."'  data-toggle='tooltip' title='Ubah Data'><span class='fa-stack'>
					   	<i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-eye fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
                if($record->status>=7 and $record->status<=9){
                    $btn_actions  [] = "
                        <a class='btn-verifikasi-usulan' href='".base_url()."kpo/kpo-instansi/edit/".$record->usulan_id."'  data-toggle='tooltip' title='Ubah Data'><span class='fa-stack'>
                            <i class='fa fa-square fa-stack-2x'></i>
                            <i class='fa fa-pencil fa-stack-1x fa-inverse'></i>
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
        Template::set('PNS_ID', $PNS_ID);
        Template::set('TAB_ID', uniqid("TAB_CONTOH"));
        Template::set_view('layanan_kpo/kpo_instansi/v_index');
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
        //$this->auth->restrict($this->permissionEdit); 
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
         }           
         echo json_encode($response);
    }
    public function form_finalisasi_usulan(){
        $this->db->group_start();
            $this->db->where('perkiraan_kpo.status','1');
            $this->db->or_where('perkiraan_kpo.status','3');
            $this->db->or_where('perkiraan_kpo.status','4');
            $this->db->or_where('perkiraan_kpo.status','6');
            $this->db->or_where('perkiraan_kpo.status','7');
        $this->db->group_end();
        $this->layanan_kpo_model->db->group_start();
            $this->layanan_kpo_model->db->where("vw.ID",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_4",$this->satker_pegawai);
        $this->layanan_kpo_model->db->group_end();
        $num_generate_xls=$this->layanan_kpo_model->count_all();
        if($num_generate_xls>0){
            echo "<center><b>Data tidak dapat di finalisasi, masih ada yang belum di verifikasi</b></center>";    
            return;
        }

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
        $this->db->where('perkiraan_kpo.status','9');
        $records = $this->layanan_kpo_model->find_all();
        if(sizeof($records)>0){
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
    }
    public function cetak_xls(){
		$this->load->helper('dikbud');
		$template_data=$this->db->query("select * from mst_templates where id = ?",array(TEMPLATE_LIST_USULAN_KPO))->first_row();
		
		header('Set-Cookie: fileDownload=true; path=/');


		
		$this->load->library('Template_doc_lib');
		$TBS = $this->template_doc_lib->gen_xls(BASEPATH."../../assets/templates/".$template_data->template_file);
        $data = array();
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
}
