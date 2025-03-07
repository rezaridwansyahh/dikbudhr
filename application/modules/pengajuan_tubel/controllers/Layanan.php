<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Layanan controller
 */
class Layanan extends Admin_Controller
{
    protected $permissionCreate = 'Pengajuan_tubel.Layanan.Create';
    protected $permissionDelete = 'Pengajuan_tubel.Layanan.Delete';
    protected $permissionEdit   = 'Pengajuan_tubel.Layanan.Edit';
    protected $permissionView   = 'Pengajuan_tubel.Layanan.View';
    protected $permissionVerifikasi   = 'Pengajuan_tubel.Layanan.Verifikasi';
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->auth->restrict($this->permissionView);
        $this->load->model('pengajuan_tubel/pengajuan_tubel_model');
        $this->load->model('pegawai/tingkatpendidikan_model');
        $this->lang->load('pengajuan_tubel');
        
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'layanan/_sub_nav');

        Assets::add_module_js('pengajuan_tubel', 'pengajuan_tubel.js');
        $this->load->helper('dikbud');

        $tingkatpendidikans = $this->tingkatpendidikan_model->find_all();
        Template::set('tk_pendidikans', $tingkatpendidikans);
    }

    /**
     * Display a list of pengajuan tubel data.
     *
     * @return void
     */
    public function index()
    {

        Template::set('toolbar_title', lang('pengajuan_tubel_manage'));

        Template::render();
    }
    public function viewadm()
    {
        Template::set('toolbar_title', lang('pengajuan_tubel_manage'));
        Template::render();
    }
    
    /**
     * Create a pengajuan tubel object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        

        if (isset($_POST['save'])) {
            if ($insert_id = $this->save_pengajuan_tubel()) {
                log_activity($this->auth->user_id(), lang('pengajuan_tubel_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'pengajuan_tubel');
                Template::set_message(lang('pengajuan_tubel_create_success'), 'success');

                redirect(SITE_AREA . '/layanan/pengajuan_tubel');
            }

            // Not validation error
            if ( ! empty($this->pengajuan_tubel_model->error)) {
                Template::set_message(lang('pengajuan_tubel_create_failure') . $this->pengajuan_tubel_model->error, 'error');
            }
        }

        Template::set('toolbar_title', "Ajukan Tugas Belajar");

        Template::render();
    }
    /**
     * Allows editing of pengajuan tubel data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('pengajuan_tubel_invalid_id'), 'error');

            redirect(SITE_AREA . '/layanan/pengajuan_tubel');
        }
        
        
        
        Template::set('pengajuan_tubel', $this->pengajuan_tubel_model->find($id));

        Template::set('toolbar_title', lang('pengajuan_tubel_edit_heading'));
        Template::render();
    }
    public function verifikasi()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('pengajuan_tubel_invalid_id'), 'error');

            redirect(SITE_AREA . '/layanan/pengajuan_tubel/viewadm');
        }
        Template::set('pengajuan_tubel', $this->pengajuan_tubel_model->find($id));
        Template::set('toolbar_title', "Verifikasi Tubel");
        Template::render();
    }

    //--------------------------------------------------------------------------
    // !PRIVATE METHODS
    //--------------------------------------------------------------------------

    /**
     * Save the data.
     *
     * @param string $type Either 'insert' or 'update'.
     * @param int    $id   The ID of the record to update, ignored on inserts.
     *
     * @return boolean|integer An ID for successful inserts, true for successful
     * updates, else false.
     */
    public function getdata(){
        $this->auth->restrict($this->permissionView);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/layanan/pengajuan_tubel');
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

        $selectedUnors = array();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            if($filters['KETERANGAN'] != ""){
                $this->pengajuan_tubel_model->like('upper("NOMOR_USUL")',strtoupper($filters['NOMOR_USUL']),"BOTH"); 
            }
        }
        $output=array();
        $output['draw']=$draw;
        $this->pengajuan_tubel_model->where('NIP',trim($this->auth->get_nip())); 
        $total= $this->pengajuan_tubel_model->count_all();
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        $this->db->start_cache();
        
        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            if($filters['NOMOR_USUL'] != ""){
                $this->pengajuan_tubel_model->like('upper("NOMOR_USUL")',strtoupper($filters['NOMOR_USUL']),"BOTH");    
            }
            
            
        }
        $this->db->stop_cache(); 
        $this->pengajuan_tubel_model->limit($length,$start);
        $this->pengajuan_tubel_model->where('NIP',trim($this->auth->get_nip())); 
        $records=$this->pengajuan_tubel_model->find_all();
        $this->db->flush_cache();
        $nomor_urut=$start+1;

        $abeasiswa_status = getlistbeasiswa();
        $astatuspengajuan_tb = getlistdatastatustb();

        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $status = "<span class='label label-warning'>Pengajuan</span>";
                $statusjumlah = "0";
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = "#".$record->NOMOR_USUL;
                $row []  = $record->UNIVERSITAS;
                //$row []  = isset($abeasiswa_status[$record->BEASISWA]) ? $abeasiswa_status[$record->BEASISWA] : "";
                $row []  = $record->MULAI_BELAJAR." - ".$record->AKHIR_BELAJAR;
                $row []  = $record->NAMA;
                 
                
                if($record->STATUS == "2"){
                    $status = "<span class='label label-success'>Diterima</span>";
                }
                if($record->STATUS == "3"){
                    $status = "<span class='label label-danger'>Ditolak</span>";
                }
                $row []  = $status; 

                $btn_actions = array();
                if($this->auth->has_permission("Pengajuan_tubel.Layanan.Edit")){
                    $btn_actions  [] = "<a href='".base_url()."admin/layanan/pengajuan_tubel/edit/".$record->ID."' data-toggle='tooltip' title='Edit Pengajuan' tooltip='Edit Pengajuan' class='btn btn-sm btn-warning btn-batalkan show-modal'><i class='glyphicon glyphicon-edit'></i> </a>";  
                }
                if($this->auth->has_permission("Pengajuan_tubel.Layanan.Delete")){
                    $btn_actions  [] = "<a href='#' kode='".$record->ID."' data-toggle='tooltip' title='Hapus' class='btn btn-sm btn-danger btn-hapus'><i class='glyphicon glyphicon-trash'></i> </a>";
                }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    public function getdataadmin(){
        $this->auth->restrict($this->permissionView);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/layanan/pengajuan_tubel');
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

        $selectedUnors = array();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            if($filters['NOMOR_USUL'] != ""){
                $this->pengajuan_tubel_model->like('upper("NOMOR_USUL")',strtoupper($filters['NOMOR_USUL']),"BOTH"); 
            }
            if(isset($filters['unit_id_key']) && $filters['unit_id_key'] != ""){
                    $this->db->group_start();
                    $this->db->where('vw."ID"',$filters['unit_id_key']);    
                    $this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);   
                    $this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);   
                    $this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);   
                    $this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);   
                    $this->db->group_end();
            }
            if($filters['NIP'] != ""){
                $this->db->group_start();
                $this->pengajuan_tubel_model->where('"NIP"',$filters['NIP']); 
                $this->pengajuan_tubel_model->or_like('upper("NAMA")',strtoupper($filters['NIP']),"BOTH"); 
                $this->db->group_end();
            }
        }
        $output=array();
        $output['draw']=$draw;
        $total= $this->pengajuan_tubel_model->count_all_admin();
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        $this->db->start_cache();
        
        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            if($filters['NOMOR_USUL'] != ""){
                $this->pengajuan_tubel_model->like('upper("NOMOR_USUL")',strtoupper($filters['NOMOR_USUL']),"BOTH"); 
            }
            if(isset($filters['unit_id_key']) && $filters['unit_id_key'] != ""){
                    $this->db->group_start();
                    $this->db->where('vw."ID"',$filters['unit_id_key']);    
                    $this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);   
                    $this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);   
                    $this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);   
                    $this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);   
                    $this->db->group_end();
            }
            if($filters['NIP'] != ""){
                $this->db->group_start();
                $this->pengajuan_tubel_model->where('upper("NIP")',strtoupper($filters['NIP'])); 
                $this->pengajuan_tubel_model->or_like('upper("NAMA")',strtoupper($filters['NIP']),"BOTH"); 
                $this->db->group_end();
            }
        }
        $this->db->stop_cache(); 
        $this->pengajuan_tubel_model->limit($length,$start);
        $records=$this->pengajuan_tubel_model->find_all_admin();
        $this->db->flush_cache();
        $nomor_urut=$start+1;

        $abeasiswa_status = getlistbeasiswa();
        $astatuspengajuan_tb = getlistdatastatustb();
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $status = "<span class='label label-warning'>Pengajuan</span>";
                $statusjumlah = "0";
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = "#".$record->NOMOR_USUL;
                $row []  = "<b>".$record->NAMA."</b><br>
                            <b>".$record->NIP."</b>
                            <br>".$record->NAMA_UNOR_FULL;
                $row []  = $record->UNIVERSITAS;

                //$row []  = isset($abeasiswa_status[$record->BEASISWA]) ? $abeasiswa_status[$record->BEASISWA] : "";
                $row []  = $record->MULAI_BELAJAR." - ".$record->AKHIR_BELAJAR;
                $row []  = $record->JENJANG;
                if($record->STATUS == "2"){
                    $status = "<span class='label label-success'>Diterima</span>";
                }
                if($record->STATUS == "3"){
                    $status = "<span class='label label-danger'>Ditolak</span>";
                }
                $row []  = $status; 
                $btn_actions = array();
                if($this->auth->has_permission('Pengajuan_tubel.Layanan.Verifikasi')){
                    $btn_actions  [] = "<a href='".base_url()."admin/layanan/pengajuan_tubel/verifikasi/".$record->ID."' data-toggle='tooltip' title='Verifikasi Pengajuan' class='btn btn-sm btn-warning btn-batalkan show-modal' tooltip='Verifikasi Pengajuan'><i class='glyphicon glyphicon-edit'></i> </a>";  
                }
                if($this->auth->has_permission("Pengajuan_tubel.Layanan.Delete")){
                    $btn_actions  [] = "<a href='#' kode='".$record->ID."' data-toggle='tooltip' title='Hapus' class='btn btn-sm btn-danger btn-hapus'><i class='glyphicon glyphicon-trash'></i> </a>";
                }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    public function deletedata()
    {
        $this->auth->restrict($this->permissionDelete);
        $this->load->model('pengajuan_tubel/pengajuan_tubel_model');
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/layanan/pengajuan_tubel');
        }
        $id     = $this->input->post('kode');
        $data = $this->pengajuan_tubel_model->find($id);
        if($data->STATUS != "1"){
            echo "Data tidak bisa dihapus karena sudah melewati verifikasi";
            die();
        }
        if ($this->pengajuan_tubel_model->delete($id)) {
             
             log_activity($this->auth->user_id(),"Delete data" . ': ' . $id . ' : ' . $this->input->ip_address(), 'pengajuan_tubel');
             Template::set_message("Delete data sukses", 'success');
             echo "Sukses";
        }else{
            echo "Gagal";
        }

        exit();
    }
     
    public function save_pengajuan_tubel()
    {

        //$this->form_validation->set_rules($this->pengajuan_tubel_model->get_validation_rules());
        $this->form_validation->set_rules('UNIVERSITAS','UNIVERSITAS','required|max_length[200]');
        if ($this->form_validation->run() === FALSE)
        {

            $response['msg'] = "
            <div class='alert alert-danger alert-dismissable alert-block alert-error fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                    Error :
                </h4>
                ".validation_errors()."
            </div>
            ";
            $response['shortmsg'] = "
            <div class='alert alert-danger alert-dismissable alert-block alert-error fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                Terdapat kesalahan pada formulir, silahkan cek kembali kelengkapan data.
            </div>
            ";
            echo json_encode($response);
            exit();
         }
         if(trim($this->auth->get_nip()) == ""){
            $response['msg'] = "
            <div class='alert alert-danger alert-dismissable alert-block alert-error fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                    Error :
                </h4>
                Pegawai tidak ketahui, Silahkan hubungi administrator
            </div>
            ";
            $response['shortmsg'] = "
            <div class='alert alert-danger alert-dismissable alert-block alert-error fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                Terdapat kesalahan pada formulir, silahkan cek kembali kelengkapan data.
            </div>
            ";
            echo json_encode($response);
            exit();
         }
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        $data = $this->pengajuan_tubel_model->prep_data($this->input->post());
        $data['NIP']        = trim($this->auth->get_nip());
        $data['NOMOR_USUL'] = "TB".time();
        $data['TANGGAL_USUL'] = date("Y-m-d");
        $data['BEASISWA']   = (int)$this->input->post('BEASISWA');
        $data['STATUS']     = $this->input->post('STATUS') ? $this->input->post('STATUS') : 1;
        $id_data = $this->input->post("ID");
        if(empty($data["MULAI_BELAJAR"])){
            unset($data["MULAI_BELAJAR"]);
        }
        if(empty($data["AKHIR_BELAJAR"])){
            unset($data["AKHIR_BELAJAR"]);
        }
        //die($id_data." id data");
        if(isset($id_data) && !empty($id_data)){
            $this->pengajuan_tubel_model->update($id_data,$data);
        }
        else {
            $this->pengajuan_tubel_model->insert($data);
            // add id =
        }
        $response ['success']= true;
        $response ['msg']= "berhasil";
        echo json_encode($response);    
    }
    public function save_verifikasi()
    {
        $this->load->model('pegawai/riwayat_tugasbelajar_model');
        $this->form_validation->set_rules($this->pengajuan_tubel_model->get_validation_rules());
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        $data = $this->pengajuan_tubel_model->prep_data($this->input->post());
        $data['STATUS']     = $this->input->post('STATUS') ? $this->input->post('STATUS') : 0;
        $id_data = $this->input->post("ID");
        //die($id_data." id data");
        if(isset($id_data) && !empty($id_data)){
            $this->pengajuan_tubel_model->update($id_data,$data);
            if($this->input->post('STATUS') == "2"){
                $datapengajuan = $this->pengajuan_tubel_model->find($id_data);
                // simpan ke data riwayat tugas belajar
                $data = array();
                $pegawai_data = $this->pegawai_model->find_by("NIP_BARU",$datapengajuan->NIP);  
                $data["NAMA"]               = $pegawai_data->NAMA;
                $data["NIP"]                = $datapengajuan->NIP;
                $data["TINGKAT_PENDIDIKAN"] = $datapengajuan->JENJANG;
                $data["PROGRAM_STUDI"]      = $datapengajuan->PRODI;
                $data["FAKULTAS"]           = $datapengajuan->FAKULTAS;
                $data["UNIVERSITAS"]        = $datapengajuan->UNIVERSITAS;
                $data["ID_PENGAJUAN"]       = $datapengajuan->NOMOR_USUL;

                $data["MULAI_BELAJAR"]      = $datapengajuan->MULAI_BELAJAR;
                $data["AKHIR_BELAJAR"]      = $datapengajuan->AKHIR_BELAJAR;
                if(empty($data["TANGGAL_SK"])){
                    unset($data["TANGGAL_SK"]);
                }
                if(empty($data["MULAI_BELAJAR"])){
                    unset($data["MULAI_BELAJAR"]);
                }
                if(empty($data["AKHIR_BELAJAR"])){
                    unset($data["AKHIR_BELAJAR"]);
                }
                
                $recriwayat_tugasbelajar = $this->riwayat_tugasbelajar_model->find_by("ID_PENGAJUAN",$datapengajuan->NOMOR_USUL);  
                $idriwayat = $recriwayat_tugasbelajar->ID;
                if(isset($idriwayat) && !empty($idriwayat)){
                    $this->riwayat_tugasbelajar_model->update($idriwayat,$data);
                }
                else $this->riwayat_tugasbelajar_model->insert($data);
            }
        }
        else {
            $this->pengajuan_tubel_model->insert($data);
            // add id =
        }
        $response ['success']= true;
        $response ['msg']= "berhasil";
        echo json_encode($response);    
    }
    public function downloadall1($id){
        $pengajuan_tubel = $this->pengajuan_tubel_model->find_all_admin();
        $this->load->library('LibOpenTbs');
        $template_name = APPPATH."..".DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'MASTER_UNDANGAN.docx';
        //$template_name = '/Applications/XAMPP/xamppfiles/htdocs/dikbudhrd/assets/templates'.DIRECTORY_SEPARATOR.'MASTER_UNDANGAN.docx';
        $TBS = $this->libopentbs->TBS;
        $TBS->LoadTemplate($template_name, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
        //die($foto_pegawai);
        $hari = date('l', strtotime($tanggal_pelantikan));
        $jabatan = isset($pegawai->JABATAN) ? $pegawai->JABATAN : "";
        $TBS->MergeField('a', array(
            'fullnama'=>$pegawai->GELAR_DEPAN." ".$pegawai->NAMA." ".$pegawai->GELAR_BELAKANG,
            'hari'=>isset($this->ahari[$hari]) ? $this->ahari[$hari] : "",
            'jabatan'=>$jabatan,
            'tgl_pelantikan'=>$tanggal_pelantikan,
        )); 

        $output_file_name = 'undangan.docx';
        $output_file_name = str_replace('.', '_'.date('Y-m-d').$pegawai->NIP_BARU.'.', $output_file_name);
        $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.
    }
    public function downloadall()
    {

        $datapegwai=$this->pengajuan_tubel_model->find_all_admin();
        $this->load->library('Excel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel = PHPExcel_IOFactory::load(trim($this->settings_lib->item('site.pathuploaded')).'template.xls');

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,"Baperjakat Periode : ".$keterangan);

        $objPHPExcel->setActiveSheetIndex(0);
        $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,3,"No");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,3,"Nama");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,3,"Unitkerja");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,3,"Universitas");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,3,"Waktu");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,3,"Jenjang");$col++;
        
        
        $row = 4;
        if (isset($datapegwai) && is_array($datapegwai) && count($datapegwai)) :
            $nomor_urut = 1;
            foreach ($datapegwai as $record) :
                $col = 0;
                $type = PHPExcel_Cell_DataType::TYPE_STRING;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$row,$nomor_urut,PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,$record->NIP." ",PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,$record->NAMA." ",PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row,$record->NAMA_PANGKAT."/".$record->NAMA_GOLONGAN." ",PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row,$this->kategori[$record->KATEGORI]." ",PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$row,$record->NAMA_UNOR_FULL." ",PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$row,$record->NAMA_JABATAN." ",PHPExcel_Cell_DataType::TYPE_STRING);

                //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$row,$photo,PHPExcel_Cell_DataType::TYPE_STRING);    
                //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$row,$photo." ",PHPExcel_Cell_DataType::TYPE_STRING);
            $row++;
            $nomor_urut ++;
            endforeach;
        endif;
          
        $filename = "penetapan_".$periode."_".mt_rand(1,100000).'.xls'; //just some random filename
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
        //$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
        $objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
        exit; //done.. exiting!
        
    }
}