<?php 
    $this->load->library('convert');
 	  $convert = new convert();
    $tab_pane_pendidikan_id = "tab_panel_pendidikan";//uniqid("tab_pane_pendidikan");
    $tab_pane_diklat_struktural_id = "tab_pane_diklat_struktural";//uniqid("tab_pane_diklat_struktural");
    $tab_pane_diklat_fungsional_id = "tab_pane_diklat_fungsional";//uniqid("tab_pane_diklat_fungsional");
    $tab_pane_rwt_kursus = "tab_pane_rwt_kursus";
?>

 
<?php
$id = isset($pegawai->ID) ? $pegawai->ID : '';
$PNS_ID = isset($pegawai->PNS_ID) ? $pegawai->PNS_ID : '';

?>
<div class="tab-pane" id="<?php echo $TAB_ID;?>">
           <div class="nav-tabs-custom">
                <ul id="tab-insides-here" class="nav nav-tabs">
                     
                    <li class="active">
                        <a href="#<?php echo $tab_pane_pendidikan_id; ?>" data-toggle="tab" aria-expanded="false"> Pendidikan Formal </a>
                    </li>
                     <li class="">
                        <a href="#<?php echo $tab_pane_diklat_struktural_id;?>" data-toggle="tab" aria-expanded="false"> Diklat Struktural </a>
                    </li>
                    <li class="">
                        <a href="#<?php echo $tab_pane_diklat_fungsional_id;?>" data-toggle="tab" aria-expanded="false"> Diklat Fungsional </a>
                    </li>
                    <li class="">
                        <a href="#<?php echo $tab_pane_rwt_kursus;?>" data-toggle="tab" aria-expanded="false"> Kursus </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <?php 
                        $this->load->view('kepegawaian/tab_pane_riwayat_pendidikan',array('TAB_ID'=>$tab_pane_pendidikan_id));
                        $this->load->view('kepegawaian/tab_pane_riwayat_diklat_struktural',array('TAB_ID'=>$tab_pane_diklat_struktural_id));
                        $this->load->view('kepegawaian/tab_pane_riwayat_diklat_fungsional',array('TAB_ID'=>$tab_pane_diklat_fungsional_id));
                        $this->load->view('kepegawaian/tab_pane_rwt_kursus',array('TAB_ID'=>$tab_pane_rwt_kursus));
                    ?>
                </div>
            </div>
</div>