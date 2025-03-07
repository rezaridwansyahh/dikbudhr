
<script src="<?php echo base_url(); ?>assets/js/amcharts/amcharts.js" type="text/javascript" ></script>
<script src="<?php echo base_url(); ?>assets/js/amcharts/serial.js" type="text/javascript" ></script>  
<script src="<?php echo base_url(); ?>assets/js/amcharts/pie.js" type="text/javascript" ></script>  
<script src="<?php echo base_url(); ?>assets/js/amcharts/exporting/amexport.js" type="text/javascript" ></script>  
<script src="<?php echo base_url(); ?>assets/js/amcharts/exporting/canvg.js" type="text/javascript" ></script>  
<script src="<?php echo base_url(); ?>assets/js/amcharts/exporting/filesaver.js" type="text/javascript" ></script>  
<script src="<?php echo base_url(); ?>assets/js/amcharts/exporting/jspdf.js" type="text/javascript" ></script>  
<script src="<?php echo base_url(); ?>assets/js/amcharts/exporting/rgbcolor.js" type="text/javascript" ></script>  
<?php
	$this->load->library('convert');
	$convert = new convert();
	$mainmenu = $this->uri->segment(2);
	$menu = $this->uri->segment(3);
	$submenu = $this->uri->segment(4);
?>

<div class="row">
	
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Jumlah Pegawai</span>
              <span class="info-box-number"><?php echo isset($totalpegawai) ? number_format($totalpegawai,0,"",".") : ""; ?> <small></small></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-home"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Satker</span>
              <span class="info-box-number"><?php echo isset($jmlsatker) ? $jmlsatker : ""; ?> <small> Satker</small></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-4 col-sm-4 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-user"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Pensiun Tahun ini</span>
               <span class="info-box-number">
               <a href="<?php echo base_url(); ?>admin/kepegawaian/pegawai/listpensiun"><?php echo isset($jmlpensiun) ? number_format($jmlpensiun,0,"",".") : ""; ?></a>
               <small> Orang</small></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        
</div>
<?php if(!$this->auth->has_permission("Pegawai.Kepegawaian.Filtersatker") or $this->auth->has_permission("Pegawai.Kepegawaian.permissionEselon1")){ ?>
<div class="row " style="margin:10px 0px;">
	<div class="col-md-12x">
		<table class="filter_unit_kerja" sborder=0 width='100%' cellpadding="10">
				<tr>
					<td><label for="example-text-input" class="col-form-label">Unit Kerja</label></td>
				</tr>	
				<tr>
					<td colspan=2>
						<select id="unit_id_key" name="unit_id_key" width="100%" class=" col-md-10 format-control">
							<?php 
								if($selectedSatker){
									echo "<option value='$selectedSatker->ID' SELECTED>$selectedSatker->NAMA_UNOR_FULL</option>";
								}
							?>
						</select>
					</td>
				</tr>
		</table>		
	</div>
</div>
<?Php } ?>
<script type="text/javascript">
	$("#unit_id_key").select2({
		placeholder: 'Cari Unit Kerja...',
		width: '100%',
		minimumInputLength: 0,
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
	}).change(function(){
		if($(this).val()){
			window.location = "?unit_id="+$(this).val();
		}
		else window.location = "?";
	});
</script>	
<div class="row">
	<div class="col-md-7">
		<div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Grafik Berdasarkan Golongan</h3>

              <div class="box-tools pull-right">
								<div class="btn-group ">
									<button type="button" class="btn btn-warning">Silahkan Pilih</button>
									<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										<span class="caret"></span>
										<span class="sr-only">Toggle Dropdown</span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li><a href="?unit_id=<?php echo $this->input->get('unit_id');?>&action=download&data=golongan" target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download .xls</a></li>
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
                 		<div id="chartgolongan" style="width: 100%; height: 350px;"> </div>
              		</div>
          </div>
        <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Grafik Pendidikan</h3>

              
              <div class="box-tools pull-right">
								<div class="btn-group ">
									<button type="button" class="btn btn-warning">Silahkan Pilih</button>
									<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										<span class="caret"></span>
										<span class="sr-only">Toggle Dropdown</span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li><a href="?unit_id=<?php echo $this->input->get('unit_id');?>&action=download&data=pendidikan" target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download .xls</a></li>
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
				   <div id="chartpendidikan" style="width: 100%; height: 350px;"> </div>
			   </div>
          </div> 
		  <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Grafik Umur</h3>

             
              <div class="box-tools pull-right">
								<div class="btn-group ">
									<button type="button" class="btn btn-warning">Silahkan Pilih</button>
									<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										<span class="caret"></span>
										<span class="sr-only">Toggle Dropdown</span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li><a href="?unit_id=<?php echo $this->input->get('unit_id');?>&action=download&data=umur" target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download .xls</a></li>
									</ul>
								</div>	
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
								</button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
								
							</div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="chart-responsive">
                    <div id="divumur" style="width: 100%; height: 300px;"></div>
                  </div>
                  <!-- ./chart-responsive -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- /.box-body -->
            
            <!-- /.footer -->
          </div>
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Grafik Kategori Jabatan</h3>

             
              <div class="box-tools pull-right">
								<div class="btn-group ">
									<button type="button" class="btn btn-warning">Silahkan Pilih</button>
									<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										<span class="caret"></span>
										<span class="sr-only">Toggle Dropdown</span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li><a href="?unit_id=<?php echo $this->input->get('unit_id');?>&action=download&data=kategori_jabatan" target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download .xls</a></li>
										<li><a href="?unit_id=<?php echo $this->input->get('unit_id');?>&action=download&data=kategori_jabatan_eselon1" target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download detil pereselon 1 .xls</a></li>
									</ul>
								</div>	
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
								</button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
							</div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="chart-responsive">
                    <div id="divkategori_jabatan" style="width: 100%; height: 300px;"></div>
                  </div>
                  <!-- ./chart-responsive -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- /.box-body -->
            
            <!-- /.footer -->
          </div>
        
	</div>
	<div class="col-md-5">
					<div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Proyeksi Pensiun Pertahun</h3>

              
              <div class="box-tools pull-right">
								<div class="btn-group ">
									<button type="button" class="btn btn-warning">Silahkan Pilih</button>
									<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										<span class="caret"></span>
										<span class="sr-only">Toggle Dropdown</span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li><a href="?unit_id=<?php echo $this->input->get('unit_id');?>&action=download&data=proyeksi_pensiun" target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download .xls</a></li>
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
                 		<div id="chartpensiuntahun" style="width: 100%; height: 350px;"> </div>
              		</div>
          </div>
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Grafik Agama</h3>

              
              <div class="box-tools pull-right">
								<div class="btn-group ">
									<button type="button" class="btn btn-warning">Silahkan Pilih</button>
									<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										<span class="caret"></span>
										<span class="sr-only">Toggle Dropdown</span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li><a href="?unit_id=<?php echo $this->input->get('unit_id');?>&action=download&data=agama" target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download .xls</a></li>
									</ul>
								</div>	
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
								</button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
								
							</div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="chart-responsive">
                    <div id="pieChart" style="width: 100%; height: 400px;"></div>
                  </div>
                  <!-- ./chart-responsive -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-padding">
              <ul class="nav nav-pills nav-stacked">
              	<?php
				 if (isset($agamas) && is_array($agamas) && count($agamas)):
					 foreach($agamas as $rec):
					 if($rec->value > 0){
				?>
					<li><a href="#"><?=$rec->label;?>
 	               		<span class="pull-right text-red"><?=$rec->value?></span></a>
 	               	</li>
				<?php
					}
					 endforeach;
				 endif;
		
		
              	?>
              </ul>
            </div>
            <!-- /.footer -->
          </div>
          <!-- /.box -->
							
           <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Grafik Jenis Kelamin</h3>

              
              <div class="box-tools pull-right">
								<div class="btn-group ">
									<button type="button" class="btn btn-warning">Silahkan Pilih</button>
									<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										<span class="caret"></span>
										<span class="sr-only">Toggle Dropdown</span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li><a href="?unit_id=<?php echo $this->input->get('unit_id');?>&action=download&data=jenis_kelamin" target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download .xls</a></li>
									</ul>
								</div>	
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
								</button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
								
							</div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="chart-responsive">
                    <div id="divjeniskelamin" style="width: 100%; height: 300px;"></div>
                  </div>
                  <!-- ./chart-responsive -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- /.box-body -->
            
            <!-- /.footer -->
          </div>

          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Grafik Generasi</h3>

              
              <div class="box-tools pull-right">
								<div class="btn-group ">
									<button type="button" class="btn btn-warning">Silahkan Pilih</button>
									<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										<span class="caret"></span>
										<span class="sr-only">Toggle Dropdown</span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li><a href="?unit_id=<?php echo $this->input->get('unit_id');?>&action=download&data=generasi" target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download .xls</a></li>
									</ul>
								</div>	
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
								</button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
								
							</div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="chart-responsive">
                    <div id="divgenerasi" style="width: 100%; height: 500px;"></div>
                  </div>
                  <!-- ./chart-responsive -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- /.box-body -->
            
            <!-- /.footer -->
          </div>
          
         
    </div>
</div>

<script type="text/javascript">  
var colors=    [ '#FCD202', '#B0DE09','#FF6600', '#0D8ECF', '#2A0CD0', '#CD0D74', '#CC0000', '#00CC00', '#0000CC', '#DDDDDD', '#999999', '#333333', '#990000']

var inputpendidikan = <?php echo $jsonpendidikan; ?>;
//inject color attribute with value
for (i = 0; i < inputpendidikan.length; i++) {inputpendidikan[i].color = colors[i];}	
	var chart = AmCharts.makeChart("chartpendidikan", {
				  "type": "serial",
				  "dataProvider":  inputpendidikan,
				   "theme": "light",
				  "categoryField": "NAMA",
				  "rotate": false,
				  "startDuration": 0,
				  "depth3D": 0,
				  "colorField" : "#ffd900",
				  "angle": 30,
				  "chartCursor": {
					  "categoryBalloonEnabled": false,
					  "cursorAlpha": 0,
					  "zoomable": false,
				  },    
				   "categoryAxis": {
					 "gridPosition": "start",
					 "labelRotation": 45,
					 "axisAlpha": 0,
					 "autoWrap":false,
					 minHorizontalGap:0,
				   },
				   
					"titles" : [{
						  "text": "NAMA"
					  }, {
						  "text": "",
						  "bold": false
					  }],
				  "trendLines": [],
				  "graphs": [
					  {
						  "balloonText": "<b>[[category]]: [[value]]</b>",
						  "fillAlphas": 0.8,
						  "id": "AmGraph-1",
						  "lineAlpha": 0.2,
						  "title": "Pendidikan",
						  "type": "column",
						  "colorField": "color",
						  "valueField": "jumlah",
              "labelText": "[[value]]",
              "labelPosition": "top",
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
				   },
           "exportConfig": {
                "menuTop": "20px",
                "menuBottom": "auto",
                "menuLeft": "23px",
                "backgroundColor": "#efefef",

               "menuItemStyle"  : {
                "backgroundColor"           : '#EFEFEF',
                "rollOverBackgroundColor"   : '#DDDDDD'},

                "menuItems": [{
                    "textAlign": 'center',
                    "icon": 'http://www.amcharts.com/lib/3/images/export.png',
                    "onclick":function(){},
                    "items": [{
                        "title": 'JPG',
                        "format": 'jpg'
                    }, {
                        "title": 'PNG',
                        "format": 'png'
                    }]
                }]
            }

	});
	
	var chartPensiunTahun = AmCharts.makeChart("chartpensiuntahun", {
				  "type": "serial",
				  "dataProvider":   <?php echo $jsonpensiuntahun; ?>,
				   "theme": "light",
				  "categoryField": "tahun",
				  "rotate": false,
				  "startDuration": 0,
				  "depth3D": 2,
				  "angle": 30,
				  labelsEnabled: true,
				  "chartCursor": {
					  "categoryBalloonEnabled": false,
					  "cursorAlpha": 0,
					  "zoomable": false,
				  },    
				   "categoryAxis": {
					 "gridPosition": "start",
					 "labelRotation": 45,
					 "axisAlpha": 0,
					 "autoWrap":false,
					 minHorizontalGap:0,
				   },
				   
					"titles" : [{
						  "text": "tahun"
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
						  "valueField": "jumlah",
              "labelText": "[[value]]",
              "labelPosition": "top",
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
				   },
           "exportConfig": {
                "menuTop": "20px",
                "menuBottom": "auto",
                "menuRight": "50px",
                "backgroundColor": "#efefef",

               "menuItemStyle"  : {
                "backgroundColor"           : '#EFEFEF',
                "rollOverBackgroundColor"   : '#DDDDDD'},

                "menuItems": [{
                    "textAlign": 'center',
                    "icon": 'http://www.amcharts.com/lib/3/images/export.png',
                    "onclick":function(){},
                    "items": [{
                        "title": 'JPG',
                        "format": 'jpg'
                    }, {
                        "title": 'PNG',
                        "format": 'png'
                    }]
                }]
            }


	});
	AmCharts.makeChart("pieChart", {
     "type": "pie",
     "theme": "light",
     "dataProvider":  <?php echo json_encode($agamas); ?>,
     "titleField": "label",
     "valueField": "value",
     "alignLabels":false,
     "innerRadius": "40%",
     "labelsEnabled": true,
     "labelText": "[[title]]:[[value]]",
     "ticks": {
        "disabled": true
      },
     "export": {
       "enabled": true
     },
     
     "export": {
            "enabled": true
           },
       "exportConfig": {
            "menuTop": "20px",
            "menuBottom": "auto",
            "menuRight": "0px",
            "backgroundColor": "#efefef",

           "menuItemStyle"  : {
            "backgroundColor"           : '#EFEFEF',
            "rollOverBackgroundColor"   : '#DDDDDD'},

            "menuItems": [{
                "textAlign": 'center',
                "icon": 'http://www.amcharts.com/lib/3/images/export.png',
                "onclick":function(){},
                "items": [{
                    "title": 'JPG',
                    "format": 'jpg'
                }, {
                    "title": 'PNG',
                    "format": 'png'
                }]
            }]
        },
     "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
     // "legend": {
     //   "align": "center",
     //   "markerType": "circle"
     // }

   });
    
			  
	 AmCharts.makeChart("divjeniskelamin", {
		 "type": "pie",
		 "theme": "light",
		 "dataProvider":  <?php echo $jsonjk; ?>,
		 "titleField": "Jenis_Kelamin",
		 "valueField": "jumlah",
		 "labelsEnabled": true,
     "labelText": "[[title]]:[[value]]",
		"labels": {
      "maxWidth": 130,
      "wrap": true
    },
     "export": {
            "enabled": true
           },
       "exportConfig": {
            "menuTop": "20px",
            "menuBottom": "auto",
            "menuRight": "0px",
            "backgroundColor": "#efefef",

           "menuItemStyle"  : {
            "backgroundColor"           : '#EFEFEF',
            "rollOverBackgroundColor"   : '#DDDDDD'},

            "menuItems": [{
                "textAlign": 'center',
                "icon": 'http://www.amcharts.com/lib/3/images/export.png',
                "onclick":function(){},
                "items": [{
                    "title": 'JPG',
                    "format": 'jpg'
                }, {
                    "title": 'PNG',
                    "format": 'png'
                }]
            }]
        },
		 "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
		 // "legend": {
			//  "align": "center",
			//  "markerType": "circle"
		 // }

	 });
	 // generasi
	 AmCharts.makeChart("divgenerasi", {
		 "type": "pie",
		 "theme": "light",
		 "dataProvider":  <?php echo $json_generasi; ?>,
		 "titleField": "generasi",
		 "valueField": "jumlah",
		 "colorField": "color",
		 "labelsEnabled": false,
     "labelText": "[[title]]:[[value]]",
		"labels": {
      "maxWidth": 130,
      "wrap": true
    },
     "export": {
            "enabled": true
           },
       "exportConfig": {
            "menuTop": "20px",
            "menuBottom": "auto",
            "menuRight": "0px",
            "backgroundColor": "#efefef",

           "menuItemStyle"  : {
            "backgroundColor"           : '#EFEFEF',
            "rollOverBackgroundColor"   : '#DDDDDD'},

            "menuItems": [{
                "textAlign": 'center',
                "icon": 'http://www.amcharts.com/lib/3/images/export.png',
                "onclick":function(){},
                "items": [{
                    "title": 'JPG',
                    "format": 'jpg'
                }, {
                    "title": 'PNG',
                    "format": 'png'
                }]
            }]
        },
		 "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
		 "legend": {
			 "align": "center",
			 "maxWidth":300
		 }

	 });
	 
	 var chartDivUmur = AmCharts.makeChart("divumur", {
				  "type": "serial",
				  "dataProvider":   <?php echo $jsonumur; ?>,
				   "theme": "light",
				  "categoryField": "label",
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
					 "labelRotation": 45,
					 "axisAlpha": 0,
					 "autoWrap":false,
					 minHorizontalGap:0,
				   },
				   
					"titles" : [{
						  "text": "label"
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
						  "valueField": "jumlah",
              "labelText": "[[value]]",
              "labelPosition": "top",
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
				   },
           "exportConfig": {
                "menuTop": "20px",
                "menuBottom": "auto",
                "menuLeft": "23px",
                "backgroundColor": "#efefef",

               "menuItemStyle"  : {
                "backgroundColor"           : '#EFEFEF',
                "rollOverBackgroundColor"   : '#DDDDDD'},

                "menuItems": [{
                    "textAlign": 'center',
                    "icon": 'http://www.amcharts.com/lib/3/images/export.png',
                    "onclick":function(){},
                    "items": [{
                        "title": 'JPG',
                        "format": 'jpg'
                    }, {
                        "title": 'PNG',
                        "format": 'png'
                    }]
                }]
            }

	});
	
	 var chartRekapGolongan =  AmCharts.makeChart("chartgolongan", {
				  "type": "serial",
				  "dataProvider":   <?php echo $data_jumlah_pegawai_per_golongan; ?>,
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
					 "labelRotation": 45,
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
						  "valueField": "total",
              "labelText": "[[value]]",
              "labelPosition": "top",
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
				   },
           "exportConfig": {
                "menuTop": "20px",
                "menuBottom": "auto",
                "menuLeft": "23px",
                "backgroundColor": "#efefef",

               "menuItemStyle"  : {
                "backgroundColor"           : '#EFEFEF',
                "rollOverBackgroundColor"   : '#DDDDDD'},

                "menuItems": [{
                    "textAlign": 'center',
                    "icon": 'http://www.amcharts.com/lib/3/images/export.png',
                    "onclick":function(){},
                    "items": [{
                        "title": 'JPG',
                        "format": 'jpg'
                    }, {
                        "title": 'PNG',
                        "format": 'png'
                    }]
                }]
            }

	});
	 var input_kategorijabatan = <?php echo $json_kategori_jabatan; ?>;
for (i = 0; i < input_kategorijabatan.length; i++) {input_kategorijabatan[i].color = colors[i];}	
	 var chart_kategori_jabatan =  AmCharts.makeChart("divkategori_jabatan", {
				  "type": "serial",
				  "dataProvider":   input_kategorijabatan,
				   "theme": "light",
				  "categoryField": "KATEGORI_JABATAN",
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
					 "labelRotation": 45,
					 "axisAlpha": 0,
					 "autoWrap":false,
					 minHorizontalGap:0,
				   },
				   
					"titles" : [{
						  "text": "KATEGORI_JABATAN"
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
						  "valueField": "jumlah",
              "labelText": "[[value]]",
              "labelPosition": "top",
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
            "enabled": true,
          }
          ,"exportConfig": {
                "menuTop": "20px",
                "menuBottom": "auto",
                "menuLeft": "23px",
                "backgroundColor": "#efefef",
               "menuItemStyle"  : {
                "backgroundColor"           : '#EFEFEF',
                "rollOverBackgroundColor"   : '#DDDDDD'},

                "menuItems": [{
                    "textAlign": 'center',
                    
                    "icon": 'http://www.amcharts.com/lib/3/images/export.png',
                    "onclick":function(){},
                    "items": [{
                        "title": 'JPG',
                        "format": 'jpg'
                    }, {
                        "title": 'PNG',
                        "format": 'png'
                    }]
                }]
            }

	});
</script>
