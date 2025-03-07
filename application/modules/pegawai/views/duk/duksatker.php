<?php 
	$TAB_ID = "DUK_CONTAINER";
?>
<style>
	.dt-center {
		text-align:center;
	}
</style>
<!--tab-pane-->
<div id="<?=$TAB_ID ?>" class="admin-box box box-primary">
	<div class="box-body">	
		<div class="form-group">
			<div class="row">
				  <center>
				  	DAFTAR URUT KEPANGKATAN PEGAWAI NEGERI SIPIL<br>
				  	<?php echo $NAMA_UNOR; ?><br>
				  	Keadaan Bulan <?php echo $nama_bulan; ?> <?php echo date("Y"); ?> 																							
				  </center>
			</div>
			<div class="row">
				<div class="col-md-12">

				&nbsp;<button class='btn btn-danger download_xls pull-right' target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download .xls</button>
					<table id="duk_table" class="table table-data">
						<thead>
							<tr>
								<th class="bordertopbottom" width="35" rowspan="2" valign="top" align="center"> No Urut </th>
								<th class="bordertopbottom" width="183" rowspan="2" align="center"> Unitkerja</th> 
								<th class="bordertop" width="183" rowspan="2" align="center"> NAMA</th>
								<th class="bordertop" width="183" rowspan="2" align="center"> Karpeg</th>
								<th class="bordertop" width="79" align="center" colspan="2"> Golongan</th>
								<th class="bordertop" width="200" rowspan="2" align="center"> NAMA JABATAN </th>
								<th class="bordertop" width="76" rowspan="2" align="center"> MASA KERJA </th>
								<th class="bordertop" width="111" rowspan="2" align="center"> TMT CPNS </th>
								
								<th class="bordertop" width="196" rowspan="2" align="center">PENDIDIKAN </th>
								<th class="bordertop" width="61" rowspan="2" align="center"> USIA </th>
							</tr>
							<tr>
								<th class="bordertop" width="79" align="center"> PANGKAT/GOL </th>
								<th class="bordertop" width="111" align="center"> TMT GOLONGAN </th>
							</tr>
						</thead>
						<tfoot>
							<tr>
									
							</tr>
						</tfoot>
						<tbody>
						
						</tbody>
					</table>  
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--tab-pane-->


<script type="text/javascript">
	$(".download_xls").click(function(){
		var xyz = $("#form_search_pegawai").serialize();
		window.open("<?php echo base_url('pegawai/duk/duksatker_download');?>?"+xyz);
	});
	(function($){
		var $container = $("#<?php echo $TAB_ID;?>");
		var grid_daftar = $(".table-data",$container).DataTable({
				ordering: false,
				processing: true,
				"bFilter": false,
				"bLengthChange": false,
				serverSide: true,
				"columnDefs": [
					{"className": "dt-center", "targets": [0,3]},
					{"className": "dt-left", "targets": [2,3]},
				],
				ajax: {
					url: "<?php echo base_url() ?>pegawai/duk/ajax_list_satker",
					type:'POST',
					data : function(d){
						d.unit_id = $("#unit_id").val();
					}
				}
		});		
	})(jQuery);
</script>
