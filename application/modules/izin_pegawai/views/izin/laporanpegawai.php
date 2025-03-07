<div class="nav-tabs-custom">
        <ul id="tab-insides-here" class="nav nav-tabs">
            <li class="active">
                <a href="#rekap" data-toggle="tab" aria-expanded="false"> Rekapitulasi </a>
            </li>
            <li>
                <a href="#grafik" data-toggle="tab" aria-expanded="true"> Grafik </a>
            </li> 
            <li>
                <a href="#matrik" data-toggle="tab" aria-expanded="true"> Matriks Satker</a>
            </li> 
            <?php if ($this->auth->has_permission('Izin_pegawai.Izin.LaporanPegawai') or $is_pejabat_cuti) : ?>
            <li>
                <a href="#matrikatasan" data-toggle="tab" aria-expanded="true"> Matriks Atasan</a>
            </li> 
        <?php endif; ?>
        </ul>
        <div class="tab-content">
            <?php 
                $this->load->view('izin/tab_grafik',array('TAB_ID'=>"grafik",'status_izin'=>$status_izin,'data_jenisizin'=>$data_jenisizin,'data_jumlah_hari'=>$data_jumlah_hari));                                   
                $this->load->view('izin/tab_rekap',array('TAB_ID'=>"rekap"));
                $this->load->view('izin/tab_matriks',array('TAB_ID'=>"matrik"));
                if ($this->auth->has_permission('Izin_pegawai.Izin.LaporanPegawai') or $is_pejabat_cuti) :
                    $this->load->view('izin/tab_matriks_atasan',array('TAB_ID'=>"matrikatasan"));
                endif;
            ?>
        </div>
    </div>
