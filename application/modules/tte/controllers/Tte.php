<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Masters controller
 */
class Tte extends Admin_Controller
{
    protected $permissionCreate = 'Tte.Tte.Create';
    protected $permissionDelete = 'Tte.Tte.Delete';
    protected $permissionEdit   = 'Tte.Tte.Edit';
    protected $permissionView   = 'Tte.Tte.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->auth->restrict($this->permissionView);
        $this->load->model('tte/tte_model');
        $this->load->model('tte/variable_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('tte/proses_variable_model');
        $this->load->model('tte/tte_master_korektor_model');
        $this->load->model('tte/tte_trx_draft_sk_model');
        $this->load->model('tte/tte_trx_draft_sk_detil_model');
        $this->load->model('tte/Tte_trx_korektor_draft_model');
        $this->load->model('sk_ds/kategori_ds_model');
        $this->load->model('sk_ds/sk_ds_model');
        $this->load->model('sk_ds/sk_ds_korektor_model');
        $this->lang->load('tte');
        

        $reckategori_ds = $this->tte_model->find_all();
        Template::set("reckategori_ds",$reckategori_ds);
        
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
    }

    /**
     * Display a list of tte data.
     *
     * @return void
     */
    public function index()
    {
        
        Template::set('toolbar_title', "Daftar Draft SK");
        Template::set_view("tte/tte/index");
        Template::render();
    }
     
    
    /**
     * Create a tte object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        $id = $this->uri->segment(5);
        $tte = $this->tte_model->find($id);        
        $judul_ttd = $tte->nama_proses;
        $penandatangan_sk = $tte->penandatangan_sk;
        $apenandatangan_sk = $this->pegawai_model->find_by("PNS_ID",$penandatangan_sk);
        Template::set('apenandatangan_sk', $apenandatangan_sk);

        $tanggal = array("tanggal_dokumen"=>"","tmt_dokumen"=>"");

        if($id==7 || $id==6){
            $tanggal['tanggal_dokumen'] = "2022-02-15";
            $tanggal['tmt_dokumen'] = "2022-03-01";
        }

        Template::set('tanggal', $tanggal);
        Template::set('auth_id',$this->auth->user_id());

        $this->proses_variable_model->where("id_proses",$id);
        $proses_variable = $this->proses_variable_model->find_detil();
        Template::set('proses_variable', $proses_variable);

        $this->tte_master_korektor_model->where("id_tte_master_proses",$id);
        $verifikators = $this->tte_master_korektor_model->find_all();
        Template::set('verifikators', $verifikators);
        Template::set('tte', $tte);
        Template::set('judul_ttd', $judul_ttd);
        Template::set('id_master_proses', $id);
        Template::set('toolbar_title', "Buat Dokumen ".$judul_ttd);
        Template::set_view("tte/tte/create");
        Template::render();
    }
    /**
     * Allows editing of tte data.
     *
     * @return void
     */
    public function edit()
    {
        $id_table = $this->uri->segment(5);
        
        $draftsk = $this->tte_trx_draft_sk_model->find($id_table);
        if($draftsk->base64pdf_hasil == ""){
            $this->crete_word($id_table);
        }

        $id = $draftsk->id_master_proses;
        $penandatangan_sk = $draftsk->penandatangan_sk;
        $nip_sk = $draftsk->nip_sk;
        if (empty($id_table)) {
            Template::set_message(lang('tte_invalid_id'), 'error');
            redirect(SITE_AREA . '/tte/tte');
        }
        $tte = $this->tte_model->find($id);        
        $judul_ttd = $tte->nama_proses;
        $apenandatangan_sk = $this->pegawai_model->find_by("PNS_ID",$penandatangan_sk);
        Template::set('apenandatangan_sk', $apenandatangan_sk);

        $apegawai = $this->pegawai_model->find_by("NIP_BARU",$nip_sk);
        Template::set('pegawai', $apegawai);

        $this->proses_variable_model->where("id_proses",$id);
        $proses_variable = $this->proses_variable_model->find_detil();
        Template::set('proses_variable', $proses_variable);

        $this->Tte_trx_korektor_draft_model->where("id_tte_trx_draft_sk",$id_table);
        $verifikators = $this->Tte_trx_korektor_draft_model->find_detil();
        Template::set('verifikators', $verifikators);

        $this->tte_trx_draft_sk_detil_model->where("id_tte_trx_draft_sk",$id_table);
        $aisi_variable = $this->tte_trx_draft_sk_detil_model->find_all();
        $isivar = array();
        if (isset($aisi_variable) && is_array($aisi_variable) && count($aisi_variable)):
            foreach($aisi_variable as $isi_variable):
                $isivar[$isi_variable->id_variable] = $isi_variable->isi;
            endforeach;
        endif;
        Template::set('aisivar', $isivar);
        Template::set('judul_ttd', $judul_ttd);
        Template::set('id_master_proses', $id);
        Template::set('toolbar_title', "Buat Dokumen ".$judul_ttd);

        
        Template::set('draftsk', $draftsk);
        Template::set('id_table', $id_table);
        Template::set('toolbar_title', "Edit SK ");
        Template::set_view("tte/tte/edit");
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
    private function save_tte($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['id'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->tte_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->tte_model->prep_data($this->input->post());

        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->tte_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->tte_model->update($id, $data);
        }

        return $return;
    }
    public function ajax_data(){
        $this->auth->restrict($this->permissionView);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai');
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";
        $NIP = trim($this->auth->username());
        $this->pegawai_model->select("PNS_ID,NIK,NIP_BARU");
        $pegawai_login = $this->pegawai_model->find_by("NIP_BARU",trim($NIP));
        $PNS_ID = isset($pegawai_login->PNS_ID) ? $pegawai_login->PNS_ID : "";
        $selectedUnors = array();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            
        }

        $this->db->start_cache();
        
        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
             
            if($filters['nama_cb']){
                $this->tte_trx_draft_sk_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");    
            }
            if($filters['nip_cb']){
                $this->tte_trx_draft_sk_model->like('upper(pegawai."NIP_BARU")',strtoupper($filters['nip_key']),"BOTH"); 
            }
             
            if($filters['id_pegawai_ttd']){
                $this->tte_trx_draft_sk_model->where('tte_trx_draft_sk."penandatangan_sk"',$filters['id_pegawai_ttd']); 
            }
            if($filters['kategori_cb']){
                $this->tte_trx_draft_sk_model->where('id_master_proses',$filters['kategori_sk']); 
            }
            if($filters['no_dok']){
                $this->tte_trx_draft_sk_model->like('upper(tte_trx_draft_sk."nomor_sk")',strtoupper($filters['nomor_sk']),"BOTH"); 
             //   $this->tte_trx_draft_sk_model->where('tte_trx_draft_sk.nomor_sk',$filters['nomor_sk']); 
            }
        }
        
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total= $this->tte_trx_draft_sk_model->count_detil();
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->tte_trx_draft_sk_model->order_by("nama_proses",$order['dir']);
            }
            if($order['column']==2){
                $this->tte_trx_draft_sk_model->order_by("pegawai.NAMA",$order['dir']);
            }
            if($order['column']==3){
                $this->tte_trx_draft_sk_model->order_by("nama_penandatangan_sk",$order['dir']);
            }
            if($order['column']==4){
                $this->tte_trx_draft_sk_model->order_by("nomor_sk",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->tte_trx_draft_sk_model->limit($length,$start);
        $records=$this->tte_trx_draft_sk_model->find_detil();
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $class_delete = "";
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->nama_proses;
                if($record->NAMA!= "")
                    $row []  = $record->NAMA."<br>".$record->NIP_BARU;
                else{
                    $row []  = $record->nama_pemilik_sk."<br>".$record->nip_sk;
                }
                $row []  = $record->nama_penandatangan_sk;
                $row []  = "<b>".$record->nomor_sk."</b><br>".$record->tgl_sk."<br>".$record->id_file;
                if($record->id_file_sk != ""){
                    $class_signed = "warning";
                    $msg_ttd = "Blm Ttd";
                    if($record->is_signed == "1"){
                        $class_signed = "success";
                        $msg_ttd = "Sudah Tandatangan";
                    }
                    $row []  = "<a href='#' data-toggle='tooltip' title='Sudah ada di daftar DS dan ".$msg_ttd."' class='btn btn-sm btn-".$class_signed."'><i class='fa fa-check-circle'></i> </a><br>Qr:".$record->show_qrcode."<br>Letak".$record->letak_ttd;
                }else{
                    $row []  = "";    
                }
                if($record->is_signed == "1"){
                    $class_delete = "disabled";
                } 
                $btn_actions = array(); 
                $btn_actions  [] = "<a href='".base_url()."admin/tte/tte/edit/".$record->id."' data-toggle='tooltip' title='Edit data' class='btn btn-sm btn-success ".$class_delete."'><i class='fa fa-pencil'></i> </a>";
                
                $btn_actions  [] = "<a href='#' kode='".$record->id."'  data-toggle='tooltip' title='Hapus' class='btn btn-hapus btn-sm btn-danger ".$class_delete."'><i class='fa fa-trash-o'></i> </a>";
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";

                 

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        die();
    }


    function act_savedraft(){
        //echo "start save draft";
        $this->auth->restrict($this->permissionCreate);
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
        $this->form_validation->set_rules($this->tte_trx_draft_sk_model->get_validation_rules());
        $this->form_validation->set_rules('penandatangan_sk','Penandatangan','required|max_length[32]');
        $this->form_validation->set_rules('nip_pemilik_sk','NIP Pemilik SK','required|max_length[32]');
        $this->form_validation->set_rules('nomor_sk','Nomor Dokumen/ Nomor SK','required|max_length[100]');
        
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

        // echo json_encode($this->input->post());
        // echo "|";
        //$this->load->library('upload');

        //var_dump($this->auth->user_id());
        $this->load->helper('handle_upload');
        $status = false;
        $msg = "";  
        $post = $this->input->post();
        $penandatangan_sk = $this->input->post('penandatangan_sk');
        $id_master_proses   = $this->input->post('id_master_proses');
        $nip_sk             = $this->input->post('nip_pemilik_sk');
        $nama_pemilik_sk             = $this->input->post('nama_pemilik_sk');
        $averifikator = $this->input->post('verifikator');
        $kode = $this->input->post('id');    
        $direktori = trim($this->settings_lib->item('site.pathsk'));
        $halaman_ttd = $this->input->post('halaman_ttd');
        $show_qrcode = $this->input->post('show_qrcode');
        $letak_ttd = $this->input->post('letak_ttd');

        // File upload
        $base64data = "";
        $save_file_ds = false;
        $data_ds = array();
        if (isset($_FILES['nama_sk']) && $_FILES['nama_sk']['name']) {
            $errors=array();
            $allowed_ext= array('pdf');
            $file_name =$_FILES['nama_sk']['name'];
         //   $file_name =$_FILES['image']['tmp_name'];
            $file_ext = explode('.',$file_name);
            $file_size=$_FILES['nama_sk']['size'];
            $file_tmp= $_FILES['nama_sk']['tmp_name'];
            $type= $_FILES['nama_sk']['type'];
            //echo $file_ext[1];echo "<br>";
            $content_file = file_get_contents($file_tmp);
            $base64data = base64_encode($content_file);
            //die($base64data);
            if($base64data != ""){
                $data["base64pdf_hasil"]        = $base64data;    
                $data_ds["teks_base64"]         = $base64data;    
                $save_file_ds = true;
            }
            if(in_array(end($file_ext),$allowed_ext) === false)
            {
                $errors[]='Extension not allowed';
                $response['msg'] = "
                <div class='alert alert-block alert-error fade in note note-danger'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Ada kesalahan
                    </h4>
                    <p>Extension BA pelantikan tidak diizinkan, silahkan pilih file pdf</p>
                </div>
                ";
                echo json_encode($response);
                exit();
            }
            if($file_size > 5097152)
            {
                $errors[]= 'File size must be under 5mb';
                $response['msg'] = "
                <div class='alert alert-block alert-error fade in note note-danger'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Ada kesalahan
                    </h4>
                    <p>File size must be under 5mb</p>
                </div>
                ";
                echo json_encode($response);
                exit();

            }
            if(count($averifikator) == 0){
                $response['msg'] = "
                <div class='alert alert-block alert-error fade in'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Error
                    </h4>
                    Silahkan tentukan minimal 1 Verifikator
                </div>
                ";
                echo json_encode($response);
                exit();
            }else{
                $jmlverifikator = 0;
                foreach ($averifikator as $pid) {
                    if($pid != ""){
                        $jmlverifikator++;
                    }
                }
                if($jmlverifikator == 0){
                     $response['msg'] = "
                        <div class='alert alert-block alert-error fade in'>
                            <a class='close' data-dismiss='alert'>&times;</a>
                            <h4 class='alert-heading'>
                                Error
                            </h4>
                            Silahkan tentukan minimal 1 Verifikator
                        </div>
                        ";
                        echo json_encode($response);
                        exit();
                }
            }
        }
         
        if($penandatangan_sk ==""){
            $status = FALSE;
            $msg = "silahkan isi penandatangan SK";
        }
        
        $id_file = "";
        $nomor_sk = $this->input->post('nomor_sk') ? trim($this->input->post('nomor_sk')) : null;
        $tgl_sk = $this->input->post('tgl_sk') ? trim($this->input->post('tgl_sk')) : null;
        $tmt_sk = $this->input->post('tmt_sk') ? trim($this->input->post('tmt_sk')) : null;
        if($nomor_sk != "" and $kode == ""){
            $id_file = md5($nomor_sk.date("ymdhis"));
            $data['id_file']            = $id_file;
        }
        $data['nip_sk']             = $nip_sk;
        $data['nama_pemilik_sk']             = $nama_pemilik_sk;
        
        $data['penandatangan_sk']             = $penandatangan_sk;
        $data['id_master_proses']             = $id_master_proses ? $id_master_proses : null;
        $data['tgl_sk']             = $tgl_sk;
        $data['tmt_sk']             = $tmt_sk;
        $data['nomor_sk']           = $nomor_sk;

        $data['show_qrcode']           = $show_qrcode;
        $data['letak_ttd']           = $letak_ttd;
        $data['halaman_ttd']           = $halaman_ttd;
        
        if($kode != ""){
            $data['updated_by']         = $this->auth->user_id();
            $data['updated_date']        = date("Y-m-d");
            $id = $this->tte_trx_draft_sk_model->update($kode,$data);
        }else{
            $data['created_by']         = $this->auth->user_id();
            $data['created_date']            = date("Y-m-d");

            $id = $this->tte_trx_draft_sk_model->insert($data);   
            $kode = $id; 
        }
        
        if($id){
            $status = true;
            $msg = "Berhasil";   
        }else{
            $status = false;
            $msg = "Gagal";   
        }
    // save variable
        if($id_master_proses != ""){
            $datadel = array('id_tte_trx_draft_sk '=>$kode);
            $this->tte_trx_draft_sk_detil_model->delete_where($datadel);

            $this->proses_variable_model->where("id_proses",$id_master_proses);
            $proses_variable = $this->proses_variable_model->find_detil();
            if (isset($proses_variable) && is_array($proses_variable) && count($proses_variable)):
                foreach($proses_variable as $recordvar):
                    $datavar = array();
                    $datavar["id_tte_trx_draft_sk"] = $kode;
                    $datavar['id_variable']         = $recordvar->id_variable;
                    $datavar['isi']         = $this->input->post($recordvar->nama_variable);
                    $id_proses = $this->tte_trx_draft_sk_detil_model->insert($datavar);
                endforeach;
            endif;
            // korektor
            
            $datadel = array('id_tte_trx_draft_sk '=>$kode);
            $this->Tte_trx_korektor_draft_model->delete_where($datadel);
            $korektor_ke = 1;
            foreach ($averifikator as $pid) {
                if($pid != ""){
                    $datavar = array();
                    $datavar["id_pegawai_korektor"]      = $pid;
                    $datavar['id_tte_trx_draft_sk']        = $kode;
                    $datavar['korektor_ke']        = $korektor_ke;
                    $id_proses = $this->Tte_trx_korektor_draft_model->insert($datavar);
                    $korektor_ke++;
                }
            }
            if($save_file_ds){
                if($kode != ""){
                    $tte_draft = $this->tte_trx_draft_sk_model->find($kode);
                    $id_file = isset($tte_draft->id_file) ? $tte_draft->id_file : "";
                    
                }
                
                // save to sk_ds
                // create file
                $data_ds["teks_base64"] = $this->createfile_pdf($base64data,$id_file,$this->input->post('mode_usul'),$kode);
                $data_file_ds = $id_sk_ds = $this->sk_ds_model->find_by("id_file",$id_file);
                $id_sk_ds      = isset($data_file_ds->id) ? $data_file_ds->id : "";
                $is_signed = isset($data_file_ds->is_signed) ? $data_file_ds->is_signed : "";
                if($is_signed == "1"){
                    $data_json = array(
                        'status' => false,
                        'msg' => "File sudah di tandatangan, tidak bisa diubah"

                    );

                    $json_data = json_encode($data_json);
                    echo $json_data;
                    die();
                }
                $tte = $this->tte_model->find($id_master_proses);        
                $kategori = $tte->nama_proses;
                $data_ds['id_file']           = $id_file;
                $data_ds['waktu_buat']        = date("Y-m-d");
                $data_ds['id_pegawai_ttd']    = $penandatangan_sk;
                $data_ds['nip_sk']            = $nip_sk;
                $data_ds['nama_pemilik_sk']            = $nama_pemilik_sk;
                $data_ds['kategori']          = $kategori;
                $data_ds['nomor_sk']          = $nomor_sk;

                $data_ds['show_qrcode']         = $show_qrcode;
                $data_ds['letak_ttd']           = $letak_ttd;
                $data_ds['halaman_ttd']         = $halaman_ttd;

                if(count($averifikator) > 0){
                    $data_ds['is_signed']            = 0;
                    $data_ds['is_corrected']         = 0;
                }else{
                    $data_ds['is_corrected']         = 1;// dibuat langsung bisa tandatangan jika ga ada korektor        
                    $data_ds['is_signed']            = 0;
                }
                
                $data_ds['ds_ok']               = 1; // merupakan ttd digital
                if($id_sk_ds != ""){
                    $return_ds = $this->sk_ds_model->update($id_file,$data_ds);
                    log_activity($this->auth->user_id(),"Update File ds dari web " . ': ' . $id_sk_ds . ' : ' . $this->input->ip_address(), 'sk_ds');
                }else{
                    $return_ds = $this->sk_ds_model->insert($data_ds);    
                    log_activity($this->auth->user_id(),"Save File ds dari web " . ': ' . $return_ds . ' : ' . $this->input->ip_address(), 'sk_ds');
                }
                if($return_ds){
                    // save korektor
                    $averifikator = $this->input->post('verifikator');
                    $datadel_korektor = array('id_file '=>$id_file);
                    $this->sk_ds_korektor_model->delete_where($datadel_korektor);
                    $korektor_ke = 1;
                    if(count($averifikator) > 0){
                        foreach ($averifikator as $pid) {
                            if($pid != ""){
                                $data_korektor = array();
                                $data_korektor['id_file']        = $id_file;
                                $data_korektor["id_pegawai_korektor"]      = $pid;
                                $data_korektor['korektor_ke']        = $korektor_ke;
                                $data_korektor['is_corrected']       = $korektor_ke == 1 ? 2 : null;
                                $id_ds_korektor = $this->sk_ds_korektor_model->insert($data_korektor);
                                if($id_ds_korektor){
                                    log_activity($this->auth->user_id(),"Save Korektor ds dari web " . ': ' . $id_ds_korektor . ' : ' . $this->input->ip_address(), 'sk_ds');
                                }
                                $korektor_ke++;
                            }
                        }
                    } 
                    
                    $status = true;
                    $msg = "Upload berhasil";   
                }else{
                    $status = false;
                    $msg = "Upload gagal";   
                }
            }
        }
        $data_json = array(
            'status' => $status,
            'msg' => $msg

        );

        $json_data = json_encode($data_json);
        echo $json_data;
    }

    private function surat_usul_docs($file_name,$id_file,$kode){
        //echo "coeg";
        $this->load->library('fpdf/FPDF');
        $this->load->library('fpdi/FPDI');
        $pdf = new FPDI(); // Array sets the X, Y dimensions in mm
        $pagecount = $pdf->setSourceFile($file_name);
        for($i=1;$i<=$pagecount;$i++){
            $tppl = $pdf->importPage($i);
            //$pdf->AddPage();
            //echo BASEPATH."../../assets/templates/kop.png";
            $specs = $pdf->getTemplateSize($tppl);
            $pdf->addPage($specs['h'] > $specs['w'] ? 'P' : 'L');
            $pdf->useTemplate($tppl, 0, 0, 0, 0);

            if($i==1){
                //echo $specs['h']." | ".$specs['w'];
                $pdf->Image(BASEPATH."../../assets/templates/kopristek.png",0,0,210,43);  
                $pdf->Image(BASEPATH."../../assets/templates/BSRE.png",0,$specs['h']-14,210,13); 
                $pdf->Image($this->gen_qrcode($id_file),140,$specs['h']-96,14,14);    
            }else if($i!=1){
                $pdf->Image($this->gen_qrcode($id_file),3,$specs['h']-19,16,16); // X start, Y start, X width, Y width in mm
                $pdf->Image(BASEPATH."../../assets/templates/deskripsi.jpg",19,$specs['h']-19,20,16);
            }
        }
        

        $pdf->Output($direktori.$file_name, "F");

    }

    private function nota_usul_docs($file_name,$id_file,$kode){
        //echo "coeg";
        $this->load->library('fpdf/FPDF');
        $this->load->library('fpdi/FPDI');
        $pdf = new FPDI(); // Array sets the X, Y dimensions in mm
        $pagecount = $pdf->setSourceFile($file_name);
        for($i=1;$i<=$pagecount;$i++){
            $tppl = $pdf->importPage($i);
            
            $specs = $pdf->getTemplateSize($tppl);
            $pdf->addPage($specs['h'] > $specs['w'] ? 'P' : 'L');
            $pdf->useTemplate($tppl, 0, 0, 0, 0);

            if($i==1){
                
                $pdf->Image($this->gen_qrcode($id_file),3,$specs['h']-40,16,16); // X start, Y start, X width, Y width in mm
                $pdf->Image(BASEPATH."../../assets/templates/deskripsi.jpg",19,$specs['h']-40,20,16);
                $pdf->Image(BASEPATH."../../assets/templates/BSRE.png",0,$specs['h']-20,210,13); 
                //$pdf->Image($this->gen_qrcode($id_file),143,$specs['h']-46,12,12);  
                  
            }
        }
        

        $pdf->Output($direktori.$file_name, "F");

    }

    private function createfile_pdf($b64 = "",$file_id = "",$usul = 0,$kode){
        //echo "coeg";
        $direktori = trim($this->settings_lib->item('site.pathsk'));
        //$direktori = "/Users/echa/";
        $file_name = $file_id.".pdf";
        if (file_exists($direktori.$file_name)) {
           unlink($direktori.$file_name);
        }
        
        $bin = base64_decode($b64, true);
        file_put_contents($direktori.$file_name, $bin);
        
        if($usul==2){
            $this->surat_usul_docs($direktori.$file_name,$file_id,$kode);
        }else if($usul==1){
            $this->nota_usul_docs($direktori.$file_name,$file_id,$kode);
        }

        $imagedata = file_get_contents($direktori.$file_name);
        $base64 = base64_encode($imagedata);
        $data["base64pdf_hasil"] = $base64;        
        $this->tte_trx_draft_sk_model->update($kode,$data);

        return $base64;
        
    }


    public function delete()
    {
        $this->auth->restrict($this->permissionDelete);
        $id = $this->input->post('kode');
        $data_draft_sk = $this->tte_trx_draft_sk_model->find($id);
        $id_file = $data_draft_sk->id_file;
        $datadel = array('id_tte_trx_draft_sk '=>$id);
        $this->tte_trx_draft_sk_detil_model->delete_where($datadel);

        $datadel = array('id_tte_trx_draft_sk '=>$kode);
        $this->Tte_trx_korektor_draft_model->delete_where($datadel);

        if ($this->tte_trx_draft_sk_model->delete($id)) {
            $this->hapus_sk_ds($id_file);
             log_activity($this->auth->user_id(),"Delete data draft SK" . ': ' . $id . ' : ' . $this->input->ip_address(), 'tte_model');
             Template::set_message("Delete Draft SK sukses", 'success');
             echo "Sukses";
        }else{
            echo "Gagal";
        }

        exit();
    }
    private function hapus_sk_ds($id_file = ""){
        if($id_file != ""){
            $data = $this->sk_ds_model->find_by("id_file",$id_file);
            if($data->is_signed != "1"){ // jika blm ttd bisa dihapus
                // $data_updates = array(
                //     'ds_ok'     => 0
                // );
                // $this->sk_ds_model->update_where("id_file",$id_file, $data_updates);

                $datadel = array('id_file '=>$id_file);
                $this->sk_ds_model->delete_where($datadel);

                $datadel_korektor = array('id_file '=>$id_file);
                $this->sk_ds_korektor_model->delete_where($datadel_korektor);
            }else{
                die("Tidak bisa dihapus karena sudah ttd");    
            }
        }else{
            die("Id dile tidak ditemukan");
        }
    }
    public function downloadsk($id_table){
        $this->load->helper('dikbud');
        $draftsk = $this->tte_trx_draft_sk_model->find($id_table);
        $id = $draftsk->id_master_proses;
        $tgl_sk = $draftsk->tgl_sk;
        $nomor_sk = $draftsk->nomor_sk;
        $penandatangan_sk = $draftsk->penandatangan_sk;
        $nip_sk = $draftsk->nip_sk;
        $id_file = $draftsk->id_file;
        $qrcode = $this->gen_qrcode($id_file);
        if (empty($id_table)) {
            Template::set_message("Dokumen tidak ditemukan", 'error');
            redirect(SITE_AREA . '/tte/tte');
        }
        
        
        $tte = $this->tte_model->find($id);        
        $judul_ttd = $tte->nama_proses;
        $template_sk = $tte->template_sk;
        
        $apenandatangan_sk = $this->pegawai_model->find_by("PNS_ID",$penandatangan_sk);
        Template::set('apenandatangan_sk', $apenandatangan_sk);

        $apegawai = $this->initial_pegawai($nip_sk);
         
        $this->proses_variable_model->where("id_proses",$id);
        $proses_variable = $this->proses_variable_model->find_detil();
        Template::set('proses_variable', $proses_variable);

        $this->Tte_trx_korektor_draft_model->where("id_tte_trx_draft_sk",$id_table);
        $verifikators = $this->Tte_trx_korektor_draft_model->find_detil();
        Template::set('verifikators', $verifikators);

        $this->tte_trx_draft_sk_detil_model->where("id_tte_trx_draft_sk",$id_table);
        $aisi_variable = $this->tte_trx_draft_sk_detil_model->find_detil();
        $isivar = array();
        if (isset($aisi_variable) && is_array($aisi_variable) && count($aisi_variable)):
            foreach($aisi_variable as $isi_variable):
                $isivar[$isi_variable->nama_variable] = $isi_variable->isi;

                //array_push($isivar, [$isi_variable->id_variable => $isi_variable->isi]);

            endforeach;
        endif;
        $this->load->library('LibOpenTbs');
        $template_name = trim($this->settings_lib->item('site.pathsk'))."template/".$template_sk;
        $TBS = $this->libopentbs->TBS;
        
        $TBS->LoadTemplate($template_name, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
        $foto = trim($this->settings_lib->item('site.pathqrcode')).$id_table.".png";
        if(file_exists(trim($this->settings_lib->item('site.pathqrcode')).$id_table.".png") and $id_table != ""){
            $foto =  trim($this->settings_lib->item('site.pathqrcode')).$id_table.".png";
        }
        //die($foto_pegawai);
        $GELAR_DEPAN = $apegawai->GELAR_DEPAN != "" ? $apegawai->GELAR_DEPAN." " : "";
        $GELAR_BELAKANG = $apegawai->GELAR_BELAKANG != "" ? ", ".$apegawai->GELAR_BELAKANG : "";
        // $TBS->MergeField('v',$isivar);
        $var_qr = array(
            'foto'=>$qrcode
        );
        $vardefault = array(
            'nip_pemilik_sk'=>$draftsk->nip_pemilik_sk,
            'nama_pemilik_sk'=>$draftsk->nama_pemilik_sk,
            'tgl_sk'=>getIndonesiaFormat($draftsk->tgl_sk),
            'nomor_sk'=>$nomor_sk,
            'penandatangan_sk'=>$apenandatangan_sk->NAMA,
            'nip_penandatangan_sk'=>$apenandatangan_sk->NIP_BARU
        );
        $array_variable = array_merge($vardefault,$isivar,$apegawai,$var_qr);
        $TBS->MergeField('p',$array_variable);

        $output_file_name = $id_file.'.docx';
        $direktori = trim($this->settings_lib->item('site.pathsk'))."template/";
        $docx_location = $direktori.$output_file_name;
        
        //$TBS->Show(OPENTBS_FILE, $docx_location); // Also merges all [onshow] automatic fields.
        $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields. dan download file
        
        /*
        try {
            $unoconv = Unoconv\Unoconv::create([
                 'unoconv.binaries' => '/usr/bin/unoconv',
                ] 
            );
            $unoconv->transcode($docx_location, 'pdf', "temps/".$output_file_name.'.pdf');
            header("Content-type: application/pdf");
            header("Content-Disposition: inline; filename=filename.pdf");
            @readfile("temps/".$output_file_name.'.pdf');
            
        }catch(Exception $e){
            die($e." ada error");
            echo $e->getMessage();
        }   
        */
    }
    private function generate_pdf_from_word($file_word = ""){
        $command = "";
        //$return = shell_exec('libreoffice  --convert-to pdf --outdir /var/www/html/var/www/datasdmkemdikbudgoid/dikbudhr/assets/surat/template /var/www/datasdmkemdikbudgoid/dikbudhr/assets/surat/template/a275cfb9a271fb05ceb877ada4d4b1c7.docx.docx');
        $return = shell_exec('export HOME=/tmp && /usr/bin/soffice --convert-to pdf --outdir /var/www/datasdmkemdikbudgoid/dikbudhr/assets/surat/template/ '.$file_word);

        //$return = shell_exec('export HOME=/tmp && lowriter --convert-to pdf --outdir /var/www/html/templatesurat/assets/uploaded/template/result /var/www/html/templatesurat/assets/uploaded/template/Surat$
        if ($return) {
            //echo "PDF Created Successfully";
        } else {
            //echo 'PDF not created. Command = ' . $command . '=' . $return;
        }
        //  shell_exec('/usr/bin/libreoffice --headless -convert-to pdf --outdir /var/www/html/templatesurat/assets/uploaded/template/Surat_Mencoba.doc');
        //echo shell_exec(" ls -la /var/www/html/templatesurat/assets/uploaded/template/");
        //die("masuk");
    }
    private function crete_word($id_table = ""){
        $this->load->helper('dikbud');
        $draftsk = $this->tte_trx_draft_sk_model->find($id_table);
        $id = $draftsk->id_master_proses;
        $tgl_sk = $draftsk->tgl_sk;
        $nomor_sk = $draftsk->nomor_sk;
        $penandatangan_sk = $draftsk->penandatangan_sk;
        $nip_sk = $draftsk->nip_sk;
        $id_file = $draftsk->id_file;
        $qrcode = $this->gen_qrcode($id_file);
         
        $tte = $this->tte_model->find($id);        
        $judul_ttd = $tte->nama_proses;
        $template_sk = $tte->template_sk;
        if($template_sk != ""){
            $apenandatangan_sk = $this->pegawai_model->find_by("PNS_ID",$penandatangan_sk);
        
            $apegawai = $this->initial_pegawai($nip_sk);
            $this->tte_trx_draft_sk_detil_model->where("id_tte_trx_draft_sk",$id_table);
            $aisi_variable = $this->tte_trx_draft_sk_detil_model->find_detil();
            $isivar = array();
            if (isset($aisi_variable) && is_array($aisi_variable) && count($aisi_variable)):
                foreach($aisi_variable as $isi_variable):
                    $isivar[$isi_variable->nama_variable] = $isi_variable->isi;
                endforeach;
            endif;
            $this->load->library('LibOpenTbs');
            $template_name = trim($this->settings_lib->item('site.pathsk'))."template/".$template_sk;
            $TBS = $this->libopentbs->TBS;
            
            $TBS->LoadTemplate($template_name, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
            $foto = trim($this->settings_lib->item('site.pathqrcode')).$id_table.".png";
            if(file_exists(trim($this->settings_lib->item('site.pathqrcode')).$id_table.".png") and $id_table != ""){
                $foto =  trim($this->settings_lib->item('site.pathqrcode')).$id_table.".png";
            }
            $var_qr = array(
                'foto'=>$qrcode
            );
            $vardefault = array(
                'nip_pemilik_sk'=>$draftsk->nip_pemilik_sk,
                'nama_pemilik_sk'=>$draftsk->nama_pemilik_sk,
                'tgl_sk'=>getIndonesiaFormat($draftsk->tgl_sk),
                'nomor_sk'=>$nomor_sk,
                'penandatangan_sk'=>$apenandatangan_sk->NAMA,
                'nip_penandatangan_sk'=>$apenandatangan_sk->NIP_BARU
            );
            $array_variable = array_merge($vardefault,$isivar,$apegawai,$var_qr);
            $TBS->MergeField('p',$array_variable);

            $output_file_name = $id_file.'.docx';
            $direktori = trim($this->settings_lib->item('site.pathsk'))."template/";
            $docx_location = $direktori.$output_file_name;
            
            $TBS->Show(OPENTBS_FILE, $docx_location); // Also merges all [onshow] automatic fields.
            $this->generate_pdf_from_word($docx_location);
            //$TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields. dan download file    
        }
        
    }
    public function testpdf(){
        $command = "";
        //$return = shell_exec('libreoffice  --convert-to pdf --outdir /var/www/html/var/www/datasdmkemdikbudgoid/dikbudhr/assets/surat/template /var/www/datasdmkemdikbudgoid/dikbudhr/assets/surat/template/a275cfb9a271fb05ceb877ada4d4b1c7.docx.docx');
        $return = shell_exec('export HOME=/tmp && /usr/bin/soffice --convert-to pdf --outdir /var/www/datasdmkemdikbudgoid/dikbudhr/assets/surat/template/ /var/www/datasdmkemdikbudgoid/dikbudhr/assets/surat/template/a275cfb9a271fb05ceb877ada4d4b1c7.docx.docx');

        //$return = shell_exec('export HOME=/tmp && lowriter --convert-to pdf --outdir /var/www/html/templatesurat/assets/uploaded/template/result /var/www/html/templatesurat/assets/uploaded/template/Surat$
        if ($return) {
            echo "PDF Created Successfully";
        } else {
            echo 'PDF not created. Command = ' . $command . '=' . $return;
        }
        //  shell_exec('/usr/bin/libreoffice --headless -convert-to pdf --outdir /var/www/html/templatesurat/assets/uploaded/template/Surat_Mencoba.doc');
        //echo shell_exec(" ls -la /var/www/html/templatesurat/assets/uploaded/template/");
        //die("masuk");
    }
    private function initial_pegawai($nip_sk = ""){
        $apegawai = $this->pegawai_model->find_by("NIP_BARU",$nip_sk);
        $var_pegawai = array();
        $itemfield = $this->db->list_fields('pegawai');
        foreach($itemfield as $field)
        {
            $var_pegawai[$field] = isset($apegawai->$field) ? $apegawai->$field : "";  
        }
        if(isset($apegawai->NIP_BARU)){
            $var_pegawai["nama"] = $apegawai->GELAR_DEPAN."".$apegawai->NAMA.", ".$apegawai->GELAR_BELAKANG;
            $var_pegawai["nip"] = $NIP_BARU;
        }
        return $var_pegawai;
    }
    public function viewpdf($id_table){
        
        $draftsk = $this->tte_trx_draft_sk_model->find($id_table);
        $id = $draftsk->id_master_proses;
        $base64pdf_hasil = $draftsk->base64pdf_hasil;
        if (empty($base64pdf_hasil)) {
            Template::set_message("Dokumen pdf tidak ditemukan", 'error');

            redirect(SITE_AREA . '/tte/tte');
        }
        
        $output = $this->load->view('tte/tte/lihatsk',array("base64pdf_hasil"=>$base64pdf_hasil),true);    
        echo $output;
    }
    private function gen_qrcode($ref){
        $this->load->library('phpqrcode_lib');
        //$ref = uniqid("sk_kgb");
        $barcode_file = $ref.".png";
        $barcode_file_path = APPPATH."..".DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."qrcodes".DIRECTORY_SEPARATOR."".$barcode_file; 
        $qr_data = base_url()."dokumen/validasi/".$ref;
        QRcode::png($qr_data, $barcode_file_path, 'L', 8, 1);

        // === Adding image to qrcode
        $logo = APPPATH."..".DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."tutwuri.png";
        $QR = imagecreatefrompng($barcode_file_path);
        if($logo !== FALSE){
            $logopng = imagecreatefrompng($logo);
            $QR_width = imagesx($QR);
            $QR_height = imagesy($QR);
            $logo_width = imagesx($logopng);
            $logo_height = imagesy($logopng);
            
            list($newwidth, $newheight) = getimagesize($logo);
            $out = imagecreatetruecolor($QR_width, $QR_width);
            imagecopyresampled($out, $QR, 0, 0, 0, 0, $QR_width, $QR_height, $QR_width, $QR_height);
            imagecopyresampled($out, $logopng, $QR_width/2.65, $QR_height/2.65, 0, 0, $QR_width/4, $QR_height/4, $newwidth, $newheight);
            
        }
        imagepng($out,$barcode_file_path);
        imagedestroy($out);

        return $barcode_file_path;
    }
    public function viewdoc($file = "")
    {
        if (empty($id)) {
            Template::set_message("file tidak ditemukan", 'error');
        }
        $direktori = trim($this->settings_lib->item('site.pathsk'))."template/";
        $file_name = $file.".pdf";
        $base64signed = "";
        
        if (file_exists($direktori.$file_name)) {
            $base64signed = base64_encode(file_get_contents($direktori.$file_name));
        }
        $FILE_BASE64 = "data:application/pdf;base64,".$base64signed;
        echo '<embed src="'.$FILE_BASE64.'" width="100%" height="700" alt="pdf">';
        die();
    }
}