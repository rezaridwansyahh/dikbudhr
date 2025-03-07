
<?php
$can_delete	= $this->auth->has_permission('Petajabatan.Reports.Delete');
$can_edit		= $this->auth->has_permission('Petajabatan.Reports.Edit');
$can_add		= $this->auth->has_permission('Petajabatan.Reports.Create');
?>
<?php  if($unitkerja == ""){ ?>
<div class="callout callout-warning">
<h4>Perhatian!</h4>
<p>Silahkan Masukan unitkerja</p>
</div>
<?php 
die();
} ?>
<table class="table table-bordered" border="1">
	<thead>
		<tr>
			<th>
				NO.
			</th>
			<th>
				KODE UNIT
			</th>
			<th colspan="4">
				UNIT KERJA
			</th>
			<th>
				KELAS
			</th>
			<th>
				BEZET
			</th>
			<th>
				KBTHN
			</th>
			<th>
				SELISIH
			</th>
			<th>
				USUL FORMASI PNS
			</th>
			<th>
				SKALA PRIORITAS
			</th>
			<th>
				ALASAN
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
			</td>
			<td>
			</td>
			<td width="20px">
			</td>
			<td width="20px">
			</td>
			<td width="20px">
			</td>
			<td>
			</td>
			<td>
			</td>
			<td>
			</td>
			<td>
			</td>
			<td>
			</td>
			<td>
			</td>
			<td>
			</td>
			<td>
			</td>
		</tr>
		<?php
		if (isset($satker) && is_array($satker) && count($satker)):
			$no = 1;
            foreach($satker as $record):
        ?>
        <tr>
        	<td valign="top">
        		<?php echo $no; ?>.
        	</td>
        	<td valign="top">
        		<?php echo $record->KODE_INTERNAL; ?>
        	</td>
        	<td valign="top" colspan="12">
        		<b>
        		<?php echo $record->NAMA_UNOR; ?><br>
        		<p class="text-red">
	        		<?php echo $record->GELAR_DEPAN; ?>
	        		<?php echo $record->PEJABAT_NAMA; ?>
	        		<?php echo $record->GELAR_BELAKANG; ?><br>
	        		NIP : <?php echo $record->NIP_BARU; ?>
        		</p>
        		</b>
        	</td>
        </tr>
        <?php
			if(isset($akuota[$record->ID."-ID_JABATAN"])){
				$jmlbezet 		= 0;
				$jmlkebutuhan 	= 0;
				$jmlselisih 	= 0;
				for($a=0;$a < count($akuota[$record->ID."-ID_JABATAN"]);$a++){
					$id_jabatan = $akuota[$record->ID."-ID_JABATAN"][$a];
				?>
				<tr>
		        	<td valign="top" align="center">
		        		-
		        	</td>
		        	<td valign="top" align="center">
		        		-
		        	</td>
		        	<td valign="top" colspan="2">
		        	</td>
		        	<td valign="top" colspan="2">
		        		<b>
		        			<?php 
		        			echo isset($akuota[$record->ID."-NAMA_Jabatan"][$a]) ? $akuota[$record->ID."-NAMA_Jabatan"][$a] : "-";
		        			?>
		        		</b>
		        	</td>
		        	<td valign="top" align="center">
		        		<b>
		        			<?php 
		        			echo $akuota[$record->ID."-KELAS"][$a]; ?>
		        		</b>
		        	</td>
		        	<td valign="top" align="center">
		        		<b>
		        			<?php 
		        			// bezet
		        			$jumlahada = isset($apegawai[trim($record->ID)."-jml-".trim($id_jabatan)]) ? (int)$apegawai[trim($record->ID)."-jml-".trim($id_jabatan)] : 0;
		        			echo  $jumlahada;
		        			$jmlbezet = $jmlbezet + $jumlahada;
		        			?>
		        		</b>
		        	</td>
		        	<td valign="top" align="center">
		        		<b>
		        			<?php 
		        			$kuota = isset($akuota[$record->ID."-JML"][$a]) ? $akuota[$record->ID."-JML"][$a] : 0;
		        			echo $kuota; 
		        			$jmlkebutuhan = $jmlkebutuhan + $kuota;

		        			$selisih = $jumlahada - $kuota;
		        			?>
		        		</b>
		        		 
		        	</td>
		        	<td valign="top" align="center" class="<?php echo $selisih<0 ? 'bg-blue' : ''; ?> <?php echo $selisih>0 ? 'bg-red' : ''; ?>">
		        		 
		        		<b>
		        			<?php 
		        			echo $selisih; 

		        			$jmlselisih = $jmlselisih + $selisih;
		        			?>
		        		</b>
		        	</td>
		        	<td align="">
		        		<?php if($this->auth->has_permission("Petajabatan.Reports.Request")){ ?>
		        		<a href="<?php echo base_url(); ?>admin/reports/bezzeting/ajukan/<?php echo $id_jabatan; ?>/<?php echo $record->ID; ?>" class="btn show-modal <?php echo isset($arequest_data[$record->ID."_".$id_jabatan]) ? "btn-warning " : "btn-primary " ?>" class="ajukan_formasi"><i class="fa fa-table"></i> <?php echo isset($arequest_data[$record->ID."_".$id_jabatan]) ? "[".$arequest_data[$record->ID."_".$id_jabatan]."]" : "Usulkan" ?></a>
		        		<?php	} ?>
		        	</td>
		        	<td></td><td></td>
		        </tr>
		        <?php
		        	if(isset($apegawaidet[$record->ID."-NAMA-".$id_jabatan])){
		        		$nomor = 1;
		        		for($P=0;$P < count($apegawaidet[$record->ID."-NAMA-".$id_jabatan]);$P++){

							?>
							<tr>
					        	<td valign="top" align="center">
					        	</td>
					        	<td valign="top" align="center">
					        	</td>
					        	<td valign="top" colspan="2">
					        	</td>
					        	<td valign="top" colspan="1">
		        				</td>
					        	<td valign="top">
					        		<?php echo $nomor; ?>. 
					        		 <?php
					        		 echo isset($apegawaidet[$record->ID."-NAMA-".$id_jabatan][$P]) ? $apegawaidet[$record->ID."-NAMA-".$id_jabatan][$P] : "";
					        		 ?>; 
					        		 &nbsp;&nbsp;&nbsp;
					        		 <a class="show-modal" href="<?php echo base_url(); ?>admin/kepegawaian/pegawai/profilen/<?php echo $apegawaidet[$record->ID."-ID-".$id_jabatan][$P]; ?>">
					        		  NIP : <?php
					        		 echo isset($apegawaidet[$record->ID."-NIP-".$id_jabatan][$P]) ? $apegawaidet[$record->ID."-NIP-".$id_jabatan][$P] : "";
					        		 ?>
					        		 </a>
					        	</td>
					        	<td valign="top" align="center">
					        		 
					        	</td>
					        	<td valign="top" align="center">
					        		 
					        	</td>
					        	<td valign="top" align="center">
					        		 
					        		 
					        	</td>
					        	<td valign="top" align="center">
					        	</td>
					        	<td></td><td></td><td></td>
					        </tr>
							<?php
							$nomor++;
						}
		        	}
		        ?>

				<?php
				}

				?>
				<tr class="red" style="background-color: #d0d3dc;">
					<td>
					</td>
					<td>
					</td>
					<td colspan="4">
						<b>JUMLAH BEZETING / KEBUTUHAN / SELISIH KESELURUHAN</b>
					</td>
					<td>
					</td>
					<td align="center">
						<b>
						<?php 
							echo $jmlbezet;
						?>
						</b>
					</td>
					<td align="center">
						<b>
						<?php 
							echo $jmlkebutuhan;
						?>
						</b>
					</td>
					<td align="center">
						<b>
						<?php 
							echo $jmlselisih;
						?>
						</b>
					</td>
					<td colspan="4">
					</td>
				</tr>
				<?php
			}

		?>
        <?php
        	$no++;
            endforeach;
        endif;
        ?>
	</tbody>
</table>
 