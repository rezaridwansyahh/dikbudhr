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
    
}