
<style>
	.dt-center {
		text-align:center;
	}
</style>
<!--tab-pane-->
<div class="tab-pane active" id="<?php echo $TAB_ID;?>">
    <div class="form-group">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-21 col-xs-12 divcontentortu">
             
            
        </div>
        </div>
    </div>
</div>
<!--tab-pane-->


<script type="text/javascript">
function loadkeluargaall(){
	$('.divcontentortu').html("<center>Load Data...</center>");
	$.ajax({
		type: 'POST',
		url: "<?php echo base_url() ?>pegawai/data_keluarga/contentorangtua",
		data : {
			PNS_ID:'<?php echo $PNS_ID;?>'
		},
		success: function(data) {
			$('.divcontentortu').html(data);
			$('.divcontentortu').fadeIn(1000);
		}
	})
}
loadkeluargaall();
</script>
