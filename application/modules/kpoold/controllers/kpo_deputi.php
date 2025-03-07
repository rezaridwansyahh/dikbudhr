<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Kpo_deputi extends Admin_Controller
{
    protected $permissionCreate = 'LayananKpo.View.PengelolaKPOSatker';
    protected $permissionEdit   = 'LayananKpo.Masters.Edit';
    protected $permissionView   = 'LayananKpo.Cetak';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('kpo/layanan_kpo_model');
        $this->load->model('pegawai/pegawai_model');
		$this->load->helper('kpo/layanan_kpo');
        $this->role = $this->session->userdata('role_id');
        Template::set('module_url',base_url('kpo/kpo-deputi'));
        $this->satker_pegawai = getEselon1Pegawai($this->auth->username());
    }
    public function select2_list_pegawai(){
        $term = $this->input->get('term');
        $this->pegawai_model->like('lower("NAMA")',strtolower($term),"BOTH");

        $records=$this->pegawai_model->find_all($this->satker_pegawai);
        $o= array();
        foreach($records as $record){
            $o['results'][] = array(
                'id'=>$record->PNS_ID,
                'text'=>$record->NIP_BARU ." | ". $record->NAMA ." | ". $record->nama_satker
            );
        }
        echo json_encode($o);
    }
    
    public function ajax_list(){
        
        $draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length');
		$start= $this->input->post('start');

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        
        $this->db->where('perkiraan_kpo.status >','0');

        $this->layanan_kpo_model->db->group_start();
            $this->layanan_kpo_model->db->where("vw.ID",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_4",$this->satker_pegawai);
        $this->layanan_kpo_model->db->group_end();

        $total= $this->layanan_kpo_model->count_all();
        
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();


		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if($search!=""){
			$this->layanan_kpo_model->where('upper("NAMA") LIKE \'%'.strtoupper($search).'%\'');

        }
		$this->layanan_kpo_model->limit($length,$start);
		
		$kolom = $iSortCol != "" ? $iSortCol : "name";
		$sSortCol == "asc" ? "asc" : "desc";
        $this->layanan_kpo_model->order_by($iSortCol,$sSortCol);
        
        $this->db->where('perkiraan_kpo.status >','0');    
        $this->layanan_kpo_model->db->group_start();
            $this->layanan_kpo_model->db->where("vw.ID",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_4",$this->satker_pegawai);
        $this->layanan_kpo_model->db->group_end();
        $this->db->order_by('perkiraan_kpo.status','desc'); 
		$records=$this->layanan_kpo_model->find_all();

		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($search != "")
		{
			$this->layanan_kpo_model->where('upper("NAMA") LIKE \'%'.strtoupper($search).'%\'');
		    $jum	= $this->layanan_kpo_model->count_all();
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}
		
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = get_status_layanan_kpo($record->status);
                $row []  = $record->no_surat_pengantar;
                $row []  = $record->NIP_BARU."<br>".$record->NAMA;
                $informasi_tambahan = array();
                if($record->rwt_hukdis_kpo){
                    $informasi_tambahan[] = "Hukuman Disiplin : ".$record->rwt_hukdis_kpo;
                }
                if($record->last_ppk){
                    $informasi_tambahan[] = "Nilai PPK Terakhir : ".$record->last_ppk;
                }
                $row []  = implode("<br>",$informasi_tambahan);
                $row []  = $record->nama_satker;
                
                $btn_actions = array();
                $btn_actions  [] = "
                    <a  href='".base_url()."admin/kepegawaian/pegawai/profile/".$record->ID."'  data-toggle='tooltip' title='Profile Pegawai'><span class='fa-stack'>
					   	<i class='fa fa-square fa-stack-2x'></i>
					   	<i class='fa fa-eye fa-stack-1x fa-inverse'></i>
					   	</span>
					   	</a>
                ";
                if($record->status<7){
                    $btn_actions  [] = "
                        <a class='show-modal-custom' href='".base_url()."kpo/kpo-deputi/edit/".$record->usulan_id."'  data-toggle='tooltip' title='Ubah Data'><span class='fa-stack'>
                            <i class='fa fa-square fa-stack-2x'></i>
                            <i class='fa fa-pencil fa-stack-1x fa-inverse'></i>
                            </span>
                            </a>
                    ";
                }
                $btn_actions  [] = "
                <a  href='".base_url()."kpo/kpo-satker/dokumen_pendukung/".$record->usulan_id."'  data-toggle='tooltip' title='Upload Dokumen Pendukung Data'><span class='fa-stack'>
                       <i class='fa fa-square fa-stack-2x'></i>
                       <i class='fa fa-upload fa-stack-1x fa-inverse'></i>
                       </span>
                       </a>
            ";
                if($this->auth->has_permission($this->permissionDelete))   {
                    $btn_actions  [] = "
                            <a href='#' kode='$record->usulan_id' class='btn-hapus' data-toggle='tooltip' title='Hapus data' >
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
		die();
    }
    public function index(){
        Template::set('PNS_ID', $PNS_ID);
        Template::set('TAB_ID', uniqid("TAB_CONTOH"));
		Template::set_view('layanan_kpo/kpo_deputi/v_usulan_kpo');
        Template::render();
    }
	
   
	public function edit($ID)
    {
        $list_status = array(
            array(
                'name'=>'Memenuhi Syarat (MS)',
                'value'=>'6'
            ),
            array(
                'name'=>'Tidak Memenuhi Syarat (TMS)',
                'value'=>'5'
            ),
        );
        //$this->auth->restrict($this->permissionEdit); 
        if($this->input->post('save')){
          $response = $this->save();
          if($response['success']){
                Template::set_message($response['msg'], 'success');
                redirect(base_url('kpo/kpo-satker'));
          }
          else {
              if(isset($response['msg'])){
                Template::set_message($response['msg'], 'error');
              } 
          }
        }
        //$this->auth->restrict($this->permissionCreate); 
        $selectedData = $this->layanan_kpo_model->find($ID);
        Template::set('list_status',$list_status);
        Template::set('selectedData',$selectedData);
        //print_r($selectedData);
		Template::set_view('layanan_kpo/kpo_deputi/v_form_crud_usulan');
        Template::set('toolbar_title', "Form Usulan KPO");

        Template::render();
       
    }
	
    public function save_usulan(){
         // Validate the data
         $this->form_validation->set_rules($this->layanan_kpo_model->get_validation_rules());
         $response = array(
             'success'=>false
         );
         $data = $this->layanan_kpo_model->prep_data($this->input->post());
             
         $id_data = $this->input->post("id");
         if(isset($id_data) && !empty($id_data)){    
             $this->layanan_kpo_model->update($id_data,$data);
             $store_data = array(
                 'usulan_id'=>$id_data,
                 'status'=>$data['status'],
                 'alasan'=>$data['alasan']
             );
             $this->db->insert('perkiraan_usulan_log',$store_data);
             $response ['success']= true;
             $response ['message']= "Transaksi berhasil";
         }           
         echo json_encode($response);
    }
    public function form_kirim_usulan(){
        $this->db->where('perkiraan_kpo.status','4');
        $this->layanan_kpo_model->db->group_start();
            $this->layanan_kpo_model->db->where("vw.ID",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_4",$this->satker_pegawai);
        $this->layanan_kpo_model->db->group_end();
        $num_generate_xls=$this->layanan_kpo_model->count_all();
        if($num_generate_xls>0){
            echo "<center><b>Data tidak dapat di kirim, masih ada yang belum di verifikasi</b></center>";    
            return;
        }

        $this->db->where('perkiraan_kpo.status >','6');
        $this->layanan_kpo_model->db->group_start();
            $this->layanan_kpo_model->db->where("vw.ID",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_4",$this->satker_pegawai);
        $this->layanan_kpo_model->db->group_end();
        $num_generate_xls=$this->layanan_kpo_model->count_all();
        if($num_generate_xls>0){
            echo "<center><b>Form Kirim ke Verifikator Instansi tidak dapat ditampilkan, Tidak ada data yang Memenuhi Syarat atau Data telah terkirim</b></center>";    
            return;
        }
       
        Template::set_view('layanan_kpo/kpo_deputi/v_form_kirim_usulan');
        Template::set('toolbar_title', "Form Usulan KPO");

        Template::render();
    }
    function do_kirim_usulan(){
        $this->db->where('perkiraan_kpo.status','6');
        $this->layanan_kpo_model->db->group_start();
            $this->layanan_kpo_model->db->where("vw.ID",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_1",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_2",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_3",$this->satker_pegawai);
            $this->layanan_kpo_model->db->or_where("vw.ESELON_4",$this->satker_pegawai);
        $this->layanan_kpo_model->db->group_end();

        $records = $this->layanan_kpo_model->find_all();
        if(sizeof($records)>0){
            $no_surat_pengantar = $this->input->post('no_surat_pengantar');
            $update_data = array();
            foreach($records as $record){
                $update_data[] = array(
                    'status'=>'7',
                    'id'=>$record->usulan_id
                );
            }
            
            $this->db->update_batch('perkiraan_kpo',$update_data,'id');

            $output = array(
                'success'=>true
            );
            echo json_encode($output);
        }
        
        
    }
}
