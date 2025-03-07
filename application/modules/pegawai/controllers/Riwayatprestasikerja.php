<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Riwayatprestasikerja extends Admin_Controller
{
    protected $permissionCreate = 'RiwayatPrestasiKerja.Kepegawaian.Create';
    protected $permissionDelete = 'RiwayatPrestasiKerja.Kepegawaian.Delete';
    protected $permissionEdit   = 'RiwayatPrestasiKerja.Kepegawaian.Edit';
    protected $permissionView   = 'RiwayatPrestasiKerja.Kepegawaian.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/riwayat_prestasi_kerja_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/jenis_jabatan_model');
    }
    public function get_atasan_langsung_ajax(){
        $json = array();
        $limit = 10;
        $page = $this->input->get('page') ? $this->input->get('page') : "1";
        $q= $this->input->get('term');
        $start = ($page-1)*$limit;
        
        if(!empty($q)){
            $this->db->start_cache();
            $this->db->like('lower("NAMA")', strtolower($q));
            $this->db->or_like('lower("NIP_BARU")', strtolower($q));
            $this->db->from("hris.pegawai");
            $this->db->stop_cache();
            $total = $this->db->get()->num_rows();
            $this->db->select('PNS_ID as id,NAMA as text,NAMA,NIP_BARU,JABATAN_NAMA');
            $this->db->limit($limit,$start);

            $data = $this->db->get()->result();

            $endCount = $start + $limit;
            $morePages = $endCount > $total;
            $o = array(
            "results" => $data,
                "pagination" => array(
                    "more" =>$morePages,
                    "totalx"=>$total,
                    "startx"=>$start,
                    "limitx"=>$limit
                )
            );   
            $this->db->flush_cache();
            $json = $o;
        }
            echo json_encode($json);
    }
    
    public function ajax_list(){
        
        $draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
        $PNS_ID = $this->input->post('PNS_ID');
        if(empty($PNS_ID)){
            ECHO "die";
            die();
        }
        $this->pegawai_model->where("PNS_ID",$PNS_ID);
        $pegawai_data = $this->pegawai_model->find_first_row();
       
		$length= $this->input->post('length');
		$start= $this->input->post('start');

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$this->riwayat_prestasi_kerja_model->where("PNS_ID",$pegawai_data->PNS_ID);
		$total= $this->riwayat_prestasi_kerja_model->count_all();;
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
        
		
        
		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if($search!=""){
			$this->riwayat_prestasi_kerja_model->where('upper("NAMA_UNOR_BARU") LIKE \''.strtoupper($search).'%\'');
		}
		$this->riwayat_prestasi_kerja_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
        
		$kolom = $iSortCol != "" ? $iSortCol : "NAMA";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->riwayat_prestasi_kerja_model->order_by($iSortCol,$sSortCol);
        $this->riwayat_prestasi_kerja_model->order_by("TAHUN","ASC");
        $this->riwayat_prestasi_kerja_model->where("PNS_ID",$pegawai_data->PNS_ID);  
		
        $records=$this->riwayat_prestasi_kerja_model->find_all();
            
		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($search != "")
		{
			$jum	= $this->riwayat_prestasi_kerja_model->count_all();
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}
		
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->TAHUN;
                $row []  = round($record->NILAI_PPK,2);
                $row []  = round($record->NILAI_SKP,2);
                $row []  = round($record->NILAI_PERILAKU,2);
                $row []  = $record->JABATAN_NAMA;

                $btn_actions = array();
                if($this->auth->has_permission($this->permissionEdit))
                {    
                    $btn_actions  [] = "<a href='".base_url()."pegawai/riwayatprestasikerja/edit/".$PNS_ID."/".$record->ID."' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success show-modal-custom'><i class='fa fa-pencil'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionDelete))
                {    
                    $btn_actions  [] = "<a href='#' kode='$record->ID' data-toggle='tooltip' title='Hapus Data' class='btn btn-sm btn-danger btn-hapus'><i class='fa fa-trash-o'></i> </a>";
                }

                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";

                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		die();
    }
    public function show($PNS_ID,$record_id=''){
        $jenis_jabatans = $this->jenis_jabatan_model->find_all();
        Template::set('jenis_jabatans',$jenis_jabatans );  
        Template::set('NILAI_PROSENTASE_SKP',60);
        Template::set('NILAI_PROSENTASE_PERILAKU',40);
        if(empty($record_id)){
            $this->auth->restrict($this->permissionCreate);
            Template::set_view("kepegawaian/riwayat_prestasi_kerja_crud");
            
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Riwayat Pindah Unit Kerja");

            Template::render();
        }
        else {
            $this->auth->restrict($this->permissionEdit);
           
            Template::set_view("kepegawaian/riwayat_prestasi_kerja_crud");
            $detailRiwayat = $this->riwayat_prestasi_kerja_model->find($record_id);
            $this->pegawai_model->where("PNS_ID",$detailRiwayat->ATASAN_LANGSUNG_PNS_ID);
            $selectedAtasanLangsung = $this->pegawai_model->find_first_row();
            Template::set('selectedAtasanLangsung',$selectedAtasanLangsung );
            
            $this->pegawai_model->where("PNS_ID",$detailRiwayat->ATASAN_ATASAN_LANGSUNG_PNS_ID);
            $selectedAtasanAtasanLangsung = $this->pegawai_model->find_first_row();
            Template::set('selectedAtasanAtasanLangsung',$selectedAtasanAtasanLangsung );
            
            

            Template::set('detail_riwayat',$detailRiwayat );    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Riwayat Pindah Unit Kerja");

            Template::render();
        }
    }
    public function add($PNS_ID){
        $this->show($PNS_ID);
    }
    public function edit($PNS_ID,$record_id=''){
        $this->show($PNS_ID,$record_id);
    }
    function maximumCheck($num)
    {
        if ($num > 100)
        {
            $this->form_validation->set_message(
                            'your_number_field',
                            'The %s field must be less than 24'
                        );
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    public function save(){
         // Validate the data
        $this->form_validation->set_rules($this->riwayat_prestasi_kerja_model->get_validation_rules());
        $this->form_validation->set_rules('TAHUN','TAHUN','required|max_length[4]');
        $this->form_validation->set_rules('PERATURAN','PERATURAN','required|max_length[18]');
        $this->form_validation->set_rules('PERILAKU_INISIATIF_KERJA','INISIATIF KERJA','required|max_length[18]');
        if($this->input->post("PERATURAN") == "PP46"){
            $this->form_validation->set_rules('NILAI_SKP','NILAI SKP','numeric|required|less_than_equal_to[100]');
        }
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = "
            <div class='alert alert-block alert-error fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                    Error
                </h4>
                ".validation_errors()."
            </div>
            ";
            echo json_encode($response);
            exit();
        }

        $data = $this->riwayat_prestasi_kerja_model->prep_data($this->input->post());
       
        $this->pegawai_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $pegawai_data = $this->pegawai_model->find_first_row();  
        $data["PNS_ID"] = $pegawai_data->PNS_ID;
        $data["PNS_NAMA"] = $pegawai_data->NAMA;
        $data["PNS_NIP"] = $pegawai_data->NIP_BARU;
        
        
        $jenis_jabatan_data = $this->jenis_jabatan_model->find($this->input->post("JABATAN_TIPE"));
        $data["JABATAN_TIPE_TEXT"] = $jenis_jabatan_data->NAMA;

       
        if(empty($data["SK_TANGGAL"])){
            unset($data["SK_TANGGAL"]);
        }

        $data['PERATURAN'] = $this->input->post("PERATURAN");
        $data['NILAI_PROSENTASE_SKP'] = $this->input->post("NILAI_PROSENTASE_SKP");
        $data['NILAI_SKP_AKHIR'] = $data['NILAI_PROSENTASE_SKP']/100*$data['NILAI_SKP'];

        $data['NILAI_PROSENTASE_PERILAKU'] = $this->input->post("NILAI_PROSENTASE_PERILAKU");//40;
        $data['NILAI_PERILAKU'] = $this->input->post("NILAI_PERILAKU");//40;
        // $data['NILAI_PERILAKU'] = $data['PERILAKU_KOMITMEN'] 
        //                           +$data['PERILAKU_INTEGRITAS'] 
        //                           +$data['PERILAKU_DISIPLIN'] 
        //                           +$data['PERILAKU_KERJASAMA'] 
        //                           +$data['PERILAKU_ORIENTASI_PELAYANAN'] ;
        // if($data['JABATAN_TIPE']==1){
        //      $data['NILAI_PERILAKU'] += $data['PERILAKU_KEPEMIMPINAN'] ;
        //       $data['NILAI_PERILAKU'] = $data['NILAI_PERILAKU']/6;
        // }
        // else {
        //     $data['NILAI_PERILAKU'] = $data['NILAI_PERILAKU']/5;
        // }
        // $data['NILAI_PERILAKU_AKHIR'] = $data['NILAI_PROSENTASE_PERILAKU']/100*$data['NILAI_PERILAKU'];
        $data['NILAI_PERILAKU_AKHIR'] = $this->input->post("NILAI_PERILAKU_AKHIR");
        $data['PERILAKU_INISIATIF_KERJA'] = $this->input->post("PERILAKU_INISIATIF_KERJA");
        
        $data['NILAI_PPK'] = round($data['NILAI_SKP_AKHIR']  + $data['NILAI_PERILAKU_AKHIR'],2);
        $id_data = $this->input->post("ID");
        if(isset($id_data) && !empty($id_data)){
            $data['updated_date'] = date("Y-m-d");
            $this->riwayat_prestasi_kerja_model->update($id_data,$data);
        }
        else{
            $data['created_date'] = date("Y-m-d");
            $this->riwayat_prestasi_kerja_model->insert($data);
        }
        $response ['success']= true;
        $response ['msg']= "Transaksi berhasil";
        echo json_encode($response);    

    }
    public function delete($record_id){
        $this->auth->restrict($this->permissionDelete);
		if ($this->riwayat_prestasi_kerja_model->delete($record_id)) {
			 log_activity($this->auth->user_id(), 'delete data Riwayat Diklat Kepangkatan : ' . $record_id . ' : ' . $this->input->ip_address(), 'pegawai');
			 Template::set_message("Sukses Hapus data", 'success');
			 echo "Sukses";
		}else{
			echo "Gagal";
		}

		exit();
    }
    public function index($PNS_ID='1552260645'){
        Template::set_view("kepegawaian/tab_pane_riwayat_prestasi_kerja");
        Template::set('PNS_ID', $PNS_ID);
        Template::set('TAB_ID', uniqid("TAB_CONTOH"));
        Template::render();
    }
}
