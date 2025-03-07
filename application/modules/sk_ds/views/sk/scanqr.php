<!-- Content Header (Page header) -->

<script src="<?php echo base_url();?>assets/js/NovComet.js" type="text/javascript"></script>
     
    <!-- Main content -->
    <section class="content">
      <!-- Main row -->
      <div class="row">
        
        <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Scan Qrcode</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <canvas></canvas>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Hasil</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form">
              <div class="box-body">
                
                <div class="form-group">
                  <label for="">Lokasi File</label>
                  <input type="text" class="form-control" id="lokasi_file" placeholder="">
                </div>
                 
            </form>
          </div>
        </div>
 

      </div>
      <!-- /.row (main row) -->

    </section>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/admin/assets/qrcode/js/qrcodelib.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>themes/admin/assets/qrcode/js/webcodecamjquery.js"></script>

<script type="text/javascript">
  NovComet.subscribe('customAlert', function(data){
      console.log('Show Info on dashboard');
  });

</script>

<script type="text/javascript">
  // Initial JavaScript
  NovComet.run();
  
   // Function Ajax ambil no registrasi
   function ajax_get_reg(lokasi){
      $table.ajax.reload(null,true);
    }
  // Scan Qrcode
  var arg = {
      resultFunction: function(result) {
        <?php if($textbox != ""){ ?>
        	$('#<?php echo $textbox; ?>').val(result.code);
        	$('#<?php echo $textbox; ?>').val(result.code).attr('style','background-color: #f7f6b2');
        <?php }else{ ?>
          $('#textqrcode').val(result.code);
          $('#textqrcode').val(result.code).attr('style','background-color: #f7f6b2');
        <?php }?>
          $('#lokasi_file').val(result.code);
          //Otomatis jalannya Function Ajax
          <?php if($tabel != ""){ ?>
        	$<?php echo $tabel; ?>.ajax.reload(null,true);
        <?php }else{ ?>
          $table.ajax.reload(null,true);
        <?php }?>
          
          decoder.stop();
          $("#modal-global").modal("hide");
      }
  };
  // $("canvas").WebCodeCamJQuery(arg).data().plugin_WebCodeCamJQuery.play();
  var decoder = $("canvas").WebCodeCamJQuery(arg).data().plugin_WebCodeCamJQuery;
  decoder.play();
$('#modal-global').on('hidden.bs.modal', function () {
	decoder.stop();
  // do something…
})
</script>

