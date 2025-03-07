<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Reports controller
 */
class Reports extends Admin_Controller
{
    protected $permissionCreate = 'Kondisi_pegawai.Reports.Create';
    protected $permissionDelete = 'Kondisi_pegawai.Reports.Delete';
    protected $permissionEdit   = 'Kondisi_pegawai.Reports.Edit';
    protected $permissionView   = 'Kondisi_pegawai.Reports.View';
    protected $permissionViewJumlah   = 'Jumlah.Reports.View';
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->auth->restrict($this->permissionView);
        $this->lang->load('kondisi_pegawai');
        
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'reports/_sub_nav');

        Assets::add_module_js('kondisi_pegawai', 'kondisi_pegawai.js');

        $this->load->model('kondisi_pegawai/kondisi_pegawai_model');
        $this->load->model('kondisi_pegawai/rpt_pendidikan_bulan_model');
        $this->load->model('kondisi_pegawai/rpt_jumlah_asn_model');
        $this->load->model('pegawai/mv_jml_unor_induk_model');
    }

    /**
     * Display a list of kondisi pegawai data.
     *
     * @return void
     */
    public function index($tahun = "")
    {

        $bulan = date("m");
        $tahun = $this->input->get("tahun") ? $this->input->get("tahun") : date("Y");
        // jumlah ASN
        $jsonasn = array();
        $record_asn = $this->rpt_jumlah_asn_model->group_by_asn_tahun("ASN"); 
        $dataasn = array();
        if (isset($record_asn) && is_array($record_asn) && count($record_asn)) :
            foreach ($record_asn as $record) :
                $dataasn["TAHUN"]   = $record->TAHUN;
                $dataasn["JUMLAH"]  = $record->JUMLAH;
                $jsonasn[]          = $dataasn;
            endforeach;
        endif;
        Template::set('jsonasn', json_encode($jsonasn));
        // end jumlah ASN
        // jenis kelamin
        $jks = $this->rpt_jumlah_asn_model->group_by_jk("JK",$tahun);
        $jsonjk = array();
        $datajk = array();
        if (isset($jks) && is_array($jks) && count($jks)) :
            foreach ($jks as $record) :
                $datajk["Jenis_Kelamin"] = $record->KETERANGAN;
                $datajk["jumlah"] = $record->JUMLAH;
                $jsonjk[]   = $datajk;
            endforeach;
        endif;
        Template::set('jsonjk', json_encode($jsonjk));
        // end jenis kelamin
        // jenis GOLONGAN
        $GOLS = $this->rpt_jumlah_asn_model->group_by_gol("GOL",$tahun);
        $jsongol = array();
        $datagol = array();
        if (isset($GOLS) && is_array($GOLS) && count($GOLS)) :
            foreach ($GOLS as $record) :
                $datagol["golongan"] = "Golongan ".$record->KETERANGAN;
                $datagol["jumlah"] = $record->JUMLAH;
                $jsongol[]   = $datagol;
            endforeach;
        endif;
        Template::set('jsongol', json_encode($jsongol));
        // end jenis GOLONGAN
        // jenis jabatan
        $arrjabatan = array();
        $arrjabatan["1"] = "Struktural";
        $arrjabatan["2"] = "Jabatan Fungsional Tertentu";
        $arrjabatan["3"] = "Jabatan Staff Khusus";
        $arrjabatan["4"] = "Jabatan Fungsional Umum";
        $arrjabatan[] = "Belum ada";
        $jenisjab = $this->rpt_jumlah_asn_model->group_by_gol("JENIS_JABATAN",$tahun);
        $jsonjab = array();
        $datajab = array();
        if (isset($jenisjab) && is_array($jenisjab) && count($jenisjab)) :
            foreach ($jenisjab as $record) :
                $datajab["jabatan"] = isset($arrjabatan[trim($record->KETERANGAN)]) ? $arrjabatan[trim($record->KETERANGAN)] : "Kosong";
                $datajab["jumlah"] = $record->JUMLAH;
                $jsonjab[]   = $datajab;
            endforeach;
        endif;
        Template::set('jsonjab', json_encode($jsonjab));
        // end jabatan
        // PENDIDIKAN
        $arrtingkat = array();
        $arrtingkat["1"] = "SD";
        $arrtingkat["2"] = "SLTP";
        $arrtingkat["3"] = "SLTA";
        $arrtingkat["4"] = "Diploma";
        $arrtingkat["5"] = "Strata";
        $arrtingkat[] = "Belum ada";
        $jsonpendidikan = array();
        $recordpendidikan = $this->rpt_jumlah_asn_model->group_by_gol("TK_PENDIDIKAN",$tahun); 
        $datatkpendidikan = array();
        if (isset($recordpendidikan) && is_array($recordpendidikan) && count($recordpendidikan)) :
            foreach ($recordpendidikan as $record) :
               $datatkpendidikan["NAMA"] = isset($arrtingkat[trim($record->KETERANGAN)]) ? $arrtingkat[trim($record->KETERANGAN)] : "Kosong";
                $datatkpendidikan["jumlah"] = $record->JUMLAH;
                $jsonpendidikan[]   = $datatkpendidikan;
            endforeach;
        endif;
        Template::set('jsonpendidikan', json_encode($jsonpendidikan));
        // UMUR JENIS KELAMIN F
        $record_M = $this->rpt_jumlah_asn_model->group_by_tahun("UMUR_M",$tahun); 
        $dataumurm = array();
        if (isset($record_M) && is_array($record_M) && count($record_M)) :
            foreach ($record_M as $record) :
                $dataumurm[$record->KETERANGAN]     = $record->JUMLAH;
            endforeach;
        endif;

        $jsonumurf = array();
        $record_F = $this->rpt_jumlah_asn_model->group_by_tahun("UMUR_F",$tahun); 
        $dataumurf = array();
        if (isset($record_F) && is_array($record_F) && count($record_F)) :
            foreach ($record_F as $record) :
                $dataumurf["NAMA"]     = $record->KETERANGAN;
                $dataumurf["JUMLAH"]      = $record->JUMLAH;
                $dataumurf["JUMLAHM"]      = isset($dataumurm[trim($record->KETERANGAN)]) ? $dataumurm[trim($record->KETERANGAN)] : 0;
                $jsonumurf[]            = $dataumurf;
            endforeach;
        endif;
        Template::set('jsonumurf', json_encode($jsonumurf));
        // end jumlah UMUR JENISKELAMIN


        
        Template::set('tahun', $tahun);
        Template::set('toolbar_title',"Laporan Kondisi Pegawai");
        Template::render();
    }
    public function bulanan($tahun = "")
    {
        $bulan = date("m");

        $arrbulan = array();
        $arrbulan["1"] = "Jan";
        $arrbulan["2"] = "Feb";
        $arrbulan["3"] = "Mar";
        $arrbulan["4"] = "Apr";
        $arrbulan["5"] = "Mei";
        $arrbulan["6"] = "Jun";
        $arrbulan["7"] = "Jul";
        $arrbulan["8"] = "Ags";
        $arrbulan["9"] = "Sept";
        $arrbulan["10"] = "Okt";
        $arrbulan["11"] = "Nov";
        $arrbulan["12"] = "Des";
        
        $tahun = $this->input->get("tahun") ? $this->input->get("tahun") : date("Y");
        // jumlah ASN
        $jsonasn = array();
        $record_asn = $this->rpt_jumlah_asn_model->group_by_asn_bulan("ASN_B",$tahun); 
        $dataasn = array();
        if (isset($record_asn) && is_array($record_asn) && count($record_asn)) :
            foreach ($record_asn as $record) :
                $dataasn["BULAN"]   = isset($arrbulan[$record->BULAN]) ? $arrbulan[$record->BULAN] : "";
                $dataasn["JUMLAH"]  = $record->JUMLAH;
                $jsonasn[]          = $dataasn;
            endforeach;
        endif;
        Template::set('jsonasn', json_encode($jsonasn));
        // end jumlah ASN
         
        
        // PENDIDIKAN
        $arrtingkat = array();
        $arrtingkat["1"] = "SD";
        $arrtingkat["2"] = "SLTP";
        $arrtingkat["3"] = "SLTA";
        $arrtingkat["4"] = "Diploma";
        $arrtingkat["5"] = "Strata";
        $arrtingkat[] = "Belum ada";
        $jsonpendidikan = array();
        $recordpendidikan = $this->rpt_jumlah_asn_model->jenis_by_bulan("TK_PENDIDIKAN_B",$tahun); 
        $datatkpendidikan = array();
        $adatatkpendidikan = array();
        

        if (isset($recordpendidikan) && is_array($recordpendidikan) && count($recordpendidikan)) :
            foreach ($recordpendidikan as $record) :
                $adatatkpendidikan[$record->BULAN."_".$record->KETERANGAN]  = $record->JUMLAH;
            endforeach;
        endif;
        $intbulan = (int)$bulan;
        $bln = 1;
        foreach($arrbulan as $key => $feature) {
            if($bln <= $intbulan && $tahun == date("Y")){
                $datatkpendidikan["BULAN"]         = $feature;
                $datatkpendidikan["SD"]         = isset($adatatkpendidikan[$key."_1"]) ? $adatatkpendidikan[$key."_1"] : 0;
                $datatkpendidikan["SLTP"]       = isset($adatatkpendidikan[$key."_2"]) ? $adatatkpendidikan[$key."_2"] : 0;
                $datatkpendidikan["SLTA"]       = isset($adatatkpendidikan[$key."_3"]) ? $adatatkpendidikan[$key."_3"] : 0;
                $datatkpendidikan["Diploma"]    = isset($adatatkpendidikan[$key."_4"]) ? $adatatkpendidikan[$key."_4"] : 0;
                $datatkpendidikan["Strata"]     = isset($adatatkpendidikan[$key."_5"]) ? $adatatkpendidikan[$key."_5"] : 0;
                $datatkpendidikan["Belum ada"]  = isset($adatatkpendidikan[$key."_"]) ? $adatatkpendidikan[$key."_"] : 0;
                $jsonpendidikan[]               = $datatkpendidikan;
            }else if($tahun < date("Y")){
                $datatkpendidikan["BULAN"]         = $feature;
                $datatkpendidikan["SD"]         = isset($adatatkpendidikan[$key."_1"]) ? $adatatkpendidikan[$key."_1"] : 0;
                $datatkpendidikan["SLTP"]       = isset($adatatkpendidikan[$key."_2"]) ? $adatatkpendidikan[$key."_2"] : 0;
                $datatkpendidikan["SLTA"]       = isset($adatatkpendidikan[$key."_3"]) ? $adatatkpendidikan[$key."_3"] : 0;
                $datatkpendidikan["Diploma"]    = isset($adatatkpendidikan[$key."_4"]) ? $adatatkpendidikan[$key."_4"] : 0;
                $datatkpendidikan["Strata"]     = isset($adatatkpendidikan[$key."_5"]) ? $adatatkpendidikan[$key."_5"] : 0;
                $datatkpendidikan["Belum ada"]  = isset($adatatkpendidikan[$key."_"]) ? $adatatkpendidikan[$key."_"] : 0;
                $jsonpendidikan[]               = $datatkpendidikan;
            }
            $bln++;
        }
        Template::set('jsonpendidikan', json_encode($jsonpendidikan));
         
        // jenis GOLONGAN
        $arrtingkat = array();
        $arrtingkat["1"] = "1";
        $arrtingkat["2"] = "2";
        $arrtingkat["3"] = "3";
        $arrtingkat[] = "Belum ada";
        $jsongol = array();
        $recordgolongan_b = $this->rpt_jumlah_asn_model->jenis_by_bulan("GOL_B",$tahun); 
        $datagolongan = array();
        $adatagolongan = array();
        

        if (isset($recordgolongan_b) && is_array($recordgolongan_b) && count($recordgolongan_b)) :
            foreach ($recordgolongan_b as $record) :
                $adatagolongan[$record->BULAN."_".$record->KETERANGAN]  = $record->JUMLAH;
            endforeach;
        endif;
        $bln = 1;
        foreach($arrbulan as $key => $feature) {
            if($bln <= $intbulan && $tahun == date("Y")){
                $datagolongan["BULAN"]         = $feature;
                $datagolongan["1"]         = isset($adatagolongan[$key."_1"]) ? $adatagolongan[$key."_1"] : 0;
                $datagolongan["2"]       = isset($adatagolongan[$key."_2"]) ? $adatagolongan[$key."_2"] : 0;
                $datagolongan["3"]       = isset($adatagolongan[$key."_3"]) ? $adatagolongan[$key."_3"] : 0;
                $datagolongan["Belum ada"]      = isset($adatagolongan[$key."_"]) ? $adatagolongan[$key."_"] : 0;
                $jsongol[]                      = $datagolongan;
            }else if($tahun < date("Y")){
                $datagolongan["BULAN"]         = $feature;
                $datagolongan["1"]         = isset($adatagolongan[$key."_1"]) ? $adatagolongan[$key."_1"] : 0;
                $datagolongan["2"]       = isset($adatagolongan[$key."_2"]) ? $adatagolongan[$key."_2"] : 0;
                $datagolongan["3"]       = isset($adatagolongan[$key."_3"]) ? $adatagolongan[$key."_3"] : 0;
                $datagolongan["Belum ada"]      = isset($adatagolongan[$key."_"]) ? $adatagolongan[$key."_"] : 0;
                $jsongol[]                      = $datagolongan;
            }
            $bln++;
        }
        Template::set('jsongol', json_encode($jsongol));
        // end jenis GOLONGAN
        // jenis jabatan
        $arrjabatan = array();
        $arrjabatan["1"] = "Struktural";
        $arrjabatan["2"] = "Jabatan Fungsional Tertentu";
        $arrjabatan["3"] = "Jabatan Staff Khusus";
        $arrjabatan["4"] = "Jabatan Fungsional Umum";
        $arrjabatan[] = "Belum ada";
        $jenisjab = $this->rpt_jumlah_asn_model->jenis_by_bulan("JENIS_JABATAN_B",$tahun);
        $jsonjab = array();
        $datajab = array();
        $adatajabatan = array();
        if (isset($jenisjab) && is_array($jenisjab) && count($jenisjab)) :
            foreach ($jenisjab as $record) :
                $adatajabatan[$record->BULAN."_".$record->KETERANGAN]  = $record->JUMLAH;
            endforeach;
        endif;
        $bln = 1;
        foreach($arrbulan as $key => $feature) {
            if($bln <= $intbulan && $tahun == date("Y")){
                $datajab["BULAN"]         = $feature;
                $datajab["1"]         = isset($adatajabatan[$key."_1"]) ? $adatajabatan[$key."_1"] : 0;
                $datajab["2"]       = isset($adatajabatan[$key."_2"]) ? $adatajabatan[$key."_2"] : 0;
                $datajab["3"]       = isset($adatajabatan[$key."_3"]) ? $adatajabatan[$key."_3"] : 0;
                $datajab["4"]       = isset($adatajabatan[$key."_4"]) ? $adatajabatan[$key."_4"] : 0;
                $datajab["Belum ada"]      = isset($adatajabatan[$key."_"]) ? $adatajabatan[$key."_"] : 0;
                $jsonjab[]   = $datajab;
            }else if($tahun < date("Y")){
                $datajab["BULAN"]         = $feature;
                $datajab["1"]         = isset($adatajabatan[$key."_1"]) ? $adatajabatan[$key."_1"] : 0;
                $datajab["2"]       = isset($adatajabatan[$key."_2"]) ? $adatajabatan[$key."_2"] : 0;
                $datajab["3"]       = isset($adatajabatan[$key."_3"]) ? $adatajabatan[$key."_3"] : 0;
                $datajab["4"]       = isset($adatajabatan[$key."_4"]) ? $adatajabatan[$key."_4"] : 0;
                $datajab["Belum ada"]      = isset($adatajabatan[$key."_"]) ? $adatajabatan[$key."_"] : 0;
                $jsonjab[]   = $datajab;
            }
            $bln++;
        }
        Template::set('jsonjab', json_encode($jsonjab));
        // end jabatan

        Template::set('tahun', $tahun);
        Template::set('toolbar_title',"Laporan Kondisi Pegawai Bulanan");
        Template::render();
    }
    public function golongan($tahun = "")
    {

        $bulan = date("m");
        $tahun = $this->input->get("tahun") ? $this->input->get("tahun") : date("Y");
        $jsongolongan = array();
        $recordgolongan = $this->kondisi_pegawai_model->group_by_golonganbulan("",$tahun); 
        $datapegawai = array();
        if (isset($recordgolongan) && is_array($recordgolongan) && count($recordgolongan)) :
            foreach ($recordgolongan as $record) :
                $datapegawai["NAMA"]    = $record->GOLONGAN_NAMA;
                $datapegawai["JUMLAH"]  = $record->JUMLAH;
                $jsongolongan[]         = $datapegawai;
            endforeach;
        endif;
        Template::set('tahun', $tahun);
        Template::set('jsongolongan', json_encode($jsongolongan));
        Template::set('toolbar_title',"Laporan Kondisi Pegawai");
        Template::render();
    }
    public function pendidikan($tahun = "")
    {

        $bulan = date("m");
        $tahun = $this->input->get("tahun") ? $this->input->get("tahun") : date("Y");
        $tahunsebelum = $tahun - 1;
        // data tahun sebelumnya
        $datagolongansebelum = array();
        $recordgolongansebelum = $this->rpt_pendidikan_bulan_model->group_by_pendidikanbulan("",$tahunsebelum); 
        if (isset($recordgolongan) && is_array($recordgolongan) && count($recordgolongan)) :
            foreach ($recordgolongan as $record) :
                $datagolongansebelum[$record->TINGKAT_PENDIDIKAN]  = $record->JUMLAH;
            endforeach;
        endif;

        $jsongolongan = array();
        $recordgolongan = $this->rpt_pendidikan_bulan_model->group_by_pendidikanbulan("",$tahun); 
        $datapegawai = array();
        if (isset($recordgolongan) && is_array($recordgolongan) && count($recordgolongan)) :
            foreach ($recordgolongan as $record) :
                $datapegawai["TAHUN_1"]    = $record->TAHUN;
                $datapegawai["TAHUN_2"]    = $tahunsebelum;
                $datapegawai["NAMA"]    = $record->NAMA_TINGKAT;
                $datapegawai["JUMLAH"]  = $record->JUMLAH;
                $datapegawai["JUMLAH_2"]  = isset($datagolongansebelum[$record->TINGKAT_PENDIDIKAN]) ? $datagolongansebelum[$record->TINGKAT_PENDIDIKAN] : 0;
                $jsongolongan[]         = $datapegawai;
            endforeach;
        endif;

        Template::set('tahun', $tahun);
        Template::set('jsongolongan', json_encode($jsongolongan));
        Template::set('toolbar_title',"Laporan Kondisi Pegawai");
        Template::render();
    }
    public function jumlahsatker()
    {
        $this->auth->restrict($this->permissionViewJumlah);
        Template::set('toolbar_title', "Jumlah Pegawai");
        Template::render();
    }
    public function getjumlah(){
        $this->auth->restrict($this->permissionViewJumlah);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

        $selectedUnors = array();
        $datapegawai = $this->getJumlahBySatker();

        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            
        }
        $kedudukan_hukum = "";
        $this->db->start_cache();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            if($filters['unit_id_key']){
                $this->db->group_start();
                $this->db->where("id_unor_bkn",$filters['unit_id_key']);    
                $this->db->group_end();
            }
            if($filters['jenis']){
                $this->db->where("JENIS_SATKER",$filters['jenis']);    
            }
        }
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $asatkers = null;
        $total= $this->mv_jml_unor_induk_model->count_all();
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->mv_jml_unor_induk_model->order_by("satker",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->mv_jml_unor_induk_model->limit($length,$start);
        $records = $this->mv_jml_unor_induk_model->find_all();
        
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->NAMA_UNOR."<br><i>".$record->JENIS_SATKER."</i>";
            
                if((int)$record->jumlah > 0){
                    $row []  = $record->jumlah;
                }else{
                    $row []  = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_laki) > 0){
                    $row []  = $datapegawai[$record->UNOR_INDUK_ID]->jml_laki;
                }else{
                    $row []  = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_wanita) > 0){
                    $row []  = $datapegawai[$record->UNOR_INDUK_ID]->jml_wanita;
                }else{
                    $row []  = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_gol1) > 0){
                    $row []  = $datapegawai[$record->UNOR_INDUK_ID]->jml_gol1;
                }else{
                    $row []  = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_gol2) > 0){
                    $row []  = $datapegawai[$record->UNOR_INDUK_ID]->jml_gol2;
                }else{
                    $row []  = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_gol3) > 0){
                    $row []  = $datapegawai[$record->UNOR_INDUK_ID]->jml_gol3;
                }else{
                    $row []  = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_gol4) > 0){
                    $row []  = $datapegawai[$record->UNOR_INDUK_ID]->jml_gol4;
                }else{
                    $row []  = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_str) > 0){
                    $row []  = $datapegawai[$record->UNOR_INDUK_ID]->jml_str;
                }else{
                    $row []  = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_jft) > 0){
                    $row []  = $datapegawai[$record->UNOR_INDUK_ID]->jml_jft;
                }else{
                    $row []  = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_dosen) > 0){
                    $row []  = $datapegawai[$record->UNOR_INDUK_ID]->jml_dosen;
                }else{
                    $row []  = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_jfu) > 0){
                    $row []  = $datapegawai[$record->UNOR_INDUK_ID]->jml_jfu;
                }else{
                    $row []  = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_sd) > 0){
                    $row []  = $datapegawai[$record->UNOR_INDUK_ID]->jml_sd;
                }else{
                    $row []  = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_smp) > 0){
                    $row []  = $datapegawai[$record->UNOR_INDUK_ID]->jml_smp;
                }else{
                    $row []  = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_sma) > 0){
                    $row []  = $datapegawai[$record->UNOR_INDUK_ID]->jml_sma;
                }else{
                    $row []  = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_d) > 0){
                    $row []  = $datapegawai[$record->UNOR_INDUK_ID]->jml_d;
                }else{
                    $row []  = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_s1) > 0){
                    $row []  = $datapegawai[$record->UNOR_INDUK_ID]->jml_s1;
                }else{
                    $row []  = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_s2) > 0){
                    $row []  = $datapegawai[$record->UNOR_INDUK_ID]->jml_s2;
                }else{
                    $row []  = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_s3) > 0){
                    $row []  = $datapegawai[$record->UNOR_INDUK_ID]->jml_s3;
                }else{
                    $row []  = "";
                }
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    public function detildikbudhr($satker_id)
    {   

        $this->auth->restrict($this->permissionSinkronisasi);
        Template::set('satker', $satker_id);
        Template::set('toolbar_title', "Nominatif Pegawai dikbudhr");
        Template::render();
    }
    private function getJumlahBySatker(){
        $this->load->model('pegawai/pegawai_model');
        $dataPegawai = $this->pegawai_model->findGroupSatker();
        $data = array();
        if (isset($dataPegawai) && is_array($dataPegawai) && count($dataPegawai)) :
            foreach ($dataPegawai as $record) :
                $data[$record->UNOR_INDUK_ID] = $record;
            endforeach;
        endif;
        return $data;
    }
    public function downloadjumlah(){
        $advanced_search_filters  = $_GET;
        $datapegawai = $this->getJumlahBySatker();
        if($advanced_search_filters){
            $filters = $advanced_search_filters;
            if($filters['jenis']){
                $this->db->where("JENIS_SATKER",$filters['jenis']);    
            }
        }
        $records = $this->mv_jml_unor_induk_model->find_all();
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->NAMA_UNOR."<br><i>".$record->JENIS_SATKER."</i>";
            
                if((int)$record->jumlah > 0){
                    $row []  = $record->jumlah;
                }else{
                    $row []  = "";
                }

                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_laki) > 0){
                    $record->jml_laki = $datapegawai[$record->UNOR_INDUK_ID]->jml_laki;
                }else{
                    $record->jml_laki = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_wanita) > 0){
                    $record->jml_wanita = $datapegawai[$record->UNOR_INDUK_ID]->jml_wanita;
                }else{
                    $record->jml_wanita = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_gol1) > 0){
                    $record->jml_gol1 = $datapegawai[$record->UNOR_INDUK_ID]->jml_gol1;
                }else{
                    $record->jml_gol1 = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_gol2) > 0){
                    $record->jml_gol2 = $datapegawai[$record->UNOR_INDUK_ID]->jml_gol2;
                }else{
                    $record->jml_gol2 = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_gol3) > 0){
                    $record->jml_gol3 = $datapegawai[$record->UNOR_INDUK_ID]->jml_gol3;
                }else{
                    $record->jml_gol3 = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_gol4) > 0){
                    $record->jml_gol4 = $datapegawai[$record->UNOR_INDUK_ID]->jml_gol4;
                }else{
                    $record->jml_gol4 = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_str) > 0){
                    $record->jml_str = $datapegawai[$record->UNOR_INDUK_ID]->jml_str;
                }else{
                    $record->jml_str = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_jft) > 0){
                    $record->jml_jft = $datapegawai[$record->UNOR_INDUK_ID]->jml_jft;
                }else{
                    $record->jml_jft = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_dosen) > 0){
                    $record->jml_dosen = $datapegawai[$record->UNOR_INDUK_ID]->jml_dosen;
                }else{
                    $record->jml_dosen = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_jfu) > 0){
                    $record->jml_jfu = $datapegawai[$record->UNOR_INDUK_ID]->jml_jfu;
                }else{
                    $record->jml_jfu = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_sd) > 0){
                    $record->jml_sd = $datapegawai[$record->UNOR_INDUK_ID]->jml_sd;
                }else{
                    $record->jml_sd = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_smp) > 0){
                    $record->jml_smp = $datapegawai[$record->UNOR_INDUK_ID]->jml_smp;
                }else{
                    $record->jml_smp = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_sma) > 0){
                    $record->jml_sma = $datapegawai[$record->UNOR_INDUK_ID]->jml_sma;
                }else{
                    $record->jml_sma = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_d) > 0){
                    $record->jml_d = $datapegawai[$record->UNOR_INDUK_ID]->jml_d;
                }else{
                    $record->jml_d = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_s1) > 0){
                    $record->jml_s1 = $datapegawai[$record->UNOR_INDUK_ID]->jml_s1;
                }else{
                    $record->jml_s1 = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_s2) > 0){
                    $record->jml_s2 = $datapegawai[$record->UNOR_INDUK_ID]->jml_s2;
                }else{
                    $record->jml_s2 = "";
                }
                if(isset($datapegawai[$record->UNOR_INDUK_ID]) &&  isset($datapegawai[$record->UNOR_INDUK_ID]->jml_s3) > 0){
                    $record->jml_s3 = $datapegawai[$record->UNOR_INDUK_ID]->jml_s3;
                }else{
                    $record->jml_s3 = "";
                }
            }
        endif;
        $this->load->library('LibOpenTbs');
        $template_name = trim($this->settings_lib->item('site.pathuploaded')).'templatejumlah.xlsx';
        $TBS = $this->libopentbs->TBS;
        $TBS->LoadTemplate($template_name, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
        $TBS->MergeBlock('r', $records);
        $TBS->MergeField('a', array(
            'tanggal'=>date("d m Y"),
        )); 

        $output_file_name = 'jumlahpegawai.xlsx';
        $output_file_name = str_replace('.', '_'.date('Y-m-d').'.', $output_file_name);
        $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.
    }
    public function satkerumur()
    {
        $this->auth->restrict($this->permissionView);
        $this->load->model('pegawai/vw_eselon1_model');
        $this->load->model('tkpendidikan/tkpendidikan_model');
        $recordsEs1 = $this->vw_eselon1_model->find_all();
        $records = $this->pegawai_model->umurbySatker();
        $tkpendidikan = $this->tkpendidikan_model->find_all();
        $recordtkpendidikan = $this->pegawai_model->findPegawaiTingkatPendidikanSatker();
        $datajumlah = array();
        $dataunit = array();
        $dataeselon1 = array();
        $jmles1 = array();
        if(isset($recordsEs1) && is_array($recordsEs1) && count($recordsEs1)):
            foreach ($recordsEs1 as $record) {
                $jmles1[$record->ID]['25'] = 0;
                $jmles1[$record->ID]['2530'] = 0;
                $jmles1[$record->ID]['3135'] = 0;
                $jmles1[$record->ID]['3640'] = 0;
                $jmles1[$record->ID]['4145'] = 0;
                $jmles1[$record->ID]['4650'] = 0;
                $jmles1[$record->ID]['50'] = 0;
                // pendidikan
                foreach ($tkpendidikan as $rectk) {
                    $jmles1[$record->ID]["tk_".$rectk->TINGKAT] = 0;
                }
                $dataeselon1[$record->ID] = $record->NAMA_UNOR;
            }
        endif;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                // if($record->ESELON_1 == $record->UNOR_INDUK_ID)
                    // $dataeselon1[$record->ESELON_1] = $record->NAMA_UNOR;
                $datajumlah[$record->UNOR_INDUK_ID]['25'] = $record->dualima;
                $datajumlah[$record->UNOR_INDUK_ID]['2530'] = $record->dualimatigapuluh;
                $datajumlah[$record->UNOR_INDUK_ID]['3135'] = $record->tigasatutigalima;
                $datajumlah[$record->UNOR_INDUK_ID]['3640'] = $record->tigaenamempatpuluh;
                $datajumlah[$record->UNOR_INDUK_ID]['4145'] = $record->empatsatuempatlima;
                $datajumlah[$record->UNOR_INDUK_ID]['4650'] = $record->empatenamlimapuluh;
                $datajumlah[$record->UNOR_INDUK_ID]['50'] = $record->limapuluh;

                $dataunit[$record->ESELON_1][$record->UNOR_INDUK_ID] = $record->NAMA_UNOR != "" ? $record->NAMA_UNOR : $record->UNOR_INDUK_ID;
                $jmles1[$record->ESELON_1]['25'] = $jmles1[$record->ESELON_1]['25'] + $record->dualima;
                $jmles1[$record->ESELON_1]['2530'] = $jmles1[$record->ESELON_1]['2530'] + $record->dualimatigapuluh;
                $jmles1[$record->ESELON_1]['3135'] = $jmles1[$record->ESELON_1]['3135'] + $record->tigasatutigalima;
                $jmles1[$record->ESELON_1]['3640'] = $jmles1[$record->ESELON_1]['3640'] + $record->tigaenamempatpuluh;
                $jmles1[$record->ESELON_1]['4145'] = $jmles1[$record->ESELON_1]['4145'] + $record->empatsatuempatlima;
                $jmles1[$record->ESELON_1]['4650'] = $jmles1[$record->ESELON_1]['4650'] + $record->empatenamlimapuluh;
                $jmles1[$record->ESELON_1]['50'] = $jmles1[$record->ESELON_1]['50'] + $record->limapuluh;
            }
        endif;
        if(isset($recordtkpendidikan) && is_array($recordtkpendidikan) && count($recordtkpendidikan)):
            foreach ($recordtkpendidikan as $record) {
                // if($record->ESELON_1 == $record->UNOR_INDUK_ID)
                //     $dataeselon1[$record->ESELON_1] = $record->NAMA_UNOR;
                $datajumlah[$record->UNOR_INDUK_ID]["tk_".$record->TINGKAT] = $record->total;

                // $dataunit[$record->ESELON_1][$record->UNOR_INDUK_ID] = $record->NAMA_UNOR;
                $jmles1[$record->ESELON_1]["tk_".$record->TINGKAT] = $jmles1[$record->ESELON_1]["tk_".$record->TINGKAT] + $record->total;
            }
        endif;
        // echo "<pre>";
        // print_r($recordtkpendidikan);
        // echo "</pre>";
        // die();
        Template::set('tkpendidikan', $tkpendidikan);
        Template::set('dataeselon1', $dataeselon1);
        Template::set('dataunit', $dataunit);
        Template::set('datajumlah', $datajumlah);
        Template::set('jmles1', $jmles1);
        Template::set('tahun', date("Y"));
        Template::set('toolbar_title', "Proyeksi Pensiun Persatker");
        Template::render();
    }
    public function detilsatkerumur(){
        $this->load->model('pegawai/unitkerja_model');
        $umur = $this->input->get('umur') ? $this->input->get('umur') : "-";
        $unit = $this->input->get('unit') ? $this->input->get('unit') : "-";
        $tkpendidikan = $this->input->get('tkpendidikan')?$this->input->get('tkpendidikan') : "-";
        $unor = $this->unitkerja_model->find_by("ID",TRIM($unit));
        Template::set('unit', $unit);
        Template::set('umur', $umur);
        Template::set('tkpendidikan', $tkpendidikan);
        Template::set('unit_name', $unor->NAMA_UNOR);
        Template::set('toolbar_title', "Detil Umur");
        Template::render();
    }
    public function getdatadetilumur($umur,$unit,$tkpendidikan){
        $this->auth->restrict($this->permissionView);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
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
        if($unit != "" && $unit != "-"){
            $this->db->group_start();
            // $this->db->where('vw."UNOR_ID"',$unit);    
            $this->db->or_where('vw."UNOR_INDUK"',$unit);   
            $this->db->group_end();
        }
        if($umur != ""){
            if($umur=="25"){
                $this->pegawai_model->where('calc_age("TGL_LAHIR") <',$umur*12);    
            }
            if($umur=="2530"){
                $this->db->group_start();
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") >=',25*12);    
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") <=',30*12);    
                $this->db->group_end();
            }
            if($umur=="3135"){
                $this->db->group_start();
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") >=',31*12);    
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") <=',35*12);    
                $this->db->group_end();
            }
            if($umur=="3640"){
                $this->db->group_start();
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") >=',36*12);    
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") <=',40*12);    
                $this->db->group_end();
            }
            if($umur=="4145"){
                $this->db->group_start();
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") >=',41*12);    
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") <=',45*12);    
                $this->db->group_end();
            }
            if($umur=="4650"){
                $this->db->group_start();
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") >=',46*12);    
                    $this->pegawai_model->where('calc_age("TGL_LAHIR") <=',50*12);    
                $this->db->group_end();
            }
            if($umur=="50"){
                $this->pegawai_model->where('calc_age("TGL_LAHIR") >',$umur*12);    
            }
        
        }
        if($tkpendidikan != "-"){
            $this->pegawai_model->where('pendidikan.TINGKAT_PENDIDIKAN_ID',$tkpendidikan);  
        }
        
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $asatkers = null;
        $total= $this->pegawai_model->count_all_detil_umur_satker($this->UNOR_ID,false,"");
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
        $records=$this->pegawai_model->find_all_detil_umur_satker($this->UNOR_ID,false,$kedudukan_hukum);
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
                $btn_actions = array();
                $btn_actions  [] = "
                    <a class='show-modal-custom' href='".base_url()."admin/kepegawaian/pegawai/profilen/".urlencode(base64_encode($record->ID))."'  data-toggle='tooltip' title='Lihat Profile'><span class='fa-stack'>
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
        
    }
    public function satkerumurdownload()
    {
        $this->auth->restrict($this->permissionView);
        $this->load->model('pegawai/vw_eselon1_model');
        $this->load->model('tkpendidikan/tkpendidikan_model');
        $recordsEs1 = $this->vw_eselon1_model->find_all();
        $records = $this->pegawai_model->umurbySatker();
        $tkpendidikan = $this->tkpendidikan_model->find_all();
        $recordtkpendidikan = $this->pegawai_model->findPegawaiTingkatPendidikanSatker();
        $datajumlah = array();
        $dataunit = array();
        $dataeselon1 = array();
        $jmles1 = array();
        if(isset($recordsEs1) && is_array($recordsEs1) && count($recordsEs1)):
            foreach ($recordsEs1 as $record) {
                $jmles1[$record->ID]['25'] = 0;
                $jmles1[$record->ID]['2530'] = 0;
                $jmles1[$record->ID]['3135'] = 0;
                $jmles1[$record->ID]['3640'] = 0;
                $jmles1[$record->ID]['4145'] = 0;
                $jmles1[$record->ID]['4650'] = 0;
                $jmles1[$record->ID]['50'] = 0;
                // pendidikan
                foreach ($tkpendidikan as $rectk) {
                    $jmles1[$record->ID]["tk_".$rectk->TINGKAT] = 0;
                }
                $dataeselon1[$record->ID] = $record->NAMA_UNOR;
            }
        endif;   
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                if($record->ESELON_1 == $record->UNOR_INDUK_ID)
                    $dataeselon1[$record->ESELON_1] = $record->NAMA_UNOR;
                $datajumlah[$record->UNOR_INDUK_ID]['25'] = $record->dualima;
                $datajumlah[$record->UNOR_INDUK_ID]['2530'] = $record->dualimatigapuluh;
                $datajumlah[$record->UNOR_INDUK_ID]['3135'] = $record->tigasatutigalima;
                $datajumlah[$record->UNOR_INDUK_ID]['3640'] = $record->tigaenamempatpuluh;
                $datajumlah[$record->UNOR_INDUK_ID]['4145'] = $record->empatsatuempatlima;
                $datajumlah[$record->UNOR_INDUK_ID]['4650'] = $record->empatenamlimapuluh;
                $datajumlah[$record->UNOR_INDUK_ID]['50'] = $record->limapuluh;

                $dataunit[$record->ESELON_1][$record->UNOR_INDUK_ID] = $record->NAMA_UNOR != "" ? $record->NAMA_UNOR : $record->UNOR_INDUK_ID;
                $jmles1[$record->ESELON_1]['25'] = $jmles1[$record->ESELON_1]['25'] + $record->dualima;
                $jmles1[$record->ESELON_1]['2530'] = $jmles1[$record->ESELON_1]['2530'] + $record->dualimatigapuluh;
                $jmles1[$record->ESELON_1]['3135'] = $jmles1[$record->ESELON_1]['3135'] + $record->tigasatutigalima;
                $jmles1[$record->ESELON_1]['3640'] = $jmles1[$record->ESELON_1]['3640'] + $record->tigaenamempatpuluh;
                $jmles1[$record->ESELON_1]['4145'] = $jmles1[$record->ESELON_1]['4145'] + $record->empatsatuempatlima;
                $jmles1[$record->ESELON_1]['4650'] = $jmles1[$record->ESELON_1]['4650'] + $record->empatenamlimapuluh;
                $jmles1[$record->ESELON_1]['50'] = $jmles1[$record->ESELON_1]['50'] + $record->limapuluh;
            }
        endif;
        if(isset($recordtkpendidikan) && is_array($recordtkpendidikan) && count($recordtkpendidikan)):
            foreach ($recordtkpendidikan as $record) {
                if($record->ESELON_1 == $record->UNOR_INDUK_ID)
                    $dataeselon1[$record->ESELON_1] = $record->NAMA_UNOR;
                $datajumlah[$record->UNOR_INDUK_ID]["tk_".$record->TINGKAT] = $record->total;

                // $dataunit[$record->ESELON_1][$record->UNOR_INDUK_ID] = $record->NAMA_UNOR;
                $jmles1[$record->ESELON_1]["tk_".$record->TINGKAT] = $jmles1[$record->ESELON_1]["tk_".$record->TINGKAT] + $record->total;
            }
        endif;
        // echo "<pre>";
        // print_r($datajumlah);
        // echo "</pre>";
        // die();
        $this->load->library('Excel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel = PHPExcel_IOFactory::load(trim($this->settings_lib->item('site.pathuploaded')).'template.xls');

        $objPHPExcel->setActiveSheetIndex(0);
        $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,1,"No");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,1,"Unit");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,1,"<25");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,1,"25-30");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,1,"31-35");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,1,"36-40");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,1,"41-45");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,1,"46-50");$col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,1,">50");$col++;
        foreach ($tkpendidikan as $rectk) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,1,$rectk->ABBREVIATION);$col++;
        }
        $row = 2;
                    $noes1 = 1;
                    foreach($dataeselon1 as $x => $vales) {
                        $col = 0;
                        $xlink = $x != "" ? $x : "-";
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$noes1,PHPExcel_Cell_DataType::TYPE_STRING);$col++;$noes1++;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$vales,PHPExcel_Cell_DataType::TYPE_STRING);$col++;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$datajumlah[$x]['25'],PHPExcel_Cell_DataType::TYPE_STRING);$col++;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$datajumlah[$x]['2530'],PHPExcel_Cell_DataType::TYPE_STRING);$col++;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$datajumlah[$x]['3135'],PHPExcel_Cell_DataType::TYPE_STRING);$col++;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$datajumlah[$x]['3640'],PHPExcel_Cell_DataType::TYPE_STRING);$col++;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$datajumlah[$x]['4145'],PHPExcel_Cell_DataType::TYPE_STRING);$col++;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$datajumlah[$x]['4650'],PHPExcel_Cell_DataType::TYPE_STRING);$col++;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$datajumlah[$x]['50'],PHPExcel_Cell_DataType::TYPE_STRING);$col++;
                        
                        foreach ($tkpendidikan as $rectk) {
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$datajumlah[$x]['tk_'.$rectk->ID],PHPExcel_Cell_DataType::TYPE_STRING);$col++;
                        }
                        $row++;
                        
                        $nounit = 1;
                        foreach($dataunit[$x] as $u => $valunit) {
                            $col = 0;
                            if($u != $x){
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$nounit,PHPExcel_Cell_DataType::TYPE_STRING);$col++;
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$valunit,PHPExcel_Cell_DataType::TYPE_STRING);$col++;
                                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$datajumlah[$u]['25'],PHPExcel_Cell_DataType::TYPE_STRING);$col++;
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$datajumlah[$u]['2530'],PHPExcel_Cell_DataType::TYPE_STRING);$col++;
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$datajumlah[$u]['3135'],PHPExcel_Cell_DataType::TYPE_STRING);$col++;
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$datajumlah[$u]['3640'],PHPExcel_Cell_DataType::TYPE_STRING);$col++;
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$datajumlah[$u]['4145'],PHPExcel_Cell_DataType::TYPE_STRING);$col++;
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$datajumlah[$u]['4650'],PHPExcel_Cell_DataType::TYPE_STRING);$col++;
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$datajumlah[$u]['50'],PHPExcel_Cell_DataType::TYPE_STRING);$col++;
                                foreach ($tkpendidikan as $rectk) {
                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$datajumlah[$u]['tk_'.$rectk->ID],PHPExcel_Cell_DataType::TYPE_STRING);$col++;
                                }
                                $nounit++;      
                            }
                            $row++;
                        }
                       $row++; 
                    }
                        

       
        $filename = "pegawaiumur".mt_rand(1,100000).'.xls'; //just some random filename
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
        //$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
        $objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
        exit; //done.. exiting!
    }
}