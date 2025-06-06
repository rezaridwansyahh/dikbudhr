<select id="filter_eselon" class="select2 form-control padding" >
	<?php 
		foreach($list_es1 as $row){
			$selected = '';
			if($row->ID==$selectedEselon){
				$selected = ' SELECTED ';
			}
			echo "<option $selected value='$row->ID'>$row->nama_unor</option>";
		}
	?>
</select>
<div class="row" style="margin-top:20px">
	<div class="col-md-6">
		<div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Total Pegawai</h3>

              <div class="box-tools pull-right">
								<div class="btn-group hide">
									<button type="button" class="btn btn-warning">Silahkan Pilih</button>
									<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										<span class="caret"></span>
										<span class="sr-only">Toggle Dropdown</span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li><a href="#" target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download .xls</a></li>
									</ul>
								</div>	
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
								</button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
								
							</div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">	
                 	<div id="container" ></div>
                 		<div id="chart_total_pegawai" style="width: 100%; height: 350px;"> </div>
              		</div>
          </div>
	</div>	  	
	<div class="col-md-6">
		<div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Pendidikan </h3>

              <div class="box-tools pull-right">
								<div class="btn-group hide">
									<button type="button" class="btn btn-warning">Silahkan Pilih</button>
									<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										<span class="caret"></span>
										<span class="sr-only">Toggle Dropdown</span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li><a href="#" target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download .xls</a></li>
									</ul>
								</div>	
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
								</button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
								
							</div>
            </div>
            <!-- /.box-header -->
            <div class="box-body ">	
						<select id="filter_tk_pendidikan" class="select2 form-control" >
							<?php 
								foreach($list_tk as $row){
									echo "<option value='{$row->ID}'>{$row->NAMA}</option>";
								}
							?>
						</select>
                 		<div id="chart_pendidikan" style="width: 100%; height: 350px;"> </div>
			</div>
          </div>
	</div>	 
</div>	
<div class="row">
	<div class="col-md-6">
		<div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Usia </h3>

              <div class="box-tools pull-right">
								<div class="btn-group hide">
									<button type="button" class="btn btn-warning">Silahkan Pilih</button>
									<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										<span class="caret"></span>
										<span class="sr-only">Toggle Dropdown</span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li><a href="#" target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download .xls</a></li>
									</ul>
								</div>	
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
								</button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
								
							</div>
            </div>
            <!-- /.box-header -->
            <div class="box-body ">	
						<select id="filter_usia" class="select2 form-control" >
							<?php 
								foreach($list_usia as $row){
									echo "<option value='".$row['ID']."'>".$row['NAMA']."</option>";
								}
							?>
						</select>
                 		<div id="chart_usia" style="width: 100%; height: 350px;"> </div>
			</div>
          </div>
	</div>	
	<div class="col-md-6">
		<div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Tipe Jabatan </h3>

              <div class="box-tools pull-right">
								<div class="btn-group hide">
									<button type="button" class="btn btn-warning">Silahkan Pilih</button>
									<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										<span class="caret"></span>
										<span class="sr-only">Toggle Dropdown</span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li><a href="#" target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download .xls</a></li>
									</ul>
								</div>	
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
								</button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
								
							</div>
            </div>
            <!-- /.box-header -->
            <div class="box-body ">	
						<select id="filter_tipe_jabatan" class="select2 form-control" >
							<?php 
								foreach($list_jf_type as $row){
									echo "<option value='".$row['ID']."'>".$row['NAMA']."</option>";
								}
							?>
						</select>
                 		<div id="chart_tipe_jabatan" style="width: 100%; height: 350px;"> </div>
			</div>
          </div>
	</div>	
</div>
<script src="<?php echo base_url(); ?>assets/js/amcharts/amcharts.js" type="text/javascript" ></script>
<script src="<?php echo base_url(); ?>assets/js/amcharts/serial.js" type="text/javascript" ></script>  
<script src="<?php echo base_url(); ?>assets/js/amcharts/pie.js" type="text/javascript" ></script>  
<script type="text/javascript" src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<script>
	var chart_total_pegawai =  AmCharts.makeChart("chart_total_pegawai", {
				  "type": "serial",
				  "dataProvider":  []
				  ,
				   "theme": "light",
				  "categoryField": "nama",
				  "rotate": false,
				  "startDuration": 0,
				  "depth3D": 2,
				  "angle": 30,
				  
				  "chartCursor": {
					  "categoryBalloonEnabled": false,
					  "cursorAlpha": 0,
					  "zoomable": false,
				  },    
				   "categoryAxis": {
					 "gridPosition": "start",
					 "labelRotation": 90,
					 "axisAlpha": 0,
					 "autoWrap":true,
					 "labelFunction": function(label, item, axis) {
						 
						  var chart = axis.chart;
						  if ( (chart.realWidth <= 300 ) && ( label.length > 5 ) )
							return label.substr(0, 5) + '...';
						  if ( (chart.realWidth <= 500 ) && ( label.length > 10 ) )
							return label.substr(0, 10) + '...';
						  return label;
						},
					 minHorizontalGap:0,
				   },
				   
					"titles" : [{
						  "text": "nama"
					  }, {
						  "text": "",
						  "bold": false
					  }],
				  "trendLines": [],
				  "graphs": [
					  {
						  "balloonText": "<b>[[category]]: [[value]]</b>",
						  "colorField": "color",
						  "fillAlphas": 0.8,
						  "lineAlpha": 0.2,
						  "type": "column",
						  "valueField": "total"
					  } 
				  ],
				  "guides": [],
				  "valueAxes": [
					  {
						  "position": "top",
						  "axisAlpha": 0
					  }
				  ],
				  "allLabels": [],
				  "balloon": {},
				  "titles": [],
				  
				  "export": {
					  "enabled": true
				   }

	});
	var chart_pendidikan =  AmCharts.makeChart("chart_pendidikan", {
				  "type": "serial",
				  "dataProvider":[],
				   "theme": "light",
				  "categoryField": "nama",
				  "rotate": false,
				  "startDuration": 0,
				  "depth3D": 2,
				  "angle": 30,
				  
				  "chartCursor": {
					  "categoryBalloonEnabled": false,
					  "cursorAlpha": 0,
					  "zoomable": false,
				  },    
				   "categoryAxis": {
					 "gridPosition": "start",
					 "labelRotation": 90,
					 "axisAlpha": 0,
					 "autoWrap":false,
					 minHorizontalGap:0,
				   },
				   
					"titles" : [{
						  "text": "nama"
					  }, {
						  "text": "",
						  "bold": false
					  }],
				  "trendLines": [],
				  "graphs": [
					  {
						  "balloonText": "<b>[[category]]: [[value]]</b>",
						  "colorField": "color",
						  "fillAlphas": 0.8,
						  "id": "AmGraph-1",
						  "lineAlpha": 0.2,
						  "type": "column",
						  "valueField": "total"
					  } 
				  ],
				  "guides": [],
				  "valueAxes": [
					  {
						  "id": "ValueAxis-1",
						  "position": "top",
						  "axisAlpha": 0
					  }
				  ],
				  "allLabels": [],
				  "balloon": {},
				  "titles": [],
				  
				  "export": {
					  "enabled": true
				   }

	});
	var chart_usia =  AmCharts.makeChart("chart_usia", {
				  "type": "serial",
				  "dataProvider":[],
				   "theme": "light",
				  "categoryField": "nama",
				  "rotate": false,
				  "startDuration": 0,
				  "depth3D": 2,
				  "angle": 30,
				  
				  "chartCursor": {
					  "categoryBalloonEnabled": false,
					  "cursorAlpha": 0,
					  "zoomable": false,
				  },    
				   "categoryAxis": {
					 "gridPosition": "start",
					 "labelRotation": 90,
					 "axisAlpha": 0,
					 "autoWrap":false,
					 minHorizontalGap:0,
				   },
				   
					"titles" : [{
						  "text": "nama"
					  }, {
						  "text": "",
						  "bold": false
					  }],
				  "trendLines": [],
				  "graphs": [
					  {
						  "balloonText": "<b>[[category]]: [[value]]</b>",
						  "colorField": "color",
						  "fillAlphas": 0.8,
						  "id": "AmGraph-1",
						  "lineAlpha": 0.2,
						  "type": "column",
						  "valueField": "total"
					  } 
				  ],
				  "guides": [],
				  "valueAxes": [
					  {
						  "id": "ValueAxis-1",
						  "position": "top",
						  "axisAlpha": 0
					  }
				  ],
				  "allLabels": [],
				  "balloon": {},
				  "titles": [],
				  
				  "export": {
					  "enabled": true
				   }

	});
	var chart_tipe_jabatan =  AmCharts.makeChart("chart_tipe_jabatan", {
				  "type": "serial",
				  "dataProvider":[],
				   "theme": "light",
				  "categoryField": "nama",
				  "rotate": false,
				  "startDuration": 0,
				  "depth3D": 2,
				  "angle": 30,
				  
				  "chartCursor": {
					  "categoryBalloonEnabled": false,
					  "cursorAlpha": 0,
					  "zoomable": false,
				  },    
				   "categoryAxis": {
					 "gridPosition": "start",
					 "labelRotation": 90,
					 "axisAlpha": 0,
					 "autoWrap":false,
					 minHorizontalGap:0,
				   },
				   
					"titles" : [{
						  "text": "nama"
					  }, {
						  "text": "",
						  "bold": false
					  }],
				  "trendLines": [],
				  "graphs": [
					  {
						  "balloonText": "<b>[[category]]: [[value]]</b>",
						  "colorField": "color",
						  "fillAlphas": 0.8,
						  "id": "AmGraph-1",
						  "lineAlpha": 0.2,
						  "type": "column",
						  "valueField": "total"
					  } 
				  ],
				  "guides": [],
				  "valueAxes": [
					  {
						  "id": "ValueAxis-1",
						  "position": "top",
						  "axisAlpha": 0
					  }
				  ],
				  "allLabels": [],
				  "balloon": {},
				  "titles": [],
				  
				  "export": {
					  "enabled": true
				   }

	});
</script>

<script>
	var BASE_URL = '<?php echo base_url();?>';
    var loadChartTotalPegawai = function(){
		$.post(BASE_URL+'rekap/eis_per_eselon/ajax_group_by_eselon_total_pegawai',{
			eselon_id : '<?php echo $selectedEselon;?>'
		},function(o){
			chart_total_pegawai.dataProvider = o;
			chart_total_pegawai.validateData();
		},'json');
	}
	var loadChartPendidikanByTK= function(tk_id){
		$.post(BASE_URL+'rekap/eis_per_eselon/ajax_group_by_tk_pendidikan/'+tk_id,{
			eselon_id : '<?php echo $selectedEselon;?>'
		},function(o){
			chart_pendidikan.dataProvider = o;
			chart_pendidikan.validateData();
		},'json');
	}
	var loadChartByUsia= function(usia_id){
		$.post(BASE_URL+'rekap/eis_per_eselon/ajax_group_by_usia/'+usia_id,{
			eselon_id : '<?php echo $selectedEselon;?>'
		},function(o){
			chart_usia.dataProvider = o;
			chart_usia.validateData();
		},'json');
	}
	var loadChartTipeJabatan= function(tipe_jabatan){
		$.post(BASE_URL+'rekap/eis_per_eselon/ajax_group_jabatan/'+tipe_jabatan,{
			eselon_id : '<?php echo $selectedEselon;?>'
		},function(o){
			chart_tipe_jabatan.dataProvider = o;
			chart_tipe_jabatan.validateData();
		},'json');
	}
	var setup_init = function(){
		loadChartTotalPegawai();
		loadChartPendidikanByTK($("#filter_tk_pendidikan").val());
		loadChartByUsia($("#filter_usia").val());
		loadChartTipeJabatan($("#filter_tipe_jabatan").val());
		
	}
	$(document).ready(function(){
		$("#filter_eselon").select2();	
		$("#filter_eselon").change(function(){
			window.location = $(this).val();
		});
		$("#filter_tk_pendidikan").select2();
		$("#filter_usia").select2();
		$("#filter_tipe_jabatan").select2();
		$("#filter_tk_pendidikan").change(function(){
			loadChartPendidikanByTK($(this).val());
		});
		$("#filter_usia").change(function(){
			loadChartByUsia($(this).val());
		});
		$("#filter_tipe_jabatan").change(function(){
			loadChartTipeJabatan($(this).val());
		});
		setup_init();
	});
</script>