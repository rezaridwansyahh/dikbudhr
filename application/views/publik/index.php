<!-- sweet alert -->
<script src="<?php echo base_url(); ?>themes/admin/js/sweetalert.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/css/sweetalert.css">
<?php

$num_columns	= 4;
$has_recordmisis	= isset($recordmisis) && is_array($recordmisis) && count($recordmisis);

$this->load->model('bidang/bidang_model');
$this->load->model('rumusan/rumusan_model');
?>
<div class="box box-info">
	  
	 <div class="box-body">
			<div class="box-header">
				Konsultasi Publik
			</div>
	 
		  <div class="box-body">
		  	<form action="" method="get">
				<div class="form-group"">
				   <div class="input-group date">
					 <div class="input-group-addon">
					   <i class="fa fa-search"></i>
					 </div>
					   <input type="text" name='keyword' class="form-control" placeholder="No Ktp.." value="<?php echo isset($keyword) ? $keyword : ""; ?>">
				   </div>
				</div> 
			</form>
		  	<h5>Provinsi : <?php echo isset($prov) ? $prov : ""; ?></h5>
		  	<h5>Kabupaten : <?php echo isset($kab) ? $kab : ""; ?></h5>
		  	<h5>Visi : <?php echo isset($visiaktif) ? $visiaktif : ""; ?></h5>
			<table class="table table-bordered table-responsive">
			<thead>
				<tr>
					<th width="20%">Misi</th>
					<th width="">Urusan</th>
					<th width="">Rumusan Masalah</th>
					<th width="">Isu Strategis</th>
					<th width="">Penyebab Masalah</th>
					<th width="">Tujuan</th>
					<th width="">Sasaran</th>
					<th width="">Strategi Pembangunan</th>
					<th width="">Arah Kebijakan</th>
					<th>Lokasi</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					 
				</tr>
			</tfoot>
			<tbody>
				<?php
				  $nob = 1;
				if ($has_recordmisis) :
					foreach ($recordmisis as $misi) :
					$reordpubliks = $this->misi_model->find_publik($misi->id);
					$jmlrecordmisi = count($reordpubliks);
				?>
					<tr>
						 <td rowspan="<?php echo $jmlrecordmisi; ?>" valign="middle"><?php echo $misi->misi; ?></td>
						
					<?php
					$no = 1;
					$bidang = "";
					$permasalahan = "";
					$isu = "";
					$tujuan = "";
					$sasaran = "";
					$strategi = "";
					$penyebab_masalah = "";
					$arah_kebijakan = "";
					$lokasi = "";
					//$reordpubliks = $this->misi_model->find_publik($misi->id);
					$no = 1;
					$has_recordpublik	= isset($reordpubliks) && is_array($reordpubliks) && count($reordpubliks);
					  if ($has_recordpublik) :
						  foreach ($reordpubliks as $publik) :
						  if($no == "2")
						  	echo "<tr>";
					  ?>
							   <td>
							   <?php 
							   		if($bidang != $publik->nama_bidang){
						  				$bidang = $publik->nama_bidang;
						  				echo $publik->nama_bidang; 
						  			}
						  			?></td>
							   <td>
							   	<?php 
							   	if($permasalahan != $publik->permasalahan){
						  				$permasalahan = $publik->permasalahan;
						  		?>
						  			<a href="<?php echo base_url(); ?>publik/viewrumusan/<?php echo $publik->idrumusan; ?>" tooltip="Lihat Detil" class="show-modal"><?php echo $publik->permasalahan; ?></a>
						  		<?php
						  				
						  			}
						  		?>
						  		</td>
						  		 <td>
							   	<?php 
							   	if($isu != $publik->isu){
						  				$isu = $publik->isu;
						  				echo $publik->isu;
						  			}
						  		?></td>
						  		<td>
							   	<?php 
							   	if($penyebab_masalah != $publik->penyebab_masalah){
						  				$penyebab_masalah = $publik->penyebab_masalah;
						  				echo $publik->penyebab_masalah;
						  			}
						  		?></td>
							   <td>
							   <?php 
							   	if($tujuan != $publik->tujuan){
						  				$tujuan = $publik->tujuan;
						  				echo $publik->tujuan;
						  			}
						  		?>
						  		</td>
							   <td>
							   <?php 
							   	if($sasaran != $publik->sasaran){
						  				$sasaran = $publik->sasaran;
						  				echo $publik->sasaran;
						  			}
						  		?>
						  		</td>
						  		<td>
							   <?php 
							   	if($strategi != $publik->strategi){
						  				$strategi = $publik->strategi;
						  				echo $publik->strategi;
						  			}
						  		?>
						  		</td>
						  		<td>
							   <?php 
							   	if($arah_kebijakan != $publik->arah_kebijakan){
						  				$arah_kebijakan = $publik->arah_kebijakan;
						  				echo $publik->arah_kebijakan;
						  			}
						  		?>
						  		</td>
								<td>
							   <?php 
							   	if($lokasi != $publik->lokasi){
						  				$lokasi = $publik->lokasi;
						  		?>
						  			<a href="<?php echo base_url(); ?>publik/viewrumusan/<?php echo $publik->idrumusan; ?>" tooltip="Lihat Detil" class="show-modal"><?php echo $publik->lokasi; ?></a>
						  		<?php
						  			}
						  		?>
						  		</td>
						   </tr>
						<?php
							$no++;
						   endforeach;
						else:
						?>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<?php
					   endif;
					   ?>
				   
				<?php
					$nob++;
					endforeach;
				endif;
				?>
			</tbody>
		</table>

    	</div>
    </div>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/jQuery/jquery-2.2.3.min.js"></script>