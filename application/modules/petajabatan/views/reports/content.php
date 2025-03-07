<?php
$can_delete	= $this->auth->has_permission('Petajabatan.Report.Delete');
$can_edit		= $this->auth->has_permission('Petajabatan.Report.Edit');
$can_add		= $this->auth->has_permission('Petajabatan.Report.Create');
?>
<center>

	<div class="col-sm-12">
		<div class="box-small col-sm-12"  style="border: 1px solid black;min-height:50px; padding:10px;margin-bottom:20px;background:#3c8dbc;color:white;">
			<b>
			<?php echo isset($datadetil->NAMA_UNOR) ? $datadetil->NAMA_UNOR : ""; ?>
			</b>
		</div>
		<div class="box-small  col-sm-4" style="margin-top:20px;min-height:50px;">
							<table class="table table-bordered">
								<tr>
									<td>
										No
									</td>
									<td>
										Jabatan
									</td>
									<td>
										KLS
									</td>
									<td>
										B
									</td>
									<td>
										K
									</td>
									<td>
										-/+
									</td>
								</tr>
								 <?php
								 $no = 1;
								 if(isset($akuota[$datadetil->ID."-ID_JABATAN"])){
								  for($a=0;$a < count($akuota[$datadetil->ID."-ID_JABATAN"]);$a++){
								  ?>
								  <tr>
									  <td>
										  <?php echo $no; ?>.
									  </td>
									  <td>
									  	<?php if($can_edit): ?>
										  <a href="<?php echo base_url(); ?>admin/reports/petajabatan/editkuota/<?php echo $akuota[$datadetil->ID]; ?>/<?php echo $akuota[$datadetil->ID."-ID_JABATAN"][$a]; ?>" class="show-modal"  tooltip="Ubah Kuota Jabatan <?php echo $akuota[$datadetil->ID."-NAMA_Jabatan"][$a]; ?>"><?php echo $akuota[$datadetil->ID."-NAMA_Jabatan"][$a]; ?></a>
										<?php else: ?>
											<?php echo $akuota[$datadetil->ID."-NAMA_Jabatan"][$a]; ?>
										<?php endif; ?>
									  </td>
									  <td>
										  <?php echo $akuota[$datadetil->ID."-KELAS"][$a]; ?>
									  </td>
									  <td>
									  <?php 
									  	$jmlada = isset($apegawai[$datadetil->ID."-jml-".$akuota[$datadetil->ID."-ID_JABATAN"][$a]]) ? $apegawai[$datadetil->ID."-jml-".$akuota[$datadetil->ID."-ID_JABATAN"][$a]] : "0";
									  	// echo $ideselon4."-jml-".$akuota[$ideselon4."-ID_JABATAN"][$a]; ?>
									  	<?php echo $jmlada; ?>
										  
									  </td>
									  <td>
										  <?php 
										  // kuota
										  $quota = $akuota[$datadetil->ID."-JML"][$a];
										  echo $quota; 
										  $sisa = (int)$jmlada - (int)$quota;
										  ?>
									  </td>
									  <td <?php echo $sisa < 0 ? "style=background:red" : "";  ?> <?php echo $sisa > 0 ? "style=background:yellow" : "";  ?>>
										  <?php
										  
										  echo $sisa;
										  ?>
									  </td>
								  </tr>
								<?php
								$no++;
								  }
							  }
							 ?>
							</table>
						</div>
			   
	</div>
		<?php 
		$width = 3;
		$jmleselon3 = count($eselon3["ID"]);
		if($jmleselon3 < 4){
			$width = 4;
		} ?>
		<?php foreach($satker as $record)
		{ ?>
			<div class="box-small  col-sm-<?php echo $width; ?>">
			   <div class="box-small  col-sm-12" style="border: 1px solid black;background:green;min-height:50px;color:white;padding-top:10px;">
				  <b> <?php echo $record->NAMA_UNOR; ?>
				  
				  </b>
				  <?php if($can_add): ?>
							   <div class="btn-group">
								  <button type="button" class="btn btn-warning">Pilih</button>
								  <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									<span class="caret"></span>
									<span class="sr-only">Toggle Dropdown</span>
								  </button>
								  <ul class="dropdown-menu" role="menu">
									<li><a href="<?php echo base_url(); ?>admin/reports/petajabatan/addkuota/<?php echo $akuota[$record->ID]; ?>" class="show-modal" tooltip="Tambah Kuota Jabatan <?php echo $aeselon4[$ideselon3][$i]; ?>">Tambah Jabatan</a></li>
								  </ul>
								</div>
						   <?php endif; ?>
			   </div>
			   <div class="box-small  col-sm-12" style="margin-top:20px;min-height:50px;">
							<table class="table table-bordered">
								<tr>
									<td>
										No
									</td>
									<td>
										Jabatan
									</td>
									<td>
										KLS
									</td>
									<td>
										B
									</td>
									<td>
										K
									</td>
									<td>
										-/+
									</td>
								</tr>
								 <?php
								 $no = 1;
								 if(isset($akuota[$record->ID."-ID_JABATAN"])){
								  for($a=0;$a < count($akuota[$record->ID."-ID_JABATAN"]);$a++){
								  
								  ?>
								  <tr>
									  <td>
										  <?php echo $no; ?>.
									  </td>
									  <td>
									  	<?php if($can_edit): ?>
										  <a href="<?php echo base_url(); ?>admin/reports/petajabatan/editkuota/<?php echo $akuota[$record->ID]; ?>/<?php echo $akuota[$record->ID."-ID_JABATAN"][$a]; ?>" class="show-modal"  tooltip="Ubah Kuota Jabatan <?php echo $akuota[$record->ID."-NAMA_Jabatan"][$a]; ?>"><?php echo $akuota[$record->ID."-NAMA_Jabatan"][$a]; ?></a>
										<?php else: ?>
											<?php echo $akuota[$record->ID."-NAMA_Jabatan"][$a]; ?>
										<?php endif; ?>
									  </td>
									  <td>
										  <?php echo $akuota[$record->ID."-KELAS"][$a]; ?>
									  </td>
									  <td>
									  <?php 
									  	$jmlada = isset($apegawai[$record->ID."-jml-".$akuota[$record->ID."-ID_JABATAN"][$a]]) ? $apegawai[$record->ID."-jml-".$akuota[$record->ID."-ID_JABATAN"][$a]] : "0";
									  	//echo $ideselon4."-jml-".$akuota[$ideselon4."-ID_JABATAN"][$a]; ?>
									  	<?php echo $jmlada; ?>
										  
									  </td>
									  <td>
										  <?php 
										  // kuota
										  $quota = $akuota[$record->ID."-JML"][$a];
										  echo $quota; 
										  $sisa = (int)$jmlada - (int)$quota;
										  ?>
									  </td>
									  <td <?php echo $sisa < 0 ? "style=background:red" : "";  ?> <?php echo $sisa > 0 ? "style=background:yellow" : "";  ?>>
										  <?php
										  
										  echo $sisa;
										  ?>
									  </td>
								  </tr>
								<?php
								$no++;
								  }
							  }
							 ?>
							</table>
						</div>
			   
				    
			</div>
			
		<?php }?>

</center>
<div class="box-small  col-sm-12">
<table>
	<tr>
		<td style="background:red;width:40px;"><td><td> &nbsp; Kurang</td>
	</tr>
	<tr>
		<td style="background:yellow;width:40px;"> <td><td> &nbsp; Melebihi</td>
	</tr>
</table>
</div>