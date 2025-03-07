<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css"/>

<div class="tab-pane active" id="<?php echo $TAB_ID;?>">
<?php
$checkSegment = $this->uri->segment(4);
$areaUrl = SITE_AREA . '/sk/sk_ds';
$num_columns	= 44;
$can_delete	= $this->auth->has_permission('Sk_ds.Sk.Delete');
$can_edit		= $this->auth->has_permission('Sk_ds.Sk.Edit');
$has_records	= isset($records) && is_array($records) && count($records);

if ($can_delete) {
    $num_columns++;
}
?>

<?php echo form_open($this->uri->uri_string(),"id=submit_form","form"); ?>
			 
			 <div class="box box-info">
            <!-- form start -->
            <form class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">File SK</label>

                  <div class="col-sm-10">
                    <div id="form_upload">
                        <input id="nama_sk" name="nama_sk" class="file" type="file" data-preview-file-type="pdf" title="Silahkan Pilih file pdf">
                      </div>  
                  </div>
                </div>
                <br>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kategori SK</label>

                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputEmail3" name="kategori" placeholder="kategori">
                  </div>
                </div>
                <br>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nomor SK</label>

                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputEmail3" name="nomor_sk" placeholder="Nomor SK">
                  </div>
                </div>
                <br>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Pemilik SK</label>

                  <div class="col-sm-10">
                    <select id="nip_sk" name="nip_sk" width="100%" class="form-control select2">
                        <?php
                        if($selectedAtasanLangsung){
                            echo "<option selected value='".$selectedAtasanLangsung->PNS_ID."'>".$selectedAtasanLangsung->NAMA."</option>";
                        }
                        ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Penandatangan</label>

                  <div class="col-sm-10">
                    <select id="id_pegawai_ttd" name="id_pegawai_ttd" width="100%" class="form-control select2">
                        <?php
                        if($selectedAtasanLangsung){
                            echo "<option selected value='".$selectedAtasanLangsung->PNS_ID."'>".$selectedAtasanLangsung->NAMA."</option>";
                        }
                        ?>
                    </select>
                  </div>
                </div>
                
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="button" id="btn_sign" class="btn btn-info pull-left">Upload</button>
                
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
		<?php
		echo form_close();    
		?>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>

<script type="text/javascript">
  function submitdata(){
    
    var the_data = new FormData(document.getElementById("submit_form"));
    $.ajax({
        url: "<?php echo base_url('admin/sk/sk_ds/act_uploadsk'); ?>",
        type: "POST",
        data: the_data,
        enctype: 'multipart/form-data',
        processData: false, // tell jQuery not to process the data
        contentType: false, // tell jQuery not to set contentType
        dataType: 'JSON',

        beforeSend: function (xhr) {
            //$("#loading-all").show();
        },
        success: function (response) {
            if(response.status){
                swal("Sukses",response.msg,"success");
            }else{
                swal("Ada kesalahan",response.msg,"error");
            }
        }
    });
    
    return false; 
  }
$('body').on('click','#btn_sign',function () { 
  swal({
    title: "Anda Yakin?",
    text: "Upload SK!",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: 'btn-warning',
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
 $("#id_pegawai_ttd").select2({
        placeholder: 'Cari Penandatangan.....',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/kepegawaian/ajax_nama_pejabat");?>',
            dataType: 'json',
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1
                }
            },
            cache: true
        }
    });
 $("#nip_sk").select2({
        placeholder: 'Cari Pemilik SK.....',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/kepegawaian/ajaxnip");?>',
            dataType: 'json',
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1
                }
            },
            cache: true
        }
    });
 
</script>