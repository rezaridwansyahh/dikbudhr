<div class="nav-tabs-custom">
        <ul id="tab-insides-here" class="nav nav-tabs">
            <li class="active">
                <a href="#tabpengajuan" data-toggle="tab" aria-expanded="true"> Pengajuan </a>
            </li>
            <li class="">
                <a href="#tabharilibur" data-toggle="tab" aria-expanded="false"> Hari Libur </a>
            </li> 
            <li class="">
                <a href="#tabalur" data-toggle="tab" aria-expanded="false"> Alur </a>
            </li> 

        </ul>
        <div class="tab-content">
            <?php 
                                                   
                $this->load->view('presensi/ajukanpresensi',array('TAB_ID'=>"tabpengajuan","record_hari_libur_tahunan"=>$record_hari_libur_tahunan));
                $this->load->view('presensi/harilibur',array('TAB_ID'=>"tabharilibur"));
                $this->load->view('presensi/alur',array('TAB_ID'=>"tabalur"));
            ?>
        </div>
    </div>
