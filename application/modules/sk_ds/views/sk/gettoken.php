<div class="tab-pane active" id="<?php echo $TAB_ID;?>">
<?php
$checkSegment = $this->uri->segment(4);
$num_columns	= 44;
$has_records	= isset($records) && is_array($records) && count($records);

if ($can_delete) {
    $num_columns++;
}
?>

<?php echo form_open($this->uri->uri_string(),"id=frm","form"); ?>
			 
			 <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Request Token</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Usercert</label>

                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputEmail3" name="username" placeholder="UserCert">
                  </div>
                </div>
                <br>
                <div class="form-group">
                  <label for="inputPassword3" class="col-sm-2 control-label">Passphrase</label>

                  <div class="col-sm-10">
                    <input type="password" name="password" class="form-control" id="inputPassword3" placeholder="Passphrase">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputPassword3" class="col-sm-2 control-label">Token</label>

                  <div class="col-sm-10">
                    <input type="text" name="token" readonly class="form-control" id="token">
                  </div>
                </div>
                 
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="button" id="btn_sign" class="btn btn-info pull-left">Dapatkan Token</button>
                
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
		<?php
		echo form_close();    
		?>

<script type="text/javascript">
  function submitdata(){
    
    var json_url = "<?php echo base_url() ?>admin/sk/sk_ds/dapatkantoken";
     $.ajax({    
      type: "POST",
      url: json_url,
      data: $("#frm").serialize(),
            dataType: "json",
            success: function(data){ 
                if(data.success){
                	$("#token").val(data.token);
                    swal("Pemberitahuan!", data.msg, "success");
                }
                else {
                  swal("Pemberitahuan!", data.msg, "error");    
                }
      }});
    return false; 
  }
$('body').on('click','#btn_sign',function () { 
  swal({
    title: "Anda Yakin?",
    text: "Aksi ini menimbulkan token sebelumnya akan dihapuskan!",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: 'btn-danger',
    confirmButtonText: 'Ya!',
    cancelButtonText: "Tidak, Batalkan!",
    closeOnConfirm: false,
    closeOnCancel: false
  },
  function (isConfirm) {
    if (isConfirm) {
      submitdata();
    } else {
      swal("Batal", "", "error");
    }
  });
});
 
</script>