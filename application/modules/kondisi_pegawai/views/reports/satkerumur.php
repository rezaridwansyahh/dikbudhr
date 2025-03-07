<div class="callout callout-info">
   <h4>Informasi!</h4>
   <p>Jumlah Pegawai Persatker dan Umur</p>
   
 </div>
<div class="admin-box box box-primary">

	<div class="box-body">	
		<div class="pull-right">
	    	<button type="button" class='btn btn-warning download pull-right' style="margin:3px;" target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download</button>
	  </div>
		<table class="table table-bordered table-striped table-responsive table-hover">
			<thead>
				<tr>
					<th width="20px" rowspan="2">No</th>
					<th rowspan="2">UNIT</th>
					<th colspan="7" rowspan="1">Umur</th>
					<th colspan="13" rowspan="1">Tingkat Pendidikan</th>
				</tr>
				<tr>
					<th>
						<25
					</th>
					<th>
						25-30
					</th>
					<th>
						31-35
					</th>
					<th>
						36-40
					</th>
					<th>
						41-45
					</th>
					<th>
						46-50
					</th>
					<th>
						>50
					</th>	

					<th align="center">SD</th>
					<th align="center">SLTP</th>
					<th align="center">SLTPK</th>
					<th align="center">SLTA</th>
					<th align="center">SLTAK</th>
					<th align="center">SLTAKg</th>
					<th align="center">D-I</th>
					<th align="center">D-II</th>
					<th align="center">D-III</th>
					<th align="center">D-IV</th>
					<th align="center">S1</th>
					<th align="center">S2</th>
					<th align="center">S3</th>
				</tr>
			</thead>
			<body>
				<?php
					$noes1 = 1;
					foreach($dataeselon1 as $x => $vales) {
						$xlink = $x != "" ? $x : "-";
				?>
					<tr>
						<td>
							<?=$noes1 ?>
						</td>
						<td>
							<?=$vales ?>	
						</td>
						
							<td align="center">
								<?php echo $datajumlah[$x]['25'] ? $datajumlah[$x]['25'] : 0; ?>
								- (<?php echo $jmles1[$x]['25']; ?>)
							</td>
							<td align="center">
								<?php echo $datajumlah[$x]['2530'] ? $datajumlah[$x]['2530'] : 0; ?>
								- (<?php echo $jmles1[$x]['2530']; ?>)
							</td>
							<td align="center">
								<?php echo $datajumlah[$x]['3135'] ? $datajumlah[$x]['3135'] : 0; ?>
								- (<?php echo $jmles1[$x]['3135']; ?>)
							</td>
							<td align="center">
								<?php echo $datajumlah[$x]['3640'] ? $datajumlah[$x]['3640'] : 0; ?>
								- (<?php echo $jmles1[$x]['3640']; ?>)
							</td>
							<td align="center">
								<?php echo $datajumlah[$x]['4145'] ? $datajumlah[$x]['4145'] : 0; ?>
								- (<?php echo $jmles1[$x]['4145']; ?>)
							</td>
							<td align="center">
								<?php echo $datajumlah[$x]['4650'] ? $datajumlah[$x]['4650'] : 0; ?>
								- (<?php echo $jmles1[$x]['4650']; ?>)
							</td>
							<td align="center">
								<?php echo $datajumlah[$x]['50'] ? $datajumlah[$x]['50'] : 0; ?>
								- (<?php echo $jmles1[$x]['50']; ?>)
							</td>
							<!-- pendidikan -->
							<?php foreach ($tkpendidikan as $rectk) { ?>
								<td align="center">
									<?php echo $datajumlah[$x]['tk_'.$rectk->ID] ? $datajumlah[$x]['tk_'.$rectk->ID] : 0; ?>
									- (<?php echo $jmles1[$x]['tk_'.$rectk->ID]; ?>)
								</td>
			                <?php } ?>
					</tr>
						<?php
						$nounit = 1;
						foreach($dataunit[$x] as $u => $valunit) {
							if($u != $x){
						?>
							<tr>
								<td align="right">
									<?=$nounit ?>
								</td>
								<td>
									<?=$valunit ?>	
								</td>
								
								<td align="center">
									<a href="<?php echo base_url()."admin/reports/kondisi_pegawai/detilsatkerumur?unit=".$u."&umur=25"; ?>" class="show-modal">
										<?php echo $datajumlah[$u]['25'] ? $datajumlah[$u]['25'] : 0; ?>
									</a>
								</td>
								<td align="center">
									<a href="<?php echo base_url()."admin/reports/kondisi_pegawai/detilsatkerumur?unit=".$u."&umur=2530"; ?>" class="show-modal">
										<?php echo $datajumlah[$u]['2530'] ? $datajumlah[$u]['2530'] : 0; ?>
									</a>
								</td>
								<td align="center">
									<a href="<?php echo base_url()."admin/reports/kondisi_pegawai/detilsatkerumur?unit=".$u."&umur=3135"; ?>" class="show-modal">
										<?php echo $datajumlah[$u]['3135'] ? $datajumlah[$u]['3135'] : 0; ?>
									</a>
								</td>
								<td align="center">
									<a href="<?php echo base_url()."admin/reports/kondisi_pegawai/detilsatkerumur?unit=".$u."&umur=3640"; ?>" class="show-modal">
										<?php echo $datajumlah[$u]['3640'] ? $datajumlah[$u]['3640'] : 0; ?>
									</a>
								</td>
								<td align="center">
									<a href="<?php echo base_url()."admin/reports/kondisi_pegawai/detilsatkerumur?unit=".$u."&umur=4145"; ?>" class="show-modal">
										<?php echo $datajumlah[$u]['4145'] ? $datajumlah[$u]['4145'] : 0; ?>
									</a>
								</td>
								<td align="center">
									<a href="<?php echo base_url()."admin/reports/kondisi_pegawai/detilsatkerumur?unit=".$u."&umur=4650"; ?>" class="show-modal">
										<?php echo $datajumlah[$u]['4650'] ? $datajumlah[$u]['4650'] : 0; ?>
									</a>
								</td>
								<td align="center">
									<a href="<?php echo base_url()."admin/reports/kondisi_pegawai/detilsatkerumur?unit=".$u."&umur=50"; ?>" class="show-modal">
										<?php echo $datajumlah[$u]['50'] ? $datajumlah[$u]['50'] : 0; ?>
									</a>
								</td>
								<!-- pendidikan -->
								<?php foreach ($tkpendidikan as $rectk) { ?>
								<td align="center">
									<a href="<?php echo base_url()."admin/reports/kondisi_pegawai/detilsatkerumur?unit=".$u."&tkpendidikan=".$rectk->ID; ?>" class="show-modal">
										<?php echo $datajumlah[$u]['tk_'.$rectk->ID] ? $datajumlah[$u]['tk_'.$rectk->ID] : 0; ?>
									</a>
								</td>
				                <?php } ?>
							</tr>
						<?php
							$nounit++;		
							}
						}
						?>
				<?php
					$noes1++;		
					}
				?>

			</body>
		</table>
	</div>
</div>

<script type="text/javascript">
$(".download").click(function(){
	window.open("<?php echo base_url('admin/reports/kondisi_pegawai/satkerumurdownload');?>");
});
</script>
