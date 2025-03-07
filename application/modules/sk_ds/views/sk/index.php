<div class="nav-tabs-custom">
        <ul id="tab-insides-here" class="nav nav-tabs">
            <li class="active">
                <a href="#belumttd" data-toggle="tab" aria-expanded="true"> Belum Tanda Tangan </a>
            </li>
            <li class="">
                <a href="#sudahttd" data-toggle="tab" aria-expanded="false"> Sudah Tanda Tangan </a>
            </li> 
            <li class="">
                <a href="#antrianttd" data-toggle="tab" aria-expanded="false"> Antrian Tanda Tangan </a>
            </li> 
            
            <?php if ($this->auth->has_permission('Sk_ds.DupakPtp.Tandatangan')){ ?>
            <li class="">
                <a href="#pakptp" data-toggle="tab" aria-expanded="false"> PAK PTP </a>
            </li> 
            <?php } ?>
        </ul>
        <div class="tab-content">
            <?php 
                                                   
                $this->load->view('sk/belumttd',array('TAB_ID'=>"belumttd"));
                $this->load->view('sk/sudahttd',array('TAB_ID'=>"sudahttd"));
                $this->load->view('sk/antrianttd',array('TAB_ID'=>"antrianttd"));
                if ($this->auth->has_permission('Sk_ds.DupakPtp.Tandatangan')){ 
                    $this->load->view('sk/pakptp',array('TAB_ID'=>"pakptp"));
                }
            ?>
        </div>
    </div>
