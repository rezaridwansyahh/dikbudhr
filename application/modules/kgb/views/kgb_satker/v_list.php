<?php 
	
?>

<div id="container-sk-kgb" class="admin-box box box-primary expanded-box">
	<div class="box-header">
              <h3 class="box-title">Pencarian Lanjut</h3>
			   <div class="box-tools pull-right">
                	<button type="button" class="btn btn-box-tool btn-default btn-advanced-search" data-widget="collapse">
						<i class="fa fa-plus"></i> Tampilkan
					</button>
                	
              </div>
	</div>

	<div class="box-body">
		<?php echo form_open($this->uri->uri_string(),"id=form_search_pegawai","form"); ?>
			<style>
				table.filter_pegawai tr td {
					padding-top: 2px;
				}
			</style>
			<table class="filter_pegawai" sborder=0 width='100%' cellpadding="10">
				<tr>
					<td width="20px"></td>
					<td width="200px"><label for="example-text-input" class="col-form-label">Satuan Kerja</label></td>
					<td colspan=2>
                        <?php echo $selectedSatker->NAMA_UNOR?>
                    </td>
				</tr>
				
				<tr>
					<td width="20px"></td>
					<td width="200px"><label for="example-text-input" class="col-form-label">Periode</label></td>
					<td style="padding-right:10px;" width="200px" >
						<select class="form-control select2" name="filter_bulan">
                            <?php 
                                $bulans = array(
                                    'Januari',
                                    'Februari',
                                    'Maret',
                                    'April',
                                    'Mei',
                                    'Juni',
                                    'Juli',
                                    'Agustus',
                                    'September',
                                    'Oktober',
                                    'Nopember',
                                    'Desember'
                                );
                                foreach($bulans as  $key=>$value){
                                    echo "<option value='".((int)($key+1))."'>$value</option>";
                                }
                            ?>
						</select>
					</td>
					<td ><input class="form-control" type="text" name="filter_tahun" readonly value="<?php echo date('Y');?>" ></td>
				</tr>
				
				<tr>
					<td colspan=4>
						<button type="submit" class="btn btn-box-tool btn-default pull-right "><i class="fa fa-search"></i> Cari</button>
					</td>
				</tr>
			</table>
		<?php
		echo form_close();    
		?>
	</div>
</div>

<div class="admin-box box box-primary">
    <div class="box-header">
        DAFTAR PEGAWAI YANG MENDAPATKAN KENAIKAN GAJI BERKALA 
		<div class="box-tools pull-right">
			<?php if ($canCetakSKKolektif) : ?>
				<button type="button" id="btn-cetak-sk-kolektif" class="btn btn-box-tool btn-default " >
					<i class="fa fa-print"></i> Surat SK Kolektif
				</button>
			<?php endif; ?>					
			<button href="<?php echo $module_url?>/form_settings" type="button" id="btn-kgb-settings-satker" class="btn btn-box-tool btn-default " >
					<i class="fa fa-cog"></i> Setting
			</button>								
        </div>                       
    </div>
	<div class="box-body">
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
			<thead>
			<tr>
				<th style="width:10px">No</th>
				<th>Pegawai</th>
				<th>Pangkat<BR>Golongan</th>
                <th>MKG (Thn)/Bulan</th>
                <th>Gaji Pokok</th>
                <th>Satuan Kerja</th>                    
				<th>Keterangan</th>   
				<th width="70px" align="center">#</th></tr>
			</thead>
		</table>
	</div>
</div>
<script>
    $(document).ready(function(){
		
		$('body').on('click','#btn-kgb-settings-satker',function(event){
			showModalX.call(this,'sukses-settings-kgb',function(){
				$table.ajax.reload();
			},this);
			event.preventDefault();
		});
		$('body').on('click','#btn-cetak-sk-kolektif',function(event){
			$.fileDownload(BASE_URL+'kgb/kgb_satker/cetak_sk_kolektif/'+$("[name=filter_tahun]").val()+"/"+$("[name=filter_bulan]").val(), {
				successCallback: function (url) {
					//alert('You just got a file download dialog or ribbon for this URL :' + url);
				},
				failCallback: function (responseHtml, url) {
					var responseCode = JSON.parse(responseHtml);
					swal("Informasi", responseCode.message, "error");
				}
			});
		});
		$('body').on('click','.btn-cetak-personal1',function(event){
			$.fileDownload(BASE_URL+'kgb/kgb_satker/cetak_sk_kolektif/'+$("[name=filter_tahun]").val()+"/"+$("[name=filter_bulan]").val(), {
				successCallback: function (url) {
					//alert('You just got a file download dialog or ribbon for this URL :' + url);
				},
				failCallback: function (responseHtml, url) {
					var responseCode = JSON.parse(responseHtml);
					swal("Informasi", responseCode.message, "error");
				}
			});
		});
		$('body').on('click','.btn-proses-kgb',function(event){
			showModalX.call(this,'sukses-proses-kgb',function(){
				$table.ajax.reload();
			},this);
			event.preventDefault();
		});
		
        
        $table = $(".table-data").DataTable({
	
            dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
            processing: true,
            serverSide: true,
            "columnDefs": [
                            {"className": "dt-center", "targets": [4]}
                        ],
            ajax: {
            url: "<?php echo $module_url?>/ajax_list",
            type:'POST',
            "data": function ( d ) {
                    d.search['advanced_search_filters']=  $("#form_search_pegawai").serializeArray();
                }
            }
        });
        $("#form_search_pegawai").submit(function(){
            $table.ajax.reload(null,true);
            return false;
        });
    });
</script>

<style>
	.tunda {
		background-color:red;
		color:white;
		font-weight:bold;
	}
</style>