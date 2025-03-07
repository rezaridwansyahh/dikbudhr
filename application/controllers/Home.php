<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications.
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Home controller
 *
 * The base controller which displays the homepage of the Bonfire site.
 *
 * @package    Bonfire
 * @subpackage Controllers
 * @category   Controllers
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class Home extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->helper('application');
		$this->load->library('Template');
		$this->load->library('Assets');
		$this->lang->load('application');
		$this->load->library('events');

        $this->load->library('installer_lib');
        if (! $this->installer_lib->is_installed()) {
            $ci =& get_instance();
            $ci->hooks->enabled = false;
            redirect('install');
        }

        // Make the requested page var available, since
        // we're not extending from a Bonfire controller
        // and it's not done for us.
        $this->requested_page = isset($_SESSION['requested_page']) ? $_SESSION['requested_page'] : null;
	}

	//--------------------------------------------------------------------

	/**
	 * Displays the homepage of the Bonfire app
	 *
	 * @return void
	 */
	public function index()
	{
		$this->load->library('users/auth');
		$this->set_current_user();

		Template::render();
	}//end index()
    public function generate_log_transaksi($param_dari,$param_sampai)
    {
        $this->load->model('pegawai/log_transaksi_model');
        $dari = date("Y-m-d");
        if($param_dari != "")
            $dari = $param_dari;
        $sampai = date("Y-m-d");
        if($param_sampai != "")
            $sampai = $param_sampai;
        // jenis kelamin
        $records = $this->log_transaksi_model->getrekap($dari,$sampai);

        // hapus data lama terlebih dahulu yang tahun dan bulan yang sama
        if (isset($records) && is_array($records) && count($records)) :

            $no = 0;
            foreach ($records as $record) :
               // echo $record->NIP;

                $data = array();
                $data['NIP']  = $record->NIP;
                $data['NAMA_KOMPUTER'] = $record->NAMA_KOMPUTER;
                $data['USER']  = $record->NAMA_KOMPUTER;
                $data['TGL_MODIFIKASI']    = $record->TGL_MODIFIKASI;
                $data['JAM_MODIFIKASI']    = $record->JAM_MODIFIKASI;
                $data['YANG_DIUBAH']    = $record->YANG_DIUBAH;
                //$data['MODULE']    = $record->MODULE;
                $this->log_transaksi_model->insert($data); 
                $no++;
            endforeach;
            echo $no;
        endif;

        
    }
    public function generate_jumlah_pegawai()
    {
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('kondisi_pegawai/rpt_jumlah_asn_model');
        $bulan = date("m");
        $tahun = date("Y");
        $jumlah = $this->pegawai_model->count_all_report();
        // hapus data lama terlebih dahulu yang tahun yang sama
        $this->rpt_jumlah_asn_model->delete_where(array('JENIS' => "ASN",'TAHUN' => $tahun));
        $data = array();
        //$data['BULAN']  = $bulan;
        $data['TAHUN']  = $tahun;
        $data['JUMLAH'] = $jumlah;
        $data['JENIS']  = "ASN";
        $this->rpt_jumlah_asn_model->insert($data); 
        // end jumlah asn tahunan
        // jumlah asn Bulanan
        // hapus data lama terlebih dahulu yang tahun yang sama
        $this->rpt_jumlah_asn_model->delete_where(array('JENIS' => "ASN_B",'BULAN' => $bulan,'TAHUN' => $tahun));
        $data = array();
        $data['BULAN']  = $bulan;
        $data['TAHUN']  = $tahun;
        $data['JUMLAH'] = $jumlah;
        $data['JENIS']  = "ASN_B";
        $this->rpt_jumlah_asn_model->insert($data); 
        // end jumlah asn bulanan

        // jenis kelamin
        $jks = $this->pegawai_model->grupbyjk();
        // hapus data lama terlebih dahulu yang tahun dan bulan yang sama
        if (isset($jks) && is_array($jks) && count($jks)) :
            $this->rpt_jumlah_asn_model->delete_where(array('JENIS' => "JK",'TAHUN' => $tahun));

            $datajk = array();
            foreach ($jks as $record) :
                if($record->JENIS_KELAMIN != "")
                    $datajk["Jenis_Kelamin"] = $record->JENIS_KELAMIN;
                else
                    $datajk["Jenis_Kelamin"] = "Belum ditentukan";

                
                $data = array();
                //$data['BULAN']  = $bulan;
                $data['TAHUN']  = $tahun;
                $data['JUMLAH'] = $record->jumlah;
                $data['JENIS']  = "JK";
                $data['KETERANGAN']    = $datajk["Jenis_Kelamin"];
                $this->rpt_jumlah_asn_model->insert($data); 
            endforeach;
        endif;

        // Gol tahunan
        $gols = $this->pegawai_model->find_by_goljenis();
        // hapus data lama terlebih dahulu yang tahun dan bulan yang sama
        if (isset($gols) && is_array($gols) && count($gols)) :
            $this->rpt_jumlah_asn_model->delete_where(array('JENIS' => "GOL",'TAHUN' => $tahun));
            foreach ($gols as $record) :
                $data = array();
                $data['TAHUN']  = $tahun;
                $data['JUMLAH'] = $record->jumlah;
                $data['JENIS']  = "GOL";
                $data['KETERANGAN']    = $record->GOL;;
                $this->rpt_jumlah_asn_model->insert($data); 
            endforeach;
        endif;
        // end golongan tahunan
        // golongan bulanan
        $gols = $this->pegawai_model->find_by_goljenis();
        // hapus data lama terlebih dahulu yang tahun dan bulan yang sama
        if (isset($gols) && is_array($gols) && count($gols)) :
            $this->rpt_jumlah_asn_model->delete_where(array('JENIS' => "GOL_B",'BULAN' => $bulan,'TAHUN' => $tahun));
            foreach ($gols as $record) :
                $data = array();
                $data['BULAN']  = $bulan;
                $data['TAHUN']  = $tahun;
                $data['JUMLAH'] = $record->jumlah;
                $data['JENIS']  = "GOL_B";
                $data['KETERANGAN']    = $record->GOL;;
                $this->rpt_jumlah_asn_model->insert($data); 
            endforeach;
        endif;
        // end golongan bulanan

        // jenis jabatan tahunan
        $jenisjabatans = $this->pegawai_model->find_by_jenisjabatan();
        // hapus data lama terlebih dahulu yang tahun dan bulan yang sama
        if (isset($jenisjabatans) && is_array($jenisjabatans) && count($jenisjabatans)) :
            $this->rpt_jumlah_asn_model->delete_where(array('JENIS' => "JENIS_JABATAN",'TAHUN' => $tahun));
            foreach ($jenisjabatans as $record) :
                $data = array();
                $data['TAHUN']  = $tahun;
                $data['JUMLAH'] = $record->jumlah;
                $data['JENIS']  = "JENIS_JABATAN";
                $data['KETERANGAN']    = $record->JENIS_JABATAN;;
                $this->rpt_jumlah_asn_model->insert($data); 
            endforeach;
        endif;
        // jenis jabatan bulanan
        $jenisjabatans = $this->pegawai_model->find_by_jenisjabatan();
        // hapus data lama terlebih dahulu yang tahun dan bulan yang sama
        if (isset($jenisjabatans) && is_array($jenisjabatans) && count($jenisjabatans)) :
            $this->rpt_jumlah_asn_model->delete_where(array('JENIS' => "JENIS_JABATAN_B",'BULAN' => $bulan,'TAHUN' => $tahun));
            foreach ($jenisjabatans as $record) :
                $data = array();
                $data['BULAN']         = $bulan;
                $data['TAHUN']         = $tahun;
                $data['JUMLAH']        = $record->jumlah;
                $data['JENIS']         = "JENIS_JABATAN_B";
                $data['KETERANGAN']    = $record->JENIS_JABATAN;;
                $this->rpt_jumlah_asn_model->insert($data); 
            endforeach;
        endif;
        

        // tingkat pendidikan
        $jsonpendidikan = array();
        $recordpendidikan = $this->pegawai_model->grupbytingkatpendidikan();
        if (isset($recordpendidikan) && is_array($recordpendidikan) && count($recordpendidikan)) :
            $this->rpt_jumlah_asn_model->delete_where(array('JENIS' => "TK_PENDIDIKAN",'TAHUN' => $tahun));
            foreach ($recordpendidikan as $record) :
                $data = array();
                $data['TAHUN']          = $tahun;
                $data['JUMLAH']         = $record->jumlah;
                $data['JENIS']          = "TK_PENDIDIKAN";
                $data['KETERANGAN']     = $record->TINGKAT;;
                $this->rpt_jumlah_asn_model->insert($data); 
            endforeach;
        endif;

        // tingkat pendidikan bulanan
        $jsonpendidikan = array();
        $recordpendidikan = $this->pegawai_model->grupbytingkatpendidikan();
        if (isset($recordpendidikan) && is_array($recordpendidikan) && count($recordpendidikan)) :
            $this->rpt_jumlah_asn_model->delete_where(array('JENIS' => "TK_PENDIDIKAN_B",'BULAN' => $bulan,'TAHUN' => $tahun));
            foreach ($recordpendidikan as $record) :
                $data = array();
                $data['BULAN']          = $bulan;
                $data['TAHUN']          = $tahun;
                $data['JUMLAH']         = $record->jumlah;
                $data['JENIS']          = "TK_PENDIDIKAN_B";
                $data['KETERANGAN']     = $record->TINGKAT;;
                $this->rpt_jumlah_asn_model->insert($data); 
            endforeach;
        endif;
        // umur jenis kelamin
        $recordumur = $this->pegawai_model->group_by_range_umur_jk($this->UNOR_ID);
        $ajsonumur = array();
        $index = 0 ;
        for($i=0;$i<count($recordumur[0]);$i++){
            if(isset($recordumur[$i])){
                foreach(array_keys($recordumur[$i]) as $key){
                    if($key == "JENIS_KELAMIN")
                    {
                        $JK = $recordumur[$i]['JENIS_KELAMIN'];
                        $this->rpt_jumlah_asn_model->delete_where(array('JENIS' => "UMUR_".$JK,'TAHUN' => $tahun));
                    }else{
                        $data = array();
                        $data['TAHUN']          = $tahun;
                        $data['JUMLAH']         = $recordumur[$i][$key];
                        $data['JENIS']          = "UMUR_".$JK;
                        $data['KETERANGAN']     = $key;
                        $this->rpt_jumlah_asn_model->insert($data); 
                    }
                }
                
            }
        }
        
        $this->load->model('sk_ds/file_ttd_model');
        $id = $this->uri->segment(5);
        $records = $this->file_ttd_model->find_all($id);
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $file_name = $record->nip.".png";
                $b64 = trim($record->base64ttd);
                $bin = base64_decode($b64, true);
                $direktorispesimen = trim($this->settings_lib->item('site.pathspesimen'));
                if (file_exists($direktorispesimen.$file_name)) {
                   unlink($direktorispesimen.$file_name);
                }
                //echo $direktorispesimen.$file_name;
                if(file_put_contents(trim($direktorispesimen).$file_name, $bin)){
                    //echo "berhasil<br>";
                }else{
                    //echo "Gagal<br>";
                }

            }
        endif;
        
        $query = "REFRESH MATERIALIZED VIEW mv_pegawai;";
        $this->db->query($query);

        $query = "REFRESH MATERIALIZED VIEW mv_duk;";
        $this->db->query($query);
         


        date_default_timezone_set('Asia/Jakarta');
        // update to log (table setting)
        $this->load->model('settings/settings_model', 'settings_model');
        $dataupdate = array();
        $dataupdate["value"] = date("Y-m-d H:i:s");
        $this->settings_model->update_where("name",'site.updateresume', $dataupdate);
    }
	public function generate_golongan_bulan()
    {
    	$this->load->model('pegawai/pegawai_model');
    	$this->load->model('pegawai/rpt_golongan_bulan_model');
    	$bulan = date("m");
    	$tahun = date("Y");
    	$records = $this->pegawai_model->find_by_golonganbulan($unorid = "");
        $recordinsert = array();
        
    	if(isset($records) && is_array($records) && count($records)):
    		// hapus data lama terlebih dahulu yang tahun dan bulan yang sama
    		$this->rpt_golongan_bulan_model->delete_where(array('BULAN' => $bulan, 'TAHUN' => $tahun));

            foreach ($records as $record) {
            	$rec = array(
                'GOLONGAN_ID'=>$record->GOL_ID,
                'GOLONGAN_NAMA'=>$record->NAMA,
                'BULAN'=>$bulan,
                'TAHUN'=>$tahun,
                'JUMLAH'=>$record->total
            );
            $recordinsert[] = $rec;
            }
        endif;
        $data = array();
        foreach($recordinsert as $key=>$record){
            $data[] = $record;
        }
        $this->db->insert_batch("rpt_golongan_bulan",$data);
    }
    public function generate_pendidikan_bulan()
    {
    	$this->load->model('pegawai/pegawai_model');
    	$this->load->model('kondisi_pegawai/rpt_pendidikan_bulan_model');
    	$bulan = date("m");
    	$tahun = date("Y");
    	$records = $this->pegawai_model->find_by_pendidikanbulan($unorid = "");
        $recordinsert = array();
        
    	if(isset($records) && is_array($records) && count($records)):
    		// hapus data lama terlebih dahulu yang tahun dan bulan yang sama
    		$this->rpt_pendidikan_bulan_model->delete_where(array('BULAN' => $bulan, 'TAHUN' => $tahun));

            foreach ($records as $record) {
            	$rec = array(
                //'PENDIDIKAN_ID'=>$record->PENDIDIKAN_ID,
                'TINGKAT_PENDIDIKAN'=>$record->TINGKAT_PENDIDIKAN,
                'NAMA_TINGKAT'=>$record->NAMA_TINGKAT,
                'BULAN'=>$bulan,
                'TAHUN'=>$tahun,
                'JUMLAH'=>$record->total
            );
            $recordinsert[] = $rec;
            }
        endif;
        $data = array();
        foreach($recordinsert as $key=>$record){
            $data[] = $record;
        }
        $this->db->insert_batch("rpt_pendidikan_bulan",$data);
    }
	public function generatespesimen()
    {
        $this->load->model('sk_ds/file_ttd_model');
        $id = $this->uri->segment(5);
        $records = $this->file_ttd_model->find_all($id);
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $file_name = $record->nip.".png";
                $b64 = trim($record->base64ttd);
                $bin = base64_decode($b64, true);
                $direktorispesimen = trim($this->settings_lib->item('site.pathspesimen'));
                if (file_exists($direktorispesimen.$file_name)) {
                   unlink($direktorispesimen.$file_name);
                }
                //echo $direktorispesimen.$file_name;
                if(file_put_contents(trim($direktorispesimen).$file_name, $bin)){
                    //echo "berhasil<br>";
                }else{
                    //echo "Gagal<br>";
                }

            }
        endif;
        die();
        
    }
    public function generateharilibur(){
        $this->load->model('izin_pegawai/hari_libur_model');
        $tahun = (int)date("Y");

        $start_date = date("Y")."-01-01";
        $end_date = date("Y")."-12-31";
        $this->load->library('Api_kehadiran');
        $api_kehadiran = new Api_kehadiran;
        $generate_harilibur = $api_kehadiran->getHarilibur($start_date,$end_date);
        $json_harilibur = json_decode($generate_harilibur);
        $dataharilibur = $json_harilibur->data;
        $jml = 0;
        // $datadel = array('extract(year from START_DATE)::int = '.$tahun.'');
        // $this->hari_libur_model->delete_where($datadel);    
        $this->db->query('delete from hari_libur where extract(year from "START_DATE") = '.$tahun.'');
        foreach($dataharilibur as $row)
        {
            // $datadel = array('START_DATE '=>$row->start_date);
            // $this->hari_libur_model->delete_where($datadel);

            $data = array();
            $data['START_DATE']       = $row->start_date;
            $data['END_DATE']         = $row->end_date;
            $data['INFO']          = $row->info;
            if($this->hari_libur_model->insert($data)){
                $jml = $jml + 1;
            } 

        }
        die("Jumlah : ".$jml);
    }
    public function migrasi_peta_jabatan(){
        $default_permen = trim($this->settings_lib->item('peta.permen'));
        // $aunors = Modules::run('pegawai/manage_unitkerja/getcache_unor');
        // $aunor = $this->cache->get('aunor');
        // $json = array();
        // if(isset($records) && is_array($records) && count($records)):
        //         foreach ($records as $record) :
        //             if($record->KODE_INTERNAL != "")
        //                 $json[$record->KODE_INTERNAL] = $record->ID;
        //         endforeach;
        //     endif;
        $this->load->model('petajabatan/kuotajabatan_model');
        // $records = $this->db_mysql->query('
        //                 select ID_Kuota,k.KODE_UNIT_KERJA,ID_JABATAN,JUMLAH_PEMANGKU_JABATAN,
        //                 KETERANGAN,FORMASI,SKALA_PRIORITAS,ID_JABATAN_PENYETARAAN from data_unit_kerja_kuota_jabatan k
        //                 inner join data_unit_kerja d on(k.KODE_UNIT_KERJA = d.KODE_UNIT_KERJA)
        //                 where AKTIF = 1
        //                 ORDER BY ID_Kuota asc limit 10')->result();
        // $this->db->query("delete from kuota_jabatan");

        $result = false;
        $index = 0;
        $datadelete = array('PERATURAN'=>$default_permen);
        $this->kuotajabatan_model->delete_where($datadelete);
        
        $records = $this->getpetajabatan();
        $records = json_decode($records);

            foreach ($records as $record) :
                $jabatans[] =  $record->kode_jabatan;
                $rec = array(
                    'KODE_UNIT_KERJA'=>$record->id_unor_bkn,
                    'KD_INTERNAL'=>$record->kode_unit_kerja,
                    'ID_JABATAN'=>$record->kode_jabatan,
                    'JUMLAH_PEMANGKU_JABATAN'=>$record->jumlah_kebutuhan,
                    'KETERANGAN'=>$record->keterangan,
                    'FORMASI'=>$record->formasi,
                    'SKALA_PRIORITAS'=>$record->skala_prioritas,
                    'PERATURAN'=>$default_permen,
                    'kepmen_peta_jabatan'=>$record->kepmen_peta_jabatan,
                    'nomor_kepmen_peta_jabatan'=>$record->nomor_kepmen_peta_jabatan,
                    'tentang_kepmen_peta_jabatan'=>$record->tentang_kepmen_peta_jabatan,
                    'aktif'=>$record->aktif
                );
                $apetajabatan[$index] = $rec;

                // $data['KODE_UNIT_KERJA'] = $record->id_unor_bkn;
                // $data['KD_INTERNAL'] = $record->kode_unit_kerja;
                // $data['ID_JABATAN'] = $record->kode_jabatan;
                // $data['JUMLAH_PEMANGKU_JABATAN'] = $record->jumlah_kebutuhan;
                // $data['KETERANGAN'] = $record->keterangan;
                // $data['FORMASI'] = $record->formasi;
                // $data['SKALA_PRIORITAS'] = $record->skala_prioritas;
                // $data['PERATURAN'] = $default_permen;
                // $data['kepmen_peta_jabatan'] = $record->kepmen_peta_jabatan;
                // $data['nomor_kepmen_peta_jabatan'] = $record->nomor_kepmen_peta_jabatan;
                // $data['tentang_kepmen_peta_jabatan'] = $record->tentang_kepmen_peta_jabatan;
                // $data['aktif'] = $record->aktif;
                // $result = $this->kuotajabatan_model->insert($data);
                // if($result){
                //     $index++;    
                // }
                $index++;    
            endforeach;
        // insert batch
        if(sizeof($jabatans)>0){
            $data = array();
            foreach($apetajabatan as $key=>$record){
                $data[] = $record;
            }
            $result = $this->db->insert_batch("kuota_jabatan",$data);
        }

        if($result){
            $response ['success']= true;
            $response ['msg']= "Selesai generate data (".$index.")";
        }else{
            $response ['success']= false;
            $response ['msg']= "Ada kesalahan (".$index.")";
        }
        echo json_encode($response);  
    }
    private function getpetajabatan(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://mutasi.sdm.kemdikbud.go.id/layanan/json/ws_peta_kebutuhan_jabatan.php",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "Cache-Control: no-cache",
            "Postman-Token: 5997b268-5f53-2dcb-41f2-a6850dc3f3ca"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          return $err;
        }
        return $response;
    }
	//--------------------------------------------------------------------

	/**
	 * If the Auth lib is loaded, it will set the current user, since users
	 * will never be needed if the Auth library is not loaded. By not requiring
	 * this to be executed and loaded for every command, we can speed up calls
	 * that don't need users at all, or rely on a different type of auth, like
	 * an API or cronjob.
	 *
	 * Copied from Base_Controller
	 */
	protected function set_current_user()
	{
        if (class_exists('Auth')) {
			// Load our current logged in user for convenience
            if ($this->auth->is_logged_in()) {
				$this->current_user = clone $this->auth->user();

				$this->current_user->user_img = gravatar_link($this->current_user->email, 22, $this->current_user->email, "{$this->current_user->email} Profile");

				// if the user has a language setting then use it
                if (isset($this->current_user->language)) {
					$this->config->set_item('language', $this->current_user->language);
				}
            } else {
				$this->current_user = null;
			}

			// Make the current user available in the views
            if (! class_exists('Template')) {
				$this->load->library('Template');
			}
			Template::set('current_user', $this->current_user);
		}
	}
}
/* end ./application/controllers/home.php */
