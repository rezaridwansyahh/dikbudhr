<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Riwayatkinerja extends Admin_Controller
{
    protected $permissionCreate = 'RiwayatKinerja.Kepegawaian.Create';
    protected $permissionDelete = 'RiwayatKinerja.Kepegawaian.Delete';
    protected $permissionEdit   = 'RiwayatKinerja.Kepegawaian.Edit';
    protected $permissionView   = 'RiwayatKinerja.Kepegawaian.View';
    protected $permissionSync   = 'RiwayatKinerja.Kepegawaian.Sinkron';
    protected $permissionUpload   = 'RiwayatKinerja.Kepegawaian.Upload';
    protected $permissionUpdateMandiri   = 'RiwayatKinerja.Kepegawaian.UpdateMandiri';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/riwayat_kinerja_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/update_mandiri_model');
        $this->load->helper('aplikasi');
    }

    public function ajax_list()
    {

        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        $PNS_ID = $this->input->post('PNS_ID');
        if (empty($PNS_ID)) {
            echo "die";
            die();
        }
        $this->pegawai_model->where("PNS_ID", $PNS_ID);
        $pegawai_data = $this->pegawai_model->find_first_row();
        $NIP_BARU = $pegawai_data->NIP_BARU;

        $length = $this->input->post('length');
        $start = $this->input->post('start');

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $this->riwayat_kinerja_model->where("nip", $NIP_BARU);
        $total = $this->riwayat_kinerja_model->count_all();;
        $output = array();
        $output['draw'] = $draw;


        $output['recordsTotal'] = $output['recordsFiltered'] = $total;
        $output['data'] = array();

        if ($search != "") {
            // $this->riwayat_kinerja_model->where('upper("NAMA_UNOR_BARU") LIKE \''.strtoupper($search).'%\'');
        }
        $this->riwayat_kinerja_model->limit($length, $start);

        $kolom = $iSortCol != "" ? $iSortCol : "nama";
        $sSortCol == "asc" ? "asc" : "desc";
        $this->riwayat_kinerja_model->order_by($iSortCol, $sSortCol);
        $this->riwayat_kinerja_model->order_by("tahun", "ASC");
        $this->riwayat_kinerja_model->where("nip", $NIP_BARU);

        $records = $this->riwayat_kinerja_model->find_all();
        if ($search != "") {
            $jum    = $this->riwayat_kinerja_model->count_all();
            $output['recordsTotal'] = $output['recordsFiltered'] = $jum;
        }

        $nomor_urut = $start + 1;
        if (isset($records) && is_array($records) && count($records)) :
            foreach ($records as $record) {
                $row = array();
                $row[]  = $nomor_urut;
                $row[]  = $record->tahun;
                $row[]  = $record->rating_hasil_kerja;
                $row[]  = $record->rating_perilaku_kerja;
                $row[]  = $record->predikat_kinerja;

                $btn_actions = array();
                if ($record->id_arsip != "") {
                    $btn_actions[] = "<a href='" . base_url() . "admin/arsip/arsip_digital/viewdoc2/" . $record->id_arsip . "' data-toggle='tooltip' title='Lihat Dokumen' tooltip='Lihat Dokumen' class='btn btn-sm btn-info modal-custom-global'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                }
                $btn_actions[] = "<a href='" . base_url() . "pegawai/riwayatkinerja/show/" . $record->ref . "' data-toggle='tooltip' title='Detail data' tooltip='Detail data' class='btn btn-sm btn-default show-modal-custom'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                if ($this->auth->has_permission($this->permissionUpdateMandiri)) {
                    $btn_actions[] = "<a href='" . base_url() . "pegawai/riwayatkinerja/editmandiri/" . $record->ref . "' data-toggle='tooltip' title='Ubah data mandiri' tooltip='Ubah data' class='btn btn-sm btn-warning show-modal-custom'><i class='fa fa-pencil'></i> </a>";
                }
                if ($this->auth->has_permission($this->permissionEdit)) {
                    $btn_actions[] = "<a href='" . base_url() . "pegawai/riwayatkinerja/edit/" . $record->ref . "' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success show-modal-custom'><i class='fa fa-pencil'></i> </a>";
                }

                // if ($this->auth->has_permission($this->permissionDelete)) {
                //     $btn_actions[] = "<a href='#' kode='$record->id' data-toggle='tooltip' title='Hapus Data' class='btn btn-sm btn-danger btn-hapus'><i class='fa fa-trash-o'></i> </a>";
                // }
                $row[] = "<div class='btn-group'>" . implode(" ", $btn_actions) . "</div>";
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        die();
    }
    public function show($ref, $view = true, $mandiri = 0, $NIP_BARU = null)
    {
        $detailRiwayat = null;
        if ($ref) {
            $detailRiwayat = $this->riwayat_kinerja_model->find($ref);
            $NIP_BARU = $detailRiwayat->nip;
        }
        Template::set('mandiri', $mandiri);
        Template::set('detail_riwayat', $detailRiwayat);
        Template::set('NIP_BARU', $NIP_BARU);
        Template::set('toolbar_title', "Detail Kinerja");
        if ($view)
            Template::set_view("kepegawaian/riwayat_kinerja_detil");
        else
            Template::set_view("kepegawaian/riwayat_kinerja_crud");


        Template::render();
    }
    public function edit($ref)
    {
        $this->show($ref, false);
    }

    public function editmandiri($ref)
    {
        $this->show($ref, false, 1);
    }
    public function save()
    {
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
        
        // Validate the data
        $this->form_validation->set_rules($this->riwayat_kinerja_model->get_validation_rules());
        $response = array(
            'success' => false,
            'msg' => 'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = "
            <div class='alert alert-block alert-error fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                    Error validation
                </h4>
                " . validation_errors() . "
            </div>
            ";
            echo json_encode($response);
            exit();
        }
        $data = $this->riwayat_kinerja_model->prep_data($this->input->post());
        $mandiri = $this->input->post("mandiri");
        if (!$mandiri)
            $this->auth->restrict($this->permissionCreate);
        $id_update_mandiri = $this->input->post("id_update_mandiri");
        // Make sure we only pass in the fields we want
        $file_ext   = "";
        $file_size  = "";
        $file_tmp   = "";
        $pegawai = $this->pegawai_model->find_by('NIP_BARU', $this->input->post('nip'));     
        $data['nip'] = $pegawai->NIP_BARU;
        if (isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['name']) {
            $errors = array();
            $allowed_ext = array('pdf');
            $file_name = $_FILES['file_dokumen']['name'];
            $file_ext   = explode('.', $file_name);
            $file_size  = $_FILES['file_dokumen']['size'];
            $file_tmp   = $_FILES['file_dokumen']['tmp_name'];

            $content    = file_get_contents($file_tmp);
            $keterangan = "Realisasi Kinerja Tahun " . $data['tahun'];
            $this->load->library('Api_s3_aws');
            $aws = new Api_s3_aws;
            $id = $aws->insertArsip($pegawai->NIP_BARU, $data['tahun'], 85, $keterangan, $content, $file_name, !$mandiri);
            if ($id) {
                $data['id_arsip'] = $id;
            } else {
                if (empty($data['id_arsip']))
                    unset($data['id_arsip']);
            }

            if (in_array(strtolower(end($file_ext)), $allowed_ext) === false) {
                $errors[] = 'Extension not allowed';
                $response['msg'] = "
                <div class='alert alert-block alert-error fade in note note-danger'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Ada kesalahan
                    </h4>
                    <p>Extension not allowed</p>
                </div>
                ";
                echo json_encode($response);
                exit();
            }
            if ($file_size > 50097152) {
                $errors[] = 'File size must be under 50mb';
                $response['msg'] = "
                <div class='alert alert-block alert-error fade in note note-danger'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Ada kesalahan
                    </h4>
                    <p>File size must be under 50Mb</p>
                </div>
                ";
                echo json_encode($response);
                exit();
            }
        } else {
            if ($mandiri && empty($data['id_arsip'])) {
                $response['success'] = false;
                $response['msg'] = "<div class='alert alert-block alert-error fade in note note-danger'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                Harap lampirkan bukti dukung
                </h4>
                </div>";
                echo json_encode($response);
                die;
            }
        }
        $ref = $this->input->post("ref");
        if (empty($ref)) {
            foreach ($data as $key => $value) {
                if (empty($data[$key]))
                    unset($data[$key]);
            }
        } else {
            foreach ($data as $key => $value) {
                if ($data[$key] == "")
                    $data[$key] = null;
            }
        }
        if (!$mandiri) {
            if (isset($ref) && !empty($ref)) {
                $this->auth->restrict($this->permissionEdit);
                $current = $this->riwayat_kinerja_model->find($ref);
                $perubahan = arrDiff($current, $data);
                $this->riwayat_kinerja_model->update($ref, $data);
                log_activity($this->auth->user_id(), 'Update data Riwayat kinerja: ' . $pegawai->NIP_BARU . ' perubahan : ' . json_encode($perubahan) . ' ' . $this->input->ip_address(), 'pegawai');
            } else {
                $id = $this->riwayat_kinerja_model->insert($data);
                log_activity($this->auth->user_id(), 'Insert data Riwayat kinerja: ' . $pegawai->NIP_BARU . ' data : ' . json_encode($data) . '  ' . $this->input->ip_address(), 'pegawai');
            }
        } else {
            if (isset($ref) && !empty($ref)) {
                $current = $this->riwayat_kinerja_model->find($ref);
                $perubahan = arrDiff($current, $data, true);
                if ($perubahan[1] != '[]') {
                    $data_update = array();
                    $data_update['PNS_ID']      = $pegawai->PNS_ID;
                    $data_update['KOLOM']       = "RIWAYAT_KINERJA";
                    $data_update['DARI']        = $perubahan[0];
                    $data_update['PERUBAHAN']   = $perubahan[1];
                    $data_update['NAMA_KOLOM']  = "RIWAYAT_KINERJA";
                    $data_update['LEVEL_UPDATE'] = 3;
                    $data_update['ID_TABEL']    = $current->id;
                    $data_update['ID_ARSIP']    = $data['id_arsip'];
                    $data_update['UPDATE_TGL']  = date("Y-m-d");
                    $data_update['UPDATED_BY']  = $this->auth->user_id();
                    $id_update = $this->update_mandiri_model->insert($data_update);
                }
            } else {
                $data_update = array();
                $data_update['PNS_ID']      = $pegawai->PNS_ID;
                $data_update['KOLOM']       = "RIWAYAT_KINERJA";
                $data_update['PERUBAHAN']   = json_encode($data);
                $data_update['NAMA_KOLOM']  = "RIWAYAT_KINERJA";
                $data_update['LEVEL_UPDATE'] = 3;
                $data_update['ID_ARSIP']    = $data['id_arsip'];
                $data_update['UPDATE_TGL']  = date("Y-m-d");
                $data_update['UPDATED_BY']  = $this->auth->user_id();
                $id_update = $this->update_mandiri_model->insert($data_update);
            }
        }
        if ($id_update_mandiri) {
            $data_update = array();
            $data_update['VERIFIKASI_BY']    = $this->auth->user_id();
            $data_update['VERIFIKASI_TGL']    = date("Y-m-d");
            $data_update['STATUS']            = 1;
            $this->update_mandiri_model->update($id_update_mandiri, $data_update);
            log_activity($this->auth->user_id(), 'Verifikasi update mandiri id : ' . $id_update_mandiri . ' STATUS : 1 ' . $this->input->ip_address(), 'pegawai');

            if ($data['id_arsip']) {
                $this->load->model('arsip_digital/arsip_digital_model');
                $this->arsip_digital_model->update($data['id_arsip'], array('ISVALID' => 1));
            }
        }
        $response['success'] = true;
        $response['msg'] = "Transaksi berhasil";
        echo json_encode($response);
    }
    public function verifikasi($ID, $read_only = false)
    {
        $data = $this->update_mandiri_model->find($ID);
        $perubahan = json_decode($data->PERUBAHAN);
        $PNS_ID = $data->PNS_ID;
        if (!$read_only)
            $read_only = $data->STATUS != '' &&
                ($this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate')
                    || $this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate3'));
        Template::set('read_only', $read_only);
        Template::set('id_update_mandiri', $ID);

        // cek orang lain lihat arsip orang
        if ($read_only && !($this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate')
            || $this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate3')) && $this->auth->username() != $data->NIP_BARU) {
            die();
        }
        if (empty($data->DARI)) {
            if (!$read_only)
                $this->auth->restrict($this->permissionCreate);
            Template::set_view("kepegawaian/riwayat_kinerja_crud");
            Template::set('detail_riwayat', $perubahan);
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Riwayat Kinerja");
            Template::render();
        } else {
            $detail_riwayat = $this->riwayat_kinerja_model->find($data->ID_TABEL);
            if (!$detail_riwayat)
                Template::set_message('Data Tidak Ditemukan/Data Telah Dihapus', 'error');
            if (!$read_only)
                $this->auth->restrict($this->permissionEdit);
            Template::set_view("kepegawaian/riwayat_kinerja_crud");
            $detail_riwayat = array_replace((array) $detail_riwayat, (array) $perubahan);
            $detail_riwayat = (object) $detail_riwayat;
            Template::set('detail_riwayat', $detail_riwayat);
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Riwayat Kinerja");

            Template::render();
        }
    }
    public function delete($record_id)
    {
        $this->auth->restrict($this->permissionDelete);
        if ($this->riwayat_kinerja_model->delete($record_id)) {
            log_activity($this->auth->user_id(), 'delete data Riwayat Kinerja : ' . $record_id . ' : ' . $this->input->ip_address(), 'pegawai');
            Template::set_message("Sukses Hapus data", 'success');
            echo "Sukses";
        } else {
            echo "Gagal";
        }

        exit();
    }
    public function index($NIP_BARU = '1552260645')
    {
        Template::set_view("kepegawaian/tab_pane_riwayat_kinerja");
        Template::set('NIP_BARU', $NIP_BARU);
        Template::set('TAB_ID', uniqid("TAB_CONTOH"));
        Template::render();
    }
}
