<style>
	.dataTables_processing{position:absolute;top:1%!important;left:50%;width:100%;height:40px;margin-left:-50%;margin-top:-25px;padding-top:20px;text-align:center;}
</style>
<div class="admin-box box box-primary expanded-box">
	<div class="box-header">
              <h3 class="box-title">Pencarian Lanjut</h3>
			   <div class="box-tools pull-right">
                	<button type="button" class="btn btn-box-tool btn-default btn-advanced-search" data-widget="collapse">
						<i class="fa fa-minus"></i> Tampilkan
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
					<td width="100px"><label for="example-text-input" class="col-form-label">Jenis</label></td>
					<td>
						<select id="jenis" name="jenis" style="width:100%" class="form-control">
	                        <option value="PUSAT">PUSAT</option>
	                        <option value="UPT">UPT</option>
	                        <option value="PTN">PTN</option>
                        </select>
					</td>
					 
				</tr>
				 
				<tr>
					<td colspan="2">
						 
						<a href="javascript:;" style="margin:3px;" class="btn green btn-info button-submit download_xls pull-right"> 
							Download
							<i class="fa fa-download" aria-hidden="true"></i> 
			            </a>
						<a href="javascript:;" id="btn_cari" style="margin:3px;" class="btn green btn-primary button-submit pull-right"> 
							Cari data
							 <i class="fa fa-search"></i>			                
			            </a>
			            
					</td>
				</tr>
			</table>
		<?php
		echo form_close();    
		?>
	</div>
</div>
<div class="admin-box box box-primary">
	<div class="box-body">
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
			<thead>
			<tr>
				<th style="width:10px" rowspan="2">No</th>
				<th rowspan="2">Satuan Kerja</th>
				<th rowspan="2">Jumlah</th>
				<th colspan="2">Jenis Kelamin</th>
				<th colspan="4">Golongan</th>
				<th colspan="4">Jenis Jabatan</th>
				<th colspan="7">Tingkat Pendidikan</th>
			</tr>
			<tr>
				<th align="center">L</th>
				<th align="center">P</th>

				<th align="center">I</th>
				<!-- <th align="center">I/b</th>
				<th align="center">I/c</th>
				<th align="center">I/d</th>
 -->
				<th align="center">II</th>
				<!-- <th align="center">II/b</th>
				<th align="center">II/c</th>
				<th align="center">II/d</th> -->

				<th align="center">III</th>
				<!-- <th align="center">III/b</th>
				<th align="center">III/c</th>
				<th align="center">III/d</th>
 -->
				<th align="center">IV</th>
<!-- 				<th align="center">IV/b</th>
				<th align="center">IV/c</th>
				<th align="center">IV/d</th>
 -->
				<th align="center">Str</th>
				<th align="center">JFT</th>
				<th align="center">Dosen</th>
				<th align="center">JFU</th>

				<th align="center">SD</th>
				<th align="center">SMP</th>
				<th align="center">SMA</th>
				<th align="center">Dip</th>
				<th align="center">S1</th>
				<th align="center">S2</th>
				<th align="center">S3</th>
			</tr>
			</thead>
			<tfoot>
				<tr>
					<th colspan="2" align="right">
						Jumlah
					</th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
<script type="text/javascript">
$("#unit_id_key").select2({
	placeholder: 'Cari Unit Kerja...',
	width: '100%',
	minimumInputLength: 5,
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
});

$table = $(".table-data").DataTable({
	
	dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
	"<'row'<'col-sm-12'tr>>" +
	"<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
	processing: true,
	serverSide: true,
	pageLength: 500,
	"columnDefs": [
					{"className": "text-center", "targets": [0,2,3]},
					{ "targets": [0,2,3], "orderable": false }
				],
	ajax: {
	  url: "<?php echo base_url() ?>admin/reports/kondisi_pegawai/getjumlah",
	  type:'POST',
	  "data": function ( d ) {
			d.search['advanced_search_filters']=  $("#form_search_pegawai").serializeArray();
		}
	},
	"footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // converting to interger to find total
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // computing column Total of the complete result 
            var kol1 = api
                .column( 1 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        
            var kol2 = api
                  .column( 2 )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );
          
              var kol3 = api
                  .column( 3 )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );
          
              var kol4 = api
                  .column( 4 )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );
          
              var kol5 = api
                  .column( 5 )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );
              var kol6 = api
                  .column( 6 )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );
              var kol7 = api
                  .column( 7 )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );
              var kol8 = api
                  .column( 8 )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );
              var kol9 = api
                  .column( 9 )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );
              var kol10 = api
                  .column( 10 )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );
              var kol11 = api
                  .column( 11 )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );
              var kol12 = api.column( 12 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
              var kol13 = api.column( 13 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
              var kol14 = api.column( 14 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
              var kol15 = api.column( 15 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
              var kol16 = api.column( 16 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
              var kol17 = api.column( 17 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
              var kol18 = api.column( 18 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
              var kol19 = api.column( 19 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
          
              //$( api.column( 1 ).footer() ).html(kol1);

              $( api.column( 2 ).footer() ).html(formatNumber(kol2));
              $( api.column( 3 ).footer() ).html(formatNumber(kol3));
              $( api.column( 4 ).footer() ).html(formatNumber(kol4));
              $( api.column( 5 ).footer() ).html(formatNumber(kol5));
              $( api.column( 6 ).footer() ).html(formatNumber(kol6));
              $( api.column( 7 ).footer() ).html(formatNumber(kol7));
              $( api.column( 8 ).footer() ).html(formatNumber(kol8));
              $( api.column( 9 ).footer() ).html(formatNumber(kol9));
              $( api.column( 10 ).footer() ).html(formatNumber(kol10));
              $( api.column( 11 ).footer() ).html(formatNumber(kol11));
              $( api.column( 12 ).footer() ).html(formatNumber(kol12));
              $( api.column( 13 ).footer() ).html(formatNumber(kol13));
              $( api.column( 14 ).footer() ).html(formatNumber(kol13));
              $( api.column( 15 ).footer() ).html(formatNumber(kol13));
              $( api.column( 16 ).footer() ).html(formatNumber(kol13));
              $( api.column( 17 ).footer() ).html(formatNumber(kol13));
              $( api.column( 18 ).footer() ).html(formatNumber(kol13));
              $( api.column( 19 ).footer() ).html(formatNumber(kol13));
          },
});
function formatNumber(num) {
  return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}
function sinkronisasi_data(){
	var the_data = new FormData(document.getElementById("form_search_pegawai"));
	var json_url = "<?php echo base_url() ?>pegawai/sinkronisasi/synchjumlah";
	 $.ajax({    
	 	type: "POST",
		url: json_url,
		data: the_data,
        dataType: "json",
        processData: false, // tell jQuery not to process the data
        contentType: false, // tell jQuery not to set contentType
		success: function(data){ 
            if(data.success){
                swal("Pemberitahuan!", data.msg, "success");
				$('#btn_proses_sinkron').removeClass('disabled');
			    $('#btn_proses_sinkron').text('Sinkron data');
			    $('#btn_proses_sinkron').append(' <i class="fa fa-gear"></i>');
            }
            else {
                swal("Pemberitahuan!", data.msg, "error");
            }
		}});
	return false; 
}
$("#btn_cari").click(function(){
	$table.ajax.reload(null,true);
	return false; 
});	
$(".download_xls").click(function(){
	var xyz = $("#form_search_pegawai").serialize();
	window.open("<?php echo base_url('admin/reports/kondisi_pegawai/downloadjumlah');?>?"+xyz);
});
</script>