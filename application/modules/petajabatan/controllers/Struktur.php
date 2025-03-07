<?php 
class Struktur extends Admin_Controller{
    public $agolongan = array('21'=>"IIa",'22'=>"IIb",'31'=>"IIIa",'32'=>"IIIb",'41'=>"IVa",'42'=>"IVb");
    public $UNOR_ID = null;
    protected $permissionFiltersatker   = 'Pegawai.Kepegawaian.Filtersatker';
    protected $permissionEselon1   = 'Pegawai.Kepegawaian.permissionEselon1';
    public function __construct(){
        parent::__construct();
        $this->load->model('pegawai/unitkerja_model');
        $this->load->model('baperjakat/baperjakat_model');
        
        if($this->auth->has_permission($this->permissionFiltersatker)){
            $this->UNOR_ID = $this->pegawai_model->getunor_induk($this->auth->username());
        }
        if($this->auth->has_permission($this->permissionEselon1)){
            $this->UNOR_ID = $this->pegawai_model->getunor_eselon1($this->auth->username());
        }
    }
    public function index(){
        Template::set_view("struktur/test.php");
        Template::render();
    }
    public function baperjakat(){
        Template::set_view("struktur/baperjakat.php");
        $id = $this->uri->segment(4);
        if($id != ""){
            $dataunor = $this->unitkerja_model->find_by("ID",$id);
            $this->UNOR_ID = isset($dataunor->ID) ? $dataunor->ID : "";
            Template::set('selectedunor',$dataunor);    
        } 
        Template::set('UNOR_ID',$this->UNOR_ID);    

        // get periode baperjakat aktif
        $baperjakataktif = $this->baperjakat_model->find_aktif();
        $id_periode      = isset($baperjakataktif->ID) ? $baperjakataktif->ID : "";
        // END PERIODE AKTIF
        Template::set('baperjakataktif', $baperjakataktif);
        Template::set('id_periode', $id_periode);

        Template::set("collapse",true);
        Template::set('toolbar_title', "Baperjakat");
        Template::render();
    }
    public function test(){
        Template::render();
    }
    public function load_tree($state='',$id=''){
        if($state==''){ //first tree
            if($id==''){
                $id = ROOT_UNOR_ID;
            }
            $root =  $this->getRootNode($id);
            echo json_encode($root);
        }
        else if ($state=='children'){
            $children = $this->getChildren($id);
            echo json_encode(array(
                'children'=>$children
            ));
        }
        else if ($state=='siblings'){

        }
        else if ($state=='families'){

        }
    }

    public function load_tree_baper($state='',$id=''){
        if($state==''){ //first tree
            if($id==''){
                $id = "8ae483a8641f817901641fce97d21d1b";
            }
            //die($id." ad");
            $root =  $this->getRootNodebaper($id);
            echo json_encode($root);
        }
        else if($state=='-'){ //first tree
            if($id==''){
                $id = "8ae483a8641f817901641fce97d21d1b";
            }
            //die($id." ad");
            $root =  $this->getRootNodebaper($id);
            echo json_encode($root);
        }
        else if ($state=='children'){
            $children = $this->getChildrenbaperprint($id);
            echo json_encode(array(
                'children'=>$children
            ));
        }
        else if ($state=='siblings'){

        }
        else if ($state=='families'){

        }
    }
    public function getRootNode($id){
        $unors = $this->unitkerja_model->get_cache();
        $unor  = isset($unors[$id])?$unors[$id]:null;
        if($unor){
            $node = new stdClass;
            $node->id =  $unor->ID;
            $node->title = $unor->NAMA_JABATAN;
            if($unor->NAMA_UNOR){
              $node->name =  $unor->NAMA_UNOR;
            }
            else $node->name =  "-";

            if($unor->NAMA_PEJABAT){
              $node->pejabat_nama =  $unor->NAMA_PEJABAT;
            }
            else $node->pejabat_nama =  "-";
              
            
            if($unor->TOTAL_CHILD>0){
                $node->relationship = '001';
            }
            else $node->relationship = '000';            

            return $node;
        }
        return null;
    }
    public function getRootNodebaper($id){
        $unors = $this->unitkerja_model->get_unor();
        //print_r($unors);
        $unor  = isset($unors[$id])?$unors[$id]:null;
        if($unor){
            $node = new stdClass;
            $node->id =  $unor->ID;
            $node->title = $unor->NAMA_JABATAN;
            if($unor->NAMA_UNOR){
              $node->name =  $unor->NAMA_UNOR;
            }
            else $node->name =  "-";

            if($unor->NAMA){
              $node->pejabat_nama =  $unor->NAMA;
            }
            else $node->pejabat_nama =  "-";
              
            
            if($unor->TOTAL_CHILD>0){
                $node->relationship = '001';
            }
            else $node->relationship = '000';            

            return $node;
        }
        return null;
    }
    public function getChildren($id){
        $unors = $this->unitkerja_model->get_cache();
        $children = array();
        $parent_node = null;
        foreach($unors as $unor){
            if($unor->ID==$id){
                $parent_node = $unor;
            }
        }
        foreach($unors as $unor){
            if($unor->DIATASAN_ID==$id){
                $node = new stdClass;
                $node->id =  $unor->ID;
                $node->title = $unor->NAMA_JABATAN;
                if($unor->NAMA_UNOR){
                $node->name =  $unor->NAMA_UNOR;
                }
                else $node->name =  "-";

                if($unor->NAMA_PEJABAT){
                    $node->pejabat_nama =  $unor->NAMA_PEJABAT;
                }
                else $node->pejabat_nama =  "-";
                

                $relationship_flag = array();
                $relationship_flag[]  = 1 ; //has parent
                 //has siblings
                if($parent_node->TOTAL_CHILD>1){
                    $relationship_flag[] = 1;
                }
                else {
                    $relationship_flag[] = 0;
                }
                //has child
                if($unor->TOTAL_CHILD>0){
                    $relationship_flag[] = 1;
                }
                else {
                    $relationship_flag[] = 0;
                }
                $node->relationship = implode("",$relationship_flag); 

                $children [] = $node;  
            }
        }
        return $children;
    }
    public function getChildrenbaper($id){
        $unors = $this->unitkerja_model->get_unor();
        $children = array();
        $parent_node = null;
        foreach($unors as $unor){
            if($unor->ID==$id){
                $parent_node = $unor;
            }
        }
        foreach($unors as $unor){
            if($unor->DIATASAN_ID==$id){
                $node = new stdClass;
                $node->id =  $unor->ID;
                $node->title = $unor->NAMA_JABATAN;
                if($unor->NAMA_UNOR){
                    $node->name =  $unor->NAMA_UNOR;
                }
                else $node->name =  "-";

                if($unor->NAMA){
                    $node->pejabat_nama =  '<a href="#" class="btn .btn-sm bg-olive margin" onclick="return carikandidat(\''.trim($unor->ID).'\');"> Lihat Kandidat </a><div id="divcandidat_'.$unor->ID.'"></div>';
                }
                else $node->pejabat_nama =  "-";
                

                $relationship_flag = array();
                $relationship_flag[]  = 1 ; //has parent
                 //has siblings
                if($parent_node->TOTAL_CHILD>1){
                    $relationship_flag[] = 1;
                }
                else {
                    $relationship_flag[] = 0;
                }
                //has child
                if($unor->TOTAL_CHILD>0){
                    $relationship_flag[] = 1;
                }
                else {
                    $relationship_flag[] = 0;
                }
                $node->relationship = implode("",$relationship_flag); 

                $children [] = $node;  
            }
        }
        return $children;
    }
    public function getChildrenbaperprint($id){
        $unors = $this->unitkerja_model->get_unor();
        $children = array();
        $parent_node = null;
        foreach($unors as $unor){
            if($unor->ID==$id){
                $parent_node = $unor;
            }
        }
        foreach($unors as $unor){
            if($unor->DIATASAN_ID==$id){
                $node = new stdClass;
                $node->id =  $unor->ID;
                $node->title = $unor->NAMA_JABATAN."<br>(".$unor->NAMA.")";
                if($unor->NAMA_UNOR){
                    $node->name =  $unor->NAMA_UNOR;
                }
                else $node->name =  "-";

                if($unor->NAMA){
                    $node->pejabat_nama =  '<b>Kandidat</b> <br><div id="divcandidat_'.$unor->ID.'">'.$this->getkandidat($unor->ID).'</div>';
                }
                else $node->pejabat_nama =  "-";
                

                $relationship_flag = array();
                $relationship_flag[]  = 1 ; //has parent
                 //has siblings
                if($parent_node->TOTAL_CHILD>1){
                    $relationship_flag[] = 1;
                }
                else {
                    $relationship_flag[] = 0;
                }
                //has child
                if($unor->TOTAL_CHILD>0){
                    $relationship_flag[] = 1;
                }
                else {
                    $relationship_flag[] = 0;
                }
                $node->relationship = implode("",$relationship_flag); 

                $children [] = $node;  
            }
        }
        return $children;
    }
    public function getkandidat($unitkerja = "")
    {
        
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/unitkerja_model');
        $this->load->model('petajabatan/kandidat_baperjakat_model');

        // get periode baperjakat aktif
        $baperjakataktif = $this->baperjakat_model->find_aktif();
        $id_periode      = isset($baperjakataktif->ID) ? $baperjakataktif->ID : "";
        // END PERIODE AKTIF
        $dataunitkerja = $this->unitkerja_model->find_by("ID",$unitkerja);
        $eselon4 = isset($dataunitkerja->ESELON_4) ? $dataunitkerja->ESELON_4 : "";
        $eselon3 = isset($dataunitkerja->ESELON_3) ? $dataunitkerja->ESELON_3 : "";
        $eselon2 = isset($dataunitkerja->ESELON_2) ? $dataunitkerja->ESELON_2 : "";
        $ESELON_ID = isset($dataunitkerja->ESELON_ID) ? $dataunitkerja->ESELON_ID : "";
        $JABATAN_ID = isset($dataunitkerja->JABATAN_ID) ? $dataunitkerja->JABATAN_ID : "";
        $NAMA_JABATAN = isset($dataunitkerja->NAMA_JABATAN) ? $dataunitkerja->NAMA_JABATAN : "";
        $PEMIMPIN_PNS_ID = isset($dataunitkerja->PEMIMPIN_PNS_ID) ? trim($dataunitkerja->PEMIMPIN_PNS_ID) : "";
        $UNOR_INDUK = isset($dataunitkerja->UNOR_INDUK) ? trim($dataunitkerja->UNOR_INDUK) : ""; // ID SATKER
        $data_peminpin = $this->pegawai_model->find_by("PNS_ID",trim($PEMIMPIN_PNS_ID));
        //print_r($dataunitkerja);
        //die("masuk");
        $NIP_PEJABAT = isset($data_peminpin->NIP_BARU) ? $data_peminpin->NIP_BARU : "";
        // cek apakah sudah pernah ada kandidat sebelumnya
        $this->kandidat_baperjakat_model->where("NIP NOT IN ('".$NIP_PEJABAT."')");// JFT
        $this->kandidat_baperjakat_model->order_by("URUTAN_UPDATE",ASC);
        $this->kandidat_baperjakat_model->where("ID_PERIODE",$id_periode);
        $datakandidat = $this->kandidat_baperjakat_model->find_all($unitkerja,date("Y"));
        if (isset($datakandidat) && is_array($datakandidat) && count($datakandidat)):
            // cek apakah kandidat sudah ada yang dipilih
            $jmlterpilih = $this->kandidat_baperjakat_model->count_terpilih($unitkerja,date("Y"));
            if($jmlterpilih >= 1){
                $kandidats = $this->kandidat_baperjakat_model->find_terpilih($unitkerja,date("Y"));
            }else{
                $kandidats = $datakandidat;
            }
        else:
            // Ambil dari hanya unor itu saja
            $this->pegawai_model->where("UNOR_ID",$unitkerja);
            // ambil dari satu satker
            //$this->pegawai_model->where("UNOR_ID",$unitkerja);
            
            // jika eselon 2 / 3 ambil dari pejabat jenjang dibawahnya
            if($ESELON_ID == 31 or $ESELON_ID == 32){
                // JIKA ESELON 3, GOLONGANNYA PALING RENDAH III/D PALING TINGGI IV/A
                // tapi diturunkan minimal golongannya
                if($ESELON_ID == 32) // eselon 3b
                    $this->pegawai_model->where('(GOL_ID >= 33 and GOL_ID <= 42)');
                else
                    $this->pegawai_model->where('(GOL_ID >= 40 and GOL_ID <= 43)'); // IV/A - IV/B untuk eselon 3.A
                
                //$this->pegawai_model->where('jabatan."JENIS_JABATAN"',"2");// JFT
                    $this->pegawai_model->or_where('PNS_ID IN (SELECT "PEMIMPIN_PNS_ID" FROM "unitkerja" where "DIATASAN_ID" = \''.$unitkerja.'\')');
            }else{
                // JIKA ESELON 4, GOLONGANNYA PALING RENDAH III/B PALING TINGGI III/C
                // tapi diturunkan minimal golongannya
                if($ESELON_ID == 42) // eselon 4b
                    $this->pegawai_model->where('(GOL_ID >= 31 and GOL_ID <= 34)');
                else
                    $this->pegawai_model->where('(GOL_ID >= 32 and GOL_ID <= 35)');

                //$this->pegawai_model->where('jabatan."JENIS_JABATAN"',"2");// JFT
            }
            // end 2/3
            //$this->pegawai_model->order_by("rwt_assesment","DESC");
            $this->pegawai_model->order_by("GOL_ID","DESC");
            $this->pegawai_model->order_by("TK_PENDIDIKAN","DESC");

            $kandidats = $this->pegawai_model->limit(10)->find_baperjakat();
            $URUTAN = 1;
            // rotasi 1, promosi 2
            $kategori = "2"; // promosi
            foreach($kandidats as $kandidat){
                $NIP_BARU = $kandidat->NIP_BARU;
                if($kandidat->ESELON_ID == $ESELON_ID && $kandidat->ESELON_ID != ""){
                    $kategori = "1"; // rotasi
                }else{
                    $kategori = "2"; // promosi
                }
                //echo $kandidat->ESELON_ID."".$ESELON_ID." ".$NIP_BARU." kategori ".$kategori."<br>";
                $this->kandidat_baperjakat_model->delete_where(array('NIP' => trim($NIP_BARU),'UNOR_ID' => trim($unitkerja),'ID_PERIODE' => trim($id_periode)));

                $data = array();
                $col = 0;
                $data['NIP']                = trim($NIP_BARU); 
                $data['UNOR_ID']            = trim($unitkerja); 
                $data['URUTAN_DEFAULT']     = trim($URUTAN); 
                $data['TAHUN']              = date("Y"); 
                $data['URUTAN_UPDATE']      = trim($URUTAN);

                $data['NILAI_ASSESMENT']    = (double)trim($kandidat->rwt_assesment);
                $data['PANGGOL']            = trim($kandidat->GOL_ID);
                $data['PENDIDIKAN']         = trim($kandidat->TK_PENDIDIKAN); 
                $data['HUKDIS']             = trim($kandidat->rwt_hukuman); 
                $data['ID_PERIODE']         = trim($id_periode);
                $data['JABATAN_ID']         = trim($JABATAN_ID); 
                $data['NAMA_JABATAN']       = trim($NAMA_JABATAN);  
                $data['KATEGORI']       = trim($kategori);  
                $this->kandidat_baperjakat_model->insert($data);

                $URUTAN++;
            }
            $this->kandidat_baperjakat_model->where("NIP NOT IN ('".$NIP_PEJABAT."')");// JFT
            $this->kandidat_baperjakat_model->order_by("URUTAN_UPDATE",ASC);
            $this->kandidat_baperjakat_model->where("ID_PERIODE",$id_periode);
            $kandidats = $this->kandidat_baperjakat_model->find_all($unitkerja,date("Y"));
        endif;
        
        //print_r($kandidats);
        $output = $this->load->view('struktur/getkandidat',array('unitkerja'=>$unitkerja,'dataunitkerja'=>$dataunitkerja,'kandidats'=>$kandidats,'NIP_PEJABAT'=>$NIP_PEJABAT),true);   
         
        return $output;
    }
    public function carikandidat()
    {
        $unitkerja = $this->input->get('unitkerja');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/unitkerja_model');
        $this->load->model('petajabatan/kandidat_baperjakat_model');

        // get periode baperjakat aktif
        $baperjakataktif = $this->baperjakat_model->find_aktif();
        $id_periode      = isset($baperjakataktif->ID) ? $baperjakataktif->ID : "";
        // END PERIODE AKTIF
        $dataunitkerja = $this->unitkerja_model->find_by("ID",$unitkerja);
        $eselon4 = isset($dataunitkerja->ESELON_4) ? $dataunitkerja->ESELON_4 : "";
        $eselon3 = isset($dataunitkerja->ESELON_3) ? $dataunitkerja->ESELON_3 : "";
        $eselon2 = isset($dataunitkerja->ESELON_2) ? $dataunitkerja->ESELON_2 : "";
        $JABATAN_ID = isset($dataunitkerja->JABATAN_ID) ? $dataunitkerja->JABATAN_ID : "";
        $NAMA_JABATAN = isset($dataunitkerja->NAMA_JABATAN) ? $dataunitkerja->NAMA_JABATAN : "";
        // cek apakah sudah pernah ada kandidat sebelumnya
        $this->kandidat_baperjakat_model->order_by("URUTAN_UPDATE",ASC);
        $this->kandidat_baperjakat_model->where("ID_PERIODE",$id_periode);
        $datakandidat = $this->kandidat_baperjakat_model->find_all($unitkerja,date("Y"));
        if (isset($datakandidat) && is_array($datakandidat) && count($datakandidat)):
            // cek apakah kandidat sudah ada yang dipilih
            $jmlterpilih = $this->kandidat_baperjakat_model->count_terpilih($unitkerja,date("Y"));
            if($jmlterpilih >= 1){
                $kandidats = $this->kandidat_baperjakat_model->find_terpilih($unitkerja,date("Y"));
            }else{
                $kandidats = $datakandidat;
            }
        else:
            $this->pegawai_model->where("UNOR_ID",$unitkerja);
            // jika eselon 2 / 3 ambil dari pejabat jenjang dibawahnya
            if($eselon4 == ""){
                $this->pegawai_model->or_where('PNS_ID IN (SELECT "PEMIMPIN_PNS_ID" FROM "unitkerja" where "DIATASAN_ID" = \''.$unitkerja.'\')');
            }
            // end 2/3
            //$this->pegawai_model->order_by("rwt_assesment","DESC");
            $this->pegawai_model->order_by("GOL_ID","DESC");
            $this->pegawai_model->order_by("TK_PENDIDIKAN","DESC");

            $kandidats = $this->pegawai_model->limit(10)->find_baperjakat();
            $URUTAN = 1;
            
            foreach($kandidats as $kandidat){
                $NIP_BARU = $kandidat->NIP_BARU;
                $data = array();
                $col = 0;
                $data['NIP']                = trim($NIP_BARU); 
                $data['UNOR_ID']            = trim($unitkerja); 
                $data['URUTAN_DEFAULT']     = trim($URUTAN); 
                $data['TAHUN']              = date("Y"); 
                $data['URUTAN_UPDATE']      = trim($URUTAN);

                $data['NILAI_ASSESMENT']    = (double)trim($kandidat->rwt_assesment);
                $data['PANGGOL']            = trim($kandidat->GOL_ID);
                $data['PENDIDIKAN']         = trim($kandidat->TK_PENDIDIKAN); 
                $data['HUKDIS']             = trim($kandidat->rwt_hukuman); 
                $data['ID_PERIODE']         = trim($id_periode);
                $data['JABATAN_ID']         = trim($JABATAN_ID); 
                $data['NAMA_JABATAN']         = trim($NAMA_JABATAN); 
                $this->kandidat_baperjakat_model->insert($data);

                $URUTAN++;
            }
        endif;
        
        //print_r($kandidats);
        //die("unit ".$unitkerja);
        $output = $this->load->view('struktur/getkandidat',array('unitkerja'=>$unitkerja,'dataunitkerja'=>$dataunitkerja,'kandidats'=>$kandidats),true);   
         
        echo $output;
        die();
    }
    public function aturkandidat($unitkerja = null){
        //$unitkerja = $this->input->get('unitkerja');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/unitkerja_model');
        $this->load->model('petajabatan/kandidat_baperjakat_model');
        $dataunitkerja = $this->unitkerja_model->find_by("ID",$unitkerja);
        $eselon4 = isset($dataunitkerja->ESELON_4) ? $dataunitkerja->ESELON_4 : "";
        $eselon3 = isset($dataunitkerja->ESELON_3) ? $dataunitkerja->ESELON_3 : "";
        $eselon2 = isset($dataunitkerja->ESELON_2) ? $dataunitkerja->ESELON_2 : "";
        $nama_jabatan = isset($dataunitkerja->NAMA_JABATAN) ? $dataunitkerja->NAMA_JABATAN : "";
        $JABATAN_ID = isset($dataunitkerja->JABATAN_ID) ? $dataunitkerja->JABATAN_ID : "";
        $ESELON_ID = isset($dataunitkerja->ESELON_ID) ? $dataunitkerja->ESELON_ID : "";
        $eselon = isset($this->agolongan[$ESELON_ID]) ? $this->agolongan[$ESELON_ID] : "-";
        Template::set("eselon",$eselon);

        Template::set("unitkerja",$unitkerja);
        Template::set("nama_jabatan",$nama_jabatan);
        Template::set("JABATAN_ID",$JABATAN_ID);
        Template::set("collapse",true);
        Template::render();
    }
    public function savekandidat(){
         // Validate the data
        $this->load->model('petajabatan/kandidat_baperjakat_model');
        $this->load->model('pegawai/pegawai_model');
        $this->form_validation->set_rules($this->kandidat_baperjakat_model->get_validation_rules());
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = validation_errors();
            echo json_encode($response);
            exit();
        }
        
        // get periode baperjakat aktif
        $baperjakataktif = $this->baperjakat_model->find_aktif();
        $id_periode      = isset($baperjakataktif->ID) ? $baperjakataktif->ID : "";
        // END PERIODE AKTIF

        $unitkerja      = $this->input->post('UNOR_ID');
        $data           = $this->kandidat_baperjakat_model->prep_data($this->input->post());
        // get urutan terakhir
        $this->kandidat_baperjakat_model->order_by("URUTAN_DEFAULT",desc);
        $datakandidat   = $this->kandidat_baperjakat_model->limit(1)->find_all($unitkerja,date("Y"));
        $urutan = isset($datakandidat[0]->URUTAN_DEFAULT) ? (int)$datakandidat[0]->URUTAN_DEFAULT + 1 : 1;


        $dataunitkerja = $this->unitkerja_model->find_by("ID",$unitkerja);
        $ESELON_ID = isset($dataunitkerja->ESELON_ID) ? $dataunitkerja->ESELON_ID : "";

        // get detil pegawai
        $NIP            = $this->input->post('NIP');
        if($NIP != ""){
            $JABATAN_ID   = $this->input->post('JABATAN_ID');
            $NAMA_JABATAN   = $this->input->post('nama_jabatan');
            $kandidat       = $this->pegawai_model->find_by_baperjakat("NIP_BARU",$NIP);
            $JENIS_JABATAN  = trim($kandidat->JENIS_JABATAN);
            $GOL_ID         = trim($kandidat->GOL_ID);
            $NAMA_GOLONGAN  = trim($kandidat->NAMA_GOLONGAN);
            $PNS_ID         = trim($kandidat->PNS_ID);
            $kategori = "";
            // fungsional UMUM
            /*
            if($JENIS_JABATAN == "4"){
                $response ['success']           = false;
                $response ['msg']               = "Jabatan tidak sesuai, Jabatan Fungsional Umum";
                echo json_encode($response);   
                die();
            }else{

                 if($ESELON_ID == 31 or $ESELON_ID == 32){
                // JIKA ESELON 3, GOLONGANNYA PALING RENDAH III/D PALING TINGGI IV/A
                // tapi diturunkan minimal golongannya
                if($ESELON_ID == 32) // eselon 3b
                    $this->pegawai_model->where('(GOL_ID >= 33 and GOL_ID <= 42)');
                else
                    $this->pegawai_model->where('(GOL_ID >= 40 and GOL_ID <= 43)'); // IV/A - IV/B untuk eselon 3.A
                
                //$this->pegawai_model->where('jabatan."JENIS_JABATAN"',"2");// JFT
                    $this->pegawai_model->or_where('PNS_ID IN (SELECT "PEMIMPIN_PNS_ID" FROM "unitkerja" where "DIATASAN_ID" = \''.$unitkerja.'\')');
            }else{
                // JIKA ESELON 4, GOLONGANNYA PALING RENDAH III/B PALING TINGGI III/C
                // tapi diturunkan minimal golongannya
                if($ESELON_ID == 42) // eselon 4b
                    $this->pegawai_model->where('(GOL_ID >= 31 and GOL_ID <= 34)');
                else
                    $this->pegawai_model->where('(GOL_ID >= 32 and GOL_ID <= 35)');

                //$this->pegawai_model->where('jabatan."JENIS_JABATAN"',"2");// JFT
            }

                */
                $mingolongan = 0;
                $maxgolongan = 0;
                // fungsional Tertentu
                if($JENIS_JABATAN == "2" or $JENIS_JABATAN == "4"){
                    if($ESELON_ID == 31 or $ESELON_ID == 32){
                        // JIKA ESELON 3, GOLONGANNYA PALING RENDAH III/D PALING TINGGI IV/A // tapi diturunkan
                        if($ESELON_ID == 32){
                            $mingolongan  = 33;
                            $maxgolongan  = 44;
                        }else{
                            $mingolongan  = 40;
                            $maxgolongan  = 45;
                        } // eselon 3b
                    }else{
                        // JIKA ESELON 4, GOLONGANNYA PALING RENDAH III/B PALING TINGGI III/C
                        if($ESELON_ID == 42){
                            $mingolongan  = 31;
                            $maxgolongan  = 35;
                        }else{
                            $mingolongan  = 32;
                            $maxgolongan  = 36;
                        }
                    }
                    if($GOL_ID >= $mingolongan && $GOL_ID <= $maxgolongan){

                    }else{
                        $response ['success']           = false;
                        $response ['msg']               = "Golongan tidak sesuai, Golongan ".$NAMA_GOLONGAN;
                        echo json_encode($response);   
                        die();
                    }
                    $kategori = "2"; // promosi
                }// end jenis jabatan FT

                // jika struktural
                if($JENIS_JABATAN == "1"){
                    // jika tujuannya eselon 4, anggap aja rotasi karena sebelumnya struktural
                    if($ESELON_ID == 42 or $ESELON_ID == 41){
                        $kategori = "1"; // rotasi
                    }
                    // jika tujuannya eselon 3 cek ke jabatan sebelumnya, bisa rotas sesama eselon 3 bisa juga mutasi dari eselon 4
                    if($ESELON_ID == 31 or $ESELON_ID == 32){
                        $datapemimpin       = $this->unitkerja_model->find_by("PEMIMPIN_PNS_ID",$PNS_ID);
                        $ESELON_IDAWAL      = isset($datapemimpin->ESELON_ID) ? $datapemimpin->ESELON_ID : "";
                        if($ESELON_IDAWAL   == 31 or $ESELON_IDAWAL == 32){
                            $kategori = "1"; // rotasi
                        }else{
                            $kategori = "2"; // mutasi
                        }
                        
                    }
                }

            //} 
            $this->kandidat_baperjakat_model->delete_where(array('NIP' => trim($NIP),'UNOR_ID' => trim($unitkerja),'ID_PERIODE' => trim($id_periode)));

            $data["NIP"]    = $NIP;
            $data["URUTAN_DEFAULT"]         = $urutan;
            $data["URUTAN_UPDATE"]          = $urutan;
            $data["STATUS_TAMBAHAN"]        = 1;
            $data["TAHUN"]                  = date("Y");

            $data['PANGGOL']            = trim($kandidat->GOL_ID);
            $data['PENDIDIKAN']         = trim($kandidat->TK_PENDIDIKAN); 
            $data['HUKDIS']             = trim($kandidat->rwt_hukuman); 
            $data['JABATAN_ID']         = trim($JABATAN_ID); 
            $data['NAMA_JABATAN']       = trim($NAMA_JABATAN);  
            $data['KATEGORI']           = trim($kategori);  

            $data["ID_PERIODE"]             = $id_periode;
            $this->kandidat_baperjakat_model->insert($data);
            $response ['success']           = true;
            $response ['msg']               = "Berhasil ditambahkan";
            echo json_encode($response);    
        }else{
            $response ['success']           = false;
            $response ['msg']               = "Silahkan isi kandidat";
            echo json_encode($response);    
        }
        

    }
    public function deletekandidat($record_id = ""){
        //$this->auth->restrict($this->permissionDelete);
        $this->load->model('petajabatan/kandidat_baperjakat_model');
        if ($this->kandidat_baperjakat_model->delete($record_id)) {
            log_activity($this->auth->user_id(), 'delete data kandidat : ' . $record_id . ' : ' . $this->input->ip_address(), 'struktural');
            Template::set_message("Sukses Hapus data", 'success');
            echo "Sukses";
        }else{
            echo "Gagal";
        }
        exit();
    }
    // tetapkan kandiddat untuk dipilih oleh menteri
    public function tetapkankandidat($record_id = ""){
        //$this->auth->restrict($this->permissionDelete);
        $this->load->model('petajabatan/kandidat_baperjakat_model');
        $datastruktur   = $this->kandidat_baperjakat_model->find($record_id);
        $UNOR_ID     = $datastruktur->UNOR_ID ? $datastruktur->UNOR_ID : 0;


        $data           = array();
        $data["STATUS"]     = 1;
        $data["UPDATE_BY"]     = $this->auth->user_id();
        $data["UPDATE_DATE"]     = date("Y-m-d");
        if ($this->kandidat_baperjakat_model->update($record_id,$data)) {
            log_activity($this->auth->user_id(), 'Penetapan data kandidat untuk menteri : ' . $record_id . ' : ' . $this->input->ip_address(), 'struktural');
            Template::set_message("Sukses menetapkan kandidat menteri", 'success');
            echo "Sukses";
        }else{
            echo "Gagal";
        }
        exit();
    }
    // tetapkan kandidat yang sudah dipilih menteri
    public function tetapkankandidatmenteri($record_id = ""){
        //$this->auth->restrict($this->permissionDelete);
        $this->load->model('petajabatan/kandidat_baperjakat_model');
        $datastruktur   = $this->kandidat_baperjakat_model->find($record_id);
        $UNOR_ID     = $datastruktur->UNOR_ID ? $datastruktur->UNOR_ID : 0;

        // update yang telah ditetapkan menjadi 0 semua
        $datas = array(
            'STATUS_MENTERI'     => 0
        );
        $this->kandidat_baperjakat_model->where('UNOR_ID',$UNOR_ID);
        $this->kandidat_baperjakat_model->update_where('STATUS_MENTERI', 1, $datas);

        $data           = array();
        $data["STATUS_MENTERI"]     = 1;
        $data["UPDATE_BY"]          = $this->auth->user_id();
        $data["UPDATE_DATE"]        = date("Y-m-d");
        if ($this->kandidat_baperjakat_model->update($record_id,$data)) {
            log_activity($this->auth->user_id(), 'Penetapan data pejabat dari menteri: ' . $record_id . ' : ' . $this->input->ip_address(), 'struktural');
            Template::set_message("Sukses menetapkan pejabat", 'success');
            echo "Sukses";
        }else{
            echo "Gagal";
        }
        exit();
    }
    public function batalkanpenetapan($record_id){
        //$this->auth->restrict($this->permissionDelete);
        $this->load->model('petajabatan/kandidat_baperjakat_model');
         

        $data           = array();
        $data["STATUS"]     = 0;
        $data["UPDATE_BY"]     = $this->auth->user_id();
        $data["UPDATE_DATE"]     = date("Y-m-d");
        if ($this->kandidat_baperjakat_model->update($record_id,$data)) {
            log_activity($this->auth->user_id(), 'Penetapan data kandidat : ' . $record_id . ' : ' . $this->input->ip_address(), 'struktural');
            Template::set_message("Sukses menetapkan kandidat", 'success');
            echo "Sukses";
        }else{
            echo "Gagal";
        }
        exit();
    }
    public function setup($record_id){
        $this->auth->restrict($this->permissionDelete);
        $this->load->model('petajabatan/kandidat_baperjakat_model');
        $datastruktur   = $this->kandidat_baperjakat_model->find($record_id);
        $urutanawal     = $datastruktur->URUTAN_UPDATE ? $datastruktur->URUTAN_UPDATE : 0;
        $data           = array();
        $URUTAN_UPDATE = $urutanawal-1;
        if($urutanawal >0){
            $datas = array(
                'URUTAN_UPDATE'     => $URUTAN_UPDATE+1
            );
            $this->kandidat_baperjakat_model->update_where('URUTAN_UPDATE', $URUTAN_UPDATE, $datas);


            $data["URUTAN_UPDATE"]     = $URUTAN_UPDATE;
            if ($this->kandidat_baperjakat_model->update($record_id,$data)) {
                 
                 log_activity($this->auth->user_id(), 'Update urutan data kandidat : ' . $record_id . ' : ' . $this->input->ip_address(), 'struktural');
                 Template::set_message("Sukses Hapus data", 'success');
                 echo "Sukses";
            }else{
                echo "Gagal";
            }

        }
        
        exit();
    }
    public function setdown($record_id){
        $this->auth->restrict($this->permissionDelete);
        $this->load->model('petajabatan/kandidat_baperjakat_model');
        $datastruktur   = $this->kandidat_baperjakat_model->find($record_id);
        $urutanawal     = $datastruktur->URUTAN_UPDATE ? $datastruktur->URUTAN_UPDATE : 0;
        $data           = array();
        $URUTAN_UPDATE  = $urutanawal+1;
        $data["URUTAN_UPDATE"]     = $URUTAN_UPDATE;

            $datas = array(
                'URUTAN_UPDATE'     => $URUTAN_UPDATE-1
            );
            $this->kandidat_baperjakat_model->update_where('URUTAN_UPDATE', $URUTAN_UPDATE, $datas);
            
            if ($this->kandidat_baperjakat_model->update($record_id,$data)) {
                 
                 log_activity($this->auth->user_id(), 'Update urutat data kandidat : ' . $record_id . ' : ' . $this->input->ip_address(), 'struktural');
                 Template::set_message("Sukses Hapus data", 'success');
                 echo "Sukses";
            }else{
                echo "Gagal";
            }

        
        exit();
    }
    public function ajax_kandidat(){
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/unitkerja_model');
        $this->load->model('petajabatan/kandidat_baperjakat_model');
        // get periode baperjakat aktif
        $baperjakataktif = $this->baperjakat_model->find_aktif();
        $id_periode      = isset($baperjakataktif->ID) ? $baperjakataktif->ID : "";
        // END PERIODE AKTIF

         
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        $unitkerja = $this->input->post('unitkerja');
        $length= $this->input->post('length');
        $start= $this->input->post('start');


        $dataunitkerja = $this->unitkerja_model->find_by("ID",$unitkerja);
        $PEMIMPIN_PNS_ID = isset($dataunitkerja->PEMIMPIN_PNS_ID) ? trim($dataunitkerja->PEMIMPIN_PNS_ID) : "";
        $data_peminpin = $this->pegawai_model->find_by("PNS_ID",trim($PEMIMPIN_PNS_ID));
        $NIP_PEJABAT = isset($data_peminpin->NIP_BARU) ? $data_peminpin->NIP_BARU : "";
        // jangan ditampilkan kandidat inkumben


        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $this->kandidat_baperjakat_model->where("NIP NOT IN ('".$NIP_PEJABAT."')");// JFT
        $this->kandidat_baperjakat_model->where("ID_PERIODE",$id_periode);
        $total= $this->kandidat_baperjakat_model->count_all($unitkerja,date("Y"));
        $output=array();
        $output['draw']=$draw;

        
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        
        
        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        if($search!=""){
            $this->kandidat_baperjakat_model->where('upper("NAMA_KURSUS") LIKE \''.strtoupper($search).'%\'');
        }
        $this->kandidat_baperjakat_model->limit($length,$start);
        /*Urutkan dari alphabet paling terkahir*/
        
        $kolom = $iSortCol != "" ? $iSortCol : "NAMA";
        $sSortCol == "asc" ? "asc" : "desc";
        //$this->kandidat_baperjakat_model->order_by($iSortCol,$sSortCol);
        $this->kandidat_baperjakat_model->where("NIP NOT IN ('".$NIP_PEJABAT."')");// JFT
        $this->kandidat_baperjakat_model->order_by("URUTAN_UPDATE",ASC);     
        $this->kandidat_baperjakat_model->where("ID_PERIODE",$id_periode);   
        $records = $this->kandidat_baperjakat_model->find_all($unitkerja,date("Y"));
        
        $nomor_urut=$start+1;
        $statawal = "";
        $statakhir = "";
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                if($nomor_urut == 1 )
                    $statawal = "disabled";
                if($nomor_urut == count($records))
                    $statakhir = "disabled";
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->GELAR_DEPAN." ".$record->NAMA." ".$record->GELAR_BELAKANG;
                $row []  = $record->rwt_assesment;
                $row []  = $record->rwt_hukuman;
                $row []  = $record->NAMA_PENDIDIKAN;
                $row []  = $record->NAMA_GOLONGAN;
                $row []  = $record->URUTAN_DEFAULT;
                $row []  = $record->URUTAN_UPDATE;
                $btn_actions = array();
                IF($record->STATUS == 1){
                    $btn_actions  [] = "<a href='#' kode='".$record->KODE."' data-toggle='tooltip' title='Batalkan Penetapan' class='btn btn-sm btn-danger btn-batalkan'><i class='glyphicon glyphicon-edit'></i> </a>";    
                }ELSE{
                    $btn_actions  [] = "<a href='#' kode='".$record->KODE."' data-toggle='tooltip' title='Tetapkan untuk dipilih menteri' class='btn btn-sm btn-warning btn-tetapkan'><i class='glyphicon glyphicon-edit'></i> </a>";
                }
                
                $btn_actions  [] = "<a href='#' kode='".$record->KODE."' data-toggle='tooltip' title='Naikan' class='btn btn-sm btn-info btnup ".$statawal."'><i class='glyphicon glyphicon-chevron-up'></i> </a>";
                $btn_actions  [] = "<a href='#' kode='".$record->KODE."' data-toggle='tooltip' title='Turunkan' class='btn btn-sm btn-info btndown ".$statakhir."'><i class='glyphicon glyphicon-chevron-down'></i> </a>";
                $btn_actions  [] = "<a href='#' kode='".$record->KODE."' data-toggle='tooltip' title='Hapus' class='btn btn-sm btn-danger btn-hapus'><i class='glyphicon glyphicon-trash'></i> </a>";

                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                
                $statawal = "";
                $statakhir = "";
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        die();
    }
    
}