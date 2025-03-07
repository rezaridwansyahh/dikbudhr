<?php
$areaUrl = SITE_AREA . '/masters/golongan';
$num_columns	= 2;
$can_delete	= $this->auth->has_permission('Golongan.Masters.Delete');
$can_edit		= $this->auth->has_permission('Golongan.Masters.Edit');
$has_records	= isset($records) && is_array($records) && count($records);

if ($can_delete) {
    $num_columns++;
}
?>
<div class="callout callout-info">
   <h4>Informasi!</h4>
   <p>Proyeksi pegawai yang akan pensiun 10 tahun kedepan berdasarkan jenis jabatan</p>
 </div>
<div class="admin-box box box-primary">
	 
	<div class="box-body">	
		<?php echo form_open($this->uri->uri_string()); ?>
			<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-datatable table-hover">
				<thead>
					<tr>
						<th rowspan="2">No</th>
						<th rowspan="2">Tahun</th>
						<th colspan="4">Jenis Jabatan</th>
					</tr>
					<tr>
						<th>
							Struktural
						</th>
						<th>
							Jabatan Fungsional Tertentu
						</th>
						<th>
							Jabatan Fungsional Umum
						</th>
						<th>
							#
						</th>
					</tr>
				</thead>
				<body>
				<?php
				$nomor_urut = 1;
				$tahun = date("Y");
		        for ($i=$tahun;$i<$tahun+10;$i++) {
		        ?>
		        	<tr>
		        		<td>
		        			<?php echo $nomor_urut; ?>
		        		</td>
		        		
		        		<td>
		        			<?php echo $i; ?>
		        		</td>
		        		 
		        		<td>
		        			<a href="<?php echo base_url()."admin/reports/proyeksi_pensiun/listpensiunjjabatan/".$i; ?>/1"><?php echo $row[$i."_1"]; ?></a>
		        		</td>
		        		<td>
		        			<a href="<?php echo base_url()."admin/reports/proyeksi_pensiun/listpensiunjjabatan/".$i; ?>/2">
		        				<?php echo $row[$i."_2"]; ?>
		        			</a>
		        		</td>
		        		<td>
		        			<a href="<?php echo base_url()."admin/reports/proyeksi_pensiun/listpensiunjjabatan/".$i; ?>/4">
		        				<?php echo $row[$i."_4"]; ?>
		        			</a>
		        		</td>
		        		<td>
		        			<?php echo $row[$i."_"]; ?>
		        		</td>
		        	</tr>
		                
		        <?php

		                $nomor_urut++;
		            }
		         
		        ?>
			</body>
			</table>
			
		<?php
   		echo form_close();
    ?>
	</div>
</div>
 
