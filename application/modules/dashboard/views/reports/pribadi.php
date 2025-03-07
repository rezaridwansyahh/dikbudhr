<?php
	$this->load->library('convert');
	$convert = new convert();
	$mainmenu = $this->uri->segment(2);
	$menu = $this->uri->segment(3);
	$submenu = $this->uri->segment(4);

  
?>

<style>



/*Form Wizard*/
.bs-wizard {border-bottom: solid 1px #e0e0e0; padding: 0 0 10px 0;}
 .bs-wizard-step {padding: 0; position: relative;}
 .bs-wizard-step + .bs-wizard-step {}
 .bs-wizard-step .bs-wizard-stepnum {color: #595959; font-size: 16px; margin-bottom: 5px;}
 .bs-wizard-step .bs-wizard-info {color: #000; font-size: 14px;}
 .bs-wizard-step > .bs-wizard-dot {position: absolute; width: 30px; height: 30px; display: block; background: #00C0EF; top: 45px; left: 50%; margin-top: -15px; margin-left: -15px; border-radius: 50%;} 
 .bs-wizard-step > .bs-wizard-dot:after {content: ' '; width: 14px; height: 14px; background: #0099ff; border-radius: 50px; position: absolute; top: 8px; left: 8px; } 
 .bs-wizard-step > .progress {position: relative; border-radius: 0px; height: 8px; box-shadow: none; margin: 20px 0;}
 .bs-wizard-step > .progress > .progress-bar {width:0px; box-shadow: none; background: #00C0EF;}
 .bs-wizard-step.complete > .progress > .progress-bar {width:100%;}
 .bs-wizard-step.active > .progress > .progress-bar {width:50%;}
 .bs-wizard-step:first-child.active > .progress > .progress-bar {width:0%;}
 .bs-wizard-step:last-child.active > .progress > .progress-bar {width: 100%;}
 .bs-wizard-step.disabled > .bs-wizard-dot {background-color: #f5f5f5;}
 .bs-wizard-step.disabled > .bs-wizard-dot:after {opacity: 0;}
 .bs-wizard-step:first-child  > .progress {left: 50%; width: 50%;}
 .bs-wizard-step:last-child  > .progress {width: 50%;}
 .bs-wizard-step.disabled a.bs-wizard-dot{ pointer-events: none; }
</style>

<div class="container">
  <?php
    
    $counter = 0;
    $available = false;

    if(isset($sk[0]->nomor_sk)){
      $available = true;
    }

    if($available){
      $additional = $value->tindakan == "MASIH DALAM PROSES" || $value->tindakan =="SK TELAH DITANDATANGANI" || $value->tindakan=="SK TELAH DIKIRIM KE PEMILIK SK" ? "" : $value->tindakan;
      echo "<h3><b>Progres SK Berjalan</b></h3>";
      foreach ($sk as $key => $value) {  
        $counter++;
  ?>


  <div class="row bs-wizard" style="border-bottom:0;">
    
    <div class="box">
      <div class="box-header">
        <h4>Progres <?=$value->kategori?>, dengan Nomor <?=$value->nomor_sk?></h4>
        <p><b><span>TMT: <?=$value->tmt_sk?> / TGL SK: <?=$value->tgl_sk?> </span></b></p>
      </div>
      

      <div class="box-body">
        <div class="col-xs-12">
          

          <div class="col-xs-4 bs-wizard-step <?=$value->tindakan == "MASIH DALAM PROSES" || $value->tindakan =="SK TELAH DITANDATANGANI" || $value->tindakan=="SK TELAH DIKIRIM KE PEMILIK SK" ? 'complete' : 'disabled'?>">
            <div class="text-center bs-wizard-stepnum">Dalam Proses Koreksi</div>
            <div class="progress">
              <div class="progress-bar"></div>
            </div>
            <a href="#" class="bs-wizard-dot"></a>
            <!-- <div class="bs-wizard-info text-center">Lorem ipsum dolor sit amet.</div> -->
          </div>

          <div class="col-xs-4 bs-wizard-step <?=$value->tindakan =="SK TELAH DITANDATANGANI" || $value->tindakan=="SK TELAH DIKIRIM KE PEMILIK SK"  ?  'complete' : 'disabled'?>">
            <div class="text-center bs-wizard-stepnum">Tanda Tangan</div>
            <div class="progress">
              <div class="progress-bar"></div>
            </div>
            <a href="#" class="bs-wizard-dot"></a>
            <!-- <div class="bs-wizard-info text-center">Lorem ipsum dolor sit amet.</div> -->
          </div>

          <div class="col-xs-4 bs-wizard-step <?=$value->tindakan=="SK TELAH DIKIRIM KE PEMILIK SK" ? 'complete' : 'disabled'?>">
            <div class="text-center bs-wizard-stepnum">SK Dikirim Ke Pemilik</div>
            <div class="progress">
              <div class="progress-bar"></div>
            </div>
            <a href="#" class="bs-wizard-dot"></a>
            <!-- <div class="bs-wizard-info text-center">Lorem ipsum dolor sit amet.

            </div> -->
          </div>

        </div>
        <?php if ($tindakan!=""){ echo "<h4><b>Informasi Tambahan $tindakan</b></h4>";}?>
      </div>

    </div>

    

  </div>
  <?php  
      
    }
  }
  ?>

  <?php
    if($sk==false || $counter==0){
      echo '<div class="callout callout-danger">
      <h4>Tidak ada SK yang sedang diproses</h4>
    </div>';
    }
  ?>

  <button class="btn btn-primary" data-toggle="modal" data-target="#modal-all-sk">Lihat Semua SK Pribadi</button>

  <div class="modal fade" id="modal-all-sk">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Daftar Semua SK</h4>
        </div>
        <div class="modal-body">
          <table class="table table-bordered">
            <tr>
              <th>Nomor SK</th>
              <th>Tanggal SK</th>
              <th>Kategori</th>
              <th>Status Tanda Tangan</th>
            </tr>

            <?php
              foreach ($sk as $key => $value) { 
            ?>
              <tr>
                <td><?=$value->nomor_sk?></td>
                <td><?=$value->tgl_sk?></td>
                <td><?=$value->kategori?></td>
                <td><?=$value->tindakan =="SK TELAH DITANDATANGANI" || $value->tindakan=="SK TELAH DIKIRIM KE PEMILIK SK"  ?  'Sudah Tanda Tangan' : 'Belum Tanda Tangan' ?></td>
              </tr>
            <?php  
                } 
            ?>
            
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          
        </div>
      </div>

    </div>

  </div>

  <br><br>

 
  
  <div class="admin-box box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">Permintaan perubahan data anda</h3>
    </div>
    
    <div class="box-body">
      <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
        <thead>
          <tr>
            <th style="width:10px">No</th>
            <th>Perubahan</th>
            <th width="70px">Status</th>
            <th width="70px" align="center">#</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
  
  <div class="row">
    <h4><b>Layanan Lainnya</b></h4>
    <div class="col-sm-6 col-md-4">
      <div class="thumbnail">
        
        <div class="caption">
          <h3>Layanan Kehadiran</h3>
          <p>Layanan ini menyediakan laporan rekaptulasi kehadiran, izin dan layanan kehadiran lain yang relevan</p>
          <p>
            <a href="http://data-sdm.kemdikbud.go.id/admin/izin/izin_pegawai/laporanpegawai" class="btn btn-primary" role="button">
              Pergi ke menu
            </a>
          </p>
        </div>
      </div>      
    </div>

    <div class="col-sm-6 col-md-4">
      <div class="thumbnail">
        <div class="caption">
          <h3>Layanan SK Elektronik</h3>
          <p>Layanan ini menyediakan berbagai SK pribadi, yang ditandatangani secara elektronik</p>
          <p>
            <a class="btn btn-primary" href="http://data-sdm.kemdikbud.go.id/admin/arsip/sk_ds/viewallpegawai"
              target="_blank">Pergi Ke Menu</a>
          </p>
        </div>
      </div>
    </div>

    
  </div>

</div>









<script type="text/javascript">
$table = $(".table-data").DataTable({
	
	dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
	"<'row'<'col-sm-12'tr>>" +
	"<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
	processing: true,
	serverSide: true,
	"columnDefs": [
					{"className": "text-center", "targets": [2,3]},
					{ "targets": [0,1], "orderable": false }
				],
	ajax: {
	  url: "<?php echo base_url() ?>admin/reports/dashboard/getdataupdatemandiri_pribadi",
	  type:'POST',
	  "data": function ( d ) {
			d.search['advanced_search_filters']=  $("#form_search_pegawai").serializeArray();
		}
	}
});

$(document).ready(function(){
  
  
});

</script>

