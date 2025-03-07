<?php defined('BASEPATH') || exit('No direct script access allowed');

class Izin_pegawai_model extends BF_Model
{
    protected $table_name	= 'izin';
	protected $key			= 'ID';
	protected $date_format	= 'datetime';

	protected $log_user 	= false;
	protected $set_created	= false;
	protected $set_modified = false;
	protected $soft_deletes	= false;


	// Customize the operations of the model without recreating the insert,
    // update, etc. methods by adding the method names to act as callbacks here.
	protected $before_insert 	= array();
	protected $after_insert 	= array();
	protected $before_update 	= array();
	protected $after_update 	= array();
	protected $before_find 	    = array();
	protected $after_find 		= array();
	protected $before_delete 	= array();
	protected $after_delete 	= array();

	// For performance reasons, you may require your model to NOT return the id
	// of the last inserted row as it is a bit of a slow method. This is
    // primarily helpful when running big loops over data.
	protected $return_insert_id = true;

	// The default type for returned row data.
	protected $return_type = 'object';

	// Items that are always removed from data prior to inserts or updates.
	protected $protected_attributes = array();

	// You may need to move certain rules (like required) into the
	// $insert_validation_rules array and out of the standard validation array.
	// That way it is only required during inserts, not updates which may only
	// be updating a portion of the data.
	protected $validation_rules 		= array(
		array(
			'field' => 'NIP_PNS',
			'label' => 'lang:izin_pegawai_field_NIP_PNS',
			'rules' => 'required|max_length[18]',
		),
		array(
			'field' => 'NAMA',
			'label' => 'lang:izin_pegawai_field_NAMA',
			'rules' => 'max_length[100]',
		),
		array(
			'field' => 'JABATAN',
			'label' => 'lang:izin_pegawai_field_JABATAN',
			'rules' => 'max_length[255]',
		),
		array(
			'field' => 'UNIT_KERJA',
			'label' => 'lang:izin_pegawai_field_UNIT_KERJA',
			'rules' => 'max_length[250]',
		),
		array(
			'field' => 'MASA_KERJA_TAHUN',
			'label' => 'lang:izin_pegawai_field_MASA_KERJA_TAHUN',
			'rules' => '',
		),
		array(
			'field' => 'MASA_KERJA_BULAN',
			'label' => 'lang:izin_pegawai_field_MASA_KERJA_BULAN',
			'rules' => '',
		),
		array(
			'field' => 'GAJI_POKOK',
			'label' => 'lang:izin_pegawai_field_GAJI_POKOK',
			'rules' => 'max_length[10]',
		),
		array(
			'field' => 'KODE_IZIN',
			'label' => 'lang:izin_pegawai_field_KODE_IZIN',
			'rules' => 'required|max_length[5]',
		),
		array(
			'field' => 'DARI_TANGGAL',
			'label' => 'lang:izin_pegawai_field_DARI_TANGGAL',
			'rules' => '',
		),
		array(
			'field' => 'SAMPAI_TANGGAL',
			'label' => 'lang:izin_pegawai_field_SAMPAI_TANGGAL',
			'rules' => '',
		),
		array(
			'field' => 'TAHUN',
			'label' => 'lang:izin_pegawai_field_TAHUN',
			'rules' => 'max_length[4]',
		),
		array(
			'field' => 'JUMLAH',
			'label' => 'lang:izin_pegawai_field_JUMLAH',
			'rules' => '',
		),
		array(
			'field' => 'SATUAN',
			'label' => 'lang:izin_pegawai_field_SATUAN',
			'rules' => 'max_length[10]',
		),
		array(
			'field' => 'KETERANGAN',
			'label' => 'lang:izin_pegawai_field_KETERANGAN',
			'rules' => 'max_length[255]',
		),
		array(
			'field' => 'ALAMAT_SELAMA_CUTI',
			'label' => 'lang:izin_pegawai_field_ALAMAT_SELAMA_CUTI',
			'rules' => 'max_length[255]',
		),
		array(
			'field' => 'TLP_SELAMA_CUTI',
			'label' => 'lang:izin_pegawai_field_TLP_SELAMA_CUTI',
			'rules' => 'max_length[20]',
		),
		array(
			'field' => 'TGL_DIBUAT',
			'label' => 'lang:izin_pegawai_field_TGL_DIBUAT',
			'rules' => '',
		),
		array(
			'field' => 'LAMPIRAN_FILE',
			'label' => 'lang:izin_pegawai_field_LAMPIRAN_FILE',
			'rules' => 'max_length[50]',
		),
		array(
			'field' => 'SISA_CUTI_TAHUN_N2',
			'label' => 'lang:izin_pegawai_field_SISA_CUTI_TAHUN_N2',
			'rules' => '',
		),
		array(
			'field' => 'SISA_CUTI_TAHUN_N1',
			'label' => 'lang:izin_pegawai_field_SISA_CUTI_TAHUN_N1',
			'rules' => '',
		),
		array(
			'field' => 'SISA_CUTI_TAHUN_N',
			'label' => 'lang:izin_pegawai_field_SISA_CUTI_TAHUN_N',
			'rules' => '',
		),
		array(
			'field' => 'ANAK_KE',
			'label' => 'lang:izin_pegawai_field_ANAK_KE',
			'rules' => 'max_length[1]',
		),
		 
		array(
			'field' => 'NIP_ATASAN',
			'label' => 'lang:izin_pegawai_field_NIP_ATASAN',
			'rules' => 'max_length[25]',
		),
		array(
			'field' => 'STATUS_ATASAN',
			'label' => 'lang:izin_pegawai_field_STATUS_ATASAN',
			'rules' => '',
		),
		array(
			'field' => 'CATATAN_ATASAN',
			'label' => 'lang:izin_pegawai_field_CATATAN_ATASAN',
			'rules' => 'max_length[255]',
		),
		array(
			'field' => 'NIP_PYBMC',
			'label' => 'lang:izin_pegawai_field_NIP_PYBMC',
			'rules' => 'max_length[25]',
		),
		array(
			'field' => 'STATUS_PYBMC',
			'label' => 'lang:izin_pegawai_field_STATUS_PYBMC',
			'rules' => '',
		),
		array(
			'field' => 'CATATAN_PYBMC',
			'label' => 'lang:izin_pegawai_field_CATATAN_PYBMC',
			'rules' => 'max_length[255]',
		),
	);
	protected $insert_validation_rules  = array();
	protected $skip_validation 			= false;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    public function find_all()
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.ID,
				KODE_IZIN,
				NIP_PNS,NAMA,NAMA_IZIN,TAHUN,
				TGL_DIBUAT,
				DARI_TANGGAL,SAMPAI_TANGGAL,
				STATUS_ATASAN,STATUS_PYBMC,
				NAMA_ATASAN,NAMA_PYBMC,
				CATATAN_ATASAN,CATATAN_PYBMC,
				LAMPIRAN_FILE,
				JUMLAH,SATUAN,
				izin.KETERANGAN,
				ALASAN_CUTI,TGL_DIBUAT
				SELAMA_JAM,SELAMA_MENIT,
				STATUS_PENGAJUAN');
		}
		$this->db->join('jenis_izin', 'izin.KODE_IZIN = jenis_izin.ID', 'left');
		return parent::find_all();
	}
	public function find_rekap($nip = "")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.
				KODE_IZIN,
				NAMA_IZIN,sum("JUMLAH") AS jumlah_hari,
				count(*) as jumlah_pengajuan
				');
		}
		if($nip != "")
			$this->db->where("NIP_PNS",$nip);
		$this->db->join('jenis_izin', 'izin.KODE_IZIN = jenis_izin.ID', 'left');
		$this->db->group_by("KODE_IZIN");
		$this->db->group_by("NAMA_IZIN");
		return parent::find_all();
	}
	public function count_rekap($nip = "")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.
				KODE_IZIN
				');
		}
		if($nip != "")
			$this->db->where("NIP_PNS",$nip);
		$this->db->join('jenis_izin', 'izin.KODE_IZIN = jenis_izin.ID', 'left');
		$this->db->distinct("KODE_IZIN");
		$this->db->distinct("NAMA_IZIN");
		return parent::count_all();
	}
	public function find_all_pegawai($nip = "")
	{
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.ID,
				KODE_IZIN,
				NIP_PNS,NAMA,NAMA_IZIN,TAHUN,
				DARI_TANGGAL,SAMPAI_TANGGAL,
				STATUS_ATASAN,STATUS_PYBMC,
				NAMA_ATASAN,NAMA_PYBMC,
				CATATAN_ATASAN,CATATAN_PYBMC,
				LAMPIRAN_FILE,
				JUMLAH,SATUAN,
				izin.KETERANGAN,
				ALASAN_CUTI,
				TGL_DIBUAT,
				SELAMA_JAM,
				SELAMA_MENIT,
				IS_SIGNED,
				STATUS_PENGAJUAN');
		}
		$this->db->where("NIP_PNS",$nip);
		$this->db->join('jenis_izin', 'izin.KODE_IZIN = jenis_izin.ID', 'left');
		return parent::find_all();
	}
	public function count_all_pegawai($nip = "")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.ID,
				KODE_IZIN,
				NIP_PNS,NAMA,NAMA_IZIN,TAHUN,
				DARI_TANGGAL,SAMPAI_TANGGAL,
				STATUS_ATASAN,STATUS_PYBMC,
				NAMA_ATASAN,NAMA_PYBMC,
				CATATAN_ATASAN,CATATAN_PYBMC,
				LAMPIRAN_FILE,
				JUMLAH,SATUAN,
				izin.KETERANGAN');
		}
		$this->db->where("NIP_PNS",$nip);
		return parent::count_all();
	}
	public function find_all_satker($unit_kerja = "")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.ID,
				KODE_IZIN,
				NAMA_IZIN,TAHUN,
				NIP_PNS,
				izin.NAMA,
				NAMA_IZIN,TAHUN,
				DARI_TANGGAL,SAMPAI_TANGGAL,
				STATUS_ATASAN,STATUS_PYBMC,
				NAMA_ATASAN,NAMA_PYBMC,
				CATATAN_ATASAN,CATATAN_PYBMC,
				LAMPIRAN_FILE,
				JUMLAH,SATUAN,
				izin.KETERANGAN,
				STATUS_PENGAJUAN
				');
		}
		$this->db->where("UNOR_INDUK_ID",$unit_kerja);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = izin.NIP_PNS', 'left');
		$this->db->join('jenis_izin', 'izin.KODE_IZIN = jenis_izin.ID', 'left');
		return parent::find_all();
	}
	public function count_all_satker($unit_kerja = "")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.ID,
				KODE_IZIN,
				NIP_PNS,
				DARI_TANGGAL,SAMPAI_TANGGAL,
				STATUS_ATASAN,STATUS_PYBMC,
				NAMA_ATASAN,NAMA_PYBMC,
				CATATAN_ATASAN,CATATAN_PYBMC,
				LAMPIRAN_FILE,
				JUMLAH,SATUAN,
				izin.KETERANGAN');
		}
		$this->db->where("UNOR_INDUK_ID",$unit_kerja);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = izin.NIP_PNS', 'left');
		return parent::count_all();
	}
	public function find_all_atasan($nip = "")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.ID,
				izin_verifikasi.ID AS "ID_VERIFIKASI",
				KODE_IZIN,
				NIP_PNS,NAMA,NAMA_IZIN,TAHUN,
				DARI_TANGGAL,SAMPAI_TANGGAL,
				STATUS_ATASAN,STATUS_PYBMC,
				NAMA_ATASAN,NAMA_PYBMC,
				CATATAN_ATASAN,CATATAN_PYBMC,
				LAMPIRAN_FILE,
				JUMLAH,SATUAN,
				izin.KETERANGAN,
				STATUS_PENGAJUAN,
				STATUS_VERIFIKASI
				');
		}
		$this->db->where("izin_verifikasi.NIP_ATASAN",$nip);
		$this->db->where("izin_verifikasi.STATUS_VERIFIKASI","1");
		$this->db->join('jenis_izin', 'izin.KODE_IZIN = jenis_izin.ID', 'left');
		$this->db->join('izin_verifikasi', 'izin_verifikasi.ID_PENGAJUAN = izin.ID', 'left');
		return parent::find_all();
	}
	public function count_all_atasan($nip = "")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.ID,
				KODE_IZIN,
				NIP_PNS,NAMA,NAMA_IZIN,TAHUN,
				DARI_TANGGAL,SAMPAI_TANGGAL,
				STATUS_ATASAN,STATUS_PYBMC,
				NAMA_ATASAN,NAMA_PYBMC,
				CATATAN_ATASAN,CATATAN_PYBMC,
				LAMPIRAN_FILE,
				JUMLAH,SATUAN,
				izin.KETERANGAN');
		}
		$this->db->where("izin_verifikasi.NIP_ATASAN",$nip);
		$this->db->where("izin_verifikasi.STATUS_VERIFIKASI","1");
		$this->db->join('jenis_izin', 'izin.KODE_IZIN = jenis_izin.ID', 'left');
		$this->db->join('izin_verifikasi', 'izin_verifikasi.ID_PENGAJUAN = izin.ID', 'left');
		
		return parent::count_all();
	}
	public function find_all_ppk($nip = "")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.ID,
				KODE_IZIN,
				NIP_PNS,NAMA,NAMA_IZIN,TAHUN,
				DARI_TANGGAL,SAMPAI_TANGGAL,
				STATUS_ATASAN,STATUS_PYBMC,
				NAMA_ATASAN,NAMA_PYBMC,
				CATATAN_ATASAN,CATATAN_PYBMC,
				LAMPIRAN_FILE,
				JUMLAH,SATUAN,
				izin.KETERANGAN,
				STATUS_PENGAJUAN');
		}
		$this->db->where("NIP_PYBMC",$nip);
		$this->db->join('jenis_izin', 'izin.KODE_IZIN = jenis_izin.ID', 'left');
		return parent::find_all();
	}
	public function count_all_ppk($nip = "")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.ID,
				KODE_IZIN,
				NIP_PNS,NAMA,NAMA_IZIN,TAHUN,
				DARI_TANGGAL,SAMPAI_TANGGAL,
				STATUS_ATASAN,STATUS_PYBMC,
				NAMA_ATASAN,NAMA_PYBMC,
				CATATAN_ATASAN,CATATAN_PYBMC,
				LAMPIRAN_FILE,
				JUMLAH,SATUAN,
				izin.KETERANGAN');
		}
		$this->db->where("NIP_PYBMC",$nip);
		return parent::count_all();
	}
	public function grupby_jenis($nip = '',$DARI_TANGGAL = "",$SAMPAI_TANGGAL = "")
	{
		 
		if (empty($this->selects)) {
			$this->select('KODE_IZIN,NAMA_IZIN,count(*) as jumlah');
		}
		if($DARI_TANGGAL != ""){
			$this->izin_pegawai_model->where("DARI_TANGGAL >= '{$DARI_TANGGAL}'");  
        	$this->izin_pegawai_model->where("SAMPAI_TANGGAL <= '{$SAMPAI_TANGGAL}'");  
		}
		$this->db->group_by('KODE_IZIN');
		$this->db->group_by('NAMA_IZIN');
		$this->db->where("NIP_PNS",$nip);
		$this->db->join('jenis_izin', 'izin.KODE_IZIN = jenis_izin.ID', 'left');
		return parent::find_all();
	}
	public function sum_hari_grupby_jenis($nip = '',$DARI_TANGGAL = "",$SAMPAI_TANGGAL = "")
	{
		 
		if (empty($this->selects)) {
			$this->select('KODE_IZIN,NAMA_IZIN,sum("JUMLAH") as jumlah');
		}
		$this->db->group_by('KODE_IZIN');
		$this->db->group_by('NAMA_IZIN');
		$this->db->where("NIP_PNS",$nip);
		if($DARI_TANGGAL != ""){
			$this->izin_pegawai_model->where("DARI_TANGGAL >= '{$DARI_TANGGAL}'");  
        	$this->izin_pegawai_model->where("SAMPAI_TANGGAL <= '{$SAMPAI_TANGGAL}'");  
		}
		$this->db->join('jenis_izin', 'izin.KODE_IZIN = jenis_izin.ID', 'left');
		return parent::find_all();
	}
	public function grupby_status($nip = '',$DARI_TANGGAL = "",$SAMPAI_TANGGAL = "")
	{
		 
		if (empty($this->selects)) {
			$this->select('STATUS_PENGAJUAN,count(*) as jumlah');
		}
		$this->db->group_by('STATUS_PENGAJUAN');
		if($nip != ""){
			$this->db->where("NIP_PNS",$nip);
		}
		if($DARI_TANGGAL != ""){
			$this->izin_pegawai_model->where("DARI_TANGGAL >= '{$DARI_TANGGAL}'");  
        	$this->izin_pegawai_model->where("SAMPAI_TANGGAL <= '{$SAMPAI_TANGGAL}'");  
		}
		
		return parent::find_all();
	}
	public function group_count_per_bulan($tahun = "",$nip = "",$DARI_TANGGAL = "",$SAMPAI_TANGGAL = "")
	{
		if (empty($this->selects))
		{
			$this->select("date_part('YEAR',\"DARI_TANGGAL\") as year,date_part('MONTH',\"DARI_TANGGAL\") as month,count(*) as jumlah");
		}
		if($tahun != ""){
			$this->db->where("date_part('YEAR',\"DARI_TANGGAL\")",$thang);
		}
		if($nip != ""){
			$this->db->where("NIP_PNS",$nip);
		}
		if($DARI_TANGGAL != ""){
			$this->izin_pegawai_model->where("DARI_TANGGAL >= '{$DARI_TANGGAL}'");  
        	$this->izin_pegawai_model->where("SAMPAI_TANGGAL <= '{$SAMPAI_TANGGAL}'");  
		}
		
		$this->db->group_by("date_part('YEAR',\"DARI_TANGGAL\")");
		$this->db->group_by("date_part('MONTH',\"DARI_TANGGAL\")");
		// $this->db->order_by('date_part(MONTH,"DARI_TANGGAL")',"asc");
		return parent::find_all();

	}
	public function validate_tanggal($nip = "",$dari_tanggal = "",$sampai_tanggal = "",$exclude_ids=array()){
		if(sizeof($exclude_ids)>0){
			$this->izin_pegawai_model->db->where_not_in('id',$exclude_ids);				
		}
		$this->db->where("NIP_PNS",$nip);
		$this->db->group_start();
		if($dari_tanggal != ""and $sampai_tanggal != ""){
			$this->izin_pegawai_model->where("\"DARI_TANGGAL\" between '".$dari_tanggal."' AND '".$sampai_tanggal."'",null);
			$this->izin_pegawai_model->or_where("\"SAMPAI_TANGGAL\" between '".$dari_tanggal."' AND '".$sampai_tanggal."'",null);
		}else{
			$this->izin_pegawai_model->where("\"DARI_TANGGAL\" between '".$dari_tanggal."' AND '".$dari_tanggal."'",null);
		}
		$this->db->group_end();
		return parent::count_all()>0;
	}
	public function validate_cuti_melahirkan($nip = "",$exclude_ids = ""){
		if(sizeof($exclude_ids)>0){
			$this->izin_pegawai_model->db->where_not_in('id',$exclude_ids);				
		}
		$this->db->where("STATUS_PENGAJUAN",3);
		$this->db->where("NIP_PNS",$nip);
		$this->izin_pegawai_model->where("KODE_IZIN",4);
		return parent::count_all()>=3;
	}
	public function find_group_pegawai($unit_kerja = "")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.
				NIP_PNS,
				pegawai.NAMA,
				PHOTO,
				COUNT(*) AS "jumlah"
				');
		}
		$this->db->where("UNOR_INDUK_ID",$unit_kerja);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = izin.NIP_PNS', 'left');
		$this->db->join('jenis_izin', 'izin.KODE_IZIN = jenis_izin.ID', 'left');
		$this->db->group_by("NIP_PNS");	
		$this->db->group_by("pegawai.NAMA");	
		$this->db->group_by("PHOTO");	
		return parent::find_all();
	}
	public function find_group_pegawai_atasan($unit_kerja = "",$nip)
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.
				NIP_PNS,
				pegawai.NAMA,
				PHOTO,
				COUNT(*) AS "jumlah"
				');
		}
		$this->db->where("UNOR_INDUK_ID",$unit_kerja);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = izin.NIP_PNS', 'left');
		$this->db->join('jenis_izin', 'izin.KODE_IZIN = jenis_izin.ID', 'left');
		$this->db->where("izin_verifikasi.NIP_ATASAN",$nip);
		$this->db->join('izin_verifikasi', 'izin_verifikasi.ID_PENGAJUAN = izin.ID', 'left');

		$this->db->group_by("NIP_PNS");	
		$this->db->group_by("pegawai.NAMA");	
		$this->db->group_by("PHOTO");	
		return parent::find_all();
	}
	
	public function find_group_pegawai_status($unit_kerja = "")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.
				NIP_PNS,
				STATUS_PENGAJUAN,
				pegawai.NAMA,
				PHOTO,
				COUNT(*) AS "jumlah"
				');
		}
		$this->db->where("UNOR_INDUK_ID",$unit_kerja);
		$this->db->join('pegawai', 'pegawai.NIP_BARU = izin.NIP_PNS', 'left');
		$this->db->join('jenis_izin', 'izin.KODE_IZIN = jenis_izin.ID', 'left');
		$this->db->group_by("NIP_PNS");	
		$this->db->group_by("pegawai.NAMA");	
		$this->db->group_by("PHOTO");
		$this->db->group_by("STATUS_PENGAJUAN");	
		return parent::find_all();
	}
}