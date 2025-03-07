<div class="nav-tabs-custom">
        <ul id="tab-insides-here" class="nav nav-tabs">
            <li class="active">
                <a href="#belumttd" data-toggle="tab" aria-expanded="true"> Belum Koreksi </a>
            </li>
            <li class="">
                <a href="#sudahttd" data-toggle="tab" aria-expanded="false"> Sudah Koreksi </a>
            </li> 
            <li class="">
                <a href="#antriankoreksi" data-toggle="tab" aria-expanded="false"> Antrian Koreksi </a>
            </li> 
        </ul>
        <div class="tab-content">
            <?php 
                                                   
                $this->load->view('sk/belumvalidasi',array('TAB_ID'=>"belumttd"));
                $this->load->view('sk/sudahvalidasi',array('TAB_ID'=>"sudahttd"));
                $this->load->view('sk/antriankoreksi',array('TAB_ID'=>"antriankoreksi"));
            ?>
        </div>
    </div>
