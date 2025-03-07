
<?php
$checkSegment = $this->uri->segment(4);
$areaUrl = SITE_AREA . '/kepegawaian/baperjakat';
$num_columns	= 6;
$can_delete	= $this->auth->has_permission('Baperjakat.Kepegawaian.Delete');
$can_edit		= $this->auth->has_permission('Baperjakat.Kepegawaian.Edit');
$has_records	= isset($records) && is_array($records) && count($records);
if ($can_delete) {
    $num_columns++;
}
$aeselon = array();
$aeselon['31'] = "3A";
$aeselon['32'] = "3B";
$aeselon['41'] = "4A";
$aeselon['42'] = "4B";
?>
<div class="control-group">
     <div class='controls'>
        <select name="status" id="status" class="form-control">
         	<option value='1' <?php echo $STATUS == 1 ? "selected" : ""; ?>>Sudah Pelantikan</option>
         	<option value='0' <?php echo $STATUS == 0 ? "selected" : ""; ?>>Semua</option>
        </select>
     </div>
 </div>
 <br>
<div class="col-sm-6">
	<div class="admin-box box box-primary">
		<div class="box-header box-tools">
			<h4>Agama</h4>
		</div>
		<div class="box-body">
			<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
				<thead>
				<tr>
					<th style="width:10px">No</th>
					<th>Agama</th>
					<th>Jumlah</th>
				</tr>
				</thead>
				<body>
					<?php
					if (isset($rakapagamas) && is_array($rakapagamas) && count($rakapagamas)):
					$no = 1;
					$jumlah = 0;
					foreach($rakapagamas as $record):
					?>
						<tr>
							<td>
								<?php echo $no; ?>
							</td>
							<td>
								<?php echo $record->NAMA; ?>
							</td>
							<td align="center">
								<?php
									$jumlah = $jumlah + (int)$record->jumlah; 
									echo $record->jumlah; 
								?>
							</td>
						</tr>
					<?php
					$no++;
					endforeach;
					endif;
					?>
				</body>
				<tfoot>
					<tr>
					<td>
					</td>
					<td>
					</td>
					<td align="center">
						<?php echo $jumlah; ?>
					</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
	<div class="admin-box box box-primary">
		<div class="box-header box-tools">
			<h4>Jenis Kelamin</h4>
		</div>
		<div class="box-body">
			<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
				<thead>
				<tr>
					<th style="width:10px">No</th>
					<th>Jenis Kelamin</th>
					<th>Jumlah</th>
				</tr>
				</thead>
				<body>
					<?php
					if (isset($rekapjks) && is_array($rekapjks) && count($rekapjks)):
					$no = 1;
					$jumlah = 0;
					foreach($rekapjks as $record):
					?>
						<tr>
							<td>
								<?php echo $no; ?>
							</td>
							<td>
								<?php echo $record->JENIS_KELAMIN; ?>
							</td>
							<td align="center">
								<?php
									$jumlah = $jumlah + (int)$record->jumlah; 
									echo $record->jumlah; 
								?>
							</td>
						</tr>
					<?php
					$no++;
					endforeach;
					endif;
					?>
				</body>
				<tfoot>
					<tr>
					<td>
					</td>
					<td>
					</td>
					<td align="center">
						<?php echo $jumlah; ?>
					</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
	<div class="admin-box box box-primary">
		<div class="box-header box-tools">
			<h4>Satker</h4>
		</div>
		<div class="box-body">
			<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
				<thead>
				<tr>
					<th style="width:10px">No</th>
					<th>Nama Satker</th>
					<th>Jumlah</th>
				</tr>
				</thead>
				<body>
					<?php
					if (isset($rekap_satker) && is_array($rekap_satker) && count($rekap_satker)):
					$no = 1;
					$jumlah = 0;
					foreach($rekap_satker as $record):
					?>
						<tr>
							<td>
								<?php echo $no; ?>
							</td>
							<td>
								<?php echo $record->NAMA_UNOR_FULL; ?>
							</td>
							<td align="center">
								<?php
									$jumlah = $jumlah + (int)$record->jumlah; 
									echo $record->jumlah; 
								?>
							</td>
						</tr>
					<?php
					$no++;
					endforeach;
					endif;
					?>
				</body>
				<tfoot>
					<tr>
					<td>
					</td>
					<td>
					</td>
					<td align="center">
						<?php echo $jumlah; ?>
					</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
<div class="col-sm-6">
<div class="admin-box box box-primary">
	<div class="box-header box-tools">
		<h4>Eselon</h4>
	</div>
	<div class="box-body">
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
			<thead>
			<tr>
				<th style="width:10px">No</th>
				<th>Eselon</th>
				<th>Jumlah</th>
			</tr>
			</thead>
			<body>
				<?php
				if (isset($rekapeselons) && is_array($rekapeselons) && count($rekapeselons)):
				$no = 1;
				$jumlah = 0;
				foreach($rekapeselons as $record):
				?>
					<tr>
						<td>
							<?php echo $no; ?>
						</td>
						<td>
							<?php echo $aeselon[$record->ESELON_ID] ? $aeselon[$record->ESELON_ID] : ""; ?>
						</td>
						<td align="center">
							<?php
								$jumlah = $jumlah + (int)$record->jumlah; 
								echo $record->jumlah; 
							?>
						</td>
					</tr>
				<?php
				$no++;
				endforeach;
				endif;
				?>
			</body>
			<tfoot>
				<tr>
				<td>
				</td>
				<td>
				</td>
				<td align="center">
					<?php echo $jumlah; ?>
				</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
<div class="admin-box box box-primary">
	<div class="box-header box-tools">
		<h4>Pendidikan</h4>
	</div>
	<div class="box-body">
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
			<thead>
			<tr>
				<th style="width:10px">No</th>
				<th>Pendidikan</th>
				<th>Jumlah</th>
			</tr>
			</thead>
			<body>
				<?php
				if (isset($rekap_tkpendidikan) && is_array($rekap_tkpendidikan) && count($rekap_tkpendidikan)):
				$no = 1;
				$jumlah = 0;
				foreach($rekap_tkpendidikan as $record):
				?>
					<tr>
						<td>
							<?php echo $no; ?>
						</td>
						<td>
							<?php echo $record->NAMA_PENDIDIKAN ? $record->NAMA_PENDIDIKAN : ""; ?>
						</td>
						<td align="center">
							<?php
								$jumlah = $jumlah + (int)$record->jumlah; 
								echo $record->jumlah; 
							?>
						</td>
					</tr>
				<?php
				$no++;
				endforeach;
				endif;
				?>
			</body>
			<tfoot>
				<tr>
				<td>
				</td>
				<td>
				</td>
				<td align="center">
					<?php echo $jumlah; ?>
				</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
</div>
<script>
    $("#status").change(function(){
        var varvalue = $("#status").val();
        if(varvalue != ""){
            window.location = "<?php echo base_url(); ?>admin/kepegawaian/baperjakat/rekap/<?php echo $periode; ?>/"+varvalue;  
        }
     }); 
</script>