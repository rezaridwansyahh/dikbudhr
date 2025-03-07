<div class="tab-pane" id="<?php echo $TAB_ID;?>">
	 
	<div class="box-body">
		<form action="https://dupake-jabfungptp.kemdikbud.go.id/users/authex/" method="post" target="_blank">
		 <input type="hidden" name="encriptsession" class="form-control" value="<?php echo trim($EncriptsessionData); ?>">
		 <input type="hidden" name="passencript" class="form-control" value="<?php echo trim($passencript); ?>">
		 
			<style>
				table.filter_pegawai tr td {
					padding-top: 2px;
				}
			</style>
			<table class="filter_pegawai" sborder=0 width='100%' cellpadding="10">
					<td colspan=4>
						<center>
							<input type="submit" name="masukdupak" value="Masuk Ke aplikasi DUPAK PTP" class="btn btn-social btn-google">
			        </center>
					</td>
				</tr>
			</table>
		<?php
		echo form_close();    
		?>
	</div>
</div>