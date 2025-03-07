<div class="callout callout-info">
   <h4>Informasi!</h4>
   <p>Proyeksi pegawai yang akan pensiun 10 tahun kedepan berdasarkan satker</p>
 </div>
<div class="admin-box box box-primary">

	<div class="box-body">	
		<table class="table table-bordered table-striped table-responsive table-hover">
			<thead>
				<tr>
					<th width="20px" rowspan="2">No</th>
					<th rowspan="2">UNIT</th>
					<th colspan="10" rowspan="1">Tahun</th>
				</tr>
				<tr>
					<?php for($i=$tahun;$i<$tahun+10;$i++){ ?>
						<th>
							<?php echo $i; ?>
						</th>	
					<?php } ?>
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
						<?php for($i=$tahun;$i<$tahun+10;$i++){ ?>
							<td align="center">
								<a href="<?php echo base_url()."admin/reports/proyeksi_pensiun/listpensiunsatker/".$i."/".$xlink;?>">
									<?php echo $datajumlah[$x][$i] ? $datajumlah[$x][$i] : 0; ?>
								</a>
								- (<?php echo $jmles1[$x][$i]; ?>)
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
								<?php for($i=$tahun;$i<$tahun+10;$i++){ ?>
									<td align="center">
										<a href="<?php echo base_url()."admin/reports/proyeksi_pensiun/listpensiunsatker/".$i."/".$u;?>">
											<?php echo $datajumlah[$u][$i]; ?>
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
	var xyz = $("#form_search_pegawai").serialize();
	window.open("<?php echo base_url('admin/reports/proyeksi_pensiun/downloadsatker');?>?"+xyz);
});
 
</script>
