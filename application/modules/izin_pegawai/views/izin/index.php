<div class="nav-tabs-custom">
        <ul id="tab-insides-here" class="nav nav-tabs">
            
            <li class="active">
                <a href="#pribadi" data-toggle="tab" aria-expanded="true"> Pribadi </a>
            </li> 
            <li>
                <a href="#lainnya" data-toggle="tab" aria-expanded="false"> Pegawai Lain </a>
            </li>
        </ul>
        <div class="tab-content">
            <?php 
                $this->load->view('izin/pencarian_pribadi',array('TAB_ID'=>"pribadi"));                                   
                $this->load->view('izin/pencarian_pegawai',array('TAB_ID'=>"lainnya"));
                
            ?>
        </div>
    </div>
