<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Kepegawaian extends Admin_Controller
{
    protected $permissionCreate = 'Pegawai.Kepegawaian.Create';
    protected $permissionViewPpnpn = 'Pegawai.Kepegawaian.CreatePpnpn';
    
    protected $permissionDelete = 'Pegawai.Kepegawaian.Delete';
    protected $permissionEdit   = 'Pegawai.Kepegawaian.Edit';
    protected $permissionView   = 'Pegawai.Kepegawaian.View';
    protected $permissionFiltersatker   = 'Pegawai.Kepegawaian.Filtersatker';
    protected $permissionAddpendidikan   = 'Pegawai.Kepegawaian.Addpendidikan';
    protected $permissionUpdateMandiri   = 'Pegawai.Kepegawaian.Updatemandiri';
    protected $permissionViewProfileOther   = 'Pegawai.Kepegawaian.LihatProfileLain';
    protected $permissionEselon1   = 'Pegawai.Kepegawaian.permissionEselon1';
	
    protected $permissionUbahfoto   = 'Pegawai.Kepegawaian.Ubahfoto';

    protected $VerifikasiUpdate   = 'Pegawai.Kepegawaian.VerifikasiUpdate';
    protected $VerifikasiUpdate3   = 'Pegawai.Kepegawaian.VerifikasiUpdate3';
    protected $UnitkerjaTerbatas   = 'Unitkerja.Kepegawaian.Terbatas';
    protected $permissionCreateUser   = 'Pegawai.CreateUser.View';
    protected $permissionResetPassword   = 'Pegawai.ResetPass.View';
    protected $permissionViewDataBkn   = 'Pegawai.ViewDataBkn.View';
    protected $permissionViewDataMutasi   = 'Pegawai.ViewDataMutasi.View';
	protected $permissionUpdatePensiun   = 'Pegawai.Kepegawaian.Pensiunupdate';
	public $UNOR_ID = null;
	public $UkerTerbatas = false;
	
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        

		set_time_limit(0);
		parent::__construct(); 
        $this->load->model('pegawai/pegawai_model');
		$this->load->model('pegawai/riwayat_pendidikan_model');
		$this->load->model('pegawai/riwayat_kepangkatan_model');
        $this->lang->load('pegawai');
		
        
        Assets::add_css('flick/jquery-ui-1.8.13.custom.css');
        Assets::add_js('jquery-ui-1.8.13.min.js');
        $this->form_validation->set_error_delimiters("<span class='has-error'>", "</span>");
        
        Template::set_block('sub_nav', 'kepegawaian/_sub_nav');

        Assets::add_module_js('pegawai', 'pegawai.js');
        
        //load referensi
        $this->load->model('pegawai/JENIS_PEGAWAI_model');
        $JENIS_PEGAWAIs = $this->JENIS_PEGAWAI_model->find_all();
		Template::set('JENIS_PEGAWAIs', $JENIS_PEGAWAIs);
		
		$this->load->model('pegawai/KEDUDUKAN_HUKUM_model');
        $KEDUDUKAN_HUKUMs = $this->KEDUDUKAN_HUKUM_model->find_all();
		Template::set('KEDUDUKAN_HUKUMs', $KEDUDUKAN_HUKUMs);
		$this->load->model('pegawai/golongan_model');
        $golongans = $this->golongan_model->find_all();
		Template::set('golongans', $golongans);
		
		$this->load->model('pegawai/agama_model');
		
        $agamas = $this->agama_model->find_all();
		Template::set('agamas', $agamas);
		
		$this->load->model('pegawai/tingkatpendidikan_model');
        $tkpendidikans = $this->tingkatpendidikan_model->find_all();
		Template::set('tkpendidikans', $tkpendidikans);
		
		$this->load->model('pegawai/kpkn_model');
        $kpkns = $this->kpkn_model->find_all();
		Template::set('kpkns', $kpkns);
		$this->load->model('ref_jabatan/jabatan_model');
		/*
		
        $jabatans = $this->ref_jabatan_model->find_all();
		Template::set('jabatans', $jabatans);
		
		
        $jabataninstansis = $this->jabatan_model->find_all();
		Template::set('jabataninstansis', $jabataninstansis);
		*/
        $this->load->model('pegawai/jenis_jabatan_model');
        $jenis_jabatans = $this->jenis_jabatan_model->find_all();
		Template::set('jenis_jabatans', $jenis_jabatans);
		$this->load->model('pegawai/jenis_kawin_model');
        $jenis_kawins = $this->jenis_kawin_model->find_all();
		Template::set('jenis_kawins', $jenis_kawins);

		// $this->load->model('ref_jabatan/ref_jabatan_model');
		// $jabatans = $this->ref_jabatan_model->find_all();
		// Template::set('list_jabatan', $jabatans);

		$this->load->model('pegawai/pendidikan_model');
		
		$this->load->model('pegawai/lokasi_model');
		$this->load->model('pegawai/unitkerja_model');
		
		// filter untuk role yang filtersatkernya aktif
		if($this->auth->has_permission($this->permissionFiltersatker)){
			$this->UNOR_ID = $this->pegawai_model->getunor_induk($this->auth->username());
			if($this->UNOR_ID == "")
				$this->UNOR_ID = "-";
		}
		if($this->auth->has_permission($this->permissionEselon1)){
			$this->UNOR_ID = $this->pegawai_model->getunor_eselon1($this->auth->username());
			if($this->UNOR_ID == "")
				$this->UNOR_ID = "-";
		}
    }

    /**
     * Display a list of pegawai data.
     *
     * @return void
     */
    public function index()
    {	

    	$this->auth->restrict($this->permissionView);
        Template::set('toolbar_title', lang('pegawai_manage'));
		
        Template::render();
    }
    public function ppnpn()
    {	
    	$this->auth->restrict($this->permissionView);
        Template::set('toolbar_title', "Pegawai PPNPN");		
        Template::render();
    }
    public function pensiun()
    {	

    	$this->auth->restrict($this->permissionView);
        Template::set('toolbar_title',"Pegawai Pensiun");
		
        Template::render();
    }
    public function uploadfoto()
	{
		$PNS_ID = $this->uri->segment(5);
		$pegawaiData = $this->pegawai_model->find_by("PNS_ID",$PNS_ID);
		$PNS_NIP = ISSET($pegawaiData->NIP_BARU) ? trim($pegawaiData->NIP_BARU) : "";
		Template::set('PNS_ID', trim($PNS_ID));
		Template::set('PNS_NIP', $PNS_NIP);
		//echo $PNS_ID." PNS_ID";

		Template::render();
	}
	function savefoto(){
		$this->auth->restrict($this->permissionUbahfoto);
    	$this->load->helper('handle_upload');
		$uploadData = array();
		$upload = true;
		$id_log = $this->input->post('PNS_ID');
		$NIP = $this->input->post('PNS_NIP');
		$id = "";
		$namafile = "";
		//$this->settings_lib->item('site.urlphoto')
		if (isset($_FILES['userfile']) && is_array($_FILES['userfile']) && $_FILES['userfile']['error'] != 4)
		 {
			//$namafile=$this->settings_lib->item('site.urlphoto');
		 	$pegawaiData = $this->pegawai_model->find_by("PNS_ID",$id_log);
			$PHOTO = ISSET($pegawaiData->PHOTO) ? trim($pegawaiData->PHOTO) : "";
			//die($this->settings_lib->item('site.urlphoto').$PHOTO);
			if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$PHOTO) && $PHOTO != ""){
				deletefile($PHOTO,trim($this->settings_lib->item('site.urlphoto')));
			}

			$tmp_name 	= pathinfo($_FILES['userfile']['name'], PATHINFO_FILENAME);
			$uploadData = handle_upload('userfile',trim($this->settings_lib->item('site.urlphoto')),$NIP);
			 if (isset($uploadData['error']) && !empty($uploadData['error']))
			 {
			 	$tipefile=$_FILES['userfile']['type'];
			 	//$tipefile = $_FILES['userfile']['name'];
				 $upload = false;
				 $namafile="error";
				//$uploadData = handle_upload('userfile',trim($this->settings_lib->item('site.urlphoto')));
				 log_activity($this->auth->user_id(), 'Gagal : '.$uploadData['error'].trim($this->settings_lib->item('site.urlphoto')).$tipefile.$this->input->ip_address(), 'pegawai');
			 }else{
			 	$namafile = $uploadData['data']['file_name'];
                log_activity($this->auth->user_id(), 'upload foto : ' . $id_log . ' : ' . $this->input->ip_address(), 'pegawai');
			 }
		 }else{
		 	die("File tidak ditemukan");
		 	log_activity($this->auth->user_id(), 'File tidak ditemukan : ' . $this->input->ip_address(), 'pegawai');
		 } 	
		if ($namafile != "")
		{
			$dataupdate = array(
			'PHOTO'	=> $namafile
			);
			$this->pegawai_model->update_where("PNS_ID",$id_log, $dataupdate);
		}
       echo '{"namafile":"'.$namafile.'"}';
       exit();
	}
	public function importdatapegawai()
	{
		Template::set('toolbar_title', "Import data");
		Template::render();
	}
	public function importhcdp()
	{
		Template::set('toolbar_title', "Import HCDP");
		Template::render();
	}
	public function importdatabkn()
	{
		Template::set('toolbar_title', "Import data dari BKN");
		Template::render();
	}
	
	function runimport(){
    	 //$this->auth->restrict($this->permissionCreate);
    	 $this->load->helper('handle_upload');
		 $uploadData = array();
		 $upload = true;
		 $id = $this->input->post('id');
		 $message = "";
		 $index = 0;
		 $gagal = 0;
		 if (isset($_FILES['userfile']) && is_array($_FILES['userfile']) && $_FILES['userfile']['error'] != 4)
		 {
			$tmp_name = pathinfo($_FILES['userfile']['name'], PATHINFO_FILENAME);
			$uploadData = handle_upload('userfile',trim($this->settings_lib->item('site.pathuploaded')),$tmp_name);
			 if (isset($uploadData['error']) && !empty($uploadData['error']))
			 {
			 	$tipefile=$_FILES['userfile']['type'];
				 $upload = false;
				 $message = "Gagal upload data".$uploadData['error'];
				 log_activity($this->auth->user_id(), 'Gagal : '.$uploadData['error'].$this->pegawai_model->error.$tipefile.$this->input->ip_address(), 'pegawai');
			 }else{
			 	
				if(isset($uploadData['data']['file_name']))
					$file = trim($this->settings_lib->item('site.pathuploaded')).$uploadData['data']['file_name'];
				else
					$file ="";
				$this->load->library('Excel');
				$objPHPExcel = PHPExcel_IOFactory::load($file);
				 //  Get worksheet dimensions
				$sheet = $objPHPExcel->getSheet(0); 
				$highestRow = $sheet->getHighestRow(); 
				$highestColumn = $sheet->getHighestColumn();
				$itemfield = $this->db->list_fields('pegawai');
   				for ($row = 2; $row <= $highestRow; $row++)
				{ 
					//  Read a row of data into an array
					$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
													  NULL,
													  TRUE,
													  FALSE);
					$data = array();
					$col = 0;
					foreach($itemfield as $field)
					{
						if(trim($rowData[0][$col]) != ''){
							$val = "";
							if($field == "TMT_CPNS" or $field == "TMT_PNS" or $field == "TMT_GOLONGAN" or $field == "TMT_JABATAN" or $field == "TGL_SK_CPNS" or $field == "TGL_SURAT_DOKTER" or $field == "TGL_BEBAS_NARKOBA" or $field == "TGL_CATATAN_POLISI" or $field == "TGL_CATATAN_POLISI" or $field == "TGL_NPWP" or $field == "TMT_PENSIUN"){
								if(trim($rowData[0][$col]) != ""){
									$valtanggal = (double)trim($rowData[0][$col]);
									$unix_date = ($valtanggal - 25569) * 86400;
									$excel_date = 25569 + ($unix_date / 86400);
									$unix_date = ($excel_date - 25569) * 86400;
									$val =  gmdate("Y-m-d", $unix_date);	
								}
								
							}
							 
							if($val != ""){
						   		$data[$field] 	= $val; 
							}
						   	else{
						   		$data[$field] 	= trim($rowData[0][$col]); 
						   	}
						}
						$col++;
					}
					if($rowData[0][1] != ""){
						$data['status_pegawai'] 	= 1; 
						if($insert_id = $this->pegawai_model->insert($data)){
							$index++;
						}else{
							$gagal++;
						}
					}
				}
				$message = "Upload Berhasil :".$index." data, gagal :".$gagal.$this->pegawai_model->error;
			 	log_activity($this->auth->user_id(), 'Berhasil  : '.$this->pegawai_model->error.$this->input->ip_address(), 'pegawai');
			 	
			 }
		 }else{
		 	log_activity($this->auth->user_id(), 'File tidak ditemukan : ' . $this->input->ip_address(), 'pegawai');
		 } 
		 echo '{"message":"'.$message.'"}';
		 exit();
	}
	
    /**
     * Create a pegawai object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        
        if (isset($_POST['save'])) {
            if ($insert_id = $this->save_pegawai()) {
                log_activity($this->auth->user_id(), lang('pegawai_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'pegawai');
                Template::set_message(lang('pegawai_create_success'), 'success');

                redirect(SITE_AREA . '/kepegawaian/pegawai');
            }

            // Not validation error
            if ( ! empty($this->pegawai_model->error)) {
                Template::set_message(lang('pegawai_create_failure') . $this->pegawai_model->error, 'error');
            }
        }

        Template::set('toolbar_title', lang('pegawai_action_create'));

        Template::render();
    }
    public function create_ppnpn()
    {
        $this->auth->restrict($this->permissionCreatePpnpn);
        Template::set('toolbar_title', "Tambah Pegawai PPNPN");

        Template::render();
    }
    public function viewupdatemandiri()
    {	
    	$this->auth->restrict($this->VerifikasiUpdate);
        Template::set('toolbar_title', "Verifikasi Update Mandiri");
        Template::render();
    }
    public function viewupdatemandiri1()
    {	
    	$this->auth->restrict($this->VerifikasiUpdate);
        Template::set('toolbar_title', "Verifikasi Update Mandiri Level Satker");
        Template::render();
    }
    // riwayat pendidikan
    public function addpendidikan($PNS_BKN_ID,$riwayat_pendidikan_id=NULL)
    {
        $this->auth->restrict($this->permissionAddpendidikan);
		Template::set('rwt_pendidikan', $this->riwayat_pendidikan_model->find($riwayat_pendidikan_id));
		
		Template::set('PNS_ID', $PNS_BKN_ID);
        Template::set('toolbar_title', "Tambah Riwayat Pendiikan");

        Template::render();
    }
    public function deletependidikan()
	{
		$this->auth->restrict($this->permissionAddpendidikan);
		$id 	= $this->input->post('kode');
		if ($this->riwayat_pendidikan_model->delete($id)) {
			 log_activity($this->auth->user_id(), 'delete data Riwayat Pendidikan : ' . $id . ' : ' . $this->input->ip_address(), 'pegawai');
			 Template::set_message("Sukses Hapus data", 'success');
			 echo "Sukses";
		}else{
			echo "Gagal";
		}

		exit();
	}
	public function createdata($type = "insert"){
        $this->auth->restrict($this->permissionCreate);
        $tahun = date("Y");
        $response = array(
            'success'=>false,
            'shortmsg'=>"",
            'msg'=>'Unknown error'
        );
        $nomor = "";
        $id = $this->input->post('id');
        $this->form_validation->set_rules($this->pegawai_model->get_validation_rules());
        $extra_unique_rule = '';
		if ($id != '')
		{
			$_POST['id'] = $id;
			$extra_unique_rule = ',pegawai.ID';
		}else{
			$this->form_validation->set_rules('PNS_ID','KODE','required|max_length[30]|unique[pegawai.PNS_ID' . $extra_unique_rule . ']');
		}
        //$this->form_validation->set_rules('JENIS_KELAMIN','JENIS_KELAMIN','required|max_length[30]');
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
            $data = $this->pegawai_model->prep_data($this->input->post());
        
	        $reclokasikerja = $this->lokasi_model->find($this->input->post('LOKASI_KERJA_ID'));
			$data['LOKASI_KERJA']	= $reclokasikerja->NAMA;
			$recpendidikan = $this->pendidikan_model->find($this->input->post('PENDIDIKAN_ID'));
			$data['PENDIDIKAN']	= $recpendidikan->NAMA;
			
			$data['PNS_ID']	= $this->input->post('PNS_ID');
			$data['NIP_BARU']	= $this->input->post('PNS_ID');
	        $data['AGAMA_ID']	= $this->input->post('AGAMA_ID') ? $this->input->post('AGAMA_ID') : null;
			$data['TGL_LAHIR']	= $this->input->post('TGL_LAHIR') ? $this->input->post('TGL_LAHIR') : null;
			$data['TGL_NPWP']	= $this->input->post('TGL_NPWP') ? $this->input->post('TGL_NPWP') : null;
			$data['terminated_date']	= $this->input->post('terminated_date') ? $this->input->post('terminated_date') : null;
			if($this->input->post("JENIS_JABATAN_ID") != ""){
				$rec_jenis = $this->jenis_jabatan_model->find($this->input->post("JENIS_JABATAN_ID"));
				$data["JENIS_JABATAN_NAMA"] = $rec_jenis->NAMA;
			}

			$recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",$this->input->post('JABATAN_INSTANSI_ID'));
			$data['JABATAN_INSTANSI_NAMA']	= $recjabatan->NAMA_JABATAN;
	        $data['JENIS_JABATAN_ID']	= $this->input->post('JENIS_JABATAN_ID') ? $this->input->post('JENIS_JABATAN_ID') : null;
	        
			// jabatan
			$data['TMT_JABATAN']	= $this->input->post('TMT_JABATAN') ? $this->input->post('TMT_JABATAN') : null;
			$data['JABATAN_INSTANSI_ID']	= $this->input->post('JABATAN_INSTANSI_ID') ? $this->input->post('JABATAN_INSTANSI_ID') : null;
			$data['JABATAN_ID']	= $this->input->post('JABATAN_INSTANSI_ID') ? $this->input->post('JABATAN_INSTANSI_ID') : null;
			$data['JABATAN_INSTANSI_REAL_ID']	= $this->input->post('JABATAN_INSTANSI_REAL_ID') ? $this->input->post('JABATAN_INSTANSI_REAL_ID') : null;
	        $return = false;
	        //die($id." ini");
	        if ($id == '') {
	            $id = $this->pegawai_model->insert($data);

	            if (is_numeric($id)) {
	                $return = $id;
	                $response ['success']= true;
                    $response ['msg']= "Berhasil Simpan data";
	            }
	        } elseif ($id != '') {
	            $return = $this->pegawai_model->update($id, $data);
	            $response ['success']= true;
                $response ['msg']= "Berhasil Update data";
	        }
        
        echo json_encode($response);
        exit();  

    }
    public function savependidikan()
    {
    	$insert_id = 0;
        $this->auth->restrict($this->permissionAddpendidikan);
		$id = $this->input->post('id_data');
		if($id != ""){
			if($this->save_pendidikan("update",$id))
			{
				 echo "Sukses Update data";
			}else{
				 echo "Gagal simpan data";
			}
        }else{
        	
        	if($id = $this->save_pendidikan()){
        		log_activity($this->auth->user_id(), 'Save riwayat Pendidikan : ' . $id . ' : ' . $this->input->ip_address(), 'pegawai');
        		echo "Sukses simpan data";
        	}else{
        		log_activity($this->auth->user_id(), 'Save riwayat Pendidikan gagal, dari' .$this->riwayat_pendidikan_model->error. $this->input->ip_address(), 'pegawai');
        		echo "Gagal ".$this->riwayat_pendidikan_model->error;
        	}
        }
       
        exit();
    }
    private function save_pendidikan($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['id'] = $id;
        }
       
        // Make sure we only pass in the fields we want
        
        $data = $this->riwayat_pendidikan_model->prep_data($this->input->post());
		$data['PNS_ID'] 	= trim($this->input->post('PNS_ID'));
		$data['TINGKAT_PENDIDIKAN_ID'] 	= $this->input->post('TINGKAT_PENDIDIKAN_ID');
		$data['PENDIDIKAN_ID'] 	= $this->input->post('PENDIDIKAN_ID');
		$data['TANGGAL_LULUS'] 	= $this->input->post('TANGGAL_LULUS') ? $this->input->post('TANGGAL_LULUS') : null;
		$data['TAHUN_LULUS'] 	= $this->input->post('TAHUN_LULUS');
		$data['NOMOR_IJASAH'] 	= $this->input->post('NOMOR_IJASAH');
		$data['NAMA_SEKOLAH'] 	= $this->input->post('NAMA_SEKOLAH');
		$data['GELAR_DEPAN'] 	= $this->input->post('GELAR_DEPAN');
		$data['GELAR_BELAKANG'] 	= $this->input->post('GELAR_BELAKANG');
		$data['PENDIDIKAN_PERTAMA'] 	= $this->input->post('PENDIDIKAN_PERTAMA');
		$data['NEGARA_SEKOLAH'] 	= $this->input->post('NEGARA_SEKOLAH');
        // Additional handling for default values should be added below,
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->riwayat_pendidikan_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->riwayat_pendidikan_model->update($id, $data);
        }

        return $return;
    }
    /**
     * Allows editing of pegawai data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        $id = (int)base64_decode(urldecode($id));
        if (empty($id)) {
            Template::set_message(lang('pegawai_invalid_id'), 'error');

            redirect(SITE_AREA . '/kepegawaian/pegawai');
        }
        
        if (isset($_POST['save'])) {
            $this->auth->restrict($this->permissionEdit);

            if ($this->save_pegawai('update', $id)) {
                log_activity($this->auth->user_id(), lang('pegawai_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'pegawai');
                Template::set_message(lang('pegawai_edit_success'), 'success');
                redirect(SITE_AREA . '/kepegawaian/pegawai');
            }

            // Not validation error
            if ( ! empty($this->pegawai_model->error)) {
                Template::set_message(lang('pegawai_edit_failure') . $this->pegawai_model->error, 'error');
            }
        }
        
        elseif (isset($_POST['delete'])) {
            $this->auth->restrict($this->permissionDelete);

            if ($this->pegawai_model->delete($id)) {
                log_activity($this->auth->user_id(), lang('pegawai_act_delete_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'pegawai');
                Template::set_message(lang('pegawai_delete_success'), 'success');

                redirect(SITE_AREA . '/kepegawaian/pegawai');
            }

            Template::set_message(lang('pegawai_delete_failure') . $this->pegawai_model->error, 'error');
        }
        
        $pegawaiData = $this->pegawai_model->find($id);
        Template::set('pegawai', $pegawaiData);


        Template::set('selectedLokasiPegawai',$this->lokasi_model->find($pegawaiData->LOKASI_KERJA_ID));
        Template::set('selectedTempatLahirPegawai',$this->lokasi_model->find($pegawaiData->TEMPAT_LAHIR_ID));
        //Template::set('selectedPendidikan',$this->pendidikan_model->find($pegawaiData->PENDIDIKAN_ID));
        if(TRIM($pegawaiData->UNOR_ID) == TRIM($pegawaiData->UNOR_INDUK_ID)){
        	$unor = $this->unitkerja_model->find_by("ID",TRIM($pegawaiData->UNOR_ID));
        	Template::set('selectedUnorid',$unor);
        	Template::set('selectedUnorindukid',$unor);
        }else{
        	Template::set('selectedUnorid',$this->unitkerja_model->find_by("ID",TRIM($pegawaiData->UNOR_ID)));
        	Template::set('selectedUnorindukid',$this->unitkerja_model->find_by("ID",TRIM($pegawaiData->UNOR_INDUK_ID)));
        }
        //die($pegawaiData->JABATAN_INSTANSI_ID);
        Template::set('selectedPendidikanID',$this->pendidikan_model->find(trim($pegawaiData->PENDIDIKAN_ID)));
        $datajabatan = $this->jabatan_model->find_by("KODE_JABATAN",trim($pegawaiData->JABATAN_INSTANSI_ID));
        Template::set('selectedJabatanInstansiID',$datajabatan);

        $datajabatan_real = $this->jabatan_model->find_by("KODE_JABATAN",trim($pegawaiData->JABATAN_INSTANSI_REAL_ID));
        Template::set('selectedJabatanInstansiRealID',$datajabatan_real);

        if($pegawaiData->JENIS_JABATAN_ID != ""){
        	//$jabataninstansis = $this->jabatan_model->find_select(trim($pegawaiData->JENIS_JABATAN_ID));
			//Template::set('jabataninstansis', $jabataninstansis);
        }
        if($pegawaiData->TK_PENDIDIKAN != ""){
        	//$pendidikans = $this->pendidikan_model->find_all(trim($pegawaiData->TK_PENDIDIKAN));
			//Template::set('pendidikans', $pendidikans);
		}
        
        Template::set('toolbar_title', lang('pegawai_edit_heading'));
        Template::render();
    }
    public function edit_ppnpn()
    {

        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('pegawai_invalid_id'), 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai_ppnpn');
        }
        
         
        $pegawaiData = $this->pegawai_model->find($id);
        Template::set('pegawai', $pegawaiData);

        Template::set('selectedLokasiPegawai',$this->lokasi_model->find($pegawaiData->LOKASI_KERJA_ID));
        Template::set('selectedTempatLahirPegawai',$this->lokasi_model->find($pegawaiData->TEMPAT_LAHIR_ID));
        //Template::set('selectedPendidikan',$this->pendidikan_model->find($pegawaiData->PENDIDIKAN_ID));
        if(TRIM($pegawaiData->UNOR_ID) == TRIM($pegawaiData->UNOR_INDUK_ID)){
        	$unor = $this->unitkerja_model->find_by("ID",TRIM($pegawaiData->UNOR_ID));
        	Template::set('selectedUnorid',$unor);
        	Template::set('selectedUnorindukid',$unor);
        }else{
        	$unor = $this->unitkerja_model->find_by("ID",TRIM($pegawaiData->UNOR_ID));
        	Template::set('selectedUnorid',$unor);
        	$unor_induk = $this->unitkerja_model->find_by("ID",TRIM($pegawaiData->UNOR_INDUK_ID));
        	Template::set('selectedUnorindukid',$unor_induk);
        }
        //die($pegawaiData->JABATAN_INSTANSI_ID);
        Template::set('selectedPendidikanID',$this->pendidikan_model->find(trim($pegawaiData->PENDIDIKAN_ID)));
        $datajabatan = $this->jabatan_model->find_by("KODE_JABATAN",trim($pegawaiData->JABATAN_INSTANSI_ID));
        Template::set('selectedJabatanInstansiID',$datajabatan);

        $datajabatan_real = $this->jabatan_model->find_by("KODE_JABATAN",trim($pegawaiData->JABATAN_INSTANSI_REAL_ID));
        Template::set('selectedJabatanInstansiRealID',$datajabatan_real);
        if($pegawaiData->JENIS_JABATAN_ID != ""){
        	//$jabataninstansis = $this->jabatan_model->find_select(trim($pegawaiData->JENIS_JABATAN_ID));
			//Template::set('jabataninstansis', $jabataninstansis);
        }
        if($pegawaiData->TK_PENDIDIKAN != ""){
        	//$pendidikans = $this->pendidikan_model->find_all(trim($pegawaiData->TK_PENDIDIKAN));
			//Template::set('pendidikans', $pendidikans);
		}
        Template::set('toolbar_title', "Edit Data Pegawai PPNPN");
        Template::render();
    }
    public function updatemandiri(){
         // Validate the data
    	$this->auth->restrict($this->permissionUpdateMandiri);
    	date_default_timezone_set("Asia/Bangkok");
    	$this->load->model('pegawai/update_mandiri_model');
    	if (!$this->input->is_ajax_request()) {
   			die("Only ajax request");
		}
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        $id_data = $this->input->post('ID');
        $pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($this->auth->username()));
		$id = isset($pegawai->ID) ? $pegawai->ID : "";
		if($id != $id_data){
 	       	$response ['success']= false;
    		$response ['msg']= "Anda tidak berhak";
	        // echo json_encode($response);  
	        log_activity($this->auth->user_id(), "Update data mandiri oleh orang lain ".$this->input->ip_address(), 'pegawai');
	        // exit();
        }
        // GET DATA PEGAWAI 
        $datasekarang = $this->pegawai_model->find($id_data);
        $PNS_ID = $datasekarang->PNS_ID;
        //$data = $this->pegawai_model->prep_data($this->input->post()); 
        // status tiga adalah level satker

        // $LOKASI_KERJA_NEW = trim($this->input->post('LOKASI_KERJA'));
        // if((trim($datasekarang->LOKASI_KERJA) != trim($LOKASI_KERJA_NEW)) && $LOKASI_KERJA_NEW != ""){
        // 	$data_update = array();
        // 	$data_update['PNS_ID']		= $PNS_ID;
        // 	$data_update['KOLOM']		= "LOKASI_KERJA";
        // 	$data_update['DARI']		= $datasekarang->LOKASI_KERJA;
        // 	$data_update['PERUBAHAN']	= $LOKASI_KERJA_NEW;
        // 	$data_update['NAMA_KOLOM']	= "LOKASI KERJA";
        // 	$data_update['LEVEL_UPDATE']= 1;
        // 	$data_update['UPDATE_TGL']	= date("Y-m-d");
		// 	//$id_update = $this->update_mandiri_model->insert($data_update);
        // }


        $KPPN_ID_NEW = trim($this->input->post('KPPN_ID'));
        if((trim($datasekarang->KPPN_ID) != trim($KPPN_ID_NEW)) && $KPPN_ID_NEW != ""){
        	$data_update = array();
        	$data_update['PNS_ID']		= $PNS_ID;
        	$data_update['KOLOM']		= "KPPN_ID";
        	$data_update['DARI']		= $datasekarang->KPPN_ID;
        	$data_update['PERUBAHAN']	= $KPPN_ID_NEW;
        	$data_update['NAMA_KOLOM']	= "KPPN";
        	$data_update['LEVEL_UPDATE']= 1;
        	$data_update['UPDATE_TGL']	= date("Y-m-d");
			//$id_update = $this->update_mandiri_model->insert($data_update);
        }

        $TEMPAT_LAHIR_ID_NEW = $this->input->post('TEMPAT_LAHIR_ID');
        if((trim($datasekarang->TEMPAT_LAHIR_ID) != trim($TEMPAT_LAHIR_ID_NEW)) && $TEMPAT_LAHIR_ID_NEW != ""){
        	$data_update = array();
        	$data_update['PNS_ID']		= $PNS_ID;
        	$data_update['KOLOM']		= "TEMPAT_LAHIR_ID";
        	$data_update['DARI']		= $datasekarang->TEMPAT_LAHIR_ID;
        	$data_update['PERUBAHAN']	= $TEMPAT_LAHIR_ID_NEW;
        	$data_update['NAMA_KOLOM']	= "Tempat Lahir";
        	$data_update['LEVEL_UPDATE']= 3;
        	$data_update['UPDATE_TGL']	= date("Y-m-d");
			$id_update = $this->update_mandiri_model->insert($data_update);
        }
        /*
        $EMAIL_NEW = trim($this->input->post('EMAIL'));
        if((trim($datasekarang->EMAIL) != trim($EMAIL_NEW)) && $EMAIL_NEW != ""){
        	$data_update = array();
        	$data_update['PNS_ID']		= $PNS_ID;
        	$data_update['KOLOM']		= "EMAIL";
        	$data_update['DARI']		= $datasekarang->EMAIL;
        	$data_update['PERUBAHAN']	= $EMAIL_NEW;
        	$data_update['NAMA_KOLOM']	= "EMAIL";
        	$data_update['LEVEL_UPDATE']= 1;
        	$data_update['UPDATE_TGL']	= date("Y-m-d");
			$id_update = $this->update_mandiri_model->insert($data_update);
        }
        
        $ALAMAT_NEW = trim($this->input->post('ALAMAT'));
        if((trim($datasekarang->ALAMAT) != trim($ALAMAT_NEW)) && $ALAMAT_NEW != ""){
        	$data_update = array();
        	$data_update['PNS_ID']		= $PNS_ID;
        	$data_update['KOLOM']		= "ALAMAT";
        	$data_update['DARI']		= $datasekarang->ALAMAT;
        	$data_update['PERUBAHAN']	= $ALAMAT_NEW;
        	$data_update['NAMA_KOLOM']	= "ALAMAT";
        	$data_update['LEVEL_UPDATE']= 1;
        	$data_update['UPDATE_TGL']	= date("Y-m-d");
			$id_update = $this->update_mandiri_model->insert($data_update);
        }

        $NOMOR_HP_NEW = trim($this->input->post('NOMOR_HP'));
        if((trim($datasekarang->NOMOR_HP) != trim($NOMOR_HP_NEW)) && $NOMOR_HP_NEW != ""){
        	$data_update = array();
        	$data_update['PNS_ID']		= $PNS_ID;
        	$data_update['KOLOM']		= "NOMOR_HP";
        	$data_update['DARI']		= $datasekarang->NOMOR_HP;
        	$data_update['PERUBAHAN']	= $NOMOR_HP_NEW;
        	$data_update['NAMA_KOLOM']	= "NOMOR_HP";
        	$data_update['LEVEL_UPDATE']= 1;
        	$data_update['UPDATE_TGL']	= date("Y-m-d");
			$id_update = $this->update_mandiri_model->insert($data_update);
        }
        $KARTU_PEGAWAI_NEW = trim($this->input->post('KARTU_PEGAWAI'));
        if((trim($datasekarang->KARTU_PEGAWAI) != trim($KARTU_PEGAWAI_NEW)) && $KARTU_PEGAWAI_NEW != ""){
        	$data_update = array();
        	$data_update['PNS_ID']		= $PNS_ID;
        	$data_update['KOLOM']		= "KARTU_PEGAWAI";
        	$data_update['DARI']		= $datasekarang->KARTU_PEGAWAI;
        	$data_update['PERUBAHAN']	= $KARTU_PEGAWAI_NEW;
        	$data_update['NAMA_KOLOM']	= "KARTU PEGAWAI";
        	$data_update['UPDATE_TGL']	= date("Y-m-d");
        	$data_update['LEVEL_UPDATE']= 3;
			$id_update = $this->update_mandiri_model->insert($data_update);
        }
        */

        $STATUS_CPNS_PNS_NEW = trim($this->input->post('STATUS_CPNS_PNS'));
        if((trim($datasekarang->STATUS_CPNS_PNS) != trim($STATUS_CPNS_PNS_NEW)) && $STATUS_CPNS_PNS_NEW != ""){
        	$data_update = array();
        	$data_update['PNS_ID']		= $PNS_ID;
        	$data_update['KOLOM']		= "STATUS_CPNS_PNS";
        	$data_update['DARI']		= $datasekarang->STATUS_CPNS_PNS;
        	$data_update['PERUBAHAN']	= $STATUS_CPNS_PNS_NEW;
        	$data_update['NAMA_KOLOM']	= "STATUS_CPNS_PNS";
        	$data_update['LEVEL_UPDATE']= 3;
        	$data_update['UPDATE_TGL']	= date("Y-m-d");
			$id_update = $this->update_mandiri_model->insert($data_update);
        }

        $TMT_PNS_NEW = trim($this->input->post('TMT_PNS'));
        if((trim($datasekarang->TMT_PNS) != trim($TMT_PNS_NEW)) && $TMT_PNS_NEW != ""){
        	$data_update = array();
        	$data_update['PNS_ID']		= $PNS_ID;
        	$data_update['KOLOM']		= "TMT_PNS";
        	$data_update['DARI']		= $datasekarang->TMT_PNS;
        	$data_update['PERUBAHAN']	= $TMT_PNS_NEW;
        	$data_update['NAMA_KOLOM']	= "TMT_PNS";
        	$data_update['LEVEL_UPDATE']= 3;
        	$data_update['UPDATE_TGL']	= date("Y-m-d");
			$id_update = $this->update_mandiri_model->insert($data_update);
        }

        $GOL_AWAL_ID_NEW = trim($this->input->post('GOL_ID'));
        if((trim($datasekarang->GOL_ID) != trim($GOL_AWAL_ID_NEW)) && $GOL_AWAL_ID_NEW != ""){
        	$data_update = array();
        	$data_update['PNS_ID']		= $PNS_ID;
        	$data_update['KOLOM']		= "GOL_ID";
        	$data_update['DARI']		= $datasekarang->GOL_ID;
        	$data_update['PERUBAHAN']	= $GOL_AWAL_ID_NEW;
        	$data_update['NAMA_KOLOM']	= "GOLONGAN";
        	$data_update['LEVEL_UPDATE']= 3;
        	$data_update['UPDATE_TGL']	= date("Y-m-d");
			$id_update = $this->update_mandiri_model->insert($data_update);
        }



        $AGAMA_ID_NEW = trim($this->input->post('AGAMA_ID'));
        if((trim($datasekarang->AGAMA_ID) != trim($AGAMA_ID_NEW)) && $AGAMA_ID_NEW != ""){
        	$data_update = array();
        	$data_update['PNS_ID']		= $PNS_ID;
        	$data_update['KOLOM']		= "AGAMA_ID";
        	$data_update['DARI']		= $datasekarang->AGAMA_ID;
        	$data_update['PERUBAHAN']	= $AGAMA_ID_NEW;
        	$data_update['NAMA_KOLOM']	= "AGAMA";
        	$data_update['UPDATE_TGL']	= date("Y-m-d");
        	$data_update['LEVEL_UPDATE']= 3;
			$id_update = $this->update_mandiri_model->insert($data_update);
        }

        // $NIK_NEW = trim($this->input->post('NIK'));
        // if((trim($datasekarang->NIK) != trim($NIK_NEW)) && $NIK_NEW != ""){
        // 	$data_update = array();
        // 	$data_update['PNS_ID']		= $PNS_ID;
        // 	$data_update['KOLOM']		= "NIK";
        // 	$data_update['DARI']		= $datasekarang->NIK;
        // 	$data_update['PERUBAHAN']	= $NIK_NEW;
        // 	$data_update['NAMA_KOLOM']	= "NIK";
        // 	$data_update['UPDATE_TGL']	= date("Y-m-d");
        // 	$data_update['LEVEL_UPDATE']= 3;
		// 	$id_update = $this->update_mandiri_model->insert($data_update);
        // }

		
        /*
        $TK_PENDIDIKAN_NEW = trim($this->input->post('TK_PENDIDIKAN'));
        if((trim($datasekarang->TK_PENDIDIKAN) != trim($TK_PENDIDIKAN_NEW)) && $TK_PENDIDIKAN_NEW != ""){
        	$data_update = array();
        	$data_update['PNS_ID']		= $PNS_ID;
        	$data_update['KOLOM']		= "TK_PENDIDIKAN";
        	$data_update['DARI']		= $datasekarang->TK_PENDIDIKAN;
        	$data_update['PERUBAHAN']	= $TK_PENDIDIKAN_NEW;
        	$data_update['NAMA_KOLOM']	= "TINGKAT PENDIDIKAN";
        	$data_update['UPDATE_TGL']	= date("Y-m-d");
        	$data_update['LEVEL_UPDATE']= 3;
			$id_update = $this->update_mandiri_model->insert($data_update);
        }

        $PENDIDIKAN_ID_NEW = trim($this->input->post('PENDIDIKAN_ID'));
        if((trim($datasekarang->PENDIDIKAN_ID) != trim($PENDIDIKAN_ID_NEW)) && $PENDIDIKAN_ID_NEW != ""){
        	$data_update = array();
        	$data_update['PNS_ID']		= $PNS_ID;
        	$data_update['KOLOM']		= "PENDIDIKAN_ID";
        	$data_update['DARI']		= $datasekarang->PENDIDIKAN_ID;
        	$data_update['PERUBAHAN']	= $PENDIDIKAN_ID_NEW;
        	$data_update['NAMA_KOLOM']	= "PENDIDIKAN";
        	$data_update['UPDATE_TGL']	= date("Y-m-d");
        	$data_update['LEVEL_UPDATE']= 3;
			$id_update = $this->update_mandiri_model->insert($data_update);
        }
        */
        $JENIS_KAWIN_ID_NEW = trim($this->input->post('JENIS_KAWIN_ID'));
        if((trim($datasekarang->JENIS_KAWIN_ID) != trim($JENIS_KAWIN_ID_NEW)) && $JENIS_KAWIN_ID_NEW != ""){
        	
        	$data_update = array();
        	$data_update['PNS_ID']		= $PNS_ID;
        	$data_update['KOLOM']		= "JENIS_KAWIN_ID";
        	$data_update['DARI']		= $datasekarang->JENIS_KAWIN_ID;
        	$data_update['PERUBAHAN']	= $JENIS_KAWIN_ID_NEW;
        	$data_update['NAMA_KOLOM']	= "JENIS_KAWIN_ID";
        	$data_update['LEVEL_UPDATE']= 3;
        	$data_update['UPDATE_TGL']	= date("Y-m-d");
			$id_update = $this->update_mandiri_model->insert($data_update);
        }
        // update yana
        //$data['TMT_PENSIUN']	= $this->input->post('TMT_PENSIUN') ? $this->input->post('TMT_PENSIUN') : null;
		$data['EMAIL']				= $this->input->post('EMAIL') ? $this->input->post('EMAIL') : null;
		$data['ALAMAT']				= $this->input->post('ALAMAT') ? $this->input->post('ALAMAT') : null;
		$data['NOMOR_HP']			= $this->input->post('NOMOR_HP') ? $this->input->post('NOMOR_HP') : null;
		$data['KARTU_PEGAWAI']		= $this->input->post('KARTU_PEGAWAI') ? $this->input->post('KARTU_PEGAWAI') : null;
		$data['NPWP']				= $this->input->post('NPWP') ? $this->input->post('NPWP') : null;
		$data['KK']					= $this->input->post('KK') ? $this->input->post('KK') : null;
		$data['LOKASI_KERJA']		= $this->input->post('LOKASI_KERJA') ? $this->input->post('LOKASI_KERJA') : null;
		$data['NIK']				= $this->input->post('NIK') ? $this->input->post('NIK') : null;
		$data['TGL_NPWP']			= $this->input->post('TGL_NPWP') ? $this->input->post('TGL_NPWP') : null;

		$data['BPJS']				= $this->input->post('BPJS') ? $this->input->post('BPJS') : null;
		$data['NO_TASPEN']			= $this->input->post('NO_TASPEN') ? $this->input->post('NO_TASPEN') : null;
		$data['AKTE_KELAHIRAN']		= $this->input->post('AKTE_KELAHIRAN') ? $this->input->post('AKTE_KELAHIRAN') : null;
		
		$data['NO_BEBAS_NARKOBA']	= $this->input->post('NO_BEBAS_NARKOBA') ? $this->input->post('NO_BEBAS_NARKOBA') : null;
		$data['TGL_BEBAS_NARKOBA']	= $this->input->post('TGL_BEBAS_NARKOBA') ? $this->input->post('TGL_BEBAS_NARKOBA') : null;
		$data['TGL_BEBAS_NARKOBA']	= $this->input->post('TGL_BEBAS_NARKOBA') ? $this->input->post('TGL_BEBAS_NARKOBA') : null;

		$data['NO_CATATAN_POLISI']	= $this->input->post('NO_CATATAN_POLISI') ? $this->input->post('NO_CATATAN_POLISI') : null;
		$data['TGL_CATATAN_POLISI']	= $this->input->post('TGL_CATATAN_POLISI') ? $this->input->post('TGL_CATATAN_POLISI') : null;

		$data['NO_SURAT_DOKTER']	= $this->input->post('NO_SURAT_DOKTER') ? $this->input->post('NO_SURAT_DOKTER') : null;
		$data['TGL_SURAT_DOKTER']	= $this->input->post('TGL_SURAT_DOKTER') ? $this->input->post('TGL_SURAT_DOKTER') : null;

		//$data['JENIS_KAWIN_ID']		= $this->input->post('JENIS_KAWIN_ID') ? $this->input->post('JENIS_KAWIN_ID') : null;
		
		
		$data['TGL_MENINGGAL']		= $this->input->post('TGL_MENINGGAL') ? $this->input->post('TGL_MENINGGAL') : null;
		
		$data['NOMOR_DARURAT']	= $this->input->post('NOMOR_DARURAT') ? $this->input->post('NOMOR_DARURAT') : null;
		$data['EMAIL_DIKBUD']		= $this->input->post('EMAIL_DIKBUD') ? $this->input->post('EMAIL_DIKBUD') : null;
		$data['LOKASI_KERJA_ID']		= $this->input->post('LOKASI_KERJA_ID') ? $this->input->post('LOKASI_KERJA_ID') : null;

        
		$data['UPDATED_DATE']	= date("Y-m-d");
        $data['UPDATED_BY']		= $this->auth->user_id();

        $result = $this->pegawai_model->update($id_data,$data);
        if($result){
 	       	$response ['success']= true;
    		$response ['msg']= "Update berhasil";
    		log_activity($this->auth->user_id(), "Update data mandiri dari ".$this->input->ip_address().json_encode($data), 'pegawai');
    	}
        echo json_encode($response);    

    }
    public function verifikasiupdatemandiri(){
         // Validate the data
    	date_default_timezone_set("Asia/Bangkok");
    	$this->auth->restrict($this->permissionUpdateMandiri);
    	$this->load->model('pegawai/update_mandiri_model');
    	if (!$this->input->is_ajax_request()) {
   			die("Only ajax request");
		}
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        $id_data = $this->input->post('kode');
        $status_update 	= $this->input->post('valstatus_update');
        $dataupdate 	= $this->update_mandiri_model->find($id_data);
        $PERUBAHAN 		= isset($dataupdate->PERUBAHAN) ? TRIM($dataupdate->PERUBAHAN) : "";
        $PNS_ID 		= isset($dataupdate->PNS_ID) ? TRIM($dataupdate->PNS_ID) : "";
        // GET DATA PEGAWAI 
        //$datasekarang = $this->pegawai_model->find($id_data);
        //$PNS_ID = $datasekarang->PNS_ID;
        //$data = $this->pegawai_model->prep_data($this->input->post()); 
        $kolomupdate = isset($dataupdate->KOLOM) ? trim($dataupdate->KOLOM) : "";
        if($kolomupdate == "GOL_AWAL_ID")
        	$kolomupdate = "GOL_ID";
        
    	$data_update = array();
    	$data_update['VERIFIKASI_BY']	= $this->auth->user_id();
    	$data_update['VERIFIKASI_TGL']	= date("Y-m-d");
    	$data_update['STATUS']			= $status_update;
		$result = $this->update_mandiri_model->update($id_data,$data_update);

		if($status_update == "1"){
			$data_pegawai = array();
			$data_pegawai['UPDATED_DATE']	= date("Y-m-d");
        	$data_pegawai['UPDATED_BY']		= $this->auth->user_id();
			$data_pegawai[$kolomupdate]		= $PERUBAHAN ? TRIM($PERUBAHAN) : null;
    		$result = $this->pegawai_model->update_where("PNS_ID",$PNS_ID,$data_pegawai);
		}
        if($result){
 	       	$response ['success']= true;
    		$response ['msg']= "Update berhasil";
    	}
        echo json_encode($response);    

    }
    public function profile($id='')
    {
    	
    	Template::set('collapse', true);
    	if($this->auth->role_id() == "2"){
    		$pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($this->auth->username()));
    		$id = isset($pegawai->ID) ? $pegawai->ID : "";
    		//die($id." masuk");
    	}
    	 
        if (empty($id)) {
            $pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($this->auth->username()));
    		$id = isset($pegawai->ID) ? $pegawai->ID : "";
    		if($id == ""){
	            Template::set_message(lang('pegawai_invalid_id'), 'error');
            	redirect(SITE_AREA . '/kepegawaian/pegawai');
            }
        }
        
        $this->load->library('convert');
 		
        $pegawai = $this->pegawai_model->find_detil($id);
        Template::set('pegawai', $pegawai);
        Template::set('PNS_ID', $pegawai->PNS_ID);
		// lokasi kerja
		// cek dari riwayat golongan terakhir
		// jika golongan riwayat lebih tinggi dengan golongan yang di pegawai maka pakai golongan yang di riwayat
		$this->load->model('pegawai/riwayat_kepangkatan_model');
		$this->riwayat_kepangkatan_model->order_by("TMT_GOLONGAN","desc");
        $this->riwayat_kepangkatan_model->where("PNS_ID",$pegawai->PNS_ID);  
        $recordrwtpangakats = $this->riwayat_kepangkatan_model->limit(1)->find_all();
        $golonganriwayat = isset($recordrwtpangakats[0]->ID_GOLONGAN) ? trim($recordrwtpangakats[0]->ID_GOLONGAN) : "";
        $gol_id = $pegawai->GOL_ID;
        if((int)$golonganriwayat > (int)$pegawai->GOL_ID){
        	$gol_id = $golonganriwayat;
        }
        // end riwayat golongan terakhir
        
		
		$recgolongan = $this->golongan_model->find(trim($gol_id));
		Template::set('GOLONGAN_AKHIR', $recgolongan->NAMA);
		
		$gol_awal_id = $pegawai->GOL_AWAL_ID;
		$recgolongan = $this->golongan_model->find($gol_awal_id);
		Template::set('GOLONGAN_AWAL', $recgolongan->NAMA);
		
		$jenis_jabatan = $pegawai->JENIS_JABATAN_ID;
		$recjenis_jabatan = $this->jenis_jabatan_model->find($jenis_jabatan);
		Template::set('JENIS_JABATAN', $recjenis_jabatan->NAMA);
		
		$JABATAN_ID = $pegawai->JABATAN_INSTANSI_ID;
		$recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_ID));
		Template::set('NAMA_JABATAN', $recjabatan->NAMA_JABATAN);
		 
		$unor = $this->unitkerja_model->find_by("ID",trim($pegawai->UNOR_ID));
		Template::set('nama_unor',$unor->NAMA_UNOR);
		$unor_induk = $this->unitkerja_model->find_by("ID",$unor->DIATASAN_ID);
		Template::set('unor_induk',$unor_induk->NAMA_UNOR);
		
		Template::set("parent_path_array_unor",$this->unitkerja_model->get_parent_path($unor->ID,true,true));
        Template::set('toolbar_title',"Lihat Profile");
		//Template::set('view_back_button',true);
		Template::set('back_button_label',"<< Kembali ke Daftar Pegawai");
		Template::set('back_button_url',base_url("admin/kepegawaian/pegawai"));
        Template::render();
    }

    public function profile2($id='')
    {
    	
    	$this->load->model('pegawai/Pns_aktif_model');
    	$this->load->library('convert');
    	Template::set('collapse', true);
    	if($this->auth->role_id() == "2"){
    		$pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($this->auth->username()));
    		$id = isset($pegawai->ID) ? $pegawai->ID : "";
    		//die($id." masuk");
    	}
    	 
        if (empty($id)) {
            $pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($this->auth->username()));
    		$id = isset($pegawai->ID) ? $pegawai->ID : "";
    		if($id == ""){
	            Template::set_message(lang('pegawai_invalid_id'), 'error');
            	redirect(SITE_AREA . '/kepegawaian/pegawai');
            }
        }
        

       	$recpns_aktif = $this->Pns_aktif_model->find($id);
 		Template::set('recpns_aktif', $recpns_aktif);

        $pegawai = $this->pegawai_model->find_detil($id);
		print_r($pegawai);
		die();
        Template::set('pegawai', $pegawai);
        Template::set('PNS_ID', $pegawai->PNS_ID);

        Template::set('selectedTempatLahirPegawai',$this->lokasi_model->find($pegawai->TEMPAT_LAHIR_ID));

		// cek dari riwayat golongan terakhir
		// jika golongan riwayat lebih tinggi dengan golongan yang di pegawai maka pakai golongan yang di riwayat
		$this->load->model('pegawai/riwayat_kepangkatan_model');
		$this->riwayat_kepangkatan_model->order_by("TMT_GOLONGAN","desc");
        $this->riwayat_kepangkatan_model->where("PNS_ID",$pegawai->PNS_ID);  
        $recordrwtpangakats = $this->riwayat_kepangkatan_model->limit(1)->find_all();
        $golonganriwayat = isset($recordrwtpangakats[0]->ID_GOLONGAN) ? trim($recordrwtpangakats[0]->ID_GOLONGAN) : "";
        $gol_id = $pegawai->GOL_ID;
        if((int)$golonganriwayat > (int)$pegawai->GOL_ID){
        	$gol_id = $golonganriwayat;
        }
        // end riwayat golongan terakhir
        
		if($gol_id != ""){
			$recgolongan = $this->golongan_model->find(trim($gol_id));
			Template::set('GOLONGAN_AKHIR', $recgolongan->NAMA);
			Template::set('NAMA_PANGKAT', $recgolongan->NAMA_PANGKAT);
			
		}
		$gol_awal_id = $pegawai->GOL_AWAL_ID;
		if($gol_awal_id != ""){
			$recgolongan = $this->golongan_model->find($gol_awal_id);
			Template::set('GOLONGAN_AWAL', $recgolongan->NAMA);
		}
		$jenis_jabatan = $pegawai->JENIS_JABATAN_ID;
		if($jenis_jabatan != ""){
			$recjenis_jabatan = $this->jenis_jabatan_model->find($jenis_jabatan);
			Template::set('JENIS_JABATAN', $recjenis_jabatan->NAMA);
		}
		$JABATAN_ID = $pegawai->JABATAN_INSTANSI_ID;
		if($JABATAN_ID != ""){
			$recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_ID));
			Template::set('NAMA_JABATAN', $recjabatan->NAMA_JABATAN);
		}

		$unor = $this->unitkerja_model->find_by("ID",trim($pegawai->UNOR_ID));
		Template::set('nama_unor',$unor->NAMA_UNOR);
		$unor_induk = $this->unitkerja_model->find_by("ID",$unor->DIATASAN_ID);
		Template::set('unor_induk',$unor_induk->NAMA_UNOR);
		
		Template::set('selectedPendidikanID',$this->pendidikan_model->find(trim($pegawai->PENDIDIKAN_ID)));
		
		Template::set("parent_path_array_unor",$this->unitkerja_model->get_parent_path($unor->ID,true,true));
        Template::set('toolbar_title',"Lihat Profile");
		//Template::set('view_back_button',true);
		Template::set('back_button_label',"<< Kembali ke Daftar Pegawai");
		Template::set('back_button_url',base_url("admin/kepegawaian/pegawai"));
        Template::render();
    }
    public function profilen($id='')
    {	
    	$id = (int)base64_decode(urldecode($id));
    	
		$this->load->model('pegawai/Pns_aktif_model');
    	$this->load->model('pegawai/Riwayat_pns_cpns_model');
		

    	$model = $this->uri->segment(6);
    	$this->load->library('convert');
    	Template::set('collapse', true);
    	if(!$this->auth->has_permission($this->permissionViewProfileOther)){
    		$pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($this->auth->username()));
    		$id = isset($pegawai->ID) ? $pegawai->ID : "";
    	}
        if (empty($id)) {
            $pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($this->auth->username()));
    		$id = isset($pegawai->ID) ? $pegawai->ID : "";
    		if($id == ""){
	            Template::set_message(lang('pegawai_invalid_id'), 'error');
            	redirect(SITE_AREA . '/kepegawaian/pegawai');
            }
        }
       	$recpns_aktif = $this->Pns_aktif_model->find($id);
 		Template::set('recpns_aktif', $recpns_aktif);
 		// die($id."ini");
        $pegawai = $this->pegawai_model->find_detil($id);
        $foto_pegawai = trim($this->settings_lib->item('site.urlphoto'))."noimage.jpg";
		if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO)){
			$foto_pegawai =  trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO;
		}
		Template::set('foto_pegawai', $foto_pegawai);
        Template::set('pegawai', $pegawai);
        Template::set('PNS_ID', $pegawai->PNS_ID);
        Template::set('NO_KARPEG', $pegawai->KARTU_PEGAWAI);

        Template::set('selectedTempatLahirPegawai',$this->lokasi_model->find($pegawai->TEMPAT_LAHIR_ID));
        Template::set('selectedLokasiPegawai',$this->lokasi_model->find($pegawai->LOKASI_KERJA_ID));
		// cek dari riwayat golongan terakhir
		// jika golongan riwayat lebih tinggi dengan golongan yang di pegawai maka pakai golongan yang di riwayat
		$this->load->model('pegawai/riwayat_kepangkatan_model');
		$this->riwayat_kepangkatan_model->order_by("TMT_GOLONGAN","desc");
        $this->riwayat_kepangkatan_model->where("PNS_ID",$pegawai->PNS_ID);  
        $recordrwtpangakats = $this->riwayat_kepangkatan_model->limit(1)->find_all();
        $golonganriwayat = isset($recordrwtpangakats[0]->ID_GOLONGAN) ? trim($recordrwtpangakats[0]->ID_GOLONGAN) : "";
        $gol_id = $pegawai->GOL_ID;

        if((int)$golonganriwayat > (int)$pegawai->GOL_ID){
        	if($gol_id == ""){
        		$gol_id = $golonganriwayat;
        	}
        }
        // end riwayat golongan terakhir
        
		if($gol_id != ""){
			$recgolongan = $this->golongan_model->find(trim($gol_id));
			$namagolongan = $pegawai->KEDUDUKAN_HUKUM_ID == 71 ? $recgolongan->GOL_PPPK : $recgolongan->NAMA;
			Template::set('GOLONGAN_AKHIR', $namagolongan);
			Template::set('NAMA_PANGKAT', $recgolongan->NAMA_PANGKAT);
			
		}
		$gol_awal_id = $pegawai->GOL_AWAL_ID;
		if($gol_awal_id != ""){
			$recgolongan = $this->golongan_model->find($gol_awal_id);
			$namagolongan = $pegawai->KEDUDUKAN_HUKUM_ID == 71 ? $recgolongan->GOL_PPPK : $recgolongan->NAMA;
			Template::set('GOLONGAN_AWAL', $namagolongan);
		}
		
		$JABATAN_ID = $pegawai->JABATAN_INSTANSI_ID;
		$JABATAN_INSTANSI_REAL_ID = $pegawai->JABATAN_INSTANSI_REAL_ID;
		$recjabatan = null;
		$jenis_jabatan = null;
		if($JABATAN_INSTANSI_REAL_ID != ""){
			$recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_INSTANSI_REAL_ID));
			Template::set('NAMA_JABATAN_REAL', $recjabatan->NAMA_JABATAN);
			$jenis_jabatan = isset($recjabatan->JENIS_JABATAN) ? $recjabatan->JENIS_JABATAN : "";
			
		}
		if($jenis_jabatan != ""){
			$recjenis_jabatan = $this->jenis_jabatan_model->find($jenis_jabatan);
			Template::set('JENIS_JABATAN', $recjenis_jabatan->NAMA);
		}
		if($JABATAN_ID != ""){
			$recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_ID));
			Template::set('NAMA_JABATAN', $recjabatan->NAMA_JABATAN);
			Template::set('NAMA_JABATAN_BKN', $recjabatan->NAMA_JABATAN_BKN);
		}
		
		$unor = $this->unitkerja_model->find_by("ID",trim($pegawai->UNOR_ID));
		$pemimpin_pns_id = isset($unor->PEMIMPIN_PNS_ID) ? $unor->PEMIMPIN_PNS_ID : "";
		$nama_jabatan_struktural = isset($unor->NAMA_JABATAN) ? $unor->NAMA_JABATAN : "";
		if($pemimpin_pns_id == $pegawai->PNS_ID && $pegawai->JENIS_JABATAN_ID == "1")
		{
			Template::set('NAMA_JABATAN_REAL', $nama_jabatan_struktural);
			Template::set('JENIS_JABATAN', "Struktural");
			
		}
		Template::set('nama_unor',$unor->NAMA_UNOR);
		$unor_induk = $this->unitkerja_model->find_by("ID",$unor->DIATASAN_ID);
		Template::set('unor_induk',$unor_induk->NAMA_UNOR);

		$this->Riwayat_pns_cpns_model->order_by("ID","DESC");
		$riwayat_pns_cpns = $this->Riwayat_pns_cpns_model->find_by("PNS_NIP",$pegawai->NIP_BARU);
		Template::set('riwayat_pns_cpns',$riwayat_pns_cpns);
		
		Template::set('selectedPendidikanID',$this->pendidikan_model->find(trim($pegawai->PENDIDIKAN_ID)));
		
		Template::set("parent_path_array_unor",$this->unitkerja_model->get_parent_path($unor->ID,true,true));
        Template::set('toolbar_title',"Profile Pegawai");
		Template::set('model',$model);
		Template::set('back_button_label',"<< Kembali ke Daftar Pegawai");
		Template::set('back_button_url',base_url("admin/kepegawaian/pegawai"));


		/**
		 * REZA WAS HERE
		 */
		
		Template::set('jenis_diklat_siasn', '[{"id":"1","jenis_diklat":"Diklat Struktural"},{"id":"2","jenis_diklat":"Diklat Fungsional"},{"id":"3","jenis_diklat":"Diklat Teknis"},{"id":"4","jenis_diklat":"Workshop"},{"id":"5","jenis_diklat":"Pelatihan Manajerial"},{"id":"6","jenis_diklat":"Pelatihan Sosial Kultural"},{"id":"7","jenis_diklat":"Sosialisasi"},{"id":"8","jenis_diklat":"Bimbingan Teknis"},{"id":"9","jenis_diklat":"Seminar"},{"id":"10","jenis_diklat":"Magang"},{"id":"11","jenis_diklat":"Kursus"},{"id":"12","jenis_diklat":"Penataran"},{"id":"13","jenis_diklat":"Pengembangan Kompetensi Dalam Bentuk Pelatihan klasikal lainnya"},{"id":"14","jenis_diklat":"Coaching"},{"id":"15","jenis_diklat":"Mentoring"},{"id":"16","jenis_diklat":"E-Learning"},{"id":"17","jenis_diklat":"Pelatihan Jarak Jauh"},{"id":"18","jenis_diklat":"Detasering (Secondment)"},{"id":"19","jenis_diklat":"Pembelajaran Alam Terbuka (Outbond)"},{"id":"20","jenis_diklat":"Patok Banding (Benchmarking)"},{"id":"21","jenis_diklat":"Pertukaran Antara Pns Dengan Pegawai Swasta/Badan Usaha Milik Negara/ Badan Usaha Milik Daerah"},{"id":"22","jenis_diklat":"Belajar Mandiri (Self Development)"},{"id":"23","jenis_diklat":"Komunitas Belajar (Community Of Practices)"},{"id":"24","jenis_diklat":"Bimbingan Di Tempat Kerja"},{"id":"25","jenis_diklat":"Pengembangan Kompetensi Dalam Bentuk Pelatihan Nonklasikal Lainnya"}]');
		Template::set('rumpun_diklat','[{"id":"0609db7b-7a0b-4107-b8e1-ffde1a4ed30e","nama":"TENAGA KEPENDIDIKAN LAINNYA","urusan":"WAJIB","pelayanan_dasar":"true","peraturan_id":"7baee56d-33e5-42e1-901f-29eade75008f","keterangan":""},{"id":"0b69a9bc-9ba8-4fab-8a06-2d051faa40f8","nama":"KEAGAMAAN DAN PENDIDIKAN","urusan":"PILIHAN","pelayanan_dasar":"true","peraturan_id":"a16ea752-772c-43d1-b914-2772fc46f85f","keterangan":""},{"id":"0e34d390-28c3-402c-b615-547fdfc114f3","nama":"PENDIDIKAN TINGKAT TAMAN KANAK-KANAK, DASAR, LANJUTAN, DAN SEKOLAH KHUSUS","urusan":"WAJIB","pelayanan_dasar":"true","peraturan_id":"f09048f0-68b7-4bd7-beda-9b6fdc17c32f","keterangan":""},{"id":"12916fd6-2a22-43bd-b5eb-6769dc51378d","nama":"KEBUDAYAAN","urusan":"WAJIB","pelayanan_dasar":"false","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"12efe78f-a4fc-4594-b935-62662038fb58","nama":"HUKUM DAN PERADILAN","urusan":"WAJIB","pelayanan_dasar":"true","peraturan_id":"2dcd6120-ba4a-438a-a65a-266997edd627","keterangan":""},{"id":"16c86621-a4ce-433c-b42e-be16883f79e3","nama":"PENDIDIKAN LAINNYA","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"e092a867-729d-457a-b3df-4e7b8232184d","keterangan":""},{"id":"1e4cf7c1-5198-43d3-8075-42ad94916290","nama":"KEKOMPUTERAN","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"04cd530f-619b-4f6a-a356-fa509d0c3aa6","keterangan":""},{"id":"22f155e1-8ff0-4c17-aef3-365aca514c62","nama":"ARSITEK, INSINYUR DAN YANG BERKAITAN","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"c01884ac-4656-4ea8-bccd-5f6fa17c626d","keterangan":""},{"id":"23480eea-3ebd-4659-92c1-e42916b00a35","nama":"ADMINISTRASI KEPENDUDUKAN DAN PENCATATAN SIPIL","urusan":"WAJIB","pelayanan_dasar":"false","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"23861db6-f6fc-4008-b83d-53e45399710a","nama":"KEARSIPAN","urusan":"WAJIB","pelayanan_dasar":"false","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"25b6cafa-6a6d-475a-b391-b5fb485c384e","nama":"PENDIDIKAN","urusan":"WAJIB","pelayanan_dasar":"true","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"260eacd9-4435-457d-9763-853727783577","nama":"AKUNTAN DAN ANGGARAN","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"972c0df1-270a-478d-a159-8877dc03a86b","keterangan":""},{"id":"265b5554-2210-4760-a1da-27ac19f116e3","nama":"PENANAMAN MODAL","urusan":"WAJIB","pelayanan_dasar":"false","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"2a87fb94-662e-44a3-b7d9-a169f9214e6f","nama":"TENAGA KERJA","urusan":"WAJIB","pelayanan_dasar":"false","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"2e4f85c0-e028-4b0b-80c6-1ed3a7fecb2e","nama":"IMIGRASI, PAJAK, DAN ASISTEN PROFESIONAL YANG BERKAITAN ","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"713c7a31-0058-4c37-b3da-d74514bd82cd","keterangan":""},{"id":"2eea7480-2c18-4619-a27c-c3f831b4867d","nama":"PENERANGAN DAN SENI BUDAYA","urusan":"WAJIB","pelayanan_dasar":"false","peraturan_id":"d2b34b0f-8984-4e74-84a5-736f9489dd62","keterangan":""},{"id":"346c4008-bc44-4e81-abaf-f79b205dbc10","nama":"Ekonomi dan Keuangan","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"83567f4d-6fea-4418-b6d7-97fe87db9791","keterangan":""},{"id":"37d048a6-c73f-4aa7-998c-831bd3d0b0c4","nama":"MATEMATIKA, STATISTIKA, DAN YANG BERKAITAN","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"8ae629ea-775c-424a-a18a-4d0cd1a11c71","keterangan":""},{"id":"3ed8b5dd-6ea9-467f-8e45-37f5e82cd6b0","nama":"Sains dan Lingkungan","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"83567f4d-6fea-4418-b6d7-97fe87db9791","keterangan":""},{"id":"40dd32df-8e79-4637-ad2e-e8fe408de9fa","nama":"MANAJEMEN","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"ae31c6d7-ac1d-4fbf-a1bf-f5a756d1fca9","keterangan":""},{"id":"41391837-9ea0-422f-a957-1bafa4c0f761","nama":"PARIWISATA","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"49b2de6d-f04e-4818-9017-9ae2bb8d7254","nama":"PERINDUSTRIAN","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"4af45dce-a4e0-46d1-b6af-ebe10de3af4b","nama":"KEPEMUDAAN DAN OLAHRAGA","urusan":"WAJIB","pelayanan_dasar":"false","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"555d45e6-8061-41cf-aca8-ab4e1d148f2b","nama":"Administrasi","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"83567f4d-6fea-4418-b6d7-97fe87db9791","keterangan":""},{"id":"59d0253f-bfd9-4167-a5b0-a2fed482dbe8","nama":"FISIKA, KIMIA DAN YANG BERKAITAN","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"2d5914bc-7ebe-477b-b968-9da853880062","keterangan":""},{"id":"67a7c89b-4e09-4320-86fc-837ed44d5fce","nama":"STATISTIK","urusan":"WAJIB","pelayanan_dasar":"false","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"682d9658-a1ff-4db7-8f3a-a123c2daaa16","nama":"PROFESIONAL BIDANG PENDIDIKAN LAINNYA","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"670494fe-ae71-4554-bda9-99c9d1f7a523","keterangan":""},{"id":"68c4919f-b5f6-4fce-b9ab-00b370e567c5","nama":"KOMUNIKASI DAN INFORMATIKA","urusan":"WAJIB","pelayanan_dasar":"false","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"6e0d3bd1-4885-4130-9b37-1e8592e6deaf","nama":"ENERGI DAN SUMBER DAYA MINERAL","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"73e44ee6-7f95-4b69-8871-f6690e3f71bf","nama":"KOPERASI USAHA KECIL DAN MENENGAH","urusan":"WAJIB","pelayanan_dasar":"false","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"76880bfc-80bb-4a81-8e12-c1b6e8f9f676","nama":"Penelitian","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"83567f4d-6fea-4418-b6d7-97fe87db9791","keterangan":""},{"id":"896be0aa-610f-4729-8434-3707d030761e","nama":"PEKERJAAN UMUM DAN PENATAAN RUANG","urusan":"WAJIB","pelayanan_dasar":"true","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"8a69e81b-a767-4ea3-9e40-f647896963d0","nama":"PEMBERDAYAAN PEREMPUAN DAN PERLINDUNGAN ANAK","urusan":"WAJIB","pelayanan_dasar":"false","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"9069160f-184b-41d8-9ac3-4e3c0d81d817","nama":"ARSIPARIS, PUSTAKAWAN, DAN YANG BERKAITAN ","urusan":"WAJIB","pelayanan_dasar":"false","peraturan_id":"5e8d9d2b-a020-408d-85fd-c597e5c4ac8d","keterangan":""},{"id":"937dcd82-c431-4022-b248-4d1ae80de871","nama":"PENGAWAS KUALITAS DAN KEAMANAN","urusan":"WAJIB","pelayanan_dasar":"true","peraturan_id":"eab89aa2-f7b7-4647-981f-e905f02f14e6","keterangan":""},{"id":"95f10610-289d-44b3-8cce-9a69717c05a4","nama":"KEAGAMAAN","urusan":"PILIHAN","pelayanan_dasar":"true","peraturan_id":"f861a47f-4d15-4c18-b6dc-4fd2a5154e49","keterangan":""},{"id":"96cda2c7-6e75-4e3e-9dbb-2dbbe6c36ac5","nama":"KEHUTANAN","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"9b2ee7d9-d94d-42d7-be94-ab5d3d95d549","nama":"PANGAN","urusan":"WAJIB","pelayanan_dasar":"false","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"9fe3de73-08cb-4347-87d0-ee0b93c87571","nama":"PENELITIAN DAN PEREKAYASAAN","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"d63926ae-3135-4bfd-b04c-8cc8b6f1b41d","keterangan":""},{"id":"a2635ed8-950d-463b-8c91-880b8da1c7a0","nama":"KELAUTAN DAN PERIKANAN","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"a2c77fd3-7b7c-45b2-a31c-07b191941a6a","nama":"KESEHATAN","urusan":"WAJIB","pelayanan_dasar":"true","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"aa4c2c50-8175-4e99-a888-105073559583","nama":"ASISTEN PROFESIONAL YANG BERHUBUNGAN DENGAN KEUANGAN DAN PENJUALAN","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"e2b90ed1-3e53-4362-8eb5-83f7bf181aa6","keterangan":""},{"id":"ac5b7383-f3a2-4f6d-a421-283b861294a5","nama":"HAK CIPTA, PATEN, DAN MEREK","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"1442b964-8c10-455e-9b31-df53ad2bc363","keterangan":""},{"id":"ae754c26-6269-4201-81f1-e60c5beaa15e","nama":"ILMU SOSIAL DAN YANG BERKAITAN","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"c5ed4868-87c1-43ec-bad6-b7c953cbf186","keterangan":""},{"id":"bba1c348-51d7-4a8c-882f-f1c618f38f43","nama":"Hukum, Politik dan Pemerintahan","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"83567f4d-6fea-4418-b6d7-97fe87db9791","keterangan":""},{"id":"bef48dad-8a72-4fbb-94e2-0a4a8c9e4849","nama":"KETENTERAMAN DAN KETERTIBAN UMUM SERTA PERLINDUNGAN MASYARAKAT","urusan":"WAJIB","pelayanan_dasar":"true","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"c01c266d-f10c-4c77-a46b-516819cdeaac","nama":"PERDAGANGAN","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"c10abe3d-8b51-403e-bfd2-54d10032a7b3","nama":"PERTANAHAN","urusan":"WAJIB","pelayanan_dasar":"false","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"c216b512-adff-4054-935d-4ab2af4ed93f","nama":"PEMBERDAYAAN MASYARAKAT DAN DESA","urusan":"WAJIB","pelayanan_dasar":"false","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"c60badce-ff96-4336-9e87-5682c10bc03f","nama":"DETEKTIF DAN PENYIDIK","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"ae779299-80fe-47c7-9149-f9e8d4c31e80","keterangan":""},{"id":"c8ded619-9e79-44b6-8953-ff348b4b1802","nama":"KESEHATAN DAN/ATAU ILMU SOSIAL","urusan":"WAJIB","pelayanan_dasar":"false","peraturan_id":"54be4e8e-cacb-4fb0-8b0f-79aa518870f3","keterangan":""},{"id":"ca27614c-53ce-4b1a-952d-4dc887a12bda","nama":"PERPUSTAKAAN","urusan":"WAJIB","pelayanan_dasar":"false","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"cbe243ce-b520-4c42-a4d2-42f67bbe1233","nama":"PERTANIAN","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"d349a48b-d178-42b7-936d-4816a1cc21c6","nama":"SDM","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"83567f4d-6fea-4418-b6d7-97fe87db9791","keterangan":""},{"id":"df549366-1ec4-4c42-88fb-fdff28771e3a","nama":"PERHUBUNGAN","urusan":"WAJIB","pelayanan_dasar":"false","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"e97ed59f-0bfb-4009-8aae-0eb48c356400","nama":"PENGENDALIAN PENDUDUK DAN KELUARGA BERENCANA","urusan":"WAJIB","pelayanan_dasar":"false","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"ead81498-b4b6-4c33-9f55-c81efa79780c","nama":"PERSANDIAN","urusan":"WAJIB","pelayanan_dasar":"false","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"f2af6cad-9a28-44ae-9916-4366a6cb27b6","nama":"PERUMAHAN RAKYAT DAN KAWASAN PEMUKIMAN","urusan":"WAJIB","pelayanan_dasar":"true","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"f59cc9533488412b9946207c29ab15c6","nama":"ILMU HAYAT","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"5bb8fbf6-2c35-4f2e-aa33-63afc21f0604","keterangan":""},{"id":"f879075c-293b-4b36-845c-5c03a3db4374","nama":"TRANSMIGRASI","urusan":"WAJIB","pelayanan_dasar":"false","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"fb195e55-d090-4c17-9cb3-c2208fb6b628","nama":"SOSIAL","urusan":"WAJIB","pelayanan_dasar":"true","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"fd95453d-0b75-4117-9eda-c37a560fee31","nama":"LINGKUNGAN HIDUP","urusan":"WAJIB","pelayanan_dasar":"false","peraturan_id":"091ac43f-bf8e-4345-b090-dbcd8bf8da23","keterangan":""},{"id":"ff94ac2b-5ac5-447f-81fc-871413390589","nama":"POLITIK DAN HUBUNGAN LUAR NEGERI","urusan":"PILIHAN","pelayanan_dasar":"false","peraturan_id":"4d52dc90-f80f-4b92-bb17-0559025162ff","keterangan":""}]');
		Template::set('diklat_struktural', '[{"id":"1","nama":"SEPADA","eselon_level":"5","ncsistime":"2011-06-19T03:40:50Z","struktural_pns":"1"},{"id":"2","nama":"SEPALA/ADUM/DIKLAT PIM TK.IV","eselon_level":"4","ncsistime":"2011-06-19T03:40:50Z","struktural_pns":"1"},{"id":"3","nama":"SEPADYA/SPAMA/DIKLAT PIM TK. III","eselon_level":"3","ncsistime":"2011-06-19T03:40:50Z","struktural_pns":"1"},{"id":"4","nama":"SPAMEN/SESPA/SESPANAS/DIKLAT PIM TK. II","eselon_level":"2","ncsistime":"2011-06-19T03:40:50Z","struktural_pns":"1"},{"id":"5","nama":"SEPATI/DIKLAT PIM TK. I","eselon_level":"1","ncsistime":"2011-06-19T03:40:50Z","struktural_pns":"1"},{"id":"6","nama":"SESPIM","eselon_level":"","ncsistime":"2019-04-04T10:00:08Z","struktural_pns":""},{"id":"7","nama":"SESPATI","eselon_level":"","ncsistime":"2019-04-04T10:00:16Z","struktural_pns":""},{"id":"8","nama":"Diklat Struktural Lainnya","eselon_level":"","ncsistime":"2019-04-04T18:39:23Z","struktural_pns":""}]');
		$tahun = (int)date("Y");
		// get data dari server SKP
		// $this->generateskp(TRIM($pegawai->NIP_BARU),$tahun-1);
		// $this->generateskp(TRIM($pegawai->NIP_BARU),$tahun-2);
        Template::render();
    }
    public function profileppnpn($id='')
    {
    	
    	$this->load->model('pegawai/Pns_aktif_model');
    	$this->load->library('convert');
    	Template::set('collapse', true);
    	if($this->auth->role_id() == "2"){
    		$pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($this->auth->username()));
    		$id = isset($pegawai->ID) ? $pegawai->ID : "";
    		//die($id." masuk");
    	}
    	 
        if (empty($id)) {
            $pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($this->auth->username()));
    		$id = isset($pegawai->ID) ? $pegawai->ID : "";
    		if($id == ""){
	            Template::set_message(lang('pegawai_invalid_id'), 'error');
            	redirect(SITE_AREA . '/kepegawaian/pegawai');
            }
        }
        

       	$recpns_aktif = $this->Pns_aktif_model->find($id);
 		Template::set('recpns_aktif', $recpns_aktif);

        $pegawai = $this->pegawai_model->find_detil($id);

        $foto_pegawai = trim($this->settings_lib->item('site.urlphoto'))."noimage.jpg";
		if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO and $pegawai->PHOTO != "")){
			$foto_pegawai =  trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO;
		}
		Template::set('foto_pegawai', $foto_pegawai);
        Template::set('pegawai', $pegawai);
        Template::set('PNS_ID', $pegawai->PNS_ID);

        Template::set('selectedTempatLahirPegawai',$this->lokasi_model->find($pegawai->TEMPAT_LAHIR_ID));

		// cek dari riwayat golongan terakhir
		// jika golongan riwayat lebih tinggi dengan golongan yang di pegawai maka pakai golongan yang di riwayat
		$this->load->model('pegawai/riwayat_kepangkatan_model');
		$this->riwayat_kepangkatan_model->order_by("TMT_GOLONGAN","desc");
        $this->riwayat_kepangkatan_model->where("PNS_ID",$pegawai->PNS_ID);  
        $recordrwtpangakats = $this->riwayat_kepangkatan_model->limit(1)->find_all();
        $golonganriwayat = isset($recordrwtpangakats[0]->ID_GOLONGAN) ? trim($recordrwtpangakats[0]->ID_GOLONGAN) : "";
        $gol_id = $pegawai->GOL_ID;
        if((int)$golonganriwayat > (int)$pegawai->GOL_ID){
        	$gol_id = $golonganriwayat;
        }
        // end riwayat golongan terakhir
        
		if($gol_id != ""){
			$recgolongan = $this->golongan_model->find(trim($gol_id));
			Template::set('GOLONGAN_AKHIR', $recgolongan->NAMA);
			Template::set('NAMA_PANGKAT', $recgolongan->NAMA_PANGKAT);
			
		}
		$gol_awal_id = $pegawai->GOL_AWAL_ID;
		if($gol_awal_id != ""){
			$recgolongan = $this->golongan_model->find($gol_awal_id);
			Template::set('GOLONGAN_AWAL', $recgolongan->NAMA);
		}
		$jenis_jabatan = $pegawai->JENIS_JABATAN_ID;
		if($jenis_jabatan != ""){
			$recjenis_jabatan = $this->jenis_jabatan_model->find($jenis_jabatan);
			Template::set('JENIS_JABATAN', $recjenis_jabatan->NAMA);
		}
		$JABATAN_ID = $pegawai->JABATAN_INSTANSI_ID;
		$JABATAN_INSTANSI_REAL_ID = $pegawai->JABATAN_INSTANSI_REAL_ID;
		if($JABATAN_INSTANSI_REAL_ID != ""){
			$recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_INSTANSI_REAL_ID));
			Template::set('NAMA_JABATAN_REAL', $recjabatan->NAMA_JABATAN);
		}

		if($JABATAN_ID != ""){
			$recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_ID));

			Template::set('NAMA_JABATAN', $recjabatan->NAMA_JABATAN);
			Template::set('NAMA_JABATAN_BKN', $recjabatan->NAMA_JABATAN_BKN);
		}

		$unor = $this->unitkerja_model->find_by("ID",trim($pegawai->UNOR_ID));
		Template::set('nama_unor',$unor->NAMA_UNOR);
		$unor_induk = $this->unitkerja_model->find_by("ID",$unor->DIATASAN_ID);
		Template::set('unor_induk',$unor_induk->NAMA_UNOR);
		
		Template::set('selectedPendidikanID',$this->pendidikan_model->find(trim($pegawai->PENDIDIKAN_ID)));
		
		Template::set("parent_path_array_unor",$this->unitkerja_model->get_parent_path($unor->ID,true,true));
        Template::set('toolbar_title',"Profile Pegawai");
		//Template::set('view_back_button',true);
		Template::set('back_button_label',"<< Kembali ke Daftar Pegawai");
		Template::set('back_button_url',base_url("admin/kepegawaian/pegawai"));
        Template::render();
    }
    public function profile_bkn($pns_id='')
    {	
    	$this->load->model('pegawai/Pns_aktif_model');
    	$this->load->model('pegawai/Riwayat_pns_cpns_model');
    	$model = $this->uri->segment(6);
    	$this->load->library('convert');
    	Template::set('collapse', true);

        $pegawai = $this->pegawai_model->find_by("PNS_ID",$pns_id);
		$id = isset($pegawai->ID) ? $pegawai->ID : "";
		$nip_baru = isset($pegawai->NIP_BARU) ? $pegawai->NIP_BARU : "";
		
       	$recpns_aktif = $this->Pns_aktif_model->find($id);
 		Template::set('recpns_aktif', $recpns_aktif);

        $pegawai = $this->pegawai_model->find_detil($id);

        $foto_pegawai = trim($this->settings_lib->item('site.urlphoto'))."noimage.jpg";
		if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO)){
			$foto_pegawai =  trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO;
		}
		$data_utama_pegawai = $this->cache->get('data_utama_'.$nip_baru);
		$data_golongan = $this->cache->get('golongan_'.$nip_baru);
		$data_pasangan = $this->cache->get('data_pasangan_'.$nip_baru);
		$data_penghargaan = $this->cache->get('data_penghargaan_'.$nip_baru);
		$data_skp = $this->cache->get('data_rwt_skp_'.$nip_baru);
		$data_pendidikan = $this->cache->get('rwt_pendidikan_'.$nip_baru);
		$data_jabatan = $this->cache->get('rwt_jabatan_'.$nip_baru);
		$data_diklat = $this->cache->get('rwt_diklat_'.$nip_baru);
		$data_masa_kerja = $this->cache->get('rwt_masakerja_'.$nip_baru);
		$data_pwk = $this->cache->get('rwt_pwk_'.$nip_baru);
		$data_ppo_hist = $this->cache->get('ppo_sk_hist_'.$nip_baru);
		$data_kpo_sk_hist = $this->cache->get('data_kpo_sk_hist_'.$nip_baru);
		$data_hukdis = $this->cache->get('data_rwt_hukdis_'.$nip_baru);
		$data_pns_unor = $this->cache->get('data_rwt_pnsunor_'.$nip_baru);
		$data_anak = $this->cache->get('data_anak_'.$nip_baru);
		$data_ortu = $this->cache->get('data_ortu_'.$nip_baru);
		$data_dp3 = $this->cache->get('data_dp3_'.$nip_baru);

		Template::set('pegawai_bkn', $data_utama_pegawai);
		Template::set('data_golongan', $data_golongan);
		Template::set('data_penghargaan', $data_penghargaan);
		Template::set('data_skp', $data_skp);
		Template::set('data_pasangan', $data_pasangan);
		Template::set('data_pendidikan', $data_pendidikan);
		Template::set('data_jabatan', $data_jabatan);
		Template::set('data_diklat', $data_diklat);
		Template::set('data_masa_kerja', $data_masa_kerja);
		Template::set('data_pwk', $data_pwk);
		Template::set('data_ppo_hist', $data_ppo_hist);
		Template::set('data_kpo_sk_hist', $data_kpo_sk_hist);
		Template::set('data_hukdis', $data_hukdis);
		Template::set('data_pns_unor', $data_pns_unor);
		Template::set('data_anak', $data_anak);
		Template::set('data_dp3', $data_dp3);

		

		Template::set('foto_pegawai', $foto_pegawai);
        Template::set('pegawai', $pegawai);
        Template::set('PNS_ID', $pegawai->PNS_ID);
        Template::set_view('bkn/profile_bkn');

        Template::render();
    }
    public function review($id='')
    {	
    	$this->load->model('pegawai/Pns_aktif_model');
    	$ID2 = $this->uri->segment(6);
    	$this->load->library('convert');
    	Template::set('collapse', true);
    	if(!$this->auth->has_permission($this->permissionViewProfileOther)){
    		$pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($this->auth->username()));
    		$id = isset($pegawai->ID) ? $pegawai->ID : "";
    	}
        if (empty($id)) {
            $pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($this->auth->username()));
    		$id = isset($pegawai->ID) ? $pegawai->ID : "";
    		if($id == ""){
	            $id = "0";
            }
        }

       	$recpns_aktif = $this->Pns_aktif_model->find($id);
 		Template::set('recpns_aktif', $recpns_aktif);

        $pegawai = $this->pegawai_model->find_detil($id);
        Template::set('pegawai', $pegawai);
        $PNS_ID = isset($pegawai->PNS_ID) ? $pegawai->PNS_ID : "";
        $selectedpegawai = $this->pegawai_model->find_by("PNS_ID",trim($PNS_ID));
        Template::set('selectedpegawai',$selectedpegawai);
        // pns 2

        $recpns_aktif2 = $this->Pns_aktif_model->find($ID2);
 		Template::set('recpns_aktif2', $recpns_aktif2);

        $pegawai2 = $this->pegawai_model->find_detil($ID2);
        Template::set('pegawai2', $pegawai2);

        $PNS_ID2 = isset($pegawai2->PNS_ID) ? $pegawai2->PNS_ID : "";
        $selectedpegawai2 = $this->pegawai_model->find_by("PNS_ID",trim($PNS_ID2));
        Template::set('selectedpegawai2',$selectedpegawai2);


        $foto_pegawai = trim($this->settings_lib->item('site.urlphoto'))."noimage.jpg";
		if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO)){
			$foto_pegawai =  trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO;
		}
		Template::set('foto_pegawai', $foto_pegawai);

		$foto_pegawai2 = trim($this->settings_lib->item('site.urlphoto'))."noimage.jpg";
		if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$pegawai2->PHOTO)){
			$foto_pegawai2 =  trim($this->settings_lib->item('site.urlphoto')).$pegawai2->PHOTO;
		}
		Template::set('foto_pegawai2', $foto_pegawai2);
        
        Template::set('PNS_ID', $pegawai->PNS_ID);
        //DIE($pegawai2->NAMA);
        Template::set('selectedTempatLahirPegawai',$this->lokasi_model->find($pegawai->TEMPAT_LAHIR_ID));
        Template::set('selectedTempatLahirPegawai2',$this->lokasi_model->find($pegawai2->TEMPAT_LAHIR_ID));

		// cek dari riwayat golongan terakhir
		// jika golongan riwayat lebih tinggi dengan golongan yang di pegawai maka pakai golongan yang di riwayat
		$this->load->model('pegawai/riwayat_kepangkatan_model');
		$this->riwayat_kepangkatan_model->order_by("TMT_GOLONGAN","desc");
        $this->riwayat_kepangkatan_model->where("PNS_ID",$pegawai->PNS_ID);  
        $recordrwtpangakats = $this->riwayat_kepangkatan_model->limit(1)->find_all();
        $golonganriwayat = isset($recordrwtpangakats[0]->ID_GOLONGAN) ? trim($recordrwtpangakats[0]->ID_GOLONGAN) : "";
        $gol_id = $pegawai->GOL_ID;
        if((int)$golonganriwayat > (int)$pegawai->GOL_ID){
        	$gol_id = $golonganriwayat;
        }
        // end riwayat golongan terakhir
        
		if($gol_id != ""){
			$recgolongan = $this->golongan_model->find(trim($gol_id));
			Template::set('GOLONGAN_AKHIR', $recgolongan->NAMA);
			Template::set('NAMA_PANGKAT', $recgolongan->NAMA_PANGKAT);
			
		}


		$this->riwayat_kepangkatan_model->order_by("TMT_GOLONGAN","desc");
        $this->riwayat_kepangkatan_model->where("PNS_ID",$pegawai2->PNS_ID);  
        $recordrwtpangakats2 = $this->riwayat_kepangkatan_model->limit(1)->find_all();
        $golonganriwayat2 = isset($recordrwtpangakats2[0]->ID_GOLONGAN) ? trim($recordrwtpangakats2[0]->ID_GOLONGAN) : "";
		$gol_id2 = $pegawai2->GOL_ID;
        if((int)$golonganriwayat2 > (int)$pegawai2->GOL_ID){
        	$gol_id2 = $golonganriwayat2;
        }
        // end riwayat golongan terakhir
        
		if($gol_id2 != ""){
			$recgolongan2 = $this->golongan_model->find(trim($gol_id2));
			Template::set('GOLONGAN_AKHIR2', $recgolongan2->NAMA);
			Template::set('NAMA_PANGKAT2', $recgolongan2->NAMA_PANGKAT);
			
		}


		$gol_awal_id = $pegawai->GOL_AWAL_ID;
		if($gol_awal_id != ""){
			$recgolongan = $this->golongan_model->find($gol_awal_id);
			Template::set('GOLONGAN_AWAL', $recgolongan->NAMA);
		}
		$gol_awal_id2 = $pegawai2->GOL_AWAL_ID;
		if($gol_awal_id2 != ""){
			$recgolongan = $this->golongan_model->find($gol_awal_id2);
			Template::set('GOLONGAN_AWAL2', $recgolongan->NAMA);
		}

		$jenis_jabatan = $pegawai->JENIS_JABATAN_ID;
		if($jenis_jabatan != ""){
			$recjenis_jabatan = $this->jenis_jabatan_model->find($jenis_jabatan);
			Template::set('JENIS_JABATAN', $recjenis_jabatan->NAMA);
		}
		$jenis_jabatan2 = $pegawai2->JENIS_JABATAN_ID;
		if($jenis_jabatan2 != ""){
			$recjenis_jabatan2 = $this->jenis_jabatan_model->find($jenis_jabatan2);
			Template::set('JENIS_JABATAN2', $recjenis_jabatan2->NAMA);
		}

		$JABATAN_ID = $pegawai->JABATAN_INSTANSI_ID;
		if($JABATAN_ID != ""){
			$recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_ID));
			Template::set('NAMA_JABATAN', $recjabatan->NAMA_JABATAN);
			Template::set('NAMA_JABATAN_BKN', $recjabatan->NAMA_JABATAN_BKN);
		}

		$JABATAN_ID2 = $pegawai2->JABATAN_INSTANSI_ID;
		if($JABATAN_ID2 != ""){
			$recjabatan2 = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_ID2));
			Template::set('NAMA_JABATAN2', $recjabatan2->NAMA_JABATAN);
			Template::set('NAMA_JABATAN_BKN2', $recjabatan2->NAMA_JABATAN_BKN);
		}

		$unor = $this->unitkerja_model->find_by("ID",trim($pegawai->UNOR_ID));
		Template::set('nama_unor',$unor->NAMA_UNOR);
		$unor_induk = $this->unitkerja_model->find_by("ID",$unor->DIATASAN_ID);
		Template::set('unor_induk',$unor_induk->NAMA_UNOR);
		
		Template::set('selectedPendidikanID',$this->pendidikan_model->find(trim($pegawai->PENDIDIKAN_ID)));
		Template::set('selectedPendidikanID2',$this->pendidikan_model->find(trim($pegawai2->PENDIDIKAN_ID)));
		
		Template::set("parent_path_array_unor",$this->unitkerja_model->get_parent_path($unor->ID,true,true));
        Template::set('toolbar_title',"Review Pegawai");
		Template::set('model',$model);
		Template::set('back_button_label',"<< Kembali ke Daftar Pegawai");
		Template::set('back_button_url',base_url("admin/kepegawaian/pegawai"));
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
            redirect(SITE_AREA . '/kepegawaian/pegawai');
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
			
		}
		$kedudukan_hukum = "";
		$this->db->start_cache();
		
		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		$advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
		if($advanced_search_filters){
			$filters = array();
			foreach($advanced_search_filters as  $filter){
				$filters[$filter['name']] = $filter["value"];
			}
			if($filters['unit_id_cb']){
				$this->db->group_start();
				$this->db->where('vw."ID"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);
				$this->db->or_where("UNOR_INDUK_ID",$filters['unit_id_key']);	
				$this->db->group_end();
			}
			if($filters['nama_cb']){
				$this->pegawai_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");	
			}
			if($filters['nip_cb']){
				$this->pegawai_model->like('upper("NIP_BARU")',strtoupper($filters['nip_key']),"BOTH");	
			}
			if($filters['umur_cb']){
				if($filters['umur_operator']=="="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR")',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") >=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">"){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") >',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") <=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<"){
					$this->pegawai_model->where('calc_age("TGL_LAHIR")<',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="!="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") !=',$filters['umur_key']*12);	
				}
			}
			if($filters['eselon_cb']){
				$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($filters['nip_key']).'%\'');	
			}
			if($filters['golongan_cb']){
				$this->pegawai_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));	
			}
			if($filters['tkp_cb']){
				$this->pegawai_model->where('"TK_PENDIDIKAN"',strtoupper($filters['tingkat_pendidikan']));	
			}
			if($filters['kategori_cb']){
				$this->pegawai_model->where('"KATEGORI_JABATAN"',$filters['kategori_jabatan']);	
			}
			if($filters['kedudukan_hukum_cb']){
				$kedudukan_hukum = $filters['kedudukan_hukum'];
			}
		}
		
		$this->db->stop_cache();
		$output=array();
		$output['draw']=$draw;
		$asatkers = null;
		if($this->auth->has_permission($this->UnitkerjaTerbatas)){
			$asatkers = json_decode($this->auth->get_satkers());
			$total= $this->pegawai_model->count_all($asatkers,false,$kedudukan_hukum);
		}else{
			$total= $this->pegawai_model->count_all($this->UNOR_ID,false,$kedudukan_hukum);
		}
		
		$orders = $this->input->post('order');
		foreach($orders as $order){
			if($order['column']==1){
				$this->pegawai_model->order_by("NIP_BARU",$order['dir']);
			}
			if($order['column']==2){
				$this->pegawai_model->order_by("pegawai.NAMA",$order['dir']);
			}
			if($order['column']==3){
				$this->pegawai_model->order_by("NAMA_PANGKAT",$order['dir']);
			}
			if($order['column']==4){
				$this->pegawai_model->order_by("NAMA_UNOR",$order['dir']);
			}
		}
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
		
		$this->pegawai_model->limit($length,$start);
		if($this->auth->has_permission($this->UnitkerjaTerbatas)){
			$asatkers = json_decode($this->auth->get_satkers());
			$records=$this->pegawai_model->find_all($asatkers,false,$kedudukan_hukum);
		}else{
			$records=$this->pegawai_model->find_all($this->UNOR_ID,false,$kedudukan_hukum);
		}
		
		
		$this->db->flush_cache();
		$nomor_urut=$start+1;
		$status_pppk = array(71,72,73);
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->NIP_BARU;

                $row []  = $record->NAMA;
                if(in_array($record->KEDUDUKAN_HUKUM_ID,$status_pppk)){ 
					$row []  = $record->GOL_PPPK;
				}else{
					$row []  = $record->NAMA_PANGKAT."/".$record->NAMA_GOLONGAN." ";
				}
				
                $row []  = $record->NAMA_UNOR_FULL;
                //$row []  = $record->KATEGORI_JABATAN;
                
                $btn_actions = array();
                $btn_actions  [] = "
                    <a class='show-modal-custom' href='".base_url()."admin/kepegawaian/pegawai/profilen/".urlencode(base64_encode($record->ID))."'  data-toggle='tooltip' title='Lihat Profile'><span class='fa-stack'>
					   <i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-eye fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";/*add by bana 04_02_2019*/
				$btn_actions  [] = "
                    <a class='show-modal-custom' target='_blank' href='".base_url()."admin/kepegawaian/pegawai/resume/".$record->NIP_BARU."'  data-toggle='tooltip' title='Lihat Resume'><span class='fa-stack'>
					   <i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-book fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
				";
				/*end*/
				$btn_actions  [] = "
                    <a class='show-modal-custom' href='".base_url()."admin/kepegawaian/pegawai/cetak_drh/".$record->PNS_ID."'  data-toggle='tooltip' title='Cetak DRH'><span class='fa-stack'>
					   <i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-download fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
                if($this->auth->has_permission("Pegawai.Kepegawaian.Edit")){
                $btn_actions  [] = "
                    <a class='show-modal-custom' href='".base_url()."admin/kepegawaian/pegawai/edit/".urlencode(base64_encode($record->ID))."' data-toggle='tooltip' title='Ubah data'><span class='fa-stack'>
					   	<i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-pencil fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
                }
                if($this->auth->has_permission("Pegawai.SinkronBkn.View")){
                $btn_actions  [] = "
                    <a class='generatedatabkn' kode='$record->NIP_BARU' href='#".$record->NIP_BARU."' data-toggle='tooltip' title='Ubah data Berdasarkan data BKN'>
                    	<span class='fa-stack'>
					   	<i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-gear fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
                }
                if($this->auth->has_permission("Pegawai.Kepegawaian.Delete")){
                $btn_actions  [] = "
                        <a href='#' kode='$record->ID' class='btn-hapus' data-toggle='tooltip' title='Hapus data' >
					   	<span class='fa-stack'>
					   	<i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-trash-o fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
                }
                if($this->auth->has_permission($this->permissionCreateUser)){
	                $btn_actions  [] = "
	                    <a class='generate_user' kode='$record->NIP_BARU' href='#".$record->NIP_BARU."' data-toggle='tooltip' title='Buat User Akun'>
	                    	<span class='fa-stack'>
						   	<i class='fa fa-square fa-stack-2x'></i>
						   	<i class='fa fa-user fa-stack-1x fa-inverse'></i>
						   	</span>
						   	</a>
	                ";
                }
                if($this->auth->has_permission($this->permissionResetPassword)){
                	$nip_base = base64_encode($record->NIP_BARU."_rahasia");
	                $btn_actions  [] = "
	                    <a class='reset_password_user' kode='$nip_base' href='#".$record->NIP_BARU."' data-toggle='tooltip' title='Reset Password'>
	                    	<span class='fa-stack'>
						   	<i class='fa fa-square fa-stack-2x'></i>
						   	<i class='fa fa-key fa-stack-1x fa-inverse'></i>
						   	</span>
						   	</a>
	                ";
                }
                if($this->auth->has_permission($this->permissionViewDataBkn)){
                $btn_actions  [] = "
                    <a class='viewdatabkn' kode='$record->NIP_BARU' href='#".$record->NIP_BARU."' data-toggle='tooltip' title='Lihat data BKN'>
                    	<span class='fa-stack'>
					   	<i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-refresh fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
                }
                if($this->auth->has_permission($this->permissionViewDataMutasi)){
	                $btn_actions  [] = "
	                    <a class='viewdatamutasi' kode='$record->NIP_BARU' href='#".$record->NIP_BARU."' data-toggle='tooltip' title='Lihat data Mutasi'>
	                    	<span class='fa-stack'>
						   	<i class='fa fa-square fa-stack-2x'></i>
						   	<i class='fa fa-users fa-stack-1x fa-inverse'></i>
						   	</span>
						   	</a>
	                ";
                }

				if($this->auth->has_permission($this->permissionUpdatePensiun)){
					$btn_actions  [] = "
						<a class='setpensiunmeninggal' kode='$record->NIP_BARU' href='#".$record->NIP_BARU."' data-toggle='tooltip' title='Set Pensiun/Meninggal/Berhenti'>
							<span class='fa-stack'>
							   <i class='fa fa-square fa-stack-2x'></i>
							   <i class='fa fa-remove fa-stack-1x fa-inverse'></i>
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
		
	}
	public function getpensiundata(){
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
			if($filters['unit_id_cb']){
				$this->db->group_start();
				$this->db->where('vw."ID"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);	
				$this->db->group_end();
			}
			if($filters['nama_cb']){
				$this->pegawai_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");	
			}
			if($filters['nip_cb']){
				$this->pegawai_model->like('upper("NIP_BARU")',strtoupper($filters['nip_key']),"BOTH");	
			}
			if($filters['umur_cb']){
				if($filters['umur_operator']=="="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR")',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") >=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">"){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") >',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") <=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<"){
					$this->pegawai_model->where('calc_age("TGL_LAHIR")<',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="!="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") !=',$filters['umur_key']*12);	
				}
			}
			if($filters['eselon_cb']){
				$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($filters['nip_key']).'%\'');	
			}
			if($filters['golongan_cb']){
				$this->pegawai_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));	
			}
			if($filters['tkp_cb']){
				$this->pegawai_model->where('"TK_PENDIDIKAN"',strtoupper($filters['tingkat_pendidikan']));	
			}
			if($filters['kategori_cb']){
				$this->pegawai_model->where('"KATEGORI_JABATAN"',$filters['kategori_jabatan']);	
			}
			
		}
		
		$this->db->stop_cache();
		$output=array();
		$output['draw']=$draw;
		$total= $this->pegawai_model->count_pensiun_all($this->UNOR_ID);
		$orders = $this->input->post('order');
		foreach($orders as $order){
			if($order['column']==1){
				$this->pegawai_model->order_by("NIP_BARU",$order['dir']);
			}
			if($order['column']==2){
				$this->pegawai_model->order_by("pegawai.NAMA",$order['dir']);
			}
			if($order['column']==3){
				$this->pegawai_model->order_by("NAMA_PANGKAT",$order['dir']);
			}
			if($order['column']==4){
				$this->pegawai_model->order_by("NAMA_UNOR",$order['dir']);
			}
		}
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
		
		$this->pegawai_model->limit($length,$start);
		$records=$this->pegawai_model->find_pensiun_all($this->UNOR_ID);
		$this->db->flush_cache();
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->NIP_BARU;
                $row []  = $record->NAMA;
                $row []  = $record->NAMA_PANGKAT."/".$record->NAMA_GOLONGAN;
                $row []  = $record->NAMA_UNOR_FULL;
                //$row []  = $record->KATEGORI_JABATAN;
                
                $btn_actions = array();
                $btn_actions  [] = "
                    <a class='show-modal-custom' href='".base_url()."admin/kepegawaian/pegawai/profilen/".urlencode(base64_encode($record->ID))."'  data-toggle='tooltip' title='Lihat Profile'><span class='fa-stack'>
					   <i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-eye fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";/*add by bana 04_02_2019*/
				$btn_actions  [] = "
                    <a class='show-modal-custom' href='".base_url()."admin/kepegawaian/pegawai/resume/".$record->NIP_BARU."'  data-toggle='tooltip' title='Lihat Resume'><span class='fa-stack'>
					   <i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-book fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
				";
				/*end*/
				$btn_actions  [] = "
                    <a class='show-modal-custom' href='".base_url()."admin/kepegawaian/pegawai/cetak_drh/".$record->PNS_ID."'  data-toggle='tooltip' title='Cetak DRH'><span class='fa-stack'>
					   <i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-download fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
                if($this->auth->has_permission("Pegawai.Kepegawaian.Edit")){
                $btn_actions  [] = "
                    <a class='show-modal-custom' href='".base_url()."admin/kepegawaian/pegawai/edit/".urlencode(base64_encode($record->ID))."' data-toggle='tooltip' title='Ubah data'><span class='fa-stack'>
					   	<i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-pencil fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
                }
                if($this->auth->has_permission("Pegawai.SinkronBkn.View")){
                $btn_actions  [] = "
                    <a class='generatedatabkn' kode='$record->NIP_BARU' href='#".$record->NIP_BARU."' data-toggle='tooltip' title='Ubah data Berdasarkan data BKN'>
                    	<span class='fa-stack'>
					   	<i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-gear fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
                }
                if($this->auth->has_permission("Pegawai.Kepegawaian.Delete")){
                $btn_actions  [] = "
                        <a href='#' kode='$record->ID' class='btn-hapus' data-toggle='tooltip' title='Hapus data' >
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
		
	}
	public function getdataupdatemandiri(){
    	$this->auth->restrict($this->VerifikasiUpdate);
    	$this->load->model('pegawai/update_mandiri_model');
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
			if($filters['unit_id_cb']){
				$this->db->group_start();
				$this->db->where('vw."ID"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);	
				$this->db->group_end();
			}
			if($filters['nama_cb']){
				$this->update_mandiri_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");	
			}
			if($filters['nip_cb']){
				$this->update_mandiri_model->like('upper("NIP_BARU")',strtoupper($filters['nip_key']),"BOTH");	
			}
			if($filters['umur_cb']){
				if($filters['umur_operator']=="="){
					$this->update_mandiri_model->where('calc_age("TGL_LAHIR")',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">="){
					$this->update_mandiri_model->where('calc_age("TGL_LAHIR") >=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">"){
					$this->update_mandiri_model->where('calc_age("TGL_LAHIR") >',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<="){
					$this->update_mandiri_model->where('calc_age("TGL_LAHIR") <=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<"){
					$this->update_mandiri_model->where('calc_age("TGL_LAHIR")<',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="!="){
					$this->update_mandiri_model->where('calc_age("TGL_LAHIR") !=',$filters['umur_key']*12);	
				}
			}
			if($filters['eselon_cb']){
				$this->update_mandiri_model->where('upper("NAMA") LIKE \''.strtoupper($filters['nip_key']).'%\'');	
			}
			if($filters['golongan_cb']){
				$this->update_mandiri_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));	
			}
			if($filters['tkp_cb']){
				$this->update_mandiri_model->where('"TK_PENDIDIKAN"',strtoupper($filters['tingkat_pendidikan']));	
			}
			if($filters['level_cb']){
				$this->update_mandiri_model->where("LEVEL_UPDATE",$filters['level']);	
			}
			
			
		}
		
		$this->db->stop_cache();
		$output=array();
		$output['draw']=$draw;
		$total= $this->update_mandiri_model->count_notif($this->UNOR_ID);
		$orders = $this->input->post('order');
		foreach($orders as $order){
			if($order['column']==1){
				$this->update_mandiri_model->order_by("NIP_BARU",$order['dir']);
			}
			if($order['column']==2){
				$this->update_mandiri_model->order_by("pegawai.NAMA",$order['dir']);
			}
			if($order['column']==3){
				$this->update_mandiri_model->order_by("NAMA_PANGKAT",$order['dir']);
			}
			if($order['column']==4){
				$this->update_mandiri_model->order_by("NAMA_UNOR",$order['dir']);
			}
		}
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
		
		$this->update_mandiri_model->limit($length,$start);
		$records=$this->update_mandiri_model->find_notif($this->UNOR_ID);
		$this->db->flush_cache();
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->NIP_BARU;
                $row []  = $record->NAMA;
                $row []  = $record->NAMA_UNOR_FULL;
                $row []  = $record->NAMA_KOLOM;
                $btn_actions = array();
                if($this->auth->has_permission($this->VerifikasiUpdate) or $this->auth->has_permission($this->VerifikasiUpdate3)){
                	if(trim($record->NAMA_KOLOM) == "RIWAYAT PENDIDIKAN"){
                		$btn_actions  [] = "<a href='".base_url()."pegawai/riwayatpendidikan/verifikasi/".$record->PNS_ID."/".$record->ID_TABEL."' class='btn btn-sm btn-warning show-modal'><i class='glyphicon glyphicon-edit'></i> </a>";
                	}else if(trim($record->NAMA_KOLOM) == "RIWAYAT KEPANGKATAN"){
                		$btn_actions  [] = "<a href='".base_url()."pegawai/riwayatkepangkatan/verifikasi/".$record->PNS_ID."/".$record->ID_TABEL."' class='btn btn-sm btn-warning show-modal'><i class='glyphicon glyphicon-edit'></i> </a>";
                	}else if(trim($record->NAMA_KOLOM) == "RIWAYAT JABATAN"){
                		$btn_actions  [] = "<a href='".base_url()."pegawai/riwayatjabatan/verifikasi/".$record->PNS_ID."/".$record->ID_TABEL."' class='btn btn-sm btn-warning show-modal'><i class='glyphicon glyphicon-edit'></i> </a>";
                	}
                	else{
                		$btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/viewupdate/".$record->ID."' class='btn btn-sm btn-warning show-modal'><i class='glyphicon glyphicon-edit'></i> </a>";	
                	}
                }
                if($this->auth->has_permission("Pegawai.Kepegawaian.Delete") or $this->auth->has_permission($this->VerifikasiUpdate3)){
                	$btn_actions  [] = "<a kode='$record->ID' class='btn btn-sm btn-danger btn-hapus' data-toggle='tooltip' data-placement='top' data-original-title='Hapus data' title='Hapus data' tooltip='Hapus'><i class='glyphicon glyphicon-remove'></i> </a>";
                }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		
	}
	public function getdataupdatemandirisatker(){
    	$this->auth->restrict($this->VerifikasiUpdate);
    	$this->load->model('pegawai/update_mandiri_model');
    	if (!$this->input->is_ajax_request()) {
   			//Template::set_message("Hanya request ajax", 'error');
            //redirect(SITE_AREA . '/kepegawaian/pegawai');
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
			if($filters['unit_id_cb']){
				$this->db->group_start();
				$this->db->where('vw."ID"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);	
				$this->db->group_end();
			}
			if($filters['nama_cb']){
				$this->update_mandiri_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");	
			}
			if($filters['nip_cb']){
				$this->update_mandiri_model->like('upper("NIP_BARU")',strtoupper($filters['nip_key']),"BOTH");	
			}
			if($filters['umur_cb']){
				if($filters['umur_operator']=="="){
					$this->update_mandiri_model->where('calc_age("TGL_LAHIR")',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">="){
					$this->update_mandiri_model->where('calc_age("TGL_LAHIR") >=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">"){
					$this->update_mandiri_model->where('calc_age("TGL_LAHIR") >',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<="){
					$this->update_mandiri_model->where('calc_age("TGL_LAHIR") <=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<"){
					$this->update_mandiri_model->where('calc_age("TGL_LAHIR")<',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="!="){
					$this->update_mandiri_model->where('calc_age("TGL_LAHIR") !=',$filters['umur_key']*12);	
				}
			}
			if($filters['eselon_cb']){
				$this->update_mandiri_model->where('upper("NAMA") LIKE \''.strtoupper($filters['nip_key']).'%\'');	
			}
			if($filters['golongan_cb']){
				$this->update_mandiri_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));	
			}
			if($filters['tkp_cb']){
				$this->update_mandiri_model->where('"TK_PENDIDIKAN"',strtoupper($filters['tingkat_pendidikan']));	
			}
			
		}
		
		$this->db->stop_cache();
		$output=array();
		$output['draw']=$draw;
		$total= $this->update_mandiri_model->count_notif1($this->UNOR_ID);
		$orders = $this->input->post('order');
		foreach($orders as $order){
			if($order['column']==1){
				$this->update_mandiri_model->order_by("NIP_BARU",$order['dir']);
			}
			if($order['column']==2){
				$this->update_mandiri_model->order_by("pegawai.NAMA",$order['dir']);
			}
			if($order['column']==3){
				$this->update_mandiri_model->order_by("NAMA_PANGKAT",$order['dir']);
			}
			if($order['column']==4){
				$this->update_mandiri_model->order_by("NAMA_UNOR",$order['dir']);
			}
		}
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
		
		$this->update_mandiri_model->limit($length,$start);
		$records=$this->update_mandiri_model->find_notif1($this->UNOR_ID);
		$this->db->flush_cache();
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->NIP_BARU;
                $row []  = $record->NAMA;
                $row []  = $record->NAMA_UNOR_FULL;
                $row []  = $record->NAMA_KOLOM;
                
                $btn_actions = array();

                if($this->auth->has_permission($this->VerifikasiUpdate) or $this->auth->has_permission($this->VerifikasiUpdate3)){
                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/viewupdate/".$record->ID."' class='btn btn-sm btn-warning show-modal'><i class='glyphicon glyphicon-edit'></i> </a>";
                }
                if($this->auth->has_permission("Pegawai.Kepegawaian.Delete")){
                	$btn_actions  [] = "<a kode='$record->ID' class='btn btn-sm btn-danger btn-hapus' data-toggle='tooltip' data-placement='top' data-original-title='Hapus data' title='Hapus data' tooltip='Hapus'><i class='glyphicon glyphicon-remove'></i> </a>";
                }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		
	}
	public function getdatappnpn(){
		$this->auth->restrict($this->permissionViewPpnpn);
		if (!$this->input->is_ajax_request()) {
   			Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai/ppnpn');
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
				$this->pegawai_model->like('upper("NAMA")',strtoupper($filters['nama_key']),"BOTH");	
			}
			if($filters['unit_id_cb']){
				$this->db->group_start();
				$this->db->where('vw."ID"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);	
				$this->db->group_end();
			}
			if($filters['pns_id']){
				$this->pegawai_model->like('upper("PNS_ID")',strtoupper($filters['pns_id']),"BOTH");	
			}
			if($filters['umur_cb']){
				if($filters['umur_operator']=="="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR")',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") >=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">"){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") >',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") <=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<"){
					$this->pegawai_model->where('calc_age("TGL_LAHIR")<',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="!="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") !=',$filters['umur_key']*12);	
				}
			}
		}
		
		$this->db->stop_cache();
		$output=array();
		$output['draw']=$draw;
		$total= $this->pegawai_model->count_all_ppnpn($this->UNOR_ID);
		$orders = $this->input->post('order');
		foreach($orders as $order){
			if($order['column']==1){
				$this->pegawai_model->order_by("NIP_BARU",$order['dir']);
			}
			if($order['column']==2){
				$this->pegawai_model->order_by("NAMA",$order['dir']);
			}
			if($order['column']==3){
				$this->pegawai_model->order_by("NAMA_UNOR",$order['dir']);
			}
		}
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
		
		$this->pegawai_model->limit($length,$start);
		$records=$this->pegawai_model->find_all_ppnpn($this->UNOR_ID);
		$this->db->flush_cache();
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->PNS_ID;
                $row []  = $record->NAMA;
                $row []  = $record->NAMA_UNOR_FULL;
                
                $btn_actions = array();
                $btn_actions  [] = "
                    <a class='show-modal-custom' href='".base_url()."admin/kepegawaian/pegawai/profileppnpn/".$record->ID."'  data-toggle='tooltip' title='Lihat Profile'><span class='fa-stack'>
					   <i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-eye fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
				$btn_actions  [] = "
                    <a class='show-modal-custom' href='".base_url()."admin/kepegawaian/pegawai/cetak_drh/".$record->PNS_ID."'  data-toggle='tooltip' title='Cetak DRH'><span class='fa-stack'>
					   <i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-download fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
                if($this->auth->has_permission("Pegawai.Kepegawaian.Editppnpn")){
                $btn_actions  [] = "
                    <a class='show-modal-custom' href='".base_url()."admin/kepegawaian/pegawai/edit_ppnpn/".$record->ID."' data-toggle='tooltip' title='Ubah data'><span class='fa-stack'>
					   	<i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-pencil fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
                }
                if($this->auth->has_permission($this->permissionCreateUser)){
	                $btn_actions  [] = "
	                    <a class='generate_user' kode='$record->NIP_BARU' href='#".$record->NIP_BARU."' data-toggle='tooltip' title='Buat User Akun'>
	                    	<span class='fa-stack'>
						   	<i class='fa fa-square fa-stack-2x'></i>
						   	<i class='fa fa-user fa-stack-1x fa-inverse'></i>
						   	</span>
						   	</a>
	                ";
                }
                if($this->auth->has_permission("Pegawai.Kepegawaian.Deleteppnpn")){
                $btn_actions  [] = "
                        <a href='#' kode='$record->ID' class='btn-hapus' data-toggle='tooltip' title='Hapus data' >
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
		
	}
	public function cetak_drh($pns_id){
		$this->load->model('pegawai/orang_tua_model');
		$this->load->model('pegawai/istri_model');
		$this->load->model('pegawai/anak_model');
		$this->load->model('pegawai/riwayat_jabatan_model');
		$pegawai = $this->pegawai_model->get_drh($pns_id);
		$this->load->library('LibOpenTbs');
		$template_name = APPPATH."..".DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'template_drh.docx';
		$TBS = $this->libopentbs->TBS;
		
		$TBS->LoadTemplate($template_name, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
		
		$foto_pegawai = trim($this->settings_lib->item('site.urlphoto'))."noimage.jpg";
		if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO) and $pegawai->PHOTO != ""){
			$foto_pegawai =  trim($this->settings_lib->item('site.urlphoto')).$pegawai->PHOTO;
		}
		//die($foto_pegawai);
		$TBS->MergeField('a', array(
			'fullname'=>$pegawai->NAMA,
			'nip'=>$pegawai->NIP_BARU,
			'alamat'=>$pegawai->ALAMAT,
			'foto'=>$foto_pegawai,
			'tempat_lahir'=>$pegawai->TEMPAT_LAHIR,
			'tanggal_lahir'=>$pegawai->TGL_LAHIR,
			'pangkat'=>$pegawai->PANGKAT_TEXT,
			'gol_ruang'=>$pegawai->GOL_TEXT,
			'sex'=>$pegawai->JENIS_KELAMIN=='M'?"Pria":"Wanita",
			'agama'=>$pegawai->AGAMA_TEXT,
			'status_kawin'=>$pegawai->KAWIN_TEXT,
		));
		$this->riwayat_pendidikan_model->order_by("TAHUN_LULUS","asc");
        $this->riwayat_pendidikan_model->where("PNS_ID",$pns_id);    
		$records=$this->riwayat_pendidikan_model->find_all();
		if(isset($records) && is_array($records) && count($records)):
			$TBS->MergeBlock('pend',$records);	
		endif;
		//

        $this->riwayat_kepangkatan_model->where("PNS_ID",$pns_id);  
        $recordpangkats = $this->riwayat_kepangkatan_model->find_all();
		if(isset($recordpangkats) && is_array($recordpangkats) && count($recordpangkats)):
			$TBS->MergeBlock('pk',$recordpangkats);	
		endif;
		//$TBS->MergeBlock('pk',$records);
		$recordkeluargas=$this->orang_tua_model->find_all($pegawai->NIP_BARU);
		if(isset($recordkeluargas) && is_array($recordkeluargas) && count($recordkeluargas)):
			$TBS->MergeBlock('kel',$recordkeluargas);	
		endif;

		$recordistris=$this->istri_model->find_all($pegawai->NIP_BARU);
		if(isset($recordistris) && is_array($recordistris) && count($recordistris)):
			$TBS->MergeBlock('istri',$recordistris);	
		endif;

		$recordanaks=$this->anak_model->find_all($pegawai->NIP_BARU);
		if(isset($recordanaks) && is_array($recordanaks) && count($recordanaks)):
			$TBS->MergeBlock('anak',$recordanaks);	
		endif;

		$this->riwayat_jabatan_model->where("PNS_NIP",$pegawai->NIP_BARU); 

		$recordjabatans =$this->riwayat_jabatan_model->find_all();
		if(isset($recordjabatans) && is_array($recordjabatans) && count($recordjabatans)):
			$TBS->MergeBlock('jb',$recordjabatans);	
		endif;
		

		$output_file_name = 'DRH.docx';
		$output_file_name = str_replace('.', '_'.date('Y-m-d').$pegawai->NIP_BARU.'.', $output_file_name);
		$TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.
	}
	public function download()
	{
	  
		$advanced_search_filters  = $_GET;
		
		if($advanced_search_filters){
			$filters = $advanced_search_filters;
			
			if($filters['unit_id_cb']){
				$this->db->group_start();
				$this->db->where('vw."ID"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);	
				$this->db->group_end();
			}
			if($filters['nama_cb']){
				$this->pegawai_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");	
			}
			if($filters['nip_cb']){
				$this->pegawai_model->like('upper("NIP_BARU")',strtoupper($filters['nip_key']),"BOTH");	
			}
			if($filters['umur_cb']){
				if($filters['umur_operator']=="="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR")',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") >=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">"){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") >',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") <=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<"){
					$this->pegawai_model->where('calc_age("TGL_LAHIR")<',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="!="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") !=',$filters['umur_key']*12);	
				}
			}
			if($filters['eselon_cb']){
				$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($filters['nip_key']).'%\'');	
			}
			if($filters['golongan_cb']){
				$this->pegawai_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));	
			}
			if($filters['tkp_cb']){
				$this->pegawai_model->where('"TK_PENDIDIKAN"',strtoupper($filters['tingkat_pendidikan']));	
			}
			if($filters['kategori_cb']){
				$this->pegawai_model->where('"KATEGORI_JABATAN"',$filters['kategori_jabatan']);	
			}
			if($filters['kedudukan_hukum_cb']){
				$kedudukan_hukum = $filters['kedudukan_hukum'];
			}
		}
		
		$datapegwai=$this->pegawai_model->find_all_detil($this->UNOR_ID);
		
		$this->load->library('Excel');
		$objPHPExcel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load(trim($this->settings_lib->item('site.pathuploaded')).'template.xls');

		$objPHPExcel->setActiveSheetIndex(0);
		$col = 0;
		$itemfield = $this->db->list_fields('pegawai');
		foreach($itemfield as $field)
		{
			 
			   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,1,$field);
			   $col++;
		}
		$row = 2;
		if (isset($datapegwai) && is_array($datapegwai) && count($datapegwai)) :
			foreach ($datapegwai as $record) :
				$col = 0;
				$type = PHPExcel_Cell_DataType::TYPE_STRING;
				foreach($itemfield as $field)
				{
					if($col == 3)
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$record->$field." ",PHPExcel_Cell_DataType::TYPE_STRING);
					else
						//$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$record->$field,PHPExcel_Cell_DataType::TYPE_STRING);
						$type = PHPExcel_Cell_DataType::TYPE_STRING;
						$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->setValueExplicit($record->$field, $type);
					$col++;
				}
			   
			$row++;
			endforeach;
		endif;
		  
		$filename = "pegawai".mt_rand(1,100000).'.xls'; //just some random filename
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
		//$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
		$objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
		exit; //done.. exiting!
		
	}
	public function downloadnominatif(){
		$advanced_search_filters  = $_GET;
		if($advanced_search_filters){
			$filters = $advanced_search_filters;
			if(isset($filters['unit_id_cb']) and $filters['unit_id_cb'] != ""){
				$this->db->group_start();
				$this->db->where('vw."ID"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);	
				$this->db->group_end();
			}
			if($filters['nama_cb']){
				$this->pegawai_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");	
			}
			if($filters['nip_cb']){
				$this->pegawai_model->like('upper("NIP_BARU")',strtoupper($filters['nip_key']),"BOTH");	
			}
			if($filters['umur_cb']){
				if($filters['umur_operator']=="="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR")',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") >=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">"){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") >',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") <=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<"){
					$this->pegawai_model->where('calc_age("TGL_LAHIR")<',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="!="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") !=',$filters['umur_key']*12);	
				}
			}
			if($filters['eselon_cb']){
				$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($filters['nip_key']).'%\'');	
			}
			if($filters['golongan_cb']){
				$this->pegawai_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));	
			}
			if($filters['tkp_cb']){
				$this->pegawai_model->where('"TK_PENDIDIKAN"',strtoupper($filters['tingkat_pendidikan']));	
			}
			if($filters['kategori_cb']){
				$this->pegawai_model->where('"KATEGORI_JABATAN"',$filters['kategori_jabatan']);	
			}
			if($filters['kedudukan_hukum_cb']){
				$kedudukan_hukum = $filters['kedudukan_hukum'];
			}
		}
		if($this->auth->has_permission($this->UnitkerjaTerbatas)){
			$asatkers = json_decode($this->auth->get_satkers());
			$datapegwai=$this->pegawai_model->find_download($asatkers,false,$kedudukan_hukum);
		}else{
			$datapegwai=$this->pegawai_model->find_download($this->UNOR_ID,false,$kedudukan_hukum);
		}
        $this->load->library('LibOpenTbs');
        $template_name = trim($this->settings_lib->item('site.pathuploaded')).'templatenominatif.xlsx';
        $TBS = $this->libopentbs->TBS;
        $TBS->LoadTemplate($template_name, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
        $TBS->MergeBlock('r', $datapegwai);
        $TBS->MergeField('a', array(
            'bulan'=>'maret',
        )); 

        $output_file_name = 'Nominatif_pegawai.xlsx';
        $output_file_name = str_replace('.', '_'.date('Y-m-d').'.', $output_file_name);
        $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.
	}
	public function downloadnominatif_mv(){
		$this->load->model('pegawai/mv_nominatif_pegawai_model');
		$advanced_search_filters  = $_GET;
		if($advanced_search_filters){
			$filters = $advanced_search_filters;
			if(isset($filters['unit_id_cb']) and $filters['unit_id_cb'] != ""){
				$this->db->group_start();
				$this->db->where('"ID_UNOR"',$filters['unit_id_key']);	
				$this->db->or_where('"ESELON_1"',$filters['unit_id_key']);	
				$this->db->or_where('"ESELON_2"',$filters['unit_id_key']);	
				$this->db->or_where('"ESELON_3"',$filters['unit_id_key']);	
				$this->db->or_where('"ESELON_4"',$filters['unit_id_key']);	
				$this->db->group_end();
			}
			if($filters['nama_cb']){
				$this->mv_nominatif_pegawai_model->like('upper("NAMA")',strtoupper($filters['nama_key']),"BOTH");	
			}
			if($filters['nip_cb']){
				$this->mv_nominatif_pegawai_model->like('upper("NIP_BARU")',strtoupper($filters['nip_key']),"BOTH");	
			}
			if($filters['umur_cb']){
				if($filters['umur_operator']=="="){
					$this->mv_nominatif_pegawai_model->where('calc_age("TGL_LAHIR")',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">="){
					$this->mv_nominatif_pegawai_model->where('calc_age("TGL_LAHIR") >=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">"){
					$this->mv_nominatif_pegawai_model->where('calc_age("TGL_LAHIR") >',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<="){
					$this->mv_nominatif_pegawai_model->where('calc_age("TGL_LAHIR") <=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<"){
					$this->mv_nominatif_pegawai_model->where('calc_age("TGL_LAHIR")<',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="!="){
					$this->mv_nominatif_pegawai_model->where('calc_age("TGL_LAHIR") !=',$filters['umur_key']*12);	
				}
			}
			if($filters['eselon_cb']){
				$this->mv_nominatif_pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($filters['nip_key']).'%\'');	
			}
			if($filters['golongan_cb']){
				$this->mv_nominatif_pegawai_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));	
			}
			if($filters['tkp_cb']){
				$this->mv_nominatif_pegawai_model->where('"TK_PENDIDIKAN"',strtoupper($filters['tingkat_pendidikan']));	
			}
			if($filters['kategori_cb']){
				$this->mv_nominatif_pegawai_model->where('"KATEGORI_JABATAN"',$filters['kategori_jabatan']);	
			}
			if($filters['kedudukan_hukum_cb']){
				$kedudukan_hukum = $filters['kedudukan_hukum'];
			}
		}
		
		$datapegwai=$this->mv_nominatif_pegawai_model->find_download($this->UNOR_ID);
        $this->load->library('LibOpenTbs');
        $template_name = trim($this->settings_lib->item('site.pathuploaded')).'templatenominatif.xlsx';
        $TBS = $this->libopentbs->TBS;
        $TBS->LoadTemplate($template_name, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
        $TBS->MergeBlock('r', $datapegwai);
        $TBS->MergeField('a', array(
            'bulan'=>'maret',
        )); 

        $output_file_name = 'Nominatif_pegawai.xlsx';
        $output_file_name = str_replace('.', '_'.date('Y-m-d').'.', $output_file_name);
        $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.
	}
	public function downloadphoto()
	{
	 
		$advanced_search_filters  = $_GET;
		$this->load->helper('download');
		if($advanced_search_filters){
			$filters = $advanced_search_filters;
			
			if($filters['nama_cb']){
				$this->pegawai_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");	
				
			}
			if($filters['unit_id_cb']){
				$this->db->group_start();
				$this->db->where('vw."ID"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);	
				$this->db->group_end();
			}


			if($filters['nip_cb']){
				$this->pegawai_model->like('upper("NIP_BARU")',strtoupper($filters['nip_key']),"BOTH");	
			}
			if($filters['umur_cb']){
				if($filters['umur_operator']=="="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR")',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") >=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">"){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") >',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") <=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<"){
					$this->pegawai_model->where('calc_age("TGL_LAHIR")<',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="!="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") !=',$filters['umur_key']*12);	
				}
			}
			if($filters['eselon_cb']){
				$this->pegawai_model->where('upper("NAMA1") LIKE \''.strtoupper($filters['nip_key']).'%\'');	
			}
			if($filters['golongan_cb']){
				$this->pegawai_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));	
			}
		}
		
		$datapegwai=$this->pegawai_model->find_all($this->UNOR_ID);
		$this->load->helper('handle_upload');
		$zip = new ZipArchive();
		$DelFilePath = "zipphoto.zip";
		//if(file_exists(trim($this->settings_lib->item('site.urlphoto')."zip/".$DelFilePath))) {
			//die("hapus");
			deletefile($DelFilePath,trim($this->settings_lib->item('site.urlphoto'))."zip/");
		    //unlink (trim($this->settings_lib->item('site.urlphoto')."zip/".$DelFilePath)); 
		//}
			
		//die(trim($this->settings_lib->item('site.urlphoto'))."ga hapus");
		if ($zip->open(trim($this->settings_lib->item('site.urlphoto'))."zip/".$DelFilePath, ZIPARCHIVE::CREATE) != TRUE) {
		    die ("Could not open archive");
		}
		if (isset($datapegwai) && is_array($datapegwai) && count($datapegwai)) :
			foreach ($datapegwai as $record) :

				$PHOTO = ISSET($record->PHOTO) ? trim($record->PHOTO) : "";
				if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$PHOTO) && $PHOTO != ""){
					$zip->addFile(trim($this->settings_lib->item('site.urlphoto')).trim($PHOTO),TRIM($PHOTO));
				}
			endforeach;
		endif;
		// close and save archive
		$zip->close();
		force_download(trim($this->settings_lib->item('site.urlphoto'))."zip/".$DelFilePath,NULL);
		exit; //done.. exiting!
		
	}
	public function getdatapensiun(){
		$draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length');
		$start= $this->input->post('start');

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		 
		
		$output=array();
		

		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if($search!=""){
			$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
		}
		
		$this->pegawai_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "NAMA";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->pegawai_model->order_by($iSortCol,$sSortCol);
		$records=$this->pegawai_model->find_all_pensiun($this->UNOR_ID);

		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($search != "")
		{
			$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
			$jum	= $this->pegawai_model->count_pensiun($this->UNOR_ID);
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}else{
			$total= $this->pegawai_model->count_pensiun($this->UNOR_ID);
			$output['draw']=$draw;
			$output['recordsTotal']= $output['recordsFiltered']=$total;
			$output['data']=array();

		}
		
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->NIP_BARU;
                $row []  = $record->NAMA;
                $row []  = $record->NAMA_JABATAN." (".$record->PENSIUN.")";
                $row []  = $record->TGL_LAHIR;
                $row []  = $record->umur;
                $row []  = $record->NAMA_UNOR;
                
                $btn_actions = array();
                $btn_actions  [] = "
                    <a class='show-modal-custom' href='".base_url()."admin/kepegawaian/pegawai/profilen/".urlencode(base64_encode($record->ID))."'  data-toggle='modal' title='Ubah Data'><span class='fa-stack'>
					   <i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-eye fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
                 
                $row[] = implode(" ",$btn_actions);
                

                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		die();

	}
	public function deletedata()
	{
		$this->auth->restrict($this->permissionDelete);
		if (!$this->input->is_ajax_request()) {
   			Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai');
		}
		$id 	= $this->input->post('kode');
		if ($this->pegawai_model->delete($id)) {
			 log_activity($this->auth->user_id(),"Delete data" . ': ' . $id . ' : ' . $this->input->ip_address(), 'pegawai');
			 Template::set_message("Delete pegawai sukses", 'success');
			 echo "Sukses";
		}else{
			echo "Gagal";
		}

		exit();
	}
	public function deletedataupdatemandiri()
	{
		$this->load->model('pegawai/update_mandiri_model');
		$this->auth->restrict($this->VerifikasiUpdate3);
		if (!$this->input->is_ajax_request()) {
   			Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai/viewupdatemandiri');
		}
		$id 	= $this->input->post('kode');
		if ($this->update_mandiri_model->delete($id)) {
			 log_activity($this->auth->user_id(),"Delete data" . ': ' . $id . ' : ' . $this->input->ip_address(), 'pegawai');
			 Template::set_message("Delete pengajuan perubahan sukses", 'success');
			 echo "Sukses";
		}else{
			echo "Gagal";
		}

		exit();
	}
	public function save_pegawai()
    {
    	date_default_timezone_set("Asia/Bangkok");
    	if (!$this->input->is_ajax_request()) {
   			die("Only ajax request");
		}
		$id_data = $this->input->post('ID');
        $this->form_validation->set_rules($this->pegawai_model->get_validation_rules());
        $extra_unique_rule = '';
		if ($id_data != '')
		{
			$_POST['id'] = $id_data;
			$extra_unique_rule = ',pegawai.ID';
		}else{
			$this->form_validation->set_rules('PNS_ID','KODE','required|max_length[30]|unique[pegawai.PNS_ID' . $extra_unique_rule . ']');
		}
        $this->form_validation->set_rules('PNS_ID','PNS ID','required|max_length[36]|unique[pegawai.PNS_ID' . $extra_unique_rule . ']');
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

        // Make sure we only pass in the fields we want
        $data = $this->pegawai_model->prep_data($this->input->post());
        
        $NIP_BARU = $this->input->post('NIP_BARU');
        $reclokasikerja = $this->lokasi_model->find($this->input->post('LOKASI_KERJA_ID'));
		$data['LOKASI_KERJA']	= $reclokasikerja->NAMA;
		$recpendidikan = $this->pendidikan_model->find($this->input->post('PENDIDIKAN_ID'));
		$data['PENDIDIKAN']	= $recpendidikan->NAMA;
		if($this->input->post("JENIS_JABATAN_ID") != ""){
			$rec_jenis = $this->jenis_jabatan_model->find($this->input->post("JENIS_JABATAN_ID"));
			$data["JENIS_JABATAN_NAMA"] = $rec_jenis->NAMA;
		}

		$recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",$this->input->post('JABATAN_INSTANSI_ID'));
		$data['JABATAN_INSTANSI_NAMA']	= $recjabatan->NAMA_JABATAN;
		
		$data['PNS_ID']	= $this->input->post('PNS_ID');
        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        $data['AGAMA_ID']	= $this->input->post('AGAMA_ID') ? $this->input->post('AGAMA_ID') : null;
        $data['GOL_ID']	= $this->input->post('GOL_ID') ? $this->input->post('GOL_ID') : null;
        $data['JENIS_JABATAN_ID']	= $this->input->post('JENIS_JABATAN_ID') ? $this->input->post('JENIS_JABATAN_ID') : null;
        
		$data['TGL_LAHIR']	= $this->input->post('TGL_LAHIR') ? $this->input->post('TGL_LAHIR') : null;
		$data['TGL_SK_CPNS']	= $this->input->post('TGL_SK_CPNS') ? $this->input->post('TGL_SK_CPNS') : null;
		$data['TMT_CPNS']	= $this->input->post('TMT_CPNS') ? $this->input->post('TMT_CPNS') : null;
		$data['TMT_PNS']	= $this->input->post('TMT_PNS') ? $this->input->post('TMT_PNS') : null;
		$data['TMT_GOLONGAN']	= $this->input->post('TMT_GOLONGAN') ? $this->input->post('TMT_GOLONGAN') : null;
		// jabatan
		$data['TMT_JABATAN']	= $this->input->post('TMT_JABATAN') ? $this->input->post('TMT_JABATAN') : null;
		$data['JABATAN_INSTANSI_ID']	= $this->input->post('JABATAN_INSTANSI_ID') ? $this->input->post('JABATAN_INSTANSI_ID') : null;
		$data['JABATAN_ID']	= $this->input->post('JABATAN_INSTANSI_ID') ? $this->input->post('JABATAN_INSTANSI_ID') : null;
		$data['JABATAN_INSTANSI_REAL_ID']	= $this->input->post('JABATAN_INSTANSI_REAL_ID') ? $this->input->post('JABATAN_INSTANSI_REAL_ID') : null;

		$data['TMT_PENSIUN']	= $this->input->post('TMT_PENSIUN') ? $this->input->post('TMT_PENSIUN') : null;
		$data['TGL_SURAT_DOKTER']	= $this->input->post('TGL_SURAT_DOKTER') ? $this->input->post('TGL_SURAT_DOKTER') : null;
		$data['TGL_BEBAS_NARKOBA']	= $this->input->post('TGL_BEBAS_NARKOBA') ? $this->input->post('TGL_BEBAS_NARKOBA') : null;
		$data['TGL_CATATAN_POLISI']	= $this->input->post('TGL_CATATAN_POLISI') ? $this->input->post('TGL_CATATAN_POLISI') : null;
		$data['TGL_MENINGGAL']	= $this->input->post('TGL_MENINGGAL') ? $this->input->post('TGL_MENINGGAL') : null;
		$data['TGL_NPWP']	= $this->input->post('TGL_NPWP') ? $this->input->post('TGL_NPWP') : null;
		$data['terminated_date']	= $this->input->post('terminated_date') ? $this->input->post('terminated_date') : null;
		$data['status_pegawai']	= $this->input->post('status_pegawai') ? $this->input->post('status_pegawai') : 1;
		$data['IS_DOSEN']	= $this->input->post('IS_DOSEN') ? $this->input->post('IS_DOSEN') : null;

		$data['MK_TAHUN_SWASTA']	= $this->input->post('MK_TAHUN_SWASTA') ? $this->input->post('MK_TAHUN_SWASTA') : 0;
		$data['MK_BULAN_SWASTA']	= $this->input->post('MK_BULAN_SWASTA') ? $this->input->post('MK_BULAN_SWASTA') : 0;

        $result = false;
        $msg = "";
        if ($id_data == "") {
        	$data['CREATED_DATE']	= date("Y-m-d");
        	$data['CREATED_BY']		= $this->auth->user_id();
            if($this->pegawai_model->insert($data)){
            	$result = true;
            	log_activity_pegawai($NIP_BARU,$this->auth->user_id(),"Tambah data pegawai", 'pegawai');
            	foreach ($data as $key => $value) {
	                $data[$key] = trim($data[$key]);
	            }
            	log_activity($this->auth->user_id(),"Create".  json_encode($data). ' : ' . $this->input->ip_address(), 'pegawai');
            }else{
            	$msg = $this->pegawai_model->error;
            }
        } elseif ($id_data != "") {
        	$data['UPDATED_DATE']	= date("Y-m-d");
        	$data['UPDATED_BY']		= $this->auth->user_id();
            $result = $this->pegawai_model->update($id_data, $data);

            // update log
            log_activity_pegawai($NIP_BARU,$this->auth->user_id(),"Perubahan data pegawai", 'pegawai');
            foreach ($data as $key => $value) {
                $data[$key] = trim($data[$key]);
            }
            log_activity($this->auth->user_id(),"Update ".  json_encode($data). ' : ' . $this->input->ip_address(), 'pegawai');
        }
        if($result){
 	       	$response ['success']= true;
    		$response ['msg']= "Simpan Berhasil";
    	}else{
    		$response ['success']= false;
    		$response ['msg']= "Ada kesalahan : ".$msg;
    	}
        echo json_encode($response);    
    }
    private function save_pegawaiold($type = 'insert', $id = 0)
    {
    	date_default_timezone_set("Asia/Bangkok");
        if ($type == 'update') {
            $_POST['id'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->pegawai_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->pegawai_model->prep_data($this->input->post());
        
        $reclokasikerja = $this->lokasi_model->find($this->input->post('LOKASI_KERJA_ID'));
		$data['LOKASI_KERJA']	= $reclokasikerja->NAMA;
		$recpendidikan = $this->pendidikan_model->find($this->input->post('PENDIDIKAN_ID'));
		$data['PENDIDIKAN']	= $recpendidikan->NAMA;
		if($this->input->post("JENIS_JABATAN_ID") != ""){
			$rec_jenis = $this->jenis_jabatan_model->find($this->input->post("JENIS_JABATAN_ID"));
			$data["JENIS_JABATAN_NAMA"] = $rec_jenis->NAMA;
		}

		$recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",$this->input->post('JABATAN_INSTANSI_ID'));
		$data['JABATAN_INSTANSI_NAMA']	= $recjabatan->NAMA_JABATAN;
		
		$data['PNS_ID']	= $this->input->post('PNS_ID');
        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        $data['AGAMA_ID']	= $this->input->post('AGAMA_ID') ? $this->input->post('AGAMA_ID') : null;
        $data['GOL_ID']	= $this->input->post('GOL_ID') ? $this->input->post('GOL_ID') : null;
        $data['JENIS_JABATAN_ID']	= $this->input->post('JENIS_JABATAN_ID') ? $this->input->post('JENIS_JABATAN_ID') : null;
        
		$data['TGL_LAHIR']	= $this->input->post('TGL_LAHIR') ? $this->input->post('TGL_LAHIR') : null;
		$data['TGL_SK_CPNS']	= $this->input->post('TGL_SK_CPNS') ? $this->input->post('TGL_SK_CPNS') : null;
		$data['TMT_CPNS']	= $this->input->post('TMT_CPNS') ? $this->input->post('TMT_CPNS') : null;
		$data['TMT_PNS']	= $this->input->post('TMT_PNS') ? $this->input->post('TMT_PNS') : null;
		$data['TMT_GOLONGAN']	= $this->input->post('TMT_GOLONGAN') ? $this->input->post('TMT_GOLONGAN') : null;
		$data['TMT_JABATAN']	= $this->input->post('TMT_JABATAN') ? $this->input->post('TMT_JABATAN') : null;
		
		$data['TMT_PENSIUN']	= $this->input->post('TMT_PENSIUN') ? $this->input->post('TMT_PENSIUN') : null;
		$data['TGL_SURAT_DOKTER']	= $this->input->post('TGL_SURAT_DOKTER') ? $this->input->post('TGL_SURAT_DOKTER') : null;
		$data['TGL_BEBAS_NARKOBA']	= $this->input->post('TGL_BEBAS_NARKOBA') ? $this->input->post('TGL_BEBAS_NARKOBA') : null;
		$data['TGL_CATATAN_POLISI']	= $this->input->post('TGL_CATATAN_POLISI') ? $this->input->post('TGL_CATATAN_POLISI') : null;
		$data['TGL_MENINGGAL']	= $this->input->post('TGL_MENINGGAL') ? $this->input->post('TGL_MENINGGAL') : null;
		$data['TGL_NPWP']	= $this->input->post('TGL_NPWP') ? $this->input->post('TGL_NPWP') : null;
		$data['terminated_date']	= $this->input->post('terminated_date') ? $this->input->post('terminated_date') : null;
		$data['status_pegawai']	= $this->input->post('status_pegawai') ? $this->input->post('status_pegawai') : 1;
		
        $return = false;
        if ($type == 'insert') {
        	$data['CREATED_DATE']	= date("Y-m-d");
        	$data['CREATED_BY']		= $this->auth->user_id();
            $id = $this->pegawai_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
        	$data['UPDATED_DATE']	= date("Y-m-d");
        	$data['UPDATED_BY']		= $this->auth->user_id();
            $return = $this->pegawai_model->update($id, $data);
        }

        return $return;
    }
    
    public function listpensiun()
    {	
    	$this->auth->restrict($this->permissionView);
        $records = $this->pegawai_model->find_all_pensiun($this->UNOR_ID);
        Template::set('records', $records);
    	Template::set('toolbar_title', "Estimasi Pegawai Pensiun");
		
        Template::render();
    }
	public function ajax_unit_list(){
		if (!$this->input->is_ajax_request()) {
   			//Template::set_message("Hanya request ajax", 'error');
            //redirect(SITE_AREA . '/kepegawaian/pegawai');
		}
		
		$length = 10;
		$term = $this->input->get('term');
		$page = $this->input->get('page');
		$this->db->flush_cache();
		//Jika ada role executive ?
		$UNOR_ID = '';
		$CI = &get_instance();
		$asatkers = null;
		if($this->auth->has_permission($this->UnitkerjaTerbatas)){
			$asatkers = json_decode($this->auth->get_satkers());
		}
		if($this->auth->has_permission($this->permissionFiltersatker)){
			$UNOR_ID = $this->pegawai_model->getunor_id($CI->auth->username());
		}
		if($this->auth->has_permission($this->permissionEselon1)){
			$UNOR_ID = $this->pegawai_model->getunor_eselon1($this->auth->username());
		}
		$data = $this->unitkerja_model->find_unit($term,$UNOR_ID,$asatkers);

		$output = array();
		$output['results'] = array();
		foreach($data as $row){
			$active = "";
			if($row->EXPIRED_DATE != ""){
				$active = " (Non Aktif)";
			}
			$output['results'] [] = array(
				'id'=>$row->ID,
				'text'=>$row->NAMA_UNOR_FULL.$active
			);
		}
		$output['pagination'] = array("more"=>false);
		
		echo json_encode($output);
	}
	

	public function resumenew($nip='')
	{
		$namafile = "5cc15eedb871c";//uniqid();

		$ch = curl_init(); 

		 
		
		$data = array(
			'nama_file'=>base_url().'assets/profile/'.$namafile.'.pdf',
			'nama_path'=>base_url().'assets/static/'
		);
		$this->load->view('kepegawaian/resume',$data);
	}
	/*end*/
	/*add by Bana 06_02_2019*/
	public function resume($nip='')
	{
		$namafile = uniqid();

		$ch = curl_init(); 

		//$url = "http://localhost:8080/proses?nip=$nip&namafile=$namafile";

		$url = "http://192.168.5.99:8080/proses?nip=$nip&namafile=$namafile";
		
		
		$headers = array(
			'Content-Type:application/json',
			'Authorization: Basic '. base64_encode("admin:123") // <---
		);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		// set url 
		curl_setopt($ch, CURLOPT_URL, $url);
		//echo $url;

		// return the transfer as a string 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

		// $output contains the output string 
		$output = curl_exec($ch); 

		// tutup curl 
		curl_close($ch);      

		// menampilkan hasil curl
		//echo $output;

		
		$data = array(
			'nama_file'=>base_url().'assets/profile/'.$namafile.'.pdf',
			'nama_path'=>base_url().'assets/static/'
		);
		$this->load->view('kepegawaian/resume',$data);
	}

	public function ajax_satker_list(){
		$length = 10;
		$term = $this->input->get('term');
		$page = $this->input->get('page');

		$UNOR_ID = '';
		$CI = &get_instance();
		
		if($this->auth->has_permission($this->permissionFiltersatker)){
			$UNOR_ID = $this->pegawai_model->getunor_id($CI->auth->username());
		}
		if($this->auth->has_permission($this->permissionEselon1)){
			$UNOR_ID = $this->pegawai_model->getunor_eselon1($this->auth->username());
		}
		
		$this->db->flush_cache();
		$this->db->like("lower(\"NAMA_UNOR\")",strtolower($term),"BOTH");
		$data = $this->unitkerja_model->find_satker($UNOR_ID);
		
		$output = array();
		$output['results'] = array();
		foreach($data as $row){
			$nama_unor = array();
			
			$output['results'] [] = array(
				'id'=>$row->ID,
				'text'=>$row->NAMA_UNOR
			);
		}
		$output['pagination'] = array("more"=>false);
		
		echo json_encode($output);
	}

	public function ajax_nama_pejabat(){
        $json = array();
        $limit = 10;
        $page = $this->input->get('page') ? $this->input->get('page') : "1";
        $q= $this->input->get('term');
        $start = ($page-1)*$limit;
		
		if(!empty($q)){
            $json = $this->data_model_nama_pejabat($q,$start,$limit);
		}
        echo json_encode($json);
	}
	
	private function data_model_nama_pejabat($key,$start,$limit){
		// update
		// ASLI
		  // $this->db->start_cache();
		  
		  // $this->db->like('lower("NAMA_UNOR")', strtolower($key));
		  // $this->db->from("hris.unitkerja");
  

	  
		  
		  
		  // $this->db->stop_cache();
		  // $total = $this->db->get()->num_rows();
		  // $this->db->select('ID as id,NAMA_UNOR as text');

		  // $this->db->limit($limit,$start);
		  // $data = $this->db->get()->result();
		  //#ASLI
		  
		  //modified by bana

		  $sql = "SELECT \"PNS_ID\" as id, \"NIP_BARU\" || ' - ' || \"NAMA\" as text FROM hris.pegawai 
		  		WHERE lower(\"NAMA\") ILIKE ? LIMIT ? OFFSET ?";
	   
		$key1 = "%".$key."%";
		//var_dump($key1);
		$query = $this->db->query($sql, array(strtolower($key1), $limit, $start));
		$data = $query->result();
		$total = $query->num_rows();
		  //var_dump($sql);

		  $endCount = $start + $limit;
		  $morePages = $endCount > $total;
		  $o = array(
		  "results" => $data,
			  "pagination" => array(
				  "more" =>$morePages,
			  )
		  );   
		  $this->db->flush_cache();
		  return $o;    
	}

	public function ajax(){
		if (!$this->input->is_ajax_request()) {
				die("Only ajax request");
		}
	    $json = array();
	    $limit = 10;
	    $page = $this->input->get('page') ? $this->input->get('page') : "1";
	    $q= $this->input->get('term');
	    $start = ($page-1)*$limit;
		
		if(!empty($q)){
	        $json = $this->data_model($q,$start,$limit);
		}
	    echo json_encode($json);
	}

	private function data_model($key,$start,$limit){
			  $sql = "SELECT trim(\"PNS_ID\") as id, \"NIP_BARU\" || ' - ' || \"NAMA\" as text FROM hris.pegawai 
			  		WHERE lower(\"NAMA\") ILIKE ? LIMIT ? OFFSET ?";
		   
			$key1 = "%".$key."%";
			$query = $this->db->query($sql, array(strtolower($key1), $limit, $start));
			$data = $query->result();
			$total = $query->num_rows();
			  //var_dump($sql);

			  $endCount = $start + $limit;
			  $morePages = $endCount > $total;
			  $o = array(
			  "results" => $data,
				  "pagination" => array(
					  "more" =>$morePages,
				  )
			  );   
			  $this->db->flush_cache();
			  return $o;    
	}
	public function ajaxid(){
		if (!$this->input->is_ajax_request()) {
			die("Only ajax request");
		}
	    $json = array();
	    $limit = 10;
	    $page = $this->input->get('page') ? $this->input->get('page') : "1";
	    $q= $this->input->get('term');
	    $start = ($page-1)*$limit;
		
		if(!empty($q)){
	        $json = $this->data_modelid($q,$start,$limit);
		}
	    echo json_encode($json);
	}
	private function data_modelid($key,$start,$limit){
			  $sql = "SELECT \"ID\" as id, \"NIP_BARU\" || ' - ' || \"NAMA\" as text FROM hris.pegawai 
			  		WHERE lower(\"NAMA\") ILIKE ? LIMIT ? OFFSET ?";
		   
			$key1 = "%".$key."%";
			$query = $this->db->query($sql, array(strtolower($key1), $limit, $start));
			$data = $query->result();
			$total = $query->num_rows();
			  //var_dump($sql);

			  $endCount = $start + $limit;
			  $morePages = $endCount > $total;
			  $o = array(
			  "results" => $data,
				  "pagination" => array(
					  "more" =>$morePages,
				  )
			  );   
			  $this->db->flush_cache();
			  return $o;    
	}
	public function ajaxnip(){
		if (!$this->input->is_ajax_request()) {
				die("Only ajax request");
		}
	    $json = array();
	    $limit = 10;
	    $page = $this->input->get('page') ? $this->input->get('page') : "1";
	    $q= $this->input->get('term');
	    $start = ($page-1)*$limit;
		
		if(!empty($q)){
	        $json = $this->data_modelnip($q,$start,$limit);
		}
	    echo json_encode($json);
	}
	private function data_modelnip($key,$start,$limit){
			  $sql = "SELECT \"NIP_BARU\" as id, \"NIP_BARU\" || ' - ' || \"NAMA\" as text FROM hris.pegawai 
			  		WHERE (lower(\"NAMA\") ILIKE ? or \"NIP_BARU\" ILIKE ?) LIMIT ? OFFSET ?";
		   
			$key1 = "%".$key."%";
			$query = $this->db->query($sql, array(strtolower($key1),strtolower($key1), $limit, $start));
			$data = $query->result();
			$total = $query->num_rows();
			  //var_dump($sql);

			  $endCount = $start + $limit;
			  $morePages = $endCount > $total;
			  $o = array(
			  "results" => $data,
				  "pagination" => array(
					  "more" =>$morePages,
				  )
			  );   
			  $this->db->flush_cache();
			  return $o;    
	}
	public function getinfounit(){
		if (!$this->input->is_ajax_request()) {
				die("Only ajax request");
		}
		$PNS_ID = $this->input->post("PNS_ID");
        $pegawai_data 	= $this->pegawai_model->find_detil_nip($PNS_ID);  
        $NAMA_UNOR_FULL = isset($pegawai_data[0]->NAMA_UNOR_FULL) ? $pegawai_data[0]->NAMA_UNOR_FULL : "";
        $NAMA_JABATAN = isset($pegawai_data[0]->JABATAN_INSTANSI_NAMA) ? $pegawai_data[0]->JABATAN_INSTANSI_NAMA : "";
        $UNIT_ASAL =  $pegawai_data[0]->UNOR_ID != "" ? $pegawai_data[0]->UNOR_ID : "";

        $output = "<div class='control-group col-sm-6'>
        			<label for='JABATAN_ID' class='control-label'>Dari</label>
                    <div class='controls'>
                        ".$NAMA_UNOR_FULL."
                    </div>
                </div>
                <div class='control-group col-sm-6'>
                	<label class='control-label'>Jabatan Awal</label>
                    <div class='controls'>
                        ".$NAMA_JABATAN."
                    </div>
                </div>
                ";
       echo $output;
        die();
	}
	public function viewupdate()
    {
        $id = $this->uri->segment(5);
        $this->load->model('pegawai/update_mandiri_model');
        $dataupdate = $this->update_mandiri_model->find($id);
        Template::set('dataupdate', $dataupdate);
        Template::set('toolbar_title', "Verifikasi Update");
        Template::render();
    }
    public function lihatupdate_pribadi()
    {
        $id = $this->uri->segment(5);
        $this->load->model('pegawai/update_mandiri_model');
        $dataupdate = $this->update_mandiri_model->find($id);
        Template::set('dataupdate', $dataupdate);
        Template::set('toolbar_title', "Lihat Permintaan Update");
        Template::render();
    }
    public function cekphoto(){
    	$this->auth->restrict($this->permissionView);
    	
		//$this->pegawai_model->limit(100);
		$records=$this->pegawai_model->find_photo();
		$nomor_urut = 1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
				$PHOTO = ISSET($record->PHOTO) ? trim($record->PHOTO) : "";
				//echo trim($this->settings_lib->item('site.pathphoto')).$PHOTO. " "."<br>";
				if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$PHOTO) && $PHOTO != ""){
					//echo " ada ";
				}else{
					//echo trim($this->settings_lib->item('site.urlphoto')).$PHOTO. "|";
					echo $nomor_urut."|"; 
					echo $PHOTO."|"; 
					echo " Tdk ada |";
					echo $record->NAMA."|<br>";
					$nomor_urut++;
				}
                
				
			}
		endif;
		
	}
	// get data from BKN
	public function getpegawaibkn(){
		$this->load->model('pegawai/golongan_model');
		$nip_baru 	= $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU",$nip_baru);		

		$this->load->library('Api_bkn');
		$api_lipi = new Api_bkn;

        // get data utama
        $jsonsatkers = $api_lipi->data_utama($nip_baru);

        $decodejson = json_decode($jsonsatkers);
        $decodejson = json_decode($decodejson);
        $pegawai 	= json_decode($decodejson->data);
        $data 		= array();
        // print_r($pegawai);
        // die();
        // update data profile utama
        //if(isset($pegawai_lokal->NIP_BARU) and $pegawai_lokal->NIP_BARU == "") 
		//$data["NIP_BARU"]	=	isset($pegawai->nipBaru)	?	$pegawai->nipBaru : 	"";

		if(isset($pegawai->id) and $pegawai->id != "") 
			if($pegawai->id!=$pegawai_lokal->PNS_ID){
				$pegawai->id = $pegawai_lokal->PNS_ID;
			}
			$data["PNS_ID"]	=	isset($pegawai->id)	?	$pegawai->id : 	"";

        if(isset($pegawai_lokal->AGAMA_ID)){
        	if($pegawai->agamaId != "")
        		$data["AGAMA_ID"]	=	isset($pegawai->agamaId)	? (int)$pegawai->agamaId : 	"";
        } 
        if(isset($pegawai_lokal->AKTE_KELAHIRAN) and $pegawai_lokal->AKTE_KELAHIRAN == "") 
			$data["AKTE_KELAHIRAN"]	=	isset($pegawai->akteKelahiran)	?	$pegawai->akteKelahiran : 	"";
		if(isset($pegawai_lokal->AKTE_MENINGGAL) and $pegawai_lokal->AKTE_MENINGGAL == "") 
			$data["AKTE_MENINGGAL"]	=	isset($pegawai->akteMeninggal)	?	$pegawai->akteMeninggal : 	"";
		if(isset($pegawai_lokal->ALAMAT) and $pegawai_lokal->ALAMAT == "") 
			$data["ALAMAT"]	=	isset($pegawai->alamat)	?	$pegawai->alamat : 	"";
		if(isset($pegawai_lokal->BPJS) and $pegawai_lokal->BPJS == "") 
			$data["BPJS"]	=	isset($pegawai->bpjs)	?	$pegawai->bpjs : 	"";
		if(isset($pegawai_lokal->EMAIL) and trim($pegawai_lokal->EMAIL) == "") 
			$data["EMAIL"]	=	isset($pegawai->email)	?	$pegawai->email : 	"";
		// if(isset($pegawai_lokal->GELAR_BELAKANG) and $pegawai_lokal->GELAR_BELAKANG == "") 
			$data["GELAR_BELAKANG"]	=	isset($pegawai->gelarBelakang)	?	$pegawai->gelarBelakang : 	"";

		// if(isset($pegawai_lokal->GELAR_DEPAN) and $pegawai_lokal->GELAR_DEPAN == "") 
			$data["GELAR_DEPAN"]	=	isset($pegawai->gelarDepan)	?	$pegawai->gelarDepan : 	"";
		if(isset($pegawai_lokal->GOL_AWAL_ID) and $pegawai_lokal->GOL_AWAL_ID == "") 
			$data["GOL_AWAL_ID"]	=	isset($pegawai->golRuangAwalId)	?	$pegawai->golRuangAwalId : 	"";
		// if(trim($pegawai_lokal->GOL_ID) == "" or $pegawai_lokal->GOL_ID == "0" or $pegawai->golRuangAkhirId != "") 
			$data["GOL_ID"]	=	isset($pegawai->golRuangAkhirId)	?	$pegawai->golRuangAkhirId : 	"";
		if(isset($pegawai_lokal->INSTANSI_INDUK_ID) and $pegawai_lokal->INSTANSI_INDUK_ID == "") 
			$data["INSTANSI_INDUK_ID"]	=	isset($pegawai->instansiIndukId)	?	$pegawai->instansiIndukId : 	"";
		if(isset($pegawai_lokal->INSTANSI_INDUK_NAMA) and $pegawai_lokal->INSTANSI_INDUK_NAMA == "") 
			$data["INSTANSI_INDUK_NAMA"]	=	isset($pegawai->instansiIndukNama)	?	$pegawai->instansiIndukNama : 	"";
		if(isset($pegawai_lokal->INSTANSI_KERJA_ID) and $pegawai_lokal->INSTANSI_KERJA_ID == "") 
			$data["INSTANSI_KERJA_ID"]	=	isset($pegawai->instansiKerjaId)	?	$pegawai->instansiKerjaId : 	"";
		if(isset($pegawai_lokal->INSTANSI_KERJA_NAMA) and $pegawai_lokal->INSTANSI_KERJA_NAMA == "") 
			$data["INSTANSI_KERJA_NAMA"]	=	isset($pegawai->instansiKerjaNama)	?	$pegawai->instansiKerjaNama : 	"";
		if(isset($pegawai_lokal->JABATAN_ID) and $pegawai_lokal->JABATAN_ID == "") 
			$data["JABATAN_ID"]	=	isset($pegawai->jabatanStrukturalId)	?	$pegawai->jabatanStrukturalId : 	"";
		
		if(isset($pegawai_lokal->JENIS_JABATAN_ID) and $pegawai_lokal->JENIS_JABATAN_ID == "") 
			$data["JENIS_JABATAN_ID"]	=	isset($pegawai->jenisJabatanId)	?	$pegawai->jenisJabatanId : 	"";
		if(isset($pegawai_lokal->JENIS_JABATAN_NAMA) and $pegawai_lokal->JENIS_JABATAN_NAMA == "") 
			$data["JENIS_JABATAN_NAMA"]	=	isset($pegawai->jenisJabatan)	?	$pegawai->jenisJabatan : 	"";
		if(isset($pegawai_lokal->JENIS_KAWIN_ID) and $pegawai_lokal->JENIS_KAWIN_ID == "") 
			$data["JENIS_KAWIN_ID"]	=	isset($pegawai->jenisKawinId)	?	$pegawai->jenisKawinId : 	"";

		if(isset($pegawai_lokal->JENIS_KELAMIN) and $pegawai->jenisKelamin != ""){
			$jenis_kelamin = isset($pegawai->jenisKelamin)	?	TRIM($pegawai->jenisKelamin) : 	"";
				if($jenis_kelamin == "Pria")
					$jk = "M";
				if($jenis_kelamin == "Wanita")
					$jk = "F";

				$data["JENIS_KELAMIN"]	=	$jk;
				// $data["JENIS_KELAMIN"]	=	isset($pegawai->jenisKelamin)	?	$pegawai->jenisKelamin : 	"";
		} 
		if(isset($pegawai_lokal->JENIS_PEGAWAI_ID) and $pegawai_lokal->JENIS_PEGAWAI_ID == "") 
			$data["JENIS_PEGAWAI_ID"]	=	isset($pegawai->jenisPegawaiId)	?	$pegawai->jenisPegawaiId : 	"";
		if(isset($pegawai_lokal->JML_ANAK) and $pegawai_lokal->JML_ANAK == "") 
			$data["JML_ANAK"]	=	isset($pegawai->jumlahAnak)	?	$pegawai->jumlahAnak : 	"";
		if(isset($pegawai_lokal->JML_ISTRI) and $pegawai_lokal->JML_ISTRI == "") 
			$data["JML_ISTRI"]	=	isset($pegawai->jumlahIstriSuami)	?	$pegawai->jumlahIstriSuami : 	"";
		if(isset($pegawai_lokal->KARTU_PEGAWAI) and $pegawai_lokal->KARTU_PEGAWAI == "") 
			$data["KARTU_PEGAWAI"]	=	isset($pegawai->noSeriKarpeg)	?	$pegawai->noSeriKarpeg : 	"";
		if(isset($pegawai_lokal->KEDUDUKAN_HUKUM_ID) and $pegawai_lokal->KEDUDUKAN_HUKUM_ID == "") 
			$data["KEDUDUKAN_HUKUM_ID"]	=	isset($pegawai->kedudukanPnsId)	?	$pegawai->kedudukanPnsId : 	"";
		if(isset($pegawai_lokal->KODECEPAT) and $pegawai_lokal->KODECEPAT == "") 
			$data["KODECEPAT"]	=	isset($pegawai->instansiKerjaKodeCepat)	?	$pegawai->instansiKerjaKodeCepat : 	"";
		if(isset($pegawai_lokal->KPKN_ID) and $pegawai_lokal->KPKN_ID == "") 
			$data["KPKN_ID"]	=	isset($pegawai->kpknId)	?	$pegawai->kpknId : 	"";
		if(isset($pegawai_lokal->MK_BULAN) and $pegawai_lokal->MK_BULAN == "" or $pegawai->mkBulan != "") 
			$data["MK_BULAN"]	=	isset($pegawai->mkBulan)	?	$pegawai->mkBulan : 	"";
		if(isset($pegawai_lokal->MK_TAHUN) and $pegawai_lokal->MK_TAHUN == "" or $pegawai->mkTahun != "") 
			$data["MK_TAHUN"]	=	isset($pegawai->mkTahun)	?	$pegawai->mkTahun : 	"";
		if(isset($pegawai_lokal->NAMA) and $pegawai_lokal->NAMA == "") 
			$data["NAMA"]	=	isset($pegawai->nama)	?	$pegawai->nama : 	"";
		if(isset($pegawai_lokal->NIK) and $pegawai_lokal->NIK == "") 
			$data["NIK"]	=	isset($pegawai->nik)	?	$pegawai->nik : 	"";
		
		if($pegawai_lokal->NIP_LAMA == "") 
			$data["NIP_LAMA"]	=	isset($pegawai->nipLama)	?	trim($pegawai->nipLama) : 	"";
		if($pegawai_lokal->NO_ASKES == "") 
			$data["NO_ASKES"]	=	isset($pegawai->noAskes)	?	trim($pegawai->noAskes) : 	"";
		if(trim($pegawai_lokal->NO_BEBAS_NARKOBA) == "") 
			$data["NO_BEBAS_NARKOBA"]	=	isset($pegawai->noSuratKeteranganBebasNarkoba)	?	$pegawai->noSuratKeteranganBebasNarkoba : 	"";
		if($pegawai_lokal->NO_CATATAN_POLISI == "") 
			$data["NO_CATATAN_POLISI"]	=	isset($pegawai->skck)	?	$pegawai->skck : 	"";
		if($pegawai_lokal->NO_SURAT_DOKTER == "") 
			$data["NO_SURAT_DOKTER"]	=	isset($pegawai->noSuratKeteranganDokter)	?	$pegawai->noSuratKeteranganDokter : 	"";
		if(trim($pegawai_lokal->NO_TASPEN) == "") 
			$data["NO_TASPEN"]	=	isset($pegawai->noTaspen)	?	trim($pegawai->noTaspen) : 	"";
		if(trim($pegawai_lokal->NOMOR_HP) == "") 
			$data["NOMOR_HP"]	=	isset($pegawai->noHp)	?	$pegawai->noHp : 	"";
		if($pegawai_lokal->NOMOR_SK_CPNS == "") 
			$data["NOMOR_SK_CPNS"]	=	isset($pegawai->nomorSkCpns)	?	$pegawai->nomorSkCpns : 	"";
		if($pegawai_lokal->NOMOR_SK_CPNS == "") 
			$data["NOMOR_SK_CPNS"]	=	isset($pegawai->nomorSkCpns)	?	$pegawai->nomorSkCpns : 	"";
		if($pegawai_lokal->NPWP == "") 
			$data["NPWP"]	=	isset($pegawai->noNpwp)	?	$pegawai->noNpwp : 	"";
		// if($pegawai_lokal->PENDIDIKAN == "" or $pegawai->pendidikanTerakhirId != "") 
		$data["PENDIDIKAN"]	=	isset($pegawai->pendidikanTerakhirNama)	?	$pegawai->pendidikanTerakhirNama : 	"";
		$data["PENDIDIKAN_ID"]	=	isset($pegawai->pendidikanTerakhirId)	?	$pegawai->pendidikanTerakhirId : 	"";
		
		if($pegawai_lokal->SATUAN_KERJA_INDUK_ID == "") 
			$data["SATUAN_KERJA_INDUK_ID"]	=	isset($pegawai->satuanKerjaIndukId)	?	$pegawai->satuanKerjaIndukId : 	"";
		if($pegawai_lokal->SATUAN_KERJA_INDUK_NAMA == "") 
			$data["SATUAN_KERJA_INDUK_NAMA"]	=	isset($pegawai->satuanKerjaIndukNama)	?	$pegawai->satuanKerjaIndukNama : 	"";
		if($pegawai_lokal->SATUAN_KERJA_KERJA_ID == "") 
			$data["SATUAN_KERJA_KERJA_ID"]	=	isset($pegawai->satuanKerjaKerjaId)	?	trim($pegawai->satuanKerjaKerjaId) : 	"";
		if(trim($pegawai_lokal->SATUAN_KERJA_NAMA) == "") 
			$data["SATUAN_KERJA_NAMA"]	=	isset($pegawai->satuanKerjaKerjaNama)	?	trim($pegawai->satuanKerjaKerjaNama) : 	"";
		if($pegawai_lokal->STATUS_CPNS_PNS == "") 
			$data["STATUS_CPNS_PNS"]	=	isset($pegawai->statusPegawai)	?	$pegawai->statusPegawai : 	"";
		if(trim($pegawai_lokal->STATUS_HIDUP) == "") 
			$data["STATUS_HIDUP"]	=	isset($pegawai->statusHidup)	?	$pegawai->statusHidup : 	"";
		if(trim($pegawai_lokal->TEMPAT_LAHIR) == "") 
			$data["TEMPAT_LAHIR"]	=	isset($pegawai->tempatLahir)	?	$pegawai->tempatLahir : 	"";
		if(trim($pegawai_lokal->TEMPAT_LAHIR_ID) == "") 
			$data["TEMPAT_LAHIR_ID"]	=	isset($pegawai->tempatLahirId)	?	$pegawai->tempatLahirId : 	"";
		if($pegawai_lokal->TGL_BEBAS_NARKOBA == "") 
			$data["TGL_BEBAS_NARKOBA"]	=	isset($pegawai->tglSuratKeteranganBebasNarkoba)	?	date('Y-m-d', strtotime($pegawai->tglSuratKeteranganBebasNarkoba)) : 	"";
		if($pegawai_lokal->TGL_CATATAN_POLISI == "") 
			$data["TGL_CATATAN_POLISI"]	=	isset($pegawai->tglSkck)	?	date('Y-m-d', strtotime($pegawai->tglSkck)) : 	"";
		if(trim($pegawai_lokal->TGL_LAHIR) == "") 
			$data["TGL_LAHIR"]	=	isset($pegawai->tglLahir)	?	date('Y-m-d', strtotime($pegawai->tglLahir)) : 	"";
		if($pegawai_lokal->TGL_MENINGGAL == "") 
			$data["TGL_MENINGGAL"]	=	isset($pegawai->tglMeninggal)	?	date('Y-m-d', strtotime($pegawai->tglMeninggal)) : 	"";
		if($pegawai_lokal->TGL_NPWP == "") 
			$data["TGL_NPWP"]	=	isset($pegawai->tglNpwp)	?	date('Y-m-d', strtotime($pegawai->tglNpwp)) : 	"";
		if($pegawai_lokal->TGL_SURAT_DOKTER == "") 
			$data["TGL_SURAT_DOKTER"]	=	isset($pegawai->tglSuratKeteranganDokter)	?	date('Y-m-d', strtotime($pegawai->tglSuratKeteranganDokter)) : 	"";
		// if($pegawai_lokal->TK_PENDIDIKAN == "" or $pegawai->tkPendidikanTerakhirId != "") 
			$data["TK_PENDIDIKAN"]	=	isset($pegawai->tkPendidikanTerakhirId)	?	$pegawai->tkPendidikanTerakhirId : 	"";
			
		if($pegawai_lokal->TMT_CPNS == "") 
			$data["TMT_CPNS"]	=	isset($pegawai->tmtCpns)	?	date('Y-m-d', strtotime($pegawai->tmtCpns)) : 	"";
		if($pegawai_lokal->TGL_SK_CPNS == "") 
			$data["TGL_SK_CPNS"]	=	isset($pegawai->tglSkCpns)	?	date('Y-m-d', strtotime($pegawai->tglSkCpns)) : 	"";
		if($pegawai_lokal->TMT_GOLONGAN == "" or $pegawai->tmtGolAkhir != "") 
			$data["TMT_GOLONGAN"]	=	isset($pegawai->tmtGolAkhir)	?	date('Y-m-d', strtotime($pegawai->tmtGolAkhir)) : 	"";
		if($pegawai_lokal->TMT_JABATAN == "" or $pegawai->tmtJabatan != "") 
			$data["TMT_JABATAN"]	=	isset($pegawai->tmtJabatan)	?	date('Y-m-d', strtotime($pegawai->tmtJabatan)) : 	"";
		if($pegawai_lokal->LOKASI_KERJA_ID == "") 
			$data["LOKASI_KERJA_ID"]	=	isset($pegawai->lokasiKerjaId)	?	$pegawai->lokasiKerjaId : 	"";
		if($pegawai_lokal->UNOR_ID == "") 
			$data["UNOR_ID"]	=	isset($pegawai->tmtPns)	?	$pegawai->tmtPns : 	"";
		if($pegawai_lokal->UNOR_ID == "") 
			$data["UNOR_ID"]	=	isset($pegawai->unorId)	?	$pegawai->unorId : 	"";
		if($pegawai_lokal->UNOR_INDUK_ID == "") 
			$data["UNOR_INDUK_ID"]	=	isset($pegawai->unorIndukId)	?	$pegawai->unorIndukId : 	"";

		//var_dump($pegawai_lokal);
		if(isset($pegawai_lokal->NIP_BARU)){
			
			if(isset($pegawai->id) and $pegawai->id != "") {
				//echo $pegawai_lokal->NIP_BARU;
				//var_dump($data);
				$status = $this->pegawai_model->update_where("NIP_BARU",$pegawai_lokal->NIP_BARU, $data);
				//var_dump($status);
				if($status)
				{
					echo "Berhasil Update";
				}	
			}else{
				echo "Data BKN tidak didapatkan..";
			}
			
		}else{
			if($this->pegawai_model->insert($data)){
				{
					echo "Berhasil Tambah data";
				}
			}
		}
	}
	public function getpegawaibknnew()
	{
		$this->load->model('pegawai/golongan_model');
        $golongan_data = $this->golongan_model->find_all();
        $agolongan = array();
        foreach($golongan_data as $row)
		{
			$agolongan[$row->ID] = $row->NAMA_PANGKAT;
		}
		 

		$nip_baru 	= $this->input->post('nip_bkn');
		$pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU",$nip_baru);		

		$this->load->library('Api_bkn');
		$api_lipi = new Api_bkn;

		// get data utama
        $jsonsatkers = $api_lipi->data_utama($nip_baru);

        $decodejson = json_decode($jsonsatkers);
        $decodejson = json_decode($decodejson);
        $pegawai 	= json_decode($decodejson->data);

        $data 		= array();
        //jika sudah ada di data lokal
        // print_r($pegawai);
        $hasil = false;
		$msg =  "";
		if(!isset($pegawai->id)){
			$response ['success']= false;
        	$response ['msg']= "Data BKN tidak ditemukan";
			$response['data'] = $pegawai;
        	echo json_encode($response);    
            exit();
		}
        if(isset($pegawai->id) and $pegawai->id != "" and isset($pegawai_lokal->NIP_BARU))
        {
			
        	if(isset($pegawai->id) and $pegawai->id != "") 
				//var_dump($pegawai->id);
				$data["PNS_ID"]	=	isset($pegawai->id)	?	$pegawai->id : 	"";
				

			// print_r($pegawai_lokal);
	        if(isset($pegawai_lokal->AGAMA_ID) and $pegawai_lokal->AGAMA_ID == "") 
	        	$data["AGAMA_ID"]	=	isset($pegawai->agamaId)	?	$pegawai->agamaId : 	"";
	        if(isset($pegawai_lokal->AKTE_KELAHIRAN) and $pegawai_lokal->AKTE_KELAHIRAN == "") 
				$data["AKTE_KELAHIRAN"]	=	isset($pegawai->akteKelahiran)	?	$pegawai->akteKelahiran : 	"";
			if(isset($pegawai_lokal->AKTE_MENINGGAL) and $pegawai_lokal->AKTE_MENINGGAL == "") 
				$data["AKTE_MENINGGAL"]	=	isset($pegawai->akteMeninggal)	?	$pegawai->akteMeninggal : 	"";
			if(isset($pegawai_lokal->ALAMAT) and $pegawai_lokal->ALAMAT == "") 
				$data["ALAMAT"]	=	isset($pegawai->alamat)	?	$pegawai->alamat : 	"";
			if(isset($pegawai_lokal->BPJS) and $pegawai_lokal->BPJS == "") 
				$data["BPJS"]	=	isset($pegawai->bpjs)	?	$pegawai->bpjs : 	"";
			if(trim($pegawai_lokal->EMAIL) == "") 
				$data["EMAIL"]	=	isset($pegawai->email)	?	$pegawai->email : 	"";
			if(isset($pegawai_lokal->GELAR_BELAKANG) and $pegawai_lokal->GELAR_BELAKANG == "") 
				$data["GELAR_BELAKANG"]	=	isset($pegawai->gelarBelakang)	?	$pegawai->gelarBelakang : 	"";

			if(isset($pegawai_lokal->GELAR_DEPAN) and $pegawai_lokal->GELAR_DEPAN == "") 
				$data["GELAR_DEPAN"]	=	isset($pegawai->gelarDepan) && $pegawai->getlarDepan!="-"	?	$pegawai->gelarDepan : 	"";
			if(isset($pegawai_lokal->GOL_AWAL_ID) and $pegawai_lokal->GOL_AWAL_ID == "") 
				$data["GOL_AWAL_ID"]	=	isset($pegawai->golRuangAwalId)	?	$pegawai->golRuangAwalId : 	"";
			if($pegawai->golRuangAkhirId>$pegawai_lokal->GOL_ID) 
				$data["GOL_ID"]	=	isset($pegawai->golRuangAkhirId)	?	$pegawai->golRuangAkhirId : 	"";
			if(isset($pegawai_lokal->INSTANSI_INDUK_ID) and $pegawai_lokal->INSTANSI_INDUK_ID == "") 
				$data["INSTANSI_INDUK_ID"]	=	isset($pegawai->instansiIndukId)	?	$pegawai->instansiIndukId : 	"";
			if(isset($pegawai_lokal->INSTANSI_INDUK_NAMA) and $pegawai_lokal->INSTANSI_INDUK_NAMA == "") 
				$data["INSTANSI_INDUK_NAMA"]	=	isset($pegawai->instansiIndukNama)	?	$pegawai->instansiIndukNama : 	"";
			if(isset($pegawai_lokal->INSTANSI_KERJA_ID) and $pegawai_lokal->INSTANSI_KERJA_ID == "") 
				$data["INSTANSI_KERJA_ID"]	=	isset($pegawai->instansiKerjaId)	?	$pegawai->instansiKerjaId : 	"";
			if(isset($pegawai_lokal->INSTANSI_KERJA_NAMA) and $pegawai_lokal->INSTANSI_KERJA_NAMA == "") 
				$data["INSTANSI_KERJA_NAMA"]	=	isset($pegawai->instansiKerjaNama)	?	$pegawai->instansiKerjaNama : 	"";
			//if(isset($pegawai_lokal->JABATAN_ID) and $pegawai_lokal->JABATAN_ID == "") 
			if(isset($pegawai_lokal->JABATAN_ID) and $pegawai_lokal->JABATAN_ID == "") 
				
				
				$data["JABATAN_ID"]	=	isset($pegawai->jabatanStrukturalId)	?	$pegawai->jabatanStrukturalId : 	"";
			
			if(isset($pegawai->jenisJabatanId)) 
				$data["JENIS_JABATAN_ID"]	=	isset($pegawai->jenisJabatanId)	?	$pegawai->jenisJabatanId : 	"";
			if(isset($pegawai->jenisJabatan)) 
				$data["JENIS_JABATAN_NAMA"]	=	isset($pegawai->jenisJabatan)	?	$pegawai->jenisJabatan : 	"";
			if(isset($pegawai_lokal->JENIS_KAWIN_ID) and $pegawai_lokal->JENIS_KAWIN_ID == "") 
				$data["JENIS_KAWIN_ID"]	=	isset($pegawai->jenisKawinId)	?	$pegawai->jenisKawinId : 	"";

			if(isset($pegawai_lokal->JENIS_KELAMIN) and $pegawai_lokal->JENIS_KELAMIN == ""){
				$jenis_kelamin = isset($pegawai->jenisKelamin)	?	TRIM($pegawai->jenisKelamin) : 	"";
				if($jenis_kelamin == "Pria")
					$jk = "M";
				if($jenis_kelamin == "Wanita")
					$jk = "F";

				$data["JENIS_KELAMIN"]	=	$jk;
			} 
				
			if(isset($pegawai_lokal->JENIS_PEGAWAI_ID) and $pegawai_lokal->JENIS_PEGAWAI_ID == "") 
				$data["JENIS_PEGAWAI_ID"]	=	isset($pegawai->jenisPegawaiId)	?	$pegawai->jenisPegawaiId : 	"";
			if(isset($pegawai_lokal->JML_ANAK) and $pegawai_lokal->JML_ANAK == "") 
				$data["JML_ANAK"]	=	isset($pegawai->jumlahAnak)	?	$pegawai->jumlahAnak : 	"";
			if(isset($pegawai_lokal->JML_ISTRI) and $pegawai_lokal->JML_ISTRI == "") 
				$data["JML_ISTRI"]	=	isset($pegawai->jumlahIstriSuami)	?	$pegawai->jumlahIstriSuami : 	"";
			if(isset($pegawai_lokal->KARTU_PEGAWAI) and $pegawai_lokal->KARTU_PEGAWAI == "") 
				$data["KARTU_PEGAWAI"]	=	isset($pegawai->noSeriKarpeg)	?	$pegawai->noSeriKarpeg : 	"";
			if(isset($pegawai_lokal->KEDUDUKAN_HUKUM_ID) and $pegawai_lokal->KEDUDUKAN_HUKUM_ID == "") 
				$data["KEDUDUKAN_HUKUM_ID"]	=	isset($pegawai->kedudukanPnsId)	?	$pegawai->kedudukanPnsId : 	"";
			if(isset($pegawai_lokal->KODECEPAT) and $pegawai_lokal->KODECEPAT == "") 
				$data["KODECEPAT"]	=	isset($pegawai->instansiKerjaKodeCepat)	?	$pegawai->instansiKerjaKodeCepat : 	"";
			if(isset($pegawai_lokal->KPKN_ID) and $pegawai_lokal->KPKN_ID == "") 
				$data["KPKN_ID"]	=	isset($pegawai->kpknId)	?	$pegawai->kpknId : 	"";
			if(isset($pegawai_lokal->MK_BULAN) and $pegawai_lokal->MK_BULAN == "") 
				$data["MK_BULAN"]	=	isset($pegawai->mkBulan)	?	$pegawai->mkBulan : 	"";
			if(isset($pegawai_lokal->MK_TAHUN) and $pegawai_lokal->MK_TAHUN == "") 
				$data["MK_TAHUN"]	=	isset($pegawai->mkTahun)	?	$pegawai->mkTahun : 	"";
			if(isset($pegawai_lokal->NAMA) and $pegawai_lokal->NAMA == "") 
				$data["NAMA"]	=	isset($pegawai->nama)	?	$pegawai->nama : 	"";
			if(isset($pegawai_lokal->NIK) and $pegawai_lokal->NIK == "") 
				$data["NIK"]	=	isset($pegawai->nik)	?	$pegawai->nik : 	"";
			
			if(isset($pegawai_lokal->NIP_LAMA) and $pegawai_lokal->NIP_LAMA == "") 
				$data["NIP_LAMA"]	=	isset($pegawai->nipLama)	?	$pegawai->nipLama : 	"";
			if(isset($pegawai_lokal->NO_ASKES) and $pegawai_lokal->NO_ASKES == "") 
				$data["NO_ASKES"]	=	isset($pegawai->noAskes)	?	$pegawai->noAskes : 	"";
			if(isset($pegawai_lokal->NO_BEBAS_NARKOBA) and $pegawai_lokal->NO_BEBAS_NARKOBA == "") 
				$data["NO_BEBAS_NARKOBA"]	=	isset($pegawai->noSuratKeteranganBebasNarkoba)	?	$pegawai->noSuratKeteranganBebasNarkoba : 	"";
			if(isset($pegawai_lokal->NO_CATATAN_POLISI) and $pegawai_lokal->NO_CATATAN_POLISI == "") 
				$data["NO_CATATAN_POLISI"]	=	isset($pegawai->skck)	?	$pegawai->skck : 	"";
			if(isset($pegawai_lokal->NO_SURAT_DOKTER) and $pegawai_lokal->NO_SURAT_DOKTER == "") 
				$data["NO_SURAT_DOKTER"]	=	isset($pegawai->noSuratKeteranganDokter)	?	$pegawai->noSuratKeteranganDokter : 	"";
			if(isset($pegawai_lokal->NO_TASPEN) and $pegawai_lokal->NO_TASPEN == "") 
				$data["NO_TASPEN"]	=	isset($pegawai->noTaspen)	?	$pegawai->noTaspen : 	"";
			if(isset($pegawai_lokal->NOMOR_HP) and $pegawai_lokal->NOMOR_HP == "") 
				$data["NOMOR_HP"]	=	isset($pegawai->noHp)	?	$pegawai->noHp : 	"";
			if(isset($pegawai_lokal->NOMOR_SK_CPNS) and $pegawai_lokal->NOMOR_SK_CPNS == "") 
				$data["NOMOR_SK_CPNS"]	=	isset($pegawai->nomorSkCpns)	?	$pegawai->nomorSkCpns : 	"";
			if(isset($pegawai_lokal->NPWP) and $pegawai_lokal->NPWP == "") 
				$data["NPWP"]	=	isset($pegawai->noNpwp)	?	$pegawai->noNpwp : 	"";
			if($pegawai->pendidikanTerakhirId!="") 
				$data["PENDIDIKAN"]	=	isset($pegawai->pendidikanTerakhirId)	?	$pegawai->pendidikanTerakhirId : 	"";
			
			if(isset($pegawai_lokal->SATUAN_KERJA_INDUK_ID) and $pegawai_lokal->SATUAN_KERJA_INDUK_ID == "") 
				$data["SATUAN_KERJA_INDUK_ID"]	=	isset($pegawai->satuanKerjaIndukId)	?	$pegawai->satuanKerjaIndukId : 	"";
			if(isset($pegawai_lokal->SATUAN_KERJA_INDUK_NAMA) and $pegawai_lokal->SATUAN_KERJA_INDUK_NAMA == "") 
				$data["SATUAN_KERJA_INDUK_NAMA"]	=	isset($pegawai->satuanKerjaIndukNama)	?	$pegawai->satuanKerjaIndukNama : 	"";
			if(isset($pegawai_lokal->SATUAN_KERJA_KERJA_ID) and $pegawai_lokal->SATUAN_KERJA_KERJA_ID == "") 
				$data["SATUAN_KERJA_KERJA_ID"]	=	isset($pegawai->satuanKerjaKerjaId)	?	$pegawai->satuanKerjaKerjaId : 	"";
			if(isset($pegawai_lokal->SATUAN_KERJA_NAMA) and $pegawai_lokal->SATUAN_KERJA_NAMA == "") 
				$data["SATUAN_KERJA_NAMA"]	=	isset($pegawai->satuanKerjaKerjaNama)	?	$pegawai->satuanKerjaKerjaNama : 	"";
			if(isset($pegawai_lokal->STATUS_CPNS_PNS) and $pegawai_lokal->STATUS_CPNS_PNS == "") 
				$data["STATUS_CPNS_PNS"]	=	isset($pegawai->statusPegawai)	?	$pegawai->statusPegawai : 	"";
			if(isset($pegawai_lokal->STATUS_HIDUP) and $pegawai_lokal->STATUS_HIDUP == "") 
				$data["STATUS_HIDUP"]	=	isset($pegawai->statusHidup)	?	$pegawai->statusHidup : 	"";
			if(isset($pegawai_lokal->TEMPAT_LAHIR) and trim($pegawai_lokal->TEMPAT_LAHIR) == "") 
				$data["TEMPAT_LAHIR"]	=	isset($pegawai->tempatLahir)	?	$pegawai->tempatLahir : 	"";
			if(isset($pegawai_lokal->TEMPAT_LAHIR_ID) and trim($pegawai_lokal->TEMPAT_LAHIR_ID) == "") 
				$data["TEMPAT_LAHIR_ID"]	=	isset($pegawai->tempatLahirId)	?	$pegawai->tempatLahirId : 	"";
			if(isset($pegawai_lokal->TGL_BEBAS_NARKOBA) and $pegawai_lokal->TGL_BEBAS_NARKOBA == "") 
				$data["TGL_BEBAS_NARKOBA"]	=	isset($pegawai->tglSuratKeteranganBebasNarkoba)	?	date('Y-m-d', strtotime($pegawai->tglSuratKeteranganBebasNarkoba)) : 	"";
			if(isset($pegawai_lokal->TGL_CATATAN_POLISI) and $pegawai_lokal->TGL_CATATAN_POLISI == "") 
				$data["TGL_CATATAN_POLISI"]	=	isset($pegawai->tglSkck)	?	$pegawai->tglSkck : 	"";
			if(isset($pegawai_lokal->TGL_LAHIR) and $pegawai_lokal->TGL_LAHIR == "") 
				$data["TGL_LAHIR"]	=	isset($pegawai->tglLahir)	?	$pegawai->tglLahir : 	"";
			if(isset($pegawai_lokal->TGL_MENINGGAL) and $pegawai_lokal->TGL_MENINGGAL == "") 
				$data["TGL_MENINGGAL"]	=	isset($pegawai->tglMeninggal)	?	$pegawai->tglMeninggal : 	"";
			if(isset($pegawai_lokal->TGL_NPWP) and $pegawai_lokal->TGL_NPWP == "") 
				$data["TGL_NPWP"]	=	isset($pegawai->tglNpwp)	?	$pegawai->tglNpwp : 	"";
			if(isset($pegawai_lokal->TGL_SURAT_DOKTER) and $pegawai_lokal->TGL_SURAT_DOKTER == "") 
				$data["TGL_SURAT_DOKTER"]	=	isset($pegawai->tglSuratKeteranganDokter)	?	$pegawai->tglSuratKeteranganDokter : 	"";
			if(isset($pegawai_lokal->TK_PENDIDIKAN) and $pegawai_lokal->TK_PENDIDIKAN == "") 
				$data["TK_PENDIDIKAN"]	=	isset($pegawai->tkPendidikanTerakhirId)	?	$pegawai->tkPendidikanTerakhirId : 	"";
			if(isset($pegawai_lokal->TMT_CPNS) and $pegawai_lokal->TMT_CPNS == "") 
				$data["TMT_CPNS"]	=	isset($pegawai->tmtCpns)	?	$pegawai->tmtCpns : 	"";
			if(isset($pegawai_lokal->TGL_SK_CPNS) and $pegawai_lokal->TGL_SK_CPNS == "") 
				$data["TGL_SK_CPNS"]	=	isset($pegawai->tglSkCpns)	?	$pegawai->tglSkCpns : 	"";
			if(isset($pegawai->tmtGolAkhir)) 
				$data["TMT_GOLONGAN"]	=	isset($pegawai->tmtGolAkhir)	?	$pegawai->tmtGolAkhir : 	"";
			if(isset($$pegawai->tmtJabatan)) 
				$data["TMT_JABATAN"]	=	isset($pegawai->tmtJabatan)	?	$pegawai->tmtJabatan : 	"";
			if(isset($pegawai_lokal->LOKASI_KERJA_ID) and $pegawai_lokal->LOKASI_KERJA_ID == "") 
				$data["LOKASI_KERJA_ID"]	=	isset($pegawai->lokasiKerjaId)	?	$pegawai->lokasiKerjaId : 	"";
			//if(isset($pegawai_lokal->UNOR_ID) and $pegawai_lokal->UNOR_ID == "") 
			//	$data["UNOR_ID"]	=	isset($pegawai->tmtPns)	?	$pegawai->tmtPns : 	"";
			if(isset($pegawai_lokal->UNOR_ID) and $pegawai_lokal->UNOR_ID == "") 
				$data["UNOR_ID"]	=	isset($pegawai->unorId)	?	$pegawai->unorId : 	"";
			if(isset($pegawai_lokal->UNOR_INDUK_ID) and $pegawai_lokal->UNOR_INDUK_ID == "") 
				$data["UNOR_INDUK_ID"]	=	isset($pegawai->unorIndukId)	?	$pegawai->unorIndukId : 	"";
			

			//var_dump($pegawai_lokal);

			$jabatanId = "";
			if($pegawai->jabatanStrukturalId!=""){
				$jabatanId = $pegawai->jabatanStrukturalId;
			}else if($pegawai->jabatanFungsionalUmumId!=""){
				$jabatanId = $pegawai->jabatanFungsionalUmumId;
			}else if($pegawai->jabatanFungsionalId!=""){
				$jabatanId = $pegawai->jabatanFungsionalId;
			}

			$data["JABATAN_ID"]	=	$jabatanId;
			$data["JABATAN_IDx"]	=	$jabatanId;


			if(isset($pegawai_lokal->NIP_BARU)){
				if(isset($pegawai->id) and $pegawai->id != "") {
					if($this->pegawai_model->update_where("NIP_BARU",trim($pegawai_lokal->NIP_BARU), $data))
					{
						$hasil = true;
						$msg =  "Berhasil update data";
						$response['data'] = $pegawai;
					}else{
						$hasil = false;
						$msg =  "DOUBLE NIP atau PNS_ID untuk PNS ID: $pegawai->id";
					}	
					
				}else{
					$hasil = false;
					$msg =  "Data BKN tidak didapatkan..";
				}
				
			}else{
				if($this->pegawai_model->insert($data)){
					{
						echo "Berhasil Tambah data";
					}
				}
			}
       
        }else{
			$data["PNS_ID"]	=	isset($pegawai->id)	?	$pegawai->id : 	"";
			$data["NIP_BARU"]	=	isset($pegawai->nipBaru)	?	$pegawai->nipBaru : 	"";
			if($pegawai->agamaId != "")
        		$data["AGAMA_ID"]	=	isset($pegawai->agamaId)	? (int)$pegawai->agamaId : 	"";
        	if($pegawai->akteKelahiran != "")
				$data["AKTE_KELAHIRAN"]	=	isset($pegawai->akteKelahiran)	?	$pegawai->akteKelahiran : 	"";
			if($pegawai->akteMeninggal != "")
				$data["AKTE_MENINGGAL"]	=	isset($pegawai->akteMeninggal)	?	$pegawai->akteMeninggal : 	"";
			$data["ALAMAT"]	=	isset($pegawai->alamat)	?	$pegawai->alamat : 	"";
			$data["BPJS"]	=	isset($pegawai->bpjs)	?	$pegawai->bpjs : 	"";
			$data["EMAIL"]	=	isset($pegawai->email)	?	$pegawai->email : 	"";
			$data["GELAR_BELAKANG"]	=	isset($pegawai->gelarBelakang)	?	$pegawai->gelarBelakang : 	"";

			$data["GELAR_DEPAN"]	=	isset($pegawai->gelarDepan)	?	$pegawai->gelarDepan : 	"";
			$data["GOL_AWAL_ID"]	=	isset($pegawai->golRuangAwalId)	?	$pegawai->golRuangAwalId : 	"";
			if($pegawai->golRuangAwal != "")
				$data["GOL_ID"]	=	isset($pegawai->golRuangAwal)	?	(int)$pegawai->golRuangAwal : 	"";
			if($pegawai->instansiIndukId != "")
				$data["INSTANSI_INDUK_ID"]	=	isset($pegawai->instansiIndukId)	?	$pegawai->instansiIndukId : 	"";
			if($pegawai->instansiIndukNama != "")
				$data["INSTANSI_INDUK_NAMA"]	=	isset($pegawai->instansiIndukNama)	?	$pegawai->instansiIndukNama : 	"";
			if($pegawai->instansiKerjaId != "")
				$data["INSTANSI_KERJA_ID"]	=	isset($pegawai->instansiKerjaId)	?	$pegawai->instansiKerjaId : 	"";
			$data["INSTANSI_KERJA_NAMA"]	=	isset($pegawai->instansiKerjaNama)	?	$pegawai->instansiKerjaNama : 	"";
			$data["JABATAN_ID"]	=	isset($pegawai->jabatanStrukturalId)	?	$pegawai->jabatanStrukturalId : 	"";
		
			$data["JENIS_JABATAN_ID"]	=	isset($pegawai->jenisJabatanId)	?	$pegawai->jenisJabatanId : 	"";
			$data["JENIS_JABATAN_NAMA"]	=	isset($pegawai->jenisJabatan)	?	$pegawai->jenisJabatan : 	"";
			$data["JENIS_KAWIN_ID"]	=	isset($pegawai->jenisKawinId)	?	$pegawai->jenisKawinId : 	"";
			$jenis_kelamin = isset($pegawai->jenisKelamin)	?	$pegawai->jenisKelamin : 	"";

				if($jenis_kelamin == "Pria")
					$jk = "M";
				if($jenis_kelamin == "Wanita")
					$jk = "F";
			
			$data["JENIS_KELAMIN"]	=	$jk;
			$data["JENIS_PEGAWAI_ID"]	=	isset($pegawai->jenisPegawaiId)	?	$pegawai->jenisPegawaiId : 	"";
			$data["JML_ANAK"]	=	isset($pegawai->jumlahAnak)	?	$pegawai->jumlahAnak : 	"";
			$data["JML_ISTRI"]	=	isset($pegawai->jumlahIstriSuami)	?	$pegawai->jumlahIstriSuami : 	"";
			$data["KARTU_PEGAWAI"]	=	isset($pegawai->noSeriKarpeg)	?	$pegawai->noSeriKarpeg : 	"";
			$data["KEDUDUKAN_HUKUM_ID"]	=	isset($pegawai->kedudukanPnsId)	?	$pegawai->kedudukanPnsId : 	"";
			$data["KODECEPAT"]	=	isset($pegawai->instansiKerjaKodeCepat)	?	$pegawai->instansiKerjaKodeCepat : 	"";
			$data["KPKN_ID"]	=	isset($pegawai->kpknId)	?	$pegawai->kpknId : 	"";
			$data["MK_BULAN"]	=	isset($pegawai->mkBulan)	?	$pegawai->mkBulan : 	"";
			$data["MK_TAHUN"]	=	isset($pegawai->mkTahun)	?	$pegawai->mkTahun : 	"";
			$data["NAMA"]	=	isset($pegawai->nama)	?	$pegawai->nama : 	"";
			$data["NIK"]	=	isset($pegawai->nik)	?	$pegawai->nik : 	"";
			$data["NIP_LAMA"]	=	isset($pegawai->nipLama)	?	$pegawai->nipLama : 	"";
			$data["NO_ASKES"]	=	isset($pegawai->noAskes)	?	$pegawai->noAskes : 	"";
			$data["NO_BEBAS_NARKOBA"]	=	isset($pegawai->noSuratKeteranganBebasNarkoba)	?	$pegawai->noSuratKeteranganBebasNarkoba : 	"";
			$data["NO_CATATAN_POLISI"]	=	isset($pegawai->skck)	?	$pegawai->skck : 	"";
			$data["NO_SURAT_DOKTER"]	=	isset($pegawai->noSuratKeteranganDokter)	?	$pegawai->noSuratKeteranganDokter : 	"";
			$data["NO_TASPEN"]	=	isset($pegawai->noTaspen)	?	$pegawai->noTaspen : 	"";
			$data["NOMOR_HP"]	=	isset($pegawai->noHp)	?	$pegawai->noHp : 	"";
			$data["NOMOR_SK_CPNS"]	=	isset($pegawai->nomorSkCpns)	?	$pegawai->nomorSkCpns : 	"";
			$data["NPWP"]	=	isset($pegawai->noNpwp)	?	$pegawai->noNpwp : 	"";
			$data["PENDIDIKAN"]	=	isset($pegawai->pendidikanTerakhirId)	?	$pegawai->pendidikanTerakhirId : 	"";
			if($pegawai->satuanKerjaIndukId != "")
				$data["SATUAN_KERJA_INDUK_ID"]	=	isset($pegawai->satuanKerjaIndukId)	?	$pegawai->satuanKerjaIndukId : 	"";
			if($pegawai->satuanKerjaIndukNama != "")
				$data["SATUAN_KERJA_INDUK_NAMA"]	=	isset($pegawai->satuanKerjaIndukNama)	?	$pegawai->satuanKerjaIndukNama : 	"";
			if($pegawai->satuanKerjaKerjaId != "")
				$data["SATUAN_KERJA_KERJA_ID"]	=	isset($pegawai->satuanKerjaKerjaId)	?	$pegawai->satuanKerjaKerjaId : 	"";
			if($pegawai->satuanKerjaKerjaNama != "")
				$data["SATUAN_KERJA_NAMA"]	=	isset($pegawai->satuanKerjaKerjaNama)	?	$pegawai->satuanKerjaKerjaNama : 	"";
			if($pegawai->statusPegawai != "")
				$data["STATUS_CPNS_PNS"]	=	isset($pegawai->statusPegawai)	?	$pegawai->statusPegawai : 	"";
			if($pegawai->statusHidup != "")
				$data["STATUS_HIDUP"]	=	isset($pegawai->statusHidup)	?	$pegawai->statusHidup : 	"";
			if($pegawai->tempatLahir != "")
				$data["TEMPAT_LAHIR"]	=	isset($pegawai->tempatLahir)	?	$pegawai->tempatLahir : 	"";
			if($pegawai->tempatLahirId != "")
				$data["TEMPAT_LAHIR_ID"]	=	isset($pegawai->tempatLahirId)	?	$pegawai->tempatLahirId : 	"";
			if($pegawai->tglSuratKeteranganBebasNarkoba != "")
				$data["TGL_BEBAS_NARKOBA"]	=	isset($pegawai->tglSuratKeteranganBebasNarkoba)	?	date('Y-m-d', strtotime($pegawai->tglSuratKeteranganBebasNarkoba)) : 	"";
			if($pegawai->tglSkck != "")
				$data["TGL_CATATAN_POLISI"]	=	isset($pegawai->tglSkck)	?	date('Y-m-d', strtotime($pegawai->tglSkck)) : 	"";
			if($pegawai->tglLahir != "")
				$data["TGL_LAHIR"]	=	isset($pegawai->tglLahir)	?	date('Y-m-d', strtotime($pegawai->tglLahir)) : 	"";
			if($pegawai->tglMeninggal != "")
				$data["TGL_MENINGGAL"]	=	isset($pegawai->tglMeninggal)	?	date('Y-m-d', strtotime($pegawai->tglMeninggal)) : 	"";
			if($pegawai->tglNpwp != "")
				$data["TGL_NPWP"]	=	isset($pegawai->tglNpwp)	?	date('Y-m-d', strtotime($pegawai->tglNpwp)) : 	"";
			if($pegawai->tglSuratKeteranganDokter != "")
				$data["TGL_SURAT_DOKTER"]	=	isset($pegawai->tglSuratKeteranganDokter)	?	date('Y-m-d', strtotime($pegawai->tglSuratKeteranganDokter)) : 	"";
			if($pegawai->tkPendidikanTerakhirId != "")
			$data["TK_PENDIDIKAN"]	=	isset($pegawai->tkPendidikanTerakhirId)	?	$pegawai->tkPendidikanTerakhirId : 	"";
			if($pegawai->tmtPns != "")
				$data["TMT_CPNS"]	=	isset($pegawai->tmtPns)	?	date('Y-m-d', strtotime($pegawai->tmtPns)) : 	"";
			if($pegawai->tmtCpns != "")
				$data["TMT_CPNS"]	=	isset($pegawai->tmtCpns)	?	date('Y-m-d', strtotime($pegawai->tmtCpns)) : 	"";
			$data["TGL_SK_CPNS"]	=	isset($pegawai->tglSkCpns)	?	date('Y-m-d', strtotime($pegawai->tglSkCpns)) : 	"";
				$data["GOL_ID"]	=	isset($pegawai->golRuangAkhirId)	?	$pegawai->golRuangAkhirId : 	"";
			$data["TMT_GOLONGAN"]	=	isset($pegawai->tmtGolAkhir)	?	date('Y-m-d', strtotime($pegawai->tmtGolAkhir)) : 	"";
			$data["TMT_JABATAN"]	=	isset($pegawai->tmtJabatan)	?	date('Y-m-d', strtotime($pegawai->tmtJabatan)) : 	"";
			$data["LOKASI_KERJA_ID"]	=	isset($pegawai->lokasiKerjaId)	?	$pegawai->lokasiKerjaId : 	"";
			$data["UNOR_ID"]	=	isset($pegawai->unorId)	?	$pegawai->unorId : 	"";
			$data["UNOR_INDUK_ID"]	=	isset($pegawai->unorIndukId)	?	$pegawai->unorIndukId : 	"";

			if($pegawai->jenisJabatanId != "")
				$data["JENIS_JABATAN_IDx"]	=	isset($pegawai->jenisJabatanId)	?	$pegawai->jenisJabatanId : 	"";

			$data["status_pegawai"]	=	1;

			if($id = $this->pegawai_model->insert($data))
			{
				$hasil = true;
				$msg =  "Berhasil Tambah data ".$id;
				log_activity($this->auth->user_id(), $msg . ' : ' . $this->input->ip_address(), 'pegawai');
			}
		}
		
		// data riwayat golongan
		$jsonrwt_golongan = $api_lipi->data_rwt_golongan_bkn($nip_baru);
        $jsonrwt_golongan = json_decode($jsonrwt_golongan);
        $jsonrwt_golongan = json_decode($jsonrwt_golongan);
        $datajsonrwt_golongan = $jsonrwt_golongan->data;
        foreach($datajsonrwt_golongan as $row)
		{
			/*
			[idPns] => A8ACA7B5BC1C3912E040640A040269BB
            [golonganId] => 22
            [golongan] => II/b
            [skNomor] => 13-09/01380/KEP/IV/1991
            [skTanggal] => 02-09-1991
            [tmtGolongan] => 01-04-1991
            [noPertekBkn] => 13-09/01380/KEP/IV/1991
            [tglPertekBkn] => 02-09-1991
            [jumlahKreditUtama] => null
            [jumlahKreditTambahan] => null
            [jenisKPId] => 101
            [jenisKPNama] => Reguler
            [masaKerjaGolonganTahun] => 3
            [masaKerjaGolonganBulan] => 1
            */
			$idPns 		= $row->idPns;
			$golonganId = $row->golonganId;
			$golongan 	= $row->golongan;
			$skNomor 	= TRIM($row->skNomor);
			$skTanggal 	= $row->skTanggal;
			$tmtGolongan 	= $row->tmtGolongan;
			$noPertekBkn 	= $row->noPertekBkn;
			$tglPertekBkn 	= $row->tglPertekBkn;
        	$this->riwayat_kepangkatan_model->where("SK_NOMOR",$skNomor);  
        	$count_golongan = $this->riwayat_kepangkatan_model->count_all($idPns);
        	if($count_golongan <= 0){
        		$data = array();
		        $NIP_BARU = $pegawai_lokal->NIP_BARU;
		        $data["ID_BKN"] = $row->id;
		        $data["PNS_NIP"] = $pegawai_lokal->NIP_BARU;
		        $data["PNS_ID"] = $pegawai_lokal->PNS_ID;
		        $data["PNS_NAMA"] = $pegawai_lokal->NAMA;
		        $data["JENIS_KP"] = $row->jenisKPNama;
		        $data["ID_GOLONGAN"] = $row->golonganId;
		        $data["SK_NOMOR"] = $row->skNomor;
		        $data["NOMOR_BKN"] = $row->noPertekBkn;
		        $data["MK_GOLONGAN_TAHUN"] = $row->masaKerjaGolonganTahun;
		        $data["MK_GOLONGAN_BULAN"] = $row->masaKerjaGolonganBulan;

		        $data["KODE_JENIS_KP"] = $row->jenisKPId;
		        $data["GOLONGAN"] = $row->golongan;
		        $data["PANGKAT"] = isset($agolongan[$row->golonganId]) ? $agolongan[$row->golonganId] : "";
		        
		        $data["TMT_GOLONGAN"] 	= date("Y-m-d", strtotime($row->tmtGolongan));
		        $data["SK_TANGGAL"] 	= date("Y-m-d", strtotime($row->skTanggal));
		        if(empty($data["TMT_GOLONGAN"])){
		            unset($data["TMT_GOLONGAN"]);
		        }
		        if(empty($data["SK_TANGGAL"])){
		            unset($data["SK_TANGGAL"]);
		        }
			    $insert_id = $this->riwayat_kepangkatan_model->insert($data);
			    log_activity_pegawai($NIP_BARU,$this->auth->user_id(),"Tambah data rwt_golongan", 'pegawai');
        	}

		}
		// end history golongan
        $response ['success']= $hasil;
        $response ['msg']= $msg;
		
        echo json_encode($response);    
	}
	private function get_data_skp($nip = "",$tahun = ""){
		$this->load->library('Api_skp');
		$api_skp = new Api_skp;
		$json_skp = $api_skp->getnilai_skp(trim($nip),$tahun);
		$data_skp = json_decode($json_skp);
		return $data_skp;
	}
	private function generateskp($nip = "",$tahun = ""){
		$this->load->model('pegawai/riwayat_prestasi_kerja_model');
		$record_skp = $this->cek_prestasi_kerja($nip,$tahun);
		$id_data = "";
		if(isset($record_skp->ID)){
			$id_data = $record_skp->ID;
		}
		$this->pegawai_model->where("NIP_BARU",$nip);
        $pegawai_data = $this->pegawai_model->find_first_row();  
        $dataskp = $this->get_data_skp($nip,$tahun);
        if($pegawai_data->PNS_ID != ""){
        	$data = array();
	        $data["PNS_ID"] = $pegawai_data->PNS_ID;
	        $data["PNS_NAMA"] = $pegawai_data->NAMA;
	        $data["PNS_NIP"] = $pegawai_data->NIP_BARU;
	        $data['TAHUN'] 		= $tahun;
	        $data['NILAI_SKP'] = $dataskp->nilaiSKP;
	        $data['NILAI_PROSENTASE_SKP'] = $dataskp->nilaiBobotSKP;
	        $data['NILAI_SKP_AKHIR'] = $data['NILAI_PROSENTASE_SKP']/100*$data['NILAI_SKP'];

	        $data['NILAI_PROSENTASE_PERILAKU'] = $dataskp->nilaiBobotPerilaku;
	        $data['NILAI_PERILAKU'] = $dataskp->nilaiPerilaku;
	        $data['PERILAKU_KOMITMEN'] = $dataskp->nilaiDetailPerilaku->Komitmen;
	        $data['PERILAKU_INTEGRITAS'] = $dataskp->nilaiDetailPerilaku->Integritas;
	        $data['PERILAKU_DISIPLIN'] = $dataskp->nilaiDetailPerilaku->Disiplin;
	        $data['PERILAKU_KERJASAMA'] = $dataskp->nilaiDetailPerilaku->Kerjasama;
	        $data['PERILAKU_ORIENTASI_PELAYANAN'] = $dataskp->nilaiDetailPerilaku->{'Orientasi Pelayanan'};

	         
	        $data['NILAI_PERILAKU_AKHIR'] = $dataskp->nilaiAkhirPerilaku;
	        $data['NILAI_PPK'] = $dataskp->nilaiPrestasiKerja;
	        if(isset($id_data) && !empty($id_data)){
	            $this->riwayat_prestasi_kerja_model->update($id_data,$data);
	        }
	        else $this->riwayat_prestasi_kerja_model->insert($data);	
        }
	}
	private function cek_prestasi_kerja($nip = "",$tahun = ""){
		$this->load->model('pegawai/riwayat_prestasi_kerja_model');
		$this->riwayat_prestasi_kerja_model->where("TAHUN",$tahun);
		$data_prestasi_kerja = $this->riwayat_prestasi_kerja_model->find_by("PNS_NIP",$nip);
		return $data_prestasi_kerja;
	}
	public function createakun()
    {
    	$this->auth->restrict($this->permissionCreateUser);
    	$nip 	= trim($this->input->post('kode'));
    	$this->load->model('users/user_model');   
    	$this->load->model('users/roles_user_model');   
    	$pegawai_data = $this->pegawai_model->find_by("NIP_BARU",$nip);
    	if($pegawai_data->PNS_ID != ""){

    	}
    	date_default_timezone_set("Asia/Bangkok");
    	// if (!$this->input->is_ajax_request()) {
   		// 	die("Only ajax request");
		// }
		
        
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if($pegawai_data->EMAIL == ""){
        	$response['msg'] = "
                Silahkan lengkapi data email terlebih dahulu
            ";
            echo json_encode($response);
            exit();
        }
        
        $datauser = $this->user_model->find_by("username",$nip);
        $result = false;
        if(!isset($datauser->id)){
        	$password = "Data.12345";
            $result = $this->save_user($nip,$password,$nip,$pegawai_data->NAMA,$pegawai_data->EMAIL);
            if($result){
            	log_activity($this->auth->user_id(), 'Create User from list pegawai ID : '.$result." IP ".$this->input->ip_address(), 'users');
            	$id_roles_users = $this->save_user_role($result,"2");
            	log_activity($this->auth->user_id(), 'Create roles User from list pegawai : '.$result."_".$id_roles_users." IP ".$this->input->ip_address(), 'users');
            }
        }else{
        	$response['msg'] = "User telah terdaftar";
            echo json_encode($response);
            exit();
        }
        if($result){
 	       	$response ['success']= true;
    		$response ['msg']= "Buat user berhasil, Username ".$nip.", Password : Data.12345";
    	}else{
    		$response ['success']= false;
    		$response ['msg']= "Ada kesalahan";
    	}
        echo json_encode($response);    
    }
    private function save_user($username = "",$password = "",$nip = "",$nama = "",$email = "")
    {
     	
        $data =  array();
        $data['timezone']       = "UP7";
        $data['username']       = trim($username);
        $data['password']       = trim($password); 
        $data['active']         = 1; 
        $data['nip']            = trim($nip);
        $data['display_name']   = trim($nama);
        $data['email']          = trim($email);
        $data['role_id']        = "2";
        $return = $this->user_model->insert($data);
        
        return $return;

    }
    public function reset_password()
    {
    	$this->auth->restrict($this->permissionResetPassword);
     	$nip_base64 	= trim($this->input->post('kode'));
     	$nip = base64_decode($nip_base64);
     	$nip = str_replace("_rahasia", "", $nip);
     	$newpassword 	= trim($this->input->post('newpassword'));
     	
        $data =  array();
        $data['password']       = $newpassword ? $newpassword : trim("Rahasia.Kita!@123"); 
        $data['active']         = 1; 
        $this->user_model->skip_validation(true);
        $result = $this->user_model->update_where("username",$nip,$data);
        if($result){
 	       	$response ['success']= true;
    		// $response ['msg']= "Password berhasil direset menjadi : Data.12345";
    		$response ['msg']= "Password berhasil direset";
    		log_activity($this->auth->user_id(), 'Password '.$nip.' berhasil direset menjadi : Rahasia.Kita!@123 new : '.$newpassword." IP ".$this->input->ip_address(), 'users');
    	}else{
    		$response ['success']= false;
    		$response ['msg']= "Reset Password gagal";
    		log_activity($this->auth->user_id(), 'Reset Password '.$nip.' gagal dari : '.$result.$newpassword." IP ".$this->input->ip_address(), 'users');
    	}
    	echo json_encode($response);
    	exit();
    }
    private function save_user_role($user_id = "",$role_id = "")
    {
     	
        $data =  array();
        $data['user_id']          = $user_id;
        $data['role_id']       = $role_id; 
        $this->roles_user_model->skip_validation(true);
        $return = $this->roles_user_model->insert($data);
        return $return;

    }
    public function download_updatemandiri(){
		$advanced_search_filters  = $_GET;
		$this->db->start_cache();
		if($advanced_search_filters){
			$filters = $advanced_search_filters;
			if($filters['unit_id_cb']){
				$this->db->group_start();
				$this->db->where('vw."ID"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);	
				$this->db->group_end();
			}
			if($filters['nama_cb']){
				$this->update_mandiri_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");	
			}
			if($filters['nip_cb']){
				$this->update_mandiri_model->like('upper("NIP_BARU")',strtoupper($filters['nip_key']),"BOTH");	
			}
			if($filters['umur_cb']){
				if($filters['umur_operator']=="="){
					$this->update_mandiri_model->where('calc_age("TGL_LAHIR")',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">="){
					$this->update_mandiri_model->where('calc_age("TGL_LAHIR") >=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">"){
					$this->update_mandiri_model->where('calc_age("TGL_LAHIR") >',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<="){
					$this->update_mandiri_model->where('calc_age("TGL_LAHIR") <=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<"){
					$this->update_mandiri_model->where('calc_age("TGL_LAHIR")<',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="!="){
					$this->update_mandiri_model->where('calc_age("TGL_LAHIR") !=',$filters['umur_key']*12);	
				}
			}
			if($filters['eselon_cb']){
				$this->update_mandiri_model->where('upper("NAMA") LIKE \''.strtoupper($filters['nip_key']).'%\'');	
			}
			if($filters['golongan_cb']){
				$this->update_mandiri_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));	
			}
			if($filters['tkp_cb']){
				$this->update_mandiri_model->where('"TK_PENDIDIKAN"',strtoupper($filters['tingkat_pendidikan']));	
			}
			if($filters['level_cb']){
				$this->update_mandiri_model->where("LEVEL_UPDATE",$filters['level']);	
			}
			
			
		}
		
		$this->db->stop_cache();
		$output=array();
		$output['draw']=$draw;
		$datapegwai = $this->update_mandiri_model->find_notif($this->UNOR_ID);

        $this->load->library('LibOpenTbs');
        $template_name = trim($this->settings_lib->item('site.pathuploaded')).'template_update_mandiri.xlsx';
        $TBS = $this->libopentbs->TBS;
        $TBS->LoadTemplate($template_name, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
        $TBS->MergeBlock('r', $datapegwai);
        $TBS->MergeField('a', array(
            'tanggal'=>date("d M Y"),
        )); 

        $output_file_name = 'request_update_mandiri_pegawai.xlsx';
        $output_file_name = str_replace('.', '_'.date('Y-m-d').'.', $output_file_name);
        $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.
	}
	public function download_updatemandiri1(){
		$advanced_search_filters  = $_GET;
		$this->db->start_cache();
		if($advanced_search_filters){
			$filters = $advanced_search_filters;
			if($filters['unit_id_cb']){
				$this->db->group_start();
				$this->db->where('vw."ID"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);	
				$this->db->group_end();
			}
			if($filters['nama_cb']){
				$this->update_mandiri_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");	
			}
			if($filters['nip_cb']){
				$this->update_mandiri_model->like('upper("NIP_BARU")',strtoupper($filters['nip_key']),"BOTH");	
			}
			if($filters['umur_cb']){
				if($filters['umur_operator']=="="){
					$this->update_mandiri_model->where('calc_age("TGL_LAHIR")',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">="){
					$this->update_mandiri_model->where('calc_age("TGL_LAHIR") >=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">"){
					$this->update_mandiri_model->where('calc_age("TGL_LAHIR") >',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<="){
					$this->update_mandiri_model->where('calc_age("TGL_LAHIR") <=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<"){
					$this->update_mandiri_model->where('calc_age("TGL_LAHIR")<',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="!="){
					$this->update_mandiri_model->where('calc_age("TGL_LAHIR") !=',$filters['umur_key']*12);	
				}
			}
			if($filters['eselon_cb']){
				$this->update_mandiri_model->where('upper("NAMA") LIKE \''.strtoupper($filters['nip_key']).'%\'');	
			}
			if($filters['golongan_cb']){
				$this->update_mandiri_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));	
			}
			if($filters['tkp_cb']){
				$this->update_mandiri_model->where('"TK_PENDIDIKAN"',strtoupper($filters['tingkat_pendidikan']));	
			}
			if($filters['level_cb']){
				$this->update_mandiri_model->where("LEVEL_UPDATE",$filters['level']);	
			}
			
			
		}
		
		$this->db->stop_cache();
		$output=array();
		$output['draw']=$draw;
		$datapegwai = $this->update_mandiri_model->find_notif1($this->UNOR_ID);
        $this->load->library('LibOpenTbs');
        $template_name = trim($this->settings_lib->item('site.pathuploaded')).'template_update_mandiri.xlsx';
        $TBS = $this->libopentbs->TBS;
        $TBS->LoadTemplate($template_name, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
        $TBS->MergeBlock('r', $datapegwai);
        $TBS->MergeField('a', array(
            'tanggal'=>date("d M Y"),
        )); 

        $output_file_name = 'request_update_mandiri_pegawai.xlsx';
        $output_file_name = str_replace('.', '_'.date('Y-m-d').'.', $output_file_name);
        $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.
	}

	public function updatepensiun(){
		$nip_baru 	= $this->input->post('nip');
		$status_pensiun = $this->input->post('status_pensiun');
		if($status_pensiun!=null){
			$dataupdate["TMT_PENSIUN"] = $this->input->post('tmt_pensiun');
		}

		$dataupdate["STATUS_HIDUP"] =  $status_pensiun != "99x" ? true : false;
		$status_pensiun = $status_pensiun == "99x" ? "99" : $status_pensiun;
		$dataupdate["KEDUDUKAN_HUKUM_ID"] = $status_pensiun;
		
		
		
		$this->pegawai_model->update_where("NIP_BARU", $nip_baru, $dataupdate);

		log_activity($this->auth->user_id(), 'Set Status Pensiu : '.$nip_baru."_".$status_pensiun." IP ".$this->input->ip_address(), 'users');
		
		echo json_encode(array("status"=>"success"));
	}
    
}

	
