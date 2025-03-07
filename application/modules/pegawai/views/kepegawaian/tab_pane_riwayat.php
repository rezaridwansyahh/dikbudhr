
<?php 
	$this->load->library('convert');
 	$convert = new convert();
    $tab_pane_kepangkatan_id = "tab_pane_kepangkatan";
    $tab_pane_pindah_unit_kerja_id = "tab_pane_pindah_unit_kerja";
    $tab_pane_rwt_jabatan = "tab_pane_rwt_jabatan";
    $tab_pane_rwt_hukdis = "tab_pane_rwt_hukdis";
    $tab_pane_rwt_pekerjaan = "tab_pane_rwt_pekerjaan";
    $tab_pane_rwt_kgb = "tab_pane_rwt_kgb";
    $tab_pane_rwt_tb = "tab_pane_rwt_tb";
    $tab_pane_rwt_pns = "tab_pane_rwt_pns";
?>
<?php
$id = isset($pegawai->ID) ? $pegawai->ID : '';
$PNS_ID = isset($pegawai->PNS_ID) ? $pegawai->PNS_ID : '';
$NIP_BARU = isset($pegawai->NIP_BARU) ? $pegawai->NIP_BARU : '';
?>
<div class="tab-pane" id="<?php echo $TAB_ID;?>">
    <div class="nav-tabs-custom">
        <ul id="tab-insides-riwayat" class="nav nav-tabs">
            <?php if($this->auth->has_permission("RiwayatKepangkatan.Kepegawaian.View")){ ?>
            <li class="active">
                <a href="#<?php echo $tab_pane_kepangkatan_id;?>" data-toggle="tab" aria-expanded="false"> Kepangkatan </a>
            </li>
            <?php } ?>
            <?php if($this->auth->has_permission("RiwayatPindahUnitKerja.Kepegawaian.View")){ ?>
            <li class="">
                <a href="#<?php echo $tab_pane_pindah_unit_kerja_id;?>" data-toggle="tab" aria-expanded="false"> Unit Kerja </a>
            </li>
            <?php } ?>
            <?php if($this->auth->has_permission("riwayatjabatan.Kepegawaian.View")){ ?>
            <li class="">
                <a href="#<?php echo $tab_pane_rwt_jabatan;?>" data-toggle="tab" aria-expanded="false"> Jabatan </a>
            </li>
            <?php } ?>
            <?php if($this->auth->has_permission("riwayatpekerjaan.Kepegawaian.View")){ ?>
            <li class="">
                <a href="#<?php echo $tab_pane_rwt_pekerjaan;?>" data-toggle="tab" aria-expanded="false"> Pekerjaan </a>
            </li>
            <?php } ?>
            <?php if($this->auth->has_permission("Riwayathukdis.Kepegawaian.View")){ ?>
            <li class="">
                <a href="#<?php echo $tab_pane_rwt_hukdis;?>" data-toggle="tab" aria-expanded="false"> Hukuman Disiplin </a>
            </li>
            <?php } ?>
            <?php if($this->auth->has_permission("riwayatkgb.Kepegawaian.View")){ ?>
            <li class="">
                <a href="#<?php echo $tab_pane_rwt_kgb;?>" data-toggle="tab" aria-expanded="false"> Kenaikan Gaji Berkala </a>
            </li>
            <?php } ?>
            <?php if($this->auth->has_permission("Riwayattb.Kepegawaian.View")){ ?>
            <li class="">
                <a href="#<?php echo $tab_pane_rwt_tb;?>" data-toggle="tab" aria-expanded="false"> Tugas Belajar </a>
            </li>
            <?php } ?>
            <?php if($this->auth->has_permission("Riwayatpns.Kepegawaian.View")){ ?>
            <li class="">
                <a href="#<?php echo $tab_pane_rwt_pns;?>" data-toggle="tab" aria-expanded="false"> CPNS/PNS </a>
            </li>
            <?php } ?>
        </ul>
        <div class="tab-content">
            <?php 
                if($this->auth->has_permission("RiwayatKepangkatan.Kepegawaian.View")){
                    $this->load->view('kepegawaian/tab_pane_riwayat_kepangkatan',array('TAB_ID'=>$tab_pane_kepangkatan_id));
                }
                if($this->auth->has_permission("RiwayatPindahUnitKerja.Kepegawaian.View")){
                    $this->load->view('kepegawaian/tab_pane_riwayat_pindah_unit_kerja',array('TAB_ID'=>$tab_pane_pindah_unit_kerja_id));
                }
                if($this->auth->has_permission("riwayatjabatan.Kepegawaian.View")){
                    $this->load->view('kepegawaian/tab_pane_riwayat_jabatan',array('TAB_ID'=>$tab_pane_rwt_jabatan));
                }
                if($this->auth->has_permission("riwayatpekerjaan.Kepegawaian.View")){
                    $this->load->view('kepegawaian/tab_pane_riwayat_pekerjaan',array('TAB_ID'=>$tab_pane_rwt_pekerjaan));
                }
                if($this->auth->has_permission("riwayatkgb.Kepegawaian.View")){
                    $this->load->view('kepegawaian/tab_pane_riwayat_kgb',array('TAB_ID'=>$tab_pane_rwt_kgb));
                }
                if($this->auth->has_permission("Riwayathukdis.Kepegawaian.View")){
                    $this->load->view('kepegawaian/tab_pane_huk_dis',array('TAB_ID'=>$tab_pane_rwt_hukdis));
                }
                if($this->auth->has_permission("Riwayattb.Kepegawaian.View")){
                    $this->load->view('kepegawaian/tab_pane_tugasbelajar',array('TAB_ID'=>$tab_pane_rwt_tb,'NIP_BARU'=>$NIP_BARU));
                }
                if($this->auth->has_permission("Riwayatpns.Kepegawaian.View")){
                    $this->load->view('kepegawaian/tab_pane_pnscpns',array('TAB_ID'=>$tab_pane_rwt_pns,'NIP_BARU'=>$NIP_BARU));
                }
            ?>
            
        </div>
    </div>
</div>
