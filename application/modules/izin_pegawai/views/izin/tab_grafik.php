<div class="tab-pane" id="<?php echo $TAB_ID;?>">
<script src="<?php echo base_url(); ?>assets/js/amcharts/amcharts.js" type="text/javascript" ></script>
<script src="<?php echo base_url(); ?>assets/js/amcharts/serial.js" type="text/javascript" ></script>  
<script src="<?php echo base_url(); ?>assets/js/amcharts/pie.js" type="text/javascript" ></script>  
<script type="text/javascript" src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/highchart/highcharts.js"></script>
<?php
    $this->load->library('Convert');
    $convert = new Convert;
?>

<div class="tab-pane" id="<?php echo $TAB_ID;?>">
  <div class="admin-box box box-primary expanded-box">
  <div class="box-header">
               
  </div>
  <div class="messages"></div>
  <div class="box-body">
    <?php echo form_open($this->uri->uri_string(),"id=form_grafik","form"); ?>
      <style>
        table.filter_pegawai tr td {
          padding-top: 2px;
        }
      </style>
      <div class="control-group col-sm-4">
          <label for="inputNAMA" class="control-label">MULAI TANGGAL</label>
          <div class="input-group date">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
              <input type='text' class="form-control pull-right datepicker" name='DARI_TANGGAL' value="<?php echo set_value('DARI_TANGGAL', isset($izin_pegawai->DARI_TANGGAL) ? $izin_pegawai->DARI_TANGGAL : ''); ?>" />
              <span class='help-inline'><?php echo form_error('DARI_TANGGAL'); ?></span>
          </div>
      </div> 
      <div class="control-group col-sm-4">
          <label for="inputNAMA" class="control-label">SAMPAI DENGAN</label>
          <div class="input-group date">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
              <input type='text' class="form-control pull-right datepicker" name='SAMPAI_TANGGAL' value="<?php echo set_value('SAMPAI_TANGGAL', isset($izin_pegawai->SAMPAI_TANGGAL) ? $izin_pegawai->SAMPAI_TANGGAL : ''); ?>" />
              <span class='help-inline'><?php echo form_error('SAMPAI_TANGGAL'); ?></span>
          </div>
      </div> 
      <div class="control-group col-sm-4">
          <label for="inputNAMA" class="control-label">&nbsp;</label>
          <div class="input-group date">
            <button type="submit" class="btn btn-success pull-right "><i class="fa fa-search"></i> Cari</button>
          </div>
      </div> 
       
    <?php
    echo form_close();    
    ?>
  </div>
</div>
  <div class="contengrafik">
      <div class="row">
          

          <div class="col-md-6">
               <div class="box box-warning">
                      <div class="box-header with-border">
                        <h3 class="box-title">Persentase Jumlah Hari</h3>
                      <!-- /.box-header -->
                      <div class="box-body">
                        <div class="row">
                          <div class="col-md-12">
                            <div class="chart-responsive">
                              <div id="divjumlahhari" style="width: 100%; height: 350px;"> </div>
                            </div>
                            <!-- ./chart-responsive -->
                          </div>
                          <!-- /.col -->
                        </div>
                        <!-- /.row -->
                      </div>
                      <!-- /.box-body -->
                      
                      <!-- /.footer -->
                    </div></div>
          </div>
          <div class="col-md-6">
               <div class="box box-warning">
                      <div class="box-header with-border">
                        <h3 class="box-title">Persentase Jumlah Pengajuan</h3>
                      <!-- /.box-header -->
                      <div class="box-body">
                        <div class="row">
                          <div class="col-md-12">
                            <div class="chart-responsive">
                              <div id="divjumlahpengajuan" style="width: 100%; height: 350px;"> </div>
                            </div>
                            <!-- ./chart-responsive -->
                          </div>
                          <!-- /.col -->
                        </div>
                        <!-- /.row -->
                      </div>
                      <!-- /.box-body -->
                      
                      <!-- /.footer -->
                    </div></div>
          </div>
      </div>
      <div class="row">
        <div class="col-md-12">
               <div class="box box-warning">
                      <div class="box-header with-border">
                        <h3 class="box-title"></h3>

                        
                         
                      <!-- /.box-header -->
                      <div class="box-body">
                        <div class="row">
                          <div class="col-md-12">
                            <div class="chart-responsive">
                              <div id="containerperbulan" style="height:500px;" ></div>
                            </div>
                            <!-- ./chart-responsive -->
                          </div>
                          <!-- /.col -->
                        </div>
                        <!-- /.row -->
                      </div>
                      <!-- /.box-body -->
                      
                      <!-- /.footer -->
                    </div></div>
          </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="box box-warning">
              <table class="table table-bordered">
                  <tr>
                      <th>Status</th>
                      <th>Jumlah</th>
                      <th>Keterangan</th>
                  </tr>
                  <tr>
                      <td>
                          Disetujui
                      </td>
                      <td align="center">
                        <?php echo isset($status_izin["3"]) ? $status_izin["3"] : 0; ?>
                      </td>
                      <td>
                      </td>
                  </tr>
                  <tr>
                      <td>
                          Menunggu
                      </td>
                      <td align="center">
                        <?php echo isset($status_izin["1"]) ? $status_izin["1"] : 0; ?>
                      </td>
                      <td>
                      </td>
                  </tr>
                  <tr>
                      <td>
                          Perubahan
                      </td>
                      <td align="center">
                        <?php echo isset($status_izin["4"]) ? $status_izin["4"] : 0; ?>
                      </td>
                      <td>
                      </td>
                  </tr>
                  <tr>
                      <td>
                          Ditolak
                      </td>
                      <td align="center">
                        <?php echo isset($status_izin["6"]) ? $status_izin["6"] : 0; ?>
                      </td>
                      <td>
                      </td>
                  </tr>
              </table>
          </div>
        </div>
          <!-- /.col -->
          <div class="col-md-6">
               <div class="box box-warning">
                      <div class="box-header with-border">
                        <h3 class="box-title"></h3>
   
                      <!-- /.box-header -->
                      <div class="box-body">
                        <div class="row">
                          <div class="col-md-12">
                            <div class="chart-responsive">
                              <div id="chartjenis" style="width: 100%; height: 350px;"> </div>
                            </div>
                            <!-- ./chart-responsive -->
                          </div>
                          <!-- /.col -->
                        </div>
                        <!-- /.row -->
                      </div>
                      <!-- /.box-body -->
                      
                      <!-- /.footer -->
                    </div></div>
          </div>
      </div>
  </div>
</div>
<script type="text/javascript">   
$("#form_grafik").submit(function(){
  searchform();
  return false;
});
 
$('.datepicker').datepicker({
  autoclose: true,format: 'yyyy-mm-dd'
});
function searchform(){
        var json_url = "<?php echo base_url() ?>admin/izin/izin_pegawai/lihatgrafik";
        event.preventDefault();

        // Get form
        var form = $('#form_grafik')[0];

        // Create an FormData object 
        var param_data = new FormData(form);
        
         $.ajax({    
            type: "POST",
            enctype: 'multipart/form-data',
            url: json_url,
            data: param_data,
            dataType: "html",
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function(data){ 
                $(".contengrafik").empty().append(data);
            },
            error: function (e) {
                //$(".messages").empty().append("Ada kesalahan, sialhkan hubungi admin");
                //console.log("ERROR : ", e);

            }});
        return false; 
    }
 </script>
</div>