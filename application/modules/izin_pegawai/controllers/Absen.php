<?php defined('BASEPATH') || exit('No direct script access allowed');
require 'application/libraries/vendor/autoload.php';
use GuzzleHttp\Client;
/**
 * Absen controller
 */
class Absen extends Admin_Controller
{
    protected $permissionCreate = 'Izin_pegawai.Izin.Create';
    protected $permissionDelete = 'Izin_pegawai.Izin.Delete';
    protected $permissionEdit   = 'Izin_pegawai.Izin.Edit';
    protected $permissionView   = 'Izin_pegawai.Izin.View';
    public $SATKER_ID = null;
    public $is_pejabat_cuti = null;
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        date_default_timezone_set("Asia/Jakarta");
        $this->load->model('pegawai/pegawai_model');
        if($this->auth->has_permission($this->permissionFiltersatker)){
            $this->SATKER_ID = $this->pegawai_model->getunor_induk($this->auth->username());
        }
    }

    /**
     * Display a list of izin pegawai data.
     *
     * @return void
     */
    public function index()
    {
        $this->auth->restrict($this->permissionView);
        $jenis_izin = $this->jenis_izin_model->find_active();
        Template::set('jenis_izin', $jenis_izin);
        Template::set('toolbar_title', lang('izin_pegawai_manage'));
        Template::render();
    }
    public function check_area()
    {
        $end_point = 'http://127.0.0.1:8000';
        $latitude = $this->input->post('lat');
        $longitude = $this->input->post('lng');
        $res = $this->makeRequestFn($end_point . "/api/lipi_area/is_inside", [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
        $this->print_json($res);
    }
    public function testabsen(){
        $this->load->library('Convert');
        $convert = new Convert;
        // absen WFH
        $tanggal = date("Y-m-d");
        $tanggal_indonesia = $convert->fmtDate($tanggal,"dd month yyyy");
        $hari = $convert->Gethari($tanggal);
        $ses_nip    = trim($this->auth->username());

        Template::set('toolbar_title', "Seting Area Kantor");
        Template::render();
    }
    public function testabsenmap(){
        $this->load->library('Convert');
        $convert = new Convert;
        // absen WFH
        $tanggal = date("Y-m-d");
        $tanggal_indonesia = $convert->fmtDate($tanggal,"dd month yyyy");
        $hari = $convert->Gethari($tanggal);
        $ses_nip    = trim($this->auth->username());

        Template::set('toolbar_title', "Tap Presensi BDR");
        Template::render();
    }
}