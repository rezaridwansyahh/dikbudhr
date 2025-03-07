<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Layanan controller
 */
class Services extends Front_Controller
{
    protected $permissionCreate = 'Pindah_unit.Layanan.Create';
    protected $permissionDelete = 'Pindah_unit.Layanan.Delete';
    protected $permissionEdit   = 'Pindah_unit.Layanan.Edit';
    protected $permissionView   = 'Pindah_unit.Layanan.View';
    protected $permissionViewUnitAsal   = 'Pindah_unit.Layanan.UnitAsal';
    protected $permissionViewUnitTujuan   = 'Pindah_unit.Layanan.UnitTujuan';
    protected $permissionViewBiro   = 'Pindah_unit.Layanan.Biro';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        //$this->auth->restrict($this->permissionView);
        $this->load->model('pindah_unit/pindah_unit_model');
        $this->load->model('pegawai/pegawai_model');
        $this->lang->load('pindah_unit');
        
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'layanan/_sub_nav');

        Assets::add_module_js('pindah_unit', 'pindah_unit.js');
        $this->load->model('pegawai/unitkerja_model');
        Template::set('recsatker', $this->unitkerja_model->find_satker());
    }
    public function detail($ref)
    {
           
        echo "Barcode detail : ".$ref ;
		
    }
}