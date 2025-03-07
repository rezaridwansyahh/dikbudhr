<script src="<?php echo base_url(); ?>assets/js/amcharts/amcharts.js" type="text/javascript" ></script>
<script src="<?php echo base_url(); ?>assets/js/amcharts/serial.js" type="text/javascript" ></script>  
<script src="<?php echo base_url(); ?>assets/js/amcharts/pie.js" type="text/javascript" ></script>  
<script type="text/javascript" src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<?php
	$this->load->library('convert');
	$convert = new convert();
	$mainmenu = $this->uri->segment(2);
	$menu = $this->uri->segment(3);
	$submenu = $this->uri->segment(4);
?>
<?php
if(!$this->auth->has_permission('Pegawai.Kepegawaian.Filtersatker')){
?>
<div class="row " style="margin:10px 0px;">
	<div class="col-md-12x">
		<table class="filter_unit_kerja" sborder=0 width='100%' cellpadding="10">
				<tr>
					<td><label for="example-text-input" class="col-form-label">Unit Kerja</label></td>
				</tr>	
				<tr>
					<td colspan=2>
						<select id="unit_id_key" name="unit_id_key" width="100%" class=" col-md-10 format-control">
							<?php 
								if($selectedSatker){
									echo "<option value='$selectedSatker->ID' SELECTED>$selectedSatker->NAMA_UNOR_FULL</option>";
								}
							?>
						</select>
					</td>
				</tr>
		</table>		
	</div>
</div>
<?php } ?>
<script type="text/javascript">
	$("#unit_id_key").select2({
		placeholder: 'Cari Unit Kerja...',
		width: '100%',
		minimumInputLength: 0,
		allowClear: true,
		ajax: {
			url: '<?php echo site_url("pegawai/kepegawaian/ajax_unit_list");?>',
			dataType: 'json',
			data: function(params) {
				return {
					term: params.term || '',
					page: params.page || 1
				}
			},
			cache: true
		}
	}).change(function(){
		if($(this).val()){
			window.location = "?unit_id="+$(this).val();
		}
		else window.location = "?";
	});
</script>	
<div class="row">
	<div class="col-md-12">
			<div class="box box-warning">
					<div class="box-header with-border">
						<h3 class="box-title">Matriks Golongan dan Pendidikan</h3>

						<div class="box-tools pull-right">
							<div class="btn-group ">
								<button type="button" class="btn btn-warning">Silahkan Pilih</button>
								<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									<span class="caret"></span>
									<span class="sr-only">Toggle Dropdown</span>
								</button>
								<ul class="dropdown-menu" role="menu">
									<li><a href="<?php echo $download_url; ?>" target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download .xls</a></li>
								</ul>
							</div>	
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="row">
								<div class="col-md-12">
									<?php 
										if(sizeof($data_rekap_golongan_per_pendidikan)>0){
											?>
												<table class="table table-list table-bordered">
													<thead>
														<tr>
																<?php 
																	foreach($data_rekap_golongan_per_pendidikan as $row){
																			foreach(array_keys($row) as $column){ ?>
																					<th align='center'><?php echo $column;?></th>
																			<?php 
																			}
																			break;
																	}
																?>
														</tr>
													</thead>
													<tbody>
														
																<?php 
																	foreach($data_rekap_golongan_per_pendidikan as $row){
																		?>
																		<tr>
																				<?php foreach(array_keys($row) as $column){ ?>
																								<td align='center'><?php echo $row[$column];?></td>
																				<?php }	?>
																		</tr>
																		<?php 
																	}
																?>
															
													</tbody>
										</table>
										<?php 	
										}
										else echo "Data tidak ada";
									?>
								</div>	
							</div>	
				</div>
			</div>	
		
	</div>
</div>